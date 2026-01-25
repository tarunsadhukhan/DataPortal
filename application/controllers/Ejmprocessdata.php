<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;

//use Mpdf\Mpdf;
use Mpdf\Mpdf;
//use Fpdf\Fpdf;
//use FPDF; //
//use FPDF; //
require_once(APPPATH . 'libraries/fpdf/fpdf.php');


//use Mpdf\Mpdf;
//require_once(APPPATH . 'libraries/fpdf/fpdf.php');


class Ejmprocessdata extends MY_Controller {

public function __construct() {
        parent::__construct();
		$this->load->library('session');
$this->load->helper(array('url', 'download'));  
		$this->load->model('Ejmallprocessdata');
   $this->config->load('python', TRUE);


        ini_set('max_execution_time', 6000); //300 seconds = 5 minutes

	
}

public function esiacreport() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$sdate=str_replace('-','',$periodfromdate);
		$ebno=$this->input->get('ebno');
 		// $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
	//	$result = $this->Ejmallprocessdata->esiacreport($periodfromdate,$ebno);

	    $result = $this->Ejmallprocessdata->esiacreport($periodfromdate, $ebno);
	$row=$result[0];
//		var_dump($row);
    if (!$row) {
        echo "No data found for this employee/period.";
        return;
    }
//echo '1st '.$row["emp_name"];
//echo '2nd '.$row->emp_name;
//echo '3rd '.$row[0];

		$rtpd=$row['gross']/$row['wdays'];

	    $this->load->library('fpdf_lib');
//	$pdf = new \FPDF();
        $pdf = $this->fpdf_lib; 
        $y=5;
        $pdf->AddPage('P', 'A4');
    	$pdf->SetFont('Arial', 'B', 12);

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetMargins(10, 10, 10);
    $pdf->SetAutoPageBreak(true, 10);
    $pdf->AddPage();

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(0, 7, "VERIFICATION OF WAGES FOR THE PURPOSE OF SECTION 2 (9) OF THE E.S.I ACT'1948", 0, 1, 'C');
    $pdf->Ln(3);

    $pdf->SetFont('Arial', '', 10);

    // 1. Employer name & code (like in your form)
    $pdf->Cell(80, 6, "1. Name and address of the Employer with Code No.", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
    $pdf->MultiCell(0, 6, $row['compname'] . ", " . $row['compaddress']. "          (Code: " . $row['compcode'] . ")", 0, 'L');

    // 2. Employee name
    $pdf->Cell(80, 6, "2. Name of the employee", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
	$pdf->MultiCell(0, 6, $row['emp_name'], 0, 'L');

    // 3. Insurance No.
    $pdf->Cell(80, 6, "3. Insurance No.", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
    $pdf->MultiCell(0, 6, $row['esi_no'], 0, 'L');

    // 4. Date of Employment Injury
    $pdf->Cell(80, 6, "4. Date of Employment Injury", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
    $pdf->MultiCell(0, 6, '', 0, 'L');

    // 5. Location of Injury
    $pdf->Cell(80, 6, "5. Location of Injury", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
    $pdf->MultiCell(0, 6, '', 0, 'L');

    // 6. Contribution Period in which E.I took place
    $pdf->Cell(80, 6, "6. Contribution Period in which E.I took place", 0, 0, 'L');
    $pdf->Cell(5, 6, ":", 0, 0, 'L');
    $pdf->MultiCell(0, 6, '', 0, 'L');

    // 7. Particulars of Wage paid/payable...
    $pdf->Ln(2);
    $pdf->Cell(80, 6, "7. Particulars of Wage paid/Payable during the", 0, 1, 'L');
    $wagePeriod = date('F Y', strtotime($periodfromdate));  // e.g. October 2025
	$pdf->Cell(30, 6, "    Wage Period i.e. ", 0, 0, 'L');
	$pdf->Cell(55);     
    $pdf->Cell(30, 6, $wagePeriod, 0, 1, 'L');

    $pdf->Ln(3);

    // ---- TABLE: Payable / Deduction / Paid (like in your sample) ----
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 8, '', 1, 0, 'C');          // blank col (description)
    $pdf->Cell(40, 8, 'Payable', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Deduction', 1, 0, 'C');
    $pdf->Cell(40, 8, 'Paid', 1, 1, 'C');

    $pdf->SetFont('Arial', '', 10);

    // Working Days row
    $pdf->Cell(60, 8, 'WORKING DAYS', 1, 0, 'L');
    $pdf->Cell(40, 8, $row['wdays'], 1, 0, 'C');
    $pdf->Cell(40, 8, '', 1, 0, 'C');
    $pdf->Cell(40, 8, '', 1, 1, 'C');

    // Festival Days
    $pdf->Cell(60, 8, 'FESTIVAL DAYS', 1, 0, 'L');
    $pdf->Cell(40, 8, $row['festival_days'], 1, 0, 'C');
    $pdf->Cell(40, 8, '', 1, 0, 'C');
    $pdf->Cell(40, 8, '', 1, 1, 'C');

    // Basic Pay
    $pdf->Cell(20, 8, 'BASIC PAY', 1, 0, 'L');
    $pdf->Cell(18, 8, 'RATE/PD', 0, 0, 'L');
    $pdf->Cell(22, 8, '@'.number_format($rtpd, 2), 0, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['gross'], 2), 1, 0, 'R');
    $pdf->Cell(20, 8, 'PF ',   1, 0, 'L');   // PF
    $pdf->Cell(20, 8, number_format($row['pf'], 2),   1, 0, 'R');   // PF
    $pdf->Cell(40, 8, '', 1, 1, 'R');

    // DA / ADA
    $pdf->Cell(60, 8, 'DA / A.D.A.', 1, 0, 'L');
    $pdf->Cell(40, 8, number_format($row['da'], 2), 1, 0, 'R');
    $pdf->Cell(20, 8, 'ESI ',   1, 0, 'L');   // PF
    $pdf->Cell(20, 8, number_format($row['esi'], 2),   1, 0, 'R');   // PF
   $pdf->Cell(40, 8, '', 1, 1, 'R');

    // Special Allowance
    $pdf->Cell(60, 8, 'SPECIAL ALLOWANCE', 1, 0, 'L');
    $pdf->Cell(40, 8, number_format($row['special_allowance'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, '', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['special_allowance'], 2), 1, 1, 'R');

    // HRA
    $pdf->Cell(30, 8, ' HRA ', 1, 0, 'L');
    $pdf->Cell(30, 8, '@'.number_format($row['hrap'], 2).'%', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['hra'], 2), 1, 0, 'R');
    $pdf->Cell(20, 8, 'PTax ',   1, 0, 'L');   // PF
    $pdf->Cell(20, 8, number_format($row['ptax'], 2),   1, 0, 'R');   // PF
    $pdf->Cell(40, 8, '', 1, 1, 'R');

    // P.D / Incentive
    $pdf->Cell(60, 8, 'P.D / Incentive', 1, 0, 'L');
    $pdf->Cell(40, 8, number_format($row['pd_incentive'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, '', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['pd_incentive'], 2), 1, 1, 'R');

    // Other Wages
    $pdf->Cell(60, 8, 'Other Wage(s)', 1, 0, 'L');
    $pdf->Cell(40, 8, number_format($row['other_wages'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, '', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['other_wages'], 2), 1, 1, 'R');

    // Totals row (bottom line in your form)
    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(60, 8, 'TOTAL (Rs.)', 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['tpay'],   2), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['tded'], 2), 1, 0, 'R');
    $pdf->Cell(40, 8, number_format($row['npay'],      2), 1, 1, 'R');
/* 	$pdf->SetFillColor(230, 230, 230);   
	$y6 = $pdf->GetY();
	$xLeft=$pdf->GetX();
	$width=200;
	$height=10;
	$pdf->Rect($xLeft, $y6-10, $width, $height, 'FD');
 */


/* 	$pdf->SetFillColor(230, 230, 230);             // light grey
$pdf->Rect(10, 60, 60, 8, 'F');               // x=10, y=60, w=60, h=8, filled

// 2) Black text on top
$pdf->SetTextColor(0, 0, 0);                  // black
$pdf->SetXY(10, 60);                          // same X, Y as Rect
$pdf->SetFont('Arial', '', 10);
$pdf->Cell(60, 8, "Data on fill", 0, 0, 'L'); // will be visible on grey
 */

    $pdf->Ln(10);

    // Signatures & footer like your sample
    $pdf->SetFont('Arial', '', 9);
    $pdf->Cell(0, 5, "Verified from Wage Records", 0, 1, 'L');
    $pdf->Ln(10);

    $pdf->SetFont('Arial', 'B', 10);
    $pdf->Cell(95, 5, "Investigating Officer", 0, 0, 'L');
    $pdf->Cell(95, 5, "Signature of the Employer", 0, 1, 'R');
    $pdf->Ln(5);
    $pdf->Cell(95, 5, "(Umesh Chandra Sahoo)", 0, 0, 'L'); // example names from your scan
    $pdf->Cell(95, 5, "Counter Signed", 0, 1, 'R');
    $pdf->Cell(95, 5, "Branch Manager", 0, 0, 'L');
    $pdf->Cell(95, 5, "Branch Manager", 0, 1, 'R');
    $pdf->Ln(10);
	$pdf->Cell(150);     
    $pdf->Cell(45, 5, "(Umesh Chandra Sahoo)", 0, 0, 'L'); // example names from your scan
    $pdf->Ln(5);
	$pdf->Cell(140);     
    $pdf->Cell(45, 5, "Branch Manager", 0, 1, 'R');


 

 
        // Create PDF
 //       $this->pdf->AddPage();
  		$filename = 'esi'.date('Ymd').'.pdf';
//          $this->fpdf_lib->Output('D', 'cepayslip'.'.pdf'); // 'D' = force download

    $filename = 'esi_verification_'.$row->emp_code.'_'.$wagePeriod.'.pdf';
    $pdf->Output('D', $filename);



	}







	public function oattprdprocess() {

		
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
//echo $periodfromdate;
        $mcc=$this->Ejmallprocessdata->oattprdprocess($periodfromdate);


			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	


	echo json_encode($response);




	}





public function oattprddownload() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$sdate=str_replace('-','',$periodfromdate);
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
	$result = $this->Ejmallprocessdata->oattprddownload($periodfromdate);

   // var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

// (Optional) Fit sheet to A4 paper
$sheet->getPageSetup()
      ->setPaperSize(PageSetup::PAPERSIZE_A4);

// (Optional) Fit to one page wide
$sheet->getPageSetup()
      ->setFitToWidth(1)
      ->setFitToHeight(0);

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 10,
							],
						];
 

$sheet->setCellValue('A1', 'OUTSIDER PRODUCTION DETAILS');
//$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':j'.$N;
$sheet->mergeCells($b);


$rowNumber = 4;

$tpay=0;

$columnNames = array_keys($result[0]);
//echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'2', $column);
        $col++;
    }


