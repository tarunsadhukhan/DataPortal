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
		$sql = "SELECT distinct eb_no from daily_attendance where company_id = ".$companyId." AND attendance_type IN ('R', 'O', 'C') AND attendance_source in ('A', 'M') AND is_active = 1 AND attendance_date >= '".date('Y-m-d',strtotime($from_date))."' AND attendance_date <= '".date('Y-m-d',strtotime($to_date))."'";
		$eb_no = $_POST['eb_no'];
		if($eb_no){
			$sql .= " and eb_no= '".$eb_no."'";
		}
		
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

		if($query->num_rows()>0){
			$columns = $this->columns->getReportColumns($submenuId,$from_date,$to_date);
			$m=1;
						foreach($mres as $row){

								if($att_dept!='0'){
									$department =  " AND department_master.dept_id = '".$att_dept."'";
								}else{
									$department = "";
								}
								if($att_desig!=0){
									$department =  " AND designation.id = '".$att_desig."'";
								}
											
								$this->db->select("daily_atten_id as `Tran_No`,eb_no as `EB_No`,attendance_date as `Date`,
								(SELECT dept_desc FROM department_master WHERE department_master.dept_id = daily_attendance.worked_department_id ".$department.") as `Department`,
								(SELECT desig FROM designation WHERE designation.id= daily_attendance.worked_department_id) as `Designation`,
								attendance_mark as `Mark`,
								idle_hours as `Idle_Hours`,
								spell_hours as `Spell_Hours`,
								working_hours as `Work_Hours`,
								(SELECT CONCAT( worker_name ,' ',middle_name,' ' ,last_name ) FROM worker_master WHERE worker_master.eb_id=daily_attendance.eb_id) as empname");
								$this->db->where("eb_no",$row->eb_no);
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
								$q=$this->db->get('daily_attendance');
								$this->varaha->print_arrays($this->db->last_query());
								if($q->num_rows()>0){
									$list = $q->row();
								}

								if($columns){
									$array_keys = array_keys($columns);
									$mdata=array('no'=> $m, 'EB_No'=>$list->EB_No, 'empname'=>$list->empname);
									$attrow=0;
									for($i=0; $i<count($array_keys); $i++){
										$attrow=0;
										if($i>2){
											$this->db->select("attendance_mark, Spell_Hours, working_hours");
											$this->db->where("eb_no",$list->EB_No);
											$this->db->where("Date(attendance_date)",$array_keys[$i]);
											$this->db->where("company_id",$companyId);
											$this->db->where("is_active",1);
											if($Source!=0){
												if($Source==1){
													$this->db->where_in("attendance_source", array('A', 'M'));
												}
												if($Source==2){
													$this->db->where_in("attendance_source", array('F'));
												}
												if($Source==3){
													$this->db->where_in("attendance_source", array('P'));
												}
											}else{
												$this->db->where_in("attendance_source", array('A', 'M','F','P'));
											}
											if($att_spells){
												$this->db->where("Spell", $att_spells);
											}else{
												$this->db->where_in("Spell", array('A1','B1','General'));
											}
											if($att_type){
												$this->db->where("attendance_type", $att_type);	
											}else{
												$this->db->where_in("attendance_type", array('R', 'O', 'C'));	
											}
											$this->db->limit(1);
											$qs=$this->db->get('daily_attendance');
											// $this->varaha->print_arrays($this->db->last_query());
											if($qs->num_rows()>0){
												$attrow = $qs->row();
											}
											if($_POST['att_mark_hrs_att']==1){
												$mdata = array_merge($mdata, [$array_keys[$i] => ($attrow ? $attrow->attendance_mark : $attrow)]);
											}else{
												$mdata = array_merge($mdata, [$array_keys[$i] => ($attrow ? $attrow->working_hours : $attrow)]);
											}
										}
									}
								}
								$attdata[] = $mdata;
								$m++;
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
	$sql = "SELECT distinct eb_no from daily_attendance where company_id = ".$companyId." AND attendance_type IN ('R', 'O', 'C') AND attendance_source in ('A', 'M') AND is_active = 1 AND attendance_date >= '".date('Y-m-d',strtotime($from_date))."' AND attendance_date <= '".date('Y-m-d',strtotime($to_date))."'";
	if($pers['eb_no']){
		$sql .= " and eb_no= '".$pers['eb_no']."'";
	}
	$query = $this->db->query($sql);
	$mres = $query->result();

		if($query->num_rows()>0){
		$columns = $this->columns->getReportColumns($submenuId,$from_date,$to_date);
		$m=1;
					foreach($mres as $row){

						if($pers['att_dept']!='0'){
							$department =  " AND department_master.dept_id = '".$att_dept."'";
						}else{
							$department = "";
						}
						if($pers['att_desig']!=0){
							$designation =  " AND designation.id = '".$att_desig."'";
						}else{
							$designation =  "";
						}
										
							$this->db->select("daily_atten_id as `Tran_No`,eb_no as `EB_No`,attendance_date as `Date`,
							(SELECT dept_desc FROM department_master WHERE department_master.dept_id = daily_attendance.worked_department_id ".$department.") as `Department`,
							(SELECT desig FROM designation WHERE designation.id= daily_attendance.worked_department_id ".$designation.") as `Designation`,
							attendance_mark as `Mark`, (SELECT CONCAT( worker_name ,' ',middle_name,' ' ,last_name ) FROM worker_master WHERE worker_master.eb_id=daily_attendance.eb_id) as empname");
							
							
							
							$this->db->where("eb_no",$row->eb_no);
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
							$q=$this->db->get('daily_attendance');
							if($q->num_rows()>0){
								$list = $q->row();
							}

							if($columns){
								$array_keys = array_keys($columns);
								$mdata=array('no'=> $m, 'EB_No'=>$list->EB_No, 'empname'=>$list->empname);
								$attrow=0;
								for($i=0; $i<count($array_keys); $i++){
									$attrow=0;
									if($i>2){
										$this->db->select("attendance_mark");
										$this->db->where("eb_no",$list->EB_No);
										$this->db->where("Date(attendance_date)",$array_keys[$i]);
										$this->db->where("company_id",$companyId);
										$this->db->where("is_active",1);
										$this->db->where_in("attendance_type", array('R', 'O', 'C'));
										if($pers['Source']!=0){
											if($pers['Source']==1){
												$this->db->where_in("attendance_source", array('A', 'M'));
											}
											if($pers['Source']==2){
												$this->db->where_in("attendance_source", array('F'));
											}
											if($pers['Source']==3){
												$this->db->where_in("attendance_source", array('P'));
											}
										}else{
											$this->db->where_in("attendance_source", array('A', 'M','F','P'));
										}
										if($att_spells){
											$this->db->where("Spell", $att_spells);
										}else{
											$this->db->where_in("Spell", array('A1','B1','General'));
										}
										if($att_type){
											$this->db->where("attendance_type", $att_type);	
										}else{
											$this->db->where_in("attendance_type", array('R', 'O', 'C'));	
										}										
										$this->db->limit(1);
										$qs=$this->db->get('daily_attendance');
										if($qs->num_rows()>0){
											$attrow = $qs->row();
										}
										$mdata = array_merge($mdata, [$array_keys[$i] => ($attrow ? $attrow->attendance_mark : $attrow)]);
									}
								}
							}
							$attdata[] = $mdata;
							$m++;
					}
				
		}

		// $this->varaha->print_arrays($attdata);
		return $attdata;



		// $q = $this->db->query($sql);
		// if($q->num_rows()>0){
		// 	foreach($q->result() as $row){
		// 		$data[] = $row;
		// 	}
		// 	return $data;
		// }
		// return false;
	}
	
	
}
?>