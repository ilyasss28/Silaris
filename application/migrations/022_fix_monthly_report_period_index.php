<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Allow one report per Notary per month instead of one report per account forever. */
class Migration_fix_monthly_report_period_index extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('laporan_bulanan')) {
            return;
        }

        $indexes = $this->db->query('SHOW INDEX FROM `laporan_bulanan`')->result();
        foreach ($indexes as $index) {
            if ($index->Key_name === 'nomor_urut' && (int) $index->Non_unique === 0) {
                $this->db->query('ALTER TABLE `laporan_bulanan` DROP INDEX `nomor_urut`');
                break;
            }
        }

        $has_period_index = false;
        foreach ($this->db->query('SHOW INDEX FROM `laporan_bulanan`')->result() as $index) {
            if ($index->Key_name === 'idx_laporan_bulanan_owner_period') {
                $has_period_index = true;
                break;
            }
        }
        if (!$has_period_index) {
            $this->db->query(
                'ALTER TABLE `laporan_bulanan` ADD INDEX '
                .'`idx_laporan_bulanan_owner_period` (`owner_user_id`, `tanggal_laporan`)'
            );
        }
    }

    public function down()
    {
        // Do not restore the invalid unique username rule: doing so could fail
        // or discard legitimate reports from different months.
    }
}
