<?php
class Hrms_cash_hands_report_model extends CI_Model
{

	


	
	
	public function __construct()
	{		
		$this->load->database();		
	}


	

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$databasecr = $this->varaha->getTenentId();
 
		$sms_url = $databasecr['serverIp']."/security-api/api/dailyAttendance/getCashHandsReportList";
 
		$parameters = array(
			'companyId' => $companyId,
			'ebId' =>  (isset($_POST['ebId']) ? $_POST['ebId'] : ""),
			'startingDate' => date('d-m-Y',strtotime($from_date)),
			'toDate' =>  date('d-m-Y',strtotime($to_date)),
			'userId' =>  $this->session->userdata('userid'),
			'subDeptId' =>  (isset($_POST['att_dept']) ? $_POST['att_dept'] : "0"),
			'designationId' =>  (isset($_POST['att_desig']) ? $_POST['att_desig'] : "0"),
			'cataId' =>  (isset($_POST['att_cat']) ? $_POST['att_cat'] : "0"),
			// 'workType' =>  (isset($_POST['att_worktype']) ? $_POST['att_worktype'] : "R"),
			'ebNo' =>  (isset($_POST['eb_no']) ? $_POST['eb_no'] : ""),
			'spell' =>  (isset($_POST['spell']) ? $_POST['spell'] : ""),
			'start' => $_POST['start'],
			'length' => $_POST['length']
		);
		$headers = [
			"Content-Type: application/json",
			"X-Content-Type-Options:nosniff",
			"Accept:application/json",
			"Cache-Control:no-cache",
			'X-TenantId: '.$databasecr['tenentId'],
			'Authorization: '.$this->session->userdata('Authorization'),
			'CompanyID: '.$companyId,
		];
		
