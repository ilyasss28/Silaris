<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Add an authoritative MPD registry while retaining mpd_wilayah as the
 * one-to-many jurisdiction table already used by the report access policy.
 */
class Migration_data_mpd_registry extends CI_Migration
{
    public function up()
    {
        $this->create_data_mpd_table();
        $this->seed_existing_mpd_accounts();
        $this->configure_permissions();
        $this->configure_menu();
    }

    public function down()
    {
        $menu = $this->db->where('link', 'data_mpd')->get('menu')->row();
        if ($menu) {
            $this->db->where('id', $menu->id)->delete('menu');
        }

        $permission_names = array(
            'menu_data_mpd', 'data_mpd_add', 'data_mpd_update',
            'data_mpd_view', 'data_mpd_delete', 'data_mpd_list',
        );
        $permissions = $this->db->where_in('name', $permission_names)->get('aauth_perms')->result();
        foreach ($permissions as $permission) {
            $this->db->where('perm_id', $permission->id)->delete('aauth_perm_to_group');
            $this->db->where('id', $permission->id)->delete('aauth_perms');
        }

        if ($this->db->table_exists('data_mpd')) {
            $this->dbforge->drop_table('data_mpd', true);
        }
    }

    private function create_data_mpd_table()
    {
        if ($this->db->table_exists('data_mpd')) {
            return;
        }

        $this->dbforge->add_field(array(
            'id_mpd' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true),
            'user_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true),
            'nama_mpd' => array('type' => 'VARCHAR', 'constraint' => 150),
            'jabatan' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => true),
            'email' => array('type' => 'VARCHAR', 'constraint' => 150, 'null' => true),
            'no_telepon' => array('type' => 'VARCHAR', 'constraint' => 30, 'null' => true),
            'nomor_sk' => array('type' => 'VARCHAR', 'constraint' => 120, 'null' => true),
            'tanggal_mulai' => array('type' => 'DATE', 'null' => true),
            'tanggal_selesai' => array('type' => 'DATE', 'null' => true),
            'alamat' => array('type' => 'VARCHAR', 'constraint' => 255, 'null' => true),
            'is_verified' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 0),
            'created_at' => array('type' => 'DATETIME'),
            'updated_at' => array('type' => 'DATETIME', 'null' => true),
        ));
        $this->dbforge->add_key('id_mpd', true);
        $this->dbforge->add_key('user_id');
        $this->dbforge->add_key('nama_mpd');
        $this->dbforge->create_table('data_mpd', true);
        $this->db->query('ALTER TABLE data_mpd ADD UNIQUE KEY uniq_data_mpd_user (user_id)');
    }

    private function seed_existing_mpd_accounts()
    {
        if (!$this->db->table_exists('data_mpd')) {
            return;
        }

        $users = $this->db
            ->distinct()
            ->select('users.id, users.full_name, users.email, users.phone_number')
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
            ->where('groups_table.name', 'MPD')
            ->get()
            ->result();

        foreach ($users as $user) {
            if ($this->db->where('user_id', (int) $user->id)->count_all_results('data_mpd')) {
                continue;
            }

            $is_placeholder = stripos((string) $user->full_name, 'belum diverifikasi') !== false
                || substr(strtolower(trim((string) $user->email)), -14) === '@invalid.local';
            $this->db->insert('data_mpd', array(
                'user_id' => (int) $user->id,
                'nama_mpd' => (string) $user->full_name,
                'jabatan' => 'Anggota MPD',
                'email' => (string) $user->email,
                'no_telepon' => (string) $user->phone_number,
                'is_verified' => $is_placeholder ? 0 : 1,
                'created_at' => date('Y-m-d H:i:s'),
            ));
        }
    }

    private function configure_permissions()
    {
        $permission_names = array(
            'menu_data_mpd', 'data_mpd_add', 'data_mpd_update',
            'data_mpd_view', 'data_mpd_delete', 'data_mpd_list',
        );
        $permissions = array();
        foreach ($permission_names as $name) {
            $permission = $this->db->where('name', $name)->get('aauth_perms')->row();
            if (!$permission) {
                $this->db->insert('aauth_perms', array('name' => $name, 'definition' => 'Data induk MPD'));
                $permission = (object) array('id' => $this->db->insert_id(), 'name' => $name);
            }
            $permissions[$name] = (int) $permission->id;
        }

        foreach (array('Admin', 'Kanwil') as $group_name) {
            $group = $this->find_group($group_name);
            if (!$group) continue;
            foreach ($permissions as $permission_id) {
                $this->grant($group->id, $permission_id);
            }
        }

        $mpd = $this->find_group('MPD');
        if ($mpd) {
            foreach (array('menu_data_mpd', 'data_mpd_list', 'data_mpd_view') as $name) {
                $this->grant($mpd->id, $permissions[$name]);
            }
            $setup_permission = $this->db->where('name', 'menu_setup')->get('aauth_perms')->row();
            if ($setup_permission) {
                $this->grant($mpd->id, $setup_permission->id);
            }
        }
    }

    private function configure_menu()
    {
        if ($this->db->where('link', 'data_mpd')->count_all_results('menu')) {
            return;
        }

        $setup = $this->db->where('label', 'SETUP')->where('menu_type_id', 1)->get('menu')->row();
        if (!$setup) {
            return;
        }

        $max_sort = $this->db->select_max('sort')->where('parent', $setup->id)->get('menu')->row();
        $this->db->insert('menu', array(
            'label' => 'Data MPD',
            'type' => 'menu',
            'icon_color' => '',
            'link' => 'data_mpd',
            'sort' => ((int) $max_sort->sort) + 1,
            'parent' => (int) $setup->id,
            'icon' => 'fa-users',
            'menu_type_id' => 1,
            'active' => 1,
        ));
    }

    private function find_group($name)
    {
        return $this->db->where('LOWER(name) =', strtolower($name))->get('aauth_groups')->row();
    }

    private function grant($group_id, $permission_id)
    {
        $where = array('group_id' => (int) $group_id, 'perm_id' => (int) $permission_id);
        if (!$this->db->where($where)->count_all_results('aauth_perm_to_group')) {
            $this->db->insert('aauth_perm_to_group', $where);
        }
    }
}
