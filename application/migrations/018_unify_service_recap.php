<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Replace duplicate recap CRUD modules with one read-only service recap. */
class Migration_unify_service_recap extends CI_Migration
{
    private $read_permissions = array('menu_rekap_layanan', 'rekap_layanan_list', 'rekap_layanan_export');
    private $oversight_groups = array('Super Admin', 'Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan', 'MPD');
    private $legacy_tables = array(
        'rekap_laporan', 'rekap_laporan_bulanan', 'rekap_reportorium',
        'rekap_daftar_proses', 'rekap_legalisasi', 'rekap_waarmerking',
    );

    public function up()
    {
        $this->replace_menu();
        $this->replace_permissions();
        $this->remove_empty_legacy_tables();
    }

    public function down()
    {
        // The former recap tables contained no data and the old CRUD pages
        // modified canonical report tables. Recreating that unsafe duplication
        // on rollback is intentionally avoided.
    }

    private function replace_menu()
    {
        if (!$this->db->table_exists('menu')) return;
        $report_parent = $this->db->where('UPPER(label)', 'LAPORAN')->where('parent', 0)->get('menu')->row();
        if (!$report_parent) return;

        $legacy_parent = $this->db->where('UPPER(label)', 'REKAP')->where('parent', 0)->get('menu')->row();
        $recap = $this->db->where('LOWER(label)', 'rekap laporan')->get('menu')->row();
        $payload = array(
            'label' => 'Rekap Layanan',
            'type' => 'menu',
            'link' => 'rekap-layanan',
            'sort' => 27,
            'parent' => (int) $report_parent->id,
            'icon' => 'fa-table',
            'icon_color' => '',
            'menu_type_id' => 1,
            'active' => 1,
        );
        if ($recap) {
            $this->db->where('id', (int) $recap->id)->update('menu', $payload);
            $recap_id = (int) $recap->id;
        } else {
            $this->db->insert('menu', $payload);
            $recap_id = (int) $this->db->insert_id();
        }

        $legacy_labels = array('Rekap Daftar Proses', 'Rekap Legalisasi', 'Rekap Reportorium', 'Rekap Waarmerking');
        $this->db->where_in('label', $legacy_labels)->where('id !=', $recap_id)->delete('menu');
        if ($legacy_parent) {
            $remaining = $this->db->where('parent', (int) $legacy_parent->id)->count_all_results('menu');
            if ($remaining === 0) $this->db->where('id', (int) $legacy_parent->id)->delete('menu');
        }
    }

    private function replace_permissions()
    {
        if (!$this->db->table_exists('aauth_perms')) return;
        foreach ($this->read_permissions as $name) $this->ensure_permission($name);

        $new_ids = $this->db->select('id')->where_in('name', $this->read_permissions)->get('aauth_perms')->result_array();
        $new_ids = array_map('intval', array_column($new_ids, 'id'));
        $legacy = $this->db->select('id')->group_start()
            ->like('name', 'rekap_', 'after')->or_like('name', 'menu_rekap', 'after')
            ->group_end()->where_not_in('name', $this->read_permissions)->get('aauth_perms')->result_array();
        $legacy_ids = array_map('intval', array_column($legacy, 'id'));
        if ($legacy_ids) {
            if ($this->db->table_exists('aauth_perm_to_group')) $this->db->where_in('perm_id', $legacy_ids)->delete('aauth_perm_to_group');
            if ($this->db->table_exists('aauth_perm_to_user')) $this->db->where_in('perm_id', $legacy_ids)->delete('aauth_perm_to_user');
            $this->db->where_in('id', $legacy_ids)->delete('aauth_perms');
        }

        if (!$this->db->table_exists('aauth_groups') || !$this->db->table_exists('aauth_perm_to_group')) return;
        $groups = $this->db->select('id')->where_in('name', $this->oversight_groups)->get('aauth_groups')->result_array();
        foreach ($groups as $group) {
            foreach ($new_ids as $permission_id) {
                $key = array('group_id' => (int) $group['id'], 'perm_id' => $permission_id);
                if (!$this->db->where($key)->count_all_results('aauth_perm_to_group')) $this->db->insert('aauth_perm_to_group', $key);
            }
        }
    }

    private function ensure_permission($name)
    {
        if (!$this->db->where('name', $name)->count_all_results('aauth_perms')) {
            $this->db->insert('aauth_perms', array('name' => $name, 'definition' => 'Akses rekap layanan hanya-baca'));
        }
    }

    private function remove_empty_legacy_tables()
    {
        foreach ($this->legacy_tables as $table) {
            if ($this->db->table_exists($table) && (int) $this->db->count_all($table) === 0) {
                $this->dbforge->drop_table($table, true);
            }
        }
    }
}

