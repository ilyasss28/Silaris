<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Wil Controller
*| --------------------------------------------------------------------------
*| Wil site
*|
*/
class Wil extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_wil');
	}

	/**
	* show all Wils
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('wil_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['wils'] = $this->model_wil->get($filter, $field, $this->limit_page, $offset);
		$this->data['wil_counts'] = $this->model_wil->count_all($filter, $field);

		$config = [
			'base_url'     => 'wil/index/',
			'total_rows'   => $this->model_wil->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Setup Wilayah List');
		$this->render('modul/wil/wil_list', $this->data);
	}
	
	/**
	* Add new wils
	*
	*/
	public function add()
	{
		$this->is_allowed('wil_add');

		$this->template->title('Setup Wilayah New');
		$this->render('modul/wil/wil_add', $this->data);
	}

	/**
	* Add New Wils
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('wil_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kd_wilayah', 'Kode Wilayah', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_wilayah' => $this->input->post('kd_wilayah'),
				'nama_wilayah' => $this->input->post('nama_wilayah'),
			];

			
			$save_wil = $this->model_wil->store($save_data);

			if ($save_wil) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_wil;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('wil/edit/' . $save_wil, 'Edit Wil'),
						anchor('wil', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('wil/edit/' . $save_wil, 'Edit Wil')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('wil');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('wil');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Wils
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('wil_update');

		$this->data['wil'] = $this->model_wil->find($id);

		$this->template->title('Setup Wilayah Update');
		$this->render('modul/wil/wil_update', $this->data);
	}

	/**
	* Update Wils
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('wil_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('kd_wilayah', 'Kode Wilayah', 'trim|required|max_length[30]');
		$this->form_validation->set_rules('nama_wilayah', 'Nama Wilayah', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_wilayah' => $this->input->post('kd_wilayah'),
				'nama_wilayah' => $this->input->post('nama_wilayah'),
			];

			
			$save_wil = $this->model_wil->change($id, $save_data);

			if ($save_wil) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('wil', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('wil');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('wil');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Wils
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('wil_delete');

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
            set_message(cclang('has_been_deleted', 'wil'), 'success');
        } else {
            set_message(cclang('error_delete', 'wil'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Wils
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('wil_view');

		$this->data['wil'] = $this->model_wil->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Setup Wilayah Detail');
		$this->render('modul/wil/wil_view', $this->data);
	}
	
	/**
	* delete Wils
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$wil = $this->model_wil->find($id);

		
		
		return $this->model_wil->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('wil_export');

		$this->model_wil->export('wil', 'wil');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('wil_export');

		$this->model_wil->pdf('wil', 'wil');
	}
}


/* End of file wil.php */
/* Location: ./application/controllers/Wil.php */