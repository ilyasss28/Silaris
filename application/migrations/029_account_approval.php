<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Separate administrative account approval from the inactive/banned flag. */
class Migration_account_approval extends CI_Migration
{
    public function up()
    {
        if (!$this->db->field_exists('is_verified', 'aauth_users')) {
            $this->dbforge->add_column('aauth_users', array(
                'is_verified' => array('type' => 'TINYINT', 'constraint' => 1, 'default' => 1, 'null' => false),
                'verification_requested_at' => array('type' => 'DATETIME', 'null' => true),
                'verified_at' => array('type' => 'DATETIME', 'null' => true),
                'verified_by' => array('type' => 'INT', 'constraint' => 11, 'null' => true),
            ));
        }

        // Every account that predates this workflow is considered reviewed.
        $this->db->where('is_verified IS NULL', null, false)->update('aauth_users', array('is_verified' => 1));
    }

    public function down()
    {
        foreach (array('verified_by', 'verified_at', 'verification_requested_at', 'is_verified') as $field) {
            if ($this->db->field_exists($field, 'aauth_users')) {
                $this->dbforge->drop_column('aauth_users', $field);
            }
        }
    }
}
