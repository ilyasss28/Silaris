<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Normalize business identifiers and add immutable ownership to every report.
 * Destructive cleanup is intentionally limited to data_notaris.password: it is
 * not authentication data, is not read by the application, and duplicates the
 * secure credential stored by Aauth.
 */
class Migration_business_schema_hardening extends CI_Migration
{
    private $service_tables = array('daftar_proses', 'legalisasi', 'reportorium', 'waarmerking');

    public function up()
    {
        foreach ($this->service_tables as $table) {
            $this->add_report_owner($table);
        }
        $this->normalize_notary_registry();
        $this->harden_reference_indexes();
    }

    public function down()
    {
        foreach ($this->service_tables as $table) {
            if ($this->db->table_exists($table) && $this->db->field_exists('owner_user_id', $table)) {
                $this->dbforge->drop_column($table, 'owner_user_id');
            }
        }
        if ($this->db->table_exists('data_notaris') && !$this->db->field_exists('password', 'data_notaris')) {
            $this->dbforge->add_column('data_notaris', array(
                'password' => array('type' => 'VARCHAR', 'constraint' => 100, 'null' => false, 'default' => ''),
            ));
        }
    }

    private function add_report_owner($table)
    {
        if (!$this->db->table_exists($table)) {
            return;
        }
        if (!$this->db->field_exists('owner_user_id', $table)) {
            $primary = $this->db->primary($table);
            $this->dbforge->add_column($table, array(
                'owner_user_id' => array(
                    'type' => 'INT', 'constraint' => 11, 'unsigned' => true,
                    'null' => true, 'after' => $primary,
                ),
            ));
            $this->db->query('ALTER TABLE `'.$table.'` ADD KEY `idx_'.$table.'_owner_user_id` (`owner_user_id`)');
        }
        $this->db->query(
            'UPDATE `'.$table.'` AS report '
            .'INNER JOIN aauth_users AS owner ON LOWER(owner.username) = LOWER(report.username) '
            .'SET report.owner_user_id = owner.id WHERE report.owner_user_id IS NULL'
        );
    }

    private function normalize_notary_registry()
    {
        if (!$this->db->table_exists('data_notaris')) {
            return;
        }

        $region_map = array(
            'kolaka' => '7401', 'konawe' => '7402', 'muna' => '7403', 'buton' => '7404',
            'konsel' => '7405', 'bombana' => '7406', 'wakatobi' => '7407', 'kolut' => '7408',
            'konut' => '7409', 'butur' => '7410', 'koltim' => '7411', 'konkep' => '7412',
            'mubar' => '7413', 'buteng' => '7414', 'busel' => '7415', 'kendari' => '7471',
            'baubau' => '7472',
        );
        foreach ($region_map as $legacy => $official) {
            $this->db->where('LOWER(TRIM(kode_wilayah)) =', $legacy)
                ->update('data_notaris', array('kode_wilayah' => $official));
        }

        $this->db->query("UPDATE data_notaris SET jenis_kelamin='Laki-laki' WHERE UPPER(TRIM(jenis_kelamin)) IN ('PRIA','LAKI-LAKI','LAKI LAKI')");
        $this->db->query("UPDATE data_notaris SET jenis_kelamin='Perempuan' WHERE UPPER(TRIM(jenis_kelamin)) IN ('WANITA','PEREMPUAN')");
        $this->db->query("UPDATE data_notaris SET status_notaris='NOTARIS AKTIF' WHERE status_notaris IS NULL OR TRIM(status_notaris)=''");
        $this->db->query("UPDATE data_notaris SET no_telepon=REPLACE(REPLACE(REPLACE(REPLACE(REPLACE(TRIM(no_telepon),' ',''),'-',''),'(',''),')',''),'.','') WHERE no_telepon IS NOT NULL");
        $this->db->query("UPDATE data_notaris SET no_telepon=CONCAT('0',no_telepon) WHERE no_telepon REGEXP '^8[0-9]+$'");
        $this->db->query("UPDATE data_notaris SET no_telepon=CONCAT('0',SUBSTRING(no_telepon,3)) WHERE no_telepon REGEXP '^62[0-9]+$'");
        $this->db->query("UPDATE data_notaris SET npwp=REPLACE(REPLACE(REPLACE(REPLACE(TRIM(npwp),'.',''),'-',''),' ',''),',','') WHERE npwp IS NOT NULL");
        $this->db->query("UPDATE data_notaris SET npwp=NULL WHERE npwp='' OR npwp='-'");
        $this->db->query("UPDATE data_notaris SET nomor_ktp=REPLACE(REPLACE(REPLACE(TRIM(nomor_ktp),' ',''),'-',''),'.','') WHERE nomor_ktp IS NOT NULL");

        // Keep the legacy display column synchronized while consumers migrate to kode_wilayah.
        $this->db->query(
            'UPDATE data_notaris AS notary INNER JOIN wilayah AS region '
            .'ON region.kd_wilayah = notary.kode_wilayah SET notary.wilayah = region.nama'
        );

        if ($this->db->field_exists('password', 'data_notaris')) {
            $this->dbforge->drop_column('data_notaris', 'password');
        }
        $this->db->query('ALTER TABLE data_notaris MODIFY no_telepon VARCHAR(20) NULL, MODIFY kode_wilayah VARCHAR(10) NOT NULL, MODIFY email VARCHAR(150) NULL');
    }

    private function harden_reference_indexes()
    {
        if ($this->db->table_exists('wilayah')) {
            $this->db->query("DELETE FROM wilayah WHERE TRIM(kd_wilayah) = ''");
            if (!$this->index_exists('wilayah', 'uniq_wilayah_code')) {
                $this->db->query('ALTER TABLE wilayah ADD UNIQUE KEY uniq_wilayah_code (kd_wilayah)');
            }
        }
        if ($this->db->table_exists('aauth_users') && !$this->index_exists('aauth_users', 'uniq_aauth_username')) {
            $this->db->query('ALTER TABLE aauth_users ADD UNIQUE KEY uniq_aauth_username (username)');
        }
    }

    private function index_exists($table, $index)
    {
        return $this->db->query(
            'SELECT 1 FROM information_schema.STATISTICS WHERE TABLE_SCHEMA=DATABASE() AND TABLE_NAME=? AND INDEX_NAME=? LIMIT 1',
            array($table, $index)
        )->num_rows() > 0;
    }
}
