<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Conditional;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Border;     
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\PageMargins;

require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\IOFactory;

use Mpdf\Mpdf;

class Data_entry_2 extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		
		$this->load->model('Data_entry_update2_model');
		$this->load->model('Loan_adv_model2');	

		
		
		
		ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
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
				}else{
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
					$No_of_installment=$record->eb_id
 					
				 	
					
				];
			}
			echo json_encode(['data' => $data]);
    }


	public function stdhandsprocessdata() {
		$rec_time =  date('Y-m-d H:i:s');
		$comp = $this->session->userdata('companyId');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
		$holget =  $this->input->post('holget');

	    $stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');

		$mcc=$this->Data_entry_update2_model->updatestdhandsData($periodfromdate,$periodtodate ,$holget);
		$data = [];
			foreach ($mcc as $record) {
				$succ=$record->succes;
			}		
			if ($succ==0) {
				$succ='1st Close All Attendance';
			} else {
				$succ='Data Processed Successfully';
			}	

			$response = array(
		'success' => true,
		'savedata'=> $succ
	);
	
		echo json_encode($response);
	
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
					$lvday=$record->incdays,
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

 
 
 
	public function tnofetch_data() {
        $tnocode = $this->input->post('tnocode');
		$comp = $this->session->userdata('companyId');
    	$frm='';
		$sql="select eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
		cash_rate,twst.subloca_id ,twst.daily_others_pay_duration 
		from worker_master wm 
		left join EMPMILL12.tbl_worker_sublocation_table twst on wm.eb_id=twst.eb_id  
		where eb_no='".$tnocode."' AND company_id =".$comp;	

		$bwt=0;
		$query1=$this->db->query($sql);
		if ( $query1->num_rows()>0 ) {
			 

			$row1 = $query1->row();
			$tnoname=$row1->wname;
			$tnorate=$row1->cash_rate;
			$paydue=$row1->daily_others_pay_duration;
			$subloca=$row1->subloca_id;
		
			
 		}
 
$response = array(
	'success' => true,
	'tnoname' => $tnoname,
	'tnorate' => $tnorate,
	'paydue' => $paydue,
	'subloca' => $subloca,
	
 
);


        echo json_encode($response);
    }

	public function nwdfetch_data() {
        $nwdfromdt = $this->input->post('nwdfromdt');
		$comp = $this->session->userdata('companyId');
    	$frm='';
		$sql="select * from EMPMILL12.tbl_non_working_days    
		where non_working_date='".$nwdfromdt."' AND company_id =".$comp;	

		$bwt=0;
		$query1=$this->db->query($sql);
		if ( $query1->num_rows()>0 ) {
			$row1 = $query1->row();
			 
			$payact=$row1->is_active;
			$offday=$row1->offday_leave;
		} else {  
			$payact=0;
			$offday=1;
			
 		}
 
$response = array(
	'success' => true,
	'payact' => $payact,
	'offday' => $offday,
 	
 
);


        echo json_encode($response);
    }


	public function tnodataupdate() {
		$tnocode = $this->input->post('tnocode');
		$tnorate = $this->input->post('tnorate');
		$paydue =  $this->input->post('paydue');
		$payduep =  $this->input->post('pay_due_daily');

	//	$paydue = isset($paydue) ? 'T' : ' ';
		
	
		$sub_location =  $this->input->post('sub_location');
		$comp = $this->session->userdata('companyId');
		$sql="select eb_id from worker_master where eb_no='".$tnocode."' and company_id=".$comp;
 			$q = $this->db->query($sql);
				foreach($q->result() as $row){
					$ebid = $row->eb_id;
				}		

 		$rec_time =  date('Y-m-d H:i:s');
		$stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
		
		$data = array(
			'cash_rate' => $tnorate 
			

		);
		$this->db->where('eb_id', $ebid);
		$this->db->update('worker_master', $data);

		//			$this->db->insert('EMPMILL12.tbl_loan_advance_table', $data);
		$sql="select * from EMPMILL12.tbl_worker_sublocation_table where eb_id=".$ebid;
 			$q = $this->db->query($sql);
			 if ( $q->num_rows()>0 ) {
				$data = array(
					'subloca_id' => $sub_location, 
					'daily_others_pay_duration' => $paydue 
				);
				$this->db->where('eb_id', $ebid);
				$this->db->update('EMPMILL12.tbl_worker_sublocation_table', $data);
					
	
			}	else {
				$data = array(

					'eb_id' => $ebid, 
					'subloca_id' => $sub_location, 
					'daily_others_pay_duration' => $paydue 
				);
				$this->db->insert('EMPMILL12.tbl_worker_sublocation_table', $data);
			}


$response = array(
		'success' => true,
		'savedata'=> 'saved',
		'paydue'=>$payduep
	);
	
		echo json_encode($response);
	
	}


	public function nwddataupdate() {
		$nwddate = $this->input->post('nwddate');
		$Sourceoff = $this->input->post('Sourceoff');
		$payact =  $this->input->post('payact');
 
		$comp = $this->session->userdata('companyId');
		$sql="select * from EMPMILL12.tbl_non_working_days where non_working_date='".$nwddate."' 
		and company_id=".$comp;
 			$q = $this->db->query($sql);
				foreach($q->result() as $row){
					$ebid = $row->non_working_days_id;
				}		

 		$rec_time =  date('Y-m-d H:i:s');
		$stat=3;
		$active=1;
		$userid=$this->session->userdata('userid');
		
 
 			$q = $this->db->query($sql);
			 if ( $ebid>0 ) {
				$data = array(
					'is_active' => $payact, 
					'offday_leave' => $Sourceoff 
				);
				$this->db->where('non_working_days_id', $ebid);
				$this->db->update('EMPMILL12.tbl_non_working_days', $data);
					
	
			}	else {
				$data = array(

					'company_id' => $comp, 
					'is_active' => $payact, 
					'offday_leave' => $Sourceoff, 
					'non_working_date' => $nwddate 
						
				);
				$this->db->insert('EMPMILL12.tbl_non_working_days', $data);
			}


$response = array(
		'success' => true,
		'savedata'=> 'saved',
		'paydue'=>$payact
	);
	
		echo json_encode($response);
	
	}
	 

		 
	 
		 
	public function trollydatafill() {
		$comp = $this->session->userdata('companyId');
		$mccodes = $this->Loan_adv_model2->trollydatafill($comp);
		$locationData = [];
				foreach ($mccodes as $record) {
					
					$locationData[] = [
						$trollyid=$record->trollyid,
						$trollyno=$record->trollyno,
					];
				}
				$this->output
				->set_content_type('application/json')
				->set_output(json_encode(['locations' => $locationData]));
		 
		}
					

	
		public function gettrollydata() {
			$frmid = $this->input->post('frameid');
			$comp = $this->session->userdata('companyId');
			$mccodes = $this->Loan_adv_model2->gettrollydata($comp,$frmid);
			$cnt=count($mccodes);
			$data = [];
			if ($cnt>0) {
				foreach ($mccodes as $record) {
					$data[] = [
						'basket_weight' => $record->basket_weight,
						'trolly_weight' => $record->trolly_weight,    // Use array notation instead of object property
					];
				}
		}
			// Return the response
		//	echo json_encode(['data' => $data]);
		echo json_encode(['success' => true, 'data' => $data]);	
	}

		
	public function updatetrollydata() {
		$frmid = $this->input->post('frameid');
		$bwt = $this->input->post('bwt');
		$twt= $this->input->post('twt');
		$comp = $this->session->userdata('companyId');
		$mccodes = $this->Loan_adv_model2->updatetrollydata($comp,$frmid,$bwt,$twt);
 		echo json_encode(['success' => true]);	
}
		 
		 

/////stldet
public function stldetdownloaddata() {
	$periodfromdate= $this->input->get('periodfromdate');
	$periodtodate= $this->input->get('periodtodate');
	$holget =  $this->input->get('holget');
		 $company_name = $this->session->userdata('companyname');
		 $comp = $this->session->userdata('companyId');
		 $zt=1;
//echo $periodfromdate,$periodtodate,$holget;

		 /////////////////////////////////// EJM Attendance Data////////////////////////30.04.24
$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        
$mccodes = $this->Loan_adv_model2->getstldetleavedata($periodfromdate,$periodtodate,$holget);
$row=2;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$row=1;
echo $mccodes;
//$sheet->setCellValue('A' . $row, 'STL Payable for the Period From'.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell

        $fields = array_keys($mccodes[0]);      

		$sheet->setCellValue('A' . $row, $company_name); // Set the value for the merged cell
//		$sheet->mergeCells('A' . $row . ':I' . $row); // Merge cells A1 to I1
		$row++;
		$sheet->setCellValue('A' . $row, 'STL Payable for the Period From '.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell
		$row++;
        // ---- Header row -----------------------------------------------------
        $col = 'A';
        foreach ($fields as $field) {
            // keep 01, 02, … as text so Excel won’t drop leading zero
       $sheet->setCellValueExplicit(
    $col.$row,
    $field,
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING   // fully-qualified name
);
            $sheet->getStyle($col.$row)->getFont()->setBold(true);
            $col++;
        }

        /* ---- Data rows ---------------------------------------------------*/
        $rowNum = 4;
        foreach ($mccodes as $rec) {
            $col = 'A';
            foreach ($fields as $field) {
                $sheet->setCellValue($col.$rowNum, $rec[$field]);
                $col++;
            }
            $rowNum++;
        }

        /* ---- Auto-size columns ------------------------------------------*/
        // last used column letter = chr(ord('A') + (#fields-1))
$colCount = count($fields);
for ($i = 2; $i <= $colCount; $i++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
}

        
$lastRow    = $rowNum - 1;
$lastColumn = Coordinate::stringFromColumnIndex(count($fields));
$sheet->getStyle("A1:{$lastColumn}{$lastRow}")
      ->applyFromArray([
          'borders' => [
              'allBorders' => [
                  'borderStyle' => Border::BORDER_THIN,
                  'color' => ['rgb' => '000000'],
              ],
          ],
      ]);



echo FCPATH;
$writer = new Xlsx($spreadsheet);
$fileName = 'stldetpayable.xlsx';
//$filePath = FCPATH . 'uploads/' . $fileName;
$filePath =$fileName;
$writer->save($filePath);

$fileContainer=$filePath;
$txt1=$fileContainer;
$files = array($txt1);
$zipname = 'stldetstmt.zip';
$zt=1;



 
////////////////////////

/////////////////////////////////// EJM Winding Production Data////////////////////////30.04.24
 
////////////////////////


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

public function canteendetdownloaddata() {
	$periodfromdate= $this->input->get('periodfromdate');
	$periodtodate= $this->input->get('periodtodate');
	$holget =  $this->input->get('holget');
		 $company_name = $this->session->userdata('companyname');
		 $comp = $this->session->userdata('companyId');
		 $zt=1;
//echo $periodfromdate,$periodtodate,$holget;

		 /////////////////////////////////// EJM Attendance Data////////////////////////30.04.24
$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        
$mccodes = $this->Loan_adv_model2->getcanteendetleavedata($periodfromdate,$periodtodate,$holget);
$row=2;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$row=1;
//$sheet->setCellValue('A' . $row, 'STL Payable for the Period From'.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell

        $fields = array_keys($mccodes[0]);      

		$sheet->setCellValue('A' . $row, $company_name); // Set the value for the merged cell
//		$sheet->mergeCells('A' . $row . ':I' . $row); // Merge cells A1 to I1
		$row++;
		$sheet->setCellValue('A' . $row, 'Canteen Statement for the Period From '.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell
		$row++;
        // ---- Header row -----------------------------------------------------
        $col = 'A';
        foreach ($fields as $field) {
            // keep 01, 02, … as text so Excel won’t drop leading zero
       $sheet->setCellValueExplicit(
    $col.$row,
    $field,
    \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING   // fully-qualified name
);
            $sheet->getStyle($col.$row)->getFont()->setBold(true);
            $col++;
        }

        /* ---- Data rows ---------------------------------------------------*/
        $rowNum = $row+1;
        foreach ($mccodes as $rec) {
            $col = 'A';
            foreach ($fields as $field) {
                $sheet->setCellValue($col.$rowNum, $rec[$field]);
                $col++;
            }
            $rowNum++;
        }

        /* ---- Auto-size columns ------------------------------------------*/
        // last used column letter = chr(ord('A') + (#fields-1))
$colCount = count($fields);
for ($i = 2; $i <= $colCount; $i++) {
    $colLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i);
    $sheet->getColumnDimension($colLetter)->setAutoSize(true);
}

        
$lastRow    = $rowNum - 1;
$lastColumn = Coordinate::stringFromColumnIndex(count($fields));
$sheet->getStyle("A1:{$lastColumn}{$lastRow}")
      ->applyFromArray([
          'borders' => [
              'allBorders' => [
                  'borderStyle' => Border::BORDER_THIN,
                  'color' => ['rgb' => '000000'],
              ],
          ],
      ]);



echo FCPATH;
$writer = new Xlsx($spreadsheet);
$fileName = 'canteenstmt.xlsx';
//$filePath = FCPATH . 'uploads/' . $fileName;
$filePath =$fileName;
$writer->save($filePath);

$fileContainer=$filePath;
$txt1=$fileContainer;
$files = array($txt1);
$zipname = 'canteendownload.zip';
$zt=1;



 
////////////////////////

/////////////////////////////////// EJM Winding Production Data////////////////////////30.04.24
 
////////////////////////


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


public function attsheetdownloaddata() {
	$periodfromdate= $this->input->get('periodfromdate');
	$periodtodate= $this->input->get('periodtodate');
	$att_dept= $this->input->get('att_dept');
//	echo $periodfromdate,"---",$periodtodate;
	$holget =  $this->input->get('holget');
		 $company_name = $this->session->userdata('companyname');
		 $comp = $this->session->userdata('companyId');
		 $zt=1;
//echo $periodfromdate,$periodtodate,$holget;

		 /////////////////////////////////// EJM Attendance Data////////////////////////30.04.24
$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        
$mccodes = $this->Loan_adv_model2->getattsheetdata($periodfromdate,$periodtodate,$holget,$att_dept);
$row=2;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->getPageSetup()
      ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)       // or ORIENTATION_PORTRAIT
      ->setPaperSize(PageSetup::PAPERSIZE_A4);    

$sheet->getPageMargins()->setTop(0.5);       // 0.5 inch
$sheet->getPageMargins()->setBottom(0.5);
$sheet->getPageMargins()->setLeft(0.4);
$sheet->getPageMargins()->setRight(0.4);
$sheet->getPageMargins()->setHeader(0.2);
$sheet->getPageMargins()->setFooter(0.2);

//$sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 2);

// -----------------------------------------------------------------------------
// fill rows + style according to type
$depnm='';
$spl='';
		$row=1;

$row = 1;
foreach ($mccodes as $rec) {
	if (($depnm !== $rec['dept_desc']) || ($spl !== $rec['atttype'])) {
		if ($depnm !== '') {
			// insert a page break before the next department
			$sheet->setBreak('A'.$row, Worksheet::BREAK_ROW);
			$row=$row+1;
		}	
 		
		$depnm = $rec['dept_desc'];
		$spl = $rec['atttype'];
//$sheet->setCellValue('A' . $row, 'ST 'L Payable for the Period From'.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell

		$sheet->setCellValue('A' . $row, $company_name); // Set the value for the merged cell
//		$sheet->mergeCells('A' . $row . ':I' . $row); // Merge cells A1 to I1
		$row++;
		$sheet->setCellValue('A' . $row, 'Attendance Sheet for the Period From '.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell
		$row++;
		$sheet->setCellValue('A' . $row, 'Department : '.$rec['dept_desc'].' Att Type :'.$spl.' (A Sft=Grey,B Sft =Bold,C Sft=Normal)' ); // Set the value for the merged cell
		$row++;


$startDay = (int)substr($periodfromdate, 8, 2);
$endDay   = (int)substr($periodtodate,   8, 2);

$sheet->setCellValue('A' . $row, 'EB No'); // Set the value for the merged cell
$sheet->setCellValue('B' . $row, 'Name'); // Set the value for the merged cell

$colIndex = 'C';
for ($d = $startDay; $d <= $endDay; $d++) {
    $sheet->setCellValue(
        $colIndex.$row,
        str_pad($d, 2, '0', STR_PAD_LEFT)   // "01" … "30"
    );
    $colIndex++;
}
$sheet->setCellValue($colIndex.'4', 'Total');
$colIndex++;
$sheet->setCellValue($colIndex.'4', 'DAYS');
$row++;

	}


    $sheet->setCellValue("A{$row}", $rec['emp_code']);
    $sheet->setCellValue("B{$row}", $rec['empname']);
    $colIndex = 'C';
    for ($d = $startDay; $d <= $endDay; $d++) {

        $alias  = str_pad($d, 2, '0', STR_PAD_LEFT);
        $hrs    = $rec[$alias];        // 8, 0, 4 …
        $atype  = $rec[$alias.'_t'];   // R / O / C / NULL

        $cell = $colIndex.$row;
        $sheet->setCellValue($cell, $hrs);

        // ---------- conditional styling ----------
        if ($atype === 'A1') {                            // bold
            $sheet->getStyle($cell)->getFont()
                  ->setBold(true);
        } elseif ($atype === 'B1') {                      // grey background
            $sheet->getStyle($cell)->getFill()
                  ->setFillType(Fill::FILL_SOLID)
                  ->getStartColor()->setARGB('FFC0C0C0');
        }
        // 'R' (or NULL) = no extra format

        $colIndex++;
    }

    // total hours
	
	$sheet->setCellValue($colIndex.$row, $rec['Total_hrs']);
    $colIndex++;
	$sheet->setCellValue($colIndex.$row, $rec['Total_days']);
	$row++;
}

$lastRow    = $sheet->getHighestDataRow();      // e.g. 37
$lastColumn = $sheet->getHighestDataColumn();   // e.g. "AG"

/* build a proper range like "A1:AG37" */
$range = "A1:{$lastColumn}{$lastRow}";

$sheet->getStyle($range)
      ->applyFromArray([
          'borders' => [
              'allBorders' => [
                  'borderStyle' => Border::BORDER_THIN,
                  'color'       => ['rgb' => '000000'],
              ],
          ],
      ]);


//$lastColIndex	  =$lastColumn;

$lastColLetter = $sheet->getHighestDataColumn();                // e.g.  "AG"
$lastColIndex  = Coordinate::columnIndexFromString($lastColLetter); // e.g. 33


for ($i = 1; $i <= $lastColIndex; $i++) {

    $letter = Coordinate::stringFromColumnIndex($i);   // "A", "B", …

    if ($i === 1) {                     // first column
        $width = 10;
    } elseif ($i === 2) {               // second column
        $width = 20;
    } elseif ($i === $lastColIndex) {   // very last column
        $width = 7;
    } else {                            // everything in between
        $width = 3;
    }

    $sheet->getColumnDimension($letter)->setWidth($width);
}

$sheet->getStyle("B1:B{$lastRow}")          // full height of the column
      ->getAlignment()
      ->setWrapText(true);

/* --------------------------------------------------
 * 3) If you need to wrap several columns (say C-to-F)
 * -------------------------------------------------- */
/* $wrapRange = "$lastColLetter"."1:" . $lastColLetter . $lastRow;
$sheet->getStyle($wrapRange)
	  ->getAlignment()
	  ->setWrapText(true);
 */
echo FCPATH;
$writer = new Xlsx($spreadsheet);
$fileName = 'attsheet.xlsx';
//$filePath = FCPATH . 'uploads/' . $fileName;
$filePath =$fileName;
$writer->save($filePath);

$fileContainer=$filePath;
$txt1=$fileContainer;
$files = array($txt1);
$zipname = 'attsheetdownload.zip';
$zt=1;



 
////////////////////////

/////////////////////////////////// EJM Winding Production Data////////////////////////30.04.24
 
////////////////////////


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



public function stldownloaddata() {
	$periodfromdate= $this->input->get('periodfromdate');
	$periodtodate= $this->input->get('periodtodate');
	$holget =  $this->input->get('holget');
		 $company_name = $this->session->userdata('companyname');
		 $comp = $this->session->userdata('companyId');
		 $zt=1;
/////////////////////////////////// EJM Attendance Data////////////////////////30.04.24
$ldate=substr($periodtodate,8,2).'-'.substr($periodtodate,5,2).'-'.substr($periodtodate,0,4);        
$mccodes = $this->Loan_adv_model2->getstlleavedata($periodfromdate,$periodtodate,$holget);
$row=2;
$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$row=1;
$sheet->setCellValue('A' . $row, 'STL Payable for the Period From'.$periodfromdate.' To '.$periodtodate); // Set the value for the merged cell
$row++;
$sheet->setCellValue('A' . $row, 'Department');
$sheet->setCellValue('B' . $row, 'EB No');
$sheet->setCellValue('C' . $row, 'Name');
$sheet->setCellValue('D' . $row, 'Leave From Date');
$sheet->setCellValue('E' . $row, 'Leave To Date');
$sheet->setCellValue('F' . $row, 'Leave Purpose');
$sheet->setCellValue('G' . $row, 'Wages from date');
$sheet->setCellValue('H' . $row, 'Wages to date');
$sheet->setCellValue('I' . $row, 'Paid');
$row++;

foreach ($mccodes as $record) {
	$sheet->setCellValue('A' . $row, $record->dept_desc);
	$sheet->setCellValue('B' . $row, $record->emp_code);
/*
$sheet->setValueExplicit(
        $record->emp_code,
        \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING
    );
*/
	$sheet->setCellValue('C' . $row, $record->empname);
	$sheet->setCellValue('D' . $row, $record->leave_from_date);
	$sheet->setCellValue('E' . $row, $record->leave_to_date);
	$sheet->setCellValue('F' . $row, $record->leave_purpose);
	$sheet->setCellValue('G' . $row, $periodfromdate);
	$sheet->setCellValue('H' . $row, $periodtodate);
	$sheet->setCellValue('I' . $row, $record->paid);
	$row++;
}	
echo FCPATH;
$writer = new Xlsx($spreadsheet);
$fileName = 'stlpayable.xlsx';
//$filePath = FCPATH . 'uploads/' . $fileName;
$filePath =$fileName;
$writer->save($filePath);

$fileContainer=$filePath;
$txt1=$fileContainer;
$files = array($txt1);
$zipname = 'Ejmstldownload.zip';
$zt=1;



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
		 $query = $this->db->get();
		  $data=$query->result();
	 
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
		$hd2="Workers Holiday Hours for the period from  ".$periodfromdate." To ".$periodtodate;
		$logMsg1.=$hd1."\n";
		$logMsg1.=$hd1a."\n";
		$logMsg1.=$hd2."\n";
		$logMsg1.="==================================================================================================="."\n";
		$logMsg1.="Emp Code   Name                            Holiday Hours                                           "."\n";
		$logMsg1.="==================================================================================================="."\n";
		
		$ln=6;
		foreach ($data as $row) {
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


		$txt1=$fileContainer;
		$txt2=$fileContainer1;
		$txt3=$fileContainer2;
		
	   $files = array($txt1,$txt2,$txt3);
	   $zipname = 'mainfnincdat.zip';
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
	 





public function stlupload() {

	$this->load->library('session');
	$this->load->helper(array('form', 'url'));
	$this->load->library('upload');
	$periodfromdate = $this->input->post('periodfromdate');
	$periodtodate = $this->input->post('periodtodate');
	$fileupload =  $this->input->post('fileupload');
 	$comp = $this->session->userdata('companyId');
	  
	 $config['upload_path'] = './uploads/';
	 $config['allowed_types'] = 'csv|xlsx';
	 $config['max_size'] = 2048;

	 $this->upload->initialize($config);

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

	//	$spreadsheet = $reader->load($_FILES['file']['tmp_name']);
		$spreadsheet = $reader->load($data['full_path']);

		//$lastRow = $spreadsheet->getHighestDataRow();
		//echo $lastRow;
		$worksheet = $spreadsheet->getActiveSheet();
		// Get the highest row and column numbers referenced in the worksheet
		$highestRow = $worksheet->getHighestRow(); // e.g. 10
		$highestColumn = $worksheet->getHighestColumn(); // e.g 'F'//$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn); // e.g. 5
//		echo 'hrow='.$highestRow.' hcol'.$highestColumn ;
		
		$sheetData = $spreadsheet->getActiveSheet()->toArray();
		$allupdt=0;
		$allfn='Y';
		if (!empty($sheetData)) {
            for ($i=2; $i<count($sheetData); $i++) { //skipping first row
                $lvid = $sheetData[$i][0];
				$fdate=	$sheetData[$i][6];
				$tdate=	$sheetData[$i][7];
				$paid=	$sheetData[$i][8];
				$fn='N';
				if ($fdate<>$periodfromdate) {
					$fn='Y';
				}
				if ($tdate<>$periodtodate) {
					$fn='Y';
				}
				if ($fn='Y') {
					$sql="insert into EMPMILL12.tbl_stl_days_payment (leave_tran_id,from_date,to_date,paid) values 
					($lvid,'$fdate','$tdate','$paid')";
				    $query = $this->db->query($sql);
					$allupdt++;
				}	
				if ($fn='N') {
					$allfn='N';
				}
			}		
		}		
			
				
		


		// Load the spreadsheet
/*
		try {
			$spreadsheet = $reader->load($data['full_path']);
			echo 'File loaded successfully.<br>';

			// You can now process the spreadsheet data
			// For example, to get the first sheet data
			$sheetData = $spreadsheet->getActiveSheet()->toArray();
			print_r($sheetData);
		} catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
			echo 'Error loading file: ', $e->getMessage();
		}
*/
	}




//echo 'stl upload';

if ($allfn='N') {
	$msg='All Data Not uploded';
} else { $msg="All Data Sucessfully Uploaded";	
}
$response = array(
	'success' => true,
	'savedata'=> 'saved',
	'msg'=>$msg
);

	echo json_encode($response);

}


	public function getmainvcpayrollexceldrg()
	{
		$this->load->model('Loan_adv_model');
		$periodfromdate = $this->input->post('periodfromdate');
		$periodtodate = $this->input->post('periodtodate');
		$att_payschm = $this->input->post('att_payschm');
		$att_spell = $this->input->post('att_spell');
		$att_dept = $this->input->post('att_dept');
		$holget = $this->input->post('hol_get');
		$ebid = $this->input->post('ebid');
		$_SESSION["fromdate"] = $periodfromdate;
		$_SESSION["todate"] = $periodtodate;
		$_SESSION["att_payschm"] = $att_payschm;
		$_SESSION["holget"] = $holget;
		$mccodes = $this->Loan_adv_model2->getmainvcpayrollexceldrg($periodfromdate, $periodtodate, $att_payschm, $holget);
		//var_dump($mccodes);
		$data = [];
		//  $sl=1;
		//$sf='';
		foreach ($mccodes as $record) {
			$data[] = [
				$EB_NO = $record->EB_NO,
				$wname = $record->wname,
				$WORKING_HOURS = $record->WORKING_HOURS,
				$HL_HRS = $record->HL_HRS,
				$NS_HRS = $record->NS_HRS,
				$STL_D = $record->STL_D,
				$TIME_RATED_BASIC = $record->TIME_RATED_BASIC,
				$PROD_BASIC = $record->PROD_BASIC,
				$TOTAL_BASIC = $record->TOTAL_BASIC,	////////for basic 
				$DA = $record->DA,
				$HOL_AMT = $record->HOL_AMT,
				$NS_AMOUNT = $record->NS_AMOUNT,
				$HRA = $record->HRA,
				$STL_WGS = $record->STL_WGS,
				$PF_GROSS = $record->PF_GROSS,
				$EPF = $record->EPF,
				$ESI_GROSS = $record->ESI_GROSS,
				$ESIC = $record->ESIC,
				$P_TAX = $record->P_TAX,
				$ADVANCE = $record->ADVANCE,
				$TOTAL_EARNING = $record->TOTAL_EARNING,
				$GROSS_DED = $record->GROSS_DED,
				$NET_PAY = $record->NET_PAY,
				$OT_HOURS = $record->OT_HOURS,
				$OT_ADVANCE = $record->OT_ADVANCE,
				$OVERTIME_PAY = $record->OVERTIME_PAY,
				$INCENTIVE_AMOUNT = $record->INCENTIVE_AMOUNT,
				$TOTAL_AMT = $record->TOTAL_AMT




			];
			// $sl++;
		}

		echo json_encode(['data' => $data]);

	}







}
