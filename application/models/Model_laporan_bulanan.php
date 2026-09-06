<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_laporan_bulanan extends MY_Model {

	private $primary_key 	= 'id_laporan_bulanan';
	private $table_name 	= 'laporan_bulanan';
	private $field_search 	= ['nama_notaris', 'username', 'tanggal_laporan', 'file_laporan'];

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
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
		$this->apply_report_scope($this->table_name);
		return $this->db->get($this->table_name)->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
		$this->apply_report_scope($this->table_name);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('laporan_bulanan.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $this->attach_owner_display_names($query->result());
	}

    public function join_avaiable() {
        $this->db->select('laporan_bulanan.*, wil.nama_wilayah');
        $this->db->join('wil', 'wil.id = laporan_bulanan.kd_wilayah', 'LEFT');

        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_laporan_bulanan.php */
/* Location: ./application/models/Model_laporan_bulanan.php */
