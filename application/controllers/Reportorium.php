<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Reportorium Controller
*| --------------------------------------------------------------------------
*| Reportorium site
*|
*/
class Reportorium extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_reportorium');
	}

	/**
	* show all Reportoriums
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('reportorium_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['reportoriums'] = $this->model_reportorium->get($filter, $field, $this->limit_page, $offset);
		$this->data['reportorium_counts'] = $this->model_reportorium->count_all($filter, $field);

		$config = [
			'base_url'     => 'reportorium/index/',
			'total_rows'   => $this->model_reportorium->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Reportorium List');
		$this->render('modul/reportorium/reportorium_list', $this->data);
	}
	
	/**
	* Add new reportoriums
	*
	*/
	public function add()
	{
		$this->is_allowed('reportorium_add');

		$this->template->title('Reportorium New');
		$this->render('modul/reportorium/reportorium_add', $this->data);
	}

	/**
	* Add New Reportoriums
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('reportorium_add', false)) {
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

			
			$save_reportorium = $this->model_reportorium->store($save_data);

			if ($save_reportorium) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_reportorium;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('reportorium/edit/' . $save_reportorium, 'Edit Reportorium'),
						anchor('reportorium', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('reportorium/edit/' . $save_reportorium, 'Edit Reportorium')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('reportorium');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('reportorium');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Reportoriums
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('reportorium_update');

		$this->data['reportorium'] = $this->model_reportorium->find($id);

		$this->template->title('Reportorium Update');
		$this->render('modul/reportorium/reportorium_update', $this->data);
	}

	/**
	* Update Reportoriums
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('reportorium_update', false)) {
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

			
			$save_reportorium = $this->model_reportorium->change($id, $save_data);

			if ($save_reportorium) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('reportorium', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('reportorium');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('reportorium');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Reportoriums
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('reportorium_delete');

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
            set_message(cclang('has_been_deleted', 'reportorium'), 'success');
        } else {
            set_message(cclang('error_delete', 'reportorium'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Reportoriums
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('reportorium_view');

		$this->data['reportorium'] = $this->model_reportorium->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Reportorium Detail');
		$this->render('modul/reportorium/reportorium_view', $this->data);
	}
	
	/**
	* delete Reportoriums
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$reportorium = $this->model_reportorium->find($id);

		
		
		return $this->model_reportorium->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('reportorium_export');

		$this->model_reportorium->export('reportorium', 'reportorium');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('reportorium_export');

		$this->model_reportorium->pdf('reportorium', 'reportorium');
	}
}


/* End of file reportorium.php */
/* Location: ./application/controllers/Reportorium.php */