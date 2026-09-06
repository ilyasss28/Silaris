<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Normalize legacy region slugs and keep the User account region aligned with
 * the authoritative Data Notaris registry.
 */
class Migration_synchronize_notary_regions extends CI_Migration
{
    public function up()
    {
        if (!$this->db->table_exists('data_notaris') || !$this->db->table_exists('aauth_users')) {
            return;
        }

        $map = array(
            'kolaka' => '7401', 'konawe' => '7402', 'muna' => '7403',
            'buton' => '7404', 'konsel' => '7405', 'bombana' => '7406',
            'wakatobi' => '7407', 'kolut' => '7408', 'konut' => '7409',
            'butur' => '7410', 'koltim' => '7411', 'konkep' => '7412',
            'mubar' => '7413', 'buteng' => '7414', 'busel' => '7415',
            'kendari' => '7471', 'baubau' => '7472',
        );
        foreach ($map as $legacy => $official) {
            $this->db
                ->where('LOWER(TRIM(kode_wilayah)) = ' . $this->db->escape($legacy), null, false)
                ->update('data_notaris', array('kode_wilayah' => $official));
        }

        if ($this->db->table_exists('wilayah')) {
            $this->db->query(
                'UPDATE data_notaris registry '
                . 'INNER JOIN wilayah regions ON regions.kd_wilayah = registry.kode_wilayah '
                . 'SET registry.wilayah = regions.nama '
                . "WHERE TRIM(COALESCE(regions.nama, '')) <> ''"
            );
        }

        if ($this->db->field_exists('user_id', 'data_notaris')) {
            $this->db->query(
                'UPDATE aauth_users users '
                . 'INNER JOIN data_notaris registry ON registry.user_id = users.id '
                . 'INNER JOIN aauth_user_to_group memberships ON memberships.user_id = users.id '
                . 'INNER JOIN aauth_groups groups_table ON groups_table.id = memberships.group_id '
                . "AND groups_table.name = 'User' "
                . 'SET users.kd_wilayah = registry.kode_wilayah '
                . "WHERE TRIM(COALESCE(registry.kode_wilayah, '')) <> '' "
                . "AND TRIM(COALESCE(users.kd_wilayah, '')) <> TRIM(registry.kode_wilayah)"
            );
        }
    }

    public function down()
    {
        // Canonical region codes are business data and must not be reverted to
        // ambiguous legacy slugs or mismatched account values.
    }
}
