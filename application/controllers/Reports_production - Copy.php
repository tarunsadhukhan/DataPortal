<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_production extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		
		$this->load->model('jute_model');
		$this->load->model('Production_winder_performance_model');

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

	public function ajax_winder_performance_report(){
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
			$list = $this->Production_winder_performance_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsTotal=$this->Production_winder_performance_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
			$recordsFiltered=$this->Production_winder_performance_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");			
			$this->data['from_date'] = date('Y-m-01',time());
			$this->data['to_date'] = date('Y-m-t',time());
			$this->data['submenuId'] = "";
			$this->data['companyId'] = "";
			$this->data['controller'] = "reports_production";
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
			$this->data['from_date'] = date('m-01-Y',time());
			$this->data['to_date'] = date('m-t-Y',time());
			$this->data['controller'] = "reports_production";
			$this->data['menudit'] = $this->varaha_model->getMenuData($mainmenuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");
			// $this->varaha->print_arrays($this->data['from_date'], $this->data['to_date']);
			$query = $this->varaha_model->getEmbedGoogleLinks($company,$submenuId);

			
				
				if($query){
					// $this->varaha->print_arrays($this->data['menudit']);
					$this->data['embed_url'] = $query;
					$this->page_construct('embedreps/embedreports',$this->data);
				
				}else{

					if($submenuId==672){
						$this->data['function'] = "ajax_winder_performance_report";
						$this->page_construct('production/report',$this->data);
					}		


					$this->page_construct('production/notfound',$this->data);
				}
			// $this->page_construct('production/report',$this->data);
			
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

				if($this->data['submenuId']==672){
					$this->data['function_name']  = 'ajax_winder_performance_report';
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
					$this->page_construct('production/notfound',$this->data);
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
					$html = $this->load->view('production/reportprint', $this->data, true);
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
					$this->page_construct('production/purchasereport',$this->data);
				}else{
					$this->page_construct('production/report',$this->data);
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
	   
	// //    header('Content-Encoding: UTF-8');
	// //    header('Content-Type: application/vnd.ms-excel');
	// //    header('Content-Type: UTF-8');
	// //    header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	// //    header('Cache-Control: max-age=0');		
	// //    mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');
	// //    echo $data;
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
