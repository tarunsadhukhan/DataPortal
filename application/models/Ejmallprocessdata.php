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

	public function getFneTargetEntry($deptId, $targetType, $effCodeId, $qualCode, $dateFrom, $dateTo){
		$this->db->where('dept_id', $deptId);
		$this->db->where('target_type', $targetType);
		$this->db->where('date_from', $dateFrom);
		$this->db->where('date_to', $dateTo);
   //     echo $targetType;
		if ($targetType === 'E') {
			$this->db->where('eff_code', $effCodeId);
		} elseif ($targetType === 'P') {
			$this->db->where('qual_code', $qualCode);
		}
		$q = $this->db->get('EMPMILL12.tbl_all_trn_eff');
    //    echo $this->db->last_query();

 //   echo $this->db->last_query();
    return $q->num_rows() > 0 ? $q->row() : false;
	}

	public function saveFneTargetEntry($data, $recordId = null){
		if ($recordId) {
			$this->db->where('all_trn_eff_id', $recordId);
			return $this->db->update('EMPMILL12.tbl_all_trn_eff', $data);
		}

		return $this->db->insert('EMPMILL12.tbl_all_trn_eff', $data);
	}

	public function getAllFneTargets($dateFrom, $dateTo){
		$this->db->select('t.all_trn_eff_id, t.dept_id, d.dept_desc, t.target_type, t.eff_code, e.eff_mast_name, t.qual_code, t.target_eff, t.date_from, t.date_to');
		$this->db->from('EMPMILL12.tbl_all_trn_eff t');
		$this->db->join('department_master d', 'd.dept_id = t.dept_id', 'left');
		$this->db->join('EMPMILL12.tbl_eff_master e', 'e.eff_code = t.eff_code', 'left');
		$this->db->where('t.date_from', $dateFrom);
		$this->db->where('t.date_to', $dateTo);
		$this->db->order_by('t.dept_id, t.target_type, t.eff_code');
		$q = $this->db->get();
     //   echo $this->db->last_query();
		return $q->result();
	}

	public function deleteFneTarget($id) {
		$this->db->where('all_trn_eff_id', $id);
        return $this->db->delete('EMPMILL12.tbl_all_trn_eff');
	}

	// ========== Wages & Production Quality Link ==========

	public function getProdWagesLinks() {
		$this->db->select('p.prod_code, p.wages_code, p.dept_id, d.dept_desc, p.code_type');
		$this->db->from('EMPMILL12.tbl_prod_wages_code_link p');
		$this->db->join('department_master d', 'd.dept_id = p.dept_id', 'left');
		$this->db->order_by('p.dept_id, p.prod_code');
		$q = $this->db->get();
     //   ECHO $this->db->last_query();
		return $q->result();
	}

	public function saveProdWagesLink($data) {
		return $this->db->insert('EMPMILL12.tbl_prod_wages_code_link', $data);
	}

	public function updateProdWagesLink($old_where, $data) {
		$this->db->where('prod_code', $old_where['prod_code']);
		$this->db->where('dept_id', $old_where['dept_id']);
		$this->db->where('code_type', $old_where['code_type']);
		return $this->db->update('EMPMILL12.tbl_prod_wages_code_link', $data);
	}

	public function deleteProdWagesLink($where) {
		$this->db->where('prod_code', $where['prod_code']);
		$this->db->where('dept_id', $where['dept_id']);
		$this->db->where('code_type', $where['code_type']);
		return $this->db->delete('EMPMILL12.tbl_prod_wages_code_link');
	}

	// ========== Attendance Preparation & Updation ==========

	public function getAttPrepData($dateFrom, $dateTo, $paySchm) {
		$this->db->select("a.att_summary_id, d.dept_id, a.dept_code, d.dept_desc, a.occu_code, a.mc_nos, a.shift, wm.eb_no, CONCAT(wm.worker_name, ' ', IFNULL(wm.middle_name,''), ' ', IFNULL(wm.last_name,'')) AS emp_name, a.working_hours, a.ot_hours, a.ns_hours, tps.NAME as pay_scheme_id, a.is_active", FALSE);
		$this->db->from('EMPMILL12.tbl_ejm_wages_att_summary a');
		$this->db->join('department_master d', 'd.dept_code = a.dept_code and d.company_id=2', 'left');
        $this->db->join('vowsls.tbl_pay_scheme tps ', 'tps.ID = a.pay_scheme_id ', 'left');
        $this->db->join('vowsls.worker_master wm ', 'wm.eb_id = a.eb_id ', 'left');
        if ($dateFrom) $this->db->where('a.date_from', $dateFrom);
		if ($dateTo) $this->db->where('a.date_to', $dateTo);
        $this->db->where('a.is_active', 1);
		if ($paySchm && !empty($paySchm)) {
			if (is_array($paySchm)) {
				$this->db->where_in('a.pay_scheme_id', $paySchm);
			} else {
				$this->db->where('a.pay_scheme_id', $paySchm);
			}
		}
		$this->db->order_by('a.dept_code, a.occu_code');
		$q = $this->db->get();
    //    echo $this->db->last_query();
        return $q->result();
	}

	public function saveAttPrep($data) {
		return $this->db->insert('EMPMILL12.tbl_ejm_wages_att_summary', $data);
	}

	public function updateAttPrep($id, $data) {
		$this->db->where('att_summary_id', $id);
		return $this->db->update('EMPMILL12.tbl_ejm_wages_att_summary', $data);
	}

	public function deleteAttPrep($id) {
		$this->db->where('att_summary_id', $id);
		return $this->db->delete('EMPMILL12.tbl_ejm_wages_att_summary');
	}

	public function processAttPrepclear($dateFrom, $dateTo, $paySchm, $deptCode) {
		// Get attendance data to process using custom query

        $sql="update  EMPMILL12.tbl_ejm_wages_att_summary set is_active=0
        where date_from='$dateFrom' and date_to='$dateTo' and pay_scheme_id='$paySchm'
        and  update_from='ATT'  ";

        $sql="delete from  EMPMILL12.tbl_ejm_wages_att_summary 
        where date_from='$dateFrom' and date_to='$dateTo' and pay_scheme_id='$paySchm'
        and  update_from='ATT' and updated_by is null ";

      //  echo $sql;
        $this->db->query($sql);

    }
    public function processAttPrepwvg($dateFrom, $dateTo, $paySchm, $deptCode) {
        $comany_id=$this->session->userdata('companyId');
        $sql="     insert into  EMPMILL12.tbl_ejm_wages_att_summary (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,mc_nos,working_hours,ot_hours,
     ns_hours,pay_scheme_id,update_from)  
     select '$dateFrom' df , '$dateTo' dt,eb_id,'08' dept_code,occu_code,shift,t_p, amcnos,rwhrs,owhrs,0 nwhrs, $paySchm,'ATT' update_from from (
                select eb_id,shift,occu_code,t_p,
        case when occu_code<>'55'  then mcnos
 else ' ' end amcnos,
        max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
    select shift,eb_id,
    regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
    (
    select attendance_date,spell,shift,eb_id,
    regular_ot,occu_code,t_p,rwhrs,nwhrs,
        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
    from
        (
    select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_id,da.worked_department_id,worked_designation_id,
    da.attendance_type regular_ot,om.occu_code,d.time_piece t_p, 
    case when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)=7.5) then 8
    when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)<>7.5) then (working_hours -idle_hours)
    when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then (working_hours -idle_hours)
    when da.spell<>'C'  then (working_hours -idle_hours) 
    else 0 end rwhrs,
    case when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then 0.5
    else 0 end nwhrs,
    case when LENGTH(mech_code)>4 then substr(mech_code,4,3) 
            else mech_code end mech_code
    from vowsls.daily_attendance da 
    left join (select * from vowsls.daily_ebmc_attendance where is_active=1 ) dea 
    on da.daily_atten_id =dea.daily_atten_id 
    left join vowsls.mechine_master mm on mm.mechine_id =dea.mc_id 
    left join vowsls.worker_master wm on wm.eb_id =da.eb_id 
    left join vowsls.designation d on d.id =da.worked_designation_id 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join vowsls.category_master cm on wm.cata_id =cm.cata_id 
    where da.attendance_date  between '$dateFrom' and '$dateTo'
    and da.worked_department_id =8 and wm.cata_id in (3,4,5,6,7,9)	
    and da.is_active =1  and da.company_id=$comany_id
    and om.dept_id=7 and om.occu_code in ('02','03','05','06')
    ) g group by attendance_date,spell,shift,eb_id, 
    regular_ot,occu_code,t_p,rwhrs,nwhrs
    ) g group by eb_id,shift,eb_id, 
    regular_ot,occu_code,t_p,mcnos
    ) h
    group by eb_id,shift,occu_code,t_p,mcnos
    ) g
    left join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID 
    where tpep.PAY_SCHEME_ID =$paySchm and tpep.STATUS =1
";
          $query = $this->db->query($sql);
    }    


    public function processAttPreppress($dateFrom, $dateTo, $paySchm, $deptCode) {
        $comany_id=$this->session->userdata('companyId');
        $sql="     insert into  EMPMILL12.tbl_ejm_wages_att_summary (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,mc_nos,working_hours,ot_hours,
     ns_hours,pay_scheme_id,update_from)  
     select '$dateFrom' df , '$dateTo' dt,eb_id,'09' dept_code,occu_code,shift,t_p, amcnos,rwhrs,owhrs,0 nwhrs,'$paySchm','ATT' update_from from (
                select eb_id,shift,occu_code,t_p,
        case when occu_code<>'55'   then mcnos else ' ' end amcnos,
        max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
    select shift,eb_id,
    regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
    (
    select attendance_date,spell,shift,eb_id,
    regular_ot,occu_code,t_p,rwhrs,nwhrs,
        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
    from
        (
    select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_id,da.worked_department_id,worked_designation_id,
    da.attendance_type regular_ot,om.occu_code,d.time_piece t_p, 
    case when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)=7.5) then 8
    when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)<>7.5) then (working_hours -idle_hours)
    when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then (working_hours -idle_hours)
    when da.spell<>'C'  then (working_hours -idle_hours) 
    else 0 end rwhrs,
    case when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then 0.5
    else 0 end nwhrs,mech_code
    from vowsls.daily_attendance da 
    left join (select * from vowsls.daily_ebmc_attendance where is_active=1 ) dea 
    on da.daily_atten_id =dea.daily_atten_id 
    left join vowsls.mechine_master mm on mm.mechine_id =dea.mc_id 
    left join vowsls.worker_master wm on wm.eb_id =da.eb_id 
    left join vowsls.designation d on d.id =da.worked_designation_id 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join vowsls.category_master cm on wm.cata_id =cm.cata_id 
    where da.attendance_date  between '$dateFrom' and '$dateTo'
    and da.worked_department_id =9 and da.worked_designation_id in (114,98) and wm.cata_id in (3,4,5,6,7,9)	
    and da.is_active =1  and da.company_id=$comany_id and
    om.dept_id = 8 AND om.occu_code IN ('01','02') 
    ) g group by attendance_date,spell,shift,eb_id, 
    regular_ot,occu_code,t_p,rwhrs,nwhrs
    ) g group by eb_id,shift,eb_id, 
    regular_ot,occu_code,t_p,mcnos
    ) h
    group by eb_id,shift,occu_code,t_p,mcnos
    ) g
    left join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID 
    where tpep.PAY_SCHEME_ID =$paySchm and tpep.STATUS =1
";
          $query = $this->db->query($sql);
    }

    public function processAttPrepbmg($dateFrom, $dateTo, $paySchm, $deptCode) {
        $comany_id=$this->session->userdata('companyId');
        $sql="     insert into  EMPMILL12.tbl_ejm_wages_att_summary (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,mc_nos,working_hours,ot_hours,
         ns_hours,pay_scheme_id,update_from)  
          select '$dateFrom' df , '$dateTo' dt,eb_id,'07' dept_code,occu_code,shift,t_p, amcnos,rwhrs,owhrs,0 nwhrs,'$paySchm','ATT' update_from from (
                select eb_id,shift,occu_code,t_p,
        case when occu_code<>'55'  then mcnos else ' ' end amcnos,
        max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
    select shift,eb_id,
    regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
    (
    select attendance_date,spell,shift,eb_id,
    regular_ot,occu_code,t_p,rwhrs,nwhrs,
        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
    from
        (
    select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_id,da.worked_department_id,worked_designation_id,
    da.attendance_type regular_ot,om.occu_code,d.time_piece t_p, 
    case when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)=7.5) then 8
    when (da.spell='C' and attendance_type='O' and (working_hours -idle_hours)<>7.5) then (working_hours -idle_hours)
    when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then (working_hours -idle_hours)
    when da.spell<>'C'  then (working_hours -idle_hours) 
    else 0 end rwhrs,
    case when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then 0.5
    else 0 end nwhrs,mech_code
     from vowsls.daily_attendance da 
    left join (select * from vowsls.daily_ebmc_attendance where is_active=1 ) dea 
    on da.daily_atten_id =dea.daily_atten_id 
    left join vowsls.mechine_master mm on mm.mechine_id =dea.mc_id 
    left join vowsls.worker_master wm on wm.eb_id =da.eb_id 
    left join vowsls.designation d on d.id =da.worked_designation_id 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join vowsls.category_master cm on wm.cata_id =cm.cata_id 
    where da.attendance_date  between '$dateFrom' and '$dateTo'
    and da.worked_department_id =7 and wm.cata_id in (3,4,5,6,7,9)	
    and da.is_active =1  and da.company_id=$comany_id
    ) g group by attendance_date,spell,shift,eb_id, 
    regular_ot,occu_code,t_p,rwhrs,nwhrs
    ) g group by eb_id,shift,eb_id, 
    regular_ot,occu_code,t_p,mcnos
    ) h
    group by eb_id,shift,occu_code,t_p,mcnos
    ) g
    left join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID 
    where tpep.PAY_SCHEME_ID ='$paySchm' and tpep.STATUS =1
