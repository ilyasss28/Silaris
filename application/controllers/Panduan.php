<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Panduan extends CI_Controller {

	public function index()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		$this->load->view('panduan');
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

}
