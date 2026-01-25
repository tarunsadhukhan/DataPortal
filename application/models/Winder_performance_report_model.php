<?php
class Winder_performance_report_model extends CI_Model
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
        $compid=$companyId;
        $Date = $from_date;
//        $ldate=date('Y-m-d', strtotime($Date. ' - 15 days'));
        $itcode = $_POST['itcod'];
        $eb_no = $_POST['eb_no'];
        $srno = $_POST['srno'];
        $Source = $_POST['Source'];

        $startdate=$from_date;
        $enddate=$to_date;
        $sql="select tpp.*,DATE_SUB(date_from, INTERVAL 1 DAY) AS previous_date_from  from EMPMILL12.tbl_report_period 
        tpp where '".$to_date."' between date_from and date_to
        and company_id=".$companyId;
//        echo $sql;
        $query=$this->db->query($sql);
            $row = $query->row();
            $cstartdate=$row->date_from;
            $cenddate=$row->date_to;
            $ldate=$row->previous_date_from ;   
            
            $sql="select * from EMPMILL12.tbl_report_period tpp where '".$ldate."' between date_from and date_to
            and company_id=".$companyId;
            $query=$this->db->query($sql);
                $row = $query->row();
                $lstartdate=$row->date_from;
                $lenddate=$row->date_to;
    
             if ($companyId==2)  { $desgid= '71,74,78,231,603'; }
             if ($companyId==1)  { $desgid= '942,1113'; }
             
             if ($Source==1) {($lmtype='Hessian');}
             if ($Source==2) {($lmtype='Sacking');}

//            echo $Source.'='.$from_date.'='.$to_date.'-cur-'.$cstartdate.'='.$cenddate.'=last-'.$ldate.'='.$lstartdate.'='.$lenddate.'=='.
//            $desgid;
  
  
        $sql="select wm.eb_no ebno,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,mcnos,dprd.spg_group,substr(lstdet.spell,1,1) shift,dprod,deff,
        dwhrs,round(dprod/dmcwhrs*8,0) davgprod,ceff,cwhrs,round(cprod/cmcwhrs*8,0) cavgprod,leff,lwhrs,round(lprod/lmcwhrs*8,0) lavgprod,
        round(oprod/otargetprod*100,1) oeff,round(oprod/omcwhrs*8,0) oavgprod from (
        select company_id,spg_group,eb_id,sum(prod) dprod,sum(atthrs) dwhrs,sum(target_prod) dtargetprod,round(sum(prod)/sum(target_prod)*100,1) deff,
        sum(mcwhrs) dmcwhrs
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  
        group by company_id,spg_group,eb_id
        ) dprd left join 
        (select company_id,spg_group,eb_id,sum(prod) cprod,sum(atthrs) cwhrs,sum(target_prod) ctargetprod,round(sum(prod)/sum(target_prod)*100,1) ceff,
        sum(mcwhrs) cmcwhrs
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$cstartdate."' and '".$cenddate."' and company_id=".$compid." 
        group by company_id,spg_group,eb_id
        ) curfne on dprd.company_id=curfne.company_id and dprd.spg_group=curfne.spg_group and dprd.eb_id=curfne.eb_id
        left join 
        (select company_id,spg_group,eb_id,sum(prod) lprod,sum(atthrs) lwhrs,sum(target_prod) ltargetprod,round(sum(prod)/sum(target_prod)*100,1) leff,
        sum(mcwhrs) lmcwhrs
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$lstartdate."' and '".$lenddate."' and company_id=".$compid."  
        group by company_id,spg_group,eb_id
        ) lfne on dprd.company_id=lfne.company_id and dprd.spg_group=lfne.spg_group and dprd.eb_id=lfne.eb_id
        left join 
        (
        select eb_id,sum(oprod) oprod,sum(owhrs) owhrs,sum(omcwhrs) omcwhrs,sum(otargetprod) otargetprod from (
        select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
        select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  
        and shift='A' 
        group by company_id,tran_date,shift,spg_group,eb_id
        ) curprd left join 
        (
        select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'A' 
        group by company_id,tran_date,shift,spg_group
        ) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
        union ALL 
        select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
        select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift='B' 
        group by company_id,tran_date,shift,spg_group,eb_id
        ) curprd left join 
        (
        select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'B' 
        group by company_id,tran_date,shift,spg_group
        ) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
        union all
        select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
        select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift='C' 
        group by company_id,tran_date,shift,spg_group,eb_id
        ) curprd left join 
        (
        select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
        from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'C' 
        group by company_id,tran_date,shift,spg_group
        ) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
        ) othprdsum group by eb_id
        ) othprddata on dprd.eb_id=othprddata.eb_id
        left join 
        (
        select eb_id,spell,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos from (
        SELECT *
        FROM EMPMILL12.view_proc_spellwindingdata lstall
        WHERE (lstall.eb_id, lstall.tran_date, lstall.spell) IN (
                SELECT max_dates.eb_id, max_dates.mxdate, max_spells.mxspell
                FROM (
                    SELECT eb_id, max(tran_date) AS mxdate
                    FROM EMPMILL12.view_proc_spellwindingdata
                    WHERE tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid." 
                    GROUP BY eb_id
                ) AS max_dates
                JOIN (
                    SELECT eb_id, tran_date, max(spell) AS mxspell
                    FROM EMPMILL12.view_proc_spellwindingdata
                    WHERE tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid." 
                    GROUP BY eb_id, tran_date
                ) AS max_spells
                ON max_dates.eb_id = max_spells.eb_id AND max_dates.mxdate = max_spells.tran_date
        ) ) g 
        group by eb_id,spell
        ) lstdet on dprd.eb_id=lstdet.eb_id
         join worker_master wm on dprd.eb_id=wm.eb_id
        order by spg_group,deff
            ";


