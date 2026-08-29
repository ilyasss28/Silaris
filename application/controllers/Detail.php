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
		$detail = $this->Model_home->get_where('data_notaris', array('id_notaris'=>$id_notaris));
		if ($detail->num_rows() === 0) {
			show_404();
		}


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
		$this->load->view('include/head');
		$this->load->view('include/header');
		$this->load->view('detail', $data);
		$this->load->view('include/footer');
		$this->load->view('include/js');
	}
}
