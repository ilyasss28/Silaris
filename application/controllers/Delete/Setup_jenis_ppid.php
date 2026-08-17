<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Setup Jenis Ppid Controller
*| --------------------------------------------------------------------------
*| Setup Jenis Ppid site
*|
*/
class Setup_jenis_ppid extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_setup_jenis_ppid');
	}

	/**
	* show all Setup Jenis Ppids
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('setup_jenis_ppid_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['setup_jenis_ppids'] = $this->model_setup_jenis_ppid->get($filter, $field, $this->limit_page, $offset);
		$this->data['setup_jenis_ppid_counts'] = $this->model_setup_jenis_ppid->count_all($filter, $field);

		$config = [
			'base_url'     => 'setup_jenis_ppid/index/',
			'total_rows'   => $this->model_setup_jenis_ppid->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Setup Jenis Ppid List');
		$this->render('modul/setup_jenis_ppid/setup_jenis_ppid_list', $this->data);
	}
	
	/**
	* Add new setup_jenis_ppids
	*
	*/
	public function add()
	{
		$this->is_allowed('setup_jenis_ppid_add');

		$this->template->title('Setup Jenis Ppid New');
		$this->render('modul/setup_jenis_ppid/setup_jenis_ppid_add', $this->data);
	}

	/**
	* Add New Setup Jenis Ppids
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('setup_jenis_ppid_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('jenis_dokumen_ppid', 'Jenis Dokumen Ppid', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'jenis_dokumen_ppid' => $this->input->post('jenis_dokumen_ppid'),
			];

			
			$save_setup_jenis_ppid = $this->model_setup_jenis_ppid->store($save_data);

			if ($save_setup_jenis_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_setup_jenis_ppid;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('setup_jenis_ppid/edit/' . $save_setup_jenis_ppid, 'Edit Setup Jenis Ppid'),
						anchor('setup_jenis_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('setup_jenis_ppid/edit/' . $save_setup_jenis_ppid, 'Edit Setup Jenis Ppid')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_jenis_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_jenis_ppid');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Setup Jenis Ppids
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('setup_jenis_ppid_update');

		$this->data['setup_jenis_ppid'] = $this->model_setup_jenis_ppid->find($id);

		$this->template->title('Setup Jenis Ppid Update');
		$this->render('modul/setup_jenis_ppid/setup_jenis_ppid_update', $this->data);
	}

	/**
	* Update Setup Jenis Ppids
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('setup_jenis_ppid_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('jenis_dokumen_ppid', 'Jenis Dokumen Ppid', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'jenis_dokumen_ppid' => $this->input->post('jenis_dokumen_ppid'),
			];

			
			$save_setup_jenis_ppid = $this->model_setup_jenis_ppid->change($id, $save_data);

			if ($save_setup_jenis_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('setup_jenis_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_jenis_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_jenis_ppid');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Setup Jenis Ppids
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('setup_jenis_ppid_delete');

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
            set_message(cclang('has_been_deleted', 'setup_jenis_ppid'), 'success');
        } else {
            set_message(cclang('error_delete', 'setup_jenis_ppid'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Setup Jenis Ppids
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('setup_jenis_ppid_view');

		$this->data['setup_jenis_ppid'] = $this->model_setup_jenis_ppid->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Setup Jenis Ppid Detail');
		$this->render('modul/setup_jenis_ppid/setup_jenis_ppid_view', $this->data);
	}
	
	/**
	* delete Setup Jenis Ppids
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$setup_jenis_ppid = $this->model_setup_jenis_ppid->find($id);

		
		
		return $this->model_setup_jenis_ppid->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('setup_jenis_ppid_export');

		$this->model_setup_jenis_ppid->export('setup_jenis_ppid', 'setup_jenis_ppid');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('setup_jenis_ppid_export');

		$this->model_setup_jenis_ppid->pdf('setup_jenis_ppid', 'setup_jenis_ppid');
	}
}


/* End of file setup_jenis_ppid.php */
/* Location: ./application/controllers/Setup Jenis Ppid.php */