//echo $sql;



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

		
        $compid=$pers['company'];
        $Date = $pers['from_date'];
//        $ldate=date('Y-m-d', strtotime($Date. ' - 15 days'));
        $itcode = $_POST['itcod'];
        $eb_no =$pers['eb_no'];
        $srno = $_POST['srno'];
        $Source = $pers['Source'];

        $startdate=$pers['from_date'];
        $enddate=$pers['to_date'];
//echo $startdate.'='.$enddate;
        $sql="select tpp.*,DATE_SUB(date_from, INTERVAL 1 DAY) AS previous_date_from  from EMPMILL12.tbl_report_period 
        tpp where '".$pers['to_date']."' between date_from and date_to
        and company_id=".$pers['company'];
   //    echo $sql;
        $query=$this->db->query($sql);
            $row = $query->row();
            $cstartdate=$row->date_from;
            $cenddate=$row->date_to;
            $ldate=$row->previous_date_from ;   
            
            $sql="select * from EMPMILL12.tbl_report_period tpp where '".$ldate."' between date_from and date_to
            and company_id=".$pers['company'];
            $query=$this->db->query($sql);
                $row = $query->row();
                $lstartdate=$row->date_from;
                $lenddate=$row->date_to;
    
             if ($pers['company']==2)  { $desgid= '71,74,78,231,603'; }
             if ($pers['company']==1)  { $desgid= '942,1113'; }
             
             if ($Source==1) {($lmtype='Hessian');}
             if ($Source==2) {($lmtype='Sacking');}

//            echo $Source.'='.$from_date.'='.$to_date.'-cur-'.$cstartdate.'='.$cenddate.'=last-'.$ldate.'='.$lstartdate.'='.$lenddate.'=='.
//            $desgid;
  
