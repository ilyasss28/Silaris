<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 *| --------------------------------------------------------------------------
 *| Keys Controller
 *| --------------------------------------------------------------------------
 *| Keys site
 *|
 */
class Keys extends Admin
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('model_keys');
    }

    /**
     * show all Keyss
     *
     * @var $offset String
     */
    public function index($offset = 0)
    {
        $this->is_allowed('keys_list');

        $filter = $this->input->get('q');
        $field  = $this->input->get('f');

        $this->data['keyss']       = $this->model_keys->get($filter, $field, $this->limit_page, $offset);
        $this->data['keys_counts'] = $this->model_keys->count_all($filter, $field);

        $config = [
            'base_url'    => 'administrator/keys/index/',
            'total_rows'  => $this->model_keys->count_all($filter, $field),
            'per_page'    => $this->limit_page,
            'uri_segment' => 4,
        ];

        $this->data['pagination'] = $this->pagination($config);

        $this->template->title('API Keys List');
        $this->render('backend/standart/administrator/keys/keys_list', $this->data);
    }

    /**
     * Add new keyss
     *
     */
    public function add()
    {
        $this->is_allowed('keys_add');

        $this->template->title('API Keys New');
        $this->render('backend/standart/administrator/keys/keys_add', $this->data);
    }

    /**
     * Add New Keyss
     *
     * @return JSON
     */
    public function add_save()
    {
        if (!$this->is_allowed('keys_add', false)) {
            return $this->response([
                'success' => false,
                'message' => cclang('sorry_you_do_not_have_permission_to_access'),
            ]);
        }

        $this->form_validation->set_rules('key', 'Key', 'trim|required|min_length[16]|max_length[40]');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|integer|greater_than_equal_to[0]|less_than_equal_to[99]');

        if ($this->form_validation->run()) {

            $key = trim($this->input->post('key'));
            if ($this->db->where('key', $key)->count_all_results('keys')) {
                return $this->response(['success' => false, 'message' => 'API key sudah digunakan. Buat key yang berbeda.']);
            }

            $ip_addresses = $this->normalize_ip_addresses($this->input->post('ip_addresses'));
            if ($ip_addresses === false) {
                return $this->response(['success' => false, 'message' => 'Daftar IP tidak valid. Pisahkan setiap alamat IP dengan koma atau baris baru.']);
            }
            if ($this->input->post('is_private_key') && empty($ip_addresses)) {
                return $this->response(['success' => false, 'message' => 'Isi minimal satu alamat IP ketika pembatasan IP diaktifkan.']);
            }

            $save_data = [
                'user_id'        => (int) get_user_data('id'),
                'key'            => $key,
                'level'          => (int) $this->input->post('level'),
                'ignore_limits'  => $this->input->post('ignore_limits') ? 1 : 0,
                'is_private_key' => $this->input->post('is_private_key') ? 1 : 0,
                'ip_addresses'   => $ip_addresses,
            ];

            $save_keys = $this->model_keys->store($save_data);

            if ($save_keys) {
                if ($this->input->post('save_type') == 'stay') {
                    $this->data['success'] = true;
                    $this->data['id']      = $save_keys;
                    $this->data['message'] = cclang('success_save_data_stay', [
                        anchor('administrator/keys/edit/' . $save_keys, 'Edit Keys'),
                        anchor('administrator/keys', ' Go back to list')
                    ]);
                } else {
                    set_message(
                        cclang('success_save_data_redirect', [
                        anchor('administrator/keys/edit/' . $save_keys, 'Edit Keys')
                    ]), 'success');

                    $this->data['success']  = true;
                    $this->data['redirect'] = base_url('administrator/keys');
                }
            } else {
                if ($this->input->post('save_type') == 'stay') {
                    $this->data['success'] = false;
                    $this->data['message'] = cclang('data_not_change');
                } else {
                    $this->data['message']  = cclang('data_not_change');
                    $this->data['success']  = false;
                    $this->data['redirect'] = base_url('administrator/keys');
                }
            }

        } else {
            $this->data['success'] = false;
            $this->data['message'] = validation_errors();
        }

        return $this->response($this->data);
    }

    /**
     * Update view Keyss
     *
     * @var $id String
     */
    public function edit($id)
    {
        $this->is_allowed('keys_update');

        $this->data['keys'] = $this->model_keys->find($id);

        if (!$this->data['keys']) {
            show_404();
        }

        $this->template->title('API Keys Update');
        $this->render('backend/standart/administrator/keys/keys_update', $this->data);
    }

    /**
     * Update Keyss
     *
     * @var $id String
     */
    public function edit_save($id)
    {
        if (!$this->is_allowed('keys_update', false)) {
            return $this->response([
                'success' => false,
                'message' => cclang('sorry_you_do_not_have_permission_to_access'),
            ]);
        }

        $this->form_validation->set_rules('key', 'Key', 'trim|required|min_length[16]|max_length[40]');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|integer|greater_than_equal_to[0]|less_than_equal_to[99]');

        if ($this->form_validation->run()) {

            $id = (int) $id;
            if (!$this->model_keys->find($id)) {
                return $this->response(['success' => false, 'message' => 'API key tidak ditemukan.']);
            }

            $key = trim($this->input->post('key'));
            if ($this->db->where('key', $key)->where('id !=', $id)->count_all_results('keys')) {
                return $this->response(['success' => false, 'message' => 'API key sudah digunakan. Buat key yang berbeda.']);
            }

            $ip_addresses = $this->normalize_ip_addresses($this->input->post('ip_addresses'));
            if ($ip_addresses === false) {
                return $this->response(['success' => false, 'message' => 'Daftar IP tidak valid. Pisahkan setiap alamat IP dengan koma atau baris baru.']);
            }
            if ($this->input->post('is_private_key') && empty($ip_addresses)) {
                return $this->response(['success' => false, 'message' => 'Isi minimal satu alamat IP ketika pembatasan IP diaktifkan.']);
            }

            $save_data = [
                'key'            => $key,
                'level'          => (int) $this->input->post('level'),
                'ignore_limits'  => $this->input->post('ignore_limits') ? 1 : 0,
                'is_private_key' => $this->input->post('is_private_key') ? 1 : 0,
                'ip_addresses'   => $ip_addresses,
            ];

            $save_keys = $this->model_keys->change($id, $save_data);

            if ($save_keys) {
                if ($this->input->post('save_type') == 'stay') {
                    $this->data['success'] = true;
                    $this->data['id']      = $id;
                    $this->data['message'] = cclang('success_update_data_stay', [
                        anchor('administrator/keys', ' Go back to list')
                    ]);
                } else {
                    set_message(
                        cclang('success_update_data_redirect', [
                    ]), 'success');

                    $this->data['success']  = true;
                    $this->data['redirect'] = base_url('administrator/keys');
                }
            } else {
                if ($this->input->post('save_type') == 'stay') {
                    $this->data['success'] = false;
                    $this->data['message'] = cclang('data_not_change');
                } else {
                    $this->data['message']  = cclang('data_not_change');
                    $this->data['success']  = false;
                    $this->data['redirect'] = base_url('administrator/keys');
                }
            }
        } else {
            $this->data['success'] = false;
            $this->data['message'] = validation_errors();
        }

        return $this->response($this->data);
    }

    /**
     * delete Keyss
     *
     * @var $id String
     */
    public function delete($id = null)
    {
        $this->is_allowed('keys_delete');

        $this->load->helper('file');

        $arr_id = $this->input->get('id');
        $arr_id = is_array($arr_id) ? array_values(array_unique(array_map('intval', $arr_id))) : [];
        $remove = false;

        if (!empty($id)) {
            $remove = $this->_remove($id);
        } elseif (!empty($arr_id)) {
            foreach ($arr_id as $id) {
                $remove = $this->_remove($id);
            }
        }

        if ($remove) {
            set_message(cclang('has_been_deleted', 'Key'), 'success');
        } else {
            set_message(cclang('error_delete', 'Key'), 'error');
        }

        redirect('administrator/keys');
    }

    /**
     * View view Keyss
     *
     * @var $id String
     */
    public function view($id)
    {
        $this->is_allowed('keys_view');

        $this->data['keys'] = $this->model_keys->find($id);

        if (!$this->data['keys']) {
            show_404();
        }

        $this->template->title('API Keys Detail');
        $this->render('backend/standart/administrator/keys/keys_view', $this->data);
    }

    /**
     * delete Keyss
     *
     * @var $id String
     */
    private function _remove($id)
    {
        $id = (int) $id;
        return $id > 0 && $this->model_keys->find($id) ? $this->model_keys->remove($id) : false;
    }

    /**
     * Normalize a comma/newline-separated IP allowlist for REST_Controller.
     * An empty value means the key is not restricted by source IP.
     */
    private function normalize_ip_addresses($value)
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $addresses = preg_split('/[\s,;]+/', $value, -1, PREG_SPLIT_NO_EMPTY);
        $addresses = array_values(array_unique(array_map('trim', $addresses)));
        foreach ($addresses as $address) {
            if (!filter_var($address, FILTER_VALIDATE_IP)) {
                return false;
            }
        }

        return implode(',', $addresses);
    }

    /**
     * Export to excel
     *
     * @return Files Excel .xls
     */
    public function export()
    {
        $this->is_allowed('keys_export');

        $this->model_keys->export('keys', 'keys');
    }

    /**
    * Export to PDF
    *
    * @return Files PDF .pdf
    */
    public function export_pdf()
    {
        $this->is_allowed('keys_export');

        $this->model_keys->pdf('keys', 'Keys');
    }
}

/* End of file keys.php */
/* Location: ./application/controllers/administrator/Keys.php */
