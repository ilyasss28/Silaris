<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Unified, read-only oversight page for every reporting service. */
class Rekap_layanan extends Admin
{
    private $allowed_roles = array('Super Admin', 'Admin', 'Kanwil', 'Kakanwil', 'PIMTI', 'Pimpinan', 'MPD');

    public function __construct()
    {
        parent::__construct();
        $this->load->model('model_rekap_layanan');
        $this->require_oversight_role();
    }

    public function index()
    {
        $this->is_allowed('rekap_layanan_list');
        $filters = $this->model_rekap_layanan->normalize_filters($this->input->get());
        $page = max(1, (int) $this->input->get('page'));
        $per_page = 25;
        $total = $this->model_rekap_layanan->count_all($filters);
        $pages = max(1, (int) ceil($total / $per_page));
        if ($page > $pages) $page = $pages;

        $options = $this->model_rekap_layanan->filter_options();
        $this->data['rekap_filters'] = $filters;
        $this->data['rekap_period_label'] = $this->model_rekap_layanan->period_label($filters);
        $this->data['rekap_services'] = $this->model_rekap_layanan->service_options();
        $this->data['rekap_summary'] = $this->model_rekap_layanan->summary($filters);
        $this->data['rekap_rows'] = $this->model_rekap_layanan->get($filters, $per_page, ($page - 1) * $per_page);
        $this->data['rekap_total'] = $total;
        $this->data['rekap_page'] = $page;
        $this->data['rekap_pages'] = $pages;
        $this->data['rekap_notaries'] = $options['notaries'];
        $this->data['rekap_regions'] = $options['regions'];
        $this->data['rekap_years'] = range((int) date('Y'), 2016);

        $this->template->title('Rekap Layanan');
        $this->render('modul/rekap_layanan/rekap_layanan_list', $this->data);
    }

    /** Export the filtered dataset as a styled, print-ready workbook. */
    public function export()
    {
        $this->is_allowed('rekap_layanan_export');
        $this->output->enable_profiler(false);
        $filters = $this->model_rekap_layanan->normalize_filters($this->input->get());
        $rows = $this->model_rekap_layanan->get_all($filters);

        $export_rows = array();
        foreach ($rows as $index => $row) {
            $export_rows[] = array(
                $index + 1,
                $row['service_label'],
                format_date_id($row['record_date']),
                format_gelar($row['nama_notaris']),
                $row['phone_number'] === '-' ? '-' : format_phone_number($row['phone_number']),
                $row['wilayah'],
                $row['nomor_akta'] === '' ? '-' : $row['nomor_akta'],
                $row['description'] === '' ? '-' : $row['description'],
            );
        }
        $this->load->library('silaris_excel');
        $this->silaris_excel->download(
            'Rekap Layanan',
            'Periode: ' . $this->model_rekap_layanan->period_label($filters),
            array('No.', 'Layanan', 'Tanggal', 'Nama Notaris', 'Nomor Telepon', 'Wilayah', 'Nomor Akta', 'Keterangan'),
            $export_rows,
            'rekap-layanan-' . date('Ymd-His')
        );
    }

    /** Keep old bookmarks useful while preventing mutations through Rekap. */
    public function legacy($service = 'laporan', $action = 'index', $id = null)
    {
        $routes = array(
            'laporan' => 'laporan',
            'laporan_bulanan' => 'laporan',
            'reportorium' => 'reportorium',
            'daftar_proses' => 'daftar_proses',
            'legalisasi' => 'legalisasi',
            'waarmerking' => 'waarmerking',
        );
        if (!isset($routes[$service])) show_404();

        $action = strtolower(trim((string) $action));
        if (in_array($action, array('delete', 'edit_save', 'add_save', 'upload_file_laporan_file', 'delete_file_laporan_file'), true)) {
            show_error('Rekap Layanan bersifat hanya-baca. Perubahan data dilakukan melalui menu Laporan.', 405, 'Operasi Tidak Diizinkan');
        }
        if (in_array($action, array('view', 'edit', 'add', 'export', 'export_pdf'), true)) {
            $target = $routes[$service].'/'.$action.($id !== null ? '/'.rawurlencode((string) $id) : '');
            redirect($target);
        }

        redirect('rekap-layanan?service='.rawurlencode($service));
    }

    private function require_oversight_role()
    {
        if (!$this->aauth->is_loggedin()) return;
        foreach ($this->aauth->get_user_groups() as $group) {
            if (in_array((string) $group->name, $this->allowed_roles, true)) return;
        }
        show_error('Rekap Layanan hanya dapat diakses oleh petugas pengawasan.', 403, 'Akses Ditolak');
    }
}
