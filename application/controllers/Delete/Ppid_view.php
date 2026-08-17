<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Ppid_view extends CI_Controller {
public function index(){
		$this->load->view('frontend/include/head');
        $this->load->view('frontend/include/navigation');
		$this->load->model('frontend_model/Model_ppid_view');
		$data['data'] = $this->Model_ppid_view->get_db();
		$this->load->view('frontend/ppid/ppid_view', $data);
		$this->load->view('frontend/include/footer');
		$this->load->view('frontend/include/jquery');
	}
public function sertamerta(){
		$this->load->view('frontend/include/head');
		$this->load->view('frontend/include/navigation');
		$this->load->model('frontend_model/Model_ppid_view');
		$data['data'] = $this->Model_ppid_view->get_sertamerta();
		$this->load->view('frontend/ppid/ppid_view', $data);
		$this->load->view('frontend/include/footer');
		$this->load->view('frontend/include/jquery');
	}
	  
public function setiapsaat(){
		$this->load->view('frontend/include/head');
		$this->load->view('frontend/include/navigation');
		$this->load->model('frontend_model/Model_ppid_view');
		$data['data'] = $this->Model_ppid_view->get_setiapsaat();
		$this->load->view('frontend/ppid/ppid_view', $data);
		$this->load->view('frontend/include/footer');
		$this->load->view('frontend/include/jquery');
	}

public function berkala(){
		$this->load->view('frontend/include/head');
		$this->load->view('frontend/include/navigation');
		$this->load->model('frontend_model/Model_ppid_view');
		$data['data'] = $this->Model_ppid_view->get_berkala();
		$this->load->view('frontend/ppid/ppid_view', $data);
        $this->load->view('frontend/include/footer');
		$this->load->view('frontend/include/jquery');
	}
	

}