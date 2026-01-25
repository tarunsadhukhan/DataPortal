<?php
class Date_wise_loom_reports_model extends CI_Model
{

	


	var $table = 'itemmaster im';	
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
		$Date = $from_date;
        $ldate=date('Y-m-d', strtotime($Date. ' - 15 days'));
        $itcode = $_POST['itcod'];
        $srno = $_POST['srno'];
   //     echo $srno;
        
//echo date('Y-m-d', strtotime($Date. ' + 10 days'));

$sql="select dprd.*,pprd.peff from (
    select mm.mech_code,loom_date,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
    from (
    select loom_date,".$companyId." compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
    efficiency_a1 eff from cuts_jugar_buff_1 cjb  
    where production_a1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
    where production_a2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
    where production_b1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
    where production_b2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
    where production_c>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    ) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
    group by mm.mech_code,loom_date
    ) dprd left join 
    (
    select mm.mech_code,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) peff
    from (
    select loom_date,".$companyId." compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
    efficiency_a1 eff from cuts_jugar_buff_1 cjb  
    where production_a1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
    where production_a2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
    where production_b1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
    where production_b2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    union all
    select loom_date,".$companyId." compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
    where production_c>0 and loom_date  between '".$from_date."' and '".$to_date."' and company_id =".$companyId." 
    ) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
    group by mech_code 
    ) pprd on dprd.mech_code=pprd.mech_code
     ";
$n=0;
if (strlen($itcode.$srno)>0) {
    $sql=$sql." where ";      
if ($itcode) {
	$sql=$sql." dprd.mech_code='".$itcode."'";
    $n++;
}
if ($srno) {
    if ($n==0) {
        $sql=$sql."  peff<".$srno;
    } else
 {
    $sql=$sql." and peff<".$srno;
}
}

}

$date1=$from_date;
$date2=$to_date;

$dt1=date_create($date1);
$dt2=date_create($date2);
$diff=date_diff($dt1,$dt2);
$dfm=$diff->format("%a")+1;

//echo $dfm;

$date3=$dt1;
$date4=$dt2;
$date5=$dt1;

$x=1;
$query="select mech_code Loom_no,";
while($date3 <= $date4) {
	
$diff=date_diff($date3,$date4);
$df=$diff->format("%a");

	$string = $date3->format('Y-m-d');
	$day=substr($string,8,2);
	$month=substr($string,5,2);
	$dm=$day.'/'.$month;

$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";


$date3=date_add($date3,date_interval_create_from_date_string("1 days"));



 
}

$query=rtrim($query)."
	count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";
	
   // distinct(peff) 'Avg Eff'";
	$cmpn='Njm';

$query=rtrim($query, ", ");
$query=$query." from ( " .$sql .") h group by mech_code order by mech_code";



$sql=$query;


	//	echo $sql;
  
		return $sql;
	}

     function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
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

		
        $itcode = $pers['itcod']; 
        $sql="select * from ( select eb_id,eb_no,empname,sum(prod) prod,sum(whrs) whrs,round(sum(prod)/sum(whrs)*8,0) 
prod_8hrs,
round(sum(targetprod)/sum(whrs)*8,0) target_8hrs,
round(sum(targetprod)-sum(prod),0) diff,
round((round(sum(prod)/ sum(whrs)* 8, 0))/(round(sum(targetprod)/ sum(whrs)* 8, 0))*100,2) eff,
wnd_group_name,image_html from ( 
select swd.*,da.eb_id,da.eb_no,(da.working_hours-idle_hours) whrs,da.attendance_type,
concat(thepd.first_name, ifnull(thepd.middle_name, ''), ifnull(thepd.last_name, '')) AS empname,d.desig,om.OCCU_CODE,
(TARGET_PROD/8*(da.working_hours-idle_hours)) targetprod
from EMPMILL12.spellwindingdata swd
left join (select * from vowsls.daily_ebmc_attendance dea where dea.is_active=1) dea
on dea.attendace_date=swd.tran_date and dea.spell=swd.spell and dea.mc_id=swd.WND_MC_ID
left join (select * from vowsls.daily_attendance where is_active=1) da
on da.daily_atten_id=dea.daily_atten_id
left join vowsls.tbl_hrms_ed_personal_details thepd on thepd.eb_id=da.eb_id
left join vowsls.ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID=da.worked_designation_id
Left join EMPMILL12.OCCUPATION_MASTER om on om.occu_id=oolt.ORA_TABLE_ID
left join vowsls.designation d on d.id=da.worked_designation_id
where prod>0 and swd.tran_date between '".$pers['from_date']."' and '".$pers['to_date']."' and 
swd.company_id=".$pers['company']."
) g left join (select * from EMPMILL12.tbl_item_image tii where image_type='E') tii  on eb_id=tii.item_id
group by eb_id,eb_no,empname,wnd_group_name,image_html
order by wnd_group_name,eb_no
";

$Date = $pers['from_date'];
$ldate=date('Y-m-d', strtotime($Date. ' - 15 days'));
$itcode = $_POST['itcod'];
$srno = $_POST['srno'];
//     echo $srno;

//echo date('Y-m-d', strtotime($Date. ' + 10 days'));

$sql="select dprd.*,pprd.peff from (
select mm.mech_code,loom_date,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,".$pers['company']." compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mech_code,loom_date
) dprd left join 
(
select mm.mech_code,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,".$pers['company']." compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,".$pers['company']." compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date  between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mech_code 
) pprd on dprd.mech_code=pprd.mech_code
";
$n=0;
if (strlen($itcode.$srno)>0) {
$sql=$sql." where ";      
if ($itcode) {
$sql=$sql." dprd.mech_code='".$itcode."'";
$n++;
}
if ($srno) {
if ($n==0) {
$sql=$sql."  peff<".$srno;
} else
{
$sql=$sql." and peff<".$srno;
}
}

}

$date1=$pers['from_date'];
$date2=$pers['to_date'];

$dt1=date_create($date1);
$dt2=date_create($date2);
$diff=date_diff($dt1,$dt2);
$dfm=$diff->format("%a")+1;

//echo $dfm;

$date3=$dt1;
$date4=$dt2;
$date5=$dt1;

$x=1;
$query="select mech_code Loom_no,";
while($date3 <= $date4) {

$diff=date_diff($date3,$date4);
$df=$diff->format("%a");

$string = $date3->format('Y-m-d');
$day=substr($string,8,2);
$month=substr($string,5,2);
$dm=$day.'/'.$month;

$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";


$date3=date_add($date3,date_interval_create_from_date_string("1 days"));




}

$query=rtrim($query)."
count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";

// distinct(peff) 'Avg Eff'";
$cmpn='Njm';

$query=rtrim($query, ", ");
$query=$query." from ( " .$sql .") h group by mech_code order by mech_code";



$sql=$query;


//	echo $sql;


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
	
}
?>
	
