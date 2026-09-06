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
        $this->join_public_account();
        $this->apply_public_notary_scope();
        $this->db->order_by('id_notaris', 'DESC');
        $query = $this->db->get();
        if ($query === FALSE) {
            log_message('error', 'Database query failed in get_db: ' . $this->db->last_query());
            return [];
        }
        return $this->attach_public_photos($query->result());

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

        $totals = [];
        foreach ($query->result_array() as $row) {
            $official_code = trim((string) $row['kode_wilayah']);
            if (!isset($this->region_map[$official_code])) {
                log_message('error', 'Unknown official notary region code ignored: ' . $official_code);
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
    $this->join_public_account();
    $this->apply_public_notary_scope();
    $this->db->where('kode_wilayah', $official_code);
    $this->db->order_by('id_notaris', 'DESC');
    $query = $this->db->get();

    if ($query === FALSE) {
        log_message('error', 'Database query failed in get_notaris_by_region: ' . $this->db->last_query());
        return [];
    }

    return $this->attach_public_photos($query->result());
}

public function get_public_notary($id_notaris)
{
    $this->select_public_fields();
    $this->db->from('data_notaris');
    $this->join_public_account();
    $this->apply_public_notary_scope();
    $this->db->where('id_notaris', (int) $id_notaris);

    $row = $this->db->limit(1)->get()->row();
    if (!$row) {
        return null;
    }

    $rows = $this->attach_public_photos([$row]);
    return $rows[0];
}

private function select_public_fields()
{
    // Do not pass private administrative fields (password, identity number,
    // tax number, BAP, home address) into public views.
    $this->db->select(array(
        'data_notaris.id_notaris', 'data_notaris.nama_notaris',
        'data_notaris.foto', 'data_notaris.jenis_kelamin', 'data_notaris.email',
        'data_notaris.wilayah', 'data_notaris.kode_wilayah',
        'data_notaris.alamat_kantor', 'data_notaris.lat', 'data_notaris.long',
        'data_notaris.no_telepon', 'data_notaris.status_notaris',
        'public_account.avatar AS account_avatar',
    ));
}

private function join_public_account()
{
    $this->db->join(
        'aauth_users public_account',
        'public_account.id = data_notaris.user_id',
        'left'
    );
}

private function attach_public_photos(array $records)
{
    foreach ($records as $record) {
        $record->photo_url = notary_photo_url(
            $record->account_avatar ?? '',
            $record->foto ?? ''
        );
    }

    return $records;
}

private function apply_public_notary_scope()
{
    $valid_regions = array_keys($this->region_map);
    $escaped_regions = array_map(array($this->db, 'escape'), $valid_regions);

    $this->db->where("UPPER(TRIM(data_notaris.status_notaris)) = 'NOTARIS AKTIF'", null, false);
    $this->db->where("TRIM(COALESCE(data_notaris.nama_notaris, '')) != ''", null, false);
    $this->db->where(
        'TRIM(data_notaris.kode_wilayah) IN (' . implode(',', $escaped_regions) . ')',
        null,
        false
    );
}
}
