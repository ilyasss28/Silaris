<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kd Fasilitatif Controller
*| --------------------------------------------------------------------------
*| Kd Fasilitatif site
*|
*/
class Kd_fasilitatif extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_kd_fasilitatif');
	}

	/**
	* show all Kd Fasilitatifs
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('kd_fasilitatif_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kd_fasilitatifs'] = $this->model_kd_fasilitatif->get($filter, $field, $this->limit_page, $offset);
		$this->data['kd_fasilitatif_counts'] = $this->model_kd_fasilitatif->count_all($filter, $field);

		$config = [
			'base_url'     => 'kd_fasilitatif/index/',
			'total_rows'   => $this->model_kd_fasilitatif->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kd Fasilitatif List');
		$this->render('modul/kd_fasilitatif/kd_fasilitatif_list', $this->data);
	}
	
	/**
	* Add new kd_fasilitatifs
	*
	*/
	public function add()
	{
		$this->is_allowed('kd_fasilitatif_add');

		$this->template->title('Kd Fasilitatif New');
		$this->render('modul/kd_fasilitatif/kd_fasilitatif_add', $this->data);
	}

	/**
	* Add New Kd Fasilitatifs
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('kd_fasilitatif_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kd_fasilitatif', 'Kode Fasilitatif', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_fasilitatif', 'Nama Fasilitatif', 'trim|required|max_length[50]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_fasilitatif' => $this->input->post('kd_fasilitatif'),
				'nama_fasilitatif' => $this->input->post('nama_fasilitatif'),
			];

			
			$save_kd_fasilitatif = $this->model_kd_fasilitatif->store($save_data);

			if ($save_kd_fasilitatif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_kd_fasilitatif;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('kd_fasilitatif/edit/' . $save_kd_fasilitatif, 'Edit Kd Fasilitatif'),
						anchor('kd_fasilitatif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('kd_fasilitatif/edit/' . $save_kd_fasilitatif, 'Edit Kd Fasilitatif')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_fasilitatif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_fasilitatif');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Kd Fasilitatifs
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('kd_fasilitatif_update');

		$this->data['kd_fasilitatif'] = $this->model_kd_fasilitatif->find($id);

		$this->template->title('Kd Fasilitatif Update');
		$this->render('modul/kd_fasilitatif/kd_fasilitatif_update', $this->data);
	}

	/**
	* Update Kd Fasilitatifs
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('kd_fasilitatif_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('kd_fasilitatif', 'Kode Fasilitatif', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_fasilitatif', 'Nama Fasilitatif', 'trim|required|max_length[50]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_fasilitatif' => $this->input->post('kd_fasilitatif'),
				'nama_fasilitatif' => $this->input->post('nama_fasilitatif'),
			];

			
			$save_kd_fasilitatif = $this->model_kd_fasilitatif->change($id, $save_data);

			if ($save_kd_fasilitatif) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('kd_fasilitatif', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_fasilitatif');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_fasilitatif');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Kd Fasilitatifs
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('kd_fasilitatif_delete');

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
            set_message(cclang('has_been_deleted', 'kd_fasilitatif'), 'success');
        } else {
            set_message(cclang('error_delete', 'kd_fasilitatif'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Kd Fasilitatifs
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kd_fasilitatif_view');

		$this->data['kd_fasilitatif'] = $this->model_kd_fasilitatif->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Kd Fasilitatif Detail');
		$this->render('modul/kd_fasilitatif/kd_fasilitatif_view', $this->data);
	}
	
	/**
	* delete Kd Fasilitatifs
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kd_fasilitatif = $this->model_kd_fasilitatif->find($id);

		
		
		return $this->model_kd_fasilitatif->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kd_fasilitatif_export');

		$this->model_kd_fasilitatif->export('kd_fasilitatif', 'kd_fasilitatif');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kd_fasilitatif_export');

		$this->model_kd_fasilitatif->pdf('kd_fasilitatif', 'kd_fasilitatif');
	}
}


/* End of file kd_fasilitatif.php */
/* Location: ./application/controllers/Kd Fasilitatif.php */