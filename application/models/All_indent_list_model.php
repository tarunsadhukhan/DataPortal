<?php
class All_indent_list_model extends CI_Model
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
		
		// $eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		$itcode = $_POST['itcod'];
		$itemdesc = $_POST['itemdesc'];
	 

 
$sql="select
indent_no,
Indent_date,
prj_name,
indent_type,
indentstatus,
itemcode,
item_desc,
uom_code,
indent_qty,
cancelled_qty,
poqty,
srqty ,
qty_outstanding_for_po,
qty_outstanding_for_receive,
outstanding_for_days
from
(
select
	g.*,
	(indent_qty-cancelled_qty-poqty) qty_outstanding_for_po,
	(indent_qty-cancelled_qty-srqty) qty_outstanding_for_receive,
	case
		when indentstatus <> 'CLOSED' THEN datediff(CURDATE(), indentdate)
		else 0
	end outstanding_for_days
from
	(
	select
		indent_no,
		Indentdate,
		DATE_FORMAT(Indentdate, '%d-%m-%Y') Indent_date,
		prj_name,
		indent_type,
		indentstatus,
		itemcode,
		item_desc,
		uom_code,
		indent_qty,
		ifnull(cancelled_qty, 0) cancelled_qty,
		sum(ifnull(poquantity, 0)) poqty,
		sum(ifnull(srquantity, 0)) srqty,
		company_id
	from
		(
		SELECT
			h.fy AS fin_year,
			h.indent_squence_no AS indent_no,
			l.indent_detail_id AS INDENT_SRL_NO,
			CAST(h.indent_date AS date) AS IndentDate,
			branch_master.branch_name AS branch_name,
			tpj.name AS prj_name,
			tpet.expense_type AS indent_type,
			CONCAT(i.group_code, i.item_code) AS itemcode,
			l.qty AS indent_qty,
			i.item_desc AS item_desc,
			l.uom_code AS uom_code,
			h.remarks AS Remarks,
			(l.qty - l.cancelled_qty - pl.qty) AS OutSt_Qty,
			l.cancelled_qty,
			l.cancelled_date,
			sm.status_name AS indentstatus,
			sup.supp_name AS supp_name,
			ph.po_sequence_no AS po_num,
			pl.po_detail_id AS LINE_ITEM_NUM,
			CAST(ph.po_date AS date) AS PO_DATE,
			pl.qty AS poQuantity,
			pl.qty - sl.approved_qty - pl.cancelled_qty AS Pending_Qty_PO,
			sh.store_receipt_no AS STORE_RECEIVE_NO,
			CAST(sh.store_receipt_date AS date) AS SRdate,
			sl.approved_qty AS srQuantity,
			h.company AS company_id
		FROM
			tbl_proc_indent h
		JOIN tbl_proc_indent_detail l ON
			h.indent_id = l.indent
			AND l.is_active = '1'
		LEFT JOIN tbl_proc_po_detail pl ON
			pl.indent_detail = l.indent_detail_id
		LEFT JOIN tbl_proc_po ph ON
			ph.po_id = pl.po
		LEFT JOIN tbl_proc_project tpj ON
			tpj.project_id = h.project
		LEFT JOIN branch_master ON
			branch_master.branch_id = h.branch
		LEFT JOIN itemmaster i ON
			i.item_id = l.item
		LEFT JOIN item_group_master ig ON
			ig.group_code = i.group_code and ig.company_id=h.company
		LEFT JOIN suppliermaster sup ON
			sup.supp_id = ph.supplier
		LEFT JOIN tbl_proc_inward_detail sl ON
			sl.po_detail = pl.po_detail_id
		LEFT JOIN tbl_proc_inward sh ON
			sl.inward = sh.inward_id
		LEFT JOIN status_master sm ON
			sm.status_id = h.indent_status
		LEFT JOIN status_master sp ON
			sp.status_id = ph.status
		LEFT JOIN status_master ss ON
			ss.status_id = sh.sr_status
		LEFT JOIN tbl_proc_expense_type tpet ON
			tpet.id = h.category
		WHERE
			h.indent_date between '".$from_date."' and '".$to_date."'
			and h.company =  ".$companyId."
			and l.is_active = 1
			and h.indent_status not in (4, 6, 0) ) g
	group by
		company_id,	
		indent_no,
		Indentdate,
		DATE_FORMAT(Indentdate, '%d-%m-%Y'),
		prj_name,
		indent_type,
		indentstatus,
		itemcode,
		item_desc,
		uom_code,
		indent_qty,
		ifnull(cancelled_qty, 0) ) g ) h where company_id=".$companyId;
		if ($itcode) {
			$sql=$sql." and itemcode='".$itcode."'";
		}
		if ($itemdesc) {
			$sql=$sql." and item_desc='".$itemdesc."'";
		}

