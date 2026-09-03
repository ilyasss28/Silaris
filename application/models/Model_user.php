<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_user extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_users';
	private $field_search 	= array('id', 'email', 'username', 'full_name');
	private $filterable_groups = array('User', 'Kanwil', 'MPD');

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
				ORDER BY FIELD(user_groups.name, 'Admin', 'User', 'Kanwil', 'MPD')
				SEPARATOR ', ')
			FROM aauth_user_to_group AS user_group_links
			INNER JOIN aauth_groups AS user_groups ON user_groups.id = user_group_links.group_id
			WHERE user_group_links.user_id = aauth_users.id
			AND user_groups.name IN ('Admin', 'User', 'Kanwil', 'MPD')
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

	public function get_mpd_regions($user_id)
	{
		if (!$this->db->table_exists('mpd_wilayah')) {
			return array();
		}

		$rows = $this->db
			->select('kode_wilayah')
			->where('user_id', (int) $user_id)
			->order_by('kode_wilayah', 'ASC')
			->get('mpd_wilayah')
			->result();

		return array_map(function ($row) {
			return (string) $row->kode_wilayah;
		}, $rows);
	}

	public function get_mpd_region_names($user_id)
	{
		if (!$this->db->table_exists('mpd_wilayah')) {
			return array();
		}

		$rows = $this->db
			->select('wilayah.nama')
			->from('mpd_wilayah')
			->join('wilayah', 'wilayah.kd_wilayah = mpd_wilayah.kode_wilayah', 'inner')
			->where('mpd_wilayah.user_id', (int) $user_id)
			->order_by('wilayah.nama', 'ASC')
			->get()
			->result();

		return array_map(function ($row) {
			return (string) $row->nama;
		}, $rows);
	}

	public function sync_mpd_regions($user_id, array $region_codes)
	{
		if (!$this->db->table_exists('mpd_wilayah')) {
			return false;
		}

		$region_codes = array_values(array_unique(array_filter(array_map('trim', $region_codes))));
		$valid_codes = array();
		if ($region_codes) {
			$regions = $this->db
				->select('kd_wilayah')
				->where_in('kd_wilayah', $region_codes)
				->get('wilayah')
				->result();
			foreach ($regions as $region) {
				if ((string) $region->kd_wilayah !== '') {
					$valid_codes[] = (string) $region->kd_wilayah;
				}
			}
		}

		$this->db->where('user_id', (int) $user_id)->delete('mpd_wilayah');
		foreach ($valid_codes as $region_code) {
			$this->db->insert('mpd_wilayah', array(
				'user_id' => (int) $user_id,
				'kode_wilayah' => $region_code,
				'created_at' => date('Y-m-d H:i:s'),
			));
		}

		return true;
	}

	public function validate_mpd_regions(array $region_codes)
	{
		if (!$this->db->table_exists('mpd_wilayah')) {
			return false;
		}

		$region_codes = array_values(array_unique(array_filter(array_map('trim', $region_codes))));
		if (!$region_codes) {
			return false;
		}

		$valid_count = $this->db
			->where('kd_wilayah !=', '')
			->where_in('kd_wilayah', $region_codes)
			->count_all_results('wilayah');

		return $valid_count === count($region_codes);
	}

	public function group_ids_include($group_ids, $group_name)
	{
		$group_ids = array_values(array_filter(array_map('intval', (array) $group_ids)));
		if (!$group_ids) {
			return false;
		}

		return $this->db
			->where_in('id', $group_ids)
			->where('name', (string) $group_name)
			->count_all_results('aauth_groups') > 0;
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
