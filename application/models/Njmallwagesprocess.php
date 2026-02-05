<?php
class Njmallwagesprocess extends CI_Model
{



public function getcewagespayslip($periodfromdate,$periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $companyId = $this->session->userdata('companyId');
    $comp=1;
    $branch_id=4 ;   
    $payid=0;
		$payscheme_id =$att_payschm;
		$from_date = $periodfromdate;
		$to_date = $periodtodate;
	
		$sql="select * from tbl_pay_period tpp where from_date='".$from_date."' and TO_DATE ='".$to_date."' 
		and PAYSCHEME_ID =".$payscheme_id."
		and company_id=".$companyId." and branch_id=".$branch_id." and STATUS not in (4)";
//echo $sql;
		$query = $this->db->query($sql);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
	 	   $payid=$record->ID;
		}	

//echo 'payid: '.$payid.' payscheme_id: '.$payscheme_id;

		$sql="";
		$sql1="";
	   $sqlmhd="";
	   $sqlfhd="";			 
		$sqlhd="select
		tppc.payslip_order,tppc.component_id,
		tppc.desc_print,tpc.CODE
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
		tps.ID =".$payscheme_id."
		AND tppc.company_id =".$companyId."
		AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id=0
	ORDER BY
		tppc.payscheme_id,
		payslip_order";
//	 echo 'aoaoao'.$sqlhd;

		$query = $this->db->query($sqlhd);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
		   $id=$record->payslip_order;
		   $des=$record->desc_print;
		   $des=$record->CODE;
		  if ($id>0) { 
		   if ($id==1)  {
				   $sqlmhd=$sqlmhd."eb_no".",";
				   $sqlfhd=$sqlfhd.'eb_no'.",";
		   }	
		   if ($id==2)  {
				   $sqlmhd=$sqlmhd."emp_name ".",";
				   $sqlfhd=$sqlfhd.'emp_name'.",";
		   }
		   if ($id==3)  {
				   $sqlmhd=$sqlmhd."dept_code ".",";
				   $sqlfhd=$sqlfhd.'dept_code'.",";
		   }		
		   if ($id==4)  {
			   $sqlmhd=$sqlmhd."department ".",";
			   $sqlfhd=$sqlfhd.'department'.",";

		   }			//		break;
		   if ($id==5)  {
			   $sqlmhd=$sqlmhd."designation".",";
			   $sqlfhd=$sqlfhd.'designation'.",";
		   }	
		   if ($id==6)  {
			   $sqlmhd=$sqlmhd."esi_no ".",";
			   $sqlfhd=$sqlfhd.'esi_no'.",";
		   }
		   if ($id==7)  {
			   $sqlmhd=$sqlmhd."uan_no ".",";
			   $sqlfhd=$sqlfhd.'uan_no'.",";
		   }
   
			   
			 }	
 
		}
//echo $sqlmhd;

		$sqlp="select ".$sqlmhd."eb_id ";
		$sqlda="select
		tppc.payslip_order,tppc.component_id,
		case when tppc.payslip_order=1 then 'eb_no'
		when tppc.payslip_order=2 then 'emp_name' 
		when tppc.payslip_order=3 then 'dept_code' 
		else tpc.CODE end CODE,
		tppc.desc_print
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
	tps.ID =".$payscheme_id."
	AND tppc.company_id =".$companyId."
	AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id>0
	ORDER BY
		tppc.payscheme_id,
		payslip_order";
	
		$query = $this->db->query($sqlda);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
					   

		$id=$record->component_id;
		$des=$record->desc_print;
		$des=$record->CODE;
		if ($id>0) { 
			$sqlp=$sqlp.","."max( case when COMPONENT_ID=".$id." then amount else 0 end ) `".$des."`";
		}
		//$sheet->getCellByColumnAndRow('3', $x)->getValue();
		
		
		
		}
        
		$sqlp=$sqlp." from (
		   SELECT
		   tpep.PAYPERIOD_ID,
		   tpp.FROM_DATE,
		   tpp.TO_DATE,
		   tpep.EMPLOYEEID AS eb_id,
		   theod.emp_code AS eb_no,
		   CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS emp_name,
		   tpep.COMPONENT_ID,
		   tpc.NAME,
		   thee.esi_no,
		   thep.pf_uan_no uan_no,
		   AMOUNT,
		   dm.dept_code,
		   dm.dept_desc AS department,
		   dsg.desig AS designation
	   FROM
		   tbl_pay_employee_payroll tpep
	   LEFT JOIN tbl_hrms_ed_personal_details thepd ON tpep.EMPLOYEEID = thepd.eb_id
	   LEFT JOIN (SELECT * FROM tbl_hrms_ed_official_details WHERE is_active = 1) theod ON tpep.EMPLOYEEID = theod.eb_id
	   LEFT JOIN (SELECT * FROM tbl_hrms_ed_pf WHERE is_active = 1) thep ON tpep.EMPLOYEEID = thep.eb_id
	   LEFT JOIN (SELECT * FROM tbl_hrms_ed_esi WHERE is_active = 1) thee ON tpep.EMPLOYEEID = thee.eb_id
	   LEFT JOIN (SELECT * FROM tbl_pay_period tpp WHERE status <> 4) tpp ON tpep.PAYPERIOD_ID = tpp.ID
	   LEFT JOIN tbl_pay_components tpc ON tpep.COMPONENT_ID = tpc.ID
	   LEFT JOIN department_master dm ON dm.dept_id = theod.department_id
	   LEFT JOIN designation dsg ON dsg.id = theod.designation_id
	   WHERE
		   tpep.PAYPERIOD_ID=".$payid."
	   ) g
		where PAYPERIOD_ID=".$payid."  group by PAYPERIOD_ID,".$sqlfhd."eb_id ORDER BY dept_code,eb_no";

//echo $sqlp;
         $query = $this->db->query($sqlp);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }





public function getcntwagespayslip($periodfromdate,$periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
        $sql=" WITH numbered_rows AS (
  SELECT 
    ROW_NUMBER() OVER (ORDER BY theod.emp_code) AS rn,
    theod.emp_code AS ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    CONCAT(MONTHNAME('$periodfromdate'), ' ', YEAR('$periodtodate')) AS month,
    MAX(CASE WHEN component_id=280 THEN amount ELSE 0 END) AS otday,
    MAX(CASE WHEN component_id=282 THEN amount ELSE 0 END) AS regday,
    MAX(CASE WHEN component_id=70 THEN amount ELSE 0 END) AS trate,
    MAX(CASE WHEN component_id=166 THEN amount ELSE 0 END) AS advance,
    MAX(CASE WHEN component_id=76 THEN amount ELSE 0 END) AS ta,
    MAX(CASE WHEN component_id=19 THEN amount ELSE 0 END) AS esi,
    MAX(CASE WHEN component_id=18 THEN amount ELSE 0 END) AS pf,
    MAX(CASE WHEN component_id=268 THEN amount ELSE 0 END) AS plus_balance,
    MAX(CASE WHEN component_id=21 THEN amount ELSE 0 END) AS net
  FROM tbl_pay_employee_payroll tpep
  LEFT JOIN tbl_pay_period tpp ON tpep.PAYPERIOD_ID = tpp.ID AND tpp.IS_ACTIVE = 1 AND tpp.STATUS NOT IN (4)
  LEFT JOIN tbl_hrms_ed_personal_details thepd ON tpep.EMPLOYEEID = thepd.eb_id
  LEFT JOIN tbl_hrms_ed_official_details theod ON tpep.EMPLOYEEID = theod.eb_id AND theod.is_active = 1
  WHERE tpep.STATUS = 1 
    AND tpep.PAYSCHEME_ID = $att_payschm
    AND tpp.FROM_DATE = '$periodfromdate'
    AND tpp.TO_DATE = '$periodtodate' 
  GROUP BY theod.emp_code, thepd.first_name, thepd.middle_name, thepd.last_name

)
SELECT 
  r1.ticket_no AS ticket_no_1,
  r1.emp_name AS emp_name_1,
  r1.otday AS otday_1,
  r1.regday AS regday_1,
  r1.trate AS trate_1,
  r1.advance AS advance_1,
  r1.ta AS ta_1,
  r1.esi AS esi_1,
  r1.pf AS pf_1,
  r1.plus_balance AS plus_balance_1,
  r1.net AS net_1,
  r2.ticket_no AS ticket_no_2,
  r2.emp_name AS emp_name_2,
  r2.otday AS otday_2,
  r2.regday AS regday_2,
  r2.trate AS trate_2,
  r2.advance AS advance_2,
  r2.ta AS ta_2,
  r2.esi AS esi_2,
  r2.pf AS pf_2,
  r2.plus_balance AS plus_balance_2,
  r2.net AS net_2
FROM numbered_rows r1
LEFT JOIN numbered_rows r2 ON r2.rn = r1.rn + 1
WHERE MOD(r1.rn, 2) = 1
ORDER BY r1.rn
";

$sql="WITH numbered_rows AS (
  SELECT 
    ROW_NUMBER() OVER (PARTITION BY dm.dept_code ORDER BY theod.emp_code) AS rn,
    theod.emp_code AS ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    dm.dept_code,
    dm.dept_desc,
    CONCAT(SUBSTR(MONTHNAME('$periodfromdate'),1,3), ' ', YEAR('$periodtodate')) AS month,
    MAX(CASE WHEN component_id = 280 THEN amount ELSE 0 END) AS otday,
    MAX(CASE WHEN component_id = 282 THEN amount ELSE 0 END) AS regday,
    MAX(CASE WHEN component_id = 70  THEN amount ELSE 0 END) AS trate,
    MAX(CASE WHEN component_id = 166 THEN amount ELSE 0 END) AS advance,
    MAX(CASE WHEN component_id = 76  THEN amount ELSE 0 END) AS ta,
    MAX(CASE WHEN component_id = 19  THEN amount ELSE 0 END) AS esi,
    MAX(CASE WHEN component_id = 18  THEN amount ELSE 0 END) AS pf,
    MAX(CASE WHEN component_id = 268 THEN amount ELSE 0 END) AS plus_balance,
    MAX(CASE WHEN component_id = 21  THEN amount ELSE 0 END) AS net
  FROM tbl_pay_employee_payroll tpep
  LEFT JOIN tbl_pay_period tpp 
         ON tpep.PAYPERIOD_ID = tpp.ID 
        AND tpp.IS_ACTIVE = 1 
        AND tpp.STATUS NOT IN (4)
  LEFT JOIN tbl_hrms_ed_personal_details thepd 
         ON tpep.EMPLOYEEID = thepd.eb_id
  LEFT JOIN tbl_hrms_ed_official_details theod 
         ON tpep.EMPLOYEEID = theod.eb_id 
        AND theod.is_active = 1
  LEFT JOIN department_master dm 
         ON theod.department_id = dm.dept_id 
  WHERE tpep.STATUS = 1 
    AND tpep.PAYSCHEME_ID = $att_payschm
    AND tpp.FROM_DATE = '$periodfromdate'
    AND tpp.TO_DATE = '$periodtodate' 
  GROUP BY theod.emp_code, thepd.first_name, thepd.middle_name, thepd.last_name, dm.dept_code, dm.dept_desc
  HAVING MAX(CASE WHEN component_id = 21 THEN amount ELSE 0 END) > 0
)
SELECT 
  r1.ticket_no    AS ticket_no_1,
  r1.emp_name     AS emp_name_1,
  r1.dept_code    AS dept_code_1,
  r1.dept_desc    AS dept_desc_1,
  r1.month        AS month_1,
  r1.otday        AS otday_1,
  r1.regday       AS regday_1,
  r1.trate        AS trate_1,
  r1.advance      AS advance_1,
  r1.ta           AS ta_1,
  r1.esi          AS esi_1,
  r1.pf           AS pf_1,
  r1.plus_balance AS plus_balance_1,
  r1.net          AS net_1,
  r2.ticket_no    AS ticket_no_2,
  r2.emp_name     AS emp_name_2,
  r2.dept_code    AS dept_code_2,
  r2.dept_desc    AS dept_desc_2,
  r2.month        AS month_2,
  r2.otday        AS otday_2,
  r2.regday       AS regday_2,
  r2.trate        AS trate_2,
  r2.advance      AS advance_2,
  r2.ta           AS ta_2,
  r2.esi          AS esi_2,
  r2.pf           AS pf_2,
  r2.plus_balance AS plus_balance_2,
  r2.net          AS net_2
FROM numbered_rows r1
LEFT JOIN numbered_rows r2 
       ON r2.rn = r1.rn + 1
      AND r2.dept_code = r1.dept_code   -- ensure same department
WHERE MOD(r1.rn, 2) = 1
ORDER BY r1.dept_code, r1.rn;
";

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




public function getcntprddpayslip($periodfromdate,$periodtodate,$att_payschm,$tkt) {

    $sql_data1 = "select emp_code ticket_no,tncwdc.prod_id quality,tncwdc.production qty, rates prate,tncwdc.prod_amount amount from EMPMILL12.tbl_njm_cnt_wages_data_collection tncwdc 
    left join tbl_hrms_ed_official_details theod on tncwdc.eb_id =theod.eb_id and theod.is_active =1
    where tncwdc.start_date ='$periodfromdate' and tncwdc.end_date ='$periodtodate'
    and production>0  and  emp_code='$tkt'  and tncwdc.is_active =1 order by prod_id";

    $query = $this->db->query($sql_data1);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


public function njmcntwagesprocessdata($periodfromdate,$periodtodate) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $canteenrate=40;
    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');
    $updfor='A';

   //delete prev upload data
    $sql="update EMPMILL12.tbl_njm_cnt_wages_data_collection set is_active=0 where 
    start_date='$periodfromdate' and end_date='$periodtodate'
    and updt_from not in ('M')";
    $this->db->query($sql);


    $sql="INSERT INTO EMPMILL12.tbl_njm_cnt_wages_data_collection (
        start_date, end_date, eb_id, dept_id, desig_id, wrk_hours_reg, wrk_hours_ot,
        wrk_hours_adj, rates, prod_id, production, amount_reg, amount_ot,
        amount_adj, canteen, advance, plus_amount, minus_amount, travel_allowance,
        updt_from, loom_type, time_piece, reg_amount, prod_amount,esi_days
    )
    SELECT
        '$periodfromdate' AS start_date,
        '$periodtodate' AS end_date,
        da.eb_id,
        wm.dept_id,
        da.worked_designation_id,
        SUM(da.rwhrs) rwhrs,
        SUM(da.owhrs) owhrs,
        0 AS wrk_hours_adj,
        0 AS rates,
        0 AS prod_id,
        0 AS production,
        0 AS amount_reg,
        0 AS amount_ot,
        0 AS amount_adj,
        0 AS canteen,
        0 AS advance,
        0 AS plus_amount,
        0 AS minus_amount,
        0 AS travel_allowance,
        'A' AS updt_from,
        NULL AS loom_type,
        dm.time_piece,
        0 AS reg_amount,
        0 AS prod_amount,
        sum(esi.esidays) esidays
    FROM (
        SELECT
            d.eb_id,
            d.eb_no,
            d.worked_designation_id,
            SUM(d.working_hours - d.idle_hours) AS working_hours,
            SUM((d.working_hours - d.idle_hours)/8) AS working_days,
            SUM(CASE WHEN d.attendance_type = 'R' THEN (d.working_hours - d.idle_hours) ELSE 0 END) AS rwhrs,
            SUM(CASE WHEN d.attendance_type = 'O' THEN (d.working_hours - d.idle_hours) ELSE 0 END) AS owhrs
        FROM
            vowsls.daily_attendance d
        WHERE
            d.attendance_date BETWEEN '$periodfromdate' AND '$periodtodate'
            AND d.attendance_type IN ('R','O')
            AND d.is_active = 1
        GROUP BY
            d.eb_id, d.eb_no, d.worked_designation_id
    ) da
    LEFT JOIN vowsls.worker_master wm ON da.eb_id = wm.eb_id
    LEFT JOIN vowsls.designation dm ON da.worked_designation_id = dm.id
	LEFT JOIN (select eb_id,count(distinct(attendance_date)) esidays from vowsls.daily_attendance da 
	where  da.attendance_date BETWEEN '$periodfromdate' AND '$periodtodate'
            AND da.attendance_type IN ('R','O') and company_id=1
            AND da.is_active = 1 and company_id=1 group by eb_id) esi on da.eb_id=esi.eb_id
    WHERE
        wm.cata_id = 14
        AND da.worked_designation_id NOT IN ('1098','1100','1104','1106')
    GROUP BY
        da.eb_id,
        wm.eb_no,
        wm.dept_id,
        da.worked_designation_id
