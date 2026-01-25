<?php
class Weaver_performance_report_model extends CI_Model
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
  
  
        $sql="select curdet.ebno,active,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,
        atthrs,ceff,catthrs,leff,latthrs,othdet.otheff,mcnos,shift,prod,eff
        from (
        select pprd.compid,pprd.ebno,pprd.prod,pprd.pwhrs,pprd.eff,pprd.atthrs,cprd.cwhrs,cprd.ceff,cprd.catthrs,lprd.lwhrs,lprd.leff,lprd.latthrs from (
        select compid,
        ebno,sum(prod) prod,sum(whrs) pwhrs,round(sum(eff*whrs)/sum(whrs),1) eff,sum(athrs) atthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) effhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$startdate."' and '".$enddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) pprd left join 
        (
        select compid,
        ebno,sum(prod) cprod,sum(whrs) cwhrs,round(sum(eff*whrs)/sum(whrs),1) ceff,sum(athrs) catthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) efhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$cstartdate."' and '".$enddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$cstartdate."' and '".$cenddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) cprd on pprd.ebno=cprd.ebno
        left join 
        (
        select compid,
        ebno,sum(prod) lprod,sum(whrs) lwhrs,round(sum(eff*whrs)/sum(whrs),1) leff,sum(athrs) latthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) efhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$lstartdate."' and '".$lenddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$lstartdate."' and '".$lenddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) lprd on pprd.ebno=lprd.ebno
        ) curdet left join 
        (
        select compid cmpid,ebno,round(sum(whrs*eff)/sum(whrs),2) as compeff,ifnull(round(sum(owhrs*oeff)/sum(owhrs),1),'NR') as otheff from ( 
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb
        where production_a1>0
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb
        where production_a2>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        UNION ALL
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,
        efficiency_b1 eff from cuts_jugar_buff_1 cjb
        where production_b1>0
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb
        where production_b2>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        UNION ALL
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,
        efficiency_c eff from cuts_jugar_buff_1 cjb
        where production_c>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='Hessian' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        ) oth group by compid,ebno
        ) othdet on curdet.ebno=othdet.ebno
         join worker_master wm on curdet.ebno=wm.eb_no and curdet.compid=wm.company_id
        left join (
            select ebno,GROUP_CONCAT(DISTINCT mech_code SEPARATOR '/') mcnos,max(substr(spell,1,1)) shift from ( 
                select lstlom.* from (
                select g.* from (
                select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
                efficiency_a1 eff from cuts_jugar_buff_1 cjb  
                where production_a1>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
                where production_a2>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
                where production_b1>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
                where production_b2>0  
                union all
                select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
                where production_c>0 
                ) g 
                left join EMPMILL12.tbl_loom_master_other_details tlmod on g.loom_id=tlmod.mechine_id
                left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
                where  loom_side='".$lmtype."' and 
                loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
                ) lstlom  join
                (		SELECT 
                da.eb_no,
                da.attendance_date AS attendance_date,
                da.spell
            FROM 
                daily_attendance da 
            JOIN (
                SELECT 
                    eb_no,
                    MAX(attendance_date) AS max_attendance_date
                FROM 
                    daily_attendance
                WHERE 
                    attendance_date BETWEEN '".$startdate."' and '".$enddate."' and company_id =".$compid." 
                    AND is_active = 1 
                    AND worked_designation_id IN (".$desgid.")
                GROUP BY 
                    eb_no
            ) AS max_dates ON da.eb_no = max_dates.eb_no AND da.attendance_date = max_dates.max_attendance_date
            WHERE 
                da.attendance_date BETWEEN '".$startdate."' and '".$enddate."' and company_id =".$compid."
                AND da.is_active = 1 
                AND da.worked_designation_id IN (".$desgid.")
                 ) att on lstlom.ebno=att.eb_no and lstlom.loom_date=att.attendance_date and lstlom.spell=att.spell
                ) k left join
                mechine_master mm on k.loom_id=mm.mechine_id
                group by ebno
                ) ebloom on curdet.ebno=ebloom.ebno
        order by eff";


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
        $sql="select tpp.*,DATE_SUB(date_from, INTERVAL 1 DAY) AS previous_date_from  from EMPMILL12.tbl_report_period 
        tpp where '".$pers['to_date']."' between date_from and date_to
        and company_id=".$pers['company'];
