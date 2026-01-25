<?php
class Jute_with_value_model extends CI_Model
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
		
		


		$sql =  "SELECT `values`.`Quality ID` as `Quality_ID`, `values`.`Quality Name`  as `Quality_Name`,
		SUM(`values`.`Receipt Bales`) as `Receipt_Bales`,
		SUM(`values`.`Issue Bales`) as `Issue_Bales` ,
		SUM(`values`.`Sold Bales`) as `Sold_Bales`,
		SUM(`values`.`Drums`) as `Drums` ,
		SUM(`values`.`Drums Issued`) as `Drums_Issued`,
		SUM(`values`.`Drums Sold`) as `Drums_Sold`,
		SUM(`values`.`Receipt Wt.(QNT)`) as `Receipt_Wt` ,
		SUM(`values`.`Issued Wt.(QNT)`) as `Issued_Wt`,
		SUM(`values`.`Sold Wt.(QNT)`) as `Sold_Wt`,
		SUM(`values`.`Avg. Issue Rate`) as `Avg_Issue_Rate`,
		SUM(`values`.`Issued Val (In Lakhs)`) as `Issued_Val`,
		SUM(`values`.`Opening Bales`) as `Opening_Bales`,
		SUM(`values`.`Opening Drums`) as `Opening_Drums`,
		SUM(`values`.`Opening Wt.(QNT)`) as `Opening_Wt`
		from (SELECT quality_code AS 'Quality ID',
		quality_name AS 'Quality Name',
		ROUND(SUM(bales_receipt), 2) AS 'Receipt Bales',
		ROUND(SUM(bales_issued), 2) AS 'Issue Bales',
		ROUND(SUM(bales_sold), 2) AS 'Sold Bales',
		ROUND(SUM(drums_receipt),2) AS 'Drums',
		ROUND(SUM(drums_issued),2) AS 'Drums Issued',
		ROUND(SUM(drums_sold), 2) AS 'Drums Sold',
		ROUND(SUM(accepted_weight),2) AS 'Receipt Wt.(QNT)',
		ROUND(SUM(weight_issued), 2) AS 'Issued Wt.(QNT)',
		ROUND(SUM(weight_sold), 2) AS 'Sold Wt.(QNT)',
		IFNULL(ROUND(SUM(weight_issued * rate) / SUM(weight_issued),2),0) AS 'Avg. Issue Rate',
		ROUND(SUM(weight_issued * rate) / 100000,2) AS 'Issued Val (In Lakhs)',0 AS 'Opening Bales',0 AS 'Opening Drums',0 AS 'Opening Wt.(QNT)'
		FROM view_jute_receipt_issue_sale
		WHERE company_id = ".$companyId." AND tran_date >= '".date('Y-m-d',strtotime($from_date))."' and tran_date<= '".date('Y-m-d',strtotime($to_date))."'  
		AND tran_status NOT IN (4 ,6) GROUP BY `quality_code` , `quality_name`  UNION  SELECT  quality_code AS 'Quality ID', quality_name AS 'Quality Name', 0 AS 'Receipt Bales', 0 AS 'Issue Bales', 0 AS 'Sold Bales', 0 AS 'Drums', 0 AS 'Drums Issued', 0 AS 'Drums Sold', 0 AS 'Receipt Wt.(QNT)', 0 AS 'Issued Wt.(QNT)', 0 AS 'Sold Wt.(QNT)', 0 AS 'Avg. Issue Rate', 0 AS 'Issued Val (In Lakhs)', ROUND(SUM(bales_receipt) -  SUM(bales_issued) - SUM(bales_sold), 2) AS 'Opening Bales', ROUND(SUM(drums_receipt)- SUM(drums_issued) - SUM(drums_sold), 2) AS 'Opening Drums', ROUND(SUM(accepted_weight) - SUM(weight_issued) - SUM(weight_sold), 2) AS 'Opening Wt.(QNT)'     
		FROM view_jute_receipt_issue_sale     
		WHERE  company_id = ".$companyId."  and tran_date < '".date('Y-m-d', strtotime($from_date))."'   and tran_status not in (4,6)      
		GROUP BY `quality_code` ,`quality_name`  ) `values` GROUP BY `values`.`Quality ID`,`values`.`Quality Name` ORDER BY `values`.`Quality ID`";
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

		$sql =  "SELECT `values`.`Quality ID` as `Quality_ID`, `values`.`Quality Name`  as `Quality_Name`,
		SUM(`values`.`Receipt Bales`) as `Receipt_Bales`,
		SUM(`values`.`Issue Bales`) as `Issue_Bales` ,
		SUM(`values`.`Sold Bales`) as `Sold_Bales`,
		SUM(`values`.`Drums`) as `Drums` ,
		SUM(`values`.`Drums Issued`) as `Drums_Issued`,
		SUM(`values`.`Drums Sold`) as `Drums_Sold`,
		SUM(`values`.`Receipt Wt.(QNT)`) as `Receipt_Wt` ,
		SUM(`values`.`Issued Wt.(QNT)`) as `Issued_Wt`,
		SUM(`values`.`Sold Wt.(QNT)`) as `Sold_Wt`,
		SUM(`values`.`Avg. Issue Rate`) as `Avg_Issue_Rate`,
		SUM(`values`.`Issued Val (In Lakhs)`) as `Issued_Val`,
		SUM(`values`.`Opening Bales`) as `Opening_Bales`,
		SUM(`values`.`Opening Drums`) as `Opening_Drums`,
		SUM(`values`.`Opening Wt.(QNT)`) as `Opening_Wt`
		from (SELECT quality_code AS 'Quality ID',
		quality_name AS 'Quality Name',
		ROUND(SUM(bales_receipt), 2) AS 'Receipt Bales',
		ROUND(SUM(bales_issued), 2) AS 'Issue Bales',
		ROUND(SUM(bales_sold), 2) AS 'Sold Bales',
		ROUND(SUM(drums_receipt),2) AS 'Drums',
		ROUND(SUM(drums_issued),2) AS 'Drums Issued',
		ROUND(SUM(drums_sold), 2) AS 'Drums Sold',
		ROUND(SUM(accepted_weight),2) AS 'Receipt Wt.(QNT)',
		ROUND(SUM(weight_issued), 2) AS 'Issued Wt.(QNT)',
		ROUND(SUM(weight_sold), 2) AS 'Sold Wt.(QNT)',
		IFNULL(ROUND(SUM(weight_issued * rate) / SUM(weight_issued),2),0) AS 'Avg. Issue Rate',
		ROUND(SUM(weight_issued * rate) / 100000,2) AS 'Issued Val (In Lakhs)',0 AS 'Opening Bales',0 AS 'Opening Drums',0 AS 'Opening Wt.(QNT)'
		FROM view_jute_receipt_issue_sale
		WHERE company_id = ".$pers['company']." AND tran_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and tran_date<= '".date('Y-m-d',strtotime($pers['to_date']))."'  
		AND tran_status NOT IN (4 ,6) GROUP BY `quality_code` , `quality_name`  UNION  SELECT  quality_code AS 'Quality ID', quality_name AS 'Quality Name', 0 AS 'Receipt Bales', 0 AS 'Issue Bales', 0 AS 'Sold Bales', 0 AS 'Drums', 0 AS 'Drums Issued', 0 AS 'Drums Sold', 0 AS 'Receipt Wt.(QNT)', 0 AS 'Issued Wt.(QNT)', 0 AS 'Sold Wt.(QNT)', 0 AS 'Avg. Issue Rate', 0 AS 'Issued Val (In Lakhs)', ROUND(SUM(bales_receipt) -  SUM(bales_issued) - SUM(bales_sold), 2) AS 'Opening Bales', ROUND(SUM(drums_receipt)- SUM(drums_issued) - SUM(drums_sold), 2) AS 'Opening Drums', ROUND(SUM(accepted_weight) - SUM(weight_issued) - SUM(weight_sold), 2) AS 'Opening Wt.(QNT)'     
		FROM view_jute_receipt_issue_sale     
		WHERE  company_id = ".$pers['company']."  and tran_date < '".date('Y-m-d',strtotime($pers['from_date']))."'   and tran_status not in (4,6)      
		GROUP BY `quality_code` ,`quality_name`  ) `values` GROUP BY `values`.`Quality ID`,`values`.`Quality Name` ORDER BY `values`.`Quality ID`";
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