  $spreadsheet->getDefaultStyle()->getFont()->setSize(10);



$rowNumber = 3;

foreach ($result as $row) {
    $col = 'A';
    $colno = 0;

    foreach ($row as $cell) {
        if ($colno <= 4) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }
//$sheet->getStyle($cell)->getFont()->setSize(10);

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='k'.$rowNumber;
$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'J') as $col) {
   //     $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(10);
						 $sheet->getColumnDimension('B')->setWidth(5);
						 $sheet->getColumnDimension('C')->setWidth(9);
						$sheet->getColumnDimension('D')->setWidth(23);
$sheet->getColumnDimension('D')->setWidth(25);
$sheet->getColumnDimension('E')->setWidth(20);
$sheet->getColumnDimension('F')->setWidth(40);
$sheet->getColumnDimension('G')->setWidth(15);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('j')->setWidth(15);

						$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$rn='A2:j'.$rowNumber;

                            $centerAlignment = $sheet->getStyle($rn)->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);

$rn='A2:j'.$rowNumber;

							$sheet->getStyle('A1:j1')->applyFromArray($borderStyle);
							$sheet->getStyle($rn)->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Template');


	
    $filename="outsider_".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}
	

public function cntexlupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
	$fileupload =  $this->input->post('fileupload');
 	$comp = $this->session->userdata('companyId');
	$payschm=$this->input->post('att_payschm');  
	 $config['upload_path'] = './uploads/';
	// $config['allowed_types'] = 'csv|xlsx';
	 $config['allowed_types'] = 'csv|xlsx|xls';  
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);
	$this->load->database();

	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);

		// Determine the appropriate reader based on the file extension
/* 		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else if ('xlsx' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		} {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
		}
 */
if ($extension == 'csv') {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
} elseif ($extension == 'xls') {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
}



	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
		$highestColIdx = Coordinate::columnIndexFromString($highestColumn); // e.g. 6

//		echo 'hrow='.$highestRow.' hcol---'.$highestColumn.'hidx '.$highestColIdx ;
		$sheetData = $spreadsheet->getActiveSheet()->toArray();
//		echo var_dump($sheetData);
//		echo 'no of records '.count($sheetData);
		if (!empty($sheetData)) {
			$ebnos='';
		    for ($i=1; $i<count($sheetData); $i++) { //skipping first row
					$ebnos=$ebnos.$sheetData[$i][0].",";
			}	
			$ebnos=  substr($ebnos, 0, -1);

			$sqlp="select distinct(PAY_SCHEME_ID) payschmid from (
			select tpep.EMPLOYEEID,eb_no,tpep.PAY_SCHEME_ID   from tbl_pay_employee_payscheme tpep
			left join worker_master wm on wm.eb_id=tpep.EMPLOYEEID and wm.company_id =2
			where tpep.STATUS =1 and wm.eb_no in ($ebnos)
			) g
			";

	//		echo $sqlp;
        $query = $this->db->query($sqlp);
	    $data=$query->result();
		$count = count($data);
		$payschmid = isset($data[0]->payschmid) ? $data[0]->payschmid : null;

// if count is 0 OR greater than 1 -> stop/return/exit
if ($count === 0 || $count > 1 || $payschmid <> $payschm) {
    // Option: simple return (stop function)

		$pscm='';
		foreach ($data as $record) {
					$pscm=$pscm.$record->payschmid.",";
			}	
			$pscm=  substr($pscm, 0, -1);
			$wrebs='';
			$sql="select tpep.EMPLOYEEID,eb_no,tpep.PAY_SCHEME_ID   from tbl_pay_employee_payscheme tpep
			left join worker_master wm on wm.eb_id=tpep.EMPLOYEEID and wm.company_id =2
			where tpep.STATUS =1 and wm.eb_no in ($ebnos) and PAY_SCHEME_ID not in ($payschm)";
//			echo $sql;
	        $query = $this->db->query($sql);
		    $data=$query->result();
			foreach ($data as $record) {
					$wrebs=$wrebs.'('.$record->eb_no.",".$record->PAY_SCHEME_ID."),";
			}	
			$wrebs=  substr($wrebs, 0, -1);



			//echo $pscm;
 			$response = array(
		'success' => False,
		'savedata'=> 'Not saved',
		'payschms'=>$wrebs
	);
	


	echo json_encode($response);
              return;


}


					$sqlu = "
						UPDATE tbl_pay_components_custom tpcc
						LEFT JOIN worker_master wm
						ON wm.eb_id = tpcc.employeeid
						SET tpcc.status = 0
						WHERE tpcc.from_date = '{$periodfromdate}'
						AND tpcc.to_date   = '{$periodtodate}'
						AND wm.eb_no IN ({$ebnos})
						";
						$this->db->query($sqlu);


// assume $sheetData is already populated, $periodfromdate and $periodtodate set
//$componentIds = [178,179,102,166,268,269,135,284,285,196,214];


$sql = "SELECT  component_id FROM EMPMILL12.tbl_pay_custom_input_link tpcil where payscheme_id=$payschm order by linked_formula_id";
$query = $this->db->query($sql);
$result = $query->result_array();

