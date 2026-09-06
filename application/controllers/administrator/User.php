<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| User Controller
*| --------------------------------------------------------------------------
*| user site
*|
*/
class User extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_user');
		$this->load->model('model_data_notaris');
		$this->load->model('model_data_mpd');
		$this->load->library('storage_manager');
	}

	private function attach_notary_profile_data($user)
	{
		$this->data['is_notary_profile'] = $user && $this->is_notary_account((int) $user->id);
		$this->data['notary_profile'] = $this->data['is_notary_profile']
			? $this->model_data_notaris->find_for_user($user)
			: false;
		$this->data['notary_completeness'] = $this->data['is_notary_profile']
			? $this->model_data_notaris->profile_completeness($this->data['notary_profile'], $user)
			: null;
	}

	private function attach_mpd_profile_data($user)
	{
		$this->data['is_mpd_profile'] = $user
			&& $this->model_data_mpd->account_is_mpd((int) $user->id);
		$this->data['mpd_profile'] = $this->data['is_mpd_profile']
			? $this->model_data_mpd->find_for_user((int) $user->id)
			: false;
	}

	/**
	 * Move an avatar uploaded by Fine Uploader from the temporary directory.
	 *
	 * The upload library may sanitize the original client filename (for example,
	 * replacing spaces with underscores), so only the filename returned by the
	 * upload endpoint can safely be used here.
	 */
	private function move_uploaded_avatar($uuid, $file_name)
	{
		return $this->storage_manager->move_from_temp($uuid, $file_name, 'uploads/user/');
	}

	/**
	* show all users
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('user_list');
		$this->model_user->sync_missing_notary_accounts();
		$this->model_user->sync_ineligible_mpd_accounts();

		$filter = $this->input->get('q');
		$field 	= $this->input->get('f');
		$group = $this->model_user->normalize_group_filter($this->input->get('group', true));
		$status = $this->model_user->normalize_status_filter($this->input->get('account_status', true));

		$this->data['users'] = $this->model_user->attach_notary_roster_status(
			$this->model_user->get($filter, $field, $this->limit_page, $offset, $group, $status)
		);
		$this->data['user_counts'] = $this->model_user->count_all($filter, $field, $group, $status);
		$this->data['group_filter'] = $group;
		$this->data['status_filter'] = $status;
		$this->data['filterable_groups'] = $this->model_user->get_filterable_groups();

		$config = [
			'base_url'     => 'administrator/user/index/',
			'total_rows'   => $this->data['user_counts'],
			'per_page'     => $this->limit_page,
			'uri_segment'  => 4,
		];

		$this->data['pagination'] = $this->pagination($config);

		$this->template->title('User List');
		$this->render('backend/standart/administrator/user/user_list', $this->data);
	}

	/**
	* show all users
	*
	*/
	public function add()
	{
		$this->is_allowed('user_add');
		$this->data['mpd_regions'] = array();

		$this->template->title('User New');
		$this->render('backend/standart/administrator/user/user_add', $this->data);
	}

	/**
	* Add New users
	*
	* @return JSON
	*/
	public function add_save()
	{
		if (!$this->is_allowed('user_add', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		$this->set_account_validation_rules(null, true);
		$selected_groups = (array) $this->input->post('group');
		$is_mpd = $this->model_user->group_ids_include($selected_groups, 'MPD');

		if ($this->form_validation->run()) {
			$user_avatar_uuid = $this->input->post('user_avatar_uuid');
			$user_avatar_name = $this->input->post('user_avatar_name');

			$save_data = [
				'full_name' 	=> format_person_name($this->input->post('full_name', true)),
				'phone_number' => format_phone_number($this->input->post('phone_number', true)),
				'avatar' 		=> 'default.png',
				'date_created'	=> date('Y-m-d H:i:s'),
				'kd_wilayah' => $this->input->post('kd_wilayah')
			];

			$new_avatar = null;
			if (!empty($user_avatar_name) && !empty($user_avatar_uuid)) {
				$user_avatar_name_copy = $this->move_uploaded_avatar($user_avatar_uuid, $user_avatar_name);

				if (!$user_avatar_name_copy) {
					return $this->response([
						'success' => false,
						'message' => 'Error uploading avatar'
					]);
				}

				$new_avatar = $save_data['avatar'] = $user_avatar_name_copy;
			}

			$save_user = $this->aauth->create_user($this->input->post('email'), $this->input->post('password'), $this->input->post('username'), $save_data);
			if (!$save_user && $new_avatar) {
				$this->storage_manager->delete_if_unreferenced('uploads/user/', $new_avatar);
			}

			if ($save_user) {
				//add user to group
				if (count($selected_groups)) {
					$user_id = $save_user;
					foreach ($selected_groups as $group_id) {
						$this->aauth->add_member($user_id, $group_id);
					}
				}
				if (!$is_mpd) {
					$this->model_user->detach_mpd_registry($save_user);
				}
				$this->model_user->enforce_notary_roster($save_user);
				$this->model_user->enforce_mpd_registry($save_user);
				if ($is_mpd === false) {
					$this->model_data_notaris->sync_account_name((int) $save_user);
				}
				if ($this->input->post('save_type') == 'stay') {
					$this->response['success'] = true;
					$this->response['message'] = cclang('success_save_data_stay', [
						anchor('administrator/user/edit/' . $save_user, 'Edit User'),
						anchor('administrator/user', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_save_data_redirect', [
						anchor('administrator/user/edit/' . $save_user, 'Edit User')
					]), 'success');

	        		$this->response['success'] = true;
					$this->response['redirect'] = site_url('administrator/user');
				}
			} else {
				$this->response['success'] = false;
				$this->response['message'] = $this->aauth->print_errors();
			}

		} else {
			$this->response['success'] = false;
			$this->response['message'] = validation_errors();
		}

		return $this->response($this->response);
	}

	/**
	* Update view users
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('user_update');

		$this->data = [
			'user' 			=> $this->model_user->find($id),
			'group_user' 	=> $this->model_user->get_group_user($id),
			'mpd_regions' => $this->model_user->get_mpd_regions($id),
		];

		$this->template->title('User Update');
		$this->render('backend/standart/administrator/user/user_update', $this->data);
	}

	/**
	* Update users
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('user_update', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}
		$existing_user = $this->model_user->find((int) $id);
		if (!$existing_user) {
			show_404();
		}

		$this->set_account_validation_rules((int) $id, false);
		$selected_groups = (array) $this->input->post('group');
		$is_mpd = $this->model_user->group_ids_include($selected_groups, 'MPD');
		$is_notary = $this->model_user->group_ids_include($selected_groups, 'User');

		if ($this->form_validation->run()) {
			$user_avatar_uuid = $this->input->post('user_avatar_uuid');
			$user_avatar_name = $this->input->post('user_avatar_name');

			$save_data = [
				'full_name' 	=> format_person_name($this->input->post('full_name', true)),
				'phone_number' => format_phone_number($this->input->post('phone_number', true)),
				'kd_wilayah' => $this->input->post('kd_wilayah', true)

			];
			if ($is_notary && $this->db->field_exists('user_id', 'data_notaris')) {
				$registry = $this->db->select('kode_wilayah')->get_where('data_notaris', array('user_id' => (int) $id))->row();
				if ($registry && trim((string) $registry->kode_wilayah) !== '') {
					$save_data['kd_wilayah'] = trim((string) $registry->kode_wilayah);
				}
			}

			$new_avatar = null;
			if (!empty($user_avatar_name)) {
				if (!empty($user_avatar_uuid)) {
					$user_avatar_name_copy = $this->move_uploaded_avatar($user_avatar_uuid, $user_avatar_name);

					if (!$user_avatar_name_copy) {
						return $this->response([
							'success' => false,
							'message' => 'Error uploading avatar'
							]);
					}

					$new_avatar = $save_data['avatar'] = $user_avatar_name_copy;
				}
			}

			if ($pass = $this->input->post('password')) {
				$password = $pass;
			} else {
				$password = false;
			}

			$save_user = $this->aauth->update_user($id, $this->input->post('email'), $password, $this->input->post('username'), $save_data);

			if ($save_user) {
				if ($new_avatar && $new_avatar !== $existing_user->avatar) {
					$this->storage_manager->delete_if_unreferenced('uploads/user/', $existing_user->avatar);
				}
				//update user to group
				$this->db->delete('aauth_user_to_group', ['user_id' => $id]);
				if (count($selected_groups)) {
					foreach ($selected_groups as $group_id) {
						$this->aauth->add_member($id, $group_id);
					}
				}
				if (!$is_mpd) {
					$this->model_user->detach_mpd_registry($id);
				}
				$this->model_user->enforce_notary_roster($id);
				$this->model_user->enforce_mpd_registry($id);
				$this->model_data_notaris->sync_account_name((int) $id);

				if ($this->input->post('save_type') == 'stay') {
					$this->response['success'] = true;
					$this->response['message'] = cclang('success_update_data_stay', [
						anchor('administrator/user', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

	        		$this->response['success'] = true;
					$this->response['redirect'] = site_url('administrator/user');
				}
			} else {
				if ($new_avatar) {
					$this->storage_manager->delete_if_unreferenced('uploads/user/', $new_avatar);
				}
				$this->response['success'] = false;
				$this->response['message'] = cclang('data_not_change').$this->aauth->print_errors();
			}

		} else {
			$this->response['success'] = false;
			$this->response['message'] = validation_errors();
		}

		return $this->response($this->response);
	}

	/**
	* delete users
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('user_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (count($arr_id) >0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}
		
		if ($remove) {
            set_message(cclang('has_been_deleted', 'User'), 'success');
        } else {
            set_message(cclang('error_delete', 'User'), 'error');
        }

		redirect_back();
	}

	/**
	* View view users
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('user_view');

		$this->data['user'] = $this->model_user->find($id);
		if (!$this->data['user']) {
			show_404();
		}
		$this->data['region_name'] = $this->model_user->get_region_name($this->data['user']->kd_wilayah);
		$this->data['groups'] = $this->aauth->get_user_groups((int) $id);
		$this->data['mpd_region_names'] = $this->model_user->get_mpd_region_names((int) $id);
		$this->attach_notary_profile_data($this->data['user']);
		$this->attach_mpd_profile_data($this->data['user']);

		$this->template->title(!empty($this->data['is_mpd_profile']) ? 'Detail Profil MPD' : 'Detail Profil Notaris');
		$this->render('backend/standart/administrator/user/user_view', $this->data);
	}

	/**
	* Profile user
	*
	*/
	public function profile()
	{
		if ($this->uri->segment(1) === 'profile'
			|| ($this->uri->segment(2) === 'user' && $this->uri->segment(3) === 'profile')) {
			redirect('administrator/profile');
		}

		$this->is_allowed('user_profile');

		$id_user = (int) $this->aauth->get_user()->id;
		$this->data['user'] = $this->model_user->find($id_user);
		$this->data['region_name'] = $this->data['user']
			? $this->model_user->get_region_name($this->data['user']->kd_wilayah)
			: null;
		$this->data['groups'] = $this->aauth->get_user_groups($id_user);
		$this->data['mpd_region_names'] = $this->model_user->get_mpd_region_names($id_user);
		$this->attach_notary_profile_data($this->data['user']);
		$this->attach_mpd_profile_data($this->data['user']);

		$this->template->title('Profil Saya');
		$this->render('backend/standart/administrator/user/user_profile', $this->data);
	}

	/**
	* Update view profile
	*
	*/
	public function edit_profile()
	{
		if ($this->uri->segment(1) === 'profile'
			|| ($this->uri->segment(2) === 'user' && $this->uri->segment(3) === 'edit_profile')) {
			redirect('administrator/profile/edit');
		}

		$this->is_allowed('user_update_profile');
		$id_user = $this->aauth->get_user()->id;
		$this->data = [
			'user' 			=> $this->model_user->find($id_user),
			'group_user' 	=> $this->model_user->get_group_user($id_user),
			'mpd_region_names' => $this->model_user->get_mpd_region_names($id_user)
		];
		$this->attach_notary_profile_data($this->data['user']);
		$this->attach_mpd_profile_data($this->data['user']);

		$this->template->title('Update Profile');
		$this->render('backend/standart/administrator/user/user_update_profile', $this->data);
	}

	/**
	* Update profile
	*
	* @var $id String
	*/
	public function edit_profile_save($legacy_id = null)
	{
		if (!$this->is_allowed('user_update_profile', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		
		$current_user = $this->aauth->get_user();
		$id = (int) $current_user->id;

		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]|valid_email|callback_unique_user_email['.$id.']');
		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'trim|required|callback_valid_indonesian_phone');
		$is_notary = $this->is_notary_account($id);
		$is_mpd = $this->model_data_mpd->account_is_mpd($id);
		$notary_profile = $is_notary ? $this->model_data_notaris->find_for_user($current_user) : false;
		$mpd_profile = $is_mpd ? $this->model_data_mpd->find_for_user($id) : false;
		if ($is_notary && $notary_profile) {
			$this->set_notary_profile_validation_rules();
		}
		if ($this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password', 'min_length[8]|max_length[72]');
		}

		if ($this->form_validation->run()) {
			$user_avatar_uuid = $this->input->post('user_avatar_uuid');
			$user_avatar_name = $this->input->post('user_avatar_name');

			$official_region_code = ($is_notary && $notary_profile)
				? trim((string) $notary_profile->kode_wilayah)
				: trim((string) $current_user->kd_wilayah);
			$save_data = [
				'full_name' 	=> format_person_name($this->input->post('full_name', true)),
				'phone_number' => format_phone_number($this->input->post('phone_number', true)),
				// Wilayah Notaris mengikuti registri Data Notaris. Untuk peran
				// lain nilai ini hanya salinan kompatibilitas, bukan sumber scope.
				'kd_wilayah' => $official_region_code
			];

			$new_avatar = null;
			if (!empty($user_avatar_name)) {
				if (!empty($user_avatar_uuid)) {
					$user_avatar_name_copy = $this->move_uploaded_avatar($user_avatar_uuid, $user_avatar_name);

					if (!$user_avatar_name_copy) {
						return $this->response([
							'success' => false,
							'message' => 'Error uploading avatar'
							]);
					}

					$new_avatar = $save_data['avatar'] = $user_avatar_name_copy;
				}
			}

			if ($pass = $this->input->post('password')) {
				$password = $pass;
			} else {
				$password = false;
			}

			$this->db->trans_begin();
			$save_user = $this->aauth->update_user($id, $this->input->post('email'), $password, $current_user->username, $save_data);
			if ($save_user && $is_notary && $notary_profile) {
				$registry_data = array(
					'nama_notaris' => $save_data['full_name'],
					'tempat_lahir' => trim((string) $this->input->post('tempat_lahir', true)),
					'tanggal_lahir' => $this->profile_nullable('tanggal_lahir'),
					'jenis_kelamin' => $this->input->post('jenis_kelamin', true),
					'email' => trim((string) $this->input->post('email', true)),
					'wilayah' => $this->model_user->get_region_name($official_region_code),
					'surat_pindah' => trim((string) $this->input->post('surat_pindah', true)),
					'surat_keputusan' => trim((string) $this->input->post('surat_keputusan', true)),
					'alamat_rumah' => trim((string) $this->input->post('alamat_rumah', true)),
					'alamat_kantor' => trim((string) $this->input->post('alamat_kantor', true)),
					'kode_wilayah' => $official_region_code,
					'lat' => $this->profile_nullable('lat'),
					'no_telepon' => format_phone_number($this->input->post('phone_number', true)),
					'long' => $this->profile_nullable('long'),
					'npwp' => $this->profile_digits('npwp'),
					'nomor_ktp' => $this->profile_digits('nomor_ktp'),
					'nomor_bap' => trim((string) $this->input->post('nomor_bap', true)),
					'tanggal_bap' => $this->profile_nullable('tanggal_bap'),
					'pemegang_protokol' => trim((string) $this->input->post('pemegang_protokol', true)),
					'status_notaris' => $notary_profile->status_notaris,
				);
				$save_user = (bool) $this->db
					->where('id_notaris', (int) $notary_profile->id_notaris)
					->update('data_notaris', $registry_data);
			}
			if ($save_user && $is_mpd && $mpd_profile) {
				$save_user = $this->model_data_mpd->sync_account_profile($id, array(
					'full_name' => $save_data['full_name'],
					'email' => $this->input->post('email', true),
					'phone_number' => $save_data['phone_number'],
				));
			}
			if ($save_user && $this->db->trans_status() !== false) $this->db->trans_commit();
			else $this->db->trans_rollback();

			if ($save_user) {
				if ($new_avatar && $new_avatar !== $current_user->avatar) {
					$this->storage_manager->delete_if_unreferenced('uploads/user/', $current_user->avatar);
				}
				$this->data['success'] = true;
				$this->data['id'] 	   = $id;
				$this->data['message'] = 'Profil akun berhasil diperbarui.';
			} else {
				if ($new_avatar) {
					$this->storage_manager->delete_if_unreferenced('uploads/user/', $new_avatar);
				}
				$this->data['success'] = false;
				$this->data['message'] = cclang('data_not_change').$this->aauth->print_errors();
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		return $this->response($this->data);
	}

	private function set_notary_profile_validation_rules()
	{
		$this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('tanggal_lahir', 'Tanggal Lahir', 'trim|required|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required|in_list[Laki-laki,Perempuan]');
		$this->form_validation->set_rules('surat_pindah', 'Surat Pindah', 'trim|max_length[100]');
		$this->form_validation->set_rules('surat_keputusan', 'Surat Keputusan', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('alamat_rumah', 'Alamat Rumah', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('alamat_kantor', 'Alamat Kantor', 'trim|required|max_length[1000]');
		$this->form_validation->set_rules('lat', 'Latitude', 'trim|required|decimal|greater_than_equal_to[-90]|less_than_equal_to[90]');
		$this->form_validation->set_rules('long', 'Longitude', 'trim|required|decimal|greater_than_equal_to[-180]|less_than_equal_to[180]');
		$this->form_validation->set_rules('npwp', 'NPWP', 'trim|required|callback_valid_npwp');
		$this->form_validation->set_rules('nomor_ktp', 'NIK', 'trim|required|exact_length[16]|numeric');
		$this->form_validation->set_rules('nomor_bap', 'Nomor BAP', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('tanggal_bap', 'Tanggal BAP', 'trim|required|callback_valid_date|callback_valid_not_future_date');
		$this->form_validation->set_rules('pemegang_protokol', 'Pemegang Protokol', 'trim|max_length[150]');
	}

	private function profile_nullable($field)
	{
		$value = trim((string) $this->input->post($field, true));
		return $value === '' ? null : $value;
	}

	private function profile_digits($field)
	{
		$value = preg_replace('/\D+/', '', (string) $this->input->post($field, true));
		return $value === '' ? null : $value;
	}

	private function set_account_validation_rules($user_id = null, $password_required = false)
	{
		$username_unique = $user_id === null
			? 'is_unique[aauth_users.username]'
			: 'callback_unique_username['.(int) $user_id.']';
		$email_unique = $user_id === null
			? 'is_unique[aauth_users.email]'
			: 'callback_unique_user_email['.(int) $user_id.']';

		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[100]|regex_match[/^[A-Za-z0-9._-]+$/]|'.$username_unique);
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]|valid_email|'.$email_unique);
		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required|max_length[200]');
		$this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'trim|required|callback_valid_indonesian_phone');
		$this->form_validation->set_rules('kd_wilayah', 'Wilayah Kerja', 'trim|required|callback_valid_region_code');
		$this->form_validation->set_rules('group[]', 'Kelompok Akses', 'required|callback_valid_group_selection');
		if ($password_required || $this->input->post('password')) {
			$this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[8]|max_length[72]');
		}
	}

	public function unique_username($value, $user_id)
	{
		$exists = $this->db->where('LOWER(username) =', strtolower(trim((string) $value)))
			->where('id !=', (int) $user_id)->count_all_results('aauth_users') > 0;
		if ($exists) {
			$this->form_validation->set_message(__FUNCTION__, 'Username sudah digunakan akun lain.');
			return false;
		}
		return true;
	}

	public function unique_user_email($value, $user_id)
	{
		$exists = $this->db->where('LOWER(email) =', strtolower(trim((string) $value)))
			->where('id !=', (int) $user_id)->count_all_results('aauth_users') > 0;
		if ($exists) {
			$this->form_validation->set_message(__FUNCTION__, 'Email sudah digunakan akun lain.');
			return false;
		}
		return true;
	}

	public function valid_group_selection($values)
	{
		$group_ids = array_values(array_unique(array_filter(array_map('intval', (array) $values))));
		$valid = $group_ids
			&& $this->db->where_in('id', $group_ids)->count_all_results('aauth_groups') === count($group_ids);
		if (!$valid) {
			$this->form_validation->set_message(__FUNCTION__, 'Pilih minimal satu kelompok akses yang valid.');
		}
		return (bool) $valid;
	}

	/**
	* delete users
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$user = $this->model_user->find($id);
		if (!$user) {
			return false;
		}

		// Preserve report ownership. Accounts with report history are
		// deactivated instead of being deleted.
		$this->db->group_start();
		if ($this->db->field_exists('owner_user_id', 'laporan')) {
			$this->db->where('owner_user_id', (int) $id);
			$this->db->or_group_start()
				->where('owner_user_id IS NULL', null, false)
				->where('LOWER(username) =', strtolower((string) $user->username))
				->group_end();
		} else {
			$this->db->where('LOWER(username) =', strtolower((string) $user->username));
		}
		$this->db->group_end();
		if ($this->db->count_all_results('laporan') > 0) {
			return false;
		}

		if ($this->db->table_exists('mpd_wilayah')) {
			$this->db->where('user_id', (int) $id)->delete('mpd_wilayah');
		}
		if ($this->db->table_exists('data_mpd')) {
			$this->model_user->detach_mpd_registry($id);
		}

		$removed = $this->model_user->remove($id);
		if ($removed && !empty($user->avatar)) {
			$this->storage_manager->delete_if_unreferenced('uploads/user/', $user->avatar);
		}
		return $removed;
	}

	/**
	* Upload Image User
	* 
	* @return JSON
	*/
	public function upload_avatar_file()
	{
		$can_upload_avatar = $this->is_allowed('user_add', false)
			|| $this->is_allowed('user_update', false)
			|| $this->is_allowed('user_update_profile', false);

		if (!$can_upload_avatar) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		$uuid = basename((string) $this->input->post('qquuid'));
		if ($uuid === '') {
			return $this->response(array('success' => false, 'error' => 'Identitas unggahan tidak valid.'));
		}

		if (!is_dir(FCPATH . '/uploads/tmp/' . $uuid)) {
			mkdir(FCPATH . '/uploads/tmp/' . $uuid, 0755, true);
		}

		$config = [
			'upload_path' 		=> './uploads/tmp/' . $uuid . '/',
			'allowed_types' 	=> 'png|jpeg|jpg|gif',
			'max_size'  		=> '5120'
		];
		
		$this->load->library('upload', $config);
		$this->load->helper('file');

		if ( ! $this->upload->do_upload('qqfile')){
			$result = [
				'success' 	=> false,
				'error' 	=>  $this->upload->display_errors()
			];

    		return $this->response($result);
		}
		else{
			$upload_data = $this->upload->data();

			$result = [
				'uploadName' 	=> $upload_data['file_name'],
				'success' 		=> true,
			];

    		return $this->response($result);
		}
	}

	/**
	* Delete Image User
	* 
	* @return JSON
	*/
	public function delete_avatar_file($uuid)
	{
		$safe_uuid = basename((string) $uuid);
		if ($safe_uuid === '' || $safe_uuid !== (string) $uuid) {
			return $this->response(array('success' => false, 'message' => 'Identitas berkas tidak valid.'));
		}
		$uuid = $safe_uuid;
		$delete_by = $this->input->get('by');
		$is_own_avatar = $delete_by === 'id' && (int) $uuid === (int) $this->aauth->get_user()->id;
		$is_temporary_avatar = $delete_by !== 'id';
		$can_delete_avatar = $this->is_allowed('user_delete', false)
			|| $this->is_allowed('user_update', false)
			|| (($is_own_avatar || $is_temporary_avatar) && $this->is_allowed('user_update_profile', false));

		if (!$can_delete_avatar) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}

		if (!empty($uuid)) {
			$this->load->helper('file');

			$delete_file = false;

			if ($delete_by == 'id') {
				$user = $this->model_user->find($uuid);
				$path = FCPATH . 'uploads/user/';
				if ($user && $this->model_user->change($uuid, ['avatar' => 'default.png'])) {
					$delete_file = $this->storage_manager->delete_if_unreferenced('uploads/user/', $user->avatar);
				}
			} else {
				$path = FCPATH . '/uploads/tmp/' . $uuid . '/';
				$delete_file = delete_files($path, true);
			}

			if ($delete_by !== 'id' && is_dir($path)) {
				@rmdir($path);
			}

			if (!$delete_file) {
				$result = [
					'error' =>  'Error delete file'
				];

	    		return $this->response($result);
			} else {
				$result = [
					'success' => true,
				];

	    		return $this->response($result);
			}
		}
	}

	/**
	* Get Image User
	* 
	* @return JSON
	*/
	public function get_avatar_file($id)
	{
		$is_own_avatar = (int) $id === (int) $this->aauth->get_user()->id;
		$can_get_avatar = $this->is_allowed('user_update', false)
			|| ($is_own_avatar && $this->is_allowed('user_update_profile', false));

		if (!$can_get_avatar) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}
		$this->load->helper('file');
		
		$user = $this->model_user->find($id);

		if (!$user) {
			$result = [
				'error' =>  'Error getting file'
			];

    		return $this->response($result);
		} else {
			if (!empty($user->avatar)) {
				$result[] = [
					'success' 				=> true,
					'thumbnailUrl' 			=> base_url('uploads/user/'.$user->avatar),
					'id' 					=> 0,
					'name' 					=> $user->avatar,
					'uuid' 					=> $user->id,
					'deleteFileEndpoint' 	=> base_url('administrator/user/delete_avatar_file'),
					'deleteFileParams'		=> ['by' => 'id']
				];

	    		return $this->response($result);
			}
		}
	}

	/**
	* Set status user
	*
	* @return JSON
	*/
	public function set_status()
	{
		if (!$this->is_allowed('user_update_status', false)) {
			return $this->response([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
		}
		$status = $this->input->post('status');
		$id = $this->input->post('id');
		$roster_status = $this->model_user->enforce_notary_roster($id);
		if ($roster_status['is_notary'] && !$roster_status['listed']) {
			return $this->response(array(
				'success' => false,
				'message' => 'Status akun dikunci karena Notaris belum terdaftar pada Data Notaris.',
			));
		}
		$mpd_status = $this->model_user->enforce_mpd_registry($id);
		if (!empty($mpd_status['is_mpd']) && empty($mpd_status['eligible'])) {
			return $this->response(array(
				'success' => false,
				'message' => !empty($mpd_status['listed'])
					? 'Status akun dikunci karena Data MPD belum diverifikasi.'
					: 'Status akun dikunci karena MPD belum terdaftar pada Data MPD.',
			));
		}

		$update_status = $this->model_user->change($id, [
			'banned' => $status == 'inactive' ? 1 : 0
		]);
		
		if ($update_status) {
			$this->response = [
				'success' => true,
				'message' => 'User status updated',
			];
		} else {
			$this->response = [
				'success' => false,
				'message' => cclang('data_not_change')
			];
		}

		return $this->response($this->response);
	}

	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('user_export');
		$this->model_user->export('aauth_users', 'user');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('user_export');

		$this->model_user->pdf('aauth_users', 'User');
	}
}

/* End of file User.php */
/* Location: ./application/controllers/administrator/User.php */
