<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_kepatuhan extends CI_Model
{
    function __construct() {
    parent:: __construct();
    }

    /**
     * Real compliance data: every notaris user account, left-joined
     * against their actual laporan_bulanan (monthly report) submissions.
     * The old get_db()/kendari()/baubau()/kolaka() methods below read
     * from laporan_bulan_2023/2024/2025 and mpd_baubau/mpd_kolaka,
     * which are all empty in this database - this derives real numbers
     * from tables that actually have data instead.
     */
    public function get_compliance($q = null)
    {
        $this->db->select("au.id, au.username, au.full_name, au.kd_wilayah,
            COUNT(lb.username) as jumlah_laporan,
            MAX(lb.tanggal_laporan) as laporan_terakhir", FALSE);
        $this->db->from('aauth_users au');
        $this->db->join('aauth_user_to_group ug', 'ug.user_id = au.id');
        $this->db->join('aauth_groups g', "g.id = ug.group_id AND g.name = 'User'");
        $this->db->join('laporan_bulanan lb', 'lb.username = au.username', 'left');
        if (!empty($q)) {
            $this->db->group_start();
            $this->db->like('au.full_name', $q);
            $this->db->or_like('au.username', $q);
            $this->db->group_end();
        }
        $this->db->group_by('au.id');
        $this->db->order_by('jumlah_laporan', 'DESC');
        $this->db->order_by('au.full_name', 'ASC');

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
        $total_akun = $this->db->select('COUNT(*) as c', FALSE)
            ->from('aauth_users au')
            ->join('aauth_user_to_group ug', 'ug.user_id = au.id')
            ->join('aauth_groups g', "g.id = ug.group_id AND g.name = 'User'")
            ->get()->row()->c ?? 0;

        $total_laporan = $this->db->select('COUNT(*) as c', FALSE)
            ->from('laporan_bulanan')
            ->get()->row()->c ?? 0;

        $aktif_melapor = $this->db->select('COUNT(DISTINCT lb.username) as c', FALSE)
            ->from('laporan_bulanan lb')
            ->join('aauth_users au', 'au.username = lb.username')
            ->get()->row()->c ?? 0;

        $periode = $this->db->select("MIN(tanggal_laporan) as awal, MAX(tanggal_laporan) as akhir", FALSE)
            ->from('laporan_bulanan')
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