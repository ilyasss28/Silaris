<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Give MPD read-only access to every reporting service in its jurisdictions. */
class Migration_mpd_service_permissions extends CI_Migration
{
    public function up()
    {
        $group = $this->db->where('LOWER(name) =', 'mpd')->get('aauth_groups')->row();
        if (!$group) return;

        $modules = array('laporan', 'reportorium', 'daftar_proses', 'legalisasi', 'waarmerking', 'fidusia');
        $read_names = array(
            'menu_laporan', 'menu_laporan_bulanan', 'menu_reportorium',
            'menu_daftar_protes', 'menu_daftar_proses', 'menu_legalisasi',
            'menu_waarmerking', 'menu_fidusia',
        );
        foreach ($modules as $module) {
            $read_names[] = $module . '_list';
            $read_names[] = $module . '_view';
            $read_names[] = $module . '_export';
        }

        $read_permissions = $this->db->where_in('name', array_unique($read_names))->get('aauth_perms')->result();
        foreach ($read_permissions as $permission) {
            $where = array('group_id' => (int) $group->id, 'perm_id' => (int) $permission->id);
            if (!$this->db->where($where)->count_all_results('aauth_perm_to_group')) {
                $this->db->insert('aauth_perm_to_group', $where);
            }
        }

        $mutation_names = array();
        foreach ($modules as $module) {
            foreach (array('add', 'update', 'delete') as $action) {
                $mutation_names[] = $module . '_' . $action;
            }
        }
        $mutations = $this->db->where_in('name', $mutation_names)->get('aauth_perms')->result();
        foreach ($mutations as $permission) {
            $this->db->where(array(
                'group_id' => (int) $group->id,
                'perm_id' => (int) $permission->id,
            ))->delete('aauth_perm_to_group');
        }
    }

    public function down()
    {
        // Read access is retained on rollback to avoid unexpectedly locking an
        // active supervisory account out of reports it was already assigned.
    }
}
