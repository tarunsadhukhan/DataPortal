<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Mpdf\Mpdf;

class Reports_jute extends MY_Controller {

	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		
		$this->load->model('jute_model');
		$this->load->model('jute_with_value_model');
		$this->load->model('jute_issue_rec_summary_model');
		$this->load->model('jute_issue__summary_model');
		$this->load->model('jute_mr_in_stock_model');
		$this->load->model('jute_godown_wise_stock_model');
		$this->load->model('jute_quantity_wise_model');
		$this->load->model('mr_wise_model');
		$this->load->model('jute_percent_claims_model');
		$this->load->model('jute_claim_deveation_model');
		$this->load->model('mukham_model');
		$this->load->model('jute_monthwise_model');
		$this->load->model('jute_daywise_model');
		$this->load->model('mr_wise_sales_model');
		$this->load->model('batch_deviation_model');
		$this->load->model('Jute_inventory0705_model');
//		$this->load->libraries('Html_to_excel');
$this->load->library('excel');
		
		
    }
    
		
	public function ajax_list(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->jute_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->company_code;
			$row[] = $loc->company_name;
			$row[] = $loc->company_code;
			$row[] = $loc->company_name;
			$row[] = $loc->company_code;
			$row[] = $loc->company_name;
			$row[] = $loc->company_code;
			$row[] = $loc->company_name;
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_jute_inventory0705(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->Jute_inventory0705_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $loc->Quality;
			$row[] = $loc->Opening_Stock;
			$row[] = $loc->recvweight;
			$row[] = $loc->issueweight;
			$row[] = $loc->closweight;
 			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Jute_inventory0705_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Jute_inventory0705_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_jute_with_value(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->jute_with_value_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Quality_ID;
			$row[] = $loc->Quality_Name;
			$row[] = $loc->Opening_Bales;
			$row[] = $loc->Receipt_Bales;
			$row[] = $loc->Issue_Bales;
			$row[] = $loc->Sold_Bales;

			$close_bales = ($loc->Opening_Bales + $loc->Receipt_Bales) - ($loc->Sold_Bales + $loc->Issue_Bales);
			$row[] = round($close_bales,2);

			$row[] = $loc->Opening_Drums;
			$row[] = $loc->Drums;
			$row[] = $loc->Drums_Issued;
			$row[] = $loc->Drums_Sold;
			$close_drums = ($loc->Opening_Drums + $loc->Drums) - ($loc->Drums_Sold + $loc->Drums_Issued);
			$row[] = round($close_drums,2);

			$row[] = $loc->Opening_Wt;			
			$row[] = $loc->Receipt_Wt;
			$row[] = $loc->Issued_Wt;
			$row[] = $loc->Sold_Wt;
			$colse_wt = ($loc->Opening_Wt + $loc->Receipt_Wt) - ($loc->Issued_Wt + $loc->Sold_Wt);
			$row[] = round($colse_wt,2);

			$row[] = $loc->Avg_Issue_Rate;
			$row[] = $loc->Issued_Val;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_with_value_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_with_value_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_jute_issue_rec_summary(){

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$jutesummary=$_POST['jutesummary'];
		$mrno=$_POST['mrno'];

		// $this->varaha->print_arrays($jutesummary);

		if($jutesummary=="1"){
			$list = $this->jute_issue_rec_summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno);

			// $this->varaha->print_arrays($jutesummary);
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $loc) {
				$no++;
				$action ='';
				$row = array();
				$row[] = $no;
				$row[] = $loc->Issue_Date;
				$row[] = $loc->MR_No;
				$row[] = $loc->Quality;
				$row[] = $loc->Godown_ID;
				$row[] = $loc->Pack_Type;
				$row[] = $loc->Quantity;
				$row[] = $loc->Weight;
				$row[] = $loc->Unit;
				$row[] = $loc->Rate;
				$row[] = $loc->Issue_Value;
				$row[] = $loc->MR_Line_No;
				$row[] = $loc->Quality_ID;
				$row[] = $loc->Godown_Name;
				$row[] = $loc->Status;
				$data[] = $row;		
				
			}
	
			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->jute_issue_rec_summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno),
							"recordsFiltered" => $this->jute_issue_rec_summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno),
							"data" => $data,
					);
			echo json_encode($output);
		}else{
			$list = $this->jute_issue__summary_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno);

			// $this->varaha->print_arrays($jutesummary);
			$data = array();
			$no = $_POST['start'];
			foreach ($list as $loc) {
				$no++;
				$action ='';
				$row = array();
				$row[] = $no;
				$row[] = $loc->MR_Date;
				$row[] = $loc->MR_No;
				$row[] = $loc->Quality;
				$row[] = $loc->Godown_ID;
				$row[] = $loc->Pack_Type;
				$row[] = $loc->Quantity;
				$row[] = $loc->Weight;
				$row[] = $loc->Unit;
				$row[] = $loc->Rate;
				$row[] = $loc->Receipt_Value;
				$row[] = $loc->MR_Line_No;
				$row[] = $loc->Quality_ID;
				$row[] = $loc->Godown_Name;
				$row[] = $loc->Status;
				$data[] = $row;		
				
			}
	
			$output = array(
							"draw" => $_POST['draw'],
							"recordsTotal" => $this->jute_issue__summary_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno),
							"recordsFiltered" => $this->jute_issue__summary_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno),
							"data" => $data,
					);
			echo json_encode($output);
		}
		
	}
	public function ajax_list_jute_mr_in_stock(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->jute_mr_in_stock_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->MR_Date;
			$row[] = $loc->MR_No;
			$row[] = $loc->Quality_ID;
			$row[] = $loc->Quality;
			$row[] = $loc->Godown_ID;
			$row[] = $loc->Godown_Name;
			$row[] = $loc->Status;
			$row[] = $loc->MR_Line_No;
			$row[] = $loc->Bales;
			$row[] = $loc->Issue_Bales;
			$row[] = $loc->Sold_Bales;
			$row[] = $loc->Bales_Stock;
			$row[] = $loc->Drums;
			$row[] = $loc->Drums_Issued;
			$row[] = $loc->Drums_Sold;
			$row[] = $loc->Drums_Stock;
			$row[] = $loc->Receipt_Wt;
			$row[] = $loc->Issued_Wt;
			$row[] = $loc->Sold_Wt;
			$row[] = $loc->Stock_Qnt;	
			$data[] = $row;		
			
			}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_mr_in_stock_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_mr_in_stock_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_jute_quantity_wise(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->jute_quantity_wise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->J_code;
			$row[] = $loc->Quality;
			$row[] = number_format($loc->Opening_Weight,2);
			$row[] = number_format($loc->Receipt_Weight,2);
			$row[] = number_format($loc->Issued_Weight,2);
			$row[] = number_format($loc->Closing_Weight,2);
			$row[] = number_format($loc->Opening_Bales,2);
			$row[] = number_format($loc->Receipt_Bales,2);
			$row[] = number_format($loc->Issued_Bales,2);
			$row[] = number_format($loc->Closing_Bales,2);
			$row[] = number_format($loc->Opening_Drums,2);
			$row[] = number_format($loc->Receipt_Drums,2);
			$row[] = number_format($loc->Issued_Drums,2);
			$row[] = number_format($loc->Closing_Drums,2);
			$data[] = $row;		
			
			}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_quantity_wise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_quantity_wise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_jute_godown_wise_stock(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->jute_godown_wise_stock_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Godown_ID;
			$row[] = $loc->item_desc;
			$row[] = $loc->Quality;
			$row[] = $loc->Bales;
			$row[] = $loc->Drums;
			$row[] = $loc->Weight;
			$row[] = $loc->QNT;
			$row[] = $loc->Quality_ID;
			$row[] = $loc->Godown_Name;
			$data[] = $row;		
			
			}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_godown_wise_stock_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_godown_wise_stock_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_mr_wise(){
	
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$this->data['eb_no']=$_POST['eb_no_att'];
//		$this->data['mrno'] = $_POST['mr_no'];
//		$jutesummary=$_POST['jutesummary'];
		$mrno=$_POST['mrno'];
//		$eb_no=$_POST['costcenter_chk'];		
//		$itcode=$this->data['costcenter']=$_POST['costcenter_chk'];

//		echo $mrno." ".$eb_no."  ".$itcode;

		$list = $this->mr_wise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->jute_received_dt;
			$row[] = $loc->mr_print_no;
			$row[] = $loc->actual_quality;
			$row[] = $loc->jute_quality;
			$row[] = $loc->jute_receive_no;
			$row[] = $loc->gdname;
			$row[] = $loc->jute_line_item_no;
			$row[] = $loc->unit;
			$row[] = $loc->noofbales;
			$row[] = $loc->actual_weight;
			$row[] = $loc->issue_date;
			$row[] = $loc->issue_quality;
			$row[] = $loc->quantity;
			$row[] = $loc->total_weight;
			$row[] = $loc->qty;
			$row[] = $loc->twt;
			$row[] = $loc->bal_qty;
			$row[] = $loc->bal_weight;
 			$data[] = $row;		
			
			}

			

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mr_wise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno),
						"recordsFiltered" => $this->mr_wise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_jute_percent_claims(){

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		
		$list = $this->jute_percent_claims_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Supp_Code;
			$row[] = $loc->Supplier_Name;
			$row[] = $loc->Total_MR;
			$row[] = $loc->Total_Pass;
			$row[] = $loc->Total_Claim;
			$row[] = $loc->Pass_percent;
			$row[] = $loc->Claim_percent;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_percent_claims_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_percent_claims_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	
	public function ajax_list_jute_claim_deveation(){

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		
		$list = $this->jute_claim_deveation_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->SUPP_CODE;
			$row[] = $loc->SUPPLIER_NAME;
			$row[] = $loc->MR_NO;
			$row[] = $loc->MR_DATE;
			$row[] = $loc->JUTE_TYPE;
			$row[] = $loc->QUALITY;
			$row[] = $loc->CONDITION;
			$row[] = $loc->ADVISED_CLAIM_KGS;
			$row[] = $loc->ACTUAL_CLAIM_KGS;
			$row[] = $loc->DEVIATION_KGS;

			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_claim_deveation_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_claim_deveation_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_mukham(){
		

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];


		$list = $this->mukham_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Supp_Code;
			$row[] = $loc->Supplier_Name;
			$row[] = $loc->Mukham;
			$row[] = $loc->Avg_Supplied_Moisture;
			$row[] = $loc->Avg_Mukam_Moisture;
			$row[] = $loc->Deviation;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mukham_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->mukham_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_jute_monthwise(){

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		
		$list = $this->jute_monthwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Year_Month;
			$row[] = $loc->Bales;
			$row[] = $loc->Issue_Bales;
			$row[] = $loc->Sold_Bales;
			$row[] = $loc->Drums;
			$row[] = $loc->Drums_Issued;
			$row[] = $loc->Drums_Sold;
			$row[] = $loc->Receipt_Wt_QNT;
			$row[] = $loc->Issued_Wt_QNT;
			$row[] = $loc->Sold_Wt_QNT;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_monthwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_monthwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_jute_daywise(){

		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		
		$list = $this->jute_daywise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->tran_date;
			$row[] = $loc->Bales;
			$row[] = $loc->Issue_Bales;
			$row[] = $loc->Sold_Bales;
			$row[] = $loc->Drums;
			$row[] = $loc->Drums_Issued;
			$row[] = $loc->Drums_Sold;
			$row[] = $loc->Receipt_Wt_QNT;
			$row[] = $loc->Issued_Wt_QNT;
			$row[] = $loc->Sold_Wt_QNT;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->jute_daywise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->jute_daywise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_list_mr_wise_sales(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		
		$list = $this->mr_wise_sales_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->Year_Month;
			$row[] = $loc->Bales;
			$row[] = $loc->Issue_Bales;
			$row[] = $loc->Sold_Bales;
			$row[] = $loc->Drums;
			$row[] = $loc->Drums_Issued;
			$row[] = $loc->Drums_Sold;
			$row[] = $loc->Receipt_Wt_QNT;
			$row[] = $loc->Issued_Wt_QNT;
			$row[] = $loc->Sold_Wt_QNT;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->mr_wise_sales_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->mr_wise_sales_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_batch_deviation(){
		
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];

		$list = $this->batch_deviation_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		$list = $list->data;
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->category;
			$row[] = $loc->planName;
			$row[] = $loc->bales;
			$row[] = $loc->drums;
			$row[] = $loc->yarnType;
			$row[] = $loc->qualities;
			$row[] = $loc->percentage;
			$row[] = $loc->actualIssue;
			$row[] = $loc->desiredIssue;
			$row[] = $loc->deviation;
			$row[] = $loc->deviationPercentage;
			$row[] = $loc->balesOrDrums;
			$row[] = $loc->value;
			$row[] = $loc->totalPlanWeight;
			$data[] = $row;		
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => count($list),
						"recordsFiltered" => count($list),
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
			
			$this->data['headtitle'] = "Dashboard";
			$this->data['mainmenuId'] = $menuId;	
					
			$this->data['menudit'] = $this->varaha_model->getMenuData($menuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");			
			$this->data['from_date'] = date('Y-m-01',time());
			$this->data['to_date'] = date('Y-m-t',time());
			$this->data['submenuId'] = "";
			$this->data['companyId'] = "";
			$this->data['controller'] = "reports_jute";
			
			$this->page_construct('jute/jutedashboard',$this->data);
			
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
			$this->data['controller'] = "reports_jute";
			$this->data['jutesummary'] = null;
			$this->data['mrno'] = null;
			$this->data['godownno'] = null;
			$this->data['srno'] = null;
			$this->data['itcod'] = $_POST['itcode_chk'];
			$this->data['costcenter'] = $_POST['costcenter_chk'];
			$this->data['agents'] = null;
			$this->data['jutequalitys'] = null;

			//			$this->data['eb_no']=$_POST['eb_no_att'];
//			$this->data['mrno'] = $_POST['mr_no'];

			echo ($this->data['eb_no'] ? "EB No : ".$this->data['eb_no'] : "");

			$this->data['menudit'] = $this->varaha_model->getMenuData($this->data['submenuId']);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");	
			$query = $this->varaha_model->getEmbedGoogleLinks($company,$submenuId);
				
				if($query){
					// $this->varaha->print_arrays($this->data['menudit']);
					$this->data['embed_url'] = $query;
					$this->page_construct('embedreps/embedreports',$this->data);
				
				}else{
						if($submenuId==90){
							$this->data['function'] = "ajax_list_jute_with_value";
						}else if($submenuId==92){
							$this->data['jutesummary'] = 1;
							$this->data['function'] = "ajax_list_jute_issue_rec_summary";
							
						}
						else if($submenuId==530){
							$this->data['from_date'] = date('Y-m-d',time());
							$this->data['function'] = "ajax_list_jute_mr_in_stock";
						}else if($submenuId==91){
							$this->data['function'] = "ajax_list_jute_quantity_wise";
						}
						else if($submenuId==93){
							$this->data['function'] = "ajax_list_jute_godown_wise_stock";
						}
						else if($submenuId==96){
							$this->data['function'] = "ajax_list_mr_wise";
						}
						else if($submenuId==227){

							$this->data['function'] = "ajax_list_jute_percent_claims";
						}
						else if($submenuId==228){
							$this->data['function'] = "ajax_list_jute_claim_deveation";
						}
						else if($submenuId==229){
							$this->data['function'] = "ajax_list_mukham";
						}
						else if($submenuId==477){
							$this->data['function'] = "ajax_list_jute_monthwise";
						}
						else if($submenuId==496){
							$this->data['function'] = "ajax_list_jute_daywise";
						}else if($submenuId==528){
							$this->data['function'] = "ajax_list_mr_wise_sales";
						}else if($submenuId==261){
							$this->data['function'] = "ajax_list_batch_deviation";
						}
						else if($submenuId==694){
							$this->data['function'] = "ajax_list_jute_inventory0705";
						}
						else{
							
							$this->page_construct('jute/notfound',$this->data);
						}
						if($submenuId==530){
							$this->data['report_title'] = $this->data['menuName'] ." Date ". date('d-m-Y', strtotime($this->data['from_date']));
						}else{
							$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($this->data['from_date'])) . " To ".date('d-m-Y', strtotime($this->data['to_date'])).($this->data['jutesummary'] ? " & Jute Issue Summary " : "");
						}
						
						
						
						

						$this->data['godowns'] = $this->varaha_model->getAllGodownsNos();			
				$this->data['agents'] = $this->varaha_model->getAllAgents($this->data['companyId']	);
				$this->data['jutequalitys'] = $this->varaha_model->getJuteQuality($this->data['companyId']);

						$this->data['menudit'] = $this->varaha_model->getMenuData($mainmenuId);
						$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");
						$this->data['columns'] = $this->columns->getReportColumns($submenuId);
						// $this->varaha->print_arrays($this->data['columns']);
						

						$this->page_construct('jute/jutereport',$this->data);
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
			$this->data['controller'] = "reports_jute";
			
			
			
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			$this->data['jutesummary'] = $_POST['jute_summary'];
			$this->data['mrno'] = $_POST['mr_no'];
			$this->data['godownno'] = $_POST['godown_no'];
			$this->data['godowns'] = $this->varaha_model->getAllGodownsNos();
			$this->data['agents'] = $this->varaha_model->getAllAgents($this->data['companyId']	);
			$this->data['jutequalitys'] = $this->varaha_model->getJuteQuality($this->data['companyId']);

			if($this->data['submenuId']==530){
				$this->data['report_title'] = $this->data['menuName'] ." Date ". date('d-m-Y', strtotime($this->data['from_date']));
			}else{
				$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($_POST['from_date'])) . " To ".date('d-m-Y', strtotime($_POST['to_date'])).($_POST['jute_summary'] ?  "& Jute ".( $_POST['jute_summary']==1 ? " Issue Summary" : " Reciept Summary") : "").($_POST['mr_no'] ? " & MR No: ". $_POST['mr_no'] : "");
			}
			if($this->data['submenuId']==90){
				$this->data['function'] = "ajax_list_jute_with_value";
			}else if($this->data['submenuId']==92){
				$this->data['function'] = "ajax_list_jute_issue_rec_summary";				
			}
			else if($this->data['submenuId']==530){

				$this->data['function'] = "ajax_list_jute_mr_in_stock";
			}
			else if($this->data['submenuId']==91){
				$this->data['function'] = "ajax_list_jute_quantity_wise";
			}
			else if($this->data['submenuId']==93){
				$this->data['function'] = "ajax_list_jute_godown_wise_stock";
			}
			else if($this->data['submenuId']==96){
				$this->data['function'] = "ajax_list_mr_wise";
			}
			else if($this->data['submenuId']==227){
				$this->data['function'] = "ajax_list_jute_percent_claims";
			}
			else if($this->data['submenuId']==228){
				$this->data['function'] = "ajax_list_jute_claim_deveation";
			}
			else if($this->data['submenuId']==229){
				$this->data['function'] = "ajax_list_mukham";
			}
			else if($this->data['submenuId']==477){
				$this->data['function'] = "ajax_list_jute_monthwise";
			}
			else if($this->data['submenuId']==496){
				$this->data['function'] = "ajax_list_jute_daywise";
			}else if($this->data['submenuId']==528){
				$this->data['function'] = "ajax_list_mr_wise_sales";
			}else if($this->data['submenuId']==261){
				$this->data['function'] = "ajax_list_batch_deviation";
			}else if($this->data['submenuId']==694){
			//	$this->data['report_title'] = $this->data['menuName'] ." Date ". date('d-m-Y', strtotime($this->data['from_date']));

				$this->data['function'] = "ajax_list_jute_inventory0705";
			}
			else{
				$this->data['function'] = "ajax_list";
			}
			
				$filename = str_replace(" ","_",$this->data['report_title'])."_".date('Ymdhis');
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $_POST['from_date'],
					'to_date' => $_POST['to_date'],
					'jutesummary' => $_POST['jute_summary'],
					'mrno' => $_POST['mr_no'],
				);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);

				if($this->data['submenuId']==0){
					$this->data['res'] = $this->jute_model->directReport($perms);
					$html = $this->load->view('jute/jutereportprint', $this->data, true);
				}
				if($this->data['submenuId']==90){					
					$this->data['res'] = $this->jute_with_value_model->directReport($perms);
					$html = $this->load->view('jute/jutereportprint', $this->data, true);
				}
				if($this->data['submenuId']==91){					
					$this->data['res'] = $this->jute_quantity_wise_model->directReport($perms);	
									
					$html = $this->load->view('jute/jutequantitywiseprint', $this->data, true);
				}
				if($this->data['submenuId']==92){					
					$this->data['res'] = $this->jute_issue_rec_summary_model->directReport($perms);
					$html = $this->load->view('jute/jute_issue_reportprint', $this->data, true);
				}
				if($this->data['submenuId']==530){					
					$this->data['res'] = $this->jute_mr_in_stock_model->directReport($perms);
					$html = $this->load->view('jute/jute_mrinstock_reportprint', $this->data, true);
				}
				// $this->varaha->print_arrays($this->data['res']);
				
				if($this->data['submenuId']==93){					
					$this->data['res'] = $this->jute_godown_wise_stock_model->directReport($perms);
					$html = $this->load->view('jute/jute_godown_wiseprint', $this->data, true);
				}
				if($this->data['submenuId']==96){					
					$this->data['res'] = $this->mr_wise_model->directReport($perms);
//					echo 'data  '.$this->data['res'];
//					var_dump($this->data['res']);

					$html = $this->load->view('jute/mr_wise_print', $this->data, true);
				}
				if($this->data['submenuId']==227){					
					$this->data['res'] = $this->jute_percent_claims_model->directReport($perms);
					$html = $this->load->view('jute/jute_percent_claimprint', $this->data, true);
				}
				if($this->data['submenuId']==228){					
					$this->data['res'] = $this->jute_claim_deveation_model->directReport($perms);
					$html = $this->load->view('jute/jute_claim_deveationprint', $this->data, true);
				}
				if($this->data['submenuId']==229){					
					$this->data['res'] = $this->mukham_model->directReport($perms);
					$html = $this->load->view('jute/mukham_print', $this->data, true);
				}
				if($this->data['submenuId']==477){					
					$this->data['res'] = $this->jute_monthwise_model->directReport($perms);
					$html = $this->load->view('jute/jute_monthwise_print', $this->data, true);
				}
				if($this->data['submenuId']==496){					
					$this->data['res'] = $this->jute_daywise_model->directReport($perms);
					$html = $this->load->view('jute/jute_daywise_print', $this->data, true);
				}
				if($this->data['submenuId']==528){					
					$this->data['res'] = $this->mr_wise_sales_model->directReport($perms);
					$html = $this->load->view('jute/mr_wise_sales_print', $this->data, true);
				}
				if($this->data['submenuId']==261){					
					$this->data['res'] = $this->batch_deviation_model->directReport($perms);
					// $this->varaha->print_arrays($this->data['res']);
					$html = $this->load->view('jute/batch_deviation_print', $this->data, true);
				}
			if($this->data['submenuId']==694){					
				echo $perms['from_date'];
				echo $perms['to_date'];
					$this->data['res'] = $this->Jute_inventory0705_model->directReport($perms);
					// $this->varaha->print_arrays($this->data['res']);
					$html = $this->load->view('jute/jute_inventory0705', $this->data, true);
				}

			if($_POST['type']==1){ // PDF
				$this->pdf($html,$filename);
			}
			
			if($_POST['type']==2){ // EXCEL
//				$this->excelexp();
	//		$this->exceldownload();			
				$this->excel($html,$filename);
			}

			if($_POST['type']==3){ // PRINT
				echo $html;
			}

			if($_POST['type']==4){ // GRID
				$this->page_construct('jute/jutereport',$this->data);
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
				   
	      ob_end_clean();
	//    if (ob_get_contents()) ob_end_clean();
	header('Content-Encoding: UTF-8');
	header('Content-Type: application/vnd.ms-excel');
    header('Content-Type: UTF-8');
	header("Content-type: application/vnd.ms-excel" );
	header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	header('Cache-Control: max-age=0');		
	header("Pragma: no-cache");
	header("Expires: 0");
    ob_end_clean();
	// if (ob_get_contents()) ob_end_clean();
	mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');	   
	echo $data;
	
	// echo $data;
	   
	//    header('Content-Encoding: UTF-8');
	//    header('Content-Type: application/vnd.ms-excel');
	//    header('Content-Type: UTF-8');
	//    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	//    header('Cache-Control: max-age=0');		
	//    mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');
	//    echo $data;

	// Headers for download 
	// header("Content-Type: application/vnd.ms-excel"); 
	// header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	// header('Cache-Control: max-age=0');		
	// header("Pragma: no-cache");
	// header("Expires: 0");
	// // Render excel data 
	// echo $data; 
	// exit;


   }
   function excel1($result, $filename) {
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
               <body>' . $result . '</body></html>';

    ob_end_clean();
    
    header('Content-Encoding: UTF-8');
    header('Content-Type: application/vnd.ms-excel; charset=UTF-8');
    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
    header('Cache-Control: max-age=0');
    header("Pragma: no-cache");
    header("Expires: 0");

    $data = mb_convert_encoding($data, 'UTF-16LE', 'UTF-8');
    echo $data;
    exit;
}


function excelexp() {
	// Load the library
	$this->load->library('excel');
	// Sample HTML data
	$htmlData = '<table border="1">
				   <tr>
					  <td>Column 1</td>
					  <td>Column 2</td>
					  <td>Column 3</td>
				   </tr>
				   <tr>
					  <td>Data 1</td>
					  <td>Data 2</td>
					  <td>Data 3</td>
				   </tr>
				</table>';

	// Load HTML data into PhpSpreadsheet
	$data = [['HTML Data'], [$htmlData]];
//	echo '<pre>';
//    print_r($data);
//    echo '</pre>';

	// Export to Excel
	$this->excel->export($data, 'export_filename');
 }

 public function exceldownload() {
	// Create a new Spreadsheet object
	$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$sdate='2023-01-01';
$cmpn='company';
$sheet->setCellValue('A1', $cmpn);
$sheet->setCellValue('A2', "Doff 10 Reports for Dated : ".$sdate);

$filename="doff10_".$sdate.'.xlsx';
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
