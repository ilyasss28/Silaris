<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_missing_reference_tables extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('setup_satker')) {
            $this->dbforge->add_field([
                'id_satker' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'nama_satker' => [
                    'type' => 'VARCHAR',
                    'constraint' => 100,
                ],
            ]);
            $this->dbforge->add_key('id_satker', true);
            $this->dbforge->create_table('setup_satker', true);
        }

        if (!$this->db->table_exists('kd_kanwil')) {
            $this->dbforge->add_field([
                'id' => [
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'auto_increment' => true,
                ],
                'kd_kanwil' => [
                    'type' => 'VARCHAR',
                    'constraint' => 10,
                ],
                'nama_kanwil' => [
                    'type' => 'VARCHAR',
                    'constraint' => 50,
                ],
            ]);
            $this->dbforge->add_key('id', true);
            $this->dbforge->add_key('kd_kanwil');
            $this->dbforge->create_table('kd_kanwil', true);
        }
    }

    public function down()
    {
        $this->dbforge->drop_table('kd_kanwil', true);
        $this->dbforge->drop_table('setup_satker', true);
    }
}
