<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_user extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_users';
	private $field_search 	= array('email', 'username', 'full_name');

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
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function get_group_user($user_id = false)
	{
		if ($user_id === false) {
			$user_id = get_user_data('id');
		}
		$result_group_user = [];

		$query = $this->db->get_where('aauth_user_to_group', ['user_id' => $user_id]);
		foreach ($query->result() as $row) {
			$result_group_user[] = $row->group_id;
		}

		return $result_group_user;
	}

	/**
	 * Resolve the stored region code to the human-readable region name.
	 * The `wilayah` table is the same reference used by user add/edit forms.
	 */
	public function get_region_name($region_code = null)
	{
		$region_code = trim((string) $region_code);
		if ($region_code === '') {
			return null;
		}

		$region = null;
		if ($this->db->table_exists('wilayah')) {
			$region = $this->db
				->select('nama')
				->limit(1)
				->get_where('wilayah', ['kd_wilayah' => $region_code])
				->row();
		}

		if ($region && trim((string) $region->nama) !== '') {
			return trim((string) $region->nama);
		}

		// Compatibility fallback for older installations that only populated
		// the generated `wil` reference table.
		if ($this->db->table_exists('wil')) {
			$legacy_region = $this->db
				->select('nama_wilayah')
				->limit(1)
				->get_where('wil', ['kd_wilayah' => $region_code])
				->row();

			if ($legacy_region && trim((string) $legacy_region->nama_wilayah) !== '') {
				return ucwords(strtolower(trim((string) $legacy_region->nama_wilayah)));
			}
		}

		return null;
	}


	public function get_user_oauth($email = null, $provider = null)
	{
		$this->db->where('email', $email);
		$this->db->where('oauth_provider', $provider);
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

}


/* End of file Model_user.php */
/* Location: ./application/models/Model_user.php */
