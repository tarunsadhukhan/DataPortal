<?php
class Store_item_ledger_report_model extends CI_Model
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
		
		

		$itcode = $_POST['itcod'];
		$itemdesc = $_POST['itemdesc'];
		$itemid=0;
		$ln=strlen($itcode)+strlen($itemdesc);

		if ($ln>0) {
			if($itcode){
				$sql="select item_id from itemmaster where company_id=".$companyId." and  concat(group_code,item_code)='".$itcode."' ";
			} else
			if($itemdesc){
				$sql="select item_id from itemmaster where company_id=".$companyId." and item_desc='".$itemdesc."'";
			}
			$query = $this->db->query($sql);
			$row = $query->row(); 
			$itemid = $row->item_id;
		}
		$sql="select
		itemcode,
		item_name,uom_code,
		tran_type,
		tran_date1,
		concat(sr_print_no, '(', hdr_id, ')') doc_no,
		open_qty,
		open_val,
		tranrecv_qty,
		tranrecv_val,
		tranissu_qty,
		tranissu_val
from
	(
	SELECT
		company_id,
		branch_id,group_code,
		CONCAT(group_code, item_code) itemcode,
		item_name,
		'O' tran_type,
		date_format('".$from_date."', '%d-%m-%Y') tran_date1 ,
		' ' sr_print_no ,
		'Opening' status_name,
		round(sum(received_qty-issued_qty), 3) open_qty,
		round(sum(received_val-issued_val), 2) open_val,
		0 tranrecv_qty,
		0 tranrecv_val,
		0 tranissu_qty,
		0 tranissu_val,
		'".$from_date."' tran_date,
		1 trn,
		0 hdr_id,uom_code
	FROM
		view_proc_store_receipt_transfer_issue
	WHERE
		tran_status IN (3)
		and tran_date<'".$from_date."'
		and company_id = ".$companyId."
		and item= ".$itemid."
	group by
		company_id,
		branch_id,
		group_code,
		CONCAT(group_code, item_code),
		item_name,uom_code
union all
	SELECT
		vpsrti.company_id,
		vpsrti.branch_id,
		vpsrti.group_code,
		CONCAT(vpsrti.group_code, vpsrti.item_code) itemcode,
		item_name,
		tran_type ,
		date_format(tran_date, '%d-%m-%Y') tran_date1 ,
		sr_print_no ,
		status_name ,
		0 open_qty,
		0 open_val ,
		round(received_qty, 3) tranrecv_qty,
		round(received_val, 2) tranrecv_val,
		round(issued_qty, 3) tranissu_qty,
		round(issued_val, 2) tranissu_val,
		tran_date,
		case
			when tran_type = 'SR' then 2
			else 3
		end trn,
		ifnull(hdr_id, 0) hdr_id,vpsrti.uom_code
	FROM
		view_proc_store_receipt_transfer_issue vpsrti
	left join scm_issue_hdr sih on
		sih.issue_no = vpsrti.tran_hdr_id
	WHERE
		tran_status IN (3)
		and tran_date >= '".$from_date."'
		and tran_date <= '".$to_date."'
		and vpsrti.company_id = ".$companyId."
	 	and item= ".$itemid." 
		and tran_type not in ('PO') ) g
order by
	tran_date,
	trn asc
 ";
//			echo $sql;
			return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
	//	echo $sql;
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
		$itcode = $pers['itcod'];
		$itemdesc = $pers['itemdesc'];
		$itemid=0;
		$ln=strlen($itcode)+strlen($itemdesc);
 		if ($ln>0) {
			if($itcode){
				$sql="select item_id from itemmaster where company_id=".$pers['company']." and concat(group_code,item_code)='".$itcode."'";
			} else
			if($itemdesc){
				$sql="select item_id from itemmaster where where company_id=".$pers['company']."  andconcat(item_desc)='".$itemdesc."'";
			}
			$query = $this->db->query($sql);
			$row = $query->row(); 
			$itemid = $row->item_id;
		}

		$sql="select
		itemcode,
		item_name,uom_code,
		tran_type,
		tran_date1,
		concat(sr_print_no, '(', hdr_id, ')') doc_no,
		open_qty,
		open_val,
		tranrecv_qty,
		tranrecv_val,
		tranissu_qty,
		tranissu_val
from
	(
	SELECT
		company_id,
		branch_id,group_code,
		CONCAT(group_code, item_code) itemcode,
		item_name,uom_code,
		'O' tran_type,
		date_format('".$pers['from_date']."', '%d-%m-%Y') tran_date1 ,
		' ' sr_print_no ,
		'Opening' status_name,
		round(sum(received_qty-issued_qty), 3) open_qty,
		round(sum(received_val-issued_val), 2) open_val,
		0 tranrecv_qty,
		0 tranrecv_val,
		0 tranissu_qty,
		0 tranissu_val,
		'".$pers['from_date']."' tran_date,
		1 trn,
		0 hdr_id
	FROM
		view_proc_store_receipt_transfer_issue
	WHERE
		tran_status IN (3)
		and tran_date<'".$pers['from_date']."'
		and company_id = ".$pers['company']."
		and item= ".$itemid."
	group by
		company_id,
		branch_id,
		group_code,
		CONCAT(group_code, item_code),
		item_name,uom_code
union all
	SELECT
		vpsrti.company_id,
		vpsrti.branch_id,
		vpsrti.group_code,
		CONCAT(vpsrti.group_code, vpsrti.item_code) itemcode,
		item_name,vpsrti.uom_code,
		tran_type ,
		date_format(tran_date, '%d-%m-%Y') tran_date1 ,
		sr_print_no ,
		status_name ,
		0 open_qty,
		0 open_val ,
		round(received_qty, 3) tranrecv_qty,
		round(received_val, 2) tranrecv_val,
		round(issued_qty, 3) tranissu_qty,
		round(issued_val, 2) tranissu_val,
		tran_date,
		case
			when tran_type = 'SR' then 2
			else 3
		end trn,
		ifnull(hdr_id, 0) hdr_id
	FROM
		view_proc_store_receipt_transfer_issue vpsrti
	left join scm_issue_hdr sih on
		sih.issue_no = vpsrti.tran_hdr_id
	WHERE
		tran_status IN (3)
		and tran_date >= '".$pers['from_date']."'
		and tran_date <= '".$pers['to_date']."'
		and vpsrti.company_id = ".$pers['company']."
	 	and item= ".$itemid." 
		and tran_type not in ('PO') ) g
order by
	tran_date,
	trn asc
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