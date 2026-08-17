<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Izin Penelitian Controller
*| --------------------------------------------------------------------------
*| Izin Penelitian site
*|
*/
class Izin_penelitian extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_izin_penelitian');
	}

	/**
	* show all Izin Penelitians
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('izin_penelitian_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['izin_penelitians'] = $this->model_izin_penelitian->get($filter, $field, $this->limit_page, $offset);
		$this->data['izin_penelitian_counts'] = $this->model_izin_penelitian->count_all($filter, $field);

		$config = [
			'base_url'     => 'izin_penelitian/index/',
			'total_rows'   => $this->model_izin_penelitian->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Izin Penelitian List');
		$this->render('modul/izin_penelitian/izin_penelitian_list', $this->data);
	}
	
	/**
	* Add new izin_penelitians
	*
	*/
	public function add()
	{
		$this->is_allowed('izin_penelitian_add');

		$this->template->title('Izin Penelitian New');
		$this->render('modul/izin_penelitian/izin_penelitian_add', $this->data);
	}

	/**
	* Add New Izin Penelitians
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('izin_penelitian_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nama_kampus', 'Nama Kampus', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nim', 'NIM', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('jurusan', 'Jurusan', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('judul_penelitian', 'Judul Penelitian', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('durasi_penelitian', 'Durasi Penelitian', 'trim|required');
		$this->form_validation->set_rules('lokasi_penelitian', 'Lokasi Penelitian', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('kontak_person', 'Kontak Person', 'trim|required|max_length[12]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]');
		

		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama' => $this->input->post('nama'),
				'nama_kampus' => $this->input->post('nama_kampus'),
				'nim' => $this->input->post('nim'),
				'jurusan' => $this->input->post('jurusan'),
				'judul_penelitian' => $this->input->post('judul_penelitian'),
				'durasi_penelitian' => $this->input->post('durasi_penelitian'),
				'lokasi_penelitian' => $this->input->post('lokasi_penelitian'),
				'kontak_person' => $this->input->post('kontak_person'),
				'email' => $this->input->post('email'),
				'status' => $this->input->post('status'),
			];

			
			$save_izin_penelitian = $this->model_izin_penelitian->store($save_data);

			if ($save_izin_penelitian) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_izin_penelitian;
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('izin_penelitian/edit/' . $save_izin_penelitian, 'Edit Izin Penelitian'),
						anchor('izin_penelitian', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('izin_penelitian/edit/' . $save_izin_penelitian, 'Edit Izin Penelitian')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('izin_penelitian');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('izin_penelitian');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
		/**
	* Update view Izin Penelitians
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('izin_penelitian_update');

		$this->data['izin_penelitian'] = $this->model_izin_penelitian->find($id);

		$this->template->title('Izin Penelitian Update');
		$this->render('modul/izin_penelitian/izin_penelitian_update', $this->data);
	}

	/**
	* Update Izin Penelitians
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('izin_penelitian_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('nama', 'Nama', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nama_kampus', 'Nama Kampus', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('nim', 'NIM', 'trim|required|max_length[50]');
		$this->form_validation->set_rules('jurusan', 'Jurusan', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('judul_penelitian', 'Judul Penelitian', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('durasi_penelitian', 'Durasi Penelitian', 'trim|required');
		$this->form_validation->set_rules('lokasi_penelitian', 'Lokasi Penelitian', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('kontak_person', 'Kontak Person', 'trim|required|max_length[12]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]');
		
		if ($this->form_validation->run()) {
		
			$save_data = [
				'nama' => $this->input->post('nama'),
				'nama_kampus' => $this->input->post('nama_kampus'),
				'nim' => $this->input->post('nim'),
				'jurusan' => $this->input->post('jurusan'),
				'judul_penelitian' => $this->input->post('judul_penelitian'),
				'durasi_penelitian' => $this->input->post('durasi_penelitian'),
				'lokasi_penelitian' => $this->input->post('lokasi_penelitian'),
				'kontak_person' => $this->input->post('kontak_person'),
				'email' => $this->input->post('email'),
				'status' => $this->input->post('status'),
			];

			
			$save_izin_penelitian = $this->model_izin_penelitian->change($id, $save_data);

			if ($save_izin_penelitian) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('izin_penelitian', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('izin_penelitian');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('izin_penelitian');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Izin Penelitians
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('izin_penelitian_delete');

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
            set_message(cclang('has_been_deleted', 'izin_penelitian'), 'success');
        } else {
            set_message(cclang('error_delete', 'izin_penelitian'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Izin Penelitians
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('izin_penelitian_view');

		$this->data['izin_penelitian'] = $this->model_izin_penelitian->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Izin Penelitian Detail');
		$this->render('modul/izin_penelitian/izin_penelitian_view', $this->data);
	}
	
	/**
	* delete Izin Penelitians
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$izin_penelitian = $this->model_izin_penelitian->find($id);

		
		
		return $this->model_izin_penelitian->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('izin_penelitian_export');

		$this->model_izin_penelitian->export('izin_penelitian', 'izin_penelitian');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('izin_penelitian_export');

		$this->model_izin_penelitian->pdf('izin_penelitian', 'izin_penelitian');
	}
}


/* End of file izin_penelitian.php */
/* Location: ./application/controllers/Izin Penelitian.php */