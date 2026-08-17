<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends CI_Controller {

	public function __construct() {
		parent::__construct();

	$this->load->view('include/head');
	$this->load->view('include/header');
	// $this->load->view('include/slider');
	$this->load->model('Model_home');

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
	public function index($id_notaris)
	{
		$this->load->view('include/head');
		$this->load->view('include/header');
		// $this->load->view('include/slider');
		$this->load->model('Model_home');
		$data['notaris'] = $this->Model_home->get_db();
		$this->load->view('detail', $data);

		// var_dump($data);
		// exit ;

		$this->load->view('include/footer');
		$this->load->view('include/js');
	}

	public function detail ()
	{
		$id_notaris = $this->uri->segment(3);
		$data['area'] = $this->Model_home->get_wilayah();
		$detail = $this->Model_home->get_where('data_notaris', array('id_notaris'=>$id_notaris));


		foreach ($detail->result() as $key) {
			$data['id_notaris']=$key->id_notaris;
			$data['nama_notaris']=$key->nama_notaris;
			$data['wilayah']=$key->wilayah;
			$data['jenis_kelamin']=$key->jenis_kelamin;
			$data['no_telepon']=$key->no_telepon;
			$data['email']=$key->email;
			$data['foto']=$key->foto;
			$data['alamat_kantor']=$key->alamat_kantor;
			$data['lat']=$key->lat;
		}
		$this->load->view('detail', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}
}
