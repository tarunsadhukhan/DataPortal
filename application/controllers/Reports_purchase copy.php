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
	


	public function ajax_list_purchase_order_list($mainmenuId,$submenuId, $companyId, $from_date,$to_date){
		
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
			$row[] = $loc->id;
			$row[] = $loc->Sequence;
			$row[] = date('d-m-Y',strtotime($loc->Date));
			$row[] = $loc->Project_Type;
			$row[] = $loc->Title;
			$row[] = $loc->Client;
			$row[] = $loc->Category;
			$row[] = $loc->branch_name;
			$row[] = $loc->status_name;
			$row[] = $loc->Value;
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
			$this->data['menudit'] = $this->varaha_model->getMenuData($mainmenuId);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports");		
			$this->data['columns'] = $this->columns->getReportColumns($submenuId);
			// $this->varaha->print_arrays($this->data['columns']);

			if($submenuId==562){
				$this->data['function_name']  = 'ajax_list_purchase_order_list';
			}else
			if($submenuId==563){
				$this->data['function_name']  = 'ajax_list_purchase_order_item_wise_list';
			}else
			if($submenuId==564){
				$this->data['function_name']  = 'ajax_indent_detail_report';
			}else{
				$this->page_construct('purchase/notfound',$this->data);
			}
			

			$this->page_construct('purchase/report',$this->data);
			
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
			
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			
				$filename = $this->data['report_title'].date('Ymdhis');
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $_POST['from_date'],
					'to_date' => $_POST['to_date']
				);

				if($this->data['submenuId']==562){
					$this->data['function_name']  = 'ajax_list_purchase_order_list';
				}else
				if($this->data['submenuId']==563){
					$this->data['function_name']  = 'ajax_list_purchase_order_item_wise_list';
				}else
				if($this->data['submenuId']==564){
					$this->data['function_name']  = 'ajax_indent_detail_report';
				}else{
					$this->page_construct('purchase/notfound',$this->data);
				}
				
				// $this->varaha->print_arrays($this->data['res']);
				if($this->data['submenuId']==562){
					$this->data['res'] = $this->purchase_list_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportpurchaselistprint', $this->data, true);
				}else if($this->data['submenuId']==563){
					$this->data['res'] = $this->purchase_list_item_wise_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportpurchaseitemwiselistprint', $this->data, true);
				}else if($this->data['submenuId']==564){
					$this->data['res'] = $this->indent_detail_report_model->directReport($perms);
					$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
					$html = $this->load->view('purchase/reportindentdetailsprint', $this->data, true);
				}else{
					$html = $this->load->view('purchase/reportprint', $this->data, true);
				}

			if($_POST['type']==1){ // PDF
				$this->pdf($html,$filename);
			}
			
			if($_POST['type']==2){ // EXCEL
				$this->excel($html,$filename);
			}

			if($_POST['type']==3){ // PRINT
				echo $html;
			}

			if($_POST['type']==4){ // GRID
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);
				$this->page_construct('purchase/report',$this->data);
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
				   
	   
	   
	   header('Content-Encoding: UTF-8');
	   header('Content-Type: application/vnd.ms-excel');
	   header('Content-Type: UTF-8');
	   header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
	   header('Cache-Control: max-age=0');		
	   mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');
	   echo $data;
   }
	
}
