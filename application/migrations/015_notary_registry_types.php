<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Replace loosely generated VARCHAR fields with types that represent the
 * actual registry data. Legacy coordinates stored together in `lat` are split
 * into latitude and longitude before conversion.
 */
class Migration_notary_registry_types extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris')) {
            return;
        }

        $this->db->query("UPDATE data_notaris SET `long`=TRIM(SUBSTRING_INDEX(lat, ',', -1)), lat=TRIM(SUBSTRING_INDEX(lat, ',', 1)) WHERE lat LIKE '%,%'");
        $this->db->query("UPDATE data_notaris SET tanggal_lahir=NULL WHERE TRIM(COALESCE(tanggal_lahir,''))=''");
        $this->db->query("UPDATE data_notaris SET lat=NULL WHERE TRIM(COALESCE(lat,''))=''");
        $this->db->query("UPDATE data_notaris SET `long`=NULL WHERE TRIM(COALESCE(`long`,''))=''");

        $this->db->query(
            'ALTER TABLE data_notaris '
            .'MODIFY nama_notaris VARCHAR(100) NOT NULL, '
            .'MODIFY tanggal_lahir DATE NULL, '
            .'MODIFY jenis_kelamin VARCHAR(20) NOT NULL, '
            .'MODIFY lat DECIMAL(20,16) NULL, '
            .'MODIFY `long` DECIMAL(20,16) NULL, '
            .'MODIFY status_notaris VARCHAR(50) NOT NULL'
        );
    }

    public function down()
    {
        if (!$this->db->table_exists('data_notaris')) {
            return;
        }
        $this->db->query(
            'ALTER TABLE data_notaris '
            .'MODIFY nama_notaris VARCHAR(100) NULL, '
            .'MODIFY tanggal_lahir VARCHAR(100) NULL, '
            .'MODIFY jenis_kelamin VARCHAR(100) NULL, '
            .'MODIFY lat VARCHAR(255) NULL, '
            .'MODIFY `long` VARCHAR(255) NULL, '
            .'MODIFY status_notaris VARCHAR(50) NULL'
        );
    }
}
