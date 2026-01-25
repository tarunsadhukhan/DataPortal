<?php
class Inventory_minmax_report_model extends CI_Model
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
		where tpmm.is_active =1 and tpmm.company_id = ".$companyId." and tpmm.branch_id=29
		and tpmm.minqty+tpmm.maxqty>0
		order by concat(i.group_code,i.item_code)
		";
			
		

	//	echo $sql;

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
where tpmm.is_active =1 and tpmm.company_id =".$pers['company']." and tpmm.branch_id=29
		and tpmm.minqty+tpmm.maxqty>0
order by concat(i.group_code,i.item_code)
";
	
	
	



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