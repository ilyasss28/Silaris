<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Daftar extends CI_Controller
{
    public function index()
    {
        $this->render_directory();
    }

    public function region($slug = null)
    {
        $this->render_directory($slug);
    }

    private function render_directory($slug = null)
    {
        $this->load->model('Model_home');
        $data = array(
            'notaris' => $slug === null
                ? $this->Model_home->get_db()
                : $this->Model_home->get_notaris_by_region($slug),
            'area' => $this->Model_home->get_wilayah(),
        );

        $this->load->view('include/head');
        $this->load->view('include/header');
        $this->load->view('daftar', $data);
        $this->load->view('include/footer');
        $this->load->view('include/js');
    }
}
