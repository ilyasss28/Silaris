<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
*| --------------------------------------------------------------------------
*| Blog Controller
*| --------------------------------------------------------------------------
*| For default controller
*|
*/
class Home extends Front
{
	
	public function __construct()
	{
		parent::__construct();
	}

    public function index() 
    {
        $query=$this->db->query('select * from wilayah_profil');
        echo json_encode($query->result());
    }


    /**
    * Register unparse HTML
    *
    * @var Object $page
    */
    private function register_unparse_html($page) {

    }
}


/* End of file Blog.php */
/* Location: ./application/controllers/Blog.php */