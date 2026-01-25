<?php
class Hrms_cash_hands_report_model extends CI_Model
{

	

	var $table = 'itemmaster im';	
	var $column_order = array(null); //set column field database for datatable orderable
	var $column_search = array(''); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    // var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name');
	
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{

		
//		$eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		$eb_no = $_POST['eb_no'];
		$att_spells = $_POST['att_spells'];
		$att_dept = $_POST['att_dept'];
		
		$sql="SELECT s.*,
		dept_desc,desig,cash_rate ,CONCAT(worker_name, ' ',ifnull(middle_name,' '),ifnull(last_name,' ') ) AS name,round((cash_rate/8)*hrs,0) as amt
		FROM (
			SELECT eb_id,eb_no,worked_department_id,worked_designation_id, attendance_date, spell,company_id, SUM(working_hours - idle_hours) AS hrs
			FROM vowsls.daily_attendance 
			WHERE  attendance_type = 'C' AND is_active = 1
			AND attendance_date between '".$from_date."' and '".$to_date."'
			GROUP BY eb_id,eb_no, attendance_date,spell,worked_department_id,worked_designation_id,company_id
			
		) s
		LEFT JOIN worker_master wm ON s.eb_id = wm.eb_id
		LEFT JOIN department_master dm ON s.worked_department_id = dm.dept_id
		LEFT JOIN designation   om ON s.worked_designation_id = om.id
		where s.company_id=".$companyId	
		;
		
	//echo $sql;
 
/*$sql="select po_no,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,make,qty ,cancelled_qty,rate , tax_type_name, tax_percentage,
 item_value,  tax_amount, total_amount,status_name,remarks, inwqty,qty_to_be_receive,outstanding_for_days  from ( 
select po_sequence_no po_no,po_detail_id,ifnull(po_approve_date,'$tday') po_approve_date,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,qty ,cancelled_qty,rate , tax_type_name, tax_percentage, 
 item_value,  tax_amount, total_amount,status_name,remarks,ifnull(inwqty,0 ) inwqty, (qty-cancelled_qty-ifnull(inwqty,0)) qty_to_be_receive,
case when  ( (qty-cancelled_qty-ifnull(inwqty,0))>0  and (datediff(CURDATE(),po_approve_date)-delivery_timeline)>0 ) THEN 
(datediff(CURDATE(),po_approve_date)) 
 when status_name='CLOSED' then 0
else 0 end outstanding_for_days,make
from (
select tpp.po_sequence_no,tppd.po_detail_id ,tpp.po_approve_date,DATE_FORMAT(tpp.po_approve_date,'%d-%m-%Y') poapprovedate,sitm.indent_type_desc  ,
tpj.name prj_name ,md.dept_desc ,
s.supp_name,concat(i.group_code,item_code) itemcode, i.item_desc 
,tppd.uom_code ,tppd.qty ,ifnull(tppd.cancelled_qty,0) cancelled_qty,
round(tppd.rate,3) rate ,ifnull(tpp.tax_type_name,'NO TAX') tax_type_name, tppd.stax_percentage +tppd.ctax_percentage +tppd.i_tax_percentage tax_percentage,
round(tppd.qty*tppd.rate,2) item_value, round((tppd.qty*tppd.rate)*(tppd.stax_percentage +tppd.ctax_percentage +tppd.i_tax_percentage)/100,2) tax_amount,
round(tppd.qty*tppd.rate,2)+round((tppd.qty*tppd.rate)*(tppd.stax_percentage +tppd.ctax_percentage +tppd.i_tax_percentage)/100,2) total_amount,
sm.status_name,tpp.delivery_timeline,tppd.remarks,tppd.make 
from tbl_proc_po tpp
left join tbl_proc_po_detail tppd  on tpp.po_id =tppd.po  
left join suppliermaster s on tpp.supplier =s.supp_id 
left join itemmaster i on i.item_id =tppd.item 
left join tbl_proc_project tpj on tpp.project =tpj.project_id 
left join master_department md on tppd.department =md.rec_id
left join scm_indent_type_master sitm on sitm.indent_type_id =tpp.category 
left join status_master sm on sm.status_id =tpp.status 
where tpp.company=".$companyId." and  tpp.status not in (4,6,0,5) and tppd.is_active =1 
and po_date between '".$from_date."' and '".$to_date."'
) a left join
(select po_detail,sum(inward_qty) inwqty from tbl_proc_inward_detail tpid 
left join tbl_proc_inward tpi on tpi.inward_id =tpid.inward 
where tpid.is_active =1 and tpi.sr_status not in (4,6,0) and po_detail  is not null
group by po_detail 
) b on a.po_detail_id=b.po_detail ) k
order by itemcode,po_approve_date  ASC 

";*/
//echo $sql;	
/*
$i = 0;
		if($_POST['search']['value']){
			foreach ($this->column_search as $eb_no){
				if($i===0){	
					$sql = $sql . $eb_no ." LIKE ". $_POST['search']['value'];
				}else{
					$sql = $sql . $item ."OR LIKE ". $_POST['search']['value'];
				}

			$i++;
			}
		}
		if(isset($_POST['order'])) {
			$sql = $sql . "ORDER BY ". $this->column_order[$_POST['order']['0']['column']].",". $_POST['order']['0']['dir'];
		}else if(isset($this->order)){
			$order = $this->order;
			 $sql = $sql . "ORDER BY ". key($order) .",". $order[key($order)];
		}
*/
		if($eb_no){
			$sql .= " and s.eb_no= '".$eb_no."'";
		}
		if($att_spells){
			$sql .= " and s.spell= '".$att_spells."'";
		}
		if($att_dept){
			$sql .= " and s.worked_department_id= '".$att_dept."'";
		}
	$sql= $sql . " order by dm.dept_code,spell,desig,wm.eb_no";
//	echo $sql;	

		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{	
		$this->db->from($this->table);		
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$eb_no = $pers['eb_no'];
		$att_spells = $pers['att_spells'];
		$att_dept = $pers['att_dept'];


		$sql="SELECT s.*,
		dept_desc,desig,cash_rate ,CONCAT(worker_name, ' ',ifnull(middle_name,' '),ifnull(last_name,' ') ) AS name,round((cash_rate/8)*hrs,0) as amt
		FROM (
			SELECT eb_id,eb_no,worked_department_id,worked_designation_id, attendance_date, spell,company_id, SUM(working_hours - idle_hours) AS hrs
			FROM vowsls.daily_attendance da
			WHERE  attendance_type = 'C' AND is_active = 1
			AND attendance_date between '".$pers['from_date']."' and '".$pers['to_date']."'
			GROUP BY eb_id,eb_no, attendance_date,spell,worked_department_id,worked_designation_id,company_id
			
		) s
		LEFT JOIN worker_master wm ON s.eb_id = wm.eb_id
		LEFT JOIN department_master dm ON s.worked_department_id = dm.dept_id
		LEFT JOIN designation   om ON s.worked_designation_id = om.id
		where s.company_id=".$pers['company']
		;


		if($eb_no){
			$sql .= " and s.eb_no= '".$eb_no."'";
		}
		if($att_spells){
			$sql .= " and s.spell= '".$att_spells."'";
		}
		if($att_dept){
			$sql .= " and s.worked_department_id= '".$att_dept."'";
		}
 
		$sql= $sql . " order by dm.dept_code,spell,desig,wm.eb_no";
//echo $sql;

		$q = $this->db->query($sql);
		// $this->varaha->print_arrays($pers, $this->db->last_query());
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function directReportsummary($pers){

		$eb_no = $pers['eb_no'];
		$att_spells = $pers['att_spells'];
		$att_dept = $pers['att_dept'];


		$sql="	select attendance_date,dept_code,dept_desc,spell,sum(hrs) hrs,sum(amt) amt from (
		SELECT s.*,
		dept_desc,desig,cash_rate ,CONCAT(worker_name, ' ',ifnull(middle_name,' '),ifnull(last_name,' ') ) AS name,round((cash_rate/8)*hrs,0) as amt,dept_code
		FROM (
			SELECT eb_id,eb_no,worked_department_id,worked_designation_id, attendance_date, spell,company_id, SUM(working_hours - idle_hours) AS hrs
			FROM vowsls.daily_attendance da
			WHERE  attendance_type = 'C' AND is_active = 1
			AND attendance_date between '".$pers['from_date']."' and '".$pers['to_date']."'
			GROUP BY eb_id,eb_no, attendance_date,spell,worked_department_id,worked_designation_id,company_id
		) s
		LEFT JOIN worker_master wm ON s.eb_id = wm.eb_id
		LEFT JOIN department_master dm ON s.worked_department_id = dm.dept_id
		LEFT JOIN designation   om ON s.worked_designation_id = om.id
		where s.company_id=".$pers['company']
		;


		if($eb_no){
			$sql .= " and s.eb_no= '".$eb_no."'";
		}
		if($att_spells){
			$sql .= " and s.spell= '".$att_spells."'";
		}
		if($att_dept){
			$sql .= " and s.worked_department_id= '".$att_dept."'";
		}
 
		$sql=$sql." ) g group by attendance_date ,dept_code ,dept_desc,spell
		order by attendance_date,dept_code,spell ";
//echo $sql;

		$q = $this->db->query($sql);
		// $this->varaha->print_arrays($pers, $this->db->last_query());
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	



	public function get_cashhands_pdf_report($pers){

//		echo 'get cahs hand pdf';
		$databasecr = $this->varaha->getTenentId();
	//	echo $databasecr;
		$sms_url = $databasecr['serverIp']."/security-api/api/dailyAttendance/downloadExtarWorkDetails";
//		echo $sms_url;
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
//var_dump($file);		

		header('Content-type: ' . 'application/octet-stream');
		header('Content-Disposition: ' . 'attachment; filename=report.pdf');
		echo $file;        


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

	public function get_cashhands_pdf_report2($pers){
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
	
