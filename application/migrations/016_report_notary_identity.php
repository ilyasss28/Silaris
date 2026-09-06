<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Give every report type the same stable ownership and Notary-name fields,
 * then repair legacy rows from their account username.
 */
class Migration_report_notary_identity extends CI_Migration
{
    private $tables = array('laporan', 'laporan_bulanan', 'daftar_proses', 'reportorium', 'legalisasi', 'waarmerking', 'fidusia');

    public function up()
    {
        foreach ($this->tables as $table) {
            if (!$this->db->table_exists($table)) continue;

            if (!$this->db->field_exists('owner_user_id', $table)) {
                $this->dbforge->add_column($table, array(
                    'owner_user_id' => array('type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true),
                ));
            }
            if (!$this->db->field_exists('nama_notaris', $table)) {
                $this->dbforge->add_column($table, array(
                    'nama_notaris' => array('type' => 'VARCHAR', 'constraint' => 200, 'null' => true),
                ));
            }

            if ($this->db->field_exists('username', $table)) {
                $this->db->query(
                    "UPDATE `{$table}` reports "
                    ."INNER JOIN aauth_users users ON LOWER(TRIM(users.username))=LOWER(TRIM(reports.username)) "
                    ."SET reports.owner_user_id=users.id "
                    ."WHERE reports.owner_user_id IS NULL OR reports.owner_user_id=0"
                );
            }
            $this->db->query(
                "UPDATE `{$table}` reports "
                ."INNER JOIN aauth_users users ON users.id=reports.owner_user_id "
                ."SET reports.nama_notaris=users.full_name "
                ."WHERE users.full_name IS NOT NULL AND TRIM(users.full_name)<>''"
            );
            if ($this->db->field_exists('username', $table)) {
                $this->db->query(
                    "UPDATE `{$table}` SET nama_notaris=username "
                    ."WHERE (nama_notaris IS NULL OR TRIM(nama_notaris)='') AND TRIM(COALESCE(username,''))<>''"
                );
            }

            $index = 'idx_'.$table.'_owner_user';
            $existing = $this->db->query("SHOW INDEX FROM `{$table}` WHERE Key_name=?", array($index))->num_rows() > 0;
            if (!$existing) $this->db->query("ALTER TABLE `{$table}` ADD INDEX `{$index}` (`owner_user_id`)");
        }
    }

    public function down()
    {
        // These identity fields are intentionally retained: dropping them can
        // orphan report ownership and erase repaired historical names.
    }
}
