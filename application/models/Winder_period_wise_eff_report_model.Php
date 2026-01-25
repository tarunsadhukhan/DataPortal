<?php
class Winder_period_wise_eff_report_model extends CI_Model
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
        $Source = $_POST['Source'];
   //     echo $srno;
        
//echo date('Y-m-d', strtotime($Date. ' + 10 days'));
 
		     if ($Source==1) {($lmtype='Hessian');}
             if ($Source==2) {($lmtype='Sacking');}



$sql="	select dprd.*,pprd.peff,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname
from (
select compid,
ebno,loom_date,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,company_id compid,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
) prd group by compid,ebno,loom_date
) dprd left join 
(
select  compid,ebno,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,company_id compid,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
union all
select loom_date,company_id compid,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date between '".$from_date."' and '".$to_date."' and company_id =".$companyId."
) prd group by compid,ebno
) pprd on dprd.ebno=pprd.ebno
join worker_master wm on dprd.ebno=wm.eb_no and dprd.compid=wm.company_id 
 
";

$sql="			select  dprd.*,pprd.* from (
    select 			
    vps.eb_no ebno,
    vps.eb_id,
    CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
    date_to loom_date,
    SUM(prod) AS prod,
    SUM(target_prod / 8 * mcwhrs) AS targetprod,
    SUM(atthrs) AS whrs,
    sum(mcwhrs) mcwhrs,
    ROUND(SUM(prod) / ROUND(SUM(target_prod / 8 * mcwhrs), 0) * 100, 2) AS eff,
    round(sum(prod)/sum(mcwhrs)*8,0) avgprod
    from EMPMILL12.tbl_winding_daily_spell_update_data vps,
    EMPMILL12.tbl_report_period trp, worker_master wm  
    where tran_date between trp.date_from and trp.date_to 
    and vps.company_id =2
    AND atthrs IS NOT NULL and trp.date_from between '".$from_date."' and '".$to_date."' and trp.company_id =".$companyId."
    and wm.eb_id=vps.eb_id
    GROUP BY
    vps.eb_no,
    vps.eb_id,
    date_to ) dprd
    left join
    (select 			
    vps.eb_id vebid,
    SUM(prod) AS pprod,
    SUM(target_prod / 8 * mcwhrs) AS ptargetprod,
    SUM(atthrs) AS pwhrs,
    sum(mcwhrs) pmcwhrs,
    ROUND(SUM(prod) / ROUND(SUM(target_prod / 8 * mcwhrs), 0) * 100, 2) AS peff,
    round(sum(prod)/sum(mcwhrs)*8,0) pavgprod
    from EMPMILL12.tbl_winding_daily_spell_update_data vps,
    EMPMILL12.tbl_report_period trp, worker_master wm  
    where tran_date between trp.date_from and trp.date_to 
    and vps.company_id =2
    AND atthrs IS NOT NULL and trp.date_from  between '".$from_date."' and '".$to_date."' and trp.company_id =".$companyId."
    and wm.eb_id=vps.eb_id
    GROUP BY
    vps.eb_id
     ) pprd on dprd.eb_id=vebid
";


$n=0;
if (strlen($itcode.$srno)>0) {
    $sql=$sql." where ";      
if ($itcode) {
	$sql=$sql." dprd.ebno='".$itcode."'";
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
$query="select ebno,wname,";
$sqlp="select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
 between '".$from_date."' and '".$to_date."' and company_id =".$companyId;
$queryp = $this->db->query($sqlp);
$datap=$queryp->result();
foreach ($datap as $recordp) {
//        $bid=$record->BUSINESSUNIT_ID;
$string=$recordp->date_to;



//while($date3 <= $date4) {
	
//$diff=date_diff($date3,$date4);
//$df=$diff->format("%a");

//	$string = $date3->format('Y-m-d');

	$day=substr($string,8,2);
	$month=substr($string,5,2);
	$dm=$day.'/'.$month;

//$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";

if ($Source==1) {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";
} else {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN prod ELSE 0 END) AS '$dm',";

}
//$date3=date_add($date3,date_interval_create_from_date_string("1 days"));



 
}

//$query=rtrim($query)."
//	count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";

    if ($Source==1) {
        $query=rtrim($query)."count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)  'Average'";
    } else {
        $query=rtrim($query)."count(*) 'Total_Days',round(sum(prod)/sum(whrs)*8,0)	'Average'";
    }
        
   // distinct(peff) 'Avg Eff'";
	$cmpn='Njm';

$query=rtrim($query, ", ");
$query=$query." from ( " .$sql .") h group by ebno,wname order by ebno";



$sql=$query;


//		echo $sql;
  
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

 
$Date = $pers['from_date'];
$ldate=date('Y-m-d', strtotime($Date. ' - 15 days'));
$itcode = $_POST['itcod'];
$srno = $_POST['srno'];
//     echo $srno;

$from_date=$pers['from_date'];
$to_date=$pers['to_date'];
$companyId=$pers['company'];
//echo date('Y-m-d', strtotime($Date. ' + 10 days'));




$sql="	select dprd.*,pprd.peff,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname
from (
select compid,
ebno,loom_date,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff
from (
select loom_date,company_id compid,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
) prd group by compid,ebno,loom_date
) dprd left join 
(
select  compid,ebno,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) peff
from (
select loom_date,company_id compid,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
efficiency_a1 eff from cuts_jugar_buff_1 cjb  
where production_a1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
where production_a2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
where production_b1>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
where production_b2>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
union all
select loom_date,company_id compid,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
where production_c>0 and loom_date between '".$pers['from_date']."' and '".$pers['to_date']."' and company_id =".$pers['company']." 
) prd group by compid,ebno
) pprd on dprd.ebno=pprd.ebno
join worker_master wm on dprd.ebno=wm.eb_no and dprd.compid=wm.company_id 
";

