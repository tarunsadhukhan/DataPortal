<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_adv_model extends CI_Model {

    public function getAllMasterDepartments() {
        // Replace this with your actual database query to fetch departments
        return $this->db->get('master_departments')->result();
    }

    public function getMCCodesByDepartment($department) {
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
        $this->db->select('mc_code_id, MC_OC_CODE, MC_DESCRIPTION');
        $this->db->where('DEPT_ID', $department);
        $this->db->where('company_id', $comp);
        $data=$this->db->get('EMPMILL12.MC_CODE_MASTER')->result();
     //   echo $this->db->last_query();
        
        return $data; 
            }

    public function getEbMaster($department) {
        
                // Replace this with your actual database query to fetch MCCodes based on department
                $company_name = $this->session->userdata('companyname');
                $comp = $this->session->userdata('companyId');
 
                $this->db->select('theod.eb_id, CONCAT(first_name, " ", last_name) AS empname');
                $this->db->from('tbl_hrms_ed_official_details theod');
                $this->db->join('tbl_hrms_ed_personal_details thepd', 'theod.eb_id = thepd.eb_id', 'left');
                $this->db->where('theod.emp_code', $department);
//               $this->db->where('thepd.is_active', 1);
                $this->db->where('thepd.company_id', $comp);
                
                $query = $this->db->get();
                $data=$query->result();
                if ($query->num_rows() > 0) {
                  
                    return $data;
                } else {

                    return $data;
                    //  return array(); // Return an empty array if no results are found
                }
    }
 
    public function getpayscheme($department) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');

        $this->db->select('theod.NAME');
        $this->db->from('tbl_pay_scheme theod');
        $this->db->where('theod.ID', $department);
        $query = $this->db->get();
   //     echo 'this query'.$this->db->last_query();
        $data=$query->result();
        if ($query->num_rows() > 0) {
          
            return $data;
        } else {

            return $data;
            //  return array(); // Return an empty array if no results are found
        }
}

    public function getPayschemeNameaa($department) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');

        $this->db->select('theod.eb_id, DESCRIPTION payscheme');
        $this->db->from('tbl_pay_scheme tps');
        $this->db->where('tps.ID', $department);
        
        $query = $this->db->get();
        $data=$query->result();
        if ($query->num_rows() > 0) {
          
            return $data;
        } else {

            return $data;
            //  return array(); // Return an empty array if no results are found
        }
}
    public function getLoanadvData($periodfromdate) {
        $department=$periodfromdate;
     //   echo 'date'.$department;
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');

        $this->db->select('tlat.loan_adv_id,date_format(tlat.loan_adv_date,"%d-%m-%Y") loan_adv_date , 
        tlat.loan_adv_type, theod.emp_code, CONCAT(thepd.first_name, " ", thepd.last_name) as empname, 
        tlat.loan_adv_amount, tlat.installment_amount, tlat.No_of_installment, 
        date_format(tlat.installment_start_date,"%d-%m-%Y") installment_start_date,tlat.eb_id  ');
        $this->db->from('EMPMILL12.tbl_loan_advance_table tlat');
        $this->db->join('vowsls.tbl_hrms_ed_official_details theod', 'tlat.eb_id = theod.eb_id', 'left');
        $this->db->join('vowsls.tbl_hrms_ed_personal_details thepd', 'thepd.eb_id = theod.eb_id', 'left');
        $this->db->where('thepd.company_id', $comp);
        $this->db->where('tlat.is_active', 1);
        $this->db->where('tlat.loan_adv_date', $department);
    
        $query = $this->db->get();
 //echo $this->db->last_query();
   
        $data=$query->result();
        if ($query->num_rows() > 0) {
        //    var_dump($data);
            return $data;
        } else {
            return array(); // Return an empty array if no results are found
        }
}
public function getLoanadvtranData($periodfromdate,$periodtodate,$att_payschm) {
 //   echo 'date'.$department;
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $this->db->select('tlat.loan_adv_id,date_format(tlat.loan_adv_date,"%d-%m-%Y") loan_adv_date , 
    tlat.loan_adv_type, theod.emp_code, CONCAT(thepd.first_name, " ", thepd.last_name) as empname, 
    tlat.loan_adv_amount, tlat.installment_amount, tlat.No_of_installment, 
    date_format(tlat.installment_start_date,"%d-%m-%Y") installment_start_date,tlat.eb_id  ');
    $this->db->from('EMPMILL12.tbl_loan_advance_table tlat');
    $this->db->join('vowsls.tbl_hrms_ed_official_details theod', 'tlat.eb_id = theod.eb_id', 'left');
    $this->db->join('vowsls.tbl_hrms_ed_personal_details thepd', 'thepd.eb_id = theod.eb_id', 'left');
    $this->db->where('thepd.company_id', $comp);
    $this->db->where('tlat.is_active', 1);
    $this->db->where('tlat.loan_adv_date', $department);

    $query = $this->db->get();
//echo $this->db->last_query();

    $data=$query->result();
    if ($query->num_rows() > 0) {
    //    var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function gethlaincelegData() {
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
   
    $sql = "	select hl_att_inc_id,emp_code,concat(thepd.first_name,' ',thepd.last_name) empname,
    case when thaie.holiday_eligibility='Y' then 'Yes' else 'No' end holidayeligibility,
    case when thaie.att_incn_eligibility='Y' then 'Yes' else 'No' end attincneligibility  ,
    fn_att_inc_rate ,mn_att_inc_rate ,thaie.eb_id ,cm.cata_desc 
    from EMPMILL12.tbl_holiday_att_inc_eligibility thaie 
    left join (select * from tbl_hrms_ed_personal_details where is_active=1) thepd on thaie.eb_id =thepd.eb_id 
    left join (select * from tbl_hrms_ed_official_details where is_active=1 ) theod on thepd.eb_id =theod.eb_id 
    left join category_master cm on theod.catagory_id=cm.cata_id
    where thaie.is_active =1 and thepd.company_id =".$comp;
    
   
    $query = $this->db->query($sql);

    if ($query->num_rows() > 0) {
        return $query->result();
    } else {
        return array(); // Return an empty array if no results are found
    }
}



public function updateLoanAdvanceData($recordid, $data) {
    // Assuming you have a database table named 'loan_advance'
    $this->db->where('loan_adv_id', $recordid);
    $this->db->update('EMPMILL12.tbl_loan_advance_table', $data);

}
public function updateeleg_data($recordid, $data) {
    // Assuming you have a database table named 'loan_advance'
    $this->db->where('hl_att_inc_id', $recordid);
    $this->db->update('EMPMILL12.tbl_holiday_att_inc_eligibility', $data);
 //   echo $this->db->last_query();

}

public function updatepayschemeparadata($recordid, $data) {
    // Assuming you have a database table named 'loan_advance'
    $this->db->where('id', $recordid);
    $this->db->update('EMPMILL12.tbl_payslip_print_component', $data);
  //  echo $this->db->last_query();
}

public function advpprocessdata($periodfromdate, $periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');



    $sql="INSERT INTO EMPMILL12.tbl_loan_adv_transaction (loan_adv_id, period_from, period_to, installment_amount, is_active, status_id, processed_by, processed_date)
    SELECT loan_adv_id, '".$periodfromdate."', '".$periodtodate."', installment_amount, 1, 3,". $userid.", CURRENT_TIMESTAMP
    FROM (
        SELECT tlat.loan_adv_id, loan_adv_date, tlat.eb_id, loan_adv_amount, installment_amount, installment_start_date, 
        IFNULL(dedamt, 0) AS dedamt, (loan_adv_amount - IFNULL(dedamt, 0)) AS balamt
        FROM EMPMILL12.tbl_loan_advance_table tlat
        LEFT JOIN (
            SELECT loan_adv_id, SUM(installment_amount) AS dedamt
            FROM EMPMILL12.tbl_loan_adv_transaction tladvt
            WHERE is_active > 0
            GROUP BY loan_adv_id
        ) tladvt ON tlat.loan_adv_id = tladvt.loan_adv_id
    left join (select * from vowsls.tbl_pay_employee_payscheme tpep where status=1  ) tpep   
    on tpep.EMPLOYEEID=tlat.eb_id 
    left join (select * from tbl_hrms_ed_personal_details where is_active =1 ) thepd on thepd.eb_id=tlat.eb_id 
    WHERE tlat.installment_start_date < '".$periodtodate."' AND tlat.is_active = 1 
    AND (loan_adv_amount - IFNULL(dedamt, 0)) > 0
    and thepd.company_id=".$comp." and tpep.PAY_SCHEME_ID=".$att_payschm."
    ) AS g";
    $this->db->query($sql);

 
  

   
}

public function updateholidayaData($periodfromdate,$periodtodate, $att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');

    if ($holget==1) {
     
    // Use active record or query builder to build the query
    $this->db->select('*');
    $this->db->from('vowsls.holiday_master');
    $this->db->where('company_id', $comp);
    $this->db->where('holiday_date >=', $periodfromdate);
    $this->db->where('holiday_date <=', $periodtodate);
    $query = $this->db->get();  

    foreach ($query->result() as $row) {
        $holid=0;
        $holprvdate='';
            // Access each column of the current row using object properties
            $holid = $row->id;
            $holprvdate = $row->period_start_date;

    
        if ($holid>0) {
            $sql="update vowsls.tbl_hrms_holiday_transactions set is_active=0 where holiday_id=".$holid;
            $this->db->query($sql);
        }   

if ($holid>0) {
    if ($comp==1) {
    $sql="INSERT INTO vowsls.tbl_hrms_holiday_transactions  (
    holiday_id,eb_id,holiday_hours,eligibility_date,company_id,created_by, is_active)
    SELECT ".$holid.",
        g.eb_id,8,'".$holprvdate."',".$comp.",".$userid.",1
        from (
            select eb_id,sum(whrs+nhrs) whrs,'A' rem from ( 
            select eb_id,substr(spell,1,1) shift,(working_hours-idle_hours) whrs,case when (spell='C' and (working_hours-idle_hours)=7.5) then 0.5 else 0 end nhrs
            from daily_attendance da where da.is_active =1 and da.company_id =".$comp."
            and da.attendance_date = '".$holprvdate."' and attendance_type ='R'
            ) g group by eb_id having sum(whrs+nhrs)>=6
            union ALL 
            select   eb_id,8 whrs,'L' rem from leave_tran_details ltd 
            join leave_transactions lt2 on lt2.leave_transaction_id =ltd.ltran_id 
            join leave_types lt on lt.leave_type_id =lt2.leave_type_id 
            where lt2.status =3 and lt.leave_type_code in ('S','L','C') and 
            lt2.company_id =".$comp."  and leave_date='".$holprvdate."'   and ltd.is_active =1 
            ) g join EMPMILL12.tbl_holiday_att_inc_eligibility thaie 
            on g.eb_id=thaie.eb_id 
            where thaie.is_active=1 and thaie.holiday_eligibility='Y'";
    } else 
    {
        $sql="INSERT INTO vowsls.tbl_hrms_holiday_transactions  (
            holiday_id,eb_id,holiday_hours,eligibility_date,company_id,created_by, is_active)
            SELECT ".$holid.",
                g.eb_id,8,'".$holprvdate."',".$comp.",".$userid.",1
                from (
                    select eb_id,sum(whrs+nhrs) whrs,'A' rem from ( 
                    select eb_id,substr(spell,1,1) shift,(working_hours-idle_hours) whrs,case when (spell='C' and (working_hours-idle_hours)=7.5) then 0.5 else 0 end nhrs
                    from daily_attendance da where da.is_active =1 and da.company_id =".$comp."
                    and da.attendance_date = '".$holprvdate."' and attendance_type ='R'
                    ) g group by eb_id having sum(whrs+nhrs)>=6
                    ) g join EMPMILL12.tbl_holiday_att_inc_eligibility thaie 
                    on g.eb_id=thaie.eb_id 
                    where thaie.is_active=1 and thaie.holiday_eligibility='Y'";
                
    }
            //echo $sql;
    $this->db->query($sql);
      $success='Success';
    }else {}     
        $success='No Records';
    }
 
    }
    if ($holget==14) {
        $sql="update EMPMILL12.tbl_daily_cash_outsider_payment set is_active=0 where pay_date ='".$periodfromdate."' and company_id=".$comp;
        $this->db->query($sql);

//        $sql="update EMPMILL12.tbl_daily_cash_outsider_payment_production set is_active=0 where pay_date ='".$periodfromdate."' and company_id=".$comp;
  //      $this->db->query($sql);

/*         $sql="select eb_id,(working_hours-idle_hours) as whrs from daily_attendance where attendance_date='".$periodfromdate."' and company_id=".$comp." 
        and substr(eb_id,1,1)='T' group by eb_id";
        $this->db->query($sql);
        $data=$this->db->query($sql)->result();
        while ($row = $this->db->query($sql)->unbuffered_row()) {
            $ebid=$row->eb_id;
            $whrs=$row->whrs;
            $sqlc="select * from EMPMILL12.tbl_daily_cash_outsider_payment_production where eb_id='".$ebid."' and pay_date='".$periodfromdate."' and company_id=".$comp;
            $this->db->query($sqlc);
            if ($this->db->query($sqlc)->num_rows()>0) {
                $sqlu="update EMPMILL12.tbl_daily_cash_outsider_payment_production set working_hours=".$whrs." where eb_id='".$ebid."' and pay_date='".$periodfromdate."' and company_id=".$comp;
                $this->db->query($sqlu);
            } else {
                $sqli="insert into EMPMILL12.tbl_daily_cash_outsider_payment_production (eb_id,pay_date,working_hours,company_id)
                values('".$ebid."','".$periodfromdate."',".$whrs.",".$comp.")";
                $this->db->query($sqli);
            }
        }
 */

        
        $sql="insert into EMPMILL12.tbl_daily_cash_outsider_payment (eb_id,pay_date,shift,dept_id,desig_id,subloca_id,working_hours,rate,oth_rate,amount,
        company_id,cont_id )
        select eb_id,'".$periodfromdate."',shift,dept_id,desg_id,subloca_id,whrs,prate,0 oth_rate,pamount,2,contractor_id from (
        select att.*,wm.eb_no,concat(wm.worker_name,' ',ifnull(wm.middle_name,''),' ',ifnull(wm.last_name,'')) wname,wm.cash_rate,theod.contractor_id,
        cm.contractor_name,tsl.sub_location, tsl.short_name,dept_desc,desig,tct.cont_type,twst.subloca_id, 
        tor.rate ocrate,contac_id,
        case when ifnull(tor.rate,0)>0 and theod.contractor_id=contac_id then tor.rate else wm.cash_rate end prate,
        case when ifnull(tor.rate,0)>0 and theod.contractor_id=contac_id then round(tor.rate*whrs/8,0)  else round(wm.cash_rate*whrs/8,0) end pamount
        from 
        (
        select attdate,eb_id,dept_id,desg_id,substr(spell,1,1) shift, 
        sum(whrs+nhrs) whrs
        from EMPMILL12.view_proc_att_leave_holiday vpalh
        where attdate ='".$periodfromdate."' and company_id=".$comp." and substr(eb_no,1,1)='T' and attendance_type in ('R','O','C')
        group by attdate,eb_id,dept_id,desg_id,substr(spell,1,1)
        ) att  join worker_master wm on wm.eb_id=att.eb_id
        join (SELECT * FROM tbl_hrms_ed_official_details theod where is_active=1) theod on theod.eb_id=att.eb_id
        left join EMPMILL12.tbl_worker_sublocation_table twst on att.eb_id=twst.eb_id
        left join EMPMILL12.tbl_sublocation tsl on tsl.subloca_id=twst.subloca_id
        left join contractor_master cm on cm.cont_id=theod.contractor_id
        join department_master dm on dm.dept_id=att.dept_id
        join designation dg on dg.id=att.desg_id
        left join EMPMILL12.tbl_contractor_type tct on cm.cont_id=tct.cont_id
        left join (select * from EMPMILL12.tbl_occupation_rate where is_active=1) tor on att.desg_id=tor.desig_id
        where twst.daily_others_pay_duration='T'
        ) g
        ";
   //    echo $sql;
      $this->db->query($sql);

        $sql="select * from EMPMILL12.tbl_occupation_rate tor where ifnull(tor.prod_calc,0)=0";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data=$query->result();
            foreach ($data as $row) {
                   $dsg=$row->desig_id;
                $rts=$row->rate;
                $sqla="update EMPMILL12.tbl_daily_cash_outsider_payment set rate=$rts,amount=round($rts*working_hours/8,0) 
                where pay_date='$periodfromdate' and desig_id=$dsg";
                 $query = $this->db->query($sqla);
          }
        }



      $sql="select * from EMPMILL12.tbl_daily_cash_outsider_payment_production where prod_date='".$periodfromdate."' 
      and is_active=1 ";
      $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data=$query->result();
            foreach ($data as $row) {
                $ebid=$row->eb_id;
                $rts=$row->prod_rate;
                $dt=$row->prod_date;
                $sft=$row->prod_shift;
                $dsg=$row->desig_id;
                $sqla="update EMPMILL12.tbl_daily_cash_outsider_payment set rate=$rts,amount=round($rts*working_hours/8,0) 
                where eb_id=$ebid and pay_date='$periodfromdate' and shift='$sft' and desig_id=$dsg";
//                echo $sqla;
                $query = $this->db->query($sqla);

            }
        }

//echo 'approve';
        $sql="select * from EMPMILL12.outsider_rate_approve ora where app_date='".$periodfromdate."' and is_active=1 ";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data=$query->result();
            foreach ($data as $row) {
                $ebid=$row->eb_id;
                $rts=$row->rate_approve;
                $dt=$row->app_date;
                $sft=$row->app_shift;
                $dsg=$row->desig_id;
                $sqla="update EMPMILL12.tbl_daily_cash_outsider_payment set rate=$rts,amount=($rts*working_hours/8) 
                where eb_id=$ebid and pay_date='$periodfromdate' and shift='$sft' ";
  //              echo $sqla;
                $query = $this->db->query($sqla);

            }
        }

        $sql=" select tdcop.*,30 ta_rate,cont_id   from EMPMILL12.tbl_daily_cash_outsider_payment tdcop 
           left join EMPMILL12.tbl_occupation_rate tor on tdcop.desig_id =tor.desig_id 
           where tdcop.pay_date ='".$periodfromdate."' and tdcop.is_active =1 
           and cont_id =10";
        $query = $this->db->query($sql);
        if ($query->num_rows() > 0) {
            $data=$query->result();
            foreach ($data as $row) {
                $ebid=$row->eb_id;
                $rts=round($row->working_hours/8 * $row->ta_rate,0);
                $dt=$row->pay_date;
                $sft=$row->shift;
                $dsg=$row->desig_id;

                $sqla="update EMPMILL12.tbl_daily_cash_outsider_payment set oth_rate=round($rts,0) 
                where eb_id='".$ebid."' and pay_date='".$periodfromdate."' and shift='".$sft."' and desig_id='".$dsg."' and company_id=".$comp;
          //      echo $sqla;
                $query = $this->db->query($sqla);

            }
        }

        $success='Success';
 

    }

    $data[] = [
        'succes'=> $success 
    ];
    return $data;
   
}



public function getholiday_data($periodfromdate,$periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $this->db->select('thht.holiday_tran_id, theod.emp_code, CONCAT(thepd.first_name, " ", IFNULL(thepd.middle_name, " "), " ", IFNULL(thepd.last_name, " ")) AS empname, date_format(hm.holiday_date,"%d-%m-%Y") holiday_date, hm.holiday,holiday_hours');
    $this->db->from('tbl_hrms_holiday_transactions thht');
    $this->db->join('tbl_hrms_ed_official_details theod', 'thht.eb_id = theod.eb_id', 'left');
    $this->db->join('tbl_hrms_ed_personal_details thepd', 'thht.eb_id = thepd.eb_id', 'left');
    $this->db->join('holiday_master hm', 'thht.holiday_id = hm.id', 'left');
    $this->db->join('tbl_pay_employee_payscheme tpep', 'thht.eb_id = tpep.EMPLOYEEID', 'left');
    $this->db->where('hm.holiday_date >=', $periodfromdate);
    $this->db->where('hm.holiday_date <=', $periodtodate);
    $this->db->where('thht.is_active', 1);
    $this->db->where('tpep.status', 1);
    $this->db->where('tpep.PAY_SCHEME_ID', $att_payschm);
    $this->db->where('thepd.company_id', $comp);
    $this->db->where('theod.is_active', 1);
    $query = $this->db->get();
     
    $data=$query->result();
//    echo $this->db->last_query();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $attincd=12;
//    $this->db->db_debug = TRUE;
 
//CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name, ' '), ' ', IFNULL(thepd.last_name, ' ')) AS empname,
if ($holget==2) { $attp="('R')";}
if ($holget==3) { $attp="('R','O')";}
//echo 'choss-'.$holget;
//echo 'ro-'.$attp;


$elhrs=8;

if  ($holget<>7) {
    $sql="SELECT
	k.eb_id,
	theod.emp_code,
	dm.dept_desc,
	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
	round(incdays, 0) incdays,
	elegday - incdays AS lvdays,
	elegday,
	fn_att_inc_rate,
	round(incdays * fn_att_inc_rate, 0) AS incamt
 from (
	select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
	select eb_id,count(*) wdays,0 lvdays from (
	select eb_id,attendance_date,sum(whrs) whrs from (
	SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
	from daily_attendance da
	where 	da.is_active = 1
			AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
			AND company_id = ".$comp."
			AND da.attendance_type in ('R', 'O')  
	) g group by eb_id,attendance_date having sum(whrs)>=$elhrs
	) k group by eb_id
	union all
	SELECT
			eb_id,0 wdays,count(*) lvdays
		FROM
			vowsls.leave_transactions lt
		LEFT JOIN vowsls.leave_tran_details ltd ON
			lt.leave_transaction_id = ltd.ltran_id
		WHERE
			leave_type_id IN (24, 25)
            AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
             AND lt.status = 3
			AND lt.company_id = ".$comp."    and ltd.is_active =1 
	GROUP BY
		eb_id
        union ALL 
        select eb_id,0 wdays,count(*) lvdays from tbl_hrms_holiday_transactions thht 
                left join holiday_master hm on thht.holiday_id =hm.id 
                where hm.company_id =".$comp."   and
                 thht.is_active =1 and hm.holiday_date between 
                 '".$periodfromdate."' AND '".$periodtodate."'
            GROUP BY
                eb_id 

        ) h group by eb_id having sum(wdays+lvdays)>=$attincd
	) k
	JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
	JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.eb_id
	LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.eb_id = theod.eb_id
	LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
	LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
	WHERE
	tpep.PAY_SCHEME_ID = ".$att_payschm." 
	AND thaie.att_incn_eligibility = 'Y'
	AND tpep.status = 1
	AND thepd.company_id = ".$comp."
	AND theod.is_active = 1
	AND thaie.is_active = 1
    order by dm.dept_code,
	theod.emp_code";

//    echo $sql;
    
    } else {
        $sql="SELECT
        k.eb_id,
        theod.emp_code,
        dm.dept_desc,
        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
        round(incdays, 0) incdays,
        elegday - incdays AS lvdays,
        elegday,
        fn_att_inc_rate,
        round(incdays * fn_att_inc_rate, 0) AS incamt
     from (
        select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
        select eb_id,count(*) wdays,0 lvdays from (
        select eb_id,attendance_date,sum(whrs) whrs from (
        SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
        from daily_attendance da
        where 	da.is_active = 1
                AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
                AND company_id = ".$comp."
                AND da.attendance_type in ('R', 'O')  
        ) g group by eb_id,attendance_date having sum(whrs)>=8
        ) k group by eb_id
        union all
        SELECT
                eb_id,0 wdays,count(*) lvdays
            FROM
                vowsls.leave_transactions lt
            LEFT JOIN vowsls.leave_tran_details ltd ON
                lt.leave_transaction_id = ltd.ltran_id
            WHERE
                leave_type_id IN (24, 25)
                AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
                 AND lt.status = 3
                AND lt.company_id = ".$comp."    and ltd.is_active =1 
        GROUP BY
            eb_id
            union ALL 
            select eb_id,0 wdays,count(*) lvdays from tbl_hrms_holiday_transactions thht 
                    left join holiday_master hm on thht.holiday_id =hm.id 
                    where hm.company_id =".$comp."   and
                     thht.is_active =1 and hm.holiday_date between 
                     '".$periodfromdate."' AND '".$periodtodate."'
                GROUP BY
                    eb_id 
        ) h group by eb_id having sum(wdays+lvdays)>=$attincd
        ) k
        JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
        LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.eb_id = theod.eb_id
        LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
        LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
        WHERE
        substr(theod.emp_code,1,1)='T' 
        AND thaie.att_incn_eligibility = 'Y'
        AND thepd.company_id = ".$comp."
        AND theod.is_active = 1
        AND thaie.is_active = 1
        order by dm.dept_code,
        theod.emp_code";
    }
    
    if  ($holget==2) {

    $sql="SELECT
	k.eb_id,
	theod.emp_code,
	dm.dept_desc,
	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
	round(incdays, 0) incdays,
	elegday - incdays AS lvdays,
	elegday,
	fn_att_inc_rate,
	round(incdays * fn_att_inc_rate, 0) AS incamt
 from (
	select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
	select eb_id,count(*) wdays,0 lvdays from (
	select eb_id,attendance_date,sum(whrs) whrs from (
	SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
	from daily_attendance da
	where 	da.is_active = 1
			AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
			AND company_id = ".$comp."
			AND da.attendance_type in ('R')  
	) g group by eb_id,attendance_date having sum(whrs)>=6
	) k group by eb_id
	union all
	SELECT
			eb_id,0 wdays,count(*) lvdays
		FROM
			vowsls.leave_transactions lt
		LEFT JOIN vowsls.leave_tran_details ltd ON
			lt.leave_transaction_id = ltd.ltran_id
		WHERE
			leave_type_id IN (24, 25)
            AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
             AND lt.status = 3
			AND lt.company_id = ".$comp."    and ltd.is_active =1 
	GROUP BY
		eb_id
        union ALL 
        select eb_id,0 wdays,count(*) lvdays from tbl_hrms_holiday_transactions thht 
                left join holiday_master hm on thht.holiday_id =hm.id 
                where hm.company_id =".$comp."   and
                 thht.is_active =1 and hm.holiday_date between 
                 '".$periodfromdate."' AND '".$periodtodate."'
            GROUP BY
                eb_id 

        ) h group by eb_id having sum(wdays+lvdays)>=$attincd
	) k
	JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
	JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.eb_id
	LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.eb_id = theod.eb_id
	LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
	LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
	WHERE
	tpep.PAY_SCHEME_ID = ".$att_payschm." 
	AND thaie.att_incn_eligibility = 'Y'
	AND tpep.status = 1
	AND thepd.company_id = ".$comp."
	AND theod.is_active = 1
	AND thaie.is_active = 1
    order by dm.dept_code,
	theod.emp_code";

    $sql="select k.employeeid eb_id,eb_no emp_code,department dept_desc,wname empname,
    case when incamt<20 then incamt/1 
    else incamt/20 end incdays,0 lbdays,0 elegdays,0 att_inc_rate,incamt 
    ,m.mxdept,
            case when shiftcd=1 then 'A'  
             when shiftcd=2 then 'B' 
            when shiftcd=3 then 'C' else 'G' end shift from (
                SELECT
                from_date,
                to_date,
                employeeid ,
                eb_no ,
                wname,
                dept_code,
                department,
                desig,
                time_piece,
                max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
                max( case when COMPONENT_ID = 248 then amount else 0 end ) AS incamt
                 FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    d.desig,
                    d.time_piece,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                left join designation d on theod.designation_id=d.id  
                left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                WHERE
                tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                and k.PAYSCHEME_ID=".$att_payschm." 
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                    and tpp.STATUS <>4
                    AND theod.is_active = 1
                    ) g
            GROUP BY		
            tpep.EMPLOYEEID,
                eb_no,
                wname,
                desig,
                time_piece,
                department,
                dept_code,tbl_hrms_ed_bank_detail_id
                ) k left join (    
          select ebno,max(dept_code) mxdept from (      
         select distinct(eb_no) ebno,dept_code from daily_attendance da 
         left join department_master dm on dm.dept_id =da.worked_department_id 
         where attendance_date between '".$periodfromdate."' and '".$periodtodate."' and da.company_id =".$comp."
         ) m group by ebno ) m on k.eb_no=m.ebno
                where incamt>0 order by dept_code,eb_no";




    }
  //echo $sql;   

