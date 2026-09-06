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
		$this->load->library('storage_manager');
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
		$this->require_complete_notary_profile();

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
		if (!$this->require_complete_notary_profile(true)) return;

		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('laporan_Laporan_name', 'Laporan', 'trim|required|max_length[255]|callback_valid_report_document');
		

		if ($this->form_validation->run()) {
			$laporan_Laporan_uuid = basename((string) $this->input->post('laporan_Laporan_uuid', true));
			$laporan_Laporan_name = basename((string) $this->input->post('laporan_Laporan_name', true));
		
			$save_data = [
				'nama_notaris' => format_person_name(get_user_data('full_name')),
				'username' => get_user_data('username'),
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
				'owner_user_id' => (int) get_user_data('id'),
			];

			$new_document = null;
			if (!empty($laporan_Laporan_name)) {
				$new_document = $this->storage_manager->move_from_temp($laporan_Laporan_uuid, $laporan_Laporan_name, 'uploads/laporan/');
				if (!$new_document) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $new_document;
			}
		
			
			$save_laporan = $this->model_laporan->store($save_data);
			if (!$save_laporan && $new_document) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan/', $new_document);
			}

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
		
		$existing_laporan = $this->model_laporan->find($id);
		if (!$existing_laporan) {
			show_404();
		}
		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('laporan_Laporan_name', 'Laporan', 'trim|required|max_length[255]|callback_valid_report_document');
		
		if ($this->form_validation->run()) {
			$laporan_Laporan_uuid = basename((string) $this->input->post('laporan_Laporan_uuid', true));
			$laporan_Laporan_name = basename((string) $this->input->post('laporan_Laporan_name', true));
		
			$save_data = [
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];

			$new_document = null;
			if (!empty($laporan_Laporan_uuid)) {
				$new_document = $this->storage_manager->move_from_temp($laporan_Laporan_uuid, $laporan_Laporan_name, 'uploads/laporan/');
				if (!$new_document) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $new_document;
			}
		
			
			$save_laporan = $this->model_laporan->change($id, $save_data);
			if ($save_laporan && $new_document && $new_document !== $existing_laporan->Laporan) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan/', $existing_laporan->Laporan);
			} elseif (!$save_laporan && $new_document) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan/', $new_document);
			}

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
		} elseif (count($arr_id) >0) {
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

		$this->template->title('Laporan Detail');
		$this->render('modul/laporan/laporan_view', $this->data);
	}

	public function document($id, $mode = 'preview')
	{
		$this->is_allowed('laporan_view');
		$laporan = $this->model_laporan->find((int) $id);
		if (!$laporan || empty($laporan->Laporan)) show_404();
		$this->serve_document('uploads/laporan', $laporan->Laporan, $mode === 'download');
	}
	
	/**
	* delete Laporans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$laporan = $this->model_laporan->find($id);
		if (!$laporan) {
			return false;
		}
		$removed = $this->model_laporan->remove($id);
		if ($removed && !empty($laporan->Laporan)) {
			$this->storage_manager->delete_if_unreferenced('uploads/laporan/', $laporan->Laporan);
		}
		return $removed;
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
			'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png',
			'max_size' 	 	=> 10000,
		]);
	}

	public function valid_report_document($filename)
	{
		$allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
		$extension = strtolower(pathinfo(basename((string) $filename), PATHINFO_EXTENSION));
		if (in_array($extension, $allowed, true)) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'Dokumen laporan harus berupa PDF, Word, Excel, JPG, atau PNG.');
		return false;
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

		$this->model_laporan->export('laporan', 'laporan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('laporan_export');

		$this->model_laporan->pdf('laporan', 'laporan');
	}
}


/* End of file laporan.php */
/* Location: ./application/controllers/Laporan.php */
