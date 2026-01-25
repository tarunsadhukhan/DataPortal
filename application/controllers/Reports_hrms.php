<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once APPPATH . '../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
//use Mpdf\Mpdf;
//use Fpdf\Fpdf;
//use FPDF; //

class Reports_hrms extends MY_Controller {
	
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
		$this->load->model('Employee_payscheme_wise_salary_Model');
		$this->load->model('Absentism_report_model');
		$this->load->model('Daily_cash_outsider_payment_module');
		$this->load->model('Man_mechine_report_module');
		$this->load->model('Overstay_after_leave_report_model');
		$this->load->model('Half_day_absentism_report_module');
		$this->load->model('Employee_Working_details_model');
		$this->load->model('Hrms_cash_hands_report_model');
		$this->load->model('Worker_Master_With_Last_Working_Day_Model');

						
		
		
		
		ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
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

	//	var_dump($array_keys);
	//	echo '1st time';
//		 $this->varaha->print_arrays($list);
		
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

	public function ajax_list_worker_master_details(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Worker_Master_With_Last_Working_Day_Model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);

	//	var_dump($array_keys);
	//	echo '1st time';
//		 $this->varaha->print_arrays($list);
		
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
						"recordsTotal" => $this->Worker_Master_With_Last_Working_Day_Model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Worker_Master_With_Last_Working_Day_Model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}



	public function ajax_list_report_650()
	{
		//		All_indent_List
		$mainmenuId = $_POST['mainmenuId'];
		$submenuId = $_POST['submenuId'];
		$companyId = $_POST['companyId'];
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		$sno = 1;
		//	echo $submenuId.$from_date.$to_date;
		//				$columns = $this->columns->getReportColumns($submenuId);	
		$columns = $this->columns->getReportColumns($submenuId, $from_date, $to_date);
//				var_dump($columns);
		$recordsTotal = "";
		$recordsFiltered = "";

		if ($submenuId == 650) {
			$list = $this->Employee_Working_details_model->get_datatables($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
			$recordsTotal = $this->Employee_Working_details_model->count_all($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
			$recordsFiltered = $this->Employee_Working_details_model->count_filtered($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
		}


		$array_keys = array_keys($columns);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action = '';
			$row = array();
			if ($array_keys) {
				for ($i = 0; $i < count($array_keys); $i++) {
					if ($sno) {
						if ($i == 0) {
							$mrowname = $array_keys[$i];
							$row[] = $loc->$mrowname;
							//							$row[] = $no;
						} else {
							$mrowname = $array_keys[$i];
							$row[] = $loc->$mrowname;
						}
					} else {
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
					}

				}
			}
			$data[] = $row;
		}
	//	var_dump($data);

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

	public function ajax_list_report_673(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Absentism_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		
	//	echo '1st time';
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
						"recordsTotal" => $this->Absentism_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Absentism_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_report_682(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Daily_cash_outsider_payment_module->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		
	//	echo '1st time';
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
						"recordsTotal" => $this->Daily_cash_outsider_payment_module->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Daily_cash_outsider_payment_module->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_report_686(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Overstay_after_leave_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		
	//	echo '1st time';
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
						"recordsTotal" => $this->Overstay_after_leave_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Overstay_after_leave_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_report_687(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
	//	echo '1st time';
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Half_day_absentism_report_module->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		
		//echo '1st time';
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
						"recordsTotal" => $this->Half_day_absentism_report_module->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Half_day_absentism_report_module->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_report_684(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->Man_mechine_report_module->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$array_keys = array_keys($columns);
		
	//	echo '1st time';
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
//var_dump($data);
		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Man_mechine_report_module->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Man_mechine_report_module->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_employee_payscheme_wise_salary(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$subId=544;
		$tp=1;

		$columns = $this->Employee_payscheme_wise_salary_Model->getDynamicArray($subId,$companyId);
		$list = $this->Employee_payscheme_wise_salary_Model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);

		$array_keys = array_keys($columns);
		

		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			if($array_keys){
				for($i=0; $i<count($array_keys); $i++){
						$mrowname = $array_keys[$i];
						$row[] = $loc->$mrowname;
						if($i==1){
						}

				}
			}
			$data[] = $row;
		}

		

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Employee_payscheme_wise_salary_Model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Employee_payscheme_wise_salary_Model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
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
////////////////////sabir change 13.11.23//////////////////////////////////////
public function ajax_Hrms_cash_hands_report(){
	$mainmenuId=$_POST['mainmenuId'];
	$submenuId=$_POST['submenuId'];
	$companyId=$_POST['companyId'];
	$from_date=$_POST['from_date'];
	$to_date=$_POST['to_date'];
	$sno=1;		
	$columns = $this->columns->getReportColumns($submenuId);	
	$recordsTotal="";
	$recordsFiltered="";
	if($submenuId==610){			
		$this->load->model('Hrms_cash_hands_report_model');			
		$list = $this->Hrms_cash_hands_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$recordsTotal=$this->Hrms_cash_hands_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$recordsFiltered=$this->Hrms_cash_hands_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
	}
	
	
	$array_keys = array_keys($columns);		
	$data = array();
	$no = $_POST['start'];
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
						$row[] = $loc->$mrowname;
				}
				
			}
		}
		$data[] = $row;
	}

	$output = array(
					"draw" => $_POST['draw'],
					"recordsTotal" => $recordsTotal,
					"recordsFiltered" => $recordsFiltered,
					"data" => $data,
			);
	//output to json format
	//$this->varaha->print_arrays($output);
	echo json_encode($output);
}
/////////////////////end sabir/////////////////////



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
			// phpinfo();
			$this->data['headtitle'] = "Dashboard";
			$this->data['mainmenuId'] = $menuId;			
			$this->data['menudit'] = $this->varaha_model->getMenuData($menuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");			
			$this->data['from_date'] = date('Y-m-01',time());
			$this->data['to_date'] = date('Y-m-t',time());
			$this->data['submenuId'] = "";
			$this->data['companyId'] = "";
			$this->data['controller'] = "reports_hrms";
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
			$this->data['controller'] = "reports_hrms";
			$this->data['menudit'] = $this->varaha_model->getMenuData($submenuId);
			



			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");
			$this->data['tableBorders']="";
			$this->data['Source']="0";
			$this->data['att_type']="0";
			$this->data['att_status']="";
			$this->data['status']=$this->varaha_model->getAllStatus();
			$this->data['departments']=$this->varaha_model->getAllDepartments($this->data['companyId']);
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
			$this->data['payschemes']=$this->varaha_model->getAllPayschemes($this->data['companyId']);
			$this->data['branch_id'] = 0;
			$this->data['componets']= $this->varaha_model->getAllComponets($this->data['companyId']);
			$this->data['componet_id'] = 21;
			$this->data['payscheme_id'] = 151;
			$this->data['itcod']="";	
			$this->data['srno']="";
		 

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
					
					}else if($submenuId==651){
						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y') ;
						$this->data['function'] = "ajax_list_worker_master_details";
					}
					else if($submenuId==673){
						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;

						$this->data['function'] = "ajax_list_report_673";
					}
					else if($submenuId==682){
						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
						$this->data['function'] = "ajax_list_report_682";
					} else if ($submenuId == 650) {
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'], $this->data['from_date'], $this->data['to_date']);
					$this->data['function'] = "ajax_list_report_650";
			//		$this->page_construct('hrms/reportnew', $this->data);

				} else if($submenuId==687){
//						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
						$this->data['function'] = "ajax_list_report_687";
					}else if($submenuId==686){
						$this->data['function'] = "ajax_list_report_686";
					}else if($submenuId==684){
						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;

						$this->data['function'] = "ajax_list_report_684";
					}
					else if($submenuId==544){

						$this->session->set_userdata(array('branch_id' => $this->data['branch_id'], 'payscheme_id' => $this->data['payscheme_id']));	
				//		$this->data['columns'] = $this->Employee_payscheme_wise_salary_Model->getColumns($subId,$companyId);
						$this->data['function'] = "ajax_list_employee_payscheme_wise_salary";
	
					
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
					}else if($submenuId==610){     //sabir 13.11.23
						$this->load->model('Hrms_cash_hands_report_model');
						$this->data['function'] = "ajax_Hrms_cash_hands_report";
						// $this->data['dates']=0; sabir 13.1123
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
					if($submenuId==673){
						$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					}	
					if($submenuId==682){
						$this->data['report_title'] = $this->data['menuName'] ." for ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					}
				if ($submenuId == 650) {
//					$this->data['report_title'] = $this->data['menuName'] . " for " . date('d-m-Y', strtotime($this->data['from_date']));
				}
				if($submenuId==684){
						$this->data['report_title'] = $this->data['menuName'] ." for ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					}	
				if($submenuId==651){
						$this->data['report_title'] = $this->data['menuName'] ." for ". date('d-m-Y') ;
					}	
					
					//.date("d",$form_date)." ".substr((date("D",$form_date)),0,2)." ".substr((date("M",$form_date)),0,2).
					if($submenuId==651){	
						$this->page_construct('hrms/report',$this->data);
//					$this->page_construct('hrms/worker_master_details',$this->data);
					}else{
						$this->page_construct('hrms/report',$this->data);
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
			$this->data['payscheme_id'] = $_POST['payscheme_chk'];
			$this->data['componets']= $this->varaha_model->getAllComponets($this->data['companyId']);
			$this->data['payschemes']=$this->varaha_model->getAllPayschemes($this->data['companyId']);
			$this->data['componet_id'] = $_POST['att_componet_id'];
			$this->data['itcod']=$_POST['itcode_chk'];
			$this->data['srno'] = $_POST['srno_chk'];

	//		echo $this->data['srno'] ;
	//		echo $this->data['itcod']; 
	//		echo $this->data['att_cat_att'].'check';
	//		echo $this->data['att_dept'];
//			echo $this->data['payscheme_id'];
	//		echo $_POST['eb_no_att'];
	//		echo ('report filtered 2nd');
	//		print_r('report flt 2nd');
			$brid=$this->data['branch_id'];
			$paysh=$this->data['payscheme_id'];
			
			if($this->data['submenuId']==603){
				$this->data['function'] = "ajax_list_full_attendance";
			}
			if($this->data['submenuId']==657){
				$this->data['function'] = "ajax_list_attendance_checklist";
			}
			if($this->data['submenuId']==651){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y') ;
				$this->data['function'] = "ajax_list_worker_master_details";
			}
			if($this->data['submenuId']==673){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
				$this->data['function'] = "ajax_list_report_673";
			}
			if($this->data['submenuId']==682){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
				$this->data['function'] = "ajax_list_report_682";
			}
			if ($this->data['submenuId'] == 650) {
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'], $this->data['from_date'], $this->data['to_date']);
				$this->data['function'] = "ajax_list_report_650";
			}
			if($this->data['submenuId']==686){
		//		$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
				$this->data['function'] = "ajax_list_report_686";
			}if($this->data['submenuId']==687){
				//		$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
						$this->data['function'] = "ajax_list_report_687";
			}if($this->data['submenuId']==684){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
				$this->data['function'] = "ajax_list_report_684";
			}
			if($this->data['submenuId']==544){
				$subId=544;
				$columns = $this->columns->getReportColumns($this->data['submenuId']);
			$this->session->set_userdata(array('branch_id' => $this->data['branch_id'], 'payscheme_id' => $this->data['payscheme_id']));	
			$columns=$this->data['columns'] = $this->Employee_payscheme_wise_salary_Model->getDynamicArray($subId,$this->data['companyId']);
				$this->data['function'] = "ajax_list_employee_payscheme_wise_salary";

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
					'eb_no' => $this->data['eb_no_att'],
					'att_mark_hrs_att' => $this->data['att_mark_hrs_att'],
					'att_worktype' => $this->data['att_worktype'],
					'att_cat' => $this->data['att_cat_att'],
					'branch_id' => $this->data['branch_id'],
					'payscheme_id' => $this->data['payscheme_id'],
					'componet_id' => $this->data['componet_id'],
					'itcod' => $this->data['itcod'],
					'srno' => $this->data['srno']
				);				
				if($this->data['submenuId']==610){
					$this->data['function'] = "ajax_Hrms_cash_hands_report";
				}
	
			if($this->data['submenuId']==603){				
				$this->data['res'] = $this->hrms_full_attendance_model->directReport($perms);
				// $this->varaha->print_arrays($this->data['res']);
				$html = $this->load->view('hrms/hrms_full_attendance', $this->data, true);
			}
			
			else if($this->data['submenuId']==657){
				$this->load->model('Attendance_checklist_Model');	
				$this->data['res'] = $this->Attendance_checklist_Model->directReport($perms);
//				var_dump($this->data);
				$html = $this->load->view('hrms/attendance_checklist', $this->data, true);
			}
			else if($this->data['submenuId']==651){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y') ;
				$this->load->model('Worker_Master_With_Last_Working_Day_Model');	
				$this->data['res'] = $this->Worker_Master_With_Last_Working_Day_Model->directReport($perms);
//				var_dump($this->data);
				$html = $this->load->view('hrms/worker_master_details', $this->data, true);
			}
			else if($this->data['submenuId']==673){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;

				$this->load->model('Absentism_report_model');	
				$this->data['res'] = $this->Absentism_report_model->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==682){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;

				$this->load->model('Daily_cash_outsider_payment_module');	
				$this->data['res'] = $this->Daily_cash_outsider_payment_module->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			} else if ($this->data['submenuId'] == 650) {
		//		$this->data['report_title'] = $this->data['menuName'] . " As on " . date('d-m-Y', strtotime($this->data['from_date']));

			//	$this->load->model('Daily_cash_outsider_payment_module');
				$this->data['res'] = $this->Employee_Working_details_model->directReport($perms);
	//			$html = $this->load->view('hrms/reportnew', $this->data, true);
				$html = $this->load->view('hrms/reportprintdynamic', $this->data, true);
	//			$html = $this->load->view('production/reportprintnew', $this->data, true);

			}else if($this->data['submenuId']==686){
//				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;

				$this->load->model('Overstay_after_leave_report_model');	
				$this->data['res'] = $this->Overstay_after_leave_report_model->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==687){
				$this->load->model('Half_day_absentism_report_module');	
				$this->data['res'] = $this->Half_day_absentism_report_module->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==684){
				$this->data['report_title'] = $this->data['menuName'] ." As on ". date('d-m-Y', strtotime($this->data['from_date'])) ;
				$this->load->model('Man_mechine_report_module');	
				$this->data['res'] = $this->Man_mechine_report_module->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			}else if($this->data['submenuId']==544){
				
				$this->load->model('Employee_payscheme_wise_salary_Model');	
				$this->data['res'] = $this->Employee_payscheme_wise_salary_Model->directReport($perms);
		//		var_dump($this->data);
				//	$this->load->model('store_issue_list_report_model');
			//	$this->data['res'] = $this->store_issue_list_report_model->directReport($perms);
				$html = $this->load->view('hrms/employee_payscheme_wise_salary', $this->data, true);
//				echo $html;
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
			//	$this->data['res'] = $this->Hrms_cash_hands_report_model->directReport($perms);
				//$this->varaha->print_arrays($this->data['res']);
		//		$html = $this->load->view('hrms/reportprint', $this->data, true);
				// $this->data['report_title'] = $this->data['menuName'];
				
				//$html = $this->load->view('hrms/', $this->reportprintdata, true);
				//$html = $this->load->view('hrms/reportprint', $this->data, true);

				//$this->load->model('Inventory_report_allitems_Model');	
				$this->load->model('hrms_cash_hands_report_model');
				$this->data['res'] = $this->hrms_cash_hands_report_model->directReport($perms);
				$html = $this->load->view('hrms/reportprint', $this->data, true);
			//	 $html = $this->load->view('store/stores_inventory_list_report', $this->data, true);
	

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
				//	$this->hrms_cash_hands_report_model->get_cashhands_pdf_report($perms);
					$this->create_cash_pdf($perms);
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
				}else if($this->data['submenuId']==682) {
						$this->outsiderdailypayexcel($perms);
				}else if($this->data['submenuId']==684) {
					$this->manmechinereportexcel($perms);
				}else if($this->data['submenuId']==694) {
					$this->outsiderdailypayexcel($perms);
				}
				else if($this->data['submenuId']==657) {
					$this->attendance_checklist($perms);
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
			//	echo $html;
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
	


public function create_cash_head_pdf2($perms,$pdf) {
$pdf->AddPage();

// === Company Title ===
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'THE EMPIRE JUTE CO. PVT. LTD.', 0, 1, 'C');

$pdf->SetFont('Arial', '', 13);
$pdf->Cell(0, 10, 'CASH HANDS REPORT', 0, 1, 'C');

// === Voucher Info Row ===
$pdf->SetFont('Arial', '', 11);
$pdf->Cell(140, 8, 'ExtraCashBookVoucherNo : ____________________', 0, 0, 'L');
$pdf->Cell(0, 8, 'Date: ' . date('d-m-Y'), 0, 1, 'R');

// === Horizontal Line Below Header ===
$pdf->Line(10, $pdf->GetY(), 287, $pdf->GetY()); // Line from left to right edge

$pdf->Ln(3); // Small vertical space

// === Column Headers with Borders ===
$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(230, 230, 230);
$pdf->SetTextColor(0);

// Table Header Row
$pdf->Cell(10, 8, 'S.No', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'EB No', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Worker Name', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Spell', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Machine', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Department', 1, 0, 'C', true);
$pdf->Cell(35, 8, 'Occupation', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Rate', 1, 0, 'C', true);
$pdf->Cell(15, 8, 'Hours', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Amount', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Sign', 1, 1, 'C', true);

}

  public function create_cash_head_pdf($perms,$pdf) {
        $pdf->AddPage();
		$pdf->SetFont('Arial', '', 16);
		$y=10;
		$ln=30;
		$pdf->SetXY(10, $y);
		$pdf->Cell(0, 10, 'Cash Hand Report', 0, 1, 'C');	
		$y=$y+10;
		$pdf->SetFont('Arial', '', 10);
		$pdf->SetXY(10, $y);
		$pdf->Cell(0, 10, 'ExtraCashBookVoucherNo');	
        $pdf->Line(10, $ln, 205, $ln);
		$ln=$ln+8;
        $pdf->Line(10, $ln, 205, $ln);
		$pdf->SetXY(0, $y);
		$prdate = date('d-m-Y', strtotime($perms['from_date']));
		$pdf->Cell(0, 10, 'Date : '.$prdate, 0, 1, 'R');
		$y=$y+8;	
		$pdf->SetFont('Arial', '', 8);
		$x=10;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'S.No');
		$x=$x+10;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'EB No');
		$x=$x+15;
 		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Worker Name');
		$x=$x+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Spell');
		$x=$x+8;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'MachineName');
		$x=$x+20;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Department');
		$x=$x+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Occupation');
		$x=$x+30;
		$pdf->SetXY($x, $y);		
		$pdf->Cell(0, 10, 'Rate');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Hours');
		$x=$x+12;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Amount');
		$x=$x+18;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Sign');

		$lnx=10;
        $pdf->Line($lnx, $ln-8, $lnx, $ln); //sno
		$lnx=$lnx+10;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //eb no
		$lnx=$lnx+15;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //worker name
		$lnx=$lnx+30;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //spell
 		$lnx=$lnx+8;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //machine name
 		$lnx=$lnx+20;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //department
 		$lnx=$lnx+30;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //occupation
 		$lnx=$lnx+30;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //rate
 		$lnx=$lnx+12;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //hours
 		$lnx=$lnx+12;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //amount
 		$lnx=$lnx+18;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //sign
 		$lnx=$lnx+10;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); 
 		$ln=$ln+10;




	}


public function create_cash_head_pdf_line($x,$y,$pdf) {
	$lastY = $pdf->GetY();
	$ln=$lastY+8 ; // Set line height
		$lnx=10;
		
        $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+10;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+15;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+30;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+8;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+20;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+30;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+30;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+12;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+12;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+18;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$lnx=$lnx+10;
	    $pdf->Line($lnx,38, $lnx, $ln); //sno
		$ln=$ln+10;

}
public function create_cash_pdf_summary($perms, $pdf) {
		$mccodes=$this->Hrms_cash_hands_report_model->directReportsummary($perms);
		$pdf->SetFont('Arial', 'B', 12);
        $pdf->AddPage();
		$pdf->SetFont('Arial', '', 16);
		$y=10;
		$ln=30;
		$pdf->SetXY(10, $y);
		$pdf->Cell(0, 10, 'Cash Hand Report Summary ', 0, 1, 'C');	
		$y=$y+10;
		$pdf->SetFont('Arial', '', 12);
		$pdf->SetXY(10, $y);
		$pdf->Cell(0, 10, 'ExtraCashBookVoucherNo');	
        $pdf->Line(10, $ln, 200, $ln);
		$ln=$ln+8;
        $pdf->Line(10, $ln, 200, $ln);
		$pdf->SetXY(0, $y);
		$prdate = date('d-m-Y', strtotime($perms['from_date']));
		$pdf->Cell(0, 10, 'Date : '.$prdate, 0, 1, 'R');
		$y=$y+8;	
		$pdf->SetFont('Arial', '', 10);
		$x=10;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'S.No');
		$x=$x+10;
		$pdf->SetXY($x, $y);
		$pdf->Cell(40, 10, 'Department',0,0, 'C');
		$x=$x+55;
		$pdf->SetXY($x, $y);		
		$pdf->Cell(0, 10, 'Spell');
		$x=$x+22;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Hours');
		$x=$x+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 10, 'Amount');
		$lnx=10;
        $pdf->Line($lnx, $ln-8, $lnx, $ln); //sno
		$lnx=$lnx+10;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //eb no
		$lnx=$lnx+50;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //worker name
		$lnx=$lnx+20;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //spell
 		$lnx=$lnx+20;
	    $pdf->Line($lnx, $ln-8, $lnx, $ln); //machine name
 		$lnx=$lnx+13;

		$y=$y+10;
	
		$sl=0;
		$totamt=0;
		$tothrs=0;
		$dp='';
		$sft='';
	foreach ($mccodes as $row) {  
			$sl++;
		$x=10;
		$y=$y+2;
		$pdf->SetXY($x, $y);
		$pdf->Cell(7, 3, $sl,0,0, 'C');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(50, 3, $row->dept_desc, 0, 0);
		$x=$x+50;
// Worker Name with wrapping
		$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
		$pdf->Cell(30, 3, $row->spell, 0,0); // width=45, height per line=5
		$endY_positions[] = $pdf->GetY();
		// Adjust Y for rest of cells (height depends on text lines)
		$x=$x+25;
		// Worker Name with wrapping
		$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
		$pdf->Cell(10, 3, $row->hrs, 0,0,'R'); // width=45, height per line=5
		$x=$x+30;
		$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
		$pdf->Cell(13, 3, $row->amt, 0,0,'R'); // width=45, height per line=5

		$totamt=$totamt+$row->amt;
				$tothrs=$tothrs+$row->hrs;

		$pdf->Line(10, $y+8, 205, $y+8);  // Bottom border for row


		$y=$y+8;
	}


$pdf->Line(10, $y+8, 205, $y+8);  // Bottom border for row


	$y=$y+3;
$x=30;
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(30, 3, 'Total', 0,0); // width=45, height per line=5

$x=$x+70;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->Cell(10, 3, $tothrs, 0,0,'R'); // width=45, height per line=5
$x=$x+30;
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->Cell(13, 3, $totamt, 0,0,'R'); // width=45, height per line=5

$y=$y+12;
		$x=10;
$amount = $totamt;
		// Convert amount to words
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 3, 'Total Amount in Words : ',0,0, 'L');
		$x=$x+40;
		$pdf->SetXY($x, $y);
		$pdf->Cell(0, 3, $this->convertNumberToWords($totamt),0,0, 'L');
$y=$y+6;
$pdf->Line(10, $y+8, 205, $y+8);  // Bottom border for row
		
		// Add figure amount


	


}


public function create_cash_pdf($perms) {
//	$pdf = new FPDF();
$pdf = new FPDF('P', 'mm', 'A4'); // Landscape, mm units, A4 size
$pdf->SetMargins(10, 10, 10);
$pdf->SetAutoPageBreak(true, 10);

		$mccodes=$this->Hrms_cash_hands_report_model->directReport($perms);

        $pdf->SetFont('Arial', '', 12);
        $pdf->SetXY(10, 5);
 
		$y=10;
		$sl=0;
		$totamt=0;
		$tothrs=0;
		$dp='';
		$sft='';
	foreach ($mccodes as $row) {  

		if ($y== 10) {
			$this->create_cash_head_pdf($perms,$pdf);
			$y=38;
		}	
		
		if($perms['att_type']=='B') {


		if (($dp<> $row->dept_desc) || ($sft<> $row->shift)) {
			// If department changes, create a new header
			if ($totamt>0) {
				// If there is a previous total, print it
				$this->create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y);
//			$this->create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y);
			
			$pdf->SetFont('Arial', '', 8);

//			$this->create_cash_head_pdf_line(10, $y, $pdf);
			$this->create_cash_head_pdf($perms,$pdf);
 			$y = 38; // Move down for next row
			$dp=$row->dept_desc;
			$sft=$row->shift;
			$totamt=0;
			$tothrs=0;
			$sl=0;

		}
	}	

		}

		if($perms['att_type']=='D') {


		if ($dp<> $row->dept_desc)  {
			// If department changes, create a new header
			if ($totamt>0) {
				// If there is a previous total, print it
				$this->create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y);
//			$this->create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y);
			
			$pdf->SetFont('Arial', '', 8);

//			$this->create_cash_head_pdf_line(10, $y, $pdf);
			$this->create_cash_head_pdf($perms,$pdf);
 			$y = 38; // Move down for next row
			$dp=$row->dept_desc;
			$sft=$row->shift;
			$totamt=0;
			$tothrs=0;
			$sl=0;

			}
	}	

		}





		$py=$pdf->GetY();
		$endY_positions = [];
		$sl++;
		$x=10;
		$y=$y+2;
		$pdf->SetXY($x, $y);
		$pdf->Cell(7, 3, $sl,0,0, 'C');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(8, 3, $row->eb_no, 0, 0, 'C');
$x=$x+12;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(30, 3, $row->name, 0,0); // width=45, height per line=5
$endY_positions[] = $pdf->GetY();
// Adjust Y for rest of cells (height depends on text lines)
$x=$x+31;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(30, 3, $row->spell, 0,0); // width=45, height per line=5
$x=$x+7;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(20, 3, '', 0,0); // width=45, height per line=5
$endY_positions[] = $pdf->GetY();
$x=$x+20;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(30, 3, $row->dept_desc, 0,0); // width=45, height per line=5
$endY_positions[] = $pdf->GetY();
$x=$x+30;
// Worker Name with wrapping
$pdf->SetXY($x , $y); // Move to start of "Worker Name" cell
$pdf->MultiCell(30, 3, $row->desig, 0,0); // width=45, height per line=5
$endY_positions[] = $pdf->GetY();
$x=$x+30;

		$pdf->SetXY($x, $y);
		$pdf->Cell(9, 3, $row->cash_rate,0,0, 'C');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, $row->hrs, 0, 0, 'C');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, $row->amt, 0, 0,'R');
		$totamt=$totamt+$row->amt;
		$tothrs=$tothrs+$row->hrs;


