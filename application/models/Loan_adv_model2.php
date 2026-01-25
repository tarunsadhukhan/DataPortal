<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Loan_adv_model2 extends CI_Model {

    public function trollydatafill($comp) {
        $sql="select * from trollymst t where company_id=".$comp." and process_type  =2";
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() > 0) {
            return $data;
        } else {
            return array(); // Return an empty array if no results are found
        }
    
    }
     
    public function gettrollydata($comp,$frmid) {
        $sql="select * from trollymst t where company_id=".$comp." and process_type  =2 and trollyid=".$frmid;
    //    echo $sql;
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() > 0) {
            return $data;
        } else {
            return array(); // Return an empty array if no results are found
        }
    
    }
  
    public function updatetrollydata($comp,$frmid,$bwt,$twt) {
        $sql="update trollymst set trolly_weight=".$twt.",basket_weight=".$bwt." where company_id=".$comp." 
        and process_type  =2 and trollyid=".$frmid;
    //    echo $sql;
        $this->db->query($sql);

     
    }
 

    public function getmainFNattincentiveData($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    
    //    $this->db->db_debug = TRUE;
    //CONCAT(thepd.first_name, ' ', IFNULL(thepd.middle_name, ' '), ' ', IFNULL(thepd.last_name, ' ')) AS empname,
    if ($holget==2) { $attp="('R')";}
    if ($holget==3) { $attp="('R','O')";}
    //echo 'choss-'.$holget;
    //echo 'ro-'.$attp;
     
        $sql="SELECT
        k.eb_id,
        theod.emp_code eb_no,
        dpm.dept_code,
        dm.dept_desc,
        CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', 
        IFNULL(trim(thepd.last_name), '')) AS wname,
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
            ) h group by eb_id having sum(wdays+lvdays)>=12
        ) k
        JOIN EMPMILL12.tbl_holiday_att_inc_eligibility thaie ON thaie.eb_id = k.eb_id
        JOIN vowsls.tbl_pay_employee_payscheme tpep ON 	tpep.EMPLOYEEID = k.eb_id
        LEFT JOIN vowsls.tbl_hrms_ed_official_details theod ON 	k.eb_id = theod.eb_id
        LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd ON 	k.eb_id = thepd.eb_id
        left join (  select da.company_id companyid,da.eb_id,max(dept_code) dept_code from daily_attendance da 
            left join department_master dm on da.worked_department_id =dm.dept_id 
            where attendance_date between '".$periodfromdate."' AND '".$periodtodate."'
            and da.company_id =".$comp."  and is_active=1 and attendance_type='R'
            group by eb_id,da.company_id
         	) dpm on dpm.eb_id=k.eb_id     
         		left join department_master dm on dpm.dept_code=dm.dept_code and dm.company_id=dpm.companyid 
                WHERE
        tpep.PAY_SCHEME_ID = ".$att_payschm." 
        AND thaie.att_incn_eligibility = 'Y'
        AND tpep.status = 1
        AND thepd.company_id = ".$comp."
        AND theod.is_active = 1
        AND thaie.is_active = 1
        and incdays>0
        order by dm.dept_code,
        theod.emp_code";


        $sql="select k.employeeid eb_id,eb_no ,department dept_desc,wname ,
        case when incamt<20 then incamt/1 
        else incamt/20 end incdays,0 lbdays,0 elegdays,0 att_inc_rate,incamt 
        ,m.mxdept,dept_code,
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
  
    
    public function getholleavedata($periodfromdate,$periodtodate,$holget) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    
   
        $sql="select emp_code,sum(holiday_hours) holiday_hours from (
        select emp_code,holiday_hours  from tbl_hrms_holiday_transactions thht 
        left join (select * from  holiday_master hm ) hm  ON hm.id =thht.holiday_id 
        left join (select eb_id,emp_code,catagory_id from tbl_hrms_ed_official_details theod where is_active=1) theod on theod.eb_id =thht.eb_id 
        where hm.holiday_date between '".$periodfromdate."' and '".$periodtodate."' and hm.company_id =2 
        and theod.catagory_id in (3,4,5,6,7,9)
        and thht.is_active =1
        and hm.company_id=2
        ) g group by emp_code";

