<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Legalisasi Controller
*| --------------------------------------------------------------------------
*| Rekap Legalisasi site
*|
*/
class Rekap_legalisasi extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_legalisasi');
	}

	/**
	* show all Rekap Legalisasis
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_legalisasi_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_legalisasis'] = $this->model_rekap_legalisasi->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_legalisasi_counts'] = $this->model_rekap_legalisasi->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_legalisasi/index/',
			'total_rows'   => $this->model_rekap_legalisasi->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Legalisasi List');
		$this->render('modul/rekap_legalisasi/rekap_legalisasi_list', $this->data);
	}
	
	/**
	* Add new rekap_legalisasis
	*
	*/
	public function add()
	{
		$this->is_allowed('rekap_legalisasi_add');

		$this->template->title('Rekap Legalisasi New');
		$this->render('modul/rekap_legalisasi/rekap_legalisasi_add', $this->data);
	}

	/**
	* Add New Rekap Legalisasis
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('rekap_legalisasi_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nomor_akta', 'Nomor Akta', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('tanggal_akta', 'Tanggal Akta', 'trim|required');
		$this->form_validation->set_rules('sifat_akta', 'Sifat Akta', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('penghadap', 'Penghadap', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'username' => get_user_data('username'),
				'nomor_akta' => $this->input->post('nomor_akta'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'sifat_akta' => $this->input->post('sifat_akta'),
				'penghadap' => $this->input->post('penghadap'),
			];

			
			$save_rekap_legalisasi = $this->model_rekap_legalisasi->store($save_data);

			if ($save_rekap_legalisasi) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_rekap_legalisasi;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('rekap_legalisasi/edit/' . $save_rekap_legalisasi, 'Edit Rekap Legalisasi'),
						anchor('rekap_legalisasi', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('rekap_legalisasi/edit/' . $save_rekap_legalisasi, 'Edit Rekap Legalisasi')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_legalisasi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_legalisasi');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Rekap Legalisasis
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('rekap_legalisasi_update');

		$this->data['rekap_legalisasi'] = $this->model_rekap_legalisasi->find($id);

		$this->template->title('Rekap Legalisasi Update');
		$this->render('modul/rekap_legalisasi/rekap_legalisasi_update', $this->data);
	}

	/**
	* Update Rekap Legalisasis
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('rekap_legalisasi_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nomor_akta', 'Nomor Akta', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('tanggal_akta', 'Tanggal Akta', 'trim|required');
		$this->form_validation->set_rules('sifat_akta', 'Sifat Akta', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('penghadap', 'Penghadap', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'username' => get_user_data('username'),
				'nomor_akta' => $this->input->post('nomor_akta'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'sifat_akta' => $this->input->post('sifat_akta'),
				'penghadap' => $this->input->post('penghadap'),
			];

			
			$save_rekap_legalisasi = $this->model_rekap_legalisasi->change($id, $save_data);

			if ($save_rekap_legalisasi) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('rekap_legalisasi', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_legalisasi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_legalisasi');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Rekap Legalisasis
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_legalisasi_delete');

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
            set_message(cclang('has_been_deleted', 'rekap_legalisasi'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_legalisasi'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Legalisasis
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_legalisasi_view');

		$this->data['rekap_legalisasi'] = $this->model_rekap_legalisasi->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Legalisasi Detail');
		$this->render('modul/rekap_legalisasi/rekap_legalisasi_view', $this->data);
	}
	
	/**
	* delete Rekap Legalisasis
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_legalisasi = $this->model_rekap_legalisasi->find($id);

		
		
		return $this->model_rekap_legalisasi->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_legalisasi_export');

		$this->model_rekap_legalisasi->export('rekap_legalisasi', 'rekap_legalisasi');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_legalisasi_export');

		$this->model_rekap_legalisasi->pdf('rekap_legalisasi', 'rekap_legalisasi');
	}
}


/* End of file rekap_legalisasi.php */
/* Location: ./application/controllers/Rekap Legalisasi.php */