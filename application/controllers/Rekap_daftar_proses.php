<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Daftar Proses Controller
*| --------------------------------------------------------------------------
*| Rekap Daftar Proses site
*|
*/
class Rekap_daftar_proses extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_daftar_proses');
	}

	/**
	* show all Rekap Daftar Prosess
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_daftar_proses_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_daftar_prosess'] = $this->model_rekap_daftar_proses->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_daftar_proses_counts'] = $this->model_rekap_daftar_proses->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_daftar_proses/index/',
			'total_rows'   => $this->model_rekap_daftar_proses->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Daftar Proses List');
		$this->render('modul/rekap_daftar_proses/rekap_daftar_proses_list', $this->data);
	}
	
	/**
	* Add new rekap_daftar_prosess
	*
	*/
	public function add()
	{
		$this->is_allowed('rekap_daftar_proses_add');

		$this->template->title('Rekap Daftar Proses New');
		$this->render('modul/rekap_daftar_proses/rekap_daftar_proses_add', $this->data);
	}

	/**
	* Add New Rekap Daftar Prosess
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('rekap_daftar_proses_add', false)) {
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

			
			$save_rekap_daftar_proses = $this->model_rekap_daftar_proses->store($save_data);

			if ($save_rekap_daftar_proses) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_rekap_daftar_proses;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('rekap_daftar_proses/edit/' . $save_rekap_daftar_proses, 'Edit Rekap Daftar Proses'),
						anchor('rekap_daftar_proses', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('rekap_daftar_proses/edit/' . $save_rekap_daftar_proses, 'Edit Rekap Daftar Proses')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_daftar_proses');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_daftar_proses');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Rekap Daftar Prosess
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('rekap_daftar_proses_update');

		$this->data['rekap_daftar_proses'] = $this->model_rekap_daftar_proses->find($id);

		$this->template->title('Rekap Daftar Proses Update');
		$this->render('modul/rekap_daftar_proses/rekap_daftar_proses_update', $this->data);
	}

	/**
	* Update Rekap Daftar Prosess
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('rekap_daftar_proses_update', false)) {
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

			
			$save_rekap_daftar_proses = $this->model_rekap_daftar_proses->change($id, $save_data);

			if ($save_rekap_daftar_proses) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('rekap_daftar_proses', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_daftar_proses');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_daftar_proses');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Rekap Daftar Prosess
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_daftar_proses_delete');

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
            set_message(cclang('has_been_deleted', 'rekap_daftar_proses'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_daftar_proses'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Daftar Prosess
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_daftar_proses_view');

		$this->data['rekap_daftar_proses'] = $this->model_rekap_daftar_proses->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Daftar Proses Detail');
		$this->render('modul/rekap_daftar_proses/rekap_daftar_proses_view', $this->data);
	}
	
	/**
	* delete Rekap Daftar Prosess
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_daftar_proses = $this->model_rekap_daftar_proses->find($id);

		
		
		return $this->model_rekap_daftar_proses->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_daftar_proses_export');

		$this->model_rekap_daftar_proses->export('rekap_daftar_proses', 'rekap_daftar_proses');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_daftar_proses_export');

		$this->model_rekap_daftar_proses->pdf('rekap_daftar_proses', 'rekap_daftar_proses');
	}
}


/* End of file rekap_daftar_proses.php */
/* Location: ./application/controllers/Rekap Daftar Proses.php */