//      echo $sql;   
    
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
  
    public function getstldetleavedata($periodfromdate,$periodtodate,$holget) {

        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');      

        $sql="select theod.emp_code,	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,dept_desc,";
        $sqlm="";
        $stda=substr($periodfromdate, 8, 2);
        $mda=substr($periodtodate, 8, 2);

        for ($i=$stda;$i<=$mda;$i++) {
            $sqlm=$sqlm."SUM(DAY(ltd.leave_date) = ".$i.") AS `".str_pad($i, 2, '0', STR_PAD_LEFT)."`,";
        }

        $sql=$sql. $sqlm."
        COUNT(*) AS total
FROM   leave_tran_details               ltd
LEFT   JOIN leave_transactions          lt   ON lt.leave_transaction_id = ltd.ltran_id
LEFT   JOIN tbl_hrms_ed_personal_details thepd ON lt.eb_id = thepd.eb_id
LEFT   JOIN tbl_hrms_ed_official_details theod ON lt.eb_id = theod.eb_id
LEFT   JOIN department_master           dm   ON dm.dept_id = theod.department_id
WHERE  ifnull(ltd.is_active,1) = 1
  AND  lt.company_id     = 1
  AND  theod.is_active   = 1
  AND  ltd.leave_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
  and lt.status =3 and lt.leave_type_id in (2)
GROUP  BY theod.emp_code, dm.dept_desc, empname
ORDER  BY dm.dept_desc, theod.emp_code";

//echo $periodfromdate.'-'.$periodtodate;
//echo '<br>';
//echo $stda.'-'.$mda;
//echo '<br>';
//echo $sql;

    $query = $this->db->query($sql);
    //    $query = $this->db->get($sql);
     //   echo $this->db->last_query();            
return $query->result_array();      
          
/*         $data=$query->result();
        if ($query->num_rows() > 0) {
       //     var_dump($data);
            return $data;
        } else {
            return array(); // Return an empty array if no results are found
        }
 */


    }


  public function getcanteendetleavedata($periodfromdate,$periodtodate,$holget) {
    $canteenrate=40;
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');      

        $sql="select theod.emp_code,	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,dept_desc,";
        $sqlm="";
      $columns = [];                                // collect pieces first
for ($i = $stda; $i <= $mda; $i++) {

    // 01, 02 … 31  ← alias
    $alias = str_pad($i, 2, '0', STR_PAD_LEFT);

    // SUM(IF(DAY(tran_date)= N, no_of_meals*rate_of_meals, 0)) AS `NN`
    $columns[] =
        "SUM(IF(DAY(ltd.tran_date) = $i, " .
        "         ltd.no_of_meals * $canteenrate, 0)) AS `$alias`";
}

$sqlm = implode(",\n", $columns);  
        $sql=$sql. $sqlm."
           SUM(ltd.no_of_meals*$canteenrate) AS total_amount
FROM   canteen_details               ltd
LEFT   JOIN tbl_hrms_ed_official_details theod ON ltd.tktno = theod.emp_code 
   JOIN tbl_hrms_ed_personal_details thepd ON theod.eb_id = thepd.eb_id and thepd.company_id = ltd.company_id  
LEFT   JOIN department_master           dm   ON dm.dept_id = theod.department_id
WHERE  
   ltd.company_id     = 1
  AND  theod.is_active   = 1
  AND  ltd.tran_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
GROUP  BY theod.emp_code, dm.dept_desc, empname,dept_code
ORDER  BY dm.dept_code, theod.emp_code";
 




$stda = (int)substr($periodfromdate, 8, 2);   // e.g.  1
$mda  = (int)substr($periodtodate, 8, 2);     // e.g. 30

$cols = [];
for ($i = $stda; $i <= $mda; $i++) {
    $alias = str_pad($i, 2, '0', STR_PAD_LEFT);          // 01, 02 …
    $cols[] = "SUM(IF(DAY(ltd.tran_date) = $i, " .
             "         ltd.no_of_meals * $canteenrate, 0)) AS `$alias`";
}
$cols[] = "SUM(ltd.no_of_meals * $canteenrate) AS total_amount";

$selectDayParts = implode(",\n    ", $cols);              // join with commas

$sql = "
SELECT
    theod.emp_code,
    CONCAT(TRIM(thepd.first_name),' ',
           IFNULL(TRIM(thepd.middle_name),''),' ',
           IFNULL(TRIM(thepd.last_name),''))              AS empname,
    dm.dept_desc,
    $selectDayParts
FROM   canteen_details                   ltd
LEFT   JOIN tbl_hrms_ed_official_details theod ON ltd.tktno       = theod.emp_code
JOIN   tbl_hrms_ed_personal_details thepd  ON theod.eb_id     = thepd.eb_id
                                             AND thepd.company_id = ltd.company_id
LEFT   JOIN department_master dm ON dm.dept_id = theod.department_id
WHERE  ltd.company_id  = 1
  AND  theod.is_active = 1
  AND  ltd.tran_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
GROUP BY theod.emp_code, dm.dept_desc, empname          -- add dept_code only if you SELECT it
ORDER BY dm.dept_desc, theod.emp_code
";


//echo $sql;


    $query = $this->db->query($sql);
  return $query->result_array();      
          
 

    }




  public function getattsheetdata($periodfromdate,$periodtodate,$holget,$att_dept) {

//echo $periodfromdate.'  -  '.$periodtodate;

        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');      

echo $att_dept;
//echo $sql;

$stda = (int)substr($periodfromdate, 8, 2);   // e.g.  1
$mda  = (int)substr($periodtodate, 8, 2);     // e.g. 30

//$periodfromdate = '2025-06-01';
//$periodtodate   = '2025-06-30';

// -----------------------------------------------------------------------------
// work out first/last calendar day numbers ( 1 … 31 )
$startDay = (int)substr($periodfromdate, 8, 2);
$endDay   = (int)substr($periodtodate,   8, 2);

// -----------------------------------------------------------------------------
// build 2 parallel column-lists: one for hours, one for attendance_type
$hourCols = [];
$typeCols = [];

for ($d = $startDay; $d <= $endDay; $d++) {
    $alias = str_pad($d, 2, '0', STR_PAD_LEFT);      // 01 … 31

    // hours (pivot)
    $hourCols[] = 
        "MAX(CASE WHEN DAY(a.attendance_date) = $d " .
        "         THEN a.working_hours END) AS `{$alias}`";

    // attendance_type (pivot) – needed only for styling
    $typeCols[] =
        "MAX(CASE WHEN DAY(a.attendance_date) = $d " .
        "         THEN a.spell END) AS `{$alias}_t`";
}

$hoursSelect = implode(",\n        ", $hourCols);
$typeSelect  = implode(",\n        ", $typeCols);


$sql = "
sELECT theod.emp_code,
CONCAT(TRIM(thepd.first_name), ' ', IFNULL(TRIM(thepd.middle_name), ''), ' ', IFNULL(TRIM(thepd.last_name), '')) AS empname,
d.dept_desc,attendance_type atttype,
    $hoursSelect,
    $typeSelect,
    SUM(a.working_hours)                                   AS Total_hrs,
    ROUND(SUM(a.working_hours)/8,2)                                   AS Total_days
FROM
(select a.company_id,a.is_active ,eb_id,a.attendance_date,a.attendance_type , spell, a.worked_department_id,
round(sum(working_hours-a.idle_hours),1) working_hours from daily_attendance a
where company_id=1 and is_active=1 group by a.company_id,a.is_active ,eb_id,a.attendance_date,a.attendance_type , spell, a.worked_department_id ) a 
LEFT JOIN tbl_hrms_ed_official_details theod ON
a.eb_id = theod.eb_id
JOIN tbl_hrms_ed_personal_details thepd ON
theod.eb_id = thepd.eb_id
LEFT JOIN department_master d ON
d.dept_id = a.worked_department_id 
left join master_department md on d.mdept_id=md.mdept_id and d.company_id=md.company_id
WHERE
a.attendance_date BETWEEN '".$periodfromdate."' AND '".$periodtodate."'
AND a.company_id = ".$comp." 
and a.is_active=1
AND theod.is_active = 1
AND thepd.company_id = 1
and md.dept_desc='".$att_dept."' 
GROUP BY
a.worked_department_id,attendance_type,
theod.emp_code,
empname,
d.dept_desc
ORDER BY
a.worked_department_id,attendance_type,emp_code
";

//$result = $this->db->query($sql, [$periodfromdate, $periodtodate])->result_array();

//echo $sql;



    $query = $this->db->query($sql);
  return $query->result_array();      
          
 

    }


    public function getstlleavedata($periodfromdate,$periodtodate,$holget) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    
  
        $sql="select lt.leave_transaction_id,theod.emp_code,	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
        dept_desc,date_format(lt.leave_from_date,'%d-%m-%Y') leave_from_date ,date_format(leave_to_date,'%d-%m-%Y') leave_to_date ,leave_purpose,tsdp.paid  
        from vowsls.leave_transactions lt 
        left join EMPMILL12.tbl_stl_days_payment tsdp on lt.leave_transaction_id =tsdp.leave_tran_id 
        left join ( 
        select eb_id,emp_code  from tbl_hrms_ed_official_details theod where is_active =1
        ) theod on lt.eb_id=theod.eb_id
        left join tbl_hrms_ed_personal_details thepd on lt.eb_id=thepd.eb_id
        left join department_master dm on theod.department_id =dm.dept_id
        where lt.company_id =2 and leave_type_id =24 and lt.status =3
        and tsdp.paid is null order by lt.leave_transaction_id
        ";

