<?php
class Store_issue_is02_report_model extends CI_Model
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
				select company_id,branch_id,order_id,dept_desc,cost_desc,
				MAX(CASE WHEN indent_type_desc = 'PRODUCTION' THEN issue_value else 0 END) 'Production',
				MAX(CASE WHEN indent_type_desc = 'OVERHAULING' THEN issue_value else 0 END) 'OVERHAULING',
				MAX(CASE WHEN indent_type_desc = 'MAINTENANCE' THEN issue_value else 0 END) 'MAINTENANCE',
				MAX(CASE WHEN indent_type_desc = 'CAPITAL' THEN issue_value else 0 END) 'CAPITAL',
				MAX(CASE WHEN indent_type_desc = 'GENERAL' THEN issue_value else 0 END) 'GENERAL'
				from (
				select company_id,branch_id,order_id,dept_desc,cost_desc,indent_type_desc,
				round(sum(iss_val),2) issue_value from  (
				select sih.company_id,sih.branch_id,issue_date,issue_no,dept_id,deptcost,md.dept_desc,
				md.dept_code,cost_desc ,sih.indent_type_id ,sitm.indent_type_desc , item_id,
				group_code,item_code,concat(GROUP_CODE,item_code) itemcode , issue_qty,
				(case
        when (`tpid`.`discount_mode` = 1) then (((`tpid`.`approved_qty` * `tpid`.`rate`) - `tpid`.`discount`) / `tpid`.`approved_qty`)*sih.issue_qty 
        when (`tpid`.`discount_mode` = 2) then (`tpid`.`rate` - ((`tpid`.`rate` * `tpid`.`discount`) / 100))*sih.issue_qty 
        else round(`tpid`.`rate`, 2)*sih.issue_qty 
    end) AS iss_val,
				issue_value issval,tpid.rate ,md.order_id 
				from 			scm_issue_hdr sih
						left join master_department md on
							sih.dept_id = md.rec_id
						left join costmaster c on
							sih.deptcost = c.id
							and sih.company_id = c.company_id
								left join tbl_proc_inward_detail tpid on
									sih.sr_line_id = tpid.indent_details_id
						left join scm_indent_type_master sitm on
							sih.indent_type_id = sitm.indent_type_code
				where   issue_date >= '".$from_date."'
				and issue_date <= '".$to_date."' 
				and issue_status =3  and sih.is_active =1
				) g group by company_id,branch_id,order_id,dept_desc,cost_desc,indent_type_desc
				) m
				group by company_id,branch_id,order_id,dept_desc,cost_desc
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

		$sql .= "	order by order_id,dept_desc,cost_desc
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




 
  
$sql .= "	order by
order_id,
dept_desc,
cost_desc,
itemcode,
item_desc
";

$sql="select k.*,(Production+OVERHAULING+MAINTENANCE+CAPITAL+GENERAL) total_amt from (
	select company_id,branch_id,order_id,dept_desc,cost_desc,
	MAX(CASE WHEN indent_type_desc = 'PRODUCTION' THEN issue_value else 0 END) 'Production',
	MAX(CASE WHEN indent_type_desc = 'OVERHAULING' THEN issue_value else 0 END) 'OVERHAULING',
	MAX(CASE WHEN indent_type_desc = 'MAINTENANCE' THEN issue_value else 0 END) 'MAINTENANCE',
	MAX(CASE WHEN indent_type_desc = 'CAPITAL' THEN issue_value else 0 END) 'CAPITAL',
	MAX(CASE WHEN indent_type_desc = 'GENERAL' THEN issue_value else 0 END) 'GENERAL'
	from (
	select company_id,branch_id,order_id,dept_desc,cost_desc,indent_type_desc,
	round(sum(iss_val),2) issue_value from  (
	select sih.company_id,sih.branch_id,issue_date,issue_no,dept_id,deptcost,md.dept_desc,md.dept_code,cost_desc ,sih.indent_type_id ,sitm.indent_type_desc , item_id,
	group_code,item_code,concat(GROUP_CODE,item_code) itemcode , issue_qty,
					(case
        when (`tpid`.`discount_mode` = 1) then (((`tpid`.`approved_qty` * `tpid`.`rate`) - `tpid`.`discount`) / `tpid`.`approved_qty`)*sih.issue_qty 
        when (`tpid`.`discount_mode` = 2) then (`tpid`.`rate` - ((`tpid`.`rate` * `tpid`.`discount`) / 100))*sih.issue_qty 
        else round(`tpid`.`rate`, 2)*sih.issue_qty 
    end) AS iss_val,
	issue_value issval,
	tpid.rate ,md.order_id 
	from 			scm_issue_hdr sih
			left join master_department md on
				sih.dept_id = md.rec_id
			left join costmaster c on
				sih.deptcost = c.id
				and sih.company_id = c.company_id
					left join tbl_proc_inward_detail tpid on
						sih.sr_line_id = tpid.indent_details_id
			left join scm_indent_type_master sitm on
				sih.indent_type_id = sitm.indent_type_code
	where   issue_date >= '".$pers['from_date']."'
	and issue_date <= '".$pers['to_date']."'
	and issue_status =3  and sih.is_active =1
	) g group by company_id,branch_id,order_id,dept_desc,cost_desc,indent_type_desc
	) m
	group by company_id,branch_id,order_id,dept_desc,cost_desc
	) k where company_id= ".$pers['company'];


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