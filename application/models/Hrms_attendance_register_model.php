<?php
class Hrms_attendance_register_model extends CI_Model
{

	var $table = 'daily_attendance da ';	
	var $column_order = array(null, 'Tran_No','EB_No','empname','Date','Department','Designation','Mark'); //set column field database for datatable orderable
	var $column_search = array( 'Tran_No','EB_No','Name','Date','Department','Designation','Mark'); //set column field database for datatable searchable 
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
		// $att_status = $_POST['att_status'];
		$att_dept = $_POST['att_dept'];
		$att_desig = $_POST['att_desig'];
		$att_spells = $_POST['att_spells'];
		$eb_no = $_POST['eb_no'];
		if($att_dept!='0'){
			$department =  " AND department_master.dept_id = '".$att_dept."'";
		}else{
			$department = "";
		}
		if($att_desig!=0){
			$designation =  " AND designation.id = '".$att_desig."'";
		}else{
			$designation =  "";
		}
		$sql = "SELECT
			`daily_atten_id` as `Tran_No`,
			`eb_no` as `EB_No`,
			`attendance_date` as `Date`,
			(
			SELECT
				dept_desc
			FROM
				department_master
			WHERE
				department_master.dept_id = daily_attendance.worked_department_id
				".$department.") as `Department`,
			(
			SELECT
				desig
			FROM
				designation
			WHERE
				designation.id = daily_attendance.worked_department_id ".$designation.") as `Designation`,
			`attendance_mark` as `Mark`,
			`idle_hours` as `Idle_Hours`,
			`spell_hours` as `Spell_Hours`,
			`working_hours` as `Work_Hours`,
			(
			SELECT
				CONCAT( worker_name, ' ', `middle_name`, ' ', last_name )
			FROM
				worker_master
			WHERE
				worker_master.eb_id = daily_attendance.eb_id) as empname
		FROM
			`daily_attendance`";

		

		if($eb_no){
			$sql .= " WHERE eb_no= '".$eb_no."' ";
		}else{

			$sql .= " WHERE
				`eb_no` IN (SELECT
				distinct eb_no
			from
				daily_attendance
			where
				company_id = 1
				AND attendance_type IN ('R', 'O', 'C')
				AND attendance_source in ('A', 'M')
				AND is_active = 1
				AND attendance_date >= '".date('Y-m-d',strtotime($from_date))."'
				AND attendance_date <= '".date('Y-m-d',strtotime($to_date))."') ";
		}
		
	//	echo $sql;
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$Source = $_POST['Source'];
		$att_type = $_POST['att_type'];
		// $att_status = $_POST['att_status'];
		$att_dept = $_POST['att_dept'];
		$att_desig = $_POST['att_desig'];
		$att_spells = $_POST['att_spells'];
		$eb_no = $_POST['eb_no'];

		$attdata = array();
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
		$mres = $query->result();
		$columns = $this->columns->getReportColumns($submenuId,$from_date,$to_date);
		$array_keys = array_keys($columns);
		$dates_array = array_slice($array_keys, 3); 

		if($query->num_rows()>0){
			$s=1;
			foreach($mres as $row){
				$this->db->select("attendance_date,attendance_mark, Sum(spell_hours), working_hours");
				$this->db->where("eb_no",$row->EB_No);
				$this->db->where_in("Date(attendance_date)",$dates_array);
				$this->db->where("company_id",$companyId);
				$this->db->where("is_active",1);
				// if($Source!=0){
				// 	if($Source==1){
				// 		$this->db->where_in("attendance_source", array('A', 'M'));
				// 	}
				// 	if($Source==2){
				// 		$this->db->where_in("attendance_source", array('F'));
				// 	}
				// 	if($Source==3){
				// 		$this->db->where_in("attendance_source", array('P'));
				// 	}
				// }else{
				// 	$this->db->where_in("attendance_source", array('A', 'M','F','P'));
				// }
				// if($att_spells){
				// 	$this->db->where("Spell", $att_spells);
				// }else{
				// 	$this->db->where_in("Spell", array('A1','B1','General'));
				// }
				// if($att_type){
				// 	$this->db->where("attendance_type", $att_type);	
				// }else{
				// 	$this->db->where_in("attendance_type", array('R', 'O', 'C'));	
				// }
				$this->db->group_by('attendance_date,attendance_mark,working_hours');
				$this->db->order_by('Date(attendance_date)');
				$qs=$this->db->get('daily_attendance');
				// $this->varaha->print_arrays($row->empname,$qs->result());
				if($qs->num_rows()>0){					
					$mdata=array('no'=> $s, 'EB_No'=>$row->EB_No, 'empname'=>$row->empname);
					$mdata1=array('no'=> $s, 'EB_No'=>$row->EB_No, 'empname'=>$row->empname);
					$m=3;					
					foreach($qs->result() as $mrow){							
						if($_POST['att_mark_hrs_att']==1){
							$mdata = array_merge($mdata, [$mrow->attendance_date => $mrow->attendance_mark]);
						}else{
							$mdata = array_merge($mdata, [$mrow->attendance_date => ($mrow ? $mrow->working_hours : "")]); 
						}
						
						$m++;						
					}
					
				}
				$s++;
				for($s=3;$s<count($array_keys);$s++){
					$name = $array_keys[$s];
					if(isset($mdata[$name])){
						$mdata1 = array_merge($mdata1, [$name => $mdata[$name]]);
					}else{
						$mdata1 = array_merge($mdata1, [$name => ""]);
					}
				}
				
				$attdata[] = $mdata1;
			}
		}
		