$query = $this->db->query($sql);
//    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
      //  var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getFNattincentiveDatamdpl($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

//    $this->db->db_debug = TRUE;
 
//CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name, ' '), ' ', IFNULL(thepd.last_name, ' ')) AS empname,
if ($holget==2) { $attp="('R')";}
//echo 'choss-'.$holget;
//echo 'ro-'.$attp;

$stdwrkhrs=8;
$attincd=12;
 


$sql="SELECT
	k.eb_id,
	theod.emp_code,
	dm.dept_desc,
	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
	round(incdays, 0) incdays,
	elegday - incdays AS lvdays,
	elegday,
	fn_att_inc_rate,
	round(incdays * fn_att_inc_rate, 0) AS incamt
 from (
	select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
	select eb_id,count(*) wdays,0 lvdays from (
	select eb_id,attendance_date,sum(whrs)
     whrs from (
	SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
	from daily_attendance da
	where 	da.is_active = 1
			AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
			AND company_id = ".$comp."
			AND da.attendance_type in ('R')  
	) g group by eb_id,attendance_date having sum(whrs)>=6
	) k group by eb_id
	union all
    select eb_id,0 wdays,count(*) lvdays
	from tbl_hrms_holiday_transactions  thht
	left join holiday_master hm on hm.id =thht.holiday_id 
	where hm.holiday_date  
    BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
         	AND hm.company_id = ".$comp."    and is_active =1 
             GROUP BY
             eb_id         	
    union all
	SELECT
			eb_id,0 wdays,count(*) lvdays
		FROM
			vowsls.leave_transactions lt
		LEFT JOIN vowsls.leave_tran_details ltd ON
			lt.leave_transaction_id = ltd.ltran_id
		WHERE
			leave_type_id IN (24, 25)
            AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
             AND lt.status = 3
			AND lt.company_id = ".$comp."    and ltd.is_active =1 
	GROUP BY
		eb_id
	) h group by eb_id having sum(wdays+lvdays)>=$attincd
	) k
	JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
	JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.eb_id
	LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1) theod ON 	k.eb_id = theod.eb_id
	LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
	LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
	WHERE
    substr(theod.emp_code,1,1) in ('1','0','8','5')    
    AND thaie.att_incn_eligibility = 'Y'
	AND tpep.status = 1
	AND thepd.company_id = ".$comp."
	AND theod.is_active = 1
	AND thaie.is_active = 1
order by
	theod.emp_code";

//zecho $sql;   

$query = $this->db->query($sql);
//    $query = $this->db->get($sql);
  //  echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getMNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

//echo $periodfromdate.'='.$periodtodate;
$start = new DateTime($periodfromdate);
$end = new DateTime($periodtodate);

$interval = new DateInterval('P1D'); // 1 day interval
$period = new DatePeriod($start, $interval, $end);
$tdays=1;
$sundays = 0;
foreach ($period as $date) {
   
    $tdays++;
    if ($date->format('D') === 'Sun') {
        $sundays++;
    }
}

 //   echo 'sundays='.$sundays;
  //  $elgdays=$tdays-$sundays;
 //   echo 'total='.$tdays.'=='. $elgdays;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    
   //  echo $sql;   
   if  ($holget<>8) {
    $sql="select count(*) nondays from EMPMILL12.tbl_non_working_days where non_working_date 
    between '".$periodfromdate."' AND '".$periodtodate."'
    AND company_id = ".$comp." and  is_active=1 and offday_leave=1";
    $ndays=0;
    $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() > 0) {
             $ndays = $data[0]->nondays;
        } 
 $elgdays=$tdays-$ndays;
 
$sql="SELECT
   k.eb_id,
   theod.emp_code,
   dm.dept_desc,
   CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
   round(incdays, 0) incdays,
   elegday - incdays AS lvdays,
   elegday,
   fn_att_inc_rate,
   round(incdays * mn_att_inc_rate, 0) AS incamt
from (
   select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
   select eb_id,count(*) wdays,0 lvdays from (
   select eb_id,attendance_date,sum(whrs) whrs from (
   SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
   from daily_attendance da
   where 	da.is_active = 1
           AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
           AND company_id = ".$comp."
           AND da.attendance_type in ('R', 'O')  
   ) g group by eb_id,attendance_date having sum(whrs)>=6
   ) k group by eb_id
   union all
   SELECT
           eb_id,0 wdays,count(*) lvdays
       FROM
           vowsls.leave_transactions lt
       LEFT JOIN vowsls.leave_tran_details ltd ON
           lt.leave_transaction_id = ltd.ltran_id
       WHERE
           leave_type_id IN (24, 25)
           AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
            AND lt.status = 3
           AND lt.company_id = ".$comp."    and ltd.is_active =1 
   GROUP BY
       eb_id
       union ALL 
       select eb_id,0 wdays,count(*) lvdays from tbl_hrms_holiday_transactions thht 
               left join holiday_master hm on thht.holiday_id =hm.id 
               where hm.company_id =".$comp." and
                thht.is_active =1 and hm.holiday_date between 
                '".$periodfromdate."' AND '".$periodtodate."'
           GROUP BY
               eb_id 

   ) h group by eb_id having sum(wdays+lvdays)>=".$elgdays."
   ) k
   JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
   JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.eb_id
   LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1) theod ON 	k.eb_id = theod.eb_id
   LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
   LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
   WHERE
   tpep.PAY_SCHEME_ID = ".$att_payschm." 
   AND thaie.att_incn_eligibility = 'Y'
   AND tpep.status = 1
   AND thepd.company_id = ".$comp."
   AND theod.is_active = 1
   AND thaie.is_active = 1
order by
   theod.emp_code";
   } else {
    $sql="select count(*) nondays from EMPMILL12.tbl_non_working_days where non_working_date 
    between '".$periodfromdate."' AND '".$periodtodate."'
    AND company_id = ".$comp." and  is_active=1 ";
    $ndays=0;
    $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() > 0) {
             $ndays = $data[0]->nondays;
        } 
 $elgdays=$tdays-$ndays;

$sql="SELECT
    k.eb_id,
    theod.emp_code,
    dm.dept_desc,
    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
    round(incdays, 0) incdays,
    elegday - incdays AS lvdays,
    elegday,
    fn_att_inc_rate,
    round(incdays * mn_att_inc_rate, 0) AS incamt
 from (
    select eb_id,sum(wdays) incdays,sum(lvdays) lvdays,sum(wdays+lvdays) elegday from (
    select eb_id,count(*) wdays,0 lvdays from (
    select eb_id,attendance_date,sum(whrs) whrs from (
    SELECT eb_id,attendance_date,case when (spell='C' and (working_hours-idle_hours)>=7.5) THEN 8 else working_hours-idle_hours end whrs  
    from daily_attendance da
    where 	da.is_active = 1
            AND attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
            AND company_id = ".$comp."
            AND da.attendance_type in ('R', 'O')  
    ) g group by eb_id,attendance_date having sum(whrs)>=8
    ) k group by eb_id
    union all
    SELECT
            eb_id,0 wdays,count(*) lvdays
        FROM
            vowsls.leave_transactions lt
        LEFT JOIN vowsls.leave_tran_details ltd ON
            lt.leave_transaction_id = ltd.ltran_id
        WHERE
            leave_type_id IN (24, 25)
            AND ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
             AND lt.status = 3
            AND lt.company_id = ".$comp."    and ltd.is_active =1 
    GROUP BY
        eb_id
        union all
        select eb_id,0 wdays,count(*) lvdays from tbl_hrms_holiday_transactions thht 
        left join holiday_master hm on thht.holiday_id =hm.id 
        where hm.company_id =".$comp." and
         thht.is_active =1 and hm.holiday_date between 
         '".$periodfromdate."' AND '".$periodtodate."'
    GROUP BY
        eb_id 
) h group by eb_id having sum(wdays+lvdays)>=".$elgdays."
    ) k
    JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
    LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1) theod ON 	k.eb_id = theod.eb_id
    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
    LEFT JOIN vowsls.department_master dm on theod.department_id = dm.dept_id
    WHERE
    substr(theod.emp_code,1,1)='T' 
    AND thaie.att_incn_eligibility = 'Y'
    AND thepd.company_id = ".$comp."
    AND theod.is_active = 1
    AND thaie.is_active = 1
    order by dm.dept_code,
    theod.emp_code";
   }
   echo $sql;
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getattwagesins($periodfromdate,$periodtodate,$att_payschm,$holget,$lnformula,$compponentid) {
    $usr=$this->session->userdata('userid');
    $comp = $this->session->userdata('companyId');
    $cdt=date("Y-m-d");
    if ($lnformula==1) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, 
        EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
        SELECT
        tpes.COMPONENT_ID,
        IFNULL(fh.fhrs, 0) AS fhrs,
        tpes.EMPLOYEEID,
        1 AS stat,".$usr."
        AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
    FROM
        tbl_pay_employee_structure tpes
    JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
    LEFT JOIN (
        SELECT
            thht.eb_id,
            SUM(thht.holiday_hours) AS fhrs
        FROM
            tbl_hrms_holiday_transactions thht
        JOIN holiday_master hm ON hm.id = thht.holiday_id
        WHERE
            hm.company_id =".$comp."
            AND hm.holiday_date BETWEEN '".$periodfromdate."'  AND '".$periodtodate."'
            AND thht.is_active = 1
        GROUP BY
            thht.eb_id
    ) fh ON fh.eb_id = tpes.EMPLOYEEID
    WHERE
        tpep.PAY_SCHEME_ID =".$att_payschm."
        AND tpep.STATUS = 1
        AND tpes.STATUS = 1
        AND tpes.COMPONENT_ID = ".$compponentid;

    }
    if ($lnformula==4) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, 
        EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
        SELECT
        tpes.COMPONENT_ID,
        IFNULL(fh.fhrs, 0) AS fhrs,
        tpes.EMPLOYEEID,
        1 AS stat,".$usr."
        AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
        FROM
        tbl_pay_employee_structure tpes
    JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
    LEFT JOIN (
        
    SELECT
            eb_id,
            SUM(whrs+nhrs) AS fhrs 
        from (    
            select da.*,case when (da.whrs=7.5 and shift='C') then 0.5 else 0 end nhrs from   
            (
            select da.eb_id,da.attendance_date,substr(spell,1,1) shift,sum(da.working_hours-idle_hours) whrs from 
            daily_attendance da
            where da.company_id = 2
            AND da.attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
            AND da.is_active = 1 and da.attendance_type='R' 
            group by da.eb_id,da.attendance_date,substr(spell,1,1)
            ) da 
            ) g group by eb_id
    ) fh ON fh.eb_id = tpes.EMPLOYEEID
    WHERE
    tpep.PAY_SCHEME_ID =".$att_payschm."
    AND tpep.STATUS = 1
    AND tpes.STATUS = 1
    AND tpes.COMPONENT_ID = ".$compponentid;
;
     
    }    
    if ($lnformula==2) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
        SELECT
            tpes.COMPONENT_ID,
            IFNULL(fh.fhrs, 0) AS fhrs,
            tpes.EMPLOYEEID,
            1 AS stat,".$usr."
         AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
        FROM
            tbl_pay_employee_structure tpes
        JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
        LEFT JOIN (
            SELECT
                da.eb_id,
                SUM(whrs) AS fhrs
            FROM
               ( select eb_id,attendance_date,is_active,attendance_type,spell,company_id,case when spell='C' and (working_hours-idle_hours)=7.5 then 8
                else (working_hours-idle_hours) end whrs from daily_attendance da
            WHERE
                da.company_id =".$comp."
                AND da.attendance_date BETWEEN '".$periodfromdate."'  AND '".$periodtodate."'
                AND da.is_active = 1 and attendance_type='O'
                ) da
            WHERE
                da.company_id =".$comp."
                AND da.attendance_date BETWEEN '".$periodfromdate."'  AND '".$periodtodate."'
                AND da.is_active = 1 and attendance_type='O'
            GROUP BY
                da.eb_id
        ) fh ON fh.eb_id = tpes.EMPLOYEEID
        WHERE
        tpep.PAY_SCHEME_ID =".$att_payschm."
        AND tpep.STATUS = 1
        AND tpes.STATUS = 1
        AND tpes.COMPONENT_ID = ".$compponentid;
        echo $sql;
        
    }    

    if ($lnformula==5) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, 
        EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
SELECT
        tpes.COMPONENT_ID,
        0 AS fhrs,
        tpes.EMPLOYEEID,
        1 AS stat,".$usr."
            AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
            FROM
            tbl_pay_employee_structure tpes
            JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
            WHERE
            tpep.PAY_SCHEME_ID =".$att_payschm."
        AND tpep.STATUS = 1
        AND tpes.STATUS = 1
        AND tpes.COMPONENT_ID = ".$compponentid;
        
    }    

    if ($lnformula==3) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
SELECT
    tpes.COMPONENT_ID,
    IFNULL(fh.fhrs, 0) AS fhrs,
    tpes.EMPLOYEEID,
    1 AS stat,".$usr."
            AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
FROM
    tbl_pay_employee_structure tpes
JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
LEFT JOIN (
    SELECT
        tlbt.eb_id,
        tlat.installment_amount fhrs from EMPMILL12.tbl_loan_adv_transaction tlat 
        LEFT join EMPMILL12.tbl_loan_advance_table tlbt on tlat.loan_adv_id=tlbt.loan_adv_id
        where tlat.period_from='".$periodfromdate."' and tlat.period_to='".$periodtodate."'
        and tlat.status_id=3 and tlat.is_active=1 and tlbt.is_active=1
) fh ON fh.eb_id = tpes.EMPLOYEEID
WHERE
tpep.PAY_SCHEME_ID =".$att_payschm."
AND tpep.STATUS = 1
AND tpes.STATUS = 1
AND tpes.COMPONENT_ID = ".$compponentid;
    }    

    if ($lnformula==6) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, 
        EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
        SELECT
        tpes.COMPONENT_ID,
        0 AS fhrs,
        tpes.EMPLOYEEID,
        1 AS stat,".$usr."
            AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
            FROM
            tbl_pay_employee_structure tpes
            JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
            WHERE
            tpep.PAY_SCHEME_ID =".$att_payschm."
        AND tpep.STATUS = 1
        AND tpes.STATUS = 1
        AND tpes.COMPONENT_ID = ".$compponentid;
    }

    if ($lnformula==7) {
        $sql="INSERT INTO vowsls.tbl_pay_components_custom (COMPONENT_ID, VALUE, 
        EMPLOYEEID, STATUS, CREATED_BY, CREATED_DATE, FROM_DATE, TO_DATE)
SELECT
        tpes.COMPONENT_ID,
        0 AS fhrs,
        tpes.EMPLOYEEID,
        1 AS stat,".$usr."
            AS cby,'".$cdt."' AS cdate,'".$periodfromdate."' AS fdate,'".$periodtodate."' AS edate
            FROM
            tbl_pay_employee_structure tpes
            JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = tpes.EMPLOYEEID
            WHERE
            tpep.PAY_SCHEME_ID =".$att_payschm."
        AND tpep.STATUS = 1
        AND tpes.STATUS = 1
        AND tpes.COMPONENT_ID = ".$compponentid;
    }
//echo $sql;

    $this->db->query($sql);


}
public function getattwagesData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo $comp;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    $asql="select g.eb_id,emp_code,sum(whrs) whrs,sum(festhrs) festhrs,sum(othrs) othrs,sum(nshrs) nshrs from ( 
        SELECT
        eb_id,
        (working_hours - idle_hours) AS whrs,
        0 AS festhrs,
        0 AS othrs,
        CASE
            WHEN (spell = 'C' AND (working_hours - idle_hours) >= 7.5) THEN 0.5
            ELSE 0
        END AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        daily_attendance da
        LEFT JOIN tbl_pay_employee_payscheme tpep ON da.eb_id = tpep.EMPLOYEEID
    WHERE
        attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND da.is_active = 1
        AND attendance_type = 'R'
        AND tpep.PAY_SCHEME_ID = ".$att_payschm." 
        AND da.company_id= ".$comp."
        UNION ALL
    SELECT
        eb_id,
        0 AS whrs,
        0 AS festhrs,
        case when (working_hours - idle_hours)>=7.5 then 8
        else (working_hours - idle_hours) end othrs,
        0 AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        daily_attendance da
        LEFT JOIN tbl_pay_employee_payscheme tpep ON da.eb_id = tpep.EMPLOYEEID
    WHERE
        attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND da.is_active = 1
        AND attendance_type = 'O'
        AND tpep.PAY_SCHEME_ID =".$att_payschm." 
        AND da.company_id= ".$comp."
    
    UNION ALL
    
    SELECT
        eb_id,
        0 AS whrs,
        (holiday_hours) AS festhrs,
        0 AS othrs,
        0 AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        tbl_hrms_holiday_transactions thht
        LEFT JOIN holiday_master hm ON thht.holiday_id = hm.id
        LEFT JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = thht.eb_id
    WHERE
        hm.holiday_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND thht.is_active = 1
        AND tpep.PAY_SCHEME_ID =".$att_payschm." 
        ) g left join tbl_hrms_ed_official_details theod on g.eb_id=theod.eb_id
        LEFT JOIN
        vowsls.tbl_hrms_ed_personal_details thepd ON g.eb_id = thepd.eb_id
          where theod.is_active=1
      AND thepd.company_id = ".$comp."
      GROUP BY
        g.eb_id,emp_code77777
         "; 
    
    
    $sql="select  g.eb_id,emp_code,sum(whrs) whrs,sum(festhrs) festhrs,sum(othrs) othrs,sum(nshrs) nshrs from ( 
        SELECT
        eb_id,
        (working_hours - idle_hours) AS whrs,
        0 AS festhrs,
        0 AS othrs,
        CASE
            WHEN (spell = 'C' AND (working_hours - idle_hours) >= 7.5) THEN 0.5
            ELSE 0
        END AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        daily_attendance da
        LEFT JOIN tbl_pay_employee_payscheme tpep ON da.eb_id = tpep.EMPLOYEEID
    WHERE
        attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND da.is_active = 1
        AND attendance_type = 'R'
        AND tpep.PAY_SCHEME_ID = ".$att_payschm." 
        AND da.company_id= ".$comp."
        UNION ALL
    SELECT
        eb_id,
        0 AS whrs,
        0 AS festhrs,
        case when spell='C' and (working_hours - idle_hours)>=7.5 then 8
        else (working_hours - idle_hours) end othrs,
        0 AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        daily_attendance da
        LEFT JOIN tbl_pay_employee_payscheme tpep ON da.eb_id = tpep.EMPLOYEEID
    WHERE
        attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND da.is_active = 1
        AND attendance_type = 'O'
        AND tpep.PAY_SCHEME_ID =".$att_payschm." 
        AND da.company_id= ".$comp."
    
    UNION ALL
    
    SELECT
        eb_id,
        0 AS whrs,
        (holiday_hours) AS festhrs,
        0 AS othrs,
        0 AS nshrs,
        0 AS advance,
        0 AS arrp,
        0 AS arrm
    FROM
        tbl_hrms_holiday_transactions thht
        LEFT JOIN holiday_master hm ON thht.holiday_id = hm.id
        LEFT JOIN tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = thht.eb_id
    WHERE
        hm.holiday_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
        AND tpep.STATUS = 1
        AND thht.is_active = 1
        AND tpep.PAY_SCHEME_ID =".$att_payschm." 
        ) g left join tbl_hrms_ed_official_details theod on g.eb_id=theod.eb_id
        LEFT JOIN
        vowsls.tbl_hrms_ed_personal_details thepd ON g.eb_id = thepd.eb_id
          where theod.is_active=1
      AND thepd.company_id = ".$comp."
      GROUP BY
        g.eb_id,emp_code
         "; 
    
//     echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function checkmcsumm_data($mcsummdate,$mcsummmcid,$comp,$att_branch,$att_desig,$hol_get) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $mdate=$mcsummdate;
    //substr($mcsummdate,8,2).'/'.substr($mcsummdate,5,2).'/'.substr($mcsummdate,0,4);
    if ($hol_get==1) {
        $sql="select daily_sum_mc_id recordid,spell_a1,spell_a2,spell_b1,spell_b2,shift_a,shift_b,shift_c from 
        EMPMILL12.tbl_daily_summ_mechine_data tdsmd where  company_id=".$comp."
        and branch_id=".$att_branch." and tran_date='".$mdate."' and mc_code_id=".$mcsummmcid."  and is_active=1 ";
    }
    if ($hol_get==2) {
      $sql="select oth_hands_id recordid,spell_a1,spell_a2,spell_b1,spell_b2,shift_a,shift_b,shift_c from 
      EMPMILL12.tbl_daily_other_hands_data tdohd where   company_id=".$comp."
        and branch_id=".$att_branch." and tran_date='".$mdate."' and occu_id=".$att_desig."  and is_active=1";
    }    
