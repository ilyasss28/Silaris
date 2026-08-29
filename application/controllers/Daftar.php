<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar extends CI_Controller {

	public function region($slug = null)
	{
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->get_notaris_by_region($slug);
		$data['area'] = $this->Model_home->get_wilayah();

		$this->load->view('include/head');
		$this->load->view('include/header');
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

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
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->get_db();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function kendari()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->kendari();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function baubau()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->baubau();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function wakatobi()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->wakatobi();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function muna()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->muna();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function mubar()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->mubar();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function konut()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->konut();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function konsel()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->konsel();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function konawe()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->konawe();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function kolut()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->kolut();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function koltim()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->koltim();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function kolaka()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->kolaka();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function buton()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->buton();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function butur()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->butur();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function buteng()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->buteng();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function busel()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->busel();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function bombana()
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->bombana();
        $data['area'] = $this->Model_home->get_wilayah();
		$this->load->view('daftar', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	
}
