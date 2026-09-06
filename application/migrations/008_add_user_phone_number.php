<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Store a phone number directly on the user account.
 *
 * Existing values are backfilled only when an account can be matched safely
 * to a notary profile by email address or exact full name.
 */
class Migration_add_user_phone_number extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('aauth_users')) {
            return;
        }

        if (!$this->db->field_exists('phone_number', 'aauth_users')) {
            $this->dbforge->add_column('aauth_users', [
                'phone_number' => [
                    'type' => 'VARCHAR',
                    'constraint' => 30,
                    'null' => true,
                    'after' => 'full_name',
                ],
            ]);
        }

        if ($this->db->table_exists('data_notaris')) {
            $this->db->query(
                "UPDATE aauth_users AS users "
                . "SET users.phone_number = ("
                . "SELECT NULLIF(TRIM(profiles.no_telepon), '') FROM data_notaris AS profiles "
                . "WHERE NULLIF(TRIM(profiles.no_telepon), '') IS NOT NULL "
                . "AND ((TRIM(users.email) != '' AND LOWER(TRIM(profiles.email)) = LOWER(TRIM(users.email))) "
                . "OR LOWER(TRIM(profiles.nama_notaris)) = LOWER(TRIM(users.full_name))) "
                . "ORDER BY (TRIM(users.email) != '' AND LOWER(TRIM(profiles.email)) = LOWER(TRIM(users.email))) DESC, profiles.id_notaris ASC "
                . "LIMIT 1) "
                . "WHERE NULLIF(TRIM(users.phone_number), '') IS NULL"
            );
        }
    }

    public function down()
    {
        if ($this->db->table_exists('aauth_users') && $this->db->field_exists('phone_number', 'aauth_users')) {
            $this->dbforge->drop_column('aauth_users', 'phone_number');
        }
    }
}