//echo $sql;
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}
public function getmcsummData($mcsummdate,$mcsummdeptid,$att_branch,$comp,$hol_get) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $mdate=$mcsummdate;
    if ($hol_get==1) {
        $sql="select daily_sum_mc_id recordid,tran_date,mcm.mc_code  code,mcm.Mechine_type_name name ,spell_a1 ,spell_a2 ,shift_a ,spell_b1 ,spell_b2 ,shift_b,shift_c 
        from EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
        left join EMPMILL12.mechine_code_master mcm on tdsmd.mc_code_id =mcm.mc_code_id  
        where tdsmd.is_active =1 and tran_date='".$mdate."' order by mcm.mc_code";
    }
    if ($hol_get==2) {
        $sql="select oth_hands_id recordid,tran_date,HOCCU_CODE code,d.desig name,spell_a1 ,
        spell_a2 ,tdohd.shift_a ,spell_b1 ,spell_b2 ,tdohd.shift_b,tdohd.shift_c 
        from EMPMILL12.tbl_daily_other_hands_data tdohd 
        left join vowsls.designation d on tdohd.occu_id =d.id 
        left join vowsls.ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID =tdohd.occu_id 
        left join EMPMILL12.OCCUPATION_MASTER om on oolt.ORA_TABLE_ID =om.OCCU_ID 
        where tdohd.is_active =1 and 
         tran_date='".$mdate."' order by HOCCU_CODE";
    }
    //substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
 //echo $sql;

    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getAllDesignationscd($companyId) {
    $comp = $this->session->userdata('companyId');
    $sql="select d.id,concat(om.HOCCU_CODE,'-',d.desig) cddesig from vowsls.designation d 
    left join vowsls.ORA_OCCU_LINK_TABLE oolt on d.id =oolt.MYSQL_TABLE_ID 
    left join EMPMILL12.OCCUPATION_MASTER om on oolt.ORA_TABLE_ID =om.OCCU_ID 
   where d.company_id=".$comp;
  // echo $sql;
     $q = $this->db->query($sql);

    if($q->num_rows() > 0){
        foreach($q->result() as $row){
            $data[] = $row;
        }
        return $data;
    }
    return false;
}


public function getAllDesignations($companyId){
    $this->db->where('company_id',$companyId);
    $q = $this->db->get('designation');
    if($q->num_rows() > 0){
        foreach($q->result() as $row){
            $data[] = $row;
        }
        return $data;
    }
    return false;
}


public function getmillwndData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $mdate=substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
    $attp="('R','O')";
    $sql="select '".$mdate."' as dt,dept_code,eb_no,sum(whrs) whrs,0 a1,sum(othrs) othrs,0 a2,shift,0 a4,occu_code,0 a3,
    occu_id from 
    (select dept_code,eb_no,om.occu_id occu_id,om.OCCU_CODE,
    case when attendance_type ='R' then (working_hours-idle_hours) else 0 end whrs,
    case when attendance_type ='O' then (working_hours-idle_hours) else 0 end othrs,
    substr(spell,1,1) shift 
    from daily_attendance da 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join department_master dm on dm.dept_id=da.worked_department_id
    where da.attendance_date between '".$periodfromdate."' AND '".$periodtodate."' and
    da.company_id=".$comp." and da.is_active=1
    ) g group by eb_no,shift,occu_code,occu_id,dept_code
    order by dt,dept_code,shift,eb_no,occu_code";
//echo $sql;

    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function otregisterprint($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    $sql="SELECT
    eb_no,
    wname,
    dept_code,
    department,
    designation,
    max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE,
    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
    max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
    max( case when COMPONENT_ID = 284 then amount else 0 end ) AS OT_ADVANCE,
    max( case when COMPONENT_ID = 285 then amount else 0 end ) AS MISC_OT_EARNINGS,
    max( case when COMPONENT_ID = 286 then amount else 0 end ) AS OT_NET_PAY
    
 FROM
    (
    SELECT
        tpep.PAYPERIOD_ID,
        tpp.FROM_DATE,
        tpp.TO_DATE,
        tpep.EMPLOYEEID,
        wm.eb_no,
        CONCAT(worker_name, ' ', IFNULL(last_name, ' ')) wname,
        COMPONENT_ID,
        tpc.NAME,
        wm.esi_no,
        wm.pf_no,
        wm.fpf_no,
        AMOUNT,
        dept_desc department,
        desig designation,tpp.status tppstat,tpep.status tpepstat,tpp.PAYSCHEME_ID ,dm.dept_code
    FROM
        tbl_pay_employee_payroll tpep ,
        worker_master wm ,
        tbl_pay_period tpp,
        tbl_pay_components tpc ,
        department_master dm,
        designation dsg
    WHERE
        tpep.EMPLOYEEID = wm.eb_id
        AND wm.dept_id = dm.dept_id
        AND wm.desg_id = dsg.id
        AND tpep.COMPONENT_ID = tpc.ID
        AND tpep.PAYPERIOD_ID = tpp.ID
        and tpep.PAYSCHEME_ID=tpp.PAYSCHEME_ID 
		AND tpep.COMPONENT_ID in (135,237,70,284,285,286) and amount>0
     	and tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' and 
     	tpep.PAYSCHEME_ID=".$att_payschm." and tpep.BUSINESSUNIT_ID=".$comp." and tpep.status=1
     	and tpp.IS_ACTIVE  =1
        and 
        ) g
GROUP BY
    PAYPERIOD_ID,
    eb_no,
    wname,
    fpf_no,
    pf_no,
    esi_no,
    department,
    dept_code,
    designation
ORDER BY
    eb_no,
    wname

     ";
     $sql="select k.*,dm.dept_code,dept_desc department from (
        SELECT
            eb_no,
            wname,
            deptcode,
            departments,
            max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE,
            max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
            max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
            max( case when COMPONENT_ID = 284 then amount else 0 end ) AS OT_ADVANCE,
            max( case when COMPONENT_ID = 285 then amount else 0 end ) AS MISC_OT_EARNINGS,
            max( case when COMPONENT_ID = 286 then amount else 0 end ) AS OT_NET_PAY
                FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc departments,
                dm.dept_code deptcode
            FROM
                tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
            left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
            left join	department_master dm on theod.department_id = dm.dept_id
            WHERE
                 k.COMPONENT_ID in (135, 237, 70,284,285,286)
                and amount>0
                and tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'
                and k.PAYSCHEME_ID =".$att_payschm."
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                and tpp.STATUS <>4
                AND theod.is_active = 1
                ) g
        GROUP BY
            eb_no,
            wname,
            departments,
            deptcode
            ) k 
             left join (  select da.company_id companyid,da.eb_no,max(dept_code) dept_code from daily_attendance da 
            left join department_master dm on da.worked_department_id =dm.dept_id 
            where attendance_date between '".$periodfromdate."' and '".$periodtodate."'
            and da.company_id = ".$comp." and is_active=1 and attendance_type in ('O','R')
            group by eb_no,da.company_id
         	) dpm on dpm.eb_no=k.eb_no 
             left join department_master dm on dm.dept_code=dpm.dept_code and dm.company_id=dpm.companyid
             where OVERTIME_PAY>0 and dm.company_id= ".$comp."
            ORDER BY
            dm.dept_code,eb_no"; 
  // echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getpaystatement($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
     $sql="select k.*,(Net_Payble+OVERTIME_PAY) total_amt from
     (
        SELECT
            eb_no,
            wname,
            dept_code,
            department,
        	max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble,
        	max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY
     FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc department,
                dm.dept_code
            FROM
                tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
            left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
            left join	department_master dm on theod.department_id = dm.dept_id
            WHERE
            k.COMPONENT_ID in (21, 237)
	                and amount>0
                and tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'
                and k.PAYSCHEME_ID =".$att_payschm."
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                and tpp.STATUS <>4
                AND theod.is_active = 1
                ) g
        GROUP BY
            eb_no,
            wname,
            department,
            dept_code
            ) k 
        ORDER BY
        dept_code,eb_no";
         
 //   echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getpaystatementgsumm($periodfromdate,$periodtodate,$att_payschm,$holget) {
    $comp = $this->session->userdata('companyId');

    $attp="('R','O')";
   echo 'choss-'.$holget;
   //echo 'ro-'.$attp;
   if ($holget==1 || $holget==2 || $holget==7) {
    $sql="select sum(WORKING_HOURS) WORKING_HOURS,sum(NS_HRS) NS_HRS,sum(HL_HRS) HL_HRS,sum(LS_HRS) LS_HRS,
    sum(STL_D*8) STL_HRS,SUM(STL_D) STL_D,0 LOFF_HRS,sum(PROD_BASIC) VFIX_BASIC, sum(FIX_BASIC+TIME_RATED_BASIC) FXBAS,SUM(DA) FDA,sum(NS_AMOUNT) NS_AMOUNT,
    sum(HOL_AMT) HOL_AMT,0 LOFAMT,SUM(STL_WGS) STL_WGS,0 ADJPF,SUM(INCREMENTA) INCREMENTA,0 ARREAR ,SUM(PF_GROSS) PFGROSS,
    SUM(GROSS_PAY) GROSS,SUM(HRA) HRA,0 ADJNPF,SUM(INCENTIVE_AMOUNT) INCENTIVE_AMOUNT,SUM(MISS_EARN) MISS_EARN,0 ADHONINC,
    SUM(ESI_GROSS) ESI_GROSS,SUM(EPF) EPF,SUM(ESIC) ESIC,SUM(LWF) LWF,0 PFLOAN,SUM(STL_ADVANCE) STL_ADVANCE,SUM(PUJA_ADVANCE) PUJA_ADVANCE,
    SUM(CO_LOAN) CO_LOAN,0 PENCONT,0 HRENT,0 PFNINT,0 ADV,0 FINE,0 OTHADV,SUM(TOTAL_EARN) TOTAL_EARN, SUM(TOTAL_DEDUCTION) TOTAL_DEDUCTION,
    SUM(TOTAL_EARN-TOTAL_DEDUCTION) NETPAY,SUM(B_F) B_F,SUM(C_F) C_F,SUM(NET_PAY) NETPAYABLE,
    sum(P_TAX) P_TAX,0 TOTPROD,sum(exadvance) exadvance,sum(arrear_plus) arrear_plus,sum(arrear_minus) arrear_minus, 
    sum(Basic_amount) Basic_amount FROM 
(    select k.*,m.mxdept,
        case when shiftcd=1 then 'A'  
         when shiftcd=2 then 'B' 
        when shiftcd=3 then 'C' else 'G' end shift from (
            SELECT
            from_date,
            to_date,
            employeeid,
            eb_no,
            wname,
            dept_code,
            department,
            desig,
            time_piece,
            max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
            max( case when COMPONENT_ID = 238 then amount else 0 end ) AS BASIC_RATE,
            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
            max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
            max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
            max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
            max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
            max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
            max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
            max( case when COMPONENT_ID = 251 then amount else 0 end ) AS PROD_BASIC,
            max( case when COMPONENT_ID = 189 then amount else 0 end ) AS TIME_RATED_BASIC,
            max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
            max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
            max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
            max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
            max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
            max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
            max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
            max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
            max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
            max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
            max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
            max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
            max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
            max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
            max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
            max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
            max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
            max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
            max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100,0) else 0 end ) AS epf_833,
            max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100,0) else 0 end ) AS epf_167,
            max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
            max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
            max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
            max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
            max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
            max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
            max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
            max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
            max( case when COMPONENT_ID = 21 then amount else 0 end ) AS NET_PAY,
            max( case when COMPONENT_ID = 166 then amount else 0 end ) AS exadvance, 
            max( case when COMPONENT_ID = 268 then amount else 0 end ) AS arrear_plus,
            max( case when COMPONENT_ID = 269 then amount else 0 end ) AS arrear_minus,
            max( case when COMPONENT_ID = 7 then amount else 0 end ) AS Basic_amount
     FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc department,
                dm.dept_code,
                d.desig,
                d.time_piece,
                thebd.tbl_hrms_ed_bank_detail_id
            FROM
                tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
            left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
            left join	department_master dm on theod.department_id = dm.dept_id
            left join designation d on theod.designation_id=d.id  
            left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
            WHERE
            tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
            and k.PAYSCHEME_ID=".$att_payschm."  
            and k.BUSINESSUNIT_ID = ".$comp."
            and k.status = 1
                and tpp.STATUS <>4
                AND theod.is_active = 1
                ) g
        GROUP BY		
        tpep.EMPLOYEEID,
            eb_no,
            wname,
            desig,
            time_piece,
            department,
            dept_code,tbl_hrms_ed_bank_detail_id
            ) k left join (    
      select ebno,max(dept_code) mxdept from (      
     select distinct(eb_no) ebno,dept_code from daily_attendance da 
     left join department_master dm on dm.dept_id =da.worked_department_id 
     where attendance_date between '".$periodfromdate."' and '".$periodtodate."' and da.company_id =".$comp."
     ) m group by ebno ) m on k.eb_no=m.ebno
            where TOTAL_EARN>0 ";
            if ($holget==2) $sql=$sql."and  EPF>0";
            if ($holget==7) $sql=$sql."and  EPF=0";
            $sql=$sql."        
   
            ORDER BY dept_code,
            eb_no  
 ) G ";

 $query = $this->db->query($sql);
 //    $query = $this->db->get($sql);
//   echo $this->db->last_query();            

   
 $data=$query->result();
 if ($query->num_rows() > 0) {
//     var_dump($data);
     return $data;
 } else {
     return array(); // Return an empty array if no results are found
 }

   }
}    
public function getpaystatementsumm($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==1 || $holget==2 || $holget==7) {

        $sql="select mxdept,sum(WORKING_HOURS) WORKING_HOURS,sum(NS_HRS) NS_HRS,sum(HL_HRS) HL_HRS,sum(LS_HRS) LS_HRS,
        sum(STL_D*8) STL_HRS,SUM(STL_D) STL_D,0 LOFF_HRS,sum(FIX_BASIC+PROD_BASIC) VFIX_BASIC, TIME_RATED_BASIC FXBAS,SUM(DA) FDA,sum(NS_AMOUNT) NS_AMOUNT,
        sum(HOL_AMT) HOL_AMT,0 LOFAMT,SUM(STL_WGS) STL_WGS,0 ADJPF,SUM(INCREMENTA) INCREMENTA,0 ARREAR ,SUM(GROSS_PAY) PFGROSS,
        SUM(GROSS_PAY) GROSS,SUM(HRA) HRA,0 ADJNPF,SUM(INCENTIVE_AMOUNT) INCENTIVE_AMOUNT,SUM(MISS_EARN) MISS_EARN,0 ADHONINC,
        SUM(ESI_GROSS) ESI_GROSS,SUM(EPF) EPF,SUM(ESIC) ESIC,SUM(LWF) LWF,0 PFLOAN,SUM(STL_ADVANCE) STL_ADVANCE,SUM(PUJA_ADVANCE) PUJA_ADVANCE,
        SUM(CO_LOAN) CO_LOAN,0 PENCONT,0 HRENT,0 PFNINT,0 ADV,0 FINE,0 OTHADV,SUM(TOTAL_EARN) TOTAL_EARN, SUM(TOTAL_DEDUCTION) TOTAL_DEDUCTION,
        SUM(TOTAL_EARN-TOTAL_DEDUCTION) NETPAY,SUM(B_F) B_F,SUM(C_F) C_F,SUM(NET_PAY) NETPAYABLE,
        sum(P_TAX) P_TAX,0 TOTPROD,sum(exadvance) exadvance,sum(arrear_plus) arrear_plus,sum(arrear_minus) arrear_minus,
        sum(Basic_amount) Basic_amount 
        FROM 
    (    select k.*,m.mxdept,
            case when shiftcd=1 then 'A'  
             when shiftcd=2 then 'B' 
            when shiftcd=3 then 'C' else 'G' end shift from (
                SELECT
                from_date,
                to_date,
                employeeid,
                eb_no,
                wname,
                dept_code,
                department,
                desig,
                time_piece,
                max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
                max( case when COMPONENT_ID = 238 then amount else 0 end ) AS BASIC_RATE,
                max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
                max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
                max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
                max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
                max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
                max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
                max( case when COMPONENT_ID = 251 then amount else 0 end ) AS PROD_BASIC,
                max( case when COMPONENT_ID = 189 then amount else 0 end ) AS TIME_RATED_BASIC,
                max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
                max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
                max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
                max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
                max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
                max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
                max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
                max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
                max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
                max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
                max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
                max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
                max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
                max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
                max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
                max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
                max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
                max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
                max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100,0) else 0 end ) AS epf_833,
                max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100,0) else 0 end ) AS epf_167,
                max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
                max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
                max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
                max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
                max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
                max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
                max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
                max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
                max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
                max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
                max( case when COMPONENT_ID = 21 then amount else 0 end ) AS NET_PAY
                max( case when COMPONENT_ID = 166 then amount else 0 end ) AS exadvance, 
                max( case when COMPONENT_ID = 268 then amount else 0 end ) AS arrear_plus,
                max( case when COMPONENT_ID = 269 then amount else 0 end ) AS arrear_minus,
                max( case when COMPONENT_ID = 7 then amount else 0 end ) AS Basic_amount
                FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    d.desig,
                    d.time_piece,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                left join designation d on theod.designation_id=d.id  
                left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                WHERE
                tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                and k.PAYSCHEME_ID=".$att_payschm."  
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                    and tpp.STATUS <>4
                    AND theod.is_active = 1
                    ) g
            GROUP BY		
            tpep.EMPLOYEEID,
                eb_no,
                wname,
                desig,
                time_piece,
                department,
                dept_code,tbl_hrms_ed_bank_detail_id
                ) k left join (    
          select ebno,max(dept_code) mxdept from (      
         select distinct(eb_no) ebno,dept_code from daily_attendance da 
         left join department_master dm on dm.dept_id =da.worked_department_id 
         where attendance_date between '".$periodfromdate."' and '".$periodtodate."' and da.company_id =".$comp."
         ) m group by ebno ) m on k.eb_no=m.ebno
                where TOTAL_EARN>0 and epf>0    
                ORDER BY dept_code,
                eb_no  
     ) G GROUP BY mxdept
     order by mxdept";
     
  //   echo $sql;
/////////////////////////////////////////////////////////////
      $sql="select
      dept_code,
      sum(WORKING_HOURS) WORKING_HOURS,
      sum(NS_HRS) NS_HRS,
      sum(HL_HRS) HL_HRS,
      sum(LS_HRS) LS_HRS,
      sum(STL_D * 8) STL_HRS,
      SUM(STL_D) STL_D,
      0 LOFF_HRS,
      sum(FIX_BASIC+PROD_BASIC) VFIX_BASIC,
      sum(TIME_RATED_BASIC) FXBAS,
      SUM(DA) FDA,
      sum(NS_AMOUNT) NS_AMOUNT,
      sum(HOL_AMT) HOL_AMT,
      0 LOFAMT,
      SUM(STL_WGS) STL_WGS,
      0 ADJPF,
      SUM(INCREMENTA) INCREMENTA,
      0 ARREAR ,
      SUM(GROSS_PAY) PFGROSS,
      SUM(GROSS_PAY) GROSS,
      SUM(HRA) HRA,
      0 ADJNPF,
      SUM(INCENTIVE_AMOUNT) INCENTIVE_AMOUNT,
      SUM(MISS_EARN) MISS_EARN,
      0 ADHONINC,
      SUM(ESI_GROSS) ESI_GROSS,
      SUM(EPF) EPF,
      SUM(ESIC) ESIC,
      SUM(LWF) LWF,
      0 PFLOAN,
      SUM(STL_ADVANCE) STL_ADVANCE,
      SUM(PUJA_ADVANCE) PUJA_ADVANCE,
      SUM(CO_LOAN) CO_LOAN,
      0 PENCONT,
      0 HRENT,
      0 PFNINT,
      0 ADV,
      0 FINE,
      0 OTHADV,
      SUM(TOTAL_EARN) TOTAL_EARN,
      SUM(TOTAL_DEDUCTION) TOTAL_DEDUCTION,
      SUM(TOTAL_EARN-TOTAL_DEDUCTION) NETPAY,
      SUM(B_F) B_F,
      SUM(C_F) C_F,
      SUM(NET_PAY) NETPAYABLE,
      sum(P_TAX) P_TAX,
      0 TOTPROD
  FROM
      (
  select
      k.*,
      pf_uan_no,
      esi_no,
      pf_no,
      case
          when shiftcd = 1 then 'A'
          when shiftcd = 2 then 'B'
          when shiftcd = 3 then 'C'
          else 'G'
      end shift,
      dm.dept_desc deparment,
      dpm.dept_code dept_code
  from
      (
      SELECT
          from_date,
          to_date,
          employeeid,
          eb_no,
          wname,
          dept_code dept_codes,
          department department,
          desig,
          time_piece,
          max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
          max( case when COMPONENT_ID = 238 then round(amount, 2) else 0 end ) AS BASIC_RATE,
          max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
          max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
          max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
          max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
          max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
          max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
          max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
          max( case when COMPONENT_ID = 251 then round(amount, 2) else 0 end ) AS PROD_BASIC,
          max( case when COMPONENT_ID = 189 then round(amount, 2) else 0 end ) AS TIME_RATED_BASIC,
          max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
          max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
          max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
          max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
          max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
          max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
          max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
          max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
          max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
          max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
          max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
          max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
          max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
          max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
          max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
          max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
          max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
          max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
          max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100, 0) else 0 end ) AS epf_833,
          max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100, 0) else 0 end ) AS epf_167,
          max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
          max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
          max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
          max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
          max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
          max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
          max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
          max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
          max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
          max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
          max( case when COMPONENT_ID = 21 then round(amount, 2) else 0 end ) AS NET_PAY,
          max( case when COMPONENT_ID = 239 then amount else 0 end ) AS PFG100,
          max( case when COMPONENT_ID = 245 then amount else 0 end ) AS PF100,
          max( case when COMPONENT_ID = 247 then amount else 0 end ) AS NET100,
          max( case when COMPONENT_ID = 244 then amount else 0 end ) AS TOTAL100,
          max( case when COMPONENT_ID = 243 then amount else 0 end ) AS GROSS_PAY100,
          max( case when COMPONENT_ID = 242 then amount else 0 end ) AS HRA_100
      FROM
          (
          SELECT
              k.PAYPERIOD_ID,
              tpp.FROM_DATE,
              tpp.TO_DATE,
              tpep.EMPLOYEEID,
              theod.emp_code eb_no,
              CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
              COMPONENT_ID,
              tpc.NAME,
              AMOUNT,
              dept_desc department,
              dm.dept_code,
              d.desig,
              d.time_piece,
              thebd.tbl_hrms_ed_bank_detail_id
          FROM
              tbl_pay_employee_payroll k
          JOIN vowsls.tbl_pay_employee_payscheme tpep ON
              tpep.EMPLOYEEID = k.EMPLOYEEID
          LEFT JOIN (
              select
                  *
              from
                  vowsls.tbl_hrms_ed_official_details
              where
                  is_active = 1) theod ON
              k.EMPLOYEEID = theod.eb_id
          LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON
              k.EMPLOYEEID = thepd.eb_id
          left join tbl_pay_period tpp on
              tpp.ID = k.PAYPERIOD_ID
          left join tbl_pay_components tpc on
              tpc.ID = k.COMPONENT_ID
          left join department_master dm on
              theod.department_id = dm.dept_id
          left join designation d on
              theod.designation_id = d.id
          left join (
              select
                  *
              from
                  tbl_hrms_ed_bank_details
              where
                  is_active = 1 )thebd on
              thebd.eb_id = k.EMPLOYEEID
          WHERE
              tpp.FROM_DATE = '".$periodfromdate."'
              and tpp.TO_DATE = '".$periodtodate."'
              and k.PAYSCHEME_ID =".$att_payschm."  
              and k.BUSINESSUNIT_ID =  ".$comp."
              and k.status = 1
              and tpp.STATUS <> 4
              AND theod.is_active = 1 ) g
      GROUP BY
          tpep.EMPLOYEEID,
          eb_no,
          wname,
          desig,
          time_piece,
          department,
          dept_code,
          tbl_hrms_ed_bank_detail_id ) k
  left join (
      select
          *
      from
          tbl_hrms_ed_pf
      where
          is_active = 1 ) thep on
      thep.eb_id = k.EMPLOYEEID
  left join (
      select
          *
      from
          tbl_hrms_ed_esi
      where
          is_active = 1 ) thee on
      thee.eb_id = k.EMPLOYEEID
  left join (
      select
          da.company_id companyid,
          da.eb_id,
          max(dept_code) dept_code
      from
          daily_attendance da
      left join department_master dm on
          da.worked_department_id = dm.dept_id
      where
          attendance_date between '".$periodfromdate."' and '".$periodtodate."'
          and da.company_id = ".$comp."
          and is_active = 1
          and attendance_type = 'R'
      group by
          eb_id,
          da.company_id ) dpm on
      dpm.eb_id = k.EMPLOYEEID
  left join department_master dm on
      dpm.dept_code = dm.dept_code
      and dm.company_id = dpm.companyid
      where TOTAL_EARN>0 ";
      if ($holget==2) $sql=$sql."and  EPF>0";
      if ($holget==7) $sql=$sql."and  EPF=0";
      $sql=$sql."        
      ) G
  GROUP BY
      dept_code
  order by
      dept_code
  ";      

  //echo $sql;
