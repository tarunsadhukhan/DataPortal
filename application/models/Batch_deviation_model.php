<?php
class Batch_deviation_model extends CI_Model
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


		$databasecr = $this->varaha->getTenentId();
		$sms_url = $databasecr['serverIp']."/security-api/api/vowreports/vowJutereportsApi";
			 
            $parameters = array(
				'taskCode' => 1024,
				'payloadSize' =>  1,
				'data' =>  array("date"=> "01-01-2021"),
				'cipher' =>  'ed54434568d56806c4360d1e787ac70e',
				'userId' =>  $this->session->userdata('userid'),
				'companyId' => $companyId,
			);

			$headers = [
				"Content-Type: application/json",
				"X-Content-Type-Options:nosniff",
				"Accept:application/json",
				"Cache-Control:no-cache",
				'X-TenantId: '.$databasecr['tenentId'],
				'Authorization: '.$this->session->userdata('Authorization'),				
			];
			
			$chs = curl_init();
			curl_setopt($chs, CURLOPT_POST, true);
			curl_setopt($chs, CURLOPT_URL, $sms_url);
			curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));			
			curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
			$output = curl_exec($chs);
			curl_close($chs);			
            if ($output){
				return json_decode($output);
			}


		// $sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// if($_POST['length'] != -1)
		// $sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		// $query = $this->db->query($sql);
		// return $query->result();
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

		
		$result =$this->get_datatables($pers['mainmenuId'],$pers['submenuId'], $pers['company'], $pers['from_date'],$pers['to_date']);
		$result = $result->data;
		if($result){
			if(count($result) > 0){			
				foreach($result as $row){
					$data[] = $row;
				}
				return $data;
			}
		}
		
		return false;
	}
	
	
}
?>