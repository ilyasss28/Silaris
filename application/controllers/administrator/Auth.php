<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
*| --------------------------------------------------------------------------
*| Auth Controller
*| --------------------------------------------------------------------------
*| For authentication
*|
*/
class Auth extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_user');
	}

	/**
	* Login user
	*
	*/
	public function login()
	{
		if ($this->aauth->is_loggedin()) {
			redirect('administrator/dashboard');
		}
		$data = [];
		$this->config->load('site');
		$data['login_groups'] = get_application_groups();

		$this->form_validation->set_rules('group', 'Group/Role', 'trim|required|in_list[Admin,User,Kanwil,MPD]');
		$this->form_validation->set_rules('username', 'Username', 'trim|required');
		// Passwords are never trimmed: whitespace may be part of an existing password.
		$this->form_validation->set_rules('password', 'Password', 'required');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_valid_captcha');

		if ($this->form_validation->run()) {
			$username = trim((string) $this->input->post('username', true));
			$pending_approval = $this->db->field_exists('is_verified', 'aauth_users')
				&& $this->db->where('LOWER(username) =', strtolower($username))
					->where('is_verified', 0)->count_all_results('aauth_users') > 0;
			$roster_status = $this->model_user->enforce_notary_roster_by_identifier($username);
			if ($pending_approval) {
				$data['error'] = 'Akun Anda telah dibuat dan sedang menunggu verifikasi serta aktivasi admin.';
			} elseif ($roster_status['is_notary'] && !$roster_status['listed']) {
				$data['error'] = 'Akun Notaris tidak aktif karena tidak terdaftar pada Data Notaris.';
			} else {
				$mpd_status = $this->model_user->enforce_mpd_registry_by_identifier($this->input->post('username'));
				if (!empty($mpd_status['is_mpd']) && empty($mpd_status['eligible'])) {
					$data['error'] = !empty($mpd_status['listed'])
						? 'Akun MPD tidak aktif karena Data MPD belum diverifikasi.'
						: 'Akun MPD tidak aktif karena belum terdaftar pada Data MPD.';
				} elseif ($this->aauth->login($username, $this->input->post('password'), 0)) {
					$selected_group = $this->input->post('group', true);
					$user_id = (int) $this->session->userdata('id');

					if ($this->aauth->is_member($selected_group, $user_id)) {
						redirect('administrator/dashboard');
					}

					$this->aauth->logout();
					$data['error'] = 'Group/Role yang dipilih tidak sesuai dengan akun Anda.';
				} else {
					$data['error'] = 'Username, password, atau Group/Role tidak sesuai.';
				}
			}
		} else {
			$data['error'] = validation_errors();
		}
		$this->template->build('backend/standart/administrator/login', $data);
	}

	/**
	* Register user member
	*
	*/
	public function register()
	{
		$data = [];

		$this->form_validation->set_rules('username', 'Username', 'trim|required|min_length[3]|max_length[50]|regex_match[/^[A-Za-z0-9._-]+$/]|is_unique[aauth_users.username]');
		$this->form_validation->set_rules('password', 'Password', 'required|min_length[8]|max_length[72]|callback_strong_password');
		$this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');
		$this->form_validation->set_rules('full_name', 'Nama Lengkap', 'trim|required|min_length[3]|max_length[200]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|max_length[100]|valid_email|is_unique[aauth_users.email]');
		$this->form_validation->set_rules('phone_number', 'Nomor Telepon', 'trim|required|callback_valid_indonesian_phone');
		$this->form_validation->set_rules('agree', 'Persetujuan', 'required|in_list[1]');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_valid_captcha');

		$this->form_validation->set_message('is_unique', '%s sudah digunakan oleh akun lain.');
		$this->form_validation->set_message('regex_match', '%s hanya boleh berisi huruf, angka, titik, garis bawah, dan tanda hubung.');
		$this->form_validation->set_message('matches', '%s harus sama dengan password.');
		$this->form_validation->set_message('in_list', 'Anda harus menyetujui ketentuan penggunaan.');

		if ($this->form_validation->run()) {
			$save_data = [
				'full_name' => format_person_name($this->input->post('full_name', true)),
				'phone_number' => format_phone_number($this->input->post('phone_number', true)),
				'avatar' => 'default.png',
				'banned' => 1,
			];
			if ($this->db->field_exists('is_verified', 'aauth_users')) {
				$save_data['is_verified'] = 0;
				$save_data['verification_requested_at'] = date('Y-m-d H:i:s');
				$save_data['verified_at'] = null;
				$save_data['verified_by'] = null;
			}

			$save_user = $this->aauth->create_user(
				strtolower($this->input->post('email', true)),
				$this->input->post('password'),
				$this->input->post('username', true),
				$save_data
			);

			if ($save_user) {
				// Public registration is always a Notary/User account. Remove the
				// Aauth default group to prevent unintended duplicate roles.
				$this->aauth->remove_member_from_all($save_user);
				$group = $this->db->where('LOWER(name) =', 'user')->get('aauth_groups')->row();
				if (!$group || !$this->aauth->add_member($save_user, (int) $group->id)) {
					$this->aauth->delete_user($save_user);
					$data['error'] = 'Kelompok akses User belum tersedia. Hubungi administrator.';
					$this->template->build('backend/standart/administrator/register_member', $data);
					return;
				}
				$this->load->library('silaris_mailer');
				$email_sent = $this->silaris_mailer->send_registration_pending(
					$this->input->post('email', true),
					$save_data['full_name']
				);
				$message = 'Akun berhasil dibuat dan menunggu verifikasi admin sebelum dapat digunakan.';
				if ($email_sent) {
					$message .= ' Konfirmasi pendaftaran telah dikirim ke email Anda.';
				} else {
					$message .= ' Email konfirmasi belum dapat dikirim; silakan hubungi admin bila diperlukan.';
				}
				set_message($message, $email_sent ? 'success' : 'warning');
				redirect('administrator/login');
			} else {
				$data['error'] = $this->aauth->print_errors();
			}
		} else {
			$data['error'] = validation_errors();
		}

		$this->template->build('backend/standart/administrator/register_member', $data);
	}

	/**
	* User forgot password
	*
	* @var String $id 
	*/
	public function forgot_password()
	{
		$data = [];

		// A password reminder must accept an email that already belongs to a user.
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
		$this->form_validation->set_rules('captcha', 'Captcha', 'trim|required|callback_valid_captcha');

		if ($this->form_validation->run()) {
			$email = strtolower(trim((string) $this->input->post('email', true)));
			$user_exists = $this->db->where('LOWER(email) =', $email)->count_all_results('aauth_users') > 0;
			$reset = $user_exists ? $this->aauth->remind_password($email) : true;
			if ($reset) {
				// The same response is used for unknown addresses to prevent account enumeration.
				set_message('Jika email terdaftar, tautan atur ulang password telah dikirim. Periksa kotak masuk dan folder spam.', 'success');
				redirect('administrator/login');
			}

			$data['error'] = 'Email ditemukan, tetapi layanan email gagal mengirim tautan. Silakan coba kembali atau hubungi admin.';
		} else {
			$data['error'] = validation_errors();
		}

		$this->template->build('backend/standart/administrator/forgot_password', $data);
	}

	/** Display and process the single-use password reset form. */
	public function reset_password($token = '')
	{
		$data = array('token' => trim((string) $token));
		$data['token_valid'] = $this->aauth->valid_password_reset_token($data['token']);

		if ($this->input->method(true) === 'POST' && $data['token_valid']) {
			$this->form_validation->set_rules('password', 'Password Baru', 'required|min_length[8]|max_length[72]|callback_strong_password');
			$this->form_validation->set_rules('password_confirmation', 'Konfirmasi Password', 'required|matches[password]');
			$this->form_validation->set_message('matches', '%s harus sama dengan password baru.');

			if ($this->form_validation->run()) {
				if ($this->aauth->reset_password($data['token'], $this->input->post('password'))) {
					set_message('Password berhasil diperbarui. Silakan masuk menggunakan password baru.', 'success');
					redirect('administrator/login');
				}
				$data['error'] = 'Password tidak dapat diperbarui. Tautan mungkin sudah digunakan atau kedaluwarsa.';
				$data['token_valid'] = false;
			} else {
				$data['error'] = validation_errors();
			}
		}

		$this->template->build('backend/standart/administrator/reset_password', $data);
	}

	/** Require a practical password without blocking passphrases or symbols. */
	public function strong_password($password)
	{
		$valid = preg_match('/[a-z]/', (string) $password)
			&& preg_match('/[A-Z]/', (string) $password)
			&& preg_match('/\d/', (string) $password);
		if (!$valid) {
			$this->form_validation->set_message(__FUNCTION__, '%s harus memuat huruf besar, huruf kecil, dan angka.');
		}
		return (bool) $valid;
	}

	/**
	* User session logout
	*
	*/
	public function logout()
	{
		$this->aauth->logout();
		redirect('login');
	}
}

/* End of file Auth.php */
/* Location: ./application/controllers/administrator/Auth.php */