/////////////////////////////////////////////////////////////



    }

    if ($holget==99) {

        $sql="select sum(WORKING_HOURS) WORKING_HOURS,sum(NS_HRS) NS_HRS,sum(HL_HRS) HL_HRS,sum(LS_HRS) LS_HRS,
        sum(STL_D*8) STL_HRS,SUM(STL_D) STL_D,0 LOFF_HRS,sum(FIX_BASIC) VFIX_BASIC, 0 FXBAS,SUM(DA) FDA,sum(NS_AMOUNT) NS_AMOUNT,
        sum(HOL_AMT) HOL_AMT,0 LOFAMT,SUM(STL_WGS) STL_WGS,0 ADJPF,SUM(INCREMENTA) INCREMENTA,0 ARREAR ,SUM(GROSS_PAY) PFGROSS,
        SUM(GROSS_PAY) GROSS,SUM(HRA) HRA,0 ADJNPF,SUM(INCENTIVE_AMOUNT) INCENTIVE_AMOUNT,SUM(MISS_EARN) MISS_EARN,0 ADHONINC,
        SUM(ESI_GROSS) ESI_GROSS,SUM(EPF) EPF,SUM(ESIC) ESIC,SUM(LWF) LWF,0 PFLOAN,SUM(STL_ADVANCE) STL_ADVANCE,SUM(PUJA_ADVANCE) PUJA_ADVANCE,
        SUM(CO_LOAN) CO_LOAN,0 PENCONT,0 HRENT,0 PFNINT,0 ADV,0 FINE,0 OTHADV,SUM(TOTAL_EARN) TOTAL_EARN, SUM(TOTAL_DEDUCTION) TOTAL_DEDUCTION,
        SUM(TOTAL_EARN-TOTAL_DEDUCTION) NETPAY,SUM(B_F) B_F,SUM(C_F) C_F,SUM(NET_PAY) NETPAYABLE,
        sum(P_TAX) P_TAX,0 TOTPROD FROM 
    (    select k.*,m.mxdept,
            case when shiftcd=1 then 'A'  
             when shiftcd=2 then 'B' 
            when shiftcd=3 then 'C' else 'G' end shift from (
                SELECT
                from_date,
                to_date,
                employeeid,
                eb_no,
                wname,
                dept_code,
                department,
                desig,
                time_piece,
                max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
                max( case when COMPONENT_ID = 238 then amount else 0 end ) AS BASIC_RATE,
                max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
                max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
                max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
                max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
                max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
                max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
                max( case when COMPONENT_ID = 251 then amount else 0 end ) AS PROD_BASIC,
                max( case when COMPONENT_ID = 189 then amount else 0 end ) AS TIME_RATED_BASIC,
                max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
                max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
                max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
                max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
                max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
                max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
                max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
                max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
                max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
                max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
                max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
                max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
                max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
                max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
                max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
                max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
                max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
                max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
                max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100,0) else 0 end ) AS epf_833,
                max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100,0) else 0 end ) AS epf_167,
                max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
                max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
                max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
                max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
                max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
                max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
                max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
                max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
                max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
                max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
                max( case when COMPONENT_ID = 21 then amount else 0 end ) AS NET_PAY
            FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    d.desig,
                    d.time_piece,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                left join designation d on theod.designation_id=d.id  
                left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                WHERE
                tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                and k.PAYSCHEME_ID=".$att_payschm."  
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                    and tpp.STATUS <>4
                    AND theod.is_active = 1
                    ) g
            GROUP BY		
            tpep.EMPLOYEEID,
                eb_no,
                wname,
                desig,
                time_piece,
                department,
                dept_code,tbl_hrms_ed_bank_detail_id
                ) k left join (    
          select ebno,max(dept_code) mxdept from (      
         select distinct(eb_no) ebno,dept_code from daily_attendance da 
         left join department_master dm on dm.dept_id =da.worked_department_id 
         where attendance_date between '".$periodfromdate."' and '".$periodtodate."' and da.company_id =".$comp."
         ) m group by ebno ) m on k.eb_no=m.ebno
                where TOTAL_EARN>0 and epf>0    
                ORDER BY dept_code,
                eb_no  
     ) G ";
     
  //   echo $sql;

    }


        if ($holget==6 ) {

    $sql="
      select 
     dept_code,
     department,sum(Net_Payble) Net_Payble,sum(OVERTIME_PAY) OVERTIME_PAY,sum(Net_Payble+OVERTIME_PAY) total_amt,
     sum(WORKING_HOURS+NIGHT_SHIFT_HR+HOLIDAY_HR+OT_HOURS) whrs from
    (
        SELECT
            eb_no,
            wname,
            dept_code,
            department,
            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
            max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NIGHT_SHIFT_HR,
            max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
            max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
            max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble,
        	max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY
     FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc department,
                dm.dept_code
            FROM
                tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
            left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
            left join	department_master dm on theod.department_id = dm.dept_id
            WHERE
            k.COMPONENT_ID in (21, 237,178,179,102,135)
	                and amount>0
                and tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'
                and k.PAYSCHEME_ID =".$att_payschm."
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                and tpp.STATUS <>4
                AND theod.is_active = 1
                ) g
        GROUP BY
            eb_no,
            wname,
            department,
            dept_code
            ) k group by 
            dept_code,
            department 
        ORDER BY
        dept_code"; 
 //   echo $sql;   

            }



    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getpayregisterbankdata($periodfromdate,$periodtodate,$att_payschm,$holget) {
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');



    if ($holget==14) {
        $sql="select k.*,pf_uan_no,esi_no,pf_no,
        bank_acc_no,
        ifsc_code,
        case when shiftcd=1 then 'A'  
         when shiftcd=2 then 'B' 
        when shiftcd=3 then 'C' else 'G' end shift,
        dm.dept_desc deparment,dpm.dept_code dept_code
        from (
            SELECT
            from_date,
            to_date,
            employeeid,
            eb_no,
            wname,
            dept_code dept_codes,
            department department,
            desig,
            time_piece,
            max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
            max( case when COMPONENT_ID = 238 then round(amount,2) else 0 end ) AS BASIC_RATE,
            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
            max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
            max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
            max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
            max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
            max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
            max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
            max( case when COMPONENT_ID = 251 then round(amount,2) else 0 end ) AS PROD_BASIC,
            max( case when COMPONENT_ID = 189 then round(amount,2) else 0 end ) AS TIME_RATED_BASIC,
            max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
            max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
            max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
            max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
            max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
            max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
            max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
            max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
            max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
            max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
            max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
            max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
            max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
            max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
            max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
            max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
            max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
            max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
            max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100,0) else 0 end ) AS epf_833,
            max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100,0) else 0 end ) AS epf_167,
            max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
            max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
            max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
            max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
            max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
            max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
            max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
            max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
            max( case when COMPONENT_ID = 21 then round(amount,2) else 0 end ) AS NET_PAY,
            max( case when COMPONENT_ID = 239 then amount else 0 end ) AS PFG100,
            max( case when COMPONENT_ID = 245 then amount else 0 end ) AS PF100, 
            max( case when COMPONENT_ID = 247 then amount else 0 end ) AS NET100,
            max( case when COMPONENT_ID = 244 then amount else 0 end ) AS TOTAL100,
            max( case when COMPONENT_ID = 243 then amount else 0 end ) AS GROSS_PAY100,
            max( case when COMPONENT_ID = 242 then amount else 0 end ) AS HRA_100,
            max( case when COMPONENT_ID = 248 then amount else 0 end ) AS attincn,
            max( case when COMPONENT_ID = 237 then amount else 0 end ) AS otpay,
            max( case when COMPONENT_ID = 284 then amount else 0 end ) AS otadv,
            max( case when COMPONENT_ID = 286 then amount else 0 end ) AS otnet
    FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc department,
                dm.dept_code,
                d.desig,
                d.time_piece,
                thebd.tbl_hrms_ed_bank_detail_id
            FROM
                tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
            left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
            left join	department_master dm on theod.department_id = dm.dept_id
            left join designation d on theod.designation_id=d.id  
            left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
            WHERE
            tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'  
            and k.PAYSCHEME_ID in (125,151,161,156)
            and k.BUSINESSUNIT_ID = ".$comp."
            and k.status = 1
                and tpp.STATUS <>4
                AND theod.is_active = 1
                ) g
        GROUP BY		
        tpep.EMPLOYEEID,
            eb_no,
            wname,
            desig,
            time_piece,
            department,
            dept_code,tbl_hrms_ed_bank_detail_id
            ) k 
            left join (select * from tbl_hrms_ed_pf  where is_active=1 ) thep on thep.eb_id=k.EMPLOYEEID
            left join (select * from tbl_hrms_ed_esi  where is_active=1 ) thee on thee.eb_id=k.EMPLOYEEID
			left join (  select da.company_id companyid,da.eb_id,max(dept_code) dept_code from daily_attendance da 
            left join department_master dm on da.worked_department_id =dm.dept_id 
            where attendance_date between '".$periodfromdate."' and '".$periodtodate."'
            and da.company_id =".$comp." and is_active=1 and attendance_type='R'
            group by eb_id,da.company_id
         	) dpm on dpm.eb_id=k.EMPLOYEEID     
         		left join department_master dm on dpm.dept_code=dm.dept_code and dm.company_id=dpm.companyid  
                 left join (select * from tbl_hrms_ed_bank_details where is_active=1) thebd on thebd.eb_id=k.EMPLOYEEID
                 where
                 NET_PAY+otnet+attincn>0
                order by  
            eb_no  
     ";

            }
            $query = $this->db->query($sql);
            //    $query = $this->db->get($sql);
         //   echo $this->db->last_query();            
        
              
            $data=$query->result();
            if ($query->num_rows() > 0) {
           //     var_dump($data);
                return $data;
            } else {
                return array(); // Return an empty array if no results are found
            }
        
        }

public function attendancechecklist($periodfromdate,$periodtodate,$att_payschm,$holget) {


    
}

public function getpayregisterdata($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
 
    if ($holget==2 || $holget==7 || $holget==1 || $holget==8) {
        $sql="select
        k.*,
        pf_uan_no,
        esi_no,
        pf_no,
        case
            when shiftcd = 1 then 'A'
            when shiftcd = 2 then 'B'
            when shiftcd = 3 then 'C'
            else 'G'
        end shift,
        dm.dept_desc department,
        SUBSTRING(RIGHT(mxebdpoc, 8), 1, 2) dept_code,
        right(mxebdpoc,
        6) desgid,
        omn.OCCU_SHR_NAME desig,omn.OCCU_CODE shrcode,occu_desc,
        case when cfshk=1 then 0-C_FF else C_FF end C_F
    from
        (
        SELECT
            DATE_FORMAT(from_date, '%d-%m-%Y') from_date,
            DATE_FORMAT(to_date, '%d-%m-%Y') to_date,
            employeeid,
            eb_no,
            wname,
            time_piece,
  max( case when (amount<0 and COMPONENT_ID = 236) then 1 else 0 end ) AS cfshk,
            max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
            max( case when COMPONENT_ID = 238 then round(amount, 2) else 0 end ) AS BASIC_RATE,
            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
            max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
            max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
            max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
            max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
            max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
            max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
            max( case when COMPONENT_ID = 251 then round(amount, 2) else 0 end ) AS PROD_BASIC,
            max( case when COMPONENT_ID = 189 then round(amount, 2) else 0 end ) AS TIME_RATED_BASIC,
            max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
            max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
            max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
            max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
            max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
            max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
            max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
            max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
            max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
            max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
            max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
            max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
            max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
            max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
            max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
            max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
            max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
            max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
            max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100, 0) else 0 end ) AS epf_833,
            max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100, 0) else 0 end ) AS epf_167,
            max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
            max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
            max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
            max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
            max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
            max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
            max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
            max( case when COMPONENT_ID = 236 then abs(amount) else 0 end ) AS C_FF,
            max( case when COMPONENT_ID = 21 then round(amount, 2) else 0 end ) AS NET_PAY,
            max( case when COMPONENT_ID = 239 then amount else 0 end ) AS PFG100,
            max( case when COMPONENT_ID = 245 then amount else 0 end ) AS PF100,
            max( case when COMPONENT_ID = 247 then amount else 0 end ) AS NET100,
            max( case when COMPONENT_ID = 244 then amount else 0 end ) AS TOTAL100,
            max( case when COMPONENT_ID = 243 then amount else 0 end ) AS GROSS_PAY100,
            max( case when COMPONENT_ID = 242 then amount else 0 end ) AS HRA_100,            
            max( case when COMPONENT_ID = 248 then amount else 0 end ) AS attincn,
            max( case when COMPONENT_ID = 237 then amount else 0 end ) AS otpay,
            max( case when COMPONENT_ID = 284 then amount else 0 end ) AS otadv,
            max( case when COMPONENT_ID = 286 then amount else 0 end ) AS otnet,
            max( case when COMPONENT_ID = 166 then amount else 0 end ) AS exadvance, 
            max( case when COMPONENT_ID = 268 then amount else 0 end ) AS arrear_plus,
            max( case when COMPONENT_ID = 269 then amount else 0 end ) AS arrear_minus
        FROM
            (
            SELECT
                k.PAYPERIOD_ID,
                tpp.FROM_DATE,
                tpp.TO_DATE,
                tpep.EMPLOYEEID,
                theod.emp_code eb_no,
                CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                COMPONENT_ID,
                tpc.NAME,
                AMOUNT,
                dept_desc department,
                dm.dept_code,
                d.desig,
                d.time_piece,
                thebd.tbl_hrms_ed_bank_detail_id
            FROM
                tbl_pay_employee_payroll k
            JOIN vowsls.tbl_pay_employee_payscheme tpep ON
                tpep.EMPLOYEEID = k.EMPLOYEEID
            LEFT JOIN (
                select
                    *
                from
                    vowsls.tbl_hrms_ed_official_details
                where
                    is_active = 1) theod ON
                k.EMPLOYEEID = theod.eb_id
            LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON
                k.EMPLOYEEID = thepd.eb_id
            left join tbl_pay_period tpp on
                tpp.ID = k.PAYPERIOD_ID
            left join tbl_pay_components tpc on
                tpc.ID = k.COMPONENT_ID
            left join department_master dm on
                theod.department_id = dm.dept_id
            left join designation d on
                theod.designation_id = d.id
            left join (
                select
                    *
                from
                    tbl_hrms_ed_bank_details
                where
                    is_active = 1 )thebd on
                thebd.eb_id = k.EMPLOYEEID
            WHERE
                tpp.FROM_DATE = '".$periodfromdate."'
                and tpp.TO_DATE = '".$periodtodate."'
                and k.PAYSCHEME_ID = ".$att_payschm."
                and k.BUSINESSUNIT_ID = ".$comp."
                and k.status = 1
                and tpp.STATUS <> 4
                AND theod.is_active = 1 ) g
        GROUP BY
            tpep.EMPLOYEEID,
            eb_no,
            wname,
            desig,
            time_piece,
            department,
            dept_code,
            tbl_hrms_ed_bank_detail_id ) k
    left join (
        select
            *
        from
            tbl_hrms_ed_pf
        where
            is_active = 1 ) thep on
        thep.eb_id = k.EMPLOYEEID
    left join (
        select
            *
        from
            tbl_hrms_ed_esi
        where
            is_active = 1 ) thee on
        thee.eb_id = k.EMPLOYEEID
    left join (
        select
            eb_id,
            da.company_id companyid,
            max(concat(eb_no, dm.dept_code, LPAD(da.worked_designation_id , 6, '0'))) mxebdpoc
        from
            daily_attendance da
        left join department_master dm on
            da.worked_department_id = dm.dept_id
        left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on
            da.worked_designation_id = omn.desig_id
        where
            attendance_date between '".$periodfromdate."' and '".$periodtodate."'
            and da.company_id = ".$comp."
            and is_active = 1
            and attendance_type = 'R'
        group by
            eb_id,
            da.company_id ) dpm on
        dpm.eb_id = k.EMPLOYEEID
    left join department_master dm on
        SUBSTRING(RIGHT(mxebdpoc, 8), 1, 2) = dm.dept_code
        and dm.company_id = dpm.companyid
    left join EMPMILL12.OCCUPATION_MASTER_NORMS omn  on
        LPAD(omn.desig_id, 6, '0')= right(mxebdpoc,
        6)
    where
        TOTAL_EARN>0 " ;  
        if ($holget==2)   $sql=$sql."and epf>0";
        if ($holget==7)   $sql=$sql."and epf=0";
        $sql=$sql." order by
        dept_code,
        eb_no
         ";
  //echo 'inner'.$sql;
    }
     ////////// end //////////////////////////////////

    if ( $holget==3) {
        $sql=" select k.*,
        esi_no,dm.dept_desc deparment,dpm.dept_code dept_code,
        round(((working_hrs+ns_hrs+hol_hrs)*rate_per_day/8),0)+(arrear_plus-arrear_minus+miss_ot_earning) -exadvance-esi net100
        from (
                           SELECT
                           from_date,
                           to_date,
                           employeeid,
                           eb_no,
                           wname,
                           dept_code deptcode,
                           department departments,
                           desig,
                           time_piece,
                            max( case when COMPONENT_ID = 7 then amount else 0 end ) AS basic,
                            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS esi,
                            max( case when COMPONENT_ID = 21 then amount else 0 end ) AS netpay,
                            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS gross_deduction,
                            max( case when COMPONENT_ID = 66 then amount else 0 end ) AS gross1,
                            max( case when COMPONENT_ID = 70 then amount else 0 end ) AS rate_per_day, 
                            max( case when COMPONENT_ID = 102 then amount else 0 end ) AS hol_hrs, 
                            max( case when COMPONENT_ID = 109 then amount else 0 end ) AS hol_wgs, 
                            max( case when COMPONENT_ID = 135 then amount else 0 end ) AS c_ot_hrs, 
                            max( case when COMPONENT_ID = 166 then amount else 0 end ) AS exadvance, 
                            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS working_hrs, 
                            max( case when COMPONENT_ID = 179 then amount else 0 end ) AS ns_hrs, 
                            max( case when COMPONENT_ID = 196 then amount else 0 end ) AS wrk_hr_eff,
                            max( case when COMPONENT_ID = 214 then amount else 0 end ) AS fix_basic, 
                            max( case when COMPONENT_ID = 224 then amount else 0 end ) AS total_earn,
                            max( case when COMPONENT_ID = 237 then amount else 0 end ) AS overtime_pay,
                            max( case when COMPONENT_ID = 268 then amount else 0 end ) AS arrear_plus,
                            max( case when COMPONENT_ID = 269 then amount else 0 end ) AS arrear_minus,
                            max( case when COMPONENT_ID = 284 then amount else 0 end ) AS ot_advance, 
                            max( case when COMPONENT_ID = 285 then amount else 0 end ) AS miss_ot_earning,
                            max( case when COMPONENT_ID = 286 then amount else 0 end ) AS ot_net_amount 
                       FROM
                           (
                           SELECT
                               k.PAYPERIOD_ID,
                               tpp.FROM_DATE,
                               tpp.TO_DATE,
                               tpep.EMPLOYEEID,
                               theod.emp_code eb_no,
                               CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                               COMPONENT_ID,
                               tpc.NAME,
                               AMOUNT,
                               dept_desc department,
                               dm.dept_code,
                               d.desig,
                               d.time_piece,
                               thebd.tbl_hrms_ed_bank_detail_id
                           FROM
                               tbl_pay_employee_payroll k
                               JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                           LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                           LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                           left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                           left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                           left join	department_master dm on theod.department_id = dm.dept_id
                           left join designation d on theod.designation_id=d.id
                           left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                           WHERE
                            tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'  
                           and k.PAYSCHEME_ID=".$att_payschm." 
                           and k.BUSINESSUNIT_ID = ".$comp."
                           and k.status = 1
                               and tpp.STATUS <>4
                               AND theod.is_active = 1
                                
                               ) g
                       GROUP BY		
                       tpep.EMPLOYEEID,
                           eb_no,
                           wname,
                           desig,
                           time_piece,
                           department,
                           dept_code,tbl_hrms_ed_bank_detail_id
                           ) k 
                           left join (select * from tbl_hrms_ed_esi  where is_active=1 ) thee on thee.eb_id=k.EMPLOYEEID
                           left join (  select da.company_id companyid,da.eb_no,max(dept_code) dept_code from daily_attendance da 
                           left join department_master dm on da.worked_department_id =dm.dept_id 
                           where attendance_date between '".$periodfromdate."' and '".$periodtodate."'
                           and da.company_id = ".$comp." and is_active=1 and attendance_type='R'
                           group by eb_no,da.company_id
                            ) dpm on dpm.eb_no=k.eb_no     
                                left join department_master dm on dpm.dept_code=dm.dept_code and dm.company_id=dpm.companyid 
                                                 where total_earn>0
                           order by  
                           dept_code,
                           eb_no
         ";
         
//        echo $sql;   
    
        }
    
    
    
    if ($holget==4) {
        $sql="select * from (
            SELECT
                eb_no,
                wname,
                dept_code,
                department,
                tbl_hrms_ed_bank_detail_id,
                max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                max( case when COMPONENT_ID = 70 then amount else 0 end ) AS `RATE_PER_DAY`,
                max( case when COMPONENT_ID = 7 then amount else 0 end ) AS `BASIC`,
                max( case when COMPONENT_ID = 109 then amount else 0 end ) AS `Festival_Wage`,
                max( case when COMPONENT_ID = 237 then amount else 0 end ) AS `OVERTIME_PAY`,
                max( case when COMPONENT_ID = 166 then amount else 0 end ) AS `ADVANCE`,
                max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble
            FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                WHERE
                tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                    and k.PAYSCHEME_ID=".$att_payschm." 
                    and k.BUSINESSUNIT_ID = ".$comp."
                    and k.status = 1
                    and tpp.STATUS <>4
                    AND theod.is_active = 1
                    ) g
            GROUP BY
                eb_no,
                wname,
                department,
                dept_code,tbl_hrms_ed_bank_detail_id
                ) k where tbl_hrms_ed_bank_detail_id is not null and  WORKING_HOURS+HOLIDAY_HR+OT_HOURS>0
            ORDER BY
            dept_code,
                eb_no 
         ";
         
