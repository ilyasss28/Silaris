<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Add indexes that match the report ownership, period, and region filters used
 * by the application. Also completes safe formatting cleanup without guessing
 * missing identity digits.
 */
class Migration_business_query_indexes extends CI_Migration
{
    private $indexes = array(
        'aauth_users' => array('idx_aauth_users_region' => array('kd_wilayah')),
        'data_notaris' => array(
            'idx_data_notaris_region' => array('kode_wilayah'),
            'idx_data_notaris_email' => array('email'),
        ),
        'laporan' => array('idx_laporan_owner_date' => array('owner_user_id', 'Tanggal_Laporan')),
        'fidusia' => array('idx_fidusia_owner_date' => array('owner_user_id', 'tanggal_akta')),
        'daftar_proses' => array('idx_daftar_proses_owner_date' => array('owner_user_id', 'tanggal_akta')),
        'legalisasi' => array('idx_legalisasi_owner_date' => array('owner_user_id', 'tanggal_akta')),
        'reportorium' => array('idx_reportorium_owner_date' => array('owner_user_id', 'tanggal_akta')),
        'waarmerking' => array('idx_waarmerking_owner_date' => array('owner_user_id', 'tanggal_akta')),
    );

    public function up()
    {
        if ($this->db->table_exists('data_notaris')) {
            $this->db->query("UPDATE data_notaris SET npwp=REPLACE(npwp, ',', '') WHERE npwp LIKE '%,%'");
        }
        if ($this->db->table_exists('aauth_users') && $this->db->field_exists('phone_number', 'aauth_users')) {
            $this->db->query("UPDATE aauth_users SET phone_number=REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(phone_number),' ',''),'-',''),'(',''),')',''),'.',''),'+','') WHERE phone_number IS NOT NULL");
            $this->db->query("UPDATE aauth_users SET phone_number=CONCAT('0',phone_number) WHERE phone_number REGEXP '^8[0-9]+$'");
            $this->db->query("UPDATE aauth_users SET phone_number=CONCAT('0',SUBSTRING(phone_number,3)) WHERE phone_number REGEXP '^62[0-9]+$'");
        }

        foreach ($this->indexes as $table => $definitions) {
            if (!$this->db->table_exists($table)) {
                continue;
            }
            foreach ($definitions as $name => $columns) {
                if (!$this->index_exists($table, $name) && $this->columns_exist($table, $columns)) {
                    $quoted = array_map(function ($column) { return '`'.$column.'`'; }, $columns);
                    $this->db->query('ALTER TABLE `'.$table.'` ADD KEY `'.$name.'` ('.implode(',', $quoted).')');
                }
            }
        }
    }

    public function down()
    {
        foreach ($this->indexes as $table => $definitions) {
            if (!$this->db->table_exists($table)) {
                continue;
            }
            foreach ($definitions as $name => $columns) {
                if ($this->index_exists($table, $name)) {
                    $this->db->query('ALTER TABLE `'.$table.'` DROP INDEX `'.$name.'`');
                }
            }
        }
    }

    private function columns_exist($table, array $columns)
    {
        foreach ($columns as $column) {
            if (!$this->db->field_exists($column, $table)) {
                return false;
            }
        }
        return true;
    }

    private function index_exists($table, $index)
    {
        return $this->db->query(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND INDEX_NAME=? LIMIT 1',
            array($table, $index)
        )->num_rows() > 0;
    }
}