";
//echo $sql;
$query = $this->db->query($sql);



    }
    public function processAttPrep($dateFrom, $dateTo, $paySchm, $deptCode) {
		// Get attendance data to process using custom query

 


		$sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_att_summary
(date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, mc_nos,
 working_hours, ot_hours, ns_hours, pay_scheme_id, update_from)
SELECT
    '$dateFrom' AS df,
    '$dateTo'   AS dt,
    da.eb_id,
    da.dept_code,
    da.occu_code,
    da.shift,
    da.t_p,
    '' AS mcnos,

    SUM(CASE WHEN da.attendance_type = 'R' THEN da.whrs  ELSE 0 END) AS whrs,
    SUM(CASE WHEN da.attendance_type = 'O' THEN da.whrs  ELSE 0 END) AS othrs,
    SUM(da.nwhrs) AS nhrs,

    $paySchm AS payschm,
    'ATT' AS update_from
FROM (
    SELECT
        x.eb_id,
        x.eb_no,
        x.shift,
        x.attendance_type,
        x.occu_code,
        x.dept_code,
        x.t_p,
        x.dept_id,

        /* hours eligible for wages */
        CASE
            WHEN x.shift = 'C' AND x.attendance_type = 'R' THEN x.working_hours
            WHEN x.shift = 'C' AND x.working_hours = 7.5 AND x.attendance_type = 'O' THEN x.working_hours
            WHEN x.shift = 'C' AND x.working_hours <> 7.5 AND x.attendance_type = 'O' THEN x.working_hours
            WHEN x.shift <> 'C' THEN x.working_hours
            ELSE 0
        END AS whrs,

        /* night shift extra */
        CASE
            WHEN x.shift = 'C' AND x.working_hours = 7.5 AND x.attendance_type = 'R' THEN 0.5
            ELSE 0
        END AS nwhrs
    FROM (
        SELECT
            da.eb_id,
            da.eb_no,
            SUBSTR(da.spell,1,1) AS shift,
            da.attendance_type,
            CASE WHEN LENGTH(om.occu_code) > 2 THEN 55 ELSE om.occu_code END AS occu_code,
            dm.dept_code,
            om.time_piece AS t_p,
            SUM(da.working_hours - da.idle_hours) AS working_hours,
            da.worked_department_id AS dept_id
        FROM vowsls.daily_attendance da
        LEFT JOIN EMPMILL12.OCCUPATION_MASTER om
            ON om.vow_occu_id = da.worked_designation_id
        LEFT JOIN vowsls.department_master dm
            ON da.worked_department_id = dm.dept_id
        WHERE da.attendance_date BETWEEN '$dateFrom' AND '$dateTo'
          AND da.company_id = 2
        GROUP BY
            da.eb_id,
            da.eb_no,
            da.attendance_date,
            SUBSTR(da.spell,1,1),
            da.attendance_type,
            CASE WHEN LENGTH(om.occu_code) > 2 THEN 55 ELSE om.occu_code END,
            dm.dept_code,
            om.time_piece,
            da.worked_department_id
    ) x
) da
JOIN vowsls.tbl_pay_employee_payscheme tpep
  ON tpep.employeeid = da.eb_id
 AND tpep.pay_scheme_id = $paySchm
 AND tpep.status = 1
WHERE NOT (
        da.dept_code = '07'
     OR (da.dept_code = '09' AND da.occu_code IN ('01','02'))
     OR (da.dept_code = '08' AND da.occu_code IN ('02','03','05','06'))
)
GROUP BY
    da.eb_id,
    da.dept_code,
    da.occu_code,
    da.shift,
    da.t_p
            ";
//		da.dept_code not in ('08','07') and
//                order by eb_no
        
            $query = $this->db->query($sql);
		if ($query === false) {
			return array('success' => false, 'message' => 'Error executing query: ' . $this->db->error()['message']);
		}
 
        $count = $this->db->affected_rows();
		if ($count == 0) {
			return array('success' => false, 'message' => 'No attendance data found to process');
		}

		return array('success' => true, 'count' => $count, 'message' => $count . ' records processed');
	}






	// ========== Advance & Other Entries ==========

	public function getEmpNameByEb($ebNo) {
		$this->db->select("CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name,''), ' ', IFNULL(thepd.last_name,'')) as emp_name");
		$this->db->from('tbl_hrms_ed_personal_details thepd');
		$this->db->join('tbl_hrms_ed_official_details theod', 'theod.eb_id = thepd.eb_id AND theod.is_active = 1', 'left');
		$this->db->where('theod.emp_code', $ebNo);
		$q = $this->db->get();
		if ($q->num_rows() > 0) {
			return $q->row()->emp_name;
		}
		return '';
	}

	public function getAdvOthData($dateFrom, $dateTo, $payscheme) {
        log_message('debug', "Fetching Advance & Other data for date range: $dateFrom to $dateTo and payscheme: $payscheme");
		$this->db->select("a.data_collection_id, theod.emp_code as eb_no, CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name,''), ' ', IFNULL(thepd.last_name,'')) as emp_name, a.stl_days, a.puja_advance, a.ot_advance, a.installment_advance, a.stl_advance, a.co_loan, a.misc_earn, a.misc_ded, a.misc_ot_earn, a.misc_ot_ded", FALSE);
		$this->db->from('EMPMILL12.tbl_ejm_wages_data_collection a');
		$this->db->join('vowsls.tbl_hrms_ed_official_details theod', 'theod.eb_id = a.eb_id AND theod.is_active = 1', 'left');
		$this->db->join('vowsls.tbl_hrms_ed_personal_details thepd', 'thepd.eb_id = theod.eb_id', 'left');
        $this->db->join('vowsls.tbl_pay_employee_payscheme tpep', 'tpep.employeeid = theod.eb_id AND tpep.status = 1', 'left');
        $this->db->where('tpep.pay_scheme_id', $payscheme);
        if ($dateFrom) $this->db->where('a.date_from', $dateFrom);
		if ($dateTo) $this->db->where('a.date_to', $dateTo);
		$this->db->where('a.is_active', 1);
		$this->db->order_by('theod.emp_code');
		$q = $this->db->get();
        log_message('debug', 'Query: ' . $this->db->last_query());
		return $q->result();

	}

	public function saveAdvOth($data) {
		return $this->db->insert('EMPMILL12.tbl_ejm_wages_data_collection', $data);
	}

	public function updateAdvOth($id, $data) {
		$this->db->where('data_collection_id', $id);
		return $this->db->update('EMPMILL12.tbl_ejm_wages_data_collection', $data);
	}

	public function deleteAdvOth($id) {
		$this->db->where('data_collection_id', $id);
		return $this->db->delete('EMPMILL12.tbl_ejm_wages_data_collection');
	}

	public function updateStlDays($id, $data) {
		$this->db->where('data_collection_id', $id);
		return $this->db->update('EMPMILL12.tbl_ejm_wages_data_collection', $data);
	}

	public function processInstallmentAdv($dateFrom, $dateTo) {
		try {
			// Custom query to fetch installment advance records with employee details
			$sql = "SELECT 
						a.data_collection_id,
						a.eb_no,
						CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name,''), ' ', IFNULL(thepd.last_name,'')) as emp_name,
						a.date_from,
						a.date_to,
						a.installment_advance,
						a.stl_days,
						a.puja_advance,
						a.ot_advance,
						a.stl_advance,
						a.co_loan,
						a.misc_earn,
						a.misc_ded,
						a.misc_ot_earn,
						a.misc_ot_ded
					FROM EMPMILL12.tbl_ejm_wages_data_collection a
					LEFT JOIN tbl_hrms_ed_official_details theod 
						ON theod.emp_code = a.eb_no AND theod.is_active = 1
					LEFT JOIN tbl_hrms_ed_personal_details thepd 
						ON thepd.eb_id = theod.eb_id
					WHERE a.date_from = ?
						AND a.date_to = ?
						AND a.is_active = 1
						AND a.installment_advance > 0
					ORDER BY a.eb_no";

			$query = $this->db->query($sql, array($dateFrom, $dateTo));
			$rows = $query->result();

			if (empty($rows)) {
				return array(
					'success' => false, 
					'message' => 'No installment records found to process',
					'count' => 0
				);
			}

			// Process installment records - Mark as processed
			$updateSql = "UPDATE EMPMILL12.tbl_ejm_wages_data_collection 
						SET installment_advance_processed = 1 
						WHERE date_from = ? 
							AND date_to = ? 
							AND is_active = 1 
							AND installment_advance > 0";

			$this->db->query($updateSql, array($dateFrom, $dateTo));
			$affected_rows = $this->db->affected_rows();

			// Calculate total installment amount processed
			$totalAmount = 0;
			foreach ($rows as $row) {
				$totalAmount += $row->installment_advance;
			}

			return array(
				'success' => true, 
				'count' => count($rows),
				'total_amount' => $totalAmount,
				'affected_rows' => $affected_rows,
				'message' => count($rows) . ' installment records processed successfully. Total Amount: ' . number_format($totalAmount, 2),
				'data' => $rows
			);

		} catch (Exception $e) {
			log_message('error', 'processInstallmentAdv Error: ' . $e->getMessage());
			return array(
				'success' => false, 
				'message' => 'Error processing installment advances: ' . $e->getMessage()
			);
		}
	}


	public function MainWagesProcessclear($fromdate, $todate, $payscheme) {
 			try {
            $comany_id=$this->session->userdata('companyId');
			$from = DateTime::createFromFormat('Y-m-d', $fromdate);
			$to = DateTime::createFromFormat('Y-m-d', $todate);
			
			if (!$from || !$to) {
				return array('success' => false, 'message' => 'Invalid date format');
			}

			// Custom Query: Process wages from attendance summary
			$sql = "update  EMPMILL12.tbl_ejm_wages_data_collection set is_active=0
            where date_from='$fromdate' and date_to='$todate' and pay_scheme_id='$payscheme'
            and update_for not in ('M')
            ";
			$sql = "delete from  EMPMILL12.tbl_ejm_wages_data_collection
            where date_from='$fromdate' and date_to='$todate' and pay_scheme_id='$payscheme'
            and update_for not in ('M')
            ";

            $result = $this->db->query($sql);
            $sql="commit";
            $this->db->query($sql);

            if ($result) {
                $affected_rows = $this->db->affected_rows();
             //   log_message('info', 'MainWagesProcessclear: ' . $affected_rows . ' records cleared');
                return array('success' => true, 'message' => 'Clearing process completed', 'affected_rows' => $affected_rows);
            } else {
            //    log_message('error', 'MainWagesProcessclear Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
          //  log_message('error', 'MainWagesProcessclear Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
     }

	public function MainWagesProcessns($fromdate, $todate, $payscheme) {
 			try {
            $comany_id=$this->session->userdata('companyId');
			$from = DateTime::createFromFormat('Y-m-d', $fromdate);
			$to = DateTime::createFromFormat('Y-m-d', $todate);
			
			if (!$from || !$to) {
				return array('success' => false, 'message' => 'Invalid date format');
			}

			// Custom Query: Process wages from attendance summary
			$sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,ns_hours,ot_ns_hours,pay_scheme_id,update_for)
                select '$fromdate' df,'$todate' dt,eb_id,sum(rnhrs) ns_hours,
                sum(onhrs) ot_ns_hours,$payscheme payschm, 'NSH' updt from (
                select eb_id,da.attendance_date,spell,da.attendance_type, 
                case when attendance_type='R' and sum(da.working_hours -idle_hours)>=7.5 then 0.5 else 0 end rnhrs,
                case when attendance_type='O' and sum(da.working_hours -idle_hours)>=7.5 then 0.5 else 0 end onhrs
                from vowsls.daily_attendance da 
                where da.attendance_date   between '$fromdate' and '$todate' and  spell='C' and company_id=$comany_id and is_active=1 
                group by eb_id,da.attendance_date,spell,attendance_type
                ) g 
                left join vowsls.tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =g.eb_id and tpep.PAY_SCHEME_ID =$payscheme and tpep.STATUS =1
                where tpep.PAY_SCHEME_ID is not null
                group by eb_id
            ";
			$result1 = $this->db->query($sql);


        $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,fest_hours,pay_scheme_id,update_for)
    select '$fromdate' df,'$todate' dt,eb_id,sum(thht.holiday_hours) hhrs,$payscheme payschm, 'FES' updt from vowsls.tbl_hrms_holiday_transactions thht 
    left join vowsls.holiday_master hm on hm.id =thht.holiday_id 
    left join vowsls.tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =eb_id and tpep.PAY_SCHEME_ID =$payscheme and tpep.STATUS =1
	where tpep.PAY_SCHEME_ID is not null
    and thht.is_active =1 and hm.holiday_date between '$fromdate' and '$todate'
    GROUP BY eb_id";
    $result2 = $this->db->query($sql);
    
    $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,stl_days,pay_scheme_id,update_for)
    select '$fromdate' df,'$todate' dt,eb_id,count(*) stldays,$payscheme payschm, 'STL' updt from vowsls.leave_tran_details ltd 
    left join vowsls.leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id
    left join vowsls.tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =eb_id and tpep.PAY_SCHEME_ID =$payscheme and tpep.STATUS =1
	where tpep.PAY_SCHEME_ID is not null
    and ltd.leave_date between '$fromdate' and '$todate' and ltd.is_active =1
    and lt.status =3 and lt.leave_type_id = 24
	group by eb_id";
    $result3 = $this->db->query($sql);
    
    if ($result1 && $result2 && $result3) {
//        log_message('info', 'MainWagesProcessns completed');
        return array('success' => true, 'message' => 'NS processing completed');
    } else {
  //      log_message('error', 'MainWagesProcessns Error: ' . $this->db->error()['message']);
        return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
    }
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcessns Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

    	public function MainWagesProcessdrg($fromdate, $todate, $payscheme) {
    		try {
 			// Validate date format
            $comany_id=$this->session->userdata('companyId');
			$from = DateTime::createFromFormat('Y-m-d', $fromdate);
			$to = DateTime::createFromFormat('Y-m-d', $todate);
			
			if (!$from || !$to) {
				return array('success' => false, 'message' => 'Invalid date format');
			}

			// Custom Query: Process wages from attendance summary
			$sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
                select  '$fromdate' df,'$todate' dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code,tewas.shift,tewas.t_p,tewas.working_hours,
                tewas.ot_hours,case when (drg.acteff /drg.eff_target)*100<100 then round( (drg.acteff /drg.eff_target)* tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
                case when (drg.acteff /drg.eff_target)*100<100 then round( (drg.acteff /drg.eff_target)* tewas.ot_hours ,2) else tewas.ot_hours end ot_hours_eff,
                $payscheme payschm, 'DRG44' updt,'PROD' updtfr, drg.acteff act_eff    
                    from EMPMILL12.tbl_ejm_wages_att_summary tewas 
                 join (
                select tewom.eff_code,acteff,tate.target_eff eff_target,tewom.deptcode,tewom.occucode from EMPMILL12.tbl_ejm_wages_occu_mast tewom 
                left join (
                select  dm.mc_group,substr(mech_code,1,2) mcgrp,sum(ddt.diff_meter) diffmeter ,sum(ddt.const_meter/8*ddt.wrk_hours) tgmeter,
                sum(ddt.wrk_hours) wrkhrs,
                round(sum(ddt.diff_meter)/sum(ddt.const_meter/8*ddt.wrk_hours)*100,2) acteff,43 effcode
                from EMPMILL12.daily_drawing_transaction ddt 
                left join EMPMILL12.drawing_master dm on dm.drg_mc_id =ddt.drg_mc_id 
                left join vowsls.mechine_master mm on mm.mechine_id =ddt.drg_mc_id 
                where ddt.tran_date between '$fromdate' and '$todate'
                and ddt.company_id =2 and ddt.diff_meter >0 and ddt.is_active =1 and substr(mech_code,1,2) ='25'
                group by  dm.mc_group,substr(mech_code,1,2) 
                ) acteff on acteff.effcode=tewom.eff_code 
                left join EMPMILL12.tbl_all_trn_eff tate  on tate.eff_code =tewom.eff_code 
                and tate.date_from ='$fromdate' and tate.date_to ='$todate' and tate.dept_id=3
				where tewom.eff_code=43
     			) drg on drg.deptcode =tewas.dept_code and drg.occucode=tewas.occu_code           
                where tewas.date_from ='$fromdate' and tewas.date_to ='$todate'
                and tewas.pay_scheme_id =$payscheme and tewas.is_active =1 and tewas.update_from ='ATT'

            ";
			$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));


			$sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
                select  '$fromdate' df,'$todate' dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code,tewas.shift,tewas.t_p,tewas.working_hours,
                tewas.ot_hours,case when (drg.acteff /drg.eff_target)*100<100 then round( (drg.acteff /drg.eff_target)* tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
                case when (drg.acteff /drg.eff_target)*100<100 then round( (drg.acteff /drg.eff_target)* tewas.ot_hours ,2) else tewas.ot_hours end ot_hours_eff,
                $payscheme payschm, 'DRG44' updt,'PROD' updtfr, drg.acteff act_eff    
                    from EMPMILL12.tbl_ejm_wages_att_summary tewas 
                 join (
                select tewom.eff_code,acteff,tate.target_eff eff_target,tewom.deptcode,tewom.occucode from EMPMILL12.tbl_ejm_wages_occu_mast tewom 
                left join (
                select  dm.mc_group,substr(mech_code,1,2) mcgrp,sum(ddt.diff_meter) diffmeter ,sum(ddt.const_meter/8*ddt.wrk_hours) tgmeter,
                sum(ddt.wrk_hours) wrkhrs,
                round(sum(ddt.diff_meter)/sum(ddt.const_meter/8*ddt.wrk_hours)*100,2) acteff,44 effcode
                from EMPMILL12.daily_drawing_transaction ddt 
                left join EMPMILL12.drawing_master dm on dm.drg_mc_id =ddt.drg_mc_id 
                left join vowsls.mechine_master mm on mm.mechine_id =ddt.drg_mc_id 
                where ddt.tran_date between '$fromdate' and '$todate'
                and ddt.company_id =2 and ddt.diff_meter >0 and ddt.is_active =1 and substr(mech_code,1,2) ='29'
                group by  dm.mc_group,substr(mech_code,1,2) 
                ) acteff on acteff.effcode=tewom.eff_code 
                left join EMPMILL12.tbl_all_trn_eff tate  on tate.eff_code =tewom.eff_code 
                and tate.date_from ='$fromdate' and tate.date_to ='$todate' and tate.dept_id=3
				where tewom.eff_code=44
     			) drg on drg.deptcode =tewas.dept_code and drg.occucode=tewas.occu_code           
                where tewas.date_from ='$fromdate' and tewas.date_to ='$todate'
                and tewas.pay_scheme_id =$payscheme and tewas.is_active =1 and tewas.update_from ='ATT'

            ";
			$result = $this->db->query($sql);
            
            if ($result) {
      //          log_message('info', 'MainWagesProcessdrg completed');
                return array('success' => true, 'message' => 'DRG processing completed');
            } else {
        //        log_message('error', 'MainWagesProcessdrg Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
        //    log_message('error', 'MainWagesProcessdrg Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

   	public function MainWagesProcesssprd($fromdate, $todate, $payscheme) {
    		try {
 			// Validate date format
            $comany_id=$this->session->userdata('companyId');
			$from = DateTime::createFromFormat('Y-m-d', $fromdate);
			$to = DateTime::createFromFormat('Y-m-d', $todate);
			
			if (!$from || !$to) {
				return array('success' => false, 'message' => 'Invalid date format');
			}

			// Custom Query: Process wages from attendance summary
			$sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
            ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
            select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,t_p,tewas.working_hours,tewas.ot_hours,
            case when sprd.acteff<100 then round(acteff/100*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
            case when sprd.acteff<100 then round(acteff/100*tewas.ot_hours,2) else tewas.ot_hours end ot_hours_eff,
            $payscheme payschm, 'SPRDI' updt,'PROD' updtfr, sprd.acteff act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join 
            (
            select df,dt,eb_id,tewom.deptcode,tewom.occucode,shift, sprd.eff_code,sum(weight) weight,sum(tate.target_eff/8*whrs) tweight,
            round(sum(weight)/sum(tate.target_eff/8*whrs)*100,2) acteff,100 tareff from (
            select sle.*,sle.hours-ifnull(bde.total_hours,0) whrs,case when mech_code in ('12001','12002') then 
            sle.production*102 else sle.production*58 end weight from (
            select tran_date,spell,'$fromdate' df,'$todate' dt,substr(spell,1,1) shift,1 eff_code,feeder_id eb_id,sle.hours,sle.production, 
            sle.prod_type,sle.mechine_id,sle.is_active  
            from EMPMILL12.spreader_lapping_entries sle 
            union all
            select tran_date,spell,'$fromdate' df,'$todate' dt,substr(spell,1,1) shift,1 eff_code,receiver_id eb_id
            ,sle.hours,sle.production ,sle.prod_type  ,sle.mechine_id,sle.is_active  
            from EMPMILL12.spreader_lapping_entries sle 
            ) sle
            left join EMPMILL12.break_down_entries bde on sle.mechine_id =bde.mechine_id and sle.tran_date =bde.tran_date 
            left join vowsls.mechine_master mm on mm.mechine_id =sle.mechine_id 
            and sle.spell =bde.spell
            where sle.prod_type =0 and sle.tran_date between '$fromdate' and '$todate' 
            and sle.is_active =1
            ) sprd
            left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =sprd.df and tate.date_to =sprd.dt and tate.eff_code =sprd.eff_code 
            left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.eff_code =sprd.eff_code and tewom.effcheck ='CI'
            group by df,dt,eff_code,shift,eb_id,tewom.deptcode,tewom.occucode 
            ) sprd on tewas.dept_code =sprd.deptcode and tewas.occu_code =sprd.occucode and sprd.shift =tewas.shift and tewas.eb_id =sprd.eb_id 
            where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT'
            and sprd.acteff is not null
            ";
			$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));



            $sql = "	insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
                ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
                select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,t_p,tewas.working_hours,tewas.ot_hours,
                case when sprd.acteff<100 then round(acteff/100*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
                case when sprd.acteff<100 then round(acteff/100*tewas.ot_hours,2) else tewas.ot_hours end ot_hours_eff,
                $payscheme payschm, 'SPRDG' updt,'PROD' updtfr, sprd.acteff act_eff
                from EMPMILL12.tbl_ejm_wages_att_summary tewas 
                left join 
                (
                select df,dt,tewom.deptcode,tewom.occucode, sprd.eff_code,sum(weight) weight,sum(tate.target_eff/8*whrs) tweight,
                round(sum(weight)/sum(tate.target_eff/8*whrs)*100,2) acteff,100 tareff from (
                select sle.*,sle.hours-ifnull(bde.total_hours,0) whrs,case when mech_code in ('12001','12002') then 
                sle.production*102 else sle.production*58 end weight from (
                select tran_date,spell,'$fromdate' df,'$todate' dt,substr(spell,1,1) shift,1 eff_code,feeder_id eb_id,sle.hours,sle.production, 
                sle.prod_type,sle.mechine_id,sle.is_active  
                from EMPMILL12.spreader_lapping_entries sle 
                union all
                select tran_date,spell,'$fromdate' df,'$todate' dt,substr(spell,1,1) shift,1 eff_code,receiver_id eb_id
                ,sle.hours,sle.production ,sle.prod_type  ,sle.mechine_id,sle.is_active  
                from EMPMILL12.spreader_lapping_entries sle 
                ) sle
                left join EMPMILL12.break_down_entries bde on sle.mechine_id =bde.mechine_id and sle.tran_date =bde.tran_date 
                left join vowsls.mechine_master mm on mm.mechine_id =sle.mechine_id 
                and sle.spell =bde.spell
                where sle.prod_type =0 and sle.tran_date between '$fromdate' and '$todate' 
                and sle.is_active =1
                ) sprd
                left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =sprd.df and tate.date_to =sprd.dt and tate.eff_code =sprd.eff_code 
                left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.eff_code =sprd.eff_code and tewom.effcheck ='CG'
                group by df,dt,eff_code,tewom.deptcode,tewom.occucode 
                ) sprd on tewas.dept_code =sprd.deptcode and tewas.occu_code =sprd.occucode   
                where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT'
                and sprd.acteff is not null
            ";
			$result = $this->db->query($sql);
            
            if ($result) {
          //      log_message('info', 'MainWagesProcesssprd completed');
                return array('success' => true, 'message' => 'SPRD processing completed');
            } else {
           //     log_message('error', 'MainWagesProcesssprd Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
        //    log_message('error', 'MainWagesProcesssprd Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

   	public function MainWagesProcessspinner($fromdate, $todate, $payscheme) {
    		try {
 			// Validate date format
            $comany_id=$this->session->userdata('companyId');
			$from = DateTime::createFromFormat('Y-m-d', $fromdate);
			$to = DateTime::createFromFormat('Y-m-d', $todate);
			
			if (!$from || !$to) {
				return array('success' => false, 'message' => 'Invalid date format');
			}

			// Custom Query: Process wages from attendance summary
			$sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
            ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
            select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
            $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join 
            (
            select doff.*,tate.target_eff  from (
            select '$fromdate' df,'$todate' dt,vpddd.eb_id ,substr(vpddd.spell,1,1) shift  ,sum(prod) prod,
            sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code  
            from EMPMILL12.view_proc_daily_doff_details vpddd 
            where vpddd.doffdate between '$fromdate' and '$todate' and compid=$comany_id and vpddd.frameno <60    
            group by vpddd.eb_id,substr(vpddd.spell,1,1) 
            ) doff 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
            ) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
            left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
            tewom.eff_code =4 and tewom.effcheck ='CI' 
            where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
            and tewas.occu_code ='01' and tewas.dept_code ='04' 	
                    ";
			$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));

            $sql = "insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
            ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
            select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
            $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join 
            (
            select doff.*,tate.target_eff  from (
            select '$fromdate' df,'$todate' dt,vpddd.eb_id ,substr(vpddd.spell,1,1) shift  ,sum(prod) prod,
            sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code  
            from EMPMILL12.view_proc_daily_doff_details vpddd 
            where vpddd.doffdate between '$fromdate' and '$todate' and vpddd.compid=$comany_id and vpddd.frameno >=60    
            group by vpddd.eb_id,substr(vpddd.spell,1,1) 
            ) doff 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
            ) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
            left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
            tewom.eff_code =4 and tewom.effcheck ='CI' 
            where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
            and tewas.occu_code ='06' and tewas.dept_code ='04' 	
	
                    ";
			$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));



            $sql="
            insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
            ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
            select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
            case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
            $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join 
            (
            select doff.*,tate.target_eff  from (
            select '$fromdate' df,'$todate' dt,da.eb_id ,substr(vpddd.spell,1,1) shift  ,
            sum(prod) prod,
            sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code
            from EMPMILL12.view_proc_daily_doff_details vpddd 
            left join vowsls.daily_ebmc_attendance dea  on dea.attendace_date =vpddd.doffdate and dea.spell =vpddd.spell 
            and dea.mc_id =vpddd.mechine_id and dea.is_active =1
            left join vowsls.daily_attendance da on da.daily_atten_id =dea.daily_atten_id and da.is_active =1
            where vpddd.doffdate between '$fromdate' and '$todate'  and vpddd.compid=$comany_id and  da.worked_designation_id =51 
            group by da.eb_id,substr(vpddd.spell,1,1)
            ) doff 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
            ) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
            left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
            tewom.eff_code =4 and tewom.effcheck ='CI' 
            where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
            and tewas.occu_code ='02' and tewas.dept_code ='04'"; 	
			$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));
	
	
	$sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
    $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
	from EMPMILL12.tbl_ejm_wages_att_summary tewas 
	left join 
	(
	select doff.*,tate.target_eff  from (
	select '$fromdate' df,'$todate' dt,da.eb_id ,substr(vpddd.spell,1,1) shift  ,sum(prod) prod,
	sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code
	from EMPMILL12.view_proc_daily_doff_details vpddd 
	left join vowsls.daily_ebmc_attendance dea  on dea.attendace_date =vpddd.doffdate and dea.spell =vpddd.spell 
	and dea.mc_id =vpddd.mechine_id and dea.is_active =1
	left join vowsls.daily_attendance da on da.daily_atten_id =dea.daily_atten_id and da.is_active =1
	where vpddd.doffdate between '$fromdate' and '$todate'  and vpddd.compid=$comany_id and  da.worked_designation_id =196  
	group by da.eb_id,substr(vpddd.spell,1,1)
	) doff 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
	) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
	left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
	tewom.eff_code =4 and tewom.effcheck ='CI' 
	where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
    and tewas.occu_code ='07' and tewas.dept_code ='04'";
    	$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));
	

    
   $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from)
	select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
    $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
	from EMPMILL12.tbl_ejm_wages_att_summary tewas 
	left join 
	(
	select doff.*,tate.target_eff  from (
	select '$fromdate' df,'$todate' dt,da.eb_id ,substr(vpddd.spell,1,1) shift  ,sum(prod) prod,
	sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code
	from EMPMILL12.view_proc_daily_doff_details vpddd 
	left join vowsls.daily_ebmc_attendance dea  on dea.attendace_date =vpddd.doffdate and dea.spell =vpddd.spell 
	and dea.mc_id =vpddd.mechine_id and dea.is_active =1
	left join vowsls.daily_attendance da on da.daily_atten_id =dea.daily_atten_id and da.is_active =1
	where vpddd.doffdate between '$fromdate' and '$todate' and vpddd.compid=$comany_id and  da.worked_designation_id =52 
	group by da.eb_id,substr(vpddd.spell,1,1)
	) doff 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
	) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
	left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
	tewom.eff_code =4 and tewom.effcheck ='CI' 
	where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
    and tewas.occu_code ='19' and tewas.dept_code ='04' ";
    $result = $this->db->query($sql, array($fromdate, $todate, $payscheme));
    


   $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from)
	select df,dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code ,tewas.shift,'T' t_p,tewas.working_hours,tewas.ot_hours,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.working_hours,2) else tewas.working_hours end  working_hours_eff,
	case when (eff/target_eff*100)<100 then round(eff/target_eff*tewas.ot_hours,2) else tewas.ot_hours end  ot_hours_eff,
    $payscheme payschm, 'DOFFS' updt,'PROD' updtfr, doff.eff act_eff
	from EMPMILL12.tbl_ejm_wages_att_summary tewas 
	left join 
	(
	select doff.*,tate.target_eff  from (
	select '$fromdate' df,'$todate' dt,da.eb_id ,substr(vpddd.spell,1,1) shift  ,sum(prod) prod,
	sum(prod100) targetprod,round(sum(prod)/sum(prod100)*100,2) eff, 4 eff_code
	from EMPMILL12.view_proc_daily_doff_details vpddd 
	left join vowsls.daily_ebmc_attendance dea  on dea.attendace_date =vpddd.doffdate and dea.spell =vpddd.spell 
	and dea.mc_id =vpddd.mechine_id and dea.is_active =1
	left join vowsls.daily_attendance da on da.daily_atten_id =dea.daily_atten_id and da.is_active =1
	where vpddd.doffdate between '$fromdate' and '$todate' and vpddd.compid=$comany_id and  da.worked_designation_id =197
	group by da.eb_id,substr(vpddd.spell,1,1)
	) doff 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.date_from =doff.df and tate.date_to =doff.dt and tate.eff_code =doff.eff_code 
	) doff	on  tewas.shift= doff.shift and tewas.eb_id=doff.eb_id
	left join EMPMILL12.tbl_ejm_wages_occu_mast tewom  on tewom.deptcode =tewas.dept_code and tewom.occucode =tewas.occu_code and
	tewom.eff_code =4 and tewom.effcheck ='CI' 
	where tewas.pay_scheme_id =$payscheme and tewas.update_from ='ATT' and tewas.is_active =1
    and tewas.occu_code ='20' and tewas.dept_code ='04' 	
	";
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));



     $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr, tew.acteff AS act_eff FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
	select  sum(totprd) totprd,sum(tottarget) tottarget,sum(tottarget*target_eff/100),round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) eff, 2 eff_code  from
	(
	select eff_code,sum(prd_a +prd_b+prd_c) totprd,sum(tarprda+tarprdb+tarprdc) tottarget from (
	select sdt.*, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate' and sdt.company_id=$comany_id and substr(q_code,1,1) in ('1','2')
	) g group by eff_code
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG'
) tew 
ON tew.deptcode = tewas.dept_code
   AND tew.occucode = tewas.occu_code
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null";
	
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));
	

	 $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
	select  sum(totprd) totprd,sum(tottarget) tottarget,sum(tottarget*target_eff/100),round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) eff, 3 eff_code  from
	(
	select eff_code,sum(prd_a +prd_b+prd_c) totprd,sum(tarprda+tarprdb+tarprdc) tottarget from (
	select sdt.*, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate' and sdt.company_id=$comany_id and substr(q_code,1,1)='3'
	) g group by eff_code
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG' 
) tew 
ON tew.deptcode = tewas.dept_code  
   AND tew.occucode = tewas.occu_code
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null ";
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));


