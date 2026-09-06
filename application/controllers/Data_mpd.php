<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_mpd extends Admin
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_data_mpd');
	}

	public function index($offset = 0)
	{
		$this->is_allowed('data_mpd_list');
		$q = $this->input->get('q', true);
		$this->data['data_mpd'] = $this->model_data_mpd->get($q, $this->limit_page, $offset);
		$this->data['data_mpd_count'] = $this->model_data_mpd->count_all($q);
		$this->data['pagination'] = $this->pagination(array(
			'base_url' => 'data_mpd/index/',
			'total_rows' => $this->data['data_mpd_count'],
			'per_page' => $this->limit_page,
			'uri_segment' => 3,
		));
		$this->template->title('Data MPD');
		$this->render('modul/data_mpd/data_mpd_list', $this->data);
	}

	public function add()
	{
		$this->is_allowed('data_mpd_add');
		$this->data['profile'] = null;
		$this->data['accounts'] = $this->model_data_mpd->get_available_accounts();
		$this->data['regions'] = $this->model_data_mpd->get_regions();
		$this->data['selected_regions'] = array();
		$this->template->title('Tambah Data MPD');
		$this->render('modul/data_mpd/data_mpd_form', $this->data);
	}

	public function add_save()
	{
		$this->is_allowed('data_mpd_add');
		$this->validate_form();
		if (!$this->form_validation->run()) {
			return $this->render_form_with_errors();
		}

		$id = $this->model_data_mpd->save_registry($this->registry_payload(), $this->input_regions());
		if (!$id) {
			set_message('Data MPD tidak dapat disimpan. Pastikan akun belum digunakan dan seluruh wilayah valid.', 'error');
			return redirect('data_mpd/add');
		}
		set_message('Data MPD berhasil disimpan. Status akun dapat diaktifkan setelah datanya terverifikasi.', 'success');
		return redirect('data_mpd/view/' . $id);
	}

	public function edit($id)
	{
		$this->is_allowed('data_mpd_update');
		$profile = $this->model_data_mpd->find_accessible($id);
		if (!$profile) show_404();
		$this->data['profile'] = $profile;
		$this->data['accounts'] = $this->model_data_mpd->get_available_accounts($profile->user_id);
		$this->data['regions'] = $this->model_data_mpd->get_regions();
		$this->data['selected_regions'] = $this->model_data_mpd->get_region_codes($profile->user_id);
		$this->template->title('Edit Data MPD');
		$this->render('modul/data_mpd/data_mpd_form', $this->data);
	}

	public function edit_save($id)
	{
		$this->is_allowed('data_mpd_update');
		$profile = $this->model_data_mpd->find_accessible($id);
		if (!$profile) show_404();
		$this->validate_form();
		if (!$this->form_validation->run()) {
			return $this->render_form_with_errors($profile);
		}

		$saved = $this->model_data_mpd->save_registry($this->registry_payload(), $this->input_regions(), $id);
		if (!$saved) {
			set_message('Data MPD tidak dapat diperbarui. Pastikan akun dan wilayah yang dipilih valid.', 'error');
			return redirect('data_mpd/edit/' . (int) $id);
		}
		set_message('Data MPD dan wilayah pengawasannya berhasil diperbarui.', 'success');
		return redirect('data_mpd/view/' . (int) $id);
	}

	public function view($id)
	{
		$this->is_allowed('data_mpd_view');
		$this->data['profile'] = $this->model_data_mpd->find_accessible($id);
		if (!$this->data['profile']) show_404();
		$this->template->title('Detail Data MPD');
		$this->render('modul/data_mpd/data_mpd_view', $this->data);
	}

	public function delete($id)
	{
		$this->is_allowed('data_mpd_delete');
		if (strtoupper($this->input->method()) !== 'POST') show_404();
		$profile = $this->model_data_mpd->find_accessible($id);
		if (!$profile) show_404();
		if ($this->model_data_mpd->remove_registry($id)) {
			set_message('Data MPD dihapus dan akun terkait telah dinonaktifkan.', 'success');
		} else {
			set_message('Data MPD tidak dapat dihapus.', 'error');
		}
		return redirect('data_mpd');
	}

	private function validate_form()
	{
		$this->form_validation->set_rules('user_id', 'Akun MPD', 'trim|required|integer');
		$this->form_validation->set_rules('nama_mpd', 'Nama MPD', 'trim|required|max_length[150]');
		$this->form_validation->set_rules('jabatan', 'Jabatan', 'trim|required|max_length[100]');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[150]');
		$this->form_validation->set_rules('no_telepon', 'Nomor Telepon', 'trim|required|callback_valid_indonesian_phone');
		$this->form_validation->set_rules('nomor_sk', 'Nomor SK', 'trim|max_length[120]');
		$this->form_validation->set_rules('wilayah[]', 'Wilayah Pengawasan', 'required');
		$this->form_validation->set_rules('tanggal_mulai', 'Mulai Masa Jabatan', 'trim|callback_valid_date');
		$this->form_validation->set_rules('tanggal_selesai', 'Selesai Masa Jabatan', 'trim|callback_valid_date|callback_valid_mpd_date_range');
		$this->form_validation->set_rules('alamat', 'Alamat', 'trim|max_length[255]');
		$this->form_validation->set_rules('is_verified', 'Status Verifikasi', 'trim|required|in_list[0,1]|callback_valid_mpd_verification');
	}

	public function valid_mpd_date_range($end_date)
	{
		$start_date = trim((string) $this->input->post('tanggal_mulai', true));
		if ($start_date === '' || trim((string) $end_date) === '' || $end_date >= $start_date) {
			return true;
		}
		$this->form_validation->set_message(__FUNCTION__, 'Tanggal selesai masa jabatan harus sama atau setelah tanggal mulai.');
		return false;
	}

	public function valid_mpd_verification($verified)
	{
		if ((string) $verified !== '1') {
			return true;
		}
		$required = array('nomor_sk', 'tanggal_mulai', 'tanggal_selesai', 'no_telepon', 'email');
		foreach ($required as $field) {
			if (trim((string) $this->input->post($field, true)) === '') {
				$this->form_validation->set_message(__FUNCTION__, 'Data MPD hanya dapat diverifikasi setelah nomor SK, periode, email, dan nomor telepon diisi.');
				return false;
			}
		}
		return true;
	}

	private function registry_payload()
	{
		return array(
			'user_id' => (int) $this->input->post('user_id'),
			'nama_mpd' => format_person_name($this->input->post('nama_mpd', true)),
			'jabatan' => trim((string) $this->input->post('jabatan', true)),
			'email' => trim((string) $this->input->post('email', true)),
			'no_telepon' => format_phone_number($this->input->post('no_telepon', true)),
			'nomor_sk' => trim((string) $this->input->post('nomor_sk', true)),
			'tanggal_mulai' => $this->nullable_date($this->input->post('tanggal_mulai', true)),
			'tanggal_selesai' => $this->nullable_date($this->input->post('tanggal_selesai', true)),
			'alamat' => trim((string) $this->input->post('alamat', true)),
			'is_verified' => $this->input->post('is_verified') ? 1 : 0,
		);
	}

	private function input_regions()
	{
		return array_values(array_unique(array_filter(array_map('trim', (array) $this->input->post('wilayah')))));
	}

	private function nullable_date($date)
	{
		$date = trim((string) $date);
		return $date === '' ? null : $date;
	}

	private function render_form_with_errors($profile = null)
	{
		$this->data['profile'] = $profile;
		$this->data['accounts'] = $this->model_data_mpd->get_available_accounts($profile ? $profile->user_id : null);
		$this->data['regions'] = $this->model_data_mpd->get_regions();
		$this->data['selected_regions'] = $this->input_regions();
		$this->template->title($profile ? 'Edit Data MPD' : 'Tambah Data MPD');
		return $this->render('modul/data_mpd/data_mpd_form', $this->data);
	}
}