//    echo $sql;   
    
        }
         
        if ($holget==5) {
            $sql="
                select * from (
                SELECT
                    eb_no,
                    wname,
                    dept_code,
                    department,
                    tbl_hrms_ed_bank_detail_id,
                    max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                    max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                    max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE_PER_DAY,
                    max( case when COMPONENT_ID = 7 then amount else 0 end ) AS BASIC,
                    max( case when COMPONENT_ID = 109 then amount else 0 end ) AS Festival_Wage,
                    max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
                    max( case when COMPONENT_ID = 166 then amount else 0 end ) AS ADVANCE,
                    max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                    max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                    max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble
                FROM
                    (
                    SELECT
                        k.PAYPERIOD_ID,
                        tpp.FROM_DATE,
                        tpp.TO_DATE,
                        tpep.EMPLOYEEID,
                        theod.emp_code eb_no,
                        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                        COMPONENT_ID,
                        tpc.NAME,
                        AMOUNT,
                        dept_desc department,
                        dm.dept_code,
                        thebd.tbl_hrms_ed_bank_detail_id
                    FROM
                        tbl_pay_employee_payroll k
                        JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                    LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                    left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                    left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                    left join	department_master dm on theod.department_id = dm.dept_id
                    left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                    WHERE
                    tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                        and k.PAYSCHEME_ID=".$att_payschm." 
                        and k.BUSINESSUNIT_ID = ".$comp."
                        and k.status = 1
                        and tpp.STATUS <>4
                        AND theod.is_active = 1
                        ) g
                GROUP BY
                    eb_no,
                    wname,
                    department,
                    dept_code,tbl_hrms_ed_bank_detail_id
                    ) k where tbl_hrms_ed_bank_detail_id is null and (WORKING_HOURS+HOLIDAY_HR+OT_HOURS+ADVANCE+Net_Payble>0)
                    order by 
                    dept_code,
                    eb_no 
                    
                    
                 ";
             
      //  echo $sql;   
        
            }
         
            if ($holget==6) {
                $sql="select k.*,pf_uan_no,esi_no from (
                    SELECT
                    employeeid,
                    eb_no,
                    wname,
                    dept_code,
                    department,
                    tbl_hrms_ed_bank_detail_id,
                    max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                    max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NIGHT_SHIFT_HR,
                    max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                    max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE_PER_DAY,
                    max( case when COMPONENT_ID = 7 then amount else 0 end ) AS BASIC,
                    max( case when COMPONENT_ID = 270 then amount else 0 end ) AS TIFFIN_AMOUNT,
                    max( case when COMPONENT_ID = 133 then amount else 0 end ) AS WASHING_ALLOWANCE,
                    max( case when COMPONENT_ID = 9 then amount else 0 end ) AS OTHER_ALLOWANCE,
                    max( case when COMPONENT_ID = 72 then amount else 0 end ) AS CONV_ALLOWANCE,
                    max( case when COMPONENT_ID = 109 then amount else 0 end ) AS Festival_Wage,
                    max( case when COMPONENT_ID = 66 then amount else 0 end ) AS GROSS2,
                    max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
                    max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESI,
                    max( case when COMPONENT_ID = 166 then amount else 0 end ) AS ADVANCE,
                    max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                    max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                    max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
                    max( case when COMPONENT_ID = 25 then amount else 0 end ) AS GROSS_DED,
                    max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
                    max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble,
                    max( case when COMPONENT_ID = 22 then amount else 0 end ) AS EMPL_EPF,
                    max( case when COMPONENT_ID = 23 then amount else 0 end ) AS EMPL_ESI,
                    0 P_TAX
                FROM
                    (
                    SELECT
                        k.PAYPERIOD_ID,
                        tpp.FROM_DATE,
                        tpp.TO_DATE,
                        tpep.EMPLOYEEID,
                        theod.emp_code eb_no,
                        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                        COMPONENT_ID,
                        tpc.NAME,
                        AMOUNT,
                        dept_desc department,
                        dm.dept_code,
                        thebd.tbl_hrms_ed_bank_detail_id
                    FROM
                        tbl_pay_employee_payroll k
                        JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                    LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                    left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                    left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                    left join	department_master dm on theod.department_id = dm.dept_id
                    left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                    WHERE
                    tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'  
                    and k.PAYSCHEME_ID=".$att_payschm." 
                    and k.BUSINESSUNIT_ID = ".$comp."
                    and k.status = 1
                        and tpp.STATUS <>4
                        AND theod.is_active = 1
                        ) g
                GROUP BY		
                tpep.EMPLOYEEID,
                    eb_no,
                    wname,
                    department,
                    dept_code,tbl_hrms_ed_bank_detail_id
                    ) k 
                    left join (select * from tbl_hrms_ed_pf  where is_active=1 ) thep on thep.eb_id=k.EMPLOYEEID
                    left join (select * from tbl_hrms_ed_esi  where is_active=1 ) thee on thee.eb_id=k.EMPLOYEEID
                    where total_earn>0
                    order by  
                    dept_code,
                    eb_no ";
  // echo $sql;
                }           

       ////////////// Contractor Wages Quary ///////////////////////////////////         
                if ($holget==16) {
                    $sql="SELECT
	k.EB_NO,
	k.wname,
	thep.pf_no,
	thep.pf_uan_no,
	thee.esi_no,
	k.*
FROM
	(
	SELECT
		tpep.EMPLOYEEID,
		theod.emp_code AS EB_NO,
		CONCAT(TRIM(thepd.first_name), ' ', IFNULL(TRIM(thepd.middle_name), ''), ' ', IFNULL(TRIM(thepd.last_name), '')) AS wname,
		dm.dept_code,
		dm.dept_desc AS department,
		MAX(CASE WHEN COMPONENT_ID = 178 THEN amount ELSE 0 END) AS WORKING_HOURS,
		MAX(CASE WHEN COMPONENT_ID = 179 THEN amount ELSE 0 END) AS NIGHT_SHIFT_HR,
		MAX(CASE WHEN COMPONENT_ID = 102 THEN amount ELSE 0 END) AS HOLIDAY_HR,
		MAX(CASE WHEN COMPONENT_ID = 135 THEN amount ELSE 0 END) AS OT_HOURS,
		MAX( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
		MAX(CASE WHEN COMPONENT_ID = 70 THEN amount ELSE 0 END) AS RATE_PER_DAY,
		MAX(CASE WHEN COMPONENT_ID = 7 THEN amount ELSE 0 END) AS BASIC,
		MAX(CASE WHEN COMPONENT_ID = 270 THEN amount ELSE 0 END) AS TIFFIN_AMOUNT,
		MAX(CASE WHEN COMPONENT_ID = 133 THEN amount ELSE 0 END) AS WASHING_ALLOWANCE,
		MAX(CASE WHEN COMPONENT_ID = 9 THEN amount ELSE 0 END) AS OTHER_ALLOWANCE,
		MAX(CASE WHEN COMPONENT_ID = 72 THEN amount ELSE 0 END) AS CONV_ALLOWANCE,
		MAX(CASE WHEN COMPONENT_ID = 109 THEN amount ELSE 0 END) AS Festival_Wage,
		MAX(CASE WHEN COMPONENT_ID = 66 THEN amount ELSE 0 END) AS GROSS2,
		MAX(CASE WHEN COMPONENT_ID = 18 THEN amount ELSE 0 END) AS EPF,
		MAX(CASE WHEN COMPONENT_ID = 19 THEN amount ELSE 0 END) AS ESI,
		MAX(CASE WHEN COMPONENT_ID = 166 THEN amount ELSE 0 END) AS ADVANCE,
		MAX(CASE WHEN COMPONENT_ID = 268 THEN amount ELSE 0 END) AS ARR_PLUS,
		MAX(CASE WHEN COMPONENT_ID = 269 THEN amount ELSE 0 END) AS ARR_MINUS,
		MAX(CASE WHEN COMPONENT_ID = 224 THEN amount ELSE 0 END) AS TOTAL_EARN,
		MAX(CASE WHEN COMPONENT_ID = 25 THEN amount ELSE 0 END) AS GROSS_DED,
		MAX(CASE WHEN COMPONENT_ID = 223 THEN amount ELSE 0 END) AS B_F,
		MAX(CASE WHEN COMPONENT_ID = 21 THEN amount ELSE 0 END) AS Net_Payble,
		MAX(CASE WHEN COMPONENT_ID = 22 THEN amount ELSE 0 END) AS EMPL_EPF,
		MAX(CASE WHEN COMPONENT_ID = 23 THEN amount ELSE 0 END) AS EMPL_ESI
	FROM
		(
		SELECT
			EMPLOYEEID,
			COMPONENT_ID,
			SUM(amount) AS amount
		FROM
			tbl_pay_employee_payroll k
		LEFT JOIN tbl_pay_period tpp ON
			k.PAYPERIOD_ID = tpp.ID
		WHERE
			tpp.FROM_DATE BETWEEN '2024-04-01' AND '2024-05-31'
			AND tpp.STATUS <> 4
			AND k.STATUS <> 4
			AND k.PAYSCHEME_ID = 159
			AND k.BUSINESSUNIT_ID = 2
		GROUP BY
			EMPLOYEEID,
			COMPONENT_ID ) AS subquery
	JOIN vowsls.tbl_pay_employee_payscheme tpep ON
		tpep.EMPLOYEEID = subquery.EMPLOYEEID
	JOIN vowsls.tbl_hrms_ed_official_details theod ON
		subquery.EMPLOYEEID = theod.eb_id
	JOIN vowsls.tbl_hrms_ed_personal_details thepd ON
		subquery.EMPLOYEEID = thepd.eb_id
	JOIN tbl_pay_components tpc ON
		tpc.ID = subquery.COMPONENT_ID
	JOIN department_master dm ON
		theod.department_id = dm.dept_id
	GROUP BY
		tpep.EMPLOYEEID,
		EB_NO,
		wname,
		dm.dept_code,
		department ) k
LEFT JOIN tbl_hrms_ed_pf thep ON
	thep.eb_id = k.EMPLOYEEID
	AND thep.is_active = 1
LEFT JOIN tbl_hrms_ed_esi thee ON
	thee.eb_id = k.EMPLOYEEID
	AND thee.is_active = 1
WHERE
	k.TOTAL_EARN > 0
ORDER BY
	k.dept_code,
	k.EB_NO";
                    }  
  ////////////// End This Contractor Wages Quary ///////////////////////////////////     

 
                
   //  echo 'this query-'.$holget.'--'. $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}
public function getworkerdetails($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept,$occucode) {
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
   //28-12-2023
    $sdate=substr($periodfromdate,6,4).'-'.substr($periodfromdate,3,2).'-'.substr($periodfromdate,0,2);        
   //echo $sdate;
    $sql="select * from (
    select eb_no,CONCAT(first_name, ' ', IFNULL(middle_name, ''),' ',IFNULL(last_name, '')) wname,
    SUBSTR(spell,1,1) shift,HOCCU_CODE, desig,da.attendance_type,
	sum(working_hours-ifnull(idle_hours,0)) whrs  
	from daily_attendance da 
	join tbl_hrms_ed_personal_details thepd on da.eb_id =thepd.eb_id
	join ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID=da.worked_designation_id
	left join EMPMILL12.OCCUPATION_MASTER om on oolt.ORA_TABLE_ID=om.OCCU_ID
	left join designation d on d.id =da.worked_designation_id 
	where da.is_active=1 and da.attendance_date='".$sdate."' and da.company_id=".$comp."
	and om.HOCCU_CODE='".$occucode."'
	group by eb_no,CONCAT(first_name, ' ', IFNULL(middle_name, ''),' ',IFNULL(last_name, '')),SUBSTR(spell,1,1),desig,da.attendance_type
	) g 
    order by shift,eb_no";   
//   echo $sql;
    $query = $this->db->query($sql);
     
   $data=$query->result();
   if ($query->num_rows() > 0) {
       return $data;
   } else {
       return array(); // Return an empty array if no results are found
   }

}
public function getworkerattdetails($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept,$ebid) {
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
   //28-12-2023
    $sdate=substr($periodfromdate,6,4).'-'.substr($periodfromdate,3,2).'-'.substr($periodfromdate,0,2);        
    $edate=substr($periodtodate,6,4).'-'.substr($periodtodate,3,2).'-'.substr($periodtodate,0,2);        
   //echo $sdate;
    $sql="select * from (
    select eb_no,CONCAT(first_name, ' ', IFNULL(middle_name, ''),' ',IFNULL(last_name, '')) wname,
    SUBSTR(spell,1,1) shift,date_format(attendance_date,'%d-%m-%Y') attendance_date, desig,da.attendance_type,
	sum(working_hours-ifnull(idle_hours,0)) whrs ,attendance_date attdate 
	from daily_attendance da 
	join tbl_hrms_ed_personal_details thepd on da.eb_id =thepd.eb_id
	join ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID=da.worked_designation_id
	left join EMPMILL12.OCCUPATION_MASTER om on oolt.ORA_TABLE_ID=om.OCCU_ID
	left join designation d on d.id =da.worked_designation_id 
	where da.is_active=1 and da.attendance_date between '".$sdate."' and '".$edate."' and da.company_id=".$comp."
	and da.eb_id='".$ebid."'
	group by eb_no,CONCAT(first_name, ' ', IFNULL(middle_name, ''),' ',IFNULL(last_name, '')),
    SUBSTR(spell,1,1),attendance_date,desig,da.attendance_type
	) g 
    order by attdate,shift,eb_no";  
 //  echo $sql;
    $query = $this->db->query($sql);
     
   $data=$query->result();
   if ($query->num_rows() > 0) {
       return $data;
   } else {
       return array(); // Return an empty array if no results are found
   }

}


public function getebmcdata($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==1) {
        $sql="select date_format(dea.attendace_date,'%d-%m-%Y') attendace_date,dea.spell,dea.eb_no,
        concat(thepd.first_name,' ',ifnull(thepd.middle_name,''),' ',ifnull(thepd.last_name,'')) wname,
        d.desig, mm.mech_code ,mm.mechine_name ,da.working_hours -da.idle_hours whrs,ddea.cnt
        from daily_ebmc_attendance dea 
        join daily_attendance da on dea.daily_atten_id =da.daily_atten_id 
        join tbl_hrms_ed_personal_details thepd on dea.eb_id =thepd.eb_id
        join mechine_master mm on dea.mc_id =mm.mechine_id
        join designation d on d.id=da.worked_designation_id 
        join (select dea2.daily_atten_id,mc_id,count(*) cnt from daily_ebmc_attendance dea2 where is_active=1 
        and dea2.attendace_date ='".$periodfromdate."' and dea2.spell='".$att_spell."'
        group by dea2.daily_atten_id,mc_id  ) ddea 
        on dea.daily_atten_id=ddea.daily_atten_id and ddea.mc_id=dea.mc_id
        where da.is_active =1 and dea.is_active =1
        and dea.attendace_date ='".$periodfromdate."' and dea.spell='".$att_spell."'
        and da.company_id =2 and worked_department_id =".$att_dept." 
        order by desig,mm.mech_code  

     ";

     
    }
    if ($holget==2) {
        $dpts='';
        if ($att_dept==1 || $att_dept==2 || $att_dept==3) {
            $dpts='1,2,3';          
        }
        if ($att_dept==4) {
            $dpts='4';          
        }
        if ($att_dept==5 || $att_dept==6 ) {
            $dpts='5,6';          
        }
        if ($att_dept==7 || $att_dept==8 ) {
            $dpts='7,8';          
        }
        if ($att_dept==9 || $att_dept==10 ) {
            $dpts='10,9';          
        }
        if ($att_dept==11 || $att_dept==12 || $att_dept==13 || $att_dept==14 || $att_dept==15)  {
            $dpts='11,12,13,14,15';          
        }

        $sql="SELECT ATTANDANCE_DATE,a.dept_code,dept_desc,om.HOCCU_CODE,a.OCCU_DESC,AHND,BHND,CHND,
         (AHND+BHND+CHND) STOTAL,(OAHND+CAHND) OAHND,
        (OBHND+CBHND) OBHND, (OCHND+CCHND) OCHND, (OAHND+OBHND+OCHND+CAHND+CBHND+CCHND) OTOTAL 
        FROM EMPMILL12.dailyhandcomp a
        left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id=a.OCCU_ID 
        WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id='".$comp."' 
        and a.dept_id in ($dpts) ORDER BY dept_code,HOCCU_CODE ";
        

        $sql="select g.*,date_format(ATTANDANCE_DATE,'%d-%m-%Y')  attdate from (
            SELECT 1 rem ,ATTANDANCE_DATE,a.dept_code,dept_desc,om.HOCCU_CODE,a.OCCU_DESC,AHND,BHND,CHND,
                     (AHND+BHND+CHND) STOTAL,(OAHND+CAHND) OAHND,
                    (OBHND+CBHND) OBHND, (OCHND+CCHND) OCHND, (OAHND+OBHND+OCHND+CAHND+CBHND+CCHND) OTOTAL 
                    FROM EMPMILL12.dailyhandcomp a
                    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id=a.OCCU_ID 
                    WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id='".$comp."' 
                    and a.dept_id in ($dpts)
            union ALL 
                SELECT 2 rem ,ATTANDANCE_DATE,dept_code,'Total' dept_desc,'' HOCCU_CODE,'' OCCU_DESC,sum(AHND) AHND,SUM(BHND) BHND,SUM(CHND) CHND,
                    SUM(AHND+BHND+CHND) STOTAL,SUM(OAHND+CAHND) OAHND,
                    SUM(OBHND+CBHND) OBHND, SUM(OCHND+CCHND) OCHND, SUM(OAHND+OBHND+OCHND+CAHND+CBHND+CCHND) OTOTAL 
                    FROM EMPMILL12.dailyhandcomp a
                    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id=a.OCCU_ID 
                    WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id='".$comp."' 
                    and a.dept_id in ($dpts)
                    group by ATTANDANCE_DATE,dept_code
            ) g order by dept_code,rem,HOCCU_CODE"; 
            

        
     
    
    }
    if ($holget==3) {
        $att_spell=substr($att_spell,0,1);
        $sql="select * from (
        select eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p,
        case when eb_no>='10000' and eb_no<='18000'  then  1 
         when eb_no>='02000' and eb_no<='03000'  then  2 
         when eb_no>='18001' and eb_no<='21000'  then  3 
         when eb_no>='50001' and eb_no<='55000'  then  3 
         when eb_no>='80000' and eb_no<='85000'  then  4 
        else 0 end ebsrl,          
        max( case when regular_ot = 'R' then whrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then whrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
        select eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p,sum(whrs) whrs,sum(nwhrs) nwhrs
        from ( 
        select eb_no,WRK_NAME,attandance_date,shift,regular_ot,om.OCCU_CODE,d.occu_desc,dept_code,dept_desc,t_p,
        case when shift='C' and sum(working_hrs-idle_hrs)=7.5 
        and regular_ot = 'O' then sum(working_hrs-idle_hrs)+0.5 
        else sum(working_hrs-idle_hrs) end whrs,
        case when shift='C' and sum(working_hrs-idle_hrs)=7.5 
        and regular_ot = 'R' then 0.5 
        else 0 end nwhrs 
        from EMPMILL12.dailyattview d
        left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =d.occu_id
        where attandance_date between '".$periodfromdate."' and '".$periodtodate."'
        and d.dept_id=".$att_dept." and d.shift='".$att_spell."' and comp_id=".$comp." and cata_id in (3,4,5,6,7,9)
        group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,attandance_date,dept_desc,t_p
        ) g group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p
        ) h group by eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p
        ) k order by  ebsrl,eb_no    
        
     ";
   // echo $sql;
    }

    if ($holget==4) {
        $att_spell=substr($att_spell,0,1);
        $sql="
    select eb_no,WRK_NAME,shift,occu_code,t_p,
    case when occu_code<>'55' then mcnos else ' ' end mcnos,
    case when eb_no>='10000' and eb_no<='18000'  then  1 
     when eb_no>='02000' and eb_no<='03000'  then  2 
     when eb_no>='18001' and eb_no<='21000'  then  3 
     when eb_no>='50001' and eb_no<='55000'  then  3 
     when eb_no>='80000' and eb_no<='85000'  then  4 
    else 0 end ebsrl,          
    max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
    max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
    sum(nwhrs) nwhrs,0 fhrs
    from (
select shift,eb_no,WRK_NAME,
regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
(
select attendance_date,spell,shift,eb_no,WRK_NAME,
regular_ot,occu_code,t_p,rwhrs,nwhrs,
    GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
from
    (
select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_no, concat(worker_name,' ',ifnull(last_name,'')) AS WRK_NAME,da.worked_department_id,worked_designation_id,
da.attendance_type regular_ot,om.occu_code,d.time_piece t_p, 
     case when da.spell='C' and (working_hours -idle_hours)=7.5 
        and da.attendance_type = 'O' then (working_hours -idle_hours)+0.5 
        else (working_hours -idle_hours) end rwhrs,
case when (da.spell='C' and attendance_type='R' and (working_hours -idle_hours)=7.5) then 0.5
else 0 end nwhrs,
case when LENGTH(mech_code)>4 then substr(mech_code,4,3) 
        else mech_code end mech_code
from daily_attendance da 
left join (select * from daily_ebmc_attendance where is_active=1 ) dea 
on da.daily_atten_id =dea.daily_atten_id 
left join mechine_master mm on mm.mechine_id =dea.mc_id 
left join worker_master wm on wm.eb_id =da.eb_id 
left join designation d on d.id =da.worked_designation_id 
left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
left join category_master cm on wm.cata_id =cm.cata_id 
where da.attendance_date between '".$periodfromdate."' and '".$periodtodate."'
and da.worked_department_id =".$att_dept." and wm.cata_id in (3,4,5,6,7,9)	
and da.is_active =1 and substr(da.spell,1,1)='".$att_spell."' and da.company_id=".$comp."
) g group by attendance_date,spell,shift,eb_no,WRK_NAME,
regular_ot,occu_code,t_p,rwhrs,nwhrs
) g group by eb_no,shift,eb_no,WRK_NAME,
regular_ot,occu_code,t_p,mcnos
) h
group by eb_no,WRK_NAME,shift,occu_code,t_p,mcnos
order by ebsrl,eb_no";
//echo $sql;

    }

    if ($holget==5) {
        $att_spell=substr($att_spell,0,1);
        $sql="select * from (
        select eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p,
        case when substr(eb_no,1,1)='C'  then  1 
         when substr(eb_no,1,1)='L'  then  2  
         when substr(eb_no,1,1)='R'  then  3 
        else 0 end ebsrl,          
        max( case when regular_ot = 'R' then whrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then whrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
        select eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p,sum(whrs) whrs,sum(nwhrs) nwhrs
        from ( 
        select eb_no,WRK_NAME,attandance_date,shift,regular_ot,om.OCCU_CODE,d.occu_desc,dept_code,dept_desc,t_p,
      case when shift='C' and sum(working_hrs-idle_hrs)=7.5 
        and regular_ot = 'O' then sum(working_hrs-idle_hrs)+0.5 
        else sum(working_hrs-idle_hrs) end whrs,
        case when shift='C' and sum(working_hrs)=7.5 and regular_ot='R' then 0.5 
        else 0 end nwhrs 
        from EMPMILL12.dailyattview d
        left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =d.occu_id
        where attandance_date between '".$periodfromdate."' and '".$periodtodate."'
        and d.dept_id=".$att_dept." and d.shift='".$att_spell."' and comp_id=".$comp." and cata_id in (8,10,11,12,13)
        group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,attandance_date,dept_desc,t_p
        ) g group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p
        ) h group by eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p
        ) k order by  ebsrl,eb_no    
        
     ";
   // echo $sql;
    }
    if ($holget==6) {
     //   2023-12-15
        $fndate=substr($periodtodate,8,2).substr($periodtodate,5,2).substr($periodtodate,2,2);
        $sql="select concat(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
            '00000000000000000000000000000','".$fndate."') prods from (
            select b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE,case when deptcode='06' then round(sum(production)/14,0) else sum(production) end prod from
            (select  tran_date,substr(spell,1,1) shift,eb_no,wnd_q_code,sum(prod) production
            from EMPMILL12.allwindingdata a where tran_date between  '".$periodfromdate."' and '".$periodtodate."'
             and company_id =".$comp."  
            group by tran_date,substr(spell,1,1),eb_no,wnd_q_code 
            ) a 
            left join EMPMILL12.winding_wages_link b on a.wnd_q_code=b.wnd_q_code
            group by b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE
            ) g where substr(eb_no,1,1) in ('1','0','5','8') and occu_code<>'55'
            order by deptcode,shift,eb_no";
               
        
     
   // echo $sql;
    }
    if ($holget==7) {
        $sql="select count(*) nwdays from EMPMILL12.tbl_non_working_days tnwd where tnwd.is_active=1 
        and tnwd.non_working_date between '".$periodfromdate."' and '".$periodtodate."'";
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() > 0) {
            foreach ($data as $row) {
                $nwdays=$row->nwdays;
            }
        } else {
            $nwdays=0;
        }
      //  echo $sql.'   nwdays-'.$nwdays;

        $sql=" select theod.eb_id,emp_code,CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS wname,
        cm.cata_desc,dm.dept_desc AS department,
        dsg.desig AS designation,ifnull(rwhrs,0) rwhrs,ifnull(owhrs,0) owhrs,ifnull(nhrs,0) nhrs,
        ifnull(round((rwhrs+nhrs)/8,2),0)  wdays,ifnull(round(owhrs/8,2),0) otdays, ifnull(leavedays,0) leavedays,
        ifnull(holidays,0) holidays,DATEDIFF('".$periodtodate."','".$periodfromdate."')+1-".$nwdays." tmworked,
        DATEDIFF('".$periodtodate."','".$periodfromdate."')+1-".$nwdays."-(ifnull(round((rwhrs+nhrs)/8,2),0)+ifnull(leavedays,0) 
        ) absentdays,date_format(mxdate,'%d-%m-%Y') mxdate
        from tbl_hrms_ed_personal_details thepd 
        left join (select * from tbl_hrms_ed_official_details where is_active=1 ) theod on theod.eb_id =thepd.eb_id 
        left join department_master dm on theod.department_id =dm.dept_id 
        left join designation dsg on theod.designation_id =dsg.id
        left join category_master cm on cm.cata_id =theod.catagory_id 
        left join (
        select eb_id, 	   
        max( case when attendance_type = 'R' then whrs else 0 end ) rwhrs,
        max( case when attendance_type <> 'R' then whrs else 0 end ) owhrs
        from (
        select eb_id,attendance_type,sum(whrs) whrs from (
        select da.eb_id,da.attendance_date,spell,da.attendance_type,
        case when spell='C' and (working_hours-idle_hours)=7.5 
        and attendance_type = 'O' then (working_hours-idle_hours)+0.5 
        else (working_hours-idle_hours) end whrs,
        case when (spell='C' and attendance_type='R' and (working_hours-idle_hours)=7.5) then 0.5 else 0 end nhrs 
        from daily_attendance da where da.company_id=".$comp." and da.is_active=1 and attendance_date between '".$periodfromdate."' and '".$periodtodate."'
        ) g group by eb_id,attendance_type    	
        ) v group by eb_id
        ) k on thepd.eb_id=k.eb_id 
        left join (
        select eb_id,sum(nhrs) nhrs from (
        select da.eb_id,da.attendance_date,spell,da.attendance_type,(working_hours-idle_hours) whrs,
        case when (spell='C' and attendance_type='R' and (working_hours-idle_hours)=7.5) then 0.5 else 0 end nhrs 
        from daily_attendance da where da.company_id=".$comp." and da.is_active=1 and attendance_date between '".$periodfromdate."' and '".$periodtodate."'
        ) g group by eb_id ) n on thepd.eb_id=n.eb_id
        left join (select eb_id,count(*) leavedays from (
select ltd.*,tnwd.non_working_date from leave_tran_details ltd
left join EMPMILL12.tbl_non_working_days tnwd on ltd.leave_date =tnwd.non_working_date
) ltd
        join leave_transactions lt  on  lt.leave_transaction_id=ltd.ltran_id 
        where ltd.leave_date between '".$periodfromdate."' and '".$periodtodate."'
        and ltd.is_active =1  and lt.status in (3) and ltd.non_working_date is null group by eb_id
) ltd on thepd.eb_id=ltd.eb_id
left join (select eb_id,max(attendance_date) mxdate from daily_attendance da where 
attendance_date between '2025-01-01' and '2025-07-31' and company_id=2 and is_active=1 group by eb_id) mda
on thepd.eb_id=mda.eb_id
        left join (select thmd.eb_id,count(*) holidays from  tbl_hrms_holiday_transactions  thmd 
        left join holiday_master hm on hm.id=thmd.holiday_id
        where is_active=1 and hm.holiday_date between  '".$periodfromdate."' and '".$periodtodate."'
        group by eb_id) themd 
        on themd.eb_id=thepd.eb_id
        where thepd.company_id=".$comp." and (theod.emp_code is not null)  and length(theod.emp_code)>0 
            and (ifnull(rwhrs,0)+ifnull(owhrs,0)+ifnull(nhrs,0)+ifnull(leavedays,0)+ifnull(holidays,0))>0
        order by department,emp_code
     ";

    // and theod.department_id=".$att_dept."
    
