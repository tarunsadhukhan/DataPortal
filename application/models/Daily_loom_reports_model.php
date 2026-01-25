<?php
class Daily_loom_reports_model extends CI_Model
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

$sql="select h.*,proda+prodb+prodc prodtot from (
select 1 rem,dprd.*,pprd.peff,line_number from 
(
select mechine_name,max( case when shift='A' then prod else 0 end ) AS proda,
max( case when shift='B' then prod else 0 end ) AS prodb,
max( case when shift='C' then prod else 0 end ) AS prodc,
max( case when shift='A' then eff else 0 end ) AS effa,
max( case when shift='B' then eff else 0 end ) AS effb,
max( case when shift='C' then eff else 0 end ) AS effc,
round(sum(whrs*eff)/sum(whrs),2) offf
from (
select mm.mechine_name,loom_date,shift,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date='".$from_date."' and company_id =".$companyId." 
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date='".$from_date."' and company_id =".$companyId."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name,loom_date,shift 
) g group by mechine_name
) dprd
left join
(
select mm.mechine_name,sum(prod) pprod,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,ifnull(production_a1,0) prod,ifnull(working_hrs_a1,0) whrs,
ifnull(efficiency_a1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a1,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,ifnull(production_a2,0) prod,ifnull(working_hrs_a2,0) whrs,ifnull(efficiency_a2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a2,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,ifnull(production_b1,0) prod,ifnull(working_hrs_b1,0) whrs,
ifnull(efficiency_b1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b1,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,ifnull(production_b2,0) prod,ifnull(working_hrs_b2,0) whrs,
ifnull(efficiency_b2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b2,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,ifnull(production_c,0) prod,ifnull(working_hrs_c,0) whrs,
ifnull(efficiency_c,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_c,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name   
) pprd on dprd.mechine_name=pprd.mechine_name
left join mechine_master mm on dprd.mechine_name=mm.mechine_name and mm.company_id=".$companyId."
union all
select 2 rem,' Line Total' mechine_name,sum(proda) proda,sum(prodb) prodb,sum(prodc) prodc,round(sum(effa*whrsa)/sum(whrsa),2) effa,
round(sum(effb*whrsb)/sum(whrsb),2) effb,round(sum(effc*whrsc)/sum(whrsc),2) effc,
round(sum((whrsa+whrsb+whrsc)*offf)/sum((whrsa+whrsb+whrsc)),2) offf,round(sum(pprd.peff*pprd.pwhrs)/sum(pprd.pwhrs),2) peff
,line_number from 
(
select mechine_name,max( case when shift='A' then prod else 0 end ) AS proda,
max( case when shift='B' then prod else 0 end ) AS prodb,
max( case when shift='C' then prod else 0 end ) AS prodc,
max( case when shift='A' then eff else 0 end ) AS effa,
max( case when shift='B' then eff else 0 end ) AS effb,
max( case when shift='C' then eff else 0 end ) AS effc,
max( case when shift='A' then whrs else 0 end ) AS whrsa,
max( case when shift='B' then whrs else 0 end ) AS whrsb,
max( case when shift='C' then whrs else 0 end ) AS whrsc,
round(sum(whrs*eff)/sum(whrs),2) offf
from (
select mm.mechine_name,loom_date,shift,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date='".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date='".$from_date."' and company_id =".$companyId."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name,loom_date,shift 
) g group by mechine_name
) dprd
left join
(
select mm.mechine_name,sum(prod) pprod,sum(whrs) pwhrs,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,ifnull(production_a1,0) prod,ifnull(working_hrs_a1,0) whrs,
ifnull(efficiency_a1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a1,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."

union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,ifnull(production_a2,0) prod,ifnull(working_hrs_a2,0) whrs,ifnull(efficiency_a2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a2,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."

union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,ifnull(production_b1,0) prod,ifnull(working_hrs_b1,0) whrs,
ifnull(efficiency_b1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b1,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,ifnull(production_b2,0) prod,ifnull(working_hrs_b2,0) whrs,
ifnull(efficiency_b2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b2,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,ifnull(production_c,0) prod,ifnull(working_hrs_c,0) whrs,
ifnull(efficiency_c,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_c,0)>0 and loom_date between '".$ldate."' and '".$from_date."' and company_id =".$companyId."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name   
) pprd on dprd.mechine_name=pprd.mechine_name
left join mechine_master mm on dprd.mechine_name=mm.mechine_name and mm.company_id=".$companyId."
group by line_number
) h ";
$n=0;
if (strlen($itcode.$srno)>0) {
    $sql=$sql." where ";      
if ($itcode) {
	$sql=$sql." line_number='".$itcode."'";
    $n++;
}
if ($srno) {
    if ($n==0) {
        $sql=$sql."  offf<".$srno;
    } else
 {
    $sql=$sql." and offf<".$srno;
}
}

}

$sql=$sql." 
order by line_number,rem,mechine_name
";
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

$sql="select h.*,proda+prodb+prodc prodtot from (
    select 1 rem,dprd.*,pprd.peff,line_number from 
    (
    select mechine_name,max( case when shift='A' then prod else 0 end ) AS proda,
max( case when shift='B' then prod else 0 end ) AS prodb,
max( case when shift='C' then prod else 0 end ) AS prodc,
max( case when shift='A' then eff else 0 end ) AS effa,
max( case when shift='B' then eff else 0 end ) AS effb,
max( case when shift='C' then eff else 0 end ) AS effc,
round(sum(whrs*eff)/sum(whrs),2) offf
from (
select mm.mechine_name,loom_date,shift,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']." 
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name,loom_date,shift 
) g group by mechine_name
) dprd
left join
(
select mm.mechine_name,sum(prod) pprod,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,ifnull(production_a1,0) prod,ifnull(working_hrs_a1,0) whrs,
ifnull(efficiency_a1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a1,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,ifnull(production_a2,0) prod,ifnull(working_hrs_a2,0) whrs,ifnull(efficiency_a2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a2,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,ifnull(production_b1,0) prod,ifnull(working_hrs_b1,0) whrs,
ifnull(efficiency_b1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b1,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,ifnull(production_b2,0) prod,ifnull(working_hrs_b2,0) whrs,
ifnull(efficiency_b2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b2,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,ifnull(production_c,0) prod,ifnull(working_hrs_c,0) whrs,
ifnull(efficiency_c,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_c,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name   
) pprd on dprd.mechine_name=pprd.mechine_name
left join mechine_master mm on dprd.mechine_name=mm.mechine_name and mm.company_id=".$pers['company']."
union all
select 2 rem,' Line Total' mechine_name,sum(proda) proda,sum(prodb) prodb,sum(prodc) prodc,round(sum(effa*whrsa)/sum(whrsa),2) effa,
round(sum(effb*whrsb)/sum(whrsb),2) effb,round(sum(effc*whrsc)/sum(whrsc),2) effc,
round(sum((whrsa+whrsb+whrsc)*offf)/sum((whrsa+whrsb+whrsc)),2) offf,round(sum(pprd.peff*pprd.pwhrs)/sum(pprd.pwhrs),2) peff
,line_number from 
(
select mechine_name,max( case when shift='A' then prod else 0 end ) AS proda,
max( case when shift='B' then prod else 0 end ) AS prodb,
max( case when shift='C' then prod else 0 end ) AS prodc,
max( case when shift='A' then eff else 0 end ) AS effa,
max( case when shift='B' then eff else 0 end ) AS effb,
max( case when shift='C' then eff else 0 end ) AS effc,
max( case when shift='A' then whrs else 0 end ) AS whrsa,
max( case when shift='B' then whrs else 0 end ) AS whrsb,
max( case when shift='C' then whrs else 0 end ) AS whrsc,
round(sum(whrs*eff)/sum(whrs),2) offf
from (
select mm.mechine_name,loom_date,shift,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date='".$pers['from_date']."' and company_id =".$pers['company']."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name,loom_date,shift 
) g group by mechine_name
) dprd
left join
(
select mm.mechine_name,sum(prod) pprod,sum(whrs) pwhrs,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,1 compid,loom_id,'A1' spell,'A' Shift,quality_code_a1 qcode,ifnull(production_a1,0) prod,ifnull(working_hrs_a1,0) whrs,
ifnull(efficiency_a1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a1,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'A2' spell,'A' Shift,quality_code_a2 qcode,ifnull(production_a2,0) prod,ifnull(working_hrs_a2,0) whrs,ifnull(efficiency_a2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_a2,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B1' spell,'B' Shift,quality_code_b1 qcode,ifnull(production_b1,0) prod,ifnull(working_hrs_b1,0) whrs,
ifnull(efficiency_b1,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b1,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'B2' spell,'B' Shift,quality_code_b2 qcode,ifnull(production_b2,0) prod,ifnull(working_hrs_b2,0) whrs,
ifnull(efficiency_b2,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_b2,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
union all
select loom_date,1 compid,loom_id,'C' spell,'C' Shift,quality_code_c qcode,ifnull(production_c,0) prod,ifnull(working_hrs_c,0) whrs,
ifnull(efficiency_c,0) eff from cuts_jugar_buff_1 cjb  
where ifnull(production_c,0)>0 and loom_date between '".$ldate."' and '".$pers['from_date']."' and company_id =".$pers['company']."
) prd left join mechine_master mm on prd.loom_id=mm.mechine_id 
group by mm.mechine_name   
) pprd on dprd.mechine_name=pprd.mechine_name
left join mechine_master mm on dprd.mechine_name=mm.mechine_name and mm.company_id=".$pers['company']."
group by line_number
) h ";
if ($itcode) {
	$sql=$sql." where  line_number='".$itcode."'";
}
$sql=$sql."
order by line_number,rem,mechine_name
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
	
