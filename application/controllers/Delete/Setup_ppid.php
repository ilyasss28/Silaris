<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Setup Ppid Controller
*| --------------------------------------------------------------------------
*| Setup Ppid site
*|
*/
class Setup_ppid extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_setup_ppid');
	}

	/**
	* show all Setup Ppids
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('setup_ppid_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['setup_ppids'] = $this->model_setup_ppid->get($filter, $field, $this->limit_page, $offset);
		$this->data['setup_ppid_counts'] = $this->model_setup_ppid->count_all($filter, $field);

		$config = [
			'base_url'     => 'setup_ppid/index/',
			'total_rows'   => $this->model_setup_ppid->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Setup Ppid List');
		$this->render('modul/setup_ppid/setup_ppid_list', $this->data);
	}
	
	/**
	* Add new setup_ppids
	*
	*/
	public function add()
	{
		$this->is_allowed('setup_ppid_add');

		$this->template->title('Setup Ppid New');
		$this->render('modul/setup_ppid/setup_ppid_add', $this->data);
	}

	/**
	* Add New Setup Ppids
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('setup_ppid_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('jenis_informasi', 'Jenis Informasi', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'jenis_informasi' => $this->input->post('jenis_informasi'),
			];

			
			$save_setup_ppid = $this->model_setup_ppid->store($save_data);

			if ($save_setup_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_setup_ppid;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('setup_ppid/edit/' . $save_setup_ppid, 'Edit Setup Ppid'),
						anchor('setup_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('setup_ppid/edit/' . $save_setup_ppid, 'Edit Setup Ppid')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_ppid');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Setup Ppids
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('setup_ppid_update');

		$this->data['setup_ppid'] = $this->model_setup_ppid->find($id);

		$this->template->title('Setup Ppid Update');
		$this->render('modul/setup_ppid/setup_ppid_update', $this->data);
	}

	/**
	* Update Setup Ppids
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('setup_ppid_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('jenis_informasi', 'Jenis Informasi', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'jenis_informasi' => $this->input->post('jenis_informasi'),
			];

			
			$save_setup_ppid = $this->model_setup_ppid->change($id, $save_data);

			if ($save_setup_ppid) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('setup_ppid', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_ppid');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_ppid');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Setup Ppids
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('setup_ppid_delete');

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
            set_message(cclang('has_been_deleted', 'setup_ppid'), 'success');
        } else {
            set_message(cclang('error_delete', 'setup_ppid'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Setup Ppids
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('setup_ppid_view');

		$this->data['setup_ppid'] = $this->model_setup_ppid->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Setup Ppid Detail');
		$this->render('modul/setup_ppid/setup_ppid_view', $this->data);
	}
	
	/**
	* delete Setup Ppids
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$setup_ppid = $this->model_setup_ppid->find($id);

		
		
		return $this->model_setup_ppid->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('setup_ppid_export');

		$this->model_setup_ppid->export('setup_ppid', 'setup_ppid');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('setup_ppid_export');

		$this->model_setup_ppid->pdf('setup_ppid', 'setup_ppid');
	}
}


/* End of file setup_ppid.php */
/* Location: ./application/controllers/Setup Ppid.php */