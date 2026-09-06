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
			'report_scoped' => true,
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
	                $where .= "fidusia.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "fidusia.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "fidusia.".$field . " LIKE '%" . $q . "%' )";
        }

		$this->join_avaiable()->filter_avaiable();
		$this->apply_report_scope($this->table_name);
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
	                $where .= "fidusia.".$field . " LIKE '%" . $q . "%' ";
	            } else {
	                $where .= "OR " . "fidusia.".$field . " LIKE '%" . $q . "%' ";
	            }
	            $iterasi++;
	        }

	        $where = '('.$where.')';
        } else {
        	$where .= "(" . "fidusia.".$field . " LIKE '%" . $q . "%' )";
        }

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
		$this->apply_report_scope($this->table_name);
        $this->db->where($where);
        $this->db->limit($limit, $offset);
        $this->db->order_by('fidusia.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $this->attach_owner_display_names($query->result());
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
