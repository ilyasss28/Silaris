<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_fidusia extends MY_Model {

	private $primary_key 	= 'id_fidusia';
	private $table_name 	= 'fidusia';
	private $field_search 	= ['nama_notaris', 'tanggal', 'tanggal_akta', 'nomor_akta', 'nama_pemberi_fidusia', 'nama_penerima_fidusia', 'no_sertifikat_jaminan_fidusia'];

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
        }
		
		$this->join_avaiable()->filter_avaiable();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('fidusia.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_fidusia.php */
/* Location: ./application/models/Model_fidusia.php */
