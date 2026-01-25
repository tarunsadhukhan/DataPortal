<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_purchase extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('jute_model');
		$this->load->model('purchase_list_model');
		$this->load->model('purchase_list_item_wise_model');
		$this->load->model('indent_detail_report_model');
		$this->load->model('purchase_reservations_model');
		$this->load->model('purchase_bill_pass_model');
		$this->load->model('Purchase_material_inward_model');
		$this->load->model('Purchase_list_include_canceld_model');
		$this->load->model('Purchase_indent_model');
		$this->load->model('All_indent_list_model');
		$this->load->model('All_indent_list_itemwise_model');
		$this->load->model('Indent_waiting_for_po_model');
		$this->load->model('Outstanding_indent_model');
		$this->load->model('All_po_list_model');
		$this->load->model('All_po_list_itewmwise_model');
		$this->load->model('All_po_list_suppwise_model');
		$this->load->model('Outstanding_po_list_model');
		$this->load->model('Outstanding_po_list_itemwise_model');
		$this->load->model('Outstanding_po_list_suppwise_model');
		$this->load->model('All_sr_register_model');
		
    }
 
	
	public function ajax_list($mainmenuId,$submenuId, $companyId, $from_date,$to_date){
		
		$list = $this->jute_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		
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
	
	public function ajax_list_purchase_order_list_including_cancel($mainmenuId,$submenuId, $companyId, $from_date,$to_date){
		$list = $this->Purchase_list_include_canceld_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->po_id;
			$row[] = $loc->created_by;
			$row[] = $loc->created_date;
			
			$row[] = $loc->last_modified_by;	
			$row[] = $loc->last_modified_date;	
			$row[] = $loc->bill_to_address;	
			$row[] = $loc->bill_to_state_name;	
			$row[] = $loc->ship_to_address;	
			$row[] = $loc->ship_to_state_name;	
			$row[] = $loc->credit_days;	
			$row[] = $loc->po_date;	
			$row[] = $loc->po_sequence_no;	
			$row[] = $loc->source;	
			$row[] = $loc->tax_payable;	
			$row[] = $loc->delivery_timeline;	
			$row[] = $loc->supplier_branch;	
			$row[] = $loc->billing_branch;	
			$row[] = $loc->category;	

			$row[] = $loc->net_amount;	
			$row[] = $loc->total_amount;	
			$row[] = $loc->tax_type;	
			$row[] = $loc->item_group;	
			$row[] = $loc->advance_type;	
			$row[] = $loc->advance_percentage;	
			$row[] = $loc->advance_amount;	
			$row[] = $loc->Status;	
			$row[] = $loc->Budget_Head;	
			$row[] = $loc->indent_squence_no;	
			$row[] = $loc->company_code;	
			$row[] = $loc->branch_name;	
			$row[] = $loc->branch_address;	
			$row[] = $loc->group_desc;	
			$row[] = $loc->supp_name;	
			$row[] = $loc->customer;	
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->Purchase_list_include_canceld_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Purchase_list_include_canceld_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_All_indent_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==493){						
			$list = $this->All_indent_list_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_indent_list_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_indent_list_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
							$mrowname = $array_keys[$i];
							$row[] = $loc->$mrowname;
//							$row[] = $no;
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_All_sr_register(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==532){						
			$list = $this->All_sr_register_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_sr_register_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_sr_register_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}
		
		
		$array_keys = array_keys($columns);		
		$data = array();
		$no = $_POST['start'];
		$totsramt=0;
		$cnt=count($array_keys);
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
					if ($i==6) {
						$totsramt=$totsramt+ $loc->$mrowname;
					}

				}
			
				
			}
			$data[] = $row;
		}
		$row = array();
		if($array_keys){
			for($i=0; $i<count($array_keys); $i++){
				if($i<=4){
					$row[] = ' ';
				}	

				if($i==6){
					$row[] = $totsramt;
				}
				if($i==5){
					$row[] = 'Grand Total';
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_All_indent_itemwise_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==155){						
			$list = $this->All_indent_list_itemwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_indent_list_itemwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_indent_list_itemwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_indent_waiting_for_po_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==533){						
			$list = $this->Indent_waiting_for_po_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Indent_waiting_for_po_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Indent_waiting_for_po_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_Outstanding_indent_list(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==564){						
			$list = $this->Outstanding_indent_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Outstanding_indent_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Outstanding_indent_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_all_po_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==492){						
			$list = $this->All_po_list_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_po_list_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_po_list_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_outstanding_po_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==538){						
			$list = $this->Outstanding_po_list_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Outstanding_po_list_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Outstanding_po_list_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_outstanding_po_itemwise_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==662){						
			$list = $this->Outstanding_po_list_itemwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Outstanding_po_list_itemwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Outstanding_po_list_itemwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_outstanding_po_suppwise_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==663){						
			$list = $this->Outstanding_po_list_suppwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Outstanding_po_list_suppwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Outstanding_po_list_suppwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_all_po_itemwise_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==661){						
			$list = $this->All_po_list_itewmwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_po_list_itewmwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_po_list_itewmwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}
	public function ajax_all_po_suppwise_List(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==660){						
			$list = $this->All_po_list_suppwise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->All_po_list_suppwise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->All_po_list_suppwise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_list_purchase_order_list($mainmenuId,$submenuId, $companyId, $from_date,$to_date){

		// $mainmenuId=$_POST['mainmenuId'];
		// $submenuId=$_POST['submenuId'];
		// $companyId=$_POST['companyId'];
		// $from_date=$_POST['from_date'];
		// $to_date=$_POST['to_date'];
		
		$list = $this->purchase_list_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->po_id;
			$row[] = $loc->created_by;
			$row[] = $loc->created_date;
			
			$row[] = $loc->last_modified_by;	
			$row[] = $loc->last_modified_date;	
			$row[] = $loc->bill_to_address;	
			$row[] = $loc->bill_to_state_name;	
			$row[] = $loc->ship_to_address;	
			$row[] = $loc->ship_to_state_name;	
			$row[] = $loc->credit_days;	
			$row[] = $loc->po_date;	
			$row[] = $loc->po_sequence_no;	
			$row[] = $loc->source;	
			$row[] = $loc->tax_payable;	
			$row[] = $loc->delivery_timeline;	
			$row[] = $loc->supplier_branch;	
			$row[] = $loc->billing_branch;	
			$row[] = $loc->category;	

			$row[] = $loc->net_amount;	
			$row[] = $loc->total_amount;	
			$row[] = $loc->tax_type;	
			$row[] = $loc->item_group;	
			$row[] = $loc->advance_type;	
			$row[] = $loc->advance_percentage;	
			$row[] = $loc->advance_amount;	
			$row[] = $loc->Status;	
			$row[] = $loc->Budget_Head;	
			$row[] = $loc->indent_squence_no;	
			$row[] = $loc->company_code;	
			$row[] = $loc->branch_name;	
			$row[] = $loc->branch_address;	
			$row[] = $loc->group_desc;	
			$row[] = $loc->supp_name;	
			$row[] = $loc->customer;	
			
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->purchase_list_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->purchase_list_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_purchase_order_item_wise_list($mainmenuId,$submenuId, $companyId, $from_date,$to_date){
		
		$list = $this->purchase_list_item_wise_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->po_detail_id;
			$row[] = $loc->qty;
			$row[] = $loc->rate;
			$row[] = $loc->rate_lastpurchase;
			$row[] = $loc->indent;
			$row[] = $loc->indent_detail;
			$row[] = $loc->item;
			$row[] = $loc->tax;
			$row[] = $loc->installation_rate;
			$row[] = $loc->installation_amount;
			$row[] = $loc->make;
			$row[] = $loc->uom_code;
			$row[] = $loc->po_sequence_no;
			$row[] = $loc->po_date;
			$row[] = $loc->status_name;
			$row[] = $loc->name;
			$row[] = $loc->group_code;
			$row[] = $loc->group_desc;
			$row[] = $loc->item_code;
			$row[] = $loc->item_desc;
			$row[] = $loc->item_wise_value;
			$row[] = $loc->supplier;
			$row[] = $loc->customer;
			$row[] = $loc->po_value_without_tax;
			$row[] = $loc->po_gross_value_with_tax;
			$row[] = $loc->source;
			$data[] = $row;

			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->purchase_list_item_wise_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->purchase_list_item_wise_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}


	public function ajax_indent_detail_report($mainmenuId,$submenuId, $companyId, $from_date,$to_date){
		
		$list = $this->indent_detail_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($list);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $loc) {
			$no++;
			$action ='';
			$row = array();
			$row[] = $no;
			$row[] = $loc->INDENT_NO;
			$row[] = $loc->INDENT_SRL_NO;
			$row[] = date('d-m-Y',strtotime($loc->IndentDate));
			$row[] = $loc->branch_name;
			$row[] = $loc->prj_name;
			$row[] = $loc->Indent_type;
			$row[] = $loc->itemcode;
			$row[] = $loc->INDENT_QTY;
			$row[] = $loc->item_desc;
			$row[] = $loc->UOM_CODE;
			$row[] = $loc->Remarks;
			$row[] = $loc->OutSt_Qty;
			$row[] = $loc->cancelled_qty;
			$row[] = $loc->cancelled_date ? date('d-m-Y',strtotime($loc->cancelled_date)) :"";
			$row[] = $loc->Indentstatus;
			$row[] = $loc->supp_name;
			$row[] = $loc->po_num;
			$row[] = $loc->LINE_ITEM_NUM;
			$row[] = $loc->PO_DATE ? date('d-m-Y',strtotime($loc->PO_DATE)) : "";
			$row[] = $loc->poQuantity;
			$row[] = $loc->Pending_Qty_PO;
			$row[] = $loc->STORE_RECEIVE_NO;
			$row[] = $loc->SRdate ? date('d-m-Y',strtotime($loc->SRdate)) : "";
			$row[] = $loc->srQuantity;
			$data[] = $row;

			
			
			
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->indent_detail_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->indent_detail_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_purchase_report(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";
		if($submenuId==609){						
			$list = $this->purchase_reservations_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->purchase_reservations_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->purchase_reservations_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}

		if($submenuId==246){
			$list = $this->purchase_bill_pass_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->purchase_bill_pass_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->purchase_bill_pass_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}
		if($submenuId==611){	
			$sno=null;							
			$list = $this->Purchase_material_inward_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Purchase_material_inward_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Purchase_material_inward_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}
		if($submenuId==154){	
									
			$list = $this->Purchase_indent_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Purchase_indent_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Purchase_indent_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
			// $this->varaha->print_arrays($this->session->userdata('companyId'), $this->data['menudit']);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");			
			$this->data['from_date'] = date('m-01-Y',time());
			$this->data['to_date'] = date('m-t-Y',time());
			$this->data['submenuId'] = "";
			$this->data['companyId'] = "";
			$this->data['controller'] = "reports_purchase";
			$this->page_construct('purchase/dashboard',$this->data);
			
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
			$this->data['controller'] = "reports_purchase";
			$this->data['menudit'] = $this->varaha_model->getMenuData($submenuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");		
			$this->data['columns'] = $this->columns->getReportColumns($submenuId);
			$this->data['tableBorders']="";
			$this->data['itcod']="";	
			$this->data['itemdesc']="";	
			$this->data['suppname']="";	
			$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($this->data['from_date'])) . " To ".date('d-m-Y', strtotime($this->data['to_date']));
			//$this->session->userdata('companyname');
			$query = $this->varaha_model->getEmbedGoogleLinks($company,$submenuId);
				
				if($query){
					// $this->varaha->print_arrays($this->data['menudit']);
					$this->data['embed_url'] = $query;
					$this->page_construct('embedreps/embedreports',$this->data);
				
				}else{
					if($submenuId==562){
						$this->data['function_name']  = 'ajax_list_purchase_order_list';
						$this->page_construct('purchase/report',$this->data);
					}else
					if($submenuId==563){
						$this->data['function_name']  = 'ajax_list_purchase_order_item_wise_list';
						$this->page_construct('purchase/report',$this->data);
					}else if($submenuId==609){
						$this->data['function']  = 'ajax_purchase_report';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==611){
						$this->data['function']  = 'ajax_purchase_report';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==246){
						$this->data['function']  = 'ajax_purchase_report';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==154){
						$this->data['function']  = 'ajax_purchase_report';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==492){
						$this->data['function']  = 'ajax_all_po_list';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==538){
						$this->data['function']  = 'ajax_outstanding_po_list';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==662){
						$this->data['function']  = 'ajax_outstanding_po_itemwise_list';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==663){
						$this->data['function']  = 'ajax_outstanding_po_suppwise_list';
						$this->page_construct('purchase/purchasereport',$this->data);
					}else if($submenuId==661){
						$this->data['function']  = 'ajax_all_po_itemwise_List';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==660){
						$this->data['function']  = 'ajax_all_po_suppwise_List';
						$this->page_construct('purchase/reportnew',$this->data);
					}else if($submenuId==493){
						$this->data['function']  = 'ajax_All_indent_List';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==155){
						$this->data['function']  = 'ajax_All_indent_itemwise_List';
						$this->page_construct('purchase/purchasereport',$this->data);	
					}else if($submenuId==532){
						$this->data['function']  = 'ajax_All_sr_register';
						$this->page_construct('purchase/purchasereport',$this->data);	
					}else if($submenuId==533){
						$this->data['function']  = 'ajax_indent_waiting_for_po_List';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==564){
						$this->data['function']  = 'ajax_Outstanding_indent_list';
						$this->page_construct('purchase/reportnew',$this->data);	
					}else if($submenuId==631){
						$this->data['function_name']  = 'ajax_list_purchase_order_list_including_cancel';
						$this->page_construct('purchase/report',$this->data);
								
					}else{
						$this->page_construct('purchase/notfound',$this->data);
					}
				}
			
			// $this->varaha->print_arrays($this->data['report_title']);
			
			
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
			$this->data['controller'] = "reports_purchase";
			if($_POST['from_date'] && $_POST['to_date']){
				$this->data['report_title'] = $this->data['menuName'] ." From ".date('d-m-Y',strtotime($_POST['from_date']))." To ". date('d-m-Y',strtotime($_POST['to_date']));
			}else{
				$this->data['report_title'] = $this->data['menuName'];
			}

			$this->data['itcod']=$_POST['itcode_chk'];
			$this->data['itemdesc']=$_POST['itemdesc_chk'];
			$this->data['suppname']=$_POST['suppname_chk'];
	
			$this->data['tableBorders']="";
			$this->data['sno']=null;
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			$this->load->model('Api_model'); 
			$cname=$this->Api_model->getCompanyName($this->data['companyId']);

			$this->data['company_name'] = $cname;
				$filename = $this->data['report_title'].date('Ymdhis');
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $_POST['from_date'],
					'to_date' => $_POST['to_date'],
					'itemdesc' => $_POST['itemdesc_chk'],
					'itcod' => $_POST['itcode_chk'],
					'suppname' => $_POST['suppname_chk'],
					
				);

		 		 

				$viewtype= 0;

				if($this->data['submenuId']==562){
					$this->data['function_name']  = 'ajax_list_purchase_order_list';
				}else if($this->data['submenuId']==631){
					$this->data['function_name']  = 'ajax_list_purchase_order_list_including_cancel';
				}else
				if($this->data['submenuId']==563){
					$this->data['function_name']  = 'ajax_list_purchase_order_item_wise_list';
				}else if($this->data['submenuId']==609){
					$this->data['function']  = 'ajax_purchase_report';
					$viewtype= 1;	
				}else if($this->data['submenuId']==246){
						$this->data['function']  = 'ajax_purchase_report';
						$viewtype= 1;	
				}else if($this->data['submenuId']==154){
					$this->data['function']  = 'ajax_purchase_report';
					// $viewtype= 1;	
				}else if($this->data['submenuId']==492){
					$this->data['function']  = 'ajax_all_po_list';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==538){
					$this->data['function']  = 'ajax_outstanding_po_list';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==662){
					$this->data['function']  = 'ajax_outstanding_po_itemwise_list';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==663){
					$this->data['function']  = 'ajax_outstanding_po_suppwise_list';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==661){
					$this->data['function']  = 'ajax_all_po_itemwise_List';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==660){
					$this->data['function']  = 'ajax_all_po_suppwise_List';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==493){
					$this->data['function']  = 'ajax_All_indent_List';
					 $viewtype= 1;	
 				}else if($this->data['submenuId']==155){
					$this->data['function']  = 'ajax_All_indent_itemwise_List';
					 $viewtype= 1;	
 				}else if($this->data['submenuId']==532){
					$this->data['function']  = 'ajax_All_sr_register';
					 $viewtype= 1;	
 				}else if($this->data['submenuId']==533){
					$this->data['function']  = 'ajax_indent_waiting_for_po_List';
				//	echo 'fileter data for 533';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==564){
					$this->data['function']  = 'ajax_Outstanding_indent_list';
				//	echo 'fileter data for 533';
					 $viewtype= 1;	
				}else if($this->data['submenuId']==611){
					$this->data['function']  = 'ajax_purchase_report';
					$viewtype= 1;							
				}else{
					$this->page_construct('purchase/notfound',$this->data);
				}
				
				// $this->varaha->print_arrays($this->data['res']);
				if($this->data['submenuId']==562){
					$this->data['res'] = $this->purchase_list_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportpurchaselistprint', $this->data, true);
				}else if($this->data['submenuId']==631){
					$this->data['res'] = $this->Purchase_list_include_canceld_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportpurchaselistprint', $this->data, true);
				}else if($this->data['submenuId']==563){
					$this->data['res'] = $this->purchase_list_item_wise_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportpurchaseitemwiselistprint', $this->data, true);
				}else if($this->data['submenuId']==609){
					$this->data['res'] = $this->purchase_reservations_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==246){
						$this->data['res'] = $this->purchase_bill_pass_model->directReport($perms);
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
						$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==492){
					$this->data['res'] = $this->All_po_list_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==538){
					$this->data['res'] = $this->Outstanding_po_list_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==662){
					$this->data['res'] = $this->Outstanding_po_list_itemwise_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==663){
					$this->data['res'] = $this->Outstanding_po_list_suppwise_model->directReport($perms);
				
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/outstanding_po_list_suppwise', $this->data, true);
			//		$html = $this->load->view('store/store_issue_checklist', $this->data, true);
				}else if($this->data['submenuId']==661){
					$this->data['res'] = $this->All_po_list_itewmwise_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
			}else if($this->data['submenuId']==660){
				$this->data['res'] = $this->All_po_list_suppwise_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/reportprintnew', $this->data, true);
		}else if($this->data['submenuId']==493){
					$this->data['res'] = $this->All_indent_list_model->directReport($perms);
				//	var_dump($this->data['res']);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
//					echo $html;
			}else if($this->data['submenuId']==155){
				$this->data['res'] = $this->All_indent_list_itemwise_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/reportprintnew', $this->data, true);

			}else if($this->data['submenuId']==532){
				$this->data['res'] = $this->All_sr_register_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/all_sr_register', $this->data, true);
			// echo $html;
			}else if($this->data['submenuId']==533){
				$this->data['res'] = $this->Indent_waiting_for_po_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/reportprintnew', $this->data, true);
			}else if($this->data['submenuId']==564){
				$this->data['res'] = $this->Outstanding_indent_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/reportprintnew', $this->data, true);
			}else if($this->data['submenuId']==154){
					$this->data['res'] = $this->Purchase_indent_model->directReport($perms);					
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$this->data['sno']=true;
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else if($this->data['submenuId']==611){
					$this->data['res'] = $this->Purchase_material_inward_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportprintnew', $this->data, true);
				}else{
					$html = $this->load->view('purchase/reportprint', $this->data, true);
				}

			if($_POST['type']==1){ // PDF
				$this->pdf($html,$filename);
			}
			
			if($_POST['type']==2){ // EXCEL
				// $this->varaha->print_arrays($html);
				$this->excel($html,$filename);
				
			}

			if($_POST['type']==3){ // PRINT
				echo $html;
				
			}

			if($_POST['type']==4){ // GRID
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				if($viewtype==1){
					$this->page_construct('purchase/purchasereport',$this->data);
				}else{
					$this->page_construct('purchase/report',$this->data);
				}
				
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
				   
	   
	   
	//    header('Content-Encoding: UTF-8');
	//    header('Content-Type: application/vnd.ms-excel');
	//    header('Content-Type: UTF-8');
	//    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	//    header('Cache-Control: max-age=0');		
	//    mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');

	   ob_end_clean();
	   header('Content-Encoding: UTF-8');
	   header('Content-Type: application/vnd.ms-excel');
	//    header('Content-Type: UTF-8');
	   header("Content-type: application/vnd.ms-excel" );
	   header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	   header('Cache-Control: max-age=0');		
	   header("Pragma: no-cache");
	   header("Expires: 0");
	   ob_end_clean();
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
	
}
