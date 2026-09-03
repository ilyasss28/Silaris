<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model_home extends CI_Model
{
    private $region_map = [
        '7401' => ['slug' => 'kolaka',   'name' => 'Kabupaten Kolaka'],
        '7402' => ['slug' => 'konawe',   'name' => 'Kabupaten Konawe'],
        '7403' => ['slug' => 'muna',     'name' => 'Kabupaten Muna'],
        '7404' => ['slug' => 'buton',    'name' => 'Kabupaten Buton'],
        '7405' => ['slug' => 'konsel',   'name' => 'Kabupaten Konawe Selatan'],
        '7406' => ['slug' => 'bombana',  'name' => 'Kabupaten Bombana'],
        '7407' => ['slug' => 'wakatobi', 'name' => 'Kabupaten Wakatobi'],
        '7408' => ['slug' => 'kolut',    'name' => 'Kabupaten Kolaka Utara'],
        '7409' => ['slug' => 'konut',    'name' => 'Kabupaten Konawe Utara'],
        '7410' => ['slug' => 'butur',    'name' => 'Kabupaten Buton Utara'],
        '7411' => ['slug' => 'koltim',   'name' => 'Kabupaten Kolaka Timur'],
        '7412' => ['slug' => 'konkep',   'name' => 'Kabupaten Konawe Kepulauan'],
        '7413' => ['slug' => 'mubar',    'name' => 'Kabupaten Muna Barat'],
        '7414' => ['slug' => 'buteng',   'name' => 'Kabupaten Buton Tengah'],
        '7415' => ['slug' => 'busel',    'name' => 'Kabupaten Buton Selatan'],
        '7471' => ['slug' => 'kendari',  'name' => 'Kota Kendari'],
        '7472' => ['slug' => 'baubau',   'name' => 'Kota Baubau'],
    ];

    function __construct() {
    parent:: __construct();
}

public function get_db(){
        $this->select_public_fields();
        $this->db->from('data_notaris');
        $this->apply_public_notary_scope();
        $this->db->order_by('id_notaris', 'DESC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_db: ' . $this->db->last_query());
            return [];
        }
        return $query->result();

}

public function get_wilayah(){
        $this->db->select('kode_wilayah, COUNT(*) as jumlah', FALSE);
        $this->db->from('data_notaris');
        $this->apply_public_notary_scope();
        $this->db->group_by('kode_wilayah');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_wilayah: ' . $this->db->last_query());
            return [];
        }

        // Older rows use short slugs (kendari, konsel, etc.), while the
        // current form stores official BPS-style numeric codes. Normalize
        // both formats so no notary disappears and duplicate cards merge.
        $slug_to_code = [];
        foreach ($this->region_map as $code => $region) {
            $slug_to_code[$region['slug']] = $code;
        }

        $totals = [];
        foreach ($query->result_array() as $row) {
            $stored_code = strtolower(trim((string) $row['kode_wilayah']));
            $official_code = isset($this->region_map[$stored_code])
                ? $stored_code
                : (isset($slug_to_code[$stored_code]) ? $slug_to_code[$stored_code] : null);

            if ($official_code === null) {
                log_message('error', 'Unknown notary region code ignored: ' . $stored_code);
                continue;
            }

            if (!isset($totals[$official_code])) {
                $totals[$official_code] = 0;
            }
            $totals[$official_code] += (int) $row['jumlah'];
        }

        $wilayah = [];
        foreach ($this->region_map as $code => $region) {
            if (empty($totals[$code])) {
                continue;
            }

            $wilayah[] = [
                'kode_wilayah' => $region['slug'],
                'wilayah'      => $region['name'],
                'jumlah'       => $totals[$code],
            ];
        }
        return $wilayah;
    }

public function get_notaris_by_region($slug)
{
    $official_code = null;
    foreach ($this->region_map as $code => $region) {
        if ($region['slug'] === strtolower((string) $slug)) {
            $official_code = $code;
            break;
        }
    }

    if ($official_code === null) {
        return [];
    }

    $this->select_public_fields();
    $this->db->from('data_notaris');
    $this->apply_public_notary_scope();
    $this->db->where_in('kode_wilayah', [$official_code, strtolower($slug)]);
    $this->db->order_by('id_notaris', 'DESC');
    $query = $this->db->get();

    if ($query === FALSE) {
        log_message('error', 'Database query failed in get_notaris_by_region: ' . $this->db->last_query());
        return [];
    }

    return $query->result();
}

public function get_public_notary($id_notaris)
{
    $this->select_public_fields();
    $this->db->from('data_notaris');
    $this->apply_public_notary_scope();
    $this->db->where('id_notaris', (int) $id_notaris);

    return $this->db->limit(1)->get()->row();
}

private function select_public_fields()
{
    // Do not pass private administrative fields (password, identity number,
    // tax number, BAP, home address) into public views.
    $this->db->select(array(
        'id_notaris', 'nama_notaris', 'foto', 'jenis_kelamin', 'email',
        'wilayah', 'kode_wilayah', 'alamat_kantor', 'lat', 'long',
        'no_telepon', 'status_notaris',
    ));
}

private function apply_public_notary_scope()
{
    $valid_regions = array_merge(array_keys($this->region_map), array_column($this->region_map, 'slug'));
    $escaped_regions = array_map(array($this->db, 'escape'), $valid_regions);

    $this->db->where("UPPER(TRIM(status_notaris)) = 'NOTARIS AKTIF'", null, false);
    $this->db->where("TRIM(COALESCE(nama_notaris, '')) != ''", null, false);
    $this->db->where(
        'LOWER(TRIM(kode_wilayah)) IN (' . implode(',', $escaped_regions) . ')',
        null,
        false
    );
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
