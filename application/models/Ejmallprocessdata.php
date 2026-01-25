<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ejmallprocessdata extends CI_Model {


    public function oattprdprocess($periodfromdate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
        $sql="update EMPMILL12.tbl_daily_cash_outsider_payment_production set is_active=0 where prod_date 
        = '$periodfromdate'  and is_active=1 ";
        $this->db->query($sql);

        $spg9=261;
        $spg13=363;
        $spg16=394;

        $wnd9=277; 
        $wnd13=330;
        $wnd16=362;

//      picecr


        //     	--- Piecer		
	
        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
    select eb_id,deptid,desigid,avgprd,act,ratetype,attendance_date,shift,tgprod,
    CASE 
        WHEN ratetype = 1 
             THEN 520/8 * (whrs + nhrs) 
             ELSE 390/8 * (whrs + nhrs) 
    END AS prodamt,
        CASE 
        WHEN ratetype = 1 
             THEN 520 
             ELSE 390 
    END AS prodrt from (
			SELECT 
    eb_id,
    deptid,
    desigid,
    avgprd,
    act,
    ratetype,
    attendance_date,
    shift,
    tgprod,whrs,case when shift='C' and whrs=7.5 then 0.5 else 0 end nhrs
FROM ( 
    SELECT 
        eb_id,
        deptid, 
        desigid,
        SUM(avgprd) AS avgprd,
        1 AS act,
        CASE 
            WHEN (SUM(avgprd*phrs) / SUM(phrs) * 8) >= (SUM(stgprd*phrs) / SUM(phrs) * 8) 
                 THEN 1 ELSE 0 
        END AS ratetype,
        attendance_date,
        SUBSTR(spell,1,1) AS shift,
        SUM(stgprd) AS tgprod,
        SUM(whrs) AS whrs
    FROM (
    select k.*,working_hours-idle_hours whrs from (
    SELECT 
            eb_id,
            eb_no,
            attendance_date,
            spell,
            deptid,
            desigid,
            SUM(net_weight) AS netwet,
            SUM(net_weight) / SUM(phrs) * 8 AS avgprd,
            SUM(stgprd) / SUM(phrs) * 8 AS stgprd,
            SUM(phrs) AS phrs
        FROM (   
            SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                dea.mc_id,
                mm.frame_no,
                tdac.net_weight,
                wqm.yarn_count,
                tdac.working_hours AS phrs,
                CASE 
                    WHEN wqm.yarn_count <= 12.99 
                         THEN $spg9/8 * tdac.working_hours
                    WHEN wqm.yarn_count BETWEEN 13 AND 15.99 
                         THEN $spg13/8 * tdac.working_hours
                    WHEN wqm.yarn_count >= 16 
                         THEN $spg16/8 * tdac.working_hours 
                END AS stgprd
            FROM daily_attendance da 	
            LEFT JOIN daily_ebmc_attendance dea 
                   ON da.daily_atten_id = dea.daily_atten_id  
                  AND dea.is_active = 1
            LEFT JOIN mechine_master mm 
                   ON mm.mechine_id = dea.mc_id 
            LEFT JOIN EMPMILL12.tbl_doffdata_all_calc tdac 
                   ON tdac.frameno = mm.frame_no 
                  AND tdac.doffdate = da.attendance_date 
                  AND da.spell = tdac.spell 
            LEFT JOIN weaving_quality_master wqm 
                   ON wqm.quality_id = tdac.quality_id
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (51,196) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
        ) g 
        GROUP BY eb_id, eb_no, attendance_date, spell, deptid, desigid
        ) k	
    LEFT JOIN daily_attendance da 
           ON da.eb_id = k.eb_id 
          AND da.attendance_date = k.attendance_date 
          AND da.spell = k.spell 
          AND da.is_active = 1
  ) h        
    GROUP BY 
        eb_id, deptid, desigid, attendance_date, substr(spell,1,1)
        ) v  ) p";
//echo $sql;
        $this->db->query($sql);


        //     	--- sliver feeder		
	
        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
    select eb_id,deptid,desigid,avgprd,act,ratetype,attendance_date,shift,tgprod,
    CASE 
        WHEN ratetype = 1 
             THEN 520/8 * (whrs + nhrs) 
             ELSE 390/8 * (whrs + nhrs) 
    END AS prodamt,
        CASE 
        WHEN ratetype = 1 
             THEN 520 
             ELSE 390 
    END AS prodrt from (
			SELECT 
    eb_id,
    deptid,
    desigid,
    avgprd,
    act,
    ratetype,
    attendance_date,
    shift,
    tgprod,whrs,case when shift='C' and whrs=7.5 then 0.5 else 0 end nhrs
FROM ( 
    SELECT 
        eb_id,
        deptid, 
        desigid,
        SUM(avgprd) AS avgprd,
        1 AS act,
        CASE 
            WHEN (SUM(avgprd*phrs) / SUM(phrs) * 8) >= (SUM(stgprd*phrs) / SUM(phrs) * 8) 
                 THEN 1 ELSE 0 
        END AS ratetype,
        attendance_date,
        SUBSTR(spell,1,1) AS shift,
        SUM(stgprd) AS tgprod,
        SUM(whrs) AS whrs
    FROM (
    select k.*,working_hours-idle_hours whrs from (
    SELECT 
            eb_id,
            eb_no,
            attendance_date,
            spell,
            deptid,
            desigid,
            SUM(net_weight) AS netwet,
            SUM(net_weight) / SUM(phrs) * 8 AS avgprd,
            SUM(stgprd) / SUM(phrs) * 8 AS stgprd,
            SUM(phrs) AS phrs
        FROM (   
            SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                dea.mc_id,
                mm.frame_no,
                tdac.net_weight,
                wqm.yarn_count,
                tdac.working_hours AS phrs,
                CASE 
                    WHEN wqm.yarn_count <= 12.99 
                         THEN $spg9/8 * tdac.working_hours
                    WHEN wqm.yarn_count BETWEEN 13 AND 15.99 
                         THEN $spg13/8 * tdac.working_hours
                    WHEN wqm.yarn_count >= 16 
                         THEN $spg16/8 * tdac.working_hours 
                END AS stgprd
            FROM daily_attendance da 	
            LEFT JOIN daily_ebmc_attendance dea 
                   ON da.daily_atten_id = dea.daily_atten_id  
                  AND dea.is_active = 1
            LEFT JOIN mechine_master mm 
                   ON mm.mechine_id = dea.mc_id 
            LEFT JOIN EMPMILL12.tbl_doffdata_all_calc tdac 
                   ON tdac.frameno = mm.frame_no 
                  AND tdac.doffdate = da.attendance_date 
                  AND da.spell = tdac.spell 
            LEFT JOIN weaving_quality_master wqm 
                   ON wqm.quality_id = tdac.quality_id
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (52,197) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
        ) g 
        GROUP BY eb_id, eb_no, attendance_date, spell, deptid, desigid
        ) k	
    LEFT JOIN daily_attendance da 
           ON da.eb_id = k.eb_id 
          AND da.attendance_date = k.attendance_date 
          AND da.spell = k.spell 
          AND da.is_active = 1
  ) h        
    GROUP BY 
        eb_id, deptid, desigid, attendance_date, substr(spell,1,1)
        ) v  ) p";
//echo $sql;
        $this->db->query($sql);
     

        //      ---  bobbin coolie

        $sql=" insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
    select eb_id,deptid,desigid,avgprd,act,ratetype,attendance_date,shift,tgprod,
    CASE 
        WHEN ratetype = 1 
             THEN 520/8 * (whrs + nhrs) 
             ELSE 390/8 * (whrs + nhrs) 
    END AS prodamt,    CASE 
        WHEN ratetype = 1 
             THEN 520 
             ELSE 390 
    END AS prodrt from (
			SELECT 
    eb_id,
    deptid,
    desigid,
    avgprd,
    act,
    ratetype,
    attendance_date,
    shift,
    tgprod,whrs,case when shift='C' and whrs=7.5 then 0.5 else 0 end nhrs
FROM ( 
    SELECT 
        eb_id,
        deptid, 
        desigid,
        SUM(avgprd) AS avgprd,
        1 AS act,
        CASE 
            WHEN (SUM(avgprd*phrs) / SUM(phrs) * 8) >= (SUM(stgprd*phrs) / SUM(phrs) * 8) 
                 THEN 1 ELSE 0 
        END AS ratetype,
        attendance_date,
        SUBSTR(spell,1,1) AS shift,
        SUM(stgprd) AS tgprod,
        SUM(whrs) AS whrs
    FROM (
    select k.*,working_hours-idle_hours whrs from (
    SELECT 
            eb_id,
            eb_no,
            attendance_date,
            spell,
            deptid,
            desigid,
            SUM(net_weight) AS netwet,
            SUM(net_weight) / SUM(phrs) * 8 AS avgprd,
            SUM(stgprd) / SUM(phrs) * 8 AS stgprd,
            SUM(phrs) AS phrs
        FROM (   
            SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                dea.mc_id,
                mm.frame_no,
                tdac.net_weight,
                wqm.yarn_count,
                tdac.working_hours AS phrs,
                CASE 
                    WHEN wqm.yarn_count <= 12.99 
                         THEN $spg9/8 * tdac.working_hours
                    WHEN wqm.yarn_count BETWEEN 13 AND 15.99 
                         THEN $spg13/8 * tdac.working_hours
                    WHEN wqm.yarn_count >= 16 
                         THEN $spg16/8 * tdac.working_hours 
                END AS stgprd
            FROM daily_attendance da 	
            LEFT JOIN daily_ebmc_attendance dea 
                   ON da.daily_atten_id = dea.daily_atten_id  
                  AND dea.is_active = 1
            LEFT JOIN mechine_master mm 
                   ON mm.mechine_id = dea.mc_id 
            LEFT JOIN EMPMILL12.tbl_doffdata_all_calc tdac 
                   ON tdac.frameno = mm.frame_no 
                  AND tdac.doffdate = da.attendance_date 
                  AND da.spell = tdac.spell 
            LEFT JOIN weaving_quality_master wqm 
                   ON wqm.quality_id = tdac.quality_id 
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (53,198) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
        ) g 
        GROUP BY eb_id, eb_no, attendance_date, spell, deptid, desigid
        ) k	
    LEFT JOIN daily_attendance da 
           ON da.eb_id = k.eb_id 
          AND da.attendance_date = k.attendance_date 
          AND da.spell = k.spell 
          AND da.is_active = 1
  ) h        
    GROUP BY 
        eb_id, deptid, desigid, attendance_date, substr(spell,1,1)
        ) v  ) p";
        $this->db->query($sql);
       
        
    //    --- spinner   ,sum(actprod) actprod

           $sql=" insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (
                  eb_id,dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
           select eb_id,deptid, desigid,round(sum(netwt),0) netwt,1 act,
		   case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   doffdate, shift,round(sum(tgprod),0) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 600/8*sum(whrs+nshrs)
           when (sum(netwt)<sum(tgprod) ) then 490/8*sum(whrs+nshrs) end rateprod,
            case when (sum(netwt)>=sum(tgprod) ) then 600
           when (sum(netwt)<sum(tgprod) ) then 490 end prodrt
           from (
           select eb_id,eb_no,empname,doffdate,substr(spell,1,1) shift,sum(actprod) actprod,sum(netwt)/sum(mchrs)*8 netwt,
		   sum(stgprod)/sum(mchrs)*8 stgprod,sum(stgprod)/sum(whrs)*8 tgprod,sum(mchrs) mchrs,desigid,sum(whrs) whrs,deptid,sum(nshrs) nshrs from (
           select tdac.eb_id,tdac.eb_no,empname,doffdate,tdac.spell,sum(net_weight) actprod,round(sum(net_weight),0) netwt,
           round(sum(stgprod),0) stgprod,
           round(sum(stgprod),0) tgprod,
           round(sum(net_weight)/sum(stgprod)*100,0) eff,da.worked_department_id  deptid,da.worked_designation_id  desigid,
           sum(da.working_hours-idle_hours) whrs,sum(mchrs) mchrs,
           case when (tdac.spell='C' and  sum(da.working_hours-idle_hours)=7.5) then 0.5 else 0 end nshrs from (
           select tdac.eb_id,wm.eb_no,concat( wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) empname,
           wqm.yarn_count,tdac.frameno ,tdac.net_weight, 
           case when wqm.yarn_count <12.99 then $spg9/8*tdac.working_hours
           when wqm.yarn_count between 13 and 15.99 then $spg13/8*tdac.working_hours
		   when wqm.yarn_count >=16 then $spg16/8*tdac.working_hours end stgprod,doffdate,spell,tdac.company_id,tdac.working_hours  mchrs
           from EMPMILL12.tbl_doffdata_all_calc tdac 
           left join worker_master wm on wm.eb_id =tdac.eb_id 
           left join weaving_quality_master wqm on wqm.quality_id =tdac.quality_id
                       left join tbl_hrms_ed_official_details theod on theod.eb_id=tdac.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
           where tdac.doffdate ='$periodfromdate'  and tdac.company_id =$comp and substr(eb_no,1,1)='T' and theod.contractor_id=10
           ) tdac    left join daily_attendance da on da.eb_id=tdac.eb_id and da.attendance_date=tdac.doffdate and da.spell=tdac.spell 
           and da.company_id=tdac.company_id  and da.is_active =1
           group by eb_id,eb_no,empname,doffdate,tdac.spell,da.worked_department_id,da.worked_designation_id 
          ) k group by eb_id,eb_no,empname,doffdate,substr(spell,1,1),desigid ,deptid
           ) g 
				group by eb_no,eb_id,deptid, desigid,doffdate,shift";
    $this->db->query($sql);

        //spining other
           $sql=" insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (
                  eb_id,dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
           select eb_id,deptid, desigid,round(sum(netwt),0) netwt,1 act,
		   case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   doffdate, shift,round(sum(tgprod),0) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 600/8*sum(whrs+nshrs)
           when (sum(netwt)<sum(tgprod) ) then 400/8*sum(whrs+nshrs) end rateprod,
            case when (sum(netwt)>=sum(tgprod) ) then 600
           when (sum(netwt)<sum(tgprod) ) then 400 end prodrt
           from (
           select eb_id,eb_no,empname,doffdate,substr(spell,1,1) shift,sum(actprod) actprod,sum(netwt)/sum(mchrs)*8 netwt,
		   sum(stgprod)/sum(mchrs)*8 stgprod,sum(stgprod)/sum(whrs)*8 tgprod,sum(mchrs) mchrs,desigid,sum(whrs) whrs,deptid,sum(nshrs) nshrs from (
           select tdac.eb_id,tdac.eb_no,empname,doffdate,tdac.spell,sum(net_weight) actprod,round(sum(net_weight),0) netwt,
           round(sum(stgprod),0) stgprod,
           round(sum(stgprod),0) tgprod,
           round(sum(net_weight)/sum(stgprod)*100,0) eff,da.worked_department_id  deptid,da.worked_designation_id  desigid,
           sum(da.working_hours-idle_hours) whrs,sum(mchrs) mchrs,
           case when (tdac.spell='C' and  sum(da.working_hours-idle_hours)=7.5) then 0.5 else 0 end nshrs from (
           select tdac.eb_id,wm.eb_no,concat( wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) empname,
           wqm.yarn_count,tdac.frameno ,tdac.net_weight, 
           case when wqm.yarn_count <12.99 then $spg9/8*tdac.working_hours
           when wqm.yarn_count between 13 and 15.99 then $spg13/8*tdac.working_hours
		   when wqm.yarn_count >=16 then $spg16/8*tdac.working_hours end stgprod,doffdate,spell,tdac.company_id,tdac.working_hours  mchrs
           from EMPMILL12.tbl_doffdata_all_calc tdac 
           left join worker_master wm on wm.eb_id =tdac.eb_id 
           left join weaving_quality_master wqm on wqm.quality_id =tdac.quality_id
                       left join tbl_hrms_ed_official_details theod on theod.eb_id=tdac.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
           where tdac.doffdate ='$periodfromdate'  and tdac.company_id =$comp and substr(eb_no,1,1)='T' and theod.contractor_id not in (10)
           ) tdac    left join daily_attendance da on da.eb_id=tdac.eb_id and da.attendance_date=tdac.doffdate and da.spell=tdac.spell 
           and da.company_id=tdac.company_id  and da.is_active =1
           group by eb_id,eb_no,empname,doffdate,tdac.spell,da.worked_department_id,da.worked_designation_id 
          ) k group by eb_id,eb_no,empname,doffdate,substr(spell,1,1),desigid ,deptid
           ) g 
				group by eb_no,eb_id,deptid, desigid,doffdate,shift";
//    $this->db->query($sql);





          //     ---  windinging

     		$sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520/8*sum(whrs+nhrs)
           when (sum(netwt)<sum(tgprod) ) then 390/8*sum(whrs+nhrs) end rateprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520
           when (sum(netwt)<sum(tgprod) ) then 390 end prodrt from (
                             SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                twdsud.prod netwt,
                twdsud.qcount,
                twdsud.atthrs AS whrs,
                CASE 
                    WHEN twdsud.qcount <= 12.99 
                         THEN $wnd9/8 * twdsud.atthrs
                    WHEN twdsud.qcount BETWEEN 13 AND 15.99 
                         THEN $wnd13/8 * twdsud.atthrs
                    WHEN twdsud.qcount >= 16 
                         THEN $wnd16/8 * twdsud.atthrs 
                END AS tgprod,
                case when da.spell='C' and atthrs=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            LEFT JOIN EMPMILL12.tbl_winding_daily_spell_update_data twdsud 
                   ON twdsud.eb_id = da.eb_id 
                  AND twdsud.tran_date = da.attendance_date 
                  AND da.spell = twdsud.spell 
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (199,201,200,202,330) 
              AND SUBSTR(da.eb_no,1,1) = 'T' and theod.contractor_id=10
           ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)";
              $this->db->query($sql);

         // winding others     

     		$sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520/8*sum(whrs+nhrs)
           when (sum(netwt)<sum(tgprod) ) then 400/8*sum(whrs+nhrs) end rateprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520
           when (sum(netwt)<sum(tgprod) ) then 400 end prodrt from (
                             SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                twdsud.prod netwt,
                twdsud.qcount,
                twdsud.atthrs AS whrs,
                CASE 
                    WHEN twdsud.qcount <= 12.99 
                         THEN $wnd9/8 * twdsud.atthrs
                    WHEN twdsud.qcount BETWEEN 13 AND 15.99 
                         THEN $wnd13/8 * twdsud.atthrs
                    WHEN twdsud.qcount >= 16 
                         THEN $wnd16/8 * twdsud.atthrs 
                END AS tgprod,
                case when da.spell='C' and atthrs=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            LEFT JOIN EMPMILL12.tbl_winding_daily_spell_update_data twdsud 
                   ON twdsud.eb_id = da.eb_id 
                  AND twdsud.tran_date = da.attendance_date 
                  AND da.spell = twdsud.spell 
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (199,201,200,202,330) 
              AND SUBSTR(da.eb_no,1,1) = 'T' and theod.contractor_id not in (10)
           ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)";
              $this->db->query($sql);





        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520/8*sum(whrs+nhrs)
           when (sum(netwt)<sum(tgprod) ) then 390/8*sum(whrs+nhrs) end rateprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520
           when (sum(netwt)<sum(tgprod) ) then 390 end prodrt from (
                  SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                0 netwt,
                0 qcount,
                working_hours-idle_hours AS whrs,
				0 tgprod,
                case when da.spell='C' and (da.working_hours-da.idle_hours)=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (11,12,604,605,30,32,33,28,29,33,35,37,
				38,39,40,41,42,44,45,46,47) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
       ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)
   ";
                $this->db->query($sql);


// T2151

        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520/8*sum(whrs+nhrs)
           when (sum(netwt)<sum(tgprod) ) then 390/8*sum(whrs+nhrs) end rateprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520
           when (sum(netwt)<sum(tgprod) ) then 390 end prodrt from (
                  SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                0 netwt,
                0 qcount,
                working_hours-idle_hours AS whrs,
				0 tgprod,
                case when da.spell='C' and (da.working_hours-da.idle_hours)=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.eb_no = 'T2151'  and theod.contractor_id=10
       ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)
   ";
                $this->db->query($sql);



        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520/8*sum(whrs+nhrs)
           when (sum(netwt)<sum(tgprod) ) then 390/8*sum(whrs+nhrs) end rateprod,
           case when (sum(netwt)>=sum(tgprod) ) then 520
           when (sum(netwt)<sum(tgprod) ) then 390 end prodrt from (
                  SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                0 netwt,
                0 qcount,
                working_hours-idle_hours AS whrs,
				0 tgprod,
                case when da.spell='C' and (da.working_hours-da.idle_hours)=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (55,56) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
       ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)
   ";
//                $this->db->query($sql);


        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,
		   dept_id,desig_id,production,is_active,rate_type,prod_date,prod_shift,targt_production,prod_amount,prod_rate)
		   select eb_id,deptid, desigid,sum(netwt) netwt,1 act,case when sum(netwt)>=sum(tgprod) then 1 else 0 end ratetype,
		   attendance_date,substr(spell,1,1) shift,sum(tgprod) tgprod,
           390  prodrt from (
                  SELECT 
                da.daily_atten_id, 
                da.eb_id,
                da.eb_no,
                da.attendance_date,
                da.spell,
                da.worked_department_id AS deptid,
                da.worked_designation_id AS desigid, 
                0 netwt,
                0 qcount,
                working_hours-idle_hours AS whrs,
				0 tgprod,
                case when da.spell='C' and (da.working_hours-da.idle_hours)=7.5 then 0.5 else 0 end nhrs
            FROM daily_attendance da 	
            left join tbl_hrms_ed_official_details theod on theod.eb_id=da.eb_id and theod.is_active=1
                   left join contractor_master cm on cm.cont_id=theod.contractor_id        
            WHERE da.attendance_date = '$periodfromdate' 
              AND da.is_active = 1 
              AND da.company_id = $comp
              AND da.worked_designation_id IN (57) 
              AND SUBSTR(da.eb_no,1,1) = 'T'  and theod.contractor_id=10
       ) g group by eb_id,deptid, desigid,attendance_date,substr(spell,1,1)
   ";
//                $this->db->query($sql);




     $success='Success';
  
    $data[] = [
        'success'=> $success 
    ];
    return $data;




    }

    public function oattprddownload($periodfromdate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    	$sql="select prod_date Date,prod_shift Shift,eb_no,concat( wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) empname,
           dept_desc,desig,round(production,0) prodcution,round(tdcopp.targt_production,0) target_prod ,
           case when tdcopp.rate_type=1 then 'Full_Rate' else  'Min_Rate' end as rate_type,tdcopp.prod_amount,
           tct.shr_name cntloca
           from EMPMILL12.tbl_daily_cash_outsider_payment_production tdcopp
           left join worker_master wm on wm.eb_id=tdcopp.eb_id  
           left join department_master dm on dm.dept_id =tdcopp.dept_id 
           left join designation d on d.id=tdcopp.desig_id 
           left join tbl_hrms_ed_official_details theod on tdcopp.eb_id=theod.eb_id and theod.is_active =1
           left join contractor_master cm on cm.cont_id =theod.contractor_id 
           left join EMPMILL12.tbl_contractor_type tct ON theod.contractor_id  =tct.cont_id 
           where prod_date='$periodfromdate' and tdcopp.is_active=1 
           order by dept_code,prod_shift,eb_no";
//            $query = $this->db->query($sql);
  $result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }

    
    public function esiacreport($periodfromdate,$ebno) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    	$sql="select h.*,round(hra/gross*100,2) hrap,gross+hra tpay,pf+esi+ptax tded,(gross+hra)-(pf+esi+ptax) npay
        ,'The Empire Jute Co Ltd' compname,
