<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_kepatuhan extends CI_Model
{
    function __construct() {
    parent:: __construct();
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