		return $attdata;
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = "SELECT distinct eb_no from daily_attendance where company_id = ".$companyId." AND attendance_type IN ('R', 'O', 'C') AND attendance_source in ('A', 'M') AND is_active = 1 AND attendance_date >= '".date('Y-m-d',strtotime($from_date))."' AND attendance_date <= '".date('Y-m-d',strtotime($to_date))."'";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{	
		$this->db->from($this->table);		
		return $this->db->count_all_results();
	}

	public function directReport($pers){

	
		$Source = $pers['Source'];
		$att_type = $pers['att_type'];
		// $att_status = $_POST['att_status'];
		$att_dept = $pers['att_dept'];
		$att_desig = $pers['att_desig'];
		$att_spells = $pers['att_spells'];
		$eb_no = $pers['eb_no'];

		$attdata = array();
		if($att_dept!='0'){
			$department =  " AND department_master.dept_id = '".$att_dept."'";
		}else{
			$department = "";
		}
		if($att_desig!=0){
			$designation =  " AND designation.id = '".$att_desig."'";
		}else{
			$designation =  "";
		}
		$sql = "SELECT
			`daily_atten_id` as `Tran_No`,
			`eb_no` as `EB_No`,
			`attendance_date` as `Date`,
			(
			SELECT
				dept_desc
			FROM
				department_master
			WHERE
				department_master.dept_id = daily_attendance.worked_department_id
				".$department.") as `Department`,
			(
			SELECT
				desig
			FROM
				designation
			WHERE
				designation.id = daily_attendance.worked_department_id ".$designation.") as `Designation`,
			`attendance_mark` as `Mark`,
			`idle_hours` as `Idle_Hours`,
			`spell_hours` as `Spell_Hours`,
			`working_hours` as `Work_Hours`,
			(
			SELECT
				CONCAT( worker_name, ' ', `middle_name`, ' ', last_name )
			FROM
				worker_master
			WHERE
				worker_master.eb_id = daily_attendance.eb_id) as empname
		FROM
			`daily_attendance`";

		

		if($eb_no){
			$sql .= " WHERE eb_no= '".$eb_no."' ";
		}else{

			$sql .= " WHERE
				`eb_no` IN (SELECT
				distinct eb_no
			from
				daily_attendance
			where
				company_id = ".$pers['company']."
				AND attendance_type IN ('R', 'O', 'C')
				AND attendance_source in ('A', 'M')
				AND is_active = 1
				AND attendance_date >= '".date('Y-m-d',strtotime($pers['from_date']))."'
				AND attendance_date <= '".date('Y-m-d',strtotime($pers['to_date']))."') ";
		}
		
		$query = $this->db->query($sql);
		$mres = $query->result();
		$columns = $this->columns->getReportColumns($pers['submenuId'],$pers['from_date'],$pers['to_date']);
		$array_keys = array_keys($columns);
		$dates_array = array_slice($array_keys, 3); 
		// $this->varaha->print_arrays($mres);
		if($query->num_rows()>0){
			$s=1;
			foreach($mres as $row){
				$this->db->select("attendance_date,attendance_mark, Sum(spell_hours), working_hours");
				$this->db->where("eb_no",$row->EB_No);
				$this->db->where_in("Date(attendance_date)",$dates_array);
				$this->db->where("company_id",$pers['company']);
				$this->db->where("is_active",1);
				// if($Source!=0){
				// 	if($Source==1){
				// 		$this->db->where_in("attendance_source", array('A', 'M'));
				// 	}
				// 	if($Source==2){
				// 		$this->db->where_in("attendance_source", array('F'));
				// 	}
				// 	if($Source==3){
				// 		$this->db->where_in("attendance_source", array('P'));
				// 	}
				// }else{
				// 	$this->db->where_in("attendance_source", array('A', 'M','F','P'));
				// }
				// if($att_spells){
				// 	$this->db->where("Spell", $att_spells);
				// }else{
				// 	$this->db->where_in("Spell", array('A1','B1','General'));
				// }
				// if($att_type){
				// 	$this->db->where("attendance_type", $att_type);	
				// }else{
				// 	$this->db->where_in("attendance_type", array('R', 'O', 'C'));	
				// }
				$this->db->group_by('attendance_date,attendance_mark,working_hours');
				$this->db->order_by('Date(attendance_date)');
				$qs=$this->db->get('daily_attendance');
				if($qs->num_rows()>0){
					
					$mdata=array('no'=> $s, 'EB_No'=>$row->EB_No, 'empname'=>$row->empname);
					$mdata1=array('no'=> $s, 'EB_No'=>$row->EB_No, 'empname'=>$row->empname);
					$m=3;
					foreach($qs->result() as $mrow){
						// if($_POST['att_mark_hrs_att']==1){
						// 	$mdata = array_merge($mdata, [$array_keys[$m] => ($mrow ? $mrow->attendance_mark : "")]);
						// }else{
						// 	$mdata = array_merge($mdata, [$array_keys[$m] => ($mrow ? $mrow->working_hours : "")]); 
						// }

						if($_POST['att_mark_hrs_att']==1){
							$mdata = array_merge($mdata, [$mrow->attendance_date => $mrow->attendance_mark]);
						}else{
							$mdata = array_merge($mdata, [$mrow->attendance_date => ($mrow ? $mrow->working_hours : "")]); 
						}
						
						$m++;						
					}
				}
				$s++;
				for($s=3;$s<count($array_keys);$s++){
					$name = $array_keys[$s];
					if(isset($mdata[$name])){
						$mdata1 = array_merge($mdata1, [$name => $mdata[$name]]);
					}else{
						$mdata1 = array_merge($mdata1, [$name => ""]);
					}
				}
				$attdata[] = $mdata1;
			}
		}

		return $attdata;

	}
	
	
}
?>