//echo 'current==='.$cstartdate.'=='.$cenddate  ;
$sql="select wm.eb_no ebno,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,mcnos,dprd.spg_group,substr(lstdet.spell,1,1) shift,dprod,deff,
dwhrs,round(dprod/dmcwhrs*8,0) davgprod,ceff,cwhrs,round(cprod/cmcwhrs*8,0) cavgprod,leff,lwhrs,round(lprod/lmcwhrs*8,0) lavgprod,
round(oprod/otargetprod*100,1) oeff,round(oprod/omcwhrs*8,0) oavgprod from (
select company_id,spg_group,eb_id,sum(prod) dprod,sum(atthrs) dwhrs,sum(target_prod) dtargetprod,round(sum(prod)/sum(target_prod)*100,1) deff,
sum(mcwhrs) dmcwhrs
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  
group by company_id,spg_group,eb_id
) dprd left join 
(select company_id,spg_group,eb_id,sum(prod) cprod,sum(atthrs) cwhrs,sum(target_prod) ctargetprod,round(sum(prod)/sum(target_prod)*100,1) ceff,
sum(mcwhrs) cmcwhrs
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$cstartdate."' and '".$cenddate."' and company_id=".$compid." 
group by company_id,spg_group,eb_id
) curfne on dprd.company_id=curfne.company_id and dprd.spg_group=curfne.spg_group and dprd.eb_id=curfne.eb_id
left join 
(select company_id,spg_group,eb_id,sum(prod) lprod,sum(atthrs) lwhrs,sum(target_prod) ltargetprod,round(sum(prod)/sum(target_prod)*100,1) leff,
sum(mcwhrs) lmcwhrs
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$lstartdate."' and '".$lenddate."' and company_id=".$compid."  
group by company_id,spg_group,eb_id
) lfne on dprd.company_id=lfne.company_id and dprd.spg_group=lfne.spg_group and dprd.eb_id=lfne.eb_id
left join 
(
select eb_id,sum(oprod) oprod,sum(owhrs) owhrs,sum(omcwhrs) omcwhrs,sum(otargetprod) otargetprod from (
select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  
and shift='A' 
group by company_id,tran_date,shift,spg_group,eb_id
) curprd left join 
(
select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'A' 
group by company_id,tran_date,shift,spg_group
) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
union ALL 
select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift='B' 
group by company_id,tran_date,shift,spg_group,eb_id
) curprd left join 
(
select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'B' 
group by company_id,tran_date,shift,spg_group
) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
union all
select curprd.eb_id,othprd.oprod,owhrs,otargetprod,omcwhrs   from (
select company_id,tran_date,shift,spg_group,eb_id,sum(prod) pprod,sum(atthrs) pwhrs,sum(target_prod) ptargetprod,sum(mcwhrs) pmcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift='C' 
group by company_id,tran_date,shift,spg_group,eb_id
) curprd left join 
(
select company_id,tran_date,shift,spg_group,sum(prod) oprod,sum(atthrs) owhrs,sum(target_prod) otargetprod,sum(mcwhrs) omcwhrs 
from EMPMILL12.view_proc_spellwindingdata where tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid."  and shift<>'C' 
group by company_id,tran_date,shift,spg_group
) othprd on curprd.company_id=othprd.company_id and curprd.tran_date=othprd.tran_date  and curprd.spg_group=othprd.spg_group
) othprdsum group by eb_id
) othprddata on dprd.eb_id=othprddata.eb_id
left join 
(
select eb_id,spell,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos from (
SELECT *
FROM EMPMILL12.view_proc_spellwindingdata lstall
WHERE (lstall.eb_id, lstall.tran_date, lstall.spell) IN (
        SELECT max_dates.eb_id, max_dates.mxdate, max_spells.mxspell
        FROM (
            SELECT eb_id, max(tran_date) AS mxdate
            FROM EMPMILL12.view_proc_spellwindingdata
            WHERE tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid." 
            GROUP BY eb_id
        ) AS max_dates
        JOIN (
            SELECT eb_id, tran_date, max(spell) AS mxspell
            FROM EMPMILL12.view_proc_spellwindingdata
            WHERE tran_date between '".$startdate."' and '".$enddate."' and company_id=".$compid." 
            GROUP BY eb_id, tran_date
        ) AS max_spells
        ON max_dates.eb_id = max_spells.eb_id AND max_dates.mxdate = max_spells.tran_date
) ) g 
group by eb_id,spell
) lstdet on dprd.eb_id=lstdet.eb_id
 join worker_master wm on dprd.eb_id=wm.eb_id
order by spg_group,deff
";


        

//echo $sql;


 
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
	
