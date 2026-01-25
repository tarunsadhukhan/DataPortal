<?php
defined('BASEPATH') OR exit('No direct script access allowed');
set_time_limit(0);
class Reports_production extends MY_Controller {
	
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
		$this->load->model('Production_winder_performance_model');
		$this->load->model('Daily_loom_reports_model');
		$this->load->model('Date_wise_loom_reports_model');
		$this->load->model('Date_Wise_Weaver_Eff_Report');
		$this->load->model('S4_loom_incentive_report_model');
		$this->load->model('Weaver_performance_report_model');
		$this->load->model('Spinner_performance_report_model');
		$this->load->model('Winder_performance_report_model');
		$this->load->model('Date_wise_winder_eff_report_model');
		$this->load->model('Winder_period_wise_eff_report_model');
		$this->load->model('All_spg_reports_model');
		$this->load->model('Date_wise_spinner_eff_report_model');


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


	public function ajax_daily_loom_report(){
//		All_indent_List
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$sno=1;		
		$columns = $this->columns->getReportColumns($submenuId);	
		$recordsTotal="";
		$recordsFiltered="";

		if($submenuId==675){						
			$list = $this->Daily_loom_reports_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Daily_loom_reports_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Daily_loom_reports_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		}		
		
		
		$array_keys = array_keys($columns);		
	//	var_dump($array_keys);
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

	public function ajax_daily_loom_report674(){
		//		All_indent_List
				$mainmenuId=$_POST['mainmenuId'];
				$submenuId=$_POST['submenuId'];
				$companyId=$_POST['companyId'];
				$from_date=$_POST['from_date'];
				$to_date=$_POST['to_date'];
				$sno=1;		
//				$columns = $this->columns->getReportColumns($submenuId);	
				$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
				$recordsTotal="";
				$recordsFiltered="";
		
				if($submenuId==674){						
					$list = $this->Date_wise_loom_reports_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
					$recordsTotal=$this->Date_wise_loom_reports_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
					$recordsFiltered=$this->Date_wise_loom_reports_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
				}		
				
				
				$array_keys = array_keys($columns);		
		//		var_dump($array_keys);
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
		
			public function ajax_daily_loom_report676(){
				//		All_indent_List
						$mainmenuId=$_POST['mainmenuId'];
						$submenuId=$_POST['submenuId'];
						$companyId=$_POST['companyId'];
						$from_date=$_POST['from_date'];
						$to_date=$_POST['to_date'];
						$sno=1;		
		//				$columns = $this->columns->getReportColumns($submenuId);	
						$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
						$recordsTotal="";
						$recordsFiltered="";
				
						if($submenuId==676){						
							$list = $this->Date_Wise_Weaver_Eff_Report->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
							$recordsTotal=$this->Date_Wise_Weaver_Eff_Report->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
							$recordsFiltered=$this->Date_Wise_Weaver_Eff_Report->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
						}		
						
						
						$array_keys = array_keys($columns);		
				//		var_dump($array_keys);
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
					public function ajax_daily_loom_report586(){
						//		All_indent_List
								$mainmenuId=$_POST['mainmenuId'];
								$submenuId=$_POST['submenuId'];
								$companyId=$_POST['companyId'];
								$from_date=$_POST['from_date'];
								$to_date=$_POST['to_date'];
								$sno=1;		
							//	echo $submenuId.$from_date.$to_date;
				//				$columns = $this->columns->getReportColumns($submenuId);	
								$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
						//		var_dump($columns);
								$recordsTotal="";
								$recordsFiltered="";
						
								if($submenuId==586){						
									$list = $this->Date_wise_winder_eff_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsTotal=$this->Date_wise_winder_eff_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsFiltered=$this->Date_wise_winder_eff_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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

					public function ajax_daily_loom_report695(){
						//		All_indent_List
								$mainmenuId=$_POST['mainmenuId'];
								$submenuId=$_POST['submenuId'];
								$companyId=$_POST['companyId'];
								$from_date=$_POST['from_date'];
								$to_date=$_POST['to_date'];
								$sno=1;		
							//	echo $submenuId.$from_date.$to_date;
				//				$columns = $this->columns->getReportColumns($submenuId);	
								$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
						//		var_dump($columns);
								$recordsTotal="";
								$recordsFiltered="";
						
								if($submenuId==695){						
									$list = $this->Date_wise_spinner_eff_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsTotal=$this->Date_wise_spinner_eff_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsFiltered=$this->Date_wise_spinner_eff_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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


							public function ajax_daily_loom_report685(){
								//		All_indent_List
										$mainmenuId=$_POST['mainmenuId'];
										$submenuId=$_POST['submenuId'];
										$companyId=$_POST['companyId'];
										$from_date=$_POST['from_date'];
										$to_date=$_POST['to_date'];
										$sno=1;		
									//	echo $submenuId.$from_date.$to_date;
						//				$columns = $this->columns->getReportColumns($submenuId);	
										$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
								//		var_dump($columns);
										$recordsTotal="";
										$recordsFiltered="";
								
										if($submenuId==685){						
											$list = $this->Winder_period_wise_eff_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
											$recordsTotal=$this->Winder_period_wise_eff_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
											$recordsFiltered=$this->Winder_period_wise_eff_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
									public function ajax_daily_loom_report679(){
						//		All_indent_List
								$mainmenuId=$_POST['mainmenuId'];
								$submenuId=$_POST['submenuId'];
								$companyId=$_POST['companyId'];
								$from_date=$_POST['from_date'];
								$to_date=$_POST['to_date'];
								$sno=1;		
								$columns = $this->columns->getReportColumns($submenuId);	
				//				$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
								$recordsTotal="";
								$recordsFiltered="";
						
								if($submenuId==679){						
									$list = $this->Weaver_performance_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsTotal=$this->Weaver_performance_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsFiltered=$this->Weaver_performance_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
								}		
								
								
								$array_keys = array_keys($columns);		
							//	var_dump($array_keys);
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
					//			 $this->varaha->print_arrays($output);
								echo json_encode($output);
							}
							public function ajax_daily_loom_report680(){
								//		All_indent_List
										$mainmenuId=$_POST['mainmenuId'];
										$submenuId=$_POST['submenuId'];
										$companyId=$_POST['companyId'];
										$from_date=$_POST['from_date'];
										$to_date=$_POST['to_date'];
										$sno=1;		
										$columns = $this->columns->getReportColumns($submenuId);	
						//				$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
										$recordsTotal="";
										$recordsFiltered="";
								
										if($submenuId==680){						
											$list = $this->Spinner_performance_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
											$recordsTotal=$this->Spinner_performance_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
											$recordsFiltered=$this->Spinner_performance_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
										}		
										
										
										$array_keys = array_keys($columns);		
									//	var_dump($array_keys);
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
							//			 $this->varaha->print_arrays($output);
										echo json_encode($output);
									}
								
									public function ajax_daily_loom_report681(){
										//		All_indent_List
												$mainmenuId=$_POST['mainmenuId'];
												$submenuId=$_POST['submenuId'];
												$companyId=$_POST['companyId'];
												$from_date=$_POST['from_date'];
												$to_date=$_POST['to_date'];
												$sno=1;		
												$columns = $this->columns->getReportColumns($submenuId);	
								//				$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
												$recordsTotal="";
												$recordsFiltered="";
										
												if($submenuId==681){						
													$list = $this->Winder_performance_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
													$recordsTotal=$this->Winder_performance_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
													$recordsFiltered=$this->Winder_performance_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
												}		
												
												
												$array_keys = array_keys($columns);		
											//	var_dump($array_keys);
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
									//			 $this->varaha->print_arrays($output);
												echo json_encode($output);
											}
								
					public function ajax_daily_loom_report677(){
						//		All_indent_List
								$mainmenuId=$_POST['mainmenuId'];
								$submenuId=$_POST['submenuId'];
								$companyId=$_POST['companyId'];
								$from_date=$_POST['from_date'];
								$to_date=$_POST['to_date'];
								$sno=1;		
				//				$columns = $this->columns->getReportColumns($submenuId);	
								$columns = $this->columns->getReportColumns($submenuId, $from_date,$to_date);
								$recordsTotal="";
								$recordsFiltered="";
						
								if($submenuId==677){						
									$list = $this->S4_loom_incentive_report_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsTotal=$this->S4_loom_incentive_report_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
									$recordsFiltered=$this->S4_loom_incentive_report_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
								}		
								
								
								$array_keys = array_keys($columns);	
						//		var_dump($array_keys);
//						var_dump($columns);
								$data = array();
								$no = $_POST['start'];
								$tamt=0;
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
									if ($mrowname='total_inc_amt') {
										$tamt=$tamt+$loc->$mrowname;
									}
									
									}
									$data[] = $row;
								}
								$row = array();
								$n=count($array_keys)-1;
								for($i=0; $i<count($array_keys); $i++){
									
									if ($i<>2) { 
									if ($i==$n) {
										$row[] = $tamt;
									} else { 
										$row[] = '';
									}
									} else {
										$row[] = 'Overall';
									} 
								}
								$data[] = $row;
	

								
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
						
						
 		
	public function ajax_winder_performance_report(){
		//		All_indent_List
				$mainmenuId=$_POST['mainmenuId'];
				$submenuId=$_POST['submenuId'];
				$companyId=$_POST['companyId'];
				$from_date=$_POST['from_date'];
				$to_date=$_POST['to_date'];
				$sno=1;		
				$columns = $this->columns->getReportColumns($submenuId);	
				$recordsTotal="";
				$recordsFiltered="";
				if($submenuId==647){						
					$list = $this->Production_winder_performance_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
					$recordsTotal=$this->Production_winder_performance_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
					$recordsFiltered=$this->Production_winder_performance_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
				}
				
				
				
				$array_keys = array_keys($columns);		
			//	var_dump($array_keys);
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
	public function ajax_spin_report()
	{
		$mainmenuId = $_POST['mainmenuId'];
		$submenuId = $_POST['submenuId'];
		$companyId = $_POST['companyId'];
		$from_date = $_POST['from_date'];
		$to_date = $_POST['to_date'];
		$sno = 1;
		$columns = $this->columns->getReportColumns($submenuId);
		$recordsTotal = "";
		$recordsFiltered = "";
		if ($submenuId == 688) {
			$list = $this->All_spg_reports_model->get_datatables($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
			$recordsTotal = $this->All_spg_reports_model->count_all($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
			$recordsFiltered = $this->All_spg_reports_model->count_filtered($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
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
							$row[] = $no;
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
			$this->data['controller'] = "Reports_production";
			$this->page_construct('production/dashboard',$this->data);
			
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
			$this->data['controller'] = "Reports_production";
			$this->data['menudit'] = $this->varaha_model->getMenuData($submenuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");		
			$this->data['columns'] = $this->columns->getReportColumns($submenuId);
			$this->data['tableBorders']="";
			$this->data['itcod']="";	
			$this->data['itemdesc']="";	
			$this->data['suppname']="";	
			$this->data['mrno']="";
			$this->data['Source']= 1;
			$this->data['eb_no']='';
	 		$this->data['srno']="";
		
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
					} else if ($submenuId == 688) {
					 			
						$this->data['function'] = 'ajax_spin_report';
						$this->page_construct('purchase/reportnew', $this->data);
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
					}else if($submenuId==647){
						$this->data['function']  = 'ajax_winder_performance_report';
						$this->page_construct('production/reportnew',$this->data);
					}else if($submenuId==674){
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
						$this->data['function']  = 'ajax_daily_loom_report674';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					$this->page_construct('production/reportnew',$this->data);
					}else if($submenuId==676){
	 
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
						$this->data['function']  = 'ajax_daily_loom_report676';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					$this->page_construct('production/reportnew',$this->data);
					}
					else if($submenuId==586){
							$myno=7;
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
						$this->data['function']  = 'ajax_daily_loom_report586';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
						$this->page_construct('production/reportnew',$this->data);
					}
					else if($submenuId==695){
						$myno=7;
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
						$this->data['function']  = 'ajax_daily_loom_report695';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
						$this->page_construct('production/reportnew',$this->data);
					}else if($submenuId==685){
						$myno=7;
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report685';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					$this->page_construct('production/reportnew',$this->data);
				}else if($submenuId==679){

					$this->data['function']  = 'ajax_daily_loom_report679';
					$this->page_construct('production/reportnew',$this->data);


				}else if($submenuId==680){
					$this->data['Source']= 8;
	
					$this->data['function']  = 'ajax_daily_loom_report680';
					$this->page_construct('production/reportnew',$this->data);


				}else if($submenuId==681){
					$this->data['Source']= 8;
	
					$this->data['function']  = 'ajax_daily_loom_report681';
					$this->page_construct('production/reportnew',$this->data);


				}else if($submenuId==677){
	 
						$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
						$this->data['function']  = 'ajax_daily_loom_report677';
//						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					$this->page_construct('production/reportnew',$this->data);
					}else if($submenuId==675){
						$this->data['function']  = 'ajax_daily_loom_report';
						$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) 
						;
					$this->page_construct('production/reportnew',$this->data);
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
			$this->data['controller'] = "Reports_production";
			if($_POST['from_date'] && $_POST['to_date']){
				$this->data['report_title'] = $this->data['menuName'] ." From ".date('d-m-Y',strtotime($_POST['from_date']))." To ". date('d-m-Y',strtotime($_POST['to_date']));
			}else{
				$this->data['report_title'] = $this->data['menuName'];
			}
 
			$this->data['srno']=$_POST['srno_chk'];
			$this->data['eb_no']=$_POST['eb_no_att'];
			$this->data['Source']= $_POST['Source_att'];
			$this->data['itcod']=$_POST['itcode_chk'];
			$this->data['itemdesc']=$_POST['itemdesc_chk'];
			$this->data['suppname']=$_POST['suppname_chk'];
	
			$this->data['tableBorders']="";
			$this->data['sno']=null;
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			$this->data['mrno'] = $_POST['mrno'];
			$this->load->model('Api_model'); 
			$cname=$this->Api_model->getCompanyName($this->data['companyId']);
			$_SESSION["fromdate"] = $_POST['from_date'];
			$_SESSION["todate"] = $_POST['to_date'];

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
					'srno' => $_POST['srno_chk'],
					'eb_no' => $_POST['eb_no_att'],
					'Source' => $_POST['Source_att'],
					
					
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
				}else if($this->data['submenuId']==647){
					$this->data['function']  = 'ajax_winder_performance_report';
					 $viewtype= 1;	
 				}else if($this->data['submenuId']==674){
//					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);

					$this->data['function']  = 'ajax_daily_loom_report674';
//					$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
					$viewtype= 1;	
 				}else if($this->data['submenuId']==676){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report676';
					$viewtype= 1;	
				} else if ($this->data['submenuId'] == 688) {
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'], $this->data['from_date'], $this->data['to_date']);
					$this->data['function'] = 'ajax_spin_report';
					$viewtype = 1;
				}else if($this->data['submenuId']==586){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				
					$this->data['function']  = 'ajax_daily_loom_report586';
					$viewtype= 1;	
				}else if($this->data['submenuId']==695){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				
					$this->data['function']  = 'ajax_daily_loom_report695';
					$viewtype= 1;	
				}else if($this->data['submenuId']==685){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				
					$this->data['function']  = 'ajax_daily_loom_report685';
					$viewtype= 1;	
				}else if($this->data['submenuId']==679){
//					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report679';
					$viewtype= 1;	
				}else if($this->data['submenuId']==680){
					//					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report680';
					$viewtype= 1;	
				}else if($this->data['submenuId']==681){
					//					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report681';
					$viewtype= 1;	
				}else if($this->data['submenuId']==677){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['function']  = 'ajax_daily_loom_report677';
					$viewtype= 1;	
				}else if($this->data['submenuId']==675){
					$this->data['function']  = 'ajax_daily_loom_report';
					$this->data['report_title'] = $this->data['menuName'] ." Dated ". date('d-m-Y', strtotime($this->data['from_date'])) ;
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
		}else if($this->data['submenuId']==647){
					$this->data['res'] = $this->Production_winder_performance_model->directReport($perms);
				//	var_dump($this->data['res']);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('production/reportprintnew', $this->data, true);
//					echo $html;
				}else if($this->data['submenuId']==674){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				$this->data['res'] = $this->Date_wise_loom_reports_model->directReport($perms);
				$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				
				}else if($this->data['submenuId']==676){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Date_Wise_Weaver_Eff_Report->directReport($perms);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				
				} else if ($this->data['submenuId'] == 688) {
				$this->data['res'] = $this->All_spg_reports_model->directReport($perms);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('purchase/reportprintnew', $this->data, true);

			}else if($this->data['submenuId']==586){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Date_wise_winder_eff_report_model->directReport($perms);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				
				}else if($this->data['submenuId']==695){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Date_wise_spinner_eff_report_model->directReport($perms);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				
				}else if($this->data['submenuId']==685){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Winder_period_wise_eff_report_model->directReport($perms);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				
				}else if($this->data['submenuId']==679){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Weaver_performance_report_model->directReport($perms);
				//	var_dump($this->data);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				//	echo $html;
				}else if($this->data['submenuId']==680){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Spinner_performance_report_model->directReport($perms);
				//	var_dump($this->data);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				//	echo $html;
				}else if($this->data['submenuId']==681){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->Winder_performance_report_model->directReport($perms);
				//	var_dump($this->data);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
				//	echo $html;
				}else if($this->data['submenuId']==677){
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
					$this->data['res'] = $this->S4_loom_incentive_report_model->directReport($perms);
			//		var_dump( $this->data);
					$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
			//		echo $html;
				}else if($this->data['submenuId']==675){
				$this->data['res'] = $this->Daily_loom_reports_model->directReport($perms);
//				var_dump($this->data['res']);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$html = $this->load->view('production/reportprintloomeffreport', $this->data, true);
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

			if ($_POST['type'] == 3) { // PDF
				if ($this->data['submenuId'] == 688) {
					$this->spinningtextreport($perms);
				} else {
					$this->text($html, $filename);
				}
				echo $perms;
			}


			if($_POST['type']==2){ // EXCEL
				// $this->varaha->print_arrays($html);
				$this->excel($html,$filename);
				
			}

			if($_POST['type']==33){ // PRINT
				echo $html;
				
			}

			if($_POST['type']==4){ // GRID
				if ( ($this->data['submenuId']==674) || ($this->data['submenuId']==685) || ($this->data['submenuId']==586) 
					|| ($this->data['submenuId'] == 688) || ($this->data['submenuId']==676) || ($this->data['submenuId'] == 695) 
					|| ($this->data['submenuId']==677) ){
				//	echo '676----';
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId'],$this->data['from_date'],$this->data['to_date']);
				} else {					
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
			}	
				if($viewtype==1){
				 
					$this->page_construct('production/reportnew',$this->data);
				}else{

					$this->page_construct('production/reportnew',$this->data);
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
	

// spining report
	public function spinningtextreport($perms)
	{
		// Retrieve input values
		$from_date = $this->input->get('from_date');
		$to_date = $this->input->get('to_date');
		$company_name = $this->session->userdata('companyname');
		$comp = $this->session->userdata('companyId');
		$from_date = $_SESSION["fromdate"];
		$to_date = $_SESSION["todate"];

		echo $from_date;
		$perms = array(
			'company' => $comp,
			'from_date' => $from_date,
			'to_date' => $to_date,



		);
		$data['current_date_time'] = date('d/m/Y H:i:s'); // Format the date and time
		$periodfrmdate = date('d-m-Y', strtotime($from_date));
		$periodtdate = date('d-m-Y', strtotime($to_date));

		// Prepare file for writing report
		$fileContainer = "SPG01.txt";
		$filePointer = fopen($fileContainer, "w+");

		$mccodes = $this->All_spg_reports_model->directReport($perms);


		// Initialize report content
		$line = str_repeat('-', 208);
		$logMsg = '';
		$logMsg .= '  ' . $line . "\n";
		$logMsg .= '   ' . 'THE EMPIRE JUTE CO.LTD.' . '                        ' . 'Hours Worked:' . '                 ' . 'No Of Days Worked :' .
			'                     ' . 'No of Shifts Worked :' . '                  ' . ' Report No: [SPG01]' . "\n";
		$logMsg .= '   ' . 'Daily Spinning Production Report Dated :' . $periodfrmdate . '                                                                     ' . 'All Figures Are In Per Shift Basis' . "\n";
		$logMsg .= '  ' . $line . "\n";
		$logMsg .= ' |' . ' Q_code' . '|' . ' Count & Type' . '   |' . 'Speed| TPI |        T  A  R  G  E  T     |                        T  O  D  A  Y               |                M  O  N  T  H    T  O  D  A  T  E                     |  Efficiency(%)  |' . "\n";
		$logMsg .= ' |       | No of Spindle  |     |     |_____________________________|____________________________________________________|______________________________________________________________________|_________________|          ' . "\n";
		$logMsg .= ' |       |                |     |     |Target|Prd/ |Prd/ |Production|Actual| No. Of |Production|Prd/ |Prd/ |Var. |Conv.on|Actual| No. Of |Production|Prd/ |Prd/ |  Target  |Target|Var. |Conv.On| Today  | Todate |' . "\n";
		$logMsg .= ' |       |                |     |     | Eff% |Frm/ |Frm/ |   (MT)   |Count | Frames |   (MT)   |Wnd  |Frm  |Prd/ |Std/Cnt|Count | Frames |   (MT)   |Wnd  |Frm  |Production|Prd/  |Prd/ |Std/Cnt|        |        |' . "\n";
		$logMsg .= ' |       |                |     |     |      |N/Cnt|A/Cnt|          |      |        |          |     |     |Frm  |Prd/frm|      |        |          |     |     |   (MT)   |Frm   |Frm  |Prd/Frm|        |        |' . "\n";
		$logMsg .= ' |   1   |      2         |  3  |  4  |  5   |  6  |  7  |    8     |  9   |  10    |   11     | 12  | 13  | 14  | 15    |  16  |   17   |    18    | 19  | 20  |    21    |  22  |     |       |        |        |' . "\n";
		$logMsg .= ' |_______|________________|_____|_____|______|_____|_____|__________|______|________|__________|_____|_____|_____|_______|______|________|__________|_____|_____|__________|______|_____|_______|________|________|' . "\n";
		// Variables to accumulate totals
		// $logMsg = '';
		$dpc = null; // Initialize $dpc
		$gtothrs = 0; // Initialize total variables
		$gtotamt = 0;
		$gtotpay = 0;
		$gnpay = 0;
		$tprod_per_mt = 0;
		$tno_of_fram = 0;
		$tprod_per_mt = 0;
		$TTPRD_FRM_N_COUNT_B = 0;

		// Process each record and append to report content
		foreach ($mccodes as $record) {

			if ($dpc !== $record->GCODE) {
				$logMsg .= ' |_______|________________|_____|_____|______|_____|_____|__________|______|________|__________|_____|_____|_____|_______|______|________|__________|_____|_____|__________|______|_____|_______|________|________|' . "\n";
				// If $dpc is not null, append the totals to the log message
				if ($dpc !== null) {
					// Prepare the parameters for the report
		
					$permsdpc = array(
						'company' => $comp,
						'from_date' => $from_date,
						'to_date' => $to_date,
						'dpc' => $dpc,
					);
		 
					// Fetch the report data
					$mccodess = $this->All_spg_reports_model->directReportt($permsdpc);
					
				//	var_dump($mccodess);	
					// Loop through the fetched report data
					foreach ($mccodess as $recorda) {

						$PRD_FRM_N_COUNT_B = $recorda->PRD_FRM_N_COUNT_B;
						$GNAME = $recorda->GNAME;
						$logMsg .= '    ' . $GNAME . str_repeat(' ', 4 - strlen($PRD_FRM_N_COUNT_B)) . $PRD_FRM_N_COUNT_B . "\n";
					}

				}

				// Update $dpc to the current record's GCODE

				$dpc = $record->GCODE;
				// Reset totals specific to the new group
				$tprd_frm_n_count = 0;
				$tno_of_fram = 0;
				$tprod_per_mt = 0;
			}


			$Q_CODE = $record->Q_CODE;
			$CTYPE = substr($record->CTYPE, 0, 16);
			$speed = $record->speed;
			$twist_per_inch = $record->twist_per_inch;
			$tar_eff = $record->tar_eff;
			$prd_frm_n_count = $record->prd_frm_n_count;
			$prd_frm_a_count = $record->prd_frm_a_count;
			$prod_per_mt = $record->prod_per_mt;
			$act_countt = $record->act_countt;
			$no_of_fram = $record->no_of_fram;
			$prod_per_mtt = $record->prod_per_mtt;
			$prod_per_winder = $record->prod_per_winder;
			$prd_per_fram = $record->prd_per_fram;
			$var_prd_fram = $record->var_prd_fram;
			$con_std_cnt_prd_fram = $record->con_std_cnt_prd_fram;
			$std_count = $record->std_count;
			$tno_of_frms = $record->tno_of_frms;
			$tprod_per_mtt = $record->tprod_per_mtt;
			$mprod_per_winder = $record->mprod_per_winder;
			$mprd_per_fram = $record->mprd_per_fram;
			$tar_prod_per_mt = $record->tar_prod_per_mt;
			$prd_frm_a_countt = $record->prd_frm_a_countt;
			$mvar_prd_fram = $record->mvar_prd_fram;
			$mcon_std_cnt_prd_fram = $record->mcon_std_cnt_prd_fram;
			$eff = $record->eff;
			$meff = $record->meff;

			$logMsg .= '  ' . $Q_CODE . ' ' . $CTYPE . str_repeat(' ', 16 - strlen($CTYPE)) .
				str_repeat(' ', 6 - strlen($speed)) . $speed .
				str_repeat(' ', 6 - strlen($twist_per_inch)) . $twist_per_inch .
				str_repeat(' ', 6 - strlen($tar_eff)) . $tar_eff .
				str_repeat(' ', 6 - strlen($prd_frm_n_count)) . $prd_frm_n_count .
				str_repeat(' ', 6 - strlen($prd_frm_a_count)) . $prd_frm_a_count .
				str_repeat(' ', 8 - strlen($prod_per_mt)) . $prod_per_mt .
				str_repeat(' ', 11 - strlen($act_countt)) . $act_countt .
				str_repeat(' ', 8 - strlen($no_of_fram)) . $no_of_fram .
				str_repeat(' ', 8 - strlen($prod_per_mtt)) . $prod_per_mtt .
				str_repeat(' ', 10 - strlen($prod_per_winder)) . $prod_per_winder .
				str_repeat(' ', 6 - strlen($prd_per_fram)) . $prd_per_fram .
				str_repeat(' ', 6 - strlen($var_prd_fram)) . $var_prd_fram .
				str_repeat(' ', 6 - strlen($con_std_cnt_prd_fram)) . $con_std_cnt_prd_fram .
				str_repeat(' ', 9 - strlen($std_count)) . $std_count .
				str_repeat(' ', 9 - strlen($tno_of_frms)) . $tno_of_frms .
				str_repeat(' ', 9 - strlen($tprod_per_mtt)) . $tprod_per_mtt .
				str_repeat(' ', 8 - strlen($mprod_per_winder)) . $mprod_per_winder .
				str_repeat(' ', 6 - strlen($mprd_per_fram)) . $mprd_per_fram .
				str_repeat(' ', 10 - strlen($tar_prod_per_mt)) . $tar_prod_per_mt .
				str_repeat(' ', 8 - strlen($prd_frm_a_countt)) . $prd_frm_a_countt .
				str_repeat(' ', 6 - strlen($mvar_prd_fram)) . $mvar_prd_fram .
				str_repeat(' ', 6 - strlen($mcon_std_cnt_prd_fram)) . $mcon_std_cnt_prd_fram .
				str_repeat('   |', 1) .
				str_repeat(' ', 6 - strlen($eff)) . $eff .
				str_repeat('  |', 1) .
				str_repeat(' ', 6 - strlen($meff)) . $meff .
				str_repeat('  |', 1) .
				"\n";
			$logMsg .= '  ' . $line . "\n";

			// Accumulate totals
			$tprod_per_mt += $prod_per_mt;
			$tno_of_fram += $no_of_fram;
			$tprod_per_mt += $prod_per_mt;


		}
		$permsdpc = array(
			'company' => $comp,
			'from_date' => $from_date,
			'to_date' => $to_date,
			'dpc' => $dpc,
		);

		$mccodess = $this->All_spg_reports_model->directReportt($permsdpc);

		//	var_dump($mccodess);	
		// Loop through the fetched report data
		foreach ($mccodess as $recorda) {

			$PRD_FRM_N_COUNT_B = $recorda->PRD_FRM_N_COUNT_B;
			$GNAME = $recorda->GNAME;
			$logMsg .= '    ' . $GNAME . str_repeat(' ', 4 - strlen($PRD_FRM_N_COUNT_B)) . $PRD_FRM_N_COUNT_B . "\n";
		}


		$logMsg .= str_repeat(' ', 5) . 'Grand Total' .
			str_repeat(' ', 8 - strlen($gtothrs)) . $gtothrs .
			str_repeat(' ', 22 - strlen($gtothrs)) . $gtothrs .
			"\n";
		$logMsg .= '  ' . $line . "\n";
		$logMsg .= '  ' . "\n";
		$logMsg .= ' Overall   Summary        |<------------------------  To - Day   ---------------------->|<------------------------ To - Date ------------------------->|   ||<-------------- Count Type Wise Production ------------>||' . "\n";
		$logMsg .= '                          |  Fine     |  Hy Fine   |  Coarse    | Sale Yarn | Overall   |   Fine    |   Hy Fine   |   Coarse   | Sale Yarn | Overall   |   ||Count|Today     |Todate    ||Count|Today     |Todate    ||' . "\n";
		$logMsg .= '                          |____________________________________________________________________________________________________________________________|   ||_____|__________|__________||_____|__________|__________||' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Frames Run' . str_repeat(' ', 15) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Target Production(MT)' . str_repeat(' ', 4) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Actual Production(MT)' . str_repeat(' ', 4) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Actual Prd/Frm/Day (Kgs)' . str_repeat(' ', 1) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'No. Of Winders' . str_repeat(' ', 11) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Production/Winder(Kgs.)' . str_repeat(' ', 2) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= str_repeat(' ', 1) . 'Actual Efficiency(%)' . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' .
			str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 7) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 6) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . str_repeat(' ', 6 - strlen(0.00)) . 0.00 . str_repeat(' ', 5) . '|' . "\n";
		$logMsg .= '  ' . "\n";
		$logMsg .= '  ' . 'Note :-  Notations on Actual eff denotes lower eff as given below' . "\n";
		$logMsg .= chr(12) . "\n";

		// Write $logMsg to file
//$filePointer = fopen('your_file.txt', 'w');
//if ($filePointer === false) {
		//   die('Failed to open file for writing');
//}

		fputs($filePointer, $logMsg);
		fclose($filePointer);

		header('Content-Type: text/plain');
		header('Content-Disposition: attachment; filename=' . basename($fileContainer));
		header('Content-Length: ' . filesize($fileContainer));
		readfile($fileContainer);

		// Clean up the temporary file
		unlink($fileContainer);

		// Prepare ZIP file with report
/*
		$zipname = 'Spinning.zip';
		$zip = new ZipArchive;
		if ($zip->open($zipname, ZipArchive::CREATE) === TRUE) {
			$zip->addFile($fileContainer);
			$zip->close();

			// Output ZIP file for download
			header('Content-Type: application/zip');
			header('Content-Disposition: attachment; filename=' . $zipname);
			header('Content-Length: ' . filesize($zipname));
			readfile($zipname);

			// Clean up temporary files
			unlink($fileContainer);
			unlink($zipname);
		} else {
			echo "Failed to create ZIP file.";
		}
*/
		}
// end spg report


}
