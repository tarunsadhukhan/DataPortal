<?php
class Hrms_full_attendance_model extends CI_Model
{

	var $table = 'daily_attendance da ';	
	var $column_order = array(null, 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable orderable
	var $column_search = array( 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable searchable 
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
		$att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept']: null);
		$att_desig = (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$att_spells = (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
		$eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($att_dept);

		

		$sql =  "SELECT da.daily_atten_id as `Tran_No`,
		da.eb_no as `EB_No`,
		CONCAT( wm.worker_name ,' ',wm.middle_name,' ' ,wm.last_name ) as Name,
		da.attendance_date as `Date`,
		dm.dept_desc as `Department`,
		d.desig as `Designation`,
		da.attendance_mark as `Mark`,
		da.spell as `Spell` ,
		da.idle_hours as `Idle_Hours`,
		da.spell_hours as `Spell_Hours`,
		da.working_hours as `Work_Hours`,
		case
			when da.attendance_source in ('A', 'M') then 'Manual'
			when da.attendance_source = 'F' then 'Facial'
			when da.attendance_source = 'P' then 'Logs'
			else ' '
		end as `Source`,
		case
			when da.attendance_type = 'R' then 'Regular'
			when da.attendance_type = 'O' then 'OT'
			when da.attendance_type = 'C' then 'Cash'
			else ' '
		end as `Type`,
		sm.status_name as `Status`,
		da.remarks as `Remarks`
	from
		daily_attendance da left join worker_master wm on wm.eb_id =da.eb_id 
	left join status_master sm on
		sm.status_id = da.status_id
	left join department_master dm on
		dm.dept_id = da.worked_department_id
	left join designation d on
		d.id = da.worked_designation_id
	where
		-- da.attendance_type in ('R', 'O', 'C')
		-- and da.attendance_source in ('A', 'M')
		-- AND da.company_id = ".$companyId."
		da.company_id = ".$companyId."
		and da.is_active = 1
		and da.attendance_date >= '".date('Y-m-d',strtotime($from_date))."'
		and da.attendance_date <= '".date('Y-m-d',strtotime($to_date))."'";

		
		if($eb_no){
			$sql .= " and da.eb_no= '".$eb_no."'";
		}
		if($att_type!=0){
			$sql .= " and da.attendance_type= '".$att_type."'";
		}
		if($Source!=0){
			if($Source==1){
				$sql .= " and da.attendance_source in ('A', 'M')";
			}
			if($Source==2){
				$sql .= " and da.attendance_source = 'F'";
			}
			if($Source==3){
				$sql .= " and da.attendance_source = 'P'";
			}
		}
		if($att_dept!='0' && $att_dept!=''  && $att_dept!=null){
			$sql .= " and dm.dept_desc= '".$att_dept."'";
		}
		if($att_desig!=0 && $att_desig!=''  && $att_desig!=null){
			$sql .= " and d.desig= '".$att_desig."'";
		}
		if($att_spells!=0 && $att_spells!='' && $att_spells!=null){
			$sql .= " and da.spell= '".$att_spells."'";
		}
		
		// and da.status_id
		// and da.worked_department_id
		// and da.worked_designation_id
		// and da.spell is not null
		// and da.attendance_type is not null
		// and da.eb_no is not null;";
		
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

		$sql =  "SELECT da.daily_atten_id as `Tran_No`,
		da.eb_no as `EB_No`,
		CONCAT( wm.worker_name ,' ',wm.middle_name,' ' ,wm.last_name ) as Name,
		da.attendance_date as `Date`,
		dm.dept_desc as `Department`,
		d.desig as `Designation`,
		da.attendance_mark as `Mark`,
		da.spell as `Spell` ,
		da.idle_hours as `Idle_Hours`,
		da.spell_hours as `Spell_Hours`,
		da.working_hours as `Work_Hours`,
		case
			when da.attendance_source in ('A', 'M') then 'Manual'
			when da.attendance_source = 'F' then 'Facial'
			when da.attendance_source = 'P' then 'Logs'
			else ' '
		end as `Source`,
		case
			when da.attendance_type = 'R' then 'Regular'
			when da.attendance_type = 'O' then 'OT'
			when da.attendance_type = 'C' then 'Cash'
			else ' '
		end as `Type`,
		sm.status_name as `Status`,
		da.remarks as `Remarks`
	from
		daily_attendance da left join worker_master wm on wm.eb_id =da.eb_id 
	left join status_master sm on
		sm.status_id = da.status_id
	left join department_master dm on
		dm.dept_id = da.worked_department_id
	left join designation d on
		d.id = da.worked_designation_id
	where
		da.company_id = ".$pers['company']."
		and da.is_active = 1
		and da.attendance_date >= '".date('Y-m-d',strtotime($pers['from_date']))."'
		and da.attendance_date <= '".date('Y-m-d',strtotime($pers['to_date']))."'";
		if($pers['eb_no']){
			$sql .= " and da.eb_no= '".$pers['eb_no']."'";
		}
		
		if($pers['att_type']=='0'){
			$sql .= " and da.attendance_type in ('R', 'O', 'C')";			
		}else{
			$sql .= " and da.attendance_type= '".$pers['att_type']."'";
		}
		if($pers['Source']!=0){
			if($pers['Source']==1){
				$sql .= " and da.attendance_source in ('A', 'M')";
			}
			if($pers['Source']==2){
				$sql .= " and da.attendance_source = 'F'";
			}
			if($pers['Source']==3){
				$sql .= " and da.attendance_source = 'P'";
			}
		}else{
			$sql .= " and da.attendance_source in ('A', 'M','F','P')";
		}

		
		

		if($pers['att_dept']!='0' && $pers['att_dept']!=''  && $pers['att_dept']!=null){
			$sql .= " and dm.dept_desc= '".$pers['att_dept']."'";
		}
		if($pers['att_desig']!=0 && $pers['att_desig']!=''  && $pers['att_desig']!=null){
			$sql .= " and d.desig= '".$pers['att_desig']."'";
		}
		
		if($pers['att_spells']!=0 && $pers['att_spells']!='' && $pers['att_spells']!=null){
			$sql .= " and da.spell= '".$pers['att_spells']."'";
		}
		// and da.status_id
		// and da.worked_department_id
		// and da.worked_designation_id
		// and da.spell is not null
		// and da.attendance_type is not null
		// and da.eb_no is not null;";
		
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
	// da.attendance_type in ('R', 'O', 'C')
	// 	and da.attendance_source in ('A', 'M')
	
}
?>