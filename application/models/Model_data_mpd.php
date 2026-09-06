<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_data_mpd extends MY_Model
{
	private $primary_key = 'id_mpd';
	private $table_name = 'data_mpd';

	public function __construct()
	{
		parent::__construct(array(
			'primary_key' => $this->primary_key,
			'table_name' => $this->table_name,
			'field_search' => array('nama_mpd', 'jabatan', 'email', 'no_telepon', 'nomor_sk'),
		));
		$this->load->library('aauth');
		$this->load->model('model_user');
	}

	public function get($q = '', $limit = 25, $offset = 0)
	{
		$this->select_details();
		$this->apply_access_scope();
		$this->apply_search($q);
		return $this->db
			->limit((int) $limit, (int) $offset)
			->order_by('data_mpd.nama_mpd', 'ASC')
			->get('data_mpd')
			->result();
	}

	public function count_all($q = '')
	{
		$this->db->from('data_mpd');
		$this->apply_access_scope();
		$this->apply_search($q);
		return (int) $this->db->count_all_results();
	}

	public function find_accessible($id)
	{
		$this->select_details();
		$this->apply_access_scope();
		return $this->db
			->where('data_mpd.id_mpd', (int) $id)
			->get('data_mpd')
			->row();
	}

	/** Return the official MPD registry record linked to one account. */
	public function find_for_user($user_id)
	{
		$user_id = (int) $user_id;
		if ($user_id <= 0) return false;
		$this->select_details();
		return $this->db
			->where('data_mpd.user_id', $user_id)
			->limit(1)
			->get('data_mpd')
			->row();
	}

	/** Synchronize editable account identity without changing official scope/SK. */
	public function sync_account_profile($user_id, array $account_data)
	{
		$user_id = (int) $user_id;
		if (!$this->account_is_mpd($user_id) || !$this->find_for_user($user_id)) return false;

		return (bool) $this->db->where('user_id', $user_id)->update('data_mpd', array(
			'nama_mpd' => format_person_name($account_data['full_name'] ?? ''),
			'email' => trim((string) ($account_data['email'] ?? '')),
			'no_telepon' => format_phone_number($account_data['phone_number'] ?? ''),
			'updated_at' => date('Y-m-d H:i:s'),
		));
	}

	public function get_region_codes($user_id)
	{
		return $this->model_user->get_mpd_regions((int) $user_id);
	}

	public function get_regions()
	{
		return $this->db
			->select('kd_wilayah, nama')
			->where('kd_wilayah !=', '')
			->order_by('nama', 'ASC')
			->get('wilayah')
			->result();
	}

	public function get_available_accounts($current_user_id = null)
	{
		$this->db
			->distinct()
			->select('users.id, users.username, users.full_name, users.email, users.banned')
			->from('aauth_users users')
			->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
			->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
			->join('data_mpd profiles', 'profiles.user_id = users.id', 'left')
			->where('groups_table.name', 'MPD')
			->group_start()
				->where('profiles.id_mpd IS NULL', null, false);
		if ($current_user_id) {
			$this->db->or_where('users.id', (int) $current_user_id);
		}
		return $this->db
			->group_end()
			->order_by('users.full_name', 'ASC')
			->get()
			->result();
	}

	public function account_is_mpd($user_id)
	{
		if ((int) $user_id <= 0) return false;
		return $this->db
			->from('aauth_user_to_group memberships')
			->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
			->where('memberships.user_id', (int) $user_id)
			->where('groups_table.name', 'MPD')
			->count_all_results() > 0;
	}

	public function save_registry(array $data, array $region_codes, $id = null)
	{
		$user_id = isset($data['user_id']) ? (int) $data['user_id'] : 0;
		if (!$this->account_is_mpd($user_id) || !$this->model_user->validate_mpd_regions($region_codes)) {
			return false;
		}

		$this->db->trans_start();
		$existing = $id ? $this->db->where('id_mpd', (int) $id)->get('data_mpd')->row() : null;
		$duplicate = $this->db->where('user_id', $user_id);
		if ($id) $duplicate->where('id_mpd !=', (int) $id);
		if ($duplicate->count_all_results('data_mpd')) {
			$this->db->trans_complete();
			return false;
		}

		$now = date('Y-m-d H:i:s');
		if ($id) {
			$data['updated_at'] = $now;
			$this->db->where('id_mpd', (int) $id)->update('data_mpd', $data);
		} else {
			$data['created_at'] = $now;
			$this->db->insert('data_mpd', $data);
			$id = $this->db->insert_id();
		}

		if ($existing && (int) $existing->user_id !== $user_id) {
			$this->db->where('user_id', (int) $existing->user_id)->delete('mpd_wilayah');
			$this->db->where('id', (int) $existing->user_id)->update('aauth_users', array('banned' => 1));
		}

		$this->model_user->sync_mpd_regions($user_id, $region_codes);
		$user_data = array(
			'full_name' => $data['nama_mpd'],
			'phone_number' => $data['no_telepon'],
		);
		if (trim((string) $data['email']) !== '') {
			$user_data['email'] = $data['email'];
		}
		if (empty($data['is_verified'])) {
			$user_data['banned'] = 1;
		}
		$this->db->where('id', $user_id)->update('aauth_users', $user_data);

		$this->db->trans_complete();
		return $this->db->trans_status() ? (int) $id : false;
	}

	public function remove_registry($id)
	{
		$profile = $this->db->where('id_mpd', (int) $id)->get('data_mpd')->row();
		if (!$profile) return false;

		$this->db->trans_start();
		if ((int) $profile->user_id > 0) {
			$this->db->where('user_id', (int) $profile->user_id)->delete('mpd_wilayah');
			$this->db->where('id', (int) $profile->user_id)->update('aauth_users', array('banned' => 1));
		}
		$this->db->where('id_mpd', (int) $id)->delete('data_mpd');
		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	private function select_details()
	{
		$this->db
			->select('data_mpd.*, users.username, users.avatar, users.banned')
			->select("(SELECT GROUP_CONCAT(regions.nama ORDER BY regions.nama SEPARATOR ', ') FROM mpd_wilayah links INNER JOIN wilayah regions ON regions.kd_wilayah = links.kode_wilayah WHERE links.user_id = data_mpd.user_id) AS wilayah_nama", false)
			->select("(SELECT GROUP_CONCAT(links.kode_wilayah ORDER BY links.kode_wilayah SEPARATOR ', ') FROM mpd_wilayah links WHERE links.user_id = data_mpd.user_id) AS wilayah_kode", false)
			->join('aauth_users users', 'users.id = data_mpd.user_id', 'left');
	}

	private function apply_search($q)
	{
		$q = trim((string) $q);
		if ($q === '') return;
		$this->db->group_start()
			->like('data_mpd.nama_mpd', $q)
			->or_like('data_mpd.jabatan', $q)
			->or_like('data_mpd.email', $q)
			->or_like('data_mpd.no_telepon', $q)
			->or_like('data_mpd.nomor_sk', $q)
			->or_where("EXISTS (SELECT 1 FROM mpd_wilayah search_links INNER JOIN wilayah search_regions ON search_regions.kd_wilayah = search_links.kode_wilayah WHERE search_links.user_id = data_mpd.user_id AND search_regions.nama LIKE " . $this->db->escape('%' . $q . '%') . ')', null, false)
			->group_end();
	}

	private function apply_access_scope()
	{
		$groups = $this->aauth->get_user_groups();
		$names = array_map(function ($group) { return (string) $group->name; }, $groups);
		if (in_array('Admin', $names, true) || in_array('Kanwil', $names, true)) return;
		if (in_array('MPD', $names, true)) {
			$this->db->where('data_mpd.user_id', (int) $this->session->userdata('id'));
			return;
		}
		$this->db->where('1 = 0', null, false);
	}
}
