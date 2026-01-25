<?php
class Spinner_performance_report_model extends CI_Model
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
        tpp where '".$from_date."' between date_from and date_to
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
    
             if ($companyId==2)  { $desgid= '50,195,213,241,242,252'; }
             if ($companyId==1)  { $desgid= '942,1113'; }
             
             if ($Source==8) {($lmtype='Fine');}
             if ($Source==9) {($lmtype='Coarse');}

            $sql="	select
            sum(no_of_doff) dnoofdoff,
            sum(prod) dprod,
            sum(prod100) dprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) dstddoffwt,
            sum(mcwhrs) dmcwhrs,
            sum(atthrs) datthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) deff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) davgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) dactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) dactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source;

            $sql="select 
            sum(no_of_doff) dnoofdoff,
            sum(net_weight) dprod,
            sum(prod100) dprod100,
            round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) dstddoffwt,
            sum(mchours) dmcwhrs,
            sum(working_hours) datthrs,
            round(sum(net_weight)/ sum(prod100)* 100 , 1) deff,
            round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) davgnodoff , 
            round(sum(no_of_doff)/ sum(mchours)* 8, 1) dactavnogdoff ,
            round((sum(net_weight)/ sum(no_of_doff)), 1) dactacgdoffwt
			from  EMPMILL12.tbl_doffdata_all_calc tdac
			left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
			left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
			where doffdate between '".$startdate."' and '".$enddate."'
			and company_id=".$compid." and tlt.loom_type_id =".$Source;



            $query=$this->db->query($sql);
                $row = $query->row();
                $tactavnogdoff=$row->dactavnogdoff;
                $tactacgdoffwt=$row->dactacgdoffwt;
 
 
                //               echo $sql;
