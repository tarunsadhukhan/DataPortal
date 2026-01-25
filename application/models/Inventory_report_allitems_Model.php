<?php
class Inventory_report_allitems_Model extends CI_Model
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
		$eb_no = $_POST['eb_no'];
	
		$this->load->library('session');
				$itcode = $_POST['itcod'];
				$Source = $_POST['Source'];
		
			$sql="select
			stk.*,
			ifnull(rp.lrecpdate,'') lrecpdate,
			ifnull(iss.lissuedate,'') lissuedate,
			case when clos_qty=0 then 0 
			when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
			when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end nodays 
		from
			(
			select
				itemcode,
				item_name,uom_code,
				round(sum(open_qty),3) open_qty,
				round(SUM(open_val),2) open_val,
				round(SUM(tranrecv_qty),3) tranrecv_qty,
				round(sum(tranrecv_val),2) tranrecv_val,
				round(SUM(tranissu_qty),3) tranissu_qty,
				round(SUM(tranissu_val),2) tranissu_val,
				round(SUM(open_qty + tranrecv_qty-tranissu_qty),3) clos_qty,
				round(sum(open_val + tranrecv_val-tranissu_val),2) clos_val,".$companyId."  
				company from
				(
				SELECT
					company_id,
					branch_id,
					group_code,
					CONCAT(group_code, item_code) itemcode,
					item_name,uom_code,
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
					'2022-04-01' tran_date
				FROM
					view_proc_store_receipt_transfer_issue
				WHERE
					tran_status IN (3)
					and tran_date<'".$from_date."'
					and company_id = ".$companyId."
				 group by
					company_id,
					branch_id,
					group_code,
					CONCAT(group_code, item_code),
					item_name,uom_code
			union all
				SELECT
					company_id,
					branch_id,
					group_code,
					CONCAT(group_code, item_code) itemcode,
					item_name,uom_code,
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
					tran_date
				FROM
					view_proc_store_receipt_transfer_issue
				WHERE
					tran_status IN (3)
					and tran_date >= '$from_date'
					and tran_date <= '$to_date'
					and company_id = ".$companyId."
					 ) g
			group by
				itemcode,
				item_name,uom_code
			having
				(abs(open_qty)+ abs(tranrecv_qty)+ abs(tranissu_qty)+ abs(open_val)+ abs(tranrecv_val)+ abs(tranissu_val))>0 ) stk
		left join (
			select
				concat(group_code, item_code) itemcode,
				max(tran_date) rpdate,
				date_format(max(tran_date), '%d-%m-%Y') lrecpdate
			from
				view_proc_store_receipt_transfer_issue
			where
				tran_status IN (3)
					and company_id = ".$companyId."
					and tran_type = 'SR'
					and tran_date <= '$to_date'
				group by
					group_code,
					item_code ) rp on
			stk.itemcode = rp.itemcode
		left JOIN (
			select
				concat(group_code, item_code) itemcode,
				max(tran_date) isdate,
				date_format(max(tran_date), '%d-%m-%Y') lissuedate
			from
				view_proc_store_receipt_transfer_issue
			where
				tran_status IN (3)
					and company_id = ".$companyId."
					and tran_type = 'I'
					and tran_date <= '$to_date'
				group by
					group_code,
					item_code ) iss on
			stk.itemcode = iss.itemcode
			where company=".$companyId;
			if($itcode){
				$sql .= " and stk.itemcode= '".$itcode."'";
			}
			if($Source==1){

				$sql .= " and (clos_qty || clos_val)<0";
	
				
//				stk.itemcode= '".$itcode."'";
			}
			if($Source==2){

				$sql .= " and 		case when clos_qty=0 then 0 
				when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
				when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end 
			 between 180 and 365";
	
				
//				stk.itemcode= '".$itcode."'";
			}
			if($Source==3){

				$sql .= " and 		case when clos_qty=0 then 0 
				when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
				when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end  
		 > 365";
	
				
//				stk.itemcode= '".$itcode."'";
			}
			
			$sql .= " order by
			stk.itemcode";
	

 				

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

		$itcode = $pers['itcod'];
		$from_date=$pers['from_date'];
		$to_date=$pers['to_date'];
 		$Source = $pers['Source'];

		$sql="select
		stk.*,
		ifnull(rp.lrecpdate,'') lrecpdate,
		ifnull(iss.lissuedate,'') lissuedate,
		case when clos_qty=0 then 0 
		when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
		when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end nodays 
