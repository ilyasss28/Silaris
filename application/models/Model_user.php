<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_user extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_users';
	private $field_search 	= array('id', 'email', 'username', 'full_name');
	private $filterable_groups = array('User', 'Kanwil', 'MPD', 'Pimpinan');

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = '', $field = '', $group = '')
	{
		$this->db->select('COUNT(DISTINCT aauth_users.id) AS total', false);
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
		$this->apply_group_filter($group);
		$query = $this->db->get($this->table_name)->row();

		return $query ? (int) $query->total : 0;
	}

	public function get($q = '', $field = '', $limit = 0, $offset = 0, $group = '')
	{
		$this->db->select('aauth_users.*');
		$this->db->select("(
			SELECT GROUP_CONCAT(DISTINCT user_groups.name
				ORDER BY FIELD(user_groups.name, 'Admin', 'User', 'Kanwil', 'MPD', 'Pimpinan')
				SEPARATOR ', ')
			FROM aauth_user_to_group AS user_group_links
			INNER JOIN aauth_groups AS user_groups ON user_groups.id = user_group_links.group_id
			WHERE user_group_links.user_id = aauth_users.id
			AND user_groups.name IN ('Admin', 'User', 'Kanwil', 'MPD', 'Pimpinan')
		) AS group_names", false);
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
		$this->apply_group_filter($group);
        $this->db->limit($limit, $offset);
        $this->db->order_by($this->table_name . '.' . $this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	public function normalize_group_filter($group)
	{
		$group = trim((string) $group);

		return in_array($group, $this->filterable_groups, true) ? $group : '';
	}

	public function get_filterable_groups()
	{
		return $this->filterable_groups;
	}

	private function apply_group_filter($group)
	{
		$group = $this->normalize_group_filter($group);
		if ($group === '') {
			return;
		}

		$escaped_group = $this->db->escape($group);
		$this->db->where(
			'EXISTS (SELECT 1 FROM aauth_user_to_group AS filter_links '
			. 'INNER JOIN aauth_groups AS filter_groups ON filter_groups.id = filter_links.group_id '
			. 'WHERE filter_links.user_id = aauth_users.id AND filter_groups.name = ' . $escaped_group . ')',
			null,
			false
		);
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