//echo $sql;
$componentIds = array_map('intval', array_column($result, 'component_id'));

$query = $this->db->query($sql);
$result = $query->result();

$cmpnid='';

//var_dump($result);
foreach ($result as $record) {

				$cmpnid=$cmpnid.$record->component_id.",";

			//	echo $record->component_id;
			}	
			$cmpnid=  substr($cmpnid, 0, -1);

//echo 'compo id='.$cmpnid;
//echo date("d-m-Y H:i:s").'</>';
//echo date("h:i A");
//echo date("Y-m-d");


$totalEmployees = 0;
$totalComponentRecords = 0;   // total component rows attempted
$totalSuccessRecords = 0;     // approx inserted/updated
$totalFailedRecords = 0;
$failedEbnos = [];

$createdBy = $this->session->userdata('userId') ?? 26577;
//$userid     =$this->session->userdata('userid');

$createdDate = date('Y-m-d');            // use this instead of DB CURDATE() for clarity
$fromDateEsc = $this->db->escape($periodfromdate);
$toDateEsc   = $this->db->escape($periodtodate);

// cache map eb_no => eb_id to avoid repeated DB lookups
$wmCache = [];

for ($i = 1; $i < count($sheetData); $i++) { // skipping header row
    $ebno = isset($sheetData[$i][0]) ? trim($sheetData[$i][0]) : '';

    if ($ebno === '') {
        $failedEbnos[] = "Row {$i}: empty EBNO";
        $totalFailedRecords++;
        continue;
    }

    // get eb_id from cache or DB
    if (array_key_exists($ebno, $wmCache)) {
        $eb_id = $wmCache[$ebno];
    } else {
        $wmRow = $this->db->select('eb_id')->from('worker_master')->where('eb_no', $ebno)->get()->row();
        if (!$wmRow) {
            $wmCache[$ebno] = null;
            $failedEbnos[] = "EBNO not found: {$ebno} (row {$i})";
            $totalFailedRecords++;
            continue;
        }
        $eb_id = (int)$wmRow->eb_id;
        $wmCache[$ebno] = $eb_id;
    }
    $totalEmployees++;

    // build VALUES tuples for this employee's components
    $valuesTuples = [];
    $n = 3; // starting column index as per your sheet layout

    foreach ($componentIds as $compId) {
        $rawVal = isset($sheetData[$i][$n]) ? $sheetData[$i][$n] : '';
        $rawVal = str_replace(',', '', $rawVal);          // remove thousand separators
        $val = ($rawVal === '' || $rawVal === null) ? 0 : (float)$rawVal;

        // ensure numeric types properly formatted for SQL (no quotes)
        $cid = (int)$compId;
        $val_sql = (is_numeric($val) ? $val : 0);

        // build tuple: (COMPONENT_ID, `VALUE`, EMPLOYEEID, `STATUS`, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
        // escape dates/ints properly - use escape for strings; dates we already validated
        $valuesTuples[] = "("
            . $cid . ", "
            . $this->db->escape($val_sql) . ", "
            . $this->db->escape($eb_id) . ", "
            . "1, "                          // STATUS
            . $this->db->escape($createdBy) . ", "
            . $this->db->escape($createdDate) . ", "
            . $fromDateEsc . ", "
            . $toDateEsc
            . ")";
        $n++;
    }

    if (empty($valuesTuples)) {
        $failedEbnos[] = "No component values for EBNO {$ebno} (row {$i})";
        $totalFailedRecords++;
        continue;
    }

    $totalComponentRecords += count($valuesTuples);

    // Build single multi-row insert with ON DUPLICATE KEY UPDATE
    // IMPORTANT: ensure you have a UNIQUE key (EMPLOYEEID, COMPONENT_ID, FROM_DATE, TO_DATE) for ON DUPLICATE to work
    $sql = "INSERT INTO tbl_pay_components_custom
            (COMPONENT_ID, `VALUE`, EMPLOYEEID, `STATUS`, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
            VALUES " . implode(", ", $valuesTuples)
         . " ON DUPLICATE KEY UPDATE
            `VALUE` = VALUES(`VALUE`),
            `STATUS` = VALUES(`STATUS`),
            CREATED_BY = VALUES(CREATED_BY),
            CREATED_DATE = VALUES(CREATED_DATE)";

    // transaction per employee (keeps partial success isolated)
    $this->db->trans_start();
    $this->db->query($sql);
    $this->db->trans_complete();

    if ($this->db->trans_status() === FALSE) {
        // failure for this employee
        $totalFailedRecords += count($valuesTuples);
        $failedEbnos[] = "DB error for EBNO {$ebno} (row {$i}): " . $this->db->last_query();
        // continue to next employee
        continue;
    } else {
        // success — affected_rows can be ambiguous with ON DUPLICATE KEY,
        // so we'll approximate success count = number of tuples for this employee
        $totalSuccessRecords += count($valuesTuples);
    }

} // end for each sheet row


//echo date("d-m-Y H:i:s").'</br>';
//echo date("h:i A");
//echo date("Y-m-d");

		$sql="select tpep.*,empid,theod.emp_code,thepd.is_active,thepd.first_name  from tbl_pay_employee_payscheme tpep
		left join (select distinct(employeeid) empid from tbl_pay_components_custom tpcc where
		tpcc.STATUS =1 
		and tpcc.from_date='$periodfromdate' and tpcc.TO_DATE='$periodtodate' ) tpcc
		on tpep.EMPLOYEEID =tpcc.empid 
		left join tbl_hrms_ed_personal_details thepd on thepd.eb_id=tpep.EMPLOYEEID and thepd.is_active =1
		left join  tbl_hrms_ed_official_details theod on thepd.eb_id=theod.eb_id and theod.is_active =1
		where tpep.STATUS =1 and tpep.PAY_SCHEME_ID =$payschm and thepd.is_active =1 and empid is null
		order by theod.emp_code";
		
		$query = $this->db->query($sql);
		$data=$query->result(); 
		$ebidrs='';
		$ebmissing=0;
		foreach  ($data as $record) {
					$ebidrs=$ebidrs.$record->EMPLOYEEID.",";
					$totalEmployees++;
					$ebmissing++;
		//			echo $ebidrs;

				}	
			$ebidrs=  substr($ebidrs, 0, -1);

		//	echo 'extra --'.$ebmissing;
		if 	($ebmissing>0) {
    		$sqlu="insert into tbl_pay_components_custom (COMPONENT_ID,VALUE,EMPLOYEEID,STATUS,CREATED_BY,CREATED_DATE,
	    	FROM_DATE,TO_DATE)
		    select tpes.COMPONENT_ID,0 value,tpep.EMPLOYEEID,1 status,26577 created_by,CURDATE() as CREATED_DATE,
		    '$periodfromdate' fromdate,'$periodtodate' 
		    todate   from tbl_pay_employee_payscheme tpep 
		    left join tbl_pay_employee_structure tpes on tpep.EMPLOYEEID =tpes.EMPLOYEEID and tpes.STATUS =1
		    where tpep.EMPLOYEEID in ($ebidrs) and tpep.status=1 and tpes.COMPONENT_ID in ($cmpnid)
		";	

	//	echo $cmpnid.'</br>';
	//	echo $sqlu;

		//echo date("d-m-Y H:i:s").'</br>';
//echo date("h:i A");
//echo date("Y-m-d");

//		echo $sqlu.'</br>';;	
//		echo $totalEmployees.'</br>';;
//		echo $ebmissing.'</br>';;			

		$query = $this->db->query($sqlu);
		}
//echo date("d-m-Y H:i:s").'</br>';
//echo date("h:i A");
//echo date("Y-m-d");
 
	
	


// summary message
			$allupdt= $totalEmployees;
			$ebmissing=$ebmissing;
			$response = array(
				'success' => true,
				'savedata'=> $allupdt,
                'ebmissing' => $ebmissing,
			);
			