";



        $this->db->query($sql);

// fng prod
    $sql="INSERT INTO EMPMILL12.tbl_njm_cnt_wages_data_collection (
        start_date, end_date, eb_id, dept_id, desig_id, wrk_hours_reg, wrk_hours_ot,
        wrk_hours_adj, rates, prod_id, production, amount_reg, amount_ot,
        amount_adj, canteen, advance, plus_amount, minus_amount, travel_allowance,
        updt_from, loom_type, time_piece, reg_amount, prod_amount,esi_days
    )
    SELECT
        '$periodfromdate' AS start_date,
        '$periodtodate' AS end_date,
        wm.eb_id ,
        wm.dept_id,
        NULL AS desig_id,
        NULL AS wrk_hours_reg,
        NULL AS wrk_hours_ot,
        NULL AS wrk_hours_adj,
        twqm.cnt_rate,
        ptm.process_code,
        fe.production,
        0 AS amount_reg,
        0 AS amount_ot,
        0 AS amount_adj,
        0 AS canteen,
        0 AS advance,
        0 AS plus_amount,
        0 AS minus_amount,
        0 AS travel_allowance,
        'P' AS updt_from,
        NULL AS loom_type,
        'P' AS time_piece,
        0 AS reg_amount,
        (twqm.cnt_rate * fe.production) AS prod_amount,esidays
    FROM (
        SELECT
            fe.eb_no,
            SUM(fe.production) AS production,
            fe.work_type,
            fe.company_id
        FROM
            vowsls.finishing_entries fe
        WHERE
            substr(fe.entry_date,1,10) BETWEEN '$periodfromdate' AND '$periodtodate' and fe.company_id=1 and fe.is_active =1
        GROUP BY
            fe.eb_no, fe.work_type, fe.company_id
    ) fe
    LEFT JOIN vowsls.worker_master wm ON fe.eb_no = wm.eb_no AND wm.company_id = 1
    LEFT JOIN vowsls.process_type_master ptm ON fe.work_type = ptm.process_type_id AND fe.company_id = ptm.company_id
    LEFT JOIN EMPMILL12.tbl_wages_quality_master twqm ON ptm.process_code = twqm.q_code AND ptm.company_id = twqm.company_id
    LEFT JOIN (select eb_no,company_id,count(distinct(substr(entry_date,1,10))) esidays from vowsls.finishing_entries fe
            where fe.entry_date BETWEEN '$periodfromdate' AND '$periodtodate' and company_id=1 and is_active=1 group by eb_no) esi
            on esi.eb_no=fe.eb_no and esi.company_id=fe.company_id
    WHERE
        wm.cata_id = 14
        AND wm.active = 'Y'
        AND fe.company_id = 1
";    


$sql=" INSERT INTO EMPMILL12.tbl_njm_cnt_wages_data_collection (
        start_date, end_date, eb_id, dept_id, desig_id, wrk_hours_reg, wrk_hours_ot,
        wrk_hours_adj, rates, prod_id, production, amount_reg, amount_ot,
        amount_adj, canteen, advance, plus_amount, minus_amount, travel_allowance,
        updt_from, loom_type, time_piece, reg_amount, prod_amount,esi_days
    )   SELECT
        '$periodfromdate' AS start_date,
        '$periodtodate' AS end_date,
        wm.eb_id ,
        wm.dept_id,
        NULL AS desig_id,
        NULL AS wrk_hours_reg,
        NULL AS wrk_hours_ot,
        NULL AS wrk_hours_adj,
        twqm.cnt_rate,
        ptm.process_code,
        fe.production,
        0 AS amount_reg,
        0 AS amount_ot,
        0 AS amount_adj,
        0 AS canteen,
        0 AS advance,
        0 AS plus_amount,
        0 AS minus_amount,
        0 AS travel_allowance,
        'P' AS updt_from,
        NULL AS loom_type,
        'P' AS time_piece,
        0 AS reg_amount,
        (twqm.cnt_rate * fe.production) AS prod_amount,esidays
    FROM (
    select eb_no,sum(tprod) production,work_type,1 company_id  from   (
    select a.*,b.* from (
	select eb_no,substr(fe.entry_date,1,10) entdate,spell, ptm.process_code,(fe.production) tprod,fe.work_type   from finishing_entries fe 
	left join process_type_master ptm on fe.work_type =ptm.process_type_id 
	where substr(fe.entry_date ,1,10) between '$periodfromdate' and '$periodtodate' and fe.is_active =1 and fe.company_id =1
	) a left join ( 	
	select da.eb_no ebno,da.attendance_date ,spell attspell,d.desig,da.working_hours,da.worked_designation_id,da.attendance_type   from daily_attendance da 
	left join designation d on d.id=da.worked_designation_id 
	where da.attendance_date between '$periodfromdate' and '$periodtodate' and da.is_active =1 and da.company_id =1
	) b on a.eb_no=b.ebno and a.entdate=b.attendance_date and a.spell=b.attspell
	) fem where worked_designation_id in ('1098','1100','1104','1106') group by eb_no,fe.work_type 
    ) fe
    LEFT JOIN vowsls.worker_master wm ON fe.eb_no = wm.eb_no AND wm.company_id = 1
    LEFT JOIN vowsls.process_type_master ptm ON fe.work_type = ptm.process_type_id AND fe.company_id = ptm.company_id
    LEFT JOIN EMPMILL12.tbl_wages_quality_master twqm ON ptm.process_code = twqm.q_code AND ptm.company_id = twqm.company_id
    LEFT JOIN (select eb_no,company_id,count(distinct(substr(entry_date,1,10))) esidays from vowsls.finishing_entries fe
            where fe.entry_date BETWEEN '$periodfromdate' AND '$periodtodate' and company_id=1 and is_active=1 group by eb_no) esi
            on esi.eb_no=fe.eb_no and esi.company_id=fe.company_id
    WHERE
        wm.cata_id = 14
        AND wm.active = 'Y'
        AND fe.company_id = 1
        ";

//echo $sql;
$this->db->query($sql);

//canteen





        $sql="INSERT INTO EMPMILL12.tbl_njm_cnt_wages_data_collection (
        start_date, end_date, eb_id, dept_id, desig_id, wrk_hours_reg, wrk_hours_ot,
        wrk_hours_adj, rates, prod_id, production, amount_reg, amount_ot,
        amount_adj, canteen, advance, plus_amount, minus_amount, travel_allowance,
        updt_from, loom_type, time_piece, reg_amount, prod_amount,esi_days
    )  
    SELECT
        '$periodfromdate' AS start_date,
        '$periodtodate' AS end_date,
        wm.eb_id ,
        NULL AS dept_id,
        NULL AS desig_id,
        NULL AS wrk_hours_reg,
        NULL AS wrk_hours_ot,
        NULL AS wrk_hours_adj,
        0 rate,
        null process_code,
        null production,
        0 AS amount_reg,
        0 AS amount_ot,
        0 AS amount_adj,
        sum(no_of_meals*$canteenrate) AS canteen,
        0 AS advance,
        0 AS plus_amount,
        0 AS minus_amount,
        0 AS travel_allowance,
        'C' AS updt_from,
        NULL AS loom_type,
        'T' AS time_piece,
        0 AS reg_amount,
        0 AS prod_amount,0 esidays
     from vowsls.canteen_details cd 
    LEFT JOIN vowsls.worker_master wm ON cd.tktno = wm.eb_no AND cd.company_id =wm.company_id
 	where wm.cata_id =14 and cd.company_id =1 and cd.tran_date between '$periodfromdate' and '$periodtodate'
 	group by wm.eb_id";

        $this->db->query($sql);



     $success='Success';
  
    $data[] = [
        'succes'=> $success 
    ];
    return $data;
   
}