//        echo $sql;
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
  
  
        $sql="select curdet.ebno,active,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,
        atthrs,ceff,catthrs,leff,latthrs,othdet.otheff,mcnos,shift,prod,eff
        from (
        select pprd.compid,pprd.ebno,pprd.prod,pprd.pwhrs,pprd.eff,pprd.atthrs,cprd.cwhrs,cprd.ceff,cprd.catthrs,lprd.lwhrs,lprd.leff,lprd.latthrs from (
        select compid,
        ebno,sum(prod) prod,sum(whrs) pwhrs,round(sum(eff*whrs)/sum(whrs),1) eff,sum(athrs) atthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) effhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$startdate."' and '".$enddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) pprd left join 
        (
        select compid,
        ebno,sum(prod) cprod,sum(whrs) cwhrs,round(sum(eff*whrs)/sum(whrs),1) ceff,sum(athrs) catthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) efhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$cstartdate."' and '".$enddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$cstartdate."' and '".$cenddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) cprd on pprd.ebno=cprd.ebno
        left join 
        (
        select compid,
        ebno,sum(prod) lprod,sum(whrs) lwhrs,round(sum(eff*whrs)/sum(whrs),1) leff,sum(athrs) latthrs
        from ( 
        select dprd.*,att.athrs from 
        (
        select compid,
        ebno,loom_date,spell,sum(prod) prod,sum(whrs) whrs,round(sum(eff*whrs)/sum(whrs),2) eff,sum(eff*whrs) efhr
        from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) dprd left join EMPMILL12.tbl_loom_master_other_details tlmod on dprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$lstartdate."' and '".$lenddate."' and compid =".$compid."
        group by compid,
        ebno,loom_date,spell
        ) dprd left join
        (select company_id,eb_no,attendance_date, spell,sum(working_hours-idle_hours) athrs from daily_attendance da where attendance_date 
        between '".$lstartdate."' and '".$lenddate."' and company_id =".$compid." and is_active =1 and worked_designation_id in (".$desgid.")
        group by company_id,eb_no,attendance_date ,spell
        ) att on dprd.compid=att.company_id and dprd.ebno=att.eb_no and dprd.loom_date=att.attendance_date and dprd.spell=att.spell
        ) g group by compid,
        ebno
        ) lprd on pprd.ebno=lprd.ebno
        ) curdet left join 
        (
        select compid cmpid,ebno,round(sum(whrs*eff)/sum(whrs),2) as compeff,ifnull(round(sum(owhrs*oeff)/sum(owhrs),1),'NR') as otheff from ( 
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
        efficiency_a1 eff from cuts_jugar_buff_1 cjb
        where production_a1>0
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb
        where production_a2>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        UNION ALL
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,
        efficiency_b1 eff from cuts_jugar_buff_1 cjb
        where production_b1>0
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb
        where production_b2>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
        where production_c>0 
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        UNION ALL
        select asfteff.loom_date,asfteff.compid,asfteff.ebno,asfteff.loom_id,asfteff.shift,asfteff.whrs,asfteff.eff,ifnull(oasfteff.owhrs,0) owhrs,ifnull(oasfteff.oeff,0) oeff from (
        select loom_date,compid,loom_id,shift,ebno,sum(whrs) whrs,round(sum(whrs*eff)/sum(whrs),2) eff  from (
        select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,
        efficiency_c eff from cuts_jugar_buff_1 cjb
        where production_c>0
        ) ashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on ashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='Hessian' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id,shift,ebno
        ) asfteff left join
        (
        select loom_date,compid,loom_id,round(sum(eff*whrs)/sum(whrs),2) oeff,sum(whrs) owhrs from (
        select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,efficiency_a1 eff from cuts_jugar_buff_1 cjb  
        where production_a1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
        where production_a2>0  
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
        where production_b1>0 
        union all
        select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
        where production_b2>0  
        ) eashftprd 
        left join EMPMILL12.tbl_loom_master_other_details tlmod on eashftprd.loom_id=tlmod.mechine_id
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
        where  loom_side='".$lmtype."' and 
        loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
        group by loom_date,compid,loom_id
        ) oasfteff on asfteff.loom_date=oasfteff.loom_date and asfteff.loom_id=oasfteff.loom_id  
        ) oth group by compid,ebno
        ) othdet on curdet.ebno=othdet.ebno
         join worker_master wm on curdet.ebno=wm.eb_no and curdet.compid=wm.company_id
        left join (
            select ebno,GROUP_CONCAT(DISTINCT mech_code SEPARATOR '/') mcnos,max(substr(spell,1,1)) shift from ( 
                select lstlom.* from (
                select g.* from (
                select loom_date,company_id compid,loom_id,ticket_no_a1 ebno,'A1' spell,'A' Shift,quality_code_a1 qcode,production_a1 prod,working_hrs_a1 whrs,
                efficiency_a1 eff from cuts_jugar_buff_1 cjb  
                where production_a1>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_a2 ebno,'A2' spell,'A' Shift,quality_code_a2 qcode,production_a2 prod,working_hrs_a2 whrs,efficiency_a2 eff from cuts_jugar_buff_1 cjb  
                where production_a2>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_b1 ebno,'B1' spell,'B' Shift,quality_code_b1 qcode,production_b1 prod,working_hrs_b1 whrs,efficiency_b1 eff from cuts_jugar_buff_1 cjb  
                where production_b1>0 
                union all
                select loom_date,company_id compid,loom_id,ticket_no_b2 ebno,'B2' spell,'B' Shift,quality_code_b2 qcode,production_b2 prod,working_hrs_b2 whrs,efficiency_b2 eff from cuts_jugar_buff_1 cjb  
                where production_b2>0  
                union all
                select loom_date,company_id compid,loom_id,ticket_no_c ebno,'C' spell,'C' Shift,quality_code_c qcode,production_c prod,working_hrs_c whrs,efficiency_c eff from cuts_jugar_buff_1 cjb  
                where production_c>0 
                ) g 
                left join EMPMILL12.tbl_loom_master_other_details tlmod on g.loom_id=tlmod.mechine_id
                left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id=tlt.loom_type_id 
                where  loom_side='".$lmtype."' and 
                loom_date between '".$startdate."' and '".$enddate."' and compid =".$compid."
                ) lstlom  join
                (		SELECT 
                da.eb_no,
                da.attendance_date AS attendance_date,
                da.spell
            FROM 
                daily_attendance da 
            JOIN (
                SELECT 
                    eb_no,
                    MAX(attendance_date) AS max_attendance_date
                FROM 
                    daily_attendance
                WHERE 
                    attendance_date BETWEEN '".$startdate."' and '".$enddate."' and company_id =".$compid." 
                    AND is_active = 1 
                    AND worked_designation_id IN (".$desgid.")
                GROUP BY 
                    eb_no
            ) AS max_dates ON da.eb_no = max_dates.eb_no AND da.attendance_date = max_dates.max_attendance_date
            WHERE 
                da.attendance_date BETWEEN '".$startdate."' and '".$enddate."' and company_id =".$compid."
                AND da.is_active = 1 
                AND da.worked_designation_id IN (".$desgid.")
                 ) att on lstlom.ebno=att.eb_no and lstlom.loom_date=att.attendance_date and lstlom.spell=att.spell
                ) k left join
                mechine_master mm on k.loom_id=mm.mechine_id
                group by ebno
                ) ebloom on curdet.ebno=ebloom.ebno
        order by eff";


        

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
	