/* 
$response = [
    'success' => true,
    'employees_processed' => $totalEmployees,
    'component_records_total_attempted' => $totalComponentRecords,
    'component_records_success_approx' => $totalSuccessRecords,
    'component_records_failed' => $totalFailedRecords,
    'errors_sample' => array_slice($failedEbnos, 0, 20) // first 20 errors if any
]; */


				echo json_encode($response);

// output JSON or echo as you prefer
//header('Content-Type: application/json');
//echo json_encode($message, JSON_PRETTY_PRINT);
//return;


	}
}
}

	public function payrollexlupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
	$fileupload =  $this->input->post('fileupload');
 	$comp = $this->session->userdata('companyId');

	
	  
	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
//	 $config['allowed_types'] = 'xls|xlsx';

	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

/* 	if (!$this->upload->do_upload('fileupload')) {
        $error = $this->upload->display_errors();
        $this->session->set_flashdata('error', 'Upload failed: ' . $error);
        redirect('your_controller/your_view_page');   // change as needed
        return;
    }
 */



	 $file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 
	 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 
	 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 
	 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//echo 'ahahah'.$fileupload;
     if (!$this->upload->do_upload('fileupload')) {
		$error = array('error' => $this->upload->display_errors());
		echo $error['error'];
	} else {
		$data = $this->upload->data();

		// Ensure PhpSpreadsheet is included

		$file_mimes = array(
			'text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream',
			'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv',
			'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
		);

		// Get the file extension
		$arr_file = explode('.', $data['file_name']);
		$extension = end($arr_file);

		// Determine the appropriate reader based on the file extension
		if ('csv' == $extension) {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
		} else {
			$reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
		}

	//	$highestRow    = $sheet->getHighestRow();      // e.g. 100
	//	$highestColumn = $sheet->getHighestColumn();   // e.g. 'F'
		$highestColIdx = Coordinate::columnIndexFromString($highestColumn); // e.g. 6


		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
		$highestColIdx = Coordinate::columnIndexFromString($highestColumn); // e.g. 6
		echo 'hrow='.$highestRow.' hcol'.$highestColumn ;

		        $sql="update ATTENEMP.items set price=$highestRow where id=1";
		    $query = $this->db->query($sql);            
		$sql="update ATTENEMP.items set price=$highestColumn where id=2";
				
        $query = $this->db->query($sql);            



  
	}
}

public function process_csv()
{
    $this->load->library('session');
    $this->load->helper(array('form', 'url'));

    // 1) Read parameters
    $periodfromdate = $this->input->post('periodfromdate'); // string date
    $periodtodate   = $this->input->post('periodtodate');   // string date
    $comp           =  $this->session->userdata('companyId'); // int
    $payschm        =  $this->input->post('att_payschm');     // int

//	echo $payschm;


    // 2) HANDLE FILE UPLOAD (field name must match your form input name)
    // <input type="file" name="fileupload">
    $config['upload_path']   = FCPATH . 'uploads/'; // make sure folder exists & writable
    $config['allowed_types'] = 'csv';
    $config['max_size']      = 10240; // 10 MB

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('fileupload')) {   // <-- not 'csv_file'
        $error = $this->upload->display_errors();
        echo json_encode([
            'success' => false,
            'message' => 'File upload failed',
            'error'   => $error
        ]);
        return;
    }

    $uploadData = $this->upload->data();
    $csvPath    = $uploadData['full_path'];   // Full server path to CSV file

    // 3) PREPARE COMMAND TO CALL PYTHON
    // Use full Python path if needed
    // $pythonPath = 'C:\\Python311\\python.exe';
    $pythonPath = 'python';

    // Your script path
    $scriptPath = 'D:\\pyproj\\pytst\\process_csv.py';

    $excfilename="process_csv.py";
    $python     = $this->config->item('python_bin', 'python');
    $scriptPath = $this->config->item('python_script_cntex', 'python');
    $scriptPath = FCPATH . "Python\\".$excfilename;



    // Build command with your real parameters
    $cmd = $pythonPath . ' ' .
           escapeshellarg($scriptPath) . ' ' .
           escapeshellarg($csvPath) . ' ' .
           escapeshellarg($periodfromdate) . ' ' .
           escapeshellarg($periodtodate) . ' ' .
           escapeshellarg($comp) . ' ' .
           escapeshellarg($payschm);

    // 4) EXECUTE PYTHON SCRIPT
    $output = shell_exec($cmd . ' 2>&1');

    if ($output === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to execute Python script'
        ]);
        return;
    }

    // 5) PARSE PYTHON RESPONSE (must be JSON)
    $result = json_decode($output, true);

    if ($result === null) {
        echo json_encode([
            'success'    => false,
            'message'    => 'Invalid JSON returned from Python',
            'raw_output' => $output
        ]);
        return;
    }

    // 6) SEND FINAL RESPONSE
    $response = array(
        'success'   => !empty($result['success']),
        'savedata'  => isset($result['savedata'])  ? $result['savedata']  : [],
        'ebmissing' => isset($result['ebmissing']) ? $result['ebmissing'] : [],
        'message'   => isset($result['message'])   ? $result['message']   : ''
    );

    echo json_encode($response);
}


     // URL example:
    // /report/esiacreportpy?periodfromdate=2025-11-01&ebno=E001
    public function esiacreportpy()
    {
        $periodfromdate = $this->input->get('periodfromdate'); // yyyy-mm-dd
        $ebno           = $this->input->get('ebno');

        if (empty($periodfromdate) || empty($ebno)) {
            show_error('Missing periodfromdate or ebno', 400);
            return;
        }

        // Folder where Python will save Excel/PDF
        $outputDir = FCPATH . 'downloads/';
        if (!is_dir($outputDir)) {
            mkdir($outputDir, 0777, true);
        }

        // Report file name
        $reportName = 'esiac_'
            . preg_replace('/[^A-Za-z0-9]/', '_', $ebno)
            . '_' . date('Ymd_His');

        // Python + script paths
        $pythonPath = 'python';  // or full path: 'C:\\Python311\\python.exe'
        $scriptPath =  'd:/pyproj/pytst/nn1.py';

        $excfilename="nn1.py";
        $python     = $this->config->item('python_bin', 'python');
        $scriptPath = $this->config->item('python_script_cntex', 'python');
        $scriptPath = FCPATH . "Python\\".$excfilename;

        // Build command: python esiac_report.py <output_dir> <report_name> <periodfromdate> <ebno>
        $cmd = $pythonPath . ' '
             . escapeshellarg($scriptPath) . ' '
             . escapeshellarg($outputDir) . ' '
             . escapeshellarg($reportName) . ' '
             . escapeshellarg($periodfromdate) . ' '
             . escapeshellarg($ebno);

        // Execute and capture output (stdout + stderr)
        $output = shell_exec($cmd . " 2>&1");

        $result = json_decode($output, true);

        if (!$result || !isset($result['success']) || !$result['success']) {
            echo "<h3>Error generating report</h3>";
            echo "<pre>" . htmlspecialchars($output) . "</pre>";
            return;
        }

        $pdfPath = $result['pdf_path'];

        if (!file_exists($pdfPath)) {
            echo "PDF file not found: " . htmlspecialchars($pdfPath);
            return;
        }

        // Force PDF download
        $data = file_get_contents($pdfPath);
        $filename = basename($pdfPath);

 //       force_download($filename, $data); // sends headers + file & exits
 
$filesize = filesize($pdfPath);
    $filename = basename($pdfPath);

    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . $filesize);

    ob_clean();
    flush();
    readfile($pdfPath);
    unlink($pdfPath);   // 👈 delete after sending
    exit;


    }


