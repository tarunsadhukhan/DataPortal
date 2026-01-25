<?php
class Reports_test_model extends CI_Model
{
	var $column_order = array(null); //set column field database for datatable orderable
	var $column_search = array(''); //set column field database for datatable searchable 
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql =  "select
		itemcode,item_name,sum(open_qty) open_qty,SUM(open_val) open_val,SUM(tranrecv_qty) tranrecv_qty,
		sum(tranrecv_val) tranrecv_val,SUM(tranissu_qty) tranissu_qty,SUM(tranissu_val) tranissu_val,
		SUM(open_qty+tranrecv_qty-tranissu_qty) clos_qty,sum(open_val+tranrecv_val-tranissu_val) clos_val 
	from
		(
		SELECT
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code) itemcode,
			item_name,
			'O' tran_type,
			date_format('2020-04-01', '%d-%m-%Y') tran_date1 ,
			' ' sr_print_no ,
			'Opening' status_name,
			round(sum(received_qty-issued_qty), 3) open_qty,
			round(sum(received_val-issued_val), 2) open_val,
			0 tranrecv_qty,
			0 tranrecv_val,
			0 tranissu_qty,
			0 tranissu_val, '2020-04-01' tran_date
		FROM
			view_proc_store_receipt_transfer_issue
		WHERE
			tran_status  IN (3)
			and tran_date<'2020-04-01' and company_id =2 and branch_id =29
		group by
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code),
			item_name
	union all
		SELECT
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code) itemcode,
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
			round(issued_val, 2) tranissu_val,tran_date
		FROM
			view_proc_store_receipt_transfer_issue
		WHERE
			tran_status  IN (3)
			 and tran_date>='".$from_date."' and tran_date<='".$to_date."' and company_id =2 and branch_id =29
	) g  group by itemcode,item_name having abs(open_qty)+abs(tranrecv_qty)+abs(tranissu_qty)+abs(open_val)+abs(tranrecv_val)+abs(tranissu_val)>0
			 order by itemcode";
		
				
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

		$sql =  "select
		itemcode,item_name,sum(open_qty) open_qty,SUM(open_val) open_val,SUM(tranrecv_qty) tranrecv_qty,
		sum(tranrecv_val) tranrecv_val,SUM(tranissu_qty) tranissu_qty,SUM(tranissu_val) tranissu_val,
		SUM(open_qty+tranrecv_qty-tranissu_qty) clos_qty,sum(open_val+tranrecv_val-tranissu_val) clos_val 
	from
		(
		SELECT
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code) itemcode,
			item_name,
			'O' tran_type,
			date_format('2020-04-01', '%d-%m-%Y') tran_date1 ,
			' ' sr_print_no ,
			'Opening' status_name,
			round(sum(received_qty-issued_qty), 3) open_qty,
			round(sum(received_val-issued_val), 2) open_val,
			0 tranrecv_qty,
			0 tranrecv_val,
			0 tranissu_qty,
			0 tranissu_val, '2020-04-01' tran_date
		FROM
			view_proc_store_receipt_transfer_issue
		WHERE
			tran_status  IN (3)
			and tran_date<'2020-04-01' and company_id =2 and branch_id =29
		group by
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code),
			item_name
	union all
		SELECT
			company_id,
			branch_id,
			group_code,
			CONCAT(group_code, item_code) itemcode,
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
			round(issued_val, 2) tranissu_val,tran_date
		FROM
			view_proc_store_receipt_transfer_issue
		WHERE
			tran_status  IN (3)
			 and tran_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and tran_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and company_id =2 and branch_id =29
	) g  group by itemcode,item_name having abs(open_qty)+abs(tranrecv_qty)+abs(tranissu_qty)+abs(open_val)+abs(tranrecv_val)+abs(tranissu_val)>0
			 order by itemcode";
			 
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