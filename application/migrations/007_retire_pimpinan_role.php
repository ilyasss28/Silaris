<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Retire the Pimpinan role. No accounts are assigned to it, so this only
 * removes the group record and whatever permissions were granted to it.
 */
class Migration_retire_pimpinan_role extends CI_Migration
{
    public function up()
    {
        $group = $this->db
            ->where('LOWER(name) =', 'pimpinan')
            ->get('aauth_groups')
            ->row();

        if (!$group) {
            return;
        }

        $this->db->where('group_id', $group->id)->delete('aauth_user_to_group');
        $this->db->where('group_id', $group->id)->delete('aauth_perm_to_group');
        $this->db->where('id', $group->id)->delete('aauth_groups');
    }

    public function down()
    {
        // The role is retired intentionally; the group record is not
        // restored automatically to avoid resurrecting stale permission
        // grants that were never meant to be reassigned.
    }
}