public function updatesalcomp()
{
    $this->output->set_content_type('application/json');

    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate   = $this->input->post('periodtodate');
    $att_payschm    = $this->input->post('att_payschm');

    if (empty($periodfromdate) || empty($periodtodate) || empty($att_payschm)) {
        echo json_encode([
            'success' => false,
            'message' => 'Missing parameters'
        ]);
        return;
    }

    // Check file
    if (empty($_FILES['fileupload']['name'])) {
        echo json_encode([
            'success' => false,
            'message' => 'No file uploaded'
        ]);
        return;
    }

    // Upload CSV to temp folder
    $upload_path = FCPATH . 'uploads/njm_csv/';
    if (!is_dir($upload_path)) {
        mkdir($upload_path, 0777, true);
    }

    $config['upload_path']   = $upload_path;
    $config['allowed_types'] = 'csv';
    $config['max_size']      = 10240; // 10 MB

    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('fileupload')) {
        echo json_encode([
            'success' => false,
            'message' => $this->upload->display_errors('', '')
        ]);
        return;
    }

    $upload_data = $this->upload->data();
    $csv_path    = $upload_data['full_path'];

    // Build Python command
    $pythonPath = 'python'; // or full path to python.exe
    $scriptPath = 'd:/pyproj/pytst/csvinsert.py';
    
    $excfilename="csvinsert.py";
    $python     = $this->config->item('python_bin', 'python');
    $scriptPath = FCPATH . "Python\\".$excfilename;
 


    $cmd = $pythonPath . ' '
         . escapeshellarg($scriptPath) . ' '
         . escapeshellarg($csv_path) . ' '
         . escapeshellarg($periodfromdate) . ' '
         . escapeshellarg($periodtodate) . ' '
         . escapeshellarg($att_payschm);

    $output = shell_exec($cmd . ' 2>&1');

    // Remove temp file after processing
    if (file_exists($csv_path)) {
        unlink($csv_path);
    }

    $result = json_decode($output, true);

    if (!$result || !isset($result['success']) || !$result['success']) {
        echo json_encode([
            'success' => false,
            'message' => $result['message'] ?? 'Unknown error',
            'raw_output' => $output
        ]);
        return;
    }

    echo json_encode([
        'success'     => true,
        'message'     => $result['message'] ?? 'Updated successfully',
        'total_rows'  => $result['total_rows'] ?? 0
    ]);
}




public function donwjuteReport1() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
        $periodtodate= $this->input->get('periodtodate');
        
		$sdate=str_replace('-','',$periodfromdate);
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
	$result = $this->Ejmallprocessdata->donwjuteReport1($periodfromdate,$periodtodate);

   // var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

// (Optional) Fit sheet to A4 paper
$sheet->getPageSetup()
      ->setPaperSize(PageSetup::PAPERSIZE_A4);

// (Optional) Fit to one page wide
$sheet->getPageSetup()
      ->setFitToWidth(1)
      ->setFitToHeight(0);

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 10,
							],
						];
 

$sheet->setCellValue('A1', 'STOCK REGISTER FOR THE PERIOD '.$periodfromdate.' TO '.$periodtodate);
//$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':M'.$N;
$sheet->mergeCells($b);


$rowNumber = 4;

$tpay=0;

$columnNames = array_keys($result[0]);
//echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'2', $column);
        $col++;
    }


  $spreadsheet->getDefaultStyle()->getFont()->setSize(10);



$rowNumber = 3;

$mrno='';
$cmrno='';
foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
       $n=0;
        if ($mrno==$row['CO_MR_No']) {
            $n=1; 
        }
        else {
            $n=0;
            $mrno=$row['CO_MR_No'];
        }     
echo 'mr '.$mrno,'-'.'row '.$n.$row['CO_MR_No']; 
    foreach ($row as $cell) {
           
        if ($n==1) {
            if ($colno<=5) {
                $cell='';
            }   
        }
        if ($colno <= 4) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }
//$sheet->getStyle($cell)->getFont()->setSize(10);

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='k'.$rowNumber;

$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'M') as $col) {
   //     $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(15);
						 $sheet->getColumnDimension('B')->setWidth(18);
						 $sheet->getColumnDimension('C')->setWidth(15);
						$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('i')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
    
						$centerAlignment = $sheet->getStyle('A1:A1')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$rn='A2:M'.$rowNumber;

                            $centerAlignment = $sheet->getStyle($rn)->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);

$rn='A2:M'.$rowNumber;

							$sheet->getStyle('A1:j1')->applyFromArray($borderStyle);
							$sheet->getStyle($rn)->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Agent MR Details');


	
    $filename="Agent MR Details".$sdate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}


public function downjutetallyReport1() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
        $periodtodate= $this->input->get('periodtodate');
        $comp = $this->session->userdata('companyId');        
		$sdate=str_replace('-','',$periodfromdate);
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);


    $sql="select company_code from company_master where comp_id=$comp";
    $query = $this->db->query($sql);
    $row = $query->row();
    $company_code='';
    if (isset($row)) {
        $comp_code=$row->company_code;
    }



   $result = $this->Ejmallprocessdata->downjutetallyReport1($periodfromdate,$periodtodate);

   // var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

// (Optional) Fit sheet to A4 paper
$sheet->getPageSetup()
      ->setPaperSize(PageSetup::PAPERSIZE_A4);

// (Optional) Fit to one page wide
$sheet->getPageSetup()
      ->setFitToWidth(1)
      ->setFitToHeight(0);

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 10,
							],
						];
 

//$sheet->setCellValue('A1', 'TALLY DATA FOR THE PERIOD '.$periodfromdate.' TO '.$periodtodate);
//$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':M'.$N;
//$sheet->mergeCells($b);


$rowNumber = 1;

$tpay=0;

$columnNames = array_keys($result[0]);
//echo "Column Names: " . implode(", ", $columnNames) . "<br>";
//var_dump($columnNames);
$cn=1;
$col = 'A';
    foreach ($columnNames as $column) {
        if ($col=='Y' || $col=='AI' || $col=='BD' || $col=='BG' || $col=='BH' || $col=='BK' || $col=='BN' 
        || $col=='BO' || $col=='BR' || $col=='BS') {
            $column = substr($column, 0, -2);
  
        }
        if ($cn<=73) {
            $sheet->setCellValue($col.$rowNumber, $column);
        }    
        $col++;
        $cn++;
    }


  $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

$highestColLetter = $sheet->getHighestColumn();

// Convert to column index, e.g. G=7, AA=27
$highestColIndex = Coordinate::columnIndexFromString($highestColLetter);


$rowNumber ++;

$mrno='';
$cmrno='';
$ln=0;
$aln=1;
foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
       $n=0;

  //      echo '1st vch '.$row['Vch No.'].' --mr  '.$mrno.' lne '.$aln.'</br>';
        $aln++;
        $cnt=$row['noofitems'];
        if ($mrno==$row['Vch No.']   ) {
            $n=1; 
            $ln++;           
        
        }
        else {
            $col='B';   
        
            if (strlen($mrno)>0) {

            $col='B';
            $col='A';
                $ln++;
                $cn=1;
 
                for ($i=$ln;$i<=5;$i++) {
                $col='A';
                $sheet->setCellValue($col . $rowNumber, $mr);
                $col='B';
                $sheet->setCellValue($col . $rowNumber, $vtyp);
                $col='C';
                $sheet->setCellValue($col . $rowNumber, $vdt);
                $col='D';
                $sheet->setCellValue($col . $rowNumber, $sino);
                $col='E';
                $sheet->setCellValue($col . $rowNumber, $sidt);
                $col='J';
                $sheet->setCellValue($col . $rowNumber, $sname);
                $col='S';
                $sheet->setCellValue($col . $rowNumber, $pledger);
                $col='AA';
                $sheet->setCellValue($col . $rowNumber, $godown);
                $col='AB';
                $sheet->setCellValue($col . $rowNumber, $batch);
                $col='AR';
                $sheet->setCellValue($col . $rowNumber, $refno);
                $col='AS';
                $sheet->setCellValue($col . $rowNumber, $duon);
                if ($tdsamt<>0 and $cn>0) {
                    $col='AJ';
                    $sheet->setCellValue($col . $rowNumber, $tdsnar);
                    $col='AL';
                    $sheet->setCellValue($col . $rowNumber, $tdsamt);
                    $cn=0;
                }
 



                $col++; 
                $rowNumber++;
            }
    }

            $n=0;
            $mrno=$row['Vch No.'];
            $ln=0;
        }     
