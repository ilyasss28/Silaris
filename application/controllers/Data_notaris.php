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
		$this->load->model('model_user');
		$this->load->library('storage_manager');
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

		$this->set_registry_validation_rules();
		

		if ($this->form_validation->run()) {
			$data_notaris_foto_uuid = $this->input->post('data_notaris_foto_uuid');
			$data_notaris_foto_name = $this->input->post('data_notaris_foto_name');
		
			$save_data = [
				'nama_notaris' => format_person_name($this->input->post('nama_notaris', true)),
				'tempat_lahir' => trim((string) $this->input->post('tempat_lahir', true)),
				'tanggal_lahir' => $this->nullable_value('tanggal_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
				'email' => trim((string) $this->input->post('email', true)),
				'wilayah' => $this->region_name($this->input->post('kode_wilayah', true)),
				'surat_pindah' => trim((string) $this->input->post('surat_pindah', true)),
				'surat_keputusan' => trim((string) $this->input->post('surat_keputusan', true)),
				'alamat_rumah' => trim((string) $this->input->post('alamat_rumah', true)),
				'alamat_kantor' => trim((string) $this->input->post('alamat_kantor', true)),
				'kode_wilayah' => $this->input->post('kode_wilayah', true),
				'lat' => $this->nullable_value('lat'),
				'no_telepon' => format_phone_number($this->input->post('no_telepon', true)),
				'long' => $this->nullable_value('long'),
				'npwp' => $this->digits_or_null('npwp'),
				'nomor_ktp' => $this->digits_or_null('nomor_ktp'),
				'nomor_bap' => trim((string) $this->input->post('nomor_bap', true)),
				'tanggal_bap' => $this->nullable_value('tanggal_bap'),
				'pemegang_protokol' => trim((string) $this->input->post('pemegang_protokol', true)),
				'status_notaris' => $this->input->post('status_notaris', true),
			];

			$new_photo = null;
			if (!empty($data_notaris_foto_name)) {
				$new_photo = $this->storage_manager->move_from_temp($data_notaris_foto_uuid, $data_notaris_foto_name, 'uploads/data_notaris/');
				if (!$new_photo) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['foto'] = $new_photo;
			}
		
			
			$save_data_notaris = $this->model_data_notaris->store($save_data);
			if (!$save_data_notaris && $new_photo) {
				$this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $new_photo);
			}
			if ($save_data_notaris) {
				$this->model_data_notaris->link_registry_to_account((int) $save_data_notaris);
				if ($new_photo && !$this->storage_manager->promote_notary_photo((int) $save_data_notaris)) {
					log_message('error', 'Gagal mempromosikan foto Data Notaris #' . (int) $save_data_notaris . ' menjadi avatar akun.');
				}
			}

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
		
		$existing_notary = $this->model_data_notaris->find($id);
		if (!$existing_notary) {
			show_404();
		}
		$this->set_registry_validation_rules();
		
		if ($this->form_validation->run()) {
			$data_notaris_foto_uuid = $this->input->post('data_notaris_foto_uuid');
			$data_notaris_foto_name = $this->input->post('data_notaris_foto_name');
		
			$save_data = [
				'nama_notaris' => format_person_name($this->input->post('nama_notaris', true)),
				'tempat_lahir' => trim((string) $this->input->post('tempat_lahir', true)),
				'tanggal_lahir' => $this->nullable_value('tanggal_lahir'),
				'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
				'email' => trim((string) $this->input->post('email', true)),
				'wilayah' => $this->region_name($this->input->post('kode_wilayah', true)),
				'surat_pindah' => trim((string) $this->input->post('surat_pindah', true)),
				'surat_keputusan' => trim((string) $this->input->post('surat_keputusan', true)),
				'alamat_rumah' => trim((string) $this->input->post('alamat_rumah', true)),
				'alamat_kantor' => trim((string) $this->input->post('alamat_kantor', true)),
				'kode_wilayah' => $this->input->post('kode_wilayah', true),
				'lat' => $this->nullable_value('lat'),
				'no_telepon' => format_phone_number($this->input->post('no_telepon', true)),
				'long' => $this->nullable_value('long'),
				'npwp' => $this->digits_or_null('npwp'),
				'nomor_ktp' => $this->digits_or_null('nomor_ktp'),
				'nomor_bap' => trim((string) $this->input->post('nomor_bap', true)),
				'tanggal_bap' => $this->nullable_value('tanggal_bap'),
				'pemegang_protokol' => trim((string) $this->input->post('pemegang_protokol', true)),
				'status_notaris' => $this->input->post('status_notaris', true),
			];

			$new_photo = null;
			if (!empty($data_notaris_foto_uuid)) {
				$new_photo = $this->storage_manager->move_from_temp($data_notaris_foto_uuid, $data_notaris_foto_name, 'uploads/data_notaris/');
				if (!$new_photo) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['foto'] = $new_photo;
			}
		
			
			$save_data_notaris = $this->model_data_notaris->change($id, $save_data);
			if ($save_data_notaris) {
				$this->model_data_notaris->link_registry_to_account((int) $id);
				if ($new_photo && $new_photo !== $existing_notary->foto) {
					$this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $existing_notary->foto);
				}
				if ($new_photo && !$this->storage_manager->promote_notary_photo((int) $id)) {
					log_message('error', 'Gagal mempromosikan foto Data Notaris #' . (int) $id . ' menjadi avatar akun.');
				}
			} elseif ($new_photo) {
				$this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $new_photo);
			}

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
		$user_id = $data_notaris ? (int) ($data_notaris->user_id ?? $data_notaris->account_user_id ?? 0) : 0;

		$removed = $this->model_data_notaris->remove($id);
		if ($removed && !empty($data_notaris->foto)) {
			$this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $data_notaris->foto);
			$this->storage_manager->delete_if_unreferenced('uploads/user/', $data_notaris->foto);
		}
		if ($removed && $user_id > 0) {
			$this->model_user->enforce_notary_roster($user_id);
		}
		return $removed;
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

		if ($this->input->get('by') !== 'id') {
			echo $this->delete_file(['uuid' => $uuid, 'upload_path_tmp' => './uploads/tmp/']);
			return;
		}

		$data_notaris = $this->model_data_notaris->find($uuid);
		$filename = $data_notaris ? basename((string) $data_notaris->foto) : '';
		$updated = $data_notaris && $this->model_data_notaris->change($uuid, ['foto' => null]);
		if ($updated && $filename !== '') {
			$deleted_staging = $this->storage_manager->delete_if_unreferenced('uploads/data_notaris/', $filename);
			$deleted_canonical = $this->storage_manager->delete_if_unreferenced('uploads/user/', $filename);
			echo json_encode($deleted_staging && $deleted_canonical ? ['success' => true] : ['error' => 'Error delete file']);
			return;
		}

		echo json_encode(['error' => 'File tidak ditemukan.']);
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
			'upload_path'       => 'uploads/user/',
            'delete_endpoint'   => 'data_notaris/delete_foto_file'
        ]);
	}

	private function set_registry_validation_rules()
	{
		$this->form_validation->set_rules('nama_notaris', 'Nama Notaris', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|max_length[100]');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required|in_list[Laki-laki,Perempuan]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[150]');
		$this->form_validation->set_rules('kode_wilayah', 'Wilayah Kerja', 'trim|required|callback_valid_region_code');
		$this->form_validation->set_rules('surat_pindah', 'Surat Pindah', 'trim|max_length[100]');
		$this->form_validation->set_rules('surat_keputusan', 'Surat Keputusan', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_rumah', 'Alamat Rumah', 'trim|max_length[100]');
		$this->form_validation->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|max_length[1000]');
		$this->form_validation->set_rules('no_telepon', 'Nomor Telepon', 'trim|required|callback_valid_indonesian_phone');
		$this->form_validation->set_rules('lat', 'Latitude', 'trim|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]');
		$this->form_validation->set_rules('long', 'Longitude', 'trim|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]');
		$this->form_validation->set_rules('npwp', 'NPWP', 'trim|callback_valid_npwp');
		$this->form_validation->set_rules('nomor_ktp', 'Nomor KTP', 'trim|exact_length[16]|numeric');
		$this->form_validation->set_rules('nomor_bap', 'Nomor BAP', 'trim|max_length[150]');
		$this->form_validation->set_rules('tanggal_bap', 'Tanggal BAP', 'trim|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('pemegang_protokol', 'Pemegang Protokol', 'trim|max_length[150]');
		$this->form_validation->set_rules('status_notaris', 'Status Notaris', 'trim|required|in_list[NOTARIS AKTIF,NOTARIS NONAKTIF,CUTI,PINDAH,MENINGGAL DUNIA]');
	}

	public function valid_npwp($value)
	{
		$digits = preg_replace('/\D+/', '', (string) $value);
		if ($digits === '' || in_array(strlen($digits), array(15, 16), true)) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'NPWP harus terdiri dari 15 atau 16 digit.');
		return false;
	}

	private function region_name($code)
	{
		$row = $this->db->select('nama')->get_where('wilayah', array('kd_wilayah' => trim((string) $code)))->row();
		return $row ? format_title_case($row->nama) : '';
	}

	private function nullable_value($field)
	{
		$value = trim((string) $this->input->post($field, true));
		return $value === '' ? null : $value;
	}

	private function digits_or_null($field)
	{
		$value = preg_replace('/\D+/', '', (string) $this->input->post($field, true));
		return $value === '' ? null : $value;
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
