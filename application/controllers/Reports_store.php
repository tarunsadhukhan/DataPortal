<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_store extends MY_Controller {
	
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
		$this->load->model('jute_model');
		$this->load->model('store_issue_list_report_model');
		$this->load->model('store_Inventory_Min_Max_Report_model');
//		$this->load->model('Stores_inventory_list_report_model');	
		$this->load->model('Inventory_report_allitems_Model');
		$this->load->model('Inventory_minmax_report_model');
		$this->load->model('Store_issue_is01_report_model');
		$this->load->model('Store_issue_is02_report_model');
		$this->load->model('Store_issue_is05_report_model');
		$this->load->model('Store_issue_is06_report_model');
		$this->load->model('Store_issue_is03_report_model');
		$this->load->model('Store_item_ledger_report_model');
		$this->load->model('Store_item_monthwise_consumption_model');
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
						"recordsTotal" => $this->Attendance_checklist_Model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->Attendance_checklist_Model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		// $this->varaha->print_arrays($output);
		echo json_encode($output);
	}

	public function ajax_list_stores_issue_checklist(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->store_issue_list_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
							"recordsTotal" => $this->store_issue_list_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
							"recordsFiltered" => $this->store_issue_list_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
							"data" => $data,
					);
			//output to json format
			// $this->varaha->print_arrays($output);
			echo json_encode($output);
		}
		public function ajax_item_ledger_report(){
			$mainmenuId=$_POST['mainmenuId'];
			$submenuId=$_POST['submenuId'];
			$companyId=$_POST['companyId'];
			$from_date=$_POST['from_date'];
			$to_date=$_POST['to_date'];
			$columns = $this->columns->getReportColumns($submenuId);
			$list = $this->Store_item_ledger_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$array_keys = array_keys($columns);
				$data = array();
				$no = $_POST['start'];
			
				$cstockqty=0;
                $cstockval=0;
                $ostockqty=0;
                $ostockval=0;
                $rstockqty=0;
                $rstockval=0;
                $istockqty=0;
                $istockval=0;
				foreach ($list as $loc) {
					$no++;
					$action ='';
              
					$row = array();
					
					$mrowname = $array_keys[2];
					$tr = $loc->$mrowname;
					$mrowname = $array_keys[6];
					$oqty = $loc->$mrowname;
					$mrowname = $array_keys[7];
					$oval = $loc->$mrowname;
					$mrowname = $array_keys[8];
					$rqty = $loc->$mrowname;
					$mrowname = $array_keys[9];
					$rval = $loc->$mrowname;
					$mrowname = $array_keys[10];
					$iqty = $loc->$mrowname;
					$mrowname = $array_keys[11];
					$ival = $loc->$mrowname;
		
					if  ($tr=='O') { 
						$ostockqty=$oqty;
						$ostockval=$oval;
					 }
					 $cstockqty=$cstockqty+$oqty+$rqty-$iqty;
					 $cstockval=$cstockval+$oval+$rval-$ival;
					 $rstockqty=$rstockqty+$rqty;
					 $rstockval=$rstockval+$rval;
					 $istockqty=$istockqty+$iqty;
					 $istockval=$istockval+$ival;
		 //			 echo $cstockval;
		//			 echo $oqty.'-'.$rqty.'-'.$iqty.'='. $cstockqty;
			//		 echo 'cl qty-'.$cstockqty."<br>";
			//		 echo 'cl val-'.$cstockval."<br>";
				  
					/*
					$cstockqty=$cstockqty+$row->open_qty+$row->tranrecv_qty-$row->tranissu_qty;
                    $cstockval=$cstockval+$row->open_val+$row->tranrecv_val-$row->tranissu_val;
                    if  ($row->tran_type=='O') { 
                       $ostockqty=$row->open_qty;
                       $ostockval=$row->open_val;
                    }
                    $rstockqty=$rstockqty+$row->tranrecv_qty;
                    $rstockval=$rstockval+$row->tranrecv_val;
                    $istockqty=$istockqty+$row->tranissu_qty;
                    $istockval=$istockval+$row->tranissu_val;
			*/		
				
					if($array_keys){
						$cnt=count($array_keys);
		//				echo 'count cols==='.$cnt.'====';
						for($i=0; $i<count($array_keys); $i++){
							$mrowname = $array_keys[$i];
							
								if ($i<=$cnt-3) {
									$mrowname = $array_keys[$i];
									if ($i<6) {	
										$row[] = $loc->$mrowname;
									} 
									if ($i==6 || $i==8 || $i==10) {
										$row[] = number_format($loc->$mrowname,3);
									}	
									if ($i==7 || $i==9 || $i==11) {
										$row[] = number_format($loc->$mrowname,2);
									}	
								}
								if ($i==12) {
									$row[] = number_format(round($cstockqty,3),3);
								//	$row[] = 100;
								}
								if ($i==13) {
									$row[] = number_format(round($cstockval,2),2);
							}
		//						echo 'data col-'.$i.'='.$mrowname.'-next-';
							}
							
						}
				

					$data[] = $row;
				}
				$row = array();
				
				$row[] ='';
				$row[] = '';
				$row[] = ' ';
				$row[] ='';
				$row[] = $to_date; 
				$row[] = 'Closing' ;					
				$row[] = number_format(round($ostockqty,3),3);
				$row[] = number_format(round($ostockval,3),2);
				$row[] = number_format(round($rstockqty,3),3);
				$row[] = number_format(round($rstockval,3),2);
				$row[] = number_format(round($istockqty,3),3);
				$row[] = number_format(round($istockval,3),2);
				$row[] = number_format(round($cstockqty,3),3);					
				$row[] = number_format(round($cstockval,2),2);	
				$data[] = $row;

	//		var_dump($data);
				$output = array(
								"draw" => $_POST['draw'],
								"recordsTotal" => $this->Store_item_ledger_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
								"recordsFiltered" => $this->Store_item_ledger_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
								"data" => $data,
						);
				//output to json format
				// $this->varaha->print_arrays($output);
				echo json_encode($output);
			}
	
		public function ajax_list_stores_inventory_list(){
			$mainmenuId=$_POST['mainmenuId'];
			$submenuId=$_POST['submenuId'];
			$companyId=$_POST['companyId'];
			$from_date=$_POST['from_date'];
			$to_date=$_POST['to_date'];
			$columns = $this->columns->getReportColumns($submenuId);
			$list = $this->Inventory_report_allitems_Model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$array_keys = array_keys($columns);
			
			//	echo '1st time';
				// $this->varaha->print_arrays($list);
				$opval=$rcpval=$issval=$stkval=0;

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
								$colval = $loc->$mrowname;
								if ($i==5) {$opval=$opval+ $loc->$mrowname;
//									number_format($row['A1DOFFNO'],0)
								//	$cl=floatval($loc->$mrowname);
									$colval = sprintf("%.2f", $loc->$mrowname);
									//	$colval=number_format($cl,2);
									 
								}
								if ($i==7) {$rcpval=$rcpval+ $loc->$mrowname;
						//			$colval=number_format($loc->$mrowname, 2);
									$colval = sprintf("%.2f", $loc->$mrowname);
					}
								if ($i==9) {$issval=$issval+ $loc->$mrowname;
						//			$colval=number_format($loc->$mrowname, 2);
									$colval = sprintf("%.2f", $loc->$mrowname);
								}
								if ($i==11) {$stkval=$stkval+ $loc->$mrowname;
						//			$colval=number_format($loc->$mrowname, 2);
									$colval = sprintf("%.2f", $loc->$mrowname);
								}
									$row[] = $colval;
						

							}
							
							
						}
					}
					$data[] = $row;
				}
				$row = array();
				$row[] = '';
				$row[] = '';
				$row[] = 'Grand Total';
				$row[] = '';
				$row[] = '  ';
				$row[] = round($opval,2);
				$row[] = ' ';
				$row[] = round($rcpval,2);
				$row[] = ' ';
				$row[] = round($issval,2);
				$row[] = ' ';
				$row[] = round($stkval,2);
				$row[] = '';
				$row[] = '';
				$row[] = '';
												
			$data[] = $row;


				$output = array(
								"draw" => $_POST['draw'],
								"recordsTotal" => $this->Inventory_report_allitems_Model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
								"recordsFiltered" => $this->Inventory_report_allitems_Model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
								"data" => $data,
						);
				//output to json format
				// $this->varaha->print_arrays($output);
				echo json_encode($output);
			}
	
			public function ajax_list_item_monthwise_list(){
				$mainmenuId=$_POST['mainmenuId'];
				$submenuId=$_POST['submenuId'];
				$companyId=$_POST['companyId'];
				$from_date=$_POST['from_date'];
				$to_date=$_POST['to_date'];
				$itcode=$this->data['itcod']=$_POST['itcode_chk'];
				$columns = $this->columns->getReportColumnsm($submenuId,$from_date,$to_date,$companyId,$itcode );
				$list = $this->Store_item_monthwise_consumption_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
				$array_keys = array_keys($columns);
		//		var_dump($list);
					
					$data = array();
					$no = $_POST['start'];
					foreach ($list as $loc) {
						$no++;
						$action ='';
						$total=0;
						$nom=0;
						$avg=0;
						$row = array();
						if($array_keys){
							$cnt=count($array_keys)-3;
							for($i=0; $i<$cnt; $i++){
									$mrowname = $array_keys[$i];
									$row[] = $loc->$mrowname;
									if ($i>=2 ) {
										$total=$total+$loc->$mrowname;
										if ($loc->$mrowname>0) {
											$nom++;
										}
									}
								}
									$row[] = round($total,3);
									$row[] = $nom;
									$avg=round($total/$nom,3);
									$row[] = $avg;
							
								
							}
						$data[] = $row;
					}
			
					$output = array(
									"draw" => $_POST['draw'],
									"recordsTotal" => $this->Store_item_monthwise_consumption_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
									"recordsFiltered" => $this->Store_item_monthwise_consumption_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
									"data" => $data,
							);
					//output to json format
					// $this->varaha->print_arrays($output);
					echo json_encode($output);
				}
	
			public function ajax_list_stores_inventory_minmax_list(){
				$mainmenuId=$_POST['mainmenuId'];
				$submenuId=$_POST['submenuId'];
				$companyId=$_POST['companyId'];
				$from_date=$_POST['from_date'];
				$to_date=$_POST['to_date'];
				$columns = $this->columns->getReportColumns($submenuId);
				$list = $this->Inventory_minmax_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
									"recordsTotal" => $this->Inventory_minmax_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
									"recordsFiltered" => $this->Inventory_minmax_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
									"data" => $data,
							);
					//output to json format
					// $this->varaha->print_arrays($output);
					echo json_encode($output);
				}
		
				public function ajax_stores_consumption_is01_list(){
					$mainmenuId=$_POST['mainmenuId'];
					$submenuId=$_POST['submenuId'];
					$companyId=$_POST['companyId'];
					$from_date=$_POST['from_date'];
					$to_date=$_POST['to_date'];
					$columns = $this->columns->getReportColumns($submenuId);
					$list = $this->Store_issue_is01_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
										"recordsTotal" => $this->Store_issue_is01_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
										"recordsFiltered" => $this->Store_issue_is01_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
										"data" => $data,
								);
						//output to json format
						// $this->varaha->print_arrays($output);
						echo json_encode($output);
					}
			
		
					public function ajax_stores_consumption_is02_list(){
						$mainmenuId=$_POST['mainmenuId'];
						$submenuId=$_POST['submenuId'];
						$companyId=$_POST['companyId'];
						$from_date=$_POST['from_date'];
						$to_date=$_POST['to_date'];
						$columns = $this->columns->getReportColumns($submenuId);
						$list = $this->Store_issue_is02_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
											"recordsTotal" => $this->Store_issue_is02_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
											"recordsFiltered" => $this->Store_issue_is02_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
											"data" => $data,
									);
							//output to json format
							// $this->varaha->print_arrays($output);
							echo json_encode($output);
						}
						public function ajax_stores_consumption_is05_list(){
							$mainmenuId=$_POST['mainmenuId'];
							$submenuId=$_POST['submenuId'];
							$companyId=$_POST['companyId'];
							$from_date=$_POST['from_date'];
							$to_date=$_POST['to_date'];
							$columns = $this->columns->getReportColumns($submenuId);
							$list = $this->Store_issue_is05_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
												"recordsTotal" => $this->Store_issue_is05_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
												"recordsFiltered" => $this->Store_issue_is05_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
												"data" => $data,
										);
								//output to json format
								// $this->varaha->print_arrays($output);
								echo json_encode($output);
							}
							public function ajax_stores_consumption_is06_list(){
								$mainmenuId=$_POST['mainmenuId'];
								$submenuId=$_POST['submenuId'];
								$companyId=$_POST['companyId'];
								$from_date=$_POST['from_date'];
								$to_date=$_POST['to_date'];
								$columns = $this->columns->getReportColumns($submenuId);
								$list = $this->Store_issue_is06_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
												$mrowname = $array_keys[$i];
												$row[] = $loc->$mrowname;
										
											}
										}
										$data[] = $row;
									}
							
									$output = array(
													"draw" => $_POST['draw'],
													"recordsTotal" => $this->Store_issue_is06_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
													"recordsFiltered" => $this->Store_issue_is06_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
													"data" => $data,
											);
									//output to json format
									// $this->varaha->print_arrays($output);
									echo json_encode($output);
								}
		
	public function ajax_stores_consumption_is03_list(){
									$mainmenuId=$_POST['mainmenuId'];
									$submenuId=$_POST['submenuId'];
									$companyId=$_POST['companyId'];
									$from_date=$_POST['from_date'];
									$to_date=$_POST['to_date'];
									$columns = $this->columns->getReportColumns($submenuId);
									$list = $this->Store_issue_is03_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
													$mrowname = $array_keys[$i];
													$row[] = $loc->$mrowname;
											
												}
									
											}
											$data[] = $row;
										}
											$output = array(
														"draw" => $_POST['draw'],
														"recordsTotal" => $this->Store_issue_is03_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
														"recordsFiltered" => $this->Store_issue_is03_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
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
			$this->data['controller'] = "reports_store";
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
				$this->page_construct('store/dashboard',$this->data);
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
			$this->data['from_date'] = date('Y-m-01');
			$this->data['to_date'] = date('Y-m-t');
		
			$this->data['controller'] = "reports_store";
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
			$this->data['itcod']="";			
			$this->data['itemdesc']="";			
			$this->data['att_mark_hrs_att']="1";
			$this->data['dates']=1;
			$this->data['att_worktype']="R";
			$this->data['att_cat']="";
			$this->data['categorys']= $this->varaha_model->getAllCategorys($this->data['companyId']);
			$this->data['branchs']= $this->varaha_model->getAllBranchs($this->data['companyId']);
			$this->data['branch_id'] = "";
			$this->data['componets']= $this->varaha_model->getAllComponets($this->data['companyId']);
			$this->data['componet_id'] = 21;
		

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
					
				}else if($submenuId==185){
					$this->data['function'] = "ajax_list_stores_issue_checklist";
				}else if($this->data['submenuId']==230){
					$this->data['function'] = "ajax_list_stores_inventory_minmax_list";				
				}else if($this->data['submenuId']==233){
					$this->data['function'] = "ajax_stores_consumption_is01_list";				
				}else if($this->data['submenuId']==217){
					$this->data['function'] = "ajax_stores_consumption_is02_list";				
					}else if($submenuId==415){
				$this->data['function'] = "ajax_list_stores_inventory_list";

			}
			else if($submenuId==499){
				$this->data['function'] = "ajax_item_ledger_report";
			}else if($submenuId==253){
//				echo 'all it=='.$this->data['itcod'];
				$this->data['columns'] = $this->columns->getReportColumnsm($submenuId,$this->data['from_date'],$this->data['to_date'],$this->data['companyId'],$this->data['itcod'] );			
				$this->data['function'] = "ajax_list_item_monthwise_list";
			}else if($this->data['submenuId']==248){
				$this->data['function'] = "ajax_stores_consumption_is05_list";				
				}else if($submenuId==415){
			$this->data['function'] = "ajax_list_stores_inventory_list";

		}
		else if($this->data['submenuId']==503){
			$this->data['function'] = "ajax_stores_consumption_is06_list";				
			}else if($submenuId==415){
		$this->data['function'] = "ajax_list_stores_inventory_list";
		}
		else if($this->data['submenuId']==386){
			$this->data['function'] = "ajax_stores_consumption_is03_list";				
		}else if($submenuId==415){
			$this->data['function'] = "ajax_list_stores_inventory_list";
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
			
					if($submenuId==530){
						$this->data['report_title'] = $this->data['menuName'] ." Date ". date('d-m-Y', strtotime($this->data['from_date']));
					}else if($submenuId==230){
						$dt=date('Y-m-d');
						$this->data['report_title'] = $this->data['menuName'] ." As On ". date('d-m-Y', strtotime($dt));
					}else{
						$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($this->data['from_date'])) . " To ".date('d-m-Y', strtotime($this->data['to_date'])).($this->data['jutesummary'] ? " & Jute Issue Summary " : "");
					}
						
					
					//.date("d",$form_date)." ".substr((date("D",$form_date)),0,2)." ".substr((date("M",$form_date)),0,2).
					
					$this->page_construct('store/storereport',$this->data);
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
			$this->data['controller'] = "reports_store";
			
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
			$this->data['itcod']=$_POST['itcode_chk'];
			$this->data['costcenter']=$_POST['costcenter_chk'];
			$this->data['itemdesc']=$_POST['itemdesc_chk'];
			$itc=$_POST['itcode_chk'];
			$this->load->library('session');
			$this->session->set_flashdata('itcode', $itc);
			if($this->data['submenuId']==603){
				$this->data['function'] = "ajax_list_full_attendance";
			}
			if($this->data['submenuId']==657){
				$this->data['function'] = "ajax_list_attendance_checklist";
			}
			if($this->data['submenuId']==185){
				$this->data['function'] = "ajax_list_stores_issue_checklist";
			}
				
			if($this->data['submenuId']==499){
				$this->data['function'] = "ajax_item_ledger_report";
			}if($this->data['submenuId']==253){
			//	echo 'all it=='.$this->data['itcod'];
				$this->data['columns'] = $this->columns->getReportColumnsm($this->data['submenuId'],$this->data['from_date'],$this->data['to_date'],$this->data['companyId'],$this->data['itcod'] );
				$this->data['function'] = "ajax_list_item_monthwise_list";
			}if($this->data['submenuId']==230){
				$this->data['function'] = "ajax_list_stores_inventory_minmax_list";
			}
			if($this->data['submenuId']==233){
				$this->data['function'] = "ajax_stores_consumption_is01_list";
			}
			if($this->data['submenuId']==217){
				$this->data['function'] = "ajax_stores_consumption_is02_list";
			}
			if($this->data['submenuId']==503){
				$this->data['function'] = "ajax_stores_consumption_is06_list";
			}
			if($this->data['submenuId']==386){
				$this->data['function'] = "ajax_stores_consumption_is03_list";
			}
			if($this->data['submenuId']==248){
				$this->data['function'] = "ajax_stores_consumption_is05_list";
			}
			
			if($this->data['submenuId']==415){
				$this->data['function'] = "ajax_list_stores_inventory_list";
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
					'itcod' => $this->data['itcod'],
					'att_mark_hrs_att' => $this->data['att_mark_hrs_att'],
					'att_worktype' => $this->data['att_worktype'],
					'att_cat' => $this->data['att_cat_att'],
					'branch_id' => $this->data['branch_id'],
					'componet_id' => $this->data['componet_id'],
					'constenter' => $this->data['costcenter'],
					'itemdesc' => $this->data['itemdesc']
				);				
			
			if($this->data['submenuId']==603){				
				$this->data['res'] = $this->hrms_full_attendance_model->directReport($perms);
				// $this->varaha->print_arrays($this->data['res']);
				$html = $this->load->view('hrms/hrms_full_attendance', $this->data, true);
				
			}else if($this->data['submenuId']==657){
				$this->load->model('Attendance_checklist_Model');	
				$this->data['res'] = $this->Attendance_checklist_Model->directReport($perms);
			//	$this->load->model('store_issue_list_report_model');
			//	$this->data['res'] = $this->store_issue_list_report_model->directReport($perms);
				$html = $this->load->view('hrms/attendance_checklist', $this->data, true);
			}else if($this->data['submenuId']==185){
				$this->load->model('Store_issue_list_report_model');	
				$this->data['res'] = $this->store_issue_list_report_model->directReport($perms);
 				$html = $this->load->view('store/store_issue_checklist', $this->data, true);
			}
			else if($this->data['submenuId']==499){
				$this->load->model('Store_item_ledger_report_model');	
				$this->data['res'] = $this->Store_item_ledger_report_model->directReport($perms);
 				$html = $this->load->view('store/stores_item_ledger_report', $this->data, true);
			}else if($this->data['submenuId']==253){
//				echo 'all it=='.$this->data['itcod'];

				$this->load->model('Store_item_monthwise_consumption_model');	
				$this->data['columns'] = $this->columns->getReportColumnsm($this->data['submenuId'],$this->data['from_date'],$this->data['to_date'],$this->data['companyId'],$this->data['itcod'] );

				$this->data['res'] = $this->Store_item_monthwise_consumption_model->directReport($perms);
 				$html = $this->load->view('store/stores_item_monthwise_consumption', $this->data, true);
			}else if($this->data['submenuId']==230){
				$this->load->model('Inventory_minmax_report_model');	
				$this->data['res'] = $this->Inventory_minmax_report_model->directReport($perms);
				$dt=date('Y-m-d'); 

				$this->data['report_title'] = $this->data['menuName'] ." As On ". date('d-m-Y', strtotime($dt));
				$html = $this->load->view('store/Store_Inventory_Min_Max_Report', $this->data, true);
		
			}
			else if($this->data['submenuId']==233){
				$this->load->model('Store_issue_is01_report_model');	
				$this->data['res'] = $this->Store_issue_is01_report_model->directReport($perms);
				$html = $this->load->view('store/stores_consumption_is01_report', $this->data, true);
				
			}else if($this->data['submenuId']==217){
				$this->load->model('Store_issue_is02_report_model');	
				$this->data['res'] = $this->Store_issue_is02_report_model->directReport($perms);
				$html = $this->load->view('store/stores_consumption_is02_report', $this->data, true);
		//		$html = $this->load->view('store/storereportprint', $this->data, true);
			}else if($this->data['submenuId']==503){
				$this->load->model('Store_issue_is06_report_model');	
				$this->data['res'] = $this->Store_issue_is06_report_model->directReport($perms);
			//	var_dump($this->data);
				$html = $this->load->view('store/stores_consumption_is06_report', $this->data, true);
	 	//		$html = $this->load->view('store/storereportprint', $this->data, true);
			}else if($this->data['submenuId']==386){
				$this->load->model('Store_issue_is03_report_model');	
				$this->data['res'] = $this->Store_issue_is03_report_model->directReport($perms);
			//	var_dump($this->data);
				$html = $this->load->view('store/stores_consumption_is03_report', $this->data, true);
	 	//		$html = $this->load->view('store/storereportprint', $this->data, true);
			}else if($this->data['submenuId']==248){
				$this->load->model('Store_issue_is05_report_model');	
				$this->data['res'] = $this->Store_issue_is05_report_model->directReport($perms);
				$html = $this->load->view('store/stores_consumption_is05_report', $this->data, true);
				
			}else if($this->data['submenuId']==415){

			$this->load->model('Inventory_report_allitems_Model');	
			$this->data['res'] = $this->Inventory_report_allitems_Model->directReport($perms);
 			$html = $this->load->view('store/stores_inventory_list_report', $this->data, true);
			// echo $html;
	
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
	//			echo $html;
			}

			if($_POST['type']==4){ // GRID				
				$this->page_construct('store/storereport',$this->data);
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
	
}
