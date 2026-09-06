<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Link each Data Notaris row to its SILARIS User account. The account name is
 * authoritative once linked, while registry-only Notaries remain supported.
 */
class Migration_link_notary_registry_accounts extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris') || !$this->db->table_exists('aauth_users')) {
            return;
        }

        $this->db->query('ALTER TABLE `data_notaris` MODIFY `nama_notaris` VARCHAR(200) NOT NULL');

        if (!$this->db->field_exists('user_id', 'data_notaris')) {
            $this->dbforge->add_column('data_notaris', array(
                'user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'id_notaris',
                ),
            ));
        }

        $accounts = $this->db
            ->distinct()
            ->select('users.id, users.email, users.phone_number, users.full_name, users.kd_wilayah')
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
            ->where('groups_table.name', 'User')
            ->get()
            ->result();
        $profiles = $this->db
            ->select('id_notaris, user_id, email, no_telepon, nama_notaris, kode_wilayah')
            ->get('data_notaris')
            ->result();

        $assigned_profiles = array();
        $assigned_accounts = array();
        foreach ($profiles as $profile) {
            if ((int) $profile->user_id > 0) {
                $assigned_profiles[(int) $profile->id_notaris] = true;
                $assigned_accounts[(int) $profile->user_id] = true;
            }
        }

        $key_builders = array(
            function ($item, $profile) { return strtolower(trim((string) $item->email)); },
            function ($item, $profile) {
                $name = person_name_identity_key($item->nama_notaris ?? $item->full_name);
                $region = trim((string) ($profile ? $item->kode_wilayah : $item->kd_wilayah));
                return $name !== '' && $region !== '' ? $name.'|'.$region : '';
            },
            function ($item, $profile) { return format_phone_number($profile ? $item->no_telepon : $item->phone_number); },
            function ($item) { return person_name_identity_key($item->nama_notaris ?? $item->full_name); },
            function ($item) { return person_name_initial_key($item->nama_notaris ?? $item->full_name); },
        );

        foreach ($key_builders as $build_key) {
            $profile_map = array();
            $account_map = array();
            foreach ($profiles as $profile) {
                if (isset($assigned_profiles[(int) $profile->id_notaris])) continue;
                $key = $build_key($profile, true);
                if ($key !== '') $profile_map[$key][] = $profile;
            }
            foreach ($accounts as $account) {
                if (isset($assigned_accounts[(int) $account->id])) continue;
                $key = $build_key($account, false);
                if ($key !== '') $account_map[$key][] = $account;
            }
            foreach ($profile_map as $key => $matched_profiles) {
                if (count($matched_profiles) !== 1 || !isset($account_map[$key]) || count($account_map[$key]) !== 1) continue;
                $profile = $matched_profiles[0];
                $account = $account_map[$key][0];
                $this->db->where('id_notaris', (int) $profile->id_notaris)->update('data_notaris', array(
                    'user_id' => (int) $account->id,
                    'nama_notaris' => trim((string) $account->full_name),
                ));
                $assigned_profiles[(int) $profile->id_notaris] = true;
                $assigned_accounts[(int) $account->id] = true;
            }
        }

        $this->db->query(
            'UPDATE `data_notaris` registry INNER JOIN `aauth_users` users ON users.id=registry.user_id '
            ."SET registry.nama_notaris=TRIM(users.full_name) WHERE TRIM(COALESCE(users.full_name,''))<>''"
        );

        // Registry-only records have no account source yet, but still use the
        // same name formatter used when User accounts are saved.
        foreach ($this->db->select('id_notaris, nama_notaris')->where('user_id IS NULL', null, false)->get('data_notaris')->result() as $profile) {
            $formatted_name = format_person_name($profile->nama_notaris);
            if ($formatted_name !== '') {
                $this->db->where('id_notaris', (int) $profile->id_notaris)->update('data_notaris', array(
                    'nama_notaris' => $formatted_name,
                ));
            }
        }

        if (!$this->has_index('data_notaris', 'uq_data_notaris_user')) {
            $this->db->query('ALTER TABLE `data_notaris` ADD UNIQUE INDEX `uq_data_notaris_user` (`user_id`)');
        }
        if (!$this->has_foreign_key('data_notaris', 'fk_data_notaris_user')) {
            $this->db->query(
                'ALTER TABLE `data_notaris` ADD CONSTRAINT `fk_data_notaris_user` '
                .'FOREIGN KEY (`user_id`) REFERENCES `aauth_users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE'
            );
        }
    }

    public function down()
    {
        if (!$this->db->table_exists('data_notaris') || !$this->db->field_exists('user_id', 'data_notaris')) return;
        if ($this->has_foreign_key('data_notaris', 'fk_data_notaris_user')) {
            $this->db->query('ALTER TABLE `data_notaris` DROP FOREIGN KEY `fk_data_notaris_user`');
        }
        if ($this->has_index('data_notaris', 'uq_data_notaris_user')) {
            $this->db->query('ALTER TABLE `data_notaris` DROP INDEX `uq_data_notaris_user`');
        }
        $this->dbforge->drop_column('data_notaris', 'user_id');
    }

    private function has_index($table, $name)
    {
        return $this->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name=?", array($name))->num_rows() > 0;
    }

    private function has_foreign_key($table, $name)
    {
        return $this->db->query(
            'SELECT 1 FROM information_schema.TABLE_CONSTRAINTS '
            .'WHERE CONSTRAINT_SCHEMA=DATABASE() AND TABLE_NAME=? AND CONSTRAINT_NAME=? AND CONSTRAINT_TYPE=\'FOREIGN KEY\'',
            array($table, $name)
        )->num_rows() > 0;
    }
}
