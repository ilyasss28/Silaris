<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Give every report an immutable account owner.
 *
 * The legacy schema related reports through username. Usernames are editable,
 * so that relationship was not safe enough for jurisdiction enforcement.
 */
class Migration_stable_report_ownership extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('owner_user_id', 'laporan')) {
            $this->dbforge->add_column('laporan', array(
                'owner_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'id',
                ),
            ));
            $this->db->query('ALTER TABLE laporan ADD KEY idx_laporan_owner_user_id (owner_user_id)');
        }

        $this->db->query(
            'UPDATE laporan AS report '
            . 'INNER JOIN aauth_users AS owner ON LOWER(owner.username) = LOWER(report.username) '
            . 'SET report.owner_user_id = owner.id '
            . 'WHERE report.owner_user_id IS NULL'
        );

        $this->configure_mpd_report_permissions();
    }

    public function down()
    {
        // Permissions remain intact so rollback cannot lock out an active MPD.
        if ($this->db->field_exists('owner_user_id', 'laporan')) {
            $this->dbforge->drop_column('laporan', 'owner_user_id');
        }
    }

    private function configure_mpd_report_permissions()
    {
        $group = $this->db->where('LOWER(name) =', 'mpd')->get('aauth_groups')->row();
        if (!$group) {
            return;
        }

        $read_names = array(
            'menu_laporan', 'menu_laporan_bulanan', 'laporan_list', 'laporan_view',
            'menu_rekap', 'rekap_Laporan_list', 'rekap_Laporan_view',
        );
        $permissions = $this->db->where_in('name', $read_names)->get('aauth_perms')->result();
        foreach ($permissions as $permission) {
            $exists = $this->db->where(array(
                'group_id' => (int) $group->id,
                'perm_id' => (int) $permission->id,
            ))->count_all_results('aauth_perm_to_group');
            if (!$exists) {
                $this->db->insert('aauth_perm_to_group', array(
                    'group_id' => (int) $group->id,
                    'perm_id' => (int) $permission->id,
                ));
            }
        }

        $mutation_names = array(
            'laporan_add', 'laporan_update', 'laporan_delete',
            'rekap_Laporan_add', 'rekap_Laporan_update', 'rekap_Laporan_delete',
        );
        $mutations = $this->db->where_in('name', $mutation_names)->get('aauth_perms')->result();
        foreach ($mutations as $permission) {
            $this->db->where(array(
                'group_id' => (int) $group->id,
                'perm_id' => (int) $permission->id,
            ))->delete('aauth_perm_to_group');
        }
    }
}

