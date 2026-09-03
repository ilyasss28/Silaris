<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_dashboard extends CI_Model
{
    private $services = [
        ['key' => 'daftar_proses', 'label' => 'Daftar Proses', 'table' => 'daftar_proses', 'date' => 'tanggal_akta', 'icon' => 'fa-list-alt'],
        ['key' => 'legalisasi', 'label' => 'Legalisasi', 'table' => 'legalisasi', 'date' => 'tanggal_akta', 'icon' => 'fa-certificate'],
        ['key' => 'reportorium', 'label' => 'Repertorium', 'table' => 'reportorium', 'date' => 'tanggal_akta', 'icon' => 'fa-book'],
        ['key' => 'waarmerking', 'label' => 'Waarmerking', 'table' => 'waarmerking', 'date' => 'tanggal_akta', 'icon' => 'fa-check-square-o'],
        ['key' => 'fidusia', 'label' => 'Fidusia', 'table' => 'fidusia', 'date' => 'tanggal', 'icon' => 'fa-file-text-o'],
    ];

    public function resolve_role(array $group_names)
    {
        foreach (['Admin', 'Kanwil', 'MPD', 'User'] as $role) {
            if (in_array($role, $group_names, true)) {
                return $role;
            }
        }

        return 'User';
    }

    public function build($user_id, $role)
    {
        $user = $this->db->get_where('aauth_users', ['id' => (int) $user_id])->row();
        $username = $user ? (string) $user->username : '';
        $region_code = $user ? trim((string) $user->kd_wilayah) : '';
        $profile = in_array($role, ['Admin', 'Kanwil'], true)
            ? 'executive'
            : ($role === 'MPD' ? 'mpd' : 'user');
        $scope_username = $profile === 'user' ? $username : null;
        $scope_region = $profile === 'mpd' ? $region_code : null;

        $report_count = $this->count_records('laporan', 'Tanggal_Laporan', $scope_username, $scope_region, true);
        $service_breakdown = $this->service_breakdown($scope_username, $scope_region);
        $service_total = array_sum(array_column($service_breakdown, 'total'));
        $compliance = $this->compliance($scope_username, $scope_region, $profile);
        $region_name = $scope_region ? $this->region_name($scope_region) : null;

        return [
            'dashboard_profile' => $profile,
            'dashboard_role' => $role,
            'dashboard_region' => $region_name,
            'dashboard_period' => date('Y'),
            'dashboard_stats' => $this->stats($profile, $report_count, $service_total, $compliance, $scope_username, $scope_region),
            'dashboard_services' => $service_breakdown,
            'dashboard_trend' => $this->monthly_trend($scope_username, $scope_region),
            'dashboard_compliance' => $compliance,
            'dashboard_recent' => $this->recent_reports($scope_username, $scope_region),
            'dashboard_regions' => $profile === 'executive' ? $this->regional_distribution() : [],
            'dashboard_attention' => $profile !== 'user' ? $this->notaries_needing_attention($scope_region) : [],
            'dashboard_quick_links' => $this->quick_links($profile),
        ];
    }

    private function stats($profile, $report_count, $service_total, array $compliance, $username, $region_code)
    {
        if ($profile === 'user') {
            $total_reports = $this->count_records('laporan', 'Tanggal_Laporan', $username, null, false);
            $latest = $this->latest_report_date($username);

            return [
                ['label' => 'Laporan Tahun Ini', 'value' => $report_count, 'detail' => 'Laporan tercatat sampai hari ini', 'icon' => 'fa-file-text-o', 'tone' => 'navy'],
                ['label' => 'Aktivitas Layanan', 'value' => $service_total, 'detail' => 'Total layanan pada tahun berjalan', 'icon' => 'fa-line-chart', 'tone' => 'gold'],
                ['label' => 'Total Riwayat Laporan', 'value' => $total_reports, 'detail' => 'Seluruh laporan yang pernah dikirim', 'icon' => 'fa-archive', 'tone' => 'blue'],
                ['label' => 'Laporan Terakhir', 'value' => $latest ? $this->format_date($latest) : 'Belum ada', 'detail' => $latest ? 'Tanggal pengiriman terakhir' : 'Segera lengkapi laporan Anda', 'icon' => 'fa-calendar-check-o', 'tone' => $latest ? 'green' : 'red'],
            ];
        }

        if ($profile === 'mpd') {
            return [
                ['label' => 'Notaris Wilayah', 'value' => $compliance['total'], 'detail' => 'Akun notaris dalam wilayah pengawasan', 'icon' => 'fa-users', 'tone' => 'navy'],
                ['label' => 'Laporan Tahun Ini', 'value' => $report_count, 'detail' => 'Laporan dari wilayah sampai hari ini', 'icon' => 'fa-file-text-o', 'tone' => 'gold'],
                ['label' => 'Aktivitas Layanan', 'value' => $service_total, 'detail' => 'Aktivitas layanan wilayah tahun berjalan', 'icon' => 'fa-line-chart', 'tone' => 'blue'],
                ['label' => 'Kepatuhan Pelaporan', 'value' => $compliance['percentage'] . '%', 'detail' => $compliance['submitted'] . ' dari ' . $compliance['total'] . ' notaris telah melapor', 'icon' => 'fa-shield', 'tone' => $compliance['percentage'] >= 75 ? 'green' : 'red'],
            ];
        }

        $active_notaries = (int) $this->db
            ->where("UPPER(TRIM(status_notaris)) = 'NOTARIS AKTIF'", null, false)
            ->count_all_results('data_notaris');

        return [
            ['label' => 'Notaris Aktif', 'value' => $active_notaries, 'detail' => 'Dari ' . $this->db->count_all('data_notaris') . ' data notaris terdaftar', 'icon' => 'fa-users', 'tone' => 'navy'],
            ['label' => 'Laporan Tahun Ini', 'value' => $report_count, 'detail' => 'Laporan tercatat sampai hari ini', 'icon' => 'fa-file-text-o', 'tone' => 'gold'],
            ['label' => 'Aktivitas Layanan', 'value' => $service_total, 'detail' => 'Akumulasi lima layanan tahun berjalan', 'icon' => 'fa-line-chart', 'tone' => 'blue'],
            ['label' => 'Kepatuhan Pelaporan', 'value' => $compliance['percentage'] . '%', 'detail' => $compliance['submitted'] . ' dari ' . $compliance['total'] . ' notaris telah melapor', 'icon' => 'fa-shield', 'tone' => $compliance['percentage'] >= 75 ? 'green' : 'red'],
        ];
    }

    private function service_breakdown($username = null, $region_code = null)
    {
        $result = [];
        foreach ($this->services as $service) {
            $service['total'] = $this->count_records($service['table'], $service['date'], $username, $region_code, true);
            $result[] = $service;
        }

        return $result;
    }

    private function count_records($table, $date_column, $username = null, $region_code = null, $current_year = true)
    {
        if ($table === 'laporan') {
            return $this->count_valid_reports($username, $region_code, $current_year);
        }

        $this->db->from($table . ' dashboard_records');
        if ($region_code !== null) {
            $this->db->join('aauth_users dashboard_users', 'dashboard_users.username = dashboard_records.username');
            $this->db->where('dashboard_users.kd_wilayah', $region_code);
        }
        if ($username !== null) {
            $this->db->where('dashboard_records.username', $username);
        }
        if ($current_year) {
            $this->db->where('dashboard_records.' . $date_column . ' >=', date('Y-01-01'));
        }
        $this->db->where('dashboard_records.' . $date_column . ' <=', date('Y-m-d'));

        return (int) $this->db->count_all_results();
    }

    private function count_valid_reports($username = null, $region_code = null, $current_year = true)
    {
        $owner_join = $this->report_owner_join('dashboard_records', 'dashboard_users');
        $this->db
            ->from('laporan dashboard_records')
            ->join('aauth_users dashboard_users', $owner_join, 'inner', false)
            ->join('aauth_user_to_group memberships', 'memberships.user_id = dashboard_users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('dashboard_users.banned', 0);
        if ($region_code !== null) {
            $this->db->where('dashboard_users.kd_wilayah', $region_code);
        }
        if ($username !== null) {
            $this->db->where('dashboard_users.username', $username);
        }
        if ($current_year) {
            $this->db->where('dashboard_records.Tanggal_Laporan >=', date('Y-01-01'));
        }
        $this->db->where('dashboard_records.Tanggal_Laporan <=', date('Y-m-d'));

        $row = $this->db->select('COUNT(DISTINCT dashboard_records.id) AS total', false)->get()->row();
        return $row ? (int) $row->total : 0;
    }

    private function report_owner_join($report_alias, $user_alias)
    {
        if ($this->db->field_exists('owner_user_id', 'laporan')) {
            return '(' . $report_alias . '.owner_user_id = ' . $user_alias . '.id'
                . ' OR (' . $report_alias . '.owner_user_id IS NULL AND LOWER('
                . $report_alias . '.username) = LOWER(' . $user_alias . '.username)))';
        }

        return 'LOWER(' . $report_alias . '.username) = LOWER(' . $user_alias . '.username)';
    }

    private function compliance($username, $region_code, $profile)
    {
        if ($profile === 'user') {
            $submitted = $this->count_records('laporan', 'Tanggal_Laporan', $username, null, true) > 0 ? 1 : 0;
            return ['total' => 1, 'submitted' => $submitted, 'missing' => 1 - $submitted, 'percentage' => $submitted * 100];
        }

        $this->db->select('COUNT(DISTINCT users.id) AS total', false)
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'");
        if ($region_code !== null) {
            $this->db->where('users.kd_wilayah', $region_code);
        }
        $total_row = $this->db->get()->row();
        $total = $total_row ? (int) $total_row->total : 0;

        $this->db->select('COUNT(DISTINCT reports.username) AS submitted', false)
            ->from('laporan reports')
            ->join('aauth_users users', 'users.username = reports.username')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('reports.Tanggal_Laporan >=', date('Y-01-01'))
            ->where('reports.Tanggal_Laporan <=', date('Y-m-d'));
        if ($region_code !== null) {
            $this->db->where('users.kd_wilayah', $region_code);
        }
        $submitted_row = $this->db->get()->row();
        $submitted = $submitted_row ? (int) $submitted_row->submitted : 0;

        return [
            'total' => $total,
            'submitted' => $submitted,
            'missing' => max(0, $total - $submitted),
            'percentage' => $total > 0 ? (int) round(($submitted / $total) * 100) : 0,
        ];
    }

    private function monthly_trend($username = null, $region_code = null)
    {
        $months = [];
        $current_month = (int) date('n');
        $start_month = max(1, $current_month - 5);
        $month_names = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        for ($month = $start_month; $month <= $current_month; $month++) {
            $months[$month] = ['label' => $month_names[$month], 'total' => 0];
        }

        $sources = array_merge(
            [['table' => 'laporan', 'date' => 'Tanggal_Laporan']],
            array_map(function ($service) { return ['table' => $service['table'], 'date' => $service['date']]; }, $this->services)
        );

        foreach ($sources as $source) {
            $this->db->select('MONTH(records.' . $source['date'] . ') AS month_number, COUNT(*) AS total', false)
                ->from($source['table'] . ' records')
                ->where('records.' . $source['date'] . ' >=', date('Y-') . str_pad($start_month, 2, '0', STR_PAD_LEFT) . '-01')
                ->where('records.' . $source['date'] . ' <=', date('Y-m-d'));
            if ($username !== null) {
                $this->db->where('records.username', $username);
            }
            if ($region_code !== null) {
                $this->db->join('aauth_users users', 'users.username = records.username');
                $this->db->where('users.kd_wilayah', $region_code);
            }
            $rows = $this->db->group_by('MONTH(records.' . $source['date'] . ')')->get()->result();
            foreach ($rows as $row) {
                $month_number = (int) $row->month_number;
                if (isset($months[$month_number])) {
                    $months[$month_number]['total'] += (int) $row->total;
                }
            }
        }

        return array_values($months);
    }

    private function recent_reports($username = null, $region_code = null)
    {
        $this->db->select("reports.id, reports.username, reports.Tanggal_Laporan AS report_date, COALESCE(NULLIF(reports.nama_notaris, ''), users.full_name, reports.username) AS display_name", false)
            ->from('laporan reports')
            ->join('aauth_users users', 'users.username = reports.username', 'left')
            ->where('reports.Tanggal_Laporan <=', date('Y-m-d'));
        if ($username !== null) {
            $this->db->where('reports.username', $username);
        }
        if ($region_code !== null) {
            $this->db->where('users.kd_wilayah', $region_code);
        }

        return $this->db->order_by('reports.Tanggal_Laporan', 'DESC')->order_by('reports.id', 'DESC')->limit(6)->get()->result_array();
    }

    private function regional_distribution()
    {
        return $this->db
            ->select('regions.nama AS label, COUNT(notaries.id_notaris) AS total', false)
            ->from('wilayah regions')
            ->join('data_notaris notaries', "(notaries.kode_wilayah = regions.kd_wilayah OR UPPER(TRIM(notaries.wilayah)) = UPPER(TRIM(regions.nama))) AND UPPER(TRIM(notaries.status_notaris)) = 'NOTARIS AKTIF'", 'left', false)
            ->where('regions.kd_wilayah !=', '')
            ->where('regions.nama IS NOT NULL', null, false)
            ->group_by(['regions.id', 'regions.kd_wilayah', 'regions.nama'])
            ->order_by('total', 'DESC')
            ->order_by('regions.nama', 'ASC')
            ->get()
            ->result_array();
    }

    private function notaries_needing_attention($region_code = null)
    {
        $this->db->select('users.id, users.username, users.full_name, regions.nama AS region_name, MAX(reports.Tanggal_Laporan) AS last_report', false)
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->join('wilayah regions', 'regions.kd_wilayah = users.kd_wilayah', 'left')
            ->join('laporan reports', "reports.username = users.username AND reports.Tanggal_Laporan <= '" . date('Y-m-d') . "'", 'left')
            ->where("NOT EXISTS (SELECT 1 FROM laporan current_reports WHERE current_reports.username = users.username AND current_reports.Tanggal_Laporan >= '" . date('Y-01-01') . "' AND current_reports.Tanggal_Laporan <= '" . date('Y-m-d') . "')", null, false);
        if ($region_code !== null) {
            $this->db->where('users.kd_wilayah', $region_code);
        }

        return $this->db
            ->group_by(['users.id', 'users.username', 'users.full_name', 'regions.nama'])
            ->order_by('last_report', 'ASC')
            ->order_by('users.full_name', 'ASC')
            ->limit(6)
            ->get()
            ->result_array();
    }

    private function latest_report_date($username)
    {
        $row = $this->db
            ->select_max('Tanggal_Laporan', 'latest')
            ->where('username', $username)
            ->where('Tanggal_Laporan <=', date('Y-m-d'))
            ->get('laporan')
            ->row();

        return $row ? $row->latest : null;
    }

    private function region_name($region_code)
    {
        $region = $this->db->select('nama')->get_where('wilayah', ['kd_wilayah' => $region_code])->row();
        return $region ? $region->nama : 'Wilayah ' . $region_code;
    }

    private function format_date($date)
    {
        $formatted = format_date_id($date);
        return $formatted !== '' ? $formatted : '-';
    }

    private function quick_links($profile)
    {
        if ($profile === 'user') {
            return [
                ['label' => 'Laporan Saya', 'description' => 'Kelola laporan yang telah Anda kirim.', 'url' => site_url('laporan'), 'icon' => 'fa-file-text-o'],
                ['label' => 'Repertorium', 'description' => 'Catat dan periksa data repertorium.', 'url' => site_url('reportorium'), 'icon' => 'fa-book'],
                ['label' => 'Legalisasi', 'description' => 'Kelola pencatatan legalisasi.', 'url' => site_url('legalisasi'), 'icon' => 'fa-certificate'],
                ['label' => 'Profil Saya', 'description' => 'Perbarui informasi dan keamanan akun.', 'url' => site_url('administrator/profile'), 'icon' => 'fa-user'],
            ];
        }

        return [
            ['label' => 'Rekap Laporan', 'description' => 'Pantau seluruh laporan yang telah masuk.', 'url' => site_url('rekap-laporan'), 'icon' => 'fa-file-text-o'],
            ['label' => 'Data Notaris', 'description' => 'Lihat basis data notaris terdaftar.', 'url' => site_url('data_notaris'), 'icon' => 'fa-book'],
            ['label' => 'Kepatuhan', 'description' => 'Tinjau kepatuhan pelaporan notaris.', 'url' => site_url('kepatuhan'), 'icon' => 'fa-shield'],
            ['label' => 'Profil Saya', 'description' => 'Perbarui informasi dan keamanan akun.', 'url' => site_url('administrator/profile'), 'icon' => 'fa-user'],
        ];
    }
}
