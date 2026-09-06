{php_open_tag}
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| <?= ucwords(clean_snake_case($table_name)); ?> Controller
*| --------------------------------------------------------------------------
*| <?= ucwords(clean_snake_case($table_name)); ?> site
*|
*/
class <?= ucwords($table_name); ?> extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_{table_name}');
	}

	/**
	* show all <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('{table_name}_list');

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');

		$this->data['{table_name}s'] = $this->model_{table_name}->get($filter, $field, $this->limit_page, $offset);
		$this->data['{table_name}_counts'] = $this->model_{table_name}->count_all($filter, $field);

		$config = [
			'base_url'     => '{table_name}/index/',
			'total_rows'   => $this->model_{table_name}->count_all($filter, $field),
			'per_page'     => $this->limit_page,
			'uri_segment'  => 3,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('<?= ucwords(clean_snake_case($title)); ?> List');
		$this->render('modul/{table_name}/{table_name}_list', $this->data);
	}
	
	<?php if ($this->input->post('create')) { ?>/**
	* Add new {table_name}s
	*
	*/
	public function add()
	{
		$this->is_allowed('{table_name}_add');

		$this->template->title('<?= ucwords(clean_snake_case($title)); ?> New');
		$this->render('modul/{table_name}/{table_name}_add', $this->data);
	}

	/**
	* Add New <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('{table_name}_add', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		<?php foreach ($this->crud_builder->getFieldValidation() as $input => $rules):
		$option = $this->crud_builder->getFieldAndOptios($input); 
			if (in_array($input, $this->crud_builder->getFieldShowInAddForm())) {
				$rules_arr = [];
				foreach ($rules as $rule => $val) {
					if (!in_array($rule, ['allowed_extension', 'max_width', 'max_height', 'max_size', 'max_item'])) {
						if (in_array($rule, $this->crud_builder->getCallBackValidation())) {
							$call_back = 'callback_';
						} else {
							$call_back = '';
						}
						if (in_array($rule, $non_input_able_validation)) {
							$rules_arr[] = $call_back.$rule;
						} else {
							$rules_arr[] = $call_back.$rule.'['.addslashes((string) $val).']';
						}
					}
				}
				if ($this->crud_builder->getFieldFile($input)) {
					?>$this->form_validation->set_rules('{table_name}_<?= $input; ?>_name', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				} elseif ($this->crud_builder->getFieldFileMultiple($input)) {
					?>$this->form_validation->set_rules('{table_name}_<?= $input; ?>_name[]', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				} else {
					if ($this->crud_builder->isMultipleInput($input)) {
						$multiple = '[]';
					} else {
						$multiple = '';
					}
				?>$this->form_validation->set_rules('<?= $input.$multiple; ?>', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				}
			}
		endforeach; 
		?>


		if ($this->form_validation->run()) {
		<?php 
		foreach ($this->crud_builder->getFieldFile() as $file):
			if (in_array($file, $show_in_add_form)) {
		?>
	${table_name}_<?= $file; ?>_uuid = basename((string) $this->input->post('{table_name}_<?= $file; ?>_uuid', true));
			${table_name}_<?= $file; ?>_name = basename((string) $this->input->post('{table_name}_<?= $file; ?>_name', true));
		<?php 
			}
		endforeach; 
		?>

			$save_data = [
			<?php 
			foreach ($this->crud_builder->getFieldShowInAddForm(true, true) as $input => $option):
				if (in_array($option['input_type'], $this->crud_builder->getInputMultiple())) { 
				?>
	'<?= $input; ?>' => implode(',', (array) $this->input->post('<?= $input; ?>', true)),
<?php } elseif ($option['input_type'] == 'timestamp') { ?>
	'<?= $input; ?>' => date('Y-m-d H:i:s'),
<?php } elseif ($option['input_type'] == 'current_user_username') { ?>
	'<?= $input; ?>' => get_user_data('username'),
	<?php } elseif ($option['input_type'] == 'current_user_full_name') { ?>
	'<?= $input; ?>' => get_user_data('full_name'),
<?php } elseif ($option['input_type'] == 'current_user_id') { ?>
	'<?= $input; ?>' => get_user_data('id'),<?php 
	} elseif ($option['input_type'] == 'file_multiple') { continue; } 
	else { ?>
	'<?= $input; ?>' => $this->input->post('<?= $input; ?>', true),
<?php } ?>
			<?php endforeach; ?>];

			<?php 
			if ($this->crud_builder->getFieldFile() or $this->crud_builder->getFieldFileMultiple()) { 
				?>if (!is_dir(FCPATH . '/uploads/{table_name}/') && !mkdir(FCPATH . '/uploads/{table_name}/', 0755, true)) {
				return $this->response(array('success' => false, 'message' => 'Folder upload tidak dapat dibuat.'));
			}

			<?php	
			}
			foreach ($this->crud_builder->getFieldFile() as $file):
				if (in_array($file, $show_in_add_form)) {
			?>
if (!empty(${table_name}_<?= $file; ?>_name)) {
				if (!preg_match('/^[A-Za-z0-9-]+$/', ${table_name}_<?= $file; ?>_uuid)) {
					return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
				}
				${table_name}_<?= $file; ?>_name_copy = date('YmdHis') . '-' . ${table_name}_<?= $file; ?>_name;

				rename(FCPATH . 'uploads/tmp/' . ${table_name}_<?= $file; ?>_uuid . '/' . ${table_name}_<?= $file; ?>_name, 
						FCPATH . 'uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy);

				if (!is_file(FCPATH . '/uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['<?= $file; ?>'] = ${table_name}_<?= $file; ?>_name_copy;
			}
		
			<?php 
				}
			endforeach; 
			foreach ($this->crud_builder->getFieldFileMultiple() as $file):
				if (in_array($file, $show_in_add_form)) {
					$listed_image = [];
			?>${table_name}_<?= $file; ?>_names = (array) $this->input->post('{table_name}_<?= $file; ?>_name', true);
			${table_name}_<?= $file; ?>_uuids = (array) $this->input->post('{table_name}_<?= $file; ?>_uuid', true);
			if (count(${table_name}_<?= $file; ?>_names)) {
				foreach (${table_name}_<?= $file; ?>_names as $idx => $file_name) {
					$file_name = basename((string) $file_name);
					$file_uuid = isset(${table_name}_<?= $file; ?>_uuids[$idx]) ? basename((string) ${table_name}_<?= $file; ?>_uuids[$idx]) : '';
					if (!preg_match('/^[A-Za-z0-9-]+$/', $file_uuid)) {
						return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
					}
					${table_name}_<?= $file; ?>_name_copy = date('YmdHis') . '-' . $file_name;

					rename(FCPATH . 'uploads/tmp/' . $file_uuid . '/' .  $file_name,
							FCPATH . 'uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy);

					$listed_image[] = ${table_name}_<?= $file; ?>_name_copy;

					if (!is_file(FCPATH . '/uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy)) {
						echo json_encode([
							'success' => false,
							'message' => 'Error uploading file'
							]);
						exit;
					}
				}

				$save_data['<?= $file; ?>'] = implode($listed_image, ',');
			}
		
			<?php 
				}
			endforeach; 
			?>

			$save_{table_name} = $this->model_{table_name}->store($save_data);

			if ($save_{table_name}) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $save_{table_name};
					$this->data['message'] = cclang('success_save_data_stay', [
						anchor('{table_name}/edit/' . $save_{table_name}, 'Edit <?= ucwords(clean_snake_case($table_name)); ?>'),
						anchor('{table_name}', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('{table_name}/edit/' . $save_{table_name}, 'Edit <?= ucwords(clean_snake_case($table_name)); ?>')
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('{table_name}');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('{table_name}');
				}
			}

		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	<?php } ?>

	<?php if ($this->input->post('update')) { ?>
	/**
	* Update view <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('{table_name}_update');

		$this->data['{table_name}'] = $this->model_{table_name}->filter_avaiable()->find($id);
		if (!$this->data['{table_name}']) {
			show_404();
		}

		$this->template->title('<?= ucwords(clean_snake_case($title)); ?> Update');
		$this->render('modul/{table_name}/{table_name}_update', $this->data);
	}

	/**
	* Update <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('{table_name}_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		if (!$this->model_{table_name}->filter_avaiable()->find($id)) {
			return $this->response(array('success' => false, 'message' => 'Data tidak ditemukan atau tidak dapat diakses.'));
		}
		
		<?php foreach ($this->crud_builder->getFieldValidation() as $input => $rules):
		$option = $this->crud_builder->getFieldAndOptios($input); 
			if (in_array($input, $this->crud_builder->getFieldShowInUpdateForm())) {
				$rules_arr = [];
				foreach ($rules as $rule => $val) {
					if (!in_array($rule, ['allowed_extension', 'max_width', 'max_height', 'max_size', 'max_item'])) {
						if (in_array($rule, $this->crud_builder->getCallBackValidation())) {
							$call_back = 'callback_';
						} else {
							$call_back = '';
						}
						if (in_array($rule, $non_input_able_validation)) {
							$rules_arr[] = $call_back.$rule;
						} else {
							$rules_arr[] = $call_back.$rule.'['.addslashes((string) $val).']';
						}
					}
				}
				if ($this->crud_builder->getFieldFile($input)) {
					?>$this->form_validation->set_rules('{table_name}_<?= $input; ?>_name', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				} elseif ($this->crud_builder->getFieldFileMultiple($input)) {
					?>$this->form_validation->set_rules('{table_name}_<?= $input; ?>_name[]', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				} else {
				if ($this->crud_builder->isMultipleInput($input)) {
					$multiple = '[]';
				} else {
					$multiple = '';
				}
				?>$this->form_validation->set_rules('<?= $input.$multiple; ?>', '<?= ucwords(clean_snake_case($option['label'])); ?>', 'trim<?= build_rules('|', $rules_arr); ?>');
		<?php
				}
			}
		endforeach; 
		?>

		if ($this->form_validation->run()) {
		<?php 
		foreach ($this->crud_builder->getFieldFile() as $file):
			if (in_array($file, $show_in_add_form)) {
		?>
	${table_name}_<?= $file; ?>_uuid = basename((string) $this->input->post('{table_name}_<?= $file; ?>_uuid', true));
			${table_name}_<?= $file; ?>_name = basename((string) $this->input->post('{table_name}_<?= $file; ?>_name', true));
		<?php 
			}
		endforeach; 
		?>

			$save_data = [
			<?php foreach ($this->crud_builder->getFieldShowInUpdateForm(true, true) as $input => $option):
				if (in_array($option['input_type'], $this->crud_builder->getInputMultiple())) { 
				?>
	'<?= $input; ?>' => implode(',', (array) $this->input->post('<?= $input; ?>', true)),
<?php } elseif ($option['input_type'] == 'timestamp') { ?>
	'<?= $input; ?>' => date('Y-m-d H:i:s'),
<?php } elseif ($option['input_type'] == 'current_user_username') { ?>
	'<?= $input; ?>' => get_user_data('username'),
<?php } elseif ($option['input_type'] == 'current_user_id') { ?>
	'<?= $input; ?>' => get_user_data('id'),<?php 
	} elseif ($option['input_type'] == 'file_multiple') { continue; ?>
<?php } else { ?>
	'<?= $input; ?>' => $this->input->post('<?= $input; ?>', true),
<?php } ?>
			<?php endforeach; ?>];

			<?php 
			if ($this->crud_builder->getFieldFile()) { 
				?>if (!is_dir(FCPATH . '/uploads/{table_name}/') && !mkdir(FCPATH . '/uploads/{table_name}/', 0755, true)) {
				return $this->response(array('success' => false, 'message' => 'Folder upload tidak dapat dibuat.'));
			}

			<?php	
			}
			foreach ($this->crud_builder->getFieldFile() as $file):
				if (in_array($file, $show_in_add_form)) {
			?>
if (!empty(${table_name}_<?= $file; ?>_uuid)) {
				if (!preg_match('/^[A-Za-z0-9-]+$/', ${table_name}_<?= $file; ?>_uuid)) {
					return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
				}
				${table_name}_<?= $file; ?>_name_copy = date('YmdHis') . '-' . ${table_name}_<?= $file; ?>_name;

				rename(FCPATH . 'uploads/tmp/' . ${table_name}_<?= $file; ?>_uuid . '/' . ${table_name}_<?= $file; ?>_name, 
						FCPATH . 'uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy);

				if (!is_file(FCPATH . '/uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['<?= $file; ?>'] = ${table_name}_<?= $file; ?>_name_copy;
			}
		
			<?php 
				}
			endforeach; 
			foreach ($this->crud_builder->getFieldFileMultiple() as $file):
				if (in_array($file, $show_in_add_form)) {
					$listed_image = [];
			?>$listed_image = [];
			${table_name}_<?= $file; ?>_names = (array) $this->input->post('{table_name}_<?= $file; ?>_name', true);
			${table_name}_<?= $file; ?>_uuids = (array) $this->input->post('{table_name}_<?= $file; ?>_uuid', true);
			if (count(${table_name}_<?= $file; ?>_names)) {
				foreach (${table_name}_<?= $file; ?>_names as $idx => $file_name) {
					$file_name = basename((string) $file_name);
					$file_uuid = isset(${table_name}_<?= $file; ?>_uuids[$idx]) ? basename((string) ${table_name}_<?= $file; ?>_uuids[$idx]) : '';
					if ($file_uuid !== '') {
						if (!preg_match('/^[A-Za-z0-9-]+$/', $file_uuid)) {
							return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
						}
						${table_name}_<?= $file; ?>_name_copy = date('YmdHis') . '-' . $file_name;

						rename(FCPATH . 'uploads/tmp/' . $file_uuid . '/' .  $file_name,
								FCPATH . 'uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy);

						$listed_image[] = ${table_name}_<?= $file; ?>_name_copy;

						if (!is_file(FCPATH . '/uploads/{table_name}/' . ${table_name}_<?= $file; ?>_name_copy)) {
							echo json_encode([
								'success' => false,
								'message' => 'Error uploading file'
								]);
							exit;
						}
					} else {
						$listed_image[] = $file_name;
					}
				}
			}
			
			$save_data['<?= $file; ?>'] = implode($listed_image, ',');
		
			<?php 
				}
			endforeach; 
			?>

			$save_{table_name} = $this->model_{table_name}->change($id, $save_data);

			if ($save_{table_name}) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('{table_name}', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('{table_name}');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('{table_name}');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	<?php } ?>

	/**
	* delete <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $id String
	*/
	public function delete()
	{
		$this->is_allowed('{table_name}_delete');
		if (strtoupper($this->input->method()) !== 'POST') {
			show_error('Method Not Allowed', 405);
		}

		$this->load->helper('file');

		$arr_id = array_values(array_unique(array_filter(array_map('intval', (array) $this->input->post('id')))));
		$remove = !empty($arr_id);
		foreach ($arr_id as $id) {
			$remove = $this->_remove($id) && $remove;
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', '{table_name}'), 'success');
        } else {
            set_message(cclang('error_delete', '{table_name}'), 'error');
        }

		redirect_back();
	}

	<?php if ($this->input->post('read')) { ?>
	/**
	* View view <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('{table_name}_view');

		$this->data['{table_name}'] = $this->model_{table_name}->join_avaiable()->filter_avaiable()->find($id);
		if (!$this->data['{table_name}']) {
			show_404();
		}

		$this->template->title('<?= ucwords(clean_snake_case($title)); ?> Detail');
		$this->render('modul/{table_name}/{table_name}_view', $this->data);
	}
	<?php } ?>

	/**
	* delete <?= ucwords(clean_snake_case($table_name)); ?>s
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		${table_name} = $this->model_{table_name}->filter_avaiable()->find($id);
		if (!${table_name}) {
			return false;
		}

		<?php foreach ($files = $this->crud_builder->getFieldFile() as $file): 
		?>if (!empty(${table_name}-><?= $file; ?>)) {
			$path = FCPATH . '/uploads/{table_name}/' . basename((string) ${table_name}-><?= $file; ?>);

			if (is_file($path)) {
				$delete_file = unlink($path);
			}
		}
		<?php endforeach; ?>

		<?php foreach ($files = $this->crud_builder->getFieldFileMultiple() as $file): 
		?>if (!empty(${table_name}-><?= $file; ?>)) {
			foreach ((array) explode(',', ${table_name}-><?= $file; ?>) as $filename) {
				$path = FCPATH . '/uploads/{table_name}/' . basename((string) $filename);

				if (is_file($path)) {
					$delete_file = unlink($path);
				}
			}
		}
		<?php endforeach; ?>

		return $this->model_{table_name}->remove($id);
	}
	<?php foreach ($this->crud_builder->getFieldFile() as $file): 
		$max_size = $this->crud_builder->getFieldValidation($file, 'max_size');
		$max_height = $this->crud_builder->getFieldValidation($file, 'max_height');
		$max_width = $this->crud_builder->getFieldValidation($file, 'max_width');
		$allowed_extension = $this->crud_builder->getFieldValidation($file, 'allowed_extension');
		$allowed_extension = str_replace(',', '|', $allowed_extension ?? '');
	?>

	/**
	* Upload Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function upload_<?= $file; ?>_file()
	{
		if (!$this->is_allowed('{table_name}_add', false) && !$this->is_allowed('{table_name}_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = basename((string) $this->input->post('qquuid', true));
		if (!preg_match('/^[A-Za-z0-9-]+$/', $uuid)) {
			return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
		}

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> '{table_name}',
<?php if ($allowed_extension): 
			?>
			'allowed_types' => '<?= $allowed_extension; ?>',
<?php endif; 
			?><?php if ($max_size): 
			?>
			'max_size' 	 	=> <?= $max_size; ?>,
<?php endif; 
			?><?php if ($max_width): 
			?>
			'max_width' 	=> <?= $max_width; ?>,
<?php endif; 
			?><?php if ($max_height): 
			?>
			'max_height' 	=> <?= $max_height; ?>,
			<?php endif; ?>		]);
	}

	/**
	* Delete Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function delete_<?= $file; ?>_file($uuid)
	{
		if (!$this->is_allowed('{table_name}_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$uuid = basename((string) $uuid);
		$delete_by = $this->input->get('by', true);
		if ($delete_by === 'id' && !$this->model_{table_name}->filter_avaiable()->find($uuid)) {
			return $this->response(array('success' => false, 'message' => 'File tidak dapat diakses.'));
		}

		echo $this->delete_file([
			'uuid'              => $uuid,
			'delete_by'         => $delete_by,
            'field_name'        => '<?= $file; ?>', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => '{table_name}',
            'primary_key'       => '{primary_key}',
            'upload_path'       => 'uploads/{table_name}/'
        ]);
	}

	/**
	* Get Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function get_<?= $file; ?>_file($id)
	{
		if (!$this->is_allowed('{table_name}_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		${table_name} = $this->model_{table_name}->filter_avaiable()->find($id);
		if (!${table_name}) {
			return $this->response(array('success' => false, 'message' => 'File tidak dapat diakses.'));
		}

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => '<?= $file; ?>', 
            'table_name'        => '{table_name}',
            'primary_key'       => '{primary_key}',
            'upload_path'       => 'uploads/{table_name}/',
            'delete_endpoint'   => '{table_name}/delete_<?= $file; ?>_file'
        ]);
	}
	<?php endforeach; ?>

	<?php foreach ($this->crud_builder->getFieldFileMultiple() as $file): 
		$max_size = $this->crud_builder->getFieldValidation($file, 'max_size');
		$max_height = $this->crud_builder->getFieldValidation($file, 'max_height');
		$max_width = $this->crud_builder->getFieldValidation($file, 'max_width');
		$allowed_extension = $this->crud_builder->getFieldValidation($file, 'allowed_extension');
		$allowed_extension = str_replace(',', '|', $allowed_extension ?? '');
	?>

	/**
	* Upload Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function upload_<?= $file; ?>_file()
	{
		if (!$this->is_allowed('{table_name}_add', false) && !$this->is_allowed('{table_name}_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = basename((string) $this->input->post('qquuid', true));
		if (!preg_match('/^[A-Za-z0-9-]+$/', $uuid)) {
			return $this->response(array('success' => false, 'message' => 'Upload identifier tidak valid.'));
		}

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> '{table_name}',
<?php if ($allowed_extension): 
			?>
			'allowed_types' => '<?= $allowed_extension; ?>',
<?php endif; 
			?><?php if ($max_size): 
			?>
			'max_size' 	 	=> <?= $max_size; ?>,
<?php endif; 
			?><?php if ($max_width): 
			?>
			'max_width' 	=> <?= $max_width; ?>,
<?php endif; 
			?><?php if ($max_height): 
			?>
			'max_height' 	=> <?= $max_height; ?>,
			<?php endif; ?>		]);
	}

	/**
	* Delete Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function delete_<?= $file; ?>_file($uuid)
	{
		if (!$this->is_allowed('{table_name}_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}
		$uuid = basename((string) $uuid);
		$delete_by = $this->input->get('by', true);
		if ($delete_by === 'id' && !$this->model_{table_name}->filter_avaiable()->find($uuid)) {
			return $this->response(array('success' => false, 'message' => 'File tidak dapat diakses.'));
		}

		echo $this->delete_file([
			'uuid'              => $uuid,
			'delete_by'         => $delete_by,
            'field_name'        => '<?= $file; ?>', 
            'upload_path_tmp'   => './uploads/tmp/',
            'table_name'        => '{table_name}',
            'primary_key'       => '{primary_key}',
            'upload_path'       => 'uploads/{table_name}/'
        ]);
	}

	/**
	* Get Image <?= ucwords(clean_snake_case($table_name)); ?>
	* 
	* @return JSON
	*/
	public function get_<?= $file; ?>_file($id)
	{
		if (!$this->is_allowed('{table_name}_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		${table_name} = $this->model_{table_name}->filter_avaiable()->find($id);
		if (!${table_name}) {
			return $this->response(array('success' => false, 'message' => 'File tidak dapat diakses.'));
		}

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => '<?= $file; ?>', 
            'table_name'        => '{table_name}',
            'primary_key'       => '{primary_key}',
            'upload_path'       => 'uploads/{table_name}/',
            'delete_endpoint'   => '{table_name}/delete_<?= $file; ?>_file'
        ]);
	}
	<?php endforeach; ?>

	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('{table_name}_export');

		$this->model_{table_name}->export('{table_name}', '{table_name}');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('{table_name}_export');

		$this->model_{table_name}->pdf('{table_name}', '{table_name}');
	}
}


/* End of file {table_name}.php */
/* Location: ./application/controllers/<?= ucwords(clean_snake_case($table_name)); ?>.php */
