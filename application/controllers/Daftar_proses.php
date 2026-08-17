<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Daftar Proses Controller
*| --------------------------------------------------------------------------
*| Daftar Proses site
*|
*/
class Daftar_proses extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_daftar_proses');
	}

	/**
	* show all Daftar Prosess
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('daftar_proses_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['daftar_prosess'] = $this->model_daftar_proses->get($filter, $field, $this->limit_page, $offset);
		$this->data['daftar_proses_counts'] = $this->model_daftar_proses->count_all($filter, $field);

		$config = [
			'base_url'     => 'daftar_proses/index/',
			'total_rows'   => $this->model_daftar_proses->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Daftar Proses List');
		$this->render('modul/daftar_proses/daftar_proses_list', $this->data);
	}
	
	/**
	* Add new daftar_prosess
	*
	*/
	public function add()
	{
		$this->is_allowed('daftar_proses_add');

		$this->template->title('Daftar Proses New');
		$this->render('modul/daftar_proses/daftar_proses_add', $this->data);
	}

	/**
	* Add New Daftar Prosess
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('daftar_proses_add', false)) {
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

			
			$save_daftar_proses = $this->model_daftar_proses->store($save_data);

			if ($save_daftar_proses) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_daftar_proses;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('daftar_proses/edit/' . $save_daftar_proses, 'Edit Daftar Proses'),
						anchor('daftar_proses', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('daftar_proses/edit/' . $save_daftar_proses, 'Edit Daftar Proses')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('daftar_proses');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('daftar_proses');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Daftar Prosess
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('daftar_proses_update');

		$this->data['daftar_proses'] = $this->model_daftar_proses->find($id);

		$this->template->title('Daftar Proses Update');
		$this->render('modul/daftar_proses/daftar_proses_update', $this->data);
	}

	/**
	* Update Daftar Prosess
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('daftar_proses_update', false)) {
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

			
			$save_daftar_proses = $this->model_daftar_proses->change($id, $save_data);

			if ($save_daftar_proses) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('daftar_proses', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('daftar_proses');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('daftar_proses');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Daftar Prosess
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('daftar_proses_delete');

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
            set_message(cclang('has_been_deleted', 'daftar_proses'), 'success');
        } else {
            set_message(cclang('error_delete', 'daftar_proses'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Daftar Prosess
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('daftar_proses_view');

		$this->data['daftar_proses'] = $this->model_daftar_proses->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Daftar Proses Detail');
		$this->render('modul/daftar_proses/daftar_proses_view', $this->data);
	}
	
	/**
	* delete Daftar Prosess
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$daftar_proses = $this->model_daftar_proses->find($id);

		
		
		return $this->model_daftar_proses->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('daftar_proses_export');

		$this->model_daftar_proses->export('daftar_proses', 'daftar_proses');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('daftar_proses_export');

		$this->model_daftar_proses->pdf('daftar_proses', 'daftar_proses');
	}
}


/* End of file daftar_proses.php */
/* Location: ./application/controllers/Daftar Proses.php */