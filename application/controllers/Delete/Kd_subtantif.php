<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kd Subtantif Controller
*| --------------------------------------------------------------------------
*| Kd Subtantif site
*|
*/
class Kd_subtantif extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_kd_subtantif');
	}

	/**
	* show all Kd Subtantifs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('kd_subtantif_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kd_subtantifs'] = $this->model_kd_subtantif->get($filter, $field, $this->limit_page, $offset);
		$this->data['kd_subtantif_counts'] = $this->model_kd_subtantif->count_all($filter, $field);

		$config = [
			'base_url'     => 'kd_subtantif/index/',
			'total_rows'   => $this->model_kd_subtantif->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kd Subtantif List');
		$this->render('modul/kd_subtantif/kd_subtantif_list', $this->data);
	}
	
	/**
	* Add new kd_subtantifs
	*
	*/
	public function add()
	{
		$this->is_allowed('kd_subtantif_add');

		$this->template->title('Kd Subtantif New');
		$this->render('modul/kd_subtantif/kd_subtantif_add', $this->data);
	}

	/**
	* Add New Kd Subtantifs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('kd_subtantif_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kd_subtantif', 'Kode Subtantif', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_subtantif', 'Nama Subtantif', 'trim|required|max_length[50]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_subtantif' => $this->input->post('kd_subtantif'),
				'nama_subtantif' => $this->input->post('nama_subtantif'),
			];

			
			$save_kd_subtantif = $this->model_kd_subtantif->store($save_data);

			if ($save_kd_subtantif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_kd_subtantif;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('kd_subtantif/edit/' . $save_kd_subtantif, 'Edit Kd Subtantif'),
						anchor('kd_subtantif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('kd_subtantif/edit/' . $save_kd_subtantif, 'Edit Kd Subtantif')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_subtantif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_subtantif');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Kd Subtantifs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('kd_subtantif_update');

		$this->data['kd_subtantif'] = $this->model_kd_subtantif->find($id);

		$this->template->title('Kd Subtantif Update');
		$this->render('modul/kd_subtantif/kd_subtantif_update', $this->data);
	}

	/**
	* Update Kd Subtantifs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('kd_subtantif_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('kd_subtantif', 'Kode Subtantif', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_subtantif', 'Nama Subtantif', 'trim|required|max_length[50]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_subtantif' => $this->input->post('kd_subtantif'),
				'nama_subtantif' => $this->input->post('nama_subtantif'),
			];

			
			$save_kd_subtantif = $this->model_kd_subtantif->change($id, $save_data);

			if ($save_kd_subtantif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('kd_subtantif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_subtantif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_subtantif');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Kd Subtantifs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('kd_subtantif_delete');

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
            set_message(cclang('has_been_deleted', 'kd_subtantif'), 'success');
        } else {
            set_message(cclang('error_delete', 'kd_subtantif'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Kd Subtantifs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kd_subtantif_view');

		$this->data['kd_subtantif'] = $this->model_kd_subtantif->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Kd Subtantif Detail');
		$this->render('modul/kd_subtantif/kd_subtantif_view', $this->data);
	}
	
	/**
	* delete Kd Subtantifs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kd_subtantif = $this->model_kd_subtantif->find($id);

		
		
		return $this->model_kd_subtantif->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kd_subtantif_export');

		$this->model_kd_subtantif->export('kd_subtantif', 'kd_subtantif');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kd_subtantif_export');

		$this->model_kd_subtantif->pdf('kd_subtantif', 'kd_subtantif');
	}
}


/* End of file kd_subtantif.php */
/* Location: ./application/controllers/Kd Subtantif.php */