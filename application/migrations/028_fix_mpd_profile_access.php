<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Give MPD access to its own profile and remove the administrative Setup menu. */
class Migration_fix_mpd_profile_access extends CI_Migration
{
    private $profile_permissions = array(
        'user_profile' => 'Melihat profil akun sendiri',
        'user_update_profile' => 'Memperbarui profil akun sendiri',
        'user_update_password' => 'Memperbarui kata sandi akun sendiri',
    );

    public function up()
    {
        $mpd = $this->db->where('LOWER(name) =', 'mpd')->get('aauth_groups')->row();
        if (!$mpd) return;

        foreach ($this->profile_permissions as $name => $definition) {
            $permission = $this->db->where('name', $name)->get('aauth_perms')->row();
            if (!$permission) {
                $this->db->insert('aauth_perms', array('name' => $name, 'definition' => $definition));
                $permission = (object) array('id' => $this->db->insert_id());
            }
            $link = array('group_id' => (int) $mpd->id, 'perm_id' => (int) $permission->id);
            if (!$this->db->where($link)->count_all_results('aauth_perm_to_group')) {
                $this->db->insert('aauth_perm_to_group', $link);
            }
        }

        $setup = $this->db->where('name', 'menu_setup')->get('aauth_perms')->row();
        if ($setup) {
            $this->db->where(array(
                'group_id' => (int) $mpd->id,
                'perm_id' => (int) $setup->id,
            ))->delete('aauth_perm_to_group');
        }
    }

    public function down()
    {
        $mpd = $this->db->where('LOWER(name) =', 'mpd')->get('aauth_groups')->row();
        if (!$mpd) return;

        foreach (array_keys($this->profile_permissions) as $name) {
            $permission = $this->db->where('name', $name)->get('aauth_perms')->row();
            if ($permission) {
                $this->db->where(array(
                    'group_id' => (int) $mpd->id,
                    'perm_id' => (int) $permission->id,
                ))->delete('aauth_perm_to_group');
            }
        }

        $setup = $this->db->where('name', 'menu_setup')->get('aauth_perms')->row();
        if ($setup) {
            $link = array('group_id' => (int) $mpd->id, 'perm_id' => (int) $setup->id);
            if (!$this->db->where($link)->count_all_results('aauth_perm_to_group')) {
                $this->db->insert('aauth_perm_to_group', $link);
            }
        }
    }
}
