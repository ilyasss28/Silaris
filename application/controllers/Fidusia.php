<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Fidusia Controller
*| --------------------------------------------------------------------------
*| Fidusia site
*|
*/
class Fidusia extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_fidusia');
	}

	/**
	* show all Fidusias
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('fidusia_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['fidusias'] = $this->model_fidusia->get($filter, $field, $this->limit_page, $offset);
		$this->data['fidusia_counts'] = $this->model_fidusia->count_all($filter, $field);

		$config = [
			'base_url'     => 'fidusia/index/',
			'total_rows'   => $this->model_fidusia->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Fidusia List');
		$this->render('modul/fidusia/fidusia_list', $this->data);
	}
	
	/**
	* Add new fidusias
	*
	*/
	public function add()
	{
		$this->is_allowed('fidusia_add');

		$this->template->title('Fidusia New');
		$this->render('modul/fidusia/fidusia_add', $this->data);
	}

	/**
	* Add New Fidusias
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('fidusia_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('tanggal_akta', 'Tanggal Akta', 'trim|required');
		$this->form_validation->set_rules('nomor_akta', 'Nomor Akta', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_pemberi_fidusia', 'Nama Pemberi Fidusia', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('nama_penerima_fidusia', 'Nama Penerima Fidusia', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('no_sertifikat_jaminan_fidusia', 'No Sertifikat Jaminan Fidusia', 'trim|required|max_length[255]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'username' => get_user_data('username'),
				'nama_notaris' => get_user_data('full_name'),
				'tanggal' => date('Y-m-d H:i:s'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'nomor_akta' => $this->input->post('nomor_akta'),
				'nama_pemberi_fidusia' => $this->input->post('nama_pemberi_fidusia'),
				'nama_penerima_fidusia' => $this->input->post('nama_penerima_fidusia'),
				'no_sertifikat_jaminan_fidusia' => $this->input->post('no_sertifikat_jaminan_fidusia'),
			];
			if ($this->db->field_exists('owner_user_id', 'fidusia')) {
				$save_data['owner_user_id'] = (int) get_user_data('id');
			}

			
			$save_fidusia = $this->model_fidusia->store($save_data);

			if ($save_fidusia) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_fidusia;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('fidusia/edit/' . $save_fidusia, 'Edit Fidusia'),
						anchor('fidusia', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('fidusia/edit/' . $save_fidusia, 'Edit Fidusia')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('fidusia');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('fidusia');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Fidusias
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('fidusia_update');

		$this->data['fidusia'] = $this->model_fidusia->find($id);
		if (!$this->data['fidusia']) {
			show_404();
		}

		$this->template->title('Fidusia Update');
		$this->render('modul/fidusia/fidusia_update', $this->data);
	}

	/**
	* Update Fidusias
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('fidusia_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		if (!$this->model_fidusia->find($id)) {
			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Data Fidusia tidak ditemukan atau tidak berada dalam cakupan akses Anda.',
				]));
		}
		
		$this->form_validation->set_rules('tanggal_akta', 'Tanggal Akta', 'trim|required');
		$this->form_validation->set_rules('nomor_akta', 'Nomor Akta', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_pemberi_fidusia', 'Nama Pemberi Fidusia', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('nama_penerima_fidusia', 'Nama Penerima Fidusia', 'trim|required|max_length[255]');
		$this->form_validation->set_rules('no_sertifikat_jaminan_fidusia', 'No Sertifikat Jaminan Fidusia', 'trim|required|max_length[255]');
		
		if ($this->form_validation->run()) {
		
			// Ownership is immutable during an edit. In particular, an Admin or
			// MPD must not become the owner merely because they corrected a record.
			$save_data = [
				'tanggal' => date('Y-m-d H:i:s'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'nomor_akta' => $this->input->post('nomor_akta'),
				'nama_pemberi_fidusia' => $this->input->post('nama_pemberi_fidusia'),
				'nama_penerima_fidusia' => $this->input->post('nama_penerima_fidusia'),
				'no_sertifikat_jaminan_fidusia' => $this->input->post('no_sertifikat_jaminan_fidusia'),
			];

			
			$save_fidusia = $this->model_fidusia->change($id, $save_data);

			if ($save_fidusia) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('fidusia', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('fidusia');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('fidusia');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Fidusias
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('fidusia_delete');

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
            set_message(cclang('has_been_deleted', 'fidusia'), 'success');
        } else {
            set_message(cclang('error_delete', 'fidusia'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Fidusias
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('fidusia_view');

		$this->data['fidusia'] = $this->model_fidusia->join_avaiable()->filter_avaiable()->find($id);
		if (!$this->data['fidusia']) {
			show_404();
		}

		$this->template->title('Fidusia Detail');
		$this->render('modul/fidusia/fidusia_view', $this->data);
	}
	
	/**
	* delete Fidusias
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$fidusia = $this->model_fidusia->find($id);

		
		
		return $fidusia ? $this->model_fidusia->remove($id) : false;
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('fidusia_export');

		$this->model_fidusia->export_scoped('fidusia');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('fidusia_export');

		$this->model_fidusia->pdf_scoped('Fidusia');
	}
}


/* End of file fidusia.php */
/* Location: ./application/controllers/Fidusia.php */
