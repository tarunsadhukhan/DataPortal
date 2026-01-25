<?php
class Date_wise_spinner_eff_report_model extends CI_Model
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
   //    echo 'my sorce'.$Source;
   //       echo 'srno '.$srno.' itt '.$itcode;  
//echo date('Y-m-d', strtotime($Date. ' + 10 days'));
 
		     if ($Source==1) {($lmtype='Hessian');}
             if ($Source==2) {($lmtype='Sacking');}



$sql="        select dprd.*,pprd.* from (
            SELECT vps.ebno ebno,vps.eb_id,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
           doffdate loom_date,
           round(SUM(prod),0) AS prod,
           SUM(prod100) AS targetprod,
           SUM(atthrs) AS whrs,
           ROUND(SUM(prod) / (SUM(prod100)) * 100, 2) AS eff
    FROM EMPMILL12.view_proc_daily_doff_details vps
    left join worker_master wm on vps.eb_id =wm.eb_id 
    WHERE vps.doffdate between '$from_date' and '$to_date' and vps.compid =$companyId
    AND atthrs IS NOT NULL
    GROUP BY vps.ebno,vps.eb_id, doffdate,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) 
    ) dprd left join 
    (SELECT vps.eb_id pebid,
           round(SUM(prod),0) AS pprod,
           SUM(prod100) AS ptargetprod,
           SUM(atthrs) AS pwhrs,
           ROUND(SUM(prod) / (SUM(prod100)) * 100, 2) AS peff
    FROM EMPMILL12.view_proc_daily_doff_details vps
    WHERE vps.doffdate between '$from_date' and '$to_date' and vps.compid =$companyId
    AND atthrs IS NOT NULL
    GROUP BY eb_id 
    ) pprd on dprd.eb_id=pprd.pebid
        ";

//echo $sql;
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
while($date3 <= $date4) {
	
$diff=date_diff($date3,$date4);
$df=$diff->format("%a");

	$string = $date3->format('Y-m-d');
	$day=substr($string,8,2);
	$month=substr($string,5,2);
	$dm=$day.'/'.$month;

 //   echo 'source'.$Source;

    if ($Source==1) {
        $query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";
    } else {
        $query=$query."MAX(CASE WHEN loom_date = '$string' THEN prod ELSE 0 END) AS '$dm',";
    
    }
    

$date3=date_add($date3,date_interval_create_from_date_string("1 days"));



 
}

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
$Source=$pers['Source'];
//echo date('Y-m-d', strtotime($Date. ' + 10 days'));



$sql="        select dprd.*,pprd.* from (
            SELECT vps.ebno ebno,vps.eb_id,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
           doffdate loom_date,
           round(SUM(prod),0) AS prod,
           SUM(prod100) AS targetprod,
           SUM(atthrs) AS whrs,
           ROUND(SUM(prod) / (SUM(prod100)) * 100, 2) AS eff
    FROM EMPMILL12.view_proc_daily_doff_details vps
    left join worker_master wm on vps.eb_id =wm.eb_id 
    WHERE vps.doffdate between '$from_date' and '$to_date' and vps.compid =$companyId
    AND atthrs IS NOT NULL
    GROUP BY vps.ebno,vps.eb_id, doffdate,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) 
    ) dprd left join 
    (SELECT vps.eb_id pebid,
           round(SUM(prod),0) AS pprod,
           SUM(prod100) AS ptargetprod,
           SUM(atthrs) AS pwhrs,
           ROUND(SUM(prod) / (SUM(prod100)) * 100, 2) AS peff
    FROM EMPMILL12.view_proc_daily_doff_details vps
    WHERE vps.doffdate between '$from_date' and '$to_date' and vps.compid =$companyId
    AND atthrs IS NOT NULL
    GROUP BY eb_id 
    ) pprd on dprd.eb_id=pprd.pebid
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
while($date3 <= $date4) {

$diff=date_diff($date3,$date4);
$df=$diff->format("%a");

$string = $date3->format('Y-m-d');
$day=substr($string,8,2);
$month=substr($string,5,2);
$dm=$day.'/'.$month;
if ($Source==1) {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";
} else {
    $query=$query."MAX(CASE WHEN loom_date = '$string' THEN prod ELSE 0 END) AS '$dm',";

}

$date3=date_add($date3,date_interval_create_from_date_string("1 days"));




}


if ($Source==1) {
    $query=rtrim($query)."count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";
} else {
    $query=rtrim($query)."count(*) 'Total_Days',round(sum(prod)/sum(whrs)*8,0)	'Avg_eff'";
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
	
