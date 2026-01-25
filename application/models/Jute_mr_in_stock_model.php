<?php
class Jute_mr_in_stock_model extends CI_Model
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

	// 	$sql =  "SELECT a.tran_date AS 'MR_Date', a.jute_receive_no AS 'MR_No',a.quality_code as 'Quality_ID',a.quality_name as 'Quality', a.godown as 'Godown_ID', a.godown_name as 'Godown_Name',    a.status_name as 'Status',
	// 	b.MRLineNo as 'MR_Line_No',b.Bales, b.`Issue Bales` as 'Issue_Bales',
	// 	b.`Sold Bales`as 'Sold_Bales',b.`Bales Stock`as'Bales_Stock',b.Drums,b.`Drums Issued`as 'Drums_Issued',
	// 	b.`Drums Sold`as'Drums_Sold',b.`Drums Stock`as 'Drums_Stock',b.`Receipt Wt.(QNT)`as 'Receipt_Wt',
	// 	b.`Issued Wt.(QNT)`as 'Issued_Wt',b.`Sold Wt.(QNT)`as'Sold_Wt',b.`Stock (QNT)`as'Stock_Qnt'
	// 	FROM view_jute_receipt_issue_sale a     
	//    left join (   select mr_line_id as 'MRLineNo',   
	//    round(sum(bales_receipt),2) as 'Bales',   
	//    round(sum(bales_issued),2) as 'Issue Bales',   
	//    round(sum(bales_sold),2) as 'Sold Bales',   
	//    round(sum(bales_receipt)-sum(bales_issued)-sum(bales_sold),2) as 'Bales Stock',   
	//    round(sum(drums_receipt),2) as 'Drums',   
	//    round(sum(drums_issued),2) as 'Drums Issued',   
	//    round(sum(drums_sold),2) as 'Drums Sold',  
	//    round(sum(drums_receipt)-sum(drums_issued)-sum(drums_sold),2) as 'Drums Stock',   
	//    round(sum(accepted_weight),2) as 'Receipt Wt.(QNT)',   
	//    round(sum(weight_issued),2) as 'Issued Wt.(QNT)',   
	//    round(sum(weight_sold),2) as 'Sold Wt.(QNT)',   
	//    round(sum(accepted_weight)-sum(weight_issued)-sum(weight_sold),2) as 'Stock (QNT)'   
	//    from view_jute_receipt_issue_sale group by mr_line_id   ) b on b.MRLineNo=a.mr_line_id    
	//    where a.transaction_type='R' 
	//    and a.company_id='".$companyId."'
	//    AND a.tran_date >= '".date('Y-m-d',strtotime($from_date))."' 
	//    AND a.tran_date <= '".date('Y-m-d',strtotime($to_date))."' order by a.tran_date desc";


	$sql = "SELECT
	a.tran_date AS 'MR_Date',
	a.jute_receive_no AS 'MR_No',
	a.quality_code as 'Quality_ID',
	a.quality_name as 'Quality',
	a.godown as 'Godown_ID',
	a.godown_name as 'Godown_Name',
	a.status_name as 'Status',
   b.MRLineNo as 'MR_Line_No',b.Bales, b.`Issue Bales` as 'Issue_Bales',
   b.`Sold Bales`as 'Sold_Bales',b.`Bales Stock`as'Bales_Stock',b.Drums,b.`Drums Issued`as 'Drums_Issued',
   b.`Drums Sold`as'Drums_Sold',b.`Drums Stock`as 'Drums_Stock',b.`Receipt Wt.(QNT)`as 'Receipt_Wt',
   b.`Issued Wt.(QNT)`as 'Issued_Wt',b.`Sold Wt.(QNT)`as'Sold_Wt',b.`Stock (QNT)`as'Stock_Qnt'
FROM
 view_jute_receipt_issue_sale a
left join
	 (select
	 mr_line_id as 'MRLineNo',
	 round(sum(bales_receipt),
			 2) as 'Bales',
	 round(sum(bales_issued),
			 2) as 'Issue Bales',
	 round(sum(bales_sold),
			 2) as 'Sold Bales',
	 round(sum(bales_receipt)-sum(bales_issued)-sum(bales_sold),
			 2) as 'Bales Stock',
	 round(sum(drums_receipt),
			 2) as 'Drums',
	 round(sum(drums_issued),
			 2) as 'Drums Issued',
	 round(sum(drums_sold),
			 2) as 'Drums Sold',
	 round(sum(drums_receipt)-sum(drums_issued)-sum(drums_sold),
			 2) as 'Drums Stock',
	 round(sum(accepted_weight),
			 2) as 'Receipt Wt.(QNT)',
	 round(sum(weight_issued),
			 2) as 'Issued Wt.(QNT)',
	 round(sum(weight_sold),
			 2) as 'Sold Wt.(QNT)',
	 round(sum(accepted_weight)-sum(weight_issued)-sum(weight_sold),
			 2) as 'Stock (QNT)'
 from
	 view_jute_receipt_issue_sale
 where
	 tran_date <='".date('Y-m-d',strtotime($from_date))."' 
 group by
	 mr_line_id
	 ) b
		 on
 b.MRLineNo = a.mr_line_id
