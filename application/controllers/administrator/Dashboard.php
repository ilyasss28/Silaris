<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
*| --------------------------------------------------------------------------
*| Dashboard Controller
*| --------------------------------------------------------------------------
*| For see your board
*|
*/
class Dashboard extends Admin	
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('model_dashboard');
	}

	public function index()
	{
		$this->require_authenticated_user();

		$user_id = (int) $this->session->userdata('id');
		$groups = $this->aauth->get_user_groups($user_id);
		$group_names = array_map(function ($group) {
			return $group->name;
		}, $groups);
		$role = $this->model_dashboard->resolve_role($group_names);
		$chart_filter = [
			'mode' => $this->input->get('chart_mode', true),
			'year' => $this->input->get('chart_year', true),
			'month' => $this->input->get('chart_month', true),
			'quarter' => $this->input->get('chart_quarter', true),
			'semester' => $this->input->get('chart_semester', true),
		];
		$data = $this->model_dashboard->build($user_id, $role, $chart_filter);

		$this->render('backend/standart/dashboard', $data);
	}

	public function chart()
	{
		$this->require_authenticated_user();

		$data = [];
		$this->render('backend/standart/chart', $data);
	}

	public function download_compliance()
	{
		$this->require_authenticated_user();

		$user_id = (int) $this->session->userdata('id');
		$groups = $this->aauth->get_user_groups($user_id);
		$group_names = array_map(function ($group) {
			return $group->name;
		}, $groups);
		$role = $this->model_dashboard->resolve_role($group_names);
		$status = strtolower((string) $this->input->get('status', true));
		if (!in_array($status, ['submitted', 'missing'], true)) {
			$status = 'submitted';
		}
		$filter = [
			'mode' => $this->input->get('chart_mode', true),
			'year' => $this->input->get('chart_year', true),
			'month' => $this->input->get('chart_month', true),
			'quarter' => $this->input->get('chart_quarter', true),
			'semester' => $this->input->get('chart_semester', true),
		];
		$export = $this->model_dashboard->compliance_export($user_id, $role, $filter, $status);
		$status_label = $status === 'submitted' ? 'sudah-melapor' : 'belum-melapor';
		$status_title = $status === 'submitted' ? 'SUDAH MELAPOR' : 'BELUM MELAPOR';
		$filename = 'kepatuhan-' . $status_label . '-' . date('Ymd-His') . '.xlsx';
		$previous_error_reporting = error_reporting();
		error_reporting($previous_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
		$this->load->library('excel');

		$sheet = $this->excel->setActiveSheetIndex(0);
		$sheet->setTitle($status === 'submitted' ? 'Sudah Melapor' : 'Belum Melapor');
		$sheet->mergeCells('A1:H1')->setCellValue('A1', 'DAFTAR NOTARIS ' . $status_title);
		$sheet->mergeCells('A2:H2')->setCellValue('A2', 'Periode Pemantauan: ' . $export['period_label']);
		$headers = ['No.', 'Nama Notaris', 'Nomor Telepon', 'Wilayah', 'Status', 'Jumlah Laporan', 'Laporan Terakhir', 'Periode'];
		foreach ($headers as $column => $header) {
			$sheet->setCellValueByColumnAndRow($column, 4, $header);
		}

		$row_number = 5;
		foreach ($export['rows'] as $index => $row) {
			$sheet->setCellValueExplicit('A' . $row_number, $index + 1, PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$sheet->setCellValueExplicit('B' . $row_number, (string) $row['display_name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('C' . $row_number, $row['phone_number'] === '-' ? 'Belum tersedia' : format_phone_number($row['phone_number']), PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValueExplicit('D' . $row_number, (string) $row['region_name'], PHPExcel_Cell_DataType::TYPE_STRING);
			$sheet->setCellValue('E' . $row_number, $row['status'] === 'submitted' ? 'Sudah Melapor' : 'Belum Melapor');
			$sheet->setCellValueExplicit('F' . $row_number, (int) $row['report_count'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
			$sheet->setCellValue('G' . $row_number, $row['last_report'] ? format_date_id($row['last_report']) : '-');
			$sheet->setCellValue('H' . $row_number, $export['period_label']);
			if ($row_number % 2 === 0) {
				$sheet->getStyle('A' . $row_number . ':H' . $row_number)->getFill()
					->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F7F9FC');
			}
			$row_number++;
		}
		$last_row = max(4, $row_number - 1);

		$sheet->getStyle('A1:H1')->applyFromArray([
			'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => 'FFFFFF']],
			'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '07064F']],
			'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
		]);
		$sheet->getStyle('A2:H2')->applyFromArray([
			'font' => ['bold' => true, 'size' => 11, 'color' => ['rgb' => '725B00']],
			'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => 'FFF7D8']],
			'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER],
		]);
		$sheet->getStyle('A4:H4')->applyFromArray([
			'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
			'fill' => ['type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => ['rgb' => '07064F']],
			'alignment' => ['horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true],
		]);
		if ($last_row >= 5) {
			$sheet->getStyle('A5:H' . $last_row)->applyFromArray([
				'borders' => ['allborders' => ['style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => ['rgb' => 'DFE5EE']]],
				'alignment' => ['vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER],
			]);
			$sheet->getStyle('A5:A' . $last_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E5:H' . $last_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheet->getStyle('E5:E' . $last_row)->getFont()->setBold(true)->getColor()->setRGB($status === 'submitted' ? '168252' : 'C23949');
		}

		$widths = ['A' => 7, 'B' => 34, 'C' => 21, 'D' => 30, 'E' => 19, 'F' => 16, 'G' => 21, 'H' => 22];
		foreach ($widths as $column => $width) {
			$sheet->getColumnDimension($column)->setWidth($width);
		}
		$sheet->getRowDimension(1)->setRowHeight(30);
		$sheet->getRowDimension(2)->setRowHeight(22);
		$sheet->getRowDimension(4)->setRowHeight(28);
		$sheet->freezePane('A5');
		$sheet->setAutoFilter('A4:H4');
		$sheet->getPageSetup()->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4)
			->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE)
			->setFitToWidth(1)->setFitToHeight(0)
			->setRowsToRepeatAtTopByStartAndEnd(1, 4)
			->setPrintArea('A1:H' . $last_row)
			->setHorizontalCentered(true);
		$sheet->getPageMargins()->setTop(0.5)->setRight(0.4)->setLeft(0.4)->setBottom(0.5);
		$sheet->getHeaderFooter()->setOddHeader('&LSILARIS&RKEPATUHAN NOTARIS')
			->setOddFooter('&LKantor Wilayah Kementerian Hukum Sulawesi Tenggara&RHalaman &P dari &N');
		$sheet->setShowGridlines(false);
		$this->excel->getProperties()->setCreator('SILARIS')->setTitle('Kepatuhan Notaris ' . $status_title)->setSubject($export['period_label']);

		$temporary_file = tempnam(sys_get_temp_dir(), 'silaris-excel-');
		$writer = PHPExcel_IOFactory::createWriter($this->excel, 'Excel2007');
		$writer->save($temporary_file);
		while (ob_get_level() > 0) {
			ob_end_clean();
		}
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment; filename="' . $filename . '"');
		header('Content-Length: ' . filesize($temporary_file));
		header('Cache-Control: no-store, no-cache, must-revalidate');
		readfile($temporary_file);
		unlink($temporary_file);
		error_reporting($previous_error_reporting);
		exit;
	}

	public function compliance_notaries()
	{
		$this->require_authenticated_user();

		$user_id = (int) $this->session->userdata('id');
		$groups = $this->aauth->get_user_groups($user_id);
		$group_names = array_map(function ($group) {
			return $group->name;
		}, $groups);
		$role = $this->model_dashboard->resolve_role($group_names);
		$status = strtolower((string) $this->input->get('status', true));
		if (!in_array($status, ['submitted', 'missing'], true)) {
			$status = 'missing';
		}
		$filter = [
			'mode' => $this->input->get('chart_mode', true),
			'year' => $this->input->get('chart_year', true),
			'month' => $this->input->get('chart_month', true),
			'quarter' => $this->input->get('chart_quarter', true),
			'semester' => $this->input->get('chart_semester', true),
		];
		$export = $this->model_dashboard->compliance_export($user_id, $role, $filter, $status);
		$query = http_build_query([
			'chart_mode' => $export['period']['mode'],
			'chart_year' => $export['period']['year'],
			'chart_month' => $export['period']['month'],
			'chart_quarter' => $export['period']['quarter'],
			'chart_semester' => $export['period']['semester'],
		]);

		$data = [
			'compliance_status' => $status,
			'compliance_rows' => $export['rows'],
			'compliance_period' => $export['period_label'],
			'compliance_query' => $query,
		];
		$this->render('backend/standart/dashboard_compliance', $data);
	}

	/**
	 * Dashboard is the safe landing page used after login and after a denied
	 * permission. Requiring the dashboard permission here would redirect a
	 * denied user back to this same URL forever.
	 */
	private function require_authenticated_user()
	{
		if (!$this->aauth->is_loggedin()) {
			redirect('login');
		}
	}
}

/* End of file Dashboard.php */
/* Location: ./application/controllers/administrator/Dashboard.php */