//     echo $sql;
     
    }

    if ($holget==8) {
        $sql="SELECT 
        da.eb_id,
        da.eb_no,
        CONCAT(thepd.first_name, ' ', COALESCE(thepd.middle_name, ''), ' ', COALESCE(thepd.last_name, '')) AS work_name,
        worked_department_id,
        worked_designation_id,
        dm.dept_desc,
        d.desig,
        SUBSTRING(da.spell, 1, 1) as shift,
        SUM(working_hours-idle_hours) AS work_hours,
        CASE
            WHEN worked_designation_id NOT IN (78, 79) AND SUM(working_hours-idle_hours) between 5 and 7.49  THEN 200
            WHEN worked_designation_id NOT IN (78, 79) AND SUM(working_hours-idle_hours) between  3 and 4.99 THEN 150
            WHEN worked_designation_id NOT IN (78, 79) AND SUM(working_hours-idle_hours) >=7.5 THEN 300
            when worked_designation_id=78 and DAYNAME('$periodfromdate')<>'Sunday' THEN round(SUM(working_hours-idle_hours)/8*450,0)
            when worked_designation_id=78 and DAYNAME('$periodfromdate')='Sunday' THEN SUM(working_hours-idle_hours)/8*300
            when worked_designation_id=79 and DAYNAME('$periodfromdate')<>'Sunday' THEN round(SUM(working_hours-idle_hours)/8*450,0)
            when worked_designation_id=79 and DAYNAME('$periodfromdate')='Sunday' THEN SUM(working_hours-idle_hours)/8*300
            ELSE 0
        END AS rate
    FROM 
        daily_attendance da
    JOIN 
        tbl_hrms_ed_personal_details thepd ON thepd.eb_id = da.eb_id  
    JOIN 
        designation d On d.id=da.worked_designation_id 
    JOIN 
        department_master dm on dm.dept_id =da.worked_department_id 
    WHERE 
        attendance_date = '$periodfromdate'       
        AND worked_department_id = $att_dept
        AND attendance_type = 'O'
        and substr(eb_no,1,1) not in ('C','T','L')
    GROUP BY 
        da.eb_id, 
        da.eb_no, 
        worked_department_id,
        worked_designation_id,
       shift
    HAVING  
        shift = '$att_spell' and work_hours>=3
     ";

    // and theod.department_id=".$att_dept."
    
     //echo $sql;
     
    }

    //echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getebmcdatal($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept) {
    $dpts='';
    if ($att_dept==1 || $att_dept==2 || $att_dept==3) {
        $dpts='1,2,3';  
    }
    if ($att_dept==4) {
        $dpts='4';          
    }
    if ($att_dept==5 || $att_dept==6 ) {
        $dpts='5,6';          
    }
    if ($att_dept==7 || $att_dept==8 ) {
        $dpts='7,8';          
    }
    if ($att_dept==9 || $att_dept==10 ) {
        $dpts='10,9';          
    }
    if ($att_dept==11 || $att_dept==12 || $att_dept==13 || $att_dept==14 || $att_dept==15)  {
        $dpts='11,12,13,14,15';          
    }

    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    $sql="SELECT cata_id,dept_code,cata_desc,ATTANDANCE_DATE,SUM(AHND+OAHND) AS ASHIFT,SUM(BHND+OBHND) AS BSHIFT,SUM(CHND+OCHND) AS CSHIFT
    ,SUM(AHND+BHND+CHND+OAHND+OBHND+OCHND ) AS STOTAL,
    SUM(OAHND) AS OASHIFT,SUM(OBHND) AS OBSHIFT,SUM(OCHND) AS OCSHIFT,SUM(OAHND+OBHND+OCHND ) AS OTOTAL
    FROM EMPMILL12.dailyhandcomp a
    WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id=".$comp."
    and a.dept_id in ($dpts) ORDER BY dept_code,HOCCU_CODE 
     ";
     $sql="select * from (
        SELECT 1 rem,cata_id, dept_code,cata_desc,ATTANDANCE_DATE,(AHND+OAHND) AS ASHIFT,(BHND+OBHND) AS BSHIFT,(CHND+OCHND) AS CSHIFT
        ,(AHND+BHND+CHND+OAHND+OBHND+OCHND ) AS STOTAL
        FROM EMPMILL12.dailycatasumm a
        WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id=".$comp."
        and a.dept_id in ($dpts)   
        union all
        SELECT 2 rem,'' cata_id,dept_code,'Total' cata_desc,ATTANDANCE_DATE,sum(AHND+OAHND) AS ASHIFT,sum(BHND+OBHND) AS BSHIFT,sum(CHND+OCHND) AS CSHIFT
        ,sum(AHND+BHND+CHND+OAHND+OBHND+OCHND ) AS STOTAL
        FROM EMPMILL12.dailycatasumm a
        WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id=".$comp."
        and a.dept_id in ($dpts) group by dept_code
        ) g  ORDER BY dept_code,rem ,cata_desc"; 
        
     
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getebmcdet($periodfromdate,$periodtodate,$att_spell,$holget,$att_dept) {
    $dpts='';
    if ($att_dept==1 || $att_dept==2 || $att_dept==3) {
        $dpts='14,15,16';          
    }
    if ($att_dept==4) {
        $dpts='17';          
    }
    if ($att_dept==5 || $att_dept==6 ) {
        $dpts='19,154';          
    }
    if ($att_dept==7 || $att_dept==8 ) {
        $dpts='20,21';          
    }
    if ($att_dept==9 || $att_dept==10 ) {
        $dpts='22,75';
    }
    if ($att_dept==11 || $att_dept==12 || $att_dept==13 || $att_dept==14 || $att_dept==15)  {
        $dpts='99';          
    }

    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    $sql="SELECT cata_id,dept_code,cata_desc,ATTANDANCE_DATE,SUM(AHND+OAHND) AS ASHIFT,SUM(BHND+OBHND) 
    AS BSHIFT,SUM(CHND+OCHND) AS CSHIFT
    ,SUM(AHND+BHND+CHND+OAHND+OBHND+OCHND ) AS STOTAL,
    SUM(OAHND) AS OASHIFT,SUM(OBHND) AS OBSHIFT,SUM(OCHND) AS OCSHIFT,SUM(OAHND+OBHND+OCHND ) AS OTOTAL
    FROM EMPMILL12.dailyhandcomp a
    WHERE ATTANDANCE_DATE='".$periodfromdate."' and comp_id=".$comp."
    and a.dept_id in ($dpts) ORDER BY dept_code,HOCCU_CODE 
     ";
     $sql="select mcm.mc_code,mcm.Mechine_type_name,shift_a ASHIFT,shift_b BSHIFT,shift_c CSHIFT,
     (shift_a+shift_b+shift_c) STOTAL 
     from 
     EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
     left join EMPMILL12.mechine_code_master mcm on tdsmd.mc_code_id=mcm.mc_code_id
     where tdsmd.is_active = 1 and tdsmd.tran_date ='".$periodfromdate."' and tdsmd.company_id =".$comp."
     and mcm.dept_id in ($dpts) order by mcm.mc_code 
     "; 
        
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}



public function getpayregistersummdata($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==1 || $holget==2) {
    $sql="SELECT
	FROM_DATE,
	TO_DATE,
	eb_no,
    wname,
    department,
    designation,
    max( case when COMPONENT_ID = 177 then amount else 0 end ) AS `FIXED_BASIC_PER_HOUR`,
    max( case when COMPONENT_ID = 190 then amount else 0 end ) AS `DA_RATE`,
    max( case when COMPONENT_ID = 178 then amount else 0 end ) AS `WORKING_HOURS`,
    max( case when COMPONENT_ID = 179 then amount else 0 end ) AS `NIGHT_SHIFT_HR`,
    max( case when COMPONENT_ID = 102 then amount else 0 end ) AS `HOLIDAY_HR`,
    max( case when COMPONENT_ID = 183 then amount else 0 end ) AS `STLD`,
    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS `OT_HOURS`,
    max( case when COMPONENT_ID = 184 then amount else 0 end ) AS `MISC_EARNING`,
    max( case when COMPONENT_ID = 191 then amount else 0 end ) AS `HRA%`,
    max( case when COMPONENT_ID = 196 then amount else 0 end ) AS `WRK_HOURS_ON_EFF`,
    max( case when COMPONENT_ID = 216 then amount else 0 end ) AS `FIX_BASIC`,
    max( case when COMPONENT_ID = 171 then amount else 0 end ) AS `Night_Allowance`,
    max( case when COMPONENT_ID = 212 then amount else 0 end ) AS `DA`,
    max( case when COMPONENT_ID = 109 then amount else 0 end ) AS `Festival_Wage`,
    max( case when COMPONENT_ID = 112 then amount else 0 end ) AS `STL_Wage`,
    max( case when COMPONENT_ID = 134 then amount else 0 end ) AS `PF_Gross`,
    max( case when COMPONENT_ID = 8 then amount else 0 end ) AS `HRA`,
    max( case when COMPONENT_ID = 20 then amount else 0 end ) AS `Gross_Earnings`,
    max( case when COMPONENT_ID = 18 then amount else 0 end ) AS `EPF`,
    max( case when COMPONENT_ID = 19 then amount else 0 end ) AS `ESI`,
    max( case when COMPONENT_ID = 185 then amount else 0 end ) AS `COMPANY_LOAN`,
    max( case when COMPONENT_ID = 186 then amount else 0 end ) AS `PUJA_ADVANCE`,
    max( case when COMPONENT_ID = 16 then amount else 0 end ) AS `PTAX`,
    max( case when COMPONENT_ID = 25 then amount else 0 end ) AS `Gross_Deductions`,
    max( case when COMPONENT_ID = 21 then amount else 0 end ) AS `Net_Payble`,
    max( case when COMPONENT_ID = 237 then amount else 0 end ) AS `OVERTIME_PAY`,
    max( case when COMPONENT_ID = 249 then amount else 0 end ) AS `ATT_INC`,
	max( case when COMPONENT_ID = 251 then amount else 0 end ) AS `prod_bsc`,
	max( case when COMPONENT_ID = 189 then amount else 0 end ) AS `TIME_RATED_BASIC`
 FROM
    (
    SELECT
        tpep.PAYPERIOD_ID,
        tpp.FROM_DATE,
        tpp.TO_DATE,
        tpep.EMPLOYEEID,
        wm.eb_no,
        CONCAT(worker_name, ' ', IFNULL(last_name, ' ')) wname,
        COMPONENT_ID,
        tpc.NAME,
        wm.esi_no,
        wm.pf_no,
        wm.fpf_no,
        AMOUNT,
        dept_desc department,
        desig designation
    FROM
        tbl_pay_employee_payroll tpep ,
        worker_master wm ,
        tbl_pay_period tpp,
        tbl_pay_components tpc ,
        department_master dm,
        designation dsg
    WHERE
         tpep.EMPLOYEEID = wm.eb_id
        AND wm.dept_id = dm.dept_id
        AND wm.desg_id = dsg.id
        AND tpep.COMPONENT_ID = tpc.ID
        AND tpep.PAYPERIOD_ID = tpp.ID
        and tpep.PAYSCHEME_ID=".$att_payschm." 
        and tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
       	and tpp.is_active=1
    ) g            
  
GROUP BY
    PAYPERIOD_ID,
    eb_no,
    wname,
    fpf_no,
    pf_no,
    esi_no,
    department,
    designation
ORDER BY
    dept_code,    
    eb_no,
    wname


     ";
    }



    
    if ($holget==3 || $holget==4) {
        $sql="select dept_code,department,sum(WORKING_HOURS+HOLIDAY_HR+OT_HOURS) whrs,sum(Net_Payble) Net_Payble from (
            SELECT
                eb_no,
                wname,
                dept_code,
                department,
                tbl_hrms_ed_bank_detail_id,
                max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                max( case when COMPONENT_ID = 70 then amount else 0 end ) AS `RATE_PER_DAY`,
                max( case when COMPONENT_ID = 7 then amount else 0 end ) AS `BASIC`,
                max( case when COMPONENT_ID = 109 then amount else 0 end ) AS `Festival_Wage`,
                max( case when COMPONENT_ID = 237 then amount else 0 end ) AS `OVERTIME_PAY`,
                max( case when COMPONENT_ID = 166 then amount else 0 end ) AS `ADVANCE`,
                max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble
            FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                WHERE
                tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                    and k.PAYSCHEME_ID=".$att_payschm." 
                    and k.BUSINESSUNIT_ID = ".$comp."
                    and k.status = 1
                    and tpp.STATUS <>4
                    AND theod.is_active = 1
                    ) g
            GROUP BY
                eb_no,
                wname,
                department,
                dept_code,tbl_hrms_ed_bank_detail_id
                ) k where tbl_hrms_ed_bank_detail_id is not null
                group by dept_code,department        
                ORDER BY
            dept_code,department 
         ";
         
//    echo $sql;   
    
        }
    
        if ($holget==5) {
            
            $sql="select dept_code,department,sum(WORKING_HOURS+HOLIDAY_HR+OT_HOURS) whrs,sum(Net_Payble) Net_Payble from (
                SELECT
                    eb_no,
                    wname,
                    dept_code,
                    department,
                    tbl_hrms_ed_bank_detail_id,
                    max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                    max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                    max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE_PER_DAY,
                    max( case when COMPONENT_ID = 7 then amount else 0 end ) AS BASIC,
                    max( case when COMPONENT_ID = 109 then amount else 0 end ) AS Festival_Wage,
                    max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
                    max( case when COMPONENT_ID = 166 then amount else 0 end ) AS ADVANCE,
                    max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                    max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                    max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble
                FROM
                    (
                    SELECT
                        k.PAYPERIOD_ID,
                        tpp.FROM_DATE,
                        tpp.TO_DATE,
                        tpep.EMPLOYEEID,
                        theod.emp_code eb_no,
                        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                        COMPONENT_ID,
                        tpc.NAME,
                        AMOUNT,
                        dept_desc department,
                        dm.dept_code,
                        thebd.tbl_hrms_ed_bank_detail_id
                    FROM
                        tbl_pay_employee_payroll k
                        JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                    LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                    left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                    left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                    left join	department_master dm on theod.department_id = dm.dept_id
                    left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                    WHERE
                    tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."' 
                        and k.PAYSCHEME_ID=".$att_payschm." 
                        and k.BUSINESSUNIT_ID = ".$comp."
                        and k.status = 1
                        and tpp.STATUS <>4
                        AND theod.is_active = 1
                        ) g
                GROUP BY
                    eb_no,
                    wname,
                    department,
                    dept_code,tbl_hrms_ed_bank_detail_id
                    ) k where tbl_hrms_ed_bank_detail_id is null and (WORKING_HOURS+HOLIDAY_HR+OT_HOURS+ADVANCE+Net_Payble>0)
                    group by dept_code ,department       
                    ORDER BY
                dept_code,department 
                     ";
             
      //  echo $sql;   
        
            }
         
            if ($holget==6) {
                $sql="select k.*,pf_uan_no,esi_no from (
                SELECT
                employeeid,
                eb_no,
                    wname,
                    dept_code,
                    department,
                    tbl_hrms_ed_bank_detail_id,
                    max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                    max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NIGHT_SHIFT_HR,
                    max( case when COMPONENT_ID = 102 then amount else 0 end ) AS HOLIDAY_HR,
                    max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                    max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE_PER_DAY,
                    max( case when COMPONENT_ID = 7 then amount else 0 end ) AS BASIC,
                    max( case when COMPONENT_ID = 270 then amount else 0 end ) AS TIFFIN_AMOUNT,
                    max( case when COMPONENT_ID = 133 then amount else 0 end ) AS WASHING_ALLOWANCE,
                    max( case when COMPONENT_ID = 9 then amount else 0 end ) AS OTHER_ALLOWANCE,
                    max( case when COMPONENT_ID = 72 then amount else 0 end ) AS CONV_ALLOWANCE,
                    max( case when COMPONENT_ID = 109 then amount else 0 end ) AS Festival_Wage,
                    max( case when COMPONENT_ID = 66 then amount else 0 end ) AS GROSS2,
                    max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
                    max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESI,
                    max( case when COMPONENT_ID = 166 then amount else 0 end ) AS ADVANCE,
                    max( case when COMPONENT_ID = 268 then amount else 0 end ) AS ARR_PLUS,
                    max( case when COMPONENT_ID = 269 then amount else 0 end ) AS ARR_MINUS,
                    max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
                    max( case when COMPONENT_ID = 25 then amount else 0 end ) AS GROSS_DED,
                    max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
                    max( case when COMPONENT_ID = 21 then amount else 0 end ) AS Net_Payble,
                    max( case when COMPONENT_ID = 22 then amount else 0 end ) AS EMPL_EPF,
                    max( case when COMPONENT_ID = 23 then amount else 0 end ) AS EMPL_ESI,
                    0 P_TAX
                FROM
                    (
                    SELECT
                        k.PAYPERIOD_ID,
                        tpp.FROM_DATE,
                        tpp.TO_DATE,
                        tpep.EMPLOYEEID,
                        theod.emp_code eb_no,
                        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                        COMPONENT_ID,
                        tpc.NAME,
                        AMOUNT,
                        dept_desc department,
                        dm.dept_code,
                        thebd.tbl_hrms_ed_bank_detail_id
                    FROM
                        tbl_pay_employee_payroll k
                        JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                    LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                    left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                    left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                    left join	department_master dm on theod.department_id = dm.dept_id
                    left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                    WHERE
                    tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'  
                    and k.PAYSCHEME_ID=".$att_payschm." 
                    and k.BUSINESSUNIT_ID = ".$comp."
                    and k.status = 1
                        and tpp.STATUS <>4
                        AND theod.is_active = 1
                        ) g
                GROUP BY
                tpep.EMPLOYEEID,
                eb_no,
                    wname,
                    department,
                    dept_code,tbl_hrms_ed_bank_detail_id
                    ) k 
                    order by  
                    dept_code,
                    eb_no ";
                }           

   // echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getpayschemeparadata($att_branch,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
 

    if ($att_payschm>0) {
    $sql="select * from EMPMILL12.tbl_payslip_print_component tppc where payscheme_id =".$att_payschm." and  
    company_id=".$comp." and branch_id =".$att_branch;
    $query = $this->db->query($sql);
    if ($query->num_rows()== 0) {

        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Emp Code',
        1,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);
        
        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Name',
        2,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);

        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Department',
        3,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);
 
        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Designation',
        4,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);

        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Uan no',
        5,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);

        $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
        payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   values (0,'Esi No',
        6,".$att_payschm.",".$comp.",".$att_branch.",'F',1,0,1)";
        $this->db->query($sql);
        

        $sql="SELECT tps.BUSINESSUNIT_ID,bm.branch_id,tps.ID,tps.NAME,tpsd.COMPONENT_ID ,tpc.NAME ,
        tpc.DESCRIPTION ,0 payslip,0 totpayslip
        FROM tbl_pay_scheme tps
        JOIN vowsls.tbl_pay_scheme_details tpsd ON tps.ID = tpsd.PAY_SCHEME_ID
        JOIN vowsls.tbl_pay_components tpc ON tpc.ID = tpsd.COMPONENT_ID  -- Fix: Added '='
        JOIN vowsls.company_master cm ON cm.comp_id = tps.BUSINESSUNIT_ID
        JOIN vowsls.branch_master bm ON bm.company_id = cm.comp_id
        WHERE tps.ID = ".$att_payschm."
        AND tps.BUSINESSUNIT_ID =".$comp."
        AND tps.STATUS = 32
        AND tpsd.STATUS = 1
        AND bm.branch_id = ".$att_branch."
        order by tpc.NAME ";
        $n=7;
        $query = $this->db->query($sql);
        $data=$query->result();
        foreach ($data as $record) {
                $bid=$record->BUSINESSUNIT_ID;
                $brid=$record->branch_id;
                $pid=$record->ID;
                $pcompid=$record->COMPONENT_ID;
                $pcompdesc=$record->NAME;
                $payslip=0;
                $totpayslip=0;
                $payslipord=7;
    
                $sql="insert into EMPMILL12.tbl_payslip_print_component (component_id,desc_print,payslip_order,
                payscheme_id,company_id,branch_id,fixed_var_cols,is_active,total_print,payslip_print )   
                values (".$pcompid.",'".$pcompdesc."',".
                $n.",".$att_payschm.",".$comp.",".$att_branch.",'V',1,0,0)";
                $this->db->query($sql);
            
            }    


    }    
    }
     
  $sql="select
  tppc.id paraid,
  tppc.company_id ,
  tppc.branch_id,
  tppc.payscheme_id,
  tps.NAME payshmname,
  tppc.COMPONENT_ID ,
  tpc.NAME ,
  tppc.desc_print ,	tppc.payslip_order,
  tppc.payslip_print,
  tppc.total_print,tpc.TYPE
from
  EMPMILL12.tbl_payslip_print_component tppc
  left join (select * from tbl_pay_scheme where status=32) tps on
  tps.ID = tppc.payscheme_id
left join (select * from tbl_pay_scheme_details where status=1) tpsd on tps.id=tpsd.PAY_SCHEME_ID and 
tpsd.COMPONENT_ID  =tppc.component_id and tpsd.PAY_SCHEME_ID =tppc.payscheme_id 
left 	JOIN vowsls.tbl_pay_components tpc ON
  tppc.component_id  = tpc.ID
WHERE
tps.ID = ".$att_payschm."
  AND tppc.company_id  =". $comp."
  AND tppc.branch_id = ".$att_branch."
  and tppc.is_active = 1
ORDER BY
  tpc.TYPE,tppc.payslip_order,tpc.NAME 
";
  //  echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getAllDesignationsgg($companyId){
    $this->db->where('company_id',$companyId);
    $q = $this->db->get('designation');
    if($q->num_rows() > 0){
        foreach($q->result() as $row){
            $data[] = $row;
        }
        return $data;
    }
    return false;
}

