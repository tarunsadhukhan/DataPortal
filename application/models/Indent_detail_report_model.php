<?php
class Indent_detail_report_model extends CI_Model
{

	


	var $table = 'worker_master wm';	
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
		// $Source = $_POST['Source'];
		// $att_type = $_POST['att_type'];
		// $att_status = $_POST['att_status'];
		// $att_dept = $_POST['att_dept'];
		// $att_desig = $_POST['att_desig'];
		// $att_spells = $_POST['att_spells'];
		// $eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		if($companyId==118){
			$companywhere =" ";
			
		}else{
			$companywhere = " and h.company=".$companyId." ";
		}
		

		$sql = "SELECT
		h.fy AS fin_year,
		h.indent_squence_no AS INDENT_NO,
		l.indent_detail_id AS INDENT_SRL_NO,
		DATE_FORMAT(h.indent_date, '%d-%m-%Y') AS IndentDate,
		branch_master.branch_name AS branch_name,
		tpj.name AS prj_name,
		tpet.expense_type AS Indent_type,
		CONCAT(i.group_code, i.item_code) AS itemcode,
		l.qty AS INDENT_QTY,
		i.item_desc AS item_desc,
		l.uom_code AS UOM_CODE,
		h.remarks AS Remarks,
		(l.qty - l.cancelled_qty - pl.qty) AS OutSt_Qty,
		l.cancelled_qty,
		l.cancelled_date,
		sm.status_name AS Indentstatus,
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
		JOIN tbl_proc_indent_detail l ON h.indent_id = l.indent AND l.is_active = '1'
		LEFT JOIN tbl_proc_po_detail pl ON pl.indent_detail = l.indent_detail_id
		LEFT JOIN tbl_proc_po ph ON ph.po_id = pl.po
		LEFT JOIN tbl_proc_project tpj ON tpj.project_id = h.project
		LEFT JOIN branch_master ON branch_master.branch_id = h.branch
		LEFT JOIN itemmaster i ON i.item_id = l.item
		LEFT JOIN item_group_master ig ON ig.group_code = i.group_code
		LEFT JOIN suppliermaster sup ON sup.supp_id = ph.supplier
		LEFT JOIN tbl_proc_inward_detail sl ON sl.po_detail = pl.po_detail_id
		LEFT JOIN tbl_proc_inward sh ON sl.inward = sh.inward_id
		LEFT JOIN status_master sm ON sm.status_id = h.indent_status
		LEFT JOIN status_master sp ON sp.status_id = ph.status
		LEFT JOIN status_master ss ON ss.status_id = sh.sr_status
		LEFT JOIN tbl_proc_expense_type tpet ON tpet.id = h.category
	WHERE
		h.indent_date BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'".$companywhere."
	ORDER BY
		l.indent_detail_id,po_detail_id";//
		
		$i = 0;
		if($_POST['search']['value']){
			foreach ($this->column_search as $item){
				if($i===0){	
					$sql = $sql . $item ." LIKE ". $_POST['search']['value'];
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
			// $sql = $sql . "ORDER BY ". key($order) .",". $order[key($order)];
		}

		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];
		// $this->varaha->print_arrays($sql);		
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

		if($pers['company']==118){
			$companywhere ="";
		}else{
			$companywhere = " and h.company=".$pers['company']." ";
			
		}

		$sql = "SELECT
		h.fy AS fin_year,
		h.indent_squence_no AS INDENT_NO,
		l.indent_detail_id AS INDENT_SRL_NO,
		DATE_FORMAT(h.indent_date, '%d-%m-%Y') AS IndentDate,
		branch_master.branch_name AS branch_name,
		tpj.name AS prj_name,
		tpet.expense_type AS Indent_type,
		CONCAT(i.group_code, i.item_code) AS itemcode,
		l.qty AS INDENT_QTY,
		i.item_desc AS item_desc,
		l.uom_code AS UOM_CODE,
		h.remarks AS Remarks,
		(l.qty - l.cancelled_qty - pl.qty) AS OutSt_Qty,
		l.cancelled_qty,
		l.cancelled_date,
		sm.status_name AS Indentstatus,
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
		JOIN tbl_proc_indent_detail l ON h.indent_id = l.indent AND l.is_active = '1'
		LEFT JOIN tbl_proc_po_detail pl ON pl.indent_detail = l.indent_detail_id
		LEFT JOIN tbl_proc_po ph ON ph.po_id = pl.po
		LEFT JOIN tbl_proc_project tpj ON tpj.project_id = h.project
		LEFT JOIN branch_master ON branch_master.branch_id = h.branch
		LEFT JOIN itemmaster i ON i.item_id = l.item
		LEFT JOIN item_group_master ig ON ig.group_code = i.group_code
		LEFT JOIN suppliermaster sup ON sup.supp_id = ph.supplier
		LEFT JOIN tbl_proc_inward_detail sl ON sl.po_detail = pl.po_detail_id
		LEFT JOIN tbl_proc_inward sh ON sl.inward = sh.inward_id
		LEFT JOIN status_master sm ON sm.status_id = h.indent_status
		LEFT JOIN status_master sp ON sp.status_id = ph.status
		LEFT JOIN status_master ss ON ss.status_id = sh.sr_status
		LEFT JOIN tbl_proc_expense_type tpet ON tpet.id = h.category
	WHERE
		h.indent_date BETWEEN '".date('Y-m-d',strtotime($pers['from_date']))."' AND '".date('Y-m-d',strtotime($pers['to_date']))."'".$companywhere."
	ORDER BY
		l.indent_detail_id,po_detail_id"; //
		
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
	
