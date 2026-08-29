<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Access Controller
*| --------------------------------------------------------------------------
*| access site
*|
*/
class Access extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model([
			'model_access',
			'model_group'
		]);
	}

	/**
	* show all access
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('access_list');

		$this->data['groups'] = $this->model_group->find_all();

		$this->template->title('Access List');
		$this->render('backend/standart/administrator/access/access_list', $this->data);
	}

	/**
	* Update accesss
	*
	* @var String $id 
	*/
	public function save()
	{
		if (!$this->is_allowed('access_update', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		$group_id = (int) $this->input->post('group_id');
		$permissions = $this->input->post('id');
		$permissions = is_array($permissions) ? array_values(array_unique(array_filter(array_map('intval', $permissions)))) : [];

		if ($group_id < 1 || !$this->db->where('id', $group_id)->count_all_results('aauth_groups')) {
			return $this->response([
				'success' => false,
				'message' => 'Grup pengguna tidak valid.',
			], 422);
		}

		if (!empty($permissions)) {
			$valid_permissions = $this->db
				->select('id')
				->where_in('id', $permissions)
				->get('aauth_perms')
				->result_array();
			$permissions = array_map('intval', array_column($valid_permissions, 'id'));
		}

		$this->db->trans_start();
		$this->db->delete('aauth_perm_to_group', ['group_id' => $group_id]);
		if (!empty($permissions)) {
			$data = [];
			foreach ($permissions as $perms) {
				$data[] = [
					'perm_id' => $perms,
					'group_id' => $group_id,
				];
			}
			$this->db->insert_batch('aauth_perm_to_group', $data);
		}
		$this->db->trans_complete();
		$save_access = $this->db->trans_status();

		if ($save_access) {
			$this->data = [
				'success' => true,
				'message' => cclang('success_save_data_stay', [
				]),
			];
		} else {
			$this->data = [
				'success' => false,
				'message' => cclang('data_not_change'),
			];
		}

		return $this->response($this->data);
	}

	/**
    * Get Access group
    *
    * @var String $group_id 
    */
    public function get_access_group($group_id)
    {
        if (!$this->is_allowed('access_list', false)) {
            echo '<center>Sorry you do not have permission to access</center>';
            exit;
        }
        $group_id = (int) $group_id;
        if ($group_id < 1 || !$this->db->where('id', $group_id)->count_all_results('aauth_groups')) {
            $this->output->set_status_header(404);
            echo '<li class="access-empty"><p>Grup pengguna tidak ditemukan.</p></li>';
            return;
        }

        $group_perms_groupping = [];

        $group_perms = $this->model_group->get_permission_group($group_id);
        foreach(db_get_all_data('aauth_perms') as $perms) { 

            $group_name = 'other';
            $perm_tmp_arr = explode('_', $perms->name);

            if (isset($perm_tmp_arr[0]) AND !empty($perm_tmp_arr[0])) {
                $group_name =  strtolower($perm_tmp_arr[0]);
            } 
            $group_perms_groupping[$group_name][] = $perms;
        }

        if (empty($group_perms_groupping)) {
            echo '<li class="access-empty"><i class="fa fa-key fa-2x"></i><p>Belum ada permission yang tersedia.</p></li>';
            return;
        }

        $group_index = 0;
        foreach($group_perms_groupping as $group_name => $childs) {
            $group_key = 'permission-group-' . $group_index++;
            ?>
            <li class="permission-group" data-permission-group="<?= $group_key; ?>">
                <div class="permission-group-header">
                    <button type="button" class="permission-group-toggle" data-target="<?= $group_key; ?>" title="Centang atau kosongkan seluruh permission pada kelompok ini">
                        <i class="fa fa-check-square-o"></i>
                        <span><?= _ent(ucwords(clean_snake_case($group_name))); ?></span>
                    </button>
                    <span class="permission-count"><?= count($childs); ?> permission</span>
                </div>
                <ul class="permission-items">
                    <?php foreach($childs as $perms) { ?>
                    <li class="permission-item">
                        <label>
                            <input type="checkbox" class="check" data-group="<?= $group_key; ?>" name="id[]" value="<?= (int) $perms->id; ?>" <?= in_array($perms->id, $group_perms) ? 'checked' : ''; ?>>
                            <span><?= _ent(ucwords(clean_snake_case($perms->name))); ?></span>
                        </label>
                    </li>
                    <?php } ?>
                </ul>
            </li>
            <?php
        }
    }
	
}


/* End of file Access.php */
/* Location: ./application/controllers/administrator/Access.php */
