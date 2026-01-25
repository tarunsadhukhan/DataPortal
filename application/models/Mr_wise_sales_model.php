<?php
class Mr_wise_sales_model extends CI_Model
{

	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'Year_Month','Bales','Issue_Bales','Sold_Bales','Drums','Drums_Issued','Drums_Sold','Receipt_Wt_QNT','Issued_Wt_QNT','Sold_Wt_QNT'); //set column field database for datatable orderable
	var $column_search = array( 'Year_Month','Bales','Issue_Bales','Sold_Bales','Drums','Drums_Issued','Drums_Sold','Receipt_Wt_QNT','Issued_Wt_QNT','Sold_Wt_QNT'); //set column field database for datatable searchable 
	//var $order = array('tran_date' => 'desc'); // default order
	
	
	public function __construct()
	{		
		$this->load->database();		
	}
	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{

		$sql =  "SELECT     
		extract(year_month from tran_date) as 'Year_Month',    
		round(sum(bales_receipt),2) as 'Bales',   
		round(sum(bales_issued),2) as 'Issue_Bales',  
		round(sum(bales_sold),2) as 'Sold_Bales',   
		round(sum(drums_receipt),2) as 'Drums',   
		round(sum(drums_issued),2) as 'Drums_Issued',   
		round(sum(drums_sold),2) as 'Drums_Sold',   
		round(sum(accepted_weight),2) as 'Receipt_Wt_QNT',   
		round(sum(weight_issued),2) as 'Issued_Wt_QNT',   
		round(sum(weight_sold),2) as 'Sold_Wt_QNT' 
		FROM  view_jute_receipt_issue_sale
		where company_id=".$companyId."  AND tran_date >= '".date('Y-m-d',strtotime($from_date))."' and tran_date<= '".date('Y-m-d',strtotime($to_date))."' and tran_status not in (4,6)  
		GROUP BY extract(year_month from tran_date)";
		
		
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

		$sql =  "SELECT     
		extract(year_month from tran_date) as 'Year_Month',     
		round(sum(bales_receipt),2) as 'Bales',   
		round(sum(bales_issued),2) as 'Issue_Bales',  
		round(sum(bales_sold),2) as 'Sold_Bales',   
		round(sum(drums_receipt),2) as 'Drums',   
		round(sum(drums_issued),2) as 'Drums_Issued',   
		round(sum(drums_sold),2) as 'Drums_Sold',   
		round(sum(accepted_weight),2) as 'Receipt_Wt_QNT',   
		round(sum(weight_issued),2) as 'Issued_Wt_QNT',   
		round(sum(weight_sold),2) as 'Sold_Wt_QNT' 
		FROM  view_jute_receipt_issue_sale 
		where company_id=".$pers['company']."  AND tran_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and tran_date<= '".date('Y-m-d',strtotime($pers['to_date']))."'  and tran_status not in (4,6) 
		GROUP BY extract(year_month from tran_date)";
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