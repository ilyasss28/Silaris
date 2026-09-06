{php_open_tag}
defined('BASEPATH') OR exit('No direct script access allowed');

class Model_{table_name} extends MY_Model {

	<?php $field_in_column = $this->crud_builder->getFieldShowInColumn(); ?>
private $primary_key 	= '{primary_key}';
	private $table_name 	= '{table_name}';
	private $field_search 	= ['<?= implode("', '", $field_in_column); ?>'];

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
        $this->db->limit($limit, $offset);
        $this->db->order_by('{table_name}.'.$this->primary_key, "DESC");
		$query = $this->db->get($this->table_name);

		return $query->result();
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
			$qualified_field = '{table_name}.'.$search_field;
			$index === 0
				? $this->db->like($qualified_field, $q)
				: $this->db->or_like($qualified_field, $q);
		}
		$this->db->group_end();
	}

    public function join_avaiable() {
        <?php
        $tables = [];
        $i= ''; 
        foreach ($this->crud_builder->getFieldRelation() as $field => $join): 
            $tables[] = $join['relation_table'];
            $count = array_count_values($tables);
            if (in_array($join['relation_table'], $tables)) {
                $i = $count[$join['relation_table']]-1;
                if ($i<=0) {
                    $i = '';
                }
            }

        ?>$this->db->join('<?= $join['relation_table'] ; ?><?= $i > 0 ? ' '.$join['relation_table'].$i : '' ; ?>', '<?= $join['relation_table'].$i ; ?>.<?= $join['relation_value']; ?> = {table_name}.<?= $field; ?>', 'LEFT');
        <?php endforeach; ?>

        return $this;
    }

    public function filter_avaiable() {
        <?php
        foreach ($this->crud_builder->getFieldByType('current_user_id') as $field): 
        ?>$this->db->where('<?= $field ?>', get_user_data('id'));
        <?php endforeach; ?>

        return $this;
    }

}

/* End of file Model_{table_name}.php */
/* Location: ./application/models/Model_{table_name}.php */
