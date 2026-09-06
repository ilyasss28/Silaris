<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Central role and jurisdiction policy for records owned by a user account.
 *
 * This policy is intentionally applied by the models so list filtering cannot
 * be bypassed by opening a detail/edit/delete URL directly.
 */
class Report_access
{
    private $ci;
    private $policy_db;
    private $role_priority = array('Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan', 'MPD', 'User');

    public function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->database();
        // Keep policy lookups isolated from the report query builder. Using the
        // same builder here would accidentally carry report JOIN/WHERE clauses
        // into the verification query and corrupt the report query afterwards.
        $this->policy_db = $this->ci->load->database('default', true);
        $this->ci->load->library('aauth');
    }

    public function current_role()
    {
        $groups = $this->ci->aauth->get_user_groups();
        $group_names = array();
        foreach ($groups as $group) {
            $group_names[] = trim((string) $group->name);
        }

        foreach ($this->role_priority as $role) {
            if (in_array($role, $group_names, true)) {
                return $role;
            }
        }

        return null;
    }

    public function can_view_all()
    {
        return in_array($this->current_role(), array('Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan'), true);
    }

    /** Apply the current account's report scope to a CI query builder. */
    public function apply_scope($db, $report_alias = 'laporan')
    {
        $role = $this->current_role();
        if (in_array($role, array('Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan'), true)) {
            return;
        }

        $user_id = (int) $this->ci->session->userdata('id');
        if ($user_id <= 0) {
            $db->where('1 = 0', null, false);
            return;
        }

        // Some newer tables have an immutable owner_user_id, while legacy
        // service tables (including Fidusia) are still linked by username.
        // Inspect the actual scoped table so this policy can safely serve both.
        $table_name = preg_split('/\s+/', trim($report_alias, " `\t\n\r\0\x0B"))[0];
        $has_stable_owner = $table_name !== ''
            && $this->policy_db->table_exists($table_name)
            && $this->policy_db->field_exists('owner_user_id', $table_name);

        if ($role === 'MPD') {
            // Deployment safety: if application files arrive before migration
            // 004, deny the query instead of exposing data or raising SQL errors.
			if (!$this->policy_db->table_exists('mpd_wilayah') || !$this->policy_db->table_exists('data_mpd')) {
				$db->where('1 = 0', null, false);
				return;
			}
			$verified_profile = $this->policy_db
				->where('user_id', $user_id)
				->where('is_verified', 1)
				->count_all_results('data_mpd') > 0;
			if (!$verified_profile) {
				$db->where('1 = 0', null, false);
				return;
			}

            $quoted_alias = '`' . str_replace('`', '', $report_alias) . '`';
            $owner_match = 'LOWER(report_owner.username) = LOWER(' . $quoted_alias . '.username)';
            if ($has_stable_owner) {
                $owner_match = '(report_owner.id = ' . $quoted_alias . '.owner_user_id '
                    . 'OR ((' . $quoted_alias . '.owner_user_id IS NULL OR '
                    . $quoted_alias . '.owner_user_id = 0) AND ' . $owner_match . '))';
            }
            $db->where(
                'EXISTS ('
                . 'SELECT 1 FROM aauth_users AS report_owner '
                . 'INNER JOIN data_notaris AS report_notary '
                . 'ON report_notary.user_id = report_owner.id '
                . 'INNER JOIN mpd_wilayah AS jurisdiction '
                . 'ON jurisdiction.kode_wilayah = report_notary.kode_wilayah '
                . 'AND jurisdiction.user_id = ' . $user_id . ' '
                . 'WHERE ' . $owner_match
                . ')',
                null,
                false
            );
            return;
        }

        if ($role === 'User') {
            if ($has_stable_owner) {
                $quoted_alias = '`' . str_replace('`', '', $report_alias) . '`';
                $db->where(
                    '(' . $quoted_alias . '.owner_user_id = ' . $user_id
                    . ' OR ((' . $quoted_alias . '.owner_user_id IS NULL OR '
                    . $quoted_alias . '.owner_user_id = 0) AND LOWER('
                    . $quoted_alias . '.username) = LOWER('
                    . $this->policy_db->escape((string) get_user_data('username')) . ')))',
                    null,
                    false
                );
            } else {
                $db->where($report_alias . '.username', (string) get_user_data('username'));
            }
            return;
        }

        // Unknown/public roles must fail closed.
        $db->where('1 = 0', null, false);
    }
}
