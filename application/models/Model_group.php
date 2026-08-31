<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_group extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_groups';
	private $field_search 	= array('name', 'definition');
	private $application_groups = array('Admin', 'User', 'Kanwil', 'MPD', 'Pimpinan');

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = '', $field = '')
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	            } else if ($iterasi == $num) {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

		$this->db->where($where);
		$this->db->where_in('name', $this->application_groups);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = '', $field = '', $limit = 0, $offset = 0)
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "(" . $field . " LIKE '%" . $q . "%' ";
	            } else if ($iterasi == $num) {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%') ";
	            } else {
	                $where .= "OR " . $field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }
        } else {
        	$where .= "(" . $field . " LIKE '%" . $q . "%' )";
        }

		$this->db->where($where);
		$this->db->where_in('name', $this->application_groups);
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		$groups = $query->result();
		$order = array_flip($this->application_groups);
		usort($groups, function ($left, $right) use ($order) {
			return ($order[$left->name] ?? PHP_INT_MAX) <=> ($order[$right->name] ?? PHP_INT_MAX);
		});

		return $groups;
	}

	public function get_application_group_names()
	{
		return $this->application_groups;
	}

	public function is_application_group_name($name)
	{
		return in_array(trim((string) $name), $this->application_groups, true);
	}

	public function find_application_group($id)
	{
		$group = parent::find((int) $id);

		return $group && $this->is_application_group_name($group->name) ? $group : false;
	}

	public function get_permission_group($group_id = false)
	{
		if ($group_id === false) {
			$group_id = get_user_data('id');
		}
		$result_perm_group[] = 0;

		$query = $this->db->get_where('aauth_perm_to_group', ['group_id' => $group_id]);

		foreach ($query->result() as $row) {
			$result_perm_group[] = $row->perm_id;
		}

		return $result_perm_group;
	}

}

/* End of file Model_group.php */
/* Location: ./application/models/Model_group.php */
