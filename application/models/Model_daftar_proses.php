<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_daftar_proses extends MY_Model {

	private $primary_key 	= 'id_daftar_proses';
	private $table_name 	= 'daftar_proses';
	private $field_search 	= ['nama_notaris', 'username', 'nomor_akta', 'tanggal_akta', 'sifat_akta', 'penghadap'];

	public function __construct()
	{
		$config = array(
			'primary_key' 	=> $this->primary_key,
		 	'table_name' 	=> $this->table_name,
		 	'field_search' 	=> $this->field_search,
			'report_scoped' => true,
		 );

		parent::__construct($config);
	}

	public function count_all($q = null, $field = null)
	{
		$this->join_avaiable()->filter_avaiable();
		$this->apply_search($q, $field);
		$this->apply_report_scope($this->table_name);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
		$this->apply_search($q, $field);
		$this->apply_report_scope($this->table_name);
        $this->db->limit($limit, $offset);
        $this->db->order_by('daftar_proses.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $this->attach_owner_display_names($query->result());
	}

	private function apply_search($q, $field)
	{
		$q = trim((string) $q);
		$field = trim((string) $field);
		if ($q === '' || empty($this->field_search)) {
			return;
		}

		$fields = in_array($field, $this->field_search, true) ? array($field) : $this->field_search;
		$this->db->group_start();
		foreach ($fields as $index => $search_field) {
			$qualified_field = 'daftar_proses.'.$search_field;
			$index === 0
				? $this->db->like($qualified_field, $q)
				: $this->db->or_like($qualified_field, $q);
		}
		$this->db->group_end();
	}

    public function join_avaiable() {
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_daftar_proses.php */
/* Location: ./application/models/Model_daftar_proses.php */
