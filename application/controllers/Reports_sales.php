<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_sales extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		
		
		
		ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
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
			$this->data['controller'] = "reports_sales";
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
				$this->page_construct('sales/dashboard',$this->data);
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
			$this->data['controller'] = "reports_sales";
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
					if($submenuId==0){						
						$this->data['function'] = "list_ajax";
						// $this->data['dates']=0;
					}else{
						
						$this->page_construct('sales/notfound',$this->data);
					}
					if($this->data['dates']){
						$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($this->data['from_date'])) . " To ".date('d-m-Y', strtotime($this->data['to_date']));
					}else{
						$this->data['report_title'] = $this->data['menuName'];
					}
					
					
					//.date("d",$form_date)." ".substr((date("D",$form_date)),0,2)." ".substr((date("M",$form_date)),0,2).
					
					$this->page_construct('sales/report',$this->data);
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
			$this->data['controller'] = "reports_sales";
			
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
				
				$this->data['function'] = "list_ajax";
				$this->data['dates']=0;
				$this->data['sno']=null;
				$this->data['res'] = $this->sales_employee_bank_statement_report_model->directReport($perms);
				// $this->data['report_title'] = $this->data['menuName'];
				
				$html = $this->load->view('sales/reportprint', $this->data, true);
			}else{
				$this->page_construct('sales/notfound',$this->data);
			}
				


			if($_POST['type']==1){ // PDF
				
				$this->pdf($html,$filename);
				
				
			}
			
			if($_POST['type']==2){ // EXCEL
				if($this->data['submenuId']==604){
					$this->data['res'] = $this->sales_attendance_register_model->directReport($perms);
					$html = $this->load->view('sales/reportprint', $this->data, true);
				}else{
					$this->excel($html,$filename);
				}
				
			}

			if($_POST['type']==3){ // PRINT
				
				echo $html;
			}

			if($_POST['type']==4){ // GRID				
				$this->page_construct('sales/report',$this->data);
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