		// $this->varaha->print_arrays($sms_url, json_encode($parameters), $headers);
		$chs = curl_init();
		curl_setopt($chs, CURLOPT_POST, true);
		curl_setopt($chs, CURLOPT_URL, $sms_url);
		curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));
		// curl_setopt($chs, CURLOPT_POSTFIELDS, $mydata);
		curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
		$output1 = curl_exec($chs);
		curl_close($chs);
		 $this->varaha->print_arrays($output1);
		if ($output1){
			
			$compdata= array();
			$res = json_decode($output1, true);
		}

		return $res;
		
	}

	public function directReport($pers){
		$databasecr = $this->varaha->getTenentId();
		$sms_url = $databasecr['serverIp']."/security-api/api/dailyAttendance/getCashHandsReportList";
		

		$parameters = array(
			'companyId' => $pers['company'],
			'ebId' =>  (isset($pers['ebId']) ? $pers['ebId'] : ""),
			'startingDate' => date('d-m-Y',strtotime($pers['from_date'])),
			'toDate' =>  date('d-m-Y',strtotime($pers['to_date'])),
			'userId' =>  $this->session->userdata('userid'),
			'subDeptId' =>  (isset($pers['att_dept']) ? $pers['att_dept'] : "0"),
			'designationId' =>  (isset($pers['att_desig']) ? $pers['att_desig'] : "0"),
			'cataId' =>  (isset($pers['att_cat']) ? $pers['att_cat'] : "0"),
			'workType' =>  (isset($pers['att_worktype']) ? $pers['att_worktype'] : "R"),
			'ebNo' =>  (isset($pers['eb_no']) ? $pers['eb_no'] : ""),
			'spell' =>  (isset($pers['spell']) ? $pers['spell'] : ""),
			'start' => null,
			'length' => null
		);
		$headers = [
			"Content-Type: application/json",
			"X-Content-Type-Options:nosniff",
			"Accept:application/json",
			"Cache-Control:no-cache",
			'X-TenantId: '.$databasecr['tenentId'],
			'Authorization: '.$this->session->userdata('Authorization'),
			'CompanyID: '.$pers['company'],
		];
		
		$chs = curl_init();
		curl_setopt($chs, CURLOPT_POST, true);
		curl_setopt($chs, CURLOPT_URL, $sms_url);
		curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));
		// curl_setopt($chs, CURLOPT_POSTFIELDS, $mydata);
		curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
		$output1 = curl_exec($chs);
		curl_close($chs);
		// $this->varaha->print_arrays($output1);
		if ($output1){
			
			$compdata= array();
			$res = json_decode($output1, true);
		}
		if($res){
			return $res['data'];
		}else{
			return false;
		}
		
	}

	public function get_cashhands_pdf_report1($pers){
		$databasecr = $this->varaha->getTenentId();
		$sms_url = $databasecr['serverIp']."/security-api/api/dailyAttendance/downloadExtarWorkDetails";

		//https://devapi.vowerp.com/security-api/api/dailyAttendance/downloadExtarWorkDetails

		//{"companyId":"1","ebId":"","startingDate":"","toDate":"","userId":"26305","subDeptId":0,"designationId":0,"cataId":0,"workType":"","start":1,"length":100,"ebNo":"","spell":""}

		$parameters = array(
			'companyId' => $pers['company'],
			'ebId' =>  (isset($pers['ebId']) ? $pers['ebId'] : ""),
			'startingDate' => date('d-m-Y',strtotime($pers['from_date'])),
			'toDate' =>  date('d-m-Y',strtotime($pers['to_date'])),
			'userId' =>  $this->session->userdata('userid'),
			'subDeptId' =>  (isset($pers['att_dept']) ? $pers['att_dept'] : "0"),
			'designationId' =>  (isset($pers['att_desig']) ? $pers['att_desig'] : "0"),
			'cataId' =>  (isset($pers['att_cat']) ? $pers['att_cat'] : "0"),
			// 'workType' =>  (isset($pers['att_worktype']) ? $pers['att_worktype'] : "R"),
			'ebNo' =>  (isset($pers['eb_no']) ? $pers['eb_no'] : ""),
			'spell' =>  (isset($pers['spell']) ? $pers['spell'] : ""),
			'start' => null,
			'length' => null
		);
		
		$headers = [
			"Content-Type: application/json",
			"X-Content-Type-Options:nosniff",
			"Accept:application/json",
			"Cache-Control:no-cache",
			'X-TenantId: '.$databasecr['tenentId'],
			'Authorization: '.$this->session->userdata('Authorization'),
			'CompanyID: '.$pers['company'],
		];

		// $this->varaha->print_arrays($databasecr['tenentId'], $this->session->userdata('Authorization'));
		
		$chs = curl_init();
		curl_setopt($chs, CURLOPT_POST, true);
		curl_setopt($chs, CURLOPT_URL, $sms_url);
		curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));
		// curl_setopt($chs, CURLOPT_POSTFIELDS, $mydata);
		curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
		$file = curl_exec($chs);
		

		if ($file === false) 
		{
			$info = curl_getinfo($curl);
			curl_close($chs);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($chs);
		

		header('Content-type: ' . 'application/octet-stream');
		header('Content-Disposition: ' . 'attachment; filename=report.pdf');
//		echo $file;        


		// $this->varaha->print_arrays($output1);
		// if ($output1){
			
		// 	$compdata= array();
		// 	$res = json_decode($output1, true);
		// }
		// if($res){
		// 	return $res['data'];
		// }else{
		// 	return false;
		// }
	}


	public function get_cashhands_pdf_report($pers){
		$databasecr = $this->varaha->getTenentId();
		$sms_url = $databasecr['serverIp']."/security-api/api/dailyAttendance/downloadExtarWorkDetails";
		//https://devapi.vowerp.com/security-api/api/dailyAttendance/downloadExtarWorkDetails

		//{"companyId":"1","ebId":"","startingDate":"","toDate":"","userId":"26305","subDeptId":0,"designationId":0,"cataId":0,"workType":"","start":1,"length":100,"ebNo":"","spell":""}

		$parameters = array(
			'companyId' => $pers['company'],
			'ebId' =>  (isset($pers['ebId']) ? $pers['ebId'] : ""),
			'startingDate' => date('d-m-Y',strtotime($pers['from_date'])),
			'toDate' =>  date('d-m-Y',strtotime($pers['to_date'])),
			'userId' =>  $this->session->userdata('userid'),
			'subDeptId' =>  (isset($pers['att_dept']) ? $pers['att_dept'] : "0"),
			'designationId' =>  (isset($pers['att_desig']) ? $pers['att_desig'] : "0"),
			'cataId' =>  (isset($pers['att_cat']) ? $pers['att_cat'] : "0"),
			// 'workType' =>  (isset($pers['att_worktype']) ? $pers['att_worktype'] : "R"),
			'ebNo' =>  (isset($pers['eb_no']) ? $pers['eb_no'] : ""),
			'spell' =>  (isset($pers['spell']) ? $pers['spell'] : ""),
			'start' => null,
			'length' => null
		);
		
		$headers = [
			"Content-Type: application/json",
			"X-Content-Type-Options:nosniff",
			"Accept:application/json",
			"Cache-Control:no-cache",
			'X-TenantId: '.$databasecr['tenentId'],
			'Authorization: '.$this->session->userdata('Authorization'),
			'CompanyID: '.$pers['company'],
		];

		// $this->varaha->print_arrays($databasecr['tenentId'], $this->session->userdata('Authorization'));
		
		$chs = curl_init();
		curl_setopt($chs, CURLOPT_POST, true);
		curl_setopt($chs, CURLOPT_URL, $sms_url);
		curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));
		// curl_setopt($chs, CURLOPT_POSTFIELDS, $mydata);
		curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
		$file = curl_exec($chs);
		

		if ($file === false) 
		{
			$info = curl_getinfo($curl);
			curl_close($chs);
			die('error occured during curl exec. Additioanl info: ' . var_export($info));
		}
		curl_close($chs);
		

		header('Content-type: ' . 'application/octet-stream');
		header('Content-Disposition: ' . 'attachment; filename=report.pdf');
//		echo $file;        


		// $this->varaha->print_arrays($output1);
		// if ($output1){
			
		// 	$compdata= array();
		// 	$res = json_decode($output1, true);
		// }
		// if($res){
		// 	return $res['data'];
		// }else{
		// 	return false;
		// }
	}

	

}
?>
	
