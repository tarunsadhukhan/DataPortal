<?php
class Half_day_absentism_report_module extends CI_Model
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
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_desig = (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$att_spells = (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
		$eb_no = $_POST['eb_no'];
		$att_cat_att = $_POST['att_cat_att'];
        $srno = $_POST['srno'];


        $compid=$companyId;
        $paydate= $from_date;
    	$Source = $_POST['Source'];
		$att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept']: null);
		$itcod = $_POST['itcod'];
        
        $fromdate=$from_date;
        $todate=$to_date;
 
	 
          

        
         $sql="SELECT B.eb_id,emp_code,concat(thepd.first_name,' ',thepd.last_name) empname,DEPT_DESC,
         date_format(attendance_date,'%d-%m-%Y') attendance_date,shift,HALFDAYS_mn,v.HALFDAYS FROM 
         (
         SELECT eb_id,YEAR,MONTH,HALFDAYS HALFDAYS_mn FROM
         (
         select eb_id,EXTRACT(YEAR FROM attendance_date) YEAR ,EXTRACT(month FROM attendance_date) MONTH,COUNT(*) HALFDAYS from EMPMILL12.half_day_data
         WHERE attendance_date between '".$fromdate."' and '".$todate."' 
         GROUP BY eb_id,EXTRACT(YEAR FROM attendance_date) ,EXTRACT(month FROM attendance_date)
         ) g
         ) A
         left join 
         (
         select spl.*,allsft.whrs from (
         SELECT eb_id,attendance_date,shift,SUM(spl1wrkhrs) spl1wrkhrs,SUM(spl2wrkhrs) spl2wrkhrs  FROM 
         (
         select company_id,eb_id,attendance_date, substr(spell,1,1) shift,(working_hours) spl1wrkhrs,0 spl2wrkhrs   from daily_attendance da
         where attendance_type ='R' and spell='A1' and is_active=1 and attendance_date 
         between  '".$fromdate."' and '".$todate."' and company_id=".$compid."
         union all
         select company_id,eb_id,attendance_date,substr(spell,1,1) shift,0 spl1wrkhrs,(working_hours) spl2wrkhrs   from daily_attendance da
         where attendance_type ='R' and spell='A2' and is_active=1 and attendance_date 
         between '".$fromdate."' and '".$todate."' and company_id=".$compid."
         union all
         select company_id,eb_id,attendance_date,substr(spell,1,1) shift,(working_hours) spl1wrkhrs,0 spl2wrkhrs   from daily_attendance da
         where attendance_type ='R' and spell='B1' and is_active=1 and attendance_date 
         between '".$fromdate."' and '".$todate."' and company_id=".$compid."
         union all
         select company_id,eb_id,attendance_date,substr(spell,1,1) shift,0 spl1wrkhrs,(working_hours-idle_hours) spl2wrkhrs   from daily_attendance da
         where attendance_type ='R' and spell='B2' and is_active=1 and attendance_date 
         between '".$fromdate."' and '".$todate."' and company_id=".$compid."
         ) g group by  eb_id,attendance_date,shift 
         ) spl  join
         (
         SELECT eb_id,attendance_date,sum(working_hours) whrs from daily_attendance da 
         where attendance_type ='R' and is_active =1 group by eb_id,attendance_date
         ) allsft on spl.eb_id=allsft.eb_id and spl.attendance_date=allsft.attendance_date
         and spl1wrkhrs>0 AND spl2wrkhrs=0 and whrs<>8
         )
          B  on A.eb_id=B.eb_id AND EXTRACT(YEAR FROM attendance_date)=YEAR AND 
         EXTRACT(MONTH FROM attendance_date)=MONTH
         Left join (
         select eb_id,COUNT(*) HALFDAYS from EMPMILL12.half_day_data
         WHERE attendance_date between '".$fromdate."' and '".$todate."' 
         GROUP BY eb_id
         ) v on A.eb_id=v.eb_id
          join tbl_hrms_ed_personal_details thepd on thepd.eb_id=A.eb_id
          left join (select * from tbl_hrms_ed_official_details where is_active=1 ) theod  on A.eb_id=theod.eb_id
          left join department_master dm on dm.dept_id=theod.department_id
          where thepd.is_active=1 
          and attendance_date<='".$todate."'  and thepd.company_id=".$compid
          
        ;

        if ($itcod) {
            $sql=$sql." and theod.emp_code='".$itcod."'";
        }    
        if ($att_dept) {
            if ($att_dept<>'ALL') {
                $sql=$sql." and dept_desc='".$att_dept."'";
            }    
        }    


        $sql=$sql."  order by theod.emp_code

        ";
        



//		echo $sql;
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

		$att_cat = $pers['att_cat'];
        $srno = $pers['srno'];
		$att_cat_att = $pers['att_cat_att'];
        $fromdate  =$pers['from_date'];             
        $companyId=$pers['company'];
        $todate  =$pers['to_date'];    
        $paydate=$pers['from_date'];
        $compid=$pers['company'];
  
        $Source = $pers['Source'];
        $att_dept = $pers['att_dept'];
        $itcod = $pers['itcod'];

  
        $sql="SELECT B.eb_id,emp_code,concat(thepd.first_name,' ',thepd.last_name) empname,
        DEPT_DESC,date_format(attendance_date,'%d-%m-%Y') attendance_date,shift,HALFDAYS_mn,v.HALFDAYS FROM 
        (
        SELECT eb_id,YEAR,MONTH,HALFDAYS HALFDAYS_mn FROM
        (
        select eb_id,EXTRACT(YEAR FROM attendance_date) YEAR ,EXTRACT(month FROM attendance_date) MONTH,COUNT(*) HALFDAYS from EMPMILL12.half_day_data
        WHERE attendance_date between '".$fromdate."' and '".$todate."' 
        GROUP BY eb_id,EXTRACT(YEAR FROM attendance_date) ,EXTRACT(month FROM attendance_date)
        ) g
        ) A
        left join 
        (
        select spl.*,allsft.whrs from (
        SELECT eb_id,attendance_date,shift,SUM(spl1wrkhrs) spl1wrkhrs,SUM(spl2wrkhrs) spl2wrkhrs  FROM 
        (
        select company_id,eb_id,attendance_date, substr(spell,1,1) shift,(working_hours) spl1wrkhrs,0 spl2wrkhrs   from daily_attendance da
        where attendance_type ='R' and spell='A1' and is_active=1 and attendance_date 
        between '".$fromdate."' and '".$todate."' and company_id=".$compid."
        union all
        select company_id,eb_id,attendance_date,substr(spell,1,1) shift,0 spl1wrkhrs,(working_hours) spl2wrkhrs   from daily_attendance da
        where attendance_type ='R' and spell='A2' and is_active=1 and attendance_date 
        between '".$fromdate."' and '".$todate."' and company_id=".$compid."
        union all
        select company_id,eb_id,attendance_date,substr(spell,1,1) shift,(working_hours) spl1wrkhrs,0 spl2wrkhrs   from daily_attendance da
        where attendance_type ='R' and spell='B1' and is_active=1 and attendance_date 
        between '".$fromdate."' and '".$todate."' and company_id=".$compid."
        union all
        select company_id,eb_id,attendance_date,substr(spell,1,1) shift,0 spl1wrkhrs,(working_hours-idle_hours) spl2wrkhrs   from daily_attendance da
        where attendance_type ='R' and spell='B2' and is_active=1 and attendance_date 
        between '".$fromdate."' and '".$todate."' and company_id=".$compid."
        ) g group by  eb_id,attendance_date,shift 
        ) spl  join
        (
        SELECT eb_id,attendance_date,sum(working_hours) whrs from daily_attendance da 
        where attendance_type ='R' and is_active =1 group by eb_id,attendance_date
        ) allsft on spl.eb_id=allsft.eb_id and spl.attendance_date=allsft.attendance_date
        and spl1wrkhrs>0 AND spl2wrkhrs=0 and whrs<>8
        )
         B  on A.eb_id=B.eb_id AND EXTRACT(YEAR FROM attendance_date)=YEAR AND 
        EXTRACT(MONTH FROM attendance_date)=MONTH
        Left join (
        select eb_id,COUNT(*) HALFDAYS from EMPMILL12.half_day_data
        WHERE attendance_date between '".$fromdate."' and '".$todate."' 
        GROUP BY eb_id
        ) v on A.eb_id=v.eb_id
         join tbl_hrms_ed_personal_details thepd on thepd.eb_id=A.eb_id
         left join (select * from tbl_hrms_ed_official_details where is_active=1 ) theod  on A.eb_id=theod.eb_id
         left join department_master dm on dm.dept_id=theod.department_id
         where thepd.is_active=1 
         and attendance_date<='".$todate."'  and thepd.company_id=".$compid
         
       ;

       if ($itcod) {
           $sql=$sql." and theod.emp_code='".$itcod."'";
       }    
       if ($att_dept) {
           if ($att_dept<>'ALL') {
               $sql=$sql." and dept_desc='".$att_dept."'";
           }    
       }    


       $sql=$sql."  order by theod.emp_code
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
	// da.attendance_type in ('R', 'O', 'C')
	// 	and da.attendance_source in ('A', 'M')
	
}
?>