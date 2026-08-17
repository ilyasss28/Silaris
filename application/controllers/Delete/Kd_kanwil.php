<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Kd Kanwil Controller
*| --------------------------------------------------------------------------
*| Kd Kanwil site
*|
*/
class Kd_kanwil extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_kd_kanwil');
	}

	/**
	* show all Kd Kanwils
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('kd_kanwil_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['kd_kanwils'] = $this->model_kd_kanwil->get($filter, $field, $this->limit_page, $offset);
		$this->data['kd_kanwil_counts'] = $this->model_kd_kanwil->count_all($filter, $field);

		$config = [
			'base_url'     => 'kd_kanwil/index/',
			'total_rows'   => $this->model_kd_kanwil->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Kd Kanwil List');
		$this->render('modul/kd_kanwil/kd_kanwil_list', $this->data);
	}
	
	/**
	* Add new kd_kanwils
	*
	*/
	public function add()
	{
		$this->is_allowed('kd_kanwil_add');

		$this->template->title('Kd Kanwil New');
		$this->render('modul/kd_kanwil/kd_kanwil_add', $this->data);
	}

	/**
	* Add New Kd Kanwils
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('kd_kanwil_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('kd_kanwil', 'Kode Kanwil', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_kanwil', 'Nama Kanwil', 'trim|required|max_length[50]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_kanwil' => $this->input->post('kd_kanwil'),
				'nama_kanwil' => $this->input->post('nama_kanwil'),
			];

			
			$save_kd_kanwil = $this->model_kd_kanwil->store($save_data);

			if ($save_kd_kanwil) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_kd_kanwil;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('kd_kanwil/edit/' . $save_kd_kanwil, 'Edit Kd Kanwil'),
						anchor('kd_kanwil', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('kd_kanwil/edit/' . $save_kd_kanwil, 'Edit Kd Kanwil')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_kanwil');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_kanwil');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Kd Kanwils
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('kd_kanwil_update');

		$this->data['kd_kanwil'] = $this->model_kd_kanwil->find($id);

		$this->template->title('Kd Kanwil Update');
		$this->render('modul/kd_kanwil/kd_kanwil_update', $this->data);
	}

	/**
	* Update Kd Kanwils
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('kd_kanwil_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('kd_kanwil', 'Kode Kanwil', 'trim|required|max_length[10]');
		$this->form_validation->set_rules('nama_kanwil', 'Nama Kanwil', 'trim|required|max_length[50]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'kd_kanwil' => $this->input->post('kd_kanwil'),
				'nama_kanwil' => $this->input->post('nama_kanwil'),
			];

			
			$save_kd_kanwil = $this->model_kd_kanwil->change($id, $save_data);

			if ($save_kd_kanwil) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('kd_kanwil', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('kd_kanwil');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('kd_kanwil');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Kd Kanwils
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('kd_kanwil_delete');

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
            set_message(cclang('has_been_deleted', 'kd_kanwil'), 'success');
        } else {
            set_message(cclang('error_delete', 'kd_kanwil'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Kd Kanwils
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('kd_kanwil_view');

		$this->data['kd_kanwil'] = $this->model_kd_kanwil->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Kd Kanwil Detail');
		$this->render('modul/kd_kanwil/kd_kanwil_view', $this->data);
	}
	
	/**
	* delete Kd Kanwils
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$kd_kanwil = $this->model_kd_kanwil->find($id);

		
		
		return $this->model_kd_kanwil->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('kd_kanwil_export');

		$this->model_kd_kanwil->export('kd_kanwil', 'kd_kanwil');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('kd_kanwil_export');

		$this->model_kd_kanwil->pdf('kd_kanwil', 'kd_kanwil');
	}
}


/* End of file kd_kanwil.php */
/* Location: ./application/controllers/Kd Kanwil.php */