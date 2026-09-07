<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Model_user extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'aauth_users';
	private $field_search 	= array('id', 'email', 'username', 'full_name', 'phone_number');
	private $filterable_groups = array('Admin', 'User', 'Kanwil', 'MPD');
	private $filterable_statuses = array('active', 'inactive', 'pending');

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = '', $field = '', $group = '', $status = '')
	{
		$this->db->select('COUNT(DISTINCT aauth_users.id) AS total', false);
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
		$this->apply_group_filter($group);
		$this->apply_status_filter($status);
		$query = $this->db->get($this->table_name)->row();

		return $query ? (int) $query->total : 0;
	}

	public function get($q = '', $field = '', $limit = 0, $offset = 0, $group = '', $status = '')
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
		$this->apply_status_filter($status);
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

	public function normalize_status_filter($status)
	{
		$status = strtolower(trim((string) $status));

		return in_array($status, $this->filterable_statuses, true) ? $status : '';
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

	private function apply_status_filter($status)
	{
		$status = $this->normalize_status_filter($status);
		if ($status === '') {
			return;
		}

		if ($status === 'pending' && $this->db->field_exists('is_verified', $this->table_name)) {
			$this->db->where($this->table_name . '.is_verified', 0);
			return;
		}

		$this->db->where($this->table_name . '.banned', $status === 'inactive' ? 1 : 0);
		if ($this->db->field_exists('is_verified', $this->table_name)) {
			$this->db->where($this->table_name . '.is_verified', 1);
		}
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

	public function detach_mpd_registry($user_id)
	{
		$user_id = (int) $user_id;
		if ($user_id <= 0) {
			return false;
		}

		$this->db->trans_start();
		if ($this->db->table_exists('mpd_wilayah')) {
			$this->db->where('user_id', $user_id)->delete('mpd_wilayah');
		}
		if ($this->db->table_exists('data_mpd')) {
			$this->db->where('user_id', $user_id)->update('data_mpd', array(
				'user_id' => null,
				'is_verified' => 0,
				'updated_at' => date('Y-m-d H:i:s'),
			));
		}
		$this->db->trans_complete();

		return $this->db->trans_status();
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
	 * Audit accounts whose sole application role is User/Notaris against the
	 * authoritative Data Notaris roster. Privileged accounts are never included.
	 */
	public function audit_notary_roster($user_id = null)
	{
		if (!$this->db->table_exists('data_notaris')) {
			return array();
		}

		$roster = $this->notary_roster_index();
		$this->db
			->distinct()
			->select('users.id, users.username, users.email, users.full_name, users.banned')
			->from('aauth_users users')
			->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
			->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
			->where(
				"NOT EXISTS (SELECT 1 FROM aauth_user_to_group privileged_links "
				. "INNER JOIN aauth_groups privileged_groups ON privileged_groups.id = privileged_links.group_id "
				. "WHERE privileged_links.user_id = users.id "
				. "AND privileged_groups.name IN ('Admin', 'Kanwil', 'MPD', 'Pimpinan'))",
				null,
				false
			);
		if ($user_id !== null) {
			$this->db->where('users.id', (int) $user_id);
		}

		$accounts = $this->db->get()->result();
		$audit = array();
		foreach ($accounts as $account) {
			$audit[] = array(
				'user_id' => (int) $account->id,
				'username' => (string) $account->username,
				'full_name' => (string) $account->full_name,
				'listed' => $this->notary_account_is_listed($account, $roster),
				'banned' => (int) $account->banned === 1,
			);
		}

		return $audit;
	}

	public function enforce_notary_roster($user_id)
	{
		$audit = $this->audit_notary_roster((int) $user_id);
		if (!$audit) {
			return array('is_notary' => false, 'listed' => true, 'deactivated' => false);
		}

		$status = $audit[0];
		$status['is_notary'] = true;
		$status['deactivated'] = false;
		if (!$status['listed'] && !$status['banned']) {
			$status['deactivated'] = (bool) $this->db
				->where('id', (int) $status['user_id'])
				->update('aauth_users', array('banned' => 1));
			$status['banned'] = $status['deactivated'];
		}

		return $status;
	}

	public function enforce_notary_roster_by_identifier($identifier)
	{
		$identifier = trim((string) $identifier);
		if ($identifier === '') {
			return array('is_notary' => false, 'listed' => true, 'deactivated' => false);
		}

		$user = $this->db
			->group_start()
				->where('username', $identifier)
				->or_where('email', $identifier)
			->group_end()
			->get('aauth_users')
			->row();

		return $user
			? $this->enforce_notary_roster((int) $user->id)
			: array('is_notary' => false, 'listed' => true, 'deactivated' => false);
	}

	public function sync_missing_notary_accounts()
	{
		$audit = $this->audit_notary_roster();
		$deactivate_ids = array();
		foreach ($audit as $status) {
			if (!$status['listed'] && !$status['banned']) {
				$deactivate_ids[] = (int) $status['user_id'];
			}
		}

		if (!$deactivate_ids) {
			return 0;
		}

		$this->db->where_in('id', $deactivate_ids)->update('aauth_users', array('banned' => 1));
		return $this->db->affected_rows();
	}

	public function audit_mpd_registry($user_id = null)
	{
		if (!$this->db->table_exists('data_mpd')) return array();

		$this->db
			->distinct()
			->select('users.id, users.username, users.full_name, users.banned, profiles.id_mpd, profiles.is_verified')
			->from('aauth_users users')
			->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
			->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'MPD'")
			->join('data_mpd profiles', 'profiles.user_id = users.id', 'left')
			->where(
				"NOT EXISTS (SELECT 1 FROM aauth_user_to_group privileged_links "
				. "INNER JOIN aauth_groups privileged_groups ON privileged_groups.id = privileged_links.group_id "
				. "WHERE privileged_links.user_id = users.id AND privileged_groups.name IN ('Admin', 'Kanwil'))",
				null,
				false
			);
		if ($user_id !== null) $this->db->where('users.id', (int) $user_id);

		$audit = array();
		foreach ($this->db->get()->result() as $account) {
			$audit[] = array(
				'user_id' => (int) $account->id,
				'username' => (string) $account->username,
				'full_name' => (string) $account->full_name,
				'is_mpd' => true,
				'listed' => !empty($account->id_mpd),
				'verified' => !empty($account->is_verified),
				'eligible' => !empty($account->id_mpd) && !empty($account->is_verified),
				'banned' => (int) $account->banned === 1,
			);
		}
		return $audit;
	}

	public function enforce_mpd_registry($user_id)
	{
		$audit = $this->audit_mpd_registry((int) $user_id);
		if (!$audit) return array('is_mpd' => false, 'listed' => true, 'verified' => true, 'eligible' => true, 'deactivated' => false);

		$status = $audit[0];
		$status['deactivated'] = false;
		if (!$status['eligible'] && !$status['banned']) {
			$status['deactivated'] = (bool) $this->db->where('id', (int) $status['user_id'])->update('aauth_users', array('banned' => 1));
			$status['banned'] = $status['deactivated'];
		}
		return $status;
	}

	public function enforce_mpd_registry_by_identifier($identifier)
	{
		$identifier = trim((string) $identifier);
		if ($identifier === '') return array('is_mpd' => false, 'eligible' => true);
		$user = $this->db->group_start()->where('username', $identifier)->or_where('email', $identifier)->group_end()->get('aauth_users')->row();
		return $user ? $this->enforce_mpd_registry((int) $user->id) : array('is_mpd' => false, 'eligible' => true);
	}

	public function sync_ineligible_mpd_accounts()
	{
		$ids = array();
		foreach ($this->audit_mpd_registry() as $status) {
			if (!$status['eligible'] && !$status['banned']) $ids[] = (int) $status['user_id'];
		}
		if (!$ids) return 0;
		$this->db->where_in('id', $ids)->update('aauth_users', array('banned' => 1));
		return $this->db->affected_rows();
	}

	/**
	 * Mark User/Notaris accounts that are not present in Data Notaris so their
	 * activation status can be rendered as read-only in the user list.
	 */
	public function attach_notary_roster_status(array $users)
	{
		$locked_user_ids = array();
		$locked_reasons = array();
		foreach ($this->audit_notary_roster() as $status) {
			if (!$status['listed']) {
				$locked_user_ids[(int) $status['user_id']] = true;
				$locked_reasons[(int) $status['user_id']] = 'Notaris belum terdaftar pada Data Notaris.';
			}
		}
		foreach ($this->audit_mpd_registry() as $status) {
			if (!$status['eligible']) {
				$locked_user_ids[(int) $status['user_id']] = true;
				$locked_reasons[(int) $status['user_id']] = $status['listed']
					? 'Data MPD belum diverifikasi.'
					: 'MPD belum terdaftar pada Data MPD.';
			}
		}

		foreach ($users as $user) {
			$user->notary_roster_locked = isset($locked_user_ids[(int) $user->id]);
			$user->roster_lock_reason = isset($locked_reasons[(int) $user->id]) ? $locked_reasons[(int) $user->id] : '';
		}

		return $users;
	}

	private function notary_roster_index()
	{
		$profile_fields = 'email, nama_notaris';
		if ($this->db->field_exists('user_id', 'data_notaris')) $profile_fields .= ', user_id';
		$profiles = $this->db
			->select($profile_fields)
			->get('data_notaris')
			->result();
		$index = array('user_ids' => array(), 'emails' => array(), 'names' => array(), 'initial_names' => array());
		foreach ($profiles as $profile) {
			$user_id = isset($profile->user_id) ? (int) $profile->user_id : 0;
			$email_key = strtolower(trim((string) $profile->email));
			$name_key = person_name_identity_key($profile->nama_notaris);
			$initial_name_key = person_name_initial_key($profile->nama_notaris);
			if ($user_id > 0) {
				$index['user_ids'][$user_id] = true;
			}
			if ($email_key !== '') {
				$index['emails'][$email_key] = true;
			}
			if ($name_key !== '') {
				$index['names'][$name_key] = true;
			}
			if ($initial_name_key !== '') {
				$index['initial_names'][$initial_name_key] = isset($index['initial_names'][$initial_name_key])
					? $index['initial_names'][$initial_name_key] + 1
					: 1;
			}
		}

		return $index;
	}

	private function notary_account_is_listed($account, array $roster)
	{
		if (isset($roster['user_ids'][(int) $account->id])) {
			return true;
		}

		$email_key = strtolower(trim((string) $account->email));
		if ($email_key !== '' && isset($roster['emails'][$email_key])) {
			return true;
		}

		$name_key = person_name_identity_key($account->full_name);
		if ($name_key !== '' && isset($roster['names'][$name_key])) {
			return true;
		}

		$initial_name_key = person_name_initial_key($account->full_name);
		return $initial_name_key !== ''
			&& isset($roster['initial_names'][$initial_name_key])
			&& $roster['initial_names'][$initial_name_key] === 1;
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
