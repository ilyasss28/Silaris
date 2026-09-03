<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_report_role_scopes extends CI_Migration
{
    public function up()
    {
        $this->create_mpd_regions_table();
        $this->configure_permissions();
        $this->seed_inactive_mpd_accounts();
    }

    public function down()
    {
        // User accounts are retained because administrators may already have
        // replaced the placeholders with valid MPD identities.
        if ($this->db->table_exists('mpd_wilayah')) {
            $this->dbforge->drop_table('mpd_wilayah', true);
        }
    }

    private function create_mpd_regions_table()
    {
        if ($this->db->table_exists('mpd_wilayah')) {
            return;
        }

        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ),
            'user_id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ),
            'kode_wilayah' => array(
                'type' => 'VARCHAR',
                'constraint' => 10,
            ),
            'created_at' => array(
                'type' => 'DATETIME',
                'null' => false,
            ),
        ));
        $this->dbforge->add_key('id', true);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('kode_wilayah');
        $this->dbforge->create_table('mpd_wilayah', true);
        $this->db->query('ALTER TABLE mpd_wilayah ADD UNIQUE KEY uniq_mpd_user_region (user_id, kode_wilayah)');
    }

    private function configure_permissions()
    {
        $read_permissions = array(
            'Admin' => array('menu_laporan', 'menu_laporan_bulanan', 'laporan_list', 'laporan_view', 'menu_rekap', 'rekap_Laporan_list', 'rekap_Laporan_view'),
            'Kanwil' => array('menu_laporan', 'menu_laporan_bulanan', 'laporan_list', 'laporan_view', 'menu_rekap', 'rekap_Laporan_list', 'rekap_Laporan_view'),
            'Pimpinan' => array('menu_laporan', 'menu_laporan_bulanan', 'laporan_list', 'laporan_view', 'menu_rekap', 'rekap_Laporan_list', 'rekap_Laporan_view'),
            'MPD' => array('menu_rekap', 'rekap_Laporan_list', 'rekap_Laporan_view'),
            'User' => array('menu_laporan', 'menu_laporan_bulanan', 'laporan_add', 'laporan_list', 'laporan_update', 'laporan_view', 'laporan_delete'),
        );

        foreach ($read_permissions as $group_name => $permission_names) {
            $group = $this->find_group($group_name);
            if (!$group) {
                continue;
            }
            foreach ($permission_names as $permission_name) {
                $permission = $this->db->where('name', $permission_name)->get('aauth_perms')->row();
                if ($permission) {
                    $this->insert_permission((int) $permission->id, (int) $group->id);
                }
            }
        }

        // MPD is a monitoring role. Mutating a shared report remains an
        // administrative responsibility even when a stale DB granted it.
        $mpd = $this->find_group('MPD');
        if ($mpd) {
            $mutation_names = array('rekap_Laporan_add', 'rekap_Laporan_update', 'rekap_Laporan_delete');
            $mutations = $this->db->where_in('name', $mutation_names)->get('aauth_perms')->result();
            foreach ($mutations as $permission) {
                $this->db->where(array(
                    'group_id' => (int) $mpd->id,
                    'perm_id' => (int) $permission->id,
                ))->delete('aauth_perm_to_group');
            }
        }
    }

    private function seed_inactive_mpd_accounts()
    {
        $mpd_group = $this->find_group('MPD');
        if (!$mpd_group) {
            return;
        }

        $placeholders = array(
            array('username' => 'mpd_kendari', 'name' => 'MPD Kendari (Belum Diverifikasi)', 'region' => '7471'),
            array('username' => 'mpd_baubau', 'name' => 'MPD Baubau (Belum Diverifikasi)', 'region' => '7472'),
            array('username' => 'mpd_konawe', 'name' => 'MPD Konawe (Belum Diverifikasi)', 'region' => '7402'),
        );

        foreach ($placeholders as $placeholder) {
            $user = $this->db->where('username', $placeholder['username'])->get('aauth_users')->row();
            if (!$user) {
                $this->db->insert('aauth_users', array(
                    'email' => $placeholder['username'] . '@invalid.local',
                    'pass' => password_hash(bin2hex(random_bytes(32)), PASSWORD_BCRYPT, array('cost' => 12)),
                    'username' => $placeholder['username'],
                    'full_name' => $placeholder['name'],
                    'avatar' => 'default.png',
                    'banned' => 1,
                    'date_created' => date('Y-m-d H:i:s'),
                    'kd_wilayah' => $placeholder['region'],
                ));
                $user = (object) array('id' => $this->db->insert_id());
            }

            $this->insert_membership((int) $user->id, (int) $mpd_group->id);
            if (!$this->db->where(array(
                'user_id' => (int) $user->id,
                'kode_wilayah' => $placeholder['region'],
            ))->count_all_results('mpd_wilayah')) {
                $this->db->insert('mpd_wilayah', array(
                    'user_id' => (int) $user->id,
                    'kode_wilayah' => $placeholder['region'],
                    'created_at' => date('Y-m-d H:i:s'),
                ));
            }
        }
    }

    private function find_group($name)
    {
        return $this->db->where('LOWER(name) =', strtolower($name))->get('aauth_groups')->row();
    }

    private function insert_membership($user_id, $group_id)
    {
        if (!$this->db->where(array('user_id' => $user_id, 'group_id' => $group_id))->count_all_results('aauth_user_to_group')) {
            $this->db->insert('aauth_user_to_group', array('user_id' => $user_id, 'group_id' => $group_id));
        }
    }

    private function insert_permission($permission_id, $group_id)
    {
        if (!$this->db->where(array('perm_id' => $permission_id, 'group_id' => $group_id))->count_all_results('aauth_perm_to_group')) {
            $this->db->insert('aauth_perm_to_group', array('perm_id' => $permission_id, 'group_id' => $group_id));
        }
    }
}

