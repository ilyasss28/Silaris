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

        $this->db->select("users.id, users.username, users.full_name, users.kd_wilayah,
            COUNT(DISTINCT reports.id) AS jumlah_laporan,
            MAX(reports.Tanggal_Laporan) AS laporan_terakhir", false);
        $this->db->from('aauth_users users');
        $this->db->join('aauth_user_to_group memberships', 'memberships.user_id = users.id');
        $this->db->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'");
        $this->db->join('laporan reports', $owner_join, 'left', false);
        $this->db->where('users.banned', 0);
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('users.full_name', $q);
            $this->db->or_like('users.username', $q);
            $this->db->group_end();
        }
        $this->db->group_by(array('users.id', 'users.username', 'users.full_name', 'users.kd_wilayah'));
        $this->db->order_by('jumlah_laporan', 'DESC');
        $this->db->order_by('users.full_name', 'ASC');

        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_compliance (Model_kepatuhan): ' . $this->db->last_query());
            return [];
        }
        return $query->result();
    }

    /**
     * Summary counters for the compliance page's stat tiles.
     */
    public function get_compliance_summary()
    {
        $start = date('Y-01-01');
        $end = date('Y-m-d');
        $owner_join = $this->report_owner_join('reports', 'users');

        $total_akun = $this->db->select('COUNT(DISTINCT users.id) AS c', false)
            ->from('aauth_users users')
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('users.banned', 0)
            ->get()->row()->c ?? 0;

        $total_laporan = $this->db->select('COUNT(DISTINCT reports.id) AS c', false)
            ->from('laporan reports')
            ->join('aauth_users users', $owner_join, 'inner', false)
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('users.banned', 0)
            ->where('reports.Tanggal_Laporan >=', $start)
            ->where('reports.Tanggal_Laporan <=', $end)
            ->get()->row()->c ?? 0;

        $aktif_melapor = $this->db->select('COUNT(DISTINCT users.id) AS c', false)
            ->from('laporan reports')
            ->join('aauth_users users', $owner_join, 'inner', false)
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('users.banned', 0)
            ->where('reports.Tanggal_Laporan >=', $start)
            ->where('reports.Tanggal_Laporan <=', $end)
            ->get()->row()->c ?? 0;

        $periode = $this->db->select('MIN(reports.Tanggal_Laporan) AS awal, MAX(reports.Tanggal_Laporan) AS akhir', false)
            ->from('laporan reports')
            ->join('aauth_users users', $owner_join, 'inner', false)
            ->join('aauth_user_to_group memberships', 'memberships.user_id = users.id')
            ->join('aauth_groups groups_table', "groups_table.id = memberships.group_id AND groups_table.name = 'User'")
            ->where('users.banned', 0)
            ->where('reports.Tanggal_Laporan >=', $start)
            ->where('reports.Tanggal_Laporan <=', $end)
            ->get()->row();

        return [
            'total_notaris'  => (int) $total_akun,
            'total_laporan'  => (int) $total_laporan,
            'aktif_melapor'  => (int) $aktif_melapor,
            'tingkat_persen' => $total_akun > 0 ? round(($aktif_melapor / $total_akun) * 100) : 0,
            'periode_awal'   => $periode->awal ?? null,
            'periode_akhir'  => $periode->akhir ?? null,
        ];
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