public function njmcntwagesexceldownload($periodfromdate,$periodtodate,$att_payschm) {

$sql="	select emp_code  `Employee Id`,CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS Employee_Name,tps.NAME PayScheme,
    ifnull(C_WORKING_DAYS_,0) C_WORKING_DAYS_,ifnull(C_Pwage_amt_,0) C_Pwage_amt_,ifnull(C_exadvance_,0) C_exadvance_,
    ifnull(C_ARRAMTPLUS_,0) C_ARRAMTPLUS_,
    ifnull(C_ARRAMTMINUS_,0) C_ARRAMTMINUS_,ifnull(C_RATE1OTDAYS_,0) C_RATE1OTDAYS_,ifnull(C_RATE2OTDAYS_,0) C_RATE2OTDAYS_,
    ifnull(C_RATE1REGDAYS_,0) C_RATE1REGDAYS_,ifnull(C_RATE2REGDAYS_,0) C_RATE2REGDAYS_,
    ifnull(C_canteen_,0) C_canteen_,ifnull(C_esi_days_,0) C_esi_days_
    from vowsls.tbl_pay_employee_payscheme tpep 
    left JOIN  (
    select dataa.*,dataa.eb_id ebid from (
    select g.eb_id,C_WORKING_DAYS_,C_Pwage_amt_,C_exadvance_,C_ARRAMTPLUS_,
    C_ARRAMTMINUS_,C_RATE1OTDAYS_,C_RATE2OTDAYS_,C_RATE1REGDAYS_,C_RATE2REGDAYS_,C_canteen_,
    IFNULL((esi.esidays), 0) AS C_esi_days_ from (
    SELECT 
    tncwdc.eb_id,
    DATEDIFF('$periodtodate', '$periodfromdate') + 1 AS C_WORKING_DAYS_,
    IFNULL(SUM(tncwdc.prod_amount), 0) AS C_Pwage_amt_,
    IFNULL(SUM(tncwdc.advance+tncwdc.canteen), 0) AS C_exadvance_,
    IFNULL(SUM(tncwdc.plus_amount), 0) AS C_ARRAMTPLUS_,
    IFNULL(SUM(tncwdc.minus_amount), 0) AS C_ARRAMTMINUS_,
    IFNULL(ROUND(SUM(tncwdc.wrk_hours_ot) / 8, 2), 0) AS C_RATE1OTDAYS_,
    0 AS C_RATE2OTDAYS_,
    IFNULL ( ROUND ( (SUM(tncwdc.wrk_hours_reg + tncwdc.wrk_hours_adj) /8) , 2 ), 0 ) AS C_RATE1REGDAYS_,
    0 AS C_RATE2REGDAYS_,
    IFNULL(SUM(tncwdc.canteen), 0) AS C_canteen_
    FROM vowsls.tbl_pay_employee_payscheme tpep
LEFT JOIN EMPMILL12.tbl_njm_cnt_wages_data_collection tncwdc 
    ON tncwdc.eb_id = tpep.EMPLOYEEID 
LEFT JOIN vowsls.tbl_pay_scheme tps 
    ON tpep.PAY_SCHEME_ID = tps.id
        WHERE 
    tps.id = 163 
    AND tpep.STATUS = 1
    AND tncwdc.start_date = '$periodfromdate' 
    AND tncwdc.end_date = '$periodtodate'
    AND tncwdc.is_active =1
GROUP BY 
	tncwdc.eb_id
	) g    
left join (    select da.eb_id,da.eb_no ,count(distinct(attendance_date)) esidays from vowsls.daily_attendance da 
    left join vowsls.worker_master wm on wm.eb_id=da.eb_id 
	where  da.attendance_date BETWEEN '$periodfromdate' AND '$periodtodate'
            AND da.attendance_type IN ('R','O') and da.company_id=1 
            AND da.is_active = 1  and wm.cata_id=14 GROUP BY
            da.eb_id,da.eb_no order by da.eb_no) esi on g.eb_id=esi.eb_id 
               ) dataa 
                              ) dataa
            on dataa.ebid =tpep.EMPLOYEEID
            left join vowsls.tbl_pay_scheme tps on tps.ID =tpep.PAY_SCHEME_ID 
	LEFT JOIN vowsls.tbl_hrms_ed_official_details theod  
    ON tpep.EMPLOYEEID  = theod.eb_id AND theod.is_active = 1
     LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd  
    	ON tpep.EMPLOYEEID  = thepd.eb_id 
	       where tpep.PAY_SCHEME_ID =163 and tpep.STATUS =1
            ORDER by theod.emp_code
 
"; 

//echo $sql;
    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function njmcntbnkexceldownload($periodfromdate,$periodtodate,$att_payschm) {

$sql="    select theod.emp_code TKTNO,CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS NAME,
    thebd.bank_name BANK_NAME,thebd.ifsc_code IFSC_CODE,thebd.bank_acc_no ACC_NO , tpep.AMOUNT NET_PAY
    from vowsls.tbl_pay_employee_payroll tpep 
    left join vowsls.tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID  and tpp.STATUS =28
    LEFT JOIN vowsls.tbl_hrms_ed_official_details theod  
    ON tpep.EMPLOYEEID  = theod.eb_id AND theod.is_active = 1
    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd  
    	ON tpep.EMPLOYEEID  = thepd.eb_id
    left join vowsls.tbl_hrms_ed_bank_details thebd on tpep.EMPLOYEEID =thebd.eb_id and thebd.is_active =1	
	       where tpp.PAYSCHEME_ID in (163) and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'
	       and tpep.COMPONENT_ID =21 AND tpep.AMOUNT >0
	       order by emp_code

"; 

    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }





public function getnjmcntbnkstatement($periodfromdate,$periodtodate,$att_payschm) {

$sql="    select theod.eb_id,theod.emp_code TKTNO,CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS NAME,
    thebd.bank_name BANK_NAME,thebd.ifsc_code IFSC_CODE,thebd.bank_acc_no ACC_NO , tpep.AMOUNT NET_PAY
    from vowsls.tbl_pay_employee_payroll tpep 
    left join vowsls.tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID  and tpp.STATUS not in (4)
    LEFT JOIN vowsls.tbl_hrms_ed_official_details theod  
    ON tpep.EMPLOYEEID  = theod.eb_id AND theod.is_active = 1
    LEFT JOIN vowsls.tbl_hrms_ed_personal_details thepd  
    	ON tpep.EMPLOYEEID  = thepd.eb_id
    left join vowsls.tbl_hrms_ed_bank_details thebd on tpep.EMPLOYEEID =thebd.eb_id and thebd.is_active =1	
	       where tpp.PAYSCHEME_ID in (163) and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'
	       and tpep.COMPONENT_ID =21 AND tpep.AMOUNT >0
	       order by emp_code

"; 
//	       where tpp.PAYSCHEME_ID in (96,1,96,94,90,24,23,23,21) and tpp.FROM_DATE ='2025-06-01' and tpp.TO_DATE ='2025-06-30'

//echo $sql;
    //    $query = $this->db->get($sql);

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



    
public function get_pay_register_details($periodfromdate,$periodtodate,$EB_NO) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $sql="select tncwdc.* from EMPMILL12.tbl_njm_cnt_wages_data_collection tncwdc
    left join vowsls.tbl_hrms_ed_official_details theod on tncwdc.eb_id =theod.eb_id and theod.is_active =1
    WHERE tncwdc.start_date ='$periodfromdate' and tncwdc.end_date ='$periodtodate'
    and theod.emp_code='C910108' and tncwdc.is_active =1 order by updt_from";
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
public function njmcontpayregisdisp($periodfromdate,$periodtodate,$att_payschm,$holget) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $periodfromdate = $this->input->post('periodfromdate');
    $periodtodate = $this->input->post('periodtodate');
    $att_payschm = $this->input->post('att_payschm');
    $holget = $this->input->post('holget');
    $mdate=substr($periodtodate,8,2).'/'.substr($periodtodate,5,2).'/'.substr($periodtodate,0,4);
    $periodfromdate='2025-07-01';
    $periodtodate='2025-07-31';

    $attp="('R','O')";
    $sql="	SELECT 
        dm.dept_desc AS Department,
        theod.emp_code AS EB_NO, 
        CONCAT(
            TRIM(thepd.first_name), ' ', 
            IFNULL(TRIM(thepd.middle_name), ''), ' ', 
            TRIM(thepd.last_name)
        ) AS wname,
        MAX(CASE WHEN COMPONENT_ID = 70 THEN AMOUNT ELSE 0 END) AS Rate,
        MAX(CASE WHEN COMPONENT_ID = 282 THEN AMOUNT ELSE 0 END) +
        MAX(CASE WHEN COMPONENT_ID = 280 THEN AMOUNT ELSE 0 END) AS Days,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 66 THEN AMOUNT ELSE 0 END), 0) AS Amount,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 7 THEN AMOUNT ELSE 0 END), 0) AS Basic,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 8 THEN AMOUNT ELSE 0 END), 0) AS HRA,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 72 THEN AMOUNT ELSE 0 END), 0) AS Conveyance, 
        ROUND(MAX(CASE WHEN COMPONENT_ID = 9 THEN AMOUNT ELSE 0 END), 0) AS `Other_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 11 THEN AMOUNT ELSE 0 END), 0) AS `Uniform_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 275 THEN AMOUNT ELSE 0 END),0) AS `Medical_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 276 THEN AMOUNT ELSE 0 END), 0) AS Telephone,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 73 THEN AMOUNT ELSE 0 END), 0) AS Education,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 75 THEN AMOUNT ELSE 0 END),0 ) AS Training,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 66 THEN AMOUNT ELSE 0 END), 0) AS GROSS1,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 22 THEN AMOUNT ELSE 0 END), 0) AS `PF_Employer`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 23 THEN AMOUNT ELSE 0 END), 0) AS `ESI_Employer`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 18 THEN AMOUNT ELSE 0 END), 0) AS `PF_Employee`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 19 THEN AMOUNT ELSE 0 END), 0) AS `ESI_Employee`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 349 THEN AMOUNT ELSE 0 END), 0) AS GROSS2,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 0 THEN AMOUNT ELSE 0 END), 0) AS GROSS3,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 166 THEN AMOUNT ELSE 0 END),0) AS Advance,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 76 THEN AMOUNT ELSE 0 END), 0) AS TA,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 268 THEN AMOUNT ELSE 0 END), 0) AS `Plus_Balance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 21 THEN AMOUNT ELSE 0 END), 0) AS NET
    FROM (
         SELECT 
            tpep.EMPLOYEEID, 
            tpep.COMPONENT_ID, 
            SUM(tpep.AMOUNT) AS AMOUNT
        FROM tbl_pay_employee_payroll tpep
        LEFT JOIN tbl_pay_period tpp ON tpep.PAYPERIOD_ID = tpp.ID
        WHERE 
            tpp.FROM_DATE BETWEEN '$periodfromdate' AND '$periodtodate'
            AND tpp.STATUS <> 4
            AND tpep.STATUS <> 4
            AND tpep.PAYSCHEME_ID = 163
            AND tpep.BUSINESSUNIT_ID = 1
        GROUP BY 
            tpep.EMPLOYEEID, 
            tpep.COMPONENT_ID
    ) AS subquery
    JOIN vowsls.tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = subquery.EMPLOYEEID
    JOIN vowsls.tbl_hrms_ed_official_details theod ON subquery.EMPLOYEEID = theod.eb_id
    JOIN vowsls.tbl_hrms_ed_personal_details thepd ON subquery.EMPLOYEEID = thepd.eb_id
    JOIN tbl_pay_components tpc ON tpc.ID = subquery.COMPONENT_ID
    LEFT JOIN vowsls.department_master dm ON theod.department_id = dm.dept_id 
    GROUP BY 
        tpep.EMPLOYEEID, 
        EB_NO, 
        wname,
        dm.dept_desc
       
";
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
   

