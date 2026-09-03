<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Give Fidusia records an immutable account owner.
 *
 * Legacy records remain compatible because Report_access falls back to their
 * username whenever owner_user_id cannot be populated.
 */
class Migration_stable_fidusia_ownership extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('fidusia')) {
            return;
        }

        if (!$this->db->field_exists('owner_user_id', 'fidusia')) {
            $this->dbforge->add_column('fidusia', array(
                'owner_user_id' => array(
                    'type' => 'INT',
                    'constraint' => 11,
                    'unsigned' => true,
                    'null' => true,
                    'after' => 'id_fidusia',
                ),
            ));
            $this->db->query('ALTER TABLE fidusia ADD KEY idx_fidusia_owner_user_id (owner_user_id)');
        }

        $this->db->query(
            'UPDATE fidusia AS record '
            . 'INNER JOIN aauth_users AS owner ON LOWER(owner.username) = LOWER(record.username) '
            . 'SET record.owner_user_id = owner.id '
            . 'WHERE record.owner_user_id IS NULL'
        );
    }

    public function down()
    {
        if ($this->db->table_exists('fidusia') && $this->db->field_exists('owner_user_id', 'fidusia')) {
            $this->dbforge->drop_column('fidusia', 'owner_user_id');
        }
    }
}

