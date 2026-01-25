<?php
class Attendance_checklist_Model extends CI_Model
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

		

 
		
		$sql="select DATE_FORMAT(attendance_date, '%d-%m-%Y')  Date,spell Spell,eb_no EB_No,name Name,dept_desc Department,
		desig Designation,attendance_type,whrs Working_Hours,mcnos MC_Nos,attendance_source,remarks from (
		select da.company_id,da.company_name,da.attendance_date,da.spell,da.eb_no,name,da.worked_department_id,dept_desc,da.worked_designation_id,desig,  
		da.attendance_type, whrs,spell_hours, ifnull(mcnos,' ') mcnos,attendance_source,remarks from (
		select da.company_id,cm.company_name,  da.daily_atten_id, da.attendance_date,da.spell,da.eb_no,
		concat(thepd.first_name, ' ', ifnull(middle_name, ' '), ' ', ifnull(last_name, ' ')) name,
		da.worked_department_id,
		dm.dept_desc,da.worked_designation_id,dsg.desig,da.attendance_type,da.attendance_source,
		da.working_hours-da.idle_hours whrs ,da.spell_hours,remarks
		from daily_attendance da , tbl_hrms_ed_personal_details thepd ,department_master dm ,  designation dsg,company_master cm 
		where da.eb_id = thepd.eb_id 
		and dm.dept_id=da.worked_department_id and dsg.id=da.worked_designation_id and da.company_id=cm.comp_id and da.is_active=1
		and attendance_date between '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."' and da.company_id= ".$companyId;
 		$sql=$sql." ) da left join ";
		$sql=$sql."
		(
		select daily_atten_id,eb_no,spell,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos  from (
		select  daily_atten_id,eb_no,spell,mechine_name from  daily_ebmc_attendance dea , mechine_master mm 
		where dea.mc_id=mm.mechine_id and is_active=1  and attendace_date between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		) g group by daily_atten_id,eb_no,spell
		) dea on da.daily_atten_id=dea.daily_atten_id
		) k where  attendance_date between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		and company_id=".$companyId;
		 if($eb_no){
			$sql .= " and eb_no= '".$eb_no."'";
		}
		if($att_type!=0){
			$sql .= " and attendance_type= '".$att_type."'";
		}
		if($Source!=0){
			if($Source==1){
				$sql .= " and attendance_source in ('A', 'M')";
			}
			if($Source==2){
				$sql .= " and attendance_source = 'F'";
			}
			if($Source==3){
				$sql .= " and attendance_source = 'P'";
			}
		}
		if($att_dept!='0' && $att_dept!=''  && $att_dept!=null){
			$sql .= " and dept_desc= '".$att_dept."'";
		}
		if($att_desig!=0 && $att_desig!=''  && $att_desig!=null){
			$sql .= " and desig= '".$att_desig."'";
		}
		if($att_spells!=0 && $att_spells!='' && $att_spells!=null){
			$sql .= " and spell= '".$att_spells."'";
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

		$sql.=" order by eb_no,attendance_date,dept_desc,spell,desig";

		if(isset($_POST['order'])) {
//			$sql = $sql . "ORDER BY ". $this->column_order[$_POST['order']['0']['column']].",". $_POST['order']['0']['dir'];
		}else if(isset($this->order)){
//			$order = $this->order;
			// $sql = $sql . "ORDER BY ". key($order) .",". $order[key($order)];
		}
 
	//	echo $sql;
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
//		 $this->varaha->print_arrays($sql);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
	//	 $this->varaha->print_arrays($this->db->last_query());
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

 		// and da.status_id
		// and da.worked_department_id
		// and da.worked_designation_id
		// and da.spell is not null
		// and da.attendance_type is not null
		// and da.eb_no is not null;";

		$sql="select DATE_FORMAT(attendance_date, '%d-%m-%Y')  Date,spell Spell,eb_no EB_No,name Name,dept_desc Department,
		desig Designation,attendance_type,whrs Working_Hours,mcnos MC_Nos,attendance_source,remarks from (
		select da.company_id,da.company_name,da.attendance_date,da.spell,da.eb_no,name,da.worked_department_id,dept_desc,da.worked_designation_id,desig,  
		da.attendance_type, whrs,spell_hours, ifnull(mcnos,' ') mcnos,attendance_source,remarks from (
		select da.company_id,cm.company_name,  da.daily_atten_id, da.attendance_date,da.spell,da.eb_no,
		concat(thepd.first_name,' ',ifnull(middle_name,' '),' ',ifnull(last_name,' '))  name,da.worked_department_id,
		dm.dept_desc,da.worked_designation_id,dsg.desig,da.attendance_type,da.attendance_source,
		da.working_hours-da.idle_hours whrs ,da.spell_hours,remarks
		from daily_attendance da , tbl_hrms_ed_personal_details thepd ,department_master dm ,  designation dsg,company_master cm 
		where da.eb_id = thepd.eb_id  
		and dm.dept_id=da.worked_department_id and dsg.id=da.worked_designation_id and da.company_id=cm.comp_id and da.is_active=1
		and attendance_date between '".date('Y-m-d',strtotime($pers['from_date']))."' and '".date('Y-m-d',strtotime($pers['to_date']))."' 
		and da.company_id= ".$pers['company'];
 		$sql=$sql." ) da left join ";
		$sql=$sql."
		(
		select daily_atten_id,eb_no,spell,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos  from (
		select  daily_atten_id,eb_no,spell,mechine_name from  daily_ebmc_attendance dea , mechine_master mm 
		where dea.mc_id=mm.mechine_id and is_active=1 and attendace_date between  '".date('Y-m-d',strtotime($pers['from_date']))."' and '".date('Y-m-d',strtotime($pers['to_date']))."'
		) g group by daily_atten_id,eb_no,spell
		) dea on da.daily_atten_id=dea.daily_atten_id
		) k where  attendance_date between  '".date('Y-m-d',strtotime($pers['from_date']))."' and '".date('Y-m-d',strtotime($pers['to_date']))."' 
		and company_id= ".$pers['company'];
		 if($pers['eb_no']){
			$sql .= " and eb_no= '".$pers['eb_no']."'";
		}
		
		if($pers['att_type']=='0'){
			$sql .= " and attendance_type in ('R', 'O', 'C')";			
		}else{
			$sql .= " and attendance_type= '".$pers['att_type']."'";
		}
		if($pers['Source']!=0){
			if($pers['Source']==1){
				$sql .= " and attendance_source in ('A', 'M')";
			}
			if($pers['Source']==2){
				$sql .= " and attendance_source = 'F'";
			}
			if($pers['Source']==3){
				$sql .= " and attendance_source = 'P'";
			}
		}else{
			$sql .= " and attendance_source in ('A', 'M','F','P')";
		}
		if($pers['att_dept']!='0' && $pers['att_dept']!=''  && $pers['att_dept']!=null){
			$sql .= " and dept_desc= '".$pers['att_dept']."'";
		}
		if($pers['att_desig']!=0 && $pers['att_desig']!=''  && $pers['att_desig']!=null){
			$sql .= " and desig= '".$pers['att_desig']."'";
		}
		
		if($pers['att_spells']!=0 && $pers['att_spells']!='' && $pers['att_spells']!=null){
			$sql .= " and spell= '".$pers['att_spells']."'";
		}

		$sql.="order by eb_no,attendance_date,dept_desc,spell,desig";
	
//echo $sql;

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
	
public function directsummReport($pers){

	$sql="SELECT 
    date_format(da.attendance_date,'%d-%m-%Y') AS `Date`,
    md.dept_desc mdeptname,d.desig,md.dept_code mdeptcode,
    -- Shift groupings
    SUM(CASE WHEN da.spell IN ('A2','A1') THEN working_hours ELSE 0 END) AS `A`,
    SUM(CASE WHEN da.spell IN ( 'B1','B2' ) THEN working_hours ELSE 0 END) AS `B`,
    SUM(CASE WHEN da.spell IN ( 'C') THEN working_hours ELSE 0 END) AS `C`,
    sum(working_hours) AS `Shift_Total`,
    -- Category-wise counts (fixed categories)
    SUM(CASE WHEN cm.cata_code = 'PER' THEN working_hours ELSE 0 END) AS `Permanent`,
    SUM(CASE WHEN cm.cata_code = 'BUD' THEN working_hours ELSE 0 END) AS `Budli`,
    SUM(CASE WHEN cm.cata_code = 'RTD' THEN working_hours ELSE 0 END) AS `Retired`,
    SUM(CASE WHEN cm.cata_code = 'NB' THEN working_hours ELSE 0 END) AS `New_Budli`,
    SUM(CASE WHEN cm.cata_code = 'C' THEN working_hours ELSE 0 END) AS `Contract`,
    SUM(CASE WHEN cm.cata_code = 'O' THEN working_hours ELSE 0 END) AS `Outsider`,
    SUM(CASE WHEN cm.cata_code = 'A' THEN working_hours ELSE 0 END) AS `Apprentice`,
    sum(working_hours) AS `Category_Total`
FROM 
    (
    SELECT attendance_date,spell,sum(working_hours-idle_hours)/8 working_hours,da.worked_department_id,da.worked_designation_id,eb_id,company_id
    from daily_attendance da 
    where attendance_date between  '".date('Y-m-d',strtotime($pers['from_date']))."' and '".date('Y-m-d',strtotime($pers['to_date']))."' 
		and company_id= ".$pers['company'] ." and is_active=1
    group by attendance_date,spell,worked_department_id,da.worked_designation_id,eb_id,company_id 
) da    
    join (select * from tbl_hrms_ed_official_details theod where is_active=1) theod on theod.eb_id =da.eb_id
JOIN 
    department_master dm  ON dm.dept_id =da.worked_department_id
JOIN 
    category_master cm ON cm.cata_id =theod.catagory_id 
JOIN master_department md on dm.mdept_id=md.mdept_id  and dm.company_id =md.company_id
JOIN designation d on d.id =da.worked_designation_id
 -- Optional: Filter by date
WHERE  theod.catagory_id in (14,15,16,17,20,21,22)
GROUP BY 
    da.attendance_date,md.dept_desc,d.desig,md.dept_code
ORDER BY 
    da.attendance_date,md.dept_code ,md.dept_desc";

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