$maxHeight = $pdf->GetY(); // End Y after MultiCell
$pdf->SetXY($x + 75, $y); // Next cell start


$maxY = max($endY_positions);
$dfy=$maxY-$py;
if ($dfy<6) {
		$maxY = $py + 6; // Ensure minimum height of 6
} else {
	$maxY = $maxY + 2; // Add some space after the last line
}

$pdf->Line(10, $maxY, 205, $maxY);  // Bottom border for row



		$y=$y+6;
		$y = $maxY;
		if ($y>250) {
				$this->create_cash_head_pdf_line($x,$y,$pdf);
			$y=10;
		}
	}

	$this->create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y);
 
		
		// Uncomment if you want to set a different font for the footer
	//		$pdf->SetFont('Arial', 'B', 10);
		
	/* 	Time Keeper Department Incharge Personal Department General Manager
		To Accounts,
		The above mentioned amount can be relased to the workers.
		Authorised Signature
		Commercial Dept. */
	$this->create_cash_pdf_summary($perms,$pdf);

		$pdf->Output('D', 'CashhandReport.pdf');



    }

	public function create_cash_pdf_bottom($totamt, $tothrs, $pdf,$y) {
		$x=40;
		$y=$y+3;
		$pdf->SetXY($x, $y);
		$pdf->Cell(9, 3, 'Total',0,0, 'C');
		
		$x=167;
//		$y=$y+3;
		$pdf->SetXY($x, $y);
		$pdf->Cell(9, 3, $tothrs,0,0, 'C');
		$x=$x+13;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, $totamt, 0, 0, 'R');
		
		$maxY = $pdf->GetY(); // Get the Y position after the last cell
		$pdf->Line(10, $maxY+8, 205, $maxY+8); 
		$lastY = $pdf->GetY();
		$y=$lastY+10;

		$this->create_cash_head_pdf_line($x,$y,$pdf);
   		$pdf->SetFont('Arial', '', 10);
		$amount = $totamt;
		$figamt=$this->convertNumberToWords($amount);
		$x=10;
		$y=$y+3;
		$pdf->SetXY($x, $y);
		$pdf->Cell(9, 3, '(Amount in Figure : '.$figamt.')',0,0, 'L');


        $pdf->SetFont('Arial', '', 12);

		if ($y>250) {
			$pdf->AddPage();
			$y=10;
		}

		$x=10;
		$y=$y+20;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Checked By', 0, 0);
		$x=$x+100;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Approved By', 0, 0);
		
		$y=$y+6;
		$x=10;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Time Keeper', 0, 0);
		$x=$x+40;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Department Incharge', 0, 0);
		$x=$x+50;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Personal Department', 0, 0);
		$x=$x+60;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'General Manager', 0, 0);

		$x=10;
		$y=$y+20;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'To Accounts,', 0, 0);
		$x=$x+20;
		$x=10;
		$y=$y+6;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'The above mentioned amount can be relased to the workers.', 0, 0);
		$x=$x+20;

		$x=150;
		$y=$y+30;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Authorised Signature', 0, 0);
		$x=$x+20;

		$x=150;
		$y=$y+6;
		$pdf->SetXY($x, $y);
		$pdf->Cell(13, 3, 'Commercial Dept.', 0, 0);
		$x=$x+20;

		// This function is not used in the current code, but can be implemented if needed.
	}

	function convertNumberToWords($num) {
    $words = array(
        '0' => '', '1' => 'One', '2' => 'Two',
        '3' => 'Three', '4' => 'Four', '5' => 'Five',
        '6' => 'Six', '7' => 'Seven', '8' => 'Eight',
        '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven',
        '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
        '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
        '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
        '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty',
        '60' => 'Sixty', '70' => 'Seventy', '80' => 'Eighty',
        '90' => 'Ninety'
    );

    $digits = ['', 'Hundred', 'Thousand', 'Lakh', 'Crore'];

    if ($num == 0) return 'Zero only';

    $num = number_format($num, 2, '.', '');
    $split = explode('.', $num);
    $num = $split[0];

    $result = '';
    $places = [10000000, 100000, 1000, 100, 1];
    $labels = ['Crore', 'Lakh', 'Thousand', 'Hundred', ''];

    foreach ($places as $i => $place) {
        $value = intval($num / $place);
        $num = $num % $place;

        if ($value > 0) {
            if ($value < 21) {
                $result .= $words[$value] . ' ';
            } else {
                $result .= $words[(int)($value / 10) * 10] . ' ' . $words[$value % 10] . ' ';
            }

            $result .= $labels[$i] . ' ';
        }
    }

    return trim($result) . ' only';
}

	public function manmechinereportexcel($pers) {
 
		$from_date  =$pers['from_date'];             
		$companyId=$pers['company'];
		$paydate=$pers['from_date'];
		$compid=$pers['company'];

		$sdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
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
	
		$sql="select * from company_master where comp_id=".$compid;
		$query = $this->db->query($sql);
		$results = $query->result_array();
		foreach ($results as $row) {
			$compname=$row['company_name'];
		
		}
		$mccodes = $this->Man_mechine_report_module->directReport($pers);
	//	$list = $this->Man_mechine_report_module->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$sheet->setTitle('HANDS_DETAILS_Report');
		$hed1='Daily Std Hands Report Report Dated  '.$sdate; 

		$sheet->setCellValue('A1', $compname);
		$sheet->setCellValue('A2', $hed1);
		$sheet->setCellValue('A3', 'Occu Code');
		$sheet->setCellValue('B3', 'Occu Desc');
		$sheet->setCellValue('c3', 'Acutal Hands');
		$sheet->setCellValue('h3', 'Target Hands');
		$sheet->setCellValue('l3', 'Excess / Short');
		$sheet->setCellValue('n3', 'M-T-D');
		$sheet->setCellValue('q3', 'Direct/Indirect');
		$sheet->setCellValue('c4', 'Shift A');
		$sheet->setCellValue('d4', 'Shift B');
		$sheet->setCellValue('e4', 'Shift C');
		$sheet->setCellValue('f4', 'OT Hands');
		$sheet->setCellValue('g4', 'Total');
		$sheet->setCellValue('h4', 'Shift A');
		$sheet->setCellValue('i4', 'Shift B');
		$sheet->setCellValue('j4', 'Shift C');
		$sheet->setCellValue('k4', 'Total');
		$sheet->setCellValue('l4', 'Excess');
		$sheet->setCellValue('m4', 'Short');
		$sheet->setCellValue('n4', 'Total Hands');
		$sheet->setCellValue('o4', 'Excess');
		$sheet->setCellValue('p4', 'Short');
		
		
		$sheet->mergeCells('c3:g3');
		$sheet->mergeCells('h3:k3');
		$sheet->mergeCells('l3:m3');
		$sheet->mergeCells('n3:p3');

		$centerAlignment = $sheet->getStyle('c3:g3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:a2')->applyFromArray($boldFontStyle);
		$sheet->getStyle('A3:b4')->applyFromArray($boldFontStyle);
		$sheet->getStyle('A3:b4')->applyFromArray($borderStyle);
		$sheet->getStyle('c3:g3')->applyFromArray($boldFontStyle);
		$sheet->getStyle('c3:g3')->applyFromArray($borderStyle);
		

		$centerAlignment = $sheet->getStyle('h3:k3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('h3:k3')->applyFromArray($boldFontStyle);
		$sheet->getStyle('h3:k3')->applyFromArray($borderStyle);


		$centerAlignment = $sheet->getStyle('l3:m3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('l3:m3')->applyFromArray($boldFontStyle);
		$sheet->getStyle('l3:m3')->applyFromArray($borderStyle);

		$centerAlignment = $sheet->getStyle('n3:p3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('n3:p3')->applyFromArray($boldFontStyle);
		$sheet->getStyle('n3:p3')->applyFromArray($borderStyle);

		$sheet->getStyle('q3:q3')->applyFromArray($boldFontStyle);
		$sheet->getStyle('q3:q3')->applyFromArray($borderStyle);
$n=4;
		for ($ch = 65; $ch <= 81; $ch++) {
			$cln=chr($ch).$n;
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$sheet->getColumnDimension('A')->setWidth(10);
		if ($ch==66) {
			$sheet->getColumnDimension('b')->setWidth(40);
		} 	
	}
		$n++;
$cln='b'.$n;
$rw='$row->HOCCU_CODE'; 
$sheet->setCellValue($cln, $rw);
//$sheet->getStyle($cln)->applyFromArray($borderStyle);
//$n++;

		foreach ($mccodes as $row) {  
				$cln='a'.$n;
				$rw=$row->HOCCU_CODE; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='b'.$n;
				$rw=$row->desigd; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$sheet->getStyle($cln)->getAlignment()->setWrapText(true);
				$cln='c'.$n;
				$rw=$row->shift_a; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='d'.$n;
				$rw=$row->shift_b; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='e'.$n;
				$rw=$row->shift_c; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='f'.$n;
				$rw=0; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='g'.$n;
				$rw=$row->totshift; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='h'.$n;
				$rw=$row->target_a; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='i'.$n;
				$rw=$row->target_b; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='j'.$n;
				$rw=$row->target_c; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='k'.$n;
				$rw=$row->tottarget; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='l'.$n;
				$rw=$row->excess_hands;
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='m'.$n;
				$rw=$row->short_hands; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='n'.$n;
				$rw=$row->tdhands; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='o'.$n;
				$rw=$row->tdexcess; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='p'.$n;
				$rw=$row->tdshort; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$cln='q'.$n;
				$rw=$row->DIRECT_INDIRECT; 
				$sheet->setCellValue($cln, $rw);
				$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$row->HOCCU_CODE;
				if (strlen($rw)==0) { 
//					for ($ch = 65; $ch <= 81; $ch++) {
//						$cln=chr($ch).$n;
					$rng='a'.$n.':q'.$n;
						$sheet->getStyle($rng)->applyFromArray($boldFontStyle);
//					}
				}	
				
				$n++;

		}
		$nn=5;
		$sv1='b'.$n;
		$rng='b'.$nn.':b'.$n;
		$sheet->setCellValue($sv1, 'Grand Total');
		$sv1='c'.$n;
		$rng='c'.$nn.':c'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='d'.$n;
		$rng='d'.$nn.':d'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='e'.$n;
		$rng='e'.$nn.':e'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='f'.$n;
		$rng='f'.$nn.':f'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='g'.$n;
		$rng='g'.$nn.':g'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='h'.$n;
		$rng='h'.$nn.':h'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='i'.$n;
		$rng='i'.$nn.':i'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='j'.$n;
		$rng='j'.$nn.':j'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='k'.$n;
		$rng='k'.$nn.':k'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='l'.$n;
		$rng='l'.$nn.':l'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='m'.$n;
		$rng='m'.$nn.':m'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='n'.$n;
		$rng='n'.$nn.':n'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='o'.$n;
		$rng='o'.$nn.':o'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		$sv1='p'.$n;
		$rng='p'.$nn.':p'.$n;
		$sheet->setCellValue($sv1, '=SUM('.$rng.')/2');
		 
		$rng='a'.$n.':q'.$n;
		$sheet->getStyle($rng)->applyFromArray($boldFontStyle);


		$sht=1;
		$mccodes = $this->Man_mechine_report_module->getmechinesummdata($pers);
		$sheet = $spreadsheet->createSheet($sht);
		$sheet->setTitle('MC_SUMMARY_Report');
		$hed1='Daily Mechine Summary Report Dated   '.$sdate; 
	//	MC Code	Mechine Desc	Installed M/c	Acutal Mechine Runs				
	//	Shift A	Shift B	Shift C	Total	To Date


		$sheet->setCellValue('A1', $compname);
		$sheet->setCellValue('A2', $hed1);
		$sheet->setCellValue('A3', 'MC Code');
		$sheet->setCellValue('B3', 'Mechine Desc');
		$sheet->setCellValue('c3', 'Installed M/c');
		$sheet->setCellValue('d3', 'Acutal Mechine Runs');
		$sheet->setCellValue('d4', 'Shift A');
		$sheet->setCellValue('e4', 'Shift B');
		$sheet->setCellValue('f4', 'Shift C');
		$sheet->setCellValue('g4', 'Total');
		$sheet->setCellValue('h4', 'To Date');
		$centerAlignment = $sheet->getStyle('d3:h3')->getAlignment();
		$centerAlignment->setHorizontal(Alignment::HORIZONTAL_CENTER);
$n=3;
		$sheet->mergeCells('d3:h3');
		for ($ch = 65; $ch <= 72; $ch++) {
			$cln=chr($ch).$n;
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
				$sheet->getColumnDimension('A')->setWidth(10);
		if ($ch==66) {
			$sheet->getColumnDimension('b')->setWidth(40);
		} 	

	}
$n++;
for ($ch = 65; $ch <= 72; $ch++) {
	$cln=chr($ch).$n;
	$sheet->getStyle($cln)->applyFromArray($borderStyle);
		$sheet->getColumnDimension('A')->setWidth(10);
if ($ch==66) {
	$sheet->getColumnDimension('b')->setWidth(40);
} 	

}
		$n=3;
		$rng='a'.$n.':q'.$n+1;
		$sheet->getStyle($rng)->applyFromArray($boldFontStyle);
		$n=5;
		foreach ($mccodes as $row) {  
			$cln='a'.$n;
			$rw=$row->mc_code; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='b'.$n;
			$rw=$row->Mechine_type_name; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='c'.$n;
			$rw=$row->no_of_installed_mc; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='d'.$n;
			$rw=$row->shift_a; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='e'.$n;
			$rw=$row->shift_b; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='f'.$n;
			$rw=$row->shift_c; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='g'.$n;
			$rw=$row->totalmc; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$cln='h'.$n;
			$rw=$row->tdmc; 
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$n++;
		}	




		//		Occu Code	Occu Desc	Acutal Hands					Target Hands				Excess / Short		M-T-D			Direct/Indirect
//	 	Shift A	Shift B	Shift C	OT Hands	Total	Shift A	Shift B	Shift C	Total	Excess	Short	Total Hands	Excess	Short	
	

		


		$filename="Man_mechine_report_".$sdate.".xlsx";


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
	public function outsiderdailypayexcel($pers) {
    // Create a new Spreadsheet object
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


	$from_date  =$pers['from_date'];             
	$companyId=$pers['company'];
	$paydate=$pers['from_date'];
	$compid=$pers['company'];
	$sdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
    // Add your data to the spreadsheet
    // ...

	$sql="select * from company_master where comp_id=".$compid;
	$query = $this->db->query($sql);
	$results = $query->result_array();
	foreach ($results as $row) {
		$compname=$row['company_name'];
	
	}
	$mccodes = $this->Daily_cash_outsider_payment_module->directReport($pers);
//	$this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
//$sheet->setCellValue('A1', 'Sl No');

$sheet->setTitle('Summary Sheet');
$hed1='PaySheet Summary Dated  '.$sdate.' Shift ALL'; 
$sheet->setCellValue('A1', $hed1);
$sheet->setCellValue('a2', 'Department');
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
$dta=0;
$gta=0;
$p=2;
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
				$rw=$dta	; 
				$cln='i'.$n;
				$sheetd->setCellValue($cln, $rw);
				$sheetd->getStyle($cln)->applyFromArray($borderStyle);
				$rw=$damt+$dta; 
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
				$rw=$damt+$dta; 
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
				$dta=0;
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
	$rw=$row->amount+$row->oth_rate	; 
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
	$dta=$dta+$row->oth_rate;
	$gta=$gta+$row->oth_rate;
	$gamt=$gamt+$row->amount;
			
	



}

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
$rw=$dta; 
$cln='i'.$n;
$sheetd->setCellValue($cln, $rw);
$rw=$dta+$damt; 
$cln='j'.$n;	; 
$sheetd->setCellValue($cln, $rw);
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
		

$sheetd->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

$rw=$dhnd; 
$cln='b'.$p;
$sheet->setCellValue($cln, $rw);
$sheet->getStyle($cln)->applyFromArray($borderStyle);
$rw=$damt+$dta; 
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
$rw=$gamt+$gta; 
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


	$filename="Paysheet_".$sdate.".xlsx";


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



public function attendance_checklist($pers) {
    // Create a new Spreadsheet object
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		$sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT);

// Set paper size (e.g., A4, Letter)
	$sheet->getPageSetup()->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);

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


	$from_date  =$pers['from_date'];   
	$to_date  =$pers['to_date'];          
	$companyId=$pers['company'];
	$paydate=$pers['from_date'];
	$compid=$pers['company'];
	$sdate=substr($from_date,8,2).'-'.substr($from_date,5,2).'-'.substr($from_date,0,4);
    // Add your data to the spreadsheet
    // ...
	$tdate=substr($to_date,8,2).'-'.substr($to_date,5,2).'-'.substr($to_date,0,4);



	$sql="select * from company_master where comp_id=".$compid;
	$query = $this->db->query($sql);
	$results = $query->result_array();
	foreach ($results as $row) {
		$compname=$row['company_name'];
	
	}
	$mccodes = $this->Attendance_checklist_Model->directReport($pers);
//	$this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
//$sheet->setCellValue('A1', 'Sl No');

$sheet->setTitle('Attendance Check List');
$hed1='Attendance Check List for the Period from  '.$sdate.' To ' .$tdate; 
$hed2=$compname; 
$sheet->setCellValue('A1', $hed2);
$sheet->setCellValue('A2', $hed1);
$sheet->setCellValue('a3', 'Sl No');
$sheet->setCellValue('b3', 'Attendance Date');
$sheet->setCellValue('c3', 'Spell');
$sheet->setCellValue('d3', 'EB No');
$sheet->setCellValue('e3', 'Name');
$sheet->setCellValue('f3', 'Deaprtment');
$sheet->setCellValue('g3', 'Designation');
$sheet->setCellValue('h3', 'Att Type');
$sheet->setCellValue('i3', 'Att Source');
$sheet->setCellValue('j3', 'Working Hours');
$sheet->setCellValue('k3', 'Mc Nos');
$sheet->setCellValue('l3', 'Remarks');
$sheet->mergeCells('A1:l1');
$sheet->mergeCells('A2:l2');
$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$style = $sheet->getStyle("A1:L2");
$style->getFont()->setSize(14)->setBold(true);


$sheet->getColumnDimension('A')->setWidth(4);
$sheet->getColumnDimension('B')->setWidth(10);
$sheet->getColumnDimension('C')->setWidth(8);
$sheet->getColumnDimension('d')->setWidth(7.56);
$sheet->getColumnDimension('e')->setWidth(16.00);
$sheet->getColumnDimension('f')->setWidth(11.11);
$sheet->getColumnDimension('g')->setWidth(17.67);
$sheet->getColumnDimension('h')->setWidth(3.33);
$sheet->getColumnDimension('i')->setWidth(3.78);
$sheet->getColumnDimension('j')->setWidth(7.56);
$sheet->getColumnDimension('k')->setWidth(13.78);
$sheet->getColumnDimension('l')->setWidth(14);
 

$n=3;
for ($ch = 65; $ch <= 76; $ch++) {
	$cln=chr($ch).$n;
	$sheet->getStyle($cln)->applyFromArray($borderStyle);
} 	


$dpn='';

$n=4;
$no=1;
$sht=0;
$dhnd=0;
$damt=0;
$ghnd=0;
$gamt=0;
$p=2;

//var_dump($mccodes);
foreach ($mccodes as $row) {  
	$cln='A'.$n;
	$rw=$no;
	$sheet->setCellValue($cln, $no);
	$rw=$row->Date; 
	$cln='B'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Spell; 
	$cln='c'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->EB_No; 
	$cln='d'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Name; 
	$cln='e'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Department; 
	$cln='f'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Designation; 
	$cln='g'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->attendance_type; 
	$cln='h'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->attendance_source; 
	$cln='i'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Working_Hours; 
	$cln='j'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->MC_Nos; 
	$cln='k'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->remarks; 
	$cln='l'.$n;
	$sheet->setCellValue($cln, $rw);

 	for ($ch = 65; $ch <= 75; $ch++) {
		$cln=chr($ch).$n;
		$sheet->getStyle($cln)->applyFromArray($borderStyle);

	} 	
	$n++;
	$no++;
}



 $pn='l'.$n;
$style = $sheet->getStyle(('A3:'.$pn));
$style->getFont()->setSize(10);

$sheet->getStyle('A3:'.$pn)->getAlignment()->setWrapText(true);

	$sht=2;
	$sheet = $spreadsheet->createSheet($sht);
			$sheet->setTitle('Hands Complement');


	$mccodes = $this->Attendance_checklist_Model->directsummReport($pers);
//	$this->Loan_adv_model->getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept);
//$sheet->setCellValue('A1', 'Sl No');

$hed1='Hands Complements for the Period from  '.$sdate.' To ' .$tdate;; 
$hed2=$compname; 
$sheet->setCellValue('A1', $hed2);
$sheet->setCellValue('A2', $hed1);
$sheet->setCellValue('a3', 'Sl No');
$sheet->setCellValue('b3', 'Attendance Date');
$sheet->setCellValue('c3', 'Department');
$sheet->setCellValue('d3', 'Designation');
$sheet->setCellValue('e3', 'Shift');
$sheet->setCellValue('i3', 'Catagoty');

$sheet->setCellValue('e4', 'A');
$sheet->setCellValue('f4', 'B');
$sheet->setCellValue('g4', 'C');
$sheet->setCellValue('h4', 'Total');
$sheet->setCellValue('i4', 'Permanent');
$sheet->setCellValue('j4', 'Budli');
$sheet->setCellValue('k4', 'Retired');
$sheet->setCellValue('l4', 'New Budli');
$sheet->setCellValue('m4', 'Contract');
$sheet->setCellValue('n4', 'Outsider');
$sheet->setCellValue('o4', 'Apprentice');
$sheet->setCellValue('p4', 'Total Hands');

$sheet->mergeCells('A1:p1');
$sheet->mergeCells('A2:p2');
$sheet->mergeCells('e3:h3');
$sheet->mergeCells('i3:p3');
$sheet->getStyle('a3:p4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$sheet->getStyle('a3:p4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);


$sheet->getStyle('A1:A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
$style = $sheet->getStyle("A1:p2");
$style->getFont()->setSize(14)->setBold(true);


$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(16);
$sheet->getColumnDimension('d')->setWidth(30);
$sheet->getColumnDimension('e')->setWidth(8);
$sheet->getColumnDimension('f')->setWidth(8);
$sheet->getColumnDimension('g')->setWidth(8);
$sheet->getColumnDimension('h')->setWidth(8);
$sheet->getColumnDimension('i')->setWidth(8);
$sheet->getColumnDimension('j')->setWidth(8);
$sheet->getColumnDimension('k')->setWidth(8);
$sheet->getColumnDimension('l')->setWidth(8);
$sheet->getColumnDimension('m')->setWidth(8);
$sheet->getColumnDimension('n')->setWidth(8);
$sheet->getColumnDimension('o')->setWidth(8);
$sheet->getColumnDimension('p')->setWidth(8);
 

$n=3;
for ($ch = 65; $ch <= 80; $ch++) {
	$cln=chr($ch).$n;
	$sheet->getStyle($cln)->applyFromArray($borderStyle);
} 	
$n=4;
for ($ch = 65; $ch <= 80; $ch++) {
	$cln=chr($ch).$n;
	$sheet->getStyle($cln)->applyFromArray($borderStyle);
} 	


$dpn='';

$n=5;
$no=1;
$sht=0;
$dhnd=0;
$damt=0;
$ghnd=0;
$gamt=0;
$p=2;
$dp='';
$sfta=$sftb=$sftc=$catap=$catab=$catar=$catanb=$catacn=$catao=$cataap=0;
$sfttot=$catatot=0;
$gsfta=$gsftb=$gsftc=$gcatap=$gcatab=$gcatar=$gcatanb=$gcatacn=$gcatao=$gcataap=0;
$gsfttot=$gcatatot=0;
foreach ($mccodes as $row) {  
 
	if ($dp<>$row->mdeptcode) {
		if (strlen($dp)>0) {
//			$n++;

			$rw=$dpn.' Total'; 
			$cln='c'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sfta; 
			$cln='e'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sftb; 
			$cln='f'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sftc; 
			$cln='g'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sfttot; 
			$cln='h'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catap; 
			$cln='i'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catab; 
			$cln='j'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catar; 
			$cln='k'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catanb; 
			$cln='l'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catacn; 
			$cln='m'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catao; 
			$cln='n'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$cataap; 
			$cln='o'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catatot; 
			$cln='p'.$n;
			$sheet->setCellValue($cln, $rw);

			$rng='A'.$n.':P'.$n;
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$sheet->getStyle($rng)->getFont()->setBold(true);

			$sheet->getStyle($rng)->getFill()
    			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    			->getStartColor()->setRGB('D9D9D9'); // Yellow background
		

			$sfta=$sftb=$sftc=$catap=$catab=$catar=$catanb=$catacn=$catao=$cataap=0;
$sfttot=$catatot=0;

	$n++;
	$no++;



		}
$dp=$row->mdeptcode;
$dpn=$row->mdeptname;
		}

			$sfta=$sfta+$row->A;
			$sftb=$sftb+$row->B;
			$sftc=$sftc+$row->C;
			$sfttot=$sfttot+$row->Shift_Total;
	
			$catap=$catap+$row->Permanent;
			$catab=$catab+$row->Budli;
			$catar=$catar+$row->Retired;
			$catanb=$catanb+$row->New_Budli;
			$catacn=$catacn+$row->Contract;
			$catao=$catao+$row->Outsider;
			$cataap=$cataap+$row->Apprentice;
			$catatot=$catatot+$row->Category_Total;
	
			$gsfta=$gsfta+$row->A;
			$gsftb=$gsftb+$row->B;
			$gsftc=$gsftc+$row->C;
			$gsfttot=$gsfttot+$row->Shift_Total;

			$gcatap=$gcatap+$row->Permanent;
			$gcatab=$gcatab+$row->Budli;
			$gcatar=$gcatar+$row->Retired;
			$gcatanb=$gcatanb+$row->New_Budli;
			$gcatacn=$gcatacn+$row->Contract;
			$gcatao=$gcatao+$row->Outsider;
			$gcataap=$gcataap+$row->Apprentice;
			$gcatatot=$gcatatot+$row->Category_Total;


 		



	$cln='A'.$n;
	$rw=$no;
	$sheet->setCellValue($cln, $no);
	$rw=$row->Date; 
	$cln='B'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->mdeptname; 
	$cln='c'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->desig; 
	$cln='d'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->A; 
	$cln='e'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->B; 
	$cln='f'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->C; 
	$cln='g'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Shift_Total; 
	$cln='h'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Permanent; 
	$cln='i'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Budli; 
	$cln='j'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Retired; 
	$cln='k'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->New_Budli;
	$cln='l'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Contract;
	$cln='m'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Outsider;
	$cln='n'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Apprentice;
	$cln='o'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw=$row->Category_Total;
	$cln='p'.$n;
	$sheet->setCellValue($cln, $rw);
	$rw='dp ='.$dp;	
	$cln='q'.$n;
//	$sheet->setCellValue($cln, $rw);
	
 	for ($ch = 65; $ch <= 80; $ch++) {
		$cln=chr($ch).$n;
		$sheet->getStyle($cln)->applyFromArray($borderStyle);

	} 	
	$n++;
	$no++;
}

//	$n++;
	$no++;

//dept total
			$rw=$dpn.' Total'; 
			$cln='c'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sfta; 
			$cln='e'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sftb; 
			$cln='f'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sftc; 
			$cln='g'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$sfttot; 
			$cln='h'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catap; 
			$cln='i'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catab; 
			$cln='j'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catar; 
			$cln='k'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$catanb; 
			$cln='l'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catacn; 
			$cln='m'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catao; 
			$cln='n'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$cataap; 
			$cln='o'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$catatot; 
			$cln='p'.$n;
			$sheet->setCellValue($cln, $rw);

			$rng='A'.$n.':P'.$n;
			$sheet->getStyle($rng)->applyFromArray($borderStyle);
			$sheet->getStyle($rng)->getFont()->setBold(true);

			$sheet->getStyle($rng)->getFill()
    			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    			->getStartColor()->setRGB('D9D9D9'); // Yellow background


			$sheet->getStyle($cln)->applyFromArray($borderStyle);
$n++;
//grand total
			$rw='Grand Total'; 
			$cln='c'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gsfta; 
			$cln='e'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gsftb; 
			$cln='f'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gsftc; 
			$cln='g'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gsfttot; 
			$cln='h'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gcatap; 
			$cln='i'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gcatab; 
			$cln='j'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gcatar; 
			$cln='k'.$n;
			$sheet->setCellValue($cln, $rw);
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$rw=$gcatanb; 
			$cln='l'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$gcatacn; 
			$cln='m'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$gcatao; 
			$cln='n'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$gcataap; 
			$cln='o'.$n;
			$sheet->setCellValue($cln, $rw);
			$rw=$gcatatot; 
			$cln='p'.$n;
			$sheet->setCellValue($cln, $rw);

			$rng='A'.$n.':P'.$n;
			$sheet->getStyle($cln)->applyFromArray($borderStyle);
			$sheet->getStyle($rng)->getFont()->setBold(true);

			$sheet->getStyle($rng)->getFill()
    			->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    			->getStartColor()->setRGB('D9D9D9'); // Yellow background


			$sheet->getStyle($rng)->applyFromArray($borderStyle);






$pn='p'.$n;
$style = $sheet->getStyle(('A3:'.$pn));
$style->getFont()->setSize(10);

$sheet->getStyle('A3:'.$pn)->getAlignment()->setWrapText(true);





$filename="Attendance Check List for ".$from_date.' To '.$to_date.".xlsx";
$sheet->getPageSetup()
->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_PORTRAIT)
->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);


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

 		

 

}
