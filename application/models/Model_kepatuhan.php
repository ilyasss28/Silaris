<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_kepatuhan extends CI_Model
{
    function __construct() {
    parent:: __construct();
    }

    /** Public compliance uses the same canonical `laporan` table as backend. */
    public function get_compliance($q = null)
    {
        $start = date('Y-01-01');
        $end = date('Y-m-d');
        $owner_join = $this->report_owner_join('reports', 'users')
            . ' AND reports.Tanggal_Laporan >= ' . $this->db->escape($start)
            . ' AND reports.Tanggal_Laporan <= ' . $this->db->escape($end);

        $this->db->select("users.id, users.username, users.full_name, notary_profiles.kode_wilayah AS kd_wilayah,
            COUNT(DISTINCT reports.id) AS jumlah_laporan,
            MAX(reports.Tanggal_Laporan) AS laporan_terakhir", false);
        $this->db->from('data_notaris notary_profiles');
        $this->db->join('aauth_users users', 'users.id = notary_profiles.user_id', 'inner');
        $this->db->join('aauth_user_to_group memberships', 'memberships.user_id = users.id');
        $this->db->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'");
        $this->db->join('laporan reports', $owner_join, 'left', false);
        $this->db->where('users.banned', 0);
        if ($this->db->field_exists('is_verified', 'aauth_users')) {
            $this->db->where('users.is_verified', 1);
        }
        $this->db->where("UPPER(TRIM(notary_profiles.status_notaris)) = 'NOTARIS AKTIF'", null, false);
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('users.full_name', $q);
            $this->db->or_like('users.username', $q);
            $this->db->group_end();
        }
        $this->db->group_by(array('users.id', 'users.username', 'users.full_name', 'notary_profiles.kode_wilayah'));
        $this->db->order_by('jumlah_laporan', 'DESC');
        $this->db->order_by('users.full_name', 'ASC');

        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_compliance (Model_kepatuhan): ' . $this->db->last_query());
            return [];
        }
        $required_months = $this->current_year_required_months();
        $rows = $query->result();
        $reported_months_by_user = $this->reported_months_by_user(array_map(function ($row) {
            return (int) $row->id;
        }, $rows), $start, $end);
        foreach ($rows as $row) {
            $reported_months = isset($reported_months_by_user[(int) $row->id])
                ? array_keys($reported_months_by_user[(int) $row->id])
                : [];
            sort($reported_months, SORT_STRING);
            $missing_months = array_values(array_diff(array_keys($required_months), $reported_months));
            $row->jumlah_bulan_laporan = count(array_intersect(array_keys($required_months), $reported_months));
            $row->jumlah_bulan_wajib = count($required_months);
            $row->bulan_belum_dilaporkan = $missing_months
                ? implode(', ', array_map(function ($month) use ($required_months) { return $required_months[$month]; }, $missing_months))
                : '-';
            $row->status_kepatuhan = $required_months && !$missing_months ? 'submitted' : 'missing';
        }

        return $rows;
    }

    private function reported_months_by_user(array $user_ids, $start, $end)
    {
        $user_ids = array_values(array_unique(array_filter(array_map('intval', $user_ids))));
        if (!$user_ids) {
            return [];
        }

        $rows = $this->db
            ->distinct()
            ->select("users.id AS user_id, DATE_FORMAT(reports.Tanggal_Laporan, '%Y-%m') AS report_month", false)
            ->from('aauth_users users')
            ->join('laporan reports', $this->report_owner_join('reports', 'users'), 'inner', false)
            ->where_in('users.id', $user_ids)
            ->where('reports.Tanggal_Laporan >=', $start)
            ->where('reports.Tanggal_Laporan <=', $end)
            ->get()
            ->result_array();

        $months_by_user = [];
        foreach ($rows as $row) {
            $user_id = (int) $row['user_id'];
            $month = trim((string) $row['report_month']);
            if ($user_id > 0 && preg_match('/^\d{4}-\d{2}$/', $month)) {
                $months_by_user[$user_id][$month] = true;
            }
        }

        return $months_by_user;
    }

    /**
     * Summary counters for the compliance page's stat tiles.
     */
    public function get_compliance_summary()
    {
        $rows = $this->get_compliance();
        $total_akun = count($rows);
        $total_laporan = array_sum(array_map(function ($row) { return (int) $row->jumlah_laporan; }, $rows));
        $aktif_melapor = count(array_filter($rows, function ($row) { return $row->status_kepatuhan === 'submitted'; }));

        return [
            'total_notaris'  => (int) $total_akun,
            'total_laporan'  => (int) $total_laporan,
            'aktif_melapor'  => (int) $aktif_melapor,
            'tingkat_persen' => $total_akun > 0 ? round(($aktif_melapor / $total_akun) * 100) : 0,
            'periode_awal'   => date('Y-01-01'),
            'periode_akhir'  => date('Y-m-d'),
        ];
    }

    private function current_year_required_months()
    {
        $month_names = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $months = [];
        for ($month = 1; $month <= (int) date('n'); $month++) {
            $months[sprintf('%04d-%02d', (int) date('Y'), $month)] = $month_names[$month];
        }
        return $months;
    }

    private function report_owner_join($report_alias, $user_alias)
    {
        if ($this->db->field_exists('owner_user_id', 'laporan')) {
            return '(' . $report_alias . '.owner_user_id = ' . $user_alias . '.id'
                . ' OR ((' . $report_alias . '.owner_user_id IS NULL OR ' . $report_alias . '.owner_user_id = 0) AND LOWER('
                . $report_alias . '.username) = LOWER(' . $user_alias . '.username)))';
        }

        return 'LOWER(' . $report_alias . '.username) = LOWER(' . $user_alias . '.username)';
    }

    public function get_db(){
    $this->db->select('*');
        $this->db->from('laporan_bulan_2024');
        $this->db->order_by('nama_notaris', 'ASC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_db (Model_kepatuhan): ' . $this->db->last_query());
            return [];
        }
        return $query->result_array();

    }

    public function kendari(){
        $this->db->select('*');
        $this->db->from('mpd_baubau');
        $this->db->order_by('kd', 'ASC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in baubau (Model_kepatuhan): ' . $this->db->last_query());
            return [];
        }
        return $query->result_array();
    
        }
    
        public function baubau(){
            $this->db->select('*');
        $this->db->from('mpd_baubau');
        $this->db->order_by('kd', 'ASC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in baubau (Model_kepatuhan): ' . $this->db->last_query());
            return [];
        }
        return $query->result_array();
        
            }
        
            public function kolaka(){
                $this->db->select('*');
                $this->db->from('mpd_kolaka');
                $this->db->order_by('kd', 'ASC');
                $query = $this->db->get();
                if ($query === FALSE) {
                    log_message('error', 'Database query failed in kolaka (Model_kepatuhan): ' . $this->db->last_query());
                    return [];
                }
                return $query->result_array();

                }
            
                            
}
