<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports_test extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');
		$this->load->model('reports_test_model');
			
		
    }
    
		
	public function ajax_list(){
		$mainmenuId=$_POST['mainmenuId'];
		$submenuId=$_POST['submenuId'];
		$companyId=$_POST['companyId'];
		$from_date=$_POST['from_date'];
		$to_date=$_POST['to_date'];
		$columns = $this->columns->getReportColumns($submenuId);
		$list = $this->reports_test_model->get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
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
						"recordsTotal" => $this->reports_test_model->count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"recordsFiltered" => $this->reports_test_model->count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
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
			$this->data['controller'] = "reports_test";
			$this->data['function'] = "ajax_list";
			$this->data['jutesummary'] = null;
			$this->data['mrno'] = null;
			$this->data['godownno'] = null;

			$this->data['menudit'] = $this->varaha_model->getMenuData($this->data['submenuId']);
			$this->data['menuName'] = ($this->data['menudit'] ? $this->data['menudit']->menu : "Reports Test");	
			$query = $this->varaha_model->getEmbedGoogleLinks($company,$submenuId);
				
				if($query){
					// $this->varaha->print_arrays($this->data['menudit']);
					$this->data['embed_url'] = $query;
					$this->page_construct('embedreps/embedreports',$this->data);
				
				}else{
						if($submenuId==10000){
							$this->data['function'] = "ajax_list";
							$this->data['report_title'] = $this->data['menuName'] ." Date ". date('d-m-Y', strtotime($this->data['from_date']));
						}else{							
							$this->page_construct('jute/notfound',$this->data);
						}
						
						$this->data['columns'] = $this->columns->getReportColumns($submenuId);
						// $this->varaha->print_arrays($this->data['columns']);
						

						$this->page_construct('testreports/report',$this->data);
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
			$this->data['controller'] = "reports_test";
			$this->data['function'] = "ajax_list";
			
			
			
			$this->data['from_date'] = $_POST['from_date'];
			$this->data['to_date'] = $_POST['to_date'];
			$this->data['report_title'] = $this->data['menuName'] ." From ". date('d-m-Y', strtotime($_POST['from_date'])) . " To ".date('d-m-Y', strtotime($_POST['to_date']));
			
			if($this->data['submenuId']==0){
				$this->data['function'] = "ajax_list";
			}
			
			
				$filename = str_replace(" ","_",$this->data['report_title'])."_".date('Ymdhis');
				$perms=array(
					'company' => $this->data['companyId'],
					'mainmenuId' => $this->data['mainmenuId'],
					'submenuId' => $this->data['submenuId'],
					'from_date' => $_POST['from_date'],
					'to_date' => $_POST['to_date'],
					
				);
				$this->data['columns'] = $this->columns->getReportColumns($this->data['submenuId']);

				if($this->data['submenuId']==10000){
					$this->data['res'] = $this->reports_test_model->directReport($perms);
					// $this->varaha->print_arrays($this->data['res']);
					$html = $this->load->view('testreports/reportprint', $this->data, true);
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
				$this->page_construct('testreports/report',$this->data);
			}

			
			
		}
	}


	function pdf($result,$filename){
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
		$mpdf->Output($filename.'.pdf','D');
		
		
	}
	function pdfland($result,$filename){
		
				
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
			header('Content-Encoding: UTF-8');
			header('Content-Type: application/vnd.ms-excel');
			header('Content-Type: UTF-8');
			header("Content-type: application/vnd.ms-excel" );
			header('Content-Disposition: attachment;filename="' . $filename . '.xls"');
			header('Cache-Control: max-age=0');		
			header("Pragma: no-cache");
			header("Expires: 0");
			ob_end_clean();
			mb_convert_encoding($data, 'UCS-2LE', 'UTF-8');	   
	
	


   }
	
}
