<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Laporan Bulanan Controller
*| --------------------------------------------------------------------------
*| Laporan Bulanan site
*|
*/
class Laporan_bulanan extends Admin
{
	private $monthly_report_edit_id = 0;
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_laporan_bulanan');
		$this->load->library('storage_manager');
	}

	/**
	* show all Laporan Bulanans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('laporan_bulanan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['laporan_bulanans'] = $this->model_laporan_bulanan->get($filter, $field, $this->limit_page, $offset);
		$this->data['laporan_bulanan_counts'] = $this->model_laporan_bulanan->count_all($filter, $field);

		$config = [
			'base_url'     => 'laporan_bulanan/index/',
			'total_rows'   => $this->model_laporan_bulanan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Laporan Bulanan List');
		$this->render('modul/laporan_bulanan/laporan_bulanan_list', $this->data);
	}
	
	/**
	* Add new laporan_bulanans
	*
	*/
	public function add()
	{
		$this->is_allowed('laporan_bulanan_add');
		$this->require_complete_notary_profile();

		$this->template->title('Laporan Bulanan New');
		$this->render('modul/laporan_bulanan/laporan_bulanan_add', $this->data);
	}

	/**
	* Add New Laporan Bulanans
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('laporan_bulanan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		if (!$this->require_complete_notary_profile(true)) return;

		$this->set_monthly_report_validation_rules();

		if ($this->form_validation->run()) {
			$laporan_bulanan_file_laporan_uuid = basename((string) $this->input->post('laporan_bulanan_file_laporan_uuid', true));
			$laporan_bulanan_file_laporan_name = basename((string) $this->input->post('laporan_bulanan_file_laporan_name', true));
		
			$save_data = [
				'username' => get_user_data('username'),
				'nama_notaris' => format_person_name(get_user_data('full_name')),
				'owner_user_id' => (int) get_user_data('id'),
				'tanggal_laporan' => $this->input->post('tanggal_laporan'),
				'kd_wilayah' => $this->input->post('kd_wilayah'),
			];

			$new_document = null;
			if (!empty($laporan_bulanan_file_laporan_name)) {
				$new_document = $this->storage_manager->move_from_temp($laporan_bulanan_file_laporan_uuid, $laporan_bulanan_file_laporan_name, 'uploads/laporan_bulanan/');
				if (!$new_document) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['file_laporan'] = $new_document;
			}
		
			
			$save_laporan_bulanan = $this->model_laporan_bulanan->store($save_data);
			if (!$save_laporan_bulanan && $new_document) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan_bulanan/', $new_document);
			}

			if ($save_laporan_bulanan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_laporan_bulanan;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('laporan_bulanan/edit/' . $save_laporan_bulanan, 'Edit Laporan Bulanan'),
						anchor('laporan_bulanan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('laporan_bulanan/edit/' . $save_laporan_bulanan, 'Edit Laporan Bulanan')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('laporan_bulanan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('laporan_bulanan');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Laporan Bulanans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('laporan_bulanan_update');

		$this->data['laporan_bulanan'] = $this->model_laporan_bulanan->find($id);

		$this->template->title('Laporan Bulanan Update');
		$this->render('modul/laporan_bulanan/laporan_bulanan_update', $this->data);
	}

	/**
	* Update Laporan Bulanans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('laporan_bulanan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$existing_laporan = $this->model_laporan_bulanan->find($id);
		if (!$existing_laporan) {
			show_404();
		}
		$this->monthly_report_edit_id = (int) $id;
		$this->set_monthly_report_validation_rules();

		if ($this->form_validation->run()) {
			$laporan_bulanan_file_laporan_uuid = basename((string) $this->input->post('laporan_bulanan_file_laporan_uuid', true));
			$laporan_bulanan_file_laporan_name = basename((string) $this->input->post('laporan_bulanan_file_laporan_name', true));
		
			$save_data = [
				'tanggal_laporan' => $this->input->post('tanggal_laporan'),
				'kd_wilayah' => $this->input->post('kd_wilayah'),
			];

			$new_document = null;
			if (!empty($laporan_bulanan_file_laporan_uuid)) {
				$new_document = $this->storage_manager->move_from_temp($laporan_bulanan_file_laporan_uuid, $laporan_bulanan_file_laporan_name, 'uploads/laporan_bulanan/');
				if (!$new_document) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['file_laporan'] = $new_document;
			}
		
			
			$save_laporan_bulanan = $this->model_laporan_bulanan->change($id, $save_data);
			if ($save_laporan_bulanan && $new_document && $new_document !== $existing_laporan->file_laporan) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan_bulanan/', $existing_laporan->file_laporan);
			} elseif (!$save_laporan_bulanan && $new_document) {
				$this->storage_manager->delete_if_unreferenced('uploads/laporan_bulanan/', $new_document);
			}

			if ($save_laporan_bulanan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('laporan_bulanan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('laporan_bulanan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('laporan_bulanan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Laporan Bulanans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('laporan_bulanan_delete');

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
            set_message(cclang('has_been_deleted', 'laporan_bulanan'), 'success');
        } else {
            set_message(cclang('error_delete', 'laporan_bulanan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Laporan Bulanans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('laporan_bulanan_view');

		$this->data['laporan_bulanan'] = $this->model_laporan_bulanan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Laporan Bulanan Detail');
		$this->render('modul/laporan_bulanan/laporan_bulanan_view', $this->data);
	}

	public function document($id, $mode = 'preview')
	{
		$this->is_allowed('laporan_bulanan_view');
		$laporan = $this->model_laporan_bulanan->find((int) $id);
		if (!$laporan || empty($laporan->file_laporan)) show_404();
		$this->serve_document('uploads/laporan_bulanan', $laporan->file_laporan, $mode === 'download');
	}
	
	/**
	* delete Laporan Bulanans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$laporan_bulanan = $this->model_laporan_bulanan->find($id);
		if (!$laporan_bulanan) {
			return false;
		}
		$removed = $this->model_laporan_bulanan->remove($id);
		if ($removed && !empty($laporan_bulanan->file_laporan)) {
			$this->storage_manager->delete_if_unreferenced('uploads/laporan_bulanan/', $laporan_bulanan->file_laporan);
		}
		return $removed;
	}
	
	/**
	* Upload Image Laporan Bulanan	* 
	* @return JSON
	*/
	public function upload_file_laporan_file()
	{
		if (!$this->is_allowed('laporan_bulanan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'laporan_bulanan',
			'allowed_types' => 'pdf|doc|docx|xls|xlsx|jpg|jpeg|png',
			'max_size'      => 10000,
		]);
	}

	private function set_monthly_report_validation_rules()
	{
		$this->form_validation->set_rules(
			'tanggal_laporan',
			'Tanggal Laporan',
			'trim|required|callback_valid_date|callback_valid_not_future_date|callback_unique_monthly_period'
		);
		$this->form_validation->set_rules(
			'kd_wilayah',
			'Wilayah',
			'trim|required|integer|callback_valid_monthly_region'
		);
		$this->form_validation->set_rules(
			'laporan_bulanan_file_laporan_name',
			'File Laporan',
			'trim|required|max_length[255]|callback_valid_monthly_report_document'
		);
	}

	public function valid_monthly_region($region_id)
	{
		$valid = ctype_digit((string) $region_id)
			&& (int) $region_id > 0
			&& $this->db->where('id', (int) $region_id)->count_all_results('wil') === 1;
		if (!$valid) {
			$this->form_validation->set_message(__FUNCTION__, 'Wilayah yang dipilih tidak terdaftar.');
		}
		return $valid;
	}

	public function valid_monthly_report_document($filename)
	{
		$allowed = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'jpg', 'jpeg', 'png');
		$extension = strtolower(pathinfo(basename((string) $filename), PATHINFO_EXTENSION));
		if (in_array($extension, $allowed, true)) {
			return true;
		}
		$this->form_validation->set_message(
			__FUNCTION__,
			'Dokumen laporan harus berupa PDF, Word, Excel, JPG, JPEG, atau PNG.'
		);
		return false;
	}

	public function unique_monthly_period($report_date)
	{
		$date = DateTime::createFromFormat('!Y-m-d', (string) $report_date);
		if (!$date || $date->format('Y-m-d') !== $report_date) {
			return true;
		}

		$period_start = $date->format('Y-m-01');
		$period_end = (clone $date)->modify('first day of next month')->format('Y-m-d');
		$user_id = (int) get_user_data('id');
		$username = (string) get_user_data('username');

		$this->db
			->from('laporan_bulanan')
			->where('tanggal_laporan >=', $period_start)
			->where('tanggal_laporan <', $period_end)
			->group_start()
				->where('owner_user_id', $user_id)
				->or_group_start()
					->where('owner_user_id IS NULL', null, false)
					->where('username', $username)
				->group_end()
			->group_end();

		if ($this->monthly_report_edit_id > 0) {
			$this->db->where('id_laporan_bulanan !=', $this->monthly_report_edit_id);
		}

		if ($this->db->count_all_results() === 0) {
			return true;
		}

		$this->form_validation->set_message(
			__FUNCTION__,
			'Laporan Bulanan untuk periode tersebut sudah pernah dibuat.'
		);
		return false;
	}

	/**
	* Delete Image Laporan Bulanan	* 
	* @return JSON
	*/
	public function delete_file_laporan_file($uuid)
	{
		if (!$this->is_allowed('laporan_bulanan_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'file_laporan', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'laporan_bulanan',
            'primary_key'       => 'id_laporan_bulanan',
            'upload_path'       => 'uploads/laporan_bulanan/'
        ]);
	}

	/**
	* Get Image Laporan Bulanan	* 
	* @return JSON
	*/
	public function get_file_laporan_file($id)
	{
		if (!$this->is_allowed('laporan_bulanan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$laporan_bulanan = $this->model_laporan_bulanan->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'file_laporan', 
            'table_name'        => 'laporan_bulanan',
            'primary_key'       => 'id_laporan_bulanan',
            'upload_path'       => 'uploads/laporan_bulanan/',
            'delete_endpoint'   => 'laporan_bulanan/delete_file_laporan_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('laporan_bulanan_export');

		$this->model_laporan_bulanan->export('laporan_bulanan', 'laporan_bulanan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('laporan_bulanan_export');

		$this->model_laporan_bulanan->pdf('laporan_bulanan', 'laporan_bulanan');
	}
}


/* End of file laporan_bulanan.php */
/* Location: ./application/controllers/Laporan Bulanan.php */
