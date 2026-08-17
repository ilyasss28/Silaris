<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Waarmerking Controller
*| --------------------------------------------------------------------------
*| Waarmerking site
*|
*/
class Waarmerking extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_waarmerking');
	}

	/**
	* show all Waarmerkings
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('waarmerking_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['waarmerkings'] = $this->model_waarmerking->get($filter, $field, $this->limit_page, $offset);
		$this->data['waarmerking_counts'] = $this->model_waarmerking->count_all($filter, $field);

		$config = [
			'base_url'     => 'waarmerking/index/',
			'total_rows'   => $this->model_waarmerking->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Waarmerking List');
		$this->render('modul/waarmerking/waarmerking_list', $this->data);
	}
	
	/**
	* Add new waarmerkings
	*
	*/
	public function add()
	{
		$this->is_allowed('waarmerking_add');

		$this->template->title('Waarmerking New');
		$this->render('modul/waarmerking/waarmerking_add', $this->data);
	}

	/**
	* Add New Waarmerkings
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('waarmerking_add', false)) {
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
				'nama_notaris' => get_user_data('full_name'),
				'username' => get_user_data('username'),
					'nomor_akta' => $this->input->post('nomor_akta'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'sifat_akta' => $this->input->post('sifat_akta'),
				'penghadap' => $this->input->post('penghadap'),
			];

			
			$save_waarmerking = $this->model_waarmerking->store($save_data);

			if ($save_waarmerking) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_waarmerking;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('waarmerking/edit/' . $save_waarmerking, 'Edit Waarmerking'),
						anchor('waarmerking', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('waarmerking/edit/' . $save_waarmerking, 'Edit Waarmerking')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('waarmerking');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('waarmerking');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Waarmerkings
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('waarmerking_update');

		$this->data['waarmerking'] = $this->model_waarmerking->find($id);

		$this->template->title('Waarmerking Update');
		$this->render('modul/waarmerking/waarmerking_update', $this->data);
	}

	/**
	* Update Waarmerkings
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('waarmerking_update', false)) {
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
				'nama_notaris' => $this->input->post('nama_notaris'),
				'username' => get_user_data('username'),
				'nomor_akta' => $this->input->post('nomor_akta'),
				'tanggal_akta' => $this->input->post('tanggal_akta'),
				'sifat_akta' => $this->input->post('sifat_akta'),
				'penghadap' => $this->input->post('penghadap'),
			];

			
			$save_waarmerking = $this->model_waarmerking->change($id, $save_data);

			if ($save_waarmerking) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('waarmerking', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('waarmerking');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('waarmerking');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Waarmerkings
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('waarmerking_delete');

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
            set_message(cclang('has_been_deleted', 'waarmerking'), 'success');
        } else {
            set_message(cclang('error_delete', 'waarmerking'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Waarmerkings
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('waarmerking_view');

		$this->data['waarmerking'] = $this->model_waarmerking->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Waarmerking Detail');
		$this->render('modul/waarmerking/waarmerking_view', $this->data);
	}
	
	/**
	* delete Waarmerkings
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$waarmerking = $this->model_waarmerking->find($id);

		
		
		return $this->model_waarmerking->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('waarmerking_export');

		$this->model_waarmerking->export('waarmerking', 'waarmerking');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('waarmerking_export');

		$this->model_waarmerking->pdf('waarmerking', 'waarmerking');
	}
}


/* End of file waarmerking.php */
/* Location: ./application/controllers/Waarmerking.php */