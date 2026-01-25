<?php
class Hrms_attendance_summary_model extends CI_Model
{

	var $table = 'daily_attendance as da';	
	var $column_order = array(null, 'dm.dept_desc','d.desig'); //set column field database for datatable orderable
	var $column_search = array( 'dm.dept_desc','d.desig'); //set column field database for datatable searchable 
	var $order = array('dm.dept_desc' => 'ASC' ,'d.desig' => 'ASC'); // default order
	var $myselect = "da.worked_department_id,dm.dept_desc,da.worked_designation_id,d.desig, sum(working_hours-idle_hours) as Work_Hours, round(sum(working_hours-idle_hours)/ 8, 2) as Hands";

	public function __construct()
	{		
		$this->load->database();		
	}

	
	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->join('designation d', 'd.id =da.worked_designation_id', 'left');
		$this->db->join('department_master dm' , 'dm.dept_id =da.worked_department_id', 'left');
		$this->db->where("da.attendance_date >= '".date('Y-m-d',strtotime($from_date))."' and da.attendance_date <= '".date('Y-m-d',strtotime($to_date))."'");
		$this->db->where('da.is_active',1);
		$this->db->where('da.status_id',3);
		if($_POST['att_dept']){
			$depts_arr = explode(',', $_POST['att_dept']);
			$this->db->where_in('da.worked_department_id', $depts_arr);
		}
		if($_POST['att_spells']){
			$this->db->where('spell',$_POST['att_spells']);
		}		
		$this->db->where('da.company_id',$companyId);
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

		$this->db->group_by('da.worked_department_id, da.worked_designation_id');
		
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
		$this->db->join('designation d', 'd.id =da.worked_designation_id', 'left');
		$this->db->join('department_master dm' , 'dm.dept_id =da.worked_department_id', 'left');
		$this->db->where("da.attendance_date >= '".date('Y-m-d',strtotime($from_date))."' and da.attendance_date <= '".date('Y-m-d',strtotime($to_date))."'");
		$this->db->where('da.is_active',1);
		$this->db->where('da.status_id',3);
		if($_POST['att_dept']){
			$depts_arr = explode(',', $_POST['att_dept']);
			$this->db->where_in('da.worked_department_id', $depts_arr);
		}
		if($_POST['att_spells']){
			$this->db->where('spell',$_POST['att_spells']);
		}	
		$this->db->where('spell','A1');
		$this->db->where('da.company_id',$companyId);	
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->join('designation d', 'd.id =da.worked_designation_id', 'left');
		$this->db->join('department_master dm' , 'dm.dept_id =da.worked_department_id', 'left');
		$this->db->where("da.attendance_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and da.attendance_date <= '".date('Y-m-d',strtotime($pers['to_date']))."'");
		$this->db->where('da.is_active',1);
		$this->db->where('da.status_id',3);
		if($pers['att_dept']){
			$depts_arr = explode(',', $pers['att_dept']);
			$this->db->where_in('da.worked_department_id', $depts_arr);
		}
		if($pers['att_spells']){
			$this->db->where('spell',$pers['att_spells']);
		}	
		$this->db->where('da.company_id',$pers['company']);	
		$this->db->group_by('da.worked_department_id, da.worked_designation_id');
		$this->db->order_by('dm.dept_desc','d.desig');
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