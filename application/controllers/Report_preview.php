<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Authenticated document preview and download gateway.
 *
 * Uploaded report files remain referenced by their database records. This
 * controller validates the record, permission and resolved filesystem path
 * before a file is rendered or downloaded.
 */
class Report_preview extends Admin
{
    private $allowed_extensions = [
        'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx',
        'jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'csv',
    ];

    private $document_types = [
        'laporan' => [
            'model' => 'model_laporan',
            'permission' => 'laporan_view',
            'primary_key' => 'id',
            'file_field' => 'Laporan',
            'date_field' => 'Tanggal_Laporan',
            'owner_field' => 'nama_notaris',
            'upload_directory' => 'uploads/laporan',
            'title' => 'Laporan',
            'back_url' => 'laporan/view/',
        ],
        'rekap-laporan' => [
            'model' => 'model_rekap_Laporan',
            'permission' => 'rekap_Laporan_view',
            'primary_key' => 'id',
            'file_field' => 'Laporan',
            'date_field' => 'Tanggal_Laporan',
            'owner_field' => 'nama_notaris',
            'upload_directory' => 'uploads/rekap_Laporan',
            'title' => 'Rekap Laporan',
            'back_url' => 'rekap-laporan/view/',
        ],
        'laporan-bulanan' => [
            'model' => 'model_laporan_bulanan',
            'permission' => 'laporan_bulanan_view',
            'primary_key' => 'id_laporan_bulanan',
            'file_field' => 'file_laporan',
            'date_field' => 'tanggal_laporan',
            'owner_field' => 'username',
            'upload_directory' => 'uploads/laporan_bulanan',
            'title' => 'Laporan Bulanan',
            'back_url' => 'laporan_bulanan/view/',
        ],
        'rekap-laporan-bulanan' => [
            'model' => 'model_rekap_laporan_bulanan',
            'permission' => 'rekap_laporan_bulanan_view',
            'primary_key' => 'id_laporan_bulanan',
            'file_field' => 'file_laporan',
            'date_field' => 'tanggal_laporan',
            'owner_field' => 'username',
            'upload_directory' => 'uploads/rekap_laporan_bulanan',
            'title' => 'Rekap Laporan Bulanan',
            'back_url' => 'rekap_laporan_bulanan/view/',
        ],
    ];

    public function preview($type, $id)
    {
        $document = $this->resolve_document($type, $id);
        $extension = strtolower(pathinfo($document['file_name'], PATHINFO_EXTENSION));
        $native_extensions = ['pdf', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'txt', 'csv'];
        $office_extensions = ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'];

        if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'], true)) {
            $preview_kind = 'image';
        } elseif (in_array($extension, $native_extensions, true)) {
            $preview_kind = 'native';
        } elseif (in_array($extension, $office_extensions, true)) {
            $preview_kind = 'office';
        } else {
            $preview_kind = 'unsupported';
        }

        $public_url = base_url(
            trim($document['config']['upload_directory'], '/') . '/' . rawurlencode($document['file_name'])
        );

        $file_url = site_url('report-preview/file/' . rawurlencode($type) . '/' . (int) $id);
        $office_viewer_url = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($public_url);

        $this->data['document'] = [
            'title' => $document['config']['title'],
            'file_name' => $document['file_name'],
            'extension' => strtoupper($extension ?: 'FILE'),
            'owner' => $this->record_value($document['record'], $document['config']['owner_field']),
            'date' => $this->record_value($document['record'], $document['config']['date_field']),
            'preview_kind' => $preview_kind,
            'file_url' => $file_url,
            'download_url' => site_url('report-preview/download/' . rawurlencode($type) . '/' . (int) $id),
            'office_viewer_url' => $office_viewer_url,
            'open_url' => $preview_kind === 'office' ? $office_viewer_url : $file_url,
            'back_url' => site_url($document['config']['back_url'] . (int) $id),
        ];

        $this->template->title('Pratinjau ' . $document['config']['title']);
        $this->render('backend/standart/document_preview', $this->data);
    }

    public function file($type, $id)
    {
        $document = $this->resolve_document($type, $id);
        $this->stream_document($document, false);
    }

    public function download($type, $id)
    {
        $document = $this->resolve_document($type, $id);
        $this->stream_document($document, true);
    }

    private function resolve_document($type, $id)
    {
        $type = strtolower(trim((string) $type));
        $id = (int) $id;

        if ($id < 1 || !isset($this->document_types[$type])) {
            show_404();
        }

        $config = $this->document_types[$type];
        $this->is_allowed($config['permission']);
        $this->load->model($config['model'], 'document_model');
        $record = $this->document_model->find($id);

        if (!$record) {
            show_404();
        }

        $file_name = trim((string) $this->record_value($record, $config['file_field']));
        if ($file_name === '' || basename($file_name) !== $file_name) {
            show_404();
        }

        $extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        if (!in_array($extension, $this->allowed_extensions, true)) {
            log_message('error', 'Blocked unsupported report document extension: ' . $extension);
            show_404();
        }

        $base_directory = realpath(FCPATH . trim($config['upload_directory'], '/\\'));
        $file_path = $base_directory === false
            ? false
            : realpath($base_directory . DIRECTORY_SEPARATOR . $file_name);

        if ($base_directory === false || $file_path === false || !is_file($file_path)) {
            show_404();
        }

        $base_prefix = rtrim($base_directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $path_prefix = substr($file_path, 0, strlen($base_prefix));
        $inside_directory = DIRECTORY_SEPARATOR === '\\'
            ? strcasecmp($path_prefix, $base_prefix) === 0
            : $path_prefix === $base_prefix;

        if (!$inside_directory) {
            show_404();
        }

        return [
            'config' => $config,
            'record' => $record,
            'file_name' => $file_name,
            'file_path' => $file_path,
        ];
    }

    private function stream_document(array $document, $download)
    {
        $mime_types = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp',
            'txt' => 'text/plain; charset=UTF-8',
            'csv' => 'text/csv; charset=UTF-8',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt' => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        ];
        $extension = strtolower(pathinfo($document['file_name'], PATHINFO_EXTENSION));
        $mime_type = isset($mime_types[$extension]) ? $mime_types[$extension] : 'application/octet-stream';
        $ascii_name = preg_replace('/[^A-Za-z0-9._-]/', '_', $document['file_name']);
        $disposition = $download ? 'attachment' : 'inline';

        $this->output->enable_profiler(false);
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        header('Content-Type: ' . $mime_type);
        header('Content-Length: ' . filesize($document['file_path']));
        header('Content-Disposition: ' . $disposition . '; filename="' . $ascii_name . '"; filename*=UTF-8\'\'' . rawurlencode($document['file_name']));
        header('X-Content-Type-Options: nosniff');
        header('Cache-Control: private, no-store, max-age=0');
        readfile($document['file_path']);
        exit;
    }

    private function record_value($record, $field)
    {
        return isset($record->{$field}) ? $record->{$field} : '';
    }
}
