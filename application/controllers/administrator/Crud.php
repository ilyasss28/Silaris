<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Crud Controller
*| --------------------------------------------------------------------------
*| crud site
*|
*/
class Crud extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_crud');
	}

	/**
	* show all cruds
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('crud_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['cruds'] = $this->model_crud->get($filter, $field, $this->limit_page, $offset);
		$this->data['crud_counts'] = $this->model_crud->count_all($filter, $field);

		$config = [
			'base_url'     => 'administrator/crud/index/',
			'total_rows'   => $this->model_crud->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('Crud List');
		$this->render('backend/standart/administrator/crud/crud_list', $this->data);
	}

	/**
	* show all cruds
	*
	*/
	public function add()
	{
		$this->is_allowed('crud_add');
		$this->template->title('Crud New');
		$this->load->helper('directory');
		$directories = array();
		foreach ((array) directory_map(APPPATH . '/controllers/', 1) as $entry) {
			if (is_string($entry) && strtolower(pathinfo($entry, PATHINFO_EXTENSION)) === 'php') {
				$directories[] = strtolower(pathinfo($entry, PATHINFO_FILENAME));
			}
		}
		$tables = array_diff($this->db->list_tables(), $directories);

		$tables = array_diff($tables, get_table_not_allowed_for_builder());	

		$this->data['tables'] = $tables;
		$this->render('backend/standart/administrator/crud/crud_add', $this->data);
	}

	/**
	* Add New cruds
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('crud_add', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
		}

		$this->set_builder_validation_rules(true);

		echo $this->save_crud();
	}

	

	/**
	* Update view cruds
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('crud_update');

		$crud = $this->model_crud->find($id);
		if (!$crud || !$this->db->table_exists($crud->table_name)) {
			show_404();
		}

		$crud_field = $this->model_crud->get_crud_field($id);

		$new_crud_field = $this->model_crud->get_new_field($id);

		$crud_field = array_merge($crud_field,  $new_crud_field);

		$this->data = [
			'crud' => $crud,
			'crud_field' => $crud_field,
			'crud_field_validation' => $this->model_crud->get_crud_field_validation($id),
			'crud_field_option' => $this->model_crud->get_crud_field_option($id),
		];
		$this->template->title('Edit CRUD - ' . $crud->subject);
		$this->render('backend/standart/administrator/crud/crud_update', $this->data);
	}

	/**
	 * Display one CRUD builder configuration.
	 */
	public function view($id)
	{
		$this->is_allowed('crud_view');

		$crud = $this->model_crud->find($id);
		if (!$crud) {
			show_404();
		}

		$this->data['crud'] = $crud;
		$this->data['crud_fields'] = $this->model_crud->get_crud_field($id);
		$this->data['table_exists'] = $this->db->table_exists($crud->table_name);
		$this->template->title('Detail CRUD - ' . $crud->subject);
		$this->render('backend/standart/administrator/crud/crud_view', $this->data);
	}

	/**
	* Update cruds
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('crud_update', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		$crud = $this->model_crud->find($id);
		if (!$crud) {
			return $this->response(array('success' => false, 'message' => 'Konfigurasi CRUD tidak ditemukan.'));
		}

		$table_name = trim((string) $this->input->post('table_name'));
		$primary_key = trim((string) $this->input->post('primary_key'));
		$posted_fields = $this->input->post('crud');
		if ($table_name !== $crud->table_name || !$this->db->table_exists($crud->table_name)
			|| in_array($table_name, get_table_not_allowed_for_builder(), true)) {
			return $this->response(array('success' => false, 'message' => 'Tabel CRUD tidak valid atau sudah tidak tersedia.'));
		}
		if ($primary_key !== $crud->primary_key || !in_array($primary_key, $this->db->list_fields($crud->table_name), true)) {
			return $this->response(array('success' => false, 'message' => 'Primary key CRUD tidak valid.'));
		}
		if (!is_array($posted_fields) || empty($posted_fields)) {
			return $this->response(array('success' => false, 'message' => 'Minimal satu field harus tersedia pada konfigurasi CRUD.'));
		}

		$available_fields = $this->db->list_fields($crud->table_name);
		foreach ($posted_fields as $posted_field) {
			$field_name = is_array($posted_field) ? key($posted_field) : null;
			if (!$field_name || !in_array($field_name, $available_fields, true)) {
				return $this->response(array('success' => false, 'message' => 'Terdapat field yang tidak valid pada konfigurasi CRUD.'));
			}
		}

		$this->set_builder_validation_rules(false);

		return $this->output
			->set_content_type('application/json')
			->set_output($this->save_crud());
	}

	public function save_crud()
	{

		if ($this->form_validation->run()) {
			$crud_config = $this->normalize_crud_configuration($this->input->post('crud'));
			if (!is_array($crud_config) || empty($crud_config)) {
				return $this->response(array(
					'success' => false,
					'message' => 'Konfigurasi field CRUD tidak boleh kosong.'
				));
			}
			$table_name = trim((string) $this->input->post('table_name'));
			if (!$this->is_safe_identifier($table_name) || !$this->db->table_exists($table_name)
				|| in_array($table_name, get_table_not_allowed_for_builder(), true)) {
				return $this->response(array('success' => false, 'message' => 'Tabel sumber CRUD tidak tersedia.'));
			}
			$config_error = $this->validate_crud_configuration($table_name, $crud_config);
			if ($config_error !== null) {
				return $this->response(array('success' => false, 'message' => $config_error));
			}
			$available_fields = $this->db->list_fields($table_name);
			$submitted_field_names = array();
			foreach ($crud_config as $field_config) {
				$field_name = is_array($field_config) ? key($field_config) : null;
				if (!$field_name || !in_array($field_name, $available_fields, true) || in_array($field_name, $submitted_field_names, true)) {
					return $this->response(array('success' => false, 'message' => 'Konfigurasi field berisi data yang tidak valid atau duplikat.'));
				}
				$submitted_field_names[] = $field_name;
			}
			$primary_key = trim((string) $this->input->post('primary_key'));
			if ($primary_key === '' || !in_array($primary_key, $available_fields, true) || !in_array($primary_key, $submitted_field_names, true)) {
				return $this->response(array('success' => false, 'message' => 'Primary key wajib tersedia dalam konfigurasi field CRUD.'));
			}

			$this->load->library('parser');
			$this->load->helper('file');
			$this->load->library('crud_builder', [
				'crud' => $crud_config
				]);

			$this->data = [
				'php_open_tag' 				=> '<?php',
				'php_close_tag' 			=> '?>',
				'php_open_tag_echo' 		=> '<?=',
				'table_name'				=> $this->input->post('table_name'),
				'primary_key'				=> $this->input->post('primary_key'),
				'subject'					=> $this->input->post('subject'),
				'non_input_able_validation' => $this->crud_builder->getNonInputableValidation(),
				'input_able_validation'		=> $this->crud_builder->getInputableValidation(),
				'show_in_add_form'			=> $this->crud_builder->getFieldShowInAddForm(),
				'show_in_update_form'		=> $this->crud_builder->getFieldShowInUpdateForm(),
			];

			if ($this->input->post('title')) {
				$this->data['title'] = $this->input->post('title');
			} else {
				$this->data['title'] = $this->input->post('subject');
			}

			$view_path = FCPATH . '/application/views/modul/'.$table_name.'/';
			$controller_path = FCPATH . '/application/controllers/';
			$model_path = FCPATH . '/application/models/';

			if (!is_dir($view_path) && !mkdir($view_path, 0755, true)) {
				return $this->response(array('success' => false, 'message' => 'Folder view modul tidak dapat dibuat.'));
			}
			if (!is_writable($view_path) || !is_writable($controller_path) || !is_writable($model_path)) {
				return $this->response(array('success' => false, 'message' => 'Folder hasil generator tidak memiliki izin tulis.'));
			}

			$validate = $this->crud_builder->validateAll();

			if ($validate->isError()) {
				return $this->response([
					'success' => false,
					'message' => $validate->getErrorMessage()
					]);
			}

			$template_crud_path = 'core_template/crud/';
			$preserve_custom_files = is_file($view_path . '.tmc-preserve');

			$file_plan = array();
			if (!$preserve_custom_files) {
				$file_plan[$view_path.$table_name.'_list.php'] = $this->parser->parse($template_crud_path.'builder_list', $this->data, true);
				$file_plan[$controller_path.ucwords($table_name).'.php'] = $this->parser->parse($template_crud_path.'builder_controller', $this->data, true);
				$file_plan[$model_path.'Model_'.$table_name.'.php'] = $this->parser->parse($template_crud_path.'builder_model', $this->data, true);
			}

			if ($this->input->post('create')) {
				if (!$preserve_custom_files) {
					$file_plan[$view_path.$table_name.'_add.php'] = $this->parser->parse($template_crud_path.'builder_add', $this->data, true);
				}
			} elseif (!$preserve_custom_files) {
				$file_plan[$view_path.$table_name.'_add.php'] = null;
			}

			if ($this->input->post('update')) {
				if (!$preserve_custom_files) {
					$file_plan[$view_path.$table_name.'_update.php'] = $this->parser->parse($template_crud_path.'builder_update', $this->data, true);
				}
			} elseif (!$preserve_custom_files) {
				$file_plan[$view_path.$table_name.'_update.php'] = null;
			}

			if ($this->input->post('read')) {
				if (!$preserve_custom_files) {
					$file_plan[$view_path.$table_name.'_view.php'] = $this->parser->parse($template_crud_path.'builder_view', $this->data, true);
				}
			} elseif (!$preserve_custom_files) {
				$file_plan[$view_path.$table_name.'_view.php'] = null;
			}

			$this->db->trans_begin();
			$this->aauth->create_perm($table_name.'_list');
			$this->aauth->create_perm($table_name.'_delete');
			$this->aauth->create_perm($table_name.'_export');
			if ($this->post_flag('create')) {
				$this->aauth->create_perm($table_name.'_add');
			} else {
				$this->aauth->delete_perm($table_name.'_add');
			}
			if ($this->post_flag('update')) {
				$this->aauth->create_perm($table_name.'_update');
			} else {
				$this->aauth->delete_perm($table_name.'_update');
			}
			if ($this->post_flag('read')) {
				$this->aauth->create_perm($table_name.'_view');
			} else {
				$this->aauth->delete_perm($table_name.'_view');
			}

			$save_data = [
				'table_name' 		=> $table_name,
				'primary_key'		=> $primary_key,
				'subject' 			=> trim((string) $this->input->post('subject')),
				'title' 			=> trim((string) $this->input->post('title')),
				'page_read' 		=> $this->post_flag('read'),
				'page_update' 		=> $this->post_flag('update'),
				'page_create' 		=> $this->post_flag('create'),
			];

			if ($id_crud = $this->model_crud->crud_exist($this->input->post('table_name'))) {
				$this->model_crud->change($id_crud, $save_data);
			} else {
				$id_crud = $this->model_crud->store($save_data);
			}
			if (!$id_crud) {
				$this->db->trans_rollback();
				return $this->response(array('success' => false, 'message' => 'Konfigurasi utama CRUD gagal disimpan.'));
			}
			$save_data_field = [];
			$this->db->delete('crud_field', ['crud_id' => $id_crud]);
			$this->db->delete('crud_field_validation', ['crud_id' => $id_crud]);
			$this->db->delete('crud_custom_option', ['crud_id' => $id_crud]);

			foreach ($crud_config as $val) {
				$field_name = array_keys($val)[0];
				$field_label = isset($val[$field_name]['label']) ? $val[$field_name]['label'] : '';
				$input_type = isset($val[$field_name]['input_type']) ? $val[$field_name]['input_type'] : '';
				$show_in_column = isset($val[$field_name]['show_in_column']) ? $val[$field_name]['show_in_column'] : '';
				$show_in_add_form = isset($val[$field_name]['show_in_add_form']) ? $val[$field_name]['show_in_add_form'] : '';
				$show_in_update_form = isset($val[$field_name]['show_in_update_form']) ? $val[$field_name]['show_in_update_form'] : '';
				$show_in_detail_page = isset($val[$field_name]['show_in_detail_page']) ? $val[$field_name]['show_in_detail_page'] : '';
				$relation_table = isset($val[$field_name]['relation_table']) ? $val[$field_name]['relation_table'] : '';
				$relation_value = isset($val[$field_name]['relation_value']) ? $val[$field_name]['relation_value'] : '';
				$relation_label = isset($val[$field_name]['relation_label']) ? $val[$field_name]['relation_label'] : '';
				$sort = isset($val[$field_name]['sort']) ? $val[$field_name]['sort'] : '';

				$save_data_field = [
					'crud_id' 				=> $id_crud,
					'field_name' 			=> $field_name,
					'field_label' 			=> $field_label,
					'input_type' 			=> $input_type,
					'show_column' 			=> $show_in_column,
					'show_add_form' 		=> $show_in_add_form,
					'show_update_form' 		=> $show_in_update_form,
					'show_detail_page' 		=> $show_in_detail_page,
					'sort' 					=> $sort,
					'relation_table' 		=> $relation_table,
					'relation_value' 		=> $relation_value,
					'relation_label' 		=> $relation_label,
				];

				$this->db->insert('crud_field', $save_data_field);

				$crud_field_id = $this->db->insert_id();

				$save_data_rule = [];

				if (isset($val[$field_name]['validation']['rules'])) {
					foreach ($val[$field_name]['validation']['rules'] as $rule => $value) {
						$save_data_rule[] = [
							'crud_field_id' 	=> $crud_field_id, 
							'crud_id' 			=> $id_crud,
							'validation_name' 	=> $rule, 
							'validation_value'	=> $value
						];
					}
				}

				$save_data_option = [];

				if (isset($val[$field_name]['custom_option'])) {
					foreach ($val[$field_name]['custom_option'] as $option) {
						if (!empty($option['value']) or !empty($option['label'])) {
							$save_data_option[] = [
								'crud_field_id' 	=> $crud_field_id, 
								'crud_id' 			=> $id_crud,
								'option_value' 		=> $option['value'], 
								'option_label'		=> $option['label']
							];
						}
					}
				}

				if (count($save_data_rule)) {
					$this->db->insert_batch('crud_field_validation', $save_data_rule);
				}
				if (count($save_data_option)) {
					$this->db->insert_batch('crud_custom_option', $save_data_option);
				}
			}

			if ($this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return $this->response(array('success' => false, 'message' => 'Konfigurasi CRUD gagal disimpan ke database.'));
			}
			$file_error = $this->apply_file_plan($file_plan);
			if ($file_error !== null) {
				$this->db->trans_rollback();
				return $this->response(array('success' => false, 'message' => $file_error));
			}
			$this->db->trans_commit();

			$preserve_notice = $preserve_custom_files
				? '<strong>Desain khusus dipertahankan.</strong> Konfigurasi TMC tersimpan tanpa menimpa view, controller, dan model modul ini. '
				: '';
			if ($this->input->post('save_type') == 'stay') {
				$this->response['success'] = true;
				$this->response['message'] = $preserve_notice . cclang('success_save_data_stay', [
					anchor('administrator/crud', ' Go back to list'),
					anchor($this->input->post('table_name'), ' View')
				]);
			} else {
				set_message(
					$preserve_notice . cclang('success_save_data_redirect', [
					anchor($this->input->post('table_name'), ' View')
				]), 'success');
        		$this->response['success'] = true;
				$this->response['redirect'] = site_url('administrator/crud');
			}
		} else {
			$this->response['success'] = false;
			$this->response['message'] = validation_errors();
		}

		return json_encode($this->response);
	}

	/**
	* delete cruds
	*
	* @var $id String
	*/
	public function delete()
	{
		$this->is_allowed('crud_delete');
		if (strtoupper($this->input->method()) !== 'POST') {
			show_error('Method Not Allowed', 405);
		}

		$this->load->helper('file');
		$arr_id = array_values(array_unique(array_filter(array_map('intval', (array) $this->input->post('id')))));
		$remove = !empty($arr_id);
		foreach ($arr_id as $crud_id) {
			$remove = $this->_remove($crud_id) && $remove;
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'Crud'), 'success');
        } else {
            set_message(cclang('error_delete', 'Crud'), 'error');
        }

		redirect('administrator/crud');
	}

	/**
	* delete cruds
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$crud = $this->model_crud->find($id);
		if (!$crud || !$this->is_safe_identifier((string) $crud->table_name)) {
			return false;
		}
		$protected_marker = FCPATH . '/application/views/modul/' . $crud->table_name . '/.tmc-preserve';
		if (is_file($protected_marker)) {
			set_message('CRUD ini memiliki desain dan logika khusus sehingga tidak dapat dihapus melalui TMC CRUD.', 'error');
			return false;
		}

		if ($crud->table_name) {
			$view_path = FCPATH . '/application/views/modul/'.$crud->table_name.'/';
			$controller_path = FCPATH . '/application/controllers/'.ucwords($crud->table_name).'.php';
			$model_path = FCPATH . '/application/models/Model_'.$crud->table_name.'.php';
			$table_name = $crud->table_name;
			$file_plan = array(
				$view_path.$table_name.'_list.php' => null,
				$view_path.$table_name.'_add.php' => null,
				$view_path.$table_name.'_update.php' => null,
				$view_path.$table_name.'_view.php' => null,
				$controller_path => null,
				$model_path => null,
			);

			$permission_names = [
				$table_name.'_list',
				$table_name.'_add',
				$table_name.'_update',
				$table_name.'_view',
				$table_name.'_delete',
				$table_name.'_export'
			];
			$this->db->trans_begin();
			foreach ($permission_names as $permission_name) {
				$this->aauth->delete_perm($permission_name);
			}
			if (!$this->model_crud->remove($id) || $this->db->trans_status() === false) {
				$this->db->trans_rollback();
				return false;
			}
			if ($this->apply_file_plan($file_plan) !== null) {
				$this->db->trans_rollback();
				return false;
			}
			$this->db->trans_commit();

			if (is_dir($view_path) && count(scandir($view_path)) === 2) {
				@rmdir($view_path);
			}
			return true;
		}

		return false;
	}

	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('crud_export');

		$this->model_crud->export('crud', 'crud');
	}

	/**
	* Get field data
	*
	* @return html
	*/
	public function get_field_data($table)
	{
		if (!$this->can_read_builder_metadata()) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
			]);
		}

		$table = trim((string) $table);
		$available_tables = $this->db->list_tables();

		if ($table === '' || !in_array($table, $available_tables, true) || in_array($table, get_table_not_allowed_for_builder(), true)) {
			return $this->response([
				'success' => false,
				'message' => 'Tabel tidak tersedia atau tidak dapat digunakan oleh CRUD Builder.'
				]);
		}
		
		$this->data['html'] = $this->load->view('backend/standart/administrator/crud/crud_field_data.php', ['table' => $table], true);
		$this->data['subject'] = ucwords(clean_snake_case($table));
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	/**
	* Get field table
	*
	* @return html
	*/
	public function get_list_field_id($table)
	{
		if (!$this->can_read_builder_metadata() || !$this->is_allowed_relation_table($table)) {
			return $this->response(array('success' => false, 'message' => 'Tabel relasi tidak valid atau tidak diizinkan.'));
		}
		$this->data['html'] = $this->load->view('backend/standart/administrator/crud/crud_list_field.php', ['table' => $table], true);
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	/**
	* Get field table
	*
	* @return html
	*/
	public function get_list_field_label($table)
	{
		if (!$this->can_read_builder_metadata() || !$this->is_allowed_relation_table($table)) {
			return $this->response(array('success' => false, 'message' => 'Tabel relasi tidak valid atau tidak diizinkan.'));
		}
		$this->data['html'] = $this->load->view('backend/standart/administrator/crud/crud_list_field_label.php', ['table' => $table], true);
		$this->data['success'] = true;

		return $this->response($this->data);
	}

	private function set_builder_validation_rules($validate_table)
	{
		$table_rules = 'trim|required|callback_valid_builder_identifier';
		if ($validate_table) {
			$table_rules .= '|callback_valid_table_avaiable';
		}
		$this->form_validation->set_rules('table_name', 'Table', $table_rules);
		$this->form_validation->set_rules('subject', 'Subject', 'trim|required|max_length[100]|callback_valid_builder_label');
		$this->form_validation->set_rules('title', 'Title', 'trim|max_length[150]|callback_valid_builder_label');
		$this->form_validation->set_rules('primary_key', 'Primary Key of Table', 'trim|required|callback_valid_builder_identifier');
		$this->form_validation->set_rules('save_type', 'Save Type', 'trim|in_list[stay,back]');
		$this->form_validation->set_rules('create', 'Create Page', 'trim|in_list[yes]');
		$this->form_validation->set_rules('read', 'Detail Page', 'trim|in_list[yes]');
		$this->form_validation->set_rules('update', 'Update Page', 'trim|in_list[yes]');
	}

	public function valid_builder_identifier($value)
	{
		if ($this->is_safe_identifier((string) $value)) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'The {field} may only contain letters, numbers, and underscores, and must start with a letter.');
		return false;
	}

	public function valid_builder_label($value)
	{
		if ($value === '' || preg_match('/^[\pL\pN _.,()\/-]+$/u', (string) $value)) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'The {field} contains unsupported characters.');
		return false;
	}

	/**
	 * Kept with the historical method name because it is referenced by the
	 * existing form-validation callback.
	 */
	public function valid_table_avaiable($table_name)
	{
		$table_name = trim((string) $table_name);
		$controller = APPPATH.'controllers/'.ucwords($table_name).'.php';
		if (!$this->is_safe_identifier($table_name) || !$this->db->table_exists($table_name)
			|| in_array($table_name, get_table_not_allowed_for_builder(), true)
			|| $this->model_crud->crud_exist($table_name) || is_file($controller)) {
			$this->form_validation->set_message(__FUNCTION__, 'The selected table is unavailable, protected, or already has a CRUD/controller.');
			return false;
		}
		return true;
	}

	private function is_safe_identifier($value)
	{
		return (bool) preg_match('/^[A-Za-z][A-Za-z0-9_]*$/', $value);
	}

	private function post_flag($name)
	{
		return $this->input->post($name) === 'yes' ? 'yes' : '';
	}

	private function can_read_builder_metadata()
	{
		return $this->is_allowed('crud_add', false) || $this->is_allowed('crud_update', false);
	}

	private function is_allowed_relation_table($table)
	{
		$table = trim((string) $table);
		return $this->is_safe_identifier($table)
			&& $this->db->table_exists($table)
			&& !in_array($table, get_table_not_allowed_for_builder(), true);
	}

	/**
	 * Validate the complete nested builder payload before it reaches templates or SQL.
	 * Returns NULL when valid, otherwise a user-facing error string.
	 */
	private function validate_crud_configuration($table_name, array $crud_config)
	{
		$available_fields = $this->db->list_fields($table_name);
		$allowed_input_types = array_column($this->db->select('type')->get('crud_input_type')->result_array(), 'type');
		$allowed_validations = array_column($this->db->select('validation')->get('crud_input_validation')->result_array(), 'validation');
		$seen_fields = array();

		foreach ($crud_config as $field_wrapper) {
			if (!is_array($field_wrapper) || count($field_wrapper) !== 1) {
				return 'Struktur konfigurasi field tidak valid.';
			}
			$field_name = key($field_wrapper);
			$config = current($field_wrapper);
			if (!$this->is_safe_identifier((string) $field_name) || !in_array($field_name, $available_fields, true)
				|| isset($seen_fields[$field_name]) || !is_array($config)) {
				return 'Konfigurasi field berisi nama yang tidak valid atau duplikat.';
			}
			$seen_fields[$field_name] = true;

			$input_type = isset($config['input_type']) ? (string) $config['input_type'] : '';
			if (!in_array($input_type, $allowed_input_types, true)) {
				return 'Tipe input untuk field '.html_escape($field_name).' tidak valid.';
			}
			$label = isset($config['label']) ? trim((string) $config['label']) : '';
			if ($label === '' || mb_strlen($label) > 150 || !preg_match('/^[\pL\pN _.,()\/-]+$/u', $label)) {
				return 'Label untuk field '.html_escape($field_name).' tidak valid.';
			}
			foreach (array('show_in_column', 'show_in_add_form', 'show_in_update_form', 'show_in_detail_page') as $flag) {
				if (isset($config[$flag]) && $config[$flag] !== 'yes') {
					return 'Nilai opsi tampilan field tidak valid.';
				}
			}
			if (isset($config['sort']) && (!ctype_digit((string) $config['sort']) || (int) $config['sort'] < 1)) {
				return 'Urutan field harus berupa bilangan positif.';
			}

			$relation_table = isset($config['relation_table']) ? trim((string) $config['relation_table']) : '';
			if ($relation_table !== '') {
				if (!$this->is_allowed_relation_table($relation_table)) {
					return 'Tabel relasi untuk field '.html_escape($field_name).' tidak valid.';
				}
				$relation_fields = $this->db->list_fields($relation_table);
				if (!in_array(isset($config['relation_value']) ? $config['relation_value'] : '', $relation_fields, true)
					|| !in_array(isset($config['relation_label']) ? $config['relation_label'] : '', $relation_fields, true)) {
					return 'Kolom relasi untuk field '.html_escape($field_name).' tidak valid.';
				}
			}

			$rules = isset($config['validation']['rules']) ? $config['validation']['rules'] : array();
			if (!is_array($rules)) {
				return 'Aturan validasi field '.html_escape($field_name).' tidak valid.';
			}
			foreach ($rules as $rule => $value) {
				if (!in_array($rule, $allowed_validations, true) || is_array($value) || mb_strlen((string) $value) > 255) {
					return 'Aturan validasi field '.html_escape($field_name).' tidak diizinkan.';
				}
			}
			if (in_array($input_type, array('file', 'file_multiple'), true)) {
				$extensions = isset($rules['allowed_extension']) ? trim((string) $rules['allowed_extension']) : '';
				if ($extensions === '' || !preg_match('/^[A-Za-z0-9,|]+$/', $extensions)) {
					return 'Ekstensi file yang diizinkan wajib diisi untuk field '.html_escape($field_name).'.';
				}
				if (isset($rules['max_size']) && (!ctype_digit((string) $rules['max_size']) || (int) $rules['max_size'] < 1)) {
					return 'Batas ukuran file untuk field '.html_escape($field_name).' harus berupa bilangan positif.';
				}
			}

			if (isset($config['custom_option'])) {
				if (!is_array($config['custom_option']) || count($config['custom_option']) > 100) {
					return 'Pilihan khusus field '.html_escape($field_name).' tidak valid.';
				}
				foreach ($config['custom_option'] as $option) {
					if (!is_array($option) || !array_key_exists('value', $option) || !isset($option['label'])
						|| mb_strlen((string) $option['value']) > 255 || mb_strlen((string) $option['label']) > 255
						|| !preg_match('/^[\pL\pN _.,()\/@:+-]*$/u', (string) $option['value'])
						|| !preg_match('/^[\pL\pN _.,()\/@:+-]+$/u', (string) $option['label'])) {
						return 'Pilihan khusus field '.html_escape($field_name).' tidak valid.';
					}
				}
			}
		}

		return null;
	}

	/**
	 * Every field renders one hidden custom-option template. Browsers submit that
	 * empty template even when the selected input type does not use custom
	 * options. Remove only completely empty template rows; partially completed
	 * rows remain so validation can report them to the user.
	 */
	private function normalize_crud_configuration($crud_config)
	{
		if (!is_array($crud_config)) {
			return $crud_config;
		}

		foreach ($crud_config as &$field_wrapper) {
			if (!is_array($field_wrapper) || count($field_wrapper) !== 1) {
				continue;
			}
			$field_name = key($field_wrapper);
			if (!is_array($field_wrapper[$field_name]) || !isset($field_wrapper[$field_name]['custom_option'])
				|| !is_array($field_wrapper[$field_name]['custom_option'])) {
				continue;
			}

			$options = array_filter($field_wrapper[$field_name]['custom_option'], function ($option) {
				if (!is_array($option)) {
					return true;
				}
				$value = isset($option['value']) ? trim((string) $option['value']) : '';
				$label = isset($option['label']) ? trim((string) $option['label']) : '';
				return $value !== '' || $label !== '';
			});

			if ($options) {
				$field_wrapper[$field_name]['custom_option'] = array_values($options);
			} else {
				unset($field_wrapper[$field_name]['custom_option']);
			}
		}
		unset($field_wrapper);

		return $crud_config;
	}

	/**
	 * Apply generated files as one recoverable operation. Every previous file is
	 * restored when any write/delete fails, so source code cannot be left half-built.
	 */
	private function apply_file_plan(array $file_plan)
	{
		if (empty($file_plan)) {
			return null;
		}

		$allowed_roots = array(
			realpath(APPPATH.'controllers'),
			realpath(APPPATH.'models'),
			realpath(APPPATH.'views/modul')
		);
		$snapshots = array();
		$applied = array();

		foreach ($file_plan as $path => $content) {
			if ($content === null && !is_file($path)) {
				continue;
			}
			$directory = realpath(dirname($path));
			$allowed = false;
			foreach ($allowed_roots as $root) {
				if ($root !== false && $directory !== false
					&& strpos(str_replace('\\', '/', $directory).'/', str_replace('\\', '/', $root).'/') === 0) {
					$allowed = true;
					break;
				}
			}
			if (!$allowed) {
				$this->restore_file_snapshots($snapshots, $applied);
				return 'Lokasi file hasil generator tidak valid.';
			}

			$snapshots[$path] = is_file($path) ? file_get_contents($path) : null;
			if ($content === null) {
				if (is_file($path) && !unlink($path)) {
					$this->restore_file_snapshots($snapshots, $applied);
					return 'File CRUD lama tidak dapat dinonaktifkan.';
				}
				$applied[] = $path;
				continue;
			}

			$temp_path = tempnam($directory, '.tmc-');
			if ($temp_path === false || !write_file($temp_path, $content)) {
				$this->restore_file_snapshots($snapshots, $applied);
				return 'File sementara hasil generator tidak dapat dibuat.';
			}
			if (is_file($path) && !unlink($path)) {
				@unlink($temp_path);
				$this->restore_file_snapshots($snapshots, $applied);
				return 'File CRUD lama tidak dapat diganti.';
			}
			if (!rename($temp_path, $path)) {
				@unlink($temp_path);
				if ($snapshots[$path] !== null) {
					write_file($path, $snapshots[$path]);
				}
				$this->restore_file_snapshots($snapshots, $applied);
				return 'File hasil generator tidak dapat dipasang.';
			}
			$applied[] = $path;
		}

		return null;
	}

	private function restore_file_snapshots(array $snapshots, array $applied)
	{
		foreach (array_reverse($applied) as $path) {
			if (array_key_exists($path, $snapshots) && $snapshots[$path] !== null) {
				write_file($path, $snapshots[$path]);
			} elseif (is_file($path)) {
				@unlink($path);
			}
		}
	}
}

/* End of file Crud.php */
/* Location: ./application/controllers/administrator/Crud.php */
