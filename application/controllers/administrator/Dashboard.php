<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Dashboard Controller
*| --------------------------------------------------------------------------
*| For see your board
*|
*/
class Dashboard extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_dashboard');
	}

	public function index()
	{
		$this->require_authenticated_user();

		$user_id = (int) $this->session->userdata('id');
		$groups = $this->aauth->get_user_groups($user_id);
		$group_names = array_map(function ($group) {
			return $group->name;
		}, $groups);
		$role = $this->model_dashboard->resolve_role($group_names);
		$data = $this->model_dashboard->build($user_id, $role);

		$this->render('backend/standart/dashboard', $data);
	}

	public function chart()
	{
		$this->require_authenticated_user();

		$data = [];
		$this->render('backend/standart/chart', $data);
	}

	/**
	 * Dashboard is the safe landing page used after login and after a denied
	 * permission. Requiring the dashboard permission here would redirect a
	 * denied user back to this same URL forever.
	 */
	private function require_authenticated_user()
	{
		if (!$this->aauth->is_loggedin()) {
			redirect('login');
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */
