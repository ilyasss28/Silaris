<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Rekap Laporan Controller
*| --------------------------------------------------------------------------
*| Rekap Laporan site
*|
*/
class Rekap_Laporan extends Admin
{
	private $report_upload_path = 'uploads/laporan/';
	private $legacy_report_upload_path = 'uploads/rekap_Laporan/';

	public function __construct()
	{
		parent::__construct();

		$this->load->model('model_rekap_Laporan');
		$this->load->library('report_access');
		$this->require_rekap_role();
	}

	/**
	 * Rekap is an oversight workspace, never a second personal report list.
	 * Keep this role boundary independent from potentially stale menu grants.
	 */
	private function require_rekap_role()
	{
		if (!$this->aauth->is_loggedin()) {
			return;
		}

		$allowed_roles = array('Admin', 'Kanwil', 'MPD');
		$groups = $this->aauth->get_user_groups();
		foreach ($groups as $group) {
			if (in_array((string) $group->name, $allowed_roles, true)) {
				return;
			}
		}

		show_error('Rekap Laporan hanya dapat diakses oleh petugas pengawasan.', 403, 'Akses Ditolak');
	}

	/**
	 * Resolve the shared report document storage used by Laporan and Rekap.
	 * Older installations briefly stored Rekap files in a separate directory,
	 * so retain a read fallback without splitting new documents again.
	 */
	private function report_document_path($file_name)
	{
		$file_name = basename(str_replace('\\', '/', (string) $file_name));
		$canonical_path = $this->report_upload_path . $file_name;
		$legacy_path = $this->legacy_report_upload_path . $file_name;

		if ($file_name !== '' && !is_file(FCPATH . $canonical_path) && is_file(FCPATH . $legacy_path)) {
			return $legacy_path;
		}

		return $canonical_path;
	}

	private function report_document_url($file_name)
	{
		$path = $this->report_document_path($file_name);
		$directory = str_replace('\\', '/', dirname($path));

		return base_url(trim($directory, '/') . '/' . rawurlencode(basename($path)));
	}

	/**
	* show all Rekap Laporans
	*
	* @var $offset String
	*/
	public function index($offset = 0)
	{
		$this->is_allowed('rekap_Laporan_list');

		$total_rows = $this->model_rekap_Laporan->count_all();
		// Rows are loaded asynchronously by DataTables. Rendering thousands of
		// records into the initial HTML made this page unnecessarily large.
		$this->data['rekap_Laporans'] = [];
		$this->data['rekap_Laporan_counts'] = $total_rows;

		$this->data['pagination'] = '';

		$this->template->title('Rekap Laporan List');
		$this->render('modul/rekap_Laporan/rekap_Laporan_list', $this->data);
	}

	/**
	 * Data source for the client-side DataTable.
	 */
	public function datatable()
	{
		// The development profiler appends HTML to output by default, which
		// corrupts JSON responses consumed by DataTables.
		$this->output->enable_profiler(false);

		if (!$this->is_allowed('rekap_Laporan_list', false)) {
			return $this->output
				->set_status_header(403)
				->set_content_type('application/json')
				->set_output(json_encode(['data' => []]));
		}

		$permissions = [
			'view'   => $this->is_allowed('rekap_Laporan_view', false),
			'update' => $this->is_allowed('rekap_Laporan_update', false),
			'delete' => $this->is_allowed('rekap_Laporan_delete', false),
		];
		$draw = max(0, (int) $this->input->get('draw'));
		$start = max(0, (int) $this->input->get('start'));
		$length = (int) $this->input->get('length');
		$maximum_length = $this->input->get('export') === '1' ? 2000 : 100;
		$length = $length > 0 ? min($length, $maximum_length) : 25;
		$search_input = $this->input->get('search');
		$search = is_array($search_input) ? trim((string) ($search_input['value'] ?? '')) : '';
		$order_input = $this->input->get('order');
		$order = is_array($order_input) ? reset($order_input) : [];
		$order_columns = [null, 'nama_notaris', 'Tanggal_Laporan', 'Laporan', null];
		$order_index = is_array($order) ? (int) ($order['column'] ?? 0) : 0;
		$order_field = $order_columns[$order_index] ?? null;
		$order_direction = is_array($order) ? ($order['dir'] ?? 'DESC') : 'DESC';
		$total_rows = $this->model_rekap_Laporan->count_all();
		$filtered_rows = $search === '' ? $total_rows : $this->model_rekap_Laporan->count_all($search);
		$records = $this->model_rekap_Laporan->get(
			$search,
			null,
			$length,
			$start,
			[],
			$order_field,
			$order_direction
		);
		$rows = [];

		foreach ($records as $record) {
			$file_name = (string) $record->Laporan;
			$file_asset_url = $this->report_document_url($file_name);
			$file_url = document_preview_url($file_asset_url);
			$file = '';

			if ($file_name !== '') {
				if (is_image($file_name)) {
					$file = '<a href="' . html_escape($file_url) . '" target="_blank" rel="noopener noreferrer" title="Buka di tab baru">' .
						'<img src="' . html_escape($file_asset_url) . '" class="image-responsive" alt="Laporan" width="40"></a>';
				} else {
					$file = '<a href="' . html_escape($file_url) . '" target="_blank" rel="noopener noreferrer" title="Buka di tab baru">' .
						'<img src="' . html_escape(get_icon_file($file_name)) . '" class="image-responsive image-icon" alt="Laporan" width="40"></a>';
				}
			}

			$actions = '';
			if ($permissions['view']) {
				$actions .= '<a href="' . site_url('rekap-laporan/view/' . (int) $record->id) . '" title="Lihat" class="label-default"><i class="fa fa-newspaper-o"></i></a> ';
			}
			if ($permissions['update']) {
				$actions .= '<a href="' . site_url('rekap-laporan/edit/' . (int) $record->id) . '" title="Ubah" class="label-default"><i class="fa fa-edit"></i></a> ';
			}
			if ($permissions['delete']) {
				$actions .= '<a href="javascript:void(0);" data-href="' . site_url('rekap-laporan/delete/' . (int) $record->id) . '" title="Hapus" class="label-default remove-data"><i class="fa fa-close" style="color:red"></i></a>';
			}

			$rows[] = [
				'<input type="checkbox" class="flat-red check" name="id[]" value="' . (int) $record->id . '">',
				html_escape($record->nama_notaris),
				html_escape(format_date_id($record->Tanggal_Laporan)),
				$file,
				$actions,
			];
		}

		return $this->output
			->set_content_type('application/json')
			->set_output(json_encode([
				'draw' => $draw,
				'recordsTotal' => $total_rows,
				'recordsFiltered' => $filtered_rows,
				'data' => $rows,
			]));
	}
	
	/**
	* Add new rekap_Laporans
	*
	*/
	public function add()
	{
		show_error(
			'Laporan baru harus dikirim melalui menu Laporan oleh akun pemiliknya.',
			405,
			'Gunakan Menu Laporan'
		);
	}

	/**
	* Add New Rekap Laporans
	*
	* @return JSON
	*/
	public function add_save()
	{
		return $this->output
			->set_status_header(405)
			->set_content_type('application/json')
			->set_output(json_encode([
				'success' => false,
				'message' => 'Laporan baru harus dikirim melalui menu Laporan oleh akun pemiliknya.',
			]));
	}
	
		/**
	* Update view Rekap Laporans
	*
	* @var $id String
	*/
	public function edit($id)
	{
		$this->is_allowed('rekap_Laporan_update');

		$this->data['rekap_Laporan'] = $this->model_rekap_Laporan->find($id);
		if (!$this->data['rekap_Laporan']) {
			show_404();
		}

		$this->template->title('Rekap Laporan Update');
		$this->render('modul/rekap_Laporan/rekap_Laporan_update', $this->data);
	}

	/**
	* Update Rekap Laporans
	*
	* @var $id String
	*/
	public function edit_save($id)
	{
		if (!$this->is_allowed('rekap_Laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$current_report = $this->model_rekap_Laporan->find($id);
		if (!$current_report) {
			return $this->output
				->set_status_header(404)
				->set_content_type('application/json')
				->set_output(json_encode([
					'success' => false,
					'message' => 'Laporan tidak ditemukan.',
				]));
		}
		
		$this->form_validation->set_rules('Tanggal_Laporan', 'Tanggal Laporan', 'trim|required');
		$this->form_validation->set_rules('rekap_Laporan_Laporan_name', 'Laporan', 'trim|required|max_length[1000]');
		
		if ($this->form_validation->run()) {
			$rekap_Laporan_Laporan_uuid = $this->input->post('rekap_Laporan_Laporan_uuid');
			$rekap_Laporan_Laporan_name = $this->input->post('rekap_Laporan_Laporan_name');
		
			$save_data = [
				'Tanggal_Laporan' => $this->input->post('Tanggal_Laporan'),
			];

			if (!is_dir(FCPATH . $this->report_upload_path)) {
				mkdir(FCPATH . $this->report_upload_path, 0755, true);
			}

			if (!empty($rekap_Laporan_Laporan_uuid)) {
				$rekap_Laporan_Laporan_name_copy = date('YmdHis') . '-' . $rekap_Laporan_Laporan_name;

				rename(FCPATH . 'uploads/tmp/' . $rekap_Laporan_Laporan_uuid . '/' . $rekap_Laporan_Laporan_name,
						FCPATH . $this->report_upload_path . $rekap_Laporan_Laporan_name_copy);

				if (!is_file(FCPATH . $this->report_upload_path . $rekap_Laporan_Laporan_name_copy)) {
					echo json_encode([
						'success' => false,
						'message' => 'Error uploading file'
						]);
					exit;
				}

				$save_data['Laporan'] = $rekap_Laporan_Laporan_name_copy;
			}
		
			
			$save_rekap_Laporan = $this->model_rekap_Laporan->change($id, $save_data);

			if ($save_rekap_Laporan) {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = true;
					$this->data['id'] 	   = $id;
					$this->data['message'] = cclang('success_update_data_stay', [
						anchor('rekap_Laporan', ' Go back to list')
					]);
				} else {
					set_message(
						cclang('success_update_data_redirect', [
					]), 'success');

            		$this->data['success'] = true;
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			} else {
				if ($this->input->post('save_type') == 'stay') {
					$this->data['success'] = false;
					$this->data['message'] = cclang('data_not_change');
				} else {
            		$this->data['success'] = false;
            		$this->data['message'] = cclang('data_not_change');
					$this->data['redirect'] = base_url('rekap_Laporan');
				}
			}
		} else {
			$this->data['success'] = false;
			$this->data['message'] = validation_errors();
		}

		echo json_encode($this->data);
	}
	
	/**
	* delete Rekap Laporans
	*
	* @var $id String
	*/
	public function delete($id = null)
	{
		$this->is_allowed('rekap_Laporan_delete');

		$this->load->helper('file');

		$arr_id = $this->input->get('id');
		$remove = false;

		if (!empty($id)) {
			$remove = $this->_remove($id);
		} elseif (is_array($arr_id) && count($arr_id) > 0) {
			foreach ($arr_id as $id) {
				$remove = $this->_remove($id);
			}
		}

		if ($remove) {
            set_message(cclang('has_been_deleted', 'rekap_Laporan'), 'success');
        } else {
            set_message(cclang('error_delete', 'rekap_Laporan'), 'error');
        }

		redirect_back();
	}

		/**
	* View view Rekap Laporans
	*
	* @var $id String
	*/
	public function view($id)
	{
		$this->is_allowed('rekap_Laporan_view');

		$this->data['rekap_Laporan'] = $this->model_rekap_Laporan->join_avaiable()->filter_avaiable()->find($id);
		if (!$this->data['rekap_Laporan']) {
			show_404();
		}
		$this->data['rekap_document_url'] = $this->report_document_url($this->data['rekap_Laporan']->Laporan);

		$this->template->title('Rekap Laporan Detail');
		$this->render('modul/rekap_Laporan/rekap_Laporan_view', $this->data);
	}
	
	/**
	* delete Rekap Laporans
	*
	* @var $id String
	*/
	private function _remove($id)
	{
		$rekap_Laporan = $this->model_rekap_Laporan->find($id);

		if (!empty($rekap_Laporan->Laporan)) {
			$file_name = basename(str_replace('\\', '/', (string) $rekap_Laporan->Laporan));
			$paths = [
				FCPATH . $this->report_upload_path . $file_name,
				FCPATH . $this->legacy_report_upload_path . $file_name,
			];

			foreach (array_unique($paths) as $path) {
				if (is_file($path)) {
					unlink($path);
				}
			}
		}
		
		
		return $this->model_rekap_Laporan->remove($id);
	}
	
	/**
	* Upload Image Rekap Laporan	* 
	* @return JSON
	*/
	public function upload_Laporan_file()
	{
		if (!$this->is_allowed('rekap_Laporan_add', false) && !$this->is_allowed('rekap_Laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$uuid = $this->input->post('qquuid');

		echo $this->upload_file([
			'uuid' 		 	=> $uuid,
			'table_name' 	=> 'laporan',
			'allowed_types' => 'pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png',
			'max_size'      => 10000,
		]);
	}

	/**
	* Delete Image Rekap Laporan	* 
	* @return JSON
	*/
	public function delete_Laporan_file($uuid)
	{
		if (!$this->is_allowed('rekap_Laporan_delete', false)) {
			echo json_encode([
				'success' => false,
				'error' => cclang('sorry_you_do_not_have_permission_to_access')
				]);
			exit;
		}

		$upload_path = $this->report_upload_path;
		if (ctype_digit((string) $uuid)) {
			$rekap_Laporan = $this->model_rekap_Laporan->find($uuid);
			if ($rekap_Laporan && !empty($rekap_Laporan->Laporan)) {
				$upload_path = rtrim(str_replace('\\', '/', dirname($this->report_document_path($rekap_Laporan->Laporan))), '/') . '/';
			}
		}

		echo $this->delete_file([
            'uuid'              => $uuid, 
            'delete_by'         => $this->input->get('by'), 
            'field_name'        => 'Laporan', 
            'upload_path_tmp'   => './uploads/tmp/',
			'table_name'        => 'laporan',
            'primary_key'       => 'id',
			'upload_path'       => $upload_path
        ]);
	}

	/**
	* Get Image Rekap Laporan	* 
	* @return JSON
	*/
	public function get_Laporan_file($id)
	{
		if (!$this->is_allowed('rekap_Laporan_update', false)) {
			echo json_encode([
				'success' => false,
				'message' => 'Image not loaded, you do not have permission to access'
				]);
			exit;
		}

		$rekap_Laporan = $this->model_rekap_Laporan->find($id);

		$upload_path = $this->report_upload_path;
		if ($rekap_Laporan && !empty($rekap_Laporan->Laporan)) {
			$upload_path = rtrim(str_replace('\\', '/', dirname($this->report_document_path($rekap_Laporan->Laporan))), '/') . '/';
		}

		echo $this->get_file([
            'uuid'              => $id, 
            'delete_by'         => 'id', 
            'field_name'        => 'Laporan', 
			'table_name'        => 'laporan',
            'primary_key'       => 'id',
			'upload_path'       => $upload_path,
            'delete_endpoint'   => 'rekap_Laporan/delete_Laporan_file'
        ]);
	}
	
	
	/**
	* Export to excel
	*
	* @return Files Excel .xls
	*/
	public function export()
	{
		$this->is_allowed('rekap_Laporan_list');

		$this->model_rekap_Laporan->export_scoped('rekap_laporan');
	}

	/**
	* Export to PDF
	*
	* @return Files PDF .pdf
	*/
	public function export_pdf()
	{
		$this->is_allowed('rekap_Laporan_list');

		$this->model_rekap_Laporan->pdf_scoped('Rekap Laporan');
	}

	/**
	 * Download a lightweight inventory that binds every database row to its
	 * physical document. Include this CSV beside the SQL dump and uploads ZIP
	 * so a restored backup can be checked without opening files one by one.
	 */
	public function backup_manifest()
	{
		$this->is_allowed('rekap_Laporan_list');
		$this->output->enable_profiler(false);

		$file_name = 'manifest-laporan-' . date('Y-m-d-His') . '.csv';
		header('Content-Type: text/csv; charset=UTF-8');
		header('Content-Disposition: attachment; filename="' . $file_name . '"');
		header('Cache-Control: no-store, no-cache, must-revalidate');

		$output = fopen('php://output', 'wb');
		fwrite($output, "\xEF\xBB\xBF");
		fputcsv($output, [
			'id',
			'username',
			'nama_notaris',
			'tanggal_laporan',
			'nama_file',
			'path_backup',
			'status_file',
			'ukuran_byte',
			'terakhir_diubah',
		], ',', '"', '\\');

		$this->report_access->apply_scope($this->db, 'laporan');
		$query = $this->db
			->select(
				"laporan.id, laporan.username, "
				. "COALESCE(NULLIF(TRIM(laporan.nama_notaris), ''), owner.full_name, laporan.username) AS nama_notaris, "
				. "laporan.Tanggal_Laporan, laporan.Laporan",
				false
			)
			->join('aauth_users AS owner', 'owner.id = laporan.owner_user_id', 'left')
			->order_by('laporan.id', 'ASC')
			->get('laporan');

		foreach ($query->result() as $report) {
			$relative_path = $this->report_document_path($report->Laporan);
			$absolute_path = FCPATH . $relative_path;
			$exists = is_file($absolute_path);

			fputcsv($output, [
				(int) $report->id,
				(string) $report->username,
				(string) $report->nama_notaris,
				(string) $report->Tanggal_Laporan,
				basename(str_replace('\\', '/', (string) $report->Laporan)),
				$relative_path,
				$exists ? 'ADA' : 'HILANG',
				$exists ? (int) filesize($absolute_path) : 0,
				$exists ? date('Y-m-d H:i:s', filemtime($absolute_path)) : '',
			], ',', '"', '\\');
		}

		fclose($output);
		exit;
	}
}


/* End of file rekap_Laporan.php */
/* Location: ./application/controllers/Rekap Laporan.php */
