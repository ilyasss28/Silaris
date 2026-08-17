<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_ppid_view extends MY_Model
{
    function __construct() {
    parent:: __construct();
}
function get_db(){
$data = $this->db->select('*');
$data = $this->db->from('dokumen_ppid');
$this->db->order_by('id_ppid', 'DESC');
$data = $this->db->get();
if($data->num_rows() > 0){
return $data->result();
}else{
  return false;
}
}

function get_berkala(){
    $data = $this->db->select('*');
    $data = $this->db->from('dokumen_ppid');
    $data = $this->db->like('jenis_informasi', '1');
    $this->db->order_by('id_ppid', 'DESC');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}

function get_sertamerta(){
    $data = $this->db->select('*');
    $data = $this->db->from('dokumen_ppid');
    $data = $this->db->like('jenis_informasi', '2');
    $this->db->order_by('id_ppid', 'DESC');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
    return false;
    }
}

function get_setiapsaat(){
    $data = $this->db->select('*');
    $data = $this->db->from('dokumen_ppid');
    $data = $this->db->like('jenis_informasi', '3');
    $this->db->order_by('id_ppid', 'DESC');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
    return false;
    }
}
}