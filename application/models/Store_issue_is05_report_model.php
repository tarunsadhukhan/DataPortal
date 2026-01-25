<?php
class Store_issue_is05_report_model extends CI_Model
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

  				
				$sql="select k.* from (
					select sih.company_id,sih.branch_id,sih.machine_id,mm.mechine_name,indent_type_desc,sih.item_id,
					i.group_code,i.item_code,concat(i.GROUP_CODE,i.item_code) itemcode ,item_desc, round(sum(issue_qty),3) issue_qty,
					round(sum(issue_qty * tpid.rate),2)  iss_val,
					round(sum(issue_value),2) issval,md.dept_desc 
					from scm_issue_hdr sih
					left join itemmaster i on i.item_id =sih.item_id 
					left join tbl_proc_inward_detail tpid on sih.sr_line_id = tpid.indent_details_id
					left join mechine_master mm on mm.mechine_id = sih.machine_id 
					left join scm_indent_type_master sitm on sih.indent_type_id = sitm.indent_type_code
					left join master_department md on sih.dept_id = md.rec_id
					where   issue_date >= '".$from_date."'
					and issue_date <= '".$to_date."'  
					and issue_status =3  and sih.is_active =1 and ifnull(mechine_id,0)>0
					group by  sih.company_id,sih.branch_id,sih.machine_id,mm.mechine_name,indent_type_desc,sih.item_id,
					i.group_code,i.item_code,concat(i.GROUP_CODE,i.item_code),md.dept_desc,item_desc   
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

		$sql .= "	order by dept_desc,mechine_name
					
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

 
		$sql="select k.* from (
		select sih.company_id,sih.branch_id,sih.machine_id,mm.mechine_name,indent_type_desc,sih.item_id,
		i.group_code,i.item_code,concat(i.GROUP_CODE,i.item_code) itemcode ,item_desc, round(sum(issue_qty),3) issue_qty,
		round(sum(issue_qty * tpid.rate),2)  iss_val,
		round(sum(issue_value),2) issval,md.dept_desc 
		from scm_issue_hdr sih
		left join itemmaster i on i.item_id =sih.item_id 
		left join tbl_proc_inward_detail tpid on sih.sr_line_id = tpid.indent_details_id
		left join mechine_master mm on mm.mechine_id = sih.machine_id 
		left join scm_indent_type_master sitm on sih.indent_type_id = sitm.indent_type_code
		left join master_department md on sih.dept_id = md.rec_id
		where   issue_date >= '".$pers['from_date']."'
		and issue_date <= '".$pers['to_date']."'
			and issue_status =3  and sih.is_active =1 and ifnull(mechine_id,0)>0
		group by  sih.company_id,sih.branch_id,sih.machine_id,mm.mechine_name,indent_type_desc,sih.item_id,
		i.group_code,i.item_code,concat(i.GROUP_CODE,i.item_code),md.dept_desc,item_desc   
		) k where company_id= ".$pers['company'];
		$sql .= "	order by dept_desc,mechine_name";
					

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