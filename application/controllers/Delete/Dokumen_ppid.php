<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Dokumen Ppid Controller
*| --------------------------------------------------------------------------
*| Dokumen Ppid site
*|
*/
class Dokumen_ppid extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_dokumen_ppid');
	}

	/**
	* show all Dokumen Ppids
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('dokumen_ppid_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['dokumen_ppids'] = $this->model_dokumen_ppid->get($filter, $field, $this->limit_page, $offset);
		$this->data['dokumen_ppid_counts'] = $this->model_dokumen_ppid->count_all($filter, $field);

		$config = [
			'base_url'     => 'dokumen_ppid/index/',
			'total_rows'   => $this->model_dokumen_ppid->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Dokumen Ppid List');
		$this->render('modul/dokumen_ppid/dokumen_ppid_list', $this->data);
	}
	
	/**
	* Add new dokumen_ppids
	*
	*/
	public function add()
	{
		$this->is_allowed('dokumen_ppid_add');

		$this->template->title('Dokumen Ppid New');
		$this->render('modul/dokumen_ppid/dokumen_ppid_add', $this->data);
	}

	/**
	* Add New Dokumen Ppids
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('dokumen_ppid_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('jenis_informasi', 'Kategori', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('jenis_dokumen_ppid', 'Jenis Dokumen', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nama_dokumen_ppid', 'Nama Dokumen', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('dokumen_ppid_dokumen_ppid_name', 'File', 'trim|required');
		

		if ($this->form_validation->run()) {
			$dokumen_ppid_dokumen_ppid_uuid = $this->input->post('dokumen_ppid_dokumen_ppid_uuid');
			$dokumen_ppid_dokumen_ppid_name = $this->input->post('dokumen_ppid_dokumen_ppid_name');
		
			$save_data = [
				'jenis_informasi' => $this->input->post('jenis_informasi'),
				'jenis_dokumen_ppid' => $this->input->post('jenis_dokumen_ppid'),
				'nama_dokumen_ppid' => $this->input->post('nama_dokumen_ppid'),
				'tanggal_upload' => date('Y-m-d H:i:s'),
				'uploader' => get_user_data('username'),
			];

			if (!is_dir(FCPATH . '/uploads/dokumen_ppid/')) {
				mkdir(FCPATH . '/uploads/dokumen_ppid/');
			}

			if (!empty($dokumen_ppid_dokumen_ppid_name)) {
				$dokumen_ppid_dokumen_ppid_name_copy = date('YmdHis') . '-' . $dokumen_ppid_dokumen_ppid_name;

				rename(FCPATH . 'uploads/tmp/' . $dokumen_ppid_dokumen_ppid_uuid . '/' . $dokumen_ppid_dokumen_ppid_name, 
						FCPATH . 'uploads/dokumen_ppid/' . $dokumen_ppid_dokumen_ppid_name_copy);

				if (!is_file(FCPATH . '/uploads/dokumen_ppid/' . $dokumen_ppid_dokumen_ppid_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['dokumen_ppid'] = $dokumen_ppid_dokumen_ppid_name_copy;
			}
		
			
			$save_dokumen_ppid = $this->model_dokumen_ppid->store($save_data);

			if ($save_dokumen_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_dokumen_ppid;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('dokumen_ppid/edit/' . $save_dokumen_ppid, 'Edit Dokumen Ppid'),
						anchor('dokumen_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('dokumen_ppid/edit/' . $save_dokumen_ppid, 'Edit Dokumen Ppid')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('dokumen_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('dokumen_ppid');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Dokumen Ppids
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('dokumen_ppid_update');

		$this->data['dokumen_ppid'] = $this->model_dokumen_ppid->find($id);

		$this->template->title('Dokumen Ppid Update');
		$this->render('modul/dokumen_ppid/dokumen_ppid_update', $this->data);
	}

	/**
	* Update Dokumen Ppids
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('dokumen_ppid_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('jenis_informasi', 'Kategori', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('jenis_dokumen_ppid', 'Jenis Dokumen', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nama_dokumen_ppid', 'Nama Dokumen', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('dokumen_ppid_dokumen_ppid_name', 'File', 'trim|required');
		
		if ($this->form_validation->run()) {
			$dokumen_ppid_dokumen_ppid_uuid = $this->input->post('dokumen_ppid_dokumen_ppid_uuid');
			$dokumen_ppid_dokumen_ppid_name = $this->input->post('dokumen_ppid_dokumen_ppid_name');
		
			$save_data = [
				'jenis_informasi' => $this->input->post('jenis_informasi'),
				'jenis_dokumen_ppid' => $this->input->post('jenis_dokumen_ppid'),
				'nama_dokumen_ppid' => $this->input->post('nama_dokumen_ppid'),
				'tanggal_upload' => date('Y-m-d H:i:s'),
				'uploader' => get_user_data('username'),
			];

			if (!is_dir(FCPATH . '/uploads/dokumen_ppid/')) {
				mkdir(FCPATH . '/uploads/dokumen_ppid/');
			}

			if (!empty($dokumen_ppid_dokumen_ppid_uuid)) {
				$dokumen_ppid_dokumen_ppid_name_copy = date('YmdHis') . '-' . $dokumen_ppid_dokumen_ppid_name;

				rename(FCPATH . 'uploads/tmp/' . $dokumen_ppid_dokumen_ppid_uuid . '/' . $dokumen_ppid_dokumen_ppid_name, 
						FCPATH . 'uploads/dokumen_ppid/' . $dokumen_ppid_dokumen_ppid_name_copy);

				if (!is_file(FCPATH . '/uploads/dokumen_ppid/' . $dokumen_ppid_dokumen_ppid_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['dokumen_ppid'] = $dokumen_ppid_dokumen_ppid_name_copy;
			}
		
			
			$save_dokumen_ppid = $this->model_dokumen_ppid->change($id, $save_data);

			if ($save_dokumen_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('dokumen_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('dokumen_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('dokumen_ppid');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Dokumen Ppids
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('dokumen_ppid_delete');

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
            set_message(cclang('has_been_deleted', 'dokumen_ppid'), 'success');
        } else {
            set_message(cclang('error_delete', 'dokumen_ppid'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Dokumen Ppids
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('dokumen_ppid_view');

		$this->data['dokumen_ppid'] = $this->model_dokumen_ppid->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Dokumen Ppid Detail');
		$this->render('modul/dokumen_ppid/dokumen_ppid_view', $this->data);
	}
	
	/**
	* delete Dokumen Ppids
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$dokumen_ppid = $this->model_dokumen_ppid->find($id);

		if (!empty($dokumen_ppid->dokumen_ppid)) {
			$path = FCPATH . '/uploads/dokumen_ppid/' . $dokumen_ppid->dokumen_ppid;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_dokumen_ppid->remove($id);
	}
	
	/**
	* Upload Image Dokumen Ppid	* 
	* @return JSON
	*/
	public function upload_dokumen_ppid_file()
	{
		if (!$this->is_allowed('dokumen_ppid_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'dokumen_ppid',
		]);
	}

	/**
	* Delete Image Dokumen Ppid	* 
	* @return JSON
	*/
	public function delete_dokumen_ppid_file($uuid)
	{
		if (!$this->is_allowed('dokumen_ppid_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'dokumen_ppid', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'dokumen_ppid',
            'primary_key'       => 'id_ppid',
            'upload_path'       => 'uploads/dokumen_ppid/'
        ]);
	}

	/**
	* Get Image Dokumen Ppid	* 
	* @return JSON
	*/
	public function get_dokumen_ppid_file($id)
	{
		if (!$this->is_allowed('dokumen_ppid_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$dokumen_ppid = $this->model_dokumen_ppid->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'dokumen_ppid', 
            'table_name'        => 'dokumen_ppid',
            'primary_key'       => 'id_ppid',
            'upload_path'       => 'uploads/dokumen_ppid/',
            'delete_endpoint'   => 'dokumen_ppid/delete_dokumen_ppid_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('dokumen_ppid_export');

		$this->model_dokumen_ppid->export('dokumen_ppid', 'dokumen_ppid');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('dokumen_ppid_export');

		$this->model_dokumen_ppid->pdf('dokumen_ppid', 'dokumen_ppid');
	}
}


/* End of file dokumen_ppid.php */
/* Location: ./application/controllers/Dokumen Ppid.php */