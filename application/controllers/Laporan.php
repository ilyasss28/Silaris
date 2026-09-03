<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Laporan Controller
*| --------------------------------------------------------------------------
*| Laporan site
*|
*/
class Laporan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_laporan');
	}

	/**
	* show all Laporans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('laporan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['laporans'] = $this->model_laporan->get($filter, $field, $this->limit_page, $offset);
		$this->data['laporan_counts'] = $this->model_laporan->count_all($filter, $field);

		$config = [
			'base_url'     => 'laporan/index/',
			'total_rows'   => $this->model_laporan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Laporan List');
		$this->render('modul/laporan/laporan_list', $this->data);
	}
	
	/**
	* Add new laporans
	*
	*/
	public function add()
	{
		$this->is_allowed('laporan_add');

		$this->template->title('Laporan New');
		$this->render('modul/laporan/laporan_add', $this->data);
	}

	/**
	* Add New Laporans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('laporan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('laporan_Laporan_name', 'Laporan', 'trim|required');
		

		if ($this->form_validation->run()) {
			$laporan_Laporan_uuid = $this->input->post('laporan_Laporan_uuid');
			$laporan_Laporan_name = $this->input->post('laporan_Laporan_name');
		
			$save_data = [
				'nama_notaris' => get_user_data('full_name'),
				'username' => get_user_data('username'),
					'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];
			if ($this->db->field_exists('owner_user_id', 'laporan')) {
				$save_data['owner_user_id'] = (int) get_user_data('id');
			}

			if (!is_dir(FCPATH . '/uploads/laporan/')) {
				mkdir(FCPATH . '/uploads/laporan/');
			}

			if (!empty($laporan_Laporan_name)) {
				$laporan_Laporan_name_copy = date('YmdHis') . '-' . $laporan_Laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $laporan_Laporan_uuid . '/' . $laporan_Laporan_name, 
						FCPATH . 'uploads/laporan/' . $laporan_Laporan_name_copy);

				if (!is_file(FCPATH . '/uploads/laporan/' . $laporan_Laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $laporan_Laporan_name_copy;
			}
		
			
			$save_laporan = $this->model_laporan->store($save_data);

			if ($save_laporan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_laporan;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('laporan/edit/' . $save_laporan, 'Edit Laporan'),
						anchor('laporan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('laporan/edit/' . $save_laporan, 'Edit Laporan')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('laporan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('laporan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Laporans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('laporan_update');

		$this->data['laporan'] = $this->model_laporan->find($id);
		if (!$this->data['laporan']) {
			show_404();
		}

		$this->template->title('Laporan Update');
		$this->render('modul/laporan/laporan_update', $this->data);
	}

	/**
	* Update Laporans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if (!$this->model_laporan->find($id)) {
			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Laporan tidak ditemukan atau bukan milik akun Anda.',
				]));
		}
		
		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('laporan_Laporan_name', 'Laporan', 'trim|required');
		
		if ($this->form_validation->run()) {
			$laporan_Laporan_uuid = $this->input->post('laporan_Laporan_uuid');
			$laporan_Laporan_name = $this->input->post('laporan_Laporan_name');
		
			// Ownership is immutable during an edit. This prevents an Admin,
			// Kanwil, or MPD account from accidentally taking over a
			// notary's report merely by correcting its date or document.
			$save_data = [
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];

			if (!is_dir(FCPATH . '/uploads/laporan/')) {
				mkdir(FCPATH . '/uploads/laporan/');
			}

			if (!empty($laporan_Laporan_uuid)) {
				$laporan_Laporan_name_copy = date('YmdHis') . '-' . $laporan_Laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $laporan_Laporan_uuid . '/' . $laporan_Laporan_name, 
						FCPATH . 'uploads/laporan/' . $laporan_Laporan_name_copy);

				if (!is_file(FCPATH . '/uploads/laporan/' . $laporan_Laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $laporan_Laporan_name_copy;
			}
		
			
			$save_laporan = $this->model_laporan->change($id, $save_data);

			if ($save_laporan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('laporan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('laporan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('laporan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Laporans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('laporan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (is_array($arr_id) && count($arr_id) > 0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'laporan'), 'success');
        } else {
            set_message(cclang('error_delete', 'laporan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Laporans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('laporan_view');

		$this->data['laporan'] = $this->model_laporan->join_avaiable()->filter_avaiable()->find($id);
		if (!$this->data['laporan']) {
			show_404();
		}

		$this->template->title('Laporan Detail');
		$this->render('modul/laporan/laporan_view', $this->data);
	}
	
	/**
	* delete Laporans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$laporan = $this->model_laporan->find($id);

		if ($laporan && !empty($laporan->Laporan)) {
			$path = FCPATH . '/uploads/laporan/' . $laporan->Laporan;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $laporan ? $this->model_laporan->remove($id) : false;
	}
	
	/**
	* Upload Image Laporan	* 
	* @return JSON
	*/
	public function upload_Laporan_file()
	{
		if (!$this->is_allowed('laporan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'laporan',
			'allowed_types' => 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png',
			'max_size' 	 	=> 10000,
		]);
	}

	/**
	* Delete Image Laporan	* 
	* @return JSON
	*/
	public function delete_Laporan_file($uuid)
	{
		if (!$this->is_allowed('laporan_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if ($this->input->get('by') === 'id' && (!$this->model_laporan->find($uuid))) {
			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'error' => 'Laporan tidak ditemukan atau bukan milik akun Anda.',
				]));
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'Laporan', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'laporan',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/laporan/'
        ]);
	}

	/**
	* Get Image Laporan	* 
	* @return JSON
	*/
	public function get_Laporan_file($id)
	{
		if (!$this->is_allowed('laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$laporan = $this->model_laporan->find($id);
		if (!$laporan) {
			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Laporan tidak ditemukan atau bukan milik akun Anda.',
				]));
		}

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'Laporan', 
            'table_name'        => 'laporan',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/laporan/',
            'delete_endpoint'   => 'laporan/delete_Laporan_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('laporan_export');

		$this->model_laporan->export_scoped('laporan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('laporan_export');

		$this->model_laporan->pdf_scoped('Laporan');
	}
}


/* End of file laporan.php */
/* Location: ./application/controllers/Laporan.php */
