<?php
class Jute_percent_claims_model extends CI_Model
{

	var $table = 'vJute_supp_claim_analysis a ';	
	var $column_order = array(null, 'Supp_Code','Supplier_Name','Total_MR','Total_Pass','Total_Claim',
                              'Pass_percent','Claim_percent'); //set column field database for datatable orderable
	var $column_search = array( 'Supp_Code','Supplier_Name','Total_MR','Total_Pass','Total_Claim','Pass_percent','Claim_percent'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name');
	
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{

		

		// $sql = $this->varaha_model->getMenuBasedQuery($submenuId, date('Y-m-d',strtotime($from_date)), date('Y-m-d',strtotime($to_date)), $companyId);

		// if(!$sql){
			$sql =  "SELECT supp_code AS 'Supp_Code',supp_name AS 'Supplier_Name',
			SUM(mr_count) AS `Total_MR`,
			SUM(pass_count) AS `Total_Pass`,SUM(claim_count) AS `Total_Claim`,
			ROUND(AVG(pass_per),2) AS 'Pass_percent',
			ROUND(AVG(claim_per),2) AS 'Claim_percent'
			FROM vJute_supp_claim_analysis a 
			where company_id=".$companyId."  AND jute_receive_dt >= '".date('Y-m-d',strtotime($from_date))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."' 
			GROUP BY supp_code,supp_name";
		// }
		
		
		
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

		$sql =  "SELECT supp_code AS 'Supp_Code',supp_name AS 'Supplier_Name',
		SUM(mr_count) AS `Total_MR`,
		SUM(pass_count) AS `Total_Pass`,SUM(claim_count) AS `Total_Claim`,
		ROUND(AVG(pass_per),2) AS 'Pass_percent',
		ROUND(AVG(claim_per),2) AS 'Claim_percent'
		FROM vJute_supp_claim_analysis a 
		where company_id=".$pers['company']."  AND jute_receive_dt >= '".date('Y-m-d',strtotime($pers['from_date']))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($pers['to_date']))."' 
		GROUP BY supp_code,supp_name";
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