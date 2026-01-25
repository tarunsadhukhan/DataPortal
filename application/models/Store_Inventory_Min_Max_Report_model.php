<?php
class Store_inventory_min_max_report_model extends CI_Model
{


	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'company_code','company_name'); //set column field database for datatable orderable
	var $column_search = array( 'company_code','company_name'); //set column field database for datatable searchable 
	// var $order = array('comp_id' => 'desc'); // default order



   
	
	public function __construct()
	{		
		$this->load->database();		
	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
 
		$sql="
		select concat(i.group_code,i.item_code) Item_Code,i.item_desc Item_Description,i.uom_code Unit,
minqty Min_Qty,maxqty Max_qty,Min_Order_qty,round(ifnull(stockqty,0),3) Stock_qty,
round(ifnull(pending_indent,0),3) Pending_indent_qty,
record_type,round(ifnull(l.pending_po,0),3) Pending_PO_Qty,
case when (round(ifnull(stockqty,0),3)+round(ifnull(pending_po,0),3))<minqty then (maxqty-round(ifnull(stockqty,0),3)-
round(ifnull(l.pending_po,0),3) ) else 0 end Qty_To_Be_Order
from tbl_proc_min_max tpmm 
left join itemmaster i on tpmm.item_id =i.item_id 
left join (
select item,case when sum(tran_qty)>0 then sum(tran_qty) else 0 end stockqty
from ( 
select item,received_qty tran_qty from 
view_proc_store_receipt_transfer_issue vpsrti where vpsrti.tran_status=3 and tran_type='SR'
union ALL 
select item,0-issued_qty tran_qty from 
view_proc_store_receipt_transfer_issue vpsrti where vpsrti.tran_status in (3,1) and tran_type='I'
) g group by item 
) k on tpmm.item_id =k.item
left join(
select item,sum(qty-ifnull(qty_recvd,0)) pending_po  from tbl_proc_po_detail tppd 
left join tbl_proc_po tpp on tpp.po_id =tppd.po 
where is_active =1 and tpp.status =3 and qty-ifnull(qty_recvd,0)>0 
group by item
) l on tpmm.item_id =l.item
left join
(
select tpid.item ,sum(tpid.qty-ifnull(tpid.qty_po,0)) pending_indent from tbl_proc_indent_detail tpid
left join tbl_proc_indent tpi on tpi.indent_id =tpid.indent
where tpi.indent_status =3 and tpid.is_active =1
and tpi.record_type ='INDENT' and (tpid.qty-ifnull(tpid.qty_po,0))>0 group by tpid.item 
) h on tpmm.item_id =h.item
left join 
(
select tpid.item ,tpi.record_type  from tbl_proc_indent_detail tpid
left join tbl_proc_indent tpi on tpi.indent_id =tpid.indent
where tpi.indent_status =3 and tpid.is_active =1
and tpi.record_type ='OPENINDENT'
) j on tpmm.item_id =j.item
where tpmm.is_active =1 and tpmm.company_id = ".$companyId."
order by concat(i.group_code,i.item_code)
";
echo $sql;
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
		$sql="
		select concat(i.group_code,i.item_code) Item_Code,i.item_desc Item_Description,i.uom_code Unit,
minqty Min_Qty,maxqty Max_qty,Min_Order_qty,round(ifnull(stockqty,0),3) Stock_qty,
round(ifnull(pending_indent,0),3) Pending_indent_qty,
record_type,round(ifnull(l.pending_po,0),3) Pending_PO_Qty,
case when (round(ifnull(stockqty,0),3)+round(ifnull(pending_po,0),3))<minqty then (maxqty-round(ifnull(stockqty,0),3)-
round(ifnull(l.pending_po,0),3) ) else 0 end Qty_To_Be_Order
from tbl_proc_min_max tpmm 
left join itemmaster i on tpmm.item_id =i.item_id 
left join (
select item,case when sum(tran_qty)>0 then sum(tran_qty) else 0 end stockqty
from ( 
select item,received_qty tran_qty from 
view_proc_store_receipt_transfer_issue vpsrti where vpsrti.tran_status=3 and tran_type='SR'
union ALL 
select item,0-issued_qty tran_qty from 
view_proc_store_receipt_transfer_issue vpsrti where vpsrti.tran_status in (3,1) and tran_type='I'
) g group by item 
) k on tpmm.item_id =k.item
left join(
select item,sum(qty-ifnull(qty_recvd,0)) pending_po  from tbl_proc_po_detail tppd 
left join tbl_proc_po tpp on tpp.po_id =tppd.po 
where is_active =1 and tpp.status =3 and qty-ifnull(qty_recvd,0)>0 
group by item
) l on tpmm.item_id =l.item
left join
(
select tpid.item ,sum(tpid.qty-ifnull(tpid.qty_po,0)) pending_indent from tbl_proc_indent_detail tpid
left join tbl_proc_indent tpi on tpi.indent_id =tpid.indent
where tpi.indent_status =3 and tpid.is_active =1
and tpi.record_type ='INDENT' and (tpid.qty-ifnull(tpid.qty_po,0))>0 group by tpid.item 
) h on tpmm.item_id =h.item
left join 
(
select tpid.item ,tpi.record_type  from tbl_proc_indent_detail tpid
left join tbl_proc_indent tpi on tpi.indent_id =tpid.indent
where tpi.indent_status =3 and tpid.is_active =1
and tpi.record_type ='OPENINDENT'
) j on tpmm.item_id =j.item
where tpmm.is_active =1 and tpmm.company_id =".$pers['company']."
order by concat(i.group_code,i.item_code)
";
/*
		$sql="select hdr_id `Issue_No`,issuedate `Issue_Date`,dept_desc `Department` , g.itemcode `Item_Code`,
		item_desc `Item_Description`,uom_code Unit,cost_desc `Cost_Center`,issue_qty `Issue_Quantity`,
		issue_value `Issue_Value`,indent_type_desc `EXP_Type`,branch_name Branch,store_print_no `SR_No`,
		mm.mechine_name `Mechine_Name` from 
		(
		select sih.company_id,cmm.company_name,bm.branch_name,sih.hdr_id,issue_date,DATE_FORMAT(issue_date, '%d-%m-%Y') 
		issuedate ,md.dept_desc,cm.cost_desc, concat(im.group_code,im.item_code) itemcode,im.item_desc,im.uom_code,sih.issue_qty,sih.issue_value,
		sitm.indent_type_desc,sih.machine_id,tpi.store_receipt_no store_print_no
		from scm_issue_hdr sih,branch_master bm,company_master cmm, master_department md,costmaster cm,itemmaster im,scm_indent_type_master sitm ,
		tbl_proc_inward tpi
		where sih.company_id=cmm.comp_id and sih.branch_id=bm.branch_id
		and sih.deptcost=cm.id and sih.company_id=cm.company_id and sih.item_id=im.item_id
		and sih.indent_type_id=sitm.indent_type_code and sih.is_active=1 
		and sih.inward =tpi.inward_id  and sih.dept_id=md.rec_id and issue_date  between '".date('Y-m-d',strtotime($pers['from_date']))."'  
		and '".date('Y-m-d',strtotime($pers['to_date']))."' 
		and sih.company_id=".$pers['company']."
		) g
		left  join
		mechine_master mm on g.machine_id=mm.mechine_id order by hdr_id";
*/
		echo $sql;
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