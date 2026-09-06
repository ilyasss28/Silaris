<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Remove TMC definitions that could regenerate retired duplicate recap CRUDs. */
class Migration_remove_legacy_recap_builders extends CI_Migration
{
    private $tables = array(
        'rekap_laporan', 'rekap_laporan_bulanan', 'rekap_reportorium',
        'rekap_daftar_proses', 'rekap_legalisasi', 'rekap_waarmerking',
    );

    public function up()
    {
        if (!$this->db->table_exists('crud')) return;
        $definitions = $this->db->select('id')->where_in('LOWER(table_name)', $this->tables)->get('crud')->result_array();
        $ids = array_map('intval', array_column($definitions, 'id'));
        if (!$ids) return;

        foreach (array('crud_custom_option', 'crud_field_validation', 'crud_field') as $table) {
            if ($this->db->table_exists($table)) $this->db->where_in('crud_id', $ids)->delete($table);
        }
        $this->db->where_in('id', $ids)->delete('crud');
    }

    public function down()
    {
        // Retired generators are intentionally not restored because their
        // source tables and duplicate mutation flows no longer exist.
    }
}