//      echo $sql;   
    
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
   

    public function stlupload($periodfromdate,$periodtodate,$holget) {
        
        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');
    
  
        $sql="select lt.leave_transaction_id,theod.emp_code,	CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
        date_format(lt.leave_from_date,'%d-%m-%Y') leave_from_date ,date_format(leave_to_date,'%d-%m-%Y') leave_to_date ,leave_purpose,tsdp.paid  
        from vowsls.leave_transactions lt 
        left join EMPMILL12.tbl_stl_days_payment tsdp on lt.leave_transaction_id =tsdp.leave_tran_id 
        left join ( 
        select eb_id,emp_code  from tbl_hrms_ed_official_details theod where is_active =1
        ) theod on lt.eb_id=theod.eb_id
        left join tbl_hrms_ed_personal_details thepd on lt.eb_id=thepd.eb_id
        where lt.company_id =2 and leave_type_id =24 and lt.status =3
        and tsdp.paid is null order by lt.leave_transaction_id
        ";

//      echo $sql;   
    
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



    /////////// Date Range Data Table for Main Payroll & 18-PF/////////////
    public function getmainvcpayrollexceldrg($periodfromdate, $periodtodate, $att_payschm, $holget)
    {

        // Replace this with your actual database query to fetch MCCodes based on department
        $company_name = $this->session->userdata('companyname');
        $comp = $this->session->userdata('companyId');

        $attp = "('R','O')";
        //echo 'choss-'.$holget;
        //echo 'ro-'.$attp;
        if ($holget == 30) {
            $sql = "SELECT 
     k.EB_NO, 
     k.wname, 
     k.*,
      k.NET_PAY + ot_net_amount+INCENTIVE_AMOUNT AS TOTAL_AMT,
      k.PROD_BASIC+ k.TIME_RATED_BASIC AS TOTAL_BASIC
 FROM (
     SELECT 
         tpep.EMPLOYEEID, 
         theod.emp_code AS EB_NO, 
         CONCAT(
             TRIM(thepd.first_name), ' ', 
             IFNULL(TRIM(thepd.middle_name), ''), ' ', 
             TRIM(thepd.last_name)
         ) AS wname, 
         MAX(CASE WHEN COMPONENT_ID = 178 THEN amount ELSE 0 END) AS WORKING_HOURS,
        MAX(CASE WHEN COMPONENT_ID = 180 THEN amount ELSE 0 END) AS HL_HRS,
        MAX(CASE WHEN COMPONENT_ID = 179 THEN amount ELSE 0 END) AS NS_HRS,
        MAX(CASE WHEN COMPONENT_ID = 183 THEN amount ELSE 0 END) AS STL_D,
        MAX(CASE WHEN COMPONENT_ID = 251 THEN ROUND(amount, 2) ELSE 0 END) AS PROD_BASIC,
        MAX(CASE WHEN COMPONENT_ID = 189 THEN ROUND(amount, 2) ELSE 0 END) AS TIME_RATED_BASIC,
        MAX(CASE WHEN COMPONENT_ID = 212 THEN amount ELSE 0 END) AS DA,
        MAX(CASE WHEN COMPONENT_ID = 216 THEN amount ELSE 0 END) AS FIX_BASIC, 
        MAX(CASE WHEN COMPONENT_ID = 109 THEN amount ELSE 0 END) AS HOL_AMT, 
        MAX(CASE WHEN COMPONENT_ID = 171 THEN amount ELSE 0 END) AS NS_AMOUNT,
        MAX(CASE WHEN COMPONENT_ID = 8 THEN amount ELSE 0 END) AS HRA, 
        MAX(CASE WHEN COMPONENT_ID = 112 THEN amount ELSE 0 END) AS STL_WGS,
        MAX(CASE WHEN COMPONENT_ID = 134 THEN amount ELSE 0 END) AS PF_GROSS, 
        MAX(CASE WHEN COMPONENT_ID = 18 THEN amount ELSE 0 END) AS EPF,
        MAX(CASE WHEN COMPONENT_ID = 149 THEN amount ELSE 0 END) AS ESI_GROSS, 
        MAX(CASE WHEN COMPONENT_ID = 19 THEN amount ELSE 0 END) AS ESIC,
        MAX(CASE WHEN COMPONENT_ID = 16 THEN amount ELSE 0 END) AS P_TAX,
        MAX(CASE WHEN COMPONENT_ID = 166 THEN amount ELSE 0 END) AS ADVANCE,
        MAX( case when COMPONENT_ID = 224 THEN amount else 0 end ) AS TOTAL_EARNING,
        MAX( case when COMPONENT_ID = 184 THEN amount else 0 end ) AS MISS_EARNING, 
        MAX(CASE WHEN COMPONENT_ID = 25 THEN amount ELSE 0 END) AS GROSS_DED, 
        MAX(CASE WHEN COMPONENT_ID = 21 THEN ROUND(amount, 2) ELSE 0 END) AS NET_PAY,
        MAX(CASE WHEN COMPONENT_ID = 135 THEN amount ELSE 0 END) AS OT_HOURS,
        MAX( case when COMPONENT_ID = 284 THEN amount else 0 end ) AS OT_ADVANCE,
        MAX(CASE WHEN COMPONENT_ID = 237 THEN amount ELSE 0 END) AS OVERTIME_PAY,
        MAX(CASE WHEN COMPONENT_ID = 248 THEN amount ELSE 0 END) AS INCENTIVE_AMOUNT,
        MAX( case when COMPONENT_ID = 286 then amount else 0 end ) AS ot_net_amount,
        0 AS Misc_deduction,
        0 As Round_off
          
         FROM (
         SELECT 
             EMPLOYEEID, 
             COMPONENT_ID, 
             SUM(amount) AS amount 
         FROM 
             tbl_pay_employee_payroll k 
         LEFT JOIN 
             tbl_pay_period tpp ON k.PAYPERIOD_ID = tpp.ID 
         WHERE 
             tpp.FROM_DATE BETWEEN '" . $periodfromdate . "' AND '" . $periodtodate . "'
             AND tpp.STATUS <> 4 
             AND k.STATUS <> 4 
             AND k.PAYSCHEME_ID = '" . $att_payschm . "'
             AND k.BUSINESSUNIT_ID = 2 
         GROUP BY 
             EMPLOYEEID, COMPONENT_ID 
     ) AS subquery 
     JOIN 
         vowsls.tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = subquery.EMPLOYEEID 
     JOIN 
         vowsls.tbl_hrms_ed_official_details theod ON subquery.EMPLOYEEID = theod.eb_id 
     JOIN 
         vowsls.tbl_hrms_ed_personal_details thepd ON subquery.EMPLOYEEID = thepd.eb_id 
     JOIN 
         tbl_pay_components tpc ON tpc.ID = subquery.COMPONENT_ID 
    
     GROUP BY 
         tpep.EMPLOYEEID, 
         EB_NO, 
         wname 
        ) k 
 WHERE 
     k.NET_PAY+ot_net_amount > 0 
 ORDER BY 
     k.EB_NO;
 ";
            // echo $sql;   

            $query = $this->db->query($sql);
            //    $query = $this->db->get($sql);
            //   echo $this->db->last_query();            


            $data = $query->result();
            if ($query->num_rows() > 0) {
                //     var_dump($data);
                return $data;
            } else {
                return array(); // Return an empty array if no results are found
            }
        }
    }


    //////////// End this Reports //////////////////////



}