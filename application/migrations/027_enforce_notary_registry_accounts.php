<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Deactivate Notary accounts that are not linked to Data Notaris. */
class Migration_enforce_notary_registry_accounts extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris') || !$this->db->field_exists('user_id', 'data_notaris')) {
            return;
        }
        $this->db->query(
            'UPDATE aauth_users users '
            . 'INNER JOIN aauth_user_to_group memberships ON memberships.user_id = users.id '
            . 'INNER JOIN aauth_groups groups_table ON groups_table.id = memberships.group_id '
            . "AND groups_table.name = 'User' "
            . 'LEFT JOIN data_notaris registry ON registry.user_id = users.id '
            . 'SET users.banned = 1 '
            . 'WHERE registry.id_notaris IS NULL AND users.banned = 0'
        );
    }

    public function down()
    {
        // Account activation is an administrative decision and is not restored.
    }
}
