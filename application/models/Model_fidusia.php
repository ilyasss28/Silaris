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
		$this->load->library('report_access');
	}

	public function count_all($q = null, $field = null)
	{
		$this->join_avaiable()->filter_avaiable();
		$this->apply_access_scope();
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
		$this->apply_access_scope();
		$this->apply_search_conditions($this->table_name, $this->field_search, $q, $field);
        $this->db->limit(max(0, (int) $limit), max(0, (int) $offset));
        $this->db->order_by('fidusia.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
	}

	/** Apply role and jurisdiction restrictions to every Fidusia operation. */
	private function apply_access_scope()
	{
		$this->report_access->apply_scope($this->db, $this->table_name);
	}

	/**
	 * Some records were imported without fidusia.nama_notaris. Fall back to
	 * the owning account's full name so the display never shows a blank
	 * notary name.
	 */
	private function select_with_notaris_fallback()
	{
		$this->db->select(
			"fidusia.*, COALESCE(NULLIF(TRIM(fidusia.nama_notaris), ''), notaris_owner.full_name, fidusia.username) AS nama_notaris",
			false
		);
		$this->db->join('aauth_users AS notaris_owner', 'notaris_owner.id = fidusia.owner_user_id', 'left');
	}

	public function find($id = null, $select_field = [])
	{
		if (is_array($select_field) && count($select_field)) {
			$this->db->select($select_field);
		} else {
			$this->select_with_notaris_fallback();
		}

		$this->db->where($this->table_name . '.' . $this->primary_key, $id);
		$this->apply_access_scope();
		$query = $this->db->get($this->table_name);

		return $query->num_rows() > 0 ? $query->row() : false;
	}

	public function change($id = null, $data = [])
	{
		$this->db->where($this->primary_key, $id);
		$this->apply_access_scope();
		$this->db->update($this->table_name, $data);

		return $this->db->affected_rows();
	}

	public function remove($id = null)
	{
		$this->db->where($this->primary_key, $id);
		$this->apply_access_scope();

		return $this->db->delete($this->table_name);
	}

	public function export_scoped($subject = 'fidusia')
	{
		$this->apply_access_scope();
		return parent::export($this->table_name, $subject);
	}

	public function pdf_scoped($title = 'Fidusia')
	{
		$this->apply_access_scope();
		return parent::pdf($this->table_name, $title);
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