//         echo 'allavg=='.$tactavnogdoff.'---'.$tactacgdoffwt;

  
        $sql="select curprd.*,curfne.*,lfne.*,othprd.*,active,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,
        $tactavnogdoff tactdof,$tactacgdoffwt tactdofwt
        from (
            select
            ebno,
            eb_id,
            sum(no_of_doff) dnoofdoff,
            sum(prod) dprod,
            sum(prod100) dprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) dstddoffwt,
            sum(mcwhrs) dmcwhrs,
            sum(atthrs) datthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) deff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) davgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) dactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) dactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id ) curprd left JOIN 
            (
            select
            ebno cebno,eb_id cebid,
            sum(no_of_doff) cnoofdoff,
            sum(prod) cprod,
            sum(prod100) cprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) cstddoffwt,
            sum(mcwhrs) cmcwhrs,
            sum(atthrs) catthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) ceff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) cavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) cactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) cactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$cstartdate."' and '".$cenddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id
            ) curfne on curprd.eb_id=curfne.cebid
            left join (
            select
            ebno lebno,eb_id lebid,
            sum(no_of_doff) lnoofdoff,
            sum(prod) lprod,
            sum(prod100) lprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) lstddoffwt,
            sum(mcwhrs) lmcwhrs,
            sum(atthrs) latthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) leff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) lavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) lactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) lactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$lstartdate."' and '".$lenddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id
            ) lfne on curprd.eb_id=lfne.lebid
        left join (
        select web_id,sum(wnoofdoff) wnoofdoff,sum(wprod) wprod,sum(wprod100) wprod100,round(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs), 1) wstddoffwt,
        sum(wmcwhrs) wmcwhrs,sum(watthrs) watthrs,	round(sum(wprod)/ sum(wprod100)* 100 , 1) weff,
            round((sum(wprod100)/(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs)))/ sum(wmcwhrs)* 8, 1) wavgnodoff ,
            round(sum(wnoofdoff)/ sum(wmcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(wprod)/ sum(wnoofdoff)), 1) wactacgdoffwt,
         sum(onoofdoff)	onoofdoff,sum(oprod) oprod,sum(oprod100) oprod100,round(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs), 1)  ostddoffwt,
        sum(omcwhrs) omcwhrs,sum(oatthrs) oatthrs,	round(sum(oprod)/ sum(oprod100)* 100 , 1) oeff,
            round((sum(oprod100)/(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs)))/ sum(omcwhrs)* 8, 1) oavgnodoff ,
            round(sum(onoofdoff)/ sum(omcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(oprod)/ sum(onoofdoff)), 1) oactacgdoffwt from (
            select ebprd.*,oprd.* from (
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='A'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'A'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        union all
        select ebprd.*,oprd.* from (	
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='B'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'B'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        union all
        select ebprd.*,oprd.* from (	
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='C'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'C'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        ) g group by web_id
        ) othprd on othprd.web_id=curprd.eb_id
        left join worker_master wm on curprd.eb_id=wm.eb_id
        order by deff
        ";

        $sql=" select curprd.*,lfneprd.*,cfneprd.*,othprd.*,wm.eb_no ebno,concat(sftmc.shift,' : ',mcnos) sftmc,
        concat(wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) wname,
        $tactavnogdoff tactdof,$tactacgdoffwt tactdofwt from (
        select 
        eb_id,
        sum(no_of_doff) dnoofdoff,
        sum(net_weight) dprod,
        sum(prod100) dprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) dstddoffwt,
        sum(mchours) dmcwhrs,
        sum(working_hours) datthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) deff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) davgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) dactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) dactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." 
        group by  eb_id
        ) curprd
        left join 
        (
        select 
        eb_id leb_id,
        sum(no_of_doff) lnoofdoff,
        sum(net_weight) lprod,
        sum(prod100) lprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) lstddoffwt,
        sum(mchours) lmcwhrs,
        sum(working_hours) latthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) leff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) lavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) lactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) lactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$lstartdate."' and '".$lenddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source."  
        group by  eb_id
        ) lfneprd on lfneprd.leb_id=curprd.eb_id 
        left join 
        (
        select 
        eb_id ceb_id,
        sum(no_of_doff) cnoofdoff,
        sum(net_weight) cprod,
        sum(prod100) cprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) cstddoffwt,
        sum(mchours) cmcwhrs,
        sum(working_hours) catthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) ceff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) cavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) cactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) cactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$cstartdate."' and '".$cenddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source."
        group by  eb_id
        ) cfneprd on cfneprd.ceb_id=curprd.eb_id 
        left join 
        (
        select web_id,sum(wnoofdoff) wnoofdoff,sum(wprod) wprod,sum(wprod100) wprod100,round(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs), 1) wstddoffwt,
        sum(wmcwhrs) wmcwhrs,sum(watthrs) watthrs,	round(sum(wprod)/ sum(wprod100)* 100 , 1) weff,
        round((sum(wprod100)/(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs)))/ sum(wmcwhrs)* 8, 1) wavgnodoff ,
        round(sum(wnoofdoff)/ sum(wmcwhrs)* 8, 1) wactavnogdoff ,
        round((sum(wprod)/ sum(wnoofdoff)), 1) wactacgdoffwt,
         sum(onoofdoff)	onoofdoff,sum(oprod) oprod,sum(oprod100) oprod100,round(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs), 1)  ostddoffwt,
        sum(omcwhrs) omcwhrs,sum(oatthrs) oatthrs,	round(sum(oprod)/ sum(oprod100)* 100 , 1) oeff,
        round((sum(oprod100)/(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs)))/ sum(omcwhrs)* 8, 1) oavgnodoff ,
        round(sum(onoofdoff)/ sum(omcwhrs)* 8, 1) oactavnogdoff ,
        round((sum(oprod)/ sum(onoofdoff)), 1) oactacgdoffwt from (
        select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='A' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'A'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        union  all
    select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='B' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'B'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        union all
                select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='C' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'C'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        ) g group by web_id
        ) othprd on othprd.web_id=curprd.eb_id
        left join (	select tdac.eb_id,doffdate,substr(spell,1,1) shift,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos from  (
			select eb_id,max(doffdate) mxdate,max(spell) mxspell from 
			EMPMILL12.tbl_doffdata_all_calc tdac
			left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
			left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
			where doffdate between '".$startdate."' and '".$enddate."'
			and company_id=".$compid." and tlt.loom_type_id =".$Source."
			group by eb_id 
			) g left join EMPMILL12.tbl_doffdata_all_calc tdac
			on g.eb_id=tdac.eb_id and g.mxdate=tdac.doffdate and g.mxspell=tdac.spell
			join mechine_master mm  on tdac.mc_id=mm.mechine_id
			group by  tdac.eb_id,doffdate,substr(spell,1,1) ) sftmc
			on sftmc.eb_id=curprd.eb_id 
            left join worker_master wm on wm.eb_id=curprd.eb_id
        order by deff        ";    



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
        tpp where '".$pers['from_date']."' between date_from and date_to
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
    
             if ($pers['company']==2)  { $desgid= '50,195,213,241,242,252'; }
             if ($pers['company']==1)  { $desgid= '942,1113'; }

   


             if ($Source==8) {($lmtype='Fine');}
             if ($Source==9) {($lmtype='Coarse');}

             $sql="select 
             sum(no_of_doff) dnoofdoff,
             sum(net_weight) dprod,
             sum(prod100) dprod100,
             round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) dstddoffwt,
             sum(mchours) dmcwhrs,
             sum(working_hours) datthrs,
             round(sum(net_weight)/ sum(prod100)* 100 , 1) deff,
             round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) davgnodoff , 
             round(sum(no_of_doff)/ sum(mchours)* 8, 1) dactavnogdoff ,
             round((sum(net_weight)/ sum(no_of_doff)), 1) dactacgdoffwt
             from  EMPMILL12.tbl_doffdata_all_calc tdac
             left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
             left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
             where doffdate between '".$startdate."' and '".$enddate."'
             and company_id=".$compid." and tlt.loom_type_id =".$Source;
 
             $query=$this->db->query($sql);
                $row = $query->row();
                $tactavnogdoff=$row->dactavnogdoff;
                $tactacgdoffwt=$row->dactacgdoffwt;
 //               echo $sql;