//    echo 'mr '.$mrno,'-'.'row '.$n.' '.$row['Vch No.'].' line '.$ln.'</br>';   ; 
        $mr=$row['Vch No.'];
        $vtyp=$row['Vch Type'];
        $vdt=$row['Date'];
        $sino=$row['Supplier Inv No'];
        $sidt=$row['Supplier Inv Date'];
        $sname=$row['Party Name'];
        $pledger=$row['Purchase Ledger '];
        $godown=$row['Godown'];
        $batch=$row['Batch'];
        $refno=$row['Ref: No.'];
        $duon=$row['Due on'];
        $tdsamt=$row['tds Amount'];
        $tdsnar='TDS ON PURCHASE OF GOODS (194Q)';
echo 'tds amt '.$tdsamt.'</br>';
//Vch No.	Vch Type	Date	Supplier Inv No	Supplier Inv Date	Party Name	Purchase Ledger 	Godown	Batch	Ref: No.	Due on
//A	B	C	D	E	J	S	AA	AB	AR	AS

        $coln=1;
        $cn=1;
        $col='A';    
    foreach ($row as $cell) {
        if ($n==1) {
            if ($colno<=5) {
//                $cell='';
            }   
        }
        if ($ln>0) {
            if ($colno==71) {
                $cell='';
            }   
        }
            if ($colno>=73) {
                $cell='';
            }   
        
        
        $sheet->setCellValue($col . $rowNumber, $cell);
//            $tpay=$tpay+$row->NET_PAY;
        
    $sheet->getColumnDimension($col)->setAutoSize(true);



        $col++;     // move outside
        $colno++;   // move outside
        
    }


      $rowNumber++;

}
        $ln++;
           for ($i=$ln;$i<=5;$i++) {
                $col='A';
                $sheet->setCellValue($col . $rowNumber, $mr);
                $col='B';
                $sheet->setCellValue($col . $rowNumber, $vtyp);
                $col='C';
                $sheet->setCellValue($col . $rowNumber, $vdt);
                $col='D';
                $sheet->setCellValue($col . $rowNumber, $sino);
                $col='E';
                $sheet->setCellValue($col . $rowNumber, $sidt);
                $col='J';
                $sheet->setCellValue($col . $rowNumber, $sname);
                $col='S';
                $sheet->setCellValue($col . $rowNumber, $pledger);
                $col='AA';
                $sheet->setCellValue($col . $rowNumber, $godown);
                $col='AB';
                $sheet->setCellValue($col . $rowNumber, $batch);
                $col='AR';
                $sheet->setCellValue($col . $rowNumber, $refno);
                $col='AS';
                $sheet->setCellValue($col . $rowNumber, $duon);
                if ($tdsamt<>0 and $cn>0) {
                    $col='AJ';
                    $sheet->setCellValue($col . $rowNumber, $tdsnar);
                    $col='AL';
                    $sheet->setCellValue($col . $rowNumber, $tdsamt);
                    $cn=0;
                }




                $col++; 
                $rowNumber++;
            }
 
$rn='k'.$rowNumber;

//$sheet->setCellValue($rn, $tpay);


$sheet->getStyle(
    'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow()
)->getFont()->setName('Calibri')->setSize(11);

 
/*  
						 $sheet->getColumnDimension('A')->setWidth(15);
						 $sheet->getColumnDimension('B')->setWidth(18);
						 $sheet->getColumnDimension('C')->setWidth(15);
						$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('i')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
 */    
/* 						    $centerAlignment = $sheet->getStyle('A1:A1')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$rn='A2:'.$highestColLetter.$rowNumber;

                            $centerAlignment = $sheet->getStyle($rn)->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);

$rn='A2:'.$highestColLetter.$rowNumber;

							$sheet->getStyle('A1:j1')->applyFromArray($borderStyle);
							$sheet->getStyle($rn)->applyFromArray($borderStyle);
 */	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$shname='Purchase';
$sheet->setTitle($shname);


$resultchk = $this->Ejmallprocessdata->downjutetallycheck($periodfromdate,$periodtodate);
$rowNo = 1;
$colNo = 1;


$checklistSheet = new Worksheet($spreadsheet, 'Checklist');
$spreadsheet->addSheet($checklistSheet);

// Make Checklist active (optional)
$spreadsheet->setActiveSheetIndex(1);

// IMPORTANT: point $sheet to Checklist sheet
$sheet = $spreadsheet->getActiveSheet();     // now $sheet = Checklist
$sheet->setTitle('Check List');              
$headers = array_keys($resultchk[0]);

$rowNo = 1;
$colNo = 1;
foreach ($headers as $h) {
    $cell = Coordinate::stringFromColumnIndex($colNo) . $rowNo;
    $sheet->setCellValue($cell, $h);
    $colNo++;
}

//use PhpOffice\PhpSpreadsheet\Style\Fill;
$rowNo = 2;

foreach ($resultchk as $r) {
    $colNo = 1;

    foreach ($headers as $h) {
        $cell = Coordinate::stringFromColumnIndex($colNo) . $rowNo;
        $val  = (string)($r[$h] ?? '');

        $sheet->setCellValueExplicit($cell, $val, DataType::TYPE_STRING);
        if (!isset($val) || trim((string)$val) === '') {
        // empty or null
          $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFC7CE'); // light red
        }


        // ✅ condition: color this cell only
/*         if ($val === 'TALLYIMPORTSTATUS') {
            if (strtoupper(trim($val)) === 'ERROR') {
                $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFC7CE'); // light red
            } elseif (strtoupper(trim($val)) === 'OK') {
                $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFC6EFCE'); // light green
            }
        }
 */
        $colNo++;
    }

    $rowNo++;
}
$highestCol = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();

// Header bold
$sheet->getStyle("A1:{$highestCol}1")->getFont()->setBold(true);

// Borders
$sheet->getStyle("A1:{$highestCol}{$highestRow}")
      ->getBorders()->getAllBorders()
      ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Freeze header
$sheet->freezePane('A2');

// Auto width
for ($c = 1; $c <= Coordinate::columnIndexFromString($highestCol); $c++) {
    $sheet->getColumnDimension(
        Coordinate::stringFromColumnIndex($c)
    )->setAutoSize(true);
}
$sheet->calculateColumnWidths();




	
$filename="Tally Data_".$comp_code."_".$periodfromdate.' '.$periodtodate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}


public function downjutetallysalesReport1() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
        $periodtodate= $this->input->get('periodtodate');
        $comp = $this->session->userdata('companyId');        
		$sdate=str_replace('-','',$periodfromdate);
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);


    $sql="select company_code from company_master where comp_id=$comp";
   echo $sql;
    $query = $this->db->query($sql);
    $row = $query->row();
    $company_code='';
    if (isset($row)) {
        $comp_code=$row->company_code;
    }



   $result = $this->Ejmallprocessdata->downjutetallysalesReport1($periodfromdate,$periodtodate);

   // var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

// (Optional) Fit sheet to A4 paper
$sheet->getPageSetup()
      ->setPaperSize(PageSetup::PAPERSIZE_A4);

// (Optional) Fit to one page wide
$sheet->getPageSetup()
      ->setFitToWidth(1)
      ->setFitToHeight(0);

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 10,
							],
						];
 

//$sheet->setCellValue('A1', 'TALLY DATA FOR THE PERIOD '.$periodfromdate.' TO '.$periodtodate);
//$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':M'.$N;
//$sheet->mergeCells($b);


$rowNumber = 1;

$tpay=0;

$columnNames = array_keys($result[0]);
//echo "Column Names: " . implode(", ", $columnNames) . "<br>";
//var_dump($columnNames);
$cn=1;
$col = 'A';
    foreach ($columnNames as $column) {
        if ($cn<=51) {
            $sheet->setCellValue($col.$rowNumber, $column);
        }    
        $col++;
        $cn++;
    }


  $spreadsheet->getDefaultStyle()->getFont()->setSize(10);

$highestColLetter = $sheet->getHighestColumn();

