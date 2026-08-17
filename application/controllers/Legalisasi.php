<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Legalisasi Controller
*| --------------------------------------------------------------------------
*| Legalisasi site
*|
*/
class Legalisasi extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_legalisasi');
	}

	/**
	* show all Legalisasis
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('legalisasi_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['legalisasis'] = $this->model_legalisasi->get($filter, $field, $this->limit_page, $offset);
		$this->data['legalisasi_counts'] = $this->model_legalisasi->count_all($filter, $field);

		$config = [
			'base_url'     => 'legalisasi/index/',
			'total_rows'   => $this->model_legalisasi->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Legalisasi List');
		$this->render('modul/legalisasi/legalisasi_list', $this->data);
	}
	
	/**
	* Add new legalisasis
	*
	*/
	public function add()
	{
		$this->is_allowed('legalisasi_add');

		$this->template->title('Legalisasi New');
		$this->render('modul/legalisasi/legalisasi_add', $this->data);
	}

	/**
	* Add New Legalisasis
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('legalisasi_add', false)) {
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

			
			$save_legalisasi = $this->model_legalisasi->store($save_data);

			if ($save_legalisasi) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_legalisasi;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('legalisasi/edit/' . $save_legalisasi, 'Edit Legalisasi'),
						anchor('legalisasi', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('legalisasi/edit/' . $save_legalisasi, 'Edit Legalisasi')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('legalisasi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('legalisasi');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Legalisasis
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('legalisasi_update');

		$this->data['legalisasi'] = $this->model_legalisasi->find($id);

		$this->template->title('Legalisasi Update');
		$this->render('modul/legalisasi/legalisasi_update', $this->data);
	}

	/**
	* Update Legalisasis
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('legalisasi_update', false)) {
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

			
			$save_legalisasi = $this->model_legalisasi->change($id, $save_data);

			if ($save_legalisasi) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('legalisasi', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('legalisasi');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('legalisasi');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Legalisasis
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('legalisasi_delete');

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
            set_message(cclang('has_been_deleted', 'legalisasi'), 'success');
        } else {
            set_message(cclang('error_delete', 'legalisasi'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Legalisasis
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('legalisasi_view');

		$this->data['legalisasi'] = $this->model_legalisasi->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Legalisasi Detail');
		$this->render('modul/legalisasi/legalisasi_view', $this->data);
	}
	
	/**
	* delete Legalisasis
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$legalisasi = $this->model_legalisasi->find($id);

		
		
		return $this->model_legalisasi->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('legalisasi_export');

		$this->model_legalisasi->export('legalisasi', 'legalisasi');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('legalisasi_export');

		$this->model_legalisasi->pdf('legalisasi', 'legalisasi');
	}
}


/* End of file legalisasi.php */
/* Location: ./application/controllers/Legalisasi.php */