<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Keep the internal report owner key out of generated CRUD interfaces. */
class Migration_hide_report_owner_fields extends CI_Migration
{
    private $tables = array(
        'laporan', 'laporan_bulanan', 'daftar_proses',
        'reportorium', 'legalisasi', 'waarmerking', 'fidusia',
    );

    public function up()
    {
        if (!$this->db->table_exists('crud') || !$this->db->table_exists('crud_field')) {
            return;
        }

        $tables = implode(',', array_map(array($this->db, 'escape'), $this->tables));
        $this->db->query(
            "UPDATE crud_field fields "
            ."INNER JOIN crud definitions ON definitions.id=fields.crud_id "
            ."SET fields.show_column='', fields.show_add_form='', "
            ."fields.show_update_form='', fields.show_detail_page='' "
            ."WHERE fields.field_name='owner_user_id' "
            ."AND definitions.table_name IN ({$tables})"
        );
    }

    public function down()
    {
        // owner_user_id remains intentionally hidden because it is assigned by
        // the authenticated account and must never be editable by a client.
    }
}
