<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Remove the redundant Profile-only MASTER DATA group from the sidebar.
 * The profile routes remain available through the account controls.
 */
class Migration_remove_redundant_master_data_menu extends CI_Migration
{
    public function up()
    {
        $parents = $this->db
            ->where('menu_type_id', 1)
            ->where('parent', 0)
            ->where('UPPER(label) =', 'MASTER DATA')
            ->get('menu')
            ->result();

        foreach ($parents as $parent) {
            $this->db
                ->where('parent', (int) $parent->id)
                ->group_start()
                    ->where('LOWER(label) =', 'profil')
                    ->or_where_in('LOWER(link)', array('profile', 'administrator/profile'))
                ->group_end()
                ->update('menu', array('active' => 0));

            $remaining_children = $this->db
                ->where('parent', (int) $parent->id)
                ->where('active', 1)
                ->count_all_results('menu');

            if ($remaining_children === 0) {
                $this->db
                    ->where('id', (int) $parent->id)
                    ->update('menu', array('active' => 0));
            }
        }
    }

    public function down()
    {
        $parents = $this->db
            ->where('menu_type_id', 1)
            ->where('parent', 0)
            ->where('UPPER(label) =', 'MASTER DATA')
            ->get('menu')
            ->result();

        foreach ($parents as $parent) {
            $this->db
                ->where('id', (int) $parent->id)
                ->update('menu', array('active' => 1));

            $this->db
                ->where('parent', (int) $parent->id)
                ->group_start()
                    ->where('LOWER(label) =', 'profil')
                    ->or_where_in('LOWER(link)', array('profile', 'administrator/profile'))
                ->group_end()
                ->update('menu', array('active' => 1));
        }
    }
}