'40000031740000102' compcode,'15, B.T. Road, Kolkata - 700123' compaddress  from (
SELECT 
    g.EMPLOYEEID,
    g.emp_code,
    g.emp_name,
    g.esi_no,
ROUND(
    MAX(CASE WHEN g.COMPONENT_ID = 178 THEN g.amount/8 ELSE 0 END) +
    MAX(CASE WHEN g.COMPONENT_ID = 179 THEN g.amount / 8 ELSE 0 END) +
    MAX(CASE WHEN g.COMPONENT_ID = 180 THEN g.amount / 8 ELSE 0 END) +
    MAX(CASE WHEN g.COMPONENT_ID = 183 THEN g.amount ELSE 0 END),
    0
) AS wdays,
    MAX(CASE WHEN g.COMPONENT_ID = 8 THEN g.amount ELSE 0 END) hra,
    MAX(CASE WHEN g.COMPONENT_ID = 18 THEN g.amount ELSE 0 END) pf,
    MAX(CASE WHEN g.COMPONENT_ID = 19 THEN g.amount ELSE 0 END) esi,
    MAX(CASE WHEN g.COMPONENT_ID = 20 THEN g.amount ELSE 0 END) gross,
    MAX(CASE WHEN g.COMPONENT_ID = 16 THEN g.amount ELSE 0 END) ptax
    FROM (
    SELECT 
        tpep.EMPLOYEEID,
        theod.emp_code,
        CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
        thee.esi_no,
        tpep.COMPONENT_ID,
        SUM(tpep.AMOUNT) AS amount
    FROM tbl_pay_employee_payroll tpep 
    LEFT JOIN tbl_pay_period tpp 
        ON tpep.PAYPERIOD_ID = tpp.ID 
    LEFT JOIN tbl_hrms_ed_official_details theod 
        ON theod.eb_id = tpep.EMPLOYEEID 
       AND theod.is_active = 1
    LEFT JOIN tbl_hrms_ed_personal_details thepd 
        ON thepd.eb_id = tpep.EMPLOYEEID 
    LEFT JOIN tbl_hrms_ed_esi thee 
        ON thee.eb_id = tpep.EMPLOYEEID 
       AND thee.is_active = 1
    WHERE 
        tpep.STATUS = 1 
        AND tpp.STATUS NOT IN (4)
        AND MONTH(tpp.FROM_DATE) = month('$periodfromdate') 
        AND YEAR(tpp.TO_DATE) = year('$periodfromdate')
        AND tpep.COMPONENT_ID IN (198,180,183,191,8,18,19,149,16,20,178,179,102,66)
        AND theod.emp_code = '$ebno'
    GROUP BY 
        tpep.EMPLOYEEID,
        theod.emp_code,
        CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),
        thee.esi_no,
        tpep.COMPONENT_ID
) g 
GROUP BY 
    g.EMPLOYEEID,
    g.emp_code,
    g.emp_name,
    g.esi_no ) h