public function getnjmwagesData($periodfromdate,$periodtodate,$att_payschm,$holget) {
    $yearmn=substr($periodfromdate,0,4).substr($periodfromdate,5,2);        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $sql="select * from master_department where rec_id=";
//    AND  da.worked_department_id in (21,22,23,24,25,26,27,28,29) 


   $sql=" select dept_code,eb_no,wrk_name,
   max( case when (time_piece = 'T' and REGULAR_OT='R' and piece_rate_type<>2 )  then wkhrs else 0 end ) AS T1,
   max( case when (time_piece = 'T' and REGULAR_OT='R' and piece_rate_type=2 )  then wkhrs else 0 end ) AS T2,
   max( case when (time_piece = 'P' and REGULAR_OT='R'  )  then wkhrs else 0 end ) AS PH,
   max( case when (time_piece = 'T' and REGULAR_OT='O'  )  then wkhrs else 0 end ) AS OTT,
   max( case when (time_piece = 'P' and REGULAR_OT='O'  )  then wkhrs else 0 end ) AS OTP,
   max( case when (time_piece = 'N'   )  then wkhrs else 0 end ) AS NS,
   max( case when (time_piece = 'E'   )  then wkhrs else 0 end ) AS ED,'".$yearmn."' yearmn FROM (
   SELECT dept_code,eb_no,time_piece,piece_rate_type,wkhrs,REGULAR_OT,lay_off_hours,concat(wm.worker_name,' ',ifnull(middle_name,''),' ',ifnull(last_name,'')) wrk_name
   FROM ( 
   SELECT worked_department_id,eb_id,'N' AS time_piece,0 AS piece_rate_type,SUM(NSHFT) AS wkhrs,'E' REGULAR_OT,0 lay_off_hours FROM ( 
   SELECT da.worked_department_id,da.eb_id,attendance_type ,da.attendance_date ,SUM(da.working_hours-idle_hours) AS HRS,SUM(da.working_hours-idle_hours)/8 AS NSHFT
   FROM daily_attendance da 
   left join designation d on da.worked_designation_id =d.id 
   WHERE da.spell= 'C' 
   AND da.attendance_date >='".$periodfromdate."'
   and da.attendance_date <='".$periodtodate."'
   AND d.time_piece ='P' and da.is_active=1
   and da.worked_designation_id NOT IN (1005,1114,1115) 
   and da.company_id=1 GROUP BY worked_department_id,da.eb_id,da.attendance_date,da.attendance_type 
   ) g GROUP BY worked_department_id,eb_id,attendance_type 
   UNION ALL      
   SELECT worked_department_id,eb_id,'E' AS time_piece,0 AS piece_rate_type,COUNT(*) AS WKHRS,'E' REGULAR_OT,0 lay_off_hours FROM ( 
   SELECT DISTINCT(da.attendance_date) attendance_date,eb_id,worked_department_id FROM daily_attendance da 
   WHERE da.attendance_date >='".$periodfromdate."'
   and da.attendance_date <='".$periodtodate."' and da.company_id= ".$comp." and da.is_active=1
   AND attendance_type='R' ) A GROUP BY eb_id,worked_department_id
   UNION ALL      
   SELECT worked_department_id,da.eb_id,d.time_piece,ifnull(d.piece_rate_type,0) AS piece_rate_type,SUM(working_hours-idle_hours-ifnull(layoff_hours,0)) wkhrs,
   attendance_type REGULAR_OT,SUM(ifnull(layoff_hours,0)) lay_off_hours FROM
   (      
   select da.*,dld.layoff_hours  from daily_attendance da 
   left join daily_layoff_deptwise dld on da.company_id =dld.company_id and da.attendance_date =dld.layoff_date 
   and da.spell =dld.spell and da.worked_department_id =dld.dept_id  
   ) da
   ,designation d  where
   da.worked_designation_id=d.id  
   and  attendance_type in ('R','O')  
   AND da.attendance_date >='".$periodfromdate."'
   and da.attendance_date <='".$periodtodate."' and da.company_id= ".$comp."  and da.is_active=1      
   GROUP BY worked_department_id,da.eb_id,d.time_piece,d.piece_rate_type,da.attendance_type
   ) v,worker_master wm, department_master dm WHERE 
   v.eb_id=wm.eb_id and v.worked_department_id=dm.dept_id and wm.cata_id in (15,16,17,20,21)
   )  k  group by dept_code,eb_no,wrk_name
   ORDER BY dept_code,eb_no  
     "; 
    
    // echo $sql;   
    
    $query= $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

////////// end njm data /////////////
////////////// sabir for njm Leave data 28.12.23////////////////////
public function getnjmleavData($periodfromdate,$periodtodate,$att_payschm,$holget) {

    $meals=40;
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $yearmn=substr($periodfromdate,0,4).substr($periodfromdate,5,2);        

    $sql=" 	  select 47 dpt,eb_no,wrk_name,dept_desc,
    max( case when (lv_type = 'FL' )  then mhrs else 0 end ) AS fl,
    max( case when (lv_type = 'SL' )  then msld else 0 end ) AS sl,
    max( case when (lv_type = 'UL' )  then msld else 0 end ) AS ul,
    max( case when (lv_type = 'EL' )  then msld else 0 end ) AS el,
    max( case when (lv_type = 'SS' )  then msld else 0 end ) AS ss,
    max( case when (lv_type = 'ML' )  then msld else 0 end ) AS ml,'".$yearmn."' yearmn
    from (
    SELECT eb_no,v.lv_type,mhrs,msld,concat(wm.worker_name,' ',ifnull(middle_name,''),' ',ifnull(last_name,'')) wrk_name,
    dept_desc FROM (
    select eb_id,'FL' AS lv_type,ifnull(sum(holiday_hours),0) as mhrs,ifnull(sum(fsld),0) as msld FROM (
    SELECT eb_id,holiday_date,holiday_hours,COUNT(*) AS fsld  From tbl_hrms_holiday_transactions thht
    left join holiday_master hm on hm.id=thht.holiday_id
    where holiday_date between '$periodfromdate' and '$periodtodate' and hm.company_id= ".$comp."  and thht.is_active=1
    GROUP BY eb_id,holiday_date,holiday_hours ) g
    GROUP BY eb_id
    Union All
    SELECT eb_id,'SL' AS lv_type,0 mhrs,sum(sldays) msld from (
    SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
    join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
    join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
    Where ltd.leave_date between '$periodfromdate' and '$periodtodate' and lt.status =3 and 
    lt.company_id = ".$comp."   and ifnull(ltd.is_active,1) = 1
    AND (lt2.leave_type_code IN ('S' , 'C')) ) g group by eb_id
    Union All
    SELECT eb_id,'SS' AS lv_type,0 mhrs,sum(sldays) msld from (
    SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
    join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
    join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
    Where ltd.leave_date between '$periodfromdate' and '$periodtodate' and lt.status =3 
    and lt.company_id =".$comp."   and ifnull(ltd.is_active,1) = 1
    AND lt2.leave_type_code IN ('P') 
    ) g group by eb_id
    Union All
    SELECT eb_id,'EL' AS lv_type,0 mhrs,sum(sldays) msld from (
    SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
    join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
    join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
    Where ltd.leave_date between '$periodfromdate' and '$periodtodate' and lt.status =3 and 
    lt.company_id =".$comp."   and ifnull(ltd.is_active,1) = 1 
    AND lt2.leave_type_code IN ('L')) g  group by eb_id
    Union All
    SELECT eb_id,'UL' AS lv_type,0 mhrs,sum(sldays) msld from (
    SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
    join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
    join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
    Where ltd.leave_date between '$periodfromdate' and '$periodtodate' and lt.status =3 and 
    lt.company_id =".$comp."   and ifnull(ltd.is_active,1) = 1 
    AND lt2.leave_type_code IN ('A','U')) g  group by eb_id
    Union All
    select eb_id,'ML' AS lv_type,0 AS MHRS,SUM($meals*no_of_meals) AS msld from canteen_details cd 
    join worker_master wm on cd.tktno =wm.eb_no 
    where tran_date  between '$periodfromdate' and '$periodtodate' GROUP BY eb_id
    ) v,worker_master wm, department_master dm 
    WHERE v.eb_id=wm.eb_id and wm.dept_id =dm.dept_id 
    ) k group by eb_no,wrk_name,dept_desc
    ORDER BY eb_no
     "; 
    
   //  echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

////////// end njm data /////////////
////////////// sabir for njm Leave data 28.12.23////////////////////
public function locadatafill($comp) {
    $sql="select * from EMPMILL12.tbl_sublocation where company_id=".$comp;
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

}

public function getnjmsdrhrlData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $yearmn=substr($periodfromdate,0,4).substr($periodfromdate,5,2);        

    $sql="select eb_no,mach_shr_code,sard_help,0 hours1,0 hours2,sum(wrkhrs) wrkhrs,GROUPP,dept_code,MASTDEPT from (
        SELECT da.eb_no,da.worked_designation_id,mach_shr_code,0 hours1,0 hours2,(working_hours-idle_hours) wrkhrs,dept_code,'008' MASTDEPT,
        case when worked_designation_id=1114 then 'H'  
        when worked_designation_id=1115 then 'S'
        when worked_designation_id=1005 then 'S' end sard_help,
        case when dept_code between '029' and '033' then 'A'
        when dept_code between '034' and '038' then 'B'
        when dept_code between '039' and '043' then 'C' end GROUPP
        FROM daily_ebmc_attendance dea ,daily_attendance da ,department_master dm ,mechine_master mm 
        WHERE  da.daily_atten_id=dea.daily_atten_id and da.attendance_date between '$periodfromdate' and '$periodtodate' 
        and dept_code>='029' AND dept_code<='043' and da.worked_designation_id in (1114,1115,1005)
        and dea.mc_id=mm.mechine_id and da.worked_department_id=dm.dept_id and da.company_id=".$comp."
        ) g 
        GROUP BY eb_no,mach_shr_code,sard_help,GROUPP,dept_code 
        ORDER BY GROUPP,dept_code,eb_no
     "; 

     $sql="select eb_no,mach_shr_code,sard_help,0 hours1,0 hours2,sum(wrkhrs) wrkhrs,GROUPP,dept_code,MASTDEPT from (
        SELECT da.attendance_date,attendance_type,da.spell,da.eb_no,da.worked_designation_id,mach_shr_code,0 hours1,0 hours2,(working_hours-idle_hours) wrkhrs,dept_code,'008' MASTDEPT,
        case when worked_designation_id=1114 then 'H'  
        when worked_designation_id=1115 then 'S'
        when worked_designation_id=1005 then 'S' end sard_help,
        case when dept_code between '029' and '033' then 'A'
        when dept_code between '034' and '038' then 'B'
        when dept_code between '039' and '043' then 'C' end GROUPP
        FROM daily_ebmc_attendance dea ,daily_attendance da ,department_master dm ,mechine_master mm 
        WHERE  da.daily_atten_id=dea.daily_atten_id and da.attendance_date between '$periodfromdate' and '$periodtodate' 
        and dept_code>='029' AND dept_code<='043' and da.worked_designation_id in (1114,1115,1005)
        and dea.mc_id=mm.mechine_id and da.worked_department_id=dm.dept_id and da.company_id=".$comp."
        and dea.is_active=1 and da.is_active=1 and da.attendance_type in ('R','O')
        ) g 
        GROUP BY eb_no,mach_shr_code,sard_help,GROUPP,dept_code 
        ORDER BY GROUPP,dept_code,eb_no";
 
    
   //  echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getnjmbeamData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $yearmn=substr($periodfromdate,0,4).substr($periodfromdate,5,2);        

    $sql="select eb_no,beam_wage_code,sum(cuts) cuts,sum(whrs) whrs,worked_designation_id,dept_code,'".$yearmn."' yearmn from
    (
    select bm.*,da.eb_no,da.dept_code,da.worked_designation_id,whrs from
    (
    select tran_date,spell,beam_mc_no,beam_wage_code,sum(no_of_cuts) cuts  from beaming_daily_production bdp
    left join EMPMILL12.beaming_weaving_quality_master bwqm on bdp.quality_code=bwqm.qcode
    where bdp.company_id =".$comp." and is_active =1
    and bdp.tran_date between '$periodfromdate' and '$periodtodate' 
    group by tran_date,spell,beam_mc_no,beam_wage_code
    ) bm
    left join (
    select da.eb_no,da.attendance_date,da.spell,da.worked_designation_id,da.worked_department_id,dept_code,dea.mc_id,sum(working_hours-idle_hours) whrs  from daily_ebmc_attendance dea
    join daily_attendance da on da.daily_atten_id =dea.daily_atten_id
    join department_master dm on da.worked_department_id=dm.dept_id
    where da.is_active =1 and dea.is_active =1 and da.attendance_date between '$periodfromdate' and '$periodtodate' 
    and da.company_id=".$comp." and da.attendance_type not in ('C')
    group by da.eb_no,da.attendance_date,da.spell,da.worked_designation_id,da.worked_department_id,dea.mc_id,dept_code
    ) da on da.attendance_date=bm.tran_date and da.spell=bm.spell and da.mc_id=bm.beam_mc_no
    ) g group by eb_no,beam_wage_code,worked_designation_id,dept_code
    order by eb_no
    "; 
    
   //  echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getnjmpayslip($periodfromdate,$periodtodate,$att_payschm,$holget,$att_dept) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==11) {
        $sql="select k.*,esi_no,pf_no
        from (
                           SELECT
                           from_date,
                           to_date,
                           employeeid,
                           eb_no,
                           wname,
                           dept_code,
                           department,
                           desig,
                           time_piece,
                            max(case when  COMPONENT_ID = (324 + 303 / 288 + 289 + 98) then amount/8 else 0 end) AS RATE,
                            max(case when  COMPONENT_ID  = ( 288 + 289 + 98)  then amount/8 else 0 end) AS DAYS,
                            max( case when COMPONENT_ID = 288 then amount else 0 end ) AS THRS1,
                            max( case when COMPONENT_ID = 289 then amount else 0 end ) AS THRS2,
                            max( case when COMPONENT_ID = 98 then amount else 0 end ) AS PHRS,
                            max( case when COMPONENT_ID = 358 then amount else 0 end ) AS CPN,
                            max( case when COMPONENT_ID = 102 then amount else 0 end ) AS FHRS,
                            max( case when COMPONENT_ID = 103 then amount else 0 end ) AS LHRS,
                            max( case when COMPONENT_ID = 169 then amount else 0 end ) AS SLD,
                            max( case when COMPONENT_ID = 290 then amount else 0 end ) AS ELD,
                            max( case when COMPONENT_ID = 297 then amount else 0 end ) AS OTTHR,
                            max( case when COMPONENT_ID = 298 then amount else 0 end ) AS OTPHR,
                            max( case when COMPONENT_ID = 145 then amount else 0 end ) AS ESID,
                            max( case when COMPONENT_ID = 324 then amount else 0 end ) AS TWAGE,
                            max( case when COMPONENT_ID = 337 then amount else 0 end ) AS LOWI,
                            max( case when COMPONENT_ID = 334 then amount else 0 end ) AS ILT_PW,
                            max( case when COMPONENT_ID = 292 then amount else 0 end ) AS S_ADV,
                            max( case when COMPONENT_ID = 354 then amount else 0 end ) AS RSD,
                            max( case when COMPONENT_ID = 303 then amount else 0 end ) AS P_WAGE,
                            max( case when COMPONENT_ID = 215 then amount else 0 end ) AS DA,
                            max( case when COMPONENT_ID = 338 then amount else 0 end ) AS LODG,
                            max( case when COMPONENT_ID = 305 then amount else 0 end ) AS INCENT,
                            max( case when COMPONENT_ID = 353 then amount else 0 end ) AS CDN,
                            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS GDED,
                            max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NA,
                            max( case when COMPONENT_ID = 332 then amount else 0 end ) AS EWI,
                            max( case when COMPONENT_ID = 329 then amount else 0 end ) AS OTW,
                            max( case when COMPONENT_ID = 335 then amount else 0 end ) AS ILIN,
                            max( case when COMPONENT_ID = 18 then amount else 0 end ) AS PF,
                            max( case when COMPONENT_ID = 355 then amount else 0 end ) AS NPAY,
                            max( case when COMPONENT_ID = 325 then amount else 0 end ) AS GI,
                            max( case when COMPONENT_ID = 331 then amount else 0 end ) AS EDG,
                            max( case when COMPONENT_ID = 330 then amount else 0 end ) AS OTI,
                            max( case when COMPONENT_ID = 336 then amount else 0 end ) AS ILDG,
                            max( case when COMPONENT_ID = 343 then amount else 0 end ) AS RENT,
                            max( case when COMPONENT_ID = 21 then amount else 0 end ) AS RNPAY,
                            max( case when COMPONENT_ID = 344 then amount else 0 end ) AS INC,
                            max( case when COMPONENT_ID = 331 then amount else 0 end ) AS ODG,
                            max( case when COMPONENT_ID = 349 then amount else 0 end ) AS GRS2,
                            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESI,
                            max( case when COMPONENT_ID = 351 then amount else 0 end ) AS VRD1,
                            max( case when COMPONENT_ID = 339 then amount else 0 end ) AS FWI,
                            max( case when COMPONENT_ID = 342 then amount else 0 end ) AS SDG,
                            max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
                            max( case when COMPONENT_ID = 293 then amount else 0 end ) AS ADVDED,
                            max( case when COMPONENT_ID = 352 then amount else 0 end ) AS G_W_F,
                            max( case when COMPONENT_ID = 357 then amount else 0 end ) AS NET,
                            max( case when COMPONENT_ID = 341 then amount else 0 end ) AS SWI,
                            max( case when COMPONENT_ID = 348 then amount else 0 end ) AS TOTAL,
                            max( case when COMPONENT_ID = 350 then amount else 0 end ) AS GRS1,
                            max( case when COMPONENT_ID = 16 then amount else 0 end ) AS PTAX,
                            max( case when COMPONENT_ID = 345 then amount else 0 end ) AS LR,
                            max( case when COMPONENT_ID = 346 then amount else 0 end ) AS PVE,
                            max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PFG,
                            max( case when COMPONENT_ID = 294 then amount else 0 end ) AS OP,
                            max( case when COMPONENT_ID = 291 then amount else 0 end ) AS CANT,
                            max( case when COMPONENT_ID = 311 then amount else 0 end ) AS PROD
                                      
                           
                       FROM
                           (
                           SELECT
                               k.PAYPERIOD_ID,
                               tpp.FROM_DATE,
                               tpp.TO_DATE,
                               tpep.EMPLOYEEID,
                               theod.emp_code eb_no,
                               CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                               COMPONENT_ID,
                               tpc.NAME,
                               AMOUNT,
                               dept_desc department,
                               dm.dept_code,
                               d.desig,
                               d.time_piece,
                               thebd.tbl_hrms_ed_bank_detail_id
                           FROM
                               tbl_pay_employee_payroll k
                               JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                           LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                           LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                           left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                           left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                           left join	department_master dm on theod.department_id = dm.dept_id
                           left join designation d on theod.designation_id=d.id
                           left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on 
                           thebd.eb_id=k.EMPLOYEEID
                           WHERE
                           tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'  
                           and k.PAYSCHEME_ID=".$att_payschm." 
                           and k.BUSINESSUNIT_ID = ".$comp." and dm.dept_id in (".$att_dept.") 
                           and k.status = 1
                               and tpp.STATUS <>4
                               AND theod.is_active = 1
                                
                               ) g
                       GROUP BY		
                       tpep.EMPLOYEEID,
                           eb_no,
                           wname,
                           desig,
                           time_piece,
                           department,
                           dept_code,tbl_hrms_ed_bank_detail_id
                           ) k 
                           left join (select * from tbl_hrms_ed_esi  where is_active=1 ) thee on thee.eb_id=k.EMPLOYEEID
                           left join (select * from tbl_hrms_ed_pf  where is_active=1 ) thep on thep.eb_id=k.EMPLOYEEID
                           
                           order by  
                           dept_code,
                           eb_no ";
        }  
        //  echo  $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

public function getnjmofbclerk($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==12) {
        $sql="select k.*,esi_no,pf_no
        from (
                           SELECT
                           from_date,
                           to_date,
                           employeeid,
                           eb_no,
                           wname,
                           dept_code,
                           department,
                           desig,
                           time_piece,
                     
                               max( case when COMPONENT_ID = 5 then amount else 0 end ) AS DAYS_BASIS,
                            max( case when COMPONENT_ID = 178 then amount else 0 end )/8 AS DAYS,
                            max( case when COMPONENT_ID = 102 then amount else 0 end ) AS FHR,
                            max( case when COMPONENT_ID = 178 then amount else 0 end ) AS HOURS, 
                            max( case when COMPONENT_ID = 297 then amount else 0 end ) AS EXHRS,
                            max( case when COMPONENT_ID = 169 then amount else 0 end ) AS SL,
                            max( case when COMPONENT_ID = 290 then amount else 0 end ) AS EL,
                            max( case when COMPONENT_ID = 324 then amount else 0 end ) AS WAGE,
                            max( case when COMPONENT_ID = 348 then amount else 0 end ) AS TOTAL,
                            max( case when COMPONENT_ID = 16 then amount else 0 end ) AS PTAX,
                            max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PFG,
                            max( case when COMPONENT_ID = 25 then amount else 0 end ) AS GDED,
                            max( case when COMPONENT_ID = 215 then amount else 0 end ) AS DA,
                            max( case when COMPONENT_ID = 329 then amount else 0 end ) AS EXW,
                            max( case when COMPONENT_ID = 18 then amount else 0 end ) AS PF,
                            max( case when COMPONENT_ID = 325 then amount else 0 end ) AS GI,
                            max( case when COMPONENT_ID = 343 then amount else 0 end ) AS RENT,
                            max( case when COMPONENT_ID = 339 then amount else 0 end ) AS FHW,
                            max( case when COMPONENT_ID = 312 then amount else 0 end ) AS GWF,
                            max( case when COMPONENT_ID = 292 then amount else 0 end ) AS SADV,
                            max( case when COMPONENT_ID = 345 then amount else 0 end ) AS LRENT,
                            max( case when COMPONENT_ID = 340 then amount else 0 end ) AS FHDGI,
                            max( case when COMPONENT_ID = 354 then amount else 0 end ) AS RSD,
                            max( case when COMPONENT_ID = 355 then amount else 0 end ) AS NETPAY,
                            max( case when COMPONENT_ID = 333 then amount else 0 end ) AS SLW,
                            max( case when COMPONENT_ID = 344 then amount else 0 end ) AS INC,
                            max( case when COMPONENT_ID = 21 then amount else 0 end ) AS RNPAY,
                            max( case when COMPONENT_ID = 342 then amount else 0 end ) AS SLDGI,
                            max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
                            max( case when COMPONENT_ID = 351 then amount else 0 end ) AS L_W,
                            max( case when COMPONENT_ID = 332 then amount else 0 end ) AS ELW,
                            max( case when COMPONENT_ID = 166 then amount else 0 end ) AS ADVAN,
                            max( case when COMPONENT_ID = 357 then amount else 0 end ) AS NET,
                            max( case when COMPONENT_ID = 333 then amount else 0 end ) AS ELDGI,
                            max( case when COMPONENT_ID = 350 then amount else 0 end ) AS GROSS,
                            max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC
                                      
                           
                       FROM
                           (
                           SELECT
                               k.PAYPERIOD_ID,
                               tpp.FROM_DATE,
                               tpp.TO_DATE,
                               tpep.EMPLOYEEID,
                               theod.emp_code eb_no,
                               CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                               COMPONENT_ID,
                               tpc.NAME,
                               AMOUNT,
                               dept_desc department,
                               dm.dept_code,
                               d.desig,
                               d.time_piece,
                               thebd.tbl_hrms_ed_bank_detail_id
                           FROM
                               tbl_pay_employee_payroll k
                               JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                           LEFT JOIN (select * from vowsls.tbl_hrms_ed_official_details where is_active=1)  theod ON 	k.EMPLOYEEID = theod.eb_id
                           LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                           left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                           left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                           left join	department_master dm on theod.department_id = dm.dept_id
                           left join designation d on theod.designation_id=d.id
                            
                           left join (select * from tbl_hrms_ed_bank_details where is_active=1 )thebd on thebd.eb_id=k.EMPLOYEEID
                           WHERE
                            tpp.FROM_DATE ='2024-01-01' and tpp.TO_DATE ='2024-01-31'  
                           and k.PAYSCHEME_ID=166 
                           and k.BUSINESSUNIT_ID = 1
                           and k.status = 1
                               and tpp.STATUS <>4
                               AND theod.is_active = 1
                                
                               ) g
                       GROUP BY		
                       tpep.EMPLOYEEID,
                           eb_no,
                           wname,
                           desig,
                           time_piece,
                           department,
                           dept_code,tbl_hrms_ed_bank_detail_id
                           ) k 
                           left join (select * from tbl_hrms_ed_esi  where is_active=1 ) thee on thee.eb_id=k.EMPLOYEEID
                           left join (select * from tbl_hrms_ed_pf  where is_active=1 ) thep on thep.eb_id=k.EMPLOYEEID
                           
                           order by  
                           dept_code,
                           eb_no ";
        }  
        //  echo  $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}

///////////// end payslip///////////////////////////

///////////////////////////////////C.E DEDUCTION ABSTRACT SHEET//getcedeductionclerk

