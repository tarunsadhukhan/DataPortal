<?php
class Store_item_monthwise_consumption_model extends CI_Model
{


	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'company_code','company_name'); //set column field database for datatable orderable
	var $column_search = array( 'company_code','company_name'); //set column field database for datatable searchable 
	// var $order = array('comp_id' => 'desc'); // default order



   
	
	public function __construct()
	{		
		$this->load->database();		
	}
	
	function get_monthdata($subId,$compId,$startDate,$endDate,$itcode)
	{
//		$itcode = $_POST['itcod'];
	//	$itcode=$_POST['itcode_chk'];
	 

		$sql = "select distinct(CONCAT(substr(MONTHNAME(tran_date),1,3),YEAR(tran_date))) cmnyr ,concat(substr(tran_date,3,2),substr(tran_date,6,2)) yymm
		from view_proc_store_receipt_transfer_issue vpsrti
		where company_id =".$compId." and tran_type ='I' and tran_status =3 
		AND tran_date between '".$startDate."' and '".$endDate."'";
		if ($itcode) {
			$sql=$sql." and concat(group_code,item_code)='".$itcode."'";
		}
		$sql=$sql." order by concat(substr(tran_date,3,2),substr(tran_date,6,2))";
	//	echo 'itemmn '.$itcode;
	//	echo $sql;
		$query = $this->db->query($sql);
		return $query->result();
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$itcode = $_POST['itcod'];
	//	$itcode=$_POST['itcode_chk'];
	$sql="	select distinct(CONCAT(substr(MONTHNAME(tran_date),1,3),YEAR(tran_date))) cmnyr ,concat(substr(tran_date,3,2),substr(tran_date,6,2)) yymm
	from view_proc_store_receipt_transfer_issue vpsrti
	where company_id =".$companyId." and tran_type ='I' and tran_status =3 
	AND tran_date between '".$from_date."' and '".$to_date."'";
	if ($itcode) {
		$sql=$sql." and concat(group_code,item_code)='".$itcode."'";
	}

	$sql=$sql." order by concat(substr(tran_date,3,2),substr(tran_date,6,2))";
	$sqlm="select item,concat(group_code,item_code) itemcode,item_desc,";
	
	$listm = $this->Store_item_monthwise_consumption_model->get_monthdata($submenuId,$companyId,$from_date,$to_date,$itcode);
	foreach ($listm as $loc) {
		$sqlm=$sqlm."MAX(Case when yymm='".$loc->yymm."' then mqty end) ".$loc->cmnyr.",";
	}
	$sqlm = substr($sqlm, 0, -1)." from";
	$sql=$sqlm." 
	(	select CONCAT(substr(MONTHNAME(tran_date),1,3),'-',YEAR(tran_date)) cmnyr ,concat(substr(tran_date,3,2),substr(tran_date,6,2)) yymm,
	item,round(sum(issued_qty),3) mqty,round(sum(issued_val),2) mval from view_proc_store_receipt_transfer_issue vpsrti
	where company_id =".$companyId."  and tran_type ='I' and tran_status =3 
	AND tran_date between '".$from_date."' and '".$to_date."'";
	if ($itcode) {
		$sql=$sql." and concat(group_code,item_code)='".$itcode."'";
	}
	$sql=$sql." group by CONCAT(substr(MONTHNAME(tran_date),1,3),'-',YEAR(tran_date)),concat(substr(tran_date,3,2),substr(tran_date,6,2)),
	item order by item,concat(substr(tran_date,3,2),substr(tran_date,6,2))
	) k left join itemmaster i on k.item=i.item_id
	group by item,concat(group_code,item_code) ,item_desc
	ORDER by concat(group_code, item_code)";

		
	//	echo 'item '.$itcode;
//		echo $sql;
 
	 
			
			return $sql;
	}



	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];
//echo $sql;
		$query = $this->db->query($sql);
//		 $this->varaha->print_arrays($this->db->last_query());
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
		
		$sql="	select distinct(CONCAT(substr(MONTHNAME(tran_date),1,3),YEAR(tran_date))) cmnyr ,concat(substr(tran_date,3,2),substr(tran_date,6,2)) yymm
		from view_proc_store_receipt_transfer_issue vpsrti
		where company_id =".$pers['company']." and tran_type ='I' and tran_status =3 
		AND tran_date between '".$pers['from_date']."' and '".$pers['to_date']."'";
		if ($pers['itcod']) {
			$sql=$sql." and concat(group_code,item_code)='".$pers['itcod']."'";
		}
		$sql=$sql." order by concat(substr(tran_date,3,2),substr(tran_date,6,2))";
		$sqlm="select concat(group_code,item_code) itemcode,item_desc,";
		$listm = $this->Store_item_monthwise_consumption_model->get_monthdata($pers['$submenuId'],$pers['company'],$pers['from_date'],$pers['to_date'],$pers['itcod']);
		foreach ($listm as $loc) {
			$sqlm=$sqlm."MAX(Case when yymm='".$loc->yymm."' then mqty end) ".$loc->cmnyr.",";
		}
		$sqlm = substr($sqlm, 0, -1)." from";
		$sql=$sqlm." 
		(	select CONCAT(substr(MONTHNAME(tran_date),1,3),'-',YEAR(tran_date)) cmnyr ,concat(substr(tran_date,3,2),substr(tran_date,6,2)) yymm,
		item,round(sum(issued_qty),3) mqty,round(sum(issued_val),2) mval from view_proc_store_receipt_transfer_issue vpsrti
		where company_id =".$pers['company']."  and tran_type ='I' and tran_status =3 
		AND tran_date between '".$pers['from_date']."' and '".$pers['to_date']."'";
		if ($pers['itcod']) {
			$sql=$sql." and concat(group_code,item_code)='".$pers['itcod']."'";
		}
		$sql=$sql." group by CONCAT(substr(MONTHNAME(tran_date),1,3),'-',YEAR(tran_date)),concat(substr(tran_date,3,2),substr(tran_date,6,2)),
		item order by item,concat(substr(tran_date,3,2),substr(tran_date,6,2))
		) k left join itemmaster i on k.item=i.item_id 
		group by item,concat(group_code,item_code) ,item_desc
		 ORDER by concat(group_code, item_code)";
		
	//	echo $sql;
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