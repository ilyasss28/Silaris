<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Setup Satker Controller
*| --------------------------------------------------------------------------
*| Setup Satker site
*|
*/
class Setup_satker extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_setup_satker');
	}

	/**
	* show all Setup Satkers
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('setup_satker_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['setup_satkers'] = $this->model_setup_satker->get($filter, $field, $this->limit_page, $offset);
		$this->data['setup_satker_counts'] = $this->model_setup_satker->count_all($filter, $field);

		$config = [
			'base_url'     => 'setup_satker/index/',
			'total_rows'   => $this->model_setup_satker->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Satuan Kerja Kemenkumham Sultra List');
		$this->render('modul/setup_satker/setup_satker_list', $this->data);
	}
	
	/**
	* Add new setup_satkers
	*
	*/
	public function add()
	{
		$this->is_allowed('setup_satker_add');

		$this->template->title('Satuan Kerja Kemenkumham Sultra New');
		$this->render('modul/setup_satker/setup_satker_add', $this->data);
	}

	/**
	* Add New Setup Satkers
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('setup_satker_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama_satker', 'Nama Satker', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_satker' => $this->input->post('nama_satker'),
			];

			
			$save_setup_satker = $this->model_setup_satker->store($save_data);

			if ($save_setup_satker) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_setup_satker;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('setup_satker/edit/' . $save_setup_satker, 'Edit Setup Satker'),
						anchor('setup_satker', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('setup_satker/edit/' . $save_setup_satker, 'Edit Setup Satker')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_satker');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_satker');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Setup Satkers
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('setup_satker_update');

		$this->data['setup_satker'] = $this->model_setup_satker->find($id);

		$this->template->title('Satuan Kerja Kemenkumham Sultra Update');
		$this->render('modul/setup_satker/setup_satker_update', $this->data);
	}

	/**
	* Update Setup Satkers
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('setup_satker_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama_satker', 'Nama Satker', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama_satker' => $this->input->post('nama_satker'),
			];

			
			$save_setup_satker = $this->model_setup_satker->change($id, $save_data);

			if ($save_setup_satker) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('setup_satker', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('setup_satker');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('setup_satker');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Setup Satkers
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('setup_satker_delete');

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
            set_message(cclang('has_been_deleted', 'setup_satker'), 'success');
        } else {
            set_message(cclang('error_delete', 'setup_satker'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Setup Satkers
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('setup_satker_view');

		$this->data['setup_satker'] = $this->model_setup_satker->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Satuan Kerja Kemenkumham Sultra Detail');
		$this->render('modul/setup_satker/setup_satker_view', $this->data);
	}
	
	/**
	* delete Setup Satkers
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$setup_satker = $this->model_setup_satker->find($id);

		
		
		return $this->model_setup_satker->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('setup_satker_export');

		$this->model_setup_satker->export('setup_satker', 'setup_satker');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('setup_satker_export');

		$this->model_setup_satker->pdf('setup_satker', 'setup_satker');
	}
}


/* End of file setup_satker.php */
/* Location: ./application/controllers/Setup Satker.php */