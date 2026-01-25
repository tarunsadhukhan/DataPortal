<?php defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class Excel {

   public function export($data, $filename) {
      // Create a new PhpSpreadsheet instance
      $spreadsheet = new Spreadsheet();

      // Load HTML data into PhpSpreadsheet
      $htmlData = $data[1][0]; // Assuming the HTML data is in the second element of the array
      $spreadsheet->getActiveSheet()->setCellValue('A1', 'HTML Data');
      $spreadsheet->getActiveSheet()->getCell('A2')->setValueExplicit($htmlData, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

      // Set headers for download
      header('Content-Type: application/vnd.ms-excel');
      header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
      header('Cache-Control: max-age=0');

      // Create Excel writer
      $writer = new Xls($spreadsheet);

      // Save to output
      $writer->save('php://output');
  }
}
