<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Resolve same-name Notaries using their official working-region code. */
class Migration_disambiguate_notaries_by_region extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris') || !$this->db->field_exists('user_id', 'data_notaris')) return;

        $accounts = $this->db
            ->distinct()
            ->select('users.id, users.full_name, users.kd_wilayah')
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
            ->where('groups_table.name', 'User')
            ->get()
            ->result();
        $profiles = $this->db
            ->select('id_notaris, nama_notaris, kode_wilayah')
            ->where('user_id IS NULL', null, false)
            ->get('data_notaris')
            ->result();

        $account_map = array();
        foreach ($accounts as $account) {
            $key = $this->identity_region_key($account->full_name, $account->kd_wilayah);
            if ($key !== '') $account_map[$key][] = $account;
        }
        $profile_map = array();
        foreach ($profiles as $profile) {
            $key = $this->identity_region_key($profile->nama_notaris, $profile->kode_wilayah);
            if ($key !== '') $profile_map[$key][] = $profile;
        }

        foreach ($profile_map as $key => $matched_profiles) {
            if (count($matched_profiles) !== 1 || !isset($account_map[$key]) || count($account_map[$key]) !== 1) continue;
            $profile = $matched_profiles[0];
            $account = $account_map[$key][0];
            $already_linked = $this->db->where('user_id', (int) $account->id)->count_all_results('data_notaris') > 0;
            if ($already_linked) continue;

            $this->db->where('id_notaris', (int) $profile->id_notaris)->update('data_notaris', array(
                'user_id' => (int) $account->id,
                'nama_notaris' => format_person_name($account->full_name),
            ));
        }
    }

    public function down()
    {
        // The links are valid business identities and are intentionally retained.
    }

    private function identity_region_key($name, $region_code)
    {
        $name_key = person_name_identity_key($name);
        $region_code = trim((string) $region_code);
        return $name_key !== '' && $region_code !== '' ? $name_key.'|'.$region_code : '';
    }
}
