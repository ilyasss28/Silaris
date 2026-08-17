<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Arsip Fasilitatif Controller
*| --------------------------------------------------------------------------
*| Arsip Fasilitatif site
*|
*/
class Arsip_fasilitatif extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_arsip_fasilitatif');
	}

	/**
	* show all Arsip Fasilitatifs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('arsip_fasilitatif_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['arsip_fasilitatifs'] = $this->model_arsip_fasilitatif->get($filter, $field, $this->limit_page, $offset);
		$this->data['arsip_fasilitatif_counts'] = $this->model_arsip_fasilitatif->count_all($filter, $field);

		$config = [
			'base_url'     => 'arsip_fasilitatif/index/',
			'total_rows'   => $this->model_arsip_fasilitatif->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Arsip Fasilitatif List');
		$this->render('modul/arsip_fasilitatif/arsip_fasilitatif_list', $this->data);
	}
	
	/**
	* Add new arsip_fasilitatifs
	*
	*/
	public function add()
	{
		$this->is_allowed('arsip_fasilitatif_add');

		$this->template->title('Arsip Fasilitatif New');
		$this->render('modul/arsip_fasilitatif/arsip_fasilitatif_add', $this->data);
	}

	/**
	* Add New Arsip Fasilitatifs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('arsip_fasilitatif_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kode_fasilitatif', 'Kode', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('no_klasifikasi', 'No', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('jenis_arsip', 'Jenis Arsip', 'trim|max_length[100]');
		$this->form_validation->set_rules('nama_arsip', 'Nama Arsip', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('arsip_fasilitatif_file_arsip_name', 'File', 'trim|required');
		

		if ($this->form_validation->run()) {
			$arsip_fasilitatif_file_arsip_uuid = $this->input->post('arsip_fasilitatif_file_arsip_uuid');
			$arsip_fasilitatif_file_arsip_name = $this->input->post('arsip_fasilitatif_file_arsip_name');
		
			$save_data = [
				'kode_fasilitatif' => $this->input->post('kode_fasilitatif'),
				'no_klasifikasi' => $this->input->post('no_klasifikasi'),
				'jenis_arsip' => $this->input->post('jenis_arsip'),
				'sifat_arsip' => $this->input->post('sifat_arsip'),
				'nama_arsip' => $this->input->post('nama_arsip'),
				'tahun_arsip' => $this->input->post('tahun_arsip'),
				'created_by' => get_user_data('username'),
				'creation_date' => date('Y-m-d H:i:s'),
			];

			if (!is_dir(FCPATH . '/uploads/arsip_fasilitatif/')) {
				mkdir(FCPATH . '/uploads/arsip_fasilitatif/');
			}

			if (!empty($arsip_fasilitatif_file_arsip_name)) {
				$arsip_fasilitatif_file_arsip_name_copy = date('YmdHis') . '-' . $arsip_fasilitatif_file_arsip_name;

				rename(FCPATH . 'uploads/tmp/' . $arsip_fasilitatif_file_arsip_uuid . '/' . $arsip_fasilitatif_file_arsip_name, 
						FCPATH . 'uploads/arsip_fasilitatif/' . $arsip_fasilitatif_file_arsip_name_copy);

				if (!is_file(FCPATH . '/uploads/arsip_fasilitatif/' . $arsip_fasilitatif_file_arsip_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['file_arsip'] = $arsip_fasilitatif_file_arsip_name_copy;
			}
		
			
			$save_arsip_fasilitatif = $this->model_arsip_fasilitatif->store($save_data);

			if ($save_arsip_fasilitatif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_arsip_fasilitatif;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('arsip_fasilitatif/edit/' . $save_arsip_fasilitatif, 'Edit Arsip Fasilitatif'),
						anchor('arsip_fasilitatif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('arsip_fasilitatif/edit/' . $save_arsip_fasilitatif, 'Edit Arsip Fasilitatif')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('arsip_fasilitatif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('arsip_fasilitatif');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Arsip Fasilitatifs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('arsip_fasilitatif_update');

		$this->data['arsip_fasilitatif'] = $this->model_arsip_fasilitatif->find($id);

		$this->template->title('Arsip Fasilitatif Update');
		$this->render('modul/arsip_fasilitatif/arsip_fasilitatif_update', $this->data);
	}

	/**
	* Update Arsip Fasilitatifs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('arsip_fasilitatif_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('kode_fasilitatif', 'Kode', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('no_klasifikasi', 'No', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('jenis_arsip', 'Jenis Arsip', 'trim|max_length[100]');
		$this->form_validation->set_rules('nama_arsip', 'Nama Arsip', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('arsip_fasilitatif_file_arsip_name', 'File', 'trim|required');
		
		if ($this->form_validation->run()) {
			$arsip_fasilitatif_file_arsip_uuid = $this->input->post('arsip_fasilitatif_file_arsip_uuid');
			$arsip_fasilitatif_file_arsip_name = $this->input->post('arsip_fasilitatif_file_arsip_name');
		
			$save_data = [
				'kode_fasilitatif' => $this->input->post('kode_fasilitatif'),
				'no_klasifikasi' => $this->input->post('no_klasifikasi'),
				'jenis_arsip' => $this->input->post('jenis_arsip'),
				'sifat_arsip' => $this->input->post('sifat_arsip'),
				'nama_arsip' => $this->input->post('nama_arsip'),
				'tahun_arsip' => $this->input->post('tahun_arsip'),
				'created_by' => get_user_data('username'),
				'creation_date' => date('Y-m-d H:i:s'),
			];

			if (!is_dir(FCPATH . '/uploads/arsip_fasilitatif/')) {
				mkdir(FCPATH . '/uploads/arsip_fasilitatif/');
			}

			if (!empty($arsip_fasilitatif_file_arsip_uuid)) {
				$arsip_fasilitatif_file_arsip_name_copy = date('YmdHis') . '-' . $arsip_fasilitatif_file_arsip_name;

				rename(FCPATH . 'uploads/tmp/' . $arsip_fasilitatif_file_arsip_uuid . '/' . $arsip_fasilitatif_file_arsip_name, 
						FCPATH . 'uploads/arsip_fasilitatif/' . $arsip_fasilitatif_file_arsip_name_copy);

				if (!is_file(FCPATH . '/uploads/arsip_fasilitatif/' . $arsip_fasilitatif_file_arsip_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['file_arsip'] = $arsip_fasilitatif_file_arsip_name_copy;
			}
		
			
			$save_arsip_fasilitatif = $this->model_arsip_fasilitatif->change($id, $save_data);

			if ($save_arsip_fasilitatif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('arsip_fasilitatif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('arsip_fasilitatif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('arsip_fasilitatif');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Arsip Fasilitatifs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('arsip_fasilitatif_delete');

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
            set_message(cclang('has_been_deleted', 'arsip_fasilitatif'), 'success');
        } else {
            set_message(cclang('error_delete', 'arsip_fasilitatif'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Arsip Fasilitatifs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('arsip_fasilitatif_view');

		$this->data['arsip_fasilitatif'] = $this->model_arsip_fasilitatif->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Arsip Fasilitatif Detail');
		$this->render('modul/arsip_fasilitatif/arsip_fasilitatif_view', $this->data);
	}
	
	/**
	* delete Arsip Fasilitatifs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$arsip_fasilitatif = $this->model_arsip_fasilitatif->find($id);

		if (!empty($arsip_fasilitatif->file_arsip)) {
			$path = FCPATH . '/uploads/arsip_fasilitatif/' . $arsip_fasilitatif->file_arsip;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_arsip_fasilitatif->remove($id);
	}
	
	/**
	* Upload Image Arsip Fasilitatif	* 
	* @return JSON
	*/
	public function upload_file_arsip_file()
	{
		if (!$this->is_allowed('arsip_fasilitatif_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'arsip_fasilitatif',
		]);
	}

	/**
	* Delete Image Arsip Fasilitatif	* 
	* @return JSON
	*/
	public function delete_file_arsip_file($uuid)
	{
		if (!$this->is_allowed('arsip_fasilitatif_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'file_arsip', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'arsip_fasilitatif',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/arsip_fasilitatif/'
        ]);
	}

	/**
	* Get Image Arsip Fasilitatif	* 
	* @return JSON
	*/
	public function get_file_arsip_file($id)
	{
		if (!$this->is_allowed('arsip_fasilitatif_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$arsip_fasilitatif = $this->model_arsip_fasilitatif->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'file_arsip', 
            'table_name'        => 'arsip_fasilitatif',
            'primary_key'       => 'id',
            'upload_path'       => 'uploads/arsip_fasilitatif/',
            'delete_endpoint'   => 'arsip_fasilitatif/delete_file_arsip_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('arsip_fasilitatif_export');

		$this->model_arsip_fasilitatif->export('arsip_fasilitatif', 'arsip_fasilitatif');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('arsip_fasilitatif_export');

		$this->model_arsip_fasilitatif->pdf('arsip_fasilitatif', 'arsip_fasilitatif');
	}
}


/* End of file arsip_fasilitatif.php */
/* Location: ./application/controllers/Arsip Fasilitatif.php */