<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepatuhan extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		$this->load->model('Model_kepatuhan');

		$q = $this->input->get('q');
		$data['q'] = $q;
		$data['notaris'] = $this->Model_kepatuhan->get_compliance($q);
		$data['summary'] = $this->Model_kepatuhan->get_compliance_summary();

		$this->load->view('kepatuhan', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');

	}

	
}