//    echo 'spirn check';        
 	
  	 $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
	select  sum(totprd) totprd,sum(tottarget) tottarget,sum(tottarget*target_eff/100),round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) eff, 4 eff_code  from
	(
	select eff_code,sum(prd_a +prd_b+prd_c) totprd,sum(tarprda+tarprdb+tarprdc) tottarget from (
	select sdt.*, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate' and sdt.company_id=$comany_id and substr(q_code,1,1) in ('1','2')
	) g group by eff_code
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG' 
) tew 
ON tew.deptcode = tewas.dept_code  
   AND tew.occucode = tewas.occu_code
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null ";
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));

  

    
	
  	$sql=" insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr, tew.acteff AS act_eff FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,shift,tewom.eff_code,tewom.deptcode,tewom.occucode,eff acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
	select  shift,sum(totprd) totprd,sum(tottarget) tottarget,sum(tottarget*target_eff/100),round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) eff, 24 eff_code  from
	(
	select shift,24 eff_code,sum(prd ) totprd,sum(tarprd) tottarget from (
	select 'A' shift,prd_a prd,tarprda tarprd, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select 'B' shift,prd_b prd,tarprdb tarprd, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select 'C' shift,prd_c prd,tarprdc tarprd, case when substr(q_code,1,1) in ('1','2') then 4 else 3 end eff_code  from EMPMILL12.spining_daily_transaction sdt 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	) g group by shift
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
	group by shift
) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG' 
) tew 
ON tew.deptcode = tewas.dept_code  
   AND tew.occucode = tewas.occu_code and tew.shift=tewas.shift
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null ";
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));


  

        
  	 $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr, tew.acteff AS act_eff FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,shift,tewom.eff_code,tewom.deptcode,tewom.occucode,ifnull(eff,0) acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
	select  shift,sum(totprd) totprd,sum(tottarget) tottarget,
	case when tottarget>0 then sum(tottarget*target_eff/100) else 0 end tgpdeff,
	case when totprd>0 then round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) else 0 end eff, 7 eff_code  from
	(
	select shift,7 eff_code,sum(prd ) totprd,sum(tarprd) tottarget from (
	select sdt.tran_date, 'A' shift,prd_a prd,tarprda tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select sdt.tran_date,'B' shift,prd_b prd,tarprdb tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
 	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select sdt.tran_date,'C' shift,prd_c prd,tarprdc tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
 	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	) g where subgrp in ('WP','YN')
	group by shift
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
	group by shift
) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG' 
) tew 
ON tew.deptcode = tewas.dept_code  
   AND tew.occucode = tewas.occu_code  and tew.shift =tewas.shift 
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null ";
		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));

        
  

  	$sql=" insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,act_eff)
	SELECT
    df,
    dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'T' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN acteff < 100 THEN ROUND(acteff/100 * tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'SPGAV' AS updt,
    'PROD' AS updtfr, tew.acteff AS act_eff FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN  
(
select '$fromdate' df,'$todate' dt,shift,tewom.eff_code,tewom.deptcode,tewom.occucode,ifnull(eff,0) acteff    from 
EMPMILL12.tbl_ejm_wages_occu_mast tewom 
 join 
( 
select  shift,sum(totprd) totprd,sum(tottarget) tottarget,
	case when tottarget>0 then sum(tottarget*target_eff/100) else 0 end tgpdeff,
	case when totprd>0 then round(sum(totprd)/sum(tottarget*target_eff/100)*100,2) else 0 end eff, 8 eff_code  from
	(
	select shift,8 eff_code,sum(prd ) totprd,sum(tarprd) tottarget from (
	select sdt.tran_date, 'A' shift,prd_a prd,tarprda tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select sdt.tran_date,'B' shift,prd_b prd,tarprdb tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
 	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code 
	where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	union all
	select sdt.tran_date,'C' shift,prd_c prd,tarprdc tarprd, case when substr(sdt.q_code,1,1) in ('1','2') then 4 else 3 end eff_code,substr(sm.subgroup_type,3,2 ) subgrp  from EMPMILL12.spining_daily_transaction sdt 
 	left join EMPMILL12.spining_master sm on sm.q_code =sdt.q_code where sdt.tran_date between '$fromdate' AND '$todate'  and sdt.company_id=$comany_id
	) g where subgrp in ('WT')
	group by shift
	) sdt 
	left join EMPMILL12.tbl_all_trn_eff tate on tate.eff_code=sdt.eff_code and tate.date_from='$fromdate' and tate.date_to='$todate'
	group by shift
	) sdt on tewom.eff_code =sdt.eff_code and tewom.effcheck ='CG' 
) tew 
ON tew.deptcode = tewas.dept_code  
   AND tew.occucode = tewas.occu_code  and tew.shift =tewas.shift 
WHERE tewas.pay_scheme_id = $payscheme
  AND tewas.update_from   = 'ATT'
  AND tewas.is_active     = 1
  and df is not null ";
    		$result = $this->db->query($sql, array($fromdate, $todate, $payscheme));
    		
    		if ($result) {
    	//		log_message('info', 'MainWagesProcessspinner completed');
    			return array('success' => true, 'message' => 'Spinner processing completed');
    		} else {
    	//		log_message('error', 'MainWagesProcessspinner Error: ' . $this->db->error()['message']);
    			return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
    		}
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcessspinner Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

    public function MainWagesProcesswinding($fromdate, $todate, $payscheme) {
        try {
            $comany_id = $this->session->userdata('companyId');

            $fndate = date('Y-m-d', strtotime($todate . ' +1 day'));
            // TODO: Add your winding process SQL query here
            $sql = "   
  	 insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,prod_basic,time_basic,act_eff)
	SELECT
    '$fromdate' df,'$todate' dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100 THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100 THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
    $payscheme AS payschm,
    'WDGWP' AS updt,
    'PROD' AS updtfr,round(wnd.target_eff/8*(working_hours+ot_hours),2) prdbas,
    case when payschm=151 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100 
    then   (tewas.working_hours*13.5)/3*2+  (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*13.5)/3 )
    when payschm=151 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100 
    then   (tewas.working_hours*13.5)
    when payschm=125 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100 
    then   (tewas.working_hours*twor.f_b_rate )/3*2+  (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*twor.f_b_rate)/3 )
    when payschm=125 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100 
    then   (tewas.working_hours*twor.f_b_rate)
    else 0 end time_basic,    wnd.act_eff AS act_eff
    FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
    left join 
 			( 
  			select eb_id,g.eb_no,deptcode,occu_code,shift,wage_code,prod,tate.target_eff,$payscheme payschm   from (
            select concat(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
                        '00000000000000000000000000000','".$fndate."') prods,eb_no,deptcode,occu_code,shift,wage_code,prod from (
                        select b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE,case when deptcode='06' then round(sum(production)/14,0) else sum(production) end prod from
                        (select  tran_date,substr(spell,1,1) shift,eb_no,wnd_q_code,sum(prod) production
                        from EMPMILL12.allwindingdata a where tran_date   between '$fromdate' AND '$todate'  
                         and company_id =$comany_id
                        group by tran_date,substr(spell,1,1),eb_no,wnd_q_code 
                        ) a 
                        left join EMPMILL12.winding_wages_link b on a.wnd_q_code=b.wnd_q_code
                        group by b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE
                        ) g where substr(eb_no,1,1) in ('1','0','5','8') and occu_code<>'55'
            ) g          
            left join worker_master wm on wm.eb_no=g.eb_no and wm.company_id =$comany_id
            left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =g.wage_code and tate.date_from ='$fromdate' and tate.date_to='$todate'
            ) wnd on wnd.eb_id=tewas.eb_id and wnd.deptcode =tewas.dept_code and wnd.occu_code =tewas.occu_code 
			and wnd.shift =tewas.shift 
			left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =wnd.wage_code and tqr.dept_code=wnd.deptcode   
			left join EMPMILL12.tbl_wages_occu_rate twor on wnd.deptcode =twor.dept_code and wnd.occu_code =twor.occu_code 
			where tewas.dept_code in ('05') and tewas.occu_code in ('01','02')
			and tewas.pay_scheme_id =$payscheme";

		

  	 $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,prod_basic,time_basic,act_eff)

	SELECT
    '$fromdate' df,'$todate' dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
    CASE
        WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100 THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.working_hours, 2)
        ELSE tewas.working_hours
    END AS working_hours_eff,
    CASE
        WHEN ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100) < 100 THEN ROUND(((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*tewas.ot_hours, 2)
        ELSE tewas.ot_hours
    END AS ot_hours_eff,
     payschm,
    'WDGWT' AS updt,
    'PROD' AS updtfr,round(wnd.target_eff/8*(working_hours+ot_hours),2) prdbas,
    case when payschm=151 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100 
    then   (tewas.working_hours*13.5)/3*2+  (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*13.5)/3 )
    when payschm=151 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100 
    then   (tewas.working_hours*13.5)
    when payschm=125 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)<100 
    then   (tewas.working_hours*twor.f_b_rate )/3*2+  (((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff)*(tewas.working_hours*twor.f_b_rate)/3 )
    when payschm=125 and ((wnd.prod/(tewas.working_hours+ot_hours)*8)/target_eff*100)>=100 
    then   (tewas.working_hours*twor.f_b_rate)
    else 0 end time_basic,    wnd.act_eff AS act_eff
    FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
  			left join 
 			( 
  			select eb_id,g.eb_no,deptcode,occu_code,shift,wage_code,prod,tate.target_eff,$payscheme payschm   from (
            select concat(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
                        '00000000000000000000000000000','".$fndate."') prods,eb_no,deptcode,occu_code,shift,wage_code,prod from (
                        select b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE,case when deptcode='06' then round(sum(production)/14,0) else sum(production) end prod from
                        (select  tran_date,substr(spell,1,1) shift,eb_no,wnd_q_code,sum(prod) production
                        from EMPMILL12.allwindingdata a where tran_date   between '$fromdate' AND '$todate'  
                         and company_id =$comany_id
                        group by tran_date,substr(spell,1,1),eb_no,wnd_q_code 
                        ) a 
                        left join EMPMILL12.winding_wages_link b on a.wnd_q_code=b.wnd_q_code
                        group by b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE
                        ) g where substr(eb_no,1,1) in ('1','0','5','8') and occu_code<>'55'
            ) g          
            left join worker_master wm on wm.eb_no=g.eb_no and wm.company_id =$comany_id
            left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =g.wage_code and tate.date_from ='$fromdate' and tate.date_to='$todate'
            ) wnd on wnd.eb_id=tewas.eb_id and wnd.deptcode =tewas.dept_code and wnd.occu_code =tewas.occu_code 
			and wnd.shift =tewas.shift 
			left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =wnd.wage_code and tqr.dept_code=wnd.deptcode   
			left join EMPMILL12.tbl_wages_occu_rate twor on wnd.deptcode =twor.dept_code and wnd.occu_code =twor.occu_code 
			where tewas.dept_code in ('06') and tewas.occu_code in ('01','02')
			and tewas.pay_scheme_id =$payscheme
";
            $result = $this->db->query($sql);
            
            if ($result) {
    //            log_message('info', 'MainWagesProcesswinding completed');
                return array('success' => true, 'message' => 'Winding processing completed');
            } else {
    //            log_message('error', 'MainWagesProcesswinding Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcesswinding Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }



      public function MainWagesProcessbeaming($fromdate, $todate, $payscheme) {
        // Register a shutdown handler to detect unexpected connection loss
        register_shutdown_function(function() {
            log_message('warning', 'MainWagesProcessbeaming: Connection closed or script terminated unexpectedly');
        });
        
        try {
            $company_id = $this->session->userdata('companyId');
            
            // START TRANSACTION - Ensures data consistency
            $this->db->query("START TRANSACTION");
            
            // Use CTEs (Common Table Expressions) instead of temporary tables
            $sql="insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
    	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,prod_basic,time_basic,act_eff)

WITH 
-- ============================================================
-- STEP 1: Get raw beaming production with cuts, rate, hours
-- ============================================================
        raw_beaming_data AS (
            SELECT 
                mm.mechine_id,
                mm.mech_code,
                SUBSTR(bdp.spell, 1, 1)         AS shift,
                ewql.wages_code,
                SUM(bdp.no_of_cuts)             AS totcuts,
                bdp.tran_date,
                bdp.quality_code
            FROM vowsls.beaming_daily_production bdp
            LEFT JOIN vowsls.mechine_master mm 
                ON mm.mechine_id = bdp.beam_mc_no
            LEFT JOIN vowsls.department_master dm 
                ON dm.company_id = bdp.company_id 
                AND dm.dept_code = '07'
            LEFT JOIN EMPMILL12.tbl_prod_wages_code_link ewql 
                ON bdp.quality_code = ewql.prod_code 
                AND ewql.dept_id = dm.dept_id
            WHERE bdp.company_id = $company_id
            AND bdp.tran_date BETWEEN '$fromdate' AND '$todate'
            AND bdp.is_active = 1
            GROUP BY 
                mm.mechine_id, mm.mech_code,
                SUBSTR(bdp.spell, 1, 1),
                ewql.wages_code,
                bdp.tran_date, bdp.quality_code
        ),
        -- ============================================================
        -- STEP 2: Attach target efficiency and rate per quality/wages_code
        -- ============================================================
        raw_with_rate AS (
            SELECT 
                r.mechine_id,
                r.mech_code,
                r.shift,
                r.wages_code,
                r.totcuts,
                r.tran_date,
                IFNULL(tate.target_eff, 0)      AS target_eff,
                IFNULL(tqr.rate, 0)             AS rate
            FROM raw_beaming_data r
            LEFT JOIN EMPMILL12.tbl_all_trn_eff tate 
                ON tate.qual_code  = r.wages_code
                AND tate.date_from  = '$fromdate' 
                AND tate.date_to    = '$todate'
            LEFT JOIN EMPMILL12.tbl_quality_rate tqr 
                ON tqr.qcode = r.wages_code
        ),
        -- ============================================================
        -- STEP 3: Machine + Shift wise breakdown hours
        -- ============================================================
        breakdown_hrs AS (
            SELECT 
                mechine_id,
                SUBSTR(spell, 1, 1)             AS shift,
                SUM(total_hours)                AS brkhrs
            FROM EMPMILL12.break_down_entries
            WHERE tran_date BETWEEN '$fromdate' AND '$todate'
            GROUP BY mechine_id, SUBSTR(spell, 1, 1)
        ),
        -- ============================================================
        -- STEP 4: Machine + Shift wise available working hours
        --         (SUM of worker hours / 3 because 3 workers per machine)
        -- ============================================================
        available_hrs AS (
            SELECT 
                dea.mc_id,
                SUBSTR(da.spell, 1, 1)          AS shift,
                SUM(da.working_hours) / 3       AS whrs
            FROM vowsls.daily_attendance da
            LEFT JOIN vowsls.daily_ebmc_attendance dea 
                ON dea.daily_atten_id = da.daily_atten_id
            WHERE da.attendance_date BETWEEN '$fromdate' AND '$todate'
            AND da.worked_department_id = 7 
            AND da.company_id = $company_id
            AND da.worked_designation_id IN (501, 67)
            GROUP BY dea.mc_id, SUBSTR(da.spell, 1, 1)
        ),
        -- ============================================================
        -- STEP 5: Aggregate MC + Shift wise: total qty, amount, working hrs
        -- ============================================================
        mc_shift_summary AS (
            SELECT 
                r.mechine_id,
                r.mech_code,
                r.shift,
                MAX(r.target_eff)               AS target_eff,
                IFNULL(MAX(ah.whrs), 0)         AS whrs,
                IFNULL(MAX(bh.brkhrs), 0)       AS brkhrs,
                IFNULL(MAX(ah.whrs), 0) 
                    - IFNULL(MAX(bh.brkhrs), 0) AS wkhrs,           -- Net working hours
                SUM(r.totcuts)                  AS total_qty,
                SUM(r.totcuts * r.rate)         AS total_amount      -- Total earned amount
            FROM raw_with_rate r
            LEFT JOIN breakdown_hrs bh 
                ON bh.mechine_id = r.mechine_id 
                AND bh.shift      = r.shift
            LEFT JOIN available_hrs ah 
                ON ah.mc_id = r.mechine_id 
                AND ah.shift  = r.shift
            GROUP BY r.mechine_id, r.mech_code, r.shift
        ),
        -- ============================================================
        -- STEP 6: Calculate MC + Shift wise EFFICIENCY and RATE/HOUR
        -- ============================================================
        mc_shift_efficiency AS (
            SELECT 
                mechine_id,
                mech_code,
                shift,
                target_eff,
                whrs,
                wkhrs,
                total_qty,
                total_amount,
                -- Rate per working hour  
                CASE 
                    WHEN wkhrs > 0 
                    THEN ROUND((total_amount / wkhrs)/3, 4)
                    ELSE 0 
                END AS rate_per_hour,
                -- Actual efficiency %
                -- Formula: (actual qty / target qty possible in wkhrs) * 100
                -- Target qty possible = (target_eff / 8) * wkhrs
                CASE 
                    WHEN target_eff > 0 AND wkhrs > 0 
                    THEN ROUND(
                            total_qty / ((target_eff / 8) * wkhrs) * 100
                        , 2)
                    ELSE 0 
                END AS act_eff
            FROM mc_shift_summary
            )
        SELECT
            '$fromdate'                                            AS date_from,
            '$todate'                                              AS date_to,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            'P'                                                     AS t_p,
            tewas.working_hours,
            tewas.ot_hours,
            -- Effective working hours adjusted for efficiency
            CASE
                WHEN tewas.working_hours > 0 AND mse.act_eff > 0 AND mse.act_eff <= 100 
                THEN ROUND(tewas.working_hours/100*  (mse.act_eff ), 2)
                ELSE tewas.working_hours
            END     AS working_hours_eff,
            -- Effective OT hours adjusted for efficiency
            CASE
                WHEN tewas.ot_hours > 0 AND mse.act_eff > 0 AND mse.act_eff <= 100 
                THEN ROUND(tewas.ot_hours /100*  (mse.act_eff ), 2)
                ELSE tewas.ot_hours
            END                                                     AS ot_hours_eff,
            tewas.pay_scheme_id,
            'WDGWT'                                                 AS update_for,
            'PROD'                                                  AS updt_from,
            -- Production basic = rate/hour * employee's working hours
            ROUND(mse.rate_per_hour * tewas.working_hours, 2)       AS prod_basic,
            -- Time basic depends on pay scheme and efficiency
            CASE 
                WHEN tewas.pay_scheme_id = 151 AND mse.act_eff < 100 
                THEN 
                    -- 2/3 time basic + production basic
                    ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2, 2)
                    + ROUND ((twor.f_b_rate  * tewas.working_hours/3)*act_eff/100 , 2)
                WHEN tewas.pay_scheme_id = 151 AND mse.act_eff >= 100 
                THEN 
                    -- Full time basic only
                    ROUND(tewas.working_hours * twor.f_b_rate, 2)
                WHEN tewas.pay_scheme_id = 125 AND mse.act_eff < 100 
                THEN 
                    -- 2/3 time basic + proportional production basic
                    ROUND((tewas.working_hours * 13.5) / 3 * 2, 2)
                    + ROUND(
                        ((mse.rate_per_hour * 8) / (mse.act_eff / 100))
                        * ((tewas.working_hours * 13.5) / 3)
                    , 2)
                WHEN tewas.pay_scheme_id = 125 AND mse.act_eff >= 100 
                THEN 
                    -- Full time basic only
                    ROUND(tewas.working_hours * 13.5, 2)
                ELSE 0 
            END  AS time_basic   ,act_eff
        FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
        -- Join on MC code + Shift to get efficiency and rate/hour
        LEFT JOIN mc_shift_efficiency mse 
            ON mse.mech_code = tewas.mc_nos 
            AND mse.shift     = tewas.shift
        -- Join for occupation-level fixed basic rate (for pay scheme 125)
        LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor 
            ON twor.dept_code = tewas.dept_code 
            AND twor.occu_code = tewas.occu_code
        WHERE tewas.dept_code   IN ('07') 
        AND tewas.occu_code   IN ('01', '02')
        AND tewas.pay_scheme_id = $payscheme
        AND tewas.update_from   = 'ATT'
        AND tewas.is_active     = 1
        and tewas.date_from ='$fromdate' and tewas.date_to ='$todate'
";
         
            log_message('info', 'MainWagesProcessbeaming: Executing main insert query =='. $sql );

            $this->db->query($sql);
 
            // COMMIT TRANSACTION - All operations successful
            $this->db->query("COMMIT");
            
      //      log_message('info', 'MainWagesProcessbeaming: Completed successfully using CTEs');
            
            return array(
                'success' => true,
                'message' => 'Beaming processing completed successfully'
            );
             
        } catch (Exception $e) {
            // ROLLBACK on any error - reverts all changes
            $this->db->query("ROLLBACK");
            
            $errorMsg = 'MainWagesProcessbeaming Exception: ' . $e->getMessage();
            log_message('error', $errorMsg);
            
            return array(
                'success' => false, 
                'message' => 'Error: ' . $e->getMessage(),
                'status' => 'FAILED',
                'action' => 'All changes rolled back - Database is safe'
            );
        }
    }




    public function MainWagesProcessweaving($fromdate, $todate, $payscheme) {
        $fromdate = trim($fromdate);
        $todate = trim($todate);
        $payscheme = trim($payscheme);
        
     //   log_message('info', "=== MainWagesProcessweaving STARTED ===");
     //   log_message('info', "Parameters - fromdate: [$fromdate], todate: [$todate], payscheme: [$payscheme]");
        
        try {
            $company_id = $this->session->userdata('companyId');
       //     log_message('info', "Company ID from session: " . $company_id);
            
            // START TRANSACTION
            $this->db->query("START TRANSACTION");
            
            // Weaving process - insert wages data with production efficiency calculations
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection 
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic, act_eff)
            SELECT
                '$fromdate' AS df,
                '$todate' AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND((prd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND((prd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
                $payscheme AS payschm,
                'LOOMI' AS updt,
                'PROD' AS updtfr,
                ROUND(IFNULL(prd.amount, 0) / NULLIF(prd.tot_hrs, 0) * (tewas.working_hours), 2) AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND(((tewas.working_hours * 13.5) / 3 * 2) + (((tewas.working_hours * 13.5) / 3) * prd.acteff / 100), 2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(prd.acteff, 0) >= 100 
                    THEN ROUND((tewas.working_hours * 13.5), 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND((tewas.working_hours * twor.f_b_rate) / 3 * 2 + ((tewas.working_hours * twor.f_b_rate) / 3) * prd.acteff / 100, 2)
                    WHEN tewas.pay_scheme_id = 151 AND IFNULL(prd.acteff, 0) >= 100 
                    THEN ROUND((tewas.working_hours * twor.f_b_rate), 2)
                    ELSE 0
                END AS time_basic,
                prd.acteff AS act_eff
            FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
            LEFT JOIN (
                SELECT
                    wm.eb_id,
                    da.spell AS shift,
                    dm.dept_code,
                    omn.OCCU_CODE AS occu_code,
                    SUM((vpelds.diffm / NULLIF(vpelds.finished_length, 0)) * IFNULL(tqr.rate, 0)) AS amount,
                    SUM(da.working_hours) / 8 AS tot_hrs,
                    IFNULL(tate.target_eff, 100) AS target_eff,
                    ROUND(
                        IFNULL(tate.target_eff, 100) / NULLIF(100, 0) * 100, 2
                    ) AS acteff
                FROM EMPMILL12.view_proc_ejm_loom_data_spell vpelds
                LEFT JOIN EMPMILL12.tbl_prod_wages_code_link ewql ON ewql.prod_code = vpelds.qcod AND ewql.dept_id = 8
                LEFT JOIN EMPMILL12.tbl_quality_rate tqr ON ewql.wages_code = tqr.qcode AND tqr.dept_code = '08'
                LEFT JOIN EMPMILL12.tbl_all_trn_eff tate ON tate.qual_code = vpelds.qcod 
                    AND tate.dept_id = 8
                    AND tate.date_from = '$fromdate' 
                    AND tate.date_to = '$todate'
                LEFT JOIN vowsls.daily_attendance da ON da.attendance_date = vpelds.loom_date 
                    AND da.spell = vpelds.spell 
                    AND da.is_active = 1 
                    AND da.company_id = $company_id
                    AND da.worked_department_id = 8
                LEFT JOIN vowsls.department_master dm ON dm.dept_id = da.worked_department_id
                LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn ON omn.desig_id = da.worked_designation_id
                LEFT JOIN vowsls.worker_master wm ON wm.eb_no = vpelds.tktno AND wm.company_id = $company_id
                WHERE vpelds.loom_date BETWEEN '$fromdate' AND '$todate'
                    AND vpelds.company_id = $company_id
                GROUP BY wm.eb_id, da.spell, dm.dept_code, omn.OCCU_CODE
            ) prd ON tewas.eb_id = prd.eb_id 
                AND tewas.shift = prd.shift 
                AND tewas.dept_code = prd.dept_code 
                AND tewas.occu_code = prd.occu_code
            LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor ON twor.dept_code = tewas.dept_code 
                AND twor.occu_code = tewas.occu_code
            WHERE tewas.dept_code = '08'
                AND tewas.occu_code IN ('01', '04')
                AND tewas.is_active = 1
                AND tewas.pay_scheme_id = $payscheme
                AND tewas.update_from = 'ATT'";


        $sql=" insert into EMPMILL12.tbl_ejm_wages_data_collection (date_from,date_to,eb_id,dept_code,occu_code,shift,t_p,working_hours,
	ot_hours,working_hours_eff,ot_hours_eff,pay_scheme_id,update_for,updt_from,prod_basic,time_basic,act_eff)

	SELECT
    '$fromdate' AS df,
    '$todate' AS dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND((prd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 
                    THEN ROUND((prd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
    $payscheme AS payschm,
    'LOOMI' AS updt,
    'PROD' AS updtfr,
    CASE 
        WHEN IFNULL(prd.acteff, 0) > 0 THEN ROUND(prd.amount/NULLIF(prd.acteff, 0)*100, 2)
        ELSE 0
    END AS prdbas,
    CASE
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*prd.acteff/100),2)
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(prd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(prd.acteff, 0) < 100 AND IFNULL(prd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*prd.acteff/100,2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(prd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
         ELSE 0
    END AS time_basic,
    prd.acteff AS act_eff
FROM EMPMILL12.tbl_ejm_wages_att_summary tewas
LEFT JOIN (
    SELECT
        eb_id,
        shift,
        dept_code,
        occu_code,
        SUM(no_of_cuts * rate) AS amount,
        ROUND(SUM(eff * tot_hrs) / NULLIF(SUM(tot_hrs),0), 2) AS avgeff,
        ROUND(SUM(target_eff * tot_hrs) / NULLIF(SUM(tot_hrs),0), 2) AS tareff,
        ROUND(
            ROUND(SUM(eff * tot_hrs) / NULLIF(SUM(tot_hrs),0), 2)
            / NULLIF(ROUND(SUM(target_eff * tot_hrs) / NULLIF(SUM(tot_hrs),0), 2), 0)
            * 100,
            2
        ) AS acteff
    FROM (
    SELECT
            vpelds.*,
            ewql.wages_code,
            IFNULL(tqr.rate,0) AS rate,
            2 AS company_id,
            (vpelds.diffm / NULLIF(vpelds.finished_length,0)) AS no_of_cuts,
            tate.target_eff,
            dm.dept_code,
            omn.OCCU_CODE AS occu_code,
            wm.eb_id
        FROM EMPMILL12.view_proc_ejm_loom_data_spell vpelds
        LEFT JOIN EMPMILL12.tbl_prod_wages_code_link ewql
               ON ewql.prod_code = vpelds.qcod
              AND ewql.dept_id = 8
        LEFT JOIN EMPMILL12.tbl_quality_rate tqr
               ON ewql.wages_code = tqr.qcode
              AND tqr.dept_code = '08'
        LEFT JOIN EMPMILL12.tbl_all_trn_eff tate
               ON tate.qual_code = vpelds.qcod and tate.dept_id=8
                AND tate.date_from = '$fromdate'
                AND tate.date_to = '$todate'
        LEFT JOIN vowsls.daily_attendance da
               ON da.attendance_date = vpelds.loom_date
              AND da.spell = vpelds.spell
              AND da.eb_no = vpelds.tktno
              AND da.is_active = 1
              AND da.company_id = 2
        LEFT JOIN vowsls.department_master dm
               ON dm.dept_id = da.worked_department_id
        LEFT JOIN EMPMILL12.OCCUPATION_MASTER_NORMS omn
               ON omn.desig_id = da.worked_designation_id
        LEFT JOIN vowsls.worker_master wm
               ON wm.eb_no = vpelds.tktno
              AND wm.company_id = 2
        WHERE vpelds.loom_date BETWEEN '$fromdate' AND '$todate'
          AND eff > 0
          ) g
    GROUP BY eb_id, shift, dept_code, occu_code
) prd
    ON tewas.eb_id = prd.eb_id
   AND tewas.shift = prd.shift
   AND tewas.dept_code = prd.dept_code
   AND tewas.occu_code = prd.occu_code
LEFT JOIN EMPMILL12.tbl_wages_occu_rate twor
    ON tewas.dept_code = twor.dept_code
   AND tewas.occu_code = twor.occu_code
WHERE tewas.dept_code = '08'
  AND tewas.occu_code IN ('01','04')
  AND tewas.is_active = 1
  AND tewas.pay_scheme_id = $payscheme
";


//log_message('debug', 'Executing weaving process SQL');
            $result = $this->db->query($sql);
            
            // COMMIT TRANSACTION
            $this->db->query("COMMIT");

            if ($result) {
        //        log_message('info', '=== MainWagesProcessweaving COMPLETED SUCCESSFULLY ===');
                return array('success' => true, 'message' => 'Weaving processing completed');
            } else {
                $error = $this->db->error();
                $errorMsg = isset($error['message']) ? $error['message'] : (isset($error['code']) ? 'Error Code: ' . $error['code'] : 'Unknown error occurred');
          //      log_message('error', '=== MainWagesProcessweaving FAILED ===');
          //      log_message('error', 'Database Error: ' . $errorMsg);
                return array('success' => false, 'message' => 'Database Error: ' . $errorMsg, 'error_code' => isset($error['code']) ? $error['code'] : null);
            }
        } catch (Exception $e) {
            $this->db->query("ROLLBACK");
        //    log_message('error', '=== MainWagesProcessweaving EXCEPTION ===');
        //    log_message('error', 'Exception Message: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage(), 'exception_code' => $e->getCode());
        }
    }

    public function MainWagesProcesspress($fromdate, $todate, $payscheme) {
        try {
            $comany_id = $this->session->userdata('companyId');
            
            // TODO: Add your press process SQL query here

            $sql="CREATE TEMPORARY TABLE raw_press_data AS
            select fe.company_id,substr(fe.entry_date ,1,10) trandate,spell,tpwcl.wages_code,fe.machine_id , fe.production,tate.target_eff,ptm.process_code     
            from vowsls.finishing_entries fe 
            left join vowsls.process_type_master ptm on fe.work_type =ptm.process_type_id 
            left join EMPMILL12.tbl_prod_wages_code_link tpwcl on  tpwcl.prod_code =ptm.process_code and tpwcl.dept_id =9 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =tpwcl.wages_code and tate.date_from ='$fromdate' and tate.date_to ='$todate'
            where substr(fe.entry_date ,1,10) between '$fromdate' and '$todate'  and tpwcl.dept_id =tate.dept_id 
            and fe.is_active=1 and ptm.michine_type =31";
            $this->db->query($sql);

            $sql="CREATE TEMPORARY TABLE max_press_target AS
            select trandate,spell,machine_id,max(target_eff) maxtarget from raw_press_data
            group by trandate,spell,machine_id";
            $this->db->query($sql); 


            $sql="CREATE TEMPORARY TABLE conv_press_target AS
            select rpd.*,mpt.maxtarget,rpd.production/rpd.target_eff*mpt.maxtarget conv_prod  from raw_press_data rpd
            left join max_press_target mpt on rpd.trandate=mpt.trandate and rpd.spell=mpt.spell and rpd.machine_id=mpt.machine_id";
            $this->db->query($sql);


            $sql="CREATE TEMPORARY TABLE conv_press_target_wcode AS
            SELECT *
            FROM (
            SELECT t.*,
                    ROW_NUMBER() OVER (PARTITION BY trandate, spell, machine_id ORDER BY target_eff DESC) rn
            FROM conv_press_target t
            ) x
            WHERE rn = 1";   
            $this->db->query($sql);



        $sql="CREATE TEMPORARY TABLE conv_press_target_prod AS
        select cpt.*,cptw.wages_code mxwgcode,cpt.conv_prod*tqr.rate totamt from conv_press_target cpt
        left join conv_press_target_wcode cptw on cpt.trandate=cptw.trandate and cpt.spell=cptw.spell and cpt.machine_id=cptw.machine_id
        left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =cptw.wages_code and tqr.dept_code =9 ";
        $this->db->query($sql);


        $sql="CREATE TEMPORARY TABLE conv_press_target_prod_da AS
        select da.daily_atten_id,da.eb_id,da.attendance_date,spell,da.working_hours -da.idle_hours wkhrs
        from vowsls.daily_attendance da 
        where da.attendance_date between '$fromdate' and '$todate' and da.is_active =1 
        and da.worked_designation_id in (98,114)";
        $this->db->query($sql);

        $sql="CREATE TEMPORARY TABLE conv_press_target_prod_dea AS
        select * from vowsls.daily_ebmc_attendance dea 
        where dea.attendace_date between '$fromdate' and '$todate' and dea.is_active =1 
        and dea.is_active =1 and dea.designation_id in (98,114)";
        $this->db->query($sql);


        $sql="CREATE TEMPORARY TABLE conv_press_target_prod_eff AS
        select eb_id,shift,mech_code,round(sum(totamt/4),2) totamt,round(sum(convprod)/sum(tgprod)*100,2) acteff from (
        select da.eb_id,da.attendance_date,substr(da.spell,1,1) shift,da.wkhrs wkhrs,cpt.convprod,cpt.totamt,cpt.maxtarget,
        cpt.maxtarget/8*da.wkhrs tgprod,dea.mc_id,mm.mech_code  from conv_press_target_prod_da da
        left join conv_press_target_prod_dea dea on da.daily_atten_id =dea.daily_atten_id 
        left join (
        select trandate,spell,machine_id,maxtarget,sum(conv_prod) convprod,sum(totamt) totamt  from conv_press_target_prod cptp 
        group by trandate,spell,machine_id,maxtarget
        ) cpt on da.attendance_date =cpt.trandate and da.spell =cpt.spell and dea.mc_id =cpt.machine_id 
        left join vowsls.mechine_master mm on mm.mechine_id=dea.mc_id
        where da.attendance_date between '$fromdate' and '$todate'  
        ) g group by eb_id,shift,mech_code";
        $this->db->query($sql);



        $sql="INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
                    (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
                    ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic,act_eff)
            SELECT
            '$fromdate' AS df,
            '$todate' AS dt,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            'P' AS t_p,
            tewas.working_hours,
            tewas.ot_hours,
                        CASE
                            WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                            THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                            ELSE tewas.working_hours
                        END AS working_hours_eff,
                        CASE
                            WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                            THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                            ELSE tewas.ot_hours
                        END AS ot_hours_eff,
            $payscheme AS payschm,
            'PRESS' AS updt,
            'PROD' AS updtfr,
            CASE 
                WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
                ELSE 0
            END AS prdbas,
            CASE
                WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
                WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
                WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
                WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
                ELSE 0
            END AS time_basic,ehd.acteff
        from EMPMILL12.tbl_ejm_wages_att_summary tewas 
        left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
        left join conv_press_target_prod_eff ehd on tewas.shift=ehd.shift and tewas.mc_nos =ehd.mech_code and tewas.eb_id=ehd.eb_id
        where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
        and tewas.dept_code ='09' and tewas.occu_code in ('01','02')
        and tewas.pay_scheme_id =$payscheme and tewas.is_active =1
        ";
        $result=$this->db->query($sql);

            

            if ($result) {
          //      log_message('info', 'MainWagesProcesspress completed');
                return array('success' => true, 'message' => 'Press processing completed');
            } else {
          //      log_message('error', 'MainWagesProcesspress Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
        //    log_message('error', 'MainWagesProcesspress Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

    public function MainWagesProcessfinishing($fromdate, $todate, $payscheme) {
        try {
            $comany_id = $this->session->userdata('companyId');
            
            // TODO: Add your finishing process SQL query here
            //hemming opr
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection 
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic)
            with `raw_hemm_data` as (
            select fe.company_id,substr(fe.entry_date ,1,10) trandate,spell,tpwcl.wages_code,fe.eb_no, fe.production,tate.target_eff,ptm.process_code     from vowsls.finishing_entries fe 
            left join vowsls.process_type_master ptm on fe.work_type =ptm.process_type_id 
            left join EMPMILL12.tbl_prod_wages_code_link tpwcl on  tpwcl.prod_code =ptm.process_code and tpwcl.dept_id =10 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =tpwcl.wages_code and tate.date_from ='$fromdate' and tate.date_to ='$todate'
            where substr(fe.entry_date ,1,10) between '$fromdate' and '$todate' and substr(ptm.process_code,1,2)='10' 
            ),
            `raw_hemmatt_data` as (
            select da.company_id,eb_id,da.eb_no,da.attendance_date ,da.spell,da.working_hours -da.idle_hours wkhrs,dm.dept_code,omn.OCCU_CODE,rhd.wages_code, production,
            rhd.target_eff /8*(da.working_hours -da.idle_hours) targetprod
            from vowsls.daily_attendance da 
            left join department_master dm on da.worked_department_id =dm.dept_id 
            left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
            left join `raw_hemm_data` rhd on da.eb_no=rhd.eb_no and da.company_id =rhd.company_id and da.attendance_date =rhd.trandate and da.spell =rhd.spell 
            where da.attendance_date between '$fromdate' and '$todate' and dept_code='10' and omn.OCCU_CODE ='02'
            ),
            `eff_hemm_data` as (
                select eb_id,substr(spell,1,1) shift,rhd.dept_code,rhd.occu_code,sum(production) prod,sum(targetprod ) targetprod,sum(tqr.rate/25*rhd.production ) totamt,
                round(sum(production)/sum(targetprod )*100,2) acteff,100 tareff 
                from `raw_hemmatt_data` rhd
                left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =rhd.wages_code and tqr.dept_code =rhd.dept_code 
                group by eb_id,substr(spell,1,1) ,dept_code,occu_code
            )
                SELECT
                '$fromdate' AS df,
                '$todate' AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                            CASE
                                WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                                THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                                ELSE tewas.working_hours
                            END AS working_hours_eff,
                            CASE
                                WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                                THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                                ELSE tewas.ot_hours
                            END AS ot_hours_eff,
                $payscheme AS payschm,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE 
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
                    WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
                    WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
            left join `eff_hemm_data` ehd on tewas.eb_id=ehd.eb_id and tewas.shift=ehd.shift and tewas.dept_code=ehd.dept_code and tewas.occu_code=ehd.occu_code
            where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
            and tewas.dept_code ='10' and tewas.occu_code ='02'
            and tewas.pay_scheme_id =$payscheme and tewas.is_active =1
";
            $result = $this->db->query($sql);

                        //hera opr
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection 
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic, act_eff)

            with `raw_hera_data` as (
            select fe.company_id,substr(fe.entry_date ,1,10) trandate,spell,tpwcl.wages_code,fe.eb_no, fe.production,tate.target_eff,ptm.process_code     from vowsls.finishing_entries fe 
            left join vowsls.process_type_master ptm on fe.work_type =ptm.process_type_id 
            left join EMPMILL12.tbl_prod_wages_code_link tpwcl on  tpwcl.prod_code =ptm.process_code and tpwcl.dept_id =10 
            left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =tpwcl.wages_code and tate.date_from ='$fromdate' and tate.date_to ='$todate'
            where substr(fe.entry_date ,1,10) between '$fromdate' and '$todate' and substr(ptm.process_code,1,2)='20' 
            ),
            `raw_heraatt_data` as (
            select da.company_id,eb_id,da.eb_no,da.attendance_date ,da.spell,da.working_hours -da.idle_hours wkhrs,dm.dept_code,omn.OCCU_CODE,rhd.wages_code, production,
            rhd.target_eff /8*(da.working_hours -da.idle_hours) targetprod
            from vowsls.daily_attendance da 
            left join department_master dm on da.worked_department_id =dm.dept_id 
            left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
            left join `raw_hera_data` rhd on da.eb_no=rhd.eb_no and da.company_id =rhd.company_id and da.attendance_date =rhd.trandate and da.spell =rhd.spell 
            where da.attendance_date between '$fromdate' and '$todate' and dept_code='10' and omn.OCCU_CODE ='01'
            ),
            `eff_hera_data` as (
                select eb_id,substr(spell,1,1) shift,rhd.dept_code,rhd.occu_code,sum(production) prod,sum(targetprod ) targetprod,sum(tqr.rate/25*rhd.production ) totamt,
                round(sum(production)/sum(targetprod )*100,2) acteff,100 tareff 
                from `raw_heraatt_data` rhd
                left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =rhd.wages_code and tqr.dept_code =rhd.dept_code 
                group by eb_id,substr(spell,1,1) ,dept_code,occu_code
            )
                SELECT
                '$fromdate' AS df,
                '$todate' AS dt,
                tewas.eb_id,
                tewas.dept_code,
                tewas.occu_code,
                tewas.shift,
                'P' AS t_p,
                tewas.working_hours,
                tewas.ot_hours,
                            CASE
                                WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                                THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                                ELSE tewas.working_hours
                            END AS working_hours_eff,
                            CASE
                                WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                                THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                                ELSE tewas.ot_hours
                            END AS ot_hours_eff,
                $payscheme AS payschm,
                'HEMM' AS updt,
                'PROD' AS updtfr,
                CASE 
                    WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
                    ELSE 0
                END AS prdbas,
                CASE
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
                    WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
                    WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
                    WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
                    ELSE 0
                END AS time_basic,
                ehd.acteff AS act_eff
            from EMPMILL12.tbl_ejm_wages_att_summary tewas 
            left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
            left join `eff_hera_data` ehd on tewas.eb_id=ehd.eb_id and tewas.shift=ehd.shift and tewas.dept_code=ehd.dept_code and tewas.occu_code=ehd.occu_code
            where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
            and tewas.dept_code ='10' and tewas.occu_code ='01'
            and tewas.pay_scheme_id =$payscheme and tewas.is_active =1

";
            $result = $this->db->query($sql);

            //hand sewer
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection 
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic,act_eff)
             with `raw_hera_data` as (
        select fe.company_id,substr(fe.entry_date ,1,10) trandate,spell,tpwcl.wages_code,fe.eb_no, fe.production,tate.target_eff,ptm.process_code     from vowsls.finishing_entries fe 
        left join vowsls.process_type_master ptm on fe.work_type =ptm.process_type_id 
        left join EMPMILL12.tbl_prod_wages_code_link tpwcl on  tpwcl.prod_code =ptm.process_code and tpwcl.dept_id =10 
        left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =tpwcl.wages_code and tate.date_from ='$fromdate' and tate.date_to ='$todate'
        and tate.dept_id =10
        where substr(fe.entry_date ,1,10) between '$fromdate' and '$todate' and substr(ptm.process_code,1,2)='77' and fe.is_active =1
        ),
        `raw_heraatt_data` as (
        select da.company_id,eb_id,da.eb_no,da.attendance_date ,da.spell,da.working_hours -da.idle_hours wkhrs,dm.dept_code,omn.OCCU_CODE,rhd.wages_code, production,
        rhd.target_eff /8*(da.working_hours -da.idle_hours) targetprod
        from vowsls.daily_attendance da 
        left join department_master dm on da.worked_department_id =dm.dept_id 
        left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
        left join `raw_hera_data` rhd on da.eb_no=rhd.eb_no and da.company_id =rhd.company_id and da.attendance_date =rhd.trandate and da.spell =rhd.spell 
        where da.attendance_date between '$fromdate' and '$todate' and dept_code='10' and omn.OCCU_CODE ='04' and da.is_active =1
        )
        ,
        `eff_hera_data` as (
            select eb_id,substr(spell,1,1) shift,rhd.dept_code,rhd.occu_code,sum(production) prod,sum(targetprod ) targetprod,sum(tqr.rate*rhd.production ) totamt,
            round(sum(production)/sum(targetprod )*100,2) acteff,100 tareff 
            from `raw_heraatt_data` rhd
            left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode =rhd.wages_code and tqr.dept_code =rhd.dept_code 
            group by eb_id,substr(spell,1,1) ,dept_code,occu_code
        )
            SELECT
            '$fromdate' AS df,
            '$todate' AS dt,
            tewas.eb_id,
            tewas.dept_code,
            tewas.occu_code,
            tewas.shift,
            'P' AS t_p,
            tewas.working_hours,
            tewas.ot_hours,
                        CASE
                            WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                            THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                            ELSE tewas.working_hours
                        END AS working_hours_eff,
                        CASE
                            WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                            THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                            ELSE tewas.ot_hours
                        END AS ot_hours_eff,
            $payscheme AS payschm,
            'HEMM' AS updt,
            'PROD' AS updtfr,
            CASE 
                WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
                ELSE 0
            END AS prdbas,
            CASE
                WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
                WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
                WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
                WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
                ELSE 0
            END AS time_basic,ehd.acteff 
        from EMPMILL12.tbl_ejm_wages_att_summary tewas 
        left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
        left join `eff_hera_data` ehd on tewas.eb_id=ehd.eb_id and tewas.shift=ehd.shift and tewas.dept_code=ehd.dept_code and tewas.occu_code=ehd.occu_code
        where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
        and tewas.dept_code ='10' and tewas.occu_code ='04'
        and tewas.pay_scheme_id =$payscheme and tewas.is_active =1

";
//        log_message('info', 'MainWagesProcess finishing: Executing main insert query =='. $sql );
        $this->db->query($sql);

            
         // heracle helper
        $sql="CREATE TEMPORARY TABLE raw_hera_data AS
        select fe.company_id,substr(fe.entry_date ,1,10) trandate,spell,tpwcl.wages_code,fe.eb_no, fe.production,tate.target_eff,ptm.process_code     
        from vowsls.finishing_entries fe 
        left join vowsls.process_type_master ptm on fe.work_type =ptm.process_type_id 
        left join EMPMILL12.tbl_prod_wages_code_link tpwcl on  tpwcl.prod_code =ptm.process_code and tpwcl.dept_id =10 
        left join EMPMILL12.tbl_all_trn_eff tate on tate.qual_code =tpwcl.wages_code and tate.date_from ='$fromdate' and tate.date_to ='$todate'
        where substr(fe.entry_date ,1,10) between '$fromdate' and '$todate' and substr(ptm.process_code,1,2)='20' 
        and fe.is_active=1";

         $this->db->query($sql);            
        $sql="CREATE TEMPORARY TABLE raw_heraatt_data AS
        select da.company_id,da.eb_id,da.eb_no,da.attendance_date trandate,da.spell,da.working_hours -da.idle_hours wkhrs,dm.dept_code,omn.OCCU_CODE,
        rhd.wages_code, production,
        rhd.target_eff /8*(da.working_hours -da.idle_hours) targetprod,dea.mc_id,rhd.target_eff  
        from vowsls.daily_attendance da 
        left join vowsls.daily_ebmc_attendance dea on da.daily_atten_id =dea.daily_atten_id and dea.is_active =1
        left join department_master dm on da.worked_department_id =dm.dept_id 
        left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
        left join `raw_hera_data` rhd on da.eb_no=rhd.eb_no and da.company_id =rhd.company_id and da.attendance_date =rhd.trandate and da.spell =rhd.spell 
        where da.attendance_date between '$fromdate' and '$todate' and dept_code='10' and omn.OCCU_CODE ='01'
        and da.is_active=1";

         $this->db->query($sql);

        $sql="               
INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic,act_eff)
	SELECT
    '$fromdate' AS df,
    '$todate' AS dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
    $payscheme    AS payscheme,
    'HEMM' AS updt,
    'PROD' AS updtfr,
    CASE 
        WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
        ELSE 0
    END AS prdbas,
    CASE
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
         ELSE 0
    END AS time_basic,ehd.acteff AS act_eff
from EMPMILL12.tbl_ejm_wages_att_summary tewas 
left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
left join (
select da.eb_id,substr(da.spell,1,1) shift,dm.dept_code,
omn.OCCU_CODE,sum(production) production,sum(rhd.target_eff /8*(da.working_hours -da.idle_hours)) targetprod,sum(tqr.rate *rhd.wages_code) totamt,
round(sum(production)/sum(rhd.target_eff /8*(da.working_hours -da.idle_hours)) *100,2) acteff,100 targeteff
from vowsls.daily_attendance da 
left join vowsls.daily_ebmc_attendance dea on da.daily_atten_id =dea.daily_atten_id and dea.is_active =1
left join department_master dm on da.worked_department_id =dm.dept_id 
left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
left join raw_heraatt_data rhd on  da.company_id =rhd.company_id and da.attendance_date =rhd.trandate 
and da.spell =rhd.spell and rhd.mc_id =dea.mc_id 
left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode  =rhd.wages_code and tqr.dept_code =dm.dept_code 
where da.attendance_date between '$fromdate' and '$todate' and dm.dept_code='10' and omn.OCCU_CODE ='14'
and da.is_active=1
group by da.eb_id,substr(da.spell,1,1),dm.dept_code,
omn.OCCU_CODE 
) ehd on tewas.eb_id=ehd.eb_id and tewas.dept_code =ehd.dept_code and tewas.occu_code =ehd.OCCU_CODE 
where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
and tewas.dept_code ='10' and tewas.occu_code ='14'
and tewas.pay_scheme_id =$payscheme and tewas.is_active =1
";
$this->db->query($sql);


//heming helper
        $sql="INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic, act_eff)
	SELECT
    '$fromdate' AS df,
    '$todate' AS dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                    THEN ROUND((ehd.acteff / 100) * tewas.working_hours, 2)
                    ELSE tewas.working_hours
                END AS working_hours_eff,
                CASE
                    WHEN IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 
                    THEN ROUND((ehd.acteff / 100) * tewas.ot_hours, 2)
                    ELSE tewas.ot_hours
                END AS ot_hours_eff,
    $payscheme AS payscheme,
    'HEMM' AS updt,
    'PROD' AS updtfr,
    CASE 
        WHEN IFNULL(ehd.acteff, 0) > 0 THEN ROUND(ehd.totamt /NULLIF(ehd.acteff, 0)*100, 2)
        ELSE 0
    END AS prdbas,
    CASE
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 THEN round(((tewas.working_hours * 13.5)/3*2) + (((tewas.working_hours * 13.5)/3)*ehd.acteff/100),2)
        WHEN tewas.pay_scheme_id = 125 AND IFNULL(ehd.acteff, 0) >= 100 THEN round((tewas.working_hours * 13.5),2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) < 100 AND IFNULL(ehd.acteff, 0) > 0 then round( (tewas.working_hours * twor.f_b_rate)/3*2+((tewas.working_hours * twor.f_b_rate)/3)*ehd.acteff/100,2)
        WHEN tewas.pay_scheme_id = 151 and IFNULL(ehd.acteff, 0) >= 100 then  round((tewas.working_hours * twor.f_b_rate) ,2)
         ELSE 0
    END AS time_basic,ehd.acteff AS act_eff
from EMPMILL12.tbl_ejm_wages_att_summary tewas 
left join EMPMILL12.tbl_wages_occu_rate twor on tewas.dept_code =twor.dept_code and tewas.occu_code =twor.occu_code 
left join (
select da.eb_id,substr(da.spell,1,1) shift,dm.dept_code,
omn.OCCU_CODE,sum(production) production,sum(rhd.target_eff /8*(da.working_hours -da.idle_hours)) targetprod,sum(tqr.rate *rhd.wages_code) totamt,
round(sum(production)/sum(rhd.target_eff /8*(da.working_hours -da.idle_hours)) *100,2) acteff,100 targeteff
from vowsls.daily_attendance da 
left join vowsls.daily_ebmc_attendance dea on da.daily_atten_id =dea.daily_atten_id and dea.is_active =1
left join department_master dm on da.worked_department_id =dm.dept_id 
left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id =da.worked_designation_id 
left join raw_heraatt_data rhd on  da.company_id =rhd.company_id and da.attendance_date =rhd.trandate 
and da.spell =rhd.spell and rhd.mc_id =dea.mc_id 
left join EMPMILL12.tbl_quality_rate tqr on tqr.qcode  =rhd.wages_code and tqr.dept_code =dm.dept_code 
where da.attendance_date between '$fromdate' and '$todate' and dm.dept_code='10' and omn.OCCU_CODE ='10'
and da.is_active=1
group by da.eb_id,substr(da.spell,1,1),dm.dept_code,
omn.OCCU_CODE 
) ehd on tewas.eb_id=ehd.eb_id and tewas.dept_code =ehd.dept_code and tewas.occu_code =ehd.OCCU_CODE 
where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
and tewas.dept_code ='10' and tewas.occu_code ='10'
and tewas.pay_scheme_id =$payscheme and tewas.is_active =1
";
$result=$this->db->query($sql);
 



            if ($result) {
  //              log_message('info', 'MainWagesProcessfinishing completed');
                return array('success' => true, 'message' => 'Finishing processing completed');
            } else {
    //            log_message('error', 'MainWagesProcessfinishing Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcessfinishing Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

    public function MainWagesProcessothers($fromdate, $todate, $payscheme) {
        try {
            $comany_id = $this->session->userdata('companyId');
            
            // TODO: Add your others process SQL query here
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
             ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic)
	SELECT
    '$fromdate' AS df,
    '$todate' AS dt,
    tewas.eb_id,
    tewas.dept_code,
    tewas.occu_code,
    tewas.shift,
    'P' AS t_p,
    tewas.working_hours,
    tewas.ot_hours,
	tewas.working_hours working_hours_eff,
    tewas.ot_hours ot_hours_eff,
    $payscheme AS payscheme,
    'OTHER' AS updt,
    'ATT55' AS updtfr,
	0 prdbas,0 
 time_basic
from EMPMILL12.tbl_ejm_wages_att_summary tewas 
where tewas.date_from ='$fromdate' and tewas.date_to ='$todate' and tewas.update_from ='ATT'
and  tewas.occu_code ='55'
and tewas.pay_scheme_id =$payscheme and tewas.is_active =1
";
            $result = $this->db->query($sql);
            
            if ($result) {
//                log_message('info', 'MainWagesProcessothers completed');
                return array('success' => true, 'message' => 'Others processing completed');
            } else {
  //              log_message('error', 'MainWagesProcessothers Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcessothers Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }

    public function MainWagesProcessjute($fromdate, $todate, $payscheme) {
        try {
            $comany_id = $this->session->userdata('companyId');
            
            // TODO: Add your others process SQL query here
            $sql = "INSERT INTO EMPMILL12.tbl_ejm_wages_data_collection
	            (date_from, date_to, eb_id, dept_code, occu_code, shift, t_p, working_hours,
    	         ot_hours, working_hours_eff, ot_hours_eff, pay_scheme_id, update_for, updt_from, prod_basic, time_basic,act_eff)
                select  '$fromdate' df,'$todate' dt,tewas.eb_id,tewas.dept_code ,tewas.occu_code,tewas.shift,tewas.t_p,tewas.working_hours,
                tewas.ot_hours,tewas.working_hours working_hours_eff,tewas.ot_hours ot_hours,
                $payscheme payschm, 'JUTIS' updt,'PROD' updtfr,round((tate.target_eff/8*tewas.working_hours)*tqr.rate,2) prodbas,  round(tewas.working_hours*twor.f_b_rate,2)  timebasic,100 acteff
                    from EMPMILL12.tbl_ejm_wages_att_summary tewas 
                join (     
                select sum(twkhrs) tkwhrs,sum(weight) weight,'01' deptcode,'01' occucode,sum(weight)/sum(twkhrs)*8 from ( 
                select sum(working_hours-da.idle_hours) twkhrs,0 weight from vowsls.daily_attendance da 
                where da.attendance_date between '$fromdate' and '$todate' and 
                da.is_active =1 and da.worked_designation_id =$comany_id
                union all
                select 0 twkhrs, sum(weight) weight  from EMPMILL12.issufile i 
                where i.issuedate between '$fromdate' and '$todate' and  is_active =1  
                ) g 
                ) jut on jut.deptcode=tewas.dept_code and jut.occucode=tewas.occu_code 
				left join EMPMILL12.tbl_quality_rate tqr on jut.deptcode =tqr.dept_code and tqr.qcode ='004'
                left join vowsls.department_master dm on dm.dept_code =jut.deptcode and dm.company_id=2
             	left join EMPMILL12.tbl_all_trn_eff tate on tate.dept_id= dm.dept_id and tate.qual_code ='004'
             	left join EMPMILL12.tbl_wages_occu_rate twor on twor.dept_code =jut.deptcode and twor.occu_code =jut.occucode 
             	and tate.date_from =tewas.date_from and tate.date_to =tewas.date_to 
                where tewas.dept_code ='01' and tewas.occu_code ='01'
                and tewas.date_from ='$fromdate' and tewas.date_to ='$todate'
                and tewas.is_active =1
             	
";
            $result = $this->db->query($sql);
            
            if ($result) {
//                log_message('info', 'MainWagesProcessjute completed');
                return array('success' => true, 'message' => 'Jute processing completed');
            } else {
  //              log_message('error', 'MainWagesProcessjute Error: ' . $this->db->error()['message']);
                return array('success' => false, 'message' => 'Error: ' . $this->db->error()['message']);
            }
        } catch (Exception $e) {
    //        log_message('error', 'MainWagesProcessjute Exception: ' . $e->getMessage());
            return array('success' => false, 'message' => 'Exception: ' . $e->getMessage());
        }
    }


    }
