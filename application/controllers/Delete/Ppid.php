<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ppid extends CI_Controller {
	
	public function index()
	{
		$this->load->view('frontend/ppid');
	}

}
