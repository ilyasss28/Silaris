<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Read-only cross-service reporting projection. */
class Model_rekap_layanan extends CI_Model
{
    private $services = array(
        'laporan' => array('label' => 'Laporan Bulanan', 'table' => 'laporan', 'primary' => 'id', 'date' => 'Tanggal_Laporan', 'number' => null, 'description' => null, 'document' => 'Laporan', 'route' => 'laporan'),
        'reportorium' => array('label' => 'Repertorium', 'table' => 'reportorium', 'primary' => 'id_reportorium', 'date' => 'tanggal_akta', 'number' => 'nomor_akta', 'description' => 'sifat_akta', 'document' => null, 'route' => 'reportorium'),
        'daftar_proses' => array('label' => 'Daftar Protes', 'table' => 'daftar_proses', 'primary' => 'id_daftar_proses', 'date' => 'tanggal_akta', 'number' => 'nomor_akta', 'description' => 'sifat_akta', 'document' => null, 'route' => 'daftar_proses'),
        'legalisasi' => array('label' => 'Legalisasi', 'table' => 'legalisasi', 'primary' => 'id_legalisasi', 'date' => 'tanggal_akta', 'number' => 'nomor_akta', 'description' => 'sifat_akta', 'document' => null, 'route' => 'legalisasi'),
        'waarmerking' => array('label' => 'Waarmerking', 'table' => 'waarmerking', 'primary' => 'id_waarmerking', 'date' => 'tanggal_akta', 'number' => 'nomor_akta', 'description' => 'sifat_akta', 'document' => null, 'route' => 'waarmerking'),
        'fidusia' => array('label' => 'Fidusia', 'table' => 'fidusia', 'primary' => 'id_fidusia', 'date' => 'tanggal', 'number' => 'nomor_akta', 'description' => 'nama_pemberi_fidusia', 'document' => null, 'route' => 'fidusia'),
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->library('report_access');
    }

    public function service_options()
    {
        $options = array();
        foreach ($this->services as $key => $service) {
            $options[$key] = $service['label'];
        }
        return $options;
    }

    public function normalize_filters(array $input)
    {
        $current_year = (int) date('Y');
        $mode = strtolower(trim((string) ($input['mode'] ?? 'year')));
        $year = (int) ($input['year'] ?? $current_year);
        $service = strtolower(trim((string) ($input['service'] ?? '')));

        if (!in_array($mode, array('month', 'quarter', 'semester', 'year'), true)) $mode = 'year';
        if ($year < 2000 || $year > $current_year) $year = $current_year;
        if (!isset($this->services[$service])) $service = '';

        return array(
            'mode' => $mode,
            'year' => $year,
            'month' => min(12, max(1, (int) ($input['month'] ?? date('n')))),
            'quarter' => min(4, max(1, (int) ($input['quarter'] ?? ceil(date('n') / 3)))),
            'semester' => min(2, max(1, (int) ($input['semester'] ?? ceil(date('n') / 6)))),
            'service' => $service,
            'region' => trim((string) ($input['region'] ?? '')),
            'notary' => trim((string) ($input['notary'] ?? '')),
            'q' => trim((string) ($input['q'] ?? '')),
        );
    }

