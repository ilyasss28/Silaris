<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_data_notaris extends MY_Model {

	private $primary_key 	= 'id_notaris';
	private $table_name 	= 'data_notaris';
	private $field_search = ['nama_notaris', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'wilayah', 'alamat_kantor', 'foto', 'kode_wilayah', 'lat', 'no_telepon', 'long', 'npwp', 'nomor_ktp', 'nomor_bap', 'tanggal_bap', 'pemegang_protokol', 'status_notaris'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "data_notaris.".$field . " LIKE '%" . $q . "%' )";
        }

		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "data_notaris.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('data_notaris.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $this->attach_account_photos($query->result());
	}

	public function find($id = null, $select_field = array())
	{
		$record = parent::find($id, $select_field);
		if (!$record) {
			return false;
		}
		$records = $this->attach_account_photos(array($record));
		return $records[0];
	}

	/**
	 * The account avatar is the authoritative Notary photo. Registry photos are
	 * retained only as a fallback for Notaries that do not yet have a matched
	 * SILARIS account.
	 */
	public function attach_account_photos(array $records)
	{
		if (!$records) {
			return $records;
		}

		$accounts = $this->db
			->distinct()
			->select('users.id, users.email, users.full_name, users.phone_number, users.avatar, users.kd_wilayah')
			->from('aauth_users users')
			->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
			->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
			->where('groups_table.name', 'User')
			->get()
			->result();

		$email_map = array();
		$phone_map = array();
		$name_map = array();
		$initial_map = array();
		$name_region_map = array();
		$id_map = array();
		foreach ($accounts as $account) {
			$id_map[(int) $account->id] = $account;
			$email = strtolower(trim((string) $account->email));
			$phone = format_phone_number($account->phone_number);
			$name = person_name_identity_key($account->full_name);
			$initial = person_name_initial_key($account->full_name);
			$name_region = $name !== '' && trim((string) $account->kd_wilayah) !== ''
				? $name.'|'.trim((string) $account->kd_wilayah)
				: '';
			if ($email !== '') $email_map[$email][] = $account;
			if ($phone !== '') $phone_map[$phone][] = $account;
			if ($name !== '') $name_map[$name][] = $account;
			if ($initial !== '') $initial_map[$initial][] = $account;
			if ($name_region !== '') $name_region_map[$name_region][] = $account;
		}

		foreach ($records as $record) {
			// kode_wilayah adalah identitas relasi; nama wilayah hanya nilai
			// tampilan dan selalu diformat Title Case.
			$record->kode_wilayah = trim((string) ($record->kode_wilayah ?? ''));
			$region_name = trim((string) ($record->region_name ?? $record->wilayah ?? ''));
			$record->wilayah = format_title_case($region_name);
			$account = null;
			$linked_user_id = isset($record->user_id) ? (int) $record->user_id : 0;
			$email = strtolower(trim((string) $record->email));
			$phone = format_phone_number($record->no_telepon);
			$name = person_name_identity_key($record->nama_notaris);
			$initial = person_name_initial_key($record->nama_notaris);
			$name_region = $name !== '' && isset($record->kode_wilayah) && trim((string) $record->kode_wilayah) !== ''
				? $name.'|'.trim((string) $record->kode_wilayah)
				: '';
			if ($linked_user_id > 0 && isset($id_map[$linked_user_id])) {
				$account = $id_map[$linked_user_id];
			} elseif ($email !== '' && isset($email_map[$email]) && count($email_map[$email]) === 1) {
				$account = $email_map[$email][0];
			} elseif ($name_region !== '' && isset($name_region_map[$name_region]) && count($name_region_map[$name_region]) === 1) {
				$account = $name_region_map[$name_region][0];
			} elseif ($phone !== '' && isset($phone_map[$phone]) && count($phone_map[$phone]) === 1) {
				$account = $phone_map[$phone][0];
			} elseif ($name !== '' && isset($name_map[$name]) && count($name_map[$name]) === 1) {
				$account = $name_map[$name][0];
			} elseif ($initial !== '' && isset($initial_map[$initial]) && count($initial_map[$initial]) === 1) {
				$account = $initial_map[$initial][0];
			}

			$record->account_user_id = $account ? (int) $account->id : null;
			$record->account_avatar = $account ? $this->valid_account_avatar($account->avatar) : null;
			$record->photo_url = notary_photo_url($record->account_avatar, $record->foto ?? '');
			$record->account_full_name = $account ? format_person_name($account->full_name) : null;
			if ($record->account_full_name !== '') {
				$record->nama_notaris = $record->account_full_name;
			}
		}

		return $records;
	}

	/**
	 * Resolve the registry row owned by an account without relying on mutable
	 * display names alone. Ambiguous matches are deliberately rejected.
	 */
	public function find_for_user($user)
	{
		if (!$user) {
			return false;
		}

		if ($this->db->field_exists('user_id', $this->table_name)) {
			$linked = $this->db->get_where($this->table_name, array('user_id' => (int) $user->id))->row();
			if ($linked) {
				$linked->account_user_id = (int) $user->id;
				$linked->account_avatar = $this->valid_account_avatar($user->avatar);
				$linked->photo_url = notary_photo_url($linked->account_avatar, $linked->foto ?? '');
				$linked->account_full_name = format_person_name($user->full_name);
				$linked->nama_notaris = $linked->account_full_name;
				return $linked;
			}
		}

		$records = $this->db->get($this->table_name)->result();
		$user_name_key = person_name_identity_key($user->full_name);
		$user_region_code = trim((string) ($user->kd_wilayah ?? ''));
		$checks = array(
			'email' => strtolower(trim((string) $user->email)),
			'name_region' => $user_name_key !== '' && $user_region_code !== '' ? $user_name_key.'|'.$user_region_code : '',
			'phone' => format_phone_number(isset($user->phone_number) ? $user->phone_number : ''),
			'name' => $user_name_key,
			'initial' => person_name_initial_key($user->full_name),
		);

		foreach (array('email', 'name_region', 'phone', 'name', 'initial') as $type) {
			if ($checks[$type] === '') continue;
			$matches = array_filter($records, function ($record) use ($type, $checks) {
				if ($type === 'email') return strtolower(trim((string) $record->email)) === $checks[$type];
				if ($type === 'name_region') {
					$record_key = person_name_identity_key($record->nama_notaris).'|'.trim((string) ($record->kode_wilayah ?? ''));
					return $record_key === $checks[$type];
				}
				if ($type === 'phone') return format_phone_number($record->no_telepon) === $checks[$type];
				if ($type === 'name') return person_name_identity_key($record->nama_notaris) === $checks[$type];
				return person_name_initial_key($record->nama_notaris) === $checks[$type];
			});
			if (count($matches) === 1) {
				$record = reset($matches);
				$record->account_user_id = (int) $user->id;
				$record->account_avatar = $this->valid_account_avatar($user->avatar);
				$record->photo_url = notary_photo_url($record->account_avatar, $record->foto ?? '');
				return $record;
			}
		}

		return false;
	}

	private function valid_account_avatar($avatar)
	{
		$name = basename(trim((string) $avatar));
		if ($name === '' || strtolower($name) === 'default.png') {
			return null;
		}

		return is_file(FCPATH . 'uploads/user/' . $name) ? $name : null;
	}

	/** Link one registry row to an unambiguous User account and copy its name. */
	public function link_registry_to_account($registry_id)
	{
		$registry_id = (int) $registry_id;
		if ($registry_id <= 0 || !$this->db->field_exists('user_id', $this->table_name)) return false;

		$record = $this->db->get_where($this->table_name, array($this->primary_key => $registry_id))->row();
		if (!$record) return false;
		$resolved = $this->attach_account_photos(array($record));
		$record = $resolved[0];
		if (empty($record->account_user_id) || trim((string) $record->account_full_name) === '') return false;

		$duplicate = $this->db
			->where('user_id', (int) $record->account_user_id)
			->where($this->primary_key.' !=', $registry_id)
			->count_all_results($this->table_name) > 0;
		if ($duplicate) return false;

		$this->db->trans_start();
		$this->db->where($this->primary_key, $registry_id)->update($this->table_name, array(
			'user_id' => (int) $record->account_user_id,
			'nama_notaris' => format_person_name($record->account_full_name),
		));
		// Data Notaris is the authoritative source for a Notary's jurisdiction.
		// Keeping the account copy synchronized prevents dashboards and legacy
		// integrations from assigning the account to a different region.
		if (trim((string) $record->kode_wilayah) !== '') {
			$this->db->where('id', (int) $record->account_user_id)->update('aauth_users', array(
				'kd_wilayah' => trim((string) $record->kode_wilayah),
			));
		}
		$this->db->trans_complete();

		return $this->db->trans_status();
	}

	/** Keep the linked registry name identical to the canonical User name. */
	public function sync_account_name($user_id)
	{
		$user_id = (int) $user_id;
		if ($user_id <= 0 || !$this->db->field_exists('user_id', $this->table_name)) return false;
		$user = $this->db
			->select('users.id, users.email, users.phone_number, users.full_name, users.avatar, users.kd_wilayah')
			->from('aauth_users users')
			->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
			->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
			->where('users.id', $user_id)
			->limit(1)
			->get()
			->row();
		if (!$user || trim((string) $user->full_name) === '') return false;
		$formatted_name = format_person_name($user->full_name);

		$linked = $this->db->get_where($this->table_name, array('user_id' => $user_id))->row();
		if (!$linked) {
			$matched = $this->find_for_user($user);
			if ($matched && empty($matched->user_id)) $this->link_registry_to_account((int) $matched->id_notaris);
		}

		$updated = (bool) $this->db->where('user_id', $user_id)->update($this->table_name, array(
			'nama_notaris' => $formatted_name,
		));
		$linked = $this->db->select('kode_wilayah')->get_where($this->table_name, array('user_id' => $user_id))->row();
		if ($linked && trim((string) $linked->kode_wilayah) !== '') {
			$this->db->where('id', $user_id)->update('aauth_users', array(
				'kd_wilayah' => trim((string) $linked->kode_wilayah),
			));
		}
		return $updated;
	}

	/** Return the fields that must be complete before a Notary records a report. */
	public function profile_completeness($profile, $user)
	{
		$values = $profile ? (array) $profile : array();
		$required = array(
			'avatar' => array('label' => 'Foto profil', 'value' => isset($user->avatar) ? $user->avatar : '', 'valid' => function ($v) { return trim((string) $v) !== '' && basename($v) !== 'default.png' && is_file(FCPATH.'uploads/user/'.basename($v)); }),
			'nama_notaris' => array('label' => 'Nama notaris'),
			'tempat_lahir' => array('label' => 'Tempat lahir'),
			'tanggal_lahir' => array('label' => 'Tanggal lahir', 'valid' => function ($v) { $d = DateTime::createFromFormat('!Y-m-d', (string) $v); return $d && $d->format('Y-m-d') === $v && $v <= date('Y-m-d'); }),
			'jenis_kelamin' => array('label' => 'Jenis kelamin', 'valid' => function ($v) { return in_array($v, array('Laki-laki', 'Perempuan'), true); }),
			'email' => array('label' => 'Email', 'valid' => function ($v) { return filter_var($v, FILTER_VALIDATE_EMAIL) !== false; }),
			'kode_wilayah' => array('label' => 'Wilayah kerja', 'valid' => function ($v) { return trim((string) $v) !== '' && $this->db->where('kd_wilayah', (string) $v)->count_all_results('wilayah') === 1; }),
			'surat_keputusan' => array('label' => 'Surat keputusan'),
			'alamat_rumah' => array('label' => 'Alamat rumah'),
			'alamat_kantor' => array('label' => 'Alamat kantor'),
			'lat' => array('label' => 'Latitude kantor', 'valid' => function ($v) { return is_numeric($v) && (float) $v >= -90 && (float) $v <= 90; }),
			'long' => array('label' => 'Longitude kantor', 'valid' => function ($v) { return is_numeric($v) && (float) $v >= -180 && (float) $v <= 180; }),
			'no_telepon' => array('label' => 'Nomor telepon', 'valid' => function ($v) { return preg_match('/^08\d{8,11}$/', format_phone_number($v)) === 1; }),
			'npwp' => array('label' => 'NPWP', 'valid' => function ($v) { return in_array(strlen(preg_replace('/\D+/', '', (string) $v)), array(15, 16), true); }),
			'nomor_ktp' => array('label' => 'NIK', 'valid' => function ($v) { return preg_match('/^\d{16}$/', (string) $v) === 1; }),
			'nomor_bap' => array('label' => 'Nomor BAP'),
			'tanggal_bap' => array('label' => 'Tanggal BAP', 'valid' => function ($v) { $d = DateTime::createFromFormat('!Y-m-d', (string) $v); return $d && $d->format('Y-m-d') === $v && $v <= date('Y-m-d'); }),
			'status_notaris' => array('label' => 'Status notaris', 'valid' => function ($v) { return in_array($v, array('NOTARIS AKTIF', 'NOTARIS NONAKTIF', 'CUTI', 'PINDAH', 'MENINGGAL DUNIA'), true); }),
		);
		$missing = array();
		foreach ($required as $field => $meta) {
			$value = array_key_exists('value', $meta) ? $meta['value'] : (isset($values[$field]) ? $values[$field] : '');
			$valid = isset($meta['valid']) ? (bool) $meta['valid']($value) : trim((string) $value) !== '';
			if (!$valid) $missing[] = $meta['label'];
		}
		$total = count($required);
		$completed = $total - count($missing);
		return array('complete' => !$missing, 'completed' => $completed, 'total' => $total, 'percent' => (int) round(($completed / $total) * 100), 'missing' => $missing);
	}

    public function join_avaiable() {
        $this->db->select('data_notaris.*, wilayah.nama AS region_name, wilayah.kd_wilayah AS region_code');
        $this->db->join('wilayah', 'wilayah.kd_wilayah = data_notaris.kode_wilayah', 'LEFT');
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_data_notaris.php */
/* Location: ./application/models/Model_data_notaris.php */
