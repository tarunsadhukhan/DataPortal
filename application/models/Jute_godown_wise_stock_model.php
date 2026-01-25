<?php
class jute_godown_wise_stock_model extends CI_Model
{

	var $table = 'view_jute_receipt_issue_sale a';	
	var $column_order = array(null, 'a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name','Bales','Drums','Weight'); //set column field database for datatable orderable
	var $column_search = array( 'a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name','Bales','Drums','Weight'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name','Bales','Drums','Weight');
	
	public function __construct()
	{		
		$this->load->database();		
	}

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		


		$sql =  "select a.godown as 'Godown_ID',i.item_desc,a.quality_name as 'Quality', 
		ROUND(SUM(a.bales_receipt - a.bales_sold - a.bales_issued),2) AS `Bales`, 
		ROUND(SUM(a.drums_receipt - a.drums_sold - a.drums_issued),2) AS `Drums`,    
		ROUND(SUM(a.accepted_weight - a.weight_sold - a.weight_issued),2) AS `Weight`,    'QNT',a.quality_code as 'Quality_ID',a.godown_name as 'Godown_Name' 
		from view_jute_receipt_issue_sale a    
		left join itemmaster i on i.company_id=a.company_id and i.group_code='999' and i.item_code=a.item_code 
		WHERE  a.company_id = ".$companyId." AND a.godown != '' 
		GROUP BY a.godown,a.godown_name,i.item_desc, a.quality_name,a.quality_code order by a.godown,a.godown_name ,i.item_desc, a.quality_name";
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

		$sql =  "select a.godown as 'Godown_ID',i.item_desc,a.quality_name as 'Quality', 
		ROUND(SUM(a.bales_receipt - a.bales_sold - a.bales_issued),2) AS `Bales`, 
		ROUND(SUM(a.drums_receipt - a.drums_sold - a.drums_issued),2) AS `Drums`,    
		ROUND(SUM(a.accepted_weight - a.weight_sold - a.weight_issued),2) AS `Weight`,    'QNT',a.quality_code as 'Quality_ID',a.godown_name as 'Godown_Name' 
		from view_jute_receipt_issue_sale a    
		left join itemmaster i on i.company_id=a.company_id and i.group_code='999' and i.item_code=a.item_code 
		WHERE  a.company_id = ".$pers['company']." AND a.godown != ''
		GROUP BY a.godown,a.godown_name ,i.item_desc, a.quality_name,a.quality_code order by a.godown,a.godown_name ,i.item_desc, a.quality_name";
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