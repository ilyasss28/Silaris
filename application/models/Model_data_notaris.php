<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_data_notaris extends MY_Model {

	private $primary_key 	= 'id_notaris';
	private $table_name 	= 'data_notaris';
	private $field_search 	= ['nama_notaris', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin', 'wilayah', 'alamat_kantor', 'foto', 'kode_wilayah', 'lat', 'no_telepon', 'long'];

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
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "data_notaris.".$field . " LIKE '%" . $q . "%' )";
        }

		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
		$query = $this->db->get($this->table_name);

		return $query->num_rows();
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$iterasi = 1;
        $num = count($this->field_search);
        $where = NULL;
        $q = $this->scurity($q);
		$field = $this->scurity($field);

        if (empty($field)) {
	        foreach ($this->field_search as $field) {
	            if ($iterasi == 1) {
	                $where .= "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "data_notaris.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "data_notaris.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('data_notaris.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

    public function join_avaiable() {
        $this->db->join('wil', 'wil.nama_wilayah = data_notaris.wilayah', 'LEFT');
        $this->db->join('wilayah', 'wilayah.kd_wilayah = data_notaris.kode_wilayah', 'LEFT');
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_data_notaris.php */
/* Location: ./application/models/Model_data_notaris.php */