<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_rekap_reportorium extends MY_Model {

	private $primary_key 	= 'id_reportorium';
	private $table_name 	= 'reportorium';
	private $field_search 	= ['username', 'nomor_akta', 'tanggal_akta', 'sifat_akta', 'penghadap'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$this->join_avaiable()->filter_avaiable();
		return $this->count_search_results($this->table_name, $this->field_search, $q, $field);
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        } else {
        	$this->select_with_notaris_fallback();
        }

		$this->join_avaiable()->filter_avaiable();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('reportorium.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	/**
	 * Many legacy records were imported without reportorium.nama_notaris.
	 * Fall back to the account matching the record's username so the
	 * display never shows a blank notary name.
	 */
	private function select_with_notaris_fallback()
	{
		$this->db->select(
			"reportorium.*, COALESCE(NULLIF(TRIM(reportorium.nama_notaris), ''), notaris_owner.full_name, NULLIF(reportorium.username, '0')) AS nama_notaris",
			false
		);
		$this->db->join('aauth_users AS notaris_owner', 'LOWER(notaris_owner.username) = LOWER(reportorium.username)', 'left');
	}

    public function join_avaiable() {
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_rekap_reportorium.php */
/* Location: ./application/models/Model_rekap_reportorium.php */