";

//echo $sql;
//            $query = $this->db->query($sql);
  $result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }



    
    public function donwjuteReport1($periodfromdate,$periodtodate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    	$sql="select company_code `Company Code`,
smh.po_num `PO.No.`,
DATE_FORMAT(smh.po_date, '%d-%m-%Y') as `PO DATE` ,
smh2.mr_print_no as `EJM MR No.`,
smh.mr_print_no `CO_MR_No`,
DATE_FORMAT(smh.jute_receive_dt, '%d-%m-%Y') as `MR DATE` ,
bp.invoice_no ,
bp.invoice_date ,
s.supp_name `Party Name`,
jqpm.jute_quality `Item Quality` ,
smli.accepted_weight  `Weight (KG)`,
round(smli.rate/100,2) `MR Rate`,
round((smli.rate/100*smli.accepted_weight),0) as `Total Amount`,
smli.claim_rate `Claim Rate`,
round((smli.claim_rate/100)*(smli.accepted_weight),0) as `Claim Amount`,
((round((smli.rate/100*smli.accepted_weight),0))-round((smli.claim_rate/100)*(smli.accepted_weight),0)) as `Net Total Amount`
from scm_mr_hdr smh  
left join company_master cm on cm.comp_id = smh.company_id
left join suppliermaster s  on s.supp_code = smh.actual_supplier and smh.company_id = s.company_id
left join scm_mr_line_item smli on smli.jute_receive_no = smh.jute_receive_no and smli.is_active = 1 and smli.status not in (4,6)
left join itemmaster i on i.item_code = smli.item_code  and smli.company_id = i.company_id and i.group_code = '999'
left join jute_quality_price_master jqpm on jqpm.id = smli.actual_quality  
left join bill_pass bp on bp.sr_mr_num = smh.jute_receive_no and bp.status not in (4) and bp.is_active = 1
left join scm_mr_hdr smh2 on smh2.jute_receive_no = smh.src_hdr_id
where smh.src_com_id = $comp and substr(smh.jute_receive_dt,1,10)  between '$periodfromdate' and '$periodtodate' and smh.mr_good_recept_status not in (4,6)
order by company_code, smh.jute_receipt_no,smli.jute_line_item_no   ";
//            $query = $this->db->query($sql);
//echo $sql;
$result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }


    public function downjutetallyReport1($periodfromdate,$periodtodate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');

        $tdscap=5000000;
        $mn=substr($periodfromdate,5,6);
      
        $sql="select case when substr(smh.mr_print_no,1,3)='(A)' then substr(smh.mr_print_no,4,15) else smh.mr_print_no 
        end `Vch No.`,'Purchase' `Vch Type`,date_format(smh2.mrdate,'%d-%m-%Y') `Date`,  bp.invoice_no `Supplier Inv No` ,
DATE_FORMAT(bp.invoice_date,'%d-%m-%Y') `Supplier Inv Date`,
''  `Receipt Note No`,'' `Receipt Note Date`,smh.po_num	`Order No`,
DATE_FORMAT(smh.po_date,'%d-%m-%Y')  `Order Date`,ttlf.tally_name `Party Name`  ,
'' `Registration Type`,''	`GSTIN No`,	'' `Country`,'' `State`	,'' `Pincode`,'' `Address 1`,''	`Address 2`,''	`Address 3`,
ttlf5.tally_name  `Purchase Ledger` ,'' `Purchase Ledger Description`,
ttlf2.tally_name  `Item Name`,
case when smli.actual_bale  >0 then Concat('Raw Jute: ',smli.actual_bale,' Bales' ) else 
Concat('Raw Jute: ',' Loose' ) end `Item Description`,
'' `UNITS.`,''	`Tracking No`,''	`Order No_1`,''	`Order Due Date`,
ttlf3.tally_name  Godown,case when substr(smh.mr_print_no,1,3)='(A)' then substr(smh.mr_print_no,4,15) else smh.mr_print_no 
        end `Batch`,
smli.accepted_weight Qty,round(smli.rate/100,2) Rate,round((smli.rate/100*smli.accepted_weight),0) as `Amt`,
'' `Amount1`,''	`Discount Ledger`,'' `Discount Ledger Description`,	'' `Amount1_1`,
case when smli.claim_rate>0 then ttlf4.tally_name else '' end 
  `Additional Ledger`,case when smli.claim_rate>0 then concat('(Q): ',jqpm.jute_quality,'-',smli.accepted_weight,' X Rs. ',smli.claim_rate,' /-') else ''  end
 `Additional Ledger Description`,
case when claim_rate>0 then 0-round((smli.claim_rate/100)*(smli.accepted_weight),0) else '' end Amount, 
'' `INPUT IGST`,''	`INPUT CGST`,''	`INPUT SGST`,''	`Roundoff amt`,''	`Total`,
concat(ifnull(bp.invoice_no,''),'/',CASE
      WHEN SUBSTR(smh.mr_print_no,1,3)='(A)' THEN SUBSTR(smh.mr_print_no,4,15)
      ELSE smh.mr_print_no
    END)  `Ref: No.`, 180 `Due on`,
case when claim_rate>0 then 0-round((smli.claim_rate/100)*(smli.accepted_weight),0) else '' end Amount, 
'' `e Way Bill No.`,
'' `e Way Bill Date`,'' `Sub Type`,'' `Doc Type`,
'' `Status Of eway Bill`,'' Mode,'' `Distance (In KM)`,'' `Transporter Name`,'' `Vehicle No.`,
'' `Doc/Loading/RR/AirWay No.`,'' Date_1, '' `Transporter ID`,'' `Consignor:`,'' `Address 1_1`,'' `Address 2_1`,'' `Pin Code`,
'' Place,'' State_1,'' `GSTIN/UIN`,'' Consignee,'' `Address 1_2`,'' `Address 2_2`,'' `To Place (Destination )`,'' Pin,'' State_2,
'' `GSTIN/UIN_1`,
CONCAT(
    'Being ', smh2.mrwt,' Kg. Raw Jute Purchase From ', ttlf.tally_name,
    ' Against Inv: ', IFNULL(bp.invoice_no,''), ' Dt :', IFNULL(DATE_FORMAT(smh2.mrdate,'%d-%m-%Y'), ''),
    ' CH: ', ifnull(smh.challan_no,''), ',Dt: ', ifnull(DATE_FORMAT(smh.challan_date,'%d-%m-%Y'),''),
    ',Ref.NO. ',
    CASE
      WHEN SUBSTR(smh.mr_print_no,1,3)='(A)' THEN SUBSTR(smh.mr_print_no,4,15)
      ELSE smh.mr_print_no
    END,
    ' Amt Rs. ', smh2.naramt,' /-'
  ) AS `Narration`,'' TALLYIMPORTSTATUS,tds.cumulative_naramt `Cumulative Amount`,
case when (tds.cumulative_naramt-smh2.naramt)>$tdscap then 0-round(smh2.naramt*0.1/100,0)
when ((tds.cumulative_naramt-smh2.naramt)<$tdscap and tds.cumulative_naramt>$tdscap) then 0-round(((tds.cumulative_naramt-$tdscap)*0.1/100),0) 
else 0 end `tds Amount`,noofitems,
smh2.naramt `MR Amount`,
case when (tds.cumulative_naramt-smh2.naramt)>$tdscap then round(smh2.naramt*0.1/100,0)
when ((tds.cumulative_naramt-smh2.naramt)<$tdscap and tds.cumulative_naramt>$tdscap) then round(((tds.cumulative_naramt-$tdscap)*0.1/100),0) 
else 0 end `TDS Deducted`
from scm_mr_hdr smh 
left join scm_mr_line_item smli on smh.jute_receive_no =smli.jute_receive_no  and smli.is_active =1 
left join bill_pass bp on bp.sr_mr_num =smh.jute_receive_no and bp.status not in (4,6) and bp.is_active =1 
left join suppliermaster s on s.supp_code =smh.actual_supplier  and s.supp_type='J'  and s.company_id =smh.company_id 
left join jute_quality_price_master jqpm on jqpm.id=smli.actual_quality 
left join EMPMILL12.tbl_tally_link_file ttlf on trim(ttlf.vow_name) =trim(s.supp_name) AND ttlf.link_for ='S'
and smh.company_id =ttlf.company_id 
left join EMPMILL12.tbl_tally_link_file ttlf2 on ttlf2.vowid =smli.actual_quality and ttlf2.link_for ='Q'   
LEFT join EMPMILL12.tbl_tally_link_file ttlf3 on ttlf3.company_id =smh.company_id and ttlf3.link_for ='G'  
LEFT join EMPMILL12.tbl_tally_link_file ttlf4 on ttlf4.company_id =smh.company_id and ttlf4.link_for ='C'
LEFT join EMPMILL12.tbl_tally_link_file ttlf5 on ttlf5.company_id =smh.company_id and ttlf5.link_for ='P'
left join (select jute_receive_no,mrdate,sum(mrwt) mrwt,sum(naramt) naramt,count(*) noofitems
from (
select mr_print_no,case when smh.src_com_id =2 then smh.jute_receive_dt  else invoice_date end mrdate,  
smli.jute_receive_no,(accepted_weight) as mrwt,
round((accepted_weight/100*smli.rate),0)-(accepted_weight/100*smli.claim_rate) as naramt,
smh.company_id ,smh.jute_receive_dt ,bp.invoice_date ,smh.src_com_id,smh.gate_entry_no 
from scm_mr_line_item smli 
left join scm_mr_hdr smh on smh.jute_receive_no =smli.jute_receive_no
left join bill_pass bp on bp.sr_mr_num =smh.jute_receive_no 
where smli.is_active =1 and smli.status not in (4,6)
and smh.mr_good_recept_status not in (4,6) and smh.company_id =$comp
) g where  g.mrdate between '$periodfromdate' and '$periodtodate'  group by jute_receive_no,mrdate
) smh2 on smh2.jute_receive_no =smh.jute_receive_no
left join (SELECT
    x.suppname,
    x.mrdate,
    x.jute_receive_no,
    x.naramt,
    round(SUM(x.naramt) OVER (
        PARTITION BY x.suppname
        ORDER BY x.mrdate, x.jute_receive_no
        ROWS UNBOUNDED PRECEDING
    ),2) AS cumulative_naramt
FROM (
    SELECT
        g.suppname,
        g.mrdate,
        g.jute_receive_no,
        round(SUM(g.naramt),2) AS naramt
    FROM (
        SELECT
            smh.jute_receive_no,
            CASE
                WHEN smh.src_com_id = 2 THEN smh.jute_receive_dt
                ELSE bp.invoice_date
            END AS mrdate,
            smh.supp_code,ttlf.tally_name suppname,
            ROUND((smli.accepted_weight/100 * smli.rate), 0)
              - (smli.accepted_weight/100 * smli.claim_rate) AS naramt,
            smh.company_id
        FROM scm_mr_line_item smli
        LEFT JOIN scm_mr_hdr smh
            ON smh.jute_receive_no = smli.jute_receive_no
        LEFT JOIN bill_pass bp
            ON bp.sr_mr_num = smh.jute_receive_no
        left join suppliermaster s on s.supp_code =smh.actual_supplier  and s.supp_type='J'  and s.company_id =smh.company_id  
        left join EMPMILL12.tbl_tally_link_file ttlf on trim(ttlf.vow_name) =trim(s.supp_name) AND ttlf.link_for ='S'
        and ttlf.company_id =smh.company_id 
        WHERE smli.is_active = 1
          AND smli.status NOT IN (4,6)
          AND smh.mr_good_recept_status NOT IN (4,6)
          AND smh.company_id = $comp
    ) g
    WHERE g.mrdate >= '2025-04-01'
      AND g.mrdate <  '$periodtodate'    
    GROUP BY g.suppname, g.mrdate, g.jute_receive_no
) x
ORDER BY x.suppname, x.mrdate, x.jute_receive_no
) tds on tds.jute_receive_no =smh.jute_receive_no and tds.mrdate=smh2.mrdate
where smh.company_id =$comp and smh2.mrdate between '$periodfromdate' and '$periodtodate'
order by bp.invoice_date,smli.jute_line_item_no
";
/* case when smli.claim_rate>0 then 'Claim On Gross Purchase' else '' end 
  `cAdditional Ledger`,case when smli.claim_rate>0 then concat('(Q): ',jqpm.jute_quality,'-',smli.accepted_weight,' X Rs. ',smli.claim_rate,' /-') else ''  end
 `cAdditional Ledger Description`,
 */


 //  echo $sql;
//            $query = $this->db->query($sql);
  $result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }

    public function downjutetallycheck($periodfromdate,$periodtodate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');


$sql="select * from (
select smh.company_id,case when smh.src_com_id=2 then smh.jute_receive_dt  else bp.invoice_date end mrdate,
 smh.mr_print_no ,bp.invoice_no ,bp.invoice_date ,s.supp_name ,ttlf.tally_name supptally ,jqpm.jute_quality , ttlf2.tally_name qualitytally,
ttlf3.tally_name godowsntally,ttlf4.tally_name claimtally,ttlf5.tally_name purtally
from scm_mr_hdr smh 
left join scm_mr_line_item smli on smh.jute_receive_no =smli.jute_receive_no  and smli.is_active =1 
left join bill_pass bp on bp.sr_mr_num =smh.jute_receive_no and bp.status not in (4,6) and bp.is_active =1 
left join suppliermaster s on s.supp_code =smh.actual_supplier  and s.supp_type='J'  and s.company_id =smh.company_id 
left join jute_quality_price_master jqpm on jqpm.id=smli.actual_quality 
left join EMPMILL12.tbl_tally_link_file ttlf on trim(ttlf.vow_name) =trim(s.supp_name) AND ttlf.link_for ='S'
and smh.company_id =ttlf.company_id 
left join EMPMILL12.tbl_tally_link_file ttlf2 on ttlf2.vowid =smli.actual_quality and ttlf2.link_for ='Q' 
LEFT join EMPMILL12.tbl_tally_link_file ttlf3 on ttlf3.company_id =smh.company_id and ttlf3.link_for ='G'
LEFT join EMPMILL12.tbl_tally_link_file ttlf4 on ttlf4.company_id =smh.company_id and ttlf4.link_for ='C'
LEFT join EMPMILL12.tbl_tally_link_file ttlf5 on ttlf5.company_id =smh.company_id and ttlf5.link_for ='P'
) g 
where company_id =$comp and mrdate between '$periodfromdate' and '$periodtodate'";