public function getcedeductionclerk($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==13) {
        $sql=" SELECT
        dept_code,
        department,
        SUM(CASE WHEN COMPONENT_ID = 350 THEN amount ELSE 0 END) AS GROSS, 
        SUM(CASE WHEN COMPONENT_ID = 18 THEN amount ELSE 0 END) AS PF, 
        SUM(CASE WHEN COMPONENT_ID = 19 THEN amount ELSE 0 END) AS ESIC,
        SUM(CASE WHEN COMPONENT_ID = 354 THEN amount ELSE 0 END) AS RSD,
        SUM(CASE WHEN COMPONENT_ID = 343 THEN amount ELSE 0 END) AS RENT,
        SUM(CASE WHEN COMPONENT_ID = 345 THEN amount ELSE 0 END) AS LRENT,
        SUM(CASE WHEN COMPONENT_ID = 166 THEN amount ELSE 0 END) AS ADVANCE,
        SUM(CASE WHEN COMPONENT_ID = 292 THEN amount ELSE 0 END) AS SUN_ADV,
        SUM(CASE WHEN COMPONENT_ID = 16 THEN amount ELSE 0 END) AS PTAX ,
        SUM(CASE WHEN COMPONENT_ID = 355 THEN amount ELSE 0 END) AS NETPAY,
        SUM(CASE WHEN COMPONENT_ID = 21 THEN amount ELSE 0 END) AS RNPAY,
        SUM(CASE WHEN COMPONENT_ID = 312 THEN amount ELSE 0 END) AS GWF  
    FROM
        (
        SELECT
            k.PAYPERIOD_ID,
             COMPONENT_ID,
            tpc.NAME,
            AMOUNT,
            dept_desc department,
            dm.dept_code
           FROM
            tbl_pay_employee_payroll k
        JOIN vowsls.tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = k.EMPLOYEEID
         JOIN (SELECT * FROM vowsls.tbl_hrms_ed_official_details WHERE is_active = 1) theod ON k.EMPLOYEEID = theod.eb_id
        JOIN tbl_pay_period tpp ON tpp.ID = k.PAYPERIOD_ID
        JOIN tbl_pay_components tpc ON tpc.ID = k.COMPONENT_ID
        JOIN department_master dm ON theod.department_id = dm.dept_id
        
        WHERE
            tpp.FROM_DATE = '".$periodfromdate."' AND tpp.TO_DATE = '".$periodtodate."'  
            AND k.PAYSCHEME_ID = ".$att_payschm." 
            AND k.BUSINESSUNIT_ID = ".$comp."
            AND k.status = 1
            AND tpp.STATUS <> 4
            
        ) g
    GROUP BY
        dept_code,
        department
    ORDER BY
        department ";
        }  
        //  echo  $sql;   
   
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


public function getotsummary($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==3) {
     $sql=" select DEPT_CODE,DEPARTMENT,
     sum(ot_hours) as OTHRS,sum(OVERTIME_PAY) as OVERTIME_PAY,sum(OT_ADVANCE) as OT_ADVANCE,sum(OT_NET_PAY) as NET_PAY 
     from (
            SELECT
                eb_no,
                wname,
                dept_code,
                department,
                max( case when COMPONENT_ID = 70 then amount else 0 end ) AS RATE,
                max( case when COMPONENT_ID = 135 then amount else 0 end ) AS OT_HOURS,
                max( case when COMPONENT_ID = 237 then amount else 0 end ) AS OVERTIME_PAY,
                max( case when COMPONENT_ID = 284 then amount else 0 end ) AS OT_ADVANCE,
                max( case when COMPONENT_ID = 285 then amount else 0 end ) AS MISC_OT_EARNINGS,
                max( case when COMPONENT_ID = 286 then amount else 0 end ) AS OT_NET_PAY
                    FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code
                FROM
                    tbl_pay_employee_payroll k
                    JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
                left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
                left join	department_master dm on theod.department_id = dm.dept_id
                WHERE
                     k.COMPONENT_ID in (135, 237, 70,284,285,286)
                    and amount>0
                    AND tpp.FROM_DATE = '".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'
                AND k.PAYSCHEME_ID in(125,151,161)
                AND k.BUSINESSUNIT_ID =".$comp."
                AND k.status = 1
                AND tpp.STATUS <> 4
                AND theod.is_active = 1
                    ) g
            GROUP BY
                eb_no,
                wname,
                department,
                dept_code
                ) k where OT_NET_PAY>0
                 group by 
                dept_code,
                department
                ORDER BY
            dept_code,
                department"; 
   // echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}
}

//////////////////////////////// EJM ATTENDANCE DATA-30.04.24///////////////////////
public function getejmattdata($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==20) {
        $sql="
        select date_format(to_date,'%d-%m-%Y') fnedate,eb_no,WORKING_HOURS,NS_HRS,HL_HRS,STL_D,PF_GROSS,EPF,0 bf,0 cf,HOL_AMT,0 padv,0 colnbal,TOTAL_EARN from (
        select
            k.*,
            pf_uan_no,
            esi_no,
            pf_no,
            case
                when shiftcd = 1 then 'A'
                when shiftcd = 2 then 'B'
                when shiftcd = 3 then 'C'
                else 'G'
            end shift,
            dm.dept_desc deparment,
            dpm.dept_code dept_code
        from
            (
            SELECT
                from_date,
                to_date,
                employeeid,
                eb_no,
                wname,
                dept_code dept_codes,
                department department,
                desig,
                time_piece,
                max( case when COMPONENT_ID = 175 then amount else 0 end ) AS shiftcd,
                max( case when COMPONENT_ID = 238 then round(amount, 2) else 0 end ) AS BASIC_RATE,
                max( case when COMPONENT_ID = 178 then amount else 0 end ) AS WORKING_HOURS,
                max( case when COMPONENT_ID = 179 then amount else 0 end ) AS NS_HRS,
                max( case when COMPONENT_ID = 180 then amount else 0 end ) AS HL_HRS,
                max( case when COMPONENT_ID = 182 then amount else 0 end ) AS LS_HRS,
                max( case when COMPONENT_ID = 183 then amount else 0 end ) AS STL_D,
                max( case when COMPONENT_ID = 198 then amount else 0 end ) AS WRK_DAYS,
                max( case when COMPONENT_ID = 206 then amount else 0 end ) AS C_WORK_DAY,
                max( case when COMPONENT_ID = 251 then round(amount, 2) else 0 end ) AS PROD_BASIC,
                max( case when COMPONENT_ID = 189 then round(amount, 2) else 0 end ) AS TIME_RATED_BASIC,
                max( case when COMPONENT_ID = 216 then amount else 0 end ) AS FIX_BASIC,
                max( case when COMPONENT_ID = 207 then amount else 0 end ) AS C_BON_ERN,
                max( case when COMPONENT_ID = 134 then amount else 0 end ) AS PF_GROSS,
                max( case when COMPONENT_ID = 212 then amount else 0 end ) AS DA,
                max( case when COMPONENT_ID = 171 then amount else 0 end ) AS NS_AMOUNT,
                max( case when COMPONENT_ID = 109 then amount else 0 end ) AS HOL_AMT,
                max( case when COMPONENT_ID = 217 then amount else 0 end ) AS INCREMENTA,
                max( case when COMPONENT_ID = 221 then amount else 0 end ) AS LAYOFF_WGS,
                max( case when COMPONENT_ID = 219 then amount else 0 end ) AS INCENTIVE_AMOUNT,
                max( case when COMPONENT_ID = 184 then amount else 0 end ) AS MISS_EARN,
                max( case when COMPONENT_ID = 112 then amount else 0 end ) AS STL_WGS,
                max( case when COMPONENT_ID = 20 then amount else 0 end ) AS GROSS_PAY,
                max( case when COMPONENT_ID = 8 then amount else 0 end ) AS HRA,
                max( case when COMPONENT_ID = 223 then amount else 0 end ) AS B_F,
                max( case when COMPONENT_ID = 224 then amount else 0 end ) AS TOTAL_EARN,
                max( case when COMPONENT_ID = 18 then amount else 0 end ) AS EPF,
                max( case when COMPONENT_ID = 231 then amount else 0 end ) AS C_PF_CONT,
                max( case when COMPONENT_ID = 232 then amount else 0 end ) AS C_EPF_CONT,
                max( case when COMPONENT_ID = 134 then round(amount * 8.33 / 100, 0) else 0 end ) AS epf_833,
                max( case when COMPONENT_ID = 134 then round(amount * 1.67 / 100, 0) else 0 end ) AS epf_167,
                max( case when COMPONENT_ID = 149 then amount else 0 end ) AS ESI_GROSS,
                max( case when COMPONENT_ID = 19 then amount else 0 end ) AS ESIC,
                max( case when COMPONENT_ID = 16 then amount else 0 end ) AS P_TAX,
                max( case when COMPONENT_ID = 235 then amount else 0 end ) AS LWF,
                max( case when COMPONENT_ID = 185 then amount else 0 end ) AS CO_LOAN,
                max( case when COMPONENT_ID = 222 then amount else 0 end ) AS CO_LOAN_BAL,
                max( case when COMPONENT_ID = 186 then amount else 0 end ) AS PUJA_ADVANCE,
                max( case when COMPONENT_ID = 187 then amount else 0 end ) AS STL_ADVANCE,
                max( case when COMPONENT_ID = 25 then amount else 0 end ) AS TOTAL_DEDUCTION,
                max( case when COMPONENT_ID = 236 then amount else 0 end ) AS C_F,
                max( case when COMPONENT_ID = 21 then round(amount, 2) else 0 end ) AS NET_PAY,
                max( case when COMPONENT_ID = 239 then amount else 0 end ) AS PFG100,
                max( case when COMPONENT_ID = 245 then amount else 0 end ) AS PF100,
                max( case when COMPONENT_ID = 247 then amount else 0 end ) AS NET100,
                max( case when COMPONENT_ID = 244 then amount else 0 end ) AS TOTAL100,
                max( case when COMPONENT_ID = 243 then amount else 0 end ) AS GROSS_PAY100,
                max( case when COMPONENT_ID = 242 then amount else 0 end ) AS HRA_100
            FROM
                (
                SELECT
                    k.PAYPERIOD_ID,
                    tpp.FROM_DATE,
                    tpp.TO_DATE,
                    tpep.EMPLOYEEID,
                    theod.emp_code eb_no,
                    CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                    COMPONENT_ID,
                    tpc.NAME,
                    AMOUNT,
                    dept_desc department,
                    dm.dept_code,
                    d.desig,
                    d.time_piece,
                    thebd.tbl_hrms_ed_bank_detail_id
                FROM
                    tbl_pay_employee_payroll k
                JOIN vowsls.tbl_pay_employee_payscheme tpep ON
                    tpep.EMPLOYEEID = k.EMPLOYEEID
                LEFT JOIN (
                    select
                        *
                    from
                        vowsls.tbl_hrms_ed_official_details
                    where
                        is_active = 1) theod ON
                    k.EMPLOYEEID = theod.eb_id
                LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON
                    k.EMPLOYEEID = thepd.eb_id
                left join tbl_pay_period tpp on
                    tpp.ID = k.PAYPERIOD_ID
                left join tbl_pay_components tpc on
                    tpc.ID = k.COMPONENT_ID
                left join department_master dm on
                    theod.department_id = dm.dept_id
                left join designation d on
                    theod.designation_id = d.id
                left join (
                    select
                        *
                    from
                        tbl_hrms_ed_bank_details
                    where
                        is_active = 1 )thebd on
                    thebd.eb_id = k.EMPLOYEEID
                WHERE
                    tpp.FROM_DATE = '".$periodfromdate."' 
                    and tpp.TO_DATE = '".$periodtodate."'
                    and k.PAYSCHEME_ID in (151,125)
                    and k.BUSINESSUNIT_ID = 2
                    and k.status = 1
                    and tpp.STATUS <> 4
                    AND theod.is_active = 1 ) g
            GROUP BY
                tpep.EMPLOYEEID,
                eb_no,
                wname,
                desig,
                time_piece,
                department,
                dept_code,
                tbl_hrms_ed_bank_detail_id ) k
        left join (
            select
                *
            from
                tbl_hrms_ed_pf
            where
                is_active = 1 ) thep on
            thep.eb_id = k.EMPLOYEEID
        left join (
            select
                *
            from
                tbl_hrms_ed_esi
            where
                is_active = 1 ) thee on
            thee.eb_id = k.EMPLOYEEID
        left join (
            select
                da.company_id companyid,
                da.eb_id,
                max(dept_code) dept_code
            from
                daily_attendance da
            left join department_master dm on
                da.worked_department_id = dm.dept_id
            where
                attendance_date between '".$periodfromdate."' and '".$periodtodate."'
                and da.company_id = 2
                and is_active = 1
                and attendance_type = 'R'
            group by
                eb_id,
                da.company_id ) dpm on
            dpm.eb_id = k.EMPLOYEEID
        left join department_master dm on
            dpm.dept_code = dm.dept_code
            and dm.company_id = dpm.companyid
        where
            TOTAL_EARN>0
        )  v 	
            
         ";
    }    
    if ($holget==18) {
        $sql=" select eb_no,shift,dept_code,occu_code,t_p,' ' mcnos,rwhrs,owhrs,nwhrs,'$periodtodate' fndate from (
            select eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p,
            case when eb_no>='10000' and eb_no<='18000'  then  1 
             when eb_no>='02000' and eb_no<='03000'  then  2 
             when eb_no>='18001' and eb_no<='21000'  then  3 
             when eb_no>='50001' and eb_no<='55000'  then  3 
             when eb_no>='80000' and eb_no<='85000'  then  4 
            else 0 end ebsrl,          
            max( case when regular_ot = 'R' then whrs else 0 end ) rwhrs,
            max( case when regular_ot = 'O' then whrs else 0 end ) owhrs,
            sum(nwhrs) nwhrs,0 fhrs
            from (
            select eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p,sum(whrs) whrs,sum(nwhrs) nwhrs
            from ( 
            select eb_no,WRK_NAME,attandance_date,shift,regular_ot,om.OCCU_CODE,d.occu_desc,dept_code,dept_desc,t_p,
            case when shift='C' and  regular_ot='R' then sum(working_hrs-idle_hrs) 
            when shift='C' and sum(working_hrs)=7.5 and regular_ot='O' then 8 
            when shift='C' and sum(working_hrs)<>7.5 and regular_ot='O' then sum(working_hrs) 
            when shift<>'C'  then sum(working_hrs) 
            else 0 end whrs, 
            case when shift='C' and sum(working_hrs)=7.5 and regular_ot='R' then 0.5 
            else 0 end nwhrs 
            from EMPMILL12.dailyattview d
            left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =d.occu_id
            where attandance_date between '$periodfromdate' and '$periodtodate'
            and comp_id=2 and cata_id in (3,4,5,6,7,9)
            group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,attandance_date,dept_desc,t_p
            ) g group by eb_no,WRK_NAME,shift,regular_ot,OCCU_CODE,occu_desc,dept_code,dept_desc,t_p
            ) h group by eb_no,WRK_NAME,shift,occu_code,occu_desc,dept_code,dept_desc,t_p
            ) k where dept_code not in ('08','07')
            UNION ALL
           select eb_no,shift,'08' dept_code,occu_code,t_p, amcnos,rwhrs,owhrs,nwhrs,'$periodtodate' fndate  from (
                select eb_no,WRK_NAME,shift,occu_code,t_p,
        case when occu_code<>'55' then mcnos else ' ' end amcnos,
        case when eb_no>='10000' and eb_no<='18000'  then  1 
         when eb_no>='02000' and eb_no<='03000'  then  2 
         when eb_no>='18001' and eb_no<='21000'  then  3 
         when eb_no>='50001' and eb_no<='55000'  then  3 
         when eb_no>='80000' and eb_no<='85000'  then  4 
        else 0 end ebsrl,          
        max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
    select shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
    (
    select attendance_date,spell,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,rwhrs,nwhrs,
        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
    from
        (
    select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_no, concat(worker_name,' ',ifnull(last_name,'')) AS WRK_NAME,da.worked_department_id,worked_designation_id,
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
    from daily_attendance da 
    left join (select * from daily_ebmc_attendance where is_active=1 ) dea 
    on da.daily_atten_id =dea.daily_atten_id 
    left join mechine_master mm on mm.mechine_id =dea.mc_id 
    left join worker_master wm on wm.eb_id =da.eb_id 
    left join designation d on d.id =da.worked_designation_id 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join category_master cm on wm.cata_id =cm.cata_id 
    where da.attendance_date  between '$periodfromdate' and '$periodtodate'
    and da.worked_department_id =8 and wm.cata_id in (3,4,5,6,7,9)	
    and da.is_active =1  and da.company_id=2
    ) g group by attendance_date,spell,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,rwhrs,nwhrs
    ) g group by eb_no,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,mcnos
    ) h
    group by eb_no,WRK_NAME,shift,occu_code,t_p,mcnos
    ) g
     UNION ALL
     select eb_no,shift,'07' dept_code,occu_code,t_p, amcnos,rwhrs,owhrs,nwhrs,'$periodtodate'fndate  from (
                select eb_no,WRK_NAME,shift,occu_code,t_p,
        case when occu_code<>'55' then mcnos else ' ' end amcnos,
        case when eb_no>='10000' and eb_no<='18000'  then  1 
         when eb_no>='02000' and eb_no<='03000'  then  2 
         when eb_no>='18001' and eb_no<='21000'  then  3 
         when eb_no>='50001' and eb_no<='55000'  then  3 
         when eb_no>='80000' and eb_no<='85000'  then  4 
        else 0 end ebsrl,          
        max( case when regular_ot = 'R' then rwhrs else 0 end ) rwhrs,
        max( case when regular_ot = 'O' then rwhrs else 0 end ) owhrs,
        sum(nwhrs) nwhrs,0 fhrs
        from (
    select shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,mcnos,sum(rwhrs) rwhrs,sum(nwhrs) nwhrs from
    (
    select attendance_date,spell,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,rwhrs,nwhrs,
        GROUP_CONCAT(DISTINCT mech_code SEPARATOR '') mcnos
    from
        (
    select da.attendance_date,da.spell,substr(da.spell,1,1) shift,da.eb_no, concat(worker_name,' ',ifnull(last_name,'')) AS WRK_NAME,da.worked_department_id,worked_designation_id,
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
    from daily_attendance da 
    left join (select * from daily_ebmc_attendance where is_active=1 ) dea 
    on da.daily_atten_id =dea.daily_atten_id 
    left join mechine_master mm on mm.mechine_id =dea.mc_id 
    left join worker_master wm on wm.eb_id =da.eb_id 
    left join designation d on d.id =da.worked_designation_id 
    left join EMPMILL12.OCCUPATION_MASTER om on om.vow_occu_id =da.worked_designation_id 
    left join category_master cm on wm.cata_id =cm.cata_id 
    where da.attendance_date  between '$periodfromdate' and '$periodtodate'
    and da.worked_department_id =7 and wm.cata_id in (3,4,5,6,7,9)	
    and da.is_active =1  and da.company_id=2
    ) g group by attendance_date,spell,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,rwhrs,nwhrs
    ) g group by eb_no,shift,eb_no,WRK_NAME,
    regular_ot,occu_code,t_p,mcnos
    ) h
    group by eb_no,WRK_NAME,shift,occu_code,t_p,mcnos
    ) g
     ";
//echo 'sql-'.$sql;
     
    }  
        //  echo  $sql;   
   
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


///////////// END DATA///////////////////////////////

///////////// EJM WIND Prod Data 30.04.24////////////////
//////////////////////////////// EJM ATTENDANCE DATA-30.04.24///////////////////////
public function getejmwinddata($periodfromdate,$periodtodate,$att_payschm,$payschemeName,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
//echo 'holget-'.$holget;
     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==19) {
        $sql="select eb_no,deptcode,occu_code,shift,wage_code,prod,'$periodtodate' fndate from (
            select concat(deptcode,eb_no,shift,occu_code,wage_code,LPAD(prod, 4, '0'),
                        '00000000000000000000000000000','".$fndate."') prods,eb_no,deptcode,occu_code,shift,wage_code,prod from (
                        select b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE,case when deptcode='06' then round(sum(production)/14,0) else sum(production) end prod from
                        (select  tran_date,substr(spell,1,1) shift,eb_no,wnd_q_code,sum(prod) production
                        from EMPMILL12.allwindingdata a where tran_date between  '$periodfromdate' and '$periodtodate'
                         and company_id =2 
                        group by tran_date,substr(spell,1,1),eb_no,wnd_q_code 
                        ) a 
                        left join EMPMILL12.winding_wages_link b on a.wnd_q_code=b.wnd_q_code
                        group by b.deptcode,eb_no,shift,b.wage_code,b.OCCU_CODE
                        ) g where substr(eb_no,1,1) in ('1','0','5','8') and occu_code<>'55'
            ) g               order by deptcode,shift,eb_no
            
     ";
        }  
        //  echo  $sql;   
   
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}


///////////// END DATA///////////////////////////////

public function getattincData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
 //   echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
if ($holget==21) {
     $sql=" select DEPT_CODE,DEPARTMENT,
     sum(attincn) as attincn
    from (
           SELECT
               eb_no,
               wname,
               dept_code,
               department,
                  max( case when COMPONENT_ID = 248 then amount else 0 end ) AS attincn
                   FROM
               (
               SELECT
                   k.PAYPERIOD_ID,
                   tpp.FROM_DATE,
                   tpp.TO_DATE,
                   tpep.EMPLOYEEID,
                   theod.emp_code eb_no,
                   CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
                   COMPONENT_ID,
                   tpc.NAME,
                   AMOUNT,
                   dept_desc department,
                   dm.dept_code
               FROM
                   tbl_pay_employee_payroll k
                   JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.EMPLOYEEID
               LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.EMPLOYEEID = theod.eb_id
               LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.EMPLOYEEID = thepd.eb_id
               left join tbl_pay_period tpp on tpp.ID=k.PAYPERIOD_ID
               left join	tbl_pay_components tpc on tpc.ID=k.COMPONENT_ID
               left join	department_master dm on theod.department_id = dm.dept_id
               WHERE
                    k.COMPONENT_ID in (248)
                   and amount>0
                   AND tpp.FROM_DATE ='".$periodfromdate."' and tpp.TO_DATE ='".$periodtodate."'
               AND k.PAYSCHEME_ID in (125,151)
               AND k.BUSINESSUNIT_ID =".$comp."
               AND k.status = 1
               AND tpp.STATUS <> 4
               AND theod.is_active = 1
                   ) g
           GROUP BY
               eb_no,
               wname,
               department,
               dept_code
               ) k 
                group by 
               dept_code,
               department
               ORDER BY
           dept_code,
               department"; 
   // echo 'new '.$sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}
}

public function getptaxsummary($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

     $attp="('R','O')";
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
    if ($holget==15) {
     $sql=" SELECT
     CASE
         WHEN PTAX_EARN BETWEEN 1 AND 10000.99 THEN     '0     TO 10000'
         WHEN PTAX_EARN BETWEEN 10001 AND 15000.99 THEN '10001 TO 15000'
         WHEN PTAX_EARN BETWEEN 15001 AND 25000.99 THEN '15001 TO 25000'
         WHEN PTAX_EARN BETWEEN 25001 AND 40000.99 THEN '25001 TO 40000'
         WHEN PTAX_EARN > 40001 THEN '40001+ ABOVE'
         ELSE 'Unknown'
     END AS `range`,
      CASE
         WHEN PTAX_EARN BETWEEN 10001 AND 15000.99 THEN 110.00
         WHEN PTAX_EARN BETWEEN 15001 AND 25000.99 THEN 130.00
         WHEN PTAX_EARN BETWEEN 25001 AND 40000.99 THEN 150.00
          ELSE 0.0000
         END AS `rate`,
     COUNT(*) AS `count`,
     SUM(PTAX_EARN) AS total_ptax_earn,
     SUM(P_TAX) AS totptax
 FROM
     (
     SELECT
         tpep.EMPLOYEEID,
         MAX(CASE WHEN tpep.COMPONENT_ID = 16 THEN tpep.amount ELSE 0 END) AS P_TAX,
         MAX(CASE WHEN tpep.COMPONENT_ID = 234 THEN tpep.amount ELSE 0 END) AS PTAX_EARN
     FROM
         tbl_pay_employee_payroll tpep
     JOIN tbl_pay_period tpp ON
         tpep.PAYPERIOD_ID = tpp.ID
     WHERE
         tpp.FROM_DATE BETWEEN '$periodfromdate' AND '$periodtodate'
         AND tpep.BUSINESSUNIT_ID = 2
         AND tpp.PAYSCHEME_ID IN (151, 125, 161)
         AND tpp.status = 3
     GROUP BY
         tpep.EMPLOYEEID ) g
 where
     PTAX_EARN>0
 GROUP BY
     `range`,
     `rate`
 ORDER BY
     `range`,
     `rate`"; 
   // echo $sql;   
    
    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
}
}



}