$sql="			select  dprd.*,pprd.* from (
    select 			
    vps.eb_no ebno,
    vps.eb_id,
    CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
    date_to loom_date,
    SUM(prod) AS prod,
    SUM(target_prod / 8 * mcwhrs) AS targetprod,
    SUM(atthrs) AS whrs,
    sum(mcwhrs) mcwhrs,
    ROUND(SUM(prod) / ROUND(SUM(target_prod / 8 * mcwhrs), 0) * 100, 2) AS eff,
    round(sum(prod)/sum(mcwhrs)*8,0) avgprod
    from EMPMILL12.tbl_winding_daily_spell_update_data vps,
    EMPMILL12.tbl_report_period trp, worker_master wm  
    where tran_date between trp.date_from and trp.date_to 
    and vps.company_id =".$companyId."
    AND atthrs IS NOT NULL and trp.date_from  between '".$from_date."' and '".$to_date."' and trp.company_id =".$companyId."    and wm.eb_id=vps.eb_id
    GROUP BY
    vps.eb_no,
    vps.eb_id,
    date_to ) dprd
    left join
    (select 			
    vps.eb_id vebid,
    SUM(prod) AS pprod,
    SUM(target_prod / 8 * mcwhrs) AS ptargetprod,
    SUM(atthrs) AS pwhrs,
    sum(mcwhrs) pmcwhrs,
    ROUND(SUM(prod) / ROUND(SUM(target_prod / 8 * mcwhrs), 0) * 100, 2) AS peff,
    round(sum(prod)/sum(mcwhrs)*8,0) pavgprod
    from EMPMILL12.tbl_winding_daily_spell_update_data vps,
    EMPMILL12.tbl_report_period trp, worker_master wm  
    where tran_date between trp.date_from and trp.date_to 
    and vps.company_id =".$companyId."
    AND atthrs IS NOT NULL and trp.date_from  between '".$from_date."' and '".$to_date."' and trp.company_id =".$companyId."
    and wm.eb_id=vps.eb_id
    GROUP BY
    vps.eb_id
     ) pprd on dprd.eb_id=vebid

    ";

$n=0;
if (strlen($itcode.$srno)>0) {
$sql=$sql." where ";      
if ($itcode) {
$sql=$sql." dprd.ebno='".$itcode."'";
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
$query="select ebno,wname,";
$sqlp="select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
between '".$from_date."' and '".$to_date."' and company_id =".$companyId;
$queryp = $this->db->query($sqlp);
$datap=$queryp->result();
foreach ($datap as $recordp) {
//        $bid=$record->BUSINESSUNIT_ID;
$string=$recordp->date_to;



//while($date3 <= $date4) {
	
//$diff=date_diff($date3,$date4);
//$df=$diff->format("%a");

//	$string = $date3->format('Y-m-d');

	$day=substr($string,8,2);
	$month=substr($string,5,2);
	$dm=$day.'/'.$month;

//$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";
if ($Source==1) {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";
} else {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN prod ELSE 0 END) AS '$dm',";

}


//$date3=date_add($date3,date_interval_create_from_date_string("1 days"));



 
}

//$query=rtrim($query)."
//count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";
if ($Source==1) {
    $query=rtrim($query)."count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)  'Average'";
} else {
    $query=rtrim($query)."count(*) 'Total_Days',round(sum(prod)/sum(whrs)*8,0)	'Average'";
}

// distinct(peff) 'Avg Eff'";
$cmpn='Njm';

$query=rtrim($query, ", ");
$query=$query." from ( " .$sql .") h group by ebno,wname order by ebno";



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
	
