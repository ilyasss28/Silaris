<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_home extends CI_Model
{
    function __construct() {
    parent:: __construct();
}

public function get_db(){
$this->db->select('*');
        $this->db->from('data_notaris');
        $this->db->order_by('id_notaris', 'DESC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_db: ' . $this->db->last_query());
            return [];
        }
        return $query->result_array();

}

public function get_where($table = null, $where = null)
{
    $this->db->from($table);
    $this->db->where($where);
    return $this->db->get();
}

public function get_wilayah(){
        // jumlah_per_wilayah is an empty/stale reference table, so the
        // region breakdown is derived from the live data_notaris rows
        // instead - the same source the count of notaris itself comes from.
        $this->db->select('kode_wilayah, COUNT(*) as jumlah', FALSE);
        $this->db->from('data_notaris');
        $this->db->where('kode_wilayah IS NOT NULL', NULL, FALSE);
        $this->db->group_by('kode_wilayah');
        $this->db->order_by('jumlah', 'DESC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_wilayah: ' . $this->db->last_query());
            return [];
        }

        $wilayah = [];
        foreach ($query->result_array() as $row) {
            $kode = trim($row['kode_wilayah']);
            if ($kode === '' OR !ctype_alpha($kode)) {
                continue; // skip malformed codes (e.g. stray numeric values)
            }
            $wilayah[] = [
                'kode_wilayah' => $kode,
                'wilayah'      => ucwords($kode),
                'jumlah'       => $row['jumlah'],
            ];
        }
        return $wilayah;
    }

public function kendari(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'kendari');
    $data = $this->db->get();
    if ($data === FALSE) {
        log_message('error', 'Database query failed in kendari (Model_home): ' . $this->db->last_query());
        return [];
    }
    if($data->num_rows() > 0){
        return $data->result();
    }else{
        return false;
    }
}
    
public function baubau(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'baubau');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
        
public function wakatobi(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'wakatobi');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }}
        
public function muna(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'muna');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}

public function mubar(){
  $data = $this->db->select('*');
  $data = $this->db->from('data_notaris');
  $this->db->order_by('id_notaris', 'DESC');
  $data = $this->db->where('kode_wilayah', 'mubar');
  $data = $this->db->get();
  if($data->num_rows() > 0){
  return $data->result();
  }else{
    return false;
  }
}

public function konut(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'konut');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function konsel(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'konsel');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function konawe(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'konawe');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function kolut(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'kolut');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function koltim(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'koltim');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function kolaka(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'kolaka');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}  
public function butur(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'butur');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
} 

public function buton(){
  $data = $this->db->select('*');
  $data = $this->db->from('data_notaris');
  $this->db->order_by('id_notaris', 'DESC');
  $data = $this->db->where('kode_wilayah', 'buton');
  $data = $this->db->get();
  if($data->num_rows() > 0){
  return $data->result();
  }else{
    return false;
  }
}

public function buteng(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'buteng');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function busel(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'busel');
    $data = $this->db->get();
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
}
public function bombana(){
    $data = $this->db->select('*');
    $data = $this->db->from('data_notaris');
    $this->db->order_by('id_notaris', 'DESC');
    $data = $this->db->where('kode_wilayah', 'bombana');
    $data = $this->db->get();
        if ($data === FALSE) {
            log_message('error', 'Database query failed in busel (Model_home): ' . $this->db->last_query());
            return [];
        }
    if($data->num_rows() > 0){
    return $data->result();
    }else{
      return false;
    }
    
}

// function save(){
//   $data = array(
//     "nama" => $this ->input->post('nama'),
//     "nama_kampus" => $this ->input->post('nama_kampus'),
//     "nim" => $this ->input->post('nim'),
//     "jurusan" => $this ->input->post('jurusan'),
//     "judul_penelitian" => $this ->input->post('judul_penelitian'),
//     "durasi_penelitian" => $this ->input->post('durasi_penelitian'),
//     "lokasi_penelitian" => $this ->input->post('lokasi_penelitian'),
//     "kontak_person" => $this ->input->post('kontak_person'),
//     "email" => $this ->input->post('email')
//   );
//   return $this->db->insert($this->table, $data);
// }


}