$sql=$sql." order by
indentdate,
indent_no ASC
";

 

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

		$itcode = $pers['itcod'];
		$itemdesc = $pers['itemdesc'];
//		
 
$sql="select
indent_no,
indent_date,
prj_name,
indent_type,
indentstatus,
itemcode,
item_desc,
uom_code,
indent_qty,
cancelled_qty,
poqty,
srqty ,
qty_outstanding_for_po,
qty_outstanding_for_receive,
outstanding_for_days
from
(
select
	g.*,
	(indent_qty-cancelled_qty-poqty) qty_outstanding_for_po,
	(indent_qty-cancelled_qty-srqty) qty_outstanding_for_receive,
	case
		when indentstatus <> 'CLOSED' THEN datediff(CURDATE(), indentdate)
		else 0
	end outstanding_for_days
from
	(
	select
		indent_no,
		Indentdate,
		DATE_FORMAT(Indentdate, '%d-%m-%Y') Indent_date,
		prj_name,
		indent_type,
		indentstatus,
		itemcode,
		item_desc,
		uom_code,
		indent_qty,
		ifnull(cancelled_qty, 0) cancelled_qty,
		sum(ifnull(poquantity, 0)) poqty,
		sum(ifnull(srquantity, 0)) srqty,company_id
	from
		(
		SELECT
			h.fy AS fin_year,
			h.indent_squence_no AS indent_no,
			l.indent_detail_id AS INDENT_SRL_NO,
			CAST(h.indent_date AS date) AS IndentDate,
			branch_master.branch_name AS branch_name,
			tpj.name AS prj_name,
			tpet.expense_type AS indent_type,
			CONCAT(i.group_code, i.item_code) AS itemcode,
			l.qty AS indent_qty,
			i.item_desc AS item_desc,
			l.uom_code AS uom_code,
			h.remarks AS Remarks,
			(l.qty - l.cancelled_qty - pl.qty) AS OutSt_Qty,
			l.cancelled_qty,
			l.cancelled_date,
			sm.status_name AS indentstatus,
			sup.supp_name AS supp_name,
			ph.po_sequence_no AS po_num,
			pl.po_detail_id AS LINE_ITEM_NUM,
			CAST(ph.po_date AS date) AS PO_DATE,
			pl.qty AS poQuantity,
			pl.qty - sl.approved_qty - pl.cancelled_qty AS Pending_Qty_PO,
			sh.store_receipt_no AS STORE_RECEIVE_NO,
			CAST(sh.store_receipt_date AS date) AS SRdate,
			sl.approved_qty AS srQuantity,
			h.company AS company_id
		FROM
			tbl_proc_indent h
		JOIN tbl_proc_indent_detail l ON
			h.indent_id = l.indent
			AND l.is_active = '1'
		LEFT JOIN tbl_proc_po_detail pl ON
			pl.indent_detail = l.indent_detail_id
		LEFT JOIN tbl_proc_po ph ON
			ph.po_id = pl.po
		LEFT JOIN tbl_proc_project tpj ON
			tpj.project_id = h.project
		LEFT JOIN branch_master ON
			branch_master.branch_id = h.branch
		LEFT JOIN itemmaster i ON
			i.item_id = l.item
		LEFT JOIN item_group_master ig ON
			ig.group_code = i.group_code and ig.company_id=h.company
		LEFT JOIN suppliermaster sup ON
			sup.supp_id = ph.supplier
		LEFT JOIN tbl_proc_inward_detail sl ON
			sl.po_detail = pl.po_detail_id
		LEFT JOIN tbl_proc_inward sh ON
			sl.inward = sh.inward_id
		LEFT JOIN status_master sm ON
			sm.status_id = h.indent_status
		LEFT JOIN status_master sp ON
			sp.status_id = ph.status
		LEFT JOIN status_master ss ON
			ss.status_id = sh.sr_status
		LEFT JOIN tbl_proc_expense_type tpet ON
			tpet.id = h.category
		WHERE
			h.indent_date between '".$pers['from_date']."' and '".$pers['to_date']."'
			and h.company = ".$pers['company']."
			and l.is_active = 1
			and h.indent_status not in (4, 6, 0) ) g
	group by
		h.company_id,	
		indent_no,
		Indentdate,
		DATE_FORMAT(Indentdate, '%d-%m-%Y'),
		prj_name,
		indent_type,
		indentstatus,
		itemcode,
		item_desc,
		uom_code,
		indent_qty,
		ifnull(cancelled_qty, 0) ) g ) h where company_id=".$pers['company'];
		if ($itcode) {
			$sql=$sql." and itemcode='".$itcode."'";
		}
		if ($itemdesc) {
			$sql=$sql." and item_desc='".$itemdesc."'";
		}

$sql=$sql." order by
indentdate,
indent_no ASC
";

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
	
}
?>
	
