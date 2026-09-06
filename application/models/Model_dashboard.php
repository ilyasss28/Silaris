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
        foreach (['Admin', 'Kanwil', 'Pimpinan', 'MPD', 'User'] as $role) {
            if (in_array($role, $group_names, true)) {
                return $role;
            }
        }

        return 'User';
    }

    public function build($user_id, $role, array $chart_filter = [])
    {
        $user = $this->db->get_where('aauth_users', ['id' => (int) $user_id])->row();
        $username = $user ? (string) $user->username : '';
        $profile = in_array($role, ['Admin', 'Kanwil', 'Pimpinan'], true)
            ? 'executive'
            : ($role === 'MPD' ? 'mpd' : 'user');
        $scope_username = $profile === 'user' ? $username : null;
        $scope_regions = $profile === 'mpd' ? $this->mpd_region_codes((int) $user_id) : null;
        $chart_period = $this->normalize_chart_period($chart_filter);

        $report_count = $this->count_records('laporan', 'Tanggal_Laporan', $scope_username, $scope_regions, true);
        $current_year_services = $this->service_breakdown($scope_username, $scope_regions);
        $service_total = array_sum(array_column($current_year_services, 'total'));
        $chart_services = $this->filtered_service_breakdown($chart_period, $scope_username, $scope_regions);
        $compliance_rows = $profile === 'user'
            ? []
            : $this->compliance_notaries($scope_username, $scope_regions, $chart_period);
        $compliance = $this->compliance_summary($compliance_rows);
        $region_name = $scope_regions !== null ? $this->region_names($scope_regions) : null;

        return [
            'dashboard_profile' => $profile,
            'dashboard_role' => $role,
            'dashboard_region' => $region_name,
            'dashboard_period' => $this->chart_period_label($chart_period),
            'dashboard_chart_filter' => $chart_period,
            'dashboard_chart_years' => $this->available_chart_years($scope_username, $scope_regions),
            'dashboard_stats' => $this->stats($profile, $report_count, $service_total, $compliance, $scope_username, $scope_regions),
            'dashboard_services' => $chart_services,
            'dashboard_trend' => $this->filtered_trend($chart_period, $scope_username, $scope_regions),
            'dashboard_compliance' => $compliance,
            'dashboard_compliance_rows' => $compliance_rows,
            'dashboard_regions' => $profile === 'executive' ? $this->regional_distribution() : [],
            'dashboard_attention' => $profile !== 'user' ? $this->notaries_needing_attention($scope_regions) : [],
            'dashboard_quick_links' => $this->quick_links($profile),
        ];
    }

    public function compliance_export($user_id, $role, array $chart_filter = [], $status = 'all')
    {
        $user = $this->db->get_where('aauth_users', ['id' => (int) $user_id])->row();
        $username = $user ? (string) $user->username : '';
        $profile = in_array($role, ['Admin', 'Kanwil', 'Pimpinan'], true)
            ? 'executive'
            : ($role === 'MPD' ? 'mpd' : 'user');
        $period = $this->normalize_chart_period($chart_filter);
        $rows = $this->compliance_notaries(
            $profile === 'user' ? $username : null,
            $profile === 'mpd' ? $this->mpd_region_codes((int) $user_id) : null,
            $period
        );
        if (in_array($status, ['submitted', 'missing'], true)) {
            $rows = array_values(array_filter($rows, function ($row) use ($status) {
                return $row['status'] === $status;
            }));
        }

        return [
            'period' => $period,
            'period_label' => $this->chart_period_label($period),
            'rows' => $rows,
        ];
    }

    private function stats($profile, $report_count, $service_total, array $compliance, $username, $region_codes)
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

    private function service_breakdown($username = null, $region_codes = null)
    {
        $result = [];
        foreach ($this->services as $service) {
            $service['total'] = $this->count_records($service['table'], $service['date'], $username, $region_codes, true);
            $result[] = $service;
        }

        return $result;
    }

    private function normalize_chart_period(array $filter)
    {
        $mode = isset($filter['mode']) ? strtolower(trim((string) $filter['mode'])) : 'year';
        if (!in_array($mode, ['month', 'quarter', 'semester', 'year'], true)) {
            $mode = 'year';
        }

        $current_year = (int) date('Y');
        $year = filter_var(isset($filter['year']) ? $filter['year'] : $current_year, FILTER_VALIDATE_INT);
        if ($year === false || $year < 2000 || $year > $current_year) {
            $year = $current_year;
        }

        $month = filter_var(isset($filter['month']) ? $filter['month'] : date('n'), FILTER_VALIDATE_INT);
        if ($month === false || $month < 1 || $month > 12) {
            $month = (int) date('n');
        }

        $quarter = filter_var(isset($filter['quarter']) ? $filter['quarter'] : ceil(date('n') / 3), FILTER_VALIDATE_INT);
        if ($quarter === false || $quarter < 1 || $quarter > 4) {
            $quarter = (int) ceil(date('n') / 3);
        }

        $semester = filter_var(isset($filter['semester']) ? $filter['semester'] : ceil(date('n') / 6), FILTER_VALIDATE_INT);
        if ($semester === false || $semester < 1 || $semester > 2) {
            $semester = (int) ceil(date('n') / 6);
        }

        return [
            'mode' => $mode,
            'year' => (int) $year,
            'month' => (int) $month,
            'quarter' => (int) $quarter,
            'semester' => (int) $semester,
        ];
    }

    private function chart_period_label(array $period)
    {
        if ($period['mode'] === 'month') {
            $months = [1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return $months[$period['month']] . ' ' . $period['year'];
        }

        if ($period['mode'] === 'quarter') {
            return 'Triwulan ' . $this->roman_number($period['quarter']) . ' ' . $period['year'];
        }

        if ($period['mode'] === 'semester') {
            return 'Semester ' . $this->roman_number($period['semester']) . ' ' . $period['year'];
        }

        return (string) $period['year'];
    }

    private function roman_number($number)
    {
        $roman = [1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV'];
        return isset($roman[(int) $number]) ? $roman[(int) $number] : (string) $number;
    }

    private function filtered_service_breakdown(array $period, $username = null, $region_codes = null)
    {
        $result = [];
        foreach ($this->services as $service) {
            $rows = $this->aggregate_source($service, '1', $period, $username, $region_codes);
            $service['total'] = $rows ? array_sum(array_column($rows, 'total')) : 0;
            $result[] = $service;
        }

        return $result;
    }

    private function filtered_trend(array $period, $username = null, $region_codes = null)
    {
        $buckets = $this->trend_buckets($period);
        $group_expression = $period['mode'] === 'month'
            ? 'DAY(records.%s)'
            : 'MONTH(records.%s)';
        $sources = array_merge(
            [['table' => 'laporan', 'date' => 'Tanggal_Laporan']],
            array_map(function ($service) { return ['table' => $service['table'], 'date' => $service['date']]; }, $this->services)
        );

        foreach ($sources as $source) {
            $expression = sprintf($group_expression, $source['date']);
            foreach ($this->aggregate_source($source, $expression, $period, $username, $region_codes) as $row) {
                $key = (int) $row['bucket_key'];
                if (isset($buckets[$key])) {
                    $buckets[$key]['total'] += (int) $row['total'];
                }
            }
        }

        return array_values($buckets);
    }

    private function trend_buckets(array $period)
    {
        $buckets = [];
        if ($period['mode'] === 'month') {
            $days = (int) date('t', strtotime(sprintf('%04d-%02d-01', $period['year'], $period['month'])));
            for ($day = 1; $day <= $days; $day++) {
                $buckets[$day] = ['label' => (string) $day, 'total' => 0];
            }
            return $buckets;
        }

        $month_names = [1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $start_month = 1;
        $end_month = 12;
        if ($period['mode'] === 'quarter') {
            $start_month = (($period['quarter'] - 1) * 3) + 1;
            $end_month = $start_month + 2;
        } elseif ($period['mode'] === 'semester') {
            $start_month = (($period['semester'] - 1) * 6) + 1;
            $end_month = $start_month + 5;
        }
        foreach ($month_names as $month => $label) {
            if ($month < $start_month || $month > $end_month) {
                continue;
            }
            $buckets[$month] = ['label' => $label, 'total' => 0];
        }
        return $buckets;
    }

    private function aggregate_source(array $source, $group_expression, array $period, $username = null, $region_codes = null)
    {
        $table = $source['table'];
        $date = $source['date'];
        $count = $table === 'laporan' ? 'COUNT(DISTINCT records.id)' : 'COUNT(*)';
        $this->db->select($group_expression . ' AS bucket_key, ' . $count . ' AS total', false)
            ->from($table . ' records');

        if ($table === 'laporan') {
            $this->db
                ->join('aauth_users users', $this->report_owner_join('records', 'users'), 'inner', false)
                ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
                ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
                ->where('users.banned', 0);
        } elseif ($region_codes !== null) {
            $this->db
                ->join('aauth_users users', $this->service_owner_join($table, 'records', 'users'), 'inner', false)
                ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
                ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
                ->where('users.banned', 0);
        }

        if ($username !== null) {
            if ($table === 'laporan') {
                $this->db->where('users.username', $username);
            } else {
                $this->db->where('records.username', $username);
            }
        }
        if ($region_codes !== null) {
            $this->apply_notary_region_scope('users', $region_codes, 'dashboard_notaries');
        }

        $this->apply_period_where('records.' . $date, $period);
        return $this->db->group_by($group_expression, false)->get()->result_array();
    }

    private function apply_period_where($date_column, array $period)
    {
        list($start, $end) = $this->period_bounds($period);
        $this->db->where($date_column . ' >=', $start)->where($date_column . ' <=', $end);
    }

    private function period_bounds(array $period)
    {
        if ($period['mode'] === 'year') {
            $start = sprintf('%04d-01-01', $period['year']);
            $end = sprintf('%04d-12-31', $period['year']);
        } elseif ($period['mode'] === 'month') {
            $start = sprintf('%04d-%02d-01', $period['year'], $period['month']);
            $end = date('Y-m-t', strtotime($start));
        } else {
            $period_size = $period['mode'] === 'quarter' ? 3 : 6;
            $period_number = $period['mode'] === 'quarter' ? $period['quarter'] : $period['semester'];
            $start_month = (($period_number - 1) * $period_size) + 1;
            $start = sprintf('%04d-%02d-01', $period['year'], $start_month);
            $end = date('Y-m-t', strtotime('+' . ($period_size - 1) . ' months', strtotime($start)));
        }

        return [$start, min($end, date('Y-m-d'))];
    }

    private function available_chart_years($username = null, $region_codes = null)
    {
        $years = [(int) date('Y') => true];
        $sources = array_merge(
            [['table' => 'laporan', 'date' => 'Tanggal_Laporan']],
            array_map(function ($service) { return ['table' => $service['table'], 'date' => $service['date']]; }, $this->services)
        );
        foreach ($sources as $source) {
            $expression = 'YEAR(records.' . $source['date'] . ')';
            $this->db->reset_query();
            $table = $source['table'];
            $date = $source['date'];
            $count = $table === 'laporan' ? 'COUNT(DISTINCT records.id)' : 'COUNT(*)';
            $this->db->select($expression . ' AS bucket_key, ' . $count . ' AS total', false)->from($table . ' records');
            if ($table === 'laporan') {
                $this->db
                    ->join('aauth_users users', $this->report_owner_join('records', 'users'), 'inner', false)
                    ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
                    ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
                    ->where('users.banned', 0);
            } elseif ($region_codes !== null) {
                $this->db
                    ->join('aauth_users users', $this->service_owner_join($table, 'records', 'users'), 'inner', false)
                    ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
                    ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
                    ->where('users.banned', 0);
            }
            if ($username !== null) {
                $this->db->where($table === 'laporan' ? 'users.username' : 'records.username', $username);
            }
            if ($region_codes !== null) {
                $this->apply_notary_region_scope('users', $region_codes, 'year_notaries');
            }
            $this->db->where('records.' . $date . ' <=', date('Y-m-d'));
            foreach ($this->db->group_by($expression, false)->get()->result_array() as $row) {
                $year = (int) $row['bucket_key'];
                if ($year >= 2000 && $year <= (int) date('Y')) {
                    $years[$year] = true;
                }
            }
        }
        $years = array_keys($years);
        rsort($years, SORT_NUMERIC);
        return $years;
    }

    private function count_records($table, $date_column, $username = null, $region_codes = null, $current_year = true)
    {
        if ($table === 'laporan') {
            return $this->count_valid_reports($username, $region_codes, $current_year);
        }

        $this->db->from($table . ' dashboard_records');
        if ($region_codes !== null) {
            $this->db
                ->join('aauth_users dashboard_users', $this->service_owner_join($table, 'dashboard_records', 'dashboard_users'), 'inner', false)
                ->join('aauth_user_to_group dashboard_memberships', 'dashboard_memberships.user_id = dashboard_users.id')
                ->join('aauth_groups dashboard_groups', "dashboard_groups.id = dashboard_memberships.group_id AND dashboard_groups.name = 'User'")
                ->where('dashboard_users.banned', 0);
            $this->apply_notary_region_scope('dashboard_users', $region_codes, 'count_notaries');
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

    private function count_valid_reports($username = null, $region_codes = null, $current_year = true)
    {
        $owner_join = $this->report_owner_join('dashboard_records', 'dashboard_users');
        $this->db
            ->from('laporan dashboard_records')
            ->join('aauth_users dashboard_users', $owner_join, 'inner', false)
            ->join('aauth_user_to_group memberships', 'memberships.user_id = dashboard_users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('dashboard_users.banned', 0);
        if ($region_codes !== null) {
            $this->apply_notary_region_scope('dashboard_users', $region_codes, 'report_notaries');
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
                . ' OR ((' . $report_alias . '.owner_user_id IS NULL OR ' . $report_alias . '.owner_user_id = 0) AND LOWER('
                . $report_alias . '.username) = LOWER(' . $user_alias . '.username)))';
        }

        return 'LOWER(' . $report_alias . '.username) = LOWER(' . $user_alias . '.username)';
    }

    private function compliance_notaries($username, $region_codes, array $period)
    {
        list($start, $end) = $this->period_bounds($period);
        $report_join = $this->report_owner_join('reports', 'users')
            . ' AND reports.Tanggal_Laporan >= ' . $this->db->escape($start)
            . ' AND reports.Tanggal_Laporan <= ' . $this->db->escape($end);

        $this->db
            ->select("users.id, users.username, COALESCE(NULLIF(users.full_name, ''), notary_profiles.nama_notaris, users.username) AS display_name, COALESCE(regions.nama, notary_profiles.wilayah, '-') AS region_name, COALESCE(NULLIF(TRIM(users.phone_number), ''), NULLIF(TRIM(notary_profiles.no_telepon), ''), '-') AS phone_number, COUNT(DISTINCT reports.id) AS report_count, MAX(reports.Tanggal_Laporan) AS last_report", false)
            ->from('data_notaris notary_profiles')
            ->join('aauth_users users', 'users.id = notary_profiles.user_id', 'inner')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->join('wilayah regions', 'regions.kd_wilayah = notary_profiles.kode_wilayah', 'left')
            ->join('laporan reports', $report_join, 'left', false)
            ->where('users.banned', 0)
            ->where("UPPER(TRIM(notary_profiles.status_notaris)) = 'NOTARIS AKTIF'", null, false);
        if ($region_codes !== null) {
            $this->apply_region_codes('notary_profiles.kode_wilayah', $region_codes);
        }
        if ($username !== null) {
            $this->db->where('users.username', $username);
        }

        $rows = $this->db
            ->group_by(['users.id', 'users.username', 'users.full_name', 'users.phone_number', 'notary_profiles.nama_notaris', 'notary_profiles.no_telepon', 'notary_profiles.wilayah', 'regions.nama'])
            ->order_by('report_count', 'ASC')
            ->order_by('display_name', 'ASC')
            ->get()
            ->result_array();
        foreach ($rows as &$row) {
            $row['report_count'] = (int) $row['report_count'];
            $row['status'] = $row['report_count'] > 0 ? 'submitted' : 'missing';
        }
        unset($row);

        return $rows;
    }

    private function compliance_summary(array $rows)
    {
        $total = count($rows);
        $submitted = count(array_filter($rows, function ($row) {
            return $row['status'] === 'submitted';
        }));
        return [
            'total' => $total,
            'submitted' => $submitted,
            'missing' => max(0, $total - $submitted),
            'percentage' => $total > 0 ? (int) round(($submitted / $total) * 100) : 0,
        ];
    }

    private function regional_distribution()
    {
        return $this->db
            ->select('regions.nama AS label, COUNT(notaries.id_notaris) AS total', false)
            ->from('wilayah regions')
            ->join('data_notaris notaries', "notaries.kode_wilayah = regions.kd_wilayah AND UPPER(TRIM(notaries.status_notaris)) = 'NOTARIS AKTIF'", 'left', false)
            ->where('regions.kd_wilayah !=', '')
            ->where('regions.nama IS NOT NULL', null, false)
            ->group_by(['regions.id', 'regions.kd_wilayah', 'regions.nama'])
            ->order_by('total', 'DESC')
            ->order_by('regions.nama', 'ASC')
            ->get()
            ->result_array();
    }

    private function notaries_needing_attention($region_codes = null)
    {
        $this->db->select('users.id, users.username, users.full_name, regions.nama AS region_name, MAX(reports.Tanggal_Laporan) AS last_report', false)
            ->from('data_notaris attention_notaries')
            ->join('aauth_users users', 'users.id = attention_notaries.user_id', 'inner')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->join('wilayah regions', 'regions.kd_wilayah = attention_notaries.kode_wilayah', 'left')
            ->join('laporan reports', $this->report_owner_join('reports', 'users') . " AND reports.Tanggal_Laporan <= '" . date('Y-m-d') . "'", 'left', false)
            ->where('users.banned', 0)
            ->where("UPPER(TRIM(attention_notaries.status_notaris)) = 'NOTARIS AKTIF'", null, false)
            ->where("NOT EXISTS (SELECT 1 FROM laporan current_reports WHERE " . $this->report_owner_join('current_reports', 'users') . " AND current_reports.Tanggal_Laporan >= '" . date('Y-01-01') . "' AND current_reports.Tanggal_Laporan <= '" . date('Y-m-d') . "')", null, false);
        if ($region_codes !== null) {
            $this->apply_region_codes('attention_notaries.kode_wilayah', $region_codes);
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

    private function service_owner_join($table, $record_alias, $user_alias)
    {
        if ($this->db->field_exists('owner_user_id', $table)) {
            return '(' . $record_alias . '.owner_user_id = ' . $user_alias . '.id'
                . ' OR ((' . $record_alias . '.owner_user_id IS NULL OR ' . $record_alias . '.owner_user_id = 0)'
                . ' AND LOWER(' . $record_alias . '.username) = LOWER(' . $user_alias . '.username)))';
        }

        return 'LOWER(' . $record_alias . '.username) = LOWER(' . $user_alias . '.username)';
    }

    private function apply_notary_region_scope($user_alias, $region_codes, $notary_alias)
    {
        $this->db
            ->join('data_notaris ' . $notary_alias, $notary_alias . '.user_id = ' . $user_alias . '.id', 'inner')
            ->where("UPPER(TRIM(" . $notary_alias . ".status_notaris)) = 'NOTARIS AKTIF'", null, false);
        $this->apply_region_codes($notary_alias . '.kode_wilayah', $region_codes);
    }

    private function apply_region_codes($column, $region_codes)
    {
        $region_codes = array_values(array_unique(array_filter(array_map('trim', (array) $region_codes))));
        if (!$region_codes) {
            $this->db->where('1 = 0', null, false);
            return;
        }
        $this->db->where_in($column, $region_codes);
    }

    private function mpd_region_codes($user_id)
    {
        if (!$this->db->table_exists('mpd_wilayah') || !$this->db->table_exists('data_mpd')) {
            return [];
        }
        $verified = $this->db
            ->where('user_id', (int) $user_id)
            ->where('is_verified', 1)
            ->count_all_results('data_mpd') > 0;
        if (!$verified) {
            return [];
        }
        $rows = $this->db
            ->distinct()
            ->select('kode_wilayah')
            ->where('user_id', (int) $user_id)
            ->where("TRIM(COALESCE(kode_wilayah, '')) != ''", null, false)
            ->get('mpd_wilayah')
            ->result_array();
        return array_values(array_unique(array_column($rows, 'kode_wilayah')));
    }

    private function region_names(array $region_codes)
    {
        if (!$region_codes) {
            return 'Belum ada wilayah pengawasan';
        }
        $rows = $this->db
            ->select('nama')
            ->where_in('kd_wilayah', $region_codes)
            ->order_by('nama', 'ASC')
            ->get('wilayah')
            ->result_array();
        $names = array_filter(array_column($rows, 'nama'));
        return $names ? implode(', ', $names) : implode(', ', $region_codes);
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
            ['label' => 'Rekap Layanan', 'description' => 'Pantau seluruh jenis layanan dalam satu halaman.', 'url' => site_url('rekap-layanan'), 'icon' => 'fa-file-text-o'],
            ['label' => 'Data Notaris', 'description' => 'Lihat basis data notaris terdaftar.', 'url' => site_url('data_notaris'), 'icon' => 'fa-book'],
            ['label' => 'Kepatuhan', 'description' => 'Tinjau kepatuhan pelaporan notaris.', 'url' => site_url('kepatuhan'), 'icon' => 'fa-shield'],
            ['label' => 'Profil Saya', 'description' => 'Perbarui informasi dan keamanan akun.', 'url' => site_url('administrator/profile'), 'icon' => 'fa-user'],
        ];
    }
}
