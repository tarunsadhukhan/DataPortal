<?php
class Jute_claim_deveation_model extends CI_Model
{

	var $table = 'claim_deviation';	
	var $column_order = array(null, 'SUPP_CODE','SUPPLIER_NAME','MR_NO','MR_DATE','JUTE_TYPE','QUALITY','CONDITION','ADVISED_CLAIM_KGS','ACTUAL_CLAIM_KGS','DEVIATION_KGS'); //set column field database for datatable orderable
	var $column_search = array( 'SUPP_CODE','SUPPLIER_NAME','MR_NO','MR_DATE','JUTE_TYPE','QUALITY','CONDITION','ADVISED_CLAIM_KGS','ACTUAL_CLAIM_KGS','DEVIATION_KGS'); //set column field database for datatable searchable 
	var $order = array('jute_receive_dt' => 'desc'); // default order
	
	
	public function __construct()
	{		
		$this->load->database();		
	}	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{

		$sql =  "SELECT 
		supp_code AS `SUPP_CODE`,
		supp_name AS `SUPPLIER_NAME`,
		mr_print_no AS `MR_NO`,
		jute_receive_dt AS `MR_DATE`,
		item_desc AS `JUTE_TYPE`,
		jute_quality AS `QUALITY`,
		claims_condition+claim_dust AS `CONDITION`,
		actual_shortage AS `ADVISED_CLAIM_KGS`,
		shortage_kgs AS `ACTUAL_CLAIM_KGS`,
		actual_shortage-shortage_kgs AS `DEVIATION_KGS`
		FROM claim_deviation
		where company_id=".$companyId."  AND jute_receive_dt >= '".date('Y-m-d',strtotime($from_date))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."'  ORDER BY jute_receive_dt";
		
		
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

		$sql =  "SELECT 
		supp_code AS `SUPP_CODE`,
		supp_name AS `SUPPLIER_NAME`,
		mr_print_no AS `MR_NO`,
		jute_receive_dt AS `MR_DATE`,
		item_desc AS `JUTE_TYPE`,
		jute_quality AS `QUALITY`,
		claims_condition+claim_dust AS `CONDITION`,
		actual_shortage AS `ADVISED_CLAIM_KGS`,
		shortage_kgs AS `ACTUAL_CLAIM_KGS`,
		actual_shortage-shortage_kgs AS `DEVIATION_KGS`
		FROM claim_deviation
		where company_id=".$pers['company']."  AND jute_receive_dt >= '".date('Y-m-d',strtotime($pers['from_date']))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($pers['to_date']))."' ORDER BY jute_receive_dt";
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