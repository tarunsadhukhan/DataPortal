<?php
class Mukham_model extends CI_Model
{

	var $table = 'mukham_moisture_analysis mma';	
	var $column_order = array(null, 'Supp_Code','Supplier_Name','Mukham','Avg_Supplied_Moisture','Avg_Mukam_Moisture','Deviation'); //set column field database for datatable orderable
	var $column_search = array( 'Supp_Code','Supplier_Name','Mukham','Avg_Supplied_Moisture','Avg_Mukam_Moisture','Deviation'); //set column field database for datatable searchable 
	//var $order = array('tran_date' => 'desc'); // default order
	var $myselect = "mma.supp_code AS `Supp_Code`,
    mma.supp_name AS `Supplier_Name`,
    mma.mukam_name AS `Mukham`,
    ROUND(AVG(mma.claims_condition),2) AS `Avg_Supplied_Moisture`,
    mma.moisture AS `Avg_Mukam_Moisture`,
    round(AVG(mma.claims_condition)-mma.moisture,2) AS `Deviation`";
	
	public function __construct()
	{		
		$this->load->database();		
	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		
		 $this->db->where('company_id',$companyId);
		//$this->db->where('company_id',1);
		$this->db->where("jute_receive_dt >= '".date('Y-m-d',strtotime($from_date))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."'");
		$this->db->group_by(array('supp_code','supp_name','mukam_name','mma.moisture'));
        if($_POST['search']['value']){
			$i = 0;
			foreach ($this->column_search as $item){
				if($i===0){	
					$this->db->like($item, $_POST['search']['value']);
				}else{
					$this->db->or_like($item, $_POST['search']['value']);
				}	
			$i++;
			}
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// $this->varaha->print_arrays($this->db->last_query());
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{	
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		
		$this->db->where('company_id',$companyId);
		//$this->db->where('company_id',1);
		$this->db->where("jute_receive_dt >= '".date('Y-m-d',strtotime($from_date))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."'");
		$this->db->group_by(array('supp_code','supp_name','mukam_name','mma.moisture'));
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$this->db->select($this->myselect);
		$this->db->from($this->table);
		
		$this->db->where('company_id',$pers['company']);
		$this->db->where("jute_receive_dt>= '".date('Y-m-d',strtotime($pers['from_date']))."' and jute_receive_dt<= '".date('Y-m-d',strtotime($pers['to_date']))."'");	
		$this->db->group_by(array('supp_code','supp_name','mukam_name','mma.moisture'));
		$q = $this->db->get();
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