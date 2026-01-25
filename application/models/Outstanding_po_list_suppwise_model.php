<?php
class Outstanding_po_list_suppwise_model extends CI_Model
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
		
		$itcode = $_POST['itcod'];
		$suppname = $_POST['suppname'];
		

 
$sql="select po_no,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,make,qty ,cancelled_qty,rate , tax_type_name, tax_percentage,
 item_value,  tax_amount, total_amount,status_name,remarks, inwqty,qty_to_be_receive,outstanding_for_days  from ( 
select po_sequence_no po_no,po_detail_id,ifnull(po_approve_date,'$tday') po_approve_date,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,qty ,cancelled_qty,rate , tax_type_name, tax_percentage, 
 item_value,  tax_amount, total_amount,status_name,remarks,ifnull(inwqty,0 ) inwqty, 
round( (qty-cancelled_qty-ifnull(inwqty,0)),3) qty_to_be_receive,
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
where tpid.is_active =1 and tpi.sr_status not in (4,6,0,5) and po_detail  is not null
group by po_detail 
) b on a.po_detail_id=b.po_detail ) k where qty_to_be_receive>0

";
	//	echo $sql;
	if($itcode){
		$sql .= "  and itemcode= '".$itcode."'";
	}
	if($suppname){
		$sql .= "   and supp_name like  '%".$suppname."%'";
	}
/*
	if($costcenter){
		$sql .= "  and  cost_desc like  '%".$costcenter."%'";
	}
	if($itemdesc){
		$sql .= "   and item_desc like  '%".$itemdesc."%'";
	}
*/
	
	$sql .= " order by supp_name,po_approve_date  ASC ";

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

		
 
$sql="select po_no,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,make,qty ,cancelled_qty,rate , tax_type_name, tax_percentage,
 item_value,  tax_amount, total_amount,status_name,remarks, inwqty,qty_to_be_receive,outstanding_for_days  from ( 
select po_sequence_no po_no,po_detail_id,ifnull(po_approve_date,'2023-10-15') po_approve_date,poapprovedate,indent_type_desc, prj_name,dept_desc,supp_name,itemcode,item_desc 
,uom_code ,qty ,cancelled_qty,rate , tax_type_name, tax_percentage, 
 item_value,  tax_amount, total_amount,status_name,remarks,ifnull(inwqty,0 ) inwqty, 
 round((qty-cancelled_qty-ifnull(inwqty,0)),3) qty_to_be_receive,
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
where tpp.company=".$pers['company']." and  tpp.status not in (4,6,0,5) and tppd.is_active =1 
and po_date between '".$pers['from_date']."' and '".$pers['to_date']."'
) a left join
(select po_detail,sum(inward_qty) inwqty from tbl_proc_inward_detail tpid 
left join tbl_proc_inward tpi on tpi.inward_id =tpid.inward 
where tpid.is_active =1 and tpi.sr_status not in (4,6,0,5) and po_detail  is not null
group by po_detail 
) b on a.po_detail_id=b.po_detail ) k where qty_to_be_receive>0
order by supp_name,po_approve_date  ASC 
";
		
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
	
}
?>
	
