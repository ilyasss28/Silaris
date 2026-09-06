<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Keep the Laporan Bulanan schema and TMC metadata aligned with its form. */
class Migration_harden_monthly_report_crud extends CI_Migration
{
    private $table = 'laporan_bulanan';

    public function up()
    {
        $this->ensure_region_column();
        $this->backfill_regions();
        $this->configure_crud();
    }

    public function down()
    {
        // Report data is deliberately retained. Rolling back presentation
        // metadata must never discard a region already attached to a report.
    }

    private function ensure_region_column()
    {
        if (!$this->db->table_exists($this->table) || $this->db->field_exists('kd_wilayah', $this->table)) {
            return;
        }

        $this->dbforge->add_column($this->table, array(
            'kd_wilayah' => array(
                'type' => 'INT',
                'constraint' => 11,
                'null' => true,
                'after' => 'tanggal_laporan',
            ),
        ));
        $this->db->query('ALTER TABLE `laporan_bulanan` ADD INDEX `idx_laporan_bulanan_wilayah` (`kd_wilayah`)');
    }

    private function backfill_regions()
    {
        if (!$this->db->field_exists('kd_wilayah', $this->table)
            || !$this->db->table_exists('aauth_users') || !$this->db->table_exists('wil')) {
            return;
        }

        $this->db->query(
            'UPDATE `laporan_bulanan` report '
            .'LEFT JOIN `aauth_users` account ON account.id = report.owner_user_id '
            .'LEFT JOIN `wil` region ON region.kd_wilayah = account.kd_wilayah '
            .'SET report.kd_wilayah = region.id '
            .'WHERE report.kd_wilayah IS NULL AND region.id IS NOT NULL'
        );
    }

    private function configure_crud()
    {
        if (!$this->db->table_exists('crud') || !$this->db->table_exists('crud_field')) {
            return;
        }
        $crud = $this->db->where('table_name', $this->table)->get('crud')->row();
        if (!$crud) {
            return;
        }

        $fields = array(
            'id_laporan_bulanan' => array('ID Laporan Bulanan', 'number', '', '', '', 'yes', 1, '', '', ''),
            'nama_notaris' => array('Nama Notaris', 'current_user_full_name', 'yes', '', '', 'yes', 2, '', '', ''),
            'username' => array('Username', 'current_user_username', '', '', '', 'yes', 3, '', '', ''),
            'tanggal_laporan' => array('Tanggal Laporan', 'date', 'yes', 'yes', 'yes', 'yes', 4, '', '', ''),
            'kd_wilayah' => array('Wilayah', 'select', 'yes', 'yes', 'yes', 'yes', 5, 'wil', 'id', 'nama_wilayah'),
            'file_laporan' => array('File Laporan', 'file', 'yes', 'yes', 'yes', 'yes', 6, '', '', ''),
            'owner_user_id' => array('Pemilik Data', 'current_user_id', '', '', '', '', 7, '', '', ''),
        );

        foreach ($fields as $name => $config) {
            $field_id = $this->upsert_field((int) $crud->id, $name, $config);
            if (!$field_id) {
                continue;
            }
            $rules = array();
            if ($name === 'tanggal_laporan') {
                $rules = array('required' => '', 'valid_date' => '');
            } elseif ($name === 'kd_wilayah') {
                $rules = array('required' => '');
            } elseif ($name === 'file_laporan') {
                $rules = array(
                    'required' => '',
                    'allowed_extension' => 'pdf,doc,docx,xls,xlsx,jpg,jpeg,png',
                    'max_size' => '10000',
                );
            }
            $this->replace_rules((int) $crud->id, $field_id, $rules);
        }
    }

    private function upsert_field($crud_id, $field_name, array $config)
    {
        list($label, $input_type, $column, $add, $update, $detail, $sort, $relation_table, $relation_value, $relation_label) = $config;
        $data = array(
            'crud_id' => $crud_id,
            'field_name' => $field_name,
            'field_label' => $label,
            'input_type' => $input_type,
            'show_column' => $column,
            'show_add_form' => $add,
            'show_update_form' => $update,
            'show_detail_page' => $detail,
            'sort' => $sort,
            'relation_table' => $relation_table,
            'relation_value' => $relation_value,
            'relation_label' => $relation_label,
        );
        $existing = $this->db->where(array('crud_id' => $crud_id, 'field_name' => $field_name))->get('crud_field')->row();
        if ($existing) {
            $this->db->where('id', (int) $existing->id)->update('crud_field', $data);
            return (int) $existing->id;
        }
        $this->db->insert('crud_field', $data);
        return (int) $this->db->insert_id();
    }

    private function replace_rules($crud_id, $field_id, array $rules)
    {
        if (!$this->db->table_exists('crud_field_validation')) {
            return;
        }
        $this->db->where('crud_field_id', $field_id)->delete('crud_field_validation');
        foreach ($rules as $name => $value) {
            $this->db->insert('crud_field_validation', array(
                'crud_field_id' => $field_id,
                'crud_id' => $crud_id,
                'validation_name' => $name,
                'validation_value' => $value,
            ));
        }
    }
}
