<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Keep User-account and Data Notaris names in the same canonical title case. */
class Migration_normalize_notary_name_capitalization extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris')) return;

        if ($this->db->table_exists('aauth_users')) {
            $users = $this->db
                ->distinct()
                ->select('users.id, users.full_name')
                ->from('aauth_users users')
                ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
                ->join('aauth_groups groups_table', 'groups_table.id = memberships.group_id')
                ->where('groups_table.name', 'User')
                ->get()
                ->result();

            foreach ($users as $user) {
                $formatted_name = format_person_name($user->full_name);
                if ($formatted_name === '') continue;

                if ($formatted_name !== (string) $user->full_name) {
                    $this->db->where('id', (int) $user->id)->update('aauth_users', array(
                        'full_name' => $formatted_name,
                    ));
                }

                if ($this->db->field_exists('user_id', 'data_notaris')) {
                    $this->db->where('user_id', (int) $user->id)->update('data_notaris', array(
                        'nama_notaris' => $formatted_name,
                    ));
                }
            }
        }

        // Rows without a SILARIS account still receive the same formatting.
        $profiles = $this->db->select('id_notaris, nama_notaris')->get('data_notaris')->result();
        foreach ($profiles as $profile) {
            $formatted_name = format_person_name($profile->nama_notaris);
            if ($formatted_name !== '' && $formatted_name !== (string) $profile->nama_notaris) {
                $this->db->where('id_notaris', (int) $profile->id_notaris)->update('data_notaris', array(
                    'nama_notaris' => $formatted_name,
                ));
            }
        }
    }

    public function down()
    {
        // Capitalisation cannot be reversed without restoring inconsistent data.
    }
}
