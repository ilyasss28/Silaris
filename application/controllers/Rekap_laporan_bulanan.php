<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Laporan Bulanan Controller
*| --------------------------------------------------------------------------
*| Rekap Laporan Bulanan site
*|
*/
class Rekap_laporan_bulanan extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_laporan_bulanan');
	}

	/**
	* show all Rekap Laporan Bulanans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_laporan_bulanan_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_laporan_bulanans'] = $this->model_rekap_laporan_bulanan->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_laporan_bulanan_counts'] = $this->model_rekap_laporan_bulanan->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_laporan_bulanan/index/',
			'total_rows'   => $this->model_rekap_laporan_bulanan->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Laporan Bulanan List');
		$this->render('modul/rekap_laporan_bulanan/rekap_laporan_bulanan_list', $this->data);
	}
	
	
		/**
	* Update view Rekap Laporan Bulanans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('rekap_laporan_bulanan_update');

		$this->data['rekap_laporan_bulanan'] = $this->model_rekap_laporan_bulanan->find($id);

		$this->template->title('Rekap Laporan Bulanan Update');
		$this->render('modul/rekap_laporan_bulanan/rekap_laporan_bulanan_update', $this->data);
	}

	/**
	* Update Rekap Laporan Bulanans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('rekap_laporan_bulanan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		
		$this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tanggal_laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('rekap_laporan_bulanan_file_laporan_name', 'File Laporan', 'trim|required|max_length[1000]');
		
		if ($this->form_validation->run()) {
			$rekap_laporan_bulanan_file_laporan_uuid = $this->input->post('rekap_laporan_bulanan_file_laporan_uuid');
			$rekap_laporan_bulanan_file_laporan_name = $this->input->post('rekap_laporan_bulanan_file_laporan_name');
		
			$save_data = [
				'username' => $this->input->post('username'),
				'tanggal_laporan' => $this->input->post('tanggal_laporan'),
			];

			if (!is_dir(FCPATH . '/uploads/rekap_laporan_bulanan/')) {
				mkdir(FCPATH . '/uploads/rekap_laporan_bulanan/');
			}

			if (!empty($rekap_laporan_bulanan_file_laporan_uuid)) {
				$rekap_laporan_bulanan_file_laporan_name_copy = date('YmdHis') . '-' . $rekap_laporan_bulanan_file_laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $rekap_laporan_bulanan_file_laporan_uuid . '/' . $rekap_laporan_bulanan_file_laporan_name, 
						FCPATH . 'uploads/rekap_laporan_bulanan/' . $rekap_laporan_bulanan_file_laporan_name_copy);

				if (!is_file(FCPATH . '/uploads/rekap_laporan_bulanan/' . $rekap_laporan_bulanan_file_laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['file_laporan'] = $rekap_laporan_bulanan_file_laporan_name_copy;
			}
		
			
			$save_rekap_laporan_bulanan = $this->model_rekap_laporan_bulanan->change($id, $save_data);

			if ($save_rekap_laporan_bulanan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('rekap_laporan_bulanan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_laporan_bulanan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_laporan_bulanan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Rekap Laporan Bulanans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_laporan_bulanan_delete');

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
            set_message(cclang('has_been_deleted', 'rekap_laporan_bulanan'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_laporan_bulanan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Laporan Bulanans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_laporan_bulanan_view');

		$this->data['rekap_laporan_bulanan'] = $this->model_rekap_laporan_bulanan->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Laporan Bulanan Detail');
		$this->render('modul/rekap_laporan_bulanan/rekap_laporan_bulanan_view', $this->data);
	}
	
	/**
	* delete Rekap Laporan Bulanans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_laporan_bulanan = $this->model_rekap_laporan_bulanan->find($id);

		if (!empty($rekap_laporan_bulanan->file_laporan)) {
			$path = FCPATH . '/uploads/rekap_laporan_bulanan/' . $rekap_laporan_bulanan->file_laporan;

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		
		
		return $this->model_rekap_laporan_bulanan->remove($id);
	}
	
	/**
	* Upload Image Rekap Laporan Bulanan	* 
	* @return JSON
	*/
	public function upload_file_laporan_file()
	{
		if (!$this->is_allowed('rekap_laporan_bulanan_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'rekap_laporan_bulanan',
			'allowed_types' => 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png',
			'max_size'      => 10000,
		]);
	}

	/**
	* Delete Image Rekap Laporan Bulanan	* 
	* @return JSON
	*/
	public function delete_file_laporan_file($uuid)
	{
		if (!$this->is_allowed('rekap_laporan_bulanan_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'file_laporan', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => 'rekap_laporan_bulanan',
            'primary_key'       => 'id_laporan_bulanan',
            'upload_path'       => 'uploads/rekap_laporan_bulanan/'
        ]);
	}

	/**
	* Get Image Rekap Laporan Bulanan	* 
	* @return JSON
	*/
	public function get_file_laporan_file($id)
	{
		if (!$this->is_allowed('rekap_laporan_bulanan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$rekap_laporan_bulanan = $this->model_rekap_laporan_bulanan->find($id);

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'file_laporan', 
            'table_name'        => 'rekap_laporan_bulanan',
            'primary_key'       => 'id_laporan_bulanan',
            'upload_path'       => 'uploads/rekap_laporan_bulanan/',
            'delete_endpoint'   => 'rekap_laporan_bulanan/delete_file_laporan_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_laporan_bulanan_export');

		$this->model_rekap_laporan_bulanan->export('rekap_laporan_bulanan', 'rekap_laporan_bulanan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_laporan_bulanan_export');

		$this->model_rekap_laporan_bulanan->pdf('rekap_laporan_bulanan', 'rekap_laporan_bulanan');
	}
}


/* End of file rekap_laporan_bulanan.php */
/* Location: ./application/controllers/Rekap Laporan Bulanan.php */
