<?php
class Store_issue_is06_report_model extends CI_Model
{


	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'company_code','company_name'); //set column field database for datatable orderable
	var $column_search = array( 'company_code','company_name'); //set column field database for datatable searchable 
	// var $order = array('comp_id' => 'desc'); // default order



   
	
	public function __construct()
	{		
		$this->load->database();		
		$this->load->library('session');	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
 $eb_no = $_POST['eb_no'];
	
$this->load->library('session');
		$itcode = $_POST['itcod'];
		$costcenter = $_POST['costcenter'];
		$itemdesc = $_POST['itemdesc'];
//		echo $itemdesc;

  				 
					$sql="select k.*,(Production+OVERHAULING+MAINTENANCE+CAPITAL+GENERAL) total_amt from (
						select company_id,branch_id,group_code,group_desc,
						MAX(CASE WHEN indent_type_desc = 'PRODUCTION' THEN issue_value else 0 END) 'Production',
						MAX(CASE WHEN indent_type_desc = 'OVERHAULING' THEN issue_value else 0 END) 'OVERHAULING',
						MAX(CASE WHEN indent_type_desc = 'MAINTENANCE' THEN issue_value else 0 END) 'MAINTENANCE',
						MAX(CASE WHEN indent_type_desc = 'CAPITAL' THEN issue_value else 0 END) 'CAPITAL',
						MAX(CASE WHEN indent_type_desc = 'GENERAL' THEN issue_value else 0 END) 'GENERAL'
						from (
						select company_id,branch_id,group_code,group_desc,indent_type_desc,
						round(sum(iss_val),2) issue_value from  (
						select sih.company_id,sih.branch_id,sih.indent_type_id ,sitm.indent_type_desc, 
						igm.group_code,igm.group_desc , issue_qty,issue_qty * tpid.rate iss_val,issue_value issval,tpid.rate 
						from scm_issue_hdr sih
						left join item_group_master igm  on sih.group_code=igm.group_code and sih.company_id=igm.company_id 
						left join tbl_proc_inward_detail tpid on sih.sr_line_id = tpid.indent_details_id
						left join scm_indent_type_master sitm on sih.indent_type_id = sitm.indent_type_code
						where   issue_date >= '".$from_date."'
						and issue_date <= '".$to_date."' 
						and issue_status =3  and sih.is_active =1
						) g group by company_id,branch_id,group_code,group_desc,indent_type_desc
						) m group by company_id,branch_id,group_code,group_desc
						) k where company_id= ".$companyId;
						
					$ln=0;
		$n=1;	
		$ln=strlen($itcode)+strlen($costcenter)+strlen($itemdesc);
		if ($ln>0) {
			$sql .= " where  ";
		}	
		if($itcode){
			$sql .= "   itemcode= '".$itcode."'";
		}
		if($itemdesc){
			$sql .= "   item_desc like  '%".$itemdesc."%'";
		}
		$sql .= "	order by group_code
					
	";


 


//		echo $sql;

//echo $sql;
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

		$sql="select k.*,(Production+OVERHAULING+MAINTENANCE+CAPITAL+GENERAL) total_amt from (
			select company_id,branch_id,group_code,group_desc,
			MAX(CASE WHEN indent_type_desc = 'PRODUCTION' THEN issue_value else 0 END) 'Production',
			MAX(CASE WHEN indent_type_desc = 'OVERHAULING' THEN issue_value else 0 END) 'OVERHAULING',
			MAX(CASE WHEN indent_type_desc = 'MAINTENANCE' THEN issue_value else 0 END) 'MAINTENANCE',
			MAX(CASE WHEN indent_type_desc = 'CAPITAL' THEN issue_value else 0 END) 'CAPITAL',
			MAX(CASE WHEN indent_type_desc = 'GENERAL' THEN issue_value else 0 END) 'GENERAL'
			from (
			select company_id,branch_id,group_code,group_desc,indent_type_desc,
			round(sum(iss_val),2) issue_value from  (
			select sih.company_id,sih.branch_id,sih.indent_type_id ,sitm.indent_type_desc, 
			igm.group_code,igm.group_desc , issue_qty,issue_qty * tpid.rate iss_val,issue_value issval,tpid.rate 
			from scm_issue_hdr sih
			left join item_group_master igm  on sih.group_code=igm.group_code and sih.company_id=igm.company_id 
			left join tbl_proc_inward_detail tpid on sih.sr_line_id = tpid.indent_details_id
			left join scm_indent_type_master sitm on sih.indent_type_id = sitm.indent_type_code
			where   issue_date >= '".$pers['from_date']."'
			and issue_date <= '".$pers['to_date']."' 
			and issue_status =3  and sih.is_active =1
			) g group by company_id,branch_id,group_code,group_desc,indent_type_desc
			) m group by company_id,branch_id,group_code,group_desc
			) k where company_id= ".$pers['company'];
			$sql .= "	order by group_code";
 
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