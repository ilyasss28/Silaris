<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Creates consistently styled, print-ready SILARIS workbooks. */
class Silaris_excel
{
    private $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('excel');
    }

    public function create_file($title, $subtitle, array $headers, array $rows, $filename = 'data-silaris')
    {
        return $this->download($title, $subtitle, $headers, $rows, $filename, false);
    }

    public function download($title, $subtitle, array $headers, array $rows, $filename, $stream = true)
    {
        if (!$headers) show_error('Kolom ekspor Excel tidak tersedia.', 500);

        $previous_error_reporting = error_reporting();
        error_reporting($previous_error_reporting & ~E_DEPRECATED & ~E_USER_DEPRECATED);
        $excel = $this->CI->excel;
        $sheet = $excel->setActiveSheetIndex(0);
        $last_column = PHPExcel_Cell::stringFromColumnIndex(count($headers) - 1);
        $sheet_title = preg_replace('/[\\\/?*:\[\]]/', ' ', ucwords((string) $title));
        $sheet->setTitle(substr(trim($sheet_title) ?: 'Data SILARIS', 0, 31));

        $sheet->setCellValueExplicit('A1', strtoupper((string) $title), PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->setCellValueExplicit('A2', (string) $subtitle, PHPExcel_Cell_DataType::TYPE_STRING);
        foreach ($headers as $index => $header) {
            $sheet->setCellValueByColumnAndRow($index, 4, (string) $header);
        }

        $widths = array_map(function ($header) {
            return max(10, min(45, mb_strlen((string) $header, 'UTF-8') + 4));
        }, array_values($headers));
        $row_number = 5;
        foreach ($rows as $row_index => $row) {
            $values = array_values((array) $row);
            foreach ($headers as $column_index => $unused) {
                $value = array_key_exists($column_index, $values) ? $values[$column_index] : '';
                if (is_int($value) || is_float($value)) {
                    $sheet->setCellValueByColumnAndRow($column_index, $row_number, $value);
                } else {
                    $sheet->setCellValueExplicitByColumnAndRow(
                        $column_index,
                        $row_number,
                        (string) $value,
                        PHPExcel_Cell_DataType::TYPE_STRING
                    );
                }
                $length = mb_strlen(preg_replace('/\s+/', ' ', (string) $value), 'UTF-8') + 2;
                $widths[$column_index] = max($widths[$column_index], min(45, $length));
            }
            if ($row_number % 2 === 0) {
                $sheet->getStyle('A' . $row_number . ':' . $last_column . $row_number)->getFill()
                    ->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('F7F9FC');
            }
            $row_number++;
        }
        $last_row = max(4, $row_number - 1);

        $sheet->getStyle('A1:' . $last_column . '1')->applyFromArray(array(
            'font' => array('bold' => true, 'size' => 16, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '07064F')),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
        ));
        $sheet->getStyle('A2:' . $last_column . '2')->applyFromArray(array(
            'font' => array('bold' => true, 'size' => 10, 'color' => array('rgb' => '725B00')),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => 'FFF7D8')),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER_CONTINUOUS, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER),
        ));
        $sheet->getStyle('A4:' . $last_column . '4')->applyFromArray(array(
            'font' => array('bold' => true, 'color' => array('rgb' => 'FFFFFF')),
            'fill' => array('type' => PHPExcel_Style_Fill::FILL_SOLID, 'color' => array('rgb' => '07064F')),
            'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => '07064F'))),
            'alignment' => array('horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER, 'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER, 'wrap' => true),
        ));
        if ($last_row >= 5) {
            $sheet->getStyle('A5:' . $last_column . $last_row)->applyFromArray(array(
                'borders' => array('allborders' => array('style' => PHPExcel_Style_Border::BORDER_THIN, 'color' => array('rgb' => 'DFE5EE'))),
                'alignment' => array('vertical' => PHPExcel_Style_Alignment::VERTICAL_TOP, 'wrap' => true),
            ));
            $sheet->getStyle('A5:A' . $last_row)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }

        foreach ($widths as $index => $width) {
            $sheet->getColumnDimension(PHPExcel_Cell::stringFromColumnIndex($index))->setWidth($index === 0 ? min(8, $width) : $width);
        }
        $sheet->getRowDimension(1)->setRowHeight(30);
        $sheet->getRowDimension(2)->setRowHeight(22);
        $sheet->getRowDimension(4)->setRowHeight(30);
        for ($index = 5; $index <= $last_row; $index++) $sheet->getRowDimension($index)->setRowHeight(-1);
        $sheet->freezePane('A5');
        $sheet->setAutoFilter('A4:' . $last_column . '4');

        $sheet->getPageSetup()
            ->setPaperSize(PHPExcel_Worksheet_PageSetup::PAPERSIZE_A4)
            ->setOrientation(count($headers) > 5 ? PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE : PHPExcel_Worksheet_PageSetup::ORIENTATION_PORTRAIT)
            ->setFitToWidth(1)->setFitToHeight(0)
            ->setRowsToRepeatAtTopByStartAndEnd(1, 4)
            ->setPrintArea('A1:' . $last_column . $last_row);
        $sheet->getPageMargins()->setTop(0.5)->setRight(0.35)->setLeft(0.35)->setBottom(0.55)->setHeader(0.2)->setFooter(0.25);
        $sheet->getPageSetup()->setHorizontalCentered(true);
        $sheet->getHeaderFooter()
            ->setOddHeader('&LSILARIS&R' . strtoupper((string) $title))
            ->setOddFooter('&LKantor Wilayah Kementerian Hukum Sulawesi Tenggara&RHalaman &P dari &N');
        $sheet->setShowGridlines(false);

        $excel->getProperties()->setCreator('SILARIS')->setTitle((string) $title)->setSubject((string) $subtitle);
        $safe_filename = preg_replace('/[^A-Za-z0-9._-]+/', '-', (string) $filename);
        $safe_filename = trim($safe_filename, '-.') ?: 'data-silaris-' . date('Ymd-His');
        if (strtolower(substr($safe_filename, -5)) !== '.xlsx') $safe_filename .= '.xlsx';

        $temporary_file = tempnam(sys_get_temp_dir(), 'silaris-excel-');
        PHPExcel_IOFactory::createWriter($excel, 'Excel2007')->save($temporary_file);
        if (!$stream) {
            error_reporting($previous_error_reporting);
            return $temporary_file;
        }
        while (ob_get_level() > 0) ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $safe_filename . '"');
        header('Content-Length: ' . filesize($temporary_file));
        header('Cache-Control: no-store, no-cache, must-revalidate');
        readfile($temporary_file);
        @unlink($temporary_file);
        error_reporting($previous_error_reporting);
        exit;
    }
}