from
		(
		select
			itemcode,
			item_name,uom_code,
			round(sum(open_qty),3) open_qty,
			round(SUM(open_val),2) open_val,
			round(SUM(tranrecv_qty),3) tranrecv_qty,
			round(sum(tranrecv_val),2) tranrecv_val,
			round(SUM(tranissu_qty),3) tranissu_qty,
			round(SUM(tranissu_val),2) tranissu_val,
			round(SUM(open_qty + tranrecv_qty-tranissu_qty),3) clos_qty,
			round(sum(open_val + tranrecv_val-tranissu_val),2) clos_val,".$pers['company']."  
			company from
			(
			SELECT
				company_id,
				branch_id,
				group_code,
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
				'2022-04-01' tran_date
			FROM
				view_proc_store_receipt_transfer_issue
			WHERE
				tran_status IN (3)
				and tran_date<'".$pers['from_date']."'
				and company_id = ".$pers['company']."
			 group by
				company_id,
				branch_id,
				group_code,
				CONCAT(group_code, item_code),
				item_name,uom_code
		union all
			SELECT
				company_id,
				branch_id,
				group_code,
				CONCAT(group_code, item_code) itemcode,
				item_name,uom_code,
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
				tran_date
			FROM
				view_proc_store_receipt_transfer_issue
			WHERE
				tran_status IN (3)
				and tran_date >= '".$pers['from_date']."'
				and tran_date <= '".$pers['to_date']."'
				and company_id = ".$pers['company']."
				 ) g
		group by
			itemcode,
			item_name,uom_code
		having
			(abs(open_qty)+ abs(tranrecv_qty)+ abs(tranissu_qty)+ abs(open_val)+ abs(tranrecv_val)+ abs(tranissu_val))>0 ) stk
	left join (
		select
			concat(group_code, item_code) itemcode,
			max(tran_date) rpdate,
			date_format(max(tran_date), '%d-%m-%Y') lrecpdate
		from
			view_proc_store_receipt_transfer_issue
		where
			tran_status IN (3)
				and company_id = ".$pers['company']."
				and tran_type = 'SR'
				and tran_date <= '".$pers['to_date']."'
			group by
				group_code,
				item_code ) rp on
		stk.itemcode = rp.itemcode
	left JOIN (
		select
			concat(group_code, item_code) itemcode,
			max(tran_date) isdate,
			date_format(max(tran_date), '%d-%m-%Y') lissuedate
		from
			view_proc_store_receipt_transfer_issue
		where
			tran_status IN (3)
				and company_id = ".$pers['company']."
				and tran_type = 'I'
				and tran_date <= '".$pers['to_date']."'
			group by
				group_code,
				item_code ) iss on
		stk.itemcode = iss.itemcode
		where company=".$pers['company'];

		if($itcode){
			$sql .= " and stk.itemcode= '".$itcode."'";
		}
		if($Source==1){

			$sql .= " and (clos_qty || clos_val)<0";

			
//				stk.itemcode= '".$itcode."'";
		}
		if($Source==2){

			$sql .= " and 		case when clos_qty=0 then 0 
			when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
			when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end 
		 between 180 and 365";

			
//				stk.itemcode= '".$itcode."'";
		}
		if($Source==3){

			$sql .= " and 		case when clos_qty=0 then 0 
			when (clos_qty>0 and iss.isdate is null)  then datediff('".$to_date."', rpdate)
			when (clos_qty>0 and iss.isdate is not null) then datediff('".$to_date."', iss.isdate) end  
	 > 365";

			
//				stk.itemcode= '".$itcode."'";
		}
		
		$sql .= " order by
		stk.itemcode";



		//echo $sql;
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