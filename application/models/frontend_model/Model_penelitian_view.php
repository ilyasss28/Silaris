<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_penelitian_view extends CI_Model
{
    function __construct() {
    parent:: __construct();
}
private $table = 'izin_penelitian';

function get_db(){
$data = $this->db->select('*');
$data = $this->db->from('izin_penelitian');
$this->db->order_by('id_izpen', 'DESC');
$data = $this->db->get();
if($data->num_rows() > 0){
return $data->result();
}else{
  return false;
}
}

function get_satker(){
    $data = $this->db->select('*');
    $data = $this->db->from('setup_satker');
    $this->db->order_by('id_satker', 'DESC');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}

function save(){
  $data = array(
    "nama" => $this ->input->post('nama'),
    "nama_kampus" => $this ->input->post('nama_kampus'),
    "nim" => $this ->input->post('nim'),
    "jurusan" => $this ->input->post('jurusan'),
    "judul_penelitian" => $this ->input->post('judul_penelitian'),
    "durasi_penelitian" => $this ->input->post('durasi_penelitian'),
    "lokasi_penelitian" => $this ->input->post('lokasi_penelitian'),
    "kontak_person" => $this ->input->post('kontak_person'),
    "email" => $this ->input->post('email')
  );
  return $this->db->insert($this->table, $data);
}


}