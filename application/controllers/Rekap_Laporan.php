<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Laporan Controller
*| --------------------------------------------------------------------------
*| Rekap Laporan site
*|
*/
class Rekap_Laporan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_Laporan');
	}

	/**
	* show all Rekap Laporans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_Laporan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_Laporans'] = $this->model_rekap_Laporan->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_Laporan_counts'] = $this->model_rekap_Laporan->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_Laporan/index/',
			'total_rows'   => $this->model_rekap_Laporan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Laporan List');
		$this->render('modul/rekap_Laporan/rekap_Laporan_list', $this->data);
	}
	
	/**
	* Add new rekap_Laporans
	*
	*/
	public function add()
	{
		$this->is_allowed('rekap_Laporan_add');

		$this->template->title('Rekap Laporan New');
		$this->render('modul/rekap_Laporan/rekap_Laporan_add', $this->data);
	}

	/**
	* Add New Rekap Laporans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('rekap_Laporan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('rekap_Laporan_Laporan_name', 'Laporan', 'trim|required|max_length[1000]');
		

		if ($this->form_validation->run()) {
			$rekap_Laporan_Laporan_uuid = $this->input->post('rekap_Laporan_Laporan_uuid');
			$rekap_Laporan_Laporan_name = $this->input->post('rekap_Laporan_Laporan_name');
		
			$save_data = [
				'username' => get_user_data('username'),
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];

			if (!is_dir(FCPATH . '/uploads/rekap_Laporan/')) {
				mkdir(FCPATH . '/uploads/rekap_Laporan/');
			}

			if (!empty($rekap_Laporan_Laporan_name)) {
				$rekap_Laporan_Laporan_name_copy = date('YmdHis') . '-' . $rekap_Laporan_Laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $rekap_Laporan_Laporan_uuid . '/' . $rekap_Laporan_Laporan_name, 
						FCPATH . 'uploads/rekap_Laporan/' . $rekap_Laporan_Laporan_name_copy);

				if (!is_file(FCPATH . '/uploads/rekap_Laporan/' . $rekap_Laporan_Laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $rekap_Laporan_Laporan_name_copy;
			}
		
			
			$save_rekap_Laporan = $this->model_rekap_Laporan->store($save_data);

			if ($save_rekap_Laporan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_rekap_Laporan;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('rekap_Laporan/edit/' . $save_rekap_Laporan, 'Edit Rekap Laporan'),
						anchor('rekap_Laporan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('rekap_Laporan/edit/' . $save_rekap_Laporan, 'Edit Rekap Laporan')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Rekap Laporans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('rekap_Laporan_update');

		$this->data['rekap_Laporan'] = $this->model_rekap_Laporan->find($id);

		$this->template->title('Rekap Laporan Update');
		$this->render('modul/rekap_Laporan/rekap_Laporan_update', $this->data);
	}

	/**
	* Update Rekap Laporans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('rekap_Laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('rekap_Laporan_Laporan_name', 'Laporan', 'trim|required|max_length[1000]');
		
		if ($this->form_validation->run()) {
			$rekap_Laporan_Laporan_uuid = $this->input->post('rekap_Laporan_Laporan_uuid');
			$rekap_Laporan_Laporan_name = $this->input->post('rekap_Laporan_Laporan_name');
		
			$save_data = [
				'username' => get_user_data('username'),
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];

			if (!is_dir(FCPATH . '/uploads/rekap_Laporan/')) {
				mkdir(FCPATH . '/uploads/rekap_Laporan/');
			}

			if (!empty($rekap_Laporan_Laporan_uuid)) {
				$rekap_Laporan_Laporan_name_copy = date('YmdHis') . '-' . $rekap_Laporan_Laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $rekap_Laporan_Laporan_uuid . '/' . $rekap_Laporan_Laporan_name, 
						FCPATH . 'uploads/rekap_Laporan/' . $rekap_Laporan_Laporan_name_copy);

				if (!is_file(FCPATH . '/uploads/rekap_Laporan/' . $rekap_Laporan_Laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $rekap_Laporan_Laporan_name_copy;
			}
		
			
			$save_rekap_Laporan = $this->model_rekap_Laporan->change($id, $save_data);

			if ($save_rekap_Laporan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('rekap_Laporan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Rekap Laporans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_Laporan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'rekap_Laporan'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_Laporan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Laporans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_Laporan_view');

		$this->data['rekap_Laporan'] = $this->model_rekap_Laporan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Laporan Detail');
		$this->render('modul/rekap_Laporan/rekap_Laporan_view', $this->data);
	}
	
	/**
	* delete Rekap Laporans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_Laporan = $this->model_rekap_Laporan->find($id);

		if (!empty($rekap_Laporan->Laporan)) {
			$path = FCPATH . '/uploads/rekap_Laporan/' . $rekap_Laporan->Laporan;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_rekap_Laporan->remove($id);
	}
	
	/**
	* Upload Image Rekap Laporan	* 
	* @return JSON
	*/
	public function upload_Laporan_file()
	{
		if (!$this->is_allowed('rekap_Laporan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'rekap_Laporan',
		]);
	}

	/**
	* Delete Image Rekap Laporan	* 
	* @return JSON
	*/
	public function delete_Laporan_file($uuid)
	{
		if (!$this->is_allowed('rekap_Laporan_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'Laporan', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'rekap_Laporan',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/rekap_Laporan/'
        ]);
	}

	/**
	* Get Image Rekap Laporan	* 
	* @return JSON
	*/
	public function get_Laporan_file($id)
	{
		if (!$this->is_allowed('rekap_Laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$rekap_Laporan = $this->model_rekap_Laporan->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'Laporan', 
            'table_name'        => 'rekap_Laporan',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/rekap_Laporan/',
            'delete_endpoint'   => 'rekap_Laporan/delete_Laporan_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_Laporan_export');

		$this->model_rekap_Laporan->export('rekap_Laporan', 'rekap_Laporan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_Laporan_export');

		$this->model_rekap_Laporan->pdf('rekap_Laporan', 'rekap_Laporan');
	}
}


/* End of file rekap_Laporan.php */
/* Location: ./application/controllers/Rekap Laporan.php */