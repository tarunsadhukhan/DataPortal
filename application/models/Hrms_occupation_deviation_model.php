<?php
class Hrms_occupation_deviation_model extends CI_Model
{

	


	var $table = 'daily_attendance da ';	
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
		$Source = $_POST['Source'];
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_dept = $_POST['att_dept'];
		$att_desig = $_POST['att_desig'];
		$att_spells = $_POST['att_spells'];
		$eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		if($_POST['att_spells']){
			$sql_spells = " and da.spell='".$_POST['att_spells']."' ";
		}else{
			$sql_spells = "";
		}

		if($_POST['att_dept']){
			$sql_depts = " AND da.worked_department_id IN(".$_POST['att_dept'].") ";
		}else{
			$sql_depts = "";
		}

		if($_POST['att_desig']){
			$sql_desig = " AND da.worked_designation_id IN(".$_POST['att_desig'].") ";
		}else{
			$sql_desig = "";
		}

		$sql = "select * from (
			select
				da.eb_id ,
				da.eb_no ,
				concat(
				wm.worker_name,
				wm.middle_name ,
				wm.last_name ) as emp_name,
				da.attendance_date ,
				da.attendance_source ,
				da.attendance_type ,
				da.created_by ,
				da.worked_department_id as actual_dept_id,
				dm.dept_desc as actual_dept,
				wm.dept_id as advised_dept_id,
				(select dm2.dept_desc  from department_master dm2 where dm2.dept_id=wm.dept_id) as advised_dept,
				da.worked_designation_id as actual_occu_id,
				d.desig as actual_occu,
				wm.desg_id as advised_occu_id,
				(
				select
					d2.desig
				from
					designation d2
				where
					d2.id = wm.desg_id) as advised_occu,
				da.worked_designation_id,
				da.spell_hours ,
				da.working_hours ,
				da.idle_hours ,
				da.working_hours-da.idle_hours as Work_Hours
			from
				daily_attendance da
			left join designation d on
				d.id = da.worked_designation_id
			left join department_master dm on
				dm.dept_id = da.worked_department_id
			left join worker_master wm on
				wm.eb_id = da.eb_id
			where
				da.attendance_date BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
				and da.is_active = 1
				and da.status_id = 3 
				".$sql_spells."
				".$sql_depts."
				".$sql_desig."
				and da.company_id = ".$companyId.") d
				where d.advised_dept_id !=d.actual_dept_id or d.advised_occu_id != d.actual_occu_id";
		
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
		// $resdata=array(
		// 	'result' => $query->result(),
		// 	'num_rows' => $query->num_rows(),
		// );
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

		if($pers['att_spells']){
			$sql_spells = " and da.spell='".$pers['att_spells']."' ";
		}else{
			$sql_spells = "";
		}

		if($pers['att_dept']){
			$sql_depts = " AND da.worked_department_id IN(".$pers['att_dept'].") ";
		}else{
			$sql_depts = "";
		}

		if($pers['att_desig']){
			$sql_desig = " AND da.worked_designation_id IN(".$pers['att_desig'].") ";
		}else{
			$sql_desig = "";
		}

		$sql = "select * from (
			select
				da.eb_id ,
				da.eb_no ,
				concat(
				wm.worker_name,
				wm.middle_name ,
				wm.last_name ) as emp_name,
				da.attendance_date ,
				da.attendance_source ,
				da.attendance_type ,
				da.created_by ,
				da.worked_department_id as actual_dept_id,
				dm.dept_desc as actual_dept,
				wm.dept_id as advised_dept_id,
				(select dm2.dept_desc  from department_master dm2 where dm2.dept_id=wm.dept_id) as advised_dept,
				da.worked_designation_id as actual_occu_id,
				d.desig as actual_occu,
				wm.desg_id as advised_occu_id,
				(
				select
					d2.desig
				from
					designation d2
				where
					d2.id = wm.desg_id) as advised_occu,
				da.worked_designation_id,
				da.spell_hours ,
				da.working_hours ,
				da.idle_hours ,
				da.working_hours-da.idle_hours as Work_Hours
			from
				daily_attendance da
			left join designation d on
				d.id = da.worked_designation_id
			left join department_master dm on
				dm.dept_id = da.worked_department_id
			left join worker_master wm on
				wm.eb_id = da.eb_id
			where
				da.attendance_date BETWEEN '".date('Y-m-d',strtotime($pers['from_date']))."' AND '".date('Y-m-d',strtotime($pers['to_date']))."'
				and da.is_active = 1
				and da.status_id = 3 
				".$sql_spells."
				".$sql_depts."
				".$sql_desig."
				and da.company_id = ".$pers['company'].") d
				where d.advised_dept_id !=d.actual_dept_id or d.advised_occu_id != d.actual_occu_id";
		
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
	
