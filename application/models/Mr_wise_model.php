<?php
class Mr_wise_model extends CI_Model
{

	var $table = 'invoice_hdr i';	
	var $column_order = array(null, 'i.invoice_date','i.invoice_no_string' ,'i.customer_name', 'u.first_name','i.invoice_amount','sm.status_name','i.mr_id','s.supp_name','m.mr_print_no','m.gate_entry_no','m.jute_receive_dt','mm.mukam_name','i.sale_no','i.unit_conversion'); //set column field database for datatable orderable
	var $column_search = array( 'i.invoice_date','i.invoice_no_string' ,'i.customer_name', 'u.first_name','i.invoice_amount','sm.status_name','i.mr_id','s.supp_name','m.mr_print_no','m.gate_entry_no','m.jute_receive_dt','mm.mukam_name','i.sale_no','i.unit_conversion'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    var $order = array();
	
	public function __construct()
	{		
		$this->load->database();		
	}

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno)
	{

		$sql =  "select i.invoice_date , 
		i.invoice_no_string , 
		i.customer_name , 
		u.first_name , 
		i.invoice_amount , 
		sm.status_name , 
		i.mr_id , 
		s.supp_name ,
		m.mr_print_no , 
		m.gate_entry_no , 
		m.jute_receive_dt , 
		mm.mukam_name , 
		i.sale_no , 
		i.unit_conversion 
		from 
		invoice_hdr i , 
		scm_mr_hdr m , 
		user_details u , 
		suppliermaster s , 
		status_master sm , 
		mukam mm 
		where m.jute_receive_no=i.mr_id 
		and u.user_id=i.created_by 
		and s.supp_code=m.supp_code 
		and s.company_id=m.company_id 
		and sm.status_id=i.status 
		and mm.mukam_id=m.mukam_id 
		and i.is_active=1 
		and m.company_id=".$companyId." AND i.invoice_date >= '".date('Y-m-d',strtotime($from_date))."' and i.invoice_date<= '".date('Y-m-d',strtotime($to_date))."'";
		
		
		$sql="		  select mr.jute_receive_no,jute_line_item_no,mr_print_no,date_format(jute_receive_dt,'%d-%m-%Y') jute_received_dt,actual_quality,jqpm.jute_quality, 
		  wd.name ,noofbales,unit,actual_weight,date_format(ji.issue_date,'%d-%m-%Y') issue_date,	jqpm2.jute_quality issue_quality,
		  ifnull(ji.quantity,0) quantity,ifnull(ji.total_weight,0) total_weight,IFNULL(qty,0) qty,
		  ifnull(twt,0) twt,(noofbales-IFNULL(qty,0)) bal_qty,round((actual_weight-ifnull(twt,0)),2) bal_weight,
		  warehouse_no,name gdname  from (
		  select smh.company_id,smh.jute_receive_no ,smli.jute_line_item_no ,smh.mr_print_no,smh.jute_receive_dt,smli.actual_quality,
		  case when smli.actual_bale>0 then actual_bale else smli.actual_loose end noofbales,
		  case when smli.actual_bale>0 then 'Bales' else 'Loose' end unit,
		  smli.actual_weight,smli.warehouse_no   from scm_mr_hdr smh 
		  left join scm_mr_line_item smli on smh.jute_receive_no =smli.jute_receive_no
		  where smh.mr_good_recept_status not in (4,6) and smli.is_active =1
		  and smh.jute_receive_dt between '$from_date' and  '$to_date'
		  and smh.company_id =$companyId) mr
		  left join (
		  select ji.mr_no,ji.stock_id,ji.issue_date,ji.quantity ,ji.bale_loose,ji.total_weight,		ji.jute_quality
 from 
		  jute_issue ji where ji.is_active =1 and ji.company_id=2 and ji.issue_status not in (4,6)  
		  ) ji on ji.stock_id =mr.jute_line_item_no
		  left join 
		  (
		  select ji.mr_no,ji.stock_id,ji.bale_loose,sum(ji.quantity) qty, round(sum(ji.total_weight),2) twt from 
		  jute_issue ji where ji.is_active =1 and ji.company_id=2 and ji.issue_status not in (4,6)  
		  group by ji.mr_no,ji.stock_id,ji.bale_loose 
		) gr on gr.stock_id =mr.jute_line_item_no	
		left join jute_quality_price_master jqpm on actual_quality=jqpm.id
		left join jute_quality_price_master jqpm2 on jqpm2.id=ji.jute_quality
		left join warehouse_details wd on wd.id =mr.warehouse_no 
  	";	
		// $this->varaha->print_arrays($sql);
		//
 $n=0;
if (strlen($mrno)>0) {
    $sql=$sql." where ";      

 
if ($mrno) {
    if ($n==0) {
        $sql=$sql."  mr.mr_print_no like '%".$mrno."%'";
    } else
 {
    $sql=$sql." and mr.mr_print_no like '%".$mrno."%'";
}
}

}

	
	
	
		$sql=$sql." 		order by mr.mr_print_no";
//echo 'aa'.$mr_no.' eb '.$eb_no;
//echo $sql;		
 
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date,$mrno)
	{	
		$this->db->from($this->table);		
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$sql =  "select i.invoice_date , 
		i.invoice_no_string , 
		i.customer_name , 
		u.first_name , 
		i.invoice_amount , 
		sm.status_name , 
		i.mr_id , 
		s.supp_name ,
		m.mr_print_no , 
		m.gate_entry_no , 
		m.jute_receive_dt , 
		mm.mukam_name , 
		i.sale_no , 
		i.unit_conversion 
		from 
		invoice_hdr i , 
		scm_mr_hdr m , 
		user_details u , 
		suppliermaster s , 
		status_master sm , 
		mukam mm 
		where m.jute_receive_no=i.mr_id 
		and u.user_id=i.created_by 
		and s.supp_code=m.supp_code 
		and s.company_id=m.company_id 
		and sm.status_id=i.status 
		and mm.mukam_id=m.mukam_id 
		and i.is_active=1 
		and m.company_id=".$pers['company']." AND i.invoice_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and i.invoice_date<= '".date('Y-m-d',strtotime($pers['to_date']))."'";
		$companyId= $pers['company'];
		$from_date= $pers['from_date'];
		$to_date= $pers['to_date'];
		$mrno= $pers['mrno'];

		$sql="		  select mr.jute_receive_no,jute_line_item_no,mr_print_no,date_format(jute_receive_dt,'%d-%m-%Y') jute_received_dt,actual_quality,jqpm.jute_quality, 
		  wd.name ,noofbales,unit,actual_weight,date_format(ji.issue_date,'%d-%m-%Y') issue_date,	jqpm2.jute_quality issue_quality,
		  ifnull(ji.quantity,0) quantity,ifnull(ji.total_weight,0) total_weight,IFNULL(qty,0) qty,
		  ifnull(twt,0) twt,(noofbales-IFNULL(qty,0)) bal_qty,round((actual_weight-ifnull(twt,0)),2) bal_weight,
		  warehouse_no,name gdname  from (
		  select smh.company_id,smh.jute_receive_no ,smli.jute_line_item_no ,smh.mr_print_no,smh.jute_receive_dt,smli.actual_quality,
		  case when smli.actual_bale>0 then actual_bale else smli.actual_loose end noofbales,
		  case when smli.actual_bale>0 then 'Bales' else 'Loose' end unit,
		  smli.actual_weight,smli.warehouse_no   from scm_mr_hdr smh 
		  left join scm_mr_line_item smli on smh.jute_receive_no =smli.jute_receive_no
		  where smh.mr_good_recept_status not in (4,6) and smli.is_active =1
		  and smh.jute_receive_dt between '$from_date' and  '$to_date'
		  and smh.company_id =$companyId) mr
		  left join (
		  select ji.mr_no,ji.stock_id,ji.issue_date,ji.quantity ,ji.bale_loose,ji.total_weight,		ji.jute_quality
 from 
		  jute_issue ji where ji.is_active =1 and ji.company_id=2 and ji.issue_status not in (4,6)  
		  ) ji on ji.stock_id =mr.jute_line_item_no
		  left join 
		  (
		  select ji.mr_no,ji.stock_id,ji.bale_loose,sum(ji.quantity) qty, round(sum(ji.total_weight),2) twt from 
		  jute_issue ji where ji.is_active =1 and ji.company_id=2 and ji.issue_status not in (4,6)  
		  group by ji.mr_no,ji.stock_id,ji.bale_loose 
		) gr on gr.stock_id =mr.jute_line_item_no	
		left join jute_quality_price_master jqpm on actual_quality=jqpm.id
		left join jute_quality_price_master jqpm2 on jqpm2.id=ji.jute_quality
		left join warehouse_details wd on wd.id =mr.warehouse_no 
  	";	
 $n=0;
if (strlen($mrno)>0) {
    $sql=$sql." where ";      

 
if ($mrno) {
    if ($n==0) {
        $sql=$sql."  mr.mr_print_no like '%".$mrno."%'";
    } else
 {
    $sql=$sql." and mr.mr_print_no like '%".$mrno."%'";
}
}

}

	
	
	
		$sql=$sql." 		order by mr.mr_print_no";

//echo $sql;

		$q = $this->db->query($sql);
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	
}
?>