$result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }


      public function downjutetallysalesReport1($periodfromdate,$periodtodate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
        $tdscap=5000000;
 $sql="select * from (
select 'Sales' `Voucher Type Name`,DATE_FORMAT(ih.invoice_date,'%d-%b-%Y') `Voucher Date` ,  ih.invoice_no_string `Voucher Number`
,ttlf1.tally_name `Ledger Name`,(ih.invoice_amount-ih.round_off)  `Ledger Amount`,'Dr' `Ledger Amount Dr/Cr` ,
concat(' Ch.No: ',ih.challan_no) `Despatch Doc no.`,concat('Truck No: ',ih.vehicle_no) `Despath though`,
ih.shipping_address   `Destination`,case when substr(smh.mr_print_no,1,3)='(A)' then substr(smh.mr_print_no,4,15) else smh.mr_print_no 
        end  `other references`,' ' `Item Name`,' ' `Godown`,
'' `Batch/Lotno.`,'' `Billed Quantity`,'' `Item Rate`,'' `Item Rate per`,
'' `Item Amount`,'' `Description ?`,'Item Invoice' `Change Mode`,(ih.invoice_amount-ih.round_off)  `Bill Amount`,
'Dr' `Bill Amount - Dr/Cr`,concat(ih.invoice_no_string,' / ',smh.mr_print_no) `Bill Name`,'New Ref' `Bill Type of Ref`,
180 `Due or credit days`,  concat('Being ',ili.tqty,' Kg. Raw Jute Sold To ',ttlf1.tally_name,' against Inv No: ' ,
  ih.invoice_no_string ,'  Dt: ',ih.invoice_date ,' MR NO: ',smh.mr_print_no ,', Amount Rs. ',ih.invoice_amount ,' /-') 
 Narration,cm.address  `Address1`,cm.state  State1 ,'India'  Country1 ,cm.address  Address2,cm.state2   State2,'India' Country2,
 ih.invoice_date,ih.invoice_no_string,0 invoice_line_item_id,'hd' rem,0 slno
from invoice_hdr ih 
-- left join invoice_line_items ili on ih.invoice_id =ili.invoice_id 
left join customer_master cm  on cm.id =ih.customer_id 
left join EMPMILL12.tbl_tally_link_file ttlf1 on ih.company_id =ttlf1.company_id and ttlf1.link_for ='M'  and ttlf1.vow_name = cm.name  
left join scm_mr_hdr smh on ih.mr_id=smh.jute_receive_no 
left join (select ili.invoice_id,sum(ili.quantity*100) tqty from invoice_line_items ili where ili.is_active=1  group by ili.invoice_id  )
ili on ih.invoice_id =ili.invoice_id 
where ih.status not in (4,6) and ih.is_active =1 and  ih.company_id =$comp and ih.invoice_date between '$periodfromdate' and '$periodtodate'
UNION ALL
select '' `Voucher Type Name`,'' `Voucher Date` ,  '' `Voucher Number`
,'Sale of Raw Jute' `Ledger Name`,(ih.invoice_amount-ih.round_off)  `Ledger Amount`,'Cr' `Ledger Amount Dr/Cr` ,
'' `Despatch Doc no.`,'' `Despath though`,''  `Destination`,''  `other references`,
ttlf2.tally_name  `Item Name`,ttlf.tally_name  `Godown`,
case when substr(smh.mr_print_no,1,3)='(A)' then substr(smh.mr_print_no,4,15) else smh.mr_print_no 
        end `Batch/Lotno.`,ili.quantity*100  `Billed Quantity`,ili.rate/100  `Item Rate`,'kg' `Item Rate per`,
ili.amount_without_tax  `Item Amount`,CONCAT(
  'Raw Jute: ',
  CASE
    WHEN ili.sales_bale > 0 THEN CONCAT(ili.sales_bale, ' Bales')
    ELSE 'loose'
  END
)  `Description ?`,'' `Change Mode`,'' `Bill Amount`,
'' `Bill Amount - Dr/Cr`,'' `Bill Name`,'' `Bill Type of Ref`,
'' `Due or credit days`, '' 
 Narration,''  `Address1`,''  State1 ,''  Country1 ,''  Address2,''   State2,'' Country2
, ih.invoice_date,ih.invoice_no_string,ili.invoice_line_item_id, 'ln' rem,
  ROW_NUMBER() OVER (PARTITION BY ili.invoice_id ORDER BY ili.invoice_line_item_id) AS slno 
 from invoice_hdr ih 
left join invoice_line_items ili on ih.invoice_id =ili.invoice_id 
left join jute_quality_price_master jqpm on jqpm.id=ili.quality_id 
left join EMPMILL12.tbl_tally_link_file ttlf2 on ih.company_id =ttlf2.company_id and ttlf2.link_for ='Q'  and ttlf2.vowid  = jqpm.id 
left join EMPMILL12.tbl_tally_link_file ttlf on ih.company_id =ttlf.company_id and ttlf.link_for ='G'
left join scm_mr_hdr smh on ih.mr_id=smh.jute_receive_no 
where ih.status not in (4,6) and ih.is_active =1 and  ili.is_active=1 and ih.company_id =$comp and ih.invoice_date between 
'$periodfromdate' and '$periodtodate' 
union all
select '' `Voucher Type Name`,'' `Voucher Date` , '' `Voucher Number`
,'Claim on Gross Sales'  `Ledger Name`,0-tclm `Ledger Amount`,'' `Ledger Amount Dr/Cr` ,
'' `Despatch Doc no.`,'' `Despath though`,
''   `Destination`,''  `other references`,' ' `Item Name`,' ' `Godown`,
'' `Batch/Lotno.`,'' `Billed Quantity`,'' `Item Rate`,'' `Item Rate per`,
'' `Item Amount`,'' `Description ?`,'' `Change Mode`,'' `Bill Amount`,
'' `Bill Amount - Dr/Cr`,'' `Bill Name`,'' `Bill Type of Ref`,
'' `Due or credit days`, '' 
 Narration,''  `Address1`,''  State1 ,''  Country1 ,''  Address2,''   State2,'' Country2,
 ih.invoice_date,ih.invoice_no_string,0 invoice_line_item_id,'oth' rem,0 slno
from invoice_hdr ih 
left join customer_master cm  on cm.id =ih.customer_id 
left join EMPMILL12.tbl_tally_link_file ttlf1 on ih.company_id =ttlf1.company_id and ttlf1.link_for ='M'  and ttlf1.vow_name = cm.name  
left join scm_mr_hdr smh on ih.mr_id=smh.jute_receive_no 
left join (select ili.invoice_id,sum(ili.claim_amount_dtl ) tclm from invoice_line_items ili where ili.is_active=1 group by ili.invoice_id  )
ili on ih.invoice_id =ili.invoice_id 
where ih.status not in (4,6) and ih.is_active =1 and ih.company_id =$comp and ih.invoice_date between 
'$periodfromdate' and '$periodtodate' and ili.tclm>0
) g 
order by  invoice_date,invoice_no_string,rem,invoice_line_item_id 
 ";


 
  // echo $sql;