    public function period_label(array $filters)
    {
        $months = array(1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
        if ($filters['mode'] === 'month') return $months[$filters['month']].' '.$filters['year'];
        if ($filters['mode'] === 'quarter') return 'Triwulan '.$filters['quarter'].' '.$filters['year'];
        if ($filters['mode'] === 'semester') return 'Semester '.$filters['semester'].' '.$filters['year'];
        return 'Tahun '.$filters['year'];
    }

    public function summary(array $filters)
    {
        $summary = array();
        foreach ($this->service_options() as $key => $label) $summary[$key] = array('key' => $key, 'label' => $label, 'total' => 0);
        $union = $this->build_union($filters);
        if ($union === '') return array_values($summary);

        $rows = $this->db->query('SELECT service_key, COUNT(*) AS total FROM ('.$union.') service_records GROUP BY service_key')->result_array();
        foreach ($rows as $row) {
            if (isset($summary[$row['service_key']])) $summary[$row['service_key']]['total'] = (int) $row['total'];
        }
        return array_values($summary);
    }

    public function count_all(array $filters)
    {
        $union = $this->build_union($filters);
        if ($union === '') return 0;
        $row = $this->db->query('SELECT COUNT(*) AS total FROM ('.$union.') service_records')->row();
        return $row ? (int) $row->total : 0;
    }

    public function get(array $filters, $limit = 25, $offset = 0)
    {
        $union = $this->build_union($filters);
        if ($union === '') return array();
        $sql = 'SELECT * FROM ('.$union.') service_records ORDER BY record_date DESC, service_label ASC, record_id DESC LIMIT '.max(1, (int) $limit).' OFFSET '.max(0, (int) $offset);
        return $this->db->query($sql)->result_array();
    }

    public function get_all(array $filters)
    {
        $union = $this->build_union($filters);
        if ($union === '') return array();
        return $this->db->query('SELECT * FROM ('.$union.') service_records ORDER BY record_date DESC, service_label ASC, record_id DESC')->result_array();
    }

    public function filter_options()
    {
        $this->db->distinct()->select("users.username, COALESCE(NULLIF(TRIM(users.full_name), ''), users.username) AS full_name, notary_profiles.kode_wilayah AS kd_wilayah, COALESCE(regions.nama, notary_profiles.wilayah, notary_profiles.kode_wilayah) AS region_name", false)
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id=users.id')
            ->join('aauth_groups groups_table', "groups_table.id=memberships.group_id AND groups_table.name='User'")
            ->join('data_notaris notary_profiles', 'notary_profiles.user_id=users.id', 'inner')
            ->join('wilayah regions', 'regions.kd_wilayah=notary_profiles.kode_wilayah', 'left')
            ->where('users.banned', 0)
            ->where("UPPER(TRIM(notary_profiles.status_notaris))='NOTARIS AKTIF'", null, false);
        $this->apply_user_scope('notary_profiles');
        $notaries = $this->db->order_by('full_name', 'ASC')->get()->result_array();

        $regions = array();
        foreach ($notaries as $notary) {
            $code = trim((string) $notary['kd_wilayah']);
            if ($code !== '') $regions[$code] = $notary['region_name'];
        }
        asort($regions, SORT_NATURAL | SORT_FLAG_CASE);
        return array('notaries' => $notaries, 'regions' => $regions);
    }

    private function build_union(array $filters)
    {
        $queries = array();
        foreach ($this->services as $key => $service) {
            if ($filters['service'] !== '' && $filters['service'] !== $key) continue;
            if (!$this->db->table_exists($service['table'])) continue;
            $queries[] = $this->build_service_query($key, $service, $filters);
        }
        return implode(' UNION ALL ', $queries);
    }

    private function build_service_query($key, array $service, array $filters)
    {
        $this->db->reset_query();
        $table = $service['table'];
        $number = $service['number'] ? 'CAST('.$table.'.'.$service['number'].' AS CHAR)' : "''";
        $description = $service['description'] ? 'COALESCE('.$table.'.'.$service['description'].', \'\')' : "''";
        $document = $service['document'] ? 'COALESCE('.$table.'.'.$service['document'].', \'\')' : "''";
        $owner_join = '(owners.id='.$table.'.owner_user_id OR (('.$table.'.owner_user_id IS NULL OR '.$table.'.owner_user_id=0) AND LOWER(owners.username)=LOWER('.$table.'.username)))';

        $this->db->select(
            $this->db->escape($key).' AS service_key, '.$this->db->escape($service['label']).' AS service_label, '
            .$table.'.'.$service['primary'].' AS record_id, '.$table.'.username, '
            .'COALESCE(NULLIF(TRIM('.$table.'.nama_notaris), \'\'), NULLIF(TRIM(owners.full_name), \'\'), '.$table.'.username) AS nama_notaris, '
            .'COALESCE(NULLIF(TRIM(owners.phone_number), \'\'), \'-\') AS phone_number, '
            .'COALESCE(notary_profiles.kode_wilayah, \'\') AS kode_wilayah, COALESCE(regions.nama, notary_profiles.wilayah, notary_profiles.kode_wilayah, \'-\') AS wilayah, '
            .$table.'.'.$service['date'].' AS record_date, '.$number.' AS nomor_akta, '.$description.' AS description, '.$document.' AS document_name',
            false
        )->from($table)
            ->join('aauth_users owners', $owner_join, 'left', false)
            ->join('data_notaris notary_profiles', 'notary_profiles.user_id=owners.id', 'left')
            ->join('wilayah regions', 'regions.kd_wilayah=notary_profiles.kode_wilayah', 'left');

        $this->report_access->apply_scope($this->db, $table);
        list($start, $end) = $this->period_bounds($filters);
        $this->db->where($table.'.'.$service['date'].' >=', $start)->where($table.'.'.$service['date'].' <=', $end);
        if ($filters['region'] !== '') $this->db->where('notary_profiles.kode_wilayah', $filters['region']);
        if ($filters['notary'] !== '') $this->db->where('owners.username', $filters['notary']);
        if ($filters['q'] !== '') {
            $this->db->group_start()
                ->like($table.'.nama_notaris', $filters['q'])
                ->or_like($table.'.username', $filters['q']);
            if ($service['number']) $this->db->or_like($table.'.'.$service['number'], $filters['q']);
            if ($service['description']) $this->db->or_like($table.'.'.$service['description'], $filters['q']);
            $this->db->group_end();
        }
        return $this->db->get_compiled_select('', true);
    }

    private function period_bounds(array $filters)
    {
        if ($filters['mode'] === 'month') {
            $start = sprintf('%04d-%02d-01', $filters['year'], $filters['month']);
            $end = date('Y-m-t', strtotime($start));
        } elseif ($filters['mode'] === 'quarter') {
            $start = sprintf('%04d-%02d-01', $filters['year'], (($filters['quarter'] - 1) * 3) + 1);
            $end = date('Y-m-t', strtotime('+2 months', strtotime($start)));
        } elseif ($filters['mode'] === 'semester') {
            $start = sprintf('%04d-%02d-01', $filters['year'], (($filters['semester'] - 1) * 6) + 1);
            $end = date('Y-m-t', strtotime('+5 months', strtotime($start)));
        } else {
            $start = sprintf('%04d-01-01', $filters['year']);
            $end = sprintf('%04d-12-31', $filters['year']);
        }
        if ($filters['year'] === (int) date('Y')) $end = min($end, date('Y-m-d'));
        return array($start, $end);
    }

    private function apply_user_scope($notary_alias)
    {
        $role = $this->report_access->current_role();
        if ($role === 'MPD') {
            $user_id = (int) $this->session->userdata('id');
            $this->db->where('EXISTS (SELECT 1 FROM mpd_wilayah jurisdiction INNER JOIN data_mpd profile ON profile.user_id=jurisdiction.user_id AND profile.is_verified=1 WHERE jurisdiction.user_id='.$user_id.' AND jurisdiction.kode_wilayah='.$notary_alias.'.kode_wilayah)', null, false);
        } elseif (!in_array($role, array('Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan'), true)) {
            $this->db->where($notary_alias.'.user_id', (int) $this->session->userdata('id'));
        }
    }
}
