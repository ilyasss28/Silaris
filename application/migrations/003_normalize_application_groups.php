<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_normalize_application_groups extends CI_Migration
{
    public function up()
    {
        $this->remove_orphan_links();
        $this->normalize_permission_links();

        $groups = [
            'Admin' => 'Administrator sistem',
            'User' => 'Pengguna atau Notaris',
            'Kanwil' => 'Kantor Wilayah',
            'MPD' => 'Majelis Pengawas Daerah',
            'Pimpinan' => 'Pimpinan',
        ];

        foreach ($groups as $name => $definition) {
            $existing = $this->db
                ->where('LOWER(name) =', strtolower($name))
                ->get('aauth_groups')
                ->row();

            if ($existing) {
                $this->db->where('id', $existing->id)->update('aauth_groups', [
                    'name' => $name,
                    'definition' => $definition,
                ]);
                continue;
            }

            $this->db->insert('aauth_groups', [
                'name' => $name,
                'definition' => $definition,
            ]);
        }

        // Preserve accounts and permissions that still use historical group
        // names before those names are hidden from the user form.
        $this->merge_legacy_group('Super Admin', 'Admin');
        $this->merge_legacy_group('PIMTI', 'Pimpinan');
        $this->merge_legacy_group('Kakanwil', 'Pimpinan');

        // The original installation account must never become orphaned when
        // its legacy Super Admin option is replaced by the canonical Admin.
        $admin_group = $this->find_group('Admin');
        $admin_user = $this->db
            ->where('LOWER(username) =', 'admin')
            ->get('aauth_users')
            ->row();

        if ($admin_group && $admin_user) {
            $this->insert_membership((int) $admin_user->id, (int) $admin_group->id);
        }

        // Dashboard is the authenticated landing page and the destination
        // used after another permission is denied, so every application role
        // must be able to open it.
        $dashboard_permission = $this->db
            ->where('name', 'dashboard')
            ->get('aauth_perms')
            ->row();

        if ($dashboard_permission) {
            foreach (array_keys($groups) as $group_name) {
                $group = $this->find_group($group_name);
                if ($group) {
                    $this->insert_permission((int) $dashboard_permission->id, (int) $group->id);
                }
            }
        }
    }

    public function down()
    {
        // Role records are retained to avoid orphaning user and permission links.
    }

    private function find_group($name)
    {
        return $this->db
            ->where('LOWER(name) =', strtolower($name))
            ->get('aauth_groups')
            ->row();
    }

    private function merge_legacy_group($legacy_name, $target_name)
    {
        $legacy = $this->find_group($legacy_name);
        $target = $this->find_group($target_name);
        if (!$legacy || !$target || (int) $legacy->id === (int) $target->id) {
            return;
        }

        $members = $this->db
            ->select('user_id')
            ->where('group_id', $legacy->id)
            ->get('aauth_user_to_group')
            ->result();
        foreach ($members as $member) {
            $this->insert_membership((int) $member->user_id, (int) $target->id);
        }

        $permissions = $this->db
            ->select('perm_id')
            ->where('group_id', $legacy->id)
            ->get('aauth_perm_to_group')
            ->result();
        foreach ($permissions as $permission) {
            $this->insert_permission((int) $permission->perm_id, (int) $target->id);
        }
    }

    private function insert_membership($user_id, $group_id)
    {
        if (!$this->db->where(['user_id' => $user_id, 'group_id' => $group_id])->count_all_results('aauth_user_to_group')) {
            $this->db->insert('aauth_user_to_group', ['user_id' => $user_id, 'group_id' => $group_id]);
        }
    }

    private function insert_permission($permission_id, $group_id)
    {
        if (!$this->db->where(['perm_id' => $permission_id, 'group_id' => $group_id])->count_all_results('aauth_perm_to_group')) {
            $this->db->insert('aauth_perm_to_group', ['perm_id' => $permission_id, 'group_id' => $group_id]);
        }
    }

    private function normalize_permission_links()
    {
        $this->db->query('CREATE TEMPORARY TABLE normalized_perm_to_group AS SELECT DISTINCT perm_id, group_id FROM aauth_perm_to_group');
        $this->db->query('DELETE FROM aauth_perm_to_group');
        $this->db->query('INSERT INTO aauth_perm_to_group (perm_id, group_id) SELECT perm_id, group_id FROM normalized_perm_to_group');
        $this->db->query('DROP TEMPORARY TABLE normalized_perm_to_group');

        $index_exists = $this->db->query(
            "SELECT 1 FROM information_schema.statistics "
            . "WHERE table_schema = DATABASE() AND table_name = 'aauth_perm_to_group' "
            . "AND index_name = 'uniq_perm_group' LIMIT 1"
        )->row();

        if (!$index_exists) {
            $this->db->query('ALTER TABLE aauth_perm_to_group ADD UNIQUE KEY uniq_perm_group (perm_id, group_id)');
        }
    }

    private function remove_orphan_links()
    {
        $this->db->query(
            'DELETE links FROM aauth_user_to_group AS links '
            . 'LEFT JOIN aauth_users AS users ON users.id = links.user_id '
            . 'LEFT JOIN aauth_groups AS groups_table ON groups_table.id = links.group_id '
            . 'WHERE users.id IS NULL OR groups_table.id IS NULL'
        );
        $this->db->query(
            'DELETE links FROM aauth_perm_to_group AS links '
            . 'LEFT JOIN aauth_perms AS permissions ON permissions.id = links.perm_id '
            . 'LEFT JOIN aauth_groups AS groups_table ON groups_table.id = links.group_id '
            . 'WHERE permissions.id IS NULL OR groups_table.id IS NULL'
        );
    }
}
