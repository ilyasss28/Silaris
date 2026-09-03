<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_waarmerking extends MY_Model {

	private $primary_key 	= 'id_waarmerking';
	private $table_name 	= 'waarmerking';
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
		$scope = $username !== 'admin' ? ['waarmerking.username' => $username] : [];
		$this->join_avaiable()->filter_avaiable();
		return $this->count_search_results($this->table_name, $this->field_search, $q, $field, $scope);
	}

	public function get($q = null, $field = null, $limit = 0, $offset = 0, $select_field = [])
	{
		$username = get_user_data('username');
		$scope = $username !== 'admin' ? ['waarmerking.username' => $username] : [];

        if (is_array($select_field) AND count($select_field)) {
        	$this->db->select($select_field);
        } else {
        	$this->select_with_notaris_fallback();
        }

		$this->join_avaiable()->filter_avaiable();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field, $scope);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('waarmerking.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	/**
	 * Many legacy records were imported without waarmerking.nama_notaris.
	 * Fall back to the account matching the record's username so the
	 * display never shows a blank notary name.
	 */
	private function select_with_notaris_fallback()
	{
		$this->db->select(
			"waarmerking.*, COALESCE(NULLIF(TRIM(waarmerking.nama_notaris), ''), notaris_owner.full_name, waarmerking.username) AS nama_notaris",
			false
		);
		$this->db->join('aauth_users AS notaris_owner', 'LOWER(notaris_owner.username) = LOWER(waarmerking.username)', 'left');
	}

	public function find($id = null, $select_field = [])
	{
		if (is_array($select_field) && count($select_field)) {
			$this->db->select($select_field);
		} else {
			$this->select_with_notaris_fallback();
		}

		$this->db->where($this->table_name . '.' . $this->primary_key, $id);
		$query = $this->db->get($this->table_name);

		return $query->num_rows() > 0 ? $query->row() : false;
	}

    public function join_avaiable() {
        
        return $this;
    }

    public function filter_avaiable() {
        
        return $this;
    }

}

/* End of file Model_waarmerking.php */
/* Location: ./application/models/Model_waarmerking.php */
