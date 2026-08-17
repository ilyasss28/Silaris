<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Reportorium Controller
*| --------------------------------------------------------------------------
*| Rekap Reportorium site
*|
*/
class Rekap_reportorium extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_reportorium');
	}

	/**
	* show all Rekap Reportoriums
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_reportorium_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['rekap_reportoriums'] = $this->model_rekap_reportorium->get($filter, $field, $this->limit_page, $offset);
		$this->data['rekap_reportorium_counts'] = $this->model_rekap_reportorium->count_all($filter, $field);

		$config = [
			'base_url'     => 'rekap_reportorium/index/',
			'total_rows'   => $this->model_rekap_reportorium->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Rekap Reportorium List');
		$this->render('modul/rekap_reportorium/rekap_reportorium_list', $this->data);
	}
	
	
	
	/**
	* delete Rekap Reportoriums
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_reportorium_delete');

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
            set_message(cclang('has_been_deleted', 'rekap_reportorium'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_reportorium'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Reportoriums
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_reportorium_view');

		$this->data['rekap_reportorium'] = $this->model_rekap_reportorium->join_avaiable()->filter_avaiable()->find($id);

		$this->template->title('Rekap Reportorium Detail');
		$this->render('modul/rekap_reportorium/rekap_reportorium_view', $this->data);
	}
	
	/**
	* delete Rekap Reportoriums
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_reportorium = $this->model_rekap_reportorium->find($id);

		
		
		return $this->model_rekap_reportorium->remove($id);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_reportorium_export');

		$this->model_rekap_reportorium->export('rekap_reportorium', 'rekap_reportorium');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_reportorium_export');

		$this->model_rekap_reportorium->pdf('rekap_reportorium', 'rekap_reportorium');
	}
}


/* End of file rekap_reportorium.php */
/* Location: ./application/controllers/Rekap Reportorium.php */