//         echo 'allavg=='.$tactavnogdoff.'---'.$tactacgdoffwt;

  
        $sql="select curprd.*,curfne.*,lfne.*,othprd.*,active,concat(wm.worker_name,ifnull(wm.middle_name,''),' ',ifnull(last_name,'')) wname,
        $tactavnogdoff tactdof,$tactacgdoffwt tactdofwt
        from (
            select
            ebno,
            eb_id,
            sum(no_of_doff) dnoofdoff,
            sum(prod) dprod,
            sum(prod100) dprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) dstddoffwt,
            sum(mcwhrs) dmcwhrs,
            sum(atthrs) datthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) deff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) davgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) dactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) dactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id ) curprd left JOIN 
            (
            select
            ebno cebno,eb_id cebid,
            sum(no_of_doff) cnoofdoff,
            sum(prod) cprod,
            sum(prod100) cprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) cstddoffwt,
            sum(mcwhrs) cmcwhrs,
            sum(atthrs) catthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) ceff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) cavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) cactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) cactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$cstartdate."' and '".$cenddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id
            ) curfne on curprd.eb_id=curfne.cebid
            left join (
            select
            ebno lebno,eb_id lebid,
            sum(no_of_doff) lnoofdoff,
            sum(prod) lprod,
            sum(prod100) lprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) lstddoffwt,
            sum(mcwhrs) lmcwhrs,
            sum(atthrs) latthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) leff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) lavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) lactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) lactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$lstartdate."' and '".$lenddate."'
            and compid =".$compid."
            and loom_type_id=".$Source."
        group by
            ebno,eb_id
            ) lfne on curprd.eb_id=lfne.lebid
        left join (
        select web_id,sum(wnoofdoff) wnoofdoff,sum(wprod) wprod,sum(wprod100) wprod100,round(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs), 1) wstddoffwt,
        sum(wmcwhrs) wmcwhrs,sum(watthrs) watthrs,	round(sum(wprod)/ sum(wprod100)* 100 , 1) weff,
            round((sum(wprod100)/(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs)))/ sum(wmcwhrs)* 8, 1) wavgnodoff ,
            round(sum(wnoofdoff)/ sum(wmcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(wprod)/ sum(wnoofdoff)), 1) wactacgdoffwt,
         sum(onoofdoff)	onoofdoff,sum(oprod) oprod,sum(oprod100) oprod100,round(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs), 1)  ostddoffwt,
        sum(omcwhrs) omcwhrs,sum(oatthrs) oatthrs,	round(sum(oprod)/ sum(oprod100)* 100 , 1) oeff,
            round((sum(oprod100)/(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs)))/ sum(omcwhrs)* 8, 1) oavgnodoff ,
            round(sum(onoofdoff)/ sum(omcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(oprod)/ sum(onoofdoff)), 1) oactacgdoffwt from (
            select ebprd.*,oprd.* from (
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='A'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'A'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        union all
        select ebprd.*,oprd.* from (	
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='B'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'B'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        union all
        select ebprd.*,oprd.* from (	
            select
            doffdate wdoffdate,
            ebno webno,
            eb_id web_id,
            mechine_id wmechine_id,
            sum(no_of_doff) wnoofdoff,
            sum(prod) wprod,
            sum(prod100) wprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) wstddoffwt,
            sum(mcwhrs) wmcwhrs,
            sum(atthrs) watthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) weff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) wavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) wactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) wactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)='C'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) ebprd 	
        left join 
        (
            select
            doffdate odoffdate,
            mechine_id omechine_id,
            sum(no_of_doff) onoofdoff,
            sum(prod) oprod,
            sum(prod100) oprod100,
            round(sum(stdodffwt * mcwhrs)/ sum(mcwhrs), 1) ostddoffwt,
            sum(mcwhrs) omcwhrs,
            sum(atthrs) oatthrs,
            round(sum(prod)/ sum(prod100)* 100 , 1) oeff,
            round((sum(prod100)/(sum(stdodffwt * mcwhrs)/ sum(mcwhrs)))/ sum(mcwhrs)* 8, 1) oavgnodoff ,
            round(sum(no_of_doff)/ sum(mcwhrs)* 8, 1) oactavnogdoff ,
            round((sum(prod)/ sum(no_of_doff)), 1) oactacgdoffwt
        from
            EMPMILL12.view_proc_daily_doff_details vpddd 
            where doffdate between '".$startdate."' and '".$enddate."'
            and compid =".$compid."
            and loom_type_id=".$Source." and substr(spell,1,1)<>'C'
        group by
            ebno,eb_id,mechine_id,doffdate 	
        ) oprd on ebprd.wmechine_id=oprd.omechine_id and ebprd.wdoffdate=oprd.odoffdate
        ) g group by web_id
        ) othprd on othprd.web_id=curprd.eb_id
        left join worker_master wm on curprd.eb_id=wm.eb_id
        order by deff
        ";

        $sql=" select curprd.*,lfneprd.*,cfneprd.*,othprd.*,wm.eb_no ebno,concat(sftmc.shift,' : ',mcnos) sftmc,
        concat(wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) wname,
        $tactavnogdoff tactdof,$tactacgdoffwt tactdofwt from (
        select 
        eb_id,
        sum(no_of_doff) dnoofdoff,
        sum(net_weight) dprod,
        sum(prod100) dprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) dstddoffwt,
        sum(mchours) dmcwhrs,
        sum(working_hours) datthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) deff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) davgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) dactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) dactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." 
        group by  eb_id
        ) curprd
        left join 
        (
        select 
        eb_id leb_id,
        sum(no_of_doff) lnoofdoff,
        sum(net_weight) lprod,
        sum(prod100) lprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) lstddoffwt,
        sum(mchours) lmcwhrs,
        sum(working_hours) latthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) leff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) lavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) lactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) lactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$lstartdate."' and '".$lenddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source."  
        group by  eb_id
        ) lfneprd on lfneprd.leb_id=curprd.eb_id 
        left join 
        (
        select 
        eb_id ceb_id,
        sum(no_of_doff) cnoofdoff,
        sum(net_weight) cprod,
        sum(prod100) cprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) cstddoffwt,
        sum(mchours) cmcwhrs,
        sum(working_hours) catthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) ceff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) cavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) cactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) cactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$cstartdate."' and '".$cenddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source."
        group by  eb_id
        ) cfneprd on cfneprd.ceb_id=curprd.eb_id 
        left join 
        (
        select web_id,sum(wnoofdoff) wnoofdoff,sum(wprod) wprod,sum(wprod100) wprod100,round(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs), 1) wstddoffwt,
        sum(wmcwhrs) wmcwhrs,sum(watthrs) watthrs,	round(sum(wprod)/ sum(wprod100)* 100 , 1) weff,
        round((sum(wprod100)/(sum(wstddoffwt * wmcwhrs)/ sum(wmcwhrs)))/ sum(wmcwhrs)* 8, 1) wavgnodoff ,
        round(sum(wnoofdoff)/ sum(wmcwhrs)* 8, 1) wactavnogdoff ,
        round((sum(wprod)/ sum(wnoofdoff)), 1) wactacgdoffwt,
         sum(onoofdoff)	onoofdoff,sum(oprod) oprod,sum(oprod100) oprod100,round(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs), 1)  ostddoffwt,
        sum(omcwhrs) omcwhrs,sum(oatthrs) oatthrs,	round(sum(oprod)/ sum(oprod100)* 100 , 1) oeff,
        round((sum(oprod100)/(sum(ostddoffwt * omcwhrs)/ sum(omcwhrs)))/ sum(omcwhrs)* 8, 1) oavgnodoff ,
        round(sum(onoofdoff)/ sum(omcwhrs)* 8, 1) oactavnogdoff ,
        round((sum(oprod)/ sum(onoofdoff)), 1) oactacgdoffwt from (
        select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='A' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'A'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        union  all
    select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='B' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'B'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        union all
                select wprd.*,oprd.* from (
        select 
        doffdate wdoffdate,
        eb_id web_id,
        mc_id wmc_id,
        sum(no_of_doff) wnoofdoff,
        sum(net_weight) wprod,
        sum(prod100) wprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) wstddoffwt,
        sum(mchours) wmcwhrs,
        sum(working_hours) watthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) weff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) wavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) wactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) wactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)='C' 
        group by  eb_id,mc_id,doffdate 
        ) wprd left join             
        (select 
        doffdate odoffdate,
        mc_id omc_id,
        sum(no_of_doff) onoofdoff,
        sum(net_weight) oprod,
        sum(prod100) oprod100,
        round(sum(std_weight_per_doff * mchours)/ sum(mchours), 1) ostddoffwt,
        sum(mchours) omcwhrs,
        sum(working_hours) oatthrs,
        round(sum(net_weight)/ sum(prod100)* 100 , 1) oeff,
        round((sum(prod100)/(sum(std_weight_per_doff * mchours)/ sum(mchours)))/ sum(mchours)* 8, 1) oavgnodoff , 
        round(sum(no_of_doff)/ sum(mchours)* 8, 1) oactavnogdoff ,
        round((sum(net_weight)/ sum(no_of_doff)), 1) oactacgdoffwt
        from  EMPMILL12.tbl_doffdata_all_calc tdac
        left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
        left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
        where doffdate between '".$startdate."' and '".$enddate."'
        and company_id=".$compid." and tlt.loom_type_id =".$Source." and SUBSTR(spell,1,1)<>'C'
        group by  mc_id,doffdate 
        ) oprd on wprd.wmc_id=oprd.omc_id and wprd.wdoffdate=oprd.odoffdate 
        ) g group by web_id
        ) othprd on othprd.web_id=curprd.eb_id
        left join (	select tdac.eb_id,doffdate,substr(spell,1,1) shift,GROUP_CONCAT(DISTINCT mechine_name SEPARATOR '/') mcnos from  (
			select eb_id,max(doffdate) mxdate,max(spell) mxspell from 
			EMPMILL12.tbl_doffdata_all_calc tdac
			left join EMPMILL12.tbl_loom_master_other_details tlmod on tlmod.mechine_id =tdac.mc_id 
			left join EMPMILL12.tbl_loom_type tlt on tlmod.loom_type_id  =tlt.loom_type_id 
			where doffdate between '".$startdate."' and '".$enddate."'
			and company_id=".$compid." and tlt.loom_type_id =".$Source."
			group by eb_id 
			) g left join EMPMILL12.tbl_doffdata_all_calc tdac
			on g.eb_id=tdac.eb_id and g.mxdate=tdac.doffdate and g.mxspell=tdac.spell
			join mechine_master mm  on tdac.mc_id=mm.mechine_id
			group by  tdac.eb_id,doffdate,substr(spell,1,1) ) sftmc
			on sftmc.eb_id=curprd.eb_id 
            left join worker_master wm on wm.eb_id=curprd.eb_id
        order by deff        ";    

        

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
	
