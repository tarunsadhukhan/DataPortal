<?php
class Purchase_indent_model extends CI_Model
{

	var $table = 'tbl_proc_indent as tpi';	
	

	public function __construct()
	{		
		$this->load->database();		
	}

	
	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = "SELECT 
		tpi.indent_id AS 'ID',
		tpi.indent_squence_no AS 'Sequence_No',
		DATE_FORMAT(tpi.indent_date, '%d-%m-%Y') AS 'Date',
		tpi.record_type AS 'Project_Type',
		tpi.title AS 'Title',
		cm.name AS 'Client',
		sitm.indent_type_desc AS 'Category',
		bm.branch_name AS 'Branch_Name',
		sm.status_name AS 'Status_Name',
		tpi.total_value AS 'Value'
FROM
	tbl_proc_indent tpi
LEFT JOIN status_master sm ON
	tpi.indent_status = sm.status_id
LEFT JOIN customer_master cm ON
	tpi.customer = cm.id
LEFT JOIN scm_indent_type_master sitm ON
	tpi.category = sitm.indent_type_id
LEFT JOIN branch_master bm ON
	tpi.branch = bm.branch_id 
WHERE
	tpi.indent_date >= '".date('Y-m-d',strtotime($from_date))."'
	AND tpi.indent_date <= '".date('Y-m-d',strtotime($to_date))."'";




		
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
		// $this->varaha->print_arrays($sql);
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

		

		$sql = "SELECT 
		tpi.indent_id AS 'ID',
		tpi.indent_squence_no AS 'Sequence_No',
		DATE_FORMAT(tpi.indent_date, '%d-%m-%Y') AS 'Date',
		tpi.record_type AS 'Project_Type',
		tpi.title AS 'Title',
		cm.name AS 'Client',
		sitm.indent_type_desc AS 'Category',
		bm.branch_name AS 'Branch_Name',
		sm.status_name AS 'Status_Name',
		tpi.total_value AS 'Value'
FROM
	tbl_proc_indent tpi
LEFT JOIN status_master sm ON
	tpi.indent_status = sm.status_id
LEFT JOIN customer_master cm ON
	tpi.customer = cm.id
LEFT JOIN scm_indent_type_master sitm ON
	tpi.category = sitm.indent_type_id
LEFT JOIN branch_master bm ON
	tpi.branch = bm.branch_id 
WHERE
	tpi.indent_date >= '".date('Y-m-d',strtotime($pers['from_date']))."'
	AND tpi.indent_date <= '".date('Y-m-d',strtotime($pers['to_date']))."'";
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