// Convert to column index, e.g. G=7, AA=27
$highestColIndex = Coordinate::columnIndexFromString($highestColLetter);


$rowNumber ++;

$mrno='';
$cmrno='';
$ln=0;
$aln=1;
foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
    $mr = $row['slno'];
echo $mr;    
    foreach ($row as $cell) {
        if ($mr > 1) {
            if (($colno == 3) || ($colno == 4) || ($colno == 6)) {
                $cell = '';
            }
        }
        
        if ($colno >= 51) {
            $cell = '';
        }
        
        $sheet->setCellValue($col . $rowNumber, $cell);
        $sheet->getColumnDimension($col)->setAutoSize(true);
        
        $col++;     // increment at end of inner loop
        $colno++;   // increment at end of inner loop
    }
    
    $rowNumber++;    // increment row after processing each result row
}

$rn = 'k' . $rowNumber;

//$sheet->setCellValue($rn, $tpay);


$sheet->getStyle(
    'A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow()
)->getFont()->setName('Calibri')->setSize(11);

 
/*  
						 $sheet->getColumnDimension('A')->setWidth(15);
						 $sheet->getColumnDimension('B')->setWidth(18);
						 $sheet->getColumnDimension('C')->setWidth(15);
						$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('i')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
 */    
/* 						    $centerAlignment = $sheet->getStyle('A1:A1')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$rn='A2:'.$highestColLetter.$rowNumber;

                            $centerAlignment = $sheet->getStyle($rn)->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);

$rn='A2:'.$highestColLetter.$rowNumber;

							$sheet->getStyle('A1:j1')->applyFromArray($borderStyle);
							$sheet->getStyle($rn)->applyFromArray($borderStyle);
 */	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$shname='Sales';
$sheet->setTitle($shname);


$resultchk = $this->Ejmallprocessdata->downjutetallycheck($periodfromdate,$periodtodate);
$rowNo = 1;
$colNo = 1;


$checklistSheet = new Worksheet($spreadsheet, 'Checklist');
$spreadsheet->addSheet($checklistSheet);

// Make Checklist active (optional)
$spreadsheet->setActiveSheetIndex(1);

// IMPORTANT: point $sheet to Checklist sheet
$sheet = $spreadsheet->getActiveSheet();     // now $sheet = Checklist
$sheet->setTitle('Check List');              
$headers = array_keys($resultchk[0]);

$rowNo = 1;
$colNo = 1;
foreach ($headers as $h) {
    $cell = Coordinate::stringFromColumnIndex($colNo) . $rowNo;
    $sheet->setCellValue($cell, $h);
    $colNo++;
}

//use PhpOffice\PhpSpreadsheet\Style\Fill;
$rowNo = 2;

foreach ($resultchk as $r) {
    $colNo = 1;

    foreach ($headers as $h) {
        $cell = Coordinate::stringFromColumnIndex($colNo) . $rowNo;
        $val  = (string)($r[$h] ?? '');

        $sheet->setCellValueExplicit($cell, $val, DataType::TYPE_STRING);
        if (!isset($val) || trim((string)$val) === '') {
        // empty or null
          $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFC7CE'); // light red
        }


        // ✅ condition: color this cell only
/*         if ($val === 'TALLYIMPORTSTATUS') {
            if (strtoupper(trim($val)) === 'ERROR') {
                $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFFFC7CE'); // light red
            } elseif (strtoupper(trim($val)) === 'OK') {
                $sheet->getStyle($cell)->getFill()->setFillType(Fill::FILL_SOLID)
                      ->getStartColor()->setARGB('FFC6EFCE'); // light green
            }
        }
 */
        $colNo++;
    }

    $rowNo++;
}
$highestCol = $sheet->getHighestColumn();
$highestRow = $sheet->getHighestRow();

// Header bold
$sheet->getStyle("A1:{$highestCol}1")->getFont()->setBold(true);

// Borders
$sheet->getStyle("A1:{$highestCol}{$highestRow}")
      ->getBorders()->getAllBorders()
      ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

// Freeze header
$sheet->freezePane('A2');

// Auto width
for ($c = 1; $c <= Coordinate::columnIndexFromString($highestCol); $c++) {
    $sheet->getColumnDimension(
        Coordinate::stringFromColumnIndex($c)
    )->setAutoSize(true);
}
$sheet->calculateColumnWidths();




	
$filename="Tally Data_".$comp_code."_".$periodfromdate.' '.$periodtodate.'.xlsx';

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}



public function cntexlupload_py()
{
    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate   = $this->input->post('periodtodate');
    $payschm        = (int)$this->input->post('att_payschm');

    // upload file first (same as you already do)
    $config['upload_path']   = './uploads/';
    $config['allowed_types'] = 'csv';
    $this->load->library('upload', $config);

    if (!$this->upload->do_upload('fileupload')) {
        echo json_encode(['success'=>false,'reason'=>strip_tags($this->upload->display_errors())]);
        return;
    }

    $up      = $this->upload->data();
    $csvPath = $up['full_path'];

    $company_id = (int)($this->session->userdata('companyId') ?? 2);
    $created_by = (int)($this->session->userdata('userId') ?? 26577);

    // Build JSON payload to send to python
    $payload = json_encode([
        "csv_path"       => $csvPath,
        "periodfromdate" => $periodfromdate,
        "periodtodate"   => $periodtodate,
        "att_payschm"    => $payschm,
        "company_id"     => $company_id,
        "created_by"     => $created_by
    ]);


  //  alert(JSON.stringify(res.payload, null, 2));

//    $pyScript = FCPATH . "py/process_cntexupload.py";


$this->config->load('python', TRUE);

//$python     = "python"; // or full path to python.exe
//$scriptPath = 'D:\\pyproj\\pytst\\process_cntexlupload.py';

//$python     = $this->config->item('python_bin', 'python');
//$scriptPath = $this->config->item('python_script_cntex', 'python');

    $excfilename="process_cntexlupload.py";
    $python     = $this->config->item('python_bin', 'python');
    $scriptPath = FCPATH . "Python\\".$excfilename;


//================================================================
/*     $excfilename="process_cntexlupload.py";
    $python     = $this->config->item('python_bin', 'python');
    //$scriptPath = $this->config->item('python_script_cntex', 'python');
    $scriptPath = FCPATH . "Python\\".$excfilename;



    $cmd = $pythonPath . ' '
         . escapeshellarg($scriptPath) . ' '
         . escapeshellarg($csv_path) . ' '
         . escapeshellarg($periodfromdate) . ' '
         . escapeshellarg($periodtodate) . ' '
         . escapeshellarg($att_payschm).' '
         . escapeshellarg($company_id).' '
         . escapeshellarg($created_by)
         ;

 



       $output = shell_exec($cmd . ' 2>&1');

    if ($output === null) {
        echo json_encode([
            'success' => false,
            'message' => 'Failed to execute Python script'
        ]);
        return;
    }

    // 5) PARSE PYTHON RESPONSE (must be JSON)
    $result = json_decode($output, true);

    if ($result === null) {
        echo json_encode([
            'success'    => false,
            'message'    => 'Invalid JSON returned from Python',
            'raw_output' => $output
        ]);
        return;
    }

    // 6) SEND FINAL RESPONSE
    $response = array(
        'success'   => !empty($result['success']),
        'savedata'  => isset($result['savedata'])  ? $result['savedata']  : [],
        'ebmissing' => isset($result['ebmissing']) ? $result['ebmissing'] : [],
        'message'   => isset($result['message'])   ? $result['message']   : ''
    );

    echo json_encode($response);

 */


//===============================================================





 $payload = json_encode([
    "csv_path"       => $csvPath,
    "periodfromdate" => $periodfromdate,
    "periodtodate"   => $periodtodate,
    "att_payschm"    => (int)$payschm,
    "company_id"     => (int)$company_id,
    "created_by"     => (int)$created_by
], JSON_UNESCAPED_SLASHES);

$cmd = "\"$python\" \"$scriptPath\"";

$descriptorspec = [
    0 => ["pipe", "r"], // stdin
    1 => ["pipe", "w"], // stdout
    2 => ["pipe", "w"], // stderr
];

$process = proc_open($cmd, $descriptorspec, $pipes);

if (!is_resource($process)) {
    echo json_encode(["success"=>false, "reason"=>"Unable to start python"]);
    return;
}

fwrite($pipes[0], $payload);
fclose($pipes[0]);

$stdout = stream_get_contents($pipes[1]);
fclose($pipes[1]);

//echo $stdout;

$stderr = stream_get_contents($pipes[2]);
fclose($pipes[2]);

$exitCode = proc_close($process);

$resp = json_decode($stdout, true);
//var_dump($resp);
if (!is_array($resp)) {
    echo json_encode([
        "success" => false,
        "reason"  => "Python error / invalid JSON",
        "exitCode"=> $exitCode,
        "stdout"  => $stdout,
        "stderr"  => $stderr
    ]);
    return;
}

echo json_encode($resp);
return;
 
}


