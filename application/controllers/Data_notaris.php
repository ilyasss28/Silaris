<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Data Notaris Controller
*| --------------------------------------------------------------------------
*| Data Notaris site
*|
*/
class Data_notaris extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_data_notaris');
	}

	/**
	* show all Data Notariss
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('data_notaris_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['data_notariss'] = $this->model_data_notaris->get($filter, $field, $this->limit_page, $offset);
		$this->data['data_notaris_counts'] = $this->model_data_notaris->count_all($filter, $field);

		$config = [
			'base_url'     => 'data_notaris/index/',
			'total_rows'   => $this->model_data_notaris->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Data Notaris List');
		$this->render('modul/data_notaris/data_notaris_list', $this->data);
	}
	
	/**
	* Add new data_notariss
	*
	*/
	public function add()
	{
		$this->is_allowed('data_notaris_add');

		$this->template->title('Data Notaris New');
		$this->render('modul/data_notaris/data_notaris_add', $this->data);
	}

	/**
	* Add New Data Notariss
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('data_notaris_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_notaris', 'Nama Notaris', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|max_length[100]');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]');
		$this->form_validation->set_rules('wilayah', 'Wilayah', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('surat_pindah', 'Surat Pindah', 'trim|max_length[100]');
		$this->form_validation->set_rules('surat_keputusan', 'Surat Keputusan', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_rumah', 'Alamat Rumah', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|max_length[100]');
		$this->form_validation->set_rules('password', 'Password', 'trim|max_length[100]');
		

		if ($this->form_validation->run()) {
			$data_notaris_foto_uuid = $this->input->post('data_notaris_foto_uuid');
			$data_notaris_foto_name = $this->input->post('data_notaris_foto_name');
		
			$save_data = [
				'nama_notaris' => $this->input->post('nama_notaris'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'email' => $this->input->post('email'),
				'wilayah' => $this->input->post('wilayah'),
				'surat_pindah' => $this->input->post('surat_pindah'),
				'surat_keputusan' => $this->input->post('surat_keputusan'),
				'alamat_rumah' => $this->input->post('alamat_rumah'),
				'alamat_kantor' => $this->input->post('alamat_kantor'),
				'password' => $this->input->post('password'),
				'kode_wilayah' => $this->input->post('kode_wilayah'),
				'lat' => $this->input->post('lat'),
				'no_telepon' => $this->input->post('no_telepon'),
				'long' => $this->input->post('long'),
			];

			if (!is_dir(FCPATH . '/uploads/data_notaris/')) {
				mkdir(FCPATH . '/uploads/data_notaris/');
			}

			if (!empty($data_notaris_foto_name)) {
				$data_notaris_foto_name_copy = date('YmdHis') . '-' . $data_notaris_foto_name;

				rename(FCPATH . 'uploads/tmp/' . $data_notaris_foto_uuid . '/' . $data_notaris_foto_name, 
						FCPATH . 'uploads/data_notaris/' . $data_notaris_foto_name_copy);

				if (!is_file(FCPATH . '/uploads/data_notaris/' . $data_notaris_foto_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['foto'] = $data_notaris_foto_name_copy;
			}
		
			
			$save_data_notaris = $this->model_data_notaris->store($save_data);

			if ($save_data_notaris) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_data_notaris;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('data_notaris/edit/' . $save_data_notaris, 'Edit Data Notaris'),
						anchor('data_notaris', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('data_notaris/edit/' . $save_data_notaris, 'Edit Data Notaris')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('data_notaris');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('data_notaris');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Data Notariss
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('data_notaris_update');

		$this->data['data_notaris'] = $this->model_data_notaris->find($id);

		$this->template->title('Data Notaris Update');
		$this->render('modul/data_notaris/data_notaris_update', $this->data);
	}

	/**
	* Update Data Notariss
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('data_notaris_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama_notaris', 'Nama Notaris', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|max_length[100]');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|max_length[100]');
		$this->form_validation->set_rules('wilayah', 'Wilayah', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('surat_pindah', 'Surat Pindah', 'trim|max_length[100]');
		$this->form_validation->set_rules('surat_keputusan', 'Surat Keputusan', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_rumah', 'Alamat Rumah', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|max_length[100]');
		$this->form_validation->set_rules('password', 'Password', 'trim|max_length[100]');
		
		if ($this->form_validation->run()) {
			$data_notaris_foto_uuid = $this->input->post('data_notaris_foto_uuid');
			$data_notaris_foto_name = $this->input->post('data_notaris_foto_name');
		
			$save_data = [
				'nama_notaris' => $this->input->post('nama_notaris'),
				'tempat_lahir' => $this->input->post('tempat_lahir'),
				'tanggal_lahir' => $this->input->post('tanggal_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin'),
				'email' => $this->input->post('email'),
				'wilayah' => $this->input->post('wilayah'),
				'surat_pindah' => $this->input->post('surat_pindah'),
				'surat_keputusan' => $this->input->post('surat_keputusan'),
				'alamat_rumah' => $this->input->post('alamat_rumah'),
				'alamat_kantor' => $this->input->post('alamat_kantor'),
				'password' => $this->input->post('password'),
				'kode_wilayah' => $this->input->post('kode_wilayah'),
				'lat' => $this->input->post('lat'),
				'no_telepon' => $this->input->post('no_telepon'),
				'long' => $this->input->post('long'),
			];

			if (!is_dir(FCPATH . '/uploads/data_notaris/')) {
				mkdir(FCPATH . '/uploads/data_notaris/');
			}

			if (!empty($data_notaris_foto_uuid)) {
				$data_notaris_foto_name_copy = date('YmdHis') . '-' . $data_notaris_foto_name;

				rename(FCPATH . 'uploads/tmp/' . $data_notaris_foto_uuid . '/' . $data_notaris_foto_name, 
						FCPATH . 'uploads/data_notaris/' . $data_notaris_foto_name_copy);

				if (!is_file(FCPATH . '/uploads/data_notaris/' . $data_notaris_foto_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['foto'] = $data_notaris_foto_name_copy;
			}
		
			
			$save_data_notaris = $this->model_data_notaris->change($id, $save_data);

			if ($save_data_notaris) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('data_notaris', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('data_notaris');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('data_notaris');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Data Notariss
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('data_notaris_delete');

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
            set_message(cclang('has_been_deleted', 'data_notaris'), 'success');
        } else {
            set_message(cclang('error_delete', 'data_notaris'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Data Notariss
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('data_notaris_view');

		$this->data['data_notaris'] = $this->model_data_notaris->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Data Notaris Detail');
		$this->render('modul/data_notaris/data_notaris_view', $this->data);
	}
	
	/**
	* delete Data Notariss
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$data_notaris = $this->model_data_notaris->find($id);

		if (!empty($data_notaris->foto)) {
			$path = FCPATH . '/uploads/data_notaris/' . $data_notaris->foto;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_data_notaris->remove($id);
	}
	
	/**
	* Upload Image Data Notaris	* 
	* @return JSON
	*/
	public function upload_foto_file()
	{
		if (!$this->is_allowed('data_notaris_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'data_notaris',
		]);
	}

	/**
	* Delete Image Data Notaris	* 
	* @return JSON
	*/
	public function delete_foto_file($uuid)
	{
		if (!$this->is_allowed('data_notaris_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'foto', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'data_notaris',
            'primary_key'       => 'id_notaris',
            'upload_path'       => 'uploads/data_notaris/'
        ]);
	}

	/**
	* Get Image Data Notaris	* 
	* @return JSON
	*/
	public function get_foto_file($id)
	{
		if (!$this->is_allowed('data_notaris_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$data_notaris = $this->model_data_notaris->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'foto', 
            'table_name'        => 'data_notaris',
            'primary_key'       => 'id_notaris',
            'upload_path'       => 'uploads/data_notaris/',
            'delete_endpoint'   => 'data_notaris/delete_foto_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('data_notaris_export');

		$this->model_data_notaris->export('data_notaris', 'data_notaris');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('data_notaris_export');

		$this->model_data_notaris->pdf('data_notaris', 'data_notaris');
	}
}


/* End of file data_notaris.php */
/* Location: ./application/controllers/Data Notaris.php */