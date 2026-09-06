<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| File Controller
*| --------------------------------------------------------------------------
*| user site
*|
*/
class File extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_user');
	}

	/**
	* download file
	*
	* @var $file_path String
	* @var $file_name String
	*/
	public function download($file_path = null, $file_name = null)
	{
		$directory = basename(trim((string) $file_path));
		if ($directory === '' || trim((string) $file_name) === '') {
			show_404();
		}

		$this->serve_document('uploads/' . $directory, $file_name, true);
	}
}
