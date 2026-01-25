<?php
class Hrms_spell_wise_model extends CI_Model
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

		$sql = "select
					da.worked_department_id ,
					dm.dept_desc ,
					da.worked_designation_id,
					d.desig				
				from
					daily_attendance da left join designation d on d.id =da.worked_designation_id
					left join department_master dm on dm.dept_id =da.worked_department_id
				where
					da.attendance_date BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'
					and da.is_active = 1
					and da.status_id = 3
					".$sql_depts."
					".$sql_desig."
					and da.company_id=".$companyId."
					group by
					da.worked_department_id ,
					da.worked_designation_id    
				order by dm.dept_desc ,
				d.desig";
		
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

	function getHands($deptId,$desigId,$spell, $from_date,$to_date){

		$this->db->where("attendance_date BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."'");
		$this->db->where('worked_department_id',$deptId);
		$this->db->where('worked_designation_id',$desigId);
		$this->db->where('spell',$spell);
		$this->db->select('round(sum(working_hours-idle_hours)/ 8, 2) as value');
		$q = $this->db->get('daily_attendance');
		if($q->num_rows()>0){
			return $q->row()->value;
		}
		return null;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$spells = $this->varaha_model->getAllSpells($companyId);
		// $this->varaha->print_arrays($spells);
		$data=array();
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
		if($query->num_rows()>0){
			foreach($query->result() as $row){
				if($spells){
					foreach($spells as $spell){
						$name = $spell->spell_name;
						// $msql = "SELECT round(sum(working_hours-idle_hours)/ 8, 2) as value from daily_attendance WHERE attendance_date BETWEEN '".date('Y-m-d',strtotime($from_date))."' AND '".date('Y-m-d',strtotime($to_date))."' AND worked_department_id = ".$row->worked_department_id." AND worked_designation_id = ".$row->worked_designation_id." AND spell = '".$name."';";
						// $query1 = $this->db->query($msql);
						// $row->$name = $query1->row()->value;//$this->getHands($row->worked_department_id,$row->worked_designation_id,$name,$from_date,$to_date);
						$row->$name = $this->getHands($row->worked_department_id,$row->worked_designation_id,$name,$from_date,$to_date);
					}
				}
				$data[] = $row;
			}

		}

		// $this->varaha->print_arrays($data);		
		return $data;
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

		$sql = "select
					da.worked_department_id ,
					dm.dept_desc ,
					da.worked_designation_id,
					d.desig				
				from
					daily_attendance da left join designation d on d.id =da.worked_designation_id
					left join department_master dm on dm.dept_id =da.worked_department_id
				where
					da.attendance_date BETWEEN '".date('Y-m-d',strtotime($pers['from_date']))."' AND '".date('Y-m-d',strtotime($pers['to_date']))."'
					and da.is_active = 1
					and da.status_id = 3
					".$sql_depts."
					".$sql_desig."
					and da.company_id=".$pers['company']."
					group by
					da.worked_department_id ,
					da.worked_designation_id    
				order by dm.dept_desc ,
				d.desig";
		
		$q = $this->db->query($sql);
		$spells = $this->varaha_model->getAllSpells($pers['company']);
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				if($spells){
					foreach($spells as $spell){
						$name = $spell->spell_name;						
						$row->$name = $this->getHands($row->worked_department_id,$row->worked_designation_id,$name,date('Y-m-d',strtotime($pers['from_date'])),date('Y-m-d',strtotime($pers['to_date'])));
					}
				}
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
}
?>
	
