<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_entry_update2_model extends CI_Model {


    public function updatestdhandsData($periodfromdate,$periodtodate,$holget) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    
        $rec_time =  date('Y-m-d H:i:s');
        $stat=3;
        $active=1;
        $userid=$this->session->userdata('userid');
    
         if ($holget==15) {

            //update all data
            $sql="select count(*) from daily_attendance where attendance_date ='".$periodfromdate."' and company_id=".$comp." and is_active=1 and status_id=1";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $count = 0;
            foreach ($data as $row) {
                $count = $row['count(*)'];
            }
            if ($count > 0) {
                $success = '1st Close All Attenddance Data';
                $data[] = [
                    'succes'=> $success 
                ];
                return $data;
            }
            
            

            $sql="update  EMPMILL12.tbl_daily_hand_comp_data set is_active=0 where tran_date ='".$periodfromdate."' 
            and company_id=".$comp;
            $this->db->query($sql);

 
            //insert all data
            $sql="insert into EMPMILL12.tbl_daily_hand_comp_data (tran_date,desig_id,shift_a,shift_b,
            shift_c,shift_g,ot_hands,
            excess_hands,short_hands,updated,locked_data,company_id)
            select '".$periodfromdate."' dt,desig_id,sum(sfta) sfa,sum(sfb) sftb,sum(sftc) sftc,0 sftg,
            sum(othnd) othnd,sum(exhnd) exhnd,sum(shhnd) shhnd,1 upd,0 lck,".$comp." cmp
            from (
            select desig_id,0 sfta,0 sfb,0 sftc,0 othnd,0 exhnd,0 shhnd from EMPMILL12.OCCUPATION_MASTER_NORMS omn 
            where active='Y' and desig_id is not null and omn.company_id =".$comp."
            union all
            select occu_id desg_id,ahnd+oahnd sfta,bhnd+obhnd sftb,chnd+ochnd sftc,0 othnd,0 exhnd,0 shhnd 
            from EMPMILL12.dailyhandcomp d where d.ATTANDANCE_DATE ='".$periodfromdate."' and comp_id=".$comp."
            union all
            select occu_id desg_id,shift_a sfta,shift_b sftb,shift_c sftc,0 othnd,0 exhnd,0 shhnd 
            from EMPMILL12.tbl_daily_other_hands_data tdohd where tdohd.tran_date ='".$periodfromdate."' 
            and company_id=".$comp."
            ) g group by desig_id";
            $this->db->query($sql);
          
            //update mc wise target
            $sql="update EMPMILL12.tbl_daily_hand_comp_data tdhcd
            join (
            select tdhcd.desig_id desigid,target_a,target_b,target_c,mclink.MCCODE,mclink.OC_CODE,tdhcd.desig_id,RE_CALC,
            mclink.NO_MC,NO_HANDS, msfta, msftb, msftc,omn.OCCU_DESC,CEIL(msfta/mclink.NO_MC*NO_HANDS) tara
            ,ceil(msftb/mclink.NO_MC*NO_HANDS) tarb,ceil(msftc/mclink.NO_MC*NO_HANDS) tarc
            from EMPMILL12.tbl_daily_hand_comp_data tdhcd
            left join  EMPMILL12.OCCUPATION_MASTER_NORMS omn on tdhcd.desig_id =omn.desig_id 
            left join
            (
             select tdsmd.tran_date,shift_a msfta,shift_b msftb,shift_c msftc,mlf.MC_CODE MCCODE,OC_CODE,NO_MC,NO_HANDS,desig_id  
             from EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
            left join  EMPMILL12.MCOC_LINK_FILE_UPDATED mlf on mlf.mc_code_id =tdsmd.mc_code_id 
            where tran_date='".$periodfromdate."' and MC_CODE is not null and company_id =".$comp." 
             and mlf.is_active=1  
            and tdsmd.is_active=1
             ) mclink on tdhcd.desig_id=mclink.desig_id
            where tdhcd.tran_date ='".$periodfromdate."' and tdhcd.company_id =".$comp." and tdhcd.is_active =1
            and omn.RE_CALC='L'  and  mclink.NO_MC is not null
            ) g on tdhcd.desig_id=g.desigid
            set tdhcd.target_a =g.tara, tdhcd.target_b=g.tarb, tdhcd.target_c=g.tarc
           where  g.desigid is not null and tdhcd.tran_date='".$periodfromdate."' and tdhcd.company_id=".$comp." 
           and tdhcd.is_active=1 ";

          $sql="          update
	EMPMILL12.tbl_daily_hand_comp_data tdhcd
join (
	select
		tdhcd.desig_id desigid,
		sum(target_a) target_a,
		sum(target_b) target_b,
		sum(target_c) target_c,
		tdhcd.desig_id,
		sum(msfta) msfta,
		sum(msftb) msftb,
		sum(msftc) msftc,
		omn.OCCU_DESC,
		CEIL(sum(msfta / mclink.NO_MC * NO_HANDS)) tara ,
		ceil(sum(msftb / mclink.NO_MC * NO_HANDS)) tarb,
		ceil(sum(msftc / mclink.NO_MC * NO_HANDS)) tarc
	from
		EMPMILL12.tbl_daily_hand_comp_data tdhcd
	left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on
		tdhcd.desig_id = omn.desig_id
	left join (
		select
			tdsmd.tran_date,
			shift_a msfta,
			shift_b msftb,
			shift_c msftc,
			mlf.MC_CODE MCCODE,
			OC_CODE,
			NO_MC,
			NO_HANDS,
			desig_id
		from
			EMPMILL12.tbl_daily_summ_mechine_data tdsmd
		left join EMPMILL12.MCOC_LINK_FILE_UPDATED mlf on
			mlf.mc_code_id = tdsmd.mc_code_id
		where
			tran_date = '" . $periodfromdate . "'
			and MC_CODE is not null
			and company_id = " . $comp . " 
			and mlf.is_active = 1
			and tdsmd.is_active = 1 ) mclink on
		tdhcd.desig_id = mclink.desig_id
	where
		tdhcd.tran_date = '" . $periodfromdate . "'
		and tdhcd.company_id = " . $comp . " 
		and tdhcd.is_active = 1
		and omn.RE_CALC = 'L'
		and mclink.NO_MC is not null 
		group by tdhcd.desig_id ,tdhcd.desig_id,omn.OCCU_DESC
		) g on
	tdhcd.desig_id = g.desigid set
	tdhcd.target_a = g.tara,
	tdhcd.target_b = g.tarb,
	tdhcd.target_c = g.tarc
where
	g.desigid is not null
	and tdhcd.tran_date = '" . $periodfromdate . "'
	and tdhcd.company_id = " . $comp . " 
	and tdhcd.is_active = 1
	";

       //    echo $sql;
           $this->db->query($sql);    
          
            //update indirect mc wise target 
            $sql="update EMPMILL12.tbl_daily_hand_comp_data tdhcd
            join (
            select hnd.*,mcc.*,
            case when msfta>0 then ocsfta else 0 end tara,
            case when msftb>0 then ocsftb else 0 end tarb,
            case when msftc>0 then ocsftc else 0 end tarc
            from (
            select tdhcd.desig_id desigid,target_a,target_b,target_c,tdhcd.desig_id,RE_CALC,
            omn.OCCU_DESC,omn.shift_a ocsfta,omn.shift_b ocsftb,omn.shift_c ocsftc,MC_CODE omccode
            from EMPMILL12.tbl_daily_hand_comp_data tdhcd
            left join  EMPMILL12.OCCUPATION_MASTER_NORMS omn on tdhcd.desig_id =omn.desig_id 
            where  omn.ACTIVE ='Y' AND omn.RE_CALC ='F' AND LENGTH(omn.MC_CODE)=6
            and tran_date='".$periodfromdate."'  and tdhcd.company_id =".$comp." and tdhcd.is_active=1
            ) hnd left join 
           ( 
             select tdsmd.mc_code_id,mcm.MC_CODE,sum(tdsmd.shift_a) msfta,sum(tdsmd.shift_b) msftb,sum(tdsmd.shift_c) msftc 
             from EMPMILL12.tbl_daily_summ_mechine_data tdsmd
             left join EMPMILL12.mechine_code_master mcm ON tdsmd.mc_code_id =mcm.mc_code_id 
             where tran_date='".$periodfromdate."'  and tdsmd.company_id =".$comp." and tdsmd.is_active=1
             and tdsmd.is_active =1  
             group by tdsmd.mc_code_id,mcm.MC_CODE 
           ) mcc on hnd.omccode=mcc.MC_CODE
            ) g on tdhcd.desig_id=g.desigid
            set tdhcd.target_a =g.tara, tdhcd.target_b=g.tarb, tdhcd.target_c=g.tarc
           where  g.desigid is not null and tdhcd.tran_date='".$periodfromdate."' and tdhcd.company_id=".$comp." 
           and tdhcd.is_active=1 ";
           $this->db->query($sql);    
           
          //update only hands target no mc/dept dependency
           $sql="UPDATE EMPMILL12.tbl_daily_hand_comp_data AS t
            JOIN EMPMILL12.OCCUPATION_MASTER_NORMS AS omn ON t.desig_id = omn.desig_id
            SET t.target_a = omn.SHIFT_A,
                t.target_b = omn.SHIFT_B,
                t.target_c = omn.SHIFT_C
            WHERE t.tran_date = '".$periodfromdate."' 
            AND t.company_id = ".$comp." and t.is_active=1
            AND omn.RE_CALC = 'A' ";
            $this->db->query($sql);    

           //update for indirect dept&costcenter
            $sql="update EMPMILL12.tbl_daily_hand_comp_data tdhcd
            join (
            select hnd.*,mcc.*,
            case when msfta>0 then ocsfta else 0 end tara,
            case when msftb>0 then ocsftb else 0 end tarb,
            case when msftc>0 then ocsftc else 0 end tarc
            from (
            select tdhcd.desig_id desigid,target_a,target_b,target_c,tdhcd.desig_id,RE_CALC,
            omn.OCCU_DESC,omn.shift_a ocsfta,omn.shift_b ocsftb,omn.shift_c ocsftc,MC_CODE omccode
            from EMPMILL12.tbl_daily_hand_comp_data tdhcd
            left join  EMPMILL12.OCCUPATION_MASTER_NORMS omn on tdhcd.desig_id =omn.desig_id 
            where  omn.ACTIVE ='Y' AND omn.RE_CALC ='F' AND LENGTH(omn.MC_CODE)=4
            and tran_date='".$periodfromdate."'  and tdhcd.company_id =".$comp." and tdhcd.is_active=1
            ) hnd left join 
            ( 
            select substr(mcm.MC_CODE,1,4) MC_CODE,sum(tdsmd.shift_a) msfta,sum(tdsmd.shift_b) msftb,sum(tdsmd.shift_c) msftc 
            from EMPMILL12.tbl_daily_summ_mechine_data tdsmd
            left join EMPMILL12.mechine_code_master mcm ON tdsmd.mc_code_id =mcm.mc_code_id 
            where tran_date='".$periodfromdate."'  and tdsmd.company_id =".$comp." and tdsmd.is_active=1
            and tdsmd.is_active =1  
            group by substr(mcm.MC_CODE,1,4) 
            ) mcc on hnd.omccode=mcc.MC_CODE
            ) g on tdhcd.desig_id=g.desigid
            set tdhcd.target_a =g.tara, tdhcd.target_b=g.tarb, tdhcd.target_c=g.tarc
            where  g.desigid is not null and tdhcd.tran_date='".$periodfromdate."' and tdhcd.company_id=".$comp." 
            and tdhcd.is_active=1 ";
            $this->db->query($sql);    

            //update hands deptwise dependency
            $sql="update EMPMILL12.tbl_daily_hand_comp_data tdhcd
            join (
            select hnd.*,mcc.*,
            case when msfta>0 then ocsfta else 0 end tara,
            case when msftb>0 then ocsftb else 0 end tarb,
            case when msftc>0 then ocsftc else 0 end tarc
            from (
            select tdhcd.desig_id desigid,target_a,target_b,target_c,tdhcd.desig_id,RE_CALC,
            omn.OCCU_DESC,omn.shift_a ocsfta,omn.shift_b ocsftb,omn.shift_c ocsftc,MC_CODE omccode
            from EMPMILL12.tbl_daily_hand_comp_data tdhcd
            left join  EMPMILL12.OCCUPATION_MASTER_NORMS omn on tdhcd.desig_id =omn.desig_id 
            where  omn.ACTIVE ='Y' AND omn.RE_CALC ='F' AND LENGTH(omn.MC_CODE)=2
            and tran_date='".$periodfromdate."'  and tdhcd.company_id =".$comp." and tdhcd.is_active=1
            ) hnd left join 
           ( 
             select substr(mcm.MC_CODE,1,2) MC_CODE,sum(tdsmd.shift_a) msfta,sum(tdsmd.shift_b) msftb,sum(tdsmd.shift_c) msftc 
             from EMPMILL12.tbl_daily_summ_mechine_data tdsmd
             left join EMPMILL12.mechine_code_master mcm ON tdsmd.mc_code_id =mcm.mc_code_id 
             where tran_date='".$periodfromdate."'  and tdsmd.company_id =".$comp." and tdsmd.is_active=1
             and tdsmd.is_active =1  
             group by substr(mcm.MC_CODE,1,2) 
           ) mcc on hnd.omccode=mcc.MC_CODE
            ) g on tdhcd.desig_id=g.desigid
            set tdhcd.target_a =g.tara, tdhcd.target_b=g.tarb, tdhcd.target_c=g.tarc
            where  g.desigid is not null and tdhcd.tran_date='".$periodfromdate."' and tdhcd.company_id=".$comp." 
            and tdhcd.is_active=1 ";
            $this->db->query($sql);


            //          ---050101
            $tg = 400;
            $gtyp = "'HSWP','CNWP','SLYN'";
            $ocid=199;
      $sql = "select ifnull(ceil(SUM(prd_a)/$tg),0) prda,ifnull(ceil(SUM(prd_b)/$tg),0) prdb,ifnull(ceil(SUM(prd_c)/$tg),0) prdc from  EMPMILL12.spining_daily_transaction sdt 
                  join EMPMILL12.spining_master sm on sdt.q_code =sm.q_code and sdt.company_id =sm.company_id 
                  and tran_date ='" . $periodfromdate . "' and sm.subgroup_type in (" . $gtyp . ")";
            $query = $this->db->query($sql);
            $data = $query->result_array();
            $pda = 0;
            $pdb = 0;
            $pdc = 0;
            foreach ($data as $row) {
              $pda = $row['prda'];
              $pdb = $row['prdb'];
              $pdc = $row['prdc'];
            }
            $sql = "update EMPMILL12.tbl_daily_hand_comp_data set 
                 target_a = " . $pda . ",
                      target_b =  " . $pdb . ",
                      target_c =  " . $pdc . "
                  WHERE tran_date = '" . $periodfromdate . "' 
                  AND company_id = " . $comp . " and is_active=1
                  and  desig_id=".$ocid;
            $this->db->query($sql);

          $tg = 270;
        //(SUBGROUP_TYPE='HSWT' OR SUBGROUP_TYPE='CNWT')"
          $gtyp = "'HSWT','CNWT'";
          $ocid = 201;
      $sql = "select ifnull(ceil(SUM(prd_a)/$tg),0) prda,ifnull(ceil(SUM(prd_b)/$tg),0) prdb,ifnull(ceil(SUM(prd_c)/$tg),0) prdc from  EMPMILL12.spining_daily_transaction sdt 
                      join EMPMILL12.spining_master sm on sdt.q_code =sm.q_code and sdt.company_id =sm.company_id 
                      and tran_date ='" . $periodfromdate . "' and sm.subgroup_type in (" . $gtyp . ")";
          $query = $this->db->query($sql);
          $data = $query->result_array();
          $pda = 0;
          $pdb = 0;
          $pdc = 0;
          foreach ($data as $row) {
            $pda = $row['prda'];
            $pdb = $row['prdb'];
            $pdc = $row['prdc'];
          }
          $sql = "update EMPMILL12.tbl_daily_hand_comp_data set 
                    target_a = " . $pda . ",
                          target_b =  " . $pdb . ",
                          target_c =  " . $pdc . "
                      WHERE tran_date = '" . $periodfromdate . "' 
                      AND company_id = " . $comp . " and is_active=1
                      and  desig_id=" . $ocid;
          $this->db->query($sql);

          $tg = 450;
   //       (SUBGROUP_TYPE='SKWP'  AND B.STD_COUNT<=12)"
          $gtyp = "'SKWP'";
          $ocid = 200;
          $sql = "select ifnull(ceil(SUM(prd_a)/$tg),0) prda,ifnull(ceil(SUM(prd_b)/$tg),0) prdb,ifnull(ceil(SUM(prd_c)/$tg),0) prdc from  EMPMILL12.spining_daily_transaction sdt 
                          join EMPMILL12.spining_master sm on sdt.q_code =sm.q_code and sdt.company_id =sm.company_id 
                          and tran_date ='" . $periodfromdate . "' and sm.subgroup_type in (" . $gtyp . ") AND sm.std_count<=12";
          $query = $this->db->query($sql);
          $data = $query->result_array();
          $pda = 0;
          $pdb = 0;
          $pdc = 0;
          foreach ($data as $row) {
            $pda = $row['prda'];
            $pdb = $row['prdb'];
            $pdc = $row['prdc'];
          }
          $sql = "update EMPMILL12.tbl_daily_hand_comp_data set 
                        target_a = " . $pda . ",
                              target_b =  " . $pdb . ",
                              target_c =  " . $pdc . "
                          WHERE tran_date = '" . $periodfromdate . "' 
                          AND company_id = " . $comp . " and is_active=1
                          and  desig_id=" . $ocid;
          $this->db->query($sql);

          $tg = 460;
          //       (SUBGROUP_TYPE='SKWP'  AND B.STD_COUNT<=12)"
          $gtyp = "'SKWP'";
          $ocid = 330;
      $sql = "select ifnull(ceil(SUM(prd_a)/$tg),0) prda,ifnull(ceil(SUM(prd_b)/$tg),0) prdb,ifnull(ceil(SUM(prd_c)/$tg),0) prdc from  EMPMILL12.spining_daily_transaction sdt 
                              join EMPMILL12.spining_master sm on sdt.q_code =sm.q_code and sdt.company_id =sm.company_id 
                              and tran_date ='" . $periodfromdate . "' and sm.subgroup_type in (" . $gtyp . ") AND sm.std_count>12";
          $query = $this->db->query($sql);
          $data = $query->result_array();
          $pda = 0;
          $pdb = 0;
          $pdc = 0;
          foreach ($data as $row) {
            $pda = $row['prda'];
            $pdb = $row['prdb'];
            $pdc = $row['prdc'];
          }
          $sql = "update EMPMILL12.tbl_daily_hand_comp_data set 
                            target_a = " . $pda . ",
                                  target_b =  " . $pdb . ",
                                  target_c =  " . $pdc . "
                              WHERE tran_date = '" . $periodfromdate . "' 
                              AND company_id = " . $comp . " and is_active=1
                              and  desig_id=" . $ocid;
          $this->db->query($sql);
          $tg = 220;
          //       (SUBGROUP_TYPE='SKWP'  AND B.STD_COUNT<=12)"
          $gtyp = "'SKWT'";
          $ocid = 202;
          $sql = "select ifnull(ceil(SUM(prd_a)/$tg),0) prda,ifnull(ceil(SUM(prd_b)/$tg),0) prdb,
          ifnull(ceil(SUM(prd_c)/$tg),0) prdc from  EMPMILL12.spining_daily_transaction sdt 
                                  join EMPMILL12.spining_master sm on sdt.q_code =sm.q_code and sdt.company_id =sm.company_id 
                                  and tran_date ='" . $periodfromdate . "' and sm.subgroup_type in (" . $gtyp . ")";
          $query = $this->db->query($sql);
          $data = $query->result_array();
          $pda = 0;
          $pdb = 0;
          $pdc = 0;
          foreach ($data as $row) {
            $pda = $row['prda'];
            $pdb = $row['prdb'];
            $pdc = $row['prdc'];
          }
          $sql = "update EMPMILL12.tbl_daily_hand_comp_data set 
                                target_a = " . $pda . ",
                                      target_b =  " . $pdb . ",
                                      target_c =  " . $pdc . "
                                  WHERE tran_date = '" . $periodfromdate . "' 
                                  AND company_id = " . $comp . " and is_active=1
                                  and  desig_id=" . $ocid;
          $this->db->query($sql);


      $sql="update EMPMILL12.tbl_daily_hand_comp_data tdhcd
            join (
            select hcd.desig_id desigid,
            case when (shift_a+shift_b+shift_c)>(target_a+target_b+target_c) then (shift_a+shift_b+shift_c)-(target_a+target_b+target_c) else 0 end exhnd,
            case when (shift_a+shift_b+shift_c)<(target_a+target_b+target_c) then (target_a+target_b+target_c)-(shift_a+shift_b+shift_c) else 0 end shhnd
            from EMPMILL12.tbl_daily_hand_comp_data hcd
            where hcd.tran_date ='".$periodfromdate."' and hcd.is_active =1
            ) g on tdhcd.desig_id=g.desigid
            set tdhcd.excess_hands =g.exhnd, tdhcd.short_hands=g.shhnd 
            where  g.desigid is not null and tdhcd.tran_date='".$periodfromdate."' and tdhcd.company_id=".$comp." 
            and tdhcd.is_active=1 
            ";
            $this->db->query($sql);    

            




      $success='Success';
                




    
        }
    
        $data[] = [
            'succes'=> $success 
        ];
        return $data;
       
    }
    
    

}    
