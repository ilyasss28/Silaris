<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Detail extends CI_Controller {

	public function __construct() {
		parent::__construct();
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
	public function index($id_notaris = null)
	{
		if ($id_notaris === null) {
			show_404();
		}

		return $this->detail($id_notaris);
	}

	public function detail($id_notaris = null)
	{
		if ($id_notaris === null || !ctype_digit((string) $id_notaris)) {
			show_404();
		}

		$data['area'] = $this->Model_home->get_wilayah();
		$detail = $this->Model_home->get_public_notary($id_notaris);
		if (!$detail) {
			show_404();
		}

		$data['id_notaris']=$detail->id_notaris;
		$data['nama_notaris']=$detail->nama_notaris;
		$data['wilayah']=$detail->wilayah;
		$data['jenis_kelamin']=$detail->jenis_kelamin;
		$data['no_telepon']=$detail->no_telepon;
		$data['email']=$detail->email;
		$data['foto']=$detail->foto;
		$data['alamat_kantor']=$detail->alamat_kantor;
		$data['lat']=$detail->lat;
		$this->load->view('include/head');
		$this->load->view('include/header');
		$this->load->view('detail', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}
}
