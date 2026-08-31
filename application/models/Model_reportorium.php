<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_reportorium extends MY_Model {

	private $primary_key 	= 'id_reportorium';
	private $table_name 	= 'reportorium';
	private $field_search 	= ['nama_notaris', 'nomor_akta', 'tanggal_akta', 'sifat_akta', 'penghadap'];

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
		$username = get_user_data('username');
		$scope = $username !== 'admin' ? ['reportorium.username' => $username] : [];
		$this->join_avaiable()->filter_avaiable();
		return $this->count_search_results($this->table_name, $this->field_search, $q, $field, $scope);
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$username = get_user_data('username');
		$scope = $username !== 'admin' ? ['reportorium.username' => $username] : [];

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        }
		
		$this->join_avaiable()->filter_avaiable();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field, $scope);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('reportorium.'.$this->primary_key, "DESC");
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

/* End of file Model_reportorium.php */
/* Location: ./application/models/Model_reportorium.php */