public function downjutetallysalesReport1py()
{
 		$periodfromdate= $this->input->get('periodfromdate');
        $periodtodate= $this->input->get('periodtodate');
   //$companyid      = (int)$this->input->post('companyid');
    $companyid = $this->session->userdata('companyId');        
//echo "from ".$periodfromdate." to ".$periodtodate." comp ".$companyid;

    if (!$periodfromdate || !$periodtodate || !$companyid) {
        echo json_encode(["success"=>false,"reason"=>"Missing fromdate/todate/companyid"]);
        return;
    }
    // where python will write the excel
    if (!is_dir($outDir)) mkdir($outDir, 0777, true);

    $payload = json_encode([
        "fromdate"   => $periodfromdate,
        "todate"     => $periodtodate,
        "companyid"  => $companyid,
        "out_dir"    => $outDir
    ], JSON_UNESCAPED_SLASHES);

 


    //$python     = "python"; // OR full path: D:\Python311\python.exe
    //$scriptPath = "D:\\pyproj\\pytst\\export_tally_sales_excel.py";
 //   $this->config->load('python', TRUE);

    $excfilename="export_tally_sales_excel.py";
    $python     = $this->config->item('python_bin', 'python');
    $scriptPath = $this->config->item('python_script_cntex', 'python');
    $scriptPath = FCPATH . "Python\\".$excfilename;
    

    $cmd = "\"$python\" \"$scriptPath\"";

    $spec = [
        0 => ["pipe","r"],
        1 => ["pipe","w"],
        2 => ["pipe","w"]
    ];


    log_message('error', 'PY CMD = ' . $cmd);

    $p = proc_open($cmd, $spec, $pipes);
    if (!is_resource($p)) {
        echo json_encode(["success"=>false,"reason"=>"Unable to start python"]);
        return;
    }

    fwrite($pipes[0], $payload);
    fclose($pipes[0]);

    $stdout = stream_get_contents($pipes[1]); fclose($pipes[1]);
    $stderr = stream_get_contents($pipes[2]); fclose($pipes[2]);
    $exitCode = proc_close($p);

    $resp = json_decode($stdout, true);
    if (!is_array($resp) || empty($resp["success"])) {
        echo json_encode([
            "success" => false,
            "reason"  => "Python error",
            "exitCode"=> $exitCode,
            "stdout"  => $stdout,
            "stderr"  => $stderr

        ]);
        return;
    }

    $filePath = $resp["file_path"];
    $dlName   = $resp["download_name"];

    if (!file_exists($filePath)) {
        echo json_encode(["success"=>false,"reason"=>"Excel not created","file"=>$filePath]);
        return;
    }

    $this->load->helper('download');
    force_download($dlName, file_get_contents($filePath));

 //   @unlink($filePath); // optional
}



public function jutevowtallylist() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
        $periodtodate= $this->input->get('periodtodate');
        
		$sdate=str_replace('-','',$periodfromdate);
   // $result = $this->Njmallwagesprocess->njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm);
	$result = $this->Ejmallprocessdata->jutevowtallylist($periodfromdate,$periodtodate);

   // var_dump($result);
//    $query = $this->Njmallwagesprocess->getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm);
    if (!$result) {
        echo "Query failed.";
        return;
    }
    
if (empty($result) || count($result) == 0) {
    echo "No data found.";
    return;
}
        $query = $result;
//    echo "Total Rows: " . $result->num_rows() . "<br>";

	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);

// (Optional) Fit sheet to A4 paper
$sheet->getPageSetup()
      ->setPaperSize(PageSetup::PAPERSIZE_A4);

// (Optional) Fit to one page wide
$sheet->getPageSetup()
      ->setFitToWidth(1)
      ->setFitToHeight(0);

						$borderStyle = [
							'borders' => [
								'allBorders' => [
									'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
								],
							],
						];
						$boldFontStyle = [
							'font' => [
								'bold' => true,
								'size' => 10,
							],
						];
 

$sheet->setCellValue('A1', 'Vow Tally Link List');
//$sheet->setCellValue('A2', 'CONTRACTOR WORKERS BANK STATEMENT');
$N=1;
$b='A'.$N.':E'.$N;
$sheet->mergeCells($b);


$rowNumber = 4;

$tpay=0;

$columnNames = array_keys($result[0]);
//echo "Column Names: " . implode(", ", $columnNames) . "<br>";
$col = 'A';
    foreach ($columnNames as $column) {
        $sheet->setCellValue($col.'2', $column);
        $col++;
    }


  $spreadsheet->getDefaultStyle()->getFont()->setSize(10);



$rowNumber = 3;

$mrno='';
$cmrno='';
foreach ($result as $row) {
    $col = 'A';
    $colno = 0;
       $n=0;
     foreach ($row as $cell) {
           
        if ($n==1) {
            if ($colno<=5) {
  //              $cell='';
            }   
        }
        if ($colno <= 4) {
            $sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);
        } else {
            $sheet->setCellValue($col . $rowNumber, $cell);
            $tpay=$tpay+$row->NET_PAY;
        }
//$sheet->getStyle($cell)->getFont()->setSize(10);

        $col++;     // move outside
        $colno++;   // move outside
    }

    $rowNumber++;
}

$rn='k'.$rowNumber;

$sheet->setCellValue($rn, $tpay);

    foreach (range('A', 'M') as $col) {
   //     $sheet->getColumnDimension($col)->setAutoSize(true);
    }

 
						 $sheet->getColumnDimension('A')->setWidth(15);
						 $sheet->getColumnDimension('B')->setWidth(18);
						 $sheet->getColumnDimension('C')->setWidth(15);
						$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(15);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(15);
$sheet->getColumnDimension('G')->setWidth(30);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('i')->setWidth(30);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(15);
$sheet->getColumnDimension('L')->setWidth(15);
$sheet->getColumnDimension('M')->setWidth(15);
    
						$centerAlignment = $sheet->getStyle('A1:A1')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$rn='A2:M'.$rowNumber;

                            $centerAlignment = $sheet->getStyle($rn)->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);

$rn='A2:M'.$rowNumber;

							$sheet->getStyle('A1:j1')->applyFromArray($borderStyle);
							$sheet->getStyle($rn)->applyFromArray($borderStyle);
	 

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Agent MR Details');


	
    $filename="Vow Tally Link Details.xlsx";

// After generating the Excel file
$excelUrl = 'path_to_generated_excel_file.xlsx'; // Change this to the actual URL

// Return the URL along with other response data
echo json_encode(array('success' => true, 'savedata' => $savedata, 'excelUrl' => $excelUrl));

	// Set headers for Excel file download
//	ob_clean();
	header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//	header('Content-Disposition: attachment;filename="your_excel_file.xlsx"');

//		header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename='.$filename);
	header('Cache-Control: max-age=0');
	ob_clean();

	// Save the Excel file to output stream
	$writer = new Xlsx($spreadsheet);
	$writer->save('php://output');
	// Save the Excel file to output stream
//	$writer = new Xlsx($spreadsheet);
//	$writer->save('php://output');
	
		// Terminate the script to prevent further output
		exit;



	}





}



