<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Waarmerking Controller
*| --------------------------------------------------------------------------
*| Rekap Waarmerking site
*|
*/
class Rekap_waarmerking extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_waarmerking');
	}

	/**
	* show all Rekap Waarmerkings
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_waarmerking_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_waarmerkings'] = $this->model_rekap_waarmerking->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_waarmerking_counts'] = $this->model_rekap_waarmerking->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_waarmerking/index/',
			'total_rows'   => $this->model_rekap_waarmerking->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Waarmerking List');
		$this->render('modul/rekap_waarmerking/rekap_waarmerking_list', $this->data);
	}
	
	
	
	/**
	* delete Rekap Waarmerkings
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_waarmerking_delete');

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
            set_message(cclang('has_been_deleted', 'rekap_waarmerking'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_waarmerking'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Waarmerkings
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_waarmerking_view');

		$this->data['rekap_waarmerking'] = $this->model_rekap_waarmerking->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Waarmerking Detail');
		$this->render('modul/rekap_waarmerking/rekap_waarmerking_view', $this->data);
	}
	
	/**
	* delete Rekap Waarmerkings
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_waarmerking = $this->model_rekap_waarmerking->find($id);

		
		
		return $this->model_rekap_waarmerking->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_waarmerking_export');

		$this->model_rekap_waarmerking->export('rekap_waarmerking', 'rekap_waarmerking');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_waarmerking_export');

		$this->model_rekap_waarmerking->pdf('rekap_waarmerking', 'rekap_waarmerking');
	}
}


/* End of file rekap_waarmerking.php */
/* Location: ./application/controllers/Rekap Waarmerking.php */