//            $query = $this->db->query($sql);
  $result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }

      public function jutevowtallylist($periodfromdate,$periodtodate) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
        $tdscap=5000000;
    	$sql="select * from (
SELECT
  cm.comp_id, cm.company_code,'Purchase' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'S'
union all
SELECT
  cm.comp_id,   cm.company_code,'Sales' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'M'
union all
SELECT
  cm.comp_id,  cm.company_code, 'Quality' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'Q'
union all
SELECT
  cm.comp_id,  cm.company_code, 'Purchase ledger Name' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'P'
union all
SELECT
  cm.comp_id,  cm.company_code, 'Claim ledger Name' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'C'
union all
SELECT
  cm.comp_id,  cm.company_code, 'Godown Name' AS Type,
  ttlf.vow_name   AS `Vow Name`,
  ttlf.tally_name AS `Tally_Name`
FROM EMPMILL12.tbl_tally_link_file ttlf
LEFT JOIN vowsls.company_master cm
  ON cm.comp_id = ttlf.company_id
WHERE ttlf.link_for = 'G'
) g where g.comp_id =$comp

";
/* case when smli.claim_rate>0 then 'Claim On Gross Purchase' else '' end 
  `cAdditional Ledger`,case when smli.claim_rate>0 then concat('(Q): ',jqpm.jute_quality,'-',smli.accepted_weight,' X Rs. ',smli.claim_rate,' /-') else ''  end
 `cAdditional Ledger Description`,
 */


 //  echo $sql;
//            $query = $this->db->query($sql);
  $result = $this->db->query($sql)->result_array();

    $data = $result;
    if (!empty($data)) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }


    }




}    