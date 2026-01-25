<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Cell\DataType;

use Mpdf\Mpdf;

class Data_entry extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		
		$this->load->model('hrms_full_attendance_model');
		$this->load->model('hrms_attendance_register_model');
		$this->load->model('hrms_attendance_summary_model');
		$this->load->model('hrms_occupation_deviation_model');
		$this->load->model('hrms_spell_wise_model');
		$this->load->model('hrms_category_summary_model');
		$this->load->model('hrms_department_sub_summary_model');
		$this->load->model('hrms_department_summary_model');
		$this->load->model('hrms_designation_summary_model');
		$this->load->model('hrms_dept_cat_summary_model');
		$this->load->model('hrms_cash_hands_report_model');
		$this->load->model('hrms_employee_bank_statement_report_model');
		$this->load->model('Attendance_checklist_Model');
		$this->load->model('Loans_advance_model');
		$this->load->model('Loan_adv_model');
		$this->load->model('Loan_adv_model2');
		

		
		
		
		ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
    }


public function workingdaydetailsexcel($perms = null) {
    // Load PhpSpreadsheet classes
   // use PhpOffice\PhpSpreadsheet\Spreadsheet;
   // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

    // Retrieve session filters
    $from_date = $_SESSION["fromdate"];
    $to_date = $_SESSION["todate"];
    $att_payschm = $_SESSION["att_payschm"];
    $holget = $_SESSION["holget"];

    // Retrieve GET parameters
    $periodfromdate = $this->input->get('periodfromdate');
    $periodtodate = $this->input->get('periodtodate');
    $att_dept = $this->input->get('att_dept');
    $att_spell = $this->input->get('att_spell');
    $holget = $this->input->get('holget');

    // Format dates
    $fdate = date('d-m-Y', strtotime($periodfromdate));
    $tdate = date('d-m-Y', strtotime($periodtodate));

    // Retrieve EB_NO list from POST
    $exportType = $this->input->post('export_type');
    $eb_no_list = $this->input->post('EB_NO');
    $eb_array = [];

    if ($exportType === 'selected' && $eb_no_list) {
        $eb_array = json_decode($eb_no_list, true);
    }

    // Fetch data from model
    $records = $this->Loan_adv_model->getebmcdata($periodfromdate, $periodtodate, $att_spell, $holget, $att_dept);
//	var_dump($records);
    // Create spreadsheet
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle("WorkingDetaikls");

    // Title
    $sheet->mergeCells('A1' . $row . ':H1' . $row);
    $sheet->getStyle($row . '1')->getFont()->setBold(true);
    $sheet->setCellValue('A1', "Workers Working Details From $fdate To $tdate");

    // Headers
    //$headers = ['Sl No', 'From Date', 'To Date', 'EB No', 'Name', 'Working Hours', 'Festival Hours', 'N.S Hours', 'Total Working Days'];
    $headers = ['Sl No', 'From Date', 'To Date', 'EB No', 'Names','Catagory', 'Department', 'Occupation', 'Regular.Hours', 'OT.Hours', 'Night.Hours', 'Working.Days','OT.Days','Leave.Days','Holidays','Mill Working Days','Absent Days','Last Working Date'];
    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '2', $header);
        $sheet->getStyle($col . '2')->getFont()->setBold(true);
        $col++;
    }

    // Data rows
    $rowNum = 3;
    $sl = 1;
    foreach ($records as $rec) {
        $sheet->setCellValue("A$rowNum", $sl++);
        $sheet->setCellValue("B$rowNum", $fdate);
        $sheet->setCellValue("C$rowNum", $tdate);
        $sheet->setCellValue("D$rowNum", $rec->emp_code);
        $sheet->setCellValue("E$rowNum", $rec->wname);
		$sheet->setCellValue("F$rowNum", $rec->cata_desc);
        $sheet->setCellValue("G$rowNum", $rec->department);
        $sheet->setCellValue("H$rowNum", $rec->designation);
        $sheet->setCellValue("I$rowNum", $rec->rwhrs);
        $sheet->setCellValue("J$rowNum", $rec->owhrs);
        $sheet->setCellValue("K$rowNum", $rec->nhrs);
        $sheet->setCellValue("L$rowNum", $rec->wdays);
        $sheet->setCellValue("M$rowNum", $rec->otdays);
        $sheet->setCellValue("N$rowNum", $rec->leavedays);
        $sheet->setCellValue("O$rowNum", $rec->holidays);  
        $sheet->setCellValue("P$rowNum", $rec->tmworked);
		$sheet->setCellValue("Q$rowNum", $rec->absentdays);  
		$sheet->setCellValue("r$rowNum", $rec->mxdate);  

		$rowNum++;
    }

    // Auto-size columns
    foreach (range('A', 'N') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    // Output file
    $filename = "Workmen_Working_Details_Selected_{$fdate}_to_{$tdate}.xlsx";
    ob_clean(); // Clean output buffer
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header("Content-Disposition: attachment;filename=\"$filename\"");
    header('Cache-Control: max-age=0');

    $writer = new Xlsx($spreadsheet);
    $writer->save('php://output');
    exit;
}





	public function extraadvancepayexcel() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_dept = $this->input->get('att_dept');
		$att_spell = $this->input->get('att_spell');
		$holget = $this->input->get('holget');
//echo $periodfromdate.'=='.$periodtodate.'=='.$att_dept.'=='.$att_spell.'=='.$holget;
		$comp = $this->session->userdata('companyId');
		$sql="select * from company_master where comp_id=".$comp;
		$query = $this->db->query($sql);
		$results = $query->result_array();
		foreach ($results as $row) {
			$compname=$row['company_name'];
		
		}
		$sql="select * from department_master where dept_id=".$att_dept;
		$query = $this->db->query($sql);
		$results = $query->result_array();
		foreach ($results as $row) {
			$deptname=$row['dept_desc'];
		
		}

	$mccodes = $this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
//	var_dump($mccodes);
	$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
	$sheet->getPageMargins()->setTop(.25);
	$sheet->getPageMargins()->setRight(0.25);
	$sheet->getPageMargins()->setLeft(0.25);
	$sheet->getPageMargins()->setBottom(0.25);
	
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
			'size' => 12,
		],
	];

 	$hed1='Extra Advance Payment Sheet for Deapartment '.$deptname.' Shift '.substr($att_spell,0,1).' Dated '.$sdate; 
	$sheet->setCellValue('A1', $compname);
	$sheet->setCellValue('A2', $hed1);
	$sheet->setCellValue('A3', 'Sl No');
	$sheet->setCellValue('B3', 'EB No');
	$sheet->setCellValue('C3', 'Name/Occupation');
	$sheet->setCellValue('D3', 'Att Hours');
	$sheet->setCellValue('e3', 'Adv Amount');
	$sheet->setCellValue('f3', 'Signature');
	$N=1;	
	$b='A'.$N.':f'.$N;
	$sheet->mergeCells('A1:f1');
	$N=2;	
	$b='A'.$N.':f'.$N;
	$sheet->mergeCells('A2:f2');
  //$objPHPExcel->getActiveSheet($c)->mergeCells($b);
	$n=4;
	$no=1;
	$totamt=0;
	$tothrs=0;
	foreach ($mccodes as $row) {
			$cln='A'.$n;
			$sheet->setCellValue($cln, $no);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$row->eb_no; 
			$cln='B'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='c'.$n;
			$sheet->setCellValue($cln, $row->work_name);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='d'.$n;
			$sheet->setCellValue($cln, $row->work_hours);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='e'.$n;
			$sheet->setCellValue($cln, $row->rate);
			$cln='f'.$n;
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$sheet->getRowDimension($n)->setRowHeight(40);
			$n++;
			$cln='c'.$n;
			$sheet->setCellValue($cln, $row->desig);
			$clnr='a'.$n.':'.'f'.$n;
			$sheet->getStyle($clnr)->applyFromArray($borderStyle);
			$n++;
			$totamt=$totamt+$row->rate;
			$tothrs=$tothrs+$row->work_hours;
			$no++;
		}		
		$date = date('d/M/Y');
		$cln='a'.$n;
		$sheet->getStyle($cln)->applyFromArray($borderStyle);
		$cln='c'.$n;
		$sheet->setCellValue($cln, 'Total');
		$cln='d'.$n;
		$sheet->setCellValue($cln, $tothrs);
		$cln='e'.$n;
		$sheet->setCellValue($cln, $totamt);
		$clnr='a'.$n.':'.'f'.$n;
		$sheet->getStyle($clnr)->applyFromArray($boldFontStyle);
		$sheet->getStyle($clnr)->applyFromArray($borderStyle);
		$sheet->getRowDimension($n)->setRowHeight(40);
		
		$n++;
		$cln='a'.$n;
		$sheet->setCellValue($cln, 'Print On '.$date);
		$n++;
		$n++;
		$n++;
		$n++;
		$cln='a'.$n;
		$sheet->setCellValue($cln, 'Time Keeper');
		$cln='c'.$n;
		$sheet->setCellValue($cln, 'Shift Incharge');
		$cln='e'.$n;
		$sheet->setCellValue($cln, 'HOD');
		$cln='f'.$n;
		$sheet->setCellValue($cln, 'Comm Manager');

 
		$sheet->getProtection()->setSheet(true);

		$sheet->getProtection()->setPassword('edpemp1234');

		$sheet->getColumnDimension('A')->setWidth(9);
		$sheet->getColumnDimension('B')->setWidth(9);
		$sheet->getColumnDimension('C')->setWidth(25);
		$sheet->getColumnDimension('d')->setWidth(10);
		$sheet->getColumnDimension('e')->setWidth(15);
		$sheet->getColumnDimension('f')->setWidth(25);
		$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		
		// Apply font style to cell A1
		$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
		
	
		
		$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
		$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
		$sheet->getStyle('A3:a3')->applyFromArray($borderStyle);
		$sheet->getStyle('b3:b3')->applyFromArray($borderStyle);
		$sheet->getStyle('c3:c3')->applyFromArray($borderStyle);
		$sheet->getStyle('d3:d3')->applyFromArray($borderStyle);
		$sheet->getStyle('e3:e3')->applyFromArray($borderStyle);
		$sheet->getStyle('f3:f3')->applyFromArray($borderStyle);


	$sheet->setTitle($deptname);

	$sheet = $spreadsheet->createSheet(1);
	$sheet->setTitle('Pay Components Info');
	
		
		$filename="payroll_".$sdate.'.xlsx';
	
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






	public function njmwagesexceldownload() {
		// Create a new Spreadsheet object
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate = $this->input->get('periodtodate');
		$att_payschm = $this->input->get('att_payschm');


				$spreadsheet = new Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();

    $sdate=$periodtodate;
	//	$sdate='2024-01-01';
    $cmpn='company';
//	$sheet->setCellValue('A1', $cmpn);
//	$sheet->setCellValue('A2', "Doff 10 Reports for Dated : ".$sdate);

	$sql=" select * from tbl_pay_scheme tps where id=".$att_payschm;
	$query = $this->db->query($sql);
	$results = $query->result_array();
	foreach ($results as $row) {
		$payscmn=$row['NAME'];
	
	}
	
	$sheet->setCellValue('A1', 'Employee Id');
	$sheet->setCellValue('B1', 'Employee Name');
	$sheet->setCellValue('c1', 'PayScheme');
	$data_array = array();
	$sql="  select twdpmt.*,CODE from EMPMILL12.tbl_wages_data_payschm_map_table twdpmt
	LEFT JOIN tbl_pay_components tpc on tpc.id=twdpmt.payscheme_component_id 
	where twdpmt.payscheme_id =".$att_payschm;
	$query = $this->db->query($sql);
	$results = $query->result_array();
	foreach ($results as $row) {
			$cmpoid=$row['payscheme_component_id'];
			$datan=$row['data_column_no'];
			$paycode=$row['CODE'];
			$data_array[] = array(
				'payscheme_component_id' => $cmpoid,
				'data_column_no' => $datan,
				'pay_code' => $paycode
			);
	}		


	$ch=68;
	$cn=1;
	$df3=1+2;

	foreach ($data_array as $data) {
		$compoidno=$data['payscheme_component_id'];
		$datap=$data['data_column_no'];
		$paycode=$data['pay_code'];
	if ($df3<=25) {
		$noc1=$df3;
		$noc2=0;
		$chf=chr($noc1+65);	
	} else
	{	
		$noc1=0;
		$noc2=$df3-25;
		$chf=chr($noc1+65).chr($noc2+64);
	}
	$cln=$chf.$cn;
//		echo $cln;
		$sheet->setCellValue($cln, $paycode);
		$ch++;
		$df3++;

}

 

$sql="select tnwdc.eb_id,eb_no,CONCAT(wm.worker_name, ' ', IFNULL(wm.middle_name, ' '), ' ', IFNULL(wm.last_name, ' ')) 
AS empname,
sum(hours_wkd_1) hours_wkd_1, sum(hours_wkd_2) hours_wkd_2,sum(esi_days) esi_days,sum(el_days) el_days,sum(sl_days) sl_days, 
sum(lay_off_hrs) lay_off_hrs,sum(piece_hours) piece_hours,sum(canteen) canteen,sum(sunday_adv) sunday_adv,sum(other_adv) other_adv, 
sum(el_advance) el_advance,sum(adv_ded) adv_ded,sum(installment_advance) installment_advance,sum(extra_hours_t) extra_hours_t,
sum(extra_hours_p) extra_hours_p,sum(c_shift_days) c_shift_days,sum(festival_hours) festival_hours,sum(arrear_plus) arrear_plus, 
sum(arrear_minus) arrear_minus,sum(other_pay) other_pay, 
sum(cl_days) cl_days,sum(prod_balance) prod_balance,sum(ul_days) ul_days,sum(advance) advance,sum(act_prod_amount) act_prod_amount, 
sum(loom_production) loom_production,sum(iltime_hrs) iltime_hrs,sum(ilpiece_hrs) ilpiece_hrs,sum(sardhelp_amt) sardhelp_rate,
sum(minus_bal) minus_bal,0 cadeligibility,0 gwf_eleg,1 iftu_eleg,sum(piece_wages+sardhelp_amt) piece_wages,sum(piece_wages_inc) piece_wages_inc
from (	
select eb_id,upd_type,sum(hours_wkd_1) hours_wkd_1, sum(hours_wkd_2) hours_wkd_2,sum(esi_days) esi_days,sum(el_days) el_days,sum(sl_days) sl_days, 
sum(lay_off_hrs) lay_off_hrs,sum(piece_hours) piece_hours,sum(canteen) canteen,sum(sunday_adv) sunday_adv,sum(other_adv) other_adv, 
sum(el_advance) el_advance,sum(adv_ded) adv_ded,sum(installment_advance) installment_advance,sum(extra_hours_t) extra_hours_t,
sum(extra_hours_p) extra_hours_p,sum(c_shift_days) c_shift_days,sum(festival_hours) festival_hours,sum(arrear_plus) arrear_plus, 
sum(arrear_minus) arrear_minus,sum(piece_wages) piece_wagesp,  sum(piece_wages_inc) piece_wages_incp,sum(other_pay) other_pay, 
sum(cl_days) cl_days,sum(prod_balance) prod_balance,sum(ul_days) ul_days,sum(advance) advance,sum(act_prod_amount) act_prod_amount, 
sum(loom_production) loom_production,sum(iltime_hrs) iltime_hrs,sum(ilpiece_hrs) ilpiece_hrs,sum(sardhelp_amt) sardhelp_amt,
sum(minus_bal) minus_bal,0 cadeligibility,0 gwf_eleg, iftu_eleg,
case when (sum(act_prod_amount)/sum(piece_hours+extra_hours_p))<=(1200/208) then round(sum(act_prod_amount),2) 
else round(sum(piece_hours+extra_hours_p)*(1200/208),2)  end piece_wages,
case when (sum(act_prod_amount)/sum(piece_hours+extra_hours_p))<=(1200/208) then 0
else round(sum(act_prod_amount)-sum(piece_hours+extra_hours_p)*(1200/208),2)  end piece_wages_inc
from EMPMILL12.tbl_njm_wages_data_collection tnwdc where is_active=1 
and tnwdc.date_from ='".$periodfromdate."' and tnwdc.date_to ='".$periodtodate."' 
group by eb_id,upd_type
) tnwdc
LEFT JOIN worker_master wm on tnwdc.eb_id =wm.eb_id 
left join tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =tnwdc.eb_id
where tpep.status=1 and tpep.PAY_SCHEME_ID  =".$att_payschm."
group by tnwdc.eb_id,eb_no,CONCAT(wm.worker_name, ' ', IFNULL(wm.middle_name, ' '), ' ', IFNULL(wm.last_name, ' '))
";


$sql="select tpep.EMPLOYEEID,eb_no,CONCAT(wm.worker_name, ' ', IFNULL(wm.middle_name, ' '), ' ', IFNULL(wm.last_name, ' ')) 
AS empname,tnw.*
from tbl_pay_employee_payscheme tpep
LEFT JOIN worker_master wm on tpep.EMPLOYEEID =wm.eb_id
left join (
select tnwdc.eb_id,
sum(hours_wkd_1) hours_wkd_1, sum(hours_wkd_2) hours_wkd_2,sum(esi_days) esi_days,sum(el_days) el_days,sum(sl_days) sl_days, 
sum(lay_off_hrs) lay_off_hrs,sum(piece_hours) piece_hours,sum(canteen) canteen,sum(sunday_adv) sunday_adv,sum(other_adv) other_adv, 
sum(el_advance) el_advance,sum(adv_ded) adv_ded,sum(installment_advance) installment_advance,sum(extra_hours_t) extra_hours_t,
sum(extra_hours_p) extra_hours_p,sum(c_shift_days) c_shift_days,sum(festival_hours) festival_hours,sum(arrear_plus) arrear_plus, 
sum(arrear_minus) arrear_minus,sum(other_pay) other_pay, 
sum(cl_days) cl_days,sum(prod_balance) prod_balance,sum(ul_days) ul_days,sum(advance) advance,sum(act_prod_amount) act_prod_amount, 
sum(loom_production) loom_production,sum(iltime_hrs) iltime_hrs,sum(ilpiece_hrs) ilpiece_hrs,sum(sardhelp_amt) sardhelp_rate,
sum(minus_bal) minus_bal,0 cadeligibility,0 gwf_eleg,case when sum(iftu_eleg)>0 then 1 else 0 end iftu_eleg,sum(piece_wages+sardhelp_amt) piece_wages,sum(piece_wages_inc) piece_wages_inc
from (	
select eb_id,upd_type,sum(hours_wkd_1) hours_wkd_1, sum(hours_wkd_2) hours_wkd_2,sum(esi_days) esi_days,sum(el_days) el_days,sum(sl_days) sl_days, 
sum(lay_off_hrs) lay_off_hrs,sum(piece_hours) piece_hours,sum(canteen) canteen,sum(sunday_adv) sunday_adv,sum(other_adv) other_adv, 
sum(el_advance) el_advance,sum(adv_ded) adv_ded,sum(installment_advance) installment_advance,sum(extra_hours_t) extra_hours_t,
sum(extra_hours_p) extra_hours_p,sum(c_shift_days) c_shift_days,sum(festival_hours) festival_hours,sum(arrear_plus) arrear_plus, 
sum(arrear_minus) arrear_minus,sum(piece_wages) piece_wagesp,  sum(piece_wages_inc) piece_wages_incp,sum(other_pay) other_pay, 
sum(cl_days) cl_days,sum(prod_balance) prod_balance,sum(ul_days) ul_days,sum(advance) advance,sum(act_prod_amount) act_prod_amount, 
sum(loom_production) loom_production,sum(iltime_hrs) iltime_hrs,sum(ilpiece_hrs) ilpiece_hrs,sum(sardhelp_amt) sardhelp_amt,
sum(minus_bal) minus_bal,0 cadeligibility,0 gwf_eleg,sum(iftu_amount) iftu_eleg,
case when (sum(act_prod_amount)/sum(piece_hours+extra_hours_p))<=(1200/208) then round(sum(act_prod_amount),2) 
else round(sum(piece_hours+extra_hours_p)*(1200/208),2)  end piece_wages,
case when (sum(act_prod_amount)/sum(piece_hours+extra_hours_p))<=(1200/208) then 0
else round(sum(act_prod_amount)-sum(piece_hours+extra_hours_p)*(1200/208),2)  end piece_wages_inc
from EMPMILL12.tbl_njm_wages_data_collection tnwdc where is_active=1 
and tnwdc.date_from ='".$periodfromdate."' and tnwdc.date_to ='".$periodtodate."'  
group by eb_id,upd_type
) tnwdc
group by tnwdc.eb_id
) tnw on tpep.EMPLOYEEID =tnw.eb_id
where tpep.status=1 and tpep.PAY_SCHEME_ID   =".$att_payschm."
and wm.active='Y'
";

echo $sql;


$query = $this->db->query($sql);

// Fetching the result set as an array of arrays
$results = $query->result_array();
$columns = $query->list_fields();
$numCols = $query->num_fields();
if (!empty($results)) {
	// Loop through the results
	$cn=2;
	foreach ($results as $row) {

//		$sheet->setCellValueExplicit('A1', $number, DataType::TYPE_STRING);
		$ch=68;
		$df3=1+2;
		$ebid=$row['eb_id'];
		$cln='A'.$cn;

//		$sheet->setCellValueExplicit($col . $rowNumber, $cell, DataType::TYPE_STRING);

		$cell=$row['eb_no'];
		$sheet->setCellValueExplicit($cln, $cell, DataType::TYPE_STRING);


//		$sheet->setCellValue($cln, $row['eb_no']);
		$cln='B'.$cn;
		$sheet->setCellValue($cln, $row['empname']);
		$cln='C'.$cn;
		$sheet->setCellValue($cln, $payscmn);
//echo $cln.'=='.$row['eb_no']."<br>";	
		foreach ($data_array as $data) {
			$compoidno=$data['payscheme_component_id'];
			$datap=$data['data_column_no'];
			$datavalue=$row[$datap];
			if (strlen($datavalue)==0) { $datavalue=0;}

		if ($df3<=25) {
			$noc1=$df3;
			$noc2=0;
			$chf=chr($noc1+65);	
		} else
		{	
			$noc1=0;
			$noc2=$df3-25;
			$chf=chr($noc1+65).chr($noc2+64);
		}
		$cln=$chf.$cn;
		//	echo $cln;
			$sheet->setCellValue($cln, $datavalue);
			$ch++;
			$df3++;
	}
	$cn++;
			


	}
}

//$sheet = $spreadsheet->createSheet($index);

// Rename the sheet
$sheet->setTitle('Template');

$sheet = $spreadsheet->createSheet(1);
$sheet->setTitle('Pay Components Info');

	
    $filename="payroll_".$att_payschm."_".$sdate.'.xlsx';

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
	
	public function generatePdf() {
        // Load mPDF library
        require_once APPPATH.'../vendor/autoload.php'; // Adjust the path as needed
        $mpdf = new Mpdf();

        // Write content directly to PDF
        $mpdf->WriteHTML('<h1>Hello, mPDF!</h1>');
        $mpdf->WriteText(10, 10, 'Additional text goes here.'); // Adjust the coordinates as needed

        // Generate PDF
        $mpdf->Output('output.pdf', 'D'); // Output as download
    }

	
	public function ajax_list_full_attendance(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->hrms_full_attendance_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);

		// $this->varaha->print_arrays($list);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
					if($i==0){
						$row[] = $no;
					}else{
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
					}
				}
			}
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->hrms_full_attendance_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->hrms_full_attendance_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_attendance_checklist(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Attendance_checklist_Model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);

		// $this->varaha->print_arrays($list);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
					if($i==0){
						$row[] = $no;
					}else{
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
					}
				}
			}
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Attendance_checklist_Model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Attendance_checklist_Model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_mechine_summary_entry(){
 
	}


	public function ajax_list_attendance_register(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
		$list = $this->hrms_attendance_register_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		// $this->varaha->print_arrays($array_keys,$list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();			
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
					if($i==0){
						$row[] = $no;
					}else{
						$mrowname = $array_keys[$i];
						$row[] = ($loc[$mrowname] ? $loc[$mrowname] : "");
					}
				}
			}
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->hrms_attendance_register_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->hrms_attendance_register_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		
		echo json_encode($output);
	}

	public function ajax_list_attendance_summary(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->hrms_attendance_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);

		// $this->varaha->print_arrays($list);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
					if($i==0){
						$row[] = $no;
					}else{
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
					}
				}
			}
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->hrms_attendance_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->hrms_attendance_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_occupation_deviation(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->hrms_occupation_deviation_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);

		// $this->varaha->print_arrays($list);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
					if($i==0){
						$row[] = $no;
					}else{
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
					}
				}
			}
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->hrms_occupation_deviation_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->hrms_occupation_deviation_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function list_ajax(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;
		$spells= $this->varaha_model->getAllSpells($companyId);
		$columns = $this->columns->getReportColumns($submenuId,null,null,$spells);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==559){						
			$list = $this->hrms_spell_wise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_spell_wise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_spell_wise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}
		if($submenuId==506){
			$sno=null;			
			$list = $this->hrms_category_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_category_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_category_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}
		if($submenuId==508){
			$sno=null;			
			$list = $this->hrms_department_sub_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_department_sub_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_department_sub_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}

		if($submenuId==505){
			$sno=null;			
			$list = $this->hrms_department_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_department_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_department_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}

		if($submenuId==517){
			$sno=null;			
			$list = $this->hrms_designation_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_designation_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_designation_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}

		if($submenuId==509){
			$sno=null;			
			$list = $this->hrms_dept_cat_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->hrms_dept_cat_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_dept_cat_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}

		if($submenuId==610){
			$sno=null;			
			$list_data = $this->hrms_cash_hands_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$list = $list_data['data'];
			$recordsTotal=count($list_data['data']);
			$recordsFiltered=count($list_data['data']);
			
		}
		if($submenuId==534){
			$sno=null;			
			$list = $this->hrms_employee_bank_statement_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			// $this->varaha->print_arrays($list_data);
			$recordsTotal=$this->hrms_employee_bank_statement_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->hrms_employee_bank_statement_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			
				
		}
		$array_keys = array_keys($columns);		
		$data = array();
		$no = $_POST['start'];
		if($list){
			foreach ($list as $loc) {
				$no++;
				$action ='';
				$row = array();
				if($array_keys){
					for($i=0; $i<count($array_keys); $i++){
						if($sno){
							if($i==0){
								$row[] = $no;
							}else{
								$mrowname = $array_keys[$i];
								$row[] = $loc->$mrowname;
							}
						}else{
							$mrowname = $array_keys[$i];
							if($submenuId==610){
								$row[] = (isset($loc->$mrowname) ? $loc->$mrowname : $loc[$mrowname]);
							}else{
								$row[] = (isset($loc->$mrowname) ? $loc->$mrowname : "");
							}
						}
						
					}
				}
				$data[] = $row;
			}
	
		}
		
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $recordsTotal,
						"recordsFiltered" => $recordsFiltered,
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	

	public function dashboard($menuId, $submenuId =null){
		if($this->session->userdata('userid')==''){
			$this->data['headtitle'] = "LogIn";
			$this->load->view('login/signin',$this->data);
		}else{		
		//	 phpinfo();
			$this->data['headtitle'] = "Dashboard";
			$this->data['mainmenuId'] = $menuId;			
			$this->data['menudit'] = $this->varaha_model->getMenuData($menuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");			
			$this->data['from_date'] = date('Y-m-01',time());
			$this->data['to_date'] = date('Y-m-t',time());
			$this->data['submenuId'] = "";
			$this->data['companyId'] = "";
			$this->data['controller'] = "Data_entry";
			if($this->session->userdata('companys')){
				foreach($this->session->userdata('companys') as $company){
					if($company['name']=='TALBOT'){
						$companyName = $company['name'];
					}
				}
			}
			if(($companyName =='TALBOT')){
				$this->data['embed_url'] = "https://datastudio.google.com/embed/reporting/5c1f08d8-1ab1-4cd3-89e3-85bffd30d03a/page/LAYAD";
				$this->page_construct('embedreps/embedreports',$this->data);
			}else{
				$this->page_construct('hrms/dashboard',$this->data);
			}
			
		}
	}
	
	public function report($mainmenuId,$submenuId,$company){
		if($this->session->userdata('userid')==''){
			$this->data['headtitle'] = "LogIn";
			$this->load->view('login/signin',$this->data);
		}else{
			$this->data['headtitle'] = "Dashboard";
			$this->data['mainmenuId'] = $mainmenuId;
			$this->data['submenuId'] = $submenuId;
			$this->data['companyId'] = $company;
			$this->data['from_date'] = date('Y-m-01',time());
			$this->data['to_date'] = date('Y-m-t',time());
			$this->data['controller'] = "data_entry";
			$this->data['menudit'] = $this->varaha_model->getMenuData($submenuId);
			



			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");
			$this->data['tableBorders']="";
			$this->data['Source']="0";
			$this->data['att_type']="0";
			$this->data['att_status']="";
			$this->data['status']=$this->varaha_model->getAllStatus();
			$this->data['departments']=$this->varaha_model->getAllDepartments($this->data['companyId']);
			$this->data['masterdepartments']=$this->varaha_model->getAllMasterDepartments($this->data['companyId']);
			$this->data['payschemes']=$this->varaha_model->getAllPayschemes($this->data['companyId']);
			$this->data['att_dept']="";
			$this->data['designations']= $this->varaha_model->getAllDesignations($this->data['companyId']);
			$this->data['att_desig']="";
			$this->data['spells']= $this->varaha_model->getAllSpells($this->data['companyId']);
			$this->data['att_spells']="";
			$this->data['eb_no']="";			
			$this->data['att_mark_hrs_att']="1";
			$this->data['dates']=1;
			$this->data['att_worktype']="R";
			$this->data['att_cat']="";
			$this->data['categorys']= $this->varaha_model->getAllCategorys($this->data['companyId']);
			$this->data['branchs']= $this->varaha_model->getAllBranchs($this->data['companyId']);
			$this->data['branch_id'] = "";
			$this->data['componets']= $this->varaha_model->getAllComponets($this->data['companyId']);
			$this->data['componet_id'] = 21;
			$this->data['godowns'] = $this->varaha_model->getAllGodownsNos();
			$this->data['mccodes'] = $this->varaha_model->getAllMccodes($this->data['companyId']);
			// $this->varaha->print_arrays($this->data['from_date'], $this->data['to_date']);
			$this->data['columns'] = $this->columns->getReportColumns($submenuId);
				$query = $this->varaha_model->getEmbedGoogleLinks($company,$submenuId);
				
				if($query){
					// $this->varaha->print_arrays($query);
					$this->data['embed_url'] = $query;
					$this->page_construct('embedreps/embedreports',$this->data);
				
				}else{
					if($submenuId==603){
						$this->data['function'] = "ajax_list_full_attendance";
					}else if($submenuId==657){
						$this->data['function'] = "ajax_list_attendance_checklist";
				}else if($submenuId==658){
					 $this->page_construct('other_data_entry/mcsummary_report',$this->data);
	//	 $this->page_construct('other_data_entry/tabsample',$this->data);

				}else if($submenuId==659){
							 $this->page_construct('other_data_entry/loans_advance',$this->data);
		
					}else if($submenuId==666){
									 $this->page_construct('other_data_entry/loans_advance_installment_process',$this->data);
				
					}else if($submenuId==668){
						$this->page_construct('other_data_entry/holiday_att_incn_eligibility',$this->data);
   
	   				}else if($submenuId==669){

						$this->page_construct('other_data_entry/holiday_att_incn_process',$this->data);
   
	   				}else if($submenuId==670){
						$this->page_construct('other_data_entry/ot_register_payslip',$this->data);
				//		$html=$this->page_construct('other_data_entry/ot_register_payslip',$this->data);
				//		echo $html;
		//		$html = $this->load->view('hrms/reportprint', $this->data, true);		
		//		echo $html;
   
	   				}else if($submenuId==671){
						$this->page_construct('other_data_entry/pay_register_payslip',$this->data);
   
	   				}else if($submenuId==667){
						$this->page_construct('other_data_entry/other_data_ent_report',$this->data);
   
	   				}else if($submenuId==604){
							$this->data['columns'] = $this->columns->getReportColumns($submenuId,$this->data['from_date'],$this->data['to_date']);
							$this->data['function'] = "ajax_list_attendance_register";	
							$this->data['tableBorders']="table-bordered";			
					}else if($submenuId==607){
						$this->data['function'] = "ajax_list_attendance_summary";
				
					}else if($submenuId==601){
							$this->data['function'] = "ajax_list_occupation_deviation";
					
					}else if($submenuId==559){
						$this->data['function'] = "list_ajax";
						$this->data['columns'] = $this->columns->getReportColumns($submenuId,null,null,$this->data['spells']);	
					}else if($submenuId==506){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==508){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==505){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==517){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==509){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==610){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else if($submenuId==534){
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else{
						
						$this->page_construct('hrms/notfound',$this->data);
					}
					if($this->data['dates']){
						$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($this->data['from_date'])) . " To ".date('d-m-Y', strtotime($this->data['to_date']));
					}else{
						$this->data['report_title'] = $this->data['menuName'];
					}
					
					//.date("d",$form_date)." ".substr((date("D",$form_date)),0,2)." ".substr((date("M",$form_date)),0,2).
					
					if($submenuId==658){
//						$this->page_construct('other_data_entry/mcsummary_report',$this->data);
					}else  {	
//						$this->page_construct('other_data_entry/report',$this->data);
					}		
				}

			
		}
	}

	public function reporttype(){
		if($this->session->userdata('userid')==''){
			$this->data['headtitle'] = "LogIn";
			$this->load->view('login/signin',$this->data);
		}else{
			$this->data['headtitle'] = "Dashboard";
			$this->data['mainmenuId'] = $_POST['mainmenu'];
			$this->data['submenuId'] = $_POST['submenu'];
			$this->data['companyId'] = $_POST['companyId'];
			$this->data['menudit'] = $this->varaha_model->getMenuData($this->data['submenuId']);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");
			$this->data['controller'] = "reports_hrms";
			
			$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($_POST['from_date'])) . " To ".date('d-m-Y', strtotime($_POST['to_date']));
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			$this->data['tableBorders']="";

			$this->data['Source']= $_POST['Source_att'];
			$this->data['att_type']=$_POST['att_type_att'];
			$this->data['att_status']=$_POST['att_status_att'];
			$this->data['status']=$this->varaha_model->getAllStatus();
			$this->data['departments']=$this->varaha_model->getAllDepartments($this->data['companyId']);
			// $this->data['subdepartments']=$this->varaha_model->getAllSubDepartments($this->data['companyId']);
			$this->data['att_dept']=$_POST['att_dept_att'];
			$this->data['designations']= $this->varaha_model->getAllDesignations($this->data['companyId']);
			$this->data['att_desig']=$_POST['att_desig_att'];
			$this->data['spells']= $this->varaha_model->getAllSpells($this->data['companyId']);
			$this->data['att_spells']=$_POST['att_spells_att'];
			$this->data['eb_no']=$_POST['eb_no_att'];
			$this->data['att_mark_hrs_att']=$_POST['att_mark_hrs_att'];
			$this->data['sno']=1;
			$this->data['att_worktype']=$_POST['att_worktype_att'];
			$this->data['att_cat_att']=$_POST['att_cat_att'];
			$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
			$this->data['categorys']= $this->varaha_model->getAllCategorys($this->data['companyId']);
			$this->data['branchs']= $this->varaha_model->getAllBranchs($this->data['companyId']);
			$this->data['branch_id'] = $_POST['att_branch_id'];
			$this->data['componets']= $this->varaha_model->getAllComponets($this->data['companyId']);
			$this->data['componet_id'] = $_POST['att_componet_id'];
			if($this->data['submenuId']==603){
				$this->data['function'] = "ajax_list_full_attendance";
			}
			if($this->data['submenuId']==657){
				$this->data['function'] = "ajax_list_attendance_checklist";
			}

			$filename = $this->data['report_title'].date('Ymdhis');
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $_POST['from_date'],
					'to_date' => $_POST['to_date'],
					'Source' => $this->data['Source'],
					'att_type' => $this->data['att_type'],
					'att_status' => $this->data['att_status'],
					'att_dept' => $this->data['att_dept'],
					'att_desig' => $this->data['att_desig'],
					'att_spells' => $this->data['att_spells'],
					'eb_no' => $this->data['eb_no'],
					'att_mark_hrs_att' => $this->data['att_mark_hrs_att'],
					'att_worktype' => $this->data['att_worktype'],
					'att_cat' => $this->data['att_cat_att'],
					'branch_id' => $this->data['branch_id'],
					'componet_id' => $this->data['componet_id']
				);				
			
			if($this->data['submenuId']==603){				
				$this->data['res'] = $this->hrms_full_attendance_model->directReport($perms);
				// $this->varaha->print_arrays($this->data['res']);
				$html = $this->load->view('hrms/hrms_full_attendance', $this->data, true);
				
			}else if($this->data['submenuId']==658){
 		
		//echo 'jjjj';
				$this->load->view('other_data_entry/mechine_summary_entry');
			}else if($this->data['submenuId']==604){
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				$this->data['function'] = "ajax_list_attendance_register";	
				$this->data['tableBorders']="table-bordered";
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==607){
				$this->data['function'] = "ajax_list_attendance_summary";
				$this->data['res'] = $this->hrms_attendance_summary_model->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==601){				
				$this->data['function'] = "ajax_list_occupation_deviation";
				$this->data['res'] = $this->hrms_occupation_deviation_model->directReport($perms);	
				
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==559){
				$this->data['function'] = "list_ajax";
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],null,null,$this->data['spells']);
				$this->data['res'] = $this->hrms_spell_wise_model->directReport($perms);	
				$html = $this->load->view('hrms/reportprint', $this->data, true);	
				
			}else if($this->data['submenuId']==506){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_category_summary_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==508){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_department_sub_summary_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==505){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_department_summary_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==517){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_designation_summary_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==509){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_dept_cat_summary_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==610){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_cash_hands_report_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==534){
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->hrms_employee_bank_statement_report_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else{
				$this->page_construct('hrms/notfound',$this->data);
			}
				


			if($_POST['type']==1){ // PDF
				if($this->data['submenuId']==610){
					$this->hrms_cash_hands_report_model->get_cashhands_pdf_report($perms);
				}else{
					$this->pdf($html,$filename);
				}
				
			}
			
			if($_POST['type']==2){ // EXCEL
				
 
//				ECHO $this->data['submenuId'];
				if($this->data['submenuId']==604){
					$this->data['res'] = $this->hrms_attendance_register_model->directReport($perms);
					$html = $this->load->view('hrms/reportprint', $this->data, true);
				}
		
				if($this->data['submenuId']==534){
					$user_arr = array();
					if($this->data['res']){		
						$user_arr[] = array("","",$this->data['menuName'],"From ". date('d-m-Y', strtotime($_POST['from_date'])) . " To ".date('d-m-Y', strtotime($_POST['to_date'])),"","");		
						$user_arr[] = array("Employee Code","Employee Name","Bank Name","Account No","IFSC Code","Net Pay");		
						foreach($this->data['res'] as $row){
							$user_arr[] = array($row->Employee_Code,$row->Employee_Name,$row->Bank_Name,$row->Account_No,$row->IFSC_Code, $row->Net_Pay);
						}
					}
					$serialize_user_arr = serialize($user_arr);
					$this->excelcsv($serialize_user_arr,$filename);
					// $this->excel($html,$filename);
				}else if($this->data['submenuId']==671) {
						$this->registerpayexcelc($perms);
				}else if($this->data['submenuId']==694) {
					$this->outsiderdailypayexcel($perms);
				}
				else if($this->data['submenuId']==6571) {
					$this->attendancechecklistexcel($perms);
				}
				else{
					$this->excel($html,$filename);
				}
				
			}

			if($_POST['type']==3){ // PRINT
				if($this->data['submenuId']==604){
					$this->data['res'] = $this->hrms_attendance_register_model->directReport($perms);
					$html = $this->load->view('hrms/reportprint', $this->data, true);
				}
				echo $html;
			}

			if($_POST['type']==4){ // GRID				

				$this->page_construct('hrms/report',$this->data);
			}

			// $this->page_construct('jute/jutereport',$this->data);
			
		}
	}


	function pdf($result,$filename){
		
		/*
		include("application/third_party/MPDF/mpdf.php");
		$mpdf=new mPDF('en-GB-x','A4-P','','',10,10,10,10,6,3);
		$mpdf->list_indent_first_level = 0;
		$mpdf->shrink_tables_to_fit = 1;
		$mpdf->WriteHTML($result);
		$filename = $filename.date('d-m-Y h:m:i',time());
		$mpdf->Output($filename.'.pdf','D');
		*/
		
		require_once 'vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8',
										'format' => 'A4-L',
										'margin_top' => 10,
										'margin_left' => 10,
										'margin_right' => 10,
										'margin_bottom' => 10,
										'mirrorMargins' => true]); 
		$mpdf->list_indent_first_level = 0;
		$mpdf->shrink_tables_to_fit = 1;
		$mpdf->WriteHTML($result);
		//$filename = $filename.date('d-m-Y h:m:i',time());
		$mpdf->Output($filename.'.pdf','D');
		
		
	}
	function pdfland($result,$filename){
		
		/*
		include("application/third_party/MPDF/mpdf.php");
		$mpdf=new mPDF('en-GB-x','A4-L','','',10,10,10,10,6,3);
		$mpdf->list_indent_first_level = 0;
		$mpdf->shrink_tables_to_fit = 1;
		$mpdf->WriteHTML($result);
		$filename = $filename.date('d-m-Y h:m:i',time());
		$mpdf->Output($filename.'.pdf','I');
		*/
		
		require_once 'vendor/autoload.php';
		$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8',
										'format' => 'A4-L',
										'margin_top' => 10,
										'margin_left' => 10,
										'margin_right' => 10,
										'margin_bottom' => 10,
										'mirrorMargins' => true]); 
		$mpdf->list_indent_first_level = 0;
		$mpdf->shrink_tables_to_fit = 1;
		$mpdf->WriteHTML($result);
		$filename = $filename.date('d-m-Y h:m:i',time());
		$mpdf->Output($filename.'.pdf','I');
		
	}
	
	function excel($result,$filename){
		
		
		$data = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">
				   <head>
					   <!--[if gte mso 9]>
					   <xml>
						   <x:ExcelWorkbook>
							   <x:ExcelWorksheets>
								   <x:ExcelWorksheet>
									   <x:Name>Sheet 1</x:Name>
									   <x:WorksheetOptions>
										   <x:Print>
											   <x:ValidPrinterInfo/>
										   </x:Print>
									   </x:WorksheetOptions>
								   </x:ExcelWorksheet>
							   </x:ExcelWorksheets>
						   </x:ExcelWorkbook>
					   </xml>
					   <![endif]-->
				   </head>
				   <body>'.$result.'</body></html>';
				   
	   
	//    ob_end_clean();
	   if (ob_get_contents()) ob_end_clean();
	   header('Content-Encoding: UTF-8');
	   header('Content-Type: application/vnd.ms-excel');
	//    header('Content-Type: UTF-8');
	   header("Content-type: application/vnd.ms-excel" );
	   header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	   header('Cache-Control: max-age=0');		
	   header("Pragma: no-cache");
	   header("Expires: 0");
	//    ob_end_clean();
	   if (ob_get_contents()) ob_end_clean();
	   mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');	   
	   
	   echo $data;


	// // Headers for download 
	// header("Content-Type: application/vnd.ms-excel"); 
	// header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	// header('Cache-Control: max-age=0');		
	// header("Pragma: no-cache");
	// header("Expires: 0");
	// // Render excel data 
	// echo $data; 
	// exit;
   }

    function excelcsv($result,$filename){
		$export_data = unserialize($result);
		ob_end_clean();
		$filename = $filename.'.csv';
		$fp = fopen('php://output', 'w');
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename=' . $filename);
		// $marr = array(1,2,3,4);
		foreach ($export_data as $line){		
			fputcsv($fp, array_map(function($v){return $v."\r";},$line));
		}
		// fputcsv($fp, $marr);
		fclose( $fp );
		ob_flush();
		// foreach ($export_data as $line){			
		// 	fputcsv($fp, array_map(function($v){
		// 		return $v."\r";
		// 	},$line));
		// }	
		exit();

	}
	
	public function getMasterMCCodes() {
		$this->load->model('Loan_adv_model');
        $selectedDepartment = $this->input->post('department');
        $mccodes = $this->Loan_adv_model->getMCCodesByDepartment($selectedDepartment);
		echo json_encode($mccodes);
    }
	public function getEbMaster() {
		$this->load->model('Loan_adv_model');
        $selectedDepartment = $this->input->post('ebno');
        $mccodes = $this->Loan_adv_model->getEbMaster($selectedDepartment);
		$data = [];
		$cnt=count($mccodes);
	//	echo $cnt;
			foreach ($mccodes as $record) {
				$ebid=$record->eb_id;
				$ebname=$record->empname;
				$data[] = [
					$ebid=$ebid,
					$ebname=$ebname,
					 
				];
			}
			if ($cnt==0) {
				$ebid=0;
				$ebname='N.A';
			
			}
			$response = array(
				'success' => true,
				'eb_id' => $ebid,
				'empname' => $ebname 
			);
			echo json_encode($response);
    }

	public function getLoanadvData() {
		$this->load->model('Loan_adv_model');
     //   $selectedDepartment = $this->input->post('date');
		$periodfromdate= $this->input->post('date');
	//	echo 'date-- '.$periodfromdate;
	//	$periodtodate= $this->input->post('periodtodate');
//		$att_payschm =  $this->input->post('att_payschm');
		
		$mccodes = $this->Loan_adv_model->getLoanadvData($periodfromdate);
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$recid=$record->loan_adv_id,
					$advdate=$record->loan_adv_date,
					$advtype=$record->loan_adv_type,
					$emp_code=$record->emp_code,
					$empname=$record->empname,
					$loan_adv_amount=$record->loan_adv_amount,
					$installment_amount=$record->installment_amount,
					$No_of_installment=$record->No_of_installment,
					$installment_start_date=$record->installment_start_date,
					$eb_id=$record->eb_id,
					$inststat=$record->inststat,
					$remarks=$record->remarks,
					
				 	
					
				];
			}
			echo json_encode(['data' => $data]);
    }
	public function getLoanadvtranData() {
		$this->load->model('Loan_adv_model');
  		$periodfromdate= $this->input->post('periodfromdate');
		$periodtodate= $this->input->post('periodtodate');
		$att_payschm =  $this->input->post('att_payschm');
		
		$mccodes = $this->Loan_adv_model->getLoanadvtranData($periodfromdate,$periodtodate,$att_payschm);
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$recid=$record->loan_adv_id,
					$advdate=$record->loan_adv_date,
					$advtype=$record->loan_adv_type,
					$emp_code=$record->emp_code,
					$empname=$record->empname,
					$loan_adv_amount=$record->loan_adv_amount,
					$installment_amount=$record->installment_amount,
					$No_of_installment=$record->No_of_installment,
					$installment_start_date=$record->installment_start_date,
					$eb_id=$record->eb_id,
					$inststat=$record->inststat,
					$remarks=$record->remarks,
					
				 	
					
				];
			}
			echo json_encode(['data' => $data]);
    }

	public function saveadv_data() {
		$loanadvamt = $this->input->post('loanadvamt');
		$instamt = $this->input->post('instamt');
		$rec_time =  date('Y-m-d H:i:s');
		$ebid = $this->input->post('ebid');
		$comp = $this->session->userdata('companyId');
		$noofinst = $this->input->post('noofinst');
		$inststartdate = $this->input->post('inststartdate');
		$fromdt = $this->input->post('fromdt');
		$advtype = $this->input->post('advtype');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
 		 
		 $data = array(
			'loan_adv_amount' => $loanadvamt,
			'installment_amount' => $instamt,
			'eb_id' => $ebid,
			'No_of_installment' => $noofinst,
			'is_active' => $active,
			'installment_start_date' => $inststartdate,
			'loan_adv_date' => $fromdt,
			'status' => $stat,
			'created_by' => $userid,
			'loan_adv_type' => $advtype,
			'created_date' => $rec_time
		);
	$this->db->insert('EMPMILL12.tbl_loan_advance_table', $data);
	$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}
	
	public function updateadv_data() {
		$loanadvamt = $this->input->post('loanadvamt');
		$instamt = $this->input->post('instamt');
		$rec_time =  date('Y-m-d H:i:s');
		$ebid = $this->input->post('ebid');
		$comp = $this->session->userdata('companyId');
		$noofinst = $this->input->post('noofinst');
		$inststartdate = $this->input->post('inststartdate');
		$fromdt = $this->input->post('fromdt');
		$advtype = $this->input->post('advtype');
		$recordid = $this->input->post('record_id');
		
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
 		 
		 $data = array(
			'loan_adv_amount' => $loanadvamt,
			'installment_amount' => $instamt,
			'eb_id' => $ebid,
			'No_of_installment' => $noofinst,
			'is_active' => $active,
			'installment_start_date' => $inststartdate,
			'loan_adv_date' => $fromdt,
			'status' => $stat,
			'created_by' => $userid,
			'loan_adv_type' => $advtype,
			'mod_date' => $rec_time
			

		);

		$this->Loan_adv_model->updateLoanAdvanceData($recordid, $data);
	//	$this->Loan_adv_model->getLoanadvData($selectedDepartment);


	$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}
	
	public function advpprocessdata() {
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
	 	
	
		$this->Loan_adv_model->advpprocessdata($periodfromdate, $periodtodate,$att_payschm);
 		 
 	$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}


	public function saveeleg_data() {
		$holget = $this->input->post('holget');
		$aincget = $this->input->post('aincget');
		$rec_time =  date('Y-m-d H:i:s');
		$ebid = $this->input->post('ebid');
		$comp = $this->session->userdata('companyId');
		$fnaincamt = $this->input->post('fnaincamt');
		$mnaincamt = $this->input->post('mnaincamt');
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
    	 $data = array(
			'eb_id' => $ebid,
			'holiday_eligibility' => $holget,
			'att_incn_eligibility' => $aincget,
			'fn_att_inc_rate' => $fnaincamt,
			'mn_att_inc_rate' => $mnaincamt,
			'is_active' => $active,
			'created_by' => $userid,
			'status' => $stat
 		);
	$this->db->insert('EMPMILL12.tbl_holiday_att_inc_eligibility', $data);
//echo $this->db->last_query();
	$ebid = 0;

	$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}


	public function savemcsumm_data() {
//		mcsummdate: mcsummdate,mcsummdeptid: mcsummdeptid,companyId: companyId,record_id: record_id,
//		mcsummmcid: mcsummmcid,spella1: spella1,spella2: spella2,spellb1: spellb1,spellb2: spellb2,
//		shifta: shifta,shiftb: shiftb,shiftc: shiftc 
		$mcsummdate = $this->input->post('mcsummdate');
		$mcsummdeptid = $this->input->post('mcsummdeptid');
		$att_branch = $this->input->post('att_branch');
		$att_desig = $this->input->post('att_desig');
		$hol_get = $this->input->post('hol_get');
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$mcsummmcid = $this->input->post('mcsummmcid');
		$record_id = $this->input->post('record_id');
		$spella1 = $this->input->post('spella1');
		$spella2 = $this->input->post('spella2');
		$spellb1 = $this->input->post('spellb1');
		$spellb2 = $this->input->post('spellb2');
		$shifta = $this->input->post('shifta');
		$shiftb = $this->input->post('shiftb');
		$shiftc = $this->input->post('shiftc');
		$mdate=$mcsummdate;
		
		//substr($mcsummdate,8,2).'/'.substr($mcsummdate,5,2).'/'.substr($mcsummdate,0,4);
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
	if ($hol_get==1) {
		$data = array(
			'tran_date' => $mdate,
			'shift_a' => $shifta ,
			'shift_b' => $shiftb ,
			'shift_c' => $shiftc ,
			'spell_a1' => $spella1,
			'spell_a2' => $spella2,
			'spell_b1' => $spellb1,
			'spell_b2' => $spellb2,
			'company_id' => $comp,
			'mc_code_id' => $mcsummmcid,
			'is_active' => $active,
			'created_on' => $rec_time,
			'branch_id' => $att_branch
		);
	if ($record_id==0) {
		$this->db->insert('EMPMILL12.tbl_daily_summ_mechine_data', $data);
	}
	if ($record_id>0) {
		$data = array(
		'shift_a' => $shifta ,
		'shift_b' => $shiftb ,
		'shift_c' => $shiftc ,
		'spell_a1' => $spella1,
		'spell_a2' => $spella2,
		'spell_b1' => $spellb1,
		'spell_b2' => $spellb2,
		);

	$this->db->where('daily_sum_mc_id', $record_id);
    $this->db->update('EMPMILL12.tbl_daily_summ_mechine_data', $data);
	}
	}
	if ($hol_get==2) {
		$data = array(
			'tran_date' => $mdate,
			'shift_a' => $shifta ,
			'shift_b' => $shiftb ,
			'shift_c' => $shiftc ,
			'spell_a1' => $spella1,
			'spell_a2' => $spella2,
			'spell_b1' => $spellb1,
			'spell_b2' => $spellb2,
			'company_id' => $comp,
			'occu_id' => $att_desig,
			'is_active' => $active,
			'created_on' => $rec_time,
			'branch_id' => $att_branch
		);

		if ($record_id==0) {
			$this->db->insert('EMPMILL12.tbl_daily_other_hands_data', $data);
		}
		if ($record_id>0) {
			$data = array(
			'shift_a' => $shifta ,
			'shift_b' => $shiftb ,
			'shift_c' => $shiftc ,
			'spell_a1' => $spella1,
			'spell_a2' => $spella2,
			'spell_b1' => $spellb1,
			'spell_b2' => $spellb2,
			);
	
		$this->db->where('oth_hands_id', $record_id);
		$this->db->update('EMPMILL12.tbl_daily_other_hands_data', $data);
		}
	
//echo $this->db->last_query();
	$ebid = 0;
	}

	$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}


	
	public function checkmcsumm_data() {
	 
		$this->load->model('Loan_adv_model');
		$mcsummdate = $this->input->post('mcsummdate');
		$mcsummdeptid = $this->input->post('mcsummdeptid');
		$att_branch = $this->input->post('att_branch');
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$mcsummmcid = $this->input->post('mcsummmcid');
		$att_desig=$this->input->post('att_desig');
		$hol_get=$this->input->post('hol_get');
	 	$mccodes = $this->Loan_adv_model->checkmcsumm_data($mcsummdate,$mcsummmcid,$comp,$att_branch,$att_desig,$hol_get);
		$cnt=count($mccodes);
		$data = [];
		$response = array(
			'spella1'=>0,
			'spella2'=>0,
			'spellb1'=>0,
			'spellb2'=>0,
			'shifta'=>0,
			'shiftb'=>0,
			'shiftc'=>0,
			'recordid'=>0,
			'success' => true,
			'savedata'=> 'saved'
		);	

		if ($cnt>0) {
			foreach ($mccodes as $record) {
				$response = array(
					'spella1'=>$record->spell_a1,
					'spella2'=>$record->spell_a2,
					'spellb1'=>$record->spell_b1,
					'spellb2'=>$record->spell_b2,
					'shifta'=>$record->shift_a,
					'shiftb'=>$record->shift_b,
					'shiftc'=>$record->shift_c,
					'recordid'=>$record->recordid,
					'success' => true,
					'savedata'=> 'saved'
				
				);
				
	 		}
	
		}  		
/*	
		$response = array(
			'success' => true,
			'trollyNo' => $trlno,
			'trollyWt' => $trlwt,
			'doffNo' => $dfno,
			'tnetWt' => $twt,
			'mcno' => $frm	
		
		);
*/		
		
		echo json_encode($response);
//		echo json_encode([$response]);
    }

	public function checkdesigsumm_data() {
	 
		$this->load->model('Loan_adv_model');
		$mcsummdate = $this->input->post('mcsummdate');
		$mcsummdeptid = $this->input->post('mcsummdeptid');
		$att_branch = $this->input->post('att_branch');
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$att_desig = $this->input->post('att_desig');
	 	$mccodes = $this->Loan_adv_model->checkdesigsumm_data($mcsummdate,$att_desig,$comp,$att_branch);
		$cnt=count($mccodes);
		$data = [];
		$response = array(
			'spella1'=>0,
			'spella2'=>0,
			'spellb1'=>0,
			'spellb2'=>0,
			'shifta'=>0,
			'shiftb'=>0,
			'shiftc'=>0,
			'recordid'=>0,
			'success' => true,
			'savedata'=> 'saved'
		);	

		if ($cnt>0) {
			foreach ($mccodes as $record) {
				$response = array(
					'spella1'=>$record->spell_a1,
					'spella2'=>$record->spell_a2,
					'spellb1'=>$record->spell_b1,
					'spellb2'=>$record->spell_b2,
					'shifta'=>$record->shift_a,
					'shiftb'=>$record->shift_b,
					'shiftc'=>$record->shift_c,
					'recordid'=>$record->daily_sum_mc_id,
					'success' => true,
					'savedata'=> 'saved'
				
				);
				
	 		}
	
		}  		
/*	
		$response = array(
			'success' => true,
			'trollyNo' => $trlno,
			'trollyWt' => $trlwt,
			'doffNo' => $dfno,
			'tnetWt' => $twt,
			'mcno' => $frm	
		
		);
*/		
		
		echo json_encode($response);
//		echo json_encode([$response]);
    }


	public function getmcsummData() {
	 
	$this->load->model('Loan_adv_model');
	 $mcsummdate = $this->input->post('date');
	 $mcsummdeptid = $this->input->post('mcsummdeptid');
	 $att_branch = $this->input->post('att_branch');
	 $rec_time =  date('Y-m-d H:i:s');
	 $comp = $this->session->userdata('companyId');
	 $hol_get = $this->input->post('hol_get');
	 

		$mccodes = $this->Loan_adv_model->getmcsummData($mcsummdate,$mcsummdeptid,$att_branch,$comp,$hol_get);
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$recid=$record->recordid,
					$tran_date=$record->tran_date,
					$code=$record->code,
					$name=$record->name,
					$spella1=$record->spell_a1,
					$spella2=$record->spell_a2,
					$shifta=$record->shift_a,
					$spellb1=$record->spell_b1,
					$spellb2=$record->spell_b2,
					$shiftb=$record->shift_b,
					$shiftc=$record->shift_c


				];
			}
			echo json_encode(['data' => $data]);
    }



	public function gethlaincelegData() {
	 
		$this->load->model('Loan_adv_model');
     //   $selectedDepartment = $this->input->post('date');
	 
		$mccodes = $this->Loan_adv_model->gethlaincelegData();
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$recid=$record->hl_att_inc_id,
					$advdate=$record->emp_code,
					$advtype=$record->empname,
					$emp_code=$record->holidayeligibility,
					$empname=$record->attincneligibility,
					$loan_adv_amount=$record->fn_att_inc_rate,
					$installment_amount=$record->mn_att_inc_rate,
					$No_of_installment=$record->eb_id,
					$catadesc=$record->cata_desc
 					
				 	
					
				];
			}
			echo json_encode(['data' => $data]);
    }
	public function holidayprocessdata() {
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
		$holget = $this->input->post('holget');
//		echo 'data '.$holget;
	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');

		$mcc=$this->Loan_adv_model->updateholidayaData($periodfromdate,$periodtodate, $att_payschm,$holget);
		$data = [];
			foreach ($mcc as $record) {
				$succ=$record->succes;
			}		

			$response = array(
		'success' => true,
		'savedata'=> 'saved'
	);
	
		echo json_encode($response);
	
	}

	public function exceldownloads() {
        // Load PhpSpreadsheet library
        $this->load->library('PhpSpreadsheet');

        // Create a new PhpSpreadsheet instance
        $spreadsheet = new Spreadsheet();

        // Add some data
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Hello');
        $sheet->setCellValue('B1', 'World!');

        // Create a writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for file download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="example.xlsx"');
        header('Cache-Control: max-age=0');

        // Write the file to output
        $writer->save('php://output');

        // Exit to prevent any further output
        exit;
    }

	
    public function anotherFunction() {
        // Call exceldownload() from within anotherFunction
        $this->exceldownload();
    }





	public function getholiday_data() {
		$this->load->model('Loan_adv_model');
     //   $selectedDepartment = $this->input->post('date');
		$periodfromdate= $this->input->post('periodfromdate');
		$periodtodate= $this->input->post('periodtodate');
		$att_payschm =  $this->input->post('att_payschm');

		


//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->getholiday_data($periodfromdate,$periodtodate,$att_payschm);
		//var_dump($mccodes);
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$recid=$record->holiday_tran_id,
					$advdate=$record->emp_code,
					$advtype=$record->empname,
					$emp_code=$record->holiday_date,
					$empname=$record->holiday,
					$loan_adv_amount=$record->holiday_hours,
 					
				 	
					
				];
			}

			echo json_encode(['data' => $data]);
    }

	public function getFNattincentiveData() {
		$this->load->model('Loan_adv_model');
     //   $selectedDepartment = $this->input->post('date');
		$periodfromdate= $this->input->post('periodfromdate');
		$periodtodate= $this->input->post('periodtodate');
		$att_payschm =  $this->input->post('att_payschm');
		$holget =  $this->input->post('holget');
//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->getFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
		//var_dump($mccodes);
		$data = [];
			$totamt=0;
			foreach ($mccodes as $record) {
				$data[] = [
					$empcode=$record->emp_code,
					$empname=$record->empname,
					$department=$record->dept_desc,
					$periodfromdt=$periodfromdate,
					$periodtodt=$periodtodate,
					$incday=$record->incdays,
					$lvday=$record->lvdays,
					$inrate=$record->fn_att_inc_rate,
					$inamount=$record->incamt,

				];
				$totamt=$totamt+$record->incamt;
			}

			$data[] = [
				$empcode='',
			$empname='Grand Total',
			$department='',
			$periodfromdt='',
			$periodtodt='',
			$incday='',
			$lvday='',
			$inrate='',
			$inamount=$totamt,
		];
	
			echo json_encode(['data' => $data]);
    }

	public function getMNattincentiveData() {
		$this->load->model('Loan_adv_model');
     //   $selectedDepartment = $this->input->post('date');
		$periodfromdate= $this->input->post('periodfromdate');
		$periodtodate= $this->input->post('periodtodate');
		$att_payschm =  $this->input->post('att_payschm');
		$holget =  $this->input->post('holget');
//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->getMNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
//echo $periodfromdate,$periodtodate,$att_payschm;
 		//var_dump($mccodes);
		$data = [];
			foreach ($mccodes as $record) {
				$data[] = [
					$empcode=$record->emp_code,
					$empname=$record->empname,
					$periodfromdt=$periodfromdate,
					$periodtodt=$periodtodate,
					$incday=$record->incdays,
					$lvday=$record->lvdays,
					$inrate=$record->mn_att_inc_rate,
					$inamount=$record->incamt,
				];
			}

			echo json_encode(['data' => $data]);
    }


	public function getpayscheme() {
		$this->load->model('Loan_adv_model');
        $selectedDepartment = $this->input->post('ebno');
//		echo  'psch-'.$selectedDepartment;

        $mccodes = $this->Loan_adv_model->getpayscheme($selectedDepartment);
		$data = [];
		$cnt=count($mccodes);
	//	echo $cnt;
			foreach ($mccodes as $record) {
				$ebnames=$record->NAME;
				$data[] = [
					$ebid=$ebid,
					$ebname=$ebnames,
					 
				];
			}
			if ($cnt==0) {
				$ebid=0;
				$ebname='N.A';
			
			}
			$response = array(
				'success' => true,
				'empname' => $ebname 
			);
			echo json_encode($response);
    }

	public function exportdbfdata() {
		$periodfromdate= $this->input->get('periodfromdate');
		$periodtodate= $this->input->get('periodtodate');
		$att_payschm =  $this->input->get('att_payschm');
		$holget =  $this->input->get('holget');
		$payschemeName =  $this->input->get('payschemeName');
			 $company_name = $this->session->userdata('companyname');
			 $comp = $this->session->userdata('companyId');
			 $zt=1;

			 /////////////////////////////////// EJM Attendance Data////////////////////////30.04.24
$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        
echo $holget,$ldat;


if ($holget==20) {
	$mccodes = $this->Loan_adv_model->getejmattdata($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "Ejmcumld.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";
	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			//$aname=Trim($sard_help);
			//var_dump($aname);
		//	$logMsg.= $record->eb_no.",".$record->shift.",".$record->dept_code.",".$record->occu_code.",".$record->t_p.",".$record->mcnos.",".$record->rwhrs.",".$record->owhrs.",".$record->nwhrs.",".$record->fndate."\r\n";
			$logMsg.= $record->fnedate.",".$record->eb_no.",".$record->WORKING_HOURS.",".
			$record->NS_HRS.",".$record->HL_HRS.",".$record->STL_D.",".$record->PF_GROSS.",".$record->EPF
			.",".$record->bf.",".$record->cf.",".$record->HOL_AMT.",".$record->padv.",".$record->colnbal.",".
			$record->TOTAL_EARN."\r\n";


		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="EjmAttdata.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'Ejmcumulative.zip';
   $zt=1;
}


if ($holget==18) {
	$mccodes = $this->Loan_adv_model->getejmattdata($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "EjmAdata.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	//2024-04-30
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";
	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			//$aname=Trim($sard_help);
			//var_dump($aname);
			$logMsg.= $ldate.",".$record->eb_no.",".$record->shift.",".$record->dept_code.",".$record->occu_code.",".$record->t_p.",".$record->mcnos.",".$record->rwhrs.",".$record->owhrs.",".$record->nwhrs."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="EjmAttdata.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'EjmAttdata.zip';
   $zt=1;
}

////////////////////////

/////////////////////////////////// EJM Winding Production Data////////////////////////30.04.24
if ($holget==19) {
	$mccodes = $this->Loan_adv_model->getejmwinddata($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "EjmWprod.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";
	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			//$aname=Trim($sard_help);
			//var_dump($aname);
			$logMsg.= $record->eb_no.",".$record->deptcode.",".$record->occu_code.",".$record->shift.",".$record->wage_code.",".$record->prod.",".$ldate."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="EjmWindprod.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'EjmWindprod.zip';
   $zt=1;
}

////////////////////////


		if ($holget==1) {
			 $this->db->select('thht.holiday_tran_id, theod.emp_code, CONCAT(thepd.first_name, " ", IFNULL(thepd.middle_name, " "), " ", IFNULL(thepd.last_name, " ")) AS empname, date_format(hm.holiday_date,"%d-%m-%Y") holiday_date, hm.holiday,holiday_hours');
			 $this->db->from('tbl_hrms_holiday_transactions thht');
			 $this->db->join('tbl_hrms_ed_official_details theod', 'thht.eb_id = theod.eb_id', 'left');
			 $this->db->join('tbl_hrms_ed_personal_details thepd', 'thht.eb_id = thepd.eb_id', 'left');
			 $this->db->join('holiday_master hm', 'thht.holiday_id = hm.id', 'left');
			 $this->db->join('tbl_pay_employee_payscheme tpep', 'thht.eb_id = tpep.EMPLOYEEID', 'left');
			 $this->db->where('hm.holiday_date >=', $periodfromdate);
			 $this->db->where('hm.holiday_date <=', $periodtodate);
			 $this->db->where('thht.is_active', 1);
			 $this->db->where('tpep.status', 1);
			 $this->db->where('tpep.PAY_SCHEME_ID', $att_payschm);
			 $this->db->where('thepd.company_id', $comp);
			 $this->db->where('theod.is_active', 1);
		//	 $query = $this->db->get();
 		//	 $data=$query->result();
	
	//	 $periodfromdate= $this->input->get('periodfromdate');
	//	 $periodtodate= $this->input->get('periodtodate');
	//	 $att_payschm =  $this->input->get('att_payschm');
 
			  $sql="select emp_code,empname,sum(holiday_hours) holiday_hours from (
				SELECT thht.holiday_tran_id, theod.emp_code, 
				CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name, ''), ' ', IFNULL(thepd.last_name, ' ')) 
				AS empname, 
				date_format(hm.holiday_date, '%d-%m-%Y') holiday_date, hm.holiday, holiday_hours
				FROM tbl_hrms_holiday_transactions thht
				LEFT JOIN tbl_hrms_ed_official_details theod ON thht.eb_id = theod.eb_id
				LEFT JOIN tbl_hrms_ed_personal_details thepd ON thht.eb_id = thepd.eb_id
				LEFT JOIN holiday_master hm ON thht.holiday_id = hm.id
				LEFT JOIN tbl_pay_employee_payscheme tpep ON thht.eb_id = tpep.EMPLOYEEID ";
				$sql=$sql." where hm.holiday_date between '$periodfromdate' and '$periodtodate'
				AND thht.is_active = 1
				AND tpep.status = 1
				AND tpep.PAY_SCHEME_ID =$att_payschm
				AND thepd.company_id = '2'
				AND theod.is_active = 1
				) g group by emp_code,empname";
//				$this->db->query($sql);
//echo $sql;
				$data = $this->db->query($sql);
			  $fileContainer1 = "holiday.txt";
			  $filePointer1 = fopen($fileContainer1,"w+");
			  $fileContainer = "data".$att_payschm.".csv";
		      $filePointer = fopen($fileContainer,"w+");
		    $logMsg1='';
			$payhdr="";
			if ($att_payschm==151) { $payhdr='Main Payroll'; }
			if ($att_payschm==125) { $payhdr='Voucher  Payroll'; }
			if ($att_payschm==161) { $payhdr='Retired Payroll'; }
					 
			$logMsg='';
			$rowIndex = 4;
			$hd1="The Empire Jute Co Ltd";
			$hd1a=$payhdr;
			$hd2="Workers Holiday Ho for the period from  ".$periodfromdate." To ".$periodtodate;
			$pp='anaan  '.$this->db->last_query();
			$myp="new adta";
			$logMsg1.=$hd1."\n";
			$logMsg1.=$hd1a."\n";
			$logMsg1.=$hd2."\n";
			$logMsg1.="==================================================================================================="."\n";
			$logMsg1.="Emp Code   Name                            Holiday Hours                                          "."\n";
			$logMsg1.="==================================================================================================="."\n";
			$ln=6;
//			$row->linked_formula_id
			foreach ($data->result() as $row) {
			
				$ln++;
				$logMsg.= $row->emp_code.",".$row->holiday_hours."\r\n";
				if ($ln>58) {
					$logMsg1.=chr(12)."\n";
					$logMsg1.=$hd1."\n";
					$logMsg1.=$hd1a."\n";
					$logMsg1.=$hd2."\n";
					$logMsg1.="============================================================================="."\n";
					$logMsg1.="Emp Code   Name                            Holiday Hours                                           "."\n";
					$logMsg1.="============================================================================="."\n";
					$ln=6;		
				}
				$logMsg1.=$row->emp_code."     ".$row->empname.str_repeat(' ', 40- strlen($row->empname)).$row->holiday_hours. "\n";
				    $logMsg1.="-----------------------------------------------------------------------------"."\n";
				$ln++;
				
			}	
		
			$logMsg1.=chr(12)."\n";


			fputs($filePointer,$logMsg);
			fclose($filePointer);
	
			fputs($filePointer1,$logMsg1);
			fclose($filePointer1);
 	
	
 			$txt1="data.txt";
 			$txt1=$fileContainer;
			$txt2=$fileContainer1;
			$files = array($txt1,$txt2);
			$zipname = 'holidaydata.zip';
			$zt=1;
		}
		if ($holget==2) {
			$mccodes = $this->Loan_adv_model->getFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
//			echo $mccodes;
			$data = [];
			$fileContainer = "data1.csv";
  	        $filePointer = fopen($fileContainer,"w+");
			  $logMsg='';
			  $rowIndex = 4;
	   
			foreach ($mccodes as $record) {
					$empcode=$record->emp_code;
					$inamount=$record->incamt;
					$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
				}
		   fputs($filePointer,$logMsg);
		   fclose($filePointer);
		   $ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        

		   $mccodes = $this->Loan_adv_model->getFNattincentiveDatamdpl($periodfromdate,$periodtodate,$att_payschm,$holget);
		   $data = [];
		   $fileContainer1 = "ATINC.txt";
			 $filePointer1 = fopen($fileContainer1,"w+");

			 $fileContainer2 = "ATINC.csv";
			 $filePointer2 = fopen($fileContainer2,"w+");

			 $logMsg='';
			 $logMsgc='';
			 $rowIndex = 4;
	  
		   foreach ($mccodes as $record) {
				   $empcode=$record->emp_code;
				   $inamount=$record->incamt;
				   $inrate=$record->fn_att_inc_rate;
				   $incdays=$record->incdays;
				   $incd='';	
				   if ($incdays<10) {
					   $incd='0'.$incdays.'00';
				   }					   
				if ($incdays>=10) {
					$incd=$incdays.'00';
				}					   
				$inr=' ';
				   $rv='V';
				   if ($empcode>='10000' and $empcode<='18000') { $rv='R';}
				   if ($inrate==0.5 and $rv=='V') {$inr='N';}
				
				   $logMsg.= $rv.$record->emp_code.$inr.$incd.
				   "\r\n";

				  $logMsgc.=$record->emp_code.",".$record->incamt.",".$ldate."\r\n";	

			   }
		  fputs($filePointer1,$logMsg);
		  fclose($filePointer1);

		  fputs($filePointer2,$logMsgc);
		  fclose($filePointer2);




		  $dataf = [];
		  $fileContainer4 = "fest.csv";
			$filePointer4 = fopen($fileContainer4,"w+");
			$logMsg4='';
			$rowIndex = 4;

		  $fcodes = $this->Loan_adv_model2->getholleavedata($periodfromdate,$periodtodate,$holget);
		  foreach ($fcodes as $record) {
			$empcode=$record->emp_code;
			$inamount=$record->holiday_hours;
  		 
 
		   $logMsg4.=$record->emp_code.",".$record->holiday_hours.",0,".$ldate."\r\n";	

		}
		   fputs($filePointer4,$logMsg4);
		  fclose($filePointer4);




			$txt1=$fileContainer;
			$txt2=$fileContainer1;
			$txt3=$fileContainer2;
			$txt4=$fileContainer4;
			
		   $files = array($txt1,$txt2,$txt3,$txt4);
		   $zipname = 'mainfnincdat.zip';
		   $zt=1;

		}
	   if ($holget==3) {
		$mccodes = $this->Loan_adv_model->getFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
		$data = [];
		$fileContainer = "data.csv";
		  $filePointer = fopen($fileContainer,"w+");
		  $logMsg='';
		  $rowIndex = 4;
   
		foreach ($mccodes as $record) {
				$empcode=$record->emp_code;
				$inamount=$record->incamt;
				$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
			}
	   fputs($filePointer,$logMsg);
	   fclose($filePointer);
	   $txt1="data.txt";
		$txt1=$fileContainer;
	   $files = array($txt1);
	   $zipname = 'othersfnincdata.zip';
	   $zt=1;
	}

   if ($holget==4) {
	$mccodes = $this->Loan_adv_model->getMNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
	$data = [];
	$fileContainer = "data.csv";
	  $filePointer = fopen($fileContainer,"w+");
	  $logMsg='';
	  $rowIndex = 4;

	foreach ($mccodes as $record) {
			$empcode=$record->emp_code;
			$inamount=$record->incamt;
			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="data.txt";
	$txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'othersmnincdata.zip';
   $zt=1;
}
if ($holget==5) {

	$sql="UPDATE tbl_pay_components_custom 
	SET STATUS = 0 
	WHERE FROM_DATE = '".$periodfromdate."' 
    AND TO_DATE = '".$periodtodate."' and STATUS =1
    AND EMPLOYEEID IN (SELECT EMPLOYEEID FROM tbl_pay_employee_structure WHERE PAYSCHEME_ID = ".$att_payschm.")";
 	$this->db->query($sql);


	 $sql="select DISTINCT(PAYSCHEME_ID) payscheme_id,COMPONENT_ID,ifnull(linked_formula_id,0) linked_formula_id,cname  from ( 
		select tpes.EMPLOYEEID ,tpes.PAYSCHEME_ID,tpes.COMPONENT_ID,tpcil.linked_formula_id,tpc.NAME cname 
		from tbl_pay_employee_structure tpes 
		join tbl_pay_components tpc on tpes.COMPONENT_ID =tpc.ID 
		left join EMPMILL12.tbl_pay_custom_input_link tpcil on tpes.PAYSCHEME_ID =tpcil.payscheme_id and 
		tpes.COMPONENT_ID =tpcil.component_id 
		where tpes.PAYSCHEME_ID =".$att_payschm." and tpes.STATUS =1 and tpc.IS_EXCEL_DOWNLOADABLE =1 and tpc.STATUS =1
		) g"; 
		$q = $this->db->query($sql);
			foreach($q->result() as $row){
				$lnformula = $row->linked_formula_id;
				$compponentid=$row->COMPONENT_ID;
				echo $compponentid.'==='.$lnformula."<br>";
				if ($lnformula>0) {
					$this->Loan_adv_model->getattwagesins($periodfromdate,$periodtodate,$att_payschm,$holget,$lnformula,$compponentid);
				}	
			}
 
	

	$mccodes = $this->Loan_adv_model->getattwagesData($periodfromdate,$periodtodate,$att_payschm,$holget);
	$data = [];
	$fileContainer = "attdata.csv";
	  $filePointer = fopen($fileContainer,"w+");
	  $logMsg='';
	  $rowIndex = 4;
	  $logMsg.= 'Emp Code'.",".'Working Hrs'.",".'Fest Hours'.",".'Ot Hours'.",".'NS Hrs'.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";

	foreach ($mccodes as $record) {
			$empcode=$record->emp_code;
			$logMsg.=$as.$record->emp_code.",".$record->whrs.",".$record->festhrs.",".$record->othrs.",".$record->nshrs.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="data.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'attendance.zip';
   $zt=1;
}
if ($holget==6) {
	$mccodes = $this->Loan_adv_model->getmillwndData($periodfromdate,$periodtodate,$att_payschm,$holget);
//	var_dump($mccodes);
	$data = [];
	$fileContainer = "millwnd.txt";
	  $filePointer = fopen($fileContainer,"w+");
	  $logMsg='';
//	  $rowIndex = 4;
//	  $logMsg.= 'Emp Code'.",".'Working Hrs'.",".'Fest Hours'.",".'Ot Hours'.",".'NS Hrs'.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";

	foreach ($mccodes as $record) {
			$eb=$record->eb_no;
			
			$logMsg.= $record->dt.",".$record->dept_code.",".$eb.",".$record->whrs.",0,".
			$record->othrs.",0,".$record->shift.",,".$record->OCCU_CODE.",".$record->occu_id.
			"\r\n";
//		echo $logmsg;
		}
		fputs($filePointer,$logMsg);
		fclose($filePointer);
		$txt1="data.txt";
		 $txt1=$fileContainer;
		$files = array($txt1);
		$zipname = 'attendance.zip';
		$zt=1;
	}


if ($holget==9) {
	$mccodes = $this->Loan_adv_model->getnjmwagesData($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "NjatData.txt";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";

	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			$wname=$record->wrk_name;
			$aname=Trim($wname);
			//var_dump($aname);
			//$logMsg.= $record->dept_code.",".$record->eb_no.",".$record->wrk_name.",".$record->T1.",".$record->T2.",".$record->PH.",".$record->OTT.",".$record->OTP.",".$record->NS.",".$record->ED.",".$record->yearmn."\r\n";
			$logMsg.= $record->dept_code.",".$record->eb_no.",".$aname.",".$record->T1.",".$record->T2.",".$record->PH.",".$record->OTT.",".$record->OTP.",".$record->NS.",".$record->ED.",0,".$record->yearmn."\r\n";
		}
	//	$logMsg.= '999'.",".'999999'.",".''.",".''.",".''.",".''.",".''.",".''.",".''.",".''.",".'$record->yearmn'."\r\n";
	
   fputs($filePointer,$logMsg);
   fclose($filePointer);
 //  echo $logMsg;
   $txt1=$fileContainer;
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'njmatt.zip';
   $zt=1;
}
//////// end njm data/////////////////
//////// change njm Leave data 28.12.23////////////////
if ($holget==10) {
	$mccodes = $this->Loan_adv_model->getnjmleavData($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "Njlvdata.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";

	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			$wname=$record->wrk_name;
			$afg=($record->fl/8)+$record->el;
			$aname=Trim($wname);
			//var_dump($aname);
			//$logMsg.= $record->dept_code.",".$record->eb_no.",".$record->wrk_name.",".$record->T1.",".$record->T2.",".$record->PH.",".$record->OTT.",".$record->OTP.",".$record->NS.",".$record->ED.",".$record->yearmn."\r\n";
			$logMsg.= $record->dpt.",".$record->eb_no.",".$aname.",".$record->dept_desc.",".$record->fl.",".$record->sl.",".$record->ul.",".$record->el.",".$record->ss.",".$record->ml.",".$afg.",".$record->yearmn."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="njmdata.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'njmLeaveData.zip';
   $zt=1;
}
//////// end njm data/////////////////
//////// change njm sardar/helper Hours Trasfer data 28.12.23////////////////
if ($holget==11) {
	$mccodes = $this->Loan_adv_model->getnjmsdrhrlData($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "NjshData.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";

	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			//$aname=Trim($sard_help);
			//var_dump($aname);
			//$logMsg.= $record->dept_code.",".$record->eb_no.",".$record->wrk_name.",".$record->T1.",".$record->T2.",".$record->PH.",".$record->OTT.",".$record->OTP.",".$record->NS.",".$record->ED.",".$record->yearmn."\r\n";
			$logMsg.= $record->eb_no.",".$record->mach_shr_code.",".$record->sard_help.",".$record->wrkhrs.",".$record->GROUPP.",".$record->dept_code.",".$record->MASTDEPT."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="njmdata.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'njmSardhelpData.zip';
   $zt=1;
}

if ($holget==12) {
	$mccodes = $this->Loan_adv_model->getnjmbeamData($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget);
	$data = [];
	$fileContainer = "NjbmData.Csv";
	$filePointer = fopen($fileContainer,"w+");
	  $logMsg ='';
	  $rowIndex =4;
	 // $logMsg.= 'Dept Code'.",".'Emp Code'.",".'Emp_Name'.",".'Piece_Rate_Type_1'.",".'Piece_Rate_Type_2'.",".'Piece_Rate_Hours'.",".'Time_Rate_OT_Hours'.",".'Piece_Rate_OT_Hours'.",".'NS_Hrs'.",".'ED'.",".'Year_Month'.",".'Frome_Date'.",".'To_Date'.",".'Pay_Schime_Name'."\r\n";
	foreach ($mccodes as $record) {
			$empcode=$record->eb_no;
			//$aname=Trim($sard_help);
			//var_dump($aname);
			//$logMsg.= $record->dept_code.",".$record->eb_no.",".$record->wrk_name.",".$record->T1.",".$record->T2.",".$record->PH.",".$record->OTT.",".$record->OTP.",".$record->NS.",".$record->ED.",".$record->yearmn."\r\n";
			$logMsg.= $record->eb_no.",".$record->beam_wage_code.",".$record->cuts.",".$record->whrs.",".
			$record->worked_designation_id.",".$record->dept_code.",".$record->yearmn."\r\n";
		}
   fputs($filePointer,$logMsg);
   fclose($filePointer);
   $txt1="njmdata.txt";
   $txt1=$fileContainer;
   $files = array($txt1);
   $zipname = 'njmbeamData.zip';
   $zt=1;
}

	$zip = new ZipArchive;
	$zip->open($zipname, ZipArchive::CREATE);
	foreach ($files as $file) {
	  $zip->addFile($file);
	}
	$zip->close();
 if ( $zt==1)  {	
/* 
	if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
		$zip->addFile($fileContainer, basename($fileContainer));
		$zip->close();
		echo 'ZIP archive created successfully.'.'--'.$zt;
	} else {
//		echo 'Failed to create ZIP archive.';
	}
*/
	ob_clean();
	header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="' . $zipname . '"');
    header('Content-Length: ' . filesize($zipname));
    header('Pragma: no-cache');
    readfile($zipname);

} else {
	echo 'generate txt created successfully.'.'--'.$zt;
	
	header('Content-Type: application/text');
	header('Content-disposition: attachment; filename='.$txt1);
	header('Content-Length: ' . filesize($txt1));
	readfile($txt1);
 }	
			unlink($fileContainer);
 			unlink($zipname);
	
	
	
		}

		public function payslipprint() {
			$periodfromdate= $this->input->get('periodfromdate');
			$periodtodate= $this->input->get('periodtodate');
			$att_payschm =  $this->input->get('att_payschm');
			$holget =  $this->input->get('holget');
			$payschemeName =  $this->input->get('payschemeName');
				 $company_name = $this->session->userdata('companyname');
				 $comp = $this->session->userdata('companyId');
				 $fileContainer = "payslip.txt";
				 $filePointer = fopen($fileContainer,"w+");

				 if ($holget==3) {
					$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 72);
					  $hd1="DP S SRL T/No. Name            F/E Date | DP S SRL T/No. Name            F/E Date |";
					  $hd2="ESI NO     TOT-HRS MISC-EARN   TOT-EARN | ESI NO     TOT-HRS MISC-EARN   TOT-EARN |";
					  $hd3="ESIC  ADVANCE  MISC_DED  TOT-DED    NET | ESIC  ADVANCE  MISC_DED  TOT-DED    NET |";	
				   $bkline="                                        |                                         |";	
				    $bklin="----------------------------------------|-----------------------------------------|";	
					  $pg=1;
					  $rowIndex = 4;
					  $totamt=0;
					  $tothrs=0;
					  $lnn=8;
					  $sl=1;
					  $rnop=0;	
					  $lndet1='';
					  $lndet2='';
					  $lndet3='';
					  $lndet4='';
					  $lndet5='';
					  $lndet6='';
					  $dpc='';
					  $enddate=	substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
					  $logMsg='';
				//	var_dump($mccodes);	
					  foreach ($mccodes as $record) {
						if ($dpc<>$record->dept_code) {
							if (strlen($dpc)>0) {
								if (fmod($rnop, 2)==1)	{
									$logMsg .=$bkline."\n";
									$logMsg .=$hd1."\n";
									$logMsg .=$lndet1."\n";
									$logMsg .=$bkline."\n";
									$logMsg .=$hd2."\n";
									$logMsg .=$lndet2."\n";
									$logMsg .=$bkline."\n";
									$logMsg .=$hd3."\n";
									$logMsg .=$lndet3."\n";
									$logMsg .=$bkline."\n";
									$logMsg .=$bklin."\n";
								}	
						
								$logMsg .= Chr(12)."\n";
								$pg=1;	
								$rnop=0;
							}
							$dpc=$record->dept_code;
						}

						if ($pg>5) {
							$logMsg .= Chr(12)."\n";
							$pg=1;
						}														
					


						$whrs = $record->working_hrs+$record->ns_hrs+$record->hol_hrs;
						$mearn= $record->arrear_plus;
						$mded= $record->arrear_minus;
						$tearn= $record->total_earn;
						$esic=number_format($record->esi,0);
						$tded=  $record->gross_deduction;
						$np=  number_format($record->netpay,0);
						$adv=$record->exadvance;
					 	$rnop=$rnop+1;
						$rn=fmod($rnop, 2);
						$sln=str_repeat(' ', 3 - strlen($rnop)).$rnop;

						if (fmod($rnop, 2)==1)	{
							$lndet1='';
							$lndet2='';
							$lndet3='';
							$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
							substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
							.$enddate.' | ';
							$lndet2.=str_repeat(' ', 10 - strlen($record->esi_no)).$record->esi_no.
							str_repeat(' ', 8 - strlen($whrs)).$whrs.
							str_repeat(' ', 10 - strlen($mearn)).$mearn.
							str_repeat(' ', 11 - strlen($tearn)).$tearn
							.' | ';
							$netp=str_repeat('*', 6 - strlen($np)).$np;
							$lndet3.=str_repeat(' ', 4 - strlen($esic)).$esic.
							str_repeat(' ', 9 - strlen($adv)).$adv.
							str_repeat(' ', 10 - strlen($mded)).$mded.
							str_repeat(' ', 9 - strlen($tded)).$tded.
							str_repeat(' ', 7 - strlen($netp)).$netp.
							' | ';
						
						} else { 
							$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
							substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
							.$enddate.' | ';
							$lndet2.=str_repeat(' ', 10 - strlen($record->esi_no)).$record->esi_no.
							str_repeat(' ', 8 - strlen($whrs)).$whrs.
							str_repeat(' ', 10 - strlen($mearn)).$mearn.
							str_repeat(' ', 11 - strlen($tearn)).$tearn
							.' | ';
							$netp=str_repeat('*', 6 - strlen($np)).$np;
							$lndet3.=str_repeat(' ', 4 - strlen($esic)).$esic.
							str_repeat(' ', 9 - strlen($adv)).$adv.
							str_repeat(' ', 10 - strlen($mded)).$mded.
							str_repeat(' ', 9 - strlen($tded)).$tded.
							str_repeat(' ', 7 - strlen($netp)).$netp.
							' | ';

							$logMsg .=$bkline."\n";
							$logMsg .=$hd1."\n";
							$logMsg .=$lndet1."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$hd2."\n";
							$logMsg .=$lndet2."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$hd3."\n";
							$logMsg .=$lndet3."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$bklin."\n";
						
 						$pg++;	
					
						}}
						if (fmod($rnop, 2)==1)	{
							$logMsg .=$bkline."\n";
							$logMsg .=$hd1."\n";
							$logMsg .=$lndet1."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$hd2."\n";
							$logMsg .=$lndet2."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$hd3."\n";
							$logMsg .=$lndet3."\n";
							$logMsg .=$bkline."\n";
							$logMsg .=$bklin."\n";
						}	

						$logMsg .= Chr(12)."\n";
					fputs($filePointer,$logMsg);
					fclose($filePointer);
					 $txt1="payslip.txt";
					$txt1=$fileContainer;
					$files = array($txt1);
					$zipname = 'payslip.zip';

	 			   					}			 
			if ($holget==4 || $holget ==5 ) {
					$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 79);
				//	  $logMsg=$company_name."\n";

   					$pg=1;
					  $logMsg='';
					  $ln1=$payschemeName."\n";
					  $ln2= "Voucher Payslip for FNE ".$periodtodate.'              '.'Dept Code ';
					  $ln3= $line."\n";
					  $ln4= '  EBNO  | NAME                | TOTAL | RATE  | ADJ AMT | AMOUNT   | SIGNATURE '."\n";
					  $ln6= '        |                     | HOURS |       | ADV DED |          | '."\n";
					  $ln7= '        |                     |       |       | ADH AMT |          |'."\n";
					  $ln5.= $line."\n";

					  $totamt=0;
					  $tothrs=0;
					  $gtotamt=0;
					  $gtothrs=0;
					  $lnn=1;
					  $dpc='';
					  $noslp=1;
					foreach ($mccodes as $record) {
						if ($dpc<>$record->dept_code) { 	
							if ($pg>1) {
//								$logMsg .= Chr(12)."\n";
								$gt="Dept Total ".$dpc;
								$blnk='';
								$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
								substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
								str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
								str_repeat(' ', 8 - strlen($blnk)).$blnk.
								str_repeat(' ', 8 - strlen($blnk)).$blnk.
								str_repeat(' ', 10 - strlen($totamt)).$totamt.
								"\n";
			
								$totamt=0;
								$tothrs=0;
								$logMsg .= 'pgb'.$line."\n";
								$logMsg .= Chr(12)."\n";
								$lnn=1;
								$noslp=1;
							}	

							$dpc=$record->dept_code;
							$dptcode=$dpc;
							$pg++;
						}		

						if ($lnn>58) {
							$logMsg .= Chr(12)."\n";
							$lnn=1;
						}	
							if ($noslp>=6) {
								$logMsg .= 'pgb'.$line."\n";
								$logMsg .= Chr(12)."\n";
								$lnn=1;
								$noslp=1;
							} 
	//					if  ($lnn==1) {
							$dpc=$record->dept_code;
							$dptcode=$dpc;
							$logMsg .=$payschemeName."\n";
							$logMsg .= $ln2.$dptcode."\n";
							$logMsg .= $noslp.$ln3;
							$logMsg .= $ln4;
							$logMsg .= $ln6;
							$logMsg .= $ln7;
							$logMsg .= $ln5;
							$lnn=8;
	//					}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
							$whrs = number_format($record->WORKING_HOURS+$record->HOLIDAY_HR+$record->OT_HOURS, 2);
							$arr=number_format($record->ARR_PLUS+$record->ARR_MINUS, 2);
							$adv=number_format($record->ADVANCE, 2);
							$npay=number_format($record->Net_Payble, 0);
							$RATE= $record->RATE_PER_DAY;
							$logMsg .=$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
							substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
							str_repeat(' ', 9 - strlen($whrs)).$whrs.
							str_repeat(' ', 8 - strlen($RATE)).$RATE.
							str_repeat(' ', 10 - strlen($arr)).$arr.
							str_repeat(' ', 10 - strlen($npay)).$npay.
							$$periodtodate.
							"\n";
							$bln='';
							$dbnc='Dept Code '.$dpc;
							$logMsg .=$bln.str_repeat(' ', 10- strlen($bln)).
							substr($bln,0,20).str_repeat(' ', 20 - strlen(substr($bln,0,20))).
							str_repeat(' ', 9 - strlen($bln)).$bln.
							str_repeat(' ', 8 - strlen($bln)).$bln.
							str_repeat(' ', 10 - strlen($adv)).$adv.
							"\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
   						    $logMsg .= $line."\n";
							$totamt=$totamt+$record->Net_Payble;
							$tothrs=$tothrs+$whrs;
							$lnn=$lnn+6;
							$gtotamt=$gtotamt+$record->Net_Payble;
							$gtothrs=$gtothrs+$whrs;
							$noslp++;
							

						}
						$logMsg .= Chr(12)."\n";
						$logMsg .= $line."\n";
					fputs($filePointer,$logMsg);
					fclose($filePointer);
					 $txt1="payslip.txt";
					$txt1=$fileContainer;
					$files = array($txt1);
					$zipname = 'payslip.zip';
			   }
			   if ($holget==6 ) {
					if ($att_payschm==159) {$payschemeName=' (1)';}
					if ($att_payschm==160) {$payschemeName=' (2)';}
					if ($att_payschm==158) {$payschemeName=' (3)';}
				
					$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
				  	$line = str_repeat('-', 136);
			//	  $logMsg=$company_name."\n";
     				$fnedate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
                  $pg=1;
				  $logMsg='';
				  $ln1=chr(14).$payschemeName."\n";
				  $ln2= 'Pay Ticket for FNE '.$fnedate."\n";
				  $ln3= $line."\n";
				  $ln4= 'Dept Code    EB No             W.Hours    BASIC        (12%)    Tiffin    Washing Allow     P.Tax     Total Earn   R/off        (13.00%)|  PAY TICKET '."\n";
				  $ln6= 'Dept Name    Name                Rate     Other        (0.75%)    Conv       Gross2         Advance   Total Ded   Net Pay        (3.25%)|  ---------- '."\n";
				  $ln7= '                                                                         Adjust  Amt                                                    |'."\n";
				  $ln5.= $line."\n";

				  $totamt=0;
				  $tothrs=0;
				  $gtotamt=0;
				  $gtothrs=0;
				  $lnn=1;
				  $dpc='';
				foreach ($mccodes as $record) {
					if ($dpc<>$record->dept_code) { 	
						if ($pg>1) {
						}	
						$dpc=$record->dept_code;
						$dptcode=$dpc;
						//$pg++;
					}		

					if ($pg>5) {
						$logMsg .= Chr(12).chr(18)."\n";
						$lnn=1;
						$pg=1;
					}	

//					if  ($lnn==1) {
						$logMsg .=$ln1;
						$logMsg .=$ln2;
						$logMsg .= chr(15).$ln3;
						$logMsg .= $ln4;
						$logMsg .= $ln6;
						$logMsg .= $ln7;
						$logMsg .= $ln5;
						$lnn=8;
//					}			
					///////////pintu///////////////											//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
						$whrs = number_format($record->WORKING_HOURS+$record->NIGHT_SHIFT_HR+$record->HOLIDAY_HR,2);
						//+$record->HOLIDAY_HR+$record->OT_HOURS, 2);
						//$arr=number_format($record->ARR_PLUS+$record->ARR_MINUS, 2);
						//$adv=number_format($record->ADVANCE, 2);
						//$npay=number_format($record->Net_Payble, 0);
						//$RATE= $record->RATE_PER_DAY;
						$Basic=$record->BASIC;
						$EPF=$record->EPF;
						$ESI=$record->ESI;
						$TIFFIN_AMOUNT=$record->TIFFIN_AMOUNT;
						$WASHING_ALLOWANCE=$record->WASHING_ALLOWANCE;
						$EMPL_EPF=$record->EMPL_EPF;
						$dept_code=$record->dept_code;
						//$ptax=$record->ptax;
						$ptax = number_format($record->ptax,2);
						//$GROSS2=$record->GROSS2;
						$B_F = number_format($record->B_F,2);
						$RATE_PER_DAY = number_format($record->RATE_PER_DAY,2);
						$OTHER_ALLOWANCE=number_format($record->OTHER_ALLOWANCE,2);
						$ESI=number_format($record->ESI,2);
						$CONV_ALLOWANCE=number_format($record->CONV_ALLOWANCE,2);
						$GROSS2=number_format($record->GROSS2,2);
						$ADVANCE=number_format($record->ADVANCE,2);
						$GROSS_DED=number_format($record->GROSS_DED,2);
						$Net_Payble=number_format($record->Net_Payble,2);
						$EMPL_ESI=number_format($record->EMPL_ESI,2);
						$uanno=$record->pf_uan_no;
						$esino=$record->esi_no;
						//$B_F=$record->B_F;

						$logMsg .= '             ' .$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
						//substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
						str_repeat(' ', 16 - strlen($whrs)).$whrs.
						//str_repeat(' ', 8 - strlen($RATE)).$RATE.
						str_repeat(' ', 11 - strlen($Basic)).$Basic.
						str_repeat(' ', 8 - strlen($EPF)).$EPF.
						//str_repeat(' ', 6 - strlen($npay)).$ESI.
						str_repeat(' ', 14 - strlen($TIFFIN_AMOUNT)).$TIFFIN_AMOUNT.
						str_repeat(' ', 15 - strlen($WASHING_ALLOWANCE)).$WASHING_ALLOWANCE.
						str_repeat(' ', 11 - strlen($ptax)).$ptax.
						str_repeat(' ', 14 - strlen($GROSS2)).$GROSS2.
						str_repeat(' ', 11- strlen($B_F)).$B_F.
						str_repeat(' ', 13 - strlen($EMPL_EPF)).$EMPL_EPF.'|  EB NO : '.$record->eb_no.
						"\n";
						$bln='';
						$dbnc=' '.$dpc;
						$logMsg .= $dbnc.'          '.
						//substr($dbnc,0,20).str_repeat(' ', 20 - strlen(substr($dbnc,0,20))).
						//str_repeat(' ', 0 - strlen($dbnc)).$dbnc.
						substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
						str_repeat(' ', 6 - strlen($RATE_PER_DAY)).$RATE_PER_DAY.
						str_repeat(' ', 11 - strlen($OTHER_ALLOWANCE)).$OTHER_ALLOWANCE.
						str_repeat(' ', 8 - strlen($ESI)).$ESI.
						str_repeat(' ', 14 - strlen($CONV_ALLOWANCE)).$CONV_ALLOWANCE.
						str_repeat(' ', 15 - strlen($GROSS2)).$GROSS2.
						str_repeat(' ', 11 - strlen($ADVANCE)).$ADVANCE.
						str_repeat(' ', 14 - strlen($GROSS_DED)).$GROSS_DED.
						str_repeat(' ', 11 - strlen($Net_Payble)).$Net_Payble.
						str_repeat(' ', 13 - strlen($EMPL_ESI)).$EMPL_ESI.'|  NAME  :'.$record->wname.
		//				str_repeat(' ', 8 - strlen($bln)).$bln.
		//				str_repeat(' ', 10 - strlen($adv)).$adv.'     |'.
						"\n";
						$logMsg .= str_repeat(' ', 136 - strlen($bln)).$bln.'|  NET PAY '.$Net_Payble."\n";
//						$logMsg .= 'ESI NO: '.$esino.'              UAN NO: '.$uanno.str_repeat(' ', 50 - strlen($bln)).$bln.'     |'.
//						"\n";

						$logMsg .= ' '."\n";
					//	$logMsg .= ' '."\n";
					//	$logMsg .= ' '."\n";
						$logMsg .= $line."\n";
						$totamt=$totamt+$record->Net_Payble;
						$tothrs=$tothrs+$whrs;
						$twash=$twash+$record->WASHING_ALLOWANCE;
						$tconv=$tconv+$record->CONV_ALLOWANCE;
						$tiffin=$tiffin+$record->TIFFIN_AMOUNT;
						$tadvance=$tadvance+$record->vADVANCE;
						$tgross=$tgross+$record->TOTAL_EARN;
						$tdeduct=$tdeduct+$record->GROSS_DED;
						$tother=$tother+$record->OTHER_ALLOWANCE;
						$lnn=$lnn+6;
						$gtotamt=$gtotamt+$record->Net_Payble;
						$gtothrs=$gtothrs+$whrs;
						$gtiffin=$gtiffin+$record->TIFFIN_AMOUNT;
						$gconv=$gconv+$record->CONV_ALLOWANCE;
						$gtwash=$gtwash+$record->WASHING_ALLOWANCE;
						$gtadvance=$gtadvance+$record->vADVANCE;
						$gtgross=$gtgross+$record->TOTAL_EARN;
						$gtdeduct=$gtdeduct+$record->GROSS_DED;
						$gtother=$gtother+$record->OTHER_ALLOWANCE;
						$logMsg .= $line."\n";
						$pg++;
					}
					$logMsg .= Chr(12)."\n";
					$logMsg .= $line."\n";
/*					
					$gt="Grand Total";
					$blnk='';
					$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
					substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
					str_repeat(' ', 9 - strlen($gtothrs)).$gtothrs.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtother)).$gtother.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtiffin)).$gtiffin.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gconv)).$gconv.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtwash)).$gtwash.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 6 - strlen($gtadvance)).$gtadvance.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtgross)).$gtgross.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtdeduct)).$gtdeduct.
					str_repeat(' ', 3 - strlen($blnk)).$blnk.
					str_repeat(' ', 9 - strlen($gtotamt)).$gtotamt.
					"\n";
*/
					$logMsg .= $line."\n";
					$logMsg .= Chr(12)."\n";
				fputs($filePointer,$logMsg);
				fclose($filePointer);
				 $txt1="payslip.txt";
				$txt1=$fileContainer;
				$files = array($txt1);
				$zipname = 'payslip.zip';
		   }




		   if ($holget==1 || $holget==2 || $holget==7) {
			$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
		//	var_dump($mccodes);
			$line = str_repeat('-', 136);
			 $fnedate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,2,2);
		  $pg=1;
		  $logMsg='';
		  $lnn=1;
		  $dpc='';
		 $rnop=0;					
		 foreach ($mccodes as $record) {
				if ($dpc<>$record->dept_code ) { 	
					if (strlen($dpc)>0) {
						$logMsg .= Chr(12);
				}	
				
				
				$dpc=$record->dept_code;
				$dptcode=$dpc;
				
				
				$lnn=1;
				$pg=1;
				$rnop=0;					
				
			}		

			if ($pg>6) {
				$logMsg .= Chr(12);
				$lnn=1;
				$pg=1;
			}
			/*
			$logMsg .= '             ' .$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
			substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
			str_repeat(' ', 16 - strlen($whrs)).$whrs.
			str_repeat(' ', 8 - strlen($RATE)).$RATE.
			str_repeat(' ', 11 - strlen($Basic)).$Basic.
		number_format($record->B_F,2);*/
			$rnop=$rnop+1;
			$sln=str_repeat('0', 3 - strlen($rnop)).$rnop;
			$bln='';
			str_replace(',', '', $originalNumber);
			$whrs=str_replace(',', '',number_format($record->WORKING_HOURS,2));
			$nhrs=str_replace(',', '',number_format($record->NS_HRS,1));
			$lhrs=str_replace(',', '',number_format($record->LS_HRS,1));
			$fhrs=str_replace(',', '',number_format($record->HL_HRS,0));
			$stld=str_replace(',', '',number_format($record->STL_D,0));
			$cpfc=str_replace(',', '',number_format($record->C_PF_CONT,0));
			$cfpfc=str_replace(',', '',number_format($record->C_EPF_CONT,0));
			$gpay=str_replace(',', '',number_format($record->GROSS_PAY,2));
			$wday=number_format(round($record->WRK_DAYS,0),0);
			$cwday=round($record->C_WORK_DAY,1);
			$cbn=str_replace(',', '',number_format($record->C_BON_ERN,2));
			$bf=number_format($record->B_F,2);
		//	$wday=number_format($record->WRK_DAYS,2);
			$pbas=str_replace(',', '',number_format($record->PROD_BASIC,2));
			$brate=0;
			if ($record->WORKING_HOURS>0) {
				$brate=number_format($record->PROD_BASIC/$record->WORKING_HOURS,3);
			}	
			if ($holget==2 && $record->WORKING_HOURS>0) {
				$brate=number_format($record->BASIC_RATE,3);
			}	
			$fbas=str_replace(',', '',number_format($record->TIME_RATED_BASIC+$record->FIX_BASIC,2));
			//$fbas=str_replace(',', '',number_format($record->TIME_RATED_BASIC,2));
			$da=round($record->DA,1);
			$incra=str_replace(',', '',number_format($record->INCREMENTA,2));
			$nsa=$record->NS_AMOUNT;
			$fwgs=str_replace(',', '',number_format($record->HOL_AMT,2));
			$loffwgs=str_replace(',', '',number_format($record->LAYOFF_WGS,2));
			$pfg=str_replace(',', '',number_format($record->PF_GROSS,2));
			$mern=str_replace(',', '',number_format($record->MISS_EARN,2));
			$stlwgs=str_replace(',', '',number_format($record->STL_WGS,2));
			$hra=str_replace(',', '',number_format($record->HRA,2));
			$npay=str_replace(',', '',number_format($record->NET_PAY,0));
			$tded=str_replace(',', '',number_format($record->TOTAL_DEDUCTION,2));
			$tern=str_replace(',', '',number_format($record->TOTAL_EARN,2));
			$cf=number_format($record->C_F,2);
			$coloan=str_replace(',', '',number_format($record->CO_LOAN,0));
			$miscded=0;
			$psadv=$record->PUJA_ADVANCE+$record->STL_ADVANCE;
			$psadv=str_replace(',', '',number_format($psadv,0));
			$ptax=str_replace(',', '',number_format($record->P_TAX,2));
			$esia=str_replace(',', '',number_format($record->ESIC,0));
			$epf=str_replace(',', '',number_format($record->EPF,0));
			$colnb=str_replace(',', '',number_format($record->CO_LOAN_BAL,2));
			$ptax=str_replace(',', '',number_format($record->P_TAX,0));
			$dsg=substr($record->desig,0,4);
			$wnam=substr($record->wname,0,15);
			$wname=$record->wname;
//echo $mearn.'=='.strlen($mearn).'=='.$record->eb_no.'----'.$record->MISS_EARN.'</br>' ;
//				$logMsg.=$bln."\n";
			$logMsg.=' '.$record->dept_code.'/'.$record->shift.'/'.$sln.'   ' .str_repeat(' ', 1).$record->eb_no.
			str_repeat(' ', 9- strlen($record->eb_no)).
			$record-> time_piece.' '.$record->dept_code.'/'.$record->shift.'/'.$sln.' ' .str_repeat(' ', 3).
			$record->eb_no.str_repeat(' ', 9- strlen($record->eb_no)).$dsg.str_repeat(' ', 4- strlen($dsg)).' '.
			substr($record->esi_no,3,7).''.str_repeat(' ', 8 - strlen($brate)).$brate.
			str_repeat(' ', 7 - strlen($whrs)).$whrs.
			str_repeat(' ', 5 - strlen($nhrs)).$nhrs.
			str_repeat(' ', 4 - strlen($lhrs)).$lhrs.
			str_repeat(' ', 4 - strlen($fhrs)).$fhrs.
			str_repeat(' ', 3 - strlen($bln)).$bln.
			str_repeat(' ', 4 - strlen($stld)).$stld.
			str_repeat(' ', 5 - strlen($cpfc)).$cpfc.str_repeat(' ', 5 - strlen($cfpfc)).$cfpfc.
			str_repeat(' ', 8 - strlen($gpay)).$gpay.
			str_repeat(' ', 3 - strlen($wday)).$wday.
			str_repeat(' ', 6 - strlen($cwday)).$cwday.
			str_repeat(' ', 9 - strlen($cbn)).$cbn.
			str_repeat(' ', 5 - strlen($bf)).$bf.
			"\n";
			$logMsg.=$bln."\n";
			$logMsg.=$bln."\n";
			$logMsg.=' '.$wnam.str_repeat(' ', 15- strlen($wnam)).'  '.$dsg.str_repeat(' ', 4- strlen($dsg)).'  '.
			$wnam.str_repeat(' ', 15- strlen($wnam)).
			str_repeat(' ', 8 - strlen($pbas)).$pbas.str_repeat(' ', 7 - strlen($fbas)).$fbas.' '.
			str_repeat(' ', 7 - strlen($da)).$da.str_repeat(' ', 6 - strlen($bln)).$bln.
			str_repeat(' ', 4 - strlen($incra)).$incra.
			str_repeat(' ', 7 - strlen($nsa)).$nsa.
			str_repeat(' ', 7 - strlen($fwgs)).$fwgs.
			str_repeat(' ', 6 - strlen($loffwgs)).$loffwgs.str_repeat(' ', 6 - strlen($bln)).$bln.
			str_repeat(' ', 6 - strlen($bln)).$bln.str_repeat(' ',  8- strlen($pfg)).$pfg.
			str_repeat(' ', 8 - strlen($mern)).$mern.str_repeat(' ', 8 - strlen($stlwgs)).$stlwgs.
			str_repeat(' ', 8 - strlen($hra)).$hra.
			"\n";
			$logMsg.=$bln."\n";
			$logMsg.=$bln."\n";
			$logMsg.=' '.$fnedate.' '.
			$record->pf_no.str_repeat(' ', 6- strlen($record->pf_no)).
			str_repeat(' ', 6 - strlen($npay)).$npay.str_repeat(' ', 7 - strlen($npay)).$npay.
			str_repeat(' ', 9 - strlen($tded)).$tded.str_repeat(' ', 9 - strlen($tern)).$tern.
			str_repeat(' ', 6 - strlen($cf)).$cf.
			str_repeat(' ', 6 - strlen($miscded)).$miscded.str_repeat(' ', 6 - strlen($bln)).$bln.//////
			str_repeat(' ', 7 - strlen($bln)).$bln.str_repeat(' ', 4 - strlen($coloan)).$coloan.
			str_repeat(' ', 6 - strlen($psadv)).$psadv.str_repeat(' ', 7 - strlen($ptax)).$ptax.
			str_repeat(' ', 6 - strlen($esia)).$esia.
			str_repeat(' ', 6 - strlen($epf)).$epf.
			str_repeat(' ', 4 - strlen($bln)).$bln.str_repeat(' ', 9 - strlen($colnb)).$colnb.
			str_repeat(' ', 10 -strlen($bln)).$bln.' '.$fnedate.
			"\n";
			$logMsg.=$bln."\n";
			$logMsg.=str_repeat(' ',28).'Total'.str_repeat(' ', 10- strlen($tern)).$tern.str_repeat(' ', 15).$record->wname.str_repeat(' ', 39- strlen($wname)).' P.F. No - '.
			str_repeat(' ', 6- strlen($record->pf_no)).$record->pf_no.
			"\n";
			
			$logMsg.=$bln."\n";
			$logMsg.=$bln."\n";
			if ($pg<6) {
				$logMsg.=$bln."\n";
			}	
			//$logMsg.=$bln."\n";
			$pg++;
	
		}	

//					$logMsg .= $line."\n";
				$logMsg .= Chr(12)."\n";
			fputs($filePointer,$logMsg);
			fclose($filePointer);
			 $txt1="payslip.txt";
			$txt1=$fileContainer;
			$files = array($txt1);
			$zipname = 'payslip.zip';
	   }

	   if ($holget==11) {
		$att_dept =  $this->input->get('att_dept');
		
		$mccodes = $this->Loan_adv_model->getnjmpayslip($periodfromdate,$periodtodate,$att_payschm,$holget,$att_dept);
		$data = [];
	
		 
		$logMsg=''.chr(15);
		$dpc='';
		$rnop=0;
		$pgn=0;	
		$tpgn=0;			
		$bln='';
		foreach ($mccodes as $record) {
			   if ($dpc<>$record->dept_code ) { 	
				   if (strlen($dpc)>0) {
					$logMsg .= $bln."\n";
					$logMsg.=$bln."\n";
					$logMsg.=$bln."\n";
					$logMsg .=' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
					$logMsg .='  TOTAL NET AMOUNT: :'.$totamt.'      '.'  LOAN DED: '.$tvard.'      '.'  DEPARTMENT '.$department.  Chr(12);
			//		$logMsg .= Chr(12);
			
				
			   }	
			 
			   
			   $dpc=$record->dept_code;
			   $dptcode=$dpc;
			   
			   
			   $lnn=1;
			   $pg++;
			   $tpgn++;
				 
			   $rnop=0;	
			   $pgn=0;     
			
		   }	
		   $pgn++;		
		   if ( $pgn>5 ) { 	
			$logMsg .= Chr(12);
		  $pgn=1;
	  }	
	$logMsg .= $line."\n";
		$dept_code=$dept_code+$record->dept_code;
		$eb_no=$record->eb_no;
		$THRS1=$record->THRS1;
		$THRS2=$record->THRS2;
		$PHRS=$record->PHRS;
		$CPN=$record->CPN;
		$FHRS=$record->FHRS;
		$LHRS=$record->LHRS;
		$SLD=$record->SLD;
		$ELD=$record->ELD;
		$LODAY='00.00';
		$ALODAY=$record->$ALODAY;
		$OTTHR=$record->$OTTHR;
		$OTPHR=$record->OTPHR;
		$ESID=$record->ESID;
		$TWAGE=$record->TWAGE;
		$ADJ_A=$record->ADJ_A;
		$LOWI=$record->LOWI;
		$ILT_PW=$record->ILT_PW;
		$S_ADV=$record->S_ADV;
		$RSD=$record->RSD;
		$P_WAGE=$record->P_WAGE;
		$DA=$record->DA;
		$LODG=$record->LODG;
		$INCENT=$record->INCENT;
		$CDN=$record->CDN;
		$GDED=$record->GDED;
		$eb_no=$record->eb_no;
		$NA=$record->NA;
		$EWI=$record->EWI;
		$OTW=$record->OTW;
		$ILIN=$record->ILIN;
		$PF=$record->PF;
		$NPAY=$record->NPAY;
		$NET=$record->NET;
		$GI=$record->GI;
		$EDG=$record->EDG;
		$OTI=$record->OTI;
		$ILDG=$record->ILDG;
		$RENT=$record->RENT;
		$RNPAY=$record->RNPAY;
		$INC=$record->INC;
		$FDG=$record->FDG;
		$ODG=$record->ODG;
		$GRS2=$record->GRS2;
		$ESI=$record->ESI;
		$VRD1=$record->VRD1;
		$FWI=$record->FWI;
		$SDG=$record->SDG;
		$HRA=$record->HRA;
		$ADVDED=$record->ADVDED;
		$G_W_F=$record->G_W_F;
		$NET=$record->NET;
		$SWI=$record->SWI;
		$TOTAL=$record->TOTAL;
		$GRS1=$record->GRS1;
		$PTAX=$record->PTAX;
		$LR=$record->LR;
		$PVE=$record->PVE;
		$PFG=$record->PFG;
		$OP=$record->OP;
		$CANT=$record->CANT;
		$PROD=$record->PROD;
		$department=$record->department;
		$wnam=substr($record->wname,0,15);
		$wname=$record->wname;
		$desig=substr($record->desig,0,5);
		$desig=$record->desig;
		$esi_no=$record->esi_no;
		$TKTNO=$record->TKTNO;
		$CODE=$record->CODE;
		//$periodtodate=$record->periodtodate;
		$department=$record->department;
		$pf_no=$record->pf_no;
		$DAYS=$record->DAYS;
		$RATE=$record->RATE;
		$DAYS=$record->DAYS;
	
		/////'       '.'PFN :'.str_repeat(' ', 4 - strlen($pf_no)).$pf_no.'  '.'DAYS :'.str_repeat(' ', 20 - strlen($DAYS)).number_format($DAYS,2).
		$logMsg.=$bln."\n";
	
		$logMsg.='  '.' NJM CO LTD  '.str_repeat(' ', 10 - strlen($RATE)).number_format($RATE,4).'  '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($periodtodate)).$periodtodate.'  '.'DEPT: '.str_repeat('', 14 - strlen($department)).$department.'    '.'PFN :'.str_repeat(' ', 4 - strlen($pf_no)).$pf_no.'  '.'DAYS :'.str_repeat(' ', 10 - strlen($DAYS)).number_format($DAYS,2)."\n";
	
		$logMsg.='   '.'NAME  :'.str_repeat('', 25 - strlen($wname)).substr($wname,0,15).'       '.'DESG: '.str_repeat('', 42 - strlen($desig)).substr($desig,0,10).'   '.'ESINO: '.str_repeat('', 52 - strlen($esi_no)).$esi_no.'  '.'TKT NO: '.str_repeat('', 57 - strlen($eb_no)).$eb_no.' '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no.'                    '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($periodtodate)).$periodtodate."\n";
		  
		$logMsg.='   '.'THR1  :'.str_repeat(' ', 9 - strlen($THRS1)).$THRS1.'  '.'THR2 :'.str_repeat(' ', 8 - strlen($THRS2)).$THRS2.'   '.'PHRS :'.str_repeat(' ', 6 - strlen($PHRS)).$PHRS.'     '.'CPN  :'.str_repeat(' ', 8 - strlen($CPN)).$CPN.'   '.'FHRS :'.str_repeat(' ', 8 - strlen($FHRS)).$FHRS.'   '.'LHRS :'.str_repeat(' ', 8 - strlen($LHRS)).$LHRS.'            '.'DEPT:'.str_repeat('', 14 - strlen($department)).$department."\n";
	 
		$logMsg.='   '.'SLD   :'.str_repeat(' ', 7 - strlen($SLD)).$SLD.'    '.'ELD  :'.str_repeat(' ', 8 - strlen($ELD)).$ELD.'   '.'LODAY:'.str_repeat(' ', 9 - strlen($LODAY)).$LODAY.'  '.'OTTHR:'.str_repeat(' ', 8 - strlen($OTTHR)).$OTTHR.'   '.'OTPHR:'.str_repeat(' ', 8 - strlen($OTPHR)).$OTPHR.'   '.'ESID :'.str_repeat(' ', 8 - strlen($ESID)).$ESID.'            '.''.str_repeat('', 25 - strlen($wname)).$wname."\n";
		 
		$logMsg.='  '.' TWAGE :'.str_repeat(' ', 9 - strlen($TWAGE)).$TWAGE.'  '.'ADJ.A:'.str_repeat(' ', 8 - strlen($ADJ_A)).$ADJ_A.'   '.'LOWI :'.str_repeat(' ', 8 - strlen($LOWI)).$LOWI.'  '.'ILT&PW:'.str_repeat(' ', 8 - strlen($ILT_PW)).$ILT_PW.'   '.'S&ADV:'.str_repeat(' ', 8 - strlen($S_ADV)).$S_ADV.'   '.'RSD  :'.str_repeat(' ', 8 - strlen($RSD)).$RSD.'            '.'CODE: '.str_repeat('', 65 - strlen($eb_no)).$eb_no."\n";
		 
		$logMsg.='  '.' P.WAGE:'.str_repeat(' ', 7 - strlen($P_WAGE)).$P_WAGE.'   '.' DA   :'.str_repeat(' ', 9 - strlen($DA)).$DA.' '.' LODG :'.str_repeat(' ', 8 - strlen($LODG)).$LODG.' '.' INCENT:'.str_repeat(' ', 8 - strlen($INCENT)).$INCENT.'  '.' CDN  :'.str_repeat(' ', 8 - strlen($CDN)).$CDN.'  '.' GDED :'.str_repeat(' ', 8 - strlen($GDED)).$GDED.'            '.'TKT:'.str_repeat('', 25 - strlen($eb_no)).$eb_no."\n";
		 
		$logMsg.='  '.' NA    :'.str_repeat(' ', 7 - strlen($NA)).$NA.'   '.' EWI  :'.str_repeat(' ', 8 - strlen($EWI)).$EWI.'  '.' OTW  :'.str_repeat(' ', 8 - strlen($OTW)).$OTW.'  '.' ILIN :'.str_repeat(' ', 8 - strlen($ILIN)).$ILIN.'  '.' PF   :'.str_repeat(' ', 8 - strlen($PF)).$PF.'  '.' NPAY :'.str_repeat(' ', 8 - strlen($NPAY)).$NPAY.'            '.'NET:'.str_repeat('', 25 - strlen($NET)).$NET."\n";
		 
		$logMsg.='  '.' GI    :'.str_repeat(' ', 7 - strlen($GI)).$GI.'   '.' EDG  :'.str_repeat(' ', 8 - strlen($EDG)).$EDG.'  '.' OTI  :'.str_repeat(' ', 8 - strlen($OTI)).$OTI.'  '.' ILDG :'.str_repeat(' ', 8 - strlen($ILDG)).$ILDG.'  '.' RENT :'.str_repeat(' ', 8 - strlen($RENT)).$RENT.'  '.' RNPAY:'.str_repeat(' ', 8 - strlen($RNPAY)).$RNPAY."\n";
		 
		$logMsg.='  '.' INC   :'.str_repeat(' ', 7 - strlen($INC)).$INC.'   '.' FDG  :'.str_repeat(' ', 8 - strlen($FDG)).$FDG.'  '.' ODG  :'.str_repeat(' ', 8 - strlen($ODG)).$ODG.'  '.' GRS2 :'.str_repeat(' ', 8 - strlen($GRS2)).$GRS2.'  '.' ESI  :'.str_repeat(' ', 8 - strlen($ESI)).$ESI.'  '.' VARD1:'.str_repeat(' ', 8 - strlen($VRD1)).$VRD1."\n";
		 
		$logMsg.='  '.' FWI   :'.str_repeat(' ', 7 - strlen($FWI)).$FWI.'  '.'  SDG  :'.str_repeat(' ', 8 - strlen($SDG)).$SDG.'  '.' HRA  :'.str_repeat(' ', 8 - strlen($HRA)).$HRA.' '.' ADVDED:'.str_repeat(' ', 8 - strlen($ADVDED)).$ADVDED.'  '.' G.W.F:'.str_repeat(' ', 8 - strlen($G_W_F)).$G_W_F.'  '.' NET  :'.str_repeat(' ', 8 - strlen($NET)).$NET."\n";
	 
		$logMsg.='  '.' SWI   :'.str_repeat(' ', 7 - strlen($SWI)).$SWI.'  '.'  TOTAL:'.str_repeat(' ', 8 - strlen($TOTAL)).$TOTAL.'  '.' GRS1 :'.str_repeat(' ', 8 - strlen($GRS1)).$GRS1.'  '.' PTAX :'.str_repeat(' ', 8 - strlen($PTAX)).$PTAX.'  '.' LR   :'.str_repeat(' ', 8 - strlen($LR)).$LR."\n";
		 
		$logMsg.='  '.' PVE   :'.str_repeat(' ', 7 - strlen($PVE)).$PVE.'  '.'  P.F.G:'.str_repeat(' ', 8 - strlen($PFG)).$PFG.'  '.' OP   :'.str_repeat(' ', 8 - strlen($OP)).$OP.'  '.' CANT :'.str_repeat(' ', 8 - strlen($CANT)).$CANT.'  '.' PROD :'.str_repeat(' ', 8 - strlen($PROD)).$PROD."\n";
	 
		$totamt=$totamt+$record->NET;
		$tvard=$tvard+$record->VRD1;
		///$department+$record->department;
		$tpgn=$tpgn+$record->pgn;
		   $lnn=$lnn++;
		}
		$logMsg .= $bln."\n";
		$logMsg.=$bln."\n";
		$logMsg.=$bln."\n";
		$logMsg .=' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
		$logMsg .='  TOTAL NET AMOUNT: :'.$totamt.'      '.'  LOAN DED: '.$tvard.'      '.'  DEPARTMENT '.$department. chr(18). Chr(12)."\n";
		
		$logMsg .=chr(18). Chr(12)."\n";
		fputs($filePointer,$logMsg);
		fclose($filePointer);
		 $txt1="NjmPay.txt";
		$txt1=$fileContainer;
		$files = array($txt1);
		$zipname = 'NJMpayslip.zip';
	}
	////////// end ///////////////////
	
	//////////// njm payslip-SABIR-09.03.24//////////////
	if ($holget==12) {
		$mccodes = $this->Loan_adv_model->getnjmofbclerk($periodfromdate,$periodtodate,$att_payschm,$holget);
		$data = [];
	
		 
		$logMsg=''.chr(15);
		$dpc='';
		$rnop=0;
						
		foreach ($mccodes as $record) {
			   if ($dpc<>$record->dept_code ) { 	
				   if (strlen($dpc)>0) {
					$logMsg.='Total '.$totamt."\n";
				 $logMsg .= Chr(12);
				
			   }	
			   
			   
			   $dpc=$record->dept_code;
			   $dptcode=$dpc;
			   
			   
			   $lnn=1;
			   $pg++;
			   $rnop=0;					
			   
		   }		
		$logMsg .= $line."\n";
		//$dept_code=$dept_code+$record->dept_code;
		$WAGE=$record->WAGE;
		$DA=$record->DA;
		$GI=$record->GI;
		$FHW=$record->FHW;
		$FHDGI=$record->FHDGI;
		$SLW=$record->SLW;
		$SLDGI=$record->SLDGI;
		$ELW=$record->ELW;
		$ELDGI=$record->ELDGI;
		$TOTAL=$record->TOTAL;
		$EXW=$record->EXW;
		$EXDGI=$record->EXDGI;
		$OTW=$record->OTW;
		$OTDGI=$record->OTDGI;
		$INC=$record->INC;
		$HRA=$record->HRA;
		$EX_ALW=$record->EX_ALW;
		$GROSS=$record->GROSS;
		$PTAX=$record->PTAX;
		$UCS=$record->UCS;
		$RENT=$record->RENT;
		$GWF=$record->GWF;
		$CDN=$record->CDN;
		$BKLN=$record->BKLN;
		$W_F=$record->W_F;
		$ESIC=$record->ESIC;
		$PFG=$record->PFG;
		$GDED=$record->GDED;
		$PF=$record->PF;
		$VARE=$record->VARE;
		$F_R_F=$record->F_R_F;
		$SADV=$record->SADV;
		$RSD=$record->RSD;
		$VARD=$record->VARD;
		$ADVAN=$record->ADVAN;
		$CLOTH=$record->CLOTH; 
		$LRENT=$record->LRENT; 
		$NETPAY=$record->NETPAY; 
		$RNPAY=$record->RNPAY;
		$L_W=$record->L_W;
		$NET=$record->NET;
		$desig=$record->desig;
		$eb_no=$record->eb_no;
		$FHR=$record->FHR;
		$HOURS=$record->HOURS;
		$EXHRS=$record->EXHRS;
		$OTHRS=$record->OTHRS;
		$SL=$record->SL;
		$EL=$record->EL;
		$wname=$record->wname;
		$DAYS_BASIS=$record->DAYS_BASIS;
		$department=$record->department;
		$DAYS=$record->DAYS;
					   
		$logMsg.=$bln."\n";
		$logMsg.=''.'NJM CO LTD/NELLIMARLA '.'   '.'DAYS BASIS: '.str_repeat(' ', 5 - strlen($DAYS_BASIS)).$DAYS_BASIS.'     '.'PAY SLIP FOR:'.str_repeat(' ', 12 - strlen($periodtodate)).$periodtodate.'   '.'DEPT :'.str_repeat(' ', 14 - strlen($department)).$department.'    '.'DAYS : '.str_repeat(' ', 11 - strlen($DAYS)).number_format($DAYS,2).'        '.' NJM CO LTD'."\n" ;  
		$logMsg.=''.'NAME : '.str_repeat('', 25 - strlen($wname)).substr($wname,0,15).'                 '.'DESG :'.str_repeat('', 15 - strlen($desig)).substr($desig,0,8).'     '.'TKT NO: '.str_repeat(' ', 6 - strlen($eb_no)).$eb_no.'    '.'CODE: '.str_repeat(' ', 7 - strlen($eb_no)).$eb_no.'     '.'ESID  : '.str_repeat(' ', 7 - strlen($ESID)).number_format($ESID,2).'         '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($periodtodate)).$periodtodate."\n";
		$logMsg.=''.'FHR : '.str_repeat(' ', 5 - strlen($FHR)).$FHR.'       '.'HOURS : '.str_repeat(' ', 6 - strlen($HOURS)).$HOURS.'    '.'AHRS: '.str_repeat(' ', 3 - strlen($AHRS)).number_format($AHRS,2).'      '.'EXHRS: '.str_repeat(' ', 9 - strlen($EXHRS)).$EXHRS.'   '.'OTHRS: '.str_repeat(' ', 3 - strlen($OTHRS)).number_format($OTHRS,2).'    '.'SL: '.str_repeat(' ', 5 - strlen($SL)).number_format($SL,2).'   '.'EL: '.str_repeat(' ', 5 - strlen($EL)).number_format($EL,2).'         '.'NAME: '.str_repeat('', 25 - strlen($wname)).$wname."\n";
		$logMsg.='                                                               '.'NET: '.str_repeat(' ', 9 - strlen($NET)).$NET."\n";
		$logMsg.=''.'WAGE  : '.str_repeat(' ', 10 - strlen($WAGE)).$WAGE.'     '.'TOTAL : '.str_repeat(' ', 10 - strlen($TOTAL)).$TOTAL.'     '.'PTAX  :'.str_repeat(' ', 11 - strlen($PTAX)).$PTAX.'      '.'P.F.G  :  '.str_repeat(' ', 8 - strlen($PFG)).$PFG.'      '.'GDED  :  '.str_repeat(' ', 10 - strlen($GDED)).$GDED.'       '.str_repeat(' ', 16 - strlen($department)).$department."\n";
		$logMsg.=''.'DA    : '.str_repeat(' ', 10 - strlen($DA)).$DA.'     '.'EXW   :'.str_repeat(' ', 11 - strlen($EXW)).$EXW.'     '.'UCS   :'.str_repeat(' ', 7 - strlen($UCS)).number_format($UCS,2).'      '.'P.F    :  '.str_repeat(' ', 8 - strlen($PF)).$PF.'      '.'-VARE :  '.str_repeat(' ', 6 - strlen($VARE)).number_format($VARE,2)."\n";    
		$logMsg.=''.'GI    : '.str_repeat(' ', 10 - strlen($GI)).$GI.'     '.'EXDGI : '.str_repeat('  ', 3 - strlen($EXDGI)).number_format($EXDGI,2).'     '.'RENT  :'.str_repeat(' ', 11 - strlen($RENT)).$RENT.'      '.'F.R.F  :  '.str_repeat(' ', 4 - strlen($F_R_F)).number_format($F_R_F,2).'      '.'+VARE :  '.str_repeat(' ', 6 - strlen($VARE)).number_format($VARE,2)."\n"; 
		$logMsg.=''.'FHW   : '.str_repeat(' ', 10 - strlen($FHW)).$FHW.'     '.'OTW   : '.str_repeat('  ', 3 - strlen($OTW)).number_format($OTW,2).'     '.'GWF   : '.str_repeat('  ', 7 - strlen($GWF)).$GWF.'      '.'S.ADV  :  '.str_repeat(' ', 8 - strlen($SADV)).$SADV.'      '.'LRENT :  '.str_repeat(' ', 10 - strlen($LRENT)).$LRENT."\n"; 
		$logMsg.=''.'FHDGI : '.str_repeat(' ', 10 - strlen($FHDGI)).$FHDGI.'     '.'OTDGI : '.str_repeat('  ', 3 - strlen($OTDGI)).number_format($OTDGI,2).'     '.'CDN   : '.str_repeat('  ', 3 - strlen($CDN)).number_format($CDN,2).'      '.'RSD    :  '.str_repeat(' ', 8 - strlen($RSD)).$RSD.'      '.'NETPAY:  '.str_repeat(' ', 10 - strlen($NETPAY)).$NETPAY.'      '.str_repeat(' ', 11 - strlen($eb_no)).$eb_no."\n";
		$logMsg.=''.'SLW   : '.str_repeat(' ', 10 - strlen($SLW)).$SLW.'     '.'INC   :   '.str_repeat('  ', 6 - strlen($INC)).$INC.'     '.'ADV   : '.str_repeat('  ', 3 - strlen($ADV)).number_format($ADV,2).'      '.'VARD   :  '.str_repeat(' ', 4 - strlen($VARD)).number_format($VARD,2).'      '.'RNPAY :  '.str_repeat(' ', 10 - strlen($RNPAY)).$RNPAY."\n";
		$logMsg.=''.'SLDGI : '.str_repeat(' ', 10 - strlen($SLDGI)).$SLDGI.'     '.'HRA   :'.str_repeat(' ', 10 - strlen($HRA)).$HRA.'      '.'BKLN  : '.str_repeat('  ', 3 - strlen($BKLN)).number_format($BKLN,2).'      '.'       :  '.str_repeat(' ', 4 - strlen($VARD)).number_format($VARD,2).'      '.'L/W   :  '.str_repeat(' ', 10 - strlen($L_W)).$L_W."\n";
		$logMsg.=''.'ELW   : '.str_repeat(' ', 10 - strlen($ELW)).$ELW.'     '.'EX ALW: '.str_repeat('  ', 3 - strlen($EX_ALW)).number_format($EX_ALW,2).'     '.'W/F   : '.str_repeat('  ', 3 - strlen($W_F)).number_format($W_F,2).'      '.'ADVAN  :  '.str_repeat(' ', 8 - strlen($ADVAN)).$ADVAN.'      '.'N ET  :  '.str_repeat(' ', 10 - strlen($NET)).$NET."\n";
		$logMsg.=''.'ELDGI : '.str_repeat(' ', 10 - strlen($ELDGI)).$ELDGI.'     '.'GROSS : '.str_repeat(' ', 10 - strlen($GROSS)).$GROSS.'     '.'ESIC  : '.str_repeat(' ', 10 - strlen($ESIC)).$ESIC.'      '.'CLOTH  :  '.str_repeat(' ', 4 - strlen($CLOTH)).number_format($CLOTH,2)."\n";
		/*$logMsg.='  '.' NJM CO LTD  '.'  '.'        '.'PAY SLIP FOR:'.str_repeat('', 14 - strlen($periodtodate)).$periodtodate.'           '.'DEPT: '.str_repeat('', 14 - strlen($department)).$department.'       '.'PFN :'.str_repeat(' ', 4 - strlen($pf_no)).$pf_no.'  '.'DAYS :'.str_repeat(' ', 4 - strlen($DAYS)).$DAYS."\n";
		
		$totamt=$totamt+$record->NET;*/
		$tvard=$tvard+$record->VRD1;
		$department=$record->department;
	
		}
		$logMsg .= $bln."\n";
		
		$logMsg.=$bln."\n";
		$logMsg.=$bln."\n";
		$logMsg .=' TOTAL  NO. OF PAYSLIPS :'.$pg. "\n";
		$logMsg .='  TOTAL NET AMOUNT: :'.$totamt.'      '.'  LOAN DED: '.$tvard.'      '.'  DEPARTMENT '.$department. chr(18). Chr(12)."\n";
		
		$logMsg .=chr(18). Chr(12)."\n";
		fputs($filePointer,$logMsg);
		fclose($filePointer);
		 $txt1="NjmPay.txt";
		$txt1=$fileContainer;
		$files = array($txt1);
		$zipname = 'NjmOfbClerk.zip';
	}
	////////// end ///////////////////
	//////////////////////////////////C.E DEDUCTION ABSTRACT SHEET//getcedeductionclerk
	
	if ($holget==13) {
		$mccodes = $this->Loan_adv_model->getcedeductionclerk($periodfromdate,$periodtodate,$att_payschm,$holget);
		$data = [];
	
		 
		//$logMsg=''.chr(15);
		//$dpc='';
		//$rnop=0;
		$startdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);   
		$logMsg.=$bln."\n";
		$logMsg.='  '.'NELLIMARLA JUTE MIILS CO LTD/ NELLIMARLA '."\n" ;  
		$logMsg.='  '.'C.E DEDUCTION ABSTRACT SHEET FIGURES FOR THE MONTH OF : '.$startdate."\n" ; 
		$logMsg.=chr(15).str_repeat('-',15).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1)."\n" ; 
		$logMsg.=str_repeat('Department',1).'     '.str_repeat('|',1).'   '.str_repeat('GROSS',1).'    '.str_repeat('|',1).'    '.str_repeat('P.F ',1).'    '.str_repeat('|',1).'    '.str_repeat('E.S.I',1).'   '.str_repeat('|',1).'    '.str_repeat('R.S.D',1).'   '.str_repeat('|',1).'    '.str_repeat('RENT',1).'    '.str_repeat('|',1).' '.str_repeat('LAND RENT',1).'  '.str_repeat('|',1).'  '.str_repeat('ADVANCE',1).'   '.str_repeat('|',1).' '.str_repeat('SUNDAY ADV.',1).''.str_repeat('|',1).'   '.str_repeat('P.TAX',1).'    '.str_repeat('|',1).'   '.str_repeat('UCS',1).'      '.str_repeat('|',1).' '.str_repeat('COMP.DEDN ',1).' '.str_repeat('|',1).' '.str_repeat('ADVANCE (1)',1).''.str_repeat('|',1).' '.str_repeat('PLUS N.PAY ',1).''.str_repeat('|',1).'  '.str_repeat('R.NPAY ',1).'   '.str_repeat('|',1).'   '.str_repeat('W.F ',1).'     '.str_repeat('|',1).'   '.str_repeat('VARD ',1).'    '.str_repeat('|',1)."\n" ; 
		$logMsg.=str_repeat(' ',15).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1).str_repeat('Rs.     Ps. ', 1).str_repeat('|',1)."\n" ;
		$logMsg.=str_repeat('-',15).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1)."\n" ;
				
		foreach ($mccodes as $record) {
			
			  // if ($dpc<>$record->department ) { 	
				   //if (strlen($dpc)>0) {
				//	$logMsg.='Total '.$totamt."\n";
	//				 $logMsg .= Chr(12);
				
				   
			   
			   
			  // $dpc=$record->department;
			  // $dptcode=$dpc;
			   
			   
			   /*$lnn=1;
			   $pg++;
			   $rnop=0;	*/				
			   
		  // }	
		   
				   
	//$periodtodate=date_format("M d yyyy");
		//$logMsg .= $line."\n";
		//$dept_code=$dept_code+$record->dept_code;
		$WAGE=$record->WAGE;
		$department=$record->department;
		$GROSS=number_format($record->GROSS,2);
		$PF=number_format($record->PF,2);
		$ESIC=number_format($record->ESIC,2);
		$RSD=number_format($record->RSD,2);
		$RENT=number_format($record->RENT,2);
		$LRENT=number_format($record->LRENT,2);
		$ADVANCE=number_format($record->ADVANCE,2);
		$SUN_ADV=number_format($record->SUN_ADV,2);
		$PTAX=number_format($record->PTAX,2);
		$UCS=number_format($record->UCS,2);
		$CDED=number_format($record->CDED,2);
		$ADV1=number_format($record->ADV1,2);
		$NETPAY=number_format($record->NETPAY,2);
		$RNPAY=number_format($record->RNPAY,2);
		$GWF=number_format($record->GWF,2);
		$VARD=number_format($record->VARD,2);
		$TGROSS=$TGROSS+$record->GROSS;
		$TPF=$TPF+$record->PF;
		$TESIC=$TESIC+$record->ESIC;
		$TRSD=$TRSD+$record->RSD;
		$TRENT=$TRENT+$record->RENT;
		$TLRENT=$TLRENT+$record->LRENT;
		$TADVANCE=$TADVANCE+$record->ADVANCE;
		$TSUN_ADV=$TSUN_ADV+$record->SUN_ADV;
		$TPTAX=$TPTAX+$record->PTAX;
		$TUCS=$TUCS+$record->UCS;
		$TCDED=$TCDED+$record->CDED;
		$TADV1=$TADV1+$record->ADV1;
		$TNETPAY=$TNETPAY+$record->NETPAY;
		$TRNPAY=$TRNPAY+$record->RNPAY;
		$TGWF=$TGWF+$record->GWF;
		$TVARD=$TVARD+$record->VARD;
					
		//$line = str_repeat('-', 72); 
					
		$logMsg.=$record->department.str_repeat(' ',15-strlen($record->department)).'|'.str_repeat(' ', 12 - strlen($GROSS)).$GROSS.'|'.str_repeat(' ', 10 - strlen($PF)).$PF.'  |'.str_repeat(' ', 9 - strlen($ESIC)).$ESIC.'   |'.str_repeat(' ', 8 - strlen($RSD)).$RSD.'    |'.str_repeat(' ', 9 - strlen($RENT)).$RENT.'   |'.str_repeat(' ', 8 - strlen($LRENT)).$LRENT.'    |'.str_repeat(' ', 10 - strlen($ADVANCE)).$ADVANCE.'  |'.str_repeat(' ', 8 - strlen($SUN_ADV)).$SUN_ADV.'    |'.str_repeat(' ', 8 - strlen($PTAX)).$PTAX.'    |'.str_repeat(' ', 8 - strlen($UCS)).$UCS.'    |'.str_repeat(' ', 8 - strlen($CDED)).$CDED.'    |'.str_repeat(' ', 8 - strlen($ADV1)).$ADV1.'    |'.str_repeat(' ', 11 - strlen($NETPAY)).$NETPAY.' |'.str_repeat(' ', 11 - strlen($RNPAY)).$RNPAY.' |'.str_repeat(' ', 8 - strlen($GWF)).$GWF.'    |'.str_repeat(' ', 8 - strlen($VARD)).$VARD.'    '.str_repeat('|',1)."\n" ; 
		$logMsg.=str_repeat('-',15).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1)."\n" ;
			
	 }	
	 //$record->NET
	 //$TGROSS=$TGROSS+$record->GROSS;
		$logMsg .=''.' G.TOTAL '.'      |'.str_repeat(' ', 11 - strlen($TGROSS)).$TGROSS.' |'.str_repeat(' ', 6 - strlen($TPF)).number_format($TPF,2).'  |'.str_repeat(' ', 5 - strlen($TESIC)).number_format($TESIC,2).'   |'.str_repeat(' ', 5 - strlen($TRSD)).number_format($TRSD,2).'    |'.str_repeat(' ', 8 - strlen($TRENT)).number_format($TRENT,2).'   |'.str_repeat(' ', 5 - strlen($TLRENT)).number_format($TLRENT,2).'    |'.str_repeat(' ', 6 - strlen($TADVANCE)).number_format($TADVANCE,2).'  |'.str_repeat(' ', 5 - strlen($TSUN_ADV)).number_format($TSUN_ADV,2).'    |'.str_repeat(' ', 5 - strlen($TPTAX)).number_format($TPTAX,2).'    |'.str_repeat(' ', 5 - strlen($TUCS)).number_format($TUCS,2).
		'    |'.str_repeat(' ', 5 - strlen($TCDED)).number_format($TCDED,2).'    |'.str_repeat(' ', 5 - strlen($TADV1)).number_format($TADV1,2).'    |'.str_repeat(' ', 9 - strlen($TNETPAY)).number_format($TNETPAY,2).' |'.str_repeat(' ', 7 - strlen($TRNPAY)).number_format($TRNPAY,2).' |'.str_repeat(' ', 5 - strlen($TGWF)).number_format($TGWF,2).	'    |'.str_repeat(' ', 5 - strlen($TVARD)).number_format($TVARD,2).'    '.str_repeat('|',1).	"\n" ;  	   
		$logMsg.=str_repeat('-',15).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1).''.str_repeat('-',12).str_repeat('|',1)."\n" ;
		$logMsg .=chr(18). Chr(12)."\n";
		fputs($filePointer,$logMsg);
		fclose($filePointer);
		 $txt1="CeDeduction.txt";
		$txt1=$fileContainer;
		$files = array($txt1);
		$zipname = 'NjmCeDed.zip';
	}
	
	

	
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach ($files as $file) {
		  $zip->addFile($file);
		}
		$zip->close();
		
		ob_clean();
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);
		
				unlink($fileContainer);
				 unlink($zipname);
		
		
		
			}


		public function otregisterprint() {
			$periodfromdate= $this->input->get('periodfromdate');
			$periodtodate= $this->input->get('periodtodate');
			$att_payschm =  $this->input->get('att_payschm');
			$holget =  $this->input->get('holget');
			$payschemeName =  $this->input->get('payschemeName');
				 $company_name = $this->session->userdata('companyname');
				 $comp = $this->session->userdata('companyId');
				 $fileContainer = "otdata.txt";
				 $filePointer = fopen($fileContainer,"w+");
				 $sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
				 $ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
				if ($holget==2) {
					$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 72);
				//	  $logMsg=$company_name."\n";
					  $pg=1;
					  $logMsg.=$payschemeName."\n";
					  $logMsg .= "Trainee OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
					  $logMsg .= $line."\n";
					  $logMsg .= 'Department   EB No     Name                    Hours    Rate     Amount'."\n";
					  $logMsg .= $line."\n";
					  $logMsg .= ' '."\n";
					  $logMsg .= ' '."\n";
					  $rowIndex = 4;
					  $totamt=0;
					  $tothrs=0;
					  $lnn=8;
					foreach ($mccodes as $record) {
						if ($lnn>58) {
							$logMsg .= Chr(12)."\n";
							$pg++;
							$logMsg .=$payschemeName."\n";
							$logMsg .= "Trainee OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
							$logMsg .= $line."\n";
							$logMsg .= 'Department   EB No     Name                    Hours    Rate     Amount'."\n";
							$logMsg .= $line."\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
							$lnn=8;
						}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";

							$OT_HOURS = number_format($record->OT_HOURS, 2);
							$RATE= $record->RATE;
							$logMsg .='   '.$record->dept_code.'        '.$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
							substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
							str_repeat(' ', 9 - strlen($OT_HOURS)).$OT_HOURS.
							str_repeat(' ', 8 - strlen($RATE)).$RATE.
							str_repeat(' ', 10 - strlen($record->OVERTIME_PAY)).$record->OVERTIME_PAY.
							"\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
       					    $logMsg .= $line."\n";
							$totamt=$totamt+$record->OVERTIME_PAY;
							$tothrs=$tothrs+$OT_HOURS;
							$lnn=$lnn+4;
						}
						$gt="Grand Total";
						$blnk='';
						$logMsg .='             '.$blnk.str_repeat(' ', 10- strlen($blnk)).
						substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
						str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
						str_repeat(' ', 8 - strlen($blnk)).$blnk.
						str_repeat(' ', 10 - strlen($totamt)).$totamt.
						"\n";
	
						$logMsg .= $line."\n";
						$logMsg .= Chr(12)."\n";
					fputs($filePointer,$logMsg);
				    fclose($filePointer);
				 	$txt1="otdata.txt";
					$txt1=$fileContainer;
				    $files = array($txt1);
				    $zipname = 'othersotregister.zip';
			   }
			   if ($holget==1) {
				$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
				
			
				$data = [];
				  $line = str_repeat('-', 82);
			//	  $logMsg=$company_name."\n";
				  $pg=1;
				  $hd1='Dept  EB No     Name                Hours   Amount   Advance  Misc Earn   Net Pay'."\n";
				  $logMsg=$company_name."\n";
				  $logMsg .= $payschemeName." OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
				  $logMsg .= $line."\n";
				  $logMsg .=$hd1; 
				  $logMsg .= $line."\n";
				  $logMsg .= ' '."\n";
				  $logMsg .= ' '."\n";
				  $rowIndex = 4;
				  $totamt=0;
				  $tothrs=0;
				  $totadv=0;
				  $totmern=0;
				  $totnet=0;
				  $lnn=8;
			//	  var_dump($mccodes);
				foreach ($mccodes as $record) {
					if ($lnn>58) {
//						$line = str_repeat('-', 82);
						//	  $logMsg=$company_name."\n";
 							  $logMsg .= Chr(12);
							$pg++;	
							  $logMsg .=$payschemeName."\n";
							  $logMsg .= "Main Pay Roll OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
							  $logMsg .= $line."\n";
							  $logMsg .= $hd1;
							  $logMsg .= $line."\n";
						//	  $logMsg .= ' '."\n";
						//	  $logMsg .= ' '."\n";
							  $rowIndex = 4;
							  $lnn=5;
					}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";

						$OT_HOURS = number_format($record->OT_HOURS, 2);
						$RATE= $record->RATE;
						$miscearn=round($record->MISC_OT_EARNINGS,2);
						$otnet=round($record->OT_NET_PAY,0);
						$logMsg .=' '.$record->dept_code.'  '.$record->eb_no.str_repeat(' ', 7- strlen($record->eb_no)).
						substr($record->wname,1,20).str_repeat(' ', 20 - strlen(substr($record->wname,1,20))).
						str_repeat(' ', 9 - strlen($OT_HOURS)).$OT_HOURS.
					//	str_repeat(' ', 7 - strlen($RATE)).$RATE.
						str_repeat(' ', 9 - strlen($record->OVERTIME_PAY)).$record->OVERTIME_PAY.
						str_repeat(' ', 10 - strlen($record->OT_ADVANCE)).$record->OT_ADVANCE.
						str_repeat(' ', 11 - strlen($record->MISC_OT_EARNINGS)).$record->MISC_OT_EARNINGS.
						str_repeat(' ', 10 - strlen($otnet)).$otnet.
						"\n";
						$logMsg .= $line."\n";
//echo $logMsg;
//echo 'this eb'.$record->eb_no."<br/>";
						//						$logMsg .= ' '."\n";
//			            $logMsg .= $line."\n";
						$totamt=$totamt+$record->OVERTIME_PAY;
						$tothrs=$tothrs+$record->OT_HOURS;
						$totadv=$totadv+$record->OT_ADVANCE;
						$totmern=$totmern+$record->MISC_OT_EARNINGS;
						$totnet=$totnet+$record->OT_NET_PAY;
	  					$lnn=$lnn+2;
					}
					$gt="Grand Total";
					$logMsg .= $gt.str_repeat(' ', 11 - strlen($gt)).str_repeat(' ', 11).str_repeat(' ', 10).
					str_repeat(' ', 9 - strlen($tothrs)).$tothrs.str_repeat(' ', 9 - strlen($totamt)).$totamt.
					str_repeat(' ',10 - strlen($totadv)).$totadv.str_repeat(' ', 11 - strlen($totmern)).$totmern.
					str_repeat(' ', 10 - strlen($totnet)).$totnet.
					"\n";
					$logMsg .= $line."\n";
					$logMsg .= Chr(12)."\n";
				fputs($filePointer,$logMsg);
				fclose($filePointer);
				 $txt1="otdata.txt";
				$txt1=$fileContainer;
				$files = array($txt1);
				$zipname = 'othersotregister.zip';
		   }
 
		   if ($holget==3 ) {


			$mccodes = $this->Loan_adv_model->getotsummary($periodfromdate, $periodtodate, $att_payschm, $holget);
			$data = [];
			$line = str_repeat('-', 79);

			if ($att_payschm == 151) {
				$payschemeName = 'Main-roll';
			} else {
				$payschemeName = '18-PF';
			}
			if ($att_payschm == 161) {
				$payschemeName = 'Mill-Retired';

			}

	
			$pg=1;
			$logMsg='';
			$ln1=$line."\n";
			$ln2= 'THE EMPIRE JUTE CO.LTD.';
			$ln3= 'OT Summary Statement for the  period from'.'  ' .$sdate.' ' . 'To '.$ldate."\n";
			$ln4= $line."\n";
			$ln5= 'Dept Code   Department     Ot-Hours    Ot-Amount   Ot-Advance   Net Payment'."\n";
			$ln6.= $line."\n";

			$totamt=0;
			$gtothrs=0;
			$tothrs=0;
			$gtotamt=0;
			$gotpay=0;
			$gtotpay=0;
			$gnpay=0;
			$lnn=1;
			$dpc='';
			foreach ($mccodes as $record) {
				if ($dpc<>$record->dept_code) { 	
					if ($pg>1) {
						$gtothrs=0;
						$totamt=0;
						$tothrs=0;
						$logMsg .= $line."\n";
						//$logMsg .= Chr(12)."\n";
						$lnn=1;
					}	
					$dpc=$record->dept_code;
					$dptcode=$dpc;
					//$pg++;
				}		
				if ($lnn>58) {
					$logMsg .= Chr(12)."\n";
					$lnn=1;
				}	

				if  ($lnn==1) {
					$logMsg .=$linee."\n";
					$logMsg .=$ln2."\n";
					$logMsg .= $ln3;
					$logMsg .= $ln4;
					$logMsg .= $ln5;
					$logMsg .= $ln6;
					$lnn=6;
				}	
					$dpc=$record->DEPT_CODE;
					$DEPARTMENT=$record->DEPARTMENT;
					$OTHRS=$record->OTHRS;
					$OVERTIME_PAY=number_format($record->OVERTIME_PAY,0);		
					$OT_ADVANCE=number_format($record->OT_ADVANCE,0);
					$NET_PAY=number_format($record->NET_PAY,0);
		
					$logMsg .=' '.$dpc.'        ' .$record->DEPARTMENT.str_repeat(' ', 15- strlen($record->DEPARTMENT)).
					str_repeat(' ', 8 - strlen($OTHRS)).$OTHRS.
					str_repeat(' ', 12 - strlen($OVERTIME_PAY)).$OVERTIME_PAY.
					str_repeat(' ', 12 - strlen($OT_ADVANCE)).$OT_ADVANCE.
					str_repeat(' ', 12 - strlen($NET_PAY)).$NET_PAY.
					
					"\n";
					$logMsg .= $line."\n";
					$gtothrs=$gtothrs+$record->OTHRS;
					$gtotpay=$gtotpay+$record->OVERTIME_PAY;
					$gtotamt=$gtotamt+$record->OT_ADVANCE;
					$gnpay=$gnpay+$record->NET_PAY;
					$lnn=$lnn+2;
				}
		
				$logMsg .= $line."\n";
							
				$gt="Grand Total";
				$blnk='';
				$logMsg .=$Blnk.str_repeat(' ', 5- strlen($blnk)).
				substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
				str_repeat(' ', 8 - strlen($gtothrs)).$gtothrs.
				str_repeat(' ', 12 - strlen($gtotpay)).$gtotpay.
				str_repeat(' ', 13 - strlen($gtotamt)).$gtotamt.
				str_repeat(' ', 13 - strlen($gnpay)).$gnpay.
				"\n";

		
				$logMsg .= $line."\n";
				
				$logMsg .= $line."\n";
				$logMsg .= Chr(12)."\n";

			fputs($filePointer,$logMsg);
			fclose($filePointer);
			 $txt1="paydata.txt";
			$txt1=$fileContainer;
			$files = array($txt1);
			$zipname = 'summreg.zip';
 


		}

	
		$zip = new ZipArchive;
		$zip->open($zipname, ZipArchive::CREATE);
		foreach ($files as $file) {
		  $zip->addFile($file);
		}
		$zip->close();
		
		header('Content-Type: application/zip');
		header('Content-disposition: attachment; filename='.$zipname);
		header('Content-Length: ' . filesize($zipname));
		readfile($zipname);
		
				unlink($fileContainer);
				 unlink($zipname);
		
		
		
			}

			public function otpayslipprint() {
				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
					 $fileContainer = "otdata.txt";
					 $filePointer = fopen($fileContainer,"w+");
				if ($holget==2) {
						$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
						$data = [];
						  $line = str_repeat('-', 72);
					//	  $logMsg=$company_name."\n";
						  $pg=1;
						  $logMsg=$payschemeName."\n";
						  $logMsg .= "Trainee OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
						  $logMsg .= $line."\n";
						  $logMsg .= 'Department   EB No     Name                    Hours    Rate   Amount'."\n";
						  $logMsg .= $line."\n";
						  $logMsg .= ' '."\n";
						  $logMsg .= ' '."\n";
						  $rowIndex = 4;
						  $totamt=0;
						  $tothrs=0;
						  $lnn=8;
						foreach ($mccodes as $record) {
							if ($lnn>58) {
								$logMsg .= Chr(12)."\n";
								$pg++;
								$logMsg .=$payschemeName."\n";
								$logMsg .= "Trainee OT Sheet for the period From ".$periodfromdate." To ".$periodtodate.'       '.'Page No '.$pg."\n";
								$logMsg .= $line."\n";
								$logMsg .= 'Department   EB No     Name                    Hours    Rate     Amount'."\n";
								$logMsg .= $line."\n";
								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
								$lnn=8;
							}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
	
								$OT_HOURS = number_format($record->OT_HOURS, 2);
								$RATE= $record->RATE;
								$logMsg .='   '.$record->dept_code.'        '.$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
								substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
								str_repeat(' ', 9 - strlen($OT_HOURS)).$OT_HOURS.
								str_repeat(' ', 8 - strlen($RATE)).$RATE.
								str_repeat(' ', 10 - strlen($record->OVERTIME_PAY)).$record->OVERTIME_PAY.
								"\n";
								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
								   $logMsg .= $line."\n";
								$totamt=$totamt+$record->OVERTIME_PAY;
								$tothrs=$tothrs+$OT_HOURS;
								$lnn=$lnn+4;
							}
							$gt="Grand Total";
							$blnk='';
							$logMsg .='             '.$Blnk.str_repeat(' ', 10- strlen($blnk)).
							substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
							str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
							str_repeat(' ', 8 - strlen($blnk)).$blnk.
							str_repeat(' ', 10 - strlen($totamt)).$totamt.
							"\n";
		
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";
						
							fputs($filePointer,$logMsg);
						fclose($filePointer);
						 $txt1="otdata.txt";
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'othersotregister.zip';
				   }
		  
				   if ($holget==1) {
					$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 72);
					  $hd1="DP S SRL T/No. Name SUPL SLIP) F/E Date | DP S SRL T/No. Name SUPL SLIP) F/E Date |";
					  $hd2="           WRK-HRS ATTN-INC    TOT-EARN |            WRK-HRS ATTN-INC    TOT-EARN |";
					  $hd3="     ADVANCE     MISC               NET |      ADVANCE      MISC              NET |";	
				   $bkline="                                        |                                         |";	
				    $bklin="----------------------------------------|-----------------------------------------|";	
					  $pg=1;
					  $rowIndex = 4;
					  $totamt=0;
					  $tothrs=0;
					  $lnn=8;
					  $sl=1;
					  $rnop=0;	
					  $lndet1='';
					  $lndet2='';
					  $lndet3='';
					  $lndet4='';
					  $lndet5='';
					  $lndet6='';
					  $dpc='';
					  $enddate=	substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
					  foreach ($mccodes as $record) {
						if ($dpc<>$record->dept_code) {
							if (strlen($dpc)>0) {
								if (fmod($rnop, 2)==1)	{
								
													$logMsg .=$bkline."\n";
								//					$logMsg .= ' '."\n";
								//					$logMsg .= ' '."\n";
													$logMsg .=$hd1."\n";
													$logMsg .=$lndet1."\n";
													$logMsg .=$bkline."\n";
													$logMsg .=$hd2."\n";
													$logMsg .=$lndet2."\n";
													$logMsg .=$bkline."\n";
													$logMsg .=$hd3."\n";
													$logMsg .=$lndet3."\n";
													$logMsg .=$bklin."\n";
														
								}
								$logMsg .= Chr(12)."\n";
								$pg=1;
								$rnop=0;
							}								
							$dpc=$record->dept_code;
			
						}
						if ($pg>5) {
							$logMsg .= Chr(12)."\n";
							$pg=1;
						}														
					
						$OT_HOURS = number_format($record->OT_HOURS, 2);
						$OVERTIME_PAY= $record->OVERTIME_PAY;
						$mearn=0;
						$adv=0;
						$rnop=$rnop+1;
						$rn=fmod($rnop, 2);
						$sln=str_repeat(' ', 3 - strlen($rnop)).$rnop;
	//					$logMsg .='rec no='.$rnop.'=='.$rn.'--eb=-'.$record->eb_no."\n";

						if (fmod($rnop, 2)==1)	{
				//		if ($rno==1) {
							$lndet1='';
							$lndet2='';
							$lndet3='';
							$adv=number_format($record->OT_ADVANCE, 0);
							$miscern=number_format($record->MISC_OT_EARNINGS, 2);
							$otnet=number_format($record->OT_NET_PAY, 0);
							$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
							substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
							.$enddate.' | ';
							$lndet2.=str_repeat(' ', 18 - strlen($OT_HOURS)).$OT_HOURS.
							str_repeat(' ', 10 - strlen($mearn)).$mearn.
							str_repeat(' ', 11 - strlen($record->OVERTIME_PAY)).$record->OVERTIME_PAY
							.' | ';
							$np = number_format($record->OVERTIME_PAY, 0);
							$netp=str_repeat('*', 6 - strlen($np)).$np;
							$otnetp=str_repeat('*', 6 - strlen($otnet)).$otnet;
							$lndet3.=str_repeat(' ', 11 - strlen($adv)).$adv.str_repeat(' ', 11 - strlen($miscern)).$miscern.
							str_repeat(' ', 17 - strlen($otnetp)).$otnetp.' | ';
//							$rno++;
	//						$logMsg .='recno-'.$rnop.'=if 1st='.$lndet1."\n";
						
						} else { 
							$adv=number_format($record->OT_ADVANCE, 0);
							$miscern=number_format($record->MISC_OT_EARNINGS, 2);
							$otnet=number_format($record->OT_NET_PAY, 0);
							
							$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
							substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
							.$enddate.' | '."\n";
							$lndet2 .=str_repeat(' ', 18 - strlen($OT_HOURS)).$OT_HOURS.
							str_repeat(' ', 10 - strlen($mearn)).$mearn.
							str_repeat(' ', 11 - strlen($record->OVERTIME_PAY)).$record->OVERTIME_PAY.' | '
							."\n";
							$np = number_format($record->OVERTIME_PAY, 0);
							$netp=str_repeat('*', 6 - strlen($np)).$np;
							$otnetp=str_repeat('*', 6 - strlen($otnet)).$otnet;
							$lndet3.=str_repeat(' ', 11 - strlen($adv)).$adv.str_repeat(' ', 11 - strlen($miscern)).$miscern.
							str_repeat(' ', 17 - strlen($otnetp)).$otnetp.' | '.
							"\n";
	//						$logMsg .='recno-'.$rnop.'=if 2nd='.$lndet1."\n";

							$logMsg .=$bkline."\n";
		//					$logMsg .= ' '."\n";
		//					$logMsg .= ' '."\n";
							$logMsg .=$hd1."\n";
							$logMsg .=$lndet1;
							$logMsg .=$bkline."\n";
							$logMsg .=$hd2."\n";
							$logMsg .=$lndet2;
							$logMsg .=$bkline."\n";
							$logMsg .=$hd3."\n";
							$logMsg .=$lndet3;
							$logMsg .=$bklin."\n";
						
 						$pg++;	
//						$rno=1;				
					
						}}
						if (fmod($rnop, 2)==1)	{
							$logMsg .=$bkline."\n";
						//					$logMsg .= ' '."\n";
						//					$logMsg .= ' '."\n";
											$logMsg .=$hd1."\n";
											$logMsg .=$lndet1."\n";
											$logMsg .=$bkline."\n";
											$logMsg .=$hd2."\n";
											$logMsg .=$lndet2."\n";
											$logMsg .=$bkline."\n";
											$logMsg .=$hd3."\n";
											$logMsg .=$lndet3."\n";
											$logMsg .=$bklin."\n";
						}				
	 			   					}					
					$logMsg .= Chr(12)."\n";
					fputs($filePointer,$logMsg);
					fclose($filePointer);
					 $txt1="otdata.txt";
					$txt1=$fileContainer;
					$files = array($txt1);
					$zipname = 'othersotpayslip.zip';
			   
	 
		
			$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
			foreach ($files as $file) {
			  $zip->addFile($file);
			}
			$zip->close();
$zt=0;		
			if ( $zt==1)  {	
				/* 
					if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
						$zip->addFile($fileContainer, basename($fileContainer));
						$zip->close();
						echo 'ZIP archive created successfully.'.'--'.$zt;
					} else {
				//		echo 'Failed to create ZIP archive.';
					}
				*/
					ob_clean();
					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename="' . $zipname . '"');
					header('Content-Length: ' . filesize($zipname));
					header('Pragma: no-cache');
					readfile($zipname);
				
				} else {
					ob_clean();
					header('Content-Type: application/text');
					header('Content-disposition: attachment; filename='.$txt1);
					header('Content-Length: ' . filesize($txt1));
					readfile($txt1);
				 }	
							unlink($fileContainer);
							 unlink($zipname);
							 unlink($txt1);
				
		
/*		
			ob_clean();
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename="' . $zipname . '"');
			header('Content-Length: ' . filesize($zipname));
			header('Pragma: no-cache');
			readfile($zipname);
*/				
//			header('Content-Type: application/zip');
//			header('Content-disposition: attachment; filename='.$zipname);
//			header('Content-Length: ' . filesize($zipname));
//			readfile($zipname);
			
					unlink($fileContainer);
					 unlink($zipname);
			
			
			
				}
	
			public function paystatement() {
				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$periodtodt= substr($periodtodate,8,2);
				$periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
				$periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 

				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
					 $fileContainer = "summreg.txt";
					 $filePointer = fopen($fileContainer,"w+");
					 if ($holget==1 || $holget==2 || $holget==7) {
						$bln=' ';
						$pg=1;
						$mcsumm = $this->Loan_adv_model->getpaystatementsumm($periodfromdate,$periodtodate,$att_payschm,$holget);
//var_dump($mcsumm);
						$line = str_repeat('-', 136);
						$logMsg=$company_name.str_repeat(' ',50).'Worker PayRoll Department Summary F/E   '.$periodtdate.
						"\n";
						$logMsg .= $line."\n";
						if ($holget==2) {
							$hrem='[18 PF]';
						}
						if ($holget==1) {
							$hrem='[MAIN Payroll]';
						}
						if ($holget==7) {
							$hrem='[VCH NPF]';
						}
						if ($att_payschm==161) {
							$hrem='[Retd]';
						}
						
						$pg=1;
						foreach ($mcsumm as $record) {
							$logMsg.=$hrem.str_repeat(' ',25).'DEPT '.$record->dept_code.str_repeat(' ',25).' Shift A'."\n"; 							
							$WORKING_HOURS=number_format($record->WORKING_HOURS,2);
							$NS_HRS=number_format($record->NS_HRS,2);
							$HL_HRS=number_format($record->HL_HRS,2);
							$LS_HRS=number_format($record->LS_HRS,2);
							$STL_HRS=number_format($record->STL_HRS,2);
							$STL_D=number_format($record->STL_D,2);
							$LOFF_HRS=number_format($record->LOFF_HRS,2);
							$VFIX_BASIC=number_format($record->VFIX_BASIC,2);
							$FXBAS=number_format(($record->FXBAS+$record->Basic_amount),2);
							$FDA=number_format($record->FDA,2);
							$NS_AMOUNT=number_format($record->NS_AMOUNT,2);
							$HOL_AMT=number_format($record->HOL_AMT,2);
							$LOFAMT=number_format($record->LOFAMT,2);
							$STL_WGS=number_format($record->STL_WGS,2);
							$ADJPF=number_format($record->ADJPF,2);
							$INCREMENTA=number_format($record->INCREMENTA,2);
							$ARREAR=number_format($record->ARREAR,2);
							$PFGROSS=number_format($record->PFGROSS,2);
							$GROSS=number_format($record->GROSS,2);
							$HRA=number_format($record->HRA,2);
							$ADJNPF=number_format($record->ADJNPF,2);
							$INCENTIVE_AMOUNT=number_format($record->INCENTIVE_AMOUNT,2);
							$MISS_EARN=number_format($record->MISS_EARN,2);
							$ADHONINC=number_format($record->ADHONINC,2);
							$ESI_GROSS=number_format($record->ESI_GROSS,2);
							$EPF=number_format($record->EPF,2);
							$ESIC=number_format($record->ESIC,2);
							$LWF=number_format($record->LWF,2);
							$PFLOAN=number_format($record->PFLOAN,2);
							$STL_ADVANCE=number_format($record->STL_ADVANCE,2);
							$PUJA_ADVANCE=number_format($record->PUJA_ADVANCE,2);
							$CO_LOAN=number_format($record->CO_LOAN,2);
							$PENCONT=number_format($record->PENCONT,2);
							$HRENT=number_format($record->HRENT,2);
							$PFNINT=number_format($record->PFNINT,2);
							$ADV=number_format($record->ADV,2);
							$FINE=number_format($record->FINE,2);
							$OTHADV=number_format($record->OTHADV,2);
							$TOTAL_DEDUCTION=number_format($record->TOTAL_DEDUCTION,2);
							$NETPAY=number_format($record->NETPAY,2);
							$B_F=number_format($record->B_F,2);
							$C_F=number_format($record->C_F,2);
							$NETPAYABLE=number_format($record->NETPAYABLE,2);
							$P_TAX=number_format($record->P_TAX,2);
							$TOTAL_EARN=number_format($record->TOTAL_EARN,2);
							$TOTPROD=number_format($record->TOTPROD,2);
										
							$logMsg.=
							'WRK HRS     : '.str_repeat(' ', 10 - strlen($WORKING_HOURS)).$WORKING_HOURS.'  '.
							'Vr Basic    : '.str_repeat(' ', 10 - strlen($VFIX_BASIC)).$VFIX_BASIC.'  '.
							'H.R.A       : '.str_repeat(' ', 10 - strlen($HRA)).$HRA.'  '.
							'PF CONT     : '.str_repeat(' ', 10 - strlen($EPF)).$EPF.'  '.
							'PEN CONT    : '.str_repeat(' ', 10 - strlen($PENCONT)).$PENCONT.
							"\n";
							$logMsg.=
							'NS HRS      : '.str_repeat(' ', 10 - strlen($NS_HRS)).$NS_HRS.'  '.
							'Fx Basic    : '.str_repeat(' ', 10 - strlen($FXBAS)).$FXBAS.'  '.
							'Adj N PF    : '.str_repeat(' ', 10 - strlen($ADJNPF)).$ADJNPF.'  '.
							'ESI         : '.str_repeat(' ', 10 - strlen($ESIC)).$ESIC.'  '.
							'P TAX       : '.str_repeat(' ', 10 - strlen($P_TAX)).$P_TAX.'  '.
							"\n";
							$logMsg.=
							'HOL HRS     : '.str_repeat(' ', 10 - strlen($HL_HRS)).$HL_HRS.'  '.
							'F.D.A       : '.str_repeat(' ', 10 - strlen($FDA)).$FDA.'  '.
							'INCENTIVE   : '.str_repeat(' ', 10 - strlen($INCENTIVE_AMOUNT)).$INCENTIVE_AMOUNT.'  '.
							'L.W.F       : '.str_repeat(' ', 10 - strlen($LWF)).$LWF.'  '.
							'H-RENT      : '.str_repeat(' ', 10 - strlen($HRENT)).$HRENT.'  '.
							"\n";
							$logMsg.=
							'LOST HRS    : '.str_repeat(' ', 10 - strlen($LS_HRS)).$LS_HRS.'  '.
							'NS ALLOW    : '.str_repeat(' ', 10 - strlen($NS_AMOUNT)).$NS_AMOUNT.'  '.
							'MISC. EARN  : '.str_repeat(' ', 10 - strlen($MISS_EARN)).$MISS_EARN.'  '.
							'PF LOAN     : '.str_repeat(' ', 10 - strlen($PFLOAN)).$PFLOAN.'  '.
							'PFLN INTL   : '.str_repeat(' ', 10 - strlen($PFNINT)).$PFNINT.'  '.
							"\n";
							$logMsg.=
							'STL HRS     : '.str_repeat(' ', 10 - strlen($STL_HRS)).$STL_HRS.'  '.
							'HOL WAGES   : '.str_repeat(' ', 10 - strlen($HOL_AMT)).$HOL_AMT.'  '.
							'ADHOC INC   : '.str_repeat(' ', 10 - strlen($ADHONINC)).$ADHONINC.'  '.
							'STL ADV     : '.str_repeat(' ', 10 - strlen($STL_ADVANCE)).$STL_ADVANCE.'  '.
							'ADVANCE     : '.str_repeat(' ', 10 - strlen($PFNINT)).$PFNINT.'  '.
							"\n";
							$logMsg.=
							'LOFF HRS    : '.str_repeat(' ', 10 - strlen($LOFF_HRS)).$LOFF_HRS.'  '.
							'STL WAGES   : '.str_repeat(' ', 10 - strlen($STL_WGS)).$STL_WGS.'  '.
							'TOTAL EARN  : '.str_repeat(' ', 12 - strlen($TOTAL_EARN)).$TOTAL_EARN.'  '.
							'CO LOAN     : '.str_repeat(' ', 10 - strlen($CO_LOAN)).$CO_LOAN.'  '.
							'OTHER ADV   : '.str_repeat(' ', 10 - strlen($OTHADV)).$OTHADV.'  '.
							"\n";
							$logMsg.=
							str_repeat(' ', 25).'  '.
							'ADJ PF      : '.str_repeat(' ', 10 - strlen($ADJPF)).$ADJPF.'  '.
							"\n";
							$logMsg.=
							str_repeat(' ', 25).'  '.
							'INCREMENT   : '.str_repeat(' ', 10 - strlen($INCREMENTA)).$INCREMENTA.'  '.
							"\n";
							$logMsg.=
							str_repeat(' ', 25).'  '.
							'ARREAR   : '.str_repeat(' ', 10 - strlen($ARREAR)).$ARREAR.'  '.
							"\n";
							$logMsg.=
							'TOT PROD    : '.str_repeat(' ', 10 - strlen($TOTPROD)).$TOTPROD.'  '.
							'PF GROSSS   : '.str_repeat(' ', 12 - strlen($PFGROSS)).$PFGROSS.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'TOTAL DEDN  : '.str_repeat(' ', 10 - strlen($TOTAL_DEDUCTION)).$TOTAL_DEDUCTION.'  '.
							"\n";
							$logMsg.=
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'GROSSS      : '.str_repeat(' ', 12 - strlen($GROSS)).$GROSS.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'NET PAY     : '.str_repeat(' ', 12 - strlen($NETPAY)).$NETPAY.'  '.
							'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
							"\n";
							$logMsg.=
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'P/BF        : '.str_repeat(' ', 10 - strlen($B_F)).$B_F.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
							"\n";
							$logMsg.=
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'P/CF        : '.str_repeat(' ', 10 - strlen($C_F)).$C_F.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
							"\n";
							$logMsg.=
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'NET PAYABLE : '.str_repeat(' ', 12 - strlen($NETPAYABLE)).$NETPAYABLE.'  '.
							'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
							'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
							"\n";
							
							
							$logMsg .= $line."\n";
							$logMsg .= $bln."\n";
							$logMsg .= $bln."\n";
							$pg++;
							if ($pg>3) {
								$logMsg .= Chr(12)."\n";
								$pg=1;
							}
						}		
						

							$logMsg .= $line.chr(18)."\n";
							$logMsg .= Chr(12)."\n";
//							$holget=99;
							$mcsumm = $this->Loan_adv_model->getpaystatementgsumm($periodfromdate,$periodtodate,$att_payschm,$holget);
							$pg=1;
							foreach ($mcsumm as $record) {
								$logMsg.=$hrem.str_repeat(' ',25).'DEPT '.'***'.str_repeat(' ',25).' Shift ***'."\n"; 							
								$WORKING_HOURS=round($record->WORKING_HOURS,2);
								$NS_HRS=number_format($record->NS_HRS,2);
								$HL_HRS=number_format($record->HL_HRS,2);
								$LS_HRS=number_format($record->LS_HRS,2);
								$STL_HRS=number_format($record->STL_HRS,2);
								$STL_D=number_format($record->STL_D,2);
								$LOFF_HRS=number_format($record->LOFF_HRS,2);
								$VFIX_BASIC=number_format($record->VFIX_BASIC,2);
								$FXBAS=number_format(($record->FXBAS+$record->Basic_amount),2);
								$FDA=round($record->FDA,2);
								$NS_AMOUNT=number_format($record->NS_AMOUNT,2);
								$HOL_AMT=number_format($record->HOL_AMT,2);
								$LOFAMT=number_format($record->LOFAMT,2);
								$STL_WGS=number_format($record->STL_WGS,2);
								$ADJPF=number_format($record->ADJPF,2);
								$INCREMENTA=number_format($record->INCREMENTA,2);
								$ARREAR=number_format($record->ARREAR,2);
								$PFGROSS=number_format($record->PFGROSS,2);
								$GROSS=number_format($record->GROSS,2);
								$HRA=number_format($record->HRA,2);
								$ADJNPF=number_format($record->ADJNPF,2);
								$INCENTIVE_AMOUNT=number_format($record->INCENTIVE_AMOUNT,2);
								$MISS_EARN=number_format(($record->MISS_EARN+$record->arrear_plus),2);
								$ADHONINC=number_format($record->ADHONINC,2);
								$ESI_GROSS=number_format($record->ESI_GROSS,2);
								$EPF=number_format($record->EPF,2);
								$ESIC=number_format($record->ESIC,2);
								$LWF=number_format($record->LWF,2);
								$PFLOAN=number_format($record->PFLOAN,2);
								$STL_ADVANCE=number_format($record->STL_ADVANCE,2);
								$PUJA_ADVANCE=number_format($record->PUJA_ADVANCE,2);
								$CO_LOAN=number_format($record->CO_LOAN,2);
								$PENCONT=number_format($record->PENCONT,2);
								$HRENT=number_format($record->HRENT,2);
								$PFNINT=number_format($record->PFNINT,2);
								$ADV=number_format($record->ADV,2);
								$FINE=number_format($record->FINE,2);
								$OTHADV=number_format(($record->OTHADV+$record->exadvance),2);
								$TOTAL_DEDUCTION=number_format($record->TOTAL_DEDUCTION,2);
								$NETPAY=round($record->NETPAY,2);
								$B_F=number_format($record->B_F,2);
								$C_F=number_format($record->C_F,2);
								$NETPAYABLE=number_format($record->NETPAYABLE,0);
								$P_TAX=number_format($record->P_TAX,2);
								$TOTAL_EARN=round($record->TOTAL_EARN,2);
								$TOTPROD=number_format($record->TOTPROD,2);
											
								$logMsg.=
								'WRK HRS     : '.str_repeat(' ', 10 - strlen($WORKING_HOURS)).$WORKING_HOURS.'  '.
								'Vr Basic    : '.str_repeat(' ', 10 - strlen($VFIX_BASIC)).$VFIX_BASIC.'  '.
								'H.R.A       : '.str_repeat(' ', 10 - strlen($HRA)).$HRA.'  '.
								'PF CONT     : '.str_repeat(' ', 10 - strlen($EPF)).$EPF.'  '.
								'PEN CONT    : '.str_repeat(' ', 10 - strlen($PENCONT)).$PENCONT.
								"\n";
								$logMsg.=
								'NS HRS      : '.str_repeat(' ', 10 - strlen($NS_HRS)).$NS_HRS.'  '.
								'Fx Basic    : '.str_repeat(' ', 10 - strlen($FXBAS)).$FXBAS.'  '.
								'Adj N PF    : '.str_repeat(' ', 10 - strlen($ADJNPF)).$ADJNPF.'  '.
								'ESI         : '.str_repeat(' ', 10 - strlen($ESIC)).$ESIC.'  '.
								'P TAX       : '.str_repeat(' ', 10 - strlen($P_TAX)).$P_TAX.'  '.
								"\n";
								$logMsg.=
								'HOL HRS     : '.str_repeat(' ', 10 - strlen($HL_HRS)).$HL_HRS.'  '.
								'F.D.A       : '.str_repeat(' ', 10 - strlen($FDA)).$FDA.'  '.
								'INCENTIVE   : '.str_repeat(' ', 10 - strlen($INCENTIVE_AMOUNT)).$INCENTIVE_AMOUNT.'  '.
								'L.W.F       : '.str_repeat(' ', 10 - strlen($LWF)).$LWF.'  '.
								'H-RENT      : '.str_repeat(' ', 10 - strlen($HRENT)).$HRENT.'  '.
								"\n";
								$logMsg.=
								'LOST HRS    : '.str_repeat(' ', 10 - strlen($LS_HRS)).$LS_HRS.'  '.
								'NS ALLOW    : '.str_repeat(' ', 10 - strlen($NS_AMOUNT)).$NS_AMOUNT.'  '.
								'MISC. EARN  : '.str_repeat(' ', 10 - strlen($MISS_EARN)).$MISS_EARN.'  '.
								'PF LOAN     : '.str_repeat(' ', 10 - strlen($PFLOAN)).$PFLOAN.'  '.
								'PFLN INTL   : '.str_repeat(' ', 10 - strlen($PFNINT)).$PFNINT.'  '.
								"\n";
								$logMsg.=
								'STL HRS     : '.str_repeat(' ', 10 - strlen($STL_HRS)).$STL_HRS.'  '.
								'HOL WAGES   : '.str_repeat(' ', 10 - strlen($HOL_AMT)).$HOL_AMT.'  '.
								'ADHOC INC   : '.str_repeat(' ', 10 - strlen($ADHONINC)).$ADHONINC.'  '.
								'STL ADV     : '.str_repeat(' ', 10 - strlen($STL_ADVANCE)).$STL_ADVANCE.'  '.
								'ADVANCE     : '.str_repeat(' ', 10 - strlen($PFNINT)).$PFNINT.'  '.
								"\n";
								$logMsg.=
								'LOFF HRS    : '.str_repeat(' ', 10 - strlen($LOFF_HRS)).$LOFF_HRS.'  '.
								'STL WAGES   : '.str_repeat(' ', 10 - strlen($STL_WGS)).$STL_WGS.'  '.
								'TOTAL EARN  : '.str_repeat(' ', 12 - strlen($TOTAL_EARN)).$TOTAL_EARN.'  '.
								'CO LOAN     : '.str_repeat(' ', 10 - strlen($CO_LOAN)).$CO_LOAN.'  '.
								'OTHER ADV   : '.str_repeat(' ', 10 - strlen($OTHADV)).$OTHADV.'  '.
								"\n";
								$logMsg.=
								str_repeat(' ', 25).'  '.
								'ADJ PF      : '.str_repeat(' ', 10 - strlen($ADJPF)).$ADJPF.'  '.
								"\n";
								$logMsg.=
								str_repeat(' ', 25).'  '.
								'INCREMENT   : '.str_repeat(' ', 10 - strlen($INCREMENTA)).$INCREMENTA.'  '.
								"\n";
								$logMsg.=
								str_repeat(' ', 25).'  '.
								'ARREAR   : '.str_repeat(' ', 10 - strlen($ARREAR)).$ARREAR.'  '.
								"\n";
								$logMsg.=
								'TOT PROD    : '.str_repeat(' ', 10 - strlen($TOTPROD)).$TOTPROD.'  '.
								'PF GROSSS   : '.str_repeat(' ', 12 - strlen($PFGROSS)).$PFGROSS.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'TOTAL DEDN  : '.str_repeat(' ', 10 - strlen($TOTAL_DEDUCTION)).$TOTAL_DEDUCTION.'  '.
								"\n";
								$logMsg.=
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'GROSSS      : '.str_repeat(' ', 12 - strlen($GROSS)).$GROSS.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'NET PAY     : '.str_repeat(' ', 12 - strlen($NETPAY)).$NETPAY.'  '.
								'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
								"\n";
								$logMsg.=
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'P/BF        : '.str_repeat(' ', 10 - strlen($B_F)).$B_F.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
								"\n";
								$logMsg.=
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'P/CF        : '.str_repeat(' ', 10 - strlen($C_F)).$C_F.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
								"\n";
								$NETPAYABLE=floatval(str_replace(",", "", $NETPAYABLE));
								$logMsg.=
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'NET PAYABLE : '.str_repeat(' ', 12 - strlen($NETPAYABLE)).$NETPAYABLE.'  '.
								'            : '.str_repeat(' ', 10 - strlen($bln)).$bln.'  '.
								'            : '.str_repeat(' ', 50 - strlen($bln)).$bln.'  '.
								"\n";
								
								
								$logMsg .= $line."\n";
								$logMsg .= $bln."\n";
								$logMsg .= $bln."\n";
								$pg++;
								if ($pg>3) {
									$logMsg .= Chr(12)."\n";
									$pg=1;
								}
							}		
							
	
								$logMsg .= $line.chr(18)."\n";
								$logMsg .= Chr(12)."\n";

							fputs($filePointer,$logMsg);
							fclose($filePointer);
							$txt1="summstmt.txt";
							$txt1=$fileContainer;
							$files = array($txt1);
							$zipname = 'summreg.zip';
	
							
					
					//	  $logMsg='';
						



					 }	
					 if ($holget==6 ) {
						if ($att_payschm==159) {$payschemeName='(1)';}
						if ($att_payschm==160) {$payschemeName='(2)';}
						if ($att_payschm==158) {$payschemeName='(3)';}
						
						$mccodes = $this->Loan_adv_model->getpaystatement($periodfromdate,$periodtodate,$att_payschm,$holget);
						$mcsumm = $this->Loan_adv_model->getpaystatementsumm($periodfromdate,$periodtodate,$att_payschm,$holget);
						$data = [];
						$line = str_repeat('-', 79);
					//	  $logMsg=$company_name."\n";
	
							$pg=1;
						  $logMsg='';
						  $ln1=$payschemeName."\n";
						  $ln2= "Summary Statement for the period from  ".$periodfrmdate.'    ' . 'To '.$periodtdate;
						  $ln3= $line."\n";
						  $ln4= 'Dept Code  EB No     Name                         Amount   OT Amt   TOT Amount'."\n";
						  $ln5.= $line."\n";
	
						  $totamt=0;
						  $tothrs=0;
						  $gtotamt=0;
						  $gotpay=0;
						  $gtotpay=0;
						  $lnn=1;
						  $dpc='';
						foreach ($mccodes as $record) {
							if ($dpc<>$record->dept_code) { 	
								if ($pg>1) {
			//						$logMsg .= Chr(12)."\n";
									/*$gt="Dept Total ".$dpc;
									$blnk='';
									$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
									substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
									str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
									str_repeat(' ', 8 - strlen($blnk)).$blnk.
									str_repeat(' ', 10 - strlen($totamt)).$totamt.*/
									"\n";
				
									$totamt=0;
									$tothrs=0;
									$logMsg .= $line."\n";
									//$logMsg .= Chr(12)."\n";
									$lnn=1;
								}	
								$dpc=$record->dept_code;
								$dptcode=$dpc;
								//$pg++;
							}		
							if ($lnn>58) {
								$logMsg .= Chr(12)."\n";
								$lnn=1;
							}	
	
							if  ($lnn==1) {
								$logMsg .=$payschemeName."\n";
								$logMsg .=$ln2."\n";
								$logMsg .= $ln3;
								$logMsg .= $ln4;
								$logMsg .= $ln5;
								$lnn=5;
							}			
								$Net_Payble=number_format($record->Net_Payble,0);
								$otpay=number_format($record->OVERTIME_PAY,0);
								$totpay=number_format($record->total_amt,0);
					
								$logMsg .=$dpc.'         ' .$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
								substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
								str_repeat(' ', 15 - strlen($Net_Payble)).$Net_Payble.
								str_repeat(' ', 9 - strlen($otpay)).$otpay.
								str_repeat(' ', 13 - strlen($totpay)).$totpay.
								"\n";
								$logMsg .= $line."\n";
								$gtotpay=$gtotpay+$record->total_amt;
								$gtotamt=$gtotamt+$record->Net_Payble;
								$gotpay=$gotpay+$record->OVERTIME_PAY;
								$lnn=$lnn+2;
							}
					//		$logMsg .= Chr(12)."\n";
							$logMsg .= $line."\n";
							
							$gt="Grand Total";
							$blnk='';
							$logMsg .=$Blnk.str_repeat(' ', 21- strlen($blnk)).
							substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
							str_repeat(' ', 15 - strlen($gtotamt)).$gtotamt.
							str_repeat(' ', 9 - strlen($gotpay)).$gotpay.
							str_repeat(' ', 13 - strlen($gtotpay)).$gtotpay.
							"\n";
		
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";

							$ln1=$payschemeName."\n";
							$ln2= "Departmentwise Summary Statement for the period from  ".$periodfrmdate.'    ' . 'To '.$periodtdate;
							$ln3= $line."\n";
							$ln4= 'Dept Code    Department                W.hours       Amount   OT Amt   TOT Amount'."\n";
							$ln5.= $line."\n";
							$logMsg .=$payschemeName."\n";
							$logMsg .=$ln2."\n";
							$logMsg .= $ln3;
							$logMsg .= $ln4;
							$logMsg .= $ln5;
							$lnn=5;

							$totamt=0;
							$tothrs=0;
							$gtotamt=0;
							$gotpay=0;
							$gtotpay=0;
							foreach ($mcsumm as $record) {
						
								$Net_Payble=number_format($record->Net_Payble,0);
								$otpay=number_format($record->OVERTIME_PAY,0);
								$totpay=number_format($record->total_amt,0);
								$whrs=number_format($record->whrs,2);
								$department=$record->department;
								$dpc=$record->dept_code;
							
								$logMsg .=$dpc.'         ' .$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
								substr($department,0,15).str_repeat(' ', 15 - strlen(substr($department,0,15))).
								str_repeat(' ', 11 - strlen($whrs)).$whrs.
								str_repeat(' ', 11 - strlen($Net_Payble)).$Net_Payble.
								str_repeat(' ', 9 - strlen($otpay)).$otpay.
								str_repeat(' ', 13 - strlen($totpay)).$totpay.
								"\n";
								$logMsg .= $line."\n";
								$gtotpay=$gtotpay+$record->total_amt;
								$gtotamt=$gtotamt+$record->Net_Payble;
								$gotpay=$gotpay+$record->OVERTIME_PAY;
								$tothrs=$tothrs+$record->whrs;
								$lnn=$lnn+2;
							}
					//		$logMsg .= Chr(12)."\n";
							$logMsg .= $line."\n";
							
							$gt="Grand Total";
							$blnk='';
							$logMsg .=$Blnk.str_repeat(' ', 21- strlen($blnk)).
							substr($gt,0,15).str_repeat(' ', 15 - strlen(substr($gt,0,15))).
							str_repeat(' ', 11 - strlen($tothrs)).$tothrs.
							str_repeat(' ', 11 - strlen($gtotamt)).$gtotamt.
							str_repeat(' ', 9 - strlen($gotpay)).$gotpay.
							str_repeat(' ', 13 - strlen($gtotpay)).$gtotpay.
							"\n";
		
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";

						fputs($filePointer,$logMsg);
						fclose($filePointer);
						$txt1="paydata.txt";
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'summreg.zip';

			
					}
	
		if ($holget==15 ) {

 



$fileContainer = FCPATH . "ptaxreg.txt";
$zipname       = FCPATH . "summreg.zip";

// 1) Build your $logMsg fully first
 $line = str_repeat('=', 120);

$sdate = date('d-m-Y', strtotime($periodfromdate));
$ldate = date('d-m-Y', strtotime($periodtodate));

$ln2 = 'THE EMPIRE JUTE CO.LTD.';
$ln3 = 'FORTNIGHTLY WORKERS P-TAX SUMMARY FOR THE PERIOD OF '.$sdate.' TO '.$ldate."\n";
$ln4 = $line."\n";
$ln5 = "EARNING GROUP     RATE OF      NO. OF         TOTAL          P.TAX DEDUCTION    P.TAX DEDUCTION            TOTAL\n";
$ln6 = "                 DEDUCTION      HANDS         EARNINGS        CURRENT                 ARREAR             DEDUCTION\n";
$ln7 = $line."\n";

$logMsg = "";
$logMsg .= $line."\n";
$logMsg .= $ln2."\n";
$logMsg .= $ln3;
$logMsg .= $ln4.$ln5.$ln6.$ln7;

$mccodes = $this->Loan_adv_model->getptaxsummary($periodfromdate, $periodtodate, $att_payschm, $holget);

$gtothrs = 0;
$gtotpay = 0;
$gtotamt = 0;

foreach ($mccodes as $record) {
    $range          = (string)$record->range;
    $rate           = number_format((float)$record->rate, 2);
    $count          = (string)$record->count;
    $total_ptax_earn= number_format((float)$record->total_ptax_earn, 2);
    $totptax        = number_format((float)$record->totptax, 2);

    $logMsg .= ' '.$range
        . str_repeat(' ', max(1, 10 - strlen($rate))) . $rate
        . str_repeat(' ', max(1, 12 - strlen($count))) . $count
        . str_repeat(' ', max(1, 20 - strlen($total_ptax_earn))) . $total_ptax_earn
        . str_repeat(' ', max(1, 17 - strlen($totptax))) . $totptax
        . str_repeat(' ', max(1, 42 - strlen($totptax))) . $totptax
        . "\n";

    $logMsg .= $line."\n";

    $gtothrs += (int)$record->count;
    $gtotpay += (float)$record->total_ptax_earn;
    $gtotamt += (float)$record->totptax;
}

$logMsg .= " ***Grand Total***"
    . str_repeat(' ', max(1, 12 - strlen((string)$gtothrs))) . $gtothrs
    . str_repeat(' ', max(1, 17 - strlen(number_format($gtotpay,2)))) . number_format($gtotpay,2)
    . str_repeat(' ', max(1, 13 - strlen(number_format($gtotamt,2)))) . number_format($gtotamt,2)
    . "\n";
$logMsg .= $line."\n";

// 2) Write TXT (NO undefined vars, no partial writes)
//file_put_contents($fileContainer, $logMsg);
//echo $fileContainer;

//$logMsg =  		str_repeat(' ', 9 - strlen('$gpdif')).'$gpdif';
						
/* 	fputs($filePointer,$logMsg);
			fclose($filePointer);
			$txt1="Payregdata.txt";
			$txt1=$fileContainer;
			$files = array($txt1);
			$zipname = 'VouPayreg.zip';



}
 
					$zip = new ZipArchive;
					$zip->open($zipname, ZipArchive::CREATE);
					foreach ($files as $file) {
					  $zip->addFile($file);
					}
					$zip->close();
						
						header('Content-Type: application/zip');
						header('Content-disposition: attachment; filename='.$zipname);
						header('Content-Length: ' . filesize($zipname));
						readfile($zipname);
						
								unlink($fileContainer);
								 unlink($zipname);
 */
	
$fileContainer = FCPATH . "ptaxreg.txt";
$zipname       = FCPATH . "VouPayreg.zip";

// write literal string
//$logMsg = str_repeat(' ', max(0, 9 - strlen('$gidif'))) . '$gidif' . "\n";
file_put_contents($fileContainer, $logMsg);

// create zip

		}
			
if (file_exists($zipname)) unlink($zipname);

$zip = new ZipArchive();
$zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE);
$zip->addFile($fileContainer, basename($fileContainer));
$zip->close();

// clean output buffer
while (ob_get_level()) ob_end_clean();

// download
header('Content-Type: application/zip');
header('Content-Disposition: attachment; filename="'.basename($zipname).'"');
header('Content-Length: ' . filesize($zipname));

readfile($zipname);

// cleanup
@unlink($fileContainer);
@unlink($zipname);
exit;




				}

			public function payregisterprint() {
				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$periodtodt= substr($periodtodate,8,2);
				$periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
				$periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 



				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
				 $this->data['att_payschm'] = $att_payschm;
					 $fileContainer = "paydata.txt";
					 $filePointer = fopen($fileContainer,"w+");
					 if ($holget==14 ) {
						$mccodes = $this->Loan_adv_model->getpayregisterbankdata($periodfromdate,$periodtodate,$att_payschm,$holget);
						$spreadsheet = new Spreadsheet();
						$sheet = $spreadsheet->getActiveSheet();
						$sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4);
						$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
						
						$sheet->getPageMargins()->setTop(.25);
						$sheet->getPageMargins()->setRight(0.25);
						$sheet->getPageMargins()->setLeft(0.25);
						$sheet->getPageMargins()->setBottom(0.25);
						
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
								'size' => 12,
							],
						];
//						EB.NO	NAME	ACCOUNT NO	IFSC NO	NET PAY	

    					$hed1='Worker Wages Bank Payment Sheet for FNE '.$ldate; 
						$sheet->setCellValue('A1', 'The Empire Jute Co Ltd');
						$sheet->setCellValue('A2', $hed1);
						$sheet->setCellValue('A3', 'EB No');
						$sheet->setCellValue('B3', 'Name');
						$sheet->setCellValue('C3', 'ACCOUNT NO');
						$sheet->setCellValue('D3', 'IFSC CODE');
						$sheet->setCellValue('e3', 'NET PAY');
						$sheet->setCellValue('f3', 'BANK');
						$N=1;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A1:f1');
						$N=2;	
						$b='A'.$N.':f'.$N;
						$sheet->mergeCells('A2:f2');
					  //$objPHPExcel->getActiveSheet($c)->mergeCells($b);
						$n=4;
						$no=1;
						$totamt=0;
						$tothrs=0;
						foreach ($mccodes as $row) {
								$cln='A'.$n;
								$rw=$row->eb_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$rw=$row->wname; 
								$cln='B'.$n;
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='c'.$n;
								$rw=$row->bank_acc_no; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cll=$cln;
								$sheet->getCell($cll)
								->setValueExplicit(
									$rw,
									\PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
								);
								$cln='d'.$n;
								$rw=$row->ifsc_code; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='e'.$n;
								$rw=$row->NET_PAY+$row->otnet+$row->attincn; 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$cln='f'.$n;
								$rw=substr($row->ifsc_code,0,4); 
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$n++;
								$totamt=$totamt+$row->NET_PAY+$row->otnet+$row->attincn;
								 
							}		
							$date = date('d/M/Y');
							$cln='a'.$n;
							$sheet->getStyle($cln)->applyFromArray($borderStyle);
							$cln='c'.$n;
							$sheet->setCellValue($cln, 'Total');
							$cln='d'.$n;
							$cln='e'.$n;
							$sheet->setCellValue($cln, $totamt);
							$clnr='a'.$n.':'.'f'.$n;
							$sheet->getStyle($clnr)->applyFromArray($boldFontStyle);
						
							
					
						
					
					 
						
					
							$sheet->getColumnDimension('A')->setWidth(9);
							$sheet->getColumnDimension('B')->setWidth(30);
							$sheet->getColumnDimension('C')->setWidth(25);
							$sheet->getColumnDimension('d')->setWidth(25);
							$sheet->getColumnDimension('e')->setWidth(15);
							$sheet->getColumnDimension('f')->setWidth(15);
							$centerAlignment = $sheet->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$centerAlignment = $sheet->getStyle('A3:f3')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							
							// Apply font style to cell A1
							$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							
						
							
							$sheet->getStyle('A1:f1')->applyFromArray($borderStyle);
							$sheet->getStyle('A2:f2')->applyFromArray($borderStyle);
							$sheet->getStyle('A3:a3')->applyFromArray($borderStyle);
							$sheet->getStyle('b3:b3')->applyFromArray($borderStyle);
							$sheet->getStyle('c3:c3')->applyFromArray($borderStyle);
							$sheet->getStyle('d3:d3')->applyFromArray($borderStyle);
							$sheet->getStyle('e3:e3')->applyFromArray($borderStyle);
							$sheet->getStyle('f3:f3')->applyFromArray($borderStyle);
					
						$deptname='Bank Sheet';	
						$sheet->setTitle($deptname);
					
					
						
							
							$filename="bank_".$ldate.'.xlsx';
						
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
					 if ($holget==8 ) {
						$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
						$line = str_repeat('=', 85);
						$logMsg='';
						$ln1=$payschemeName."\n";
						$ln2= "THE EMPIRE JUTE CO. LTD   PAYIMAGE COMPARATIVE STATEMENT F/E ".$periodtodate ;
						$ln2a= $payschemeName;
						$ln3= $line."\n";
						$ln4= 'DPT EMPNO <----NAME---->  O-PFGRS  N-PFGRS O-DED N-DED O-NET N-NET  DIF-PFGR  DIF-NET'."\n";
					 	$ln5= $line."\n";
						$tgpdifamt=0;
						$tnpdifamt=0;
						$ggpdifamt=0;
						$gnpdifamt=0;
						$trec=0;
						$grec=0;
						$dpc='';
						$lnn=1;	

						foreach ($mccodes as $record) {
							if ($dpc<>$record->dept_code) { 	
								$logMsg .= $line."\n";
									$gt="Dept Total ".$dpc;
									$blnk='';
									$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
									'Total Rec'.str_repeat(' ', 9 - strlen($trec)).$trec.
									str_repeat(' ', 9 - strlen($blnk)).$blnk.
									str_repeat(' ', 36 - strlen($tgpdifamt)).$tgpdifamt.
									str_repeat(' ', 10 - strlen($tnpdifamt)).$tnpdifamt.
									"\n";
									$tgpdifamt=0;
									$tnpdifamt=0;
									$logMsg .= $line."\n";
									$trec=0;
									$logMsg .= ' '."\n";
									$logMsg .= ' '."\n";
									$logMsg .= Chr(12)."\n";
									$lnn=1;
						
									$dpc=$record->dept_code;
								$dptcode=$dpc;
								$pg++;
							}		
							if ($lnn>58) {
								$logMsg .= Chr(12)."\n";
								$lnn=1;
							}	

							if  ($lnn==1) {
								$logMsg .=$payschemeName."\n";
								$logMsg .=$ln2.$dptcode."\n";
								$logMsg .=$ln2a."\n";
								$logMsg .= $ln3;
								$logMsg .= $ln4;
								$logMsg .= $ln5;
					 
								$lnn=8;
							}			
								$gpdif=round($record->GROSS_PAY-$record->GROSS_PAY100,2);
								$npdif=round($record->NET_PAY-$record->NET100,0);
								if ($gpdif>0) { $gpdif=0; }
								if ($npdif>0) { $npdif=0;}
								$tgpdifamt=$tgpdifamt+$gpdif;
								$tnpdifamt=$tnpdifamt+$npdif;
								$ggpdifamt=$ggpdifamt+$gpdif;
								$gnpdifamt=$gnpdifamt+$npdif;
								$np100=round($record->NET100,0);
	//						echo $record->eb_no.'='.$gpdif."<br>";			
					 			$logMsg .=$dpc.'  '.$record->eb_no.str_repeat(' ', 6- strlen($record->eb_no)).
								substr($record->wname,0,15).str_repeat(' ', 15 - strlen(substr($record->wname,0,15))).
								str_repeat(' ', 9 - strlen($record->GROSS_PAY)).$record->GROSS_PAY100.
								str_repeat(' ', 9 - strlen($record->GROSS_PAY100)).$record->GROSS_PAY.
								str_repeat(' ', 8 - strlen($blnk)).$blnk.
//								str_repeat(' ', 10 - strlen($blnk)).$blnk.
								str_repeat(' ', 9 - strlen($np100)).$np100.
								str_repeat(' ', 6 - strlen($record->NET_PAY)).$record->NET_PAY.
								
								str_repeat(' ', 9 - strlen($gpdif)).$gpdif.
								str_repeat(' ', 9 - strlen($npdif)).$npdif.
								"\n";
								$lnn=$lnn+1;
								$trec=$trec+1;
								$grec=$grec+1;
							}

									$gt="Dept Total ".$dpc;
									$blnk='';
									$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
									'Total Rec'.str_repeat(' ', 9 - strlen($trec)).$trec.
									str_repeat(' ', 9 - strlen($blnk)).$blnk.
									str_repeat(' ', 36 - strlen($tgpdifamt)).$tgpdifamt.
									str_repeat(' ', 10 - strlen($tnpdifamt)).$tnpdifamt.
									"\n";
									$tgpdifamt=0;
									$tnpdifamt=0;
									$logMsg .= $line."\n";
									$trec=0;
									$logMsg .= ' '."\n";
									$logMsg .= ' '."\n";
//									$logMsg .= Chr(12)."\n";
									$lnn=$lnn+2;
						
									$gt="Grand Total ";
									$blnk='';
									$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
									'Total Rec'.str_repeat(' ', 9 - strlen($grec)).$grec.
									str_repeat(' ', 9 - strlen($blnk)).$blnk.
									str_repeat(' ', 36 - strlen($ggpdifamt)).$ggpdifamt.
									str_repeat(' ', 10 - strlen($gnpdifamt)).$gnpdifamt.
									"\n";
									$logMsg .= $line."\n";
									$trec=0;
									$logMsg .= ' '."\n";
									$logMsg .= ' '."\n";
//									$logMsg .= Chr(12)."\n";
									$lnn=$lnn+2;
									$ln3= $line."\n";
									$logMsg .= Chr(12)."\n";
						
			
									fputs($filePointer,$logMsg);
									fclose($filePointer);
									 $txt1="paydata.txt";
									$txt1=$fileContainer;
									$files = array($txt1);
									$zipname = 'payregister.zip';
									

					 }	
					 if ($holget==4 || $holget ==5 ) {
						$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
						$dpsumm = $this->Loan_adv_model->getpayregistersummdata($periodfromdate,$periodtodate,$att_payschm,$holget);
						$data = [];
						  $line = str_repeat('-', 79);
					//	  $logMsg=$company_name."\n";

	  					  $pg=1;
						  $logMsg='';
						  $ln1=$payschemeName."\n";
						  $ln2= "Voucher Register for FNE To ".$periodtodate.'           '.'Dept Code ';
						  $ln2a= $payschemeName;
						  $ln3= $line."\n";
						  $ln4= '  EBNO  | NAME                | TOTAL | RATE  | ADJ AMT | AMOUNT   | SIGNATURE '."\n";
						  $ln6= '        |                     | HOURS |       | ADV DED |          | '."\n";
						  $ln7= '        |                     |       |       | ADH AMT |          |'."\n";
						  $ln5.= $line."\n";

						  $totamt=0;
						  $tothrs=0;
						  $gtotamt=0;
						  $gtothrs=0;
						  $lnn=1;
						  $dpc='';
						foreach ($mccodes as $record) {
							if ($dpc<>$record->dept_code) { 	
								if ($pg>1) {
			//						$logMsg .= Chr(12)."\n";
									$gt="Dept Total ".$dpc;
									$blnk='';
									$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
									substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
									str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
									str_repeat(' ', 8 - strlen($blnk)).$blnk.
									str_repeat(' ', 10 - strlen($totamt)).$totamt.
									"\n";
				
									$totamt=0;
									$tothrs=0;
									$logMsg .= $line."\n";
									$logMsg .= ' '."\n";
									$logMsg .= ' '."\n";
//									$logMsg .= Chr(12)."\n";
									$lnn=$lnn+2;
								}	
								$dpc=$record->dept_code;
								$dptcode=$dpc;
								$pg++;
							}		
							if ($lnn>58) {
								$logMsg .= Chr(12)."\n";
								$lnn=1;
							}	

							if  ($lnn==1) {
								$logMsg .=$payschemeName."\n";
								$logMsg .=$ln2.$dptcode."\n";
								$logMsg .=$ln2a."\n";
								$logMsg .= $ln3;
								$logMsg .= $ln4;
								$logMsg .= $ln6;
								$logMsg .= $ln7;
								$logMsg .= $ln5;
								$lnn=8;
							}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
								$whrs = number_format($record->WORKING_HOURS+$record->HOLIDAY_HR+$record->OT_HOURS, 2);
								$arr=number_format($record->ARR_PLUS+$record->ARR_MINUS, 2);
								$adv=number_format($record->ADVANCE, 2);
								$npay=number_format($record->Net_Payble, 0);
								$RATE= $record->RATE_PER_DAY;
								$logMsg .=$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
								substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
								str_repeat(' ', 9 - strlen($whrs)).$whrs.
								str_repeat(' ', 8 - strlen($RATE)).$RATE.
								str_repeat(' ', 10 - strlen($arr)).$arr.
								str_repeat(' ', 10 - strlen($npay)).$npay.
								$$periodtodate.
								"\n";
								$bln='';
								$dbnc='Dept Code '.$dpc;
								$logMsg .=$bln.str_repeat(' ', 10- strlen($bln)).
								substr($dbnc,0,20).str_repeat(' ', 20 - strlen(substr($dbnc,0,20))).
								str_repeat(' ', 9 - strlen($bln)).$bln.
								str_repeat(' ', 8 - strlen($bln)).$bln.
								str_repeat(' ', 10 - strlen($adv)).$adv.
								"\n";
								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
 	  						   $logMsg .= $line."\n";
								$totamt=$totamt+$record->Net_Payble;
								$tothrs=$tothrs+$whrs;
								$lnn=$lnn+6;
								$gtotamt=$gtotamt+$record->Net_Payble;
								$gtothrs=$gtothrs+$whrs;
							}
							$gt="Dept Total ".$dpc;
							$blnk='';
							$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
							substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
							str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
							str_repeat(' ', 8 - strlen($blnk)).$blnk.
							str_repeat(' ', 10 - strlen($totamt)).$totamt.
							"\n";

//							$logMsg .= Chr(12)."\n";
							$logMsg .= $line."\n";
							$logMsg .= $line."\n";
							
							$gt="Grand Total";
							$blnk='';
							$logMsg .=$blnk.str_repeat(' ', 10- strlen($blnk)).
							substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
							str_repeat(' ', 9 - strlen($gtothrs)).$gtothrs.
							str_repeat(' ', 8 - strlen($blnk)).$blnk.
							str_repeat(' ', 10 - strlen($gtotamt)).$gtotamt.
							"\n";
		
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";

							$ln1=$payschemeName."\n";
							$ln2= "Voucher Summary Register for FNE To ".$periodtodate.'           '.'Dept Code ';
							$ln2a= $payschemeName;
							$ln3= $line."\n";
							$ln4= '  DEPT CODE  | TOTAL HOURS            | TOTAL AMOUNT    |  DEPARTMENT.'."\n";
							$ln5.= $line."\n";
							$logMsg .=$payschemeName."\n";
							$logMsg .=$ln2."\n";
							$logMsg .=$ln2a."\n";
							$logMsg .= $ln3;
							$logMsg .= $ln4;
							$logMsg .= $ln3;
							$lnn=5;

							$totamt=0;
							$tothrs=0;
							$gtotamt=0;
							$gtothrs=0;
							$lnn=1;
							$dpc='';
						foreach ($dpsumm as $record) {
							
							$whrs = number_format($record->whrs, 2);
							$npay=number_format($record->Net_Payble, 0);
							$tothrs=$tothrs+$whrs;
							$totamta=$totamta+$record->Net_Payble;
							$logMsg .='    '.$record->dept_code.str_repeat(' ', 15- strlen($record->dept_code)).
							str_repeat(' ', 14 - strlen($whrs)).$whrs.
							str_repeat(' ', 18 - strlen($npay)).$npay.
							'        '.$record->department.
							"\n";
							$logMsg .= $ln3;
						}	
						$gt='Grand Total';
						$logMsg .='    '.$gt.str_repeat(' ', 15- strlen($gt)).
						str_repeat(' ', 14 - strlen($tothrs)).$tothrs.
						str_repeat(' ', 18 - strlen($totamta)).$totamta.
						''.
						"\n";
						$ln3= $line."\n";
						$logMsg .= Chr(12)."\n";
			

						fputs($filePointer,$logMsg);
						fclose($filePointer);
						 $txt1="paydata.txt";
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'payregister.zip';
				   }

				   if ($holget==3  ) {
					$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$dpsumm = $this->Loan_adv_model->getpayregistersummdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 79);
				//	  $logMsg=$company_name."\n";

						$pg=1;
					  $logMsg='';
					  $ln1=$payschemeName."\n";
					  $ln2= "Voucher Register for FNE To ".$periodtodate.'           '.'Dept Code ';
					  $ln2a= $payschemeName;
					  $ln3= $line."\n";
					  $ln4= '  EBNO  | NAME                | TOTAL | RATE  | ADJ AMT | AMOUNT   | SIGNATURE '."\n";
					  $ln6= '        |                     | HOURS |       | ADV DED |          | '."\n";
					  $ln7= '        |                     |       |       | ADH AMT |          |'."\n";
					  $ln5.= $line."\n";

					  $totamt=0;
					  $tothrs=0;
					  $gtotamt=0;
					  $gtothrs=0;
					  $lnn=1;
					  $dpc='';
					foreach ($mccodes as $record) {
						if ($dpc<>$record->dept_code) { 	
							if ($pg>1) {
		//						$logMsg .= Chr(12)."\n";
								$gt="Dept Total ".$dpc;
								$blnk='';
								$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
								substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
								str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
								str_repeat(' ', 8 - strlen($blnk)).$blnk.
								str_repeat(' ', 10 - strlen($totamt)).$totamt.
								"\n";
			
								$totamt=0;
								$tothrs=0;
								$logMsg .= $line."\n";
								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
//									$logMsg .= Chr(12)."\n";
								$lnn=$lnn+2;
							}	
							$dpc=$record->dept_code;
							$dptcode=$dpc;
							$pg++;
						}		
						if ($lnn>58) {
							$logMsg .= Chr(12)."\n";
							$lnn=1;
						}	

						if  ($lnn==1) {
							$logMsg .=$payschemeName."\n";
							$logMsg .=$ln2.$dptcode."\n";
							$logMsg .=$ln2a."\n";
							$logMsg .= $ln3;
							$logMsg .= $ln4;
							$logMsg .= $ln6;
							$logMsg .= $ln7;
							$logMsg .= $ln5;
							$lnn=8;
						}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
							$whrs = number_format($record->WORKING_HOURS+$record->HOLIDAY_HR+$record->OT_HOURS, 2);
							$arr=number_format($record->ARR_PLUS+$record->ARR_MINUS, 2);
							$adv=number_format($record->ADVANCE, 2);
							$npay=number_format($record->Net_Payble, 0);
							$RATE= $record->RATE_PER_DAY;
							$logMsg .=$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
							substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
							str_repeat(' ', 9 - strlen($whrs)).$whrs.
							str_repeat(' ', 8 - strlen($RATE)).$RATE.
							str_repeat(' ', 10 - strlen($arr)).$arr.
							str_repeat(' ', 10 - strlen($npay)).$npay.
							$$periodtodate.
							"\n";
							$bln='';
							$dbnc='Dept Code '.$dpc;
							$logMsg .=$bln.str_repeat(' ', 10- strlen($bln)).
							substr($dbnc,0,20).str_repeat(' ', 20 - strlen(substr($dbnc,0,20))).
							str_repeat(' ', 9 - strlen($bln)).$bln.
							str_repeat(' ', 8 - strlen($bln)).$bln.
							str_repeat(' ', 10 - strlen($adv)).$adv.
							"\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
							$logMsg .= ' '."\n";
							  $logMsg .= $line."\n";
							$totamt=$totamt+$record->Net_Payble;
							$tothrs=$tothrs+$whrs;
							$lnn=$lnn+6;
							$gtotamt=$gtotamt+$record->Net_Payble;
							$gtothrs=$gtothrs+$whrs;
						}
						$gt="Dept Total ".$dpc;
						$blnk='';
						$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
						substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
						str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
						str_repeat(' ', 8 - strlen($blnk)).$blnk.
						str_repeat(' ', 10 - strlen($totamt)).$totamt.
						"\n";

//							$logMsg .= Chr(12)."\n";
						$logMsg .= $line."\n";
						$logMsg .= $line."\n";
						
						$gt="Grand Total";
						$blnk='';
						$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
						substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
						str_repeat(' ', 9 - strlen($gtothrs)).$gtothrs.
						str_repeat(' ', 8 - strlen($blnk)).$blnk.
						str_repeat(' ', 10 - strlen($gtotamt)).$gtotamt.
						"\n";
	
						$logMsg .= $line."\n";
						$logMsg .= Chr(12)."\n";

						$ln1=$payschemeName."\n";
						$ln2= "Voucher Summary Register for FNE To ".$periodtodate.'           '.'Dept Code ';
						$ln2a= $payschemeName;
						$ln3= $line."\n";
						$ln4= '  DEPT CODE  | TOTAL HOURS            | TOTAL AMOUNT    |  DEPARTMENT.'."\n";
						$ln5.= $line."\n";
						$logMsg .=$payschemeName."\n";
						$logMsg .=$ln2."\n";
						$logMsg .=$ln2a."\n";
						$logMsg .= $ln3;
						$logMsg .= $ln4;
						$logMsg .= $ln3;
						$lnn=5;

						$totamt=0;
						$tothrs=0;
						$gtotamt=0;
						$gtothrs=0;
						$lnn=1;
						$dpc='';
					foreach ($dpsumm as $record) {
						
						$whrs = number_format($record->whrs, 2);
						$npay=number_format($record->Net_Payble, 0);
						$tothrs=$tothrs+$whrs;
						$totamta=$totamta+$record->Net_Payble;
						$logMsg .='    '.$record->dept_code.str_repeat(' ', 15- strlen($record->dept_code)).
						str_repeat(' ', 14 - strlen($whrs)).$whrs.
						str_repeat(' ', 18 - strlen($npay)).$npay.
						'        '.$record->department.
						"\n";
						$logMsg .= $ln3;
					}	
					$gt='Grand Total';
					$logMsg .='    '.$gt.str_repeat(' ', 15- strlen($gt)).
					str_repeat(' ', 14 - strlen($tothrs)).$tothrs.
					str_repeat(' ', 18 - strlen($totamta)).$totamta.
					''.
					"\n";
					$ln3= $line."\n";
					$logMsg .= Chr(12)."\n";
		

					fputs($filePointer,$logMsg);
					fclose($filePointer);
					 $txt1="paydata.txt";
					$txt1=$fileContainer;
					$files = array($txt1);
					$zipname = 'payregister.zip';
			   }


				   if ($holget==6 ) {
					if ($att_payschm==159) {$payschemeName='(1)';}
					if ($att_payschm==160) {$payschemeName='(2)';}
					if ($att_payschm==158) {$payschemeName='(3)';}
					
					$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
					$data = [];
					  $line = str_repeat('-', 136);
				//	  $logMsg=$company_name."\n";

						$pg=1;
					  $logMsg='';
					  $ln1=$payschemeName."\n";
					  $ln2= "Pay Register for the period from  ".$periodfrmdate.'    ' . 'FNE To'.$periodtdate.'           '.'Dept Code ';
					  $ln3= $line."\n";
					  $ln4= 'Dept Code    EB No             W.Hours    BASIC        (12%)    Tiffin    Washing Allow     P.Tax     Total Earn   R/off        (13.00%)'."\n";
					  $ln6= 'Dept Name    Name                Rate     Other        (0.75%)    Conv       Gross2         Advance   Total Ded   Net Pay        (3.25%)'."\n";
					  $ln7= '                                                                         Adjust  Amt                                                  '."\n";
					  $ln5.= $line."\n";

					  $totamt=0;
					  $tothrs=0;
					  $gtotamt=0;
					  $gtothrs=0;

					  $gtotbas=$gothallow=$gepf=$gesi=$gtifallow=$gconvallow=$gwasallow=$ggros2=$gajamt=$gadvamt=$gtotearn=$gtotded=$groff=$gnpay=$gemppf=$gempesi=$gptax=0;
					  
					  $lnn=1;
					  $dpc='';
					foreach ($mccodes as $record) {
						if ($dpc<>$record->dept_code) { 	
							if ($pg>1) {
		//						$logMsg .= Chr(12)."\n";
								/*$gt="Dept Total ".$dpc;
								$blnk='';
								$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
								substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
								str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
								str_repeat(' ', 8 - strlen($blnk)).$blnk.
								str_repeat(' ', 10 - strlen($totamt)).$totamt.*/
								"\n";
			
								$totamt=0;
								$tothrs=0;
								$logMsg .= $line."\n";
								//$logMsg .= Chr(12)."\n";
								$lnn=1;
							}	
							$dpc=$record->dept_code;
							$dptcode=$dpc;
							//$pg++;
						}		
						if ($lnn>58) {
							$logMsg .= Chr(12)."\n";
							$lnn=1;
						}	

						if  ($lnn==1) {
							$logMsg .=$payschemeName."\n";
							$logMsg .=$ln2.$dptcode."\n";
							$logMsg .= $ln3;
							$logMsg .= $ln4;
							$logMsg .= $ln6;
							$logMsg .= $ln7;
							$logMsg .= $ln5;
							$lnn=8;
						}			
						///////////pintu///////////////											//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
							$whrs = number_format($record->WORKING_HOURS+$record->NIGHT_SHIFT_HR+$record->HOLIDAY_HR,2);
							//+$record->HOLIDAY_HR+$record->OT_HOURS, 2);
							//$arr=number_format($record->ARR_PLUS+$record->ARR_MINUS, 2);
							//$adv=number_format($record->ADVANCE, 2);
							//$npay=number_format($record->Net_Payble, 0);
							//$RATE= $record->RATE_PER_DAY;
							$Basic=$record->BASIC;
							$EPF=$record->EPF;
							$ESI=$record->ESI;
							$TIFFIN_AMOUNT=$record->TIFFIN_AMOUNT;
							$WASHING_ALLOWANCE=$record->WASHING_ALLOWANCE;
							$EMPL_EPF=$record->EMPL_EPF;
							$dept_code=$record->dept_code;
							//$ptax=$record->ptax;
							$ptax = number_format($record->ptax,2);
							//$GROSS2=$record->GROSS2;
							$B_F = number_format($record->B_F,2);
							$RATE_PER_DAY = number_format($record->RATE_PER_DAY,2);
							$OTHER_ALLOWANCE=number_format($record->OTHER_ALLOWANCE,2);
							$ESI=number_format($record->ESI,2);
							$CONV_ALLOWANCE=number_format($record->CONV_ALLOWANCE,2);
							$GROSS2=number_format($record->GROSS2,2);
							$ADVANCE=number_format($record->ADVANCE,2);
							$GROSS_DED=number_format($record->GROSS_DED,2);
							$Net_Payble=number_format($record->Net_Payble,2);
							$EMPL_ESI=number_format($record->EMPL_ESI,2);
							$uanno=$record->pf_uan_no;
							$esino=$record->esi_no;
							//$B_F=$record->B_F;

							$logMsg .= '             ' .$record->eb_no.str_repeat(' ', 10- strlen($record->eb_no)).
							//substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
							str_repeat(' ', 16 - strlen($whrs)).$whrs.
							//str_repeat(' ', 8 - strlen($RATE)).$RATE.
							str_repeat(' ', 11 - strlen($Basic)).$Basic.
							str_repeat(' ', 8 - strlen($EPF)).$EPF.
							//str_repeat(' ', 6 - strlen($npay)).$ESI.
							str_repeat(' ', 14 - strlen($TIFFIN_AMOUNT)).$TIFFIN_AMOUNT.
							str_repeat(' ', 15 - strlen($WASHING_ALLOWANCE)).$WASHING_ALLOWANCE.
							str_repeat(' ', 11 - strlen($ptax)).$ptax.
							str_repeat(' ', 14 - strlen($GROSS2)).$GROSS2.
							str_repeat(' ', 11- strlen($B_F)).$B_F.
							str_repeat(' ', 13 - strlen($EMPL_EPF)).$EMPL_EPF.
							 // $lnn=$lnn+4;
							
							$$periodtodate.
							"\n";
							$bln='';
							$dbnc=' '.$dpc;
							$logMsg .= $dbnc.'          '.
							//substr($dbnc,0,20).str_repeat(' ', 20 - strlen(substr($dbnc,0,20))).
							//str_repeat(' ', 0 - strlen($dbnc)).$dbnc.
							substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
							str_repeat(' ', 6 - strlen($RATE_PER_DAY)).$RATE_PER_DAY.
							str_repeat(' ', 11 - strlen($OTHER_ALLOWANCE)).$OTHER_ALLOWANCE.
							str_repeat(' ', 8 - strlen($ESI)).$ESI.
							str_repeat(' ', 14 - strlen($CONV_ALLOWANCE)).$CONV_ALLOWANCE.
							str_repeat(' ', 15 - strlen($GROSS2)).$GROSS2.
							str_repeat(' ', 11 - strlen($ADVANCE)).$ADVANCE.
							str_repeat(' ', 14 - strlen($GROSS_DED)).$GROSS_DED.
							str_repeat(' ', 11 - strlen($Net_Payble)).$Net_Payble.
							str_repeat(' ', 13 - strlen($EMPL_ESI)).$EMPL_ESI.
							str_repeat(' ', 8 - strlen($bln)). 
							"\n";

							$logMsg .= ' '."\n";
			//				$logMsg .= 'ESI NO: '.$esino.'              UAN NO: '.$uanno."\n";

							$logMsg .= ' '."\n";
						//	$logMsg .= ' '."\n";
						//	$logMsg .= ' '."\n";
						    $logMsg .= $line."\n";
							$totamt=$totamt+$record->Net_Payble;
							$tothrs=$tothrs+$whrs;
							$twash=$twash+$record->WASHING_ALLOWANCE;
							$tconv=$tconv+$record->CONV_ALLOWANCE;
							$tiffin=$tiffin+$record->TIFFIN_AMOUNT;
							$tadvance=$tadvance+$record->ADVANCE;
							$tgross=$tgross+$record->TOTAL_EARN;
							$tdeduct=$tdeduct+$record->GROSS_DED;
							$tother=$tother+$record->OTHER_ALLOWANCE;
							$lnn=$lnn+6;
							$gtotamt=$gtotamt+$record->Net_Payble;
							$gtotbas=$gtotbas+$Basic;
							$gepf=$gepf+$EPF;
							$gesi=$gesi+$ESI;
							$ggros2=$ggros2+$record->GROSS2;
							$gadv=$gadv+$record->ADVANCE;
							$groff=$groff+$B_F;
							$gemppf=$gemppf+$EMPL_EPF;
							$gempesi=$gempesi+round($ESI/.75*3.25,0);
														
							$gtothrs=$gtothrs+$whrs;
							$gtiffin=$gtiffin+$record->TIFFIN_AMOUNT;
							$gconv=$gconv+$record->CONV_ALLOWANCE;
							$gtwash=$gtwash+$record->WASHING_ALLOWANCE;
							$gtadvance=$gtadvance+$record->ADVANCE;
							$gtgross=$gtgross+$record->TOTAL_EARN;
							$gtdeduct=$gtdeduct+$record->GROSS_DED;
							$gtother=$gtother+$record->OTHER_ALLOWANCE;


						}
						$logMsg .= Chr(12)."\n";
						$logMsg .= $line."\n";
						
						$gt="Grand Total";
						$blnk='';

						$logMsg .= '        ' .$gt.str_repeat(' ', 15- strlen($gt)).
						//substr($record->wname,0,20).str_repeat(' ', 20 - strlen(substr($record->wname,0,20))).
						str_repeat(' ', 16 - strlen($gtothrs)).$gtothrs.
						//str_repeat(' ', 8 - strlen($RATE)).$RATE.
						str_repeat(' ', 11 - strlen($gtotbas)).$gtotbas.
						str_repeat(' ', 8 - strlen($gepf)).$gepf.
						//str_repeat(' ', 6 - strlen($npay)).$ESI.
						str_repeat(' ', 14 - strlen($gtiffin)).$gtiffin.
						str_repeat(' ', 15 - strlen($gtwash)).$gtwash.
						str_repeat(' ', 11 - strlen($gptax)).$gptax.
						str_repeat(' ', 14 - strlen($gtgross)).$gtgross.
						str_repeat(' ', 11- strlen($groff)).$groff.
						str_repeat(' ', 13 - strlen($gemppf)).$gemppf.
						"\n";
	
						$logMsg .= $dbnc.'          '.
							//substr($dbnc,0,20).str_repeat(' ', 20 - strlen(substr($dbnc,0,20))).
							//str_repeat(' ', 0 - strlen($dbnc)).$dbnc.
							substr($blnk,0,20).str_repeat(' ', 20 - strlen(substr($blnk,0,20))).
							str_repeat(' ', 6 - strlen($blnk)).$blnk.
							str_repeat(' ', 11 - strlen($gtother)).$gtother.
							str_repeat(' ', 8 - strlen($gesi)).$gesi.
							str_repeat(' ', 14 - strlen($gconv)).$gconv.
							str_repeat(' ', 15 - strlen($ggros2)).$ggros2.
							str_repeat(' ', 11 - strlen($gadv)).$gadv.
							str_repeat(' ', 14 - strlen($gtdeduct)).$gtdeduct.
							str_repeat(' ', 11 - strlen($gtotamt)).$gtotamt.
							str_repeat(' ', 13 - strlen($gesi)).$gesi.
							"\n";
/*

						$logMsg .=$Blnk.str_repeat(' ', 10- strlen($blnk)).
						substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
						str_repeat(' ', 9 - strlen($gtothrs)).$gtothrs.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtother)).$gtother.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtiffin)).$gtiffin.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gconv)).$gconv.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtwash)).$gtwash.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 6 - strlen($gtadvance)).$gtadvance.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtgross)).$gtgross.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtdeduct)).$gtdeduct.
						str_repeat(' ', 3 - strlen($blnk)).$blnk.
						str_repeat(' ', 9 - strlen($gtotamt)).$gtotamt.
						"\n";
*/	
						$logMsg .= $line."\n";
						$logMsg .= Chr(12)."\n";
					fputs($filePointer,$logMsg);
					fclose($filePointer);
					 $txt1="paydata.txt";
					$txt1=$fileContainer;
					$files = array($txt1);
					$zipname = 'payregister.zip';
			   }
	  
			   if ($holget==2 || $holget==7 || $holget==1) {
				if ($att_payschm==159) {$payschemeName='(1)';}
				if ($att_payschm==160) {$payschemeName='(2)';}
				if ($att_payschm==158) {$payschemeName='(3)';}
				if ($att_payschm==125 & $holget==2) {$payschemeName=' FORTNIGHTLY WORKERS PAY REGISER FOR  18-PF  ';}
				if ($att_payschm==151) {$payschemeName=' FORTNIGHTLY MAIN PAYROLL PAY REGISER   ';}
				if ($att_payschm==125 & $holget==7) {$payschemeName=' FORTNIGHTLY WORKERS PAY REGISER FOR  18-ESI ';}
			
				$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
				$data = [];
				  $line = str_repeat('=',133);
			//	  $logMsg=$company_name."\n";
				 $fnedate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);
			  $pg=1;
			  //$pg=$pg++;

			  $pschm= $this->data['att_payschm'];
			  $logMsg='';
			  $ln1=$company_name.'   '.$payschemeName.'                F/E:'.$periodtodate.'                  PAGE  ';
			  //$ln2= 'Pay Ragister for FNE '.$fnedate."\n";
			  $ln3= $line."\n";
			  $ln4= 'Dpt S SRLNO EB-NO T/P <-----NAME-----> OCC BAS-RATE  WRKHRS  NSHRS  HLHRS  LOFHR STLD WRKDAY C-WRKDAY C-BON-ERN  PF-GROSS  CO-LNBAL  '."\n";
			  $ln6= '..BASIC..FIX-BASIC ...DA... ..NS-AMT  .HOL-AMT INCREMENT ADC-INC LOFF-WG INCENTIVE MISC-ERN .STL-WG. GROSS-PAY ..HRA...B/F. TO-EARN '."\n";
			  $ln7= '..ESI-NO..PF-NO C-PF-CONT C-FPF-CONT ESIGROSS PF-CONT FPF-CONT CO-CONT .ESI. PTAX LWF CO-LN  PUJA-ADV  STLADV T-DEDN .C/F.  NET-PAY'."\n";
			  $ln5.= $line."\n";
			$pg=1;	
			  $totamt=0;
			  $tothrs=0;
			  $gtotamt=0;
			  $gtothrs=0;
			  $lnn=1;
			  $dpc='';
			//var_dump($mccodes);
			  foreach ($mccodes as $record) {
				
				 
				if ($lnn>58) {
					//$logMsg .= Chr(12).chr(12)."\n";
					$logMsg .= chr(12)."\n";
					$lnn=1;
					$pg++;
				//	$ln1='THE EMPIRE JUTE CO.LD'.$payschemeName.''.'F/E:'.$periodtodate.'    PAGE:'.$pg."\n";
				
					}	
					if ($dpc<>$record->dept_code) {
						$logMsg .= chr(12)."\n";
						$lnn=1;
						$pg=1;
						$dpc=$record->dept_code;	

					}


					if  ($lnn==1) {
						$logMsg .=$ln1.$pg."\n";
						$lnn=$lnn+1;
						//$logMsg .= $ln5;
						//$logMsg .=$ln2;
						//$logMsg .= $ln3;
						$lnn=$lnn+1;
						$logMsg .= $ln4;
						$lnn=$lnn+1;
						$logMsg .= $ln6;
						$lnn=$lnn+1;
						$logMsg .= $ln7;
						$lnn=$lnn+1;
						$logMsg .= $ln5;
						$lnn=$lnn+1;
					}			

					$whrs = $record->WORKING_HOURS;
					$dept_code=$record->dept_code;
					$eb_no=$record->eb_no;
					$time_piece=$record->time_piece;
					$wname=$record->wname;
					$desig=$record->desig;
//					$BASIC_RATE=$record->BASIC_RATE;
					$WORKING_HOURS=$record->WORKING_HOURS;
					$NS_HRS=$record->NS_HRS;
					$HL_HRS=$record->HL_HRS;
					$LS_HRS=$record->LS_HRS;
					$STL_D=$record->STL_D;
					$WRK_DAYS=$record->WRK_DAYS;
					$C_WORK_DAY=$record->C_WORK_DAY;
					$C_BON_ERN=$record->C_BON_ERN;
					$LNBL=$record->LNBL;
					$PF_GROSS=$record->PF_GROSS;
					$BASIC_RATE=$record->BASIC_RATE;
					$DA=$record->DA;
					$NS_AMOUNT=$record->NS_AMOUNT;
					$HOL_AMT=$record->HOL_AMT;
					$INCREMENTA=$record->INCREMENTA; 
					$LAYOFF_WGS=$record->LAYOFF_WGS;
					$INCENTIVE_AMOUNT=$record->INCENTIVE_AMOUNT;
					$MISS_EARN=$record->MISS_EARN;
					$STL_WGS=$record->STL_WGS;
					$GROSS_PAY=$record->GROSS_PAY;
					$HRA=$record->HRA;
					$B_F=$record->B_F;
					$TOTAL_EARN=$record->TOTAL_EARN;
					$esi_no=$record->esi_no;
					$pf_no=$record->pf_no;
					$ESI_GROSS=$record->ESI_GROSS;
					$EPF=$record->EPF;
					$epf_833=$record->epf_833;
					$epf_167=$record->epf_167;
					$ESIC=round($record->ESIC,0);
					$P_TAX=round($record->P_TAX,0);
					$LWF=round($record->LWF,0);
					$PUJA_ADVANCE=$record->PUJA_ADVANCE;
					$STL_ADVANCE=$record->STL_ADVANCE;
					$TOTAL_DEDUCTION=$record->TOTAL_DEDUCTION;
					$C_F=$record->C_F;
					$NET_PAY=$record->NET_PAY;
					$FIX_BASIC=$record->FIX_BASIC+$record->TIME_RATED_BASIC;
					$PROD_BASIC=$record->PROD_BASIC;					///////////sabir////////////20.2.24/////////
					$C_PF_CONT=$record->C_PF_CONT;
					$C_EPF_CONT=$record->C_EPF_CONT;
					$coloan=round($record->CO_LOAN,0);

					///////////sabir////////////20.2.24/////////
					
			
					$logMsg .= '' .$record->dept_code.str_repeat(' ', 10- strlen($record->dept_code)).
					str_repeat(' ', 6 - strlen($eb_no)).$eb_no.
					str_repeat(' ', 3 - strlen($time_piece)).$time_piece.'  '.
					substr($record->wname,0,15).str_repeat(' ', 20- strlen(substr($record->wname,0,15))).
					substr($record->desig,0,4).str_repeat('',4- strlen(substr($record->desig,0,4))).
					str_repeat(' ', 6 - strlen($BASIC_RATE)).$BASIC_RATE.
					str_repeat(' ', 8 - strlen($WORKING_HOURS)).$WORKING_HOURS.
					str_repeat(' ', 7 - strlen($NS_HRS)).$NS_HRS.
					str_repeat(' ', 7 - strlen($HL_HRS)).$HL_HRS.
					str_repeat(' ', 7 - strlen($LS_HRS)).$LS_HRS.
					str_repeat(' ', 6 - strlen($STL_D)).$STL_D.
					str_repeat(' ', 7 - strlen($WRK_DAYS)).$WRK_DAYS.
					str_repeat(' ', 7 - strlen($C_WORK_DAY)).$C_WORK_DAY.
					str_repeat(' ', 11- strlen($C_BON_ERN)).$C_BON_ERN.
					str_repeat(' ', 12- strlen($PF_GROSS)).$PF_GROSS.  
					"\n";
					$lnn=$lnn+1;		
					$logMsg.=str_repeat(' ', 9- strlen($PROD_BASIC)).$PROD_BASIC.
					str_repeat(' ', 9- strlen($FIX_BASIC)).$FIX_BASIC.
					str_repeat(' ', 9- strlen($DA)).$DA.
					str_repeat(' ', 7- strlen($NS_AMOUNT)).$NS_AMOUNT.
					str_repeat(' ', 9- strlen($HOL_AMT)).$HOL_AMT.
					str_repeat(' ', 9- strlen($INCREMENTA)).$INCREMENTA.'         '.
					str_repeat(' ', 9- strlen($LAYOFF_WGS)).$LAYOFF_WGS.
					str_repeat(' ', 9- strlen($INCENTIVE_AMOUNT)).$INCENTIVE_AMOUNT.
					str_repeat(' ', 9- strlen($MISS_EARN)).$MISS_EARN.
					str_repeat(' ', 9- strlen($STL_WGS)).$STL_WGS.
					str_repeat(' ', 10- strlen($GROSS_PAY)).$GROSS_PAY.
					str_repeat(' ', 8- strlen($HRA)).$HRA.
					str_repeat(' ', 6- strlen($B_F)).$B_F.
					str_repeat(' ', 10- strlen($TOTAL_EARN)).$TOTAL_EARN.  
					"\n";
					$lnn=$lnn+1;		
					$logMsg.=''.substr($record->esi_no,0,10).str_repeat('', strlen(substr($record->esi_no,0,10))).
					//substr($record->pf_no,0,12).str_repeat(' ', strlen(substr($record->pf_no,0,12))).
					str_repeat(' ', 1).$pf_no.
					///////////sabir////////////20.2.24/////////
					str_repeat(' ', 2).$C_PF_CONT.
					str_repeat(' ', 5).$C_EPF_CONT.
					///////////sabir////////////20.2.24/////////
					str_repeat(' ', 10- strlen($TOTAL_EARN)).$TOTAL_EARN.
					str_repeat(' ', 8- strlen($EPF)).$EPF.
					str_repeat(' ', 7- strlen($epf_833)).$epf_833.
					str_repeat(' ', 7- strlen($epf_167)).$epf_167.
					str_repeat(' ', 6- strlen($ESIC)).$ESIC.
					str_repeat(' ', 6- strlen($P_TAX)).$P_TAX.
					str_repeat(' ', 6- strlen($LWF)).$LWF.
					str_repeat(' ', 6- strlen($coloan)).$coloan.
					str_repeat(' ', 6- strlen($PUJA_ADVANCE)).$PUJA_ADVANCE.
					str_repeat(' ', 9- strlen($STL_ADVANCE)).$STL_ADVANCE.
					str_repeat(' ', 9- strlen($TOTAL_DEDUCTION)).$TOTAL_DEDUCTION.
					str_repeat(' ', 6- strlen($C_F)).$C_F.
					str_repeat(' ', 7- strlen($NET_PAY)).$NET_PAY.  
					
					"\n";
					$lnn=$lnn+1;	
			
				
					$logMsg .= $line.
					"\n";
					$lnn=$lnn+1;	
				/*	$totamt=$totamt+$record->Net_Payble;
					$tothrs=$tothrs+$whrs;
					$twash=$twash+$record->WASHING_ALLOWANCE;
					$tconv=$tconv+$record->CONV_ALLOWANCE;
					$tiffin=$tiffin+$record->TIFFIN_AMOUNT;
					$tadvance=$tadvance+$record->vADVANCE;
					$tgross=$tgross+$record->TOTAL_EARN;
					$tdeduct=$tdeduct+$record->GROSS_DED;
					$tother=$tother+$record->OTHER_ALLOWANCE;
					$lnn=$lnn+6;
					$gtotamt=$gtotamt+$record->Net_Payble;
					$gtothrs=$gtothrs+$whrs;
					$gtiffin=$gtiffin+$record->TIFFIN_AMOUNT;
					$gconv=$gconv+$record->CONV_ALLOWANCE;
					$gtwash=$gtwash+$record->WASHING_ALLOWANCE;
					$gtadvance=$gtadvance+$record->vADVANCE;
					$gtgross=$gtgross+$record->TOTAL_EARN;
					$gtdeduct=$gtdeduct+$record->GROSS_DED;
					$gtother=$gtother+$record->OTHER_ALLOWANCE;*/
					//$logMsg .= $line."\n";
					//$pg++;
				}
				
				//$logMsg .= $line."\n";
				//$logMsg .= Chr(12)."\n";
			fputs($filePointer,$logMsg);
			fclose($filePointer);
			$txt1="Payregdata.txt";
			$txt1=$fileContainer;
			$files = array($txt1);
			$zipname = 'VouPayreg.zip';
			}
					
			$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
			foreach ($files as $file) {
			  $zip->addFile($file);
			}


			$zip->close();
			
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$zipname);
			ob_clean();
			header('Content-Length: ' . filesize($zipname));
			readfile($zipname);
			
					unlink($fileContainer);
					 unlink($zipname);
			
			
			
				}

				
			public function hlincprint() {
				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
					 $periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
					 $periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 
					 $fileContainer = "incdata.txt";
					 $filePointer = fopen($fileContainer,"w+");
//					 echo $holget;			
					$zt=1;
					 
					 if ($holget==2) {
						$zt=1;
						$fileContainer = "incdata.txt";
						
						$filePointer = fopen($fileContainer,"w+");			
							$mccodes = $this->Loan_adv_model2->getmainFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
							
							if ($holget==2) {
								//	$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
								$data = [];
								  $line = str_repeat('-', 72);
								  $hd1="DP S SRL T/No. Name ATTN INCN) F/E Date | DP S SRL T/No. Name ATTN INCN) F/E Date |";
								  $hd2="           TOT-DAY MISC-EARN   TOT-EARN |            TOT-DAY MISC-EARN   TOT-EARN |";
								  $hd3="     ADVANCE                        NET |      ADVANCE                        NET |";	
								  $shd1="Attendance Incentive Register for the period From ".$periodfrmdate." To ".$periodtdate; 

								//  $shd2="DP S SRL T/No. Name ATTN INCN)     TOT-DAY MISC-EARN   TOT-EARN  ADVANCE       NT";	
						
								  $bkline="                                        |                                         |";	
								$bklin="----------------------------------------|-----------------------------------------|";	
								  $pg=1;
								  $rowIndex = 4;
								  $totamt=0;
								  $tothrs=0;
						 		  $lnn=8;
								  $sl=1;
								  $rnop=0;	
								  $lndet1='';
								  $lndet2='';
								  $lndet3='';
								  $lndet4='';
								  $lndet5='';
								  $lndet6='';
								  $dpc='';
								  $gtotamt=0;
								  $enddate=	substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
								  foreach ($mccodes as $record) {
									if ($dpc<>$record->dept_code) {
										if (strlen($dpc)>0) {
											if (fmod($rnop, 2)==1)	{
											
																$logMsg .=$bkline."\n";
											//					$logMsg .= ' '."\n";
											//					$logMsg .= ' '."\n";
																$logMsg .=$hd1."\n";
																$logMsg .=$lndet1."\n";
																$logMsg .=$bkline."\n";
																$logMsg .=$hd2."\n";
																$logMsg .=$lndet2."\n";
																$logMsg .=$bkline."\n";
																$logMsg .=$hd3."\n";
																$logMsg .=$lndet3."\n";
																$logMsg .=$bklin."\n";
																	
											}
											$logMsg .= Chr(12)."\n";
											$pg=1;
											$rnop=0;
										}								
										$dpc=$record->dept_code;
						
									}
									if ($pg>5) {
										$logMsg .= Chr(12)."\n";
										$pg=1;
									}														
								
									$OT_HOURS = number_format($record->incdays, 2);
									$OVERTIME_PAY= $record->incamt;
									$mearn=0;
									$adv=0;
									$rnop=$rnop+1;
									$rn=fmod($rnop, 2);
									$sln=str_repeat(' ', 3 - strlen($rnop)).$rnop;

									$gtotamt=$gtotamt+$record->incamt;
				//					$logMsg .='rec no='.$rnop.'=='.$rn.'--eb=-'.$record->eb_no."\n";
			
									if (fmod($rnop, 2)==1)	{
							//		if ($rno==1) {
										$lndet1='';
										$lndet2='';
										$lndet3='';
										$adv=0;
										$miscern=0;
										$otnet=number_format($record->incamt, 0);
										$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
										substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
										.$enddate.' | ';
										$lndet2.=str_repeat(' ', 18 - strlen($OT_HOURS)).$OT_HOURS.
										str_repeat(' ', 10 - strlen($mearn)).$mearn.
										str_repeat(' ', 11 - strlen($record->incamt)).$record->incamt
										.' | ';
										$np = number_format($record->incamt, 0);
										$netp=str_repeat('*', 6 - strlen($np)).$np;
										$otnetp=str_repeat('*', 6 - strlen($otnet)).$otnet;
										$lndet3.=str_repeat(' ', 11 - strlen($adv)).$adv.
										str_repeat(' ', 28 - strlen($otnetp)).$otnetp.' | ';
			//							$rno++;
				//						$logMsg .='recno-'.$rnop.'=if 1st='.$lndet1."\n";

				
									} else { 
										$adv=0;
										$miscern=0;
										$otnet=number_format($record->incamt, 0);
										
										$lndet1.=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
										substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13)))
										.$enddate.' | '."\n";
										$lndet2 .=str_repeat(' ', 18 - strlen($OT_HOURS)).$OT_HOURS.
										str_repeat(' ', 10 - strlen($mearn)).$mearn.
										str_repeat(' ', 11 - strlen($record->incamt)).$record->incamt.' | '
										."\n";
										$np = number_format($record->incamt, 0);
										$netp=str_repeat('*', 6 - strlen($np)).$np;
										$otnetp=str_repeat('*', 6 - strlen($otnet)).$otnet;
										$lndet3.=str_repeat(' ', 11 - strlen($adv)).$adv.
										str_repeat(' ', 28 - strlen($otnetp)).$otnetp.' | '.
										"\n";
				//						$logMsg .='recno-'.$rnop.'=if 2nd='.$lndet1."\n";
			
										$logMsg .=$bkline."\n";
					//					$logMsg .= ' '."\n";
					//					$logMsg .= ' '."\n";
										$logMsg .=$hd1."\n";
										$logMsg .=$lndet1;
										$logMsg .=$bkline."\n";
										$logMsg .=$hd2."\n";
										$logMsg .=$lndet2;
										$logMsg .=$bkline."\n";
										$logMsg .=$hd3."\n";
										$logMsg .=$lndet3;
										$logMsg .=$bklin."\n";
									
									 $pg++;	
			//						$rno=1;				
								
									}}
									if (fmod($rnop, 2)==1)	{
										$logMsg .=$bkline."\n";
									//					$logMsg .= ' '."\n";
									//					$logMsg .= ' '."\n";
														$logMsg .=$hd1."\n";
														$logMsg .=$lndet1."\n";
														$logMsg .=$bkline."\n";
														$logMsg .=$hd2."\n";
														$logMsg .=$lndet2."\n";
														$logMsg .=$bkline."\n";
														$logMsg .=$hd3."\n";
														$logMsg .=$lndet3."\n";
														$logMsg .=$bklin."\n";
									}				
													}				
													
													
													fputs($filePointer,$logMsg);
													fclose($filePointer);
											        $txt1="incdata.txt";
													$txt1=$fileContainer;
													


													$fileContainer1 = "increg.txt";
													$line = str_repeat('-', 81);
													$filePointer1 = fopen($fileContainer1,"w+");	
													$logMsg1='';	
													$dpc='';
													$gtotamt=0;
													$indays=0;
													$incamt=0;
													$dincamt=0;
													
													$enddate=	substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
													$mccodes = $this->Loan_adv_model2->getmainFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);	
													$shd2="DP S SRL T/No. Name                TOT-DAY MISC-EARN   TOT-EARN  ADVANCE      NET";	
											//	var_dump($mccodes);
													foreach ($mccodes as $record) {
													  if ($dpc<>$record->dept_code) {
														 if (strlen($dpc)>0) {
															$logMsg1 .= $line."\n";
															$logMsg1 .= str_repeat(' ', 80 - strlen($dincamt)).$dincamt."\n";
															$logMsg1 .= $line."\n";
														}			  

															$logMsg1 .=$shd1."\n";
															$logMsg1 .=$line."\n";
															$logMsg1 .=$shd2."\n";
															$logMsg1 .=$line."\n";
															$dpc=$record->dept_code;
															$dincamt=0;
														 
														}
														$OT_HOURS = number_format($record->incdays, 2);
														$OVERTIME_PAY= $record->incamt;
														
														  $np = number_format($record->incamt, 0);
														  $netp=str_repeat('*', 6 - strlen($np)).$np;
														
														  $otnet=number_format($record->incamt, 0);
														  $otnetp=str_repeat('', 6 - strlen($otnet)).$otnet;
														  $lndet1='';
														  $lndet1=$record->dept_code.' A '.$sln.' '. $record->eb_no.str_repeat(' ', 5- strlen($record->eb_no)).' '.
														  substr($record->wname,0,13).str_repeat(' ', 14 - strlen(substr($record->wname,0,13))).
														  str_repeat(' ', 13 - strlen($OT_HOURS)).$OT_HOURS.
														  str_repeat(' ', 10 - strlen($mearn)).$mearn.
														  str_repeat(' ', 11 - strlen($record->incamt)).$record->incamt
														  .str_repeat(' ', 9 - strlen($adv)).$adv.str_repeat(' ', 8 - strlen($otnetp)).$otnetp
														  ."\n";
														 
														  $logMsg1 .=$lndet1;
														  $gtotamt=$gtotamt+$record->incamt;
														  $dincamt=$dincamt+$record->incamt;
														//  echo $lndet1."<br>";
														 // echo $logMsg1;

													}	
													$logMsg1 .= $line."\n";	
													$logMsg1 .= 'Grand Total'.str_repeat(' ', 69 - strlen($gtotamt)).$gtotamt."\n";
													$logMsg1 .= $line."\n";
													fputs($filePointer1,$logMsg1);
													fclose($filePointer1);
											       
													$txt2=$fileContainer1;
													
													
													
													$files = array($txt1,$txt2);
													$zipname = 'incprint.zip';
																			



						} else { 
					  if ($holget==3 || $holget==7) {
					 	$mccodes = $this->Loan_adv_model->getFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
					 }
					 if ($holget==4 || $holget==8 ) {
						$mccodes = $this->Loan_adv_model->getMNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
					}
						
					 $fileContainer = "incdata.txt";
					 $filePointer = fopen($fileContainer,"w+");
					$totamt=0;
					$othrs=0;
					$line = str_repeat('-', 81);
					$logMsg=$payschemeName;
					$hdm1="Attendance Incentive Register for the period From ".$periodfrmdate." To ".$periodtdate;
					$hdm2='EB No    Name                 Dept Name        W.Days   Inc Amt   Signature ';
					
						  $pg=1;
						  $logMsg=$payschemeName."\n";

						  $logMsg .= $hdm1."\n";
						  $logMsg .= $line."\n";
						  $logMsg .= $hdm2."\n";
						  $logMsg .= $line."\n";
 						  $totamt=0;
						  $tothrs=0;
						  $lnn=5;
						foreach ($mccodes as $record) {
							if ($lnn>58) {
								$logMsg .= Chr(12)."\n";
								$pg++;
								$logMsg .=$payschemeName."\n";
								$logMsg .= $hdm1."\n";
								$logMsg .= $line."\n";
								$logMsg .= $hdm2."\n";
								$logMsg .= $line."\n";
								$lnn=5;
							}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
							$logMsg .=$record->emp_code.str_repeat(' ', 8- strlen($record->emp_code)).
							substr($record->empname,0,20).str_repeat(' ', 21 - strlen(substr($record->empname,0,20))).
							substr($record->dept_desc,0,15).str_repeat(' ', 15 - strlen(substr($record->dept_desc,0,15))).
							str_repeat(' ', 9 - strlen($record->incdays)).$record->incdays.
							str_repeat(' ', 10 - strlen($record->incamt)).$record->incamt.
							"\n";
 								$logMsg .= ' '."\n";
								$logMsg .= ' '."\n";
			 	 			   $logMsg .= $line."\n";
								$totamt=$totamt+$record->incamt;
								$tothrs=$tothrs+$record->incdays;
								
								$lnn=$lnn+4;
							}

							$gt="Grand Total";
							$empc=" ";
							$logMsg .=$empc.str_repeat(' ', 8- strlen($empc)).
							substr($gt,0,20).str_repeat(' ', 21 - strlen(substr($gt,0,20))).
							substr($empc,0,15).str_repeat(' ', 15 - strlen(substr($empc,0,15))).
							str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
							str_repeat(' ', 10 - strlen($totamt)).$totamt.
							"\n";
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";
				
							$logMsg .= $line."\n";
							$logMsg.=$payschemeName."\n";
							$logMsg .= $hdm1."\n";
							$logMsg .= $line."\n";
							$logMsg .= $hdm2."\n";
							$logMsg .= $line."\n";
							  
							$gt="Summary";
							$logMsg .=$empc.str_repeat(' ', 8- strlen($empc)).
							substr($gt,0,20).str_repeat(' ', 21 - strlen(substr($gt,0,20))).
							substr($empc,0,15).str_repeat(' ', 15 - strlen(substr($empc,0,15))).
							str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
							str_repeat(' ', 10 - strlen($totamt)).$totamt.
							"\n";
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";
					 	
							fputs($filePointer,$logMsg);
						fclose($filePointer);
						 $txt1="incdata.txt";
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'incprint.zip';
						}
		  
 			   
	 
		
			$zip = new ZipArchive;
			$zip->open($zipname, ZipArchive::CREATE);
			foreach ($files as $file) {
			  $zip->addFile($file);
			}
		
			if ($holget==21 ) {
				$fileContainer = "incdata.txt";
				$filePointer = fopen($fileContainer,"w+");
		   		$mccodes = $this->Loan_adv_model->getattincData($periodfromdate,$periodtodate,$att_payschm,$holget);
			//echo 'amamam';
			//	var_dump($mccodes);
				  $line = str_repeat('-', 79);
					$payschemeName = 'Over All';
			//		echo '$ln2';
					$pg=1;
				  $logMsg='';
				  $ln1=$line."\n";
				  $ln2= 'THE EMPIRE JUTE CO.LTD.' ."\n";
				  $ln3= 'Departmnt Wise Attendance Incentive Summary for ' ."\n";
				  $ln4= $payschemeName.'  '.' for period from'.'  ' .$periodfrmdate.' ' . 'To '.$periodtdate."\n";
				  $ln5= 'Dept Code   Department     Incentive-Amount      Net-Amount'   ."\n";
				  $ln6.= $line."\n";

				  $totamt=0;
				  $gtothrs=0;
				  $tothrs=0;
				  $gtotamt=0;
				  $gotpay=0;
				  $gtotpay=0;
				  $gnpay=0;
				  $lnn=1;
				  $dpc='';
	
				  $logMsg .=$ln2 ;
				  $logMsg .= $ln3;
				  $logMsg .= $ln4;
				  $logMsg .= $ln5;
				  $logMsg .= $ln6;
		  foreach ($mccodes as $record) {
	
						$dpc=$record->DEPT_CODE;
						$DEPARTMENT=$record->DEPARTMENT;
						$attincn=number_format($record->attincn,0);
						$OVERTIME_PAY=number_format($record->OVERTIME_PAY,0);		
						$OT_ADVANCE=number_format($record->OT_ADVANCE,0);
						$NET_PAY=number_format($record->NET_PAY,0);
	
						$logMsg .='    '.$dpc.'        ' .$record->DEPARTMENT.str_repeat(' ', 15- strlen($record->DEPARTMENT)).
						str_repeat(' ', 8 - strlen($attincn)).$attincn.
						str_repeat(' ', 20 - strlen($attincn)).$attincn.
						"\n";
						$logMsg .= $line."\n";
						$gtothrs=$gtothrs+$record->attincn;
						$gtotpay=$gtotpay+$record->OVERTIME_PAY;
						$gtotamt=$gtotamt+$record->OT_ADVANCE;
						$gnpay=$gnpay+$record->NET_PAY;
						$lnn=$lnn+2;
					}
			
					$logMsg .= $line."\n";
					
					$gt="Grand Total";
					$blnk='';
					$logMsg .=$blnk.str_repeat(' ', 5- strlen($blnk)).
					substr($gt,0,20).str_repeat(' ', 20 - strlen(substr($gt,0,20))).
					str_repeat(' ', 8 - strlen($gtothrs)).$gtothrs.
					str_repeat(' ', 22 - strlen($gtothrs)).$gtothrs.
					"\n";

			
					
					$logMsg .= $line."\n";
					$logMsg .= Chr(12)."\n";

				fputs($filePointer,$logMsg);
				fclose($filePointer);
				 $txt1="paydata.txt";
				$txt1=$fileContainer;
				$files = array($txt1);
				$zipname = 'summreg.zip';

				$zip = new ZipArchive;
				$zip->open($zipname, ZipArchive::CREATE);
				foreach ($files as $file) {
				  $zip->addFile($file);
				}
		 
			}		




		
			$zip->close();
			if ( $zt==1)  {	
				/* 
					if ($zip->open($zipname, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
						$zip->addFile($fileContainer, basename($fileContainer));
						$zip->close();
						echo 'ZIP archive created successfully.'.'--'.$zt;
					} else {
				//		echo 'Failed to create ZIP archive.';
					}
				*/
					ob_clean();
					header('Content-Type: application/zip');
					header('Content-Disposition: attachment; filename="' . $zipname . '"');
					header('Content-Length: ' . filesize($zipname));
					header('Pragma: no-cache');
					readfile($zipname);
				
				} else {
//					echo 'generate txt created successfully.'.'--'.$zt;
					
					header('Content-Type: application/text');
					header('Content-disposition: attachment; filename='.$txt1);
					header('Content-Length: ' . filesize($txt1));
					readfile($txt1);
				 }	
							
//			header('Content-Type: application/zip');
//			header('Content-disposition: attachment; filename='.$zipname);
//			header('Content-Length: ' . filesize($zipname));
//			readfile($zipname);
/*	
	header('Content-Type: application/octet-stream');
header('Content-disposition: attachment; filename=' . basename($fileContainer));
header('Content-Length: ' . filesize($fileContainer));

// Read the file and output it to the browser
readfile($fileContainer);
*/		
					unlink($fileContainer);
					 unlink($zipname);
			
			
			
				}
				
				public function mlincprint() {
					$periodfromdate= $this->input->get('periodfromdate');
					$periodtodate= $this->input->get('periodtodate');
					$att_payschm =  $this->input->get('att_payschm');
					$holget =  $this->input->get('holget');
					$payschemeName =  $this->input->get('payschemeName');
						 $company_name = $this->session->userdata('companyname');
						 $comp = $this->session->userdata('companyId');
						 $periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
						 $periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 
						 $mccodes = $this->Loan_adv_model->getMNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget);
						 $fileContainer = "incdata.txt";
						 $filePointer = fopen($fileContainer,"w+");
						$totamt=0;
						$othrs=0;
						$line = str_repeat('-', 81);
						$logMsg=$payschemeName;
						$hdm1="Attendance Incentivem Register for the period From ".$periodfrmdate." To ".$periodtdate;
						$hdm2='EB No    Name                 Dept Name        W.Days   Inc Amt   Signature ';
						
							  $pg=1;
							  $logMsg=$payschemeName."\n";
	
							  $logMsg .= $hdm1."\n";
							  $logMsg .= $line."\n";
							  $logMsg .= $hdm2."\n";
							  $logMsg .= $line."\n";
							   $totamt=0;
							  $tothrs=0;
							  $lnn=5;
							foreach ($mccodes as $record) {
								if ($lnn>58) {
									$logMsg .= Chr(12)."\n";
									$pg++;
									$logMsg .=$payschemeName."\n";
									$logMsg .= $hdm1."\n";
									$logMsg .= $line."\n";
									$logMsg .= $hdm2."\n";
									$logMsg .= $line."\n";
									$lnn=5;
								}														//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
								$logMsg .=$record->emp_code.str_repeat(' ', 8- strlen($record->emp_code)).
								substr($record->empname,0,20).str_repeat(' ', 21 - strlen(substr($record->empname,0,20))).
								substr($record->dept_desc,0,15).str_repeat(' ', 15 - strlen(substr($record->dept_desc,0,15))).
								str_repeat(' ', 9 - strlen($record->incdays)).$record->incdays.
								str_repeat(' ', 10 - strlen($record->incamt)).$record->incamt.
								"\n";
									 $logMsg .= ' '."\n";
									$logMsg .= ' '."\n";
									 $logMsg .= $line."\n";
									$totamt=$totamt+$record->incamt;
									$tothrs=$tothrs+$record->incdays;
									
									$lnn=$lnn+4;
								}
	
								$gt="Grand Total";
								$empc=" ";
								$logMsg .=$empc.str_repeat(' ', 8- strlen($empc)).
								substr($gt,0,20).str_repeat(' ', 21 - strlen(substr($gt,0,20))).
								substr($empc,0,15).str_repeat(' ', 15 - strlen(substr($empc,0,15))).
								str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
								str_repeat(' ', 10 - strlen($totamt)).$totamt.
								"\n";
								$logMsg .= $line."\n";
								$logMsg .= Chr(12)."\n";
					
								$logMsg .= $line."\n";
								$logMsg.=$payschemeName."\n";
								$logMsg .= $hdm1."\n";
								$logMsg .= $line."\n";
								$logMsg .= $hdm2."\n";
								$logMsg .= $line."\n";
								  
								$gt="Summary";
								$logMsg .=$empc.str_repeat(' ', 8- strlen($empc)).
								substr($gt,0,20).str_repeat(' ', 21 - strlen(substr($gt,0,20))).
								substr($empc,0,15).str_repeat(' ', 15 - strlen(substr($empc,0,15))).
								str_repeat(' ', 9 - strlen($tothrs)).$tothrs.
								str_repeat(' ', 10 - strlen($totamt)).$totamt.
								"\n";
								$logMsg .= $line."\n";
								$logMsg .= Chr(12)."\n";
	
								fputs($filePointer,$logMsg);
							fclose($filePointer);
							 $txt1="incdata.txt";
							$txt1=$fileContainer;
							$files = array($txt1);
							$zipname = 'incprint.zip';
					   
			  
					
		 
			
				$zip = new ZipArchive;
				$zip->open($zipname, ZipArchive::CREATE);
				foreach ($files as $file) {
				  $zip->addFile($file);
				}
				$zip->close();
				
				header('Content-Type: application/zip');
				header('Content-disposition: attachment; filename='.$zipname);
				header('Content-Length: ' . filesize($zipname));
				readfile($zipname);
	/*	
		header('Content-Type: application/octet-stream');
	header('Content-disposition: attachment; filename=' . basename($fileContainer));
	header('Content-Length: ' . filesize($fileContainer));
	
	// Read the file and output it to the browser
	readfile($fileContainer);
	*/		
						unlink($fileContainer);
						 unlink($zipname);
				
				
				
					}
		

			public function getotregisterdatap() {
				$periodfromdate= $this->input->post('periodfromdate');
				$periodtodate= $this->input->post('periodtodate');
				$att_payschm =  $this->input->post('att_payschm');
				$holget =  $this->input->post('holget');
			//	$payschemeName =  $this->input->post('payschemeName');
					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
					 $mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
					 $data = [];
					 foreach ($mccodes as $record) {
						 $data[] = [
							 $deptcode=$record->dept_code,
							 $deptname=$record->department,
							 $empcode=$record->eb_no,
							 $empname=$record->wname,
							 $othours=$record->OT_HOURS,
							 $otrate=$record->RATE,
							 $otamount=$record->OVERTIME_PAY,
							 $otadv=$record->OT_ADVANCE,
							 $otmearn=$record->MISC_OT_EARNINGS,
							 $otamount=$record->OT_NET_PAY
							  
							  
							 
						 ];
					 }
		 
					 echo json_encode(['data' => $data]);
						 

			}


			public function getotregisterdata() {
				$this->load->model('Loan_adv_model');
			 //   $selectedDepartment = $this->input->post('date');
				$periodfromdate= $this->input->post('periodfromdate');
				$periodtodate= $this->input->post('periodtodate');
				$att_payschm =  $this->input->post('att_payschm');
		
				
		
		
		//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget);
		//var_dump($mccodes);
				$data = [];
				$totamt=0;
				$tothrs=0;
				$totadv=0;
				$totmern=0;
				$totnet=0;
					foreach ($mccodes as $record) {
						$totamt=$totamt+$record->OVERTIME_PAY;	
						$tothrs=$tothrs+$record->OT_HOURS;	
						$totadv=$totadv+$record->OT_ADVANCE;	
						$totmern=$totmern+$record->MISC_OT_EARNINGS;	
						$totnet=$totnet+$record->OT_NET_PAY;	
						$data[] = [
							$deptcode=$record->dept_code,
							$deptname=$record->department,
							$empcode=$record->eb_no,
							$empname=$record->wname,
							$othours=$record->OT_HOURS,
							$otrate=$record->RATE,
							$otamount=$record->OVERTIME_PAY,
							$otadv=$record->OT_ADVANCE,
							$otmearn=$record->MISC_OT_EARNINGS,
							$otnet=$record->OT_NET_PAY
					   ];
					}
					$data[] = [
						$deptcode='',
						$deptname='grand Total',
						$empcode=' ',
						$empname='',
						$othours=$tothrs,
						$otrate='',
						$otamount=$totamt,
						$otadv=$totadv,
						$otmern=$totmern,
						$otnet=$totnet
						
					
					];
		
					echo json_encode(['data' => $data]);
			}
			public function getpayregisterdata() {
				$this->load->model('Loan_adv_model');
			 //   $selectedDepartment = $this->input->post('date');
				$periodfromdate= $this->input->post('periodfromdate');
				$periodtodate= $this->input->post('periodtodate');
				$att_payschm =  $this->input->post('att_payschm');
				$holget =  $this->input->post('hol_get');
				$this->data['att_payschm'] = $att_payschm;
				$compId=2;
				$cname='empire';
				$menuId=666;
//				$this->session->set_paydata('name', 'abc');

				$_SESSION["favcolor"] = "yellow";
				$_SESSION["fromdate"] = $periodfromdate;
				$_SESSION["todate"] = $periodtodate;
				$_SESSION["att_payschm"] = $att_payschm;
				$_SESSION["holget"] = $holget;
				
				
//echo $periodfromdate;
$dd=$_SESSION["fromdate"];
//echo $dd;
//				$this->session->set_userdata(array('companyId' => $compId,'companyname' => $cname,  'menuId' => $menuId));	

//				$this->session->set_paydata(array('companyId' => $compId,'companyname' => $cname,  'menuId' => $menuId));	
				
//				$this->set_paydata(array('periodfromdate' => $periodfromdate, 'periodtodate' => $periodtodate, 'att_payschm' => $att_payschm));
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $periodfromdate,
					'to_date' => $periodtodate,
					'Source' => $this->data['Source'],
					'att_type' => $this->data['att_type'],
					'att_status' => $this->data['att_status'],
					'att_dept' => $this->data['att_dept'],
					'att_desig' => $this->data['att_desig'],
					'att_spells' => $this->data['att_spells'],
					'eb_no' => $this->data['eb_no'],
					'att_mark_hrs_att' => $this->data['att_mark_hrs_att'],
					'att_worktype' => $this->data['att_worktype'],
					'att_cat' => $this->data['att_cat_att'],
					'branch_id' => $this->data['branch_id'],
					'componet_id' => $this->data['componet_id'],
					'holget'=>$holget,
					'attpayschm' => $att_payschm
				);				

			
				  $comp = $this->session->userdata('companyId');
			
				//  $pschm=$this->session->paydata('att_payschm');
//				  $pschm = $this->session->paydata('name');
					$pschm=$_SESSION["favcolor"];
			//	echo $holget;
	//			echo 'sess pay'.$pschm;
		
		//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget);
//		var_dump($mccodes);
		if ($holget==2 || $holget==7 || $holget==1) {
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$FROM_DATE=$record->from_date,
							$TO_DATE=$record->to_date,
							$eb_no=$record->eb_no,
							$wname=$record->wname,
							$department=$record->department,
							$desig=$record->desig,
							$time_piece=$record->time_piece,
							$BASIC_RATE=$record->BASIC_RATE,
							$WORKING_HOURS=$record->WORKING_HOURS,
							$NS_HRS=$record->NS_HRS,
							$HL_HRS=$record->HL_HRS,
							$LS_HRS=$record->LS_HRS,
							$STL_D=$record->STL_D,
							$WRK_DAYS=$record->WRK_DAYS,
							$PBASIC=$record->FIX_BASIC+$record->PROD_BASIC,
							$TBASIC=$record->TIME_RATED_BASIC,
							$NS_AMOUNT=$record->NS_AMOUNT,	
							$HOL_AMT=$record->HOL_AMT,
			//				$C_WORK_DAY=$record->C_WORK_DAY,
							$INCREMENTA=$record->INCREMENTA,
							$LAYOFF_WGS=$record->LAYOFF_WGS,
							$INCENTIVE_AMOUNT=$record->INCENTIVE_AMOUNT,
						
							$DA=$record->DA,
							$STL_WGS=$record->STL_WGS,
							$PF_GROSS=$record->PF_GROSS,
							$GROSS_PAY=$record->GROSS_PAY,
							$HRA=$record->HRA,
							$MISS_EARN=$record->MISS_EARN,
							$TOTAL_EARN=$record->TOTAL_EARN,
							$C_F=$record->C_F,
							$EPF=$record->EPF,
							$ESI_GROSS=$record->ESI_GROSS,
							$ESIC=$record->ESIC,
							$LWF=$record->LWF,	
							$P_TAX=$record->P_TAX,
							$PUJA_ADVANCE=$record->PUJA_ADVANCE,
							$STL_ADVANCE=$record->STL_ADVANCE,
							$TOTAL_DEDUCTION=$record->TOTAL_DEDUCTION,
							$B_F=$record->B_F,
						
							
							$NET_PAY=$record->NET_PAY,
							$C_WORK_DAY=$record->C_WORK_DAY,
							$C_BON_ERN=$record->C_BON_ERN,
							
							$pf_uan_no=$record->pf_uan_no,
							$esi_no=$record->esi_no

						];
					}
		}	
		if ( $holget==4 || $holget==5 ) {
			$twhrs=$tfhrs=$tothrs=$tbas=$toth=$ttif=$tconv=$twash=$tgros2=$tepf=$tesi=$tarr=$tadv=$ttotearn=$tgrsded=$roff=$tnpay=$temppf=$tempesi=0;
			
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$FROM_DATE=$periodfromdate,
							$TO_DATE=$periodtodate,
							$eb_no=$record->eb_no,
							$wname=$record->wname,
							$dept_code=$record->dept_code,
							$department=$record->department,
							$WORKING_HOURS=$record->WORKING_HOURS,
							$festval_hrs=$record->HOLIDAY_HR,
							$ot_hrs=$record->OT_HOURS,
							$RATE=$record->RATE_PER_DAY,
							$ARR=$record->ARR_PLUS-$record->ARR_MINUS,
							$ADV=$record->ADVANCE,
							$NETPAY=$record->Net_Payble
				 
						];
						$twhrs=$twhrs+$record->WORKING_HOURS;
						$tfhrs=$tfhrs+$record->HOLIDAY_HR;
						$tothrs=$tothrs+$record->OT_HOURS;
						$tarr=$tarr+$record->ARR_PLUS-$record->ARR_MINUS;
						$tadv=$tadv+$record->ADVANCE;
						$tnpay=$tnpay+$record->Net_Payble;

					}
					$data[] = [
						$FROM_DATE=$periodfromdate,
						$TO_DATE=$periodtodate,							
						$eb_no='',
						$wname='Grand Total',
						$dept_code='',
						$department='',
						$WORKING_HOURS=$twhrs,
						$festval_hrs=$tfhrs,
						$ot_hrs=$tothrs,
						$RATE='',
						$ARR=$tarr,
						$ADV=$tadv,
						$NETPAY=$tnpay,
				];

				}	
				if ($holget==3 ) {
					$twhrs=$tnshrs=$tfhrs=$tothrs=$tbas=$tholwgs=$tarr=$tearn=$tgros2=$tepf=$tesi=$tarr=$tadv=$ttotearn=$tgrsded=$roff=$tnpay=$temppf=$tempesi=0;
				 
					$data = [];
							foreach ($mccodes as $record) {
								$mern=$record->arrear_plus+$record->arrear_minus;
								$data[] = [
									$FROM_DATE=$periodfromdate,
									$TO_DATE=$periodtodate,
									$eb_no=$record->eb_no,
									$wname=$record->wname,
									$dept_code=$record->dept_code,
									$department=$record->department,
									$WORKING_HOURS=$record->working_hrs,
									$nshrs=$record->ns_hrs,
									$festval_hrs=$record->hol_hrs,
									$RATE=$record->rate_per_day,
									$basic=$record->basic,
									$hol_wgs=$record->hol_wgs,
									$ARR=$record->arrear_plus-$record->arrear_minus,
									$total_earn=$record->total_earn,
									$esi=$record->esi,
									$ADV=$record->exadvance,
									$NETPAY=$record->netpay
						 
								];
								$twhrs=$twhrs+$record->working_hrs;
								$tnshrs=$twhrs+$record->ns_hrs;
								$tfhrs=$tfhrs+$record->hol_hrs;
								$tbas=$tbas+$record->basic;
								$tholwgs=$tholwgs+$record->hol_wgs;
								$tarr=$tarr+$record->arrear_plus-$record->arrear_minus;
								$tearn=$tearn+$record->total_earn;
								$tesi=$tesi+$record->esi;
								$tadv=$tadv+$record->exadvance;
								$tnpay=$tnpay+$record->netpay;
		
							}
							$data[] = [
								$FROM_DATE=$periodfromdate,
								$TO_DATE=$periodtodate,
								$eb_no='',
								$wname='Grand Total',
								$dept_code='',
								$department='',
								$WORKING_HOURS=$twhrs,
								$nshrs=$tnshrs,
								$festval_hrs=$tholwgs,
								$RATE='',
								$basic=$tbas,
								$hol_wgs=$tholwgs,
								$ARR=$tarr,
								$total_earn=$tearn,
								$esi=$tesi,
								$ADV=$tadv,
								$NETPAY=$tnpay
					];
		
						}	
		
				if ($holget==6  ) {
					$twhrs=$tfhrs=$tothrs=$tbas=$toth=$ttif=$tconv=$twash=$tgros2=$tepf=$tesi=$tarr=$tadv=$ttotearn=$tgrsded=$roff=$tnpay=$temppf=$tempesi=0;
					$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$FROM_DATE=$periodfromdate,
							$TO_DATE=$periodtodate,							
							$eb_no=$record->eb_no,
							$wname=$record->wname,
							$dept_code=$record->dept_code,
							$department=$record->department,
							$WORKING_HOURS=$record->WORKING_HOURS+$record->NIGHT_SHIFT_HR,
							$festival_hrs=$record->HOLIDAY_HR,
							$othrs=$record->OT_HOURS,
							$RATE=$record->RATE_PER_DAY,
							$basic=$record->BASIC,
							$others=$record->OTHER_ALLOWANCE,
							$tiffin=$record->TIFFIN_AMOUNT,
							$conv=$record->CONV_ALLOWANCE,
							$washing=$record->WASHING_ALLOWANCE,
							$gross2=$record->GROSS2,							
							$epf=$record->EPF,
							$esi=$record->ESI,
							$arrear=$record->ARR_PLUS-$record->ARR_MINUS,
							$ptax='',
							$advance=$record->ADVANCE,
							$tot_earn=$record->TOTAL_EARN,
							$grs_ded=$record->GROSS_DED,
							$r_off=$record->B_F,
							$netpay=$record->Net_Payble,
							$emplpf=$record->EMPL_EPF,
							$emplesi=$record->EMPL_ESI,
							$uanno=$record->pf_uan_no,
							$esino=$record->esi_no
				 
				


						];
						$twhrs=$twhrs+$record->WORKING_HOURS+$record->NIGHT_SHIFT_HR;
						$tfhrs=$tfhrs+$record->HOLIDAY_HR;
						$tothrs=$tothrs+$record->OT_HOURS;
						$tbas=$tbas+$record->BASIC;
						$toth=$toth+$record->OTHER_ALLOWANCE;
						$ttif=$ttif+$record->TIFFIN_AMOUNT;
						$tconv=$tconv+$record->CONV_ALLOWANCE;
						$twash=$twash+$record->WASHING_ALLOWANCE;
						$tgros2=$tgros2+$record->GROSS2;
						$tepf=$tepf+$record->EPF;
						$tesi=$tesi+$record->ESI;
						$tarr=$tarr+$record->ARR_PLUS-$record->ARR_MINUS;
						$tadv=$tadv+$record->ADVANCE;
						$ttotearn=$ttotearn+$record->TOTAL_EARN;
						$tgrsded=$tgrsded+$record->GROSS_DED;
						$roff=$roff+$record->B_F;
						$tnpay=$tnpay+$record->Net_Payble;
						$temppf=$temppf+$record->EMPL_EPF;
						$tempesi=$tempesi+$record->EMPL_ESI;

					}
			 
					$data[] = [
						$FROM_DATE=$periodfromdate,
						$TO_DATE=$periodtodate,							
						$eb_no='',
						$wname='Grand Total',
						$dept_code='',
						$department='',
						$WORKING_HOURS=$twhrs,
						$festival_hrs=$tfhrs,
						$othrs=$tothrs,
						$RATE='',
						$basic=$tbas,
						$others=$toth,
						$tiffin=$ttif,
						$conv=$tconv,
						$washing=$twash,
						$gross2=$tgros2,							
						$epf=$tepf,
						$esi=$tesi,
						$arrear=$tarr,
						$ptax='',
					$advance=$tadv,
					$tot_earn=$ttotearn,
					$grs_ded=$tgrsded,
					$r_off=$roff,
					$netpay=$tnpay,
					$emplpf=$temppf,
					$emplesi=$tempesi,
					$uanno='',
					$esino=''
		];
								 
				}	

				if ($holget==8  ) {
					//$twhrs=$tfhrs=$tothrs=$tbas=$toth=$ttif=$tconv=$twash=$tgros2=$tepf=$tesi=$tarr=$tadv=$ttotearn=$tgrsded=$roff=$tnpay=$temppf=$tempesi=0;
					$data = [];
							foreach ($mccodes as $record) {
								$dfg=round($record->GROSS_PAY100-$record->GROSS_PAY,2);
								$dnp=round($record->NET100-$record->NET_PAY,0);
								if ($dfg<0) {$dfg=0;}
								if ($dnp<0) {$dnp=0;}

								$data[] = [
									
									$FROM_DATE=$record->FROM_DATE,
									$TO_DATE=$record->TO_DATE,
									$department=$record->dept_code,
									$eb_no=$record->eb_no,
									$wname=$record->wname,
									$OPF_GROSS=$record->GROSS_PAY100,							
									$PF_GROSS=$record->GROSS_PAY,
									$OTOTAL_DEDUCTION=$record->TOTAL_DEDUCTION,
									$TOTAL_DEDUCTION=$record->TOTAL_DEDUCTION,
									$ONET_PAY=$record->NET100,
									$NET_PAY=$record->NET_PAY,
									$diffpfg=$dfg,
									$diffnetpay=$dnp
									
									 
									
						 
						
		
		
								];
 		
							}
					 
 										 
						}	
						if ($holget==16 ) {    ///////// SABIR CHANGE 25.05.24/////////////// CHANGE FORHOLGET DATE RANGE  REPORTS
							$twhrs=$tfhrs=$tothrs=$tbas=$toth=$ttif=$tconv=$twash=$tgros2=$tepf=$tesi=$tarr=$tadv=$ttotearn=$tgrsded=$roff=$tnpay=$temppf=$tempesi=0;
							$data = [];
									foreach ($mccodes as $record) {
										$data[] = [
											$FROM_DATE=$periodfromdate,
											$TO_DATE=$periodtodate,							
											$EB_NO=$record->EB_NO,
											$wname=$record->wname,
											$dept_code=$record->dept_code,
											$department=$record->department,
											$WORKING_HOURS=$record->WORKING_HOURS+$record->NIGHT_SHIFT_HR,
											$festival_hrs=$record->HOLIDAY_HR,
											$othrs=$record->OT_HOURS,
											$RATE=$record->RATE_PER_DAY,
											$basic=$record->BASIC,
											$others=$record->OTHER_ALLOWANCE,
											$tiffin=$record->TIFFIN_AMOUNT,
											$conv=$record->CONV_ALLOWANCE,
											$washing=$record->WASHING_ALLOWANCE,
											$gross2=$record->GROSS2,							
											$epf=$record->EPF,
											$esi=$record->ESI,
											$arrear=$record->ARR_PLUS-$record->ARR_MINUS,
											$ptax='',
											$advance=$record->ADVANCE,
											$tot_earn=$record->TOTAL_EARN,
											$grs_ded=$record->GROSS_DED,
											$r_off=$record->B_F,
											$netpay=$record->Net_Payble,
											$emplpf=$record->EMPL_EPF,
											$emplesi=$record->EMPL_ESI,
											$uanno=$record->pf_uan_no,
											$esino=$record->esi_no
								 
								
						
						
										];
										$twhrs=$twhrs+$record->WORKING_HOURS+$record->NIGHT_SHIFT_HR;
										$tfhrs=$tfhrs+$record->HOLIDAY_HR;
										$tothrs=$tothrs+$record->OT_HOURS;
										$tbas=$tbas+$record->BASIC;
										$toth=$toth+$record->OTHER_ALLOWANCE;
										$ttif=$ttif+$record->TIFFIN_AMOUNT;
										$tconv=$tconv+$record->CONV_ALLOWANCE;
										$twash=$twash+$record->WASHING_ALLOWANCE;
										$tgros2=$tgros2+$record->GROSS2;
										$tepf=$tepf+$record->EPF;
										$tesi=$tesi+$record->ESI;
										$tarr=$tarr+$record->ARR_PLUS-$record->ARR_MINUS;
										$tadv=$tadv+$record->ADVANCE;
										$ttotearn=$ttotearn+$record->TOTAL_EARN;
										$tgrsded=$tgrsded+$record->GROSS_DED;
										$roff=$roff+$record->B_F;
										$tnpay=$tnpay+$record->Net_Payble;
										$temppf=$temppf+$record->EMPL_EPF;
										$tempesi=$tempesi+$record->EMPL_ESI;
						
									}
							 
 												 
								}	
						
						


					echo json_encode(['data' => $data]);
			}
	
			public function getworkerdetails() {
				$this->load->model('Loan_adv_model');
				   $periodfromdate= $this->input->post('periodfromdate');
				   $periodtodate= $this->input->post('periodtodate');
				   $att_spell =  $this->input->post('att_spell');
				   $att_dept =  $this->input->post('att_dept');
				   $holget =  $this->input->post('hol_get');
				   $occucode =  $this->input->post('occucode');
				   $mccodes = $this->Loan_adv_model->getworkerdetails($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept,$occucode);
				   //var_dump($mccodes);
					   $data = [];
					   $sl=1;
					   $sf='';
							   foreach ($mccodes as $record) {
								if ($record->shift<>$sf) 
								{ $sl=1;
									$sf=$record->shift;
								}
									$data[] = [
										$sl=$sl,
										$eb_no=$record->eb_no,
									   $wname=$record->wname,
									   $shift=$record->shift,
									   $designation=$record->desig,
									   $attendance_type=$record->attendance_type,
									   $whrs=$record->whrs,
									   $whrs1='',
								   ];
							   $sl++;
								}
		   
							   echo json_encode(['data' => $data]);
			
				}
				public function getworkerattdetails() {
					$this->load->model('Loan_adv_model');
					   $periodfromdate= $this->input->post('periodfromdate');
					   $periodtodate= $this->input->post('periodtodate');
					   $att_spell =  $this->input->post('att_spell');
					   $att_dept =  $this->input->post('att_dept');
					   $holget =  $this->input->post('hol_get');
					   $ebid =  $this->input->post('ebid');
					   $mccodes = $this->Loan_adv_model->getworkerattdetails($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept,$ebid);
					   //var_dump($mccodes);
						   $data = [];
						   $sl=1;
						   $sf='';
								   foreach ($mccodes as $record) {
									if ($record->shift<>$sf) 
									{ $sl=1;
										$sf=$record->shift;
									}
										$data[] = [
											$eb_no=$record->eb_no,
										   $wname=$record->wname,
										   $attendance_date=$record->attendance_date,
										   $shift=$record->shift,
										   $designation=$record->desig,
										   $attendance_type=$record->attendance_type,
										   $whrs=$record->whrs,
										   $whrs1='',
									   ];
								   $sl++;
									}
			   
								   echo json_encode(['data' => $data]);
				
					}
				public function getebmcdata() {
				$this->load->model('Loan_adv_model');
			 //   $selectedDepartment = $this->input->post('date');
				$periodfromdate= $this->input->post('periodfromdate');
				$periodtodate= $this->input->post('periodtodate');
				$att_spell =  $this->input->post('att_spell');
				$att_dept =  $this->input->post('att_dept');
				$holget =  $this->input->post('hol_get');
		 
				$sdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
				$edate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 

		
		//echo $periodfromdate,$periodtodate,$att_payschm;
		$mccodes = $this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
		//var_dump($mccodes);
		if ($holget==1 ) {
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$FROM_DATE=$record->attendace_date,
							$spell=$record->spell,
							$eb_no=$record->eb_no,
							$wname=$record->wname,
							$designation=$record->desig,
							$mech_code=$record->mech_code,
							$mechine_name=$record->mechine_name,
							$whrs=$record->whrs,
							$cnt=$record->cnt

						];
					}
		}	
		if ($holget==2 ) {
			$data = [];
			$dpt=0;
			$n=1;
					foreach ($mccodes as $record) {
/*
						if ($dpt<>$record->dept_code)	{
							if ($n>1) {

							}
							$dpt=$record->dept_code;
						}	
*/
						$data[] = [
							$ATTANDANCE_DATE=$record->attdate,
							$dept_code= $record->dept_code,
							$dept_desc= $record->dept_desc,
							$HOCCU_CODE= $record->HOCCU_CODE, 
							$OCCU_DESC=$record->OCCU_DESC,
							$AHND=number_format($record->AHND,3),
							$BHND=number_format($record->BHND,3),
							$CHND=number_format($record->CHND,3),
							$STOTAL=number_format($record->STOTAL,3),
							$OAHND=number_format($record->OAHND,3),
							$OBHND=number_format($record->OBHND,3),
							$OCHND=number_format($record->OCHND,3),
							$OTOTAL=number_format($record->OTOTAL,3)
							 
						];
					}
		/*		
					$mccodes = $this->Loan_adv_model->getebmcdatal($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept,$number);
					foreach ($mccodes as $record) {
					 
						$data[] = [
							$ATTANDANCE_DATE=$record->ATTANDANCE_DATE,
							$dept_code= $record->dept_code,
							$GRAND= $record->GRAND,
							$dept_code= $record->dept_code,
							$dept_desc= $record->dept_desc, 
							$ASHIFT=number_format($record->ASHIFT,3),
							$BSHIFT=number_format($record->BSHIFT,3),
							$CSHIFT=number_format($record->CSHIFT,3),
							$STOTAL=number_format($record->STOTAL,3),
							$OASHIFT=number_format($record->OASHIFT,3),
							$OBSHIFT=number_format($record->OBSHIFT,3),
							$OCSHIFT=number_format($record->OCSHIFT,3),
							$OTOTAL=number_format($record->OTOTAL,3)
							
						];
					}
		*/
				}

		
				
		if ($holget==3 ) {
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$periodfromdate=$periodfromdate, 
							$periodtodate= $periodtodate,
							$eb_no= $record->eb_no, 
							$WRK_NAME=$record->WRK_NAME,
							$shift=$record->shift,
							$dept_code=$record->dept_code,
							$dept_desc=$record->dept_desc,
							$occu_code=$record->occu_code,
							$occu_desc=$record->occu_desc,
							$rwhrs=$record->rwhrs,
							$nwhrs=$record->nwhrs,
							$fhrs=$record->fhrs,
							$owhrs=$record->owhrs
												 
						];
					}
		}
		if ($holget==4 ) {
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$periodfromdate=$periodfromdate, 
							$periodtodate= $periodtodate,
							$eb_no= $record->eb_no, 
							$WRK_NAME=$record->WRK_NAME,
							$shift=$record->mcnos,
							$dept_code=$record->dept_code,
							$dept_desc=$record->dept_desc,
							$occu_code=$record->occu_code,
							$occu_desc=$record->occu_desc,
							$rwhrs=$record->rwhrs,
							$nwhrs=$record->nwhrs,
							$fhrs=$record->fhrs,
							$owhrs=$record->owhrs
												 
						];
					}
		}

		if ($holget==7 ) {
			$data = [];
					foreach ($mccodes as $record) {
						
						$data[] = [
							$ebid=$record->eb_id, 
							$periodfromdate=$sdate, 
							$periodtodate= $edate,
							$eb_no= $record->emp_code, 
							$WRK_NAME=$record->wname,
							$catagory=$record->cata_desc,
							$department=$record->department,
							$designation=$record->designation,
							$rwhrs=$record->rwhrs,
							$owhrs=$record->owhrs,
							$nhrs=$record->nhrs,
							$wdays=$record->wdays,
							$otdays=$record->otdays,
							$leavedays=$record->leavedays,
							$holidays =$record->holidays,
							$tmwdays=$record->tmworked,
							$absentdays=$record->absentdays,
							$lastdate=$record->mxdate
												 
						];
					}
		}
		
		if ($holget==8 ) {
			$data = [];
					foreach ($mccodes as $record) {
						$data[] = [
							$eb_no=$record->eb_no,
							$work_name=$record->work_name,
							$shift=$record->shift,
							$dept_desc=$record->dept_desc,
							$desig=$record->desig,
							$work_hours=$record->work_hours,
							$rate=$record->rate
							
							
												 
						];
					}
		}



					echo json_encode(['data' => $data]);
			}


			public function otherdataprint() {
				$periodfromdate= $this->input->get('periodfromdate');
				$periodtodate= $this->input->get('periodtodate');
				$att_payschm =  $this->input->get('att_payschm');
				$holget =  $this->input->get('holget');
				$payschemeName =  $this->input->get('payschemeName');
				$att_spell =  $this->input->get('att_spell');
				$att_dept =  $this->input->get('att_dept');
				$sql="select dept_code,dept_desc from vowsls.department_master where dept_id=".$att_dept;
				$query = $this->db->query($sql);
				$data = $query->result_array();
				$zt=1;
				$deptdesc='';
				foreach ($data as $row) {
					$deptdesc= $row['dept_desc'];
					$deptcode=$row['dept_code'];
				}	



					 $company_name = $this->session->userdata('companyname');
					 $comp = $this->session->userdata('companyId');
					 $periodfrmdate=substr($periodfromdate,8,2).'-'.substr($periodfromdate,5,2).'-'.substr($periodfromdate,0,4); 
					 $periodtdate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4); 
					 $mccodes = $this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
					 if ($holget==6 ) {
						$fileContainer = "aVlltxt.txt";
						$filePointer = fopen($fileContainer,"w+");
						$logMsg='';
						foreach ($mccodes as $record) {
							$logMsg.=$record->prods."\n";; 
						  
						}	

						fputs($filePointer,$logMsg);
						fclose($filePointer);
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'winding.zip';
						$zt=1;
					 }
					 if ($holget==2 ) {
			
						$fileContainer = "Hands.txt";
						$filePointer = fopen($fileContainer,"w+");
					   $totamt=0;
					   $othrs=0;
					   $ln1=$company_name."\n";
					   $ln2="Daily Hands Complement Date.".$periodfrmdate."  \n";;
					   $logMsg='';
					   $line = str_repeat('-', 136)."\n";;
					   $ln3=' OC Code | Description               |              Shift                    |           OT                          |                '."\n";;
					   $ln4='         |                           |    A    |     B   |     C   |   Total |    A    |     B   |     C   |   Total |     Remarks    '."\n";;
					   $pg=1;
					   $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
						$nlb=' |';
					 	 $logMsg .= $ln1;
						 $logMsg .= $ln2;
						 $logMsg .= chr(15).$line;
						 $logMsg .= $ln3;
						 $logMsg .= $ln4;
						 $logMsg .= $line;
					   $lnn=6;
						$dp='';
						$nnn=0;
					   foreach ($mccodes as $record) {
						   if ($lnn>58) {
							   $logMsg .= 'p'.Chr(12)."\n";
							   $pg++;
							   $logMsg .= $ln1;
							   $logMsg .= $ln2;
							   $logMsg .= chr(15).$line;
							   $logMsg .= $ln3;
							   $logMsg .= $ln4;
							   $logMsg .= $line;
								$lnn=6;
						   }	
						   if ($dp<>$record->dept_code) {
								if ($nnn>0) {
								$logMsg .= $line;
								$ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
 											 $lnn++;
								}
								$lnn++;
								$dp=$record->dept_code;
						   }													//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
						   if (strlen($record->HOCCU_CODE)==0) {
								$logMsg .= $line;
						   }	
						   $logMsg.= 
						   $record->HOCCU_CODE.str_repeat(' ', 8- strlen($record->HOCCU_CODE)).$nlb.
						   substr($record->OCCU_DESC,0,26).str_repeat(' ', 26- strlen(substr($record->OCCU_DESC,0,26))).$nlb.	
						   str_repeat(' ', 8- strlen(number_format( $record->AHND,2))).substr((number_format($record->AHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->BHND,2))).substr((number_format($record->BHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->CHND,2))).substr((number_format($record->CHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->STOTAL,2))).substr((number_format($record->STOTAL,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->OAHND,2))).substr((number_format($record->OAHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->OBHND,2))).substr((number_format($record->OBHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->OCHND,2))).substr((number_format($record->OCHND,2)),0,8).$nlb.  
						   str_repeat(' ', 8- strlen(number_format( $record->OTOTAL,2))).substr((number_format($record->OTOTAL,2)),0,8).$nlb.  
						   "\n";

			 				   
							   $lnn=$lnn+1;
						   }
							  $logMsg .= $line;
							  $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
									 $lnn++;

 						   $logMsg .= ''."\n";
						   
							$mccodes = $this->Loan_adv_model->getebmcdatal($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);

							$ln1=$company_name."\n";
							$ln2="Daily Catagory Hands Complement Date.".$periodfrmdate."  \n";;
							$line = str_repeat('-', 136)."\n";
							$ln3='| Description               |              Shift                    |                '."\n";;
							$ln4='|                           |    A    |     B   |     C   |   Total |     Remarks    '."\n";;
							$pg=1;
							$ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
							 $nlb=' |';
  						    $logMsg .= $ln1;
							  $logMsg .= $ln2;
							  $logMsg .= chr(15).$line;
							  $logMsg .= $ln3;
							  $logMsg .= $ln4;
							  $logMsg .= $line;
							$lnn=$lnn+6;
							 $dp='';
							 $nnn=0;
							foreach ($mccodes as $record) {
								if ($lnn>58) {
									$logMsg .= 'p'.Chr(12)."\n";
									$pg++;
									$logMsg .= $ln1;
									$logMsg .= $ln2;
									$logMsg .= chr(15).$line;
									$logMsg .= $ln3;
									$logMsg .= $ln4;
									$logMsg .= $line;
										  $lnn=6;
								}	
								if ($dp<>$record->dept_code) {
									 if ($nnn>0) {
							 
										  
									 $logMsg .= $line;
									 $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
												   $lnn++;
									 }
									 $nnn++;
									 $dp=$record->dept_code;
								}													//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
								if (strlen($record->cata_id)==0) {
									 $logMsg .= $line;
									 $nnn++;
								}	
								$logMsg.= 
								substr($record->cata_desc,0,26).str_repeat(' ', 26- strlen(substr($record->cata_desc,0,26))).$nlb.	
								str_repeat(' ', 8- strlen(number_format( $record->ASHIFT,2))).substr((number_format($record->ASHIFT,2)),0,8).$nlb.  
								str_repeat(' ', 8- strlen(number_format( $record->BSHIFT,2))).substr((number_format($record->BSHIFT,2)),0,8).$nlb.  
								str_repeat(' ', 8- strlen(number_format( $record->CSHIFT,2))).substr((number_format($record->CSHIFT,2)),0,8).$nlb.  
								str_repeat(' ', 8- strlen(number_format( $record->STOTAL,2))).substr((number_format($record->STOTAL,2)),0,8).$nlb.  
								"\n";
									 
									$lnn=$lnn+1;
								}
								   $logMsg .= $line;
								   $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
										  $lnn++;
	 
										  $logMsg .= ''."\n";
										  $lnn++;
						   
										  $mccodes = $this->Loan_adv_model->getebmcdet($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
										//var_dump($mccodes);	
										  $ln1=$company_name."\n";
										  $ln2="Daily Mechine Runs Report Date.".$periodfrmdate."  \n";;
										  $line = str_repeat('-', 136)."\n";;
										  $ln3='| Code    | Description               |              Shift                    |                '."\n";;
										  $ln4='|         |                           |    A    |     B   |     C   |   Total |     Remarks    '."\n";;
										  $pg=1;
										  $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
										   $nlb=' |';
											 $logMsg .= $ln1;
											$logMsg .= $ln2;
											$logMsg .= chr(15).$line;
											$logMsg .= $ln3;
											$logMsg .= $ln4;
											$logMsg .= $line;
			  							$lnn=$lnn+5;
										   $dp='';
										   $nnn=0;
										  foreach ($mccodes as $record) {
											  if ($lnn>58) {
												  $logMsg .= 'p'.Chr(12)."\n";
												  $pg++;
												  $logMsg .= $ln1;
												  $logMsg .= $ln2;
												  $logMsg .= chr(15).$line;
												  $logMsg .= $ln3;
												  $logMsg .= $ln4;
												  $logMsg .= $line;
												  $lnn=6;
											  }	
											  if ($dp<>$record->dept_code) {
												   if ($nnn>0) {
										   
														
												   $logMsg .= $line;
												   $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
																 $lnn++;
												   }
												   $nnn++;
												   $dp=$record->dept_code;
											  }													//			$logMsg.= $record->emp_code.",".$record->incamt.",".$periodfromdate.",".$periodtodate.",".$payschemeName."\r\n";
											 
											  $fa = str_replace(',', '', $record->ASHIFT);
											  $fb = str_replace(',', '', $record->BSHIFT);
											  $fc = str_replace(',', '', $record->CSHIFT);
											  $ftot = str_replace(',', '', $record->STOTAL);
											  
											  
											  $logMsg.= 
											  $record->mc_code.str_repeat(' ', 8- strlen($record->mc_code)).$nlb.
											  substr($record->Mechine_type_name,0,26).str_repeat(' ', 26- strlen(substr($record->Mechine_type_name,0,26))).$nlb.	
											  str_repeat(' ', 9- strlen(number_format( $fa,2))).
											  substr((number_format($fa,2)),0,8).$nlb.  
											  str_repeat(' ', 8- strlen(number_format( $fb,2))).substr((number_format($fb,2)),0,8).$nlb.  
											  str_repeat(' ', 8- strlen(number_format( $fc,2))).substr((number_format($fc,2)),0,8).$nlb.  
											  str_repeat(' ', 9- strlen(number_format( $ftot,2))).substr((number_format($ftot,2)),0,8).$nlb.  
											  "\n";
												   
												  $lnn=$lnn+1;
											  }
												 $logMsg .= $line;
												 $ahnd=$bhnd=$chnd=$thnd=$oahnd=$obhnd=$ochnd=$othnd=0;	
														$lnn++;
				   
											   $logMsg .= Chr(12)."\n";
								
	 


						   fputs($filePointer,$logMsg);
					   fclose($filePointer);
					   $txt1=$fileContainer;
					   $files = array($txt1);
					   $zipname = 'handcomp.zip';
					   $zt=1;
				  
					   }

					 if ($holget==3 || $holget==5) {
						$att_spell=substr($att_spell,0,1);

     					 $fileContainer = "mroll.txt";
						 $filePointer = fopen($fileContainer,"w+");
						$totamt=0;
						$othrs=0;
						$line = str_repeat('-', 136)."\n";;
						$ln1=$company_name."\n";
		//				"Attendance Sheet " + Trim(CMBDEPT.Text) + " for The Period From " + Format(DTPicker1.Value, "dd/MM/yyyy") + " To " + Format(DTPicker2.Value, "dd/MM/yyyy") + " Shift " + Combo1.Text + Space(15) + "Page No :" + str(PGNO)
						$ebsrl=0;
						$lnb=' |';
						$ln2="Attendance Sheet for Dept ".$deptdesc." for the period From ".$periodfrmdate." To ".$periodtdate.' Shift '.$att_spell."    Dept Code ".$dept_code."    Page No  "  ;
						$ln3='       | EB No   | Name            |Sft| Working |  NSH  |TP |  HOLI |  BASIC   | S T L  | S T L    | Advance| Adj    | Adj'."\n";;
						$ln4='       |         |                 |   |  Hours  |       |   |  Days | Rate/Hr  |  Days  | Advance  |   Ded  | (+)    | (-)'."\n";;
						$logMsg='';
						  $pg=1;
						 $gothrs=$grwhrs=$gnwhrs=$gfhrs=0;
						 
						  $lnn=1;
						  $logMsg .= $ln1;
						  $logMsg .= $ln2.$pg."\n";;
						  $logMsg .= $line;
						 $logMsg .= $ln3;
						  $logMsg .= $ln4;
						  $logMsg .= $line;
							 $lnn=6;
				  foreach ($mccodes as $record) {

							if ($lnn>58) {
									$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
									substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
									substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
									str_repeat(' ', 2 - strlen($bln)).$bln.$lnb.
									str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
									str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
									str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
									str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
									 "\n";
									$logMsg .= $line."\n";
									$logMsg .= Chr(12)."\n";
									$logMsg .= $ln1;
									$logMsg .= $ln2.$pg."\n";;
									$logMsg .= $line;
 								    $logMsg .= $ln3;
									$logMsg .= $ln4;
									$logMsg .= $line;
									$lnn=6;
									$gothrs=$grwhrs=$gnwhrs=$gfhrs=0;
								$pg++;	
							}														
							if ($ebsrl<>$record->ebsrl) {
								if ($ebsrl>0) {
									$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
									substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
									substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
									str_repeat(' ', 2 - strlen($bln)).$bln.$lnb.
									str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
									str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
									str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
									str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
									 "\n";
									$logMsg .= $line."\n";
									$logMsg .= Chr(12)."\n";
									$logMsg .= $ln1;
									$logMsg .= $ln2.$pg."\n";;
									$logMsg .= $line;
 								    $logMsg .= $ln3;
									$logMsg .= $ln4;
									$logMsg .= $line;
									$lnn=6;
									$gothrs=$grwhrs=$gnwhrs=$gfhrs=0;									}
									$ebsrl=$record->ebsrl;
									$pg=1;
							}	

							$bln='';
							$rwhrs=number_format($record->rwhrs,2);
							$nwhrs=number_format($record->nwhrs,2);
							$owhrs=number_format($record->owhrs,2);
							$fhrs=number_format($record->fhrs,0);
							$gothrs=$gothrs+$record->owhrs;
							$grwhrs=$grwhrs+$record->rwhrs;
							$gnwhrs=$gnwhrs+$record->nwhrs;
							$gfhrs=$gfhrs+$record->fhrs;
							if ($rwhrs<=0) {$rwhrs='';}
							if ($nwhrs<=0) {$nwhrs='';}
							if ($owhrs<=0) {$owhrs='';}
							if ($fhrs<=0) {$fhrs='';}

							$logMsg .=$owhrs.str_repeat(' ', 6- strlen($owhrs)).$lnb.' '.
							substr($record->eb_no,0,7).str_repeat(' ', 7 - strlen(substr($record->eb_no,0,7))).$lnb.
							substr($record->WRK_NAME,0,16).str_repeat(' ', 16 - strlen(substr($record->WRK_NAME,0,16))).$lnb.
							str_repeat(' ', 2 - strlen($record->shift)).$record->shift.$lnb.
							str_repeat(' ', 8 - strlen($rwhrs)).$rwhrs.$lnb.
							str_repeat(' ', 6 - strlen($nwhrs)).$nwhrs.$lnb.
							str_repeat(' ', 2 -strlen($record->t_p)).$record->t_p.$lnb.
							str_repeat(' ', 6 - strlen($fhrs)).$fhrs.$lnb.'          |        |          |        |        |    '.
 							"\n";
							 $logMsg .=$bln.str_repeat(' ', 6- strlen($bln)).$lnb.
							 substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.' '.
							 substr($record->occu_code,0,15).str_repeat(' ', 15 - strlen(substr($record->occu_code,0,15))).$lnb.
							 '   |         |       |   |       |          |        |          |        |        |'.
							"\n";
								 $logMsg .= $line;
//								$totamt=$totamt+$record->incamt;
//								$tothrs=$tothrs+$record->incdays;
								$lnn=$lnn+3;
							}
							$bln='';
//							$gt="Grand Total";
							$empc=" ";
							$grwhrs=number_format($grwhrs,2);
							$gnwhrs=number_format($gnwhrs,2);
							$gowhrs=number_format($gowhrs,2);
							$gfhrs=number_format($gfhrs,0);

							$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
							substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
							substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
							str_repeat(' ', 2 - strlen($bln)).$bln.$lnb.
							str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
							str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
							str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
							str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
 							"\n";
							$logMsg .= $line."\n";
							$logMsg .= Chr(12)."\n";
				
 							  
				
						fputs($filePointer,$logMsg);
						fclose($filePointer);
						$txt1=$fileContainer;
						$files = array($txt1);
						$zipname = 'mroll.zip';
						$zt=1;
				   
						}		
				
						if ($holget==4 ) {
							$att_spell=substr($att_spell,0,1);
	
							  $fileContainer = "mroll.txt";
							 $filePointer = fopen($fileContainer,"w+");
							$totamt=0;
							$othrs=0;
							$line = str_repeat('-', 136)."\n";;
							$ln1=$company_name."\n";
			//				"Attendance Sheet " + Trim(CMBDEPT.Text) + " for The Period From " + Format(DTPicker1.Value, "dd/MM/yyyy") + " To " + Format(DTPicker2.Value, "dd/MM/yyyy") + " Shift " + Combo1.Text + Space(15) + "Page No :" + str(PGNO)
							$ebsrl=0;
							$lnb=' |';
							$ln2="Attendance Sheet for Dept ".$deptdesc." for the period From ".$periodfrmdate." To ".$periodtdate.' Shift '.$att_spell."    Dept Code ".$dept_code."    Page No  "  ;
							$ln3='       | EB No   | Name            |Line  No | Working |  NSH  |TP |  HOLI |  BASIC   | S T L  | S T L    | Advance| Adj    | Adj'."\n";;
							$ln4='       |         |                 | Mc Nos  |  Hours  |       |   |  Days | Rate/Hr  |  Days  | Advance  |   Ded  | (+)    | (-)'."\n";;
							$logMsg='';
							  $pg=1;
							 $gothrs=$grwhrs=$gnwhrs=$gfhrs=0;
							 
							  $lnn=1;
							  $logMsg .= $ln1;
							  $logMsg .= $ln2.$pg."\n";;
							  $logMsg .= $line;
							 $logMsg .= $ln3;
							  $logMsg .= $ln4;
							  $logMsg .= $line;
								 $lnn=6;
					  foreach ($mccodes as $record) {
	
								if ($lnn>58) {
										$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
										substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
										substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
										str_repeat(' ', 8 - strlen($bln)).$bln.$lnb.
										str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
										str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
										str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
										str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
										 "\n";
										$logMsg .= $line."\n";
										$logMsg .= Chr(12)."\n";
										$logMsg .= $ln1;
										$logMsg .= $ln2.$pg."\n";;
										$logMsg .= $line;
										 $logMsg .= $ln3;
										$logMsg .= $ln4;
										$logMsg .= $line;
										$lnn=6;
										$gothrs=$grwhrs=$gnwhrs=$gfhrs=0;
									$pg++;	
								}														
								if ($ebsrl<>$record->ebsrl) {
									if ($ebsrl>0) {
										$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
										substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
										substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
										str_repeat(' ', 8 - strlen($bln)).$bln.$lnb.
										str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
										str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
										str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
										str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
										 "\n";
										$logMsg .= $line."\n";
										$logMsg .= Chr(12)."\n";
										$logMsg .= $ln1;
										$logMsg .= $ln2.$pg."\n";;
										$logMsg .= $line;
										 $logMsg .= $ln3;
										$logMsg .= $ln4;
										$logMsg .= $line;
										$lnn=6;
										$gothrs=$grwhrs=$gnwhrs=$gfhrs=0;									}
										$ebsrl=$record->ebsrl;
										$pg=1;
								}	
	
								$bln='';
								$rwhrs=number_format($record->rwhrs,2);
								$nwhrs=number_format($record->nwhrs,2);
								$owhrs=number_format($record->owhrs,2);
								$fhrs=number_format($record->fhrs,0);
								$gothrs=$gothrs+$record->owhrs;
								$grwhrs=$grwhrs+$record->rwhrs;
								$gnwhrs=$gnwhrs+$record->nwhrs;
								$gfhrs=$gfhrs+$record->fhrs;
								if ($rwhrs<=0) {$rwhrs='';}
								if ($nwhrs<=0) {$nwhrs='';}
								if ($owhrs<=0) {$owhrs='';}
								if ($fhrs<=0) {$fhrs='';}
	
								$logMsg .=$owhrs.str_repeat(' ', 6- strlen($owhrs)).$lnb.' '.
								substr($record->eb_no,0,7).str_repeat(' ', 7 - strlen(substr($record->eb_no,0,7))).$lnb.
								substr($record->WRK_NAME,0,16).str_repeat(' ', 16 - strlen(substr($record->WRK_NAME,0,16))).$lnb.
								'  '.substr($record->mcnos,0,6).str_repeat(' ', 6 - strlen(substr($record->mcnos,0,6))).$lnb.
								str_repeat(' ', 8 - strlen($rwhrs)).$rwhrs.$lnb.
								str_repeat(' ', 6 - strlen($nwhrs)).$nwhrs.$lnb.
								str_repeat(' ', 2 -strlen($record->t_p)).$record->t_p.$lnb.
								str_repeat(' ', 6 - strlen($fhrs)).$fhrs.$lnb.'          |        |          |        |        |    '.
								 "\n";
								 $logMsg .=$bln.str_repeat(' ', 6- strlen($bln)).$lnb.
								 substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.' '.
								 substr($record->occu_code,0,15).str_repeat(' ', 15 - strlen(substr($record->occu_code,0,15))).$lnb.
								 '         |         |       |   |       |          |        |          |        |        |'.
								"\n";
									 $logMsg .= $line;
	//								$totamt=$totamt+$record->incamt;
	//								$tothrs=$tothrs+$record->incdays;
									$lnn=$lnn+3;
								}
								$bln='';
	//							$gt="Grand Total";
								$empc=" ";
								$grwhrs=number_format($grwhrs,2);
								$gnwhrs=number_format($gnwhrs,2);
								$gowhrs=number_format($gowhrs,2);
								$gfhrs=number_format($gfhrs,0);
	
								$logMsg .=$gothrs.str_repeat(' ', 6- strlen($gothrs)).$lnb.
								substr($bln,0,8).str_repeat(' ', 8 - strlen(substr($bln,0,8))).$lnb.
								substr($bln,0,16).str_repeat(' ', 16 - strlen(substr($bln,0,16))).$lnb.
								str_repeat(' ', 8 - strlen($bln)).$bln.$lnb.
								str_repeat(' ', 8 - strlen($grwhrs)).$grwhrs.$lnb.
								str_repeat(' ', 6 - strlen($gnwhrs)).$gnwhrs.$lnb.
								str_repeat(' ', 2 -strlen($bln)).$bln.$lnb.
								str_repeat(' ', 6 - strlen($gfhrs)).$gfhrs.$lnb.'          |        |          |        |        |    '.
								 "\n";
								$logMsg .= $line."\n";
								$logMsg .= Chr(12)."\n";
					
								   
					
							fputs($filePointer,$logMsg);
							fclose($filePointer);
							$txt1=$fileContainer;
							$files = array($txt1);
							$zipname = 'mroll.zip';
							$zt=1;
					   
							}		
		  
		if (strlen($zipname)>0) { 
						$zip = new ZipArchive;
						$zip->open($zipname, ZipArchive::CREATE);
						foreach ($files as $file) {
			  			$zip->addFile($file);
						}
						$zip->close();
			
			header('Content-Type: application/zip');
			header('Content-disposition: attachment; filename='.$zipname);
			header('Content-Length: ' . filesize($zipname));
			readfile($zipname);
					unlink($fileContainer);
					 unlink($zipname);
			
					}
			
				}



			public function loadAnotherPage() {
				$company = $this->session->userdata('companyId');

 				
				$this->page_construct('other_data_entry/payslip_parameters');
			}

			public function getpayschemeparadata() {
				$this->load->model('Loan_adv_model');
			 //   $selectedDepartment = $this->input->post('date');
			 $att_branch =  $this->input->post('att_branch');
			 $att_payschm =  $this->input->post('att_payschm');
		 
		$mccodes = $this->Loan_adv_model->getpayschemeparadata($att_branch,$att_payschm);
			$data = [];
					foreach ($mccodes as $record) {
						
						$data[] = [
							$id=$record->paraid,
							$bid=$record->company_id,
							$brid=$record->branch_id,
							$payschmid=$record->payscheme_id,
							$payschmnm=$record->payshmname,
							$compnid=$record->COMPONENT_ID,
							$compnm=$record->NAME,
							$desc_print=$record->desc_print,
							$payslip_order=$record->payslip_order,
							$payslip_print=$record->payslip_print,
							$total_print=$record->total_print,
							$pps=$record->payslip_print,
						//	$tps=$record->total_print
 
						];
					}
 					echo json_encode(['data' => $data]);
			}
			
			public function updatepayschemeparadata() {
				$recordid = $this->input->post('recordId');
				$checkbox1Value = $this->input->post('checkbox1Value');
				$rec_time =  date('Y-m-d H:i:s');
				$checkbox2Value = $this->input->post('checkbox2Value');
				$comp = $this->session->userdata('companyId');
 				
				$stat=3;
				$active=1;
				$userid=$this->session->userdata('userid');
				  
				 $data = array(
					'payslip_print' => $checkbox1Value,
					'total_print' => $checkbox2Value
 					
		
				);
		
				$this->Loan_adv_model->updatepayschemeparadata($recordid, $data);
			//	$this->Loan_adv_model->getLoanadvData($selectedDepartment);
//			updateLoanAdvanceData
		
			$response = array(
				'success' => true,
				'savedata'=> 'saved'
			);
			
				echo json_encode($response);
			
			}
		

			public function updateeleg_data() {
				$recordid = $this->input->post('record_id');
				$holget = $this->input->post('holget');
				$aincget = $this->input->post('aincget');
				$fnaincamt = $this->input->post('fnaincamt');
				$mnaincamt = $this->input->post('mnaincamt');
				$ebid = $this->input->post('ebid');
				$comp = $this->session->userdata('companyId');
 				
				$stat=3;
				$active=1;
				$userid=$this->session->userdata('userid');
				  
				$data = array(
					'holiday_eligibility' => $holget,
					'att_incn_eligibility' => $aincget,
					'fn_att_inc_rate' => $fnaincamt,
					'mn_att_inc_rate' => $mnaincamt,
					'status' =>$stat
				);
		
				$this->Loan_adv_model->updateeleg_data($recordid, $data);
			//	$this->Loan_adv_model->getLoanadvData($selectedDepartment);
//			updateLoanAdvanceData
		
			$response = array(
				'success' => true,
				'savedata'=> 'saved'
			);
			
				echo json_encode($response);
			
			}
		


			public function elegsavedata() {
				$holget = $this->input->post('holget');
				$aincget = $this->input->post('aincget');
				$fnaincamt =  $this->input->post('fnaincamt');
				$mnaincamt =  $this->input->post('mnaincamt');
				$ebid = $this->input->post('ebid');
				$comp = $this->session->userdata('companyId');
				$rec_time =  date('Y-m-d H:i:s');
				$stat=3;
				$active=1;
				$userid=$this->session->userdata('userid');
				
				$data = array(
					'holiday_eligibility' => $holget,
					'att_incn_eligibility' => $aincget,
					'fn_att_inc_rate' => $fnaincamt,
					'mn_att_inc_rate' => $mnaincamt,
					'eb_id' => $ebid,
					'is_active'=> 1,
					'created_by'=>$userid,
					'created_date'=>$rec_time,
					'status'=> 3
				);
		$this->db->insert('EMPMILL12.tbl_holiday_att_inc_eligibility', $data);


$response = array(
				'success' => true,
				'savedata'=> 'saved'
			);
			
				echo json_encode($response);
			
			}
		
			public function locadatafill() {
				$comp = $this->session->userdata('companyId');
				$mccodes = $this->Loan_adv_model->locadatafill($comp);
				$locationData = [];
						foreach ($mccodes as $record) {
							
							$locationData[] = [
								$subloca_id=$record->subloca_id,
								$sub_location=$record->sub_location,
							];
						}
				//		echo json_encode(['locations' => $locationData]);
						
						$this->output
						->set_content_type('application/json')
						->set_output(json_encode(['locations' => $locationData]));
				 
					//	echo json_encode(['response' => $locations]);

				}
							

				public function outsiderdailypayexcel($perms) {
					// Create a new Spreadsheet object
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$att_payschm  =$_SESSION["att_payschm"]; 
					$holget  =$_SESSION["holget"]; 
					$compid = $this->session->userdata('companyId');
				 echo $from_date;


					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->getPageMargins()->setTop(.25);
					$sheet->getPageMargins()->setRight(0.25);
					$sheet->getPageMargins()->setLeft(0.25);
					$sheet->getPageMargins()->setBottom(0.25);
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
							'size' => 12,
						],
					];
				
				
									            

					$sdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
					// Add your data to the spreadsheet
					// ...
				
					$sql="select * from company_master where comp_id=".$compid;
					$query = $this->db->query($sql);
					$results = $query->result_array();
					foreach ($results as $row) {
						$compname=$row['company_name'];
					
					}
//					$mccodes = $this->Daily_cash_outsider_payment_module->directReport($from_date,$to_date,$att_payschm,$holget,$compid);
					$mccodes = $this->Loan_adv_model->getpayregisterdata($from_date,$to_date,$att_payschm,$holget);
				//	$this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
				//$sheet->setCellValue('A1', 'Sl No');
				
				$sheet->setTitle('Summary Sheet');
				$hed1='PaySheet Summary Dated  '.$sdate.' Shift ALL'; 
				$sheet->setCellValue('A1', $hed1);
				$sheet->setCellValue('a2', 'Departments');
				$sheet->setCellValue('b2', 'No of hands');
				$sheet->setCellValue('c2', 'Wages Amount');
				$sheet->setCellValue('d2', 'Rev Wages Amount');
				$sheet->getColumnDimension('A')->setWidth(25);
				$sheet->getColumnDimension('B')->setWidth(15);
				$sheet->getColumnDimension('C')->setWidth(15);
				$sheet->getColumnDimension('d')->setWidth(15);
				$n=2;
				for ($ch = 65; $ch <= 68; $ch++) {
					$cln=chr($ch).$n;
					$sheet->getStyle($cln)->applyFromArray($borderStyle);
				
				} 	
				
				
				$dpn='';
				
				$n=2;
				$no=1;
				$sht=0;
				$dhnd=0;
				$damt=0;
				$ghnd=0;
				$gamt=0;
				$p=2;
/*	
				foreach ($mccodes as $row) {  
						if ($dpn<>$row->dept_desc) {
							if (strlen($dpn)>0) {
								$rw='Total'; 
								$cln='c'.$n;
								$sheetd->setCellValue($cln, $rw);
								$sheetd->getStyle($cln)->applyFromArray($borderStyle);
								$rw=$dhnd; 
								$cln='f'.$n;
								$sheetd->setCellValue($cln, $rw);
								$sheetd->getStyle($cln)->applyFromArray($borderStyle);
								$rw=$damt; 
								$cln='h'.$n;
								$sheetd->setCellValue($cln, $rw);
								$sheetd->getStyle($cln)->applyFromArray($borderStyle);
								$rw=$damt; 
								$cln='j'.$n;
								$sheetd->setCellValue($cln, $rw);
								$sheetd->getStyle($cln)->applyFromArray($borderStyle);
								for ($ch = 65; $ch <= 75; $ch++) {
									$cln=chr($ch).$n;
									$sheetd->getStyle($cln)->applyFromArray($borderStyle);
								} 	
								$rw=$dhnd; 
								$cln='b'.$p;
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								$rw=$damt; 
								$cln='c'.$p;
								$sheet->setCellValue($cln, $rw);
								$sheet->getStyle($cln)->applyFromArray($borderStyle);
								for ($ch = 65; $ch <= 68; $ch++) {
									$cln=chr($ch).$p;
									$sheet->getStyle($cln)->applyFromArray($borderStyle);
								
								} 	
								
								$date = date('d/M/Y');
								$n++;
								$cln='a'.$n;
								$sheetd->setCellValue($cln, 'Print On '.$date);
								$n++;
								$n++;
								$n++;
								$n++;
								$cln='a'.$n;
								$sheetd->setCellValue($cln, 'Time Keeper');
								$cln='c'.$n;
								$sheetd->setCellValue($cln, 'Shift Incharge');
								$cln='e'.$n;
								$sheetd->setCellValue($cln, 'HOD');
								$cln='f'.$n;
								$sheetd->setCellValue($cln, 'Comm Manager');
										
								$dhnd=0;
								$damt=0;
								$sheetd->getPageSetup()
								->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
								->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
								
							}
				
							$sht++;
							$sheetd = $spreadsheet->createSheet($sht);
							$sheetd->setTitle($row->dept_desc);
							$no=1;
							$n=3;
							$p++;
							$dpn=$row->dept_desc;
							$hed2='PaySheet for Department : '.$dpn.' Dated '.$sdate ;
							$sheetd->getStyle('A1:a2')->applyFromArray($boldFontStyle);
							$centerAlignment = $sheetd->getStyle('A1:a2')->getAlignment();
							$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
							$sheetd->mergeCells('A1:K1');
							$sheetd->setCellValue('a1', $hed2);
							$sheetd->setCellValue('a2', 'Sl No');
							$sheetd->setCellValue('b2', 'Remarks');
							$sheetd->setCellValue('C2', 'EB No');
							$sheetd->setCellValue('D2', 'NAME/OCCUPATION');
							$sheetd->setCellValue('E2', 'SHIFT');
							$sheetd->setCellValue('F2', 'ATT HRS');
							$sheetd->setCellValue('G2', 'RATE');
							$sheetd->setCellValue('H2', 'AMOUNT');
							$sheetd->setCellValue('I2', 'OTHERS');
							$sheetd->setCellValue('J2', 'NET PAY');
							$sheetd->setCellValue('K2', 'SIGNATURE');
							$sheet->setCellValue('A'.$p, $dpn);
				$n=2;
							for ($ch = 65; $ch <= 75; $ch++) {
								$cln=chr($ch).$n;
								$sheetd->getStyle($cln)->applyFromArray($borderStyle);
						
							} 	
						$n++;
							//	$sheet->setActiveSheetIndex(0)->setCellValue('A'.$p, $dpn);
					//	$sheet->setCellValue('A10', 'Department');
						}
						$sheetd->getColumnDimension('A')->setWidth(9);
						$sheetd->getColumnDimension('B')->setWidth(9);
						$sheetd->getColumnDimension('C')->setWidth(10);
						$sheetd->getColumnDimension('d')->setWidth(25);
						$sheetd->getColumnDimension('e')->setWidth(8);
						$sheetd->getColumnDimension('f')->setWidth(8);
						$sheetd->getColumnDimension('g')->setWidth(8);
						$sheetd->getColumnDimension('h')->setWidth(10);
						$sheetd->getColumnDimension('i')->setWidth(8);
						$sheetd->getColumnDimension('j')->setWidth(10);
						$sheetd->getColumnDimension('k')->setWidth(20);
				
				
					$cln='A'.$n;
					$sheetd->setCellValue($cln, $no);
					$rw=$row->cntloca; 
					$cln='B'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->eb_no; 
					$cln='c'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->empname; 
					$cln='d'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->shift; 
					$cln='e'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->working_hours; 
					$cln='f'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->rate; 
					$cln='g'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->amount; 
					$cln='h'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->oth_rate; 
					$cln='i'.$n;
					$sheetd->setCellValue($cln, $rw);
					$rw=$row->amount; 
					$cln='j'.$n;
					$sheetd->setCellValue($cln, $rw);
					$cln='k'.$n;
					for ($ch = 65; $ch <= 75; $ch++) {
						$cln=chr($ch).$n;
						$sheetd->getStyle($cln)->applyFromArray($borderStyle);
				
					} 	
					$sheetd->getRowDimension($n)->setRowHeight(40);
					$n++;
					$rw=$row->desig; 
					$cln='d'.$n;
					$sheetd->setCellValue($cln, $rw);
					for ($ch = 65; $ch <= 75; $ch++) {
						$cln=chr($ch).$n;
						$sheetd->getStyle($cln)->applyFromArray($borderStyle);
				
					} 	
				
					$n++;
					$no++;
					$dhnd=$dhnd+$row->working_hours/8;
					$ghnd=$ghnd+$row->working_hours/8;
					$damt=$damt+$row->amount;
					$gamt=$gamt+$row->amount;
							
					
				
				
				
			}
				$rw='Total'; 
				$cln='c'.$n;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$dhnd; 
				$cln='f'.$n;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$damt; 
				$cln='h'.$n;
				$sheet->setCellValue($cln, $rw);
				$rw=$damt; 
				$cln='k'.$n;
				$sheetd->getStyle($cln)->applyFromArray($borderStyle);
				for ($ch = 65; $ch <= 75; $ch++) {
					$cln=chr($ch).$n;
					$sheetd->getStyle($cln)->applyFromArray($borderStyle);
				
				} 	
				$sheet->getRowDimension($n)->setRowHeight(40);
				$date = date('d/M/Y');
				$n++;
				
				
				
				
				$cln='a'.$n;
				$sheetd->setCellValue($cln, 'Print On '.$date);
				$n++;
				$n++;
				$n++;
				$n++;
				$cln='a'.$n;
				$sheetd->setCellValue($cln, 'Time Keeper');
				$cln='c'.$n;
				$sheetd->setCellValue($cln, 'Shift Incharge');
				$cln='e'.$n;
				$sheetd->setCellValue($cln, 'HOD');
				$cln='f'.$n;
				$sheetd->setCellValue($cln, 'Comm Manager');
*/					
						
				
				$sheet->getPageSetup()
				->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
				->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
				
				$rw=$dhnd; 
				$cln='b'.$p;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$damt; 
				$cln='c'.$p;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				for ($ch = 65; $ch <= 68; $ch++) {
					$cln=chr($ch).$p;
					$sheet->getStyle($cln)->applyFromArray($borderStyle);
				
				} 	
				
				$p++;
				$rw='Grand Total'; 
				$cln='a'.$p;
				$sheet->setCellValue($cln, $rw);
				$rw=$ghnd; 
				$cln='b'.$p;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$gamt; 
				$cln='c'.$p;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				for ($ch = 65; $ch <= 68; $ch++) {
					$cln=chr($ch).$p;
					$sheet->getStyle($cln)->applyFromArray($borderStyle);
				
				} 	
				
				
				$date = date('d/M/Y');
				$p++;
				$cln='a'.$p;
				$sheet->setCellValue($cln, 'Print On '.$date);
				$p++;
				$p++;
				$p++;
				$p++;
				$cln='a'.$p;
				$sheet->setCellValue($cln, 'Time Keeper');
				$cln='c'.$p;
				$sheet->setCellValue($cln, 'Shift Incharge');
				$cln='e'.$p;
				$sheet->setCellValue($cln, 'HOD');
				$cln='f'.$p;
				$sheet->setCellValue($cln, 'Comm Manager');
				
				//$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
				
				
				//$sheet->getProtection()->setSheet(true);
				
				//$sheet->getProtection()->setPassword('edpemp1234');
				
				
					$filename="Payregsheet_".$sdate.".xlsx";
				
				
					// Set headers for Excel file download
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//    header('Content-Disposition: attachment;filename="payroll.xlsx"');
					header('Content-Disposition: attachment;filename="'.$filename);
					header('Cache-Control: max-age=0');
					// Clear any previous output
					ob_clean();
					// Save the Excel file to output stream
					$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
					$writer->save('php://output');
					// Terminate the script to prevent further output
					exit;
				}
			
				

				public function registerpayexcelc($perms) {
					// Create a new Spreadsheet object
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$att_payschm  =$_SESSION["att_payschm"]; 
					$holget  =$_SESSION["holget"]; 
				
					$compid = $this->session->userdata('companyId');
					if ($holget==1 || $holget==2 || $holget==7)  {
						$this->registerpayexcel($perms);						
					}
					if ($holget==3 ) {
						$this->registerpayexcelb($perms);						
					}
					if ($holget==6 ) {
						$this->cregisterpayexcelb($perms);						
					}
					if ($holget==16 ) {
						$this->dtergpayexcel($perms);						
					}
					if ($holget==29 ) {
							//echo '29';
							$this->losthrs($perms);
					}
					if ($holget==30 ) {
								//echo '29';
							$this->losthrmainvc($perms);
					}	


				}

				public function registerpayexcelbx($perms) {
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$att_payschm  =$_SESSION["att_payschm"]; 
					$holget  =$_SESSION["holget"]; 
					$eb_no=$_POST["eb_no"];
					$compid = $this->session->userdata('companyId');
					$row=3;
					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
					$name = '';
					$fdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
					$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);

					if ($att_payschm == 151) { // Use double equals (==) for comparison
						$name = 'MainPayroll';
					} else {
						$att_payschm = 125; // Corrected assignment operator
						$name = '18 Pf Voucher';
					}
				
					$filename = "Payment Register for _".'' . $name .'_'. $fdate . "To" . $tdate . ".xlsx";
					


				
				
					// Set headers for Excel file download
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//    header('Content-Disposition: attachment;filename="payroll.xlsx"');
					header('Content-Disposition: attachment;filename="'.$filename);
					header('Cache-Control: max-age=0');
					// Clear any previous output
					ob_clean();
					// Save the Excel file to output stream
					$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
					$writer->save('php://output');
					// Terminate the script to prevent further output
					exit;
				


					
				}	
				public function attendancechecklistexcel($perms) {
					// Create a new Spreadsheet object
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$holget  =$_SESSION["holget"]; 
					$eb_no=$_POST["eb_no"];
					$compid = $this->session->userdata('companyId');

					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->getPageMargins()->setTop(.25);
					$sheet->getPageMargins()->setRight(0.25);
					$sheet->getPageMargins()->setLeft(0.25);
					$sheet->getPageMargins()->setBottom(0.25);
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
							'size' => 12,
						],
					];
				
				
									            

					$fdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
					$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);
		$sql = "select * from company_master where comp_id=" . $compid;
		$query = $this->db->query($sql);
		$results = $query->result_array();
		foreach ($results as $row) {
			$compname = $row['company_name'];

		}
		//					$mccodes = $this->Daily_cash_outsider_payment_module->directReport($from_date,$to_date,$att_payschm,$holget,$compid);
		$mccodes = $this->Loan_adv_model->attendancechecklist($from_date, $to_date, $holget);

	}


				public function registerpayexcel($perms) {
					// Create a new Spreadsheet object
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$att_payschm  =$_SESSION["att_payschm"]; 
					$holget  =$_SESSION["holget"]; 
					$eb_no=$_POST["eb_no"];
					$compid = $this->session->userdata('companyId');
			//	 echo $from_date;
		//		 $eb_no = $this->input->post('eb_no');

					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					$sheet->getPageMargins()->setTop(.25);
					$sheet->getPageMargins()->setRight(0.25);
					$sheet->getPageMargins()->setLeft(0.25);
					$sheet->getPageMargins()->setBottom(0.25);
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
							'size' => 12,
						],
					];
				
				
									            

					$fdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
					$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);
					// Add your data to the spreadsheet
					// ...
				
					$sql="select * from company_master where comp_id=".$compid;
					$query = $this->db->query($sql);
					$results = $query->result_array();
					foreach ($results as $row) {
						$compname=$row['company_name'];
					
					}
//					$mccodes = $this->Daily_cash_outsider_payment_module->directReport($from_date,$to_date,$att_payschm,$holget,$compid);
					$mccodes = $this->Loan_adv_model->getpayregisterdata($from_date,$to_date,$att_payschm,$holget);
				
		//	var_dump($mccodes);
				$row=3;
				$sln=1;
				$dpc='';
				$departmentTotals = [];	
				foreach ($mccodes as $record) {
					if ($dpc != $record->department) {
						if ($dpc = $deparment) {
							// Insert the department-wise total into the appropriate row
						//	$sheet->setCellValue('A' . $row, 'Department Wise .Total')->mergeCells('A:C');
						$sheet->mergeCells('A' . $row . ':E' . $row); // Merge cells A$row:C$row
						$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
						// Get style object for the merged cell
						$style = $sheet->getStyle('A' . $row);

						// Set horizontal alignment to center
						$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						// Set font to bold
						$style->getFont()->setBold(true);


							// Add department-wise totals for each column
							// Adjust column indexes as per your data
							$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['BASIC_RATE']);
							$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['WORKING_HOURS']);
							$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['NS_HRS']);
							$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['HL_HRS']);
							$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['LS_HRS']);
							$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['STL_D']);
							$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['WRK_DAYS']);
							$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['C_WORK_DAY']);
							$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['PROD_BASIC']);
							$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['TIME_RATED_BASIC']);
							$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['FIX_BASIC']);
							$sheet->setCellValue('R' . $row, $departmentTotals[$dpc]['C_BON_ERN']);
							$sheet->setCellValue('S' . $row, $departmentTotals[$dpc]['PF_GROSS']);
							$sheet->setCellValue('T' . $row, $departmentTotals[$dpc]['DA']);
							$sheet->setCellValue('U' . $row, $departmentTotals[$dpc]['NS_AMOUNT']);
							$sheet->setCellValue('V' . $row, $departmentTotals[$dpc]['HOL_AMT']);
							$sheet->setCellValue('W' . $row, $departmentTotals[$dpc]['INCREMENTA']);
							$sheet->setCellValue('X' . $row, $departmentTotals[$dpc]['LAYOFF_WGS']);
							$sheet->setCellValue('Y' . $row, $departmentTotals[$dpc]['INCENTIVE_AMOUNT']);
							$sheet->setCellValue('Z' . $row, $departmentTotals[$dpc]['MISS_EARN']);
							$sheet->setCellValue('AA' . $row, $departmentTotals[$dpc]['STL_WGS']);
							$sheet->setCellValue('AB' . $row, $departmentTotals[$dpc]['GROSS_PAY']);
							$sheet->setCellValue('AC' . $row, $departmentTotals[$dpc]['HRA']);
							$sheet->setCellValue('AD' . $row, $departmentTotals[$dpc]['TOTAL_EARN']);
							$sheet->setCellValue('AE' . $row, $departmentTotals[$dpc]['EPF']);
							$sheet->setCellValue('AF' . $row, $departmentTotals[$dpc]['C_PF_CONT']);
							$sheet->setCellValue('AG' . $row, $departmentTotals[$dpc]['C_EPF_CONT']);
							$sheet->setCellValue('AH' . $row, $departmentTotals[$dpc]['epf_833']);
							$sheet->setCellValue('AI' . $row, $departmentTotals[$dpc]['epf_167']);
							$sheet->setCellValue('AJ' . $row, $departmentTotals[$dpc]['ESI_GROSS']);///
							$sheet->setCellValue('AK' . $row, $departmentTotals[$dpc]['ESIC']);
							$sheet->setCellValue('AL' . $row, $departmentTotals[$dpc]['P_TAX']);
							$sheet->setCellValue('AM' . $row, $departmentTotals[$dpc]['CO_LOAN']);
							$sheet->setCellValue('AN' . $row, $departmentTotals[$dpc]['CO_LOAN_BAL']);
							$sheet->setCellValue('AO' . $row, $departmentTotals[$dpc]['PUJA_ADVANCE']);
							$sheet->setCellValue('AP' . $row, $departmentTotals[$dpc]['STL_ADVANCE']);
							$sheet->setCellValue('AQ' . $row, $departmentTotals[$dpc]['TOTAL_DEDUCTION']);
							$sheet->setCellValue('AR' . $row, $departmentTotals[$dpc]['NET_PAY']);
							// Add other columns similarly
							$style = $sheet->getStyle('G' . $row . ':AR' . $row);

							// Set horizontal alignment to center
							$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
							
							// Set font to bold
							$style->getFont()->setBold(true);
							// Increment row number
							$row++;
							
						}
						// Update $dpc and reset the total for the new department
						$dpc = $record->department;
						$departmentTotals[$dpc] = [
							'BASIC_RATE' => 0,
							'WORKING_HOURS' => 0,
							'NS_HRS' => 0,
							'HL_HRS' =>0,
							'LS_HRS' =>0,
							'STL_D' =>0,
							'WRK_DAYS' =>0,
							'C_WORK_DAY' =>0,
							'PROD_BASIC' =>0,
							'TIME_RATED_BASIC' =>0,
							'FIX_BASIC' =>0,
							'C_BON_ERN' =>0,
							'PF_GROSS' =>0,
							'DA' =>0,
							'NS_AMOUNT' =>0,
							'HOL_AMT' =>0,
							'INCREMENTA' =>0,
							'LAYOFF_WGS' =>0,
							'INCENTIVE_AMOUNT' =>0,
							'MISS_EARN' =>0,
							'STL_WGS' =>0,
							'GROSS_PAY' =>0,
							'HRA' =>0,
							'TOTAL_EARN' =>0,
							'EPF' =>0,
							'C_PF_CONT' =>0,
							'C_EPF_CONT' =>0,
							'epf_833' =>0,
							'epf_167' =>0,
							'ESI_GROSS' =>0,///
							'ESIC' =>0,
							'P_TAX' =>0,
							'CO_LOAN' =>0,
							'CO_LOAN_BAL' =>0,
							'PUJA_ADVANCE' =>0,
							'STL_ADVANCE' =>0,
							'TOTAL_DEDUCTION' =>0,
							'NET_PAY' =>0,
							// Initialize other columns' totals to 0 here
						];
					}	
					  // Add the amount to the department-wise total for each column
					  /////for grand totala////////////////////////
	//$departmentTotals[99]['BASIC_RATE'] += $record->BASIC_RATE;
	$departmentTotals[99]['WORKING_HOURS'] += $record->WORKING_HOURS;
	$departmentTotals[99]['NS_HRS'] += $record->NS_HRS;
	$departmentTotals[99]['HL_HRS'] += $record->HL_HRS;
	$departmentTotals[99]['LS_HRS'] += $record->LS_HRS;
	$departmentTotals[99]['STL_D'] += $record->STL_D;
	$departmentTotals[99]['WRK_DAYS'] += $record->WRK_DAYS;
	$departmentTotals[99]['C_WORK_DAY'] += $record->C_WORK_DAY;
	$departmentTotals[99]['PROD_BASIC'] += $record->PROD_BASIC;
	$departmentTotals[99]['TIME_RATED_BASIC'] += $record->TIME_RATED_BASIC;
	$departmentTotals[99]['FIX_BASIC'] += $record->FIX_BASIC;
	$departmentTotals[99]['C_BON_ERN'] += $record->C_BON_ERN;
	$departmentTotals[99]['PF_GROSS'] += $record->PF_GROSS;
	$departmentTotals[99]['DA'] += $record->DA;
	$departmentTotals[99]['NS_AMOUNT'] += $record->NS_AMOUNT;
	$departmentTotals[99]['HOL_AMT'] += $record->HOL_AMT;
	$departmentTotals[99]['INCREMENTA'] += $record->INCREMENTA;
	$departmentTotals[99]['LAYOFF_WGS'] += $record->LAYOFF_WGS;
	$departmentTotals[99]['INCENTIVE_AMOUNT'] += $record->INCENTIVE_AMOUNT;
	$departmentTotals[99]['MISS_EARN'] += $record->MISS_EARN;
	$departmentTotals[99]['STL_WGS'] += $record->STL_WGS;
	$departmentTotals[99]['GROSS_PAY'] += $record->GROSS_PAY;//
	$departmentTotals[99]['HRA'] += $record->HRA;
	$departmentTotals[99]['TOTAL_EARN'] += $record->TOTAL_EARN;
	$departmentTotals[99]['EPF'] += $record->EPF;
	$departmentTotals[99]['C_PF_CONT'] += $record->C_PF_CONT;
	$departmentTotals[99]['C_EPF_CONT'] += $record->C_EPF_CONT;
	$departmentTotals[99]['epf_833'] += $record->epf_833;
	$departmentTotals[99]['epf_167'] += $record->epf_167;
	$departmentTotals[99]['ESI_GROSS'] += $record->ESI_GROSS;
	$departmentTotals[99]['ESIC'] += $record->ESIC;
	$departmentTotals[99]['P_TAX'] += $record->P_TAX;
	$departmentTotals[99]['CO_LOAN'] += $record->CO_LOAN;
	$departmentTotals[99]['CO_LOAN_BAL'] += $record->CO_LOAN_BAL;
	$departmentTotals[99]['PUJA_ADVANCE'] += $record->PUJA_ADVANCE;
	$departmentTotals[99]['STL_ADVANCE'] += $record->STL_ADVANCE;
	$departmentTotals[99]['TOTAL_DEDUCTION'] += $record->TOTAL_DEDUCTION;
	$departmentTotals[99]['roff'] += $record->off;
	$departmentTotals[99]['NET_PAY'] += $record->NET_PAY;
	$departmentTotals[99]['attincn'] += $record->attincn;
	$departmentTotals[99]['otpay'] += $record->otpay;
	$departmentTotals[99]['otadv'] += $record->otadv;
	$departmentTotals[99]['otnet'] += $record->otnet;

	//////////////////////
	//$departmentTotals[$dpc]['BASIC_RATE'] += $record->BASIC_RATE;
    $departmentTotals[$dpc]['WORKING_HOURS'] += $record->WORKING_HOURS;
	$departmentTotals[$dpc]['NS_HRS'] += $record->NS_HRS;
	$departmentTotals[$dpc]['HL_HRS'] += $record->HL_HRS;
	$departmentTotals[$dpc]['LS_HRS'] += $record->LS_HRS;
	$departmentTotals[$dpc]['STL_D'] += $record->STL_D;
	$departmentTotals[$dpc]['WRK_DAYS'] += $record->WRK_DAYS;
	$departmentTotals[$dpc]['C_WORK_DAY'] += $record->C_WORK_DAY;
	$departmentTotals[$dpc]['PROD_BASIC'] += $record->PROD_BASIC;
	$departmentTotals[$dpc]['TIME_RATED_BASIC'] += $record->TIME_RATED_BASIC;
	$departmentTotals[$dpc]['FIX_BASIC'] += $record->FIX_BASIC;
	$departmentTotals[$dpc]['C_BON_ERN'] += $record->C_BON_ERN;
	$departmentTotals[$dpc]['PF_GROSS'] += $record->PF_GROSS;
	$departmentTotals[$dpc]['DA'] += $record->DA;
	$departmentTotals[$dpc]['NS_AMOUNT'] += $record->NS_AMOUNT;
	$departmentTotals[$dpc]['HOL_AMT'] += $record->HOL_AMT;
	$departmentTotals[$dpc]['INCREMENTA'] += $record->INCREMENTA;
	$departmentTotals[$dpc]['LAYOFF_WGS'] += $record->LAYOFF_WGS;
	$departmentTotals[$dpc]['INCENTIVE_AMOUNT'] += $record->INCENTIVE_AMOUNT;
	$departmentTotals[$dpc]['MISS_EARN'] += $record->MISS_EARN;
	$departmentTotals[$dpc]['STL_WGS'] += $record->STL_WGS;
	$departmentTotals[$dpc]['GROSS_PAY'] += $record->GROSS_PAY;//
	$departmentTotals[$dpc]['HRA'] += $record->HRA;
	$departmentTotals[$dpc]['TOTAL_EARN'] += $record->TOTAL_EARN;
	$departmentTotals[$dpc]['EPF'] += $record->EPF;
	$departmentTotals[$dpc]['C_PF_CONT'] += $record->C_PF_CONT;
	$departmentTotals[$dpc]['C_EPF_CONT'] += $record->C_EPF_CONT;
	$departmentTotals[$dpc]['epf_833'] += $record->epf_833;
	$departmentTotals[$dpc]['epf_167'] += $record->epf_167;
	$departmentTotals[$dpc]['ESI_GROSS'] += $record->ESI_GROSS;
	$departmentTotals[$dpc]['ESIC'] += $record->ESIC;
	$departmentTotals[$dpc]['P_TAX'] += $record->P_TAX;
	$departmentTotals[$dpc]['CO_LOAN'] += $record->CO_LOAN;
	$departmentTotals[$dpc]['CO_LOAN_BAL'] += $record->CO_LOAN_BAL;
	$departmentTotals[$dpc]['PUJA_ADVANCE'] += $record->PUJA_ADVANCE;
	$departmentTotals[$dpc]['STL_ADVANCE'] += $record->STL_ADVANCE;
	$departmentTotals[$dpc]['TOTAL_DEDUCTION'] += $record->TOTAL_DEDUCTION;
	$departmentTotals[$dpc]['roff'] += $record->off;
	$departmentTotals[$dpc]['NET_PAY'] += $record->NET_PAY;
	$departmentTotals[$dpc]['attincn'] += $record->attincn;
	$departmentTotals[$dpc]['otpay'] += $record->otpay;
	$departmentTotals[$dpc]['otadv'] += $record->otadv;
	$departmentTotals[$dpc]['otnet'] += $record->otnet;
	
    // Add other columns similarly

    // Set cell values for the current record
    // This part remains unchanged from your existing code
    $sheet->setCellValue('A' . $row, $sln);
    $sheet->setCellValue('B' . $row, $record->eb_no);
	
    // Set other cell values similarly

    // Increment row number and serial number
   // $row++;
   // $sln++;

					


				$eb_no=$record->eb_no;
				$wname=$record->wname;
				$deparment=$record->department;
				$desig=$record->desig;
				$time_piece=$record->time_piece;
				$BASIC_RATE=$record->BASIC_RATE;
				$WORKING_HOURS=$record->WORKING_HOURS;
				$NS_HRS=$record->NS_HRS;
				$HL_HRS=$record->HL_HRS;
				$LS_HRS=$record->LS_HRS;
				$STL_D=$record->STL_D;
				$WRK_DAYS=$record->WRK_DAYS;
				$C_WORK_DAY=$record->C_WORK_DAY;
				$PROD_BASIC=$record->PROD_BASIC;
				$TIME_RATED_BASIC=$record->TIME_RATED_BASIC;
				$FIX_BASIC=$record->FIX_BASIC;
				$C_BON_ERN=$record->C_BON_ERN;
				$PF_GROSS=$record->PF_GROSS;
				$DA=$record->DA;
				$NS_AMOUNT=$record->NS_AMOUNT;
				$HOL_AMT=$record->HOL_AMT;
				$INCREMENTA=$record->INCREMENTA;
				$LAYOFF_WGS=$record->LAYOFF_WGS;
				$INCENTIVE_AMOUNT=$record->INCENTIVE_AMOUNT;
				$MISS_EARN=$record->MISS_EARN;
				$STL_WGS=$record->STL_WGS;
				$GROSS_PAY=$record->GROSS_PAY;
				$HRA=$record->HRA;
				$TOTAL_EARN=$record->TOTAL_EARN;
				$EPF=$record->EPF;
				$C_PF_CONT=$record->C_PF_CONT;
				$C_EPF_CONT=$record->C_EPF_CONT;
				$epf_833=$record->epf_833;
				$epf_167=$record->epf_167;
				$ESI_GROSS=$record->ESI_GROSS;///
				$ESIC=$record->ESIC;
				$P_TAX=$record->P_TAX;
				$CO_LOAN=$record->CO_LOAN;
				$CO_LOAN_BAL=$record->CO_LOAN_BAL;
				$PUJA_ADVANCE=$record->PUJA_ADVANCE;
				$STL_ADVANCE=$record->STL_ADVANCE;
				$TOTAL_DEDUCTION=$record->TOTAL_DEDUCTION;
				$NET_PAY=$record->NET_PAY;
				$attincn=$record->attincn;
				$otamt=	$record->otpay;
				$otadv=	$record->otadv;
				$otnet=	$record->otnet;
				$roff=$record->C_F;
				$sheet->setCellValue('A' . $row, $sln);
				$sheet->setCellValue('B' . $row, $eb_no);
				$sheet->setCellValue('C' . $row, $wname);
				$sheet->setCellValue('D' . $row, $deparment);
				$sheet->setCellValue('E' . $row, $desig);
				$sheet->setCellValue('F' . $row, $time_piece);
				$sheet->setCellValue('G' . $row, $BASIC_RATE);
				$sheet->setCellValue('H' . $row, $WORKING_HOURS);
				$sheet->setCellValue('I' . $row, $NS_HRS);
				$sheet->setCellValue('J' . $row, $HL_HRS);
				$sheet->setCellValue('K' . $row, $LS_HRS);
				$sheet->setCellValue('L' . $row, $STL_D);
				$sheet->setCellValue('M' . $row, $WRK_DAYS);
				$sheet->setCellValue('N' . $row, $C_WORK_DAY);
				$sheet->setCellValue('O' . $row, $PROD_BASIC);
				$sheet->setCellValue('P' . $row, $TIME_RATED_BASIC);
				$sheet->setCellValue('Q' . $row, $FIX_BASIC);
				$sheet->setCellValue('R' . $row, $C_BON_ERN);
				$sheet->setCellValue('S' . $row, $PF_GROSS);
				$sheet->setCellValue('T' . $row, $DA);
				$sheet->setCellValue('U' . $row, $NS_AMOUNT);
				$sheet->setCellValue('V' . $row, $HOL_AMT);
				$sheet->setCellValue('W' . $row, $INCREMENTA);
				$sheet->setCellValue('X' . $row, $LAYOFF_WGS);
				$sheet->setCellValue('Y' . $row, $INCENTIVE_AMOUNT);
				$sheet->setCellValue('Z' . $row, $MISS_EARN);
				$sheet->setCellValue('AA' . $row, $STL_WGS);
				$sheet->setCellValue('AB' . $row, $GROSS_PAY);
				$sheet->setCellValue('AC' . $row, $HRA);
				$sheet->setCellValue('AD' . $row, $TOTAL_EARN);
				$sheet->setCellValue('AE' . $row, $EPF);
				$sheet->setCellValue('AF' . $row, $C_PF_CONT);
				$sheet->setCellValue('AG' . $row, $C_EPF_CONT);
				$sheet->setCellValue('AH' . $row, $epf_833);
				$sheet->setCellValue('AI' . $row, $epf_167);
				$sheet->setCellValue('AJ' . $row, $ESI_GROSS);
				$sheet->setCellValue('AK' . $row, $ESIC);
				$sheet->setCellValue('AL' . $row, $P_TAX);
				$sheet->setCellValue('AM' . $row, $CO_LOAN);
				$sheet->setCellValue('AN' . $row, $CO_LOAN_BAL);
				$sheet->setCellValue('AO' . $row, $PUJA_ADVANCE);
				$sheet->setCellValue('AP' . $row, $STL_ADVANCE);
				$sheet->setCellValue('AQ' . $row, $TOTAL_DEDUCTION);
				$sheet->setCellValue('AR' . $row, $roff);
				$sheet->setCellValue('As' . $row, $NET_PAY);
				$sheet->setCellValue('At' . $row, $attincn);
				$sheet->setCellValue('Au' . $row, $otamt);
				$sheet->setCellValue('Av' . $row, $otadv);
				$sheet->setCellValue('Aw' . $row, $otnet);
				
				
				$row++;
				$sln++;
				
							//$sheet->setCellValue('A' . $row, 'Department Wise .Total');
							//$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['BASIC_RATE']);
							$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['WORKING_HOURS']);
							$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['NS_HRS']);
							$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['HL_HRS']);
							$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['LS_HRS']);
							$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['STL_D']);
							$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['WRK_DAYS']);
							$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['C_WORK_DAY']);
							$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['PROD_BASIC']);
							$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['TIME_RATED_BASIC']);
							$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['FIX_BASIC']);
							$sheet->setCellValue('R' . $row, $departmentTotals[$dpc]['C_BON_ERN']);
							$sheet->setCellValue('S' . $row, $departmentTotals[$dpc]['PF_GROSS']);
							$sheet->setCellValue('T' . $row, $departmentTotals[$dpc]['DA']);
							$sheet->setCellValue('U' . $row, $departmentTotals[$dpc]['NS_AMOUNT']);
							$sheet->setCellValue('V' . $row, $departmentTotals[$dpc]['HOL_AMT']);
							$sheet->setCellValue('W' . $row, $departmentTotals[$dpc]['INCREMENTA']);
							$sheet->setCellValue('X' . $row, $departmentTotals[$dpc]['LAYOFF_WGS']);
							$sheet->setCellValue('Y' . $row, $departmentTotals[$dpc]['INCENTIVE_AMOUNT']);
							$sheet->setCellValue('Z' . $row, $departmentTotals[$dpc]['MISS_EARN']);
							$sheet->setCellValue('AA' . $row, $departmentTotals[$dpc]['STL_WGS']);
							$sheet->setCellValue('AB' . $row, $departmentTotals[$dpc]['GROSS_PAY']);
							$sheet->setCellValue('AC' . $row, $departmentTotals[$dpc]['HRA']);
							$sheet->setCellValue('AD' . $row, $departmentTotals[$dpc]['TOTAL_EARN']);
							$sheet->setCellValue('AE' . $row, $departmentTotals[$dpc]['EPF']);
							$sheet->setCellValue('AF' . $row, $departmentTotals[$dpc]['C_PF_CONT']);
							$sheet->setCellValue('AG' . $row, $departmentTotals[$dpc]['C_EPF_CONT']);
							$sheet->setCellValue('AH' . $row, $departmentTotals[$dpc]['epf_833']);
							$sheet->setCellValue('AI' . $row, $departmentTotals[$dpc]['epf_167']);
							$sheet->setCellValue('AJ' . $row, $departmentTotals[$dpc]['ESI_GROSS']);///
							$sheet->setCellValue('AK' . $row, $departmentTotals[$dpc]['ESIC']);
							$sheet->setCellValue('AL' . $row, $departmentTotals[$dpc]['P_TAX']);
							$sheet->setCellValue('AM' . $row, $departmentTotals[$dpc]['CO_LOAN']);
							$sheet->setCellValue('AN' . $row, $departmentTotals[$dpc]['CO_LOAN_BAL']);
							$sheet->setCellValue('AO' . $row, $departmentTotals[$dpc]['PUJA_ADVANCE']);
							$sheet->setCellValue('AP' . $row, $departmentTotals[$dpc]['STL_ADVANCE']);
							$sheet->setCellValue('AQ' . $row, $departmentTotals[$dpc]['TOTAL_DEDUCTION']);
							$sheet->setCellValue('AR' . $row, $departmentTotals[$dpc]['roff']);
							$sheet->setCellValue('As' . $row, $departmentTotals[$dpc]['NET_PAY']);
							$sheet->setCellValue('At' . $row, $departmentTotals[$dpc]['attincn']);
							$sheet->setCellValue('Au' . $row, $departmentTotals[$dpc]['otpay']);
							$sheet->setCellValue('Av' . $row, $departmentTotals[$dpc]['otadv']);
							$sheet->setCellValue('Aw' . $row, $departmentTotals[$dpc]['otnet']);
				
				}	
			$row++;
					/////////// for grand/////////////////
						$sheet->mergeCells('A' . $row . ':E' . $row); // Merge cells A$row:C$row
						$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
						// Get style object for the merged cell
						$style = $sheet->getStyle('A' . $row);

						// Set horizontal alignment to center
						$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						// Set font to bold
						$style->getFont()->setBold(true);
						//	$sheet->setCellValue('G' . $row, $departmentTotals[99]['BASIC_RATE']);
							$sheet->setCellValue('H' . $row, $departmentTotals[99]['WORKING_HOURS']);
							$sheet->setCellValue('I' . $row, $departmentTotals[99]['NS_HRS']);
							$sheet->setCellValue('J' . $row, $departmentTotals[99]['HL_HRS']);
							$sheet->setCellValue('K' . $row, $departmentTotals[99]['LS_HRS']);
							$sheet->setCellValue('L' . $row, $departmentTotals[99]['STL_D']);
							$sheet->setCellValue('M' . $row, $departmentTotals[99]['WRK_DAYS']);
							$sheet->setCellValue('N' . $row, $departmentTotals[99]['C_WORK_DAY']);
							$sheet->setCellValue('O' . $row, $departmentTotals[99]['PROD_BASIC']);
							$sheet->setCellValue('P' . $row, $departmentTotals[99]['TIME_RATED_BASIC']);
							$sheet->setCellValue('Q' . $row, $departmentTotals[99]['FIX_BASIC']);
							$sheet->setCellValue('R' . $row, $departmentTotals[99]['C_BON_ERN']);
							$sheet->setCellValue('S' . $row, $departmentTotals[99]['PF_GROSS']);
							$sheet->setCellValue('T' . $row, $departmentTotals[99]['DA']);
							$sheet->setCellValue('U' . $row, $departmentTotals[99]['NS_AMOUNT']);
							$sheet->setCellValue('V' . $row, $departmentTotals[99]['HOL_AMT']);
							$sheet->setCellValue('W' . $row, $departmentTotals[99]['INCREMENTA']);
							$sheet->setCellValue('X' . $row, $departmentTotals[99]['LAYOFF_WGS']);
							$sheet->setCellValue('Y' . $row, $departmentTotals[99]['INCENTIVE_AMOUNT']);
							$sheet->setCellValue('Z' . $row, $departmentTotals[99]['MISS_EARN']);
							$sheet->setCellValue('AA' . $row, $departmentTotals[99]['STL_WGS']);
							$sheet->setCellValue('AB' . $row, $departmentTotals[99]['GROSS_PAY']);
							$sheet->setCellValue('AC' . $row, $departmentTotals[99]['HRA']);
							$sheet->setCellValue('AD' . $row, $departmentTotals[99]['TOTAL_EARN']);
							$sheet->setCellValue('AE' . $row, $departmentTotals[99]['EPF']);
							$sheet->setCellValue('AF' . $row, $departmentTotals[99]['C_PF_CONT']);
							$sheet->setCellValue('AG' . $row, $departmentTotals[99]['C_EPF_CONT']);
							$sheet->setCellValue('AH' . $row, $departmentTotals[99]['epf_833']);
							$sheet->setCellValue('AI' . $row, $departmentTotals[99]['epf_167']);
							$sheet->setCellValue('AJ' . $row, $departmentTotals[99]['ESI_GROSS']);///
							$sheet->setCellValue('AK' . $row, $departmentTotals[99]['ESIC']);
							$sheet->setCellValue('AL' . $row, $departmentTotals[99]['P_TAX']);
							$sheet->setCellValue('AM' . $row, $departmentTotals[99]['CO_LOAN']);
							$sheet->setCellValue('AN' . $row, $departmentTotals[99]['CO_LOAN_BAL']);
							$sheet->setCellValue('AO' . $row, $departmentTotals[99]['PUJA_ADVANCE']);
							$sheet->setCellValue('AP' . $row, $departmentTotals[99]['STL_ADVANCE']);
							$sheet->setCellValue('AQ' . $row, $departmentTotals[99]['TOTAL_DEDUCTION']);
							$sheet->setCellValue('AR' . $row, $departmentTotals[99]['roff']);
							$sheet->setCellValue('As' . $row, $departmentTotals[99]['NET_PAY']);
							$sheet->setCellValue('At' . $row, $departmentTotals[99]['attincn']);
							$sheet->setCellValue('Au' . $row, $departmentTotals[99]['otpay']);
							$sheet->setCellValue('Av' . $row, $departmentTotals[99]['otadv']);
							$sheet->setCellValue('Aw' . $row, $departmentTotals[99]['otnet']);
							$sheet->mergeCells('A' . $row . ':E' . $row); // Merge cells A$row:C$row
						$sheet->setCellValue('A' . $row, 'Grand Total'); // Set the value for the merged cell
						// Get style object for the merged cell
						$style = $sheet->getStyle('A' . $row . ':AR' . $row);

						// Set horizontal alignment to center
						$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						// Set font to bold
						$style->getFont()->setBold(true);
						$name = '';

						if ($att_payschm == 151) {
							$name = 'MainPayroll';
						} else {
							$att_payschm = 125;
							$name = '18 Pf Voucher';
						}
						
						// Construct the title string
						$title = "Payment Register for_" . $name . '_' . $fdate . "To" . $tdate . " ";
						
						// Set the title of the spreadsheet
				$sheet->setTitle('Summary Sheet');
				$sheet->setCellValue('A1', $title);
				$sheet->setCellValue('A2', 'Sl No');
				$sheet->setCellValue('B2', 'EB Number');
				$sheet->setCellValue('C2', 'Name');
				$sheet->setCellValue('D2', 'Department');
				$sheet->setCellValue('E2', 'Occupation');
				$sheet->setCellValue('F2', 'Time / Piece');
				$sheet->setCellValue('G2', 'Basic Rate');
				$sheet->setCellValue('H2', 'Working Hours');
				$sheet->setCellValue('I2', 'Night Hours');
				$sheet->setCellValue('J2', 'Holi Day Hrs');
				$sheet->setCellValue('K2', 'Layoff Hrs');
				$sheet->setCellValue('L2', 'STL Days');
				$sheet->setCellValue('M2', 'Work Days');
				$sheet->setCellValue('N2', 'Cumulative Work Days');
				$sheet->setCellValue('O2', 'Prod Basic');
				$sheet->setCellValue('P2', 'Time Rated Basic');
				$sheet->setCellValue('Q2', 'Fixbasic');
				$sheet->setCellValue('R2', 'Cumulative Bonus Earn');
				$sheet->setCellValue('S2', 'PF.Gross');
				$sheet->setCellValue('T2', 'DA.Amount');
				$sheet->setCellValue('U2', 'NS.Amount');
				$sheet->setCellValue('V2', 'Holi Day Amount');
				$sheet->setCellValue('W2', 'Increment Amount');
				$sheet->setCellValue('X2', 'Layoff Amount');
				$sheet->setCellValue('Y2', 'Incentive Amount');
				$sheet->setCellValue('Z2', 'Miss Earning');
				$sheet->setCellValue('AA2', 'STL.Wages');
				$sheet->setCellValue('AB2', 'Gross Pay');
				$sheet->setCellValue('AC2', 'HRA');
				$sheet->setCellValue('AD2', 'Total Earning');
				$sheet->setCellValue('AE2', 'PF Amount');
				$sheet->setCellValue('AF2', 'C PF CONT');
				$sheet->setCellValue('AG2', 'C EPF CONT');
				$sheet->setCellValue('AH2', 'EPF 8.33%');
				$sheet->setCellValue('AI2', 'EPF 1.67%');
				$sheet->setCellValue('AJ2', 'ESI Gross');
				$sheet->setCellValue('AK2', 'ESI Amount');
				$sheet->setCellValue('AL2', 'P.Tax');
				$sheet->setCellValue('AM2', 'Comp.Loan');
				$sheet->setCellValue('AN2', 'Comp.Loan Bal');
				$sheet->setCellValue('AO2', 'Puja Advance');
				$sheet->setCellValue('AP2', 'STL Advance');
				$sheet->setCellValue('AQ2', 'T.Deducion');
				$sheet->setCellValue('AR2', 'R/Off');
				$sheet->setCellValue('As2', 'Net Pay');
				$sheet->setCellValue('At2', 'Att Incn');
				$sheet->setCellValue('Au2', 'OT Amt');
				$sheet->setCellValue('Av2', 'OT Adv');
				$sheet->setCellValue('Aw2', 'OT Net');
				// Adjust column widths for all columns
				for ($col = 'A'; $col <= 'Aw'; $col++) {

  				 $sheet->getColumnDimension($col)->setWidth(25);
	//				$sheet->getColumnDimension($col)->setAutoSize(true);
				}
				$sheet->getStyle('G2:Aw2')->getAlignment()->setWrapText(true);

				$sheet->getColumnDimension('A')->setWidth(15);
				$sheet->getColumnDimension('B')->setWidth(15);
				$sheet->getColumnDimension('C')->setWidth(15);
				$sheet->getColumnDimension('d')->setWidth(15);
				
			
			
				$dpn='';
				
									
				
				$sheet->getPageSetup()
				->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
				->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
				
				
					
				
				$date = date('d/M/Y');
								
				//$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
				
				
				//$sheet->getProtection()->setSheet(true);
				
				//$sheet->getProtection()->setPassword('edpemp1234');
				
				
					//$filename="Payregsheet_".$sdate.".xlsx";
					$name = '';

					if ($att_payschm == 151) { // Use double equals (==) for comparison
						$name = 'MainPayroll';
					} else {
						$att_payschm = 125; // Corrected assignment operator
						$name = '18 Pf Voucher';
					}
				
					$filename = "Payment Register for _".'' . $name .'_'. $fdate . "To" . $tdate . ".xlsx";
					


				
				
					// Set headers for Excel file download
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//    header('Content-Disposition: attachment;filename="payroll.xlsx"');
					header('Content-Disposition: attachment;filename="'.$filename);
					header('Cache-Control: max-age=0');
					// Clear any previous output
					ob_clean();
					// Save the Excel file to output stream
					$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
					$writer->save('php://output');
					// Terminate the script to prevent further output
					exit;
				}
							
	
				public function registerpayexcelb($perms) {
					$from_date  =$_SESSION["fromdate"]; 
					$to_date  =$_SESSION["todate"]; 
					$att_payschm  =$_SESSION["att_payschm"]; 
					$holget  =$_SESSION["holget"]; 
					$eb_no=$_POST["eb_no"];
					$compid = $this->session->userdata('companyId');
					$row=3;
					$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
				//	$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
					$name = '';
					$fdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
					$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);

					$mccodes = $this->Loan_adv_model->getpayregisterdata($from_date,$to_date,$att_payschm,$holget);
				
					//	var_dump($mccodes);
							$row=3;
							$sln=1;
							$dpc='';
							$departmentTotals = [];	
				foreach ($mccodes as $record) {
					if ($dpc != $record->departments) {
						if ($dpc = $departments) {
							// Insert the department-wise total into the appropriate row
						//	$sheet->setCellValue('A' . $row, 'Department Wise .Total')->mergeCells('A:C');
						$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
						$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
						// Get style object for the merged cell
						$style = $sheet->getStyle('A' . $row);

						// Set horizontal alignment to center
						$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
						// Set font to bold
						$style->getFont()->setBold(true);


							// Add department-wise totals for each column
							// Adjust column indexes as per your data
							$sheet->setCellValue('E' . $row, $departmentTotals[$dpc]['working_hrs']);
							$sheet->setCellValue('F' . $row, $departmentTotals[$dpc]['hol_hrs']);
							$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['hol_wgs']);
							$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['arrear_plus']);
							$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['arrear_minus']);
							$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['total_earn']);
							$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['esi']);
							$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['exadvance']);
							$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['gross_deduction']);
							$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['netpay']);
							$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['c_ot_hrs']);
							$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['ot_advance']);
							$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['ot_net_amount']);
							
							// Add other columns similarly
							$style = $sheet->getStyle('E' . $row . ':Q' . $row);

							// Set horizontal alignment to center
							$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
							
							// Set font to bold
							$style->getFont()->setBold(true);
							// Increment row number
							$row++;
							
						}
						// Update $dpc and reset the total for the new department
						$dpc = $record->departments;
						$departmentTotals[$dpc] = [
							'working_hrs' => 0,
							'hol_hrs' => 0,
							'hol_wgs' =>0,
							'arrear_plus' =>0,
							'arrear_minus' =>0,
							'total_earn' =>0,
							'esi' =>0,
							'exadvance' =>0,
							'gross_deduction' =>0,
							'netpay' =>0,
							'c_ot_hrs' =>0,
							'ot_advance' =>0,
							'ot_net_amount' =>0,
							// Initialize other columns' totals to 0 here
						];
					}	
								  // Add the amount to the department-wise total for each column
								  /////for grand totala////////////////////////
				//$departmentTotals[99]['BASIC_RATE'] += $record->BASIC_RATE;
				$departmentTotals[99]['working_hrs'] += $record->working_hrs;
				$departmentTotals[99]['hol_hrs'] += $record->hol_hrs;
				$departmentTotals[99]['hol_wgs'] += $record->hol_wgs;
				$departmentTotals[99]['arrear_plus'] += $record->arrear_plus;
				$departmentTotals[99]['arrear_minus'] += $record->arrear_minus;
				$departmentTotals[99]['total_earn'] += $record->total_earn;
				$departmentTotals[99]['esi'] += $record->esi;
				$departmentTotals[99]['exadvance'] += $record->exadvance;
				$departmentTotals[99]['gross_deduction'] += $record->gross_deduction;
				$departmentTotals[99]['netpay'] += $record->netpay;
				$departmentTotals[99]['c_ot_hrs'] += $record->c_ot_hrs;
				$departmentTotals[99]['ot_advance'] += $record->ot_advance;
				$departmentTotals[99]['ot_net_amount'] += $record->ot_net_amount;
				
			
				//////////////////////
				//$departmentTotals[$dpc]['BASIC_RATE'] += $record->BASIC_RATE;
				$departmentTotals[$dpc]['working_hrs'] += $record->working_hrs;
				$departmentTotals[$dpc]['hol_hrs'] += $record->hol_hrs;
				$departmentTotals[$dpc]['hol_wgs'] += $record->hol_wgs;
				$departmentTotals[$dpc]['arrear_plus'] += $record->arrear_plus;
				$departmentTotals[$dpc]['arrear_minus'] += $record->arrear_minus;
				$departmentTotals[$dpc]['total_earn'] += $record->total_earn;
				$departmentTotals[$dpc]['esi'] += $record->esi;
				$departmentTotals[$dpc]['exadvance'] += $record->exadvance;
				$departmentTotals[$dpc]['gross_deduction'] += $record->gross_deduction;
				$departmentTotals[$dpc]['netpay'] += $record->netpay;
				$departmentTotals[$dpc]['c_ot_hrs'] += $record->c_ot_hrs;
				$departmentTotals[$dpc]['ot_advance'] += $record->ot_advance;
				$departmentTotals[$dpc]['ot_net_amount'] += $record->ot_net_amount;
				
				
				// Add other columns similarly
			
				// Set cell values for the current record
				// This part remains unchanged from your existing code
				$sheet->setCellValue('A' . $row, $sln);
				$sheet->setCellValue('B' . $row, $record->eb_no);
				$sheet->setCellValue('C' . $row, $record->wname);
				$sheet->setCellValue('D' . $row, $record->departments);
				$sheet->setCellValue('E' . $row, $record->working_hrs);
				$sheet->setCellValue('F' . $row, $record->hol_hrs);
				$sheet->setCellValue('G' . $row, $record->hol_wgs);
				$sheet->setCellValue('H' . $row, $record->arrear_plus);
				$sheet->setCellValue('I' . $row, $record->arrear_minus);
				$sheet->setCellValue('J' . $row, $record->total_earn);
				$sheet->setCellValue('K' . $row, $record->esi);
				$sheet->setCellValue('L' . $row, $record->exadvance);
				$sheet->setCellValue('M' . $row, $record->gross_deduction);
				$sheet->setCellValue('N' . $row, $record->netpay);
				$sheet->setCellValue('O' . $row, $record->c_ot_hrs);
				$sheet->setCellValue('P' . $row, $record->ot_advance);
				$sheet->setCellValue('Q' . $row, $record->ot_net_amount);
				$sheet->setCellValue('r' . $row, $record->rate_per_day);
				
				// Set other cell values similarly
			
				// Increment row number and serial number
			  //  $row++;
			   // $sln++;
			
								
			
			
							$eb_no=$record->eb_no;
							$wname=$record->wname;
							$departments=$record->departments;
							$working_hrs=$record->working_hrs;
							$hol_hrs=$record->hol_hrs;
							$hol_wgs=$record->hol_wgs;
							$arrear_plus=$record->arrear_plus;
							$arrear_minus=$record->arrear_minus;
							$total_earn=$record->total_earn;
							$esi=$record->esi;
							$exadvance=$record->exadvance;
							$gross_deduction=$record->gross_deduction;
							$netpay=$record->netpay;
							$c_ot_hrs=$record->c_ot_hrs;
							$ot_advance=$record->ot_advance;
							$ot_net_amount=$record->ot_net_amount;
							
							
							$sheet->setCellValue('A' . $row, $sln);
							$sheet->setCellValue('B' . $row, $eb_no);
							$sheet->setCellValue('C' . $row, $wname);
							$sheet->setCellValue('D' . $row, $departments);
							$sheet->setCellValue('E' . $row, $working_hrs);
							$sheet->setCellValue('F' . $row, $hol_hrs);
							$sheet->setCellValue('G' . $row, $hol_wgs);
							$sheet->setCellValue('H' . $row, $arrear_plus);
							$sheet->setCellValue('I' . $row, $arrear_minus);
							$sheet->setCellValue('J' . $row, $total_earn);
							$sheet->setCellValue('K' . $row, $esi);
							$sheet->setCellValue('L' . $row, $exadvance);
							$sheet->setCellValue('M' . $row, $gross_deduction);
							$sheet->setCellValue('N' . $row, $netpay);
							$sheet->setCellValue('O' . $row, $c_ot_hrs);
							$sheet->setCellValue('P' . $row, $ot_advance);
							$sheet->setCellValue('Q' . $row, $ot_net_amount);
							
							
							
							$row++;
							$sln++;
							
							$sheet->setCellValue('E' . $row, $departmentTotals[$dpc]['working_hrs']);
							$sheet->setCellValue('F' . $row, $departmentTotals[$dpc]['hol_hrs']);
							$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['hol_wgs']);
							$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['arrear_plus']);
							$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['arrear_minus']);
							$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['total_earn']);
							$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['esi']);
							$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['exadvance']);
							$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['gross_deduction']);
							$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['netpay']);
							$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['c_ot_hrs']);
							$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['ot_advance']);
							$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['ot_net_amount']);
							
							
							// Add other columns similarly
							$style = $sheet->getStyle('E' . $row . ':Q' . $row);

							// Set horizontal alignment to center
							$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
							
							// Set font to bold
							$style->getFont()->setBold(true);
										
							
							}	
							$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
							$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
							// Get style object for the merged cell
							$style = $sheet->getStyle('A' . $row);
							$style->getFont()->setBold(true);
						$row++;
								/////////// for grand/////////////////
								/*	$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
									$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
									// Get style object for the merged cell
									$style = $sheet->getStyle('A' . $row);*/
			
									// Set horizontal alignment to center
									$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									// Set font to bold
									$style->getFont()->setBold(true);
									//	$sheet->setCellValue('G' . $row, $departmentTotals[99]['BASIC_RATE']);
										$sheet->setCellValue('E' . $row, $departmentTotals[99]['working_hrs']);
										$sheet->setCellValue('F' . $row, $departmentTotals[99]['hol_hrs']);
										$sheet->setCellValue('G' . $row, $departmentTotals[99]['hol_wgs']);
										$sheet->setCellValue('H' . $row, $departmentTotals[99]['arrear_plus']);
										$sheet->setCellValue('I' . $row, $departmentTotals[99]['arrear_minus']);
										$sheet->setCellValue('J' . $row, $departmentTotals[99]['total_earn']);
										$sheet->setCellValue('K' . $row, $departmentTotals[99]['esi']);
										$sheet->setCellValue('L' . $row, $departmentTotals[99]['exadvance']);
										$sheet->setCellValue('M' . $row, $departmentTotals[99]['gross_deduction']);
										$sheet->setCellValue('N' . $row, $departmentTotals[99]['netpay']);
										$sheet->setCellValue('O' . $row, $departmentTotals[99]['c_ot_hrs']);
										$sheet->setCellValue('P' . $row, $departmentTotals[99]['ot_advance']);
										$sheet->setCellValue('Q' . $row, $departmentTotals[99]['ot_net_amount']);
										
										
										
										
									
										
										$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
									$sheet->setCellValue('A' . $row, 'Grand Total'); // Set the value for the merged cell
									// Get style object for the merged cell
									$style = $sheet->getStyle('A' . $row . ':AR' . $row);
			
									// Set horizontal alignment to center
									$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
									// Set font to bold
									$style->getFont()->setBold(true);

					if ($att_payschm == 151) { // Use double equals (==) for comparison
						$name = 'MainPayroll';
					} else {
						$att_payschm = 161; // Corrected assignment operator
						$name = 'Mill Retired  Voucher';
					}
					$title = "Payment Register for_" . $name . '_' . $fdate . "To" . $tdate . " ";
						
					// Set the title of the spreadsheet
			$sheet->setTitle('Summary Sheet');
			$sheet->setCellValue('A1', $title);
			$sheet->setCellValue('A2', 'SL No');
			$sheet->setCellValue('B2', 'EB Number');
			$sheet->setCellValue('C2', 'Name');
			$sheet->setCellValue('D2', 'Department');
			$sheet->setCellValue('E2', 'Working Hours');
			$sheet->setCellValue('F2', 'Holi Day Hrs');
			$sheet->setCellValue('G2', 'Holi Day Amount');
			$sheet->setCellValue('H2', 'Miss Earning');
			$sheet->setCellValue('I2', 'Miss Deducion');
			$sheet->setCellValue('J2', 'Total Earning');
			$sheet->setCellValue('K2', 'ESI Amount');
			$sheet->setCellValue('L2', 'Advance');
			$sheet->setCellValue('M2', 'T.Deducion');
			$sheet->setCellValue('N2', 'Net Pay');
			$sheet->setCellValue('O2', 'Ot Hours');
			$sheet->setCellValue('P2', 'Ot Advance');
			$sheet->setCellValue('Q2', 'Ot Amount');
			$sheet->setCellValue('r2', 'Rate');
			// Adjust column widths for all columns
			for ($col = 'A'; $col <= 'Av'; $col++) {
				$sheet->getColumnDimension($col)->setWidth(15);
			}

			$sheet->getColumnDimension('A')->setWidth(25);
			$sheet->getColumnDimension('B')->setWidth(15);
			$sheet->getColumnDimension('C')->setWidth(15);
			$sheet->getColumnDimension('d')->setWidth(15);
			$filename = "Payment Register for _".'' . $name .'_'. $fdate . "To" . $tdate . ".xlsx";
					


				
				
					// Set headers for Excel file download
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				//    header('Content-Disposition: attachment;filename="payroll.xlsx"');
					header('Content-Disposition: attachment;filename="'.$filename);
					header('Cache-Control: max-age=0');
					// Clear any previous output
					ob_clean();
					// Save the Excel file to output stream
					$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
					$writer->save('php://output');
					// Terminate the script to prevent further output
					exit;
				

				}	
				
	//////////// START date range  for Contractor WAGES EXCEL//////////////////////////
public function dtergpayexcel($perms) {
    // Retrieve session variables
    $from_date = $_SESSION["fromdate"];
    $to_date = $_SESSION["todate"];
    $att_payschm = $_SESSION["att_payschm"];
    $holget = $_SESSION["holget"];
    $eb_no = $_POST["eb_no"];
    $compid = $this->session->userdata('companyId');
    
    // Prepare date formats
    $fdate = substr($from_date, 8, 2) . '-' . substr($from_date, 5, 2) . '-' . substr($from_date, 0, 4);
    $tdate = substr($to_date, 8, 2) . '-' . substr($to_date, 5, 2) . '-' . substr($to_date, 0, 4);
    
    // Initialize PhpSpreadsheet objects
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    
    // Fetch data from the model
    $mccodes = $this->Loan_adv_model->getpayregisterdata($from_date, $to_date, $att_payschm, $holget);
    
    // Initialize variables
    $row = 2;
    $sln = 0;
    $departmentTotals = [];
    
    // Loop through records and populate the Excel sheet
    foreach ($mccodes as $record) {
        $departmentTotals[99]['WORKING_HOURS'] += $record->WORKING_HOURS;
        $departmentTotals[99]['NIGHT_SHIFT_HR'] += $record->NIGHT_SHIFT_HR;
        $departmentTotals[99]['HOLIDAY_HR'] += $record->HOLIDAY_HR;
        $departmentTotals[99]['OT_HOURS'] += $record->OT_HOURS;
        $departmentTotals[99]['BASIC'] += $record->BASIC;
        $departmentTotals[99]['OVERTIME_PAY'] += $record->OVERTIME_PAY;
        $departmentTotals[99]['OTHER_ALLOWANCE'] += $record->OTHER_ALLOWANCE;
        $departmentTotals[99]['TIFFIN_AMOUNT'] += $record->TIFFIN_AMOUNT;
        $departmentTotals[99]['CONV_ALLOWANCE'] += $record->CONV_ALLOWANCE;
        $departmentTotals[99]['WASHING_ALLOWANCE'] += $record->WASHING_ALLOWANCE;
        $departmentTotals[99]['GROSS2'] += $record->GROSS2;
        $departmentTotals[99]['EPF'] += $record->EPF;
        $departmentTotals[99]['ESI'] += $record->ESI;
        $departmentTotals[99]['ADVANCE'] += $record->ADVANCE;
        $departmentTotals[99]['TOTAL_EARN'] += $record->TOTAL_EARN;
        $departmentTotals[99]['GROSS_DED'] += $record->GROSS_DED;
        $departmentTotals[99]['Net_Payble'] += $record->Net_Payble;
        
        $row++;
        $sln++;

        // Set cell values for each record
        $sheet->setCellValue('A' . $row, $sln);
        $sheet->setCellValue('B' . $row, $record->EB_NO);
        $sheet->setCellValue('C' . $row, $record->wname);
        $sheet->setCellValue('D' . $row, $record->WORKING_HOURS);
        $sheet->setCellValue('E' . $row, $record->NIGHT_SHIFT_HR);
        $sheet->setCellValue('F' . $row, $record->HOLIDAY_HR);
        $sheet->setCellValue('G' . $row, $record->OT_HOURS);
        $sheet->setCellValue('H' . $row, $record->BASIC);
        $sheet->setCellValue('I' . $row, $record->OVERTIME_PAY);
        $sheet->setCellValue('J' . $row, $record->OTHER_ALLOWANCE);
        $sheet->setCellValue('K' . $row, $record->TIFFIN_AMOUNT);
        $sheet->setCellValue('L' . $row, $record->CONV_ALLOWANCE);
        $sheet->setCellValue('M' . $row, $record->WASHING_ALLOWANCE);
        $sheet->setCellValue('N' . $row, $record->GROSS2);
        $sheet->setCellValue('O' . $row, $record->EPF);
        $sheet->setCellValue('P' . $row, $record->ESI);
        $sheet->setCellValue('Q' . $row, $record->ADVANCE);
        $sheet->setCellValue('R' . $row, $record->TOTAL_EARN);
        $sheet->setCellValue('S' . $row, $record->GROSS_DED);
        $sheet->setCellValue('T' . $row, $record->Net_Payble);
        
        // Apply style to cells
        $style = $sheet->getStyle('D' . $row . ':T' . $row);
        $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    }
    
    // Add grand total row
    $row++;
    $sheet->setCellValue('D' . $row, $departmentTotals[99]['WORKING_HOURS']);
    $sheet->setCellValue('E' . $row, $departmentTotals[99]['NIGHT_SHIFT_HR']);
    $sheet->setCellValue('F' . $row, $departmentTotals[99]['HOLIDAY_HR']);
    $sheet->setCellValue('G' . $row, $departmentTotals[99]['OT_HOURS']);
    $sheet->setCellValue('H' . $row, $departmentTotals[99]['BASIC']);
    $sheet->setCellValue('I' . $row, $departmentTotals[99]['OVERTIME_PAY']);
    $sheet->setCellValue('J' . $row, $departmentTotals[99]['OTHER_ALLOWANCE']);
    $sheet->setCellValue('K' . $row, $departmentTotals[99]['TIFFIN_AMOUNT']);
    $sheet->setCellValue('L' . $row, $departmentTotals[99]['CONV_ALLOWANCE']);
    $sheet->setCellValue('M' . $row, $departmentTotals[99]['WASHING_ALLOWANCE']);
    $sheet->setCellValue('N' . $row, $departmentTotals[99]['GROSS2']);
    $sheet->setCellValue('O' . $row, $departmentTotals[99]['EPF']);
    $sheet->setCellValue('P' . $row, $departmentTotals[99]['ESI']);
    $sheet->setCellValue('Q' . $row, $departmentTotals[99]['ADVANCE']);
    $sheet->setCellValue('R' . $row, $departmentTotals[99]['TOTAL_EARN']);
    $sheet->setCellValue('S' . $row, $departmentTotals[99]['GROSS_DED']);
    $sheet->setCellValue('T' . $row, $departmentTotals[99]['Net_Payble']);
    
    // Merge cells for grand total label
    $sheet->mergeCells('A' . $row . ':C' . $row);
    $sheet->setCellValue('A' . $row, 'Grand Total');
    
    // Apply style to grand total row
    $style = $sheet->getStyle('A' . $row . ':T' . $row);
    $style->getFont()->setBold(true);
    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	if ($att_payschm == 158) {
        $name = '(3)';
    } elseif ($att_payschm == 159) {
        $name = '(1)';
    } else {
        $att_payschm = 160;
        $name = '(2)';
    }
    
    // Set sheet title
    $sheet->setTitle('Summary Sheet');
    $sheet->setCellValue('A1', "Payment Register for_" . $name . '_' . $fdate . "_To_" . $tdate);
    
    // Set header titles
    $sheet->setCellValue('A2', 'Sl No');
    $sheet->setCellValue('B2', 'EB Number');
    $sheet->setCellValue('C2', 'Name');
    $sheet->setCellValue('D2', 'Working Hours');
    $sheet->setCellValue('E2', 'Night Hours');
    $sheet->setCellValue('F2', 'Festival Hours');
    $sheet->setCellValue('G2', 'Overtime Hours');
    $sheet->setCellValue('H2', 'Basic Amount');
    $sheet->setCellValue('I2', 'OT.Amount');
    $sheet->setCellValue('J2', 'Other Allowance');
    $sheet->setCellValue('K2', 'Tiffin Allowance');
    $sheet->setCellValue('L2', 'Convance Allowance');
    $sheet->setCellValue('M2', 'Washing Allowance');
    $sheet->setCellValue('N2', 'Gross Amount');
    $sheet->setCellValue('O2', 'PF-12%');
    $sheet->setCellValue('P2', 'ESI-0.75%');
    $sheet->setCellValue('Q2', 'Advance Amount');
    $sheet->setCellValue('R2', 'Total Earning');
    $sheet->setCellValue('S2', 'Total Deduction');
    $sheet->setCellValue('T2', 'Net Payment');
    
    // Apply style to header row
    $headerRange = 'A2:T2';
    $style = $sheet->getStyle($headerRange);
    $style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    $style->getFont()->setBold(true);
    
    // Adjust column widths
    foreach (range('A', 'T') as $col) {
        $sheet->getColumnDimension($col)->setWidth(15);
    }
    
    // Set specific column widths
    $sheet->getColumnDimension('A')->setWidth(15);
    $sheet->getColumnDimension('B')->setWidth(15);
    $sheet->getColumnDimension('C')->setWidth(15);
    $sheet->getColumnDimension('D')->setWidth(15);
    
    // Determine file name based on pay scheme
    if ($att_payschm == 158) {
        $name = '(3)';
    } elseif ($att_payschm == 159) {
        $name = '(1)';
    } else {
        $att_payschm = 160;
        $name = '(2)';
    }
    
    $filename = "Payment Register for _" . $name . '_' . $fdate . "_To_" . $tdate . ".xlsx";
    
    // Set headers for file download
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="' . $filename . '"');
    header('Cache-Control: max-age=0');
    
    // Clear output buffer
    ob_clean();
    
    // Save the file to the output stream
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save('php://output');
    
    // Terminate the script
    exit;
}
			
public function cregisterpayexcelb($perms) {
	$from_date  =$_SESSION["fromdate"]; 
	$to_date  =$_SESSION["todate"]; 
	$att_payschm  =$_SESSION["att_payschm"]; 
	$holget  =$_SESSION["holget"]; 
	$eb_no=$_POST["eb_no"];
	$compid = $this->session->userdata('companyId');
	$row=3;
	$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
	$sheet = $spreadsheet->getActiveSheet();
	$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
	$name = '';
	$fdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
	$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);
	$mccodes = $this->Loan_adv_model->getpayregisterdata($from_date,$to_date,$att_payschm,$holget);
////////////// contract
	//	var_dump($mccodes);
	$row=3;
	$sln=1;
	$dpc='';
	$departmentTotals = [];	
foreach ($mccodes as $record) {
if ($dpc != $record->department) {
if ($dpc = $department) {
	// Insert the department-wise total into the appropriate row
//	$sheet->setCellValue('A' . $row, 'Department Wise .Total')->mergeCells('A:C');
$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
// Get style object for the merged cell
$style = $sheet->getStyle('A' . $row);

// Set horizontal alignment to center
$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
// Set font to bold
$style->getFont()->setBold(true);


	// Add department-wise totals for each column
	// Adjust column indexes as per your data

	$sheet->setCellValue('E' . $row, $departmentTotals[$dpc]['WORKING_HOURS']);
	$sheet->setCellValue('F' . $row, $departmentTotals[$dpc]['NIGHT_SHIFT_HR']);
	$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['HOLIDAY_HR']);
	$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['OT_HOURS']);
	$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['RATE_PER_DAY']);
	$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['BASIC']);
	$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['OTHER_ALLOWANCE']);
	$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['TIFFIN_AMOUNT']);
	$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['CONV_ALLOWANCE']);
	$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['WASHING_ALLOWANCE']);
	$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['GROSS2']);					
	$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['EPF']);
	$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['ESI']);
	$sheet->setCellValue('R' . $row, $departmentTotals[$dpc]['ARR_PLUS']);
	$sheet->setCellValue('S' . $row, $departmentTotals[$dpc]['P_TAX']);
	$sheet->setCellValue('T' . $row, $departmentTotals[$dpc]['ADVANCE']);
	$sheet->setCellValue('U' . $row, $departmentTotals[$dpc]['TOTAL_EARN']);
	$sheet->setCellValue('V' . $row, $departmentTotals[$dpc]['Festival_Wage']);
	$sheet->setCellValue('W' . $row, $departmentTotals[$dpc]['GROSS_DED']);
	$sheet->setCellValue('X' . $row, $departmentTotals[$dpc]['Net_Payble']);
	
	// Add other columns similarly
	$style = $sheet->getStyle('E' . $row . ':X' . $row);

	// Set horizontal alignment to center
	$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	// Set font to bold
	$style->getFont()->setBold(true);
	// Increment row number
	$row++;
	
}
// Update $dpc and reset the total for the new department
$dpc = $record->department;
$departmentTotals[$dpc] = [
	'WORKING_HOURS' => 0,
	'NIGHT_SHIFT_HR' => 0,
	'HOLIDAY_HR' =>0,
	'OT_HOURS' =>0,
	'RATE_PER_DAY' =>0,
	'BASIC' =>0,
	'OTHER_ALLOWANCE' =>0,
	'TIFFIN_AMOUNT' =>0,
	'CONV_ALLOWANCE' =>0,
	'WASHING_ALLOWANCE' =>0,
	'GROSS2' =>0,
	'EPF' =>0,
	'ESI' =>0,
	'ARR_PLUS' =>0,
	'P_TAX' =>0,
	'ADVANCE' =>0,
	'TOTAL_EARN' =>0,
	'Festival_Wage' =>0,
	'GROSS_DED' =>0,
	'Net_Payble' =>0,

	
	// Initialize other columns' totals to 0 here
];
}	
		  // Add the amount to the department-wise total for each column
		  /////for grand totala////////////////////////
//$departmentTotals[99]['BASIC_RATE'] += $record->BASIC_RATE;
$departmentTotals[99]['WORKING_HOURS'] += $record->WORKING_HOURS;
$departmentTotals[99]['NIGHT_SHIFT_HR'] += $record->NIGHT_SHIFT_HR;
$departmentTotals[99]['HOLIDAY_HR'] += $record->HOLIDAY_HR;
$departmentTotals[99]['OT_HOURS'] += $record->OT_HOURS;
$departmentTotals[99]['RATE_PER_DAY'] += $record->RATE_PER_DAY;
$departmentTotals[99]['BASIC'] += $record->BASIC;
$departmentTotals[99]['OTHER_ALLOWANCE'] += $record->OTHER_ALLOWANCE;
$departmentTotals[99]['TIFFIN_AMOUNT'] += $record->TIFFIN_AMOUNT;
$departmentTotals[99]['CONV_ALLOWANCE'] += $record->CONV_ALLOWANCE;
$departmentTotals[99]['WASHING_ALLOWANCE'] += $record->WASHING_ALLOWANCE;
$departmentTotals[99]['GROSS2'] += $record->GROSS2;
$departmentTotals[99]['EPF'] += $record->EPF;
$departmentTotals[99]['ESI'] += $record->ESI;
$departmentTotals[99]['ARR_PLUS'] += $record->ARR_PLUS;
$departmentTotals[99]['P_TAX'] += $record->P_TAX;
$departmentTotals[99]['ADVANCE'] += $record->ADVANCE;
$departmentTotals[99]['TOTAL_EARN'] += $record->TOTAL_EARN;
$departmentTotals[99]['Festival_Wage'] += $record->Festival_Wage;
$departmentTotals[99]['GROSS_DED'] += $record->GROSS_DED;
$departmentTotals[99]['Net_Payble'] += $record->Net_Payble;



//////////////////////
//$departmentTotals[$dpc]['BASIC_RATE'] += $record->BASIC_RATE;
$departmentTotals[$dpc]['WORKING_HOURS'] += $record->WORKING_HOURS;
$departmentTotals[$dpc]['NIGHT_SHIFT_HR'] += $record->NIGHT_SHIFT_HR;
$departmentTotals[$dpc]['HOLIDAY_HR'] += $record->HOLIDAY_HR;
$departmentTotals[$dpc]['OT_HOURS'] += $record->OT_HOURS;
$departmentTotals[$dpc]['RATE_PER_DAY'] += $record->RATE_PER_DAY;
$departmentTotals[$dpc]['BASIC'] += $record->BASIC;
$departmentTotals[$dpc]['OTHER_ALLOWANCE'] += $record->OTHER_ALLOWANCE;
$departmentTotals[$dpc]['TIFFIN_AMOUNT'] += $record->TIFFIN_AMOUNT;
$departmentTotals[$dpc]['CONV_ALLOWANCE'] += $record->CONV_ALLOWANCE;
$departmentTotals[$dpc]['WASHING_ALLOWANCE'] += $record->WASHING_ALLOWANCE;
$departmentTotals[$dpc]['GROSS2'] += $record->GROSS2;
$departmentTotals[$dpc]['EPF'] += $record->EPF;
$departmentTotals[$dpc]['ARR_PLUS'] += $record->ARR_PLUS;
$departmentTotals[$dpc]['P_TAX'] += $record->P_TAX;
$departmentTotals[$dpc]['ADVANCE'] += $record->ADVANCE;
$departmentTotals[$dpc]['TOTAL_EARN'] += $record->TOTAL_EARN;
$departmentTotals[$dpc]['Festival_Wage'] += $record->Festival_Wage;
$departmentTotals[$dpc]['GROSS_DED'] += $record->GROSS_DED;
$departmentTotals[$dpc]['Net_Payble'] += $record->Net_Payble;




// Add other columns similarly

// Set cell values for the current record
// This part remains unchanged from your existing code

$sheet->setCellValue('A' . $row, $record->$sln);
$sheet->setCellValue('B' . $row, $record->$eb_no);
$sheet->setCellValue('C' . $row, $record->$wname);
$sheet->setCellValue('D' . $row, $record->$department);
$sheet->setCellValue('E' . $row, $record->$WORKING_HOURS);
$sheet->setCellValue('F' . $row, $record->$NIGHT_SHIFT_HR);
$sheet->setCellValue('G' . $row, $record->$HOLIDAY_HR);
$sheet->setCellValue('H' . $row, $record->$OT_HOURS);
$sheet->setCellValue('I' . $row, $record->$RATE_PER_DAY);
$sheet->setCellValue('J' . $row, $record->$BASIC);
$sheet->setCellValue('K' . $row, $record->$OTHER_ALLOWANCE);
$sheet->setCellValue('L' . $row, $record->$TIFFIN_AMOUNT);
$sheet->setCellValue('M' . $row, $record->$CONV_ALLOWANCE);
$sheet->setCellValue('N' . $row, $record->$WASHING_ALLOWANCE);
$sheet->setCellValue('O' . $row, $record->$GROSS2);
$sheet->setCellValue('P' . $row, $record->$EPF);
$sheet->setCellValue('Q' . $row, $record->$ESI);
$sheet->setCellValue('R' . $row, $record->$ARR_PLUS);
$sheet->setCellValue('S' . $row, $record->$P_TAX);
$sheet->setCellValue('T' . $row, $record->$ADVANCE);
$sheet->setCellValue('U' . $row, $record->$TOTAL_EARN);
$sheet->setCellValue('V' . $row, $record->$Festival_Wage);
$sheet->setCellValue('W' . $row, $record->$GROSS_DED);
$sheet->setCellValue('X' . $row, $record->$Net_Payble);	

// Set other cell values similarly

// Increment row number and serial number
//  $row++;
// $sln++;

		


	$eb_no=$record->eb_no;
	$wname=$record->wname;
	$department=$record->department;
	$WORKING_HOURS=$record->WORKING_HOURS;
	$NIGHT_SHIFT_HR=$record->NIGHT_SHIFT_HR;
	$HOLIDAY_HR=$record->HOLIDAY_HR;
	$OT_HOURS=$record->OT_HOURS;
	$RATE_PER_DAY=$record->RATE_PER_DAY;
	$BASIC=$record->BASIC;
	$OTHER_ALLOWANCE=$record->OTHER_ALLOWANCE;
	$TIFFIN_AMOUNT=$record->TIFFIN_AMOUNT;
	$CONV_ALLOWANCE=$record->CONV_ALLOWANCE;
	$WASHING_ALLOWANCE=$record->WASHING_ALLOWANCE;
	$GROSS2=$record->GROSS2;
	$EPF=$record->EPF;
	$ESI=$record->ESI;
	$ARR_PLUS=$record->ARR_PLUS;
	$P_TAX=$record->P_TAX;
	$ADVANCE=$record->ADVANCE;
	$TOTAL_EARN=$record->TOTAL_EARN;
	$Festival_Wage=$record->Festival_Wage;
	$GROSS_DED=$record->GROSS_DED;
	$Net_Payble=$record->Net_Payble;
	
	
	$sheet->setCellValue('A' . $row, $sln);
	$sheet->setCellValue('B' . $row, $eb_no);
	$sheet->setCellValue('C' . $row, $wname);
	$sheet->setCellValue('D' . $row, $department);
	$sheet->setCellValue('E' . $row, $WORKING_HOURS);
	$sheet->setCellValue('F' . $row, $NIGHT_SHIFT_HR);
	$sheet->setCellValue('G' . $row, $HOLIDAY_HR);
	$sheet->setCellValue('H' . $row, $OT_HOURS);
	$sheet->setCellValue('I' . $row, $RATE_PER_DAY);
	$sheet->setCellValue('J' . $row, $BASIC);
	$sheet->setCellValue('K' . $row, $OTHER_ALLOWANCE);
	$sheet->setCellValue('L' . $row, $TIFFIN_AMOUNT);
	$sheet->setCellValue('M' . $row, $CONV_ALLOWANCE);
	$sheet->setCellValue('N' . $row, $WASHING_ALLOWANCE);
	$sheet->setCellValue('O' . $row, $GROSS2);
	$sheet->setCellValue('P' . $row, $EPF);
	$sheet->setCellValue('Q' . $row, $ESI);
	$sheet->setCellValue('R' . $row, $ARR_PLUS);
	$sheet->setCellValue('S' . $row, $P_TAX);
	$sheet->setCellValue('T' . $row, $ADVANCE);
	$sheet->setCellValue('U' . $row, $TOTAL_EARN);
	$sheet->setCellValue('V' . $row, $Festival_Wage);
	$sheet->setCellValue('W' . $row, $GROSS_DED);
	$sheet->setCellValue('X' . $row, $Net_Payble);
	
	
	
	
	$row++;
	$sln++;
	
	$sheet->setCellValue('E' . $row, $departmentTotals[$dpc]['WORKING_HOURS']);
	$sheet->setCellValue('F' . $row, $departmentTotals[$dpc]['NIGHT_SHIFT_HR']);
	$sheet->setCellValue('G' . $row, $departmentTotals[$dpc]['HOLIDAY_HR']);
	$sheet->setCellValue('H' . $row, $departmentTotals[$dpc]['OT_HOURS']);
	$sheet->setCellValue('I' . $row, $departmentTotals[$dpc]['RATE_PER_DAY']);
	$sheet->setCellValue('J' . $row, $departmentTotals[$dpc]['BASIC']);
	$sheet->setCellValue('K' . $row, $departmentTotals[$dpc]['OTHER_ALLOWANCE']);
	$sheet->setCellValue('L' . $row, $departmentTotals[$dpc]['TIFFIN_AMOUNT']);
	$sheet->setCellValue('M' . $row, $departmentTotals[$dpc]['CONV_ALLOWANCE']);
	$sheet->setCellValue('N' . $row, $departmentTotals[$dpc]['WASHING_ALLOWANCE']);
	$sheet->setCellValue('O' . $row, $departmentTotals[$dpc]['GROSS2']);					
	$sheet->setCellValue('P' . $row, $departmentTotals[$dpc]['EPF']);
	$sheet->setCellValue('Q' . $row, $departmentTotals[$dpc]['ESI']);
	$sheet->setCellValue('R' . $row, $departmentTotals[$dpc]['ARR_PLUS']);
	$sheet->setCellValue('S' . $row, $departmentTotals[$dpc]['P_TAX']);
	$sheet->setCellValue('T' . $row, $departmentTotals[$dpc]['ADVANCE']);
	$sheet->setCellValue('U' . $row, $departmentTotals[$dpc]['TOTAL_EARN']);
	$sheet->setCellValue('V' . $row, $departmentTotals[$dpc]['Festival_Wage']);
	$sheet->setCellValue('W' . $row, $departmentTotals[$dpc]['GROSS_DED']);
	$sheet->setCellValue('X' . $row, $departmentTotals[$dpc]['Net_Payble']);

	
	
	// Add other columns similarly
	$style = $sheet->getStyle('E' . $row . ':X' . $row);

	// Set horizontal alignment to center
	$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	// Set font to bold
//	$style->getFont()->setBold(true);
				
	
	}	
	$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
	$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
	// Get style object for the merged cell
	$style = $sheet->getStyle('A' . $row);
	$style->getFont()->setBold(true);
$row++;
		/////////// for grand/////////////////
		/*	$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
			$sheet->setCellValue('A' . $row, 'Department Wise Total'); // Set the value for the merged cell
			// Get style object for the merged cell
			$style = $sheet->getStyle('A' . $row);*/

			// Set horizontal alignment to center
			$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			// Set font to bold
			$style->getFont()->setBold(true);
			//	$sheet->setCellValue('G' . $row, $departmentTotals[99]['BASIC_RATE']);
			$sheet->setCellValue('E' . $row, $departmentTotals[99]['WORKING_HOURS']);
			$sheet->setCellValue('F' . $row, $departmentTotals[99]['NIGHT_SHIFT_HR']);
			$sheet->setCellValue('G' . $row, $departmentTotals[99]['HOLIDAY_HR']);
			$sheet->setCellValue('H' . $row, $departmentTotals[99]['OT_HOURS']);
			$sheet->setCellValue('I' . $row, $departmentTotals[99]['RATE_PER_DAY']);
			$sheet->setCellValue('J' . $row, $departmentTotals[99]['BASIC']);
			$sheet->setCellValue('K' . $row, $departmentTotals[99]['OTHER_ALLOWANCE']);
			$sheet->setCellValue('L' . $row, $departmentTotals[99]['TIFFIN_AMOUNT']);
			$sheet->setCellValue('M' . $row, $departmentTotals[99]['CONV_ALLOWANCE']);
			$sheet->setCellValue('N' . $row, $departmentTotals[99]['WASHING_ALLOWANCE']);
			$sheet->setCellValue('O' . $row, $departmentTotals[99]['GROSS2']);
			$sheet->setCellValue('P' . $row, $departmentTotals[99]['EPF']);
			$sheet->setCellValue('Q' . $row, $departmentTotals[99]['ESI']);
			$sheet->setCellValue('R' . $row, $departmentTotals[99]['ARR_PLUS']);
			$sheet->setCellValue('S' . $row, $departmentTotals[99]['P_TAX']);
			$sheet->setCellValue('T' . $row, $departmentTotals[99]['ADVANCE']);
			$sheet->setCellValue('U' . $row, $departmentTotals[99]['TOTAL_EARN']);
			$sheet->setCellValue('V' . $row, $departmentTotals[99]['Festival_Wage']);
			$sheet->setCellValue('W' . $row, $departmentTotals[99]['GROSS_DED']);
			$sheet->setCellValue('X' . $row, $departmentTotals[99]['Net_Payble']);
			 
		
			
				
				$sheet->mergeCells('A' . $row . ':D' . $row); // Merge cells A$row:C$row
			$sheet->setCellValue('A' . $row, 'Grand Total'); // Set the value for the merged cell
			// Get style object for the merged cell
			$style = $sheet->getStyle('E' . $row . ':X' . $row);

			// Set horizontal alignment to center
			$style->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			// Set font to bold
			$style->getFont()->setBold(true);

			if ($att_payschm == 158) { // Use double equals (==) for comparison
				$name = '(3)';
			} elseif ($att_payschm == 159) { // Use elseif instead of another else
				$name = '(1)';
			} else {
				$att_payschm = 160; // Corrected assignment operator
				$name = '(2)';
			}
			
$title = "Payment Register for_" . $name . '_' . $fdate . "_To_" . $tdate . " ";

// Set the title of the spreadsheet
$sheet->setTitle('Summary Sheet');
$sheet->setCellValue('A1', $title);
$sheet->setCellValue('A2', 'Sl No');
$sheet->setCellValue('B2', 'EB Number');
$sheet->setCellValue('C2', 'Name');
$sheet->setCellValue('D2', 'Department');
$sheet->setCellValue('E2', 'Working Hours');
$sheet->setCellValue('F2', 'Night Allowance');
$sheet->setCellValue('G2', 'Festival Hrs');
$sheet->setCellValue('H2', 'Ot Hours');
$sheet->setCellValue('I2', 'Rate');
$sheet->setCellValue('J2', 'Basic');
$sheet->setCellValue('K2', 'Other Allowance');
$sheet->setCellValue('L2', 'Tiffin Allowance');
$sheet->setCellValue('M2', 'Convance Allowance');
$sheet->setCellValue('N2', 'Washing Allowance');
$sheet->setCellValue('O2', 'Gross_2');
$sheet->setCellValue('P2', 'PF-12%');
$sheet->setCellValue('Q2', 'ESI-0.75%');
$sheet->setCellValue('R2', 'Adjustment Amt');
$sheet->setCellValue('S2', 'P.Tax');
$sheet->setCellValue('T2', 'Advance');
$sheet->setCellValue('U2', 'Total Earning');
$sheet->setCellValue('V2', 'Festival Wages');
$sheet->setCellValue('W2', 'Total Deducion');
$sheet->setCellValue('X2', 'Net Payment');
//$sheet->setCellValue('Y2', 'Net Payment');
// Adjust column widths for all columns
for ($col = 'A'; $col <= 'Av'; $col++) {
$sheet->getColumnDimension($col)->setWidth(15);
}

$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(15);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('d')->setWidth(15);
$filename = "Payment Register for _".'' . $name .'_'. $fdate . "_To_" . $tdate . ".xlsx";





// Set headers for Excel file download
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
//    header('Content-Disposition: attachment;filename="payroll.xlsx"');
header('Content-Disposition: attachment;filename="'.$filename);
header('Cache-Control: max-age=0');
// Clear any previous output
ob_clean();
// Save the Excel file to output stream
$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
$writer->save('php://output');
// Terminate the script to prevent further output
exit;


}

///////////  Start Date Range Excel Reports for Mainpayroll & 18-PF Voucher ///////////////////////				
				// Function to generate specific Excel report
				public function losthrmainvc($perms) {
					$periodfromdate= $this->input->post('periodfromdate');
					$periodtodate= $this->input->post('periodtodate');
					$att_payschm =  $this->input->post('att_payschm');
					$periodfromdate = $_SESSION["periodfromdate"];
					$periodtodate = $_SESSION["periodtodate"];
					$from_date = $_SESSION["fromdate"];
					$to_date = $_SESSION["todate"];
					$att_payschm = $_SESSION["att_payschm"];
					$holget = $_SESSION["holget"];
					$eb_no = $_POST["eb_no"];
					$compid = $this->session->userdata('companyId');
					
					$fdate = substr($from_date, 8, 2) . '-' . substr($from_date, 5, 2) . '-' . substr($from_date, 0, 4);
					$tdate = substr($to_date, 8, 2) . '-' . substr($to_date, 5, 2) . '-' . substr($to_date, 0, 4);
					
					$spreadsheet = new Spreadsheet();
					$sheet = $spreadsheet->getActiveSheet();
					
					// Fetch data from the model (assuming you have a method in Loan_adv_model)
					$mccodes = $this->Loan_adv_model2-> getmainvcpayrollexceldrg($from_date,$to_date,$att_payschm,$holget);
					
					$row = 2;
					$sln = 0;
					$departmentTotals = [
						99 => [
							'WORKING_HOURS' => 0,
							'HL_HRS' => 0,
							'NS_HRS' => 0,
							'STL_D' => 0,
							'TIME_RATED_BASIC' => 0,
							'PROD_BASIC' => 0,
							'DA' => 0,
							'ADVANCE' => 0,
							'HOL_AMT' => 0,
							'NS_AMOUNT' => 0,
							'HRA' => 0,
							'STL_WGS' => 0,
							'PF_GROSS' => 0,
							'EPF' => 0,
							'ESI_GROSS' => 0,
							'ESIC' => 0,
							'P_TAX' => 0,
							'MISS_EARNING' =>0,
							'Misc_deduction' =>0,
							'ADVANCE' => 0,
							'TOTAL_EARNING' => 0,
							'GROSS_DED' => 0,
							'Round_off'=>0,
							'NET_PAY' => 0,
							'OT_HOURS' => 0,
							'OT_ADVANCE' => 0,
							'OVERTIME_PAY' => 0,
							'ot_net_amount' =>0,
							'INCENTIVE_AMOUNT' => 0,
							'TOTAL_AMT' => 0
						]
					];
					
					foreach ($mccodes as $record) {
						$departmentTotals[99]['WORKING_HOURS'] += $record->WORKING_HOURS;
						$departmentTotals[99]['HL_HRS'] += $record->HL_HRS;
						$departmentTotals[99]['NS_HRS'] += $record->NS_HRS;
						$departmentTotals[99]['STL_D'] += $record->STL_D;
						$departmentTotals[99]['TIME_RATED_BASIC'] += $record->TIME_RATED_BASIC;
						$departmentTotals[99]['PROD_BASIC'] += $record->PROD_BASIC;
						$departmentTotals[99]['DA'] += $record->DA;
						$departmentTotals[99]['HOL_AMT'] += $record->HOL_AMT;
						$departmentTotals[99]['NS_AMOUNT'] += $record->NS_AMOUNT;
						$departmentTotals[99]['HRA'] += $record->HRA;
						$departmentTotals[99]['STL_WGS'] += $record->STL_WGS;
						$departmentTotals[99]['PF_GROSS'] += $record->PF_GROSS;
						$departmentTotals[99]['EPF'] += $record->EPF;
						$departmentTotals[99]['ESI_GROSS'] += $record->ESI_GROSS;
						$departmentTotals[99]['ESIC'] += $record->ESIC;
						$departmentTotals[99]['P_TAX'] += $record->P_TAX;
						$departmentTotals[99]['MISS_EARNING'] += $record->MISS_EARNING;
						$departmentTotals[99]['Misc_deduction'] += $record->Misc_deduction;
						$departmentTotals[99]['ADVANCE'] += $record->ADVANCE;
						$departmentTotals[99]['TOTAL_EARNING'] += $record->TOTAL_EARNING;
						$departmentTotals[99]['GROSS_DED'] += $record->GROSS_DED;
						$departmentTotals[99]['Round_off'] += $record->Round_off;
						$departmentTotals[99]['NET_PAY'] += $record->NET_PAY;
						$departmentTotals[99]['OT_HOURS'] += $record->OT_HOURS;
						$departmentTotals[99]['OT_ADVANCE'] += $record->OT_ADVANCE;
						$departmentTotals[99]['OVERTIME_PAY'] += $record->OVERTIME_PAY;
						$departmentTotals[99]['ot_net_amount'] += $record->ot_net_amount;
						$departmentTotals[99]['INCENTIVE_AMOUNT'] += $record->INCENTIVE_AMOUNT;
						$departmentTotals[99]['TOTAL_AMT'] += $record->TOTAL_AMT;
						
						$row++;
						$sln++;
				
						$sheet->setCellValue('A' . $row, $sln);
						$sheet->setCellValue('B' . $row, $record->EB_NO);
						$sheet->setCellValue('C' . $row, $record->wname);
						$sheet->setCellValue('D' . $row, $record->WORKING_HOURS);
						$sheet->setCellValue('E' . $row, $record->HL_HRS);
						$sheet->setCellValue('F' . $row, $record->NS_HRS);
						$sheet->setCellValue('G' . $row, $record->STL_D);
						$sheet->setCellValue('H' . $row, $record->TIME_RATED_BASIC);
						$sheet->setCellValue('I' . $row, $record->PROD_BASIC);
						$sheet->setCellValue('J' . $row, $record->DA);
						$sheet->setCellValue('K' . $row, $record->HOL_AMT);
						$sheet->setCellValue('L' . $row, $record->NS_AMOUNT);
						$sheet->setCellValue('M' . $row, $record->HRA);
						$sheet->setCellValue('N' . $row, $record->STL_WGS);
						$sheet->setCellValue('O' . $row, $record->PF_GROSS);
						$sheet->setCellValue('P' . $row, $record->EPF);
						$sheet->setCellValue('Q' . $row, $record->ESI_GROSS);
						$sheet->setCellValue('R' . $row, $record->ESIC);
						$sheet->setCellValue('S' . $row, $record->P_TAX);
						$sheet->setCellValue('T' . $row, $record->MISS_EARNING);
						$sheet->setCellValue('U' . $row, $record->Misc_deduction);
						$sheet->setCellValue('V' . $row, $record->ADVANCE);
						$sheet->setCellValue('W' . $row, $record->TOTAL_EARNING);
						$sheet->setCellValue('X' . $row, $record->GROSS_DED);
						$sheet->setCellValue('Y' . $row, $record->Round_off);
						$sheet->setCellValue('Z' . $row, $record->NET_PAY);
						$sheet->setCellValue('AA' . $row, $record->OT_HOURS);
						$sheet->setCellValue('AB' . $row, $record->OT_ADVANCE);
						$sheet->setCellValue('AC' . $row, $record->OVERTIME_PAY);
						$sheet->setCellValue('AD' . $row, $record->ot_net_amount);
						$sheet->setCellValue('AE' . $row, $record->INCENTIVE_AMOUNT);
						$sheet->setCellValue('AF' . $row, $record->TOTAL_AMT);
						
						
				
						$style = $sheet->getStyle('D' . $row . ':AF' . $row);
						$style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					}
					
					$row++;
					$sheet->setCellValue('D' . $row, $departmentTotals[99]['WORKING_HOURS']);
					$sheet->setCellValue('E' . $row, $departmentTotals[99]['HL_HRS']);
					$sheet->setCellValue('F' . $row, $departmentTotals[99]['NS_HRS']);
					$sheet->setCellValue('G' . $row, $departmentTotals[99]['STL_D']);
					$sheet->setCellValue('H' . $row, $departmentTotals[99]['TIME_RATED_BASIC']);
					$sheet->setCellValue('I' . $row, $departmentTotals[99]['PROD_BASIC']);
					$sheet->setCellValue('J' . $row, $departmentTotals[99]['DA']);
					$sheet->setCellValue('K' . $row, $departmentTotals[99]['HOL_AMT']);
					$sheet->setCellValue('L' . $row, $departmentTotals[99]['NS_AMOUNT']);
					$sheet->setCellValue('M' . $row, $departmentTotals[99]['HRA']);
					$sheet->setCellValue('N' . $row, $departmentTotals[99]['STL_WGS']);
					$sheet->setCellValue('O' . $row, $departmentTotals[99]['PF_GROSS']);
					$sheet->setCellValue('P' . $row, $departmentTotals[99]['EPF']);
					$sheet->setCellValue('Q' . $row, $departmentTotals[99]['ESI_GROSS']);
					$sheet->setCellValue('R' . $row, $departmentTotals[99]['ESIC']);
					$sheet->setCellValue('S' . $row, $departmentTotals[99]['P_TAX']);
					$sheet->setCellValue('T' . $row, $departmentTotals[99]['MISS_EARNING']);
					$sheet->setCellValue('U' . $row, $departmentTotals[99]['Misc_deduction']);
					$sheet->setCellValue('V' . $row, $departmentTotals[99]['ADVANCE']);
					$sheet->setCellValue('W' . $row, $departmentTotals[99]['TOTAL_EARNING']);
					$sheet->setCellValue('X' . $row, $departmentTotals[99]['GROSS_DED']);
					$sheet->setCellValue('Y' . $row, $departmentTotals[99]['Round_off']);
					$sheet->setCellValue('Z' . $row, $departmentTotals[99]['NET_PAY']);
					$sheet->setCellValue('AA' . $row, $departmentTotals[99]['OT_HOURS']);
					$sheet->setCellValue('AB' . $row, $departmentTotals[99]['OT_ADVANCE']);
					$sheet->setCellValue('AC' . $row, $departmentTotals[99]['OVERTIME_PAY']);
					$sheet->setCellValue('AD' . $row, $departmentTotals[99]['ot_net_amount']);
					$sheet->setCellValue('AE' . $row, $departmentTotals[99]['INCENTIVE_AMOUNT']);
					$sheet->setCellValue('AF' . $row, $departmentTotals[99]['TOTAL_AMT']);
				
					
					$sheet->mergeCells('A' . $row . ':C' . $row);
					$sheet->setCellValue('A' . $row, 'Grand Total');
					
					$style = $sheet->getStyle('A' . $row . ':AF' . $row);
					$style->getFont()->setBold(true);
					$style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					
					switch ($att_payschm) {
						case 125:
							$name = '18-PF Voucher';
							break;
						case 151:
							$name = 'Mainpayroll';
							break;
						default:
							$att_payschm = 10;
							$name = 'No Data';
							break;
					}
					
					$sheet->setTitle('Summary Sheet');
					$sheet->setCellValue('A1', "Payment Register for_" . $name . '_' . $fdate . "_To_" . $tdate);
					$sheet->setCellValue('A2', 'Sl No');
					$sheet->setCellValue('B2', 'EB No');
					$sheet->setCellValue('C2', 'Name');
					$sheet->setCellValue('D2', 'Working Hours');
					$sheet->setCellValue('E2', 'Festival Hours');
					$sheet->setCellValue('F2', 'N.S Hours');
					$sheet->setCellValue('G2', 'STL Days');
					$sheet->setCellValue('H2', 'Time Basic');
					$sheet->setCellValue('I2', 'Prod Basic');
					$sheet->setCellValue('J2', 'D.A Amount');
					$sheet->setCellValue('K2', 'Holiday Amount');
					$sheet->setCellValue('L2', 'N.S Amount');
					$sheet->setCellValue('M2', 'H.R.A Amount');
					$sheet->setCellValue('N2', 'STL Amount');
					$sheet->setCellValue('O2', 'PF Gross');
					$sheet->setCellValue('P2', 'P.F Amount');
					$sheet->setCellValue('Q2', 'ESI Gross');
					$sheet->setCellValue('R2', 'E.S.I Amount');
					$sheet->setCellValue('S2', 'P.Tax');
					$sheet->setCellValue('T2', 'Misscellaneous Earning');
					$sheet->setCellValue('U2', 'Misscellaneous Deduction');
					$sheet->setCellValue('V2', 'Advance Amount');
					$sheet->setCellValue('W2', 'Total Earnings');
					$sheet->setCellValue('X2', 'Gross Deduction');
					$sheet->setCellValue('Y2', 'Round Off');
					$sheet->setCellValue('Z2', 'Net Payment');
					$sheet->setCellValue('AA2', 'OT Hours');
					$sheet->setCellValue('AB2', 'OT Advance');
					$sheet->setCellValue('AC2', 'OT Amount');
					$sheet->setCellValue('AD2', 'OT Net payment');
					$sheet->setCellValue('AE2', 'Attendane Incentive');
					$sheet->setCellValue('AF2', 'Total Amount');
					
					$headerRange = 'A2:AF2';
					$style = $sheet->getStyle($headerRange);
					$style->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
					$style->getFont()->setBold(true);
					
					foreach (range('A', 'K') as $col) {
						$sheet->getColumnDimension($col)->setWidth(15);
					}
					
					$sheet->getColumnDimension('A')->setWidth(25);
					$sheet->getColumnDimension('B')->setWidth(15);
					$sheet->getColumnDimension('C')->setWidth(15);
					$sheet->getColumnDimension('D')->setWidth(15);
					
					$filename = "Payment_Register_for_" . $name . '_' . $fdate . "_To_" . $tdate . ".xlsx";
					
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
					header('Content-Disposition: attachment;filename="' . $filename . '"');
					header('Cache-Control: max-age=0');
					
					ob_clean();
					
					$writer = new Xlsx($spreadsheet);
					$writer->save('php://output');
					
					exit;
				}


///////////  End Date Range Excel Reports for Mainpayroll & 18-PF Voucher ///////////////////////				



		}