where
 a.transaction_type = 'R'
 and a.tran_status not in  (4,6)
 and a.company_id='".$companyId."'
 and (round(b.`Bales Stock`, 0) !=0
	 or round(b.`Drums Stock`, 0) !=0)
 and a.tran_date <='".date("Y-m-d",time())."' order by a.tran_date desc";
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
		// $this->varaha->print_arrays($this->db->last_query());
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

	// 	$sql =  "SELECT a.tran_date AS 'MR_Date', a.jute_receive_no AS 'MR_No',a.quality_code as 'Quality_ID',a.quality_name as 'Quality', a.godown as 'Godown_ID', a.godown_name as 'Godown_Name',    a.status_name as 'Status',
	// 	b.MRLineNo as 'MR_Line_No',b.Bales, b.`Issue Bales` as 'Issue_Bales',
	// 	b.`Sold Bales`as 'Sold_Bales',b.`Bales Stock`as'Bales_Stock',b.Drums,b.`Drums Issued`as 'Drums_Issued',
	// 	b.`Drums Sold`as'Drums_Sold',b.`Drums Stock`as 'Drums_Stock',b.`Receipt Wt.(QNT)`as 'Receipt_Wt',
	// 	b.`Issued Wt.(QNT)`as 'Issued_Wt',b.`Sold Wt.(QNT)`as'Sold_Wt',b.`Stock (QNT)`as'Stock_Qnt'
	// 	FROM view_jute_receipt_issue_sale a     
	//    left join (   select mr_line_id as 'MRLineNo',   
	//    round(sum(bales_receipt),2) as 'Bales',   
	//    round(sum(bales_issued),2) as 'Issue Bales',   
	//    round(sum(bales_sold),2) as 'Sold Bales',   
	//    round(sum(bales_receipt)-sum(bales_issued)-sum(bales_sold),2) as 'Bales Stock',   
	//    round(sum(drums_receipt),2) as 'Drums',   
	//    round(sum(drums_issued),2) as 'Drums Issued',   
	//    round(sum(drums_sold),2) as 'Drums Sold',  
	//    round(sum(drums_receipt)-sum(drums_issued)-sum(drums_sold),2) as 'Drums Stock',   
	//    round(sum(accepted_weight),2) as 'Receipt Wt.(QNT)',   
	//    round(sum(weight_issued),2) as 'Issued Wt.(QNT)',   
	//    round(sum(weight_sold),2) as 'Sold Wt.(QNT)',   
	//    round(sum(accepted_weight)-sum(weight_issued)-sum(weight_sold),2) as 'Stock (QNT)'   
	//    from view_jute_receipt_issue_sale group by mr_line_id   ) b on b.MRLineNo=a.mr_line_id    
	//    where a.transaction_type='R'    
	// 	AND  company_id = ".$pers['company']."  AND a.tran_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and a.tran_date<= '".date('Y-m-d',strtotime($pers['to_date']))."'   order by a.tran_date desc";

	$sql = "SELECT
	a.tran_date AS 'MR_Date',
	a.jute_receive_no AS 'MR_No',
	a.quality_code as 'Quality_ID',
	a.quality_name as 'Quality',
	a.godown as 'Godown_ID',
	a.godown_name as 'Godown_Name',
	a.status_name as 'Status',
   b.MRLineNo as 'MR_Line_No',b.Bales, b.`Issue Bales` as 'Issue_Bales',
   b.`Sold Bales`as 'Sold_Bales',b.`Bales Stock`as'Bales_Stock',b.Drums,b.`Drums Issued`as 'Drums_Issued',
   b.`Drums Sold`as'Drums_Sold',b.`Drums Stock`as 'Drums_Stock',b.`Receipt Wt.(QNT)`as 'Receipt_Wt',
   b.`Issued Wt.(QNT)`as 'Issued_Wt',b.`Sold Wt.(QNT)`as'Sold_Wt',b.`Stock (QNT)`as'Stock_Qnt'
   FROM
	view_jute_receipt_issue_sale a
   left join
		(select
		mr_line_id as 'MRLineNo',
		round(sum(bales_receipt),
				2) as 'Bales',
		round(sum(bales_issued),
				2) as 'Issue Bales',
		round(sum(bales_sold),
				2) as 'Sold Bales',
		round(sum(bales_receipt)-sum(bales_issued)-sum(bales_sold),
				2) as 'Bales Stock',
		round(sum(drums_receipt),
				2) as 'Drums',
		round(sum(drums_issued),
				2) as 'Drums Issued',
		round(sum(drums_sold),
				2) as 'Drums Sold',
		round(sum(drums_receipt)-sum(drums_issued)-sum(drums_sold),
				2) as 'Drums Stock',
		round(sum(accepted_weight),
				2) as 'Receipt Wt.(QNT)',
		round(sum(weight_issued),
				2) as 'Issued Wt.(QNT)',
		round(sum(weight_sold),
				2) as 'Sold Wt.(QNT)',
		round(sum(accepted_weight)-sum(weight_issued)-sum(weight_sold),
				2) as 'Stock (QNT)'
	from
		view_jute_receipt_issue_sale
	where
		tran_date <='2022-04-01'
	group by
		mr_line_id
		) b
			on
	b.MRLineNo = a.mr_line_id
   where
	a.transaction_type = 'R'
	and a.tran_status not in  (4,6)
	and a.company_id='".$pers['company']."'
	and (round(b.`Bales Stock`, 0) !=0
		or round(b.`Drums Stock`, 0) !=0)
	and a.tran_date <='".date('Y-m-d',strtotime($pers['from_date']))."' order by a.tran_date desc";
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