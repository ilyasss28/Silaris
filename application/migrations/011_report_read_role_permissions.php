<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Keep report visibility permissions aligned with the centralized role policy. */
class Migration_report_read_role_permissions extends CI_Migration
{
    public function up()
    {
        $modules = array('laporan', 'reportorium', 'daftar_proses', 'legalisasi', 'waarmerking', 'fidusia', 'rekap_Laporan');
        $read_names = array(
            'menu_laporan', 'menu_laporan_bulanan', 'menu_reportorium',
            'menu_daftar_protes', 'menu_daftar_proses', 'menu_legalisasi',
            'menu_waarmerking', 'menu_fidusia', 'menu_rekap',
        );
        foreach ($modules as $module) {
            foreach (array('list', 'view', 'export') as $action) {
                $read_names[] = $module . '_' . $action;
            }
        }
        $permissions = $this->db->where_in('name', array_unique($read_names))->get('aauth_perms')->result();

        foreach (array('Admin', 'Kanwil', 'Pimpinan', 'MPD') as $role) {
            $group = $this->db->where('LOWER(name) =', strtolower($role))->get('aauth_groups')->row();
            if (!$group) continue;
            foreach ($permissions as $permission) {
                $where = array('group_id' => (int) $group->id, 'perm_id' => (int) $permission->id);
                if (!$this->db->where($where)->count_all_results('aauth_perm_to_group')) {
                    $this->db->insert('aauth_perm_to_group', $where);
                }
            }
        }

        $readonly_groups = $this->db->where_in('LOWER(name)', array('mpd', 'pimpinan'))->get('aauth_groups')->result();
        $mutation_names = array();
        foreach ($modules as $module) {
            foreach (array('add', 'update', 'delete') as $action) {
                $mutation_names[] = $module . '_' . $action;
            }
        }
        $mutations = $this->db->where_in('name', $mutation_names)->get('aauth_perms')->result();
        foreach ($readonly_groups as $group) {
            foreach ($mutations as $permission) {
                $this->db->where(array('group_id' => (int) $group->id, 'perm_id' => (int) $permission->id))->delete('aauth_perm_to_group');
            }
        }
    }

    public function down()
    {
        // Intentionally retained: removing read access during rollback could
        // lock active supervisory accounts out of operational reports.
    }
}
