<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class MY_Model extends CI_Model {

    private $primary_key = 'id';
    private $table_name = 'table';
    private $field_search;
    private $report_scoped = false;

    public function __construct($config = array())
    {
        parent::__construct();

        foreach ($config as $key => $val)
        {
            if(isset($this->$key))
                $this->$key = $val;
        }

        $this->load->database();
        if ($this->report_scoped) {
            $this->load->library('report_access');
        }
    }

    public function remove($id = NULL)
    {
        $this->apply_report_scope();
        $this->db->where($this->primary_key, $id);
        return $this->db->delete($this->table_name);
    }

    public function change($id = NULL, $data = array())
    {
        $this->apply_report_scope();
        $this->db->where($this->primary_key, $id);
        $this->db->update($this->table_name, $data);

        return $this->db->affected_rows();
    }

    public function find($id = NULL, $select_field = [])
    {
        if (is_array($select_field) AND count($select_field)) {
            $this->db->select($select_field);
        }

        $this->apply_report_scope();
        $this->db->where("".$this->table_name.'.'.$this->primary_key,$id);
        $query = $this->db->get($this->table_name);

        if($query->num_rows()>0)
        {
            $row = $query->row();
            if (in_array($this->table_name, $this->report_owner_tables(), true)) {
                $rows = $this->attach_owner_display_names(array($row));
                return $rows[0];
            }
            return $row;
        }
        else
        {
            return FALSE;
        }
    }

    public function find_all()
    {
        $this->apply_report_scope();
        $this->db->order_by($this->primary_key, 'DESC');
        $query = $this->db->get($this->table_name);

        $rows = $query->result();
        return in_array($this->table_name, $this->report_owner_tables(), true)
            ? $this->attach_owner_display_names($rows)
            : $rows;
    }

    public function store($data = array())
    {
        $this->db->insert($this->table_name, $data);
        return $this->db->insert_id();
    }

    public function get_all_data($table = '')
    {
        $query = $this->db->get($table);

        return $query->result();
    }


    public function get_single($where)
    {
        $this->apply_report_scope();
        $query = $this->db->get_where($this->table_name, $where);

        $row = $query->row();
        if ($row && in_array($this->table_name, $this->report_owner_tables(), true)) {
            $rows = $this->attach_owner_display_names(array($row));
            return $rows[0];
        }
        return $row;
    }

    /**
     * Resolve a report's Notary name from its owning account. This keeps old
     * rows readable when the denormalized name is empty or the account name
     * has subsequently been corrected.
     */
    protected function attach_owner_display_names(array $records)
    {
        if (!$records || !$this->db->table_exists('aauth_users')) return $records;

        $accounts = $this->db->select('id, username, full_name')->get('aauth_users')->result();
        $by_id = array();
        $by_username = array();
        foreach ($accounts as $account) {
            $by_id[(int) $account->id] = $account;
            $username = strtolower(trim((string) $account->username));
            if ($username !== '') $by_username[$username] = $account;
        }

        foreach ($records as $record) {
            $account = null;
            $owner_id = isset($record->owner_user_id) ? (int) $record->owner_user_id : 0;
            $username = isset($record->username) ? strtolower(trim((string) $record->username)) : '';
            if ($owner_id > 0 && isset($by_id[$owner_id])) $account = $by_id[$owner_id];
            elseif ($username !== '' && isset($by_username[$username])) $account = $by_username[$username];

            $stored_name = isset($record->nama_notaris) ? trim((string) $record->nama_notaris) : '';
            $account_name = $account ? trim((string) $account->full_name) : '';
            $fallback = $stored_name !== '' ? $stored_name : (isset($record->username) ? trim((string) $record->username) : '');
            $record->nama_notaris = function_exists('format_person_name')
                ? format_person_name($account_name !== '' ? $account_name : $fallback)
                : ($account_name !== '' ? $account_name : $fallback);
        }
        return $records;
    }

    private function report_owner_tables()
    {
        return array('laporan', 'laporan_bulanan', 'daftar_proses', 'reportorium', 'legalisasi', 'waarmerking', 'fidusia');
    }

    public function scurity($input)
    {
        // Ensure $input is a string; null is converted to empty string to avoid deprecated warning
        $input = $input ?? '';
        return mysqli_real_escape_string($this->db->conn_id, $input);
    }

    /**
     * Count filtered rows without materializing the complete result set.
     */
    protected function count_search_results($table, array $fields, $q = null, $field = null, array $where = [])
    {
        $this->apply_search_conditions($table, $fields, $q, $field, $where);

        return $this->db->count_all_results($table);
    }

    /**
     * Apply the same escaped search and ownership scope to list and count
     * queries so server-side tables never report rows they cannot render.
     */
    protected function apply_search_conditions($table, array $fields, $q = null, $field = null, array $where = [])
    {
        foreach ($where as $column => $value) {
            $this->db->where($column, $value);
        }

        $q = trim((string) $q);
        if ($q !== '') {
            $field = in_array($field, $fields, true) ? $field : null;
            $this->db->group_start();

            if ($field !== null) {
                $this->db->like($table . '.' . $field, $q);
            } else {
                foreach ($fields as $index => $search_field) {
                    $method = $index === 0 ? 'like' : 'or_like';
                    $this->db->{$method}($table . '.' . $search_field, $q);
                }
            }

            $this->db->group_end();
        }
    }

    protected function apply_report_scope($table = null)
    {
        if ($this->report_scoped) {
            $this->report_access->apply_scope($this->db, $table ?: $this->table_name);
        }
    }

    public function export($table, $subject = 'file', array $where = array())
    {
        foreach ($where as $column => $value) {
            $this->db->where($column, $value);
        }
        $this->apply_report_scope($table);
        $result = $this->db->get($table);
        $rows = $result->result();
        if (in_array($table, $this->report_owner_tables(), true)) {
            $rows = $this->attach_owner_display_names($rows);
        }

        $fields = $result->list_fields();
        if (in_array($table, $this->report_owner_tables(), true)) {
            $fields = array_values(array_diff($fields, array('owner_user_id')));
        }
        $fields = array_values(array_filter($fields, function ($field) {
            return !preg_match('/(^pass$|password|remember_token|verification_code|forgot_exp|oauth_uid|secret)/i', $field);
        }));
        $date_fields = $this->date_result_fields($result);
        $headers = array('No.');
        foreach ($fields as $field) $headers[] = ucwords(str_replace('_', ' ', $field));
        $export_rows = array();
        foreach ($rows as $index => $data) {
            $export_row = array($index + 1);
            foreach ($fields as $field) {
                $value = isset($data->{$field}) ? $data->{$field} : '';
                if (isset($date_fields[$field])) $value = format_date_id($value);
                elseif (preg_match('/(telepon|phone)/i', $field) && trim((string) $value) !== '') $value = format_phone_number($value);
                elseif ($field === 'wilayah') $value = format_title_case($value);
                $export_row[] = $value === null ? '' : $value;
            }
            $export_rows[] = $export_row;
        }

        $this->load->library('silaris_excel');
        $this->silaris_excel->download(
            ucwords(str_replace('_', ' ', $subject)),
            'Data SILARIS • Diekspor pada ' . format_date_id(date('Y-m-d')) . ' ' . date('H:i'),
            $headers,
            $export_rows,
            strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $subject)) . '-' . date('Ymd-His')
        );
    }

    public function pdf($table, $title, array $where = array())
    {
        foreach ($where as $column => $value) {
            $this->db->where($column, $value);
        }
        $this->apply_report_scope($table);
        $result = $this->db->get($table);
        $fields = $result->list_fields();
        if (in_array($table, $this->report_owner_tables(), true)) {
            $fields = array_values(array_diff($fields, array('owner_user_id')));
        }
        $fields = array_values(array_filter($fields, function ($field) {
            return !preg_match('/(^pass$|password|remember_token|verification_code|forgot_exp|oauth_uid|secret)/i', $field);
        }));
        $date_fields = $this->date_result_fields($result);
        $rows = $result->result();
        if (in_array($table, $this->report_owner_tables(), true)) {
            $rows = $this->attach_owner_display_names($rows);
        }

        // Seluruh ekspor memakai A4 lanskap agar kolom tetap terbaca dan tidak
        // melewati area cetak dengan margin 20 mm.
        $orientation = 'L';
        $config = array(
            'orientation' => $orientation,
            'format' => 'A4',
            'marges' => array(20, 20, 20, 20)
        );

        $this->load->library('HtmlPdf');
        $this->pdf = new HtmlPdf($config);

        $column_widths = $this->pdf_column_widths($rows, $fields);

        $content = $this->pdf->loadHtmlPdf('core_template/pdf/pdf', [
            'results' => $rows,
            'fields' => $fields,
            'date_fields' => $date_fields,
            'title' => $title,
            'orientation' => $orientation,
            'column_widths' => $column_widths,
            'generated_at' => format_date_id(date('Y-m-d')) . ' ' . date('H:i')
        ], TRUE);

        $this->pdf->pdf->SetCreator('SILARIS');
        $this->pdf->pdf->SetAuthor('Kantor Wilayah Kementerian Hukum Sulawesi Tenggara');
        $this->pdf->pdf->SetTitle((string) $title);
        // Raster yang kelak disisipkan dipetakan pada kepadatan 300 DPI.
        // Teks dan garis tabel sendiri tetap berupa vektor (resolution independent).
        $this->pdf->pdf->setImageScale(300 / 72);
        $this->pdf->pdf->setJPEGQuality(100);
        $this->pdf->pdf->SetDisplayMode('fullpage');
        $this->pdf->writeHTML($content);
        $this->pdf->Output($table.'.pdf', 'H');
    }

    /**
     * Calculate printable percentage widths from headers and representative
     * values. The small fixed number column is included at index zero.
     */
    private function pdf_column_widths(array $rows, array $fields)
    {
        $weights = array(3.5);
        foreach ($fields as $field) {
            $longest = strlen(ucwords(str_replace(array('_', '-'), ' ', $field)));
            foreach (array_slice($rows, 0, 250) as $row) {
                $value = isset($row->{$field}) ? trim(strip_tags((string) $row->{$field})) : '';
                $longest = max($longest, min(strlen($value), 80));
            }
            $weights[] = min(max(sqrt(max($longest, 1)), 3.5), 9.0);
        }

        $total = array_sum($weights) ?: 1;
        $widths = array();
        foreach ($weights as $weight) {
            $widths[] = round(($weight / $total) * 100, 2);
        }
        // Prevent floating-point rounding from making the table exceed 100%.
        $widths[count($widths) - 1] += 100 - array_sum($widths);
        return $widths;
    }

    /** Return database DATE/DATETIME/TIMESTAMP columns as a lookup map. */
    private function date_result_fields($result)
    {
        $date_fields = array();
        foreach ($result->field_data() as $field) {
            if (in_array(strtolower((string) $field->type), array('date', 'datetime', 'timestamp'), true)) {
                $date_fields[$field->name] = true;
            }
        }
        return $date_fields;
    }
}

/* End of file My_Model.php */
/* Location: ./application/core/My_Model.php */
