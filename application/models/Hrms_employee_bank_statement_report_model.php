<?php
class Hrms_employee_bank_statement_report_model extends CI_Model
{

	


	var $table = 'worker_master wm';	
	var $column_order = array(null); //set column field database for datatable orderable
	var $column_search = array(''); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    // var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name');
	
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$branch_id = $_POST['branch_id'];
		$componet_id = $_POST['componet_id'];
		// $this->varaha->print_arrays($Source);
		$condition = "";
		if($branch_id){
			$condition = " and tpp.branch_id= ". $branch_id;
		}
		if($componet_id==0){
			$componet_condition = "";
		}else{
			$componet_condition = 'and COMPONENT_ID='.$componet_id;
		}

		$sql = "select  eb_no `Employee_Code`,
		concat(worker_name,'  ',last_name) `Employee_Name`,
		per_bank_name `Bank_Name`, 
		per_bank_ac_no `Account_No`,
		per_bank_ifsc `IFSC_Code`, 
		AMOUNT `Net_Pay` 
		from 
		tbl_pay_period tpp,tbl_pay_employee_payroll tpep ,worker_master wm 
		where tpp.company_id= ".$companyId.$condition."
		and from_date='".$from_date."' 
		and to_date='".$to_date."' 
		and tpp.status = 3 
		and is_active=1 
		and tpp.id=tpep.PAYPERIOD_ID 
		".$componet_condition."
		and tpep.EMPLOYEEID=wm.eb_id 
		order by eb_no
		";

		
		
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
		// $this->varaha->print_arrays($sql);
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

		
		$condition = "";
		if($pers['branch_id']){
			$condition = " and tpp.branch_id= ". $pers['branch_id'];
		}
		if($pers['componet_id']==0){
			$componet_condition = "";
		}else{
			$componet_condition = ' and COMPONENT_ID='.$pers['componet_id'];
		}
		$sql = "select  eb_no `Employee_Code`,
		concat(worker_name,'  ',last_name) `Employee_Name`,
		per_bank_name `Bank_Name`, 
		per_bank_ac_no `Account_No`,
		per_bank_ifsc `IFSC_Code`, 
		AMOUNT `Net_Pay` 
		from 
		tbl_pay_period tpp,tbl_pay_employee_payroll tpep ,worker_master wm 
		where tpp.company_id= ".$pers['company'].$condition."
		and from_date='".$pers['from_date']."' 
		and to_date='".$pers['to_date']."' 
		and tpp.status = 3
		and is_active=1 
		and tpp.id=tpep.PAYPERIOD_ID 
		".$componet_condition."
		and tpep.EMPLOYEEID=wm.eb_id 
		order by eb_no
		";
		
		$q = $this->db->query($sql);
		// $this->varaha->print_arrays($pers, $this->db->last_query());
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
	
