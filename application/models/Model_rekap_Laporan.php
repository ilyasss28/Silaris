<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_rekap_Laporan extends MY_Model {

	private $primary_key 	= 'id';
	private $table_name 	= 'laporan';
	private $field_search 	= ['username', 'nama_notaris', 'Tanggal_Laporan', 'Laporan'];

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
		$this->apply_search($q, $field);

		// COUNT(*) is substantially lighter than loading every report row just
		// to call num_rows(), especially once the report archive grows.
		return $this->db->count_all_results($this->table_name);
	}
	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [], $order_field = null, $order_direction = 'DESC')
	{
        if (is_array($select_field) AND count($select_field)) {
			$this->db->select($select_field);
		} else {
			$this->db->select('laporan.id, laporan.username, laporan.nama_notaris, laporan.Tanggal_Laporan, laporan.Laporan');
        }

		$this->join_avaiable()->filter_avaiable();
		$this->apply_search($q, $field);
		$order_field = in_array($order_field, array_merge([$this->primary_key], $this->field_search), true)
			? $order_field
			: $this->primary_key;
		$order_direction = strtoupper($order_direction) === 'ASC' ? 'ASC' : 'DESC';
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
		$this->db->order_by('laporan.'.$order_field, $order_direction);
		$query = $this->db->get($this->table_name);
		return $query->result();
	}

	/**
	 * Apply an escaped, whitelisted search condition to the current query.
	 */
	private function apply_search($q = null, $field = null)
	{
		$q = trim((string) $q);
		if ($q === '') {
			return;
		}

		$field = in_array($field, $this->field_search, true) ? $field : null;
		$this->db->group_start();

		if ($field !== null) {
			$this->db->like('laporan.'.$field, $q);
		} else {
			foreach ($this->field_search as $index => $search_field) {
				$method = $index === 0 ? 'like' : 'or_like';
				$this->db->{$method}('laporan.'.$search_field, $q);
			}
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

/* End of file Model_rekap_laporan.php */
/* Location: ./application/models/Model_rekap_laporan.php */