public function njmcntpayexceldownload($periodfromdate,$periodtodate,$att_payschm) {

    $sql="	SELECT 
        dm.dept_desc AS Department,
        theod.emp_code AS EB_NO, 
        CONCAT(
            TRIM(thepd.first_name), ' ', 
            IFNULL(TRIM(thepd.middle_name), ''), ' ', 
            TRIM(thepd.last_name)
        ) AS wname,
        MAX(CASE WHEN COMPONENT_ID = 70 THEN AMOUNT ELSE 0 END) AS Rate,
        MAX(CASE WHEN COMPONENT_ID = 282 THEN AMOUNT ELSE 0 END) +
        MAX(CASE WHEN COMPONENT_ID = 280 THEN AMOUNT ELSE 0 END) AS Days,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 66 THEN AMOUNT ELSE 0 END), 0) AS Amount,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 7 THEN AMOUNT ELSE 0 END), 0) AS Basic,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 8 THEN AMOUNT ELSE 0 END), 0) AS HRA,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 72 THEN AMOUNT ELSE 0 END), 0) AS Conveyance, 
        ROUND(MAX(CASE WHEN COMPONENT_ID = 9 THEN AMOUNT ELSE 0 END), 0) AS `Other_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 11 THEN AMOUNT ELSE 0 END), 0) AS `Uniform_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 275 THEN AMOUNT ELSE 0 END),0) AS `Medical_Allowance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 276 THEN AMOUNT ELSE 0 END), 0) AS Telephone,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 73 THEN AMOUNT ELSE 0 END), 0) AS Education,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 75 THEN AMOUNT ELSE 0 END),0 ) AS Training,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 66 THEN AMOUNT ELSE 0 END), 0) AS GROSS1,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 22 THEN AMOUNT ELSE 0 END), 0) AS `PF_Employer`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 23 THEN AMOUNT ELSE 0 END), 0) AS `ESI_Employer`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 18 THEN AMOUNT ELSE 0 END), 0) AS `PF_Employee`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 19 THEN AMOUNT ELSE 0 END), 0) AS `ESI_Employee`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 349 THEN AMOUNT ELSE 0 END), 0) AS GROSS2,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 0 THEN AMOUNT ELSE 0 END), 0) AS GROSS3,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 166 THEN AMOUNT ELSE 0 END),0) AS Advance,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 76 THEN AMOUNT ELSE 0 END), 0) AS TA,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 268 THEN AMOUNT ELSE 0 END), 0) AS `Plus_Balance`,
        ROUND(MAX(CASE WHEN COMPONENT_ID = 21 THEN AMOUNT ELSE 0 END), 0) AS NET
    FROM (
         SELECT 
            tpep.EMPLOYEEID, 
            tpep.COMPONENT_ID, 
            SUM(tpep.AMOUNT) AS AMOUNT
        FROM tbl_pay_employee_payroll tpep
        LEFT JOIN tbl_pay_period tpp ON tpep.PAYPERIOD_ID = tpp.ID
        WHERE 
            tpp.FROM_DATE BETWEEN '$periodfromdate' AND '$periodtodate'
            AND tpp.STATUS <> 4
            AND tpep.STATUS <> 4
            AND tpep.PAYSCHEME_ID = 163
            AND tpep.BUSINESSUNIT_ID = 1
        GROUP BY 
            tpep.EMPLOYEEID, 
            tpep.COMPONENT_ID
    ) AS subquery
    JOIN vowsls.tbl_pay_employee_payscheme tpep ON tpep.EMPLOYEEID = subquery.EMPLOYEEID and tpep.STATUS=1
    JOIN vowsls.tbl_hrms_ed_official_details theod ON subquery.EMPLOYEEID = theod.eb_id and theod.is_active = 1
    JOIN vowsls.tbl_hrms_ed_personal_details thepd ON subquery.EMPLOYEEID = thepd.eb_id 
    JOIN tbl_pay_components tpc ON tpc.ID = subquery.COMPONENT_ID
    LEFT JOIN vowsls.department_master dm ON theod.department_id = dm.dept_id 
    GROUP BY 
        tpep.EMPLOYEEID, 
        EB_NO, 
        wname,
        dm.dept_desc
       
";

//echo $sql;
    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function njmwagesprocessdata($periodfromdate,$periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $canteenrate=40;
    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');

    $yrmn=substr($periodtodate,0,4).substr($periodtodate,5,2);
     
    // Use active record or query builder to build the query
    $sql="update EMPMILL12.tbl_njm_wages_data_collection set is_active=0 where date_from='".$periodfromdate."'
    and date_to='".$periodtodate."' and update_for not in ('V','FA','OTH') and is_active=1 and payscheme_id= $att_payschm";
    $this->db->query($sql);
 
    //attendance data
    $updfor='ATT';
    $sql="insert into EMPMILL12.tbl_njm_wages_data_collection 
        (eb_id,date_from,date_to,hours_wkd_1,hours_wkd_2,piece_hours,extra_hours_t,extra_hours_p,
        c_shift_days,esi_days,is_active,dept_code,update_for,payscheme_id ) 
        select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt,T1,T2,PH,OTT,OTP,NS,ED,1,
        dept_code,'".$updfor."',$att_payschm  from 
                (
        select dept_code,eb_id,eb_no,wrk_name,
        max( case when (time_piece = 'T' and REGULAR_OT='R' and piece_rate_type<>2 )  then wkhrs else 0 end ) AS T1,
        max( case when (time_piece = 'T' and REGULAR_OT='R' and piece_rate_type=2 )  then wkhrs else 0 end ) AS T2,
        max( case when (time_piece = 'P' and REGULAR_OT='R'  )  then wkhrs else 0 end ) AS PH,
        max( case when (time_piece = 'T' and REGULAR_OT='O'  )  then wkhrs else 0 end ) AS OTT,
        max( case when (time_piece = 'P' and REGULAR_OT='O'  )  then wkhrs else 0 end ) AS OTP,
        max( case when (time_piece = 'N'   )  then wkhrs else 0 end ) AS NS,
        max( case when (time_piece = 'E'   )  then wkhrs else 0 end ) AS ED,$yrmn yearmn FROM (
        SELECT dept_code,wm.eb_id,eb_no,time_piece,piece_rate_type,wkhrs,REGULAR_OT,lay_off_hours,concat(wm.worker_name,' ',ifnull(middle_name,''),' ',ifnull(last_name,'')) wrk_name
        FROM ( 
        SELECT worked_department_id,eb_id,'N' AS time_piece,0 AS piece_rate_type,SUM(NSHFT) AS wkhrs,'E' REGULAR_OT,0 lay_off_hours FROM ( 
        SELECT da.worked_department_id,da.eb_id,attendance_type ,da.attendance_date ,SUM(da.working_hours-idle_hours) AS HRS,
        SUM(da.working_hours-idle_hours)/8 AS NSHFT
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
        WHERE     da.attendance_date >='".$periodfromdate."'
        and da.attendance_date <='".$periodtodate."'
        and da.company_id= 1 and da.is_active=1
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
        AND     da.attendance_date >='".$periodfromdate."'
        and da.attendance_date <='".$periodtodate."'
        and da.company_id= 1  and da.is_active=1      
        GROUP BY worked_department_id,da.eb_id,d.time_piece,d.piece_rate_type,da.attendance_type
        ) v,worker_master wm, department_master dm WHERE 
        v.eb_id=wm.eb_id and v.worked_department_id=dm.dept_id and wm.cata_id in (15,16,17,20,21)
        )  k  group by dept_code,eb_id,eb_no,wrk_name
        ) g 		join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 ";
//    echo $sql;
        $this->db->query($sql);

        
//advance data
$updfor='ADV';
 $sql="insert into EMPMILL12.tbl_njm_wages_data_collection 
    (eb_id,date_from,date_to,installment_advance,
    is_active,update_for,payscheme_id ) 
    select  tca.eb_id, '$periodfromdate'  df,'$periodtodate' dt,installment_amount,
      1 act,'ADV',$att_payschm  payscm  from EMPMILL12.tbl_company_advance tca 
	  left join (select eb_no,eb_id,sum(working_hours-idle_hours) whrs from daily_attendance da
	  where company_id=1 and da.attendance_date  between '$periodfromdate' and '$periodtodate'
	  and da.is_active=1 group by eb_no,eb_id) da on da.eb_id=tca.eb_id
	  left join vowsls.tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =tca.eb_id and tpep.PAY_SCHEME_ID =$att_payschm and tpep.STATUS =1
	  where tca.advance_date ='2026-01-05'
	  and tca.advance_amount >0
	  and da.eb_id is not null and tpep.PAY_SCHEME_ID is not null";
        $this->db->query($sql);




 //leave data
 $updfor='LEAVE';
 $sql="insert into EMPMILL12.tbl_njm_wages_data_collection 
    (eb_id,date_from,date_to,festival_hours,sl_days,ul_days,el_days,canteen,
    is_active,dept_code,update_for,payscheme_id,esi_days ) 
        select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt,fl,sl,ul,el,ml,1,dept_code,'".$updfor."',$att_payschm,
        fl/8+el ed  from ( 
        select 47 dpt,eb_no,wrk_name,dept_desc,
        max( case when (lv_type = 'FL' )  then mhrs else 0 end ) AS fl,
        max( case when (lv_type = 'SL' )  then msld else 0 end ) AS sl,
        max( case when (lv_type = 'UL' )  then msld else 0 end ) AS ul,
        max( case when (lv_type = 'EL' )  then msld else 0 end ) AS el,
        max( case when (lv_type = 'SS' )  then msld else 0 end ) AS ss,
        max( case when (lv_type = 'ML' )  then msld else 0 end ) AS ml, $yrmn yearmn
        from (
        SELECT eb_no,v.lv_type,mhrs,msld,concat(wm.worker_name,' ',ifnull(middle_name,''),' ',ifnull(last_name,'')) wrk_name,
        dept_desc FROM (
        select eb_id,'FL' AS lv_type,ifnull(sum(holiday_hours),0) as mhrs,ifnull(sum(fsld),0) as msld FROM (
        SELECT eb_id,holiday_date,holiday_hours,COUNT(*) AS fsld  From tbl_hrms_holiday_transactions thht
        left join holiday_master hm on hm.id=thht.holiday_id
        where holiday_date between '".$periodfromdate."' and '".$periodtodate."' and hm.company_id= 1  and thht.is_active=1
        GROUP BY eb_id,holiday_date,holiday_hours ) g
        GROUP BY eb_id
        Union All
        SELECT eb_id,'SL' AS lv_type,0 mhrs,sum(sldays) msld from (
        SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
        join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
        join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
        Where ltd.leave_date between '".$periodfromdate."' and '".$periodtodate."' 
        and lt.status =3 and lt.company_id = 1   and ltd.is_active =1   
        AND (lt2.leave_type_code IN ('S' , 'C')) ) g group by eb_id
        Union All
        SELECT eb_id,'SS' AS lv_type,0 mhrs,sum(sldays) msld from (
        SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
        join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
        join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
        Where ltd.leave_date between '".$periodfromdate."' and '".$periodtodate."' and lt.status =3 and 
        lt.company_id =1   and ltd.is_active =1 
        AND lt2.leave_type_code IN ('P') 
        ) g group by eb_id
        Union All
        SELECT eb_id,'EL' AS lv_type,0 mhrs,sum(sldays) msld from (
        SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
        join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
        join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
        Where ltd.leave_date between '".$periodfromdate."' and '".$periodtodate."' and lt.status =3 
        and lt.company_id =1   and ltd.is_active =1 
        AND lt2.leave_type_code IN ('L')) g  group by eb_id
        Union All
        SELECT eb_id,'UL' AS lv_type,0 mhrs,sum(sldays) msld from (
        SELECT eb_id,1 AS sldays FROM leave_tran_details ltd 
        join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
        join leave_types lt2 on lt.leave_type_id =lt2.leave_type_id 
        Where ltd.leave_date between '".$periodfromdate."' and '".$periodtodate."' and lt.status =3 and 
        lt.company_id =1   and ltd.is_active =1 
        AND lt2.leave_type_code IN ('A','U')) g  group by eb_id
        Union All
        select eb_id,'ML' AS lv_type,0 AS MHRS,SUM(cd.no_of_meals*$canteenrate) AS msld from canteen_details cd 
        join worker_master wm on cd.tktno =wm.eb_no and cd.company_id =wm.company_id 
        where tran_date  between '".$periodfromdate."' and '".$periodtodate."' and cd.company_id=1 GROUP BY eb_id
        ) v,worker_master wm, department_master dm 
        WHERE v.eb_id=wm.eb_id and wm.dept_id =dm.dept_id 
        ) k group by eb_no,wrk_name,dept_desc
        ) g , worker_master wm,department_master dm ,
        vowsls.tbl_pay_employee_payscheme tpep 
        where g.eb_no=wm.eb_no and wm.company_id=1
        and g.dept_desc=dm.dept_desc and dm.company_id=1
        and wm.cata_id in (15,16,17,20,21)
		and wm.eb_id=tpep.EMPLOYEEID
        and  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 "
        ;
        $this->db->query($sql);

//BEAMING PIECE WAGES 
$updfor='BEAM';
$sql="	insert into EMPMILL12.tbl_njm_wages_data_collection 
(eb_id,date_from,date_to,act_prod_amount,is_active,update_for,payscheme_id,prod_balance,qualcode,qual_rate,production)
select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt,pwage,1 isac,'".$updfor."',$att_payschm,cuts,beam_wage_code,qrate,cuts prod from (
  select wm.eb_id,bprd.*,round(pwage/whrs*8,2) pwrt,round(1200/26,2) bwrt  
from (
select prd.*,mdept_id,rate1,rate2,case when worked_designation_id=1118 then rate1  else rate1  end qrate, 
case when worked_designation_id=1118 then round(rate1*cuts,2) else round(rate1*cuts,2) end pwage 
from (
select eb_no,beam_wage_code,sum(cuts) cuts,sum(whrs) whrs,worked_designation_id,dept_code,$yrmn yearmn,1 company_id from
(
select bm.*,da.eb_no,da.dept_code,da.worked_designation_id,whrs from
(
select tran_date,spell,beam_mc_no,beam_wage_code,sum(no_of_cuts) cuts  from beaming_daily_production bdp
left join EMPMILL12.beaming_weaving_quality_master bwqm on bdp.quality_code=bwqm.qcode
where bdp.company_id =1 and is_active =1
and bdp.tran_date between '".$periodfromdate."' and '".$periodtodate."'  
group by tran_date,spell,beam_mc_no,beam_wage_code
) bm
left join (
select da.eb_no,da.attendance_date,da.spell,da.worked_designation_id,da.worked_department_id,dept_code,dea.mc_id,sum(working_hours-idle_hours) whrs  from daily_ebmc_attendance dea
join daily_attendance da on da.daily_atten_id =dea.daily_atten_id
join department_master dm on da.worked_department_id=dm.dept_id
where da.is_active =1 and dea.is_active =1 and da.attendance_date between '".$periodfromdate."' and '".$periodtodate."'  
and da.company_id=1 and attendance_type in ('R','O')
group by da.eb_no,da.attendance_date,da.spell,da.worked_designation_id,da.worked_department_id,dea.mc_id,dept_code
) da on da.attendance_date=bm.tran_date and da.spell=bm.spell and da.mc_id=bm.beam_mc_no
) g group by eb_no,beam_wage_code,worked_designation_id,dept_code
) prd left join department_master dm on prd.dept_code=dm.dept_code and prd.company_id=dm.company_id
left join EMPMILL12.tbl_wages_quality_master twqm on prd.beam_wage_code=twqm.q_code
) bprd left join worker_master wm on bprd.eb_no=wm.eb_no and bprd.company_id=wm.company_id
) beam 
  		join vowsls.tbl_pay_employee_payscheme tpep on beam.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 
 ";
//echo $sql;
        $this->db->query($sql);

//winding piece wages 
$updfor='WIND';
$sql="insert into EMPMILL12.tbl_njm_wages_data_collection 
        (eb_id,date_from,date_to,act_prod_amount,is_active,update_for,payscheme_id,prod_balance,qualcode,qual_rate,production)
        select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt, round(wndprod*rate1,2) pwage
        ,1 isac,'".$updfor."',$att_payschm,wndprod,qualcode,rate1,wndprod
         from (
        select wnd.eb_no,eb_id,qualcode,round(sum(wndprod),2) wndprod from (
        select DATE_FORMAT(entry_date, '%d-%m-%Y') entry_date,agroup,spell,shift2,eb_no,name,qualcode,quality,unit,target,
        wndprod,quantity2,prodk,sp_cop,whrs,targ,minach,minack,eff,tareff,avgp,attendance_type ind,yarn_type
        from (
        select h.*, whrs,attendance_type ,0 targ,0 minach,0 minack,0 eff,0 tareff,0 avgp,' ' ind from (
        select entry_date,' ' agroup,spell,' ' shift2,eb_no,name,qualcode,' ' quality,' ' unit,' ' target,sum(wndprod) wndprod,0 quantity2,0 prodk,substr(wndtype,1,1) sp_cop,yarn_type from (
        select company_name,substr(entry_date_time,1,10) entry_date,spell,wde.eb_no,concat(wm.worker_name,' ',ifnull(middle_name,' '),' ',ifnull(last_name,' '))  name,ytm.yarn_type,
        wde.net_weight , case when wde.winding_type=1 then 'Spool' else 'Cop' end wndtype,
        case when wde.winding_type=1 then wde.net_weight else wde.net_weight/15 end wndprod,
        case when wde.winding_type=1 then 5401 else 6501 end qualcode,wde.winding_type
        from winding_doff_entry wde,worker_master wm,yarn_type_master ytm,company_master cm
        where wde.is_active=1   and wde.eb_no=wm.eb_no and wde.yarn_type=ytm.yarn_type_id and is_active=1
        and wde.company_id=wm.company_id and wde.company_id=cm.comp_id 
        and  substr(entry_date_time,1,10)>='".$periodfromdate."' and 
        substr(entry_date_time,1,10)<='".$periodtodate."' and wde.company_id=1
        ) g  group by entry_date,spell,eb_no,name,spell,wndtype,qualcode,yarn_type
        ) h
        left join
        (select eb_no atebno,attendance_date,spell atspell,worked_designation_id,working_hours-idle_hours whrs,attendance_type from 
        daily_attendance  where is_active=1
        and worked_designation_id in (1124,1127)  and company_id=1 
        ) j on h.entry_date=j.attendance_date and h.spell=atspell and h.eb_no=j.atebno
        ) h where entry_date>='".$periodfromdate."' and entry_date<='".$periodtodate."' and attendance_type<>'C'
        ) wnd join worker_master wm2 on wnd.eb_no=wm2.eb_no and wm2.company_id=1
        group by wnd.eb_no,eb_id,qualcode
        ) g left join EMPMILL12.tbl_wages_quality_master twqm on g.qualcode=twqm.q_code
        join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1" 
                ;
        $this->db->query($sql);

//hemming,heracle,hand sewer piece wages 
$updfor='FNG';
$sql="insert into EMPMILL12.tbl_njm_wages_data_collection 
        (eb_id,date_from,date_to,act_prod_amount,is_active,update_for,payscheme_id,prod_balance,qualcode,qual_rate,production)
        select g.* from (
        select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt, round(sum(production*rate1),2) pwage,
        1 isac,'".$updfor."',$att_payschm,SUM(production) production,process_code,rate1,sum(production) prd  from ( 
        select fng.*,da.worked_designation_id,da.working_hours-idle_hours whrs,attendance_type,da.eb_id from 
        (
        select fe.*,ptm.process_code,ptm.process_type,
        case when fe.work_type in (13,14,21) then 1106
        when fe.work_type in (18,19,22) then 1100
        when fe.work_type in (20) then 1098 end occuid,substr(entry_date,1,10) prddate
        from finishing_entries fe 
        left join process_type_master ptm on fe.work_type =ptm.process_type_id 
        where fe.company_id =1 and 
        substr(entry_date,1,10)>='".$periodfromdate."' and substr(entry_date,1,10)<='".$periodtodate."'
        and fe.work_type in (13,14,21,18,19,22,20) and fe.is_active=1
        ) fng
        left join (select * from daily_attendance where is_active=1 and attendance_date 
        between '".$periodfromdate."' and '".$periodtodate."'
        ) da on da.attendance_date=substr(fng.entry_date,1,10) 
        and da.spell=fng.spell and fng.company_id =da.company_id and da.eb_no=fng.eb_no
        ) g left join EMPMILL12.tbl_wages_quality_master twqm on g.process_code=twqm.q_code
        where attendance_type in ('R','O')
        group by eb_id,eb_no,process_code,rate1 ) g  		join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 ";
        $this->db->query($sql);

//weaver piece wages thru view
$updfor='WEAV';
$sql="insert into EMPMILL12.tbl_njm_wages_data_collection
        (eb_id,date_from,date_to,act_prod_amount,is_active,loom_production,update_for,payscheme_id,qualcode,qual_rate,production)
        select g.* from (
        select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt, round(sum(production*rate1),2) pwage,
        1 isac,
        sum(production) production,'".$updfor."',$att_payschm,qcod,rate1,sum(production) prd from ( 
        select eb_id,tktno,qcod,sum(diffm) as production,sum(tgprd) unit_conv,
        sum(diffm*rts)/sum(diffm) rate1,sum(diffm*rts) amount from (
        select * from EMPMILL12.view_proc_njm_loom_data_for_wages vpnldfw 
        where loom_date between '".$periodfromdate."' and '".$periodtodate."'
        ) k
        group by eb_id,tktno,qcod
        ) lmpd group by eb_id,qcod,rate1
        ) g  		join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 ";
        $this->db->query($sql);

//helper piece wages
$updfor='HELP';
$sql="       insert into EMPMILL12.tbl_njm_wages_data_collection 
(eb_id,date_from,date_to,sardhelp_amt,is_active,update_for,payscheme_id)
select g.* from (
select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt, round(sum(wrkhrs*srate*1),2) pwage,1 isac
,'".$updfor."',$att_payschm from ( 
select attp.*,lmprd.*,trllh.*,
    (amount/( (running_hours-lost_hours)*no_of_looms/2)) srate,1 company_id from ( 
    select eb_no,mach_shr_code,sard_help,0 hours1,0 hours2,sum(wrkhrs) wrkhrs,GROUPP,dept_code,MASTDEPT from (
    SELECT da.attendance_date,attendance_type,da.spell,da.eb_no,da.worked_designation_id,mach_shr_code,0 hours1,0 hours2,
    (working_hours-idle_hours) wrkhrs,dept_code,'008' MASTDEPT,
    case when worked_designation_id=1114 then 'H'  
    end sard_help,
    case when dept_code between '029' and '033' then 'A'
    when dept_code between '034' and '038' then 'B'
    when dept_code between '039' and '043' then 'C' end GROUPP
    FROM daily_ebmc_attendance dea ,daily_attendance da ,department_master dm ,mechine_master mm 
    WHERE  da.daily_atten_id=dea.daily_atten_id and da.attendance_date between '".$periodfromdate."' and '".$periodtodate."' 
    and dept_code>='029' AND dept_code<='043' and da.worked_designation_id in (1114)
    and dea.mc_id=mm.mechine_id and da.worked_department_id=dm.dept_id and da.company_id=1
    and dea.is_active=1 and da.is_active=1 and da.attendance_type in ('R','O')
    ) g 
    GROUP BY eb_no,mach_shr_code,sard_help,GROUPP,dept_code 
    ) attp 
    left join 
    (select helpline,substr(ggp,1,1) grop, sum(diffm) as production,sum(tgprd) unit_conv,
        sum(diffm*rts)/sum(diffm) rate1,sum(diffm*rts) amount from (
        select * from EMPMILL12.view_proc_njm_loom_data_for_wages vpnldfw 
        where loom_date between '".$periodfromdate."' and '".$periodtodate."'
        ) g 
        group by helpline,substr(ggp,1,1)
    ) lmprd on attp.mach_shr_code=lmprd.helpline and attp.GROUPP=lmprd.grop
    left join (select * from EMPMILL12.tbl_run_loom_line_hours where sard_helper='H' and 
    date_from='".$periodfromdate."' and date_to='".$periodtodate."'  ) trllh on lmprd.helpline=trllh.line_no and lmprd.grop=trllh.wgroup 
) g left join worker_master wm on g.eb_no=wm.eb_no and g.company_id=wm.company_id
group by eb_id   
) g  		join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
		WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1 "      
;         
// echo $sql;
//        $this->db->query($sql);

//sardar piece wages
$updfor='SARD';
$sql="    insert into EMPMILL12.tbl_njm_wages_data_collection 
(eb_id,date_from,date_to,sardhelp_amt,is_active,update_for,payscheme_id)
select g.* from (
select eb_id,'".$periodfromdate."'  df,'".$periodtodate."' dt, round(sum(wrkhrs*srate*1.3),2) pwage,1 isac
,'".$updfor."',$att_payschm  from ( 
select attp.*,lmprd.*,trllh.*,
    (amount/( (running_hours-lost_hours)*no_of_looms/2)) srate,1 company_id from ( 
    select eb_no,mach_shr_code,sard_help,0 hours1,0 hours2,sum(wrkhrs) wrkhrs,GROUPP,dept_code,MASTDEPT from (
    SELECT da.attendance_date,attendance_type,da.spell,da.eb_no,da.worked_designation_id,mach_shr_code,0 hours1,0 hours2,
    (working_hours-idle_hours) wrkhrs,dept_code,'008' MASTDEPT,
    case when worked_designation_id=1115 then 'S'
    when worked_designation_id=1005 then 'S' end sard_help,
    case when dept_code between '029' and '033' then 'A'
    when dept_code between '034' and '038' then 'B'
    when dept_code between '039' and '043' then 'C' end GROUPP
    FROM daily_ebmc_attendance dea ,daily_attendance da ,department_master dm ,mechine_master mm 
    WHERE  da.daily_atten_id=dea.daily_atten_id and da.attendance_date between '$periodfromdate' and '$periodtodate' 
    and dept_code>='029' AND dept_code<='043' and da.worked_designation_id in (1115,1005)
    and dea.mc_id=mm.mechine_id and da.worked_department_id=dm.dept_id and da.company_id=1
    and dea.is_active=1 and da.is_active=1 and da.attendance_type in ('R','O')
    ) g 
    GROUP BY eb_no,mach_shr_code,sard_help,GROUPP,dept_code 
    ) attp 
    left join 
    (select sardline,substr(ggp,1,1) grop, sum(diffm) as production,sum(tgprd) unit_conv,
        sum(diffm*rts)/sum(diffm) rate1,sum(diffm*rts) amount from (
        select * from EMPMILL12.view_proc_njm_loom_data_for_wages vpnldfw 
        where loom_date between '$periodfromdate' and '$periodtodate'
        ) g 
        group by sardline,substr(ggp,1,1)
    ) lmprd on attp.mach_shr_code=lmprd.sardline and attp.GROUPP=lmprd.grop
    left join (select * from EMPMILL12.tbl_run_loom_line_hours where sard_helper='S' and 
    date_from='$periodfromdate' and date_to='$periodtodate'  ) trllh on lmprd.sardline=trllh.line_no and lmprd.grop=trllh.wgroup 
) g left join worker_master wm on g.eb_no=wm.eb_no and g.company_id=wm.company_id
group by eb_id  ) g  		join vowsls.tbl_pay_employee_payscheme tpep on g.eb_id=tpep.EMPLOYEEID
        WHERE  tpep.PAY_SCHEME_ID = $att_payschm
        AND tpep.STATUS = 1        
";        
//echo $sql;
//        $this->db->query($sql);

$sql="update EMPMILL12.tbl_njm_wages_data_collection set upd_type=2 where update_for in ('SARD')";
$this->db->query($sql);
$sql="update EMPMILL12.tbl_njm_wages_data_collection set upd_type=3 where update_for in ('HELP')";
$this->db->query($sql);



$sql="select
	line_number,
	substr(shift, 1, 1) grp,
	sum(diffm) quantity,
	round(sum( (bswg /((tg / tot_hrs * 8)* 2))* diffm), 2) amount
from
	(
	select
		h.*,
		((spd * tot_hrs * 60)/(ashots * 36))*.65 tg,
		46.15 bswg
	from
		(
		select
				loom_date as lmdate,
				DATE_FORMAT(dated, '%d-%m-%Y') loom_date,
				shift,
				mcno,
				round(diffm, 2) diffm,
				qcod ,
				qcod qcode,
				tot_hrs,
				tktnoc tktno,
				wdept_id ,
				replace(right(dept_desc, 3), ' ', '') ggp,
				line_number,
				reed_speed,
				case
					when reed_speed<34 then 168
					when reed_speed between 34 and 50 then 162.5
					else 144
				end spd,ashots
			from
				(
				select loom_date, dated,ggp  shift,mcno,0 openm,0 closm,diffm,0 eff,0 LOSTHRS,0	EFFSTD,0	CMETER,0	TAR_DIFF,0	MTR,
				0	LINE_NO,''	HS,0	SLNO,
qcod ,shift groupa,tot_hrs,0 help_no,right(mcno,3) sno,0 s_sno,tktno tkt_no,'F' misc_tag,tktnoc,t.wdept_id,dept_desc,line_number,reed_speed  from (
select loom_date,DATE_FORMAT(loom_date,'%d-%m-%Y') dated,shift,mcno,round(diffm,2) diffm, qcod , tot_hrs,tktnoc tktno,wdept_id,g.working_hours 
, replace(right(dept_desc,3), ' ','') ggp,loom_id,tktnoc,dept_desc,line_number,reed_speed from (
select a.*,b.working_hours,b.wdept_id from (
select loom_date ,shift,mcno, if((diffm<120 or diffm is null),120,diffm) as diffm, qcod , IFNULL(tot_hrs,8) as tot_hrs, 
if((tktno like '%C%' or tktno is null or tktno like '%O%') ,
999,tktno) as tktnoc,tktno,loom_id,line_number,reed_speed from (
select loom_date,'A1' as shift,mm.mechine_name as mcno,production_a1 as diffm,quality_code_a1 as qcod, working_hrs_a1 as tot_hrs,ticket_no_a1 as tktno,cjb.loom_id,mm.line_number,mm.reed_speed 
from cuts_jugar_buff_1 cjb left join mechine_master mm on mm.mechine_id =cjb.loom_id  where cjb.company_id =1 
and cjb.loom_date >='$periodfromdate' and 
cjb.loom_date <='$periodtodate' UNION  select loom_date,'A2' as shift,mm.mechine_name as mcno,production_a2 as diffm,quality_code_a2 as qcod, 
working_hrs_a2 as tot_hrs,
ticket_no_a2 as tktno,cjb.loom_id,mm.line_number,mm.reed_speed from cuts_jugar_buff_1 cjb left join mechine_master mm on mm.mechine_id =cjb.loom_id  where cjb.company_id =1 
and cjb.loom_date >='$periodfromdate' 
and cjb.loom_date <='$periodtodate' UNION  select loom_date,'B1' as shift,mm.mechine_name as mcno,production_b1 as diffm,quality_code_b1 as qcod, 
working_hrs_b1 as tot_hrs,
ticket_no_b1 as tktno,cjb.loom_id,mm.line_number,mm.reed_speed from cuts_jugar_buff_1 cjb left join mechine_master mm on mm.mechine_id =cjb.loom_id  where cjb.company_id =1 
and cjb.loom_date >='$periodfromdate' 
and cjb.loom_date <='$periodtodate' UNION  select loom_date,'B2' as shift,mm.mechine_name as mcno,production_b2 as diffm,quality_code_b2 as qcod, 
working_hrs_b2 as tot_hrs,
ticket_no_b2 as tktno,cjb.loom_id,mm.line_number,mm.reed_speed from cuts_jugar_buff_1 cjb left join mechine_master mm on mm.mechine_id =cjb.loom_id  where cjb.company_id =1 
and cjb.loom_date >='$periodfromdate' 
and cjb.loom_date <='$periodtodate' UNION  select loom_date,'C' as shift,mm.mechine_name as mcno,production_c as diffm,quality_code_c as qcod, working_hrs_c as tot_hrs,
ticket_no_c as tktno,cjb.loom_id,mm.line_number,mm.reed_speed from cuts_jugar_buff_1 cjb left join mechine_master mm on mm.mechine_id =cjb.loom_id  where cjb.company_id =1 
and cjb.loom_date >='$periodfromdate' 
and cjb.loom_date <='$periodtodate') a 
where a.tktno is not null or a.diffm is not null
) a
left join
(select eb_no,attendance_date,spell,working_hours,da.worked_department_id wdept_id from daily_attendance da 
where  da.attendance_date >='$periodfromdate' and da.attendance_date <='$periodtodate' and da.is_active =1
and worked_designation_id=1113 and attendance_type <>'C' 
) b
on a.loom_date=attendance_date and a.shift=b.spell and b.eb_no=a.tktno
) g 
left join department_master dm on g.wdept_id=dm.dept_id 
where working_hours is not null 
) t
) k
left join EMPMILL12.weaving_daily_transaction wdt on
		wdt.q_code = k.qcod
		and wdt.tran_date = k.loom_date
		AND wdt.company_id = 1 
		) h order by h.line_number ,mcno
	) v			
group by
	line_number,
	substr(shift, 1, 1)
";
//echo "====".$sql;
   //     $this->db->query($sql);
         // Update the status of the data collection
   //     $mccodes = $this->Njmallwagesprocess->njmcontpayregisdisp($periodfromdate,$periodtodate, $contractorName, $reportType);
//    $mccodes = $this->db->query($sql)->result_array();
    $mccodes = $this->db->query($sql)->result(); 
//var_dump($mccodes);
    foreach ($mccodes as $record) {
//         echo "Updating record for line number: {$record['line_number']}, group: {$record['grp']}, quantity: {$record['quantity']}, amount: {$record['amount']}\n";
//        echo "Updating record for line number: $record->line_number, group: $record->grp, quantity: $record->quantity, amount: $record->amount\n";
        $sql="update EMPMILL12.tbl_run_loom_line_hours set quantity=$record->quantity,pamount=$record->amount
         where date_from='$periodfromdate' and date_to='$periodtodate' and line_no=$record->line_number and wgroup='$record->grp' ";
//echo $sql;
         $this->db->query($sql);
    }

    $sql="insert
	into
	EMPMILL12.tbl_njm_wages_data_collection (eb_id,
	date_from,
	date_to,
	sardhelp_amt,
	is_active,
	update_for,
	payscheme_id,lineno,qual_rate,linehrs)
	select eb_id,'$periodfromdate','$periodtodate',case when sard_help='S' then  (pamount/((trllh.running_hours-lost_hours)*30)*2)*1.3*wrkhrs 
	else (pamount/((trllh.running_hours-lost_hours)*30)*2)*1*wrkhrs end amt,1,'SH' update_for, $att_payschm payschm,
    concat(sard_help,mach_shr_code) mech_shr_code,
    case when sard_help='S' then  (pamount/((trllh.running_hours-lost_hours)*30)*2)*1.3 
	else (pamount/((trllh.running_hours-lost_hours)*30)*2)*1 end rts,wrkhrs
 from (
	select
	eb_id,
	eb_no,
				mach_shr_code,
				sard_help,
				0 hours1,
				0 hours2,
				sum(wrkhrs) wrkhrs,
				GROUPP,
				dept_code,
				MASTDEPT
			from
				(
				SELECT
					da.eb_id,
					da.attendance_date,
					attendance_type,
					da.spell,
					da.eb_no,
					da.worked_designation_id,
					mach_shr_code,
					0 hours1,
					0 hours2,
					(working_hours-idle_hours) wrkhrs,
					dept_code,
					'008' MASTDEPT,
					case
						when worked_designation_id = 1114 then 'H'
						else 'S'
					end sard_help,
					case
						when dept_code between '029' and '033' then 'A'
						when dept_code between '034' and '038' then 'B'
						when dept_code between '039' and '043' then 'C'
					end GROUPP
				FROM
					daily_ebmc_attendance dea ,
					daily_attendance da ,
					department_master dm ,
					mechine_master mm
				WHERE
					da.daily_atten_id = dea.daily_atten_id
					and da.attendance_date between '$periodfromdate' and '$periodtodate'
					and dept_code >= '029'
					AND dept_code <= '043'
					and da.worked_designation_id in (1114,1115,1005)
					and dea.mc_id = mm.mechine_id
					and da.worked_department_id = dm.dept_id
					and da.company_id = 1
					and dea.is_active = 1
					and da.is_active = 1
					and da.attendance_type in ('R', 'O')
					) g
			GROUP BY
				eb_id,
				eb_no,
				mach_shr_code,
				sard_help,
				GROUPP,
				dept_code
			) at left join EMPMILL12.tbl_run_loom_line_hours trllh 
			on trllh.line_no=at.mach_shr_code and at.groupp=trllh.wgroup and at.sard_help=trllh.sard_helper  
			and date_from ='$periodfromdate' and date_to='$periodtodate'
			left join tbl_pay_employee_payscheme tpep on tpep.employeeid=at.eb_id and tpep.status=1 
			where tpep.PAY_SCHEME_ID =$att_payschm
			";
//         echo $sql;
            $this->db->query($sql);




     $success='Success';
  
    $data[] = [
        'succes'=> $success 
    ];
    return $data;
   
}



public function njmwrkvardupdate($periodfromdate,$periodtodate,$att_payschm) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');

    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');


     
    // Use active record or query builder to build the query
    $sql="update EMPMILL12.tbl_njm_wages_data_collection set is_active=0 where date_from='".$periodfromdate."'
    and date_to='".$periodtodate."' and payscheme_id=$att_payschm and update_for  in ('V') and is_active=1";
    $this->db->query($sql);


    $sql="insert into EMPMILL12.tbl_njm_wages_data_collection (eb_id,date_from ,date_to,is_active,update_for,payscheme_id,iftu_amount )        
select da.eb_id,'$periodfromdate' date_from,'$periodtodate' date_to,1 is_active,'V' update_for,PAY_SCHEME_ID,60 as vard  from
(
select eb_id,sum(whrs) whrs from (
select eb_id,(working_hours-idle_hours) whrs from vowsls.daily_attendance da 
where da.attendance_type in ('R','O') and da.is_active =1 and da.attendance_date between '$periodfromdate' 
and '$periodtodate'
union all
select eb_id,8 whrs  from leave_tran_details ltd 
left join leave_transactions lt on lt.leave_transaction_id =ltd.ltran_id 
where lt.status =3 and ltd.is_active =1 
and ltd.leave_date between '$periodfromdate' and '$periodtodate' and lt.leave_type_id =2
union all
select eb_id,8 whrs from tbl_hrms_holiday_transactions thht 
left join holiday_master hm on hm.id =thht.holiday_id 
where thht.is_active =1 and hm.holiday_date between '$periodfromdate' and '$periodtodate'
) k
group by eb_id
)  da 
JOIN vowsls.tbl_pay_employee_payscheme tpep on da.eb_id=tpep.EMPLOYEEID and tpep.status=1
left join vowsls.tbl_hrms_ed_personal_details thepd on da.eb_id=thepd.eb_id
left join vowsls.tbl_hrms_ed_official_details theod on da.eb_id=theod.eb_id and theod.is_active =1
left join vowsls.tbl_hrms_ed_resign_details therd on da.eb_id=therd.eb_id and therd.is_active =1
where tpep.PAY_SCHEME_ID =$att_payschm and da.whrs>0";
$this->db->query($sql);
            
    
 


}


public function njmstaffbanksheet($periodfromdate,$periodtodate,$att_payschm,$holget) {
 //   echo 'ooooo='.$att_payschm;
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $sql="SELECT * from (select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
thebd.ifsc_code,thebd.bank_acc_no,thebd.bank_name,thebd.bank_branch_name,amount NET_PAY,tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep 
left join worker_master wm on wm.eb_id=tpep.EMPLOYEEID
left join tbl_pay_period tpp on tpp.id=tpep.PAYPERIOD_ID
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID  and thebd.is_active =1
where tpp.STATUS not in (4) and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'
and tpp.PAYSCHEME_ID in (1,21,23,24,90,94,96) and tpep.COMPONENT_ID in (369) and amount>0
union all
select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
thebd.ifsc_code,thebd.bank_acc_no,thebd.bank_name,thebd.bank_branch_name,amount NET_PAY,tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep 
left join worker_master wm on wm.eb_id=tpep.EMPLOYEEID
left join tbl_pay_period tpp on tpp.id=tpep.PAYPERIOD_ID
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID  and thebd.is_active =1
where tpp.STATUS not in (4) and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'
and tpp.PAYSCHEME_ID in ($att_payschm) and tpep.COMPONENT_ID in (252) and amount>0
) g    order by PAYSCHEME_ID ";


$sql="SELECT * from (select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS wname,
thebd.ifsc_code,thebd.bank_acc_no,thebd.bank_name,thebd.bank_branch_name,amount NET_PAY,tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep 
left join worker_master wm on wm.eb_id=tpep.EMPLOYEEID
left join tbl_pay_period tpp on tpp.id=tpep.PAYPERIOD_ID
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID  and thebd.is_active =1
where tpp.STATUS not in (4) and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'
and tpp.PAYSCHEME_ID in ($att_payschm) and tpep.COMPONENT_ID in (369) and amount>0
) g    order by PAYSCHEME_ID ";




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


   
public function njmnewbdlpayslipprint($att_payschm,$periodfromdate,$periodtodate) {
$companyId = $this->session->userdata('companyId');
//echo 'allwg  '.$att_payschm.'  '.$periodfromdate. '  '.$periodtodate;
if (!$att_payschm  || !$periodfromdate || !$periodtodate) {
    die("Missing required parameters.");
}
$payscheme_id = $att_payschm;
$branch_id = 4; // Assuming branch_id is 1 for this example, replace as needed
		$payid=0;
//		$branch_id = $pers['branch_id'];
		$payscheme_id = $att_payschm;
		$from_date = $periodfromdate;
		$to_date = $periodtodate;
//		$companyId = $pers['company'];

		$sql="select * from tbl_pay_period tpp where from_date='".$from_date."' and TO_DATE ='".$to_date."' 
		and PAYSCHEME_ID =".$payscheme_id."
		and company_id=".$companyId." and branch_id=".$branch_id." and STATUS not in (4)";
//echo $sql;
		$query = $this->db->query($sql);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
	 	   $payid=$record->ID;
		}	

		$sql="";
		$sql1="";
	   $sqlmhd="";
	   $sqlfhd="";			 
		$sqlhd="select
		tppc.payslip_order,tppc.component_id,
		tppc.desc_print,tpc.CODE
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
		tps.ID =".$payscheme_id."
		AND tppc.company_id =".$companyId."
		AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id=0
	ORDER BY
		tppc.payscheme_id,
		payslip_order";
//	 echo $sql;

		$query = $this->db->query($sqlhd);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
		   $id=$record->payslip_order;
		   $des=$record->desc_print;
		   $des=$record->CODE;
		  if ($id>0) { 
		   if ($id==1)  {
				   $sqlmhd=$sqlmhd."eb_no".",";
				   $sqlfhd=$sqlfhd.'eb_no'.",";
		   }	
		   if ($id==2)  {
				   $sqlmhd=$sqlmhd."emp_name ".",";
				   $sqlfhd=$sqlfhd.'emp_name'.",";
		   }
		   if ($id==3)  {
				   $sqlmhd=$sqlmhd."dept_code ".",";
				   $sqlfhd=$sqlfhd.'dept_code'.",";
		   }		
		   if ($id==4)  {
			   $sqlmhd=$sqlmhd."department ".",";
			   $sqlfhd=$sqlfhd.'department'.",";

		   }			//		break;
		   if ($id==5)  {
			   $sqlmhd=$sqlmhd."designation".",";
			   $sqlfhd=$sqlfhd.'designation'.",";
		   }	
		   if ($id==6)  {
			   $sqlmhd=$sqlmhd."uan_no ".",";
			   $sqlfhd=$sqlfhd.'uan_no'.",";
		   }
		   if ($id==7)  {
			   $sqlmhd=$sqlmhd."esi_no ".",";
			   $sqlfhd=$sqlfhd.'esi_no';
		   }
   
			   
			 }	
 
		}
//echo $sqlmhd;

		$sqlp="select ".$sqlmhd."eb_id ";
		$sqlda="select
		tppc.payslip_order,tppc.component_id,
		case when tppc.payslip_order=1 then 'eb_no'
		when tppc.payslip_order=2 then 'emp_name' 
		when tppc.payslip_order=3 then 'dept_code' 
		else tpc.CODE end CODE,
		tppc.desc_print
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
	tps.ID =".$payscheme_id."
	AND tppc.company_id =".$companyId."
	AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id>0

	ORDER BY
		tppc.payscheme_id,
		payslip_order";
	
		$query = $this->db->query($sqlda);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
					   
	



		$id=$record->component_id;
		$des=$record->desc_print;
		$des=$record->CODE;
		if ($id>0) { 
			$sqlp=$sqlp.","."max( case when COMPONENT_ID=".$id." then amount else 0 end ) `".$des."`";
		}
		//$sheet->getCellByColumnAndRow('3', $x)->getValue();
		
		
		
		}
		
		$sqlp=$sqlp." from (
		   SELECT
		   tpep.PAYPERIOD_ID,
		   tpp.FROM_DATE,
		   tpp.TO_DATE,
		   tpep.EMPLOYEEID AS eb_id,
		   theod.emp_code AS eb_no,
		   CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS emp_name,
		   tpep.COMPONENT_ID,
		   tpc.NAME,
		   thee.esi_no,
		   thep.pf_uan_no uan_no,
		   AMOUNT,
		   dm.dept_code,
		   dm.dept_desc AS department,
		   dsg.desig AS designation
	   FROM
		tbl_pay_employee_payroll tpep
	LEFT JOIN tbl_hrms_ed_personal_details thepd ON
		tpep.EMPLOYEEID = thepd.eb_id
	LEFT JOIN  tbl_hrms_ed_official_details 
	theod ON
		tpep.EMPLOYEEID = theod.eb_id and theod.is_active=1
	LEFT JOIN tbl_hrms_ed_pf thep ON
		tpep.EMPLOYEEID = thep.eb_id and thep.is_active=1
	LEFT JOIN tbl_hrms_ed_esi thee ON
		tpep.EMPLOYEEID = thee.eb_id and thee.is_active=1
	 JOIN tbl_pay_period 
	tpp ON
		tpep.PAYPERIOD_ID = tpp.ID and tpp.status not in (4) and tpp.id=$payid
	LEFT JOIN tbl_pay_components tpc ON
		tpep.COMPONENT_ID = tpc.ID
	LEFT JOIN department_master dm ON
		dm.dept_id = theod.department_id
	LEFT JOIN designation dsg ON
		dsg.id = theod.designation_id
	   ) g
		  group by $sqlfhd, eb_id ";
 		
 
  //echo 'final';
	//	echo $sqlp;
//		return $sqlp;
        $query = $this->db->query($sqlp);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
   //     var_dump($data);
        return $data;
   

    }
//////////////// end this page is Njmallwagesprocess.php /////////////////


public function njmwagespayslip1641($att_payschm,$periodfromdate,$periodtodate,$att_dept) {
//	$branch_id = isset($_POST['branch_id']) ? (int)$_POST['branch_id'] : 0;
//$att_payschm = 
//$periodfromdate = isset($_POST['periodfromdate']) ? $_POST['periodfromdate'] : '';
//$periodtodate = isset($_POST['periodtodate']) ? $_POST['periodtodate'] : '';
$companyId = $this->session->userdata('companyId');
//echo 'allwg  '.$att_payschm.'  '.$periodfromdate. '  '.$periodtodate;
if (!$att_payschm  || !$periodfromdate || !$periodtodate) {
    die("Missing required parameters.");
}
$payscheme_id = $att_payschm;
$branch_id = 4; // Assuming branch_id is 1 for this example, replace as needed


	$sqlp="SELECT
		tpep.PAYPERIOD_ID,
		tpp.FROM_DATE,
		tpp.TO_DATE,
		tpep.EMPLOYEEID AS eb_id,
		theod.emp_code AS eb_no,
		CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS emp_name,
		tpep.COMPONENT_ID,
		tpc.NAME,tpc.CODE,
		thee.esi_no,
		thep.pf_no uan_no,
		AMOUNT,
		dm.dept_code,
		dm.dept_desc AS department,
		dsg.desig AS designation
	FROM
		tbl_pay_employee_payroll tpep
	LEFT JOIN tbl_hrms_ed_personal_details thepd ON
		tpep.EMPLOYEEID = thepd.eb_id
	LEFT JOIN  tbl_hrms_ed_official_details 
	theod ON
		tpep.EMPLOYEEID = theod.eb_id and theod.is_active=1
	LEFT JOIN tbl_hrms_ed_pf thep ON
		tpep.EMPLOYEEID = thep.eb_id and thep.is_active=1
	LEFT JOIN tbl_hrms_ed_esi thee ON
		tpep.EMPLOYEEID = thee.eb_id and thee.is_active=1
	 JOIN tbl_pay_period 
	tpp ON
		tpep.PAYPERIOD_ID = tpp.ID and tpp.status not in (4) 
	LEFT JOIN tbl_pay_components tpc ON
		tpep.COMPONENT_ID = tpc.ID
	LEFT JOIN department_master dm ON
		dm.dept_id = theod.department_id
	LEFT JOIN designation dsg ON
		dsg.id = theod.designation_id
		where tpp.PAYSCHEME_ID =$att_payschm and tpp.FROM_DATE ='$periodfromdate' and tpp.TO_DATE ='$periodtodate'";
    if ($att_dept) {
       $sqlp .= " and theod.department_id in ($att_dept)";
    }

    $sqlp .= " order by dm.dept_code,eb_no
        ";

 
  //echo 'final';
//echo $sqlp;
//		return $sqlp;
        $query = $this->db->query($sqlp);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            

      
    $data=$query->result();
   //     var_dump($data);
        return $data;
   

    }


public function njmnallpayregister($att_payschm,$periodfromdate,$periodtodate) {
$companyId = $this->session->userdata('companyId');
//echo 'allwg  '.$att_payschm.'  '.$periodfromdate. '  '.$periodtodate;
if (!$att_payschm  || !$periodfromdate || !$periodtodate) {
    die("Missing required parameters.");
}
$payscheme_id = $att_payschm;
$branch_id = 4; // Assuming branch_id is 1 for this example, replace as needed
		$payid=0;
//		$branch_id = $pers['branch_id'];
		$payscheme_id = $att_payschm;
		$from_date = $periodfromdate;
		$to_date = $periodtodate;
//		$companyId = $pers['company'];

		$sql="select * from tbl_pay_period tpp where from_date='".$from_date."' and TO_DATE ='".$to_date."' 
		and PAYSCHEME_ID =".$payscheme_id."
		and company_id=".$companyId." and branch_id=".$branch_id." and STATUS not in (4)";
//echo $sql;
		$query = $this->db->query($sql);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
	 	   $payid=$record->ID;
		}	

		$sql="";
		$sql1="";
	   $sqlmhd="";
	   $sqlfhd="";			 
		$sqlhd="select
		tppc.payslip_order,tppc.component_id,
		tppc.desc_print,tpc.CODE
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
		tps.ID =".$payscheme_id."
		AND tppc.company_id =".$companyId."
		AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id=0
	ORDER BY
		tppc.payscheme_id,
		payslip_order";
//	 echo $sql;

		$query = $this->db->query($sqlhd);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {
		   $id=$record->payslip_order;
		   $des=$record->desc_print;
		   $des=$record->CODE;
		  if ($id>0) { 
		   if ($id==1)  {
				   $sqlmhd=$sqlmhd."eb_no".",";
				   $sqlfhd=$sqlfhd.'eb_no'.",";
		   }	
		   if ($id==2)  {
				   $sqlmhd=$sqlmhd."emp_name ".",";
				   $sqlfhd=$sqlfhd.'emp_name'.",";
		   }
		   if ($id==3)  {
				   $sqlmhd=$sqlmhd."dept_code ".",";
				   $sqlfhd=$sqlfhd.'dept_code'.",";
		   }		
		   if ($id==4)  {
			   $sqlmhd=$sqlmhd."department ".",";
			   $sqlfhd=$sqlfhd.'department'.",";

		   }			//		break;
		   if ($id==5)  {
			   $sqlmhd=$sqlmhd."designation".",";
			   $sqlfhd=$sqlfhd.'designation'.",";
		   }	
		   if ($id==6)  {
			   $sqlmhd=$sqlmhd."uan_no ".",";
			   $sqlfhd=$sqlfhd.'uan_no'.",";
		   }
		   if ($id==7)  {
			   $sqlmhd=$sqlmhd."esi_no ".",";
			   $sqlfhd=$sqlfhd.'esi_no'.",";
		   }
   
			   
			 }	
 
		}
//echo $sqlmhd;

		$sqlp="select ".$sqlmhd."eb_id ";
		$sqlda="select
		tppc.payslip_order,tppc.component_id,
		case when tppc.payslip_order=1 then 'eb_no'
		when tppc.payslip_order=2 then 'emp_name' 
		when tppc.payslip_order=3 then 'dept_code' 
		else tpc.CODE end CODE,
		tppc.desc_print
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (
		select
			*
		from
			tbl_pay_scheme
		where
			status = 32) tps on
		tps.ID = tppc.payscheme_id
	left join (
		select
			*
		from
			tbl_pay_scheme_details
		where
			status = 1) tpsd on
		tps.id = tpsd.PAY_SCHEME_ID
		and tpsd.COMPONENT_ID = tppc.component_id
		and tpsd.PAY_SCHEME_ID = tppc.payscheme_id
	left JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id = tpc.ID
	WHERE
	tps.ID =".$payscheme_id."
	AND tppc.company_id =".$companyId."
	AND tppc.branch_id =".$branch_id."
		and tppc.payscheme_id>0
		and tppc.is_active = 1
		and tppc.payslip_print = 1
		and tppc.component_id>0
	ORDER BY
		tppc.payscheme_id,
		payslip_order";
		$query = $this->db->query($sqlda);
		$mccodes= $query->result();
		foreach ($mccodes as $record) {

		$id=$record->component_id;
		$des=$record->desc_print;
	//	$des=$record->CODE;
		if ($id>0) { 
			$sqlp=$sqlp.","."max( case when COMPONENT_ID=".$id." then amount else 0 end ) `".$des."`";
		}
		//$sheet->getCellByColumnAndRow('3', $x)->getValue();
		
		
		
		}
		
		$sqlp=$sqlp." from (
		   SELECT
		   tpep.PAYPERIOD_ID,
		   tpp.FROM_DATE,
		   tpp.TO_DATE,
		   tpep.EMPLOYEEID AS eb_id,
		   theod.emp_code AS eb_no,
		   CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS emp_name,
		   tpep.COMPONENT_ID,
		   tpc.NAME,
		   thee.esi_no,
		   thep.pf_no uan_no,
		   AMOUNT,
		   dm.dept_code,
		   dm.dept_desc AS department,
		   dsg.desig AS designation
	   FROM
		tbl_pay_employee_payroll tpep
	LEFT JOIN tbl_hrms_ed_personal_details thepd ON
		tpep.EMPLOYEEID = thepd.eb_id
	LEFT JOIN  tbl_hrms_ed_official_details 
	theod ON
		tpep.EMPLOYEEID = theod.eb_id and theod.is_active=1
	LEFT JOIN tbl_hrms_ed_pf thep ON
		tpep.EMPLOYEEID = thep.eb_id and thep.is_active=1
	LEFT JOIN tbl_hrms_ed_esi thee ON
		tpep.EMPLOYEEID = thee.eb_id and thee.is_active=1
	 JOIN tbl_pay_period 
	tpp ON
		tpep.PAYPERIOD_ID = tpp.ID and tpp.status not in (4) and tpp.id=$payid
	LEFT JOIN tbl_pay_components tpc ON
		tpep.COMPONENT_ID = tpc.ID
	LEFT JOIN department_master dm ON
		dm.dept_id = theod.department_id
	LEFT JOIN designation dsg ON
		dsg.id = theod.designation_id
	   ) g
		  group by $sqlfhd eb_id order by dept_code,eb_no ";
 		
 
  //echo 'final';
	//	echo $sqlp;
//		return $sqlp;
        $query = $this->db->query($sqlp);
    //    $query = $this->db->get($sql);
 //   echo $this->db->last_query();            



      
 //     $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }
 

    }
//////////////// end this page is Njmallwagesprocess.php /////////////////




public function njmattwithpayschmexceldata($periodfromdate,$periodtodate,$att_payschm) {
$companyId = $this->session->userdata('companyId');

$sql="select emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS empname,
dept_desc,cata_desc,sm.status_name,tps.NAME,sum(working_hours-idle_hours) whrs,theod.date_of_join   from daily_attendance da 
left join tbl_hrms_ed_personal_details thepd on thepd.eb_id=da.eb_id
left join tbl_hrms_ed_official_details theod on theod.eb_id =da.eb_id and theod.is_active =1
left join department_master dm on dm.dept_id=theod.department_id 
left join category_master cm on cm.cata_id =theod.catagory_id 
left join status_master sm on sm.status_id =thepd.status 
left join tbl_pay_employee_payscheme tpep on tpep.EMPLOYEEID =da.eb_id and tpep.STATUS =1
left join tbl_pay_scheme tps on tps.ID =tpep.PAY_SCHEME_ID 
where da.company_id = $companyId and da.is_active =1 and da.attendance_type in ('R','O')
and da.attendance_date between '$periodfromdate' and '$periodtodate'
group by emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) ,
dept_desc,cata_desc,sm.status_name,tps.NAME,date_of_join
order by cm.cata_desc 

"; 

//echo $sql;
    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


public function njmproductionchecklist($periodfromdate,$periodtodate,$att_payschm) {
$companyId = $this->session->userdata('companyId');

$sql="select tncwdc.eb_id,emp_code,sum(tncwdc.piece_hours) phrs,sum(tncwdc.act_prod_amount+tncwdc.sardhelp_amt  ) prodamt from EMPMILL12.tbl_njm_wages_data_collection tncwdc 
 left join tbl_hrms_ed_official_details theod on theod.eb_id=tncwdc.eb_id and theod.is_active =1
 where tncwdc.date_from ='$periodfromdate' and tncwdc.date_to='$periodtodate' and tncwdc.is_active =1 
 and (tncwdc.production >0 or tncwdc.act_prod_amount>0 or piece_hours>0 or piece_wages>0 or sardhelp_amt>0)
 group by emp_code,tncwdc.eb_id

"; 

$sql="select emp_code ,md.dept_desc Master_Department,tncwdc.qualcode,twqm.quality ,qual_rate,tncwdc.production  ,tncwdc.act_prod_amount from EMPMILL12.tbl_njm_wages_data_collection tncwdc 
 left join tbl_hrms_ed_official_details theod on theod.eb_id=tncwdc.eb_id and theod.is_active =1
 left join EMPMILL12.tbl_wages_quality_master twqm on twqm.q_code =tncwdc.qualcode 
 left join master_department md on twqm.master_dept_id =md.rec_id 
 where tncwdc.date_from ='$periodfromdate' and tncwdc.date_to='$periodtodate' and tncwdc.is_active =1 and (tncwdc.production >0 or tncwdc.piece_wages >0)
 ORDER by qualcode ";
 

//echo $sql;
    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function njmlinehrschecklist($periodfromdate,$periodtodate) {
$companyId = $this->session->userdata('companyId');

$sql="select line_no,
sard_helper,
running_hours,
lost_hours,
wgroup,
no_of_looms,
line_no_id,
ifnull(quantity,0) quantity,
ifnull(pamount,0) pamount 
from EMPMILL12.tbl_run_loom_line_hours trllh where trllh.date_from ='$periodfromdate' and date_to='$periodtodate'
   ORDER by sard_helper ,cast(line_no as unsigned),trllh.wgroup "; 

//echo $sql;
    $query = $this->db->query($sql);

      
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function njmofbsummdata($periodfromdate,$periodtodate,$att_payschm) {
$companyId = $this->session->userdata('companyId');

$sql="select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=350 then amount else 0 end) 'Grossearn',
    max(case when component_id=286 then amount else 0 end) 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    max(case when component_id=343 then amount else 0 end) 'rent',
    max(case when component_id=320 then amount else 0 end) 'landrent',
    max(case when component_id=166 then amount else 0 end) 'advance',
    max(case when component_id=354 then amount else 0 end) 'rsd',
    0 as canteen,
    0 otherded,
    0 roff,
    max(case when component_id=252 then amount else 0 end)+max(case when component_id=351 then amount else 0 end) 'net',
    max(case when component_id=351 then amount else 0 end) 'death',
    max(case when component_id=252 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code,
    tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (166) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'  
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no  
"; 

//echo $sql;
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }






public function nbdlwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select * from (	
   select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=349 then amount else 0 end) 'Grossearn',
    0 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    max(case when component_id=343 then amount else 0 end) 'rent',
    max(case when component_id=345 then amount else 0 end) landrent,
    max(case when component_id=166 then amount else 0 end) 'advance',
    max(case when component_id=354 then amount else 0 end) 'rsd',
    max(case when component_id=291 then amount else 0 end) 'canteen',
    0 othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    max(case when component_id=351 then amount else 0 end) death,
    max(case when component_id=357 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25,0) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code 
    ,tpep.PAYSCHEME_ID,theod.department_id 
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (169) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'  
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no ,theod.department_id
) g where netpay>0
";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


public function mainwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    
    
    
    $sql="   select * from (
   select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=349 then amount else 0 end) 'Grossearn',
    0 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    max(case when component_id=343 then amount else 0 end) 'rent',
    max(case when component_id=345 then amount else 0 end) landrent,
    max(case when component_id=166 then amount else 0 end) 'advance',
    max(case when component_id=354 then amount else 0 end) 'rsd',
    max(case when component_id=291 then amount else 0 end) 'canteen',
    0 othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    max(case when component_id=351 then amount else 0 end) death,
    max(case when component_id=357 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25,0) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code 
    ,tpep.PAYSCHEME_ID ,theod.department_id
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (164) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'  
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no  ,theod.department_id
) g where netpay>0

";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function rtdwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="   select * from (
   select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=349 then amount else 0 end) 'Grossearn',
    0 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    max(case when component_id=343 then amount else 0 end) 'rent',
    max(case when component_id=345 then amount else 0 end) landrent,
    max(case when component_id=166 then amount else 0 end) 'advance',
    max(case when component_id=354 then amount else 0 end) 'rsd',
    max(case when component_id=291 then amount else 0 end) 'canteen',
    0 othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    max(case when component_id=351 then amount else 0 end) death,
    max(case when component_id=357 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25,0) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code 
    ,tpep.PAYSCHEME_ID ,theod.department_id
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (167) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'    
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no  ,theod.department_id
) g where netpay>0


";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function ofbwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=350 then amount else 0 end) 'Grossearn',
    max(case when component_id=286 then amount else 0 end) 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    max(case when component_id=343 then amount else 0 end) 'rent',
    max(case when component_id=320 then amount else 0 end) 'landrent',
    max(case when component_id=166 then amount else 0 end) 'advance',
    max(case when component_id=354 then amount else 0 end) 'rsd',
    0 as canteen,
    0 otherded,
    0 roff,
    max(case when component_id=252 then amount else 0 end)+max(case when component_id=351 then amount else 0 end) 'net',
       max(case when component_id=351 then amount else 0 end) 'death',
    max(case when component_id=252 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code,
    tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (166) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'    
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no 



";

//echo $sql;

    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


    public function bluewgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=20 then amount else 0 end) 'Grossearn',
    max(case when component_id=62 then amount else 0 end) 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    0 rent,
    0 landrent,
    max(case when component_id=54 then amount else 0 end) 'advance',
    0 as rsd,
    0 as canteen,
    0 othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    0 death,
    max(case when component_id=21 then (amount-0) else 0 end) 'netpay',
    0 emppf,
    max(case when component_id=19 then round(amount/.75*3.25) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code
    ,tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (94,96) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'   
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID
,ifsc_code,thebd.bank_acc_no 



";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


    public function staffwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=20 then amount else 0 end) 'Grossearn',
    max(case when component_id=62 then amount else 0 end) 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    0 rent,
    0 landrent,
    max(case when component_id=54 then amount else 0 end) 'advance',
    0 as rsd,
    0 as canteen,
    0 AS othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    0 death,
    max(case when component_id=21 then (amount-0) else 0 end) 'netpay',
    max(case when component_id=18 then round(amount/10*11,0) else 0 end) emppf,
    max(case when component_id=19 then round(amount/.75*3.25) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code,
    tpep.PAYSCHEME_ID 
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (1,90,24,23,21) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'    
group by emp_code,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID,
ifsc_code,thebd.bank_acc_no 




";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }


    public function contwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select theod.emp_code ticket_no,
    CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name) AS emp_name,
    max(case when component_id=66 then amount else 0 end) 'Grossearn',
    max(case when component_id=268 then amount else 0 end)+max(case when component_id=76 then amount else 0 end) 'othern',
    max(case when component_id=18 then amount else 0 end) 'pf',
    max(case when component_id=19 then amount else 0 end) 'esi',
    max(case when component_id=16 then amount else 0 end) 'ptax',
    0 gwf,
    0 'rent',
    0 landrent,
    max(case when component_id=166 then amount else 0 end)-max(case when component_id=291 then amount else 0 end) 'advance',
	0 rsd,
    max(case when component_id=291 then amount else 0 end) 'canteen',
    0 othded,
    0 roff,
    max(case when component_id=21 then amount else 0 end) 'net',
    0 death,
    max(case when component_id=21 then (amount-0) else 0 end) 'netpay',
    0 emppf,
    max(case when component_id=19 then round(amount/.75*3.25) else 0 end) 'empesi',thebd.bank_acc_no,ifsc_code 
    ,tpep.PAYSCHEME_ID ,theod.department_id
from tbl_pay_employee_payroll tpep
left join tbl_pay_period tpp on tpep.PAYPERIOD_ID =tpp.ID and tpp.IS_ACTIVE =1 and tpp.STATUS not in (4)
left join tbl_hrms_ed_personal_details thepd on tpep.EMPLOYEEID =thepd.eb_id
left join tbl_hrms_ed_official_details theod on tpep.EMPLOYEEID =theod.eb_id and theod.is_active =1
left join tbl_hrms_ed_bank_details thebd on thebd.eb_id =tpep.EMPLOYEEID and thebd.is_active =1
where tpep.STATUS =1 and tpep.PAYSCHEME_ID in (163) and tpp.FROM_DATE ='$periodfromdate'
and tpp.TO_DATE ='$periodtodate'     
group by EMP_CODE,
CONCAT(thepd.first_name, ' ', thepd.middle_name, ' ', thepd.last_name),tpep.PAYSCHEME_ID
,
ifsc_code,thebd.bank_acc_no ,theod.department_id

";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }



public function cashwgsbrksummary($att_payschm,$periodfromdate,$periodtodate) {
    $companyId = $this->session->userdata('companyId');
    $sql="select da.eb_no,CONCAT(wm.worker_name, ' ', wm.middle_name, ' ', wm.last_name) wname,   
sum(round((da.working_hours-da.idle_hours )*wm.cash_rate/8,0)) pay,
0	OtherEarnings,0	PF,	0 ESI,0	PTAX,0	GWF,
0 Rent,0	Land_Rent,0	Advance,0	RSD,0	Canteen,0	Other_deduction,0	Roff,
sum(round((da.working_hours-da.idle_hours )*wm.cash_rate/8,0))	NetPayment,	
0 DeathFund,sum(round((da.working_hours-da.idle_hours )*wm.cash_rate/8,0))	NetPayable,wm.dept_id
from daily_attendance da 
left join worker_master wm on wm.eb_id=da.eb_id 
where da.attendance_date between '$periodfromdate'
and '$periodtodate'      and da.attendance_type ='C'
and da.is_active =1 and da.company_id =1 and da.worked_designation_id not in ('1098','1100','1104','1106')
group by da.eb_no,CONCAT(wm.worker_name, ' ', wm.middle_name, ' ', wm.last_name),cash_rate,wm.dept_id

";
    $query = $this->db->query($sql);
    $data=$query->result();
    if ($query->num_rows() > 0) {
   //     var_dump($data);
return $query->result_array(); 
//        return $data;
    } else {
        return array(); // Return an empty array if no results are found
    }

    }




public function jutevowtally($psupvow,$psuptally,$psalevow,$psaletally,$att_jcqty,$att_jtqty,$comp) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $msg='';
    if ($psupvow !='' && $psuptally !='') {
        $sql="select * from EMPMILL12.tbl_tally_link_file ttlf
        where ttlf.vow_name='$psupvow' or ttlf.tally_name='$psuptally'
        and ttlf.company_id =$comp and ttlf.is_active =1 and ttlf.link_for='S'";
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() == 0) {
            $sql="insert into EMPMILL12.tbl_tally_link_file (tally_name,link_for,
            is_active,vow_name,company_id)
            values ('$psuptally','S',1,'$psupvow',$comp)";
            $this->db->query($sql);
            $msg=$msg.'Purchase Supplier VOW/Tally Link Created Successfully ,';        
        } else {
            $msg=$msg.'Purchase Supplier VOW/Tally Already Exists ,';        
        }
    }
    if ($psalevow !='' && $psaletally !='') {
        $sql="select * from EMPMILL12.tbl_tally_link_file ttlf
        where ttlf.vow_name='$psalevow' or ttlf.tally_name='$psaletally'
        and ttlf.company_id =$comp and ttlf.is_active =1 and ttlf.link_for='M'";
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() == 0) {
            $sql="insert into EMPMILL12.tbl_tally_link_file (tally_name,link_for,
            is_active,vow_name,company_id)
            values ('$psaletally','M',1,'$psalevow',$comp)";
            $this->db->query($sql);
            $msg=$msg.'Sale Supplier VOW/Tally Link Created Successfully ,';        
        } else {
            $msg=$msg.'Sale Supplier VOW/Tally Already Exists ,';        
        }
    }

    if ( (strlen($att_jcqty)>0) && (strlen($att_jtqty)>0)) {
        $sql="select id,jute_quality from jute_quality_price_master jqpm 
        where jqpm.company_id =$comp and jute_quality='$att_jcqty'";
        $query = $this->db->query($sql);
        $iddata=$query->result(); 
        $id=$iddata[0]->id;
     //   echo "Jute Quality ID:",$id;
      //  var_dump($iddata);
        $sql="select * from EMPMILL12.tbl_tally_link_file ttlf
        where ((ttlf.vowid=$id) or (ttlf.tally_name='$att_jtqty'))
        and ttlf.company_id =$comp and ttlf.is_active =1 and ttlf.link_for='Q'";
    //    echo $sql;
        $query = $this->db->query($sql);
        $data=$query->result();
        if ($query->num_rows() == 0) {
            $sql="insert into EMPMILL12.tbl_tally_link_file (vowid,tally_name,link_for,
            is_active,vow_name,company_id)
            values ($id ,'$att_jtqty','Q',1,'$att_jcqty',$comp)";
            $this->db->query($sql);
            $msg=$msg.'Jute Quality VOW/Tally Link Created Successfully ,';        
        } else {
            $msg=$msg.'Jute Quality VOW/Tally Already Exists ,';        
        }
    }
  
    $success='success';
    $data[] = [
        'success'=> $success,
        'msg'=> $msg 
    ];
    return $data;

}


public function jutemropen($agent,$gateentryno,$comp) {
        
    // Replace this with your actual database query to fetch MCCodes based on department
    $company_name = $this->session->userdata('companyname');
    $comp = $this->session->userdata('companyId');
    $canteenrate=40;
    $rec_time =  date('Y-m-d H:i:s');
    $stat=3;
    $active=1;
    $userid=$this->session->userdata('userid');
    $updfor='A';
    $agsup='';
    $agjq=0;
    $agit=0;
    $agmk=0;
    $aggd=0;
        //delete prev upload data


//    echo 'agent  '.  $agent; 
    
    $sql="select supp_code from suppliermaster s where company_id=$comp and supp_name='$agent' and supp_type='O' ";
    $scm=$this->db->query($sql);
    $ags=$scm->row()->supp_code; 

    
    $sql="select * from company_agents cg
		join suppliermaster s on s.supp_code =cg.sup_agent_id and s.company_id =cg.company_id 
		where s.supp_type ='O' and cg.company_id =$comp and cg.sup_agent_id ='$ags' ";
   //     echo $sql;
    $scm=$this->db->query($sql);
    $agcomp=$scm->row()->agent_company_id; 
  //  echo 'agent cmp '.$agcomp."</br>";

    




    $sql ="select count(*) norec  from scm_mr_hdr smh where gate_entry_no='$gateentryno' and 
    mr_good_recept_status=21 and company_id=$comp";
    $scm=$this->db->query($sql);
    $norec=$scm->row()->norec;  
    //$res=$this->db->affected_rows();
    //echo $sql;
    //echo $norec;
    if($norec==1){ 
        $sql="select ac_year,mr_print_no,jute_receive_no, smh.supp_code,supp_name,smh.mukam_id,mukam_name  from scm_mr_hdr smh
        left join suppliermaster s on s.supp_code=smh.supp_code and s.company_id=smh.company_id and s.supp_type='J'
        left join mukam m on m.mukam_id=smh.mukam_id 
        where smh.gate_entry_no=$gateentryno";
      //  echo "</br>".$sql;
        $scm=$this->db->query($sql);
        $cmpsup=$scm->row()->supp_name;
        $cmpmuk=$scm->row()->mukam_name;  
        $acyr=$scm->row()->ac_year;  
        $cmpmr=$scm->row()->mr_print_no;
        $cmpsupcd=$scm->row()->supp_code;   
        $cmpyr = substr($cmpmr, -6);   
        $jrcv=$scm->row()->jute_receive_no;
        //echo $cmpsup;
        //echo $cmpyr;
        //echo 'mukam  '.$cmpmuk;
//        echo 'jute recv'.$jrcv;
      $sql = "select supp_code
        from suppliermaster s
        where company_id = $agcomp
          and supp_name = ".$this->db->escape($cmpsup)."
          and supp_type = 'J'
        order by s.supp_code desc
        limit 1";

        //echo "<br>$sql<br>";

$q = $this->db->query($sql);

$agsup = 'nnn';

if ($q->num_rows() > 0) {
    $agsup = $q->row()->supp_code;   // <-- this is the value you want
    //echo "found $agsup check<br>";
} else {
    echo "not found<br>";
}

//echo "ag sup $agsup<br>";




            $sql="select id,cm.company_code from warehouse_details wd 
			left join company_master cm on cm.comp_id =wd.company_id 
			where wd.company_id =$agcomp and name=concat(cm.company_code ,'_JUTE')
			";
            $this->db->query($sql);
$data = $this->db->query($sql);

//$agsup = 'nnn';

            if ($data->num_rows() > 0) {
                $aggd=$data->id;
                //echo 'agent warehouse=='.$aggd;
            }else {
                $sql="insert into warehouse_details (address,company_id,name,type) values (
                concat($data->company_code,'_JUTE'),$comp,concat($data->company_code,'_JUTE'),'J')";
                $this->db->query($sql);  
                $aggd=$this->db->insert_id();
            }   

             
             $sql="select * from mukam m where mukam_name='$cmpmuk' and m.company_id=$agcomp";

            $data = $this->db->query($sql);

 

             // $this->db->query($sql);
     //       $data = $this->db->query($sql);

 //echo $sql;
if ($data->num_rows() > 0) {
                $agm = $data->row()->mukam_id;  
            //echo 'mulam id '.$agm;
            }else {
                $sql="insert into warehouse_details (address,company_id,name,type) values (
                concat($data->company_code,'_JUTE'),$comp,concat($data->company_code,'_JUTE'),'J')";
                //$this->db->query($sql);  
                //$aggd=$this->db->insert_id();
            }   

            //echo "</br>".'mumama'.$agm."</br>";

            $sql="select store_po_prefix from company_master cm where cm.comp_id =$agcomp";
            $this->db->query($sql);
            $data = $this->db->query($sql);
            $agidcd=$data->row()->store_po_prefix;

            $sql="select max(jute_receipt_no)+1 mxsr from scm_mr_hdr smh where company_id=$agcomp 
            and mr_good_recept_status not in (4) and smh.ac_year =$acyr";
            //echo $sql;
            $this->db->query($sql);
            $data = $this->db->query($sql);
            $agsr=$data->row()->mxsr;
            $agmrno=$agidcd.$agsr.$cmpyr;
            //echo ' mr no agent='.$agsr.'=='.$agmrno;

            $sql="select id,cm.company_code from warehouse_details wd 
			left join company_master cm on cm.comp_id =wd.company_id 
			where wd.company_id =$agcomp and name=concat(cm.company_code ,'_JUTE')
			";
            $this->db->query($sql);
            $data = $this->db->query($sql);
             $aggd=$data->row()->id;
//            echo $sql;
  //           echo 'agent warehouse=='.$aggd;  

            $sql="            insert into scm_mr_hdr (
           ac_year,
actual_broker,
actual_supplier,
advised_broker,
agent_address1,
agent_address2,
agent_id,
agent_mr_date,
agent_mr_print_no,
agent_mr_receipt_no,
agent_name,
challan_date,
consignment_date,
consignment_no,
company_id,
created_by,
frieght_paid,
gate_entry_no,
jute_receive_dt,
jute_receipt_no,
mod_by,
mod_on,
mr_print_no,
mukam_id,
po_date,
po_num,
print_count,
source_jute_receive_no,
src_com_id,
src_hdr_id,
mr_good_recept_status,
submitter,
supp_code,
unit_conversion,
vehicle_no,
weight,
remarks,
branch_id,
challan_no,
mr_type,
source_mr_id,
tcs_amount,
tcs_percentage)
select     
ac_year,
actual_broker,
'$agsup' actual_supplier,
advised_broker,
agent_address1,
agent_address2,
agent_id,
agent_mr_date,
agent_mr_print_no,
agent_mr_receipt_no,
agent_name,
challan_date,
consignment_date,
consignment_no,
$agcomp company_id,
created_by,
frieght_paid,
gate_entry_no,
jute_receive_dt,
$agsr jute_receipt_no,
mod_by,
mod_on,
'$agmrno' mr_print_no,
'$agm' mukam_id,
po_date,
po_num,
print_count,
source_jute_receive_no,
$comp src_com_id,
src_hdr_id,
1 mr_good_recept_status,
submitter,
'$agsup' supp_code,
unit_conversion,
vehicle_no,
weight,
remarks,
branch_id,
challan_no,
mr_type,
source_mr_id,
tcs_amount,
tcs_percentage 
from scm_mr_hdr 
where company_id=$comp and gate_entry_no=$gateentryno";
//echo $sql;
$this->db->query($sql);
          $last = $this->db->insert_id();

//echo 'last insert id='.$last;

            $sql="select smli.*,jute_quality from  scm_mr_line_item smli
            left join jute_quality_price_master jqpm on jqpm.id=smli.actual_quality and jqpm.company_id=smli.company_id
            where smli.jute_receive_no in($jrcv) ";
//            $this->db->query($sql);
    //        echo "</br>".$sql;
            $data = $this->db->query($sql);
  //          $cmitem=$data->row()->jute_quality;
            $rows = $data->result(); // multiple objects
            $cmitem='';
            foreach ($rows as $r) {
                $jline = $r->jute_line_item_no;
                
                $cmitem = $r->jute_quality;
                $sql="select id,item_code from jute_quality_price_master im
                where im.company_id =$agcomp and im.jute_quality='$cmitem'";
                //echo $sql;
                $data = $this->db->query($sql);
                $agitcd=$data->row()->item_code;
                $agitid=$data->row()->id;
              //  echo "</br>".$agidcd." - ".$agitcd." - ".$agitid."</br>"    ;

                $sql="insert into scm_mr_line_item (
accepted_weight,
actual_bale,
actual_loose,
actual_quality,
actual_weight,
advised_item_code,
advised_quality,
advised_quantity,
advised_weight,
agent_mr_rate,
allowable_moisture,
bale,
bales_consumed,
claim_dust,
claim_quality,
claims_condition,
premium_amount,
water_damage_amount,
company_id,
created_by,
debit_credit_notes_flag,
deviation,
drums_consumed,
is_active,
item_code,
jute_gate_entry_lineitem_no,
loose,
jute_receive_no,
po_no,
quantity,
quantity_unit,
rate,
remarks,
shortage_kgs,
src_com_id,
src_line_id,
status,
weight_consumed,
total_price,
unit_conversion,
variable_shortage,
warehouse_no,
quality_desc,
ware_hou,
marka,
crop_year,
pota,
sales_bales,
sales_drums,
sales_weight,
jute_issue_rate,
claim_rate,
src_mr_no,
src_mr_line_id,
convert_jute_quality,
convert_jute_type,
percentage,
src_mr_print_no,
converted_bale,
converted_drum,
converted_weight,
jc_stock,
mr_type)
select accepted_weight,
actual_bale,
actual_loose,
$agitid actual_quality,
actual_weight,
$agitcd advised_item_code,
$agitid advised_quality,
advised_quantity,
advised_weight,
agent_mr_rate,
allowable_moisture,
bale,
bales_consumed,
claim_dust,
claim_quality,
claims_condition,
premium_amount,
water_damage_amount,
$agcomp company_id,
created_by,
debit_credit_notes_flag,
deviation,
drums_consumed,
is_active,
$agitcd item_code,
jute_gate_entry_lineitem_no,
loose,
$last jute_receive_no,
po_no,
quantity,
quantity_unit,
rate,
remarks,
shortage_kgs,
$comp src_com_id,
src_line_id,
status,
weight_consumed,
total_price,
unit_conversion,
variable_shortage,
$aggd warehouse_no,
quality_desc,
ware_hou,
marka,
crop_year,
pota,
sales_bales,
sales_drums,
sales_weight,
jute_issue_rate,
claim_rate,
src_mr_no,
src_mr_line_id,
convert_jute_quality,
convert_jute_type,
percentage,
src_mr_print_no,
converted_bale,
converted_drum,
converted_weight,
jc_stock,
mr_type
from scm_mr_line_item where jute_receive_no in ($jrcv) and company_id=$comp and jute_line_item_no=$jline ";
//echo "</br>".$sql;
$this->db->query($sql);


$sql="update scm_mr_hdr set mr_good_recept_status=24,actual_supplier = '$ags',agent_name='$agent',agent_id='$ags'
where jute_receive_no=$jrcv and company_id=$comp ";
//echo $sql;
$this->db->query($sql);


    $success='Success - MR created for Gate Entry No '.$gateentryno;
                    // do something
            }




    $success='Success - MR created for Gate Entry No '.$gateentryno;
    } else {
     $success='Failed - MR already created for Gate Entry No '.$gateentryno;

    }
    
    $datab[] = [
        'success'=> $success,
        'msg'=> $msg 
    ];
    return $data;


}






}

?>