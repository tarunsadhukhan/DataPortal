<?php
class Employee_Working_details_model extends CI_Model
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


    private function _get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $Date = $from_date;
        $ldate = date('Y-m-d', strtotime($Date . ' - 15 days'));
        $itcode = $_POST['itcod'];
        $srno = $_POST['srno'];
        $Source = $_POST['Source'];
        $att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept'] : null);

 
$sql="select w.*,theod.emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
dept_desc,status_name,cata_desc from (
select  yearmn,eb_id,sum(wdays) wdays,sum(lvdays) lvdays,sum(hldays) hldays,sum(wdays+lvdays+hldays) twdays from (
select concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) yearmn,eb_id,
ceil(sum(working_hours-idle_hours)/7.5) wdays,0 lvdays,0 hldays
from daily_attendance da where is_active =1 and company_id =" . $companyId . "
and attendance_date between '" . $from_date . "' and '" . $to_date . "' and spell='C' and da.attendance_type='R'
group by concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) ,eb_id,eb_no
union all
select concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) yearmn,eb_id,
ceil(sum(working_hours-idle_hours)/8) wdays,0 lvdays,0 hldays
from daily_attendance da where is_active =1 and company_id =" . $companyId . "
and attendance_date between '" . $from_date . "' and '" . $to_date . "' and spell<>'C' and attendance_type='R'
group by concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) ,eb_id,eb_no
union all
select concat(substr(leave_date,1,4),substr(leave_date,6,2)) yearmn,eb_id,0 wdays,count(*) as lvdays,0 hldays
from leave_transactions lt 
join (select * from leave_tran_details ltd  where ltd.is_active=1) ltd on lt.leave_transaction_id =ltd.ltran_id
and lt.status =3 and lt.company_id=" . $companyId . " and leave_date between '" . $from_date . "' and '" . $to_date . "'
group by concat(substr(leave_date,1,4),substr(leave_date,6,2)),eb_id
union all
select concat(substr(holiday_date,1,4),substr(holiday_date,6,2)) yearmn,eb_id,0 wdays,0 as lvdays,count(*) hldays
from holiday_master hm  
join (select * from tbl_hrms_holiday_transactions thht  where thht.is_active=1) thht on hm.id=thht.holiday_id
where hm.company_id=2 and holiday_date between '" . $from_date . "' and '" . $to_date . "'
group by concat(substr(holiday_date,1,4),substr(holiday_date,6,2)),eb_id
) g group by yearmn ,eb_id 
) w left join tbl_hrms_ed_personal_details thepd on w.eb_id=thepd.eb_id
left join (select eb_id,emp_code,department_id,catagory_id from tbl_hrms_ed_official_details theod where is_active=1 ) theod on theod.eb_id=w.eb_id 
left join department_master dm on theod.department_id=dm.dept_id
left join status_master sm on thepd.status=sm.status_id
left join category_master cm on theod.catagory_id=cm.cata_id
where thepd.company_id=" . $companyId 
;


        $n = 0;
 //       if (strlen($itcode . $srno) > 0) {
//            $sql = $sql . " where ";
            if ($itcode) {
                $sql = $sql . " and emp_code='" . $itcode . "'";
                $n++;
            }
            if ($srno) {
                if ($n == 0) {
         //           $sql = $sql . "  peff<" . $srno;
                } else {
         //           $sql = $sql . " and peff<" . $srno;
                }
//            }


        }
        if ($att_dept != '0' && $att_dept != '' && $att_dept != null) {
            $sql .= " and dept_desc= '" . $att_dept . "'";
        }

        $date1 = $from_date;
        $date2 = $to_date;

        $dt1 = date_create($date1);
        $dt2 = date_create($date2);
        $diff = date_diff($dt1, $dt2);
        $dfm = $diff->format("%a") + 1;

        //echo $dfm;

        $date3 = $dt1;
        $date4 = $dt2;
        $date5 = $dt1;

        $x = 1;
        $query = "select emp_code,wname,status_name,dept_desc,cata_desc,";
/*
        $sqlp = "select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
        between '" . $from_date . "' and '" . $to_date . "' and company_id =" . $companyId;
*/
        $sqlp="WITH RECURSIVE YearMonths AS (
            SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn, '" . $from_date . "' AS start_date
            UNION ALL
            SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
            FROM YearMonths
            WHERE start_date <= '" . $to_date . "'
        )
        SELECT yearmn
        FROM YearMonths";

        $sqlp="WITH RECURSIVE YearMonths AS (
        SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn,  '" . $from_date . "' AS start_date
        UNION ALL
        SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
        FROM YearMonths
        WHERE DATE_ADD(start_date, INTERVAL 1 MONTH) <= '" . $to_date . "'
    )
    SELECT yearmn
    FROM YearMonths";
            $nmmon=0;
        $queryp = $this->db->query($sqlp);
        $datap = $queryp->result();
        foreach ($datap as $recordp) {
            //        $bid=$record->BUSINESSUNIT_ID;
            $string = $recordp->yearmn;

            $yr = substr($string, 0, 4);
            $month = substr($string, 4, 2);
            $dm = $yr . '/' . $month;
            $nmmon++;
            //$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";

            if ($Source == 1) {
                $query = $query . "MAX(CASE WHEN yearmn = '$string' THEN wdays ELSE 0 END) AS '$dm',";
            } else {
                $query = $query . "MAX(CASE WHEN yearmn = '$string' THEN twdays ELSE 0 END) AS '$dm',";

            }
            //$date3=date_add($date3,date_interval_create_from_date_string("1 days"));




        }
 
        //$query=rtrim($query)."
//	count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";
/*
        if ($Source == 1) {
            $query = rtrim($query) . "count(*) 'Total_Days',round(sum(wdays),0)  'Average'";
        } else {
            $query = rtrim($query) . "count(*) 'Total_Days',round(sum(twdays),0)	'Average'";
        }
*/
        
        if ($Source == 1) {
            $query = rtrim($query) . "round(sum(wdays)/$nmmon,1) 'Total_Days',round(sum(wdays),0)  'Average'";
        } else {
            $query = rtrim($query) . "round(sum(twdays)/$nmmon,1) 'Total_Days',round(sum(twdays),0)	'Average'";
        }


        // distinct(peff) 'Avg Eff'";
        $cmpn = 'Njm';

        $query = rtrim($query, ", ");
        $query = $query . " from ( " . $sql . ") h group by emp_code,wname,dept_desc,cata_desc,status_name order by dept_desc,emp_code";

        $sql = $query;


        	//	echo $sql;

        return $sql;
    }

    function get_datatables($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {

        $sql = $this->_get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
        if ($_POST['length'] != -1)
            $sql = $sql .= " LIMIT " . $_POST['start'] . "," . $_POST['length'];
        $query = $this->db->query($sql);
        return $query->result();
    }

    function count_filtered($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $sql = $this->_get_datatables_query($mainmenuId, $submenuId, $companyId, $from_date, $to_date);
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    public function count_all($mainmenuId, $submenuId, $companyId, $from_date, $to_date)
    {
        $this->db->from($this->table);
        return $this->db->count_all_results();
    }

    public function directReport($pers)
    {


        $itcode = $pers['itcod'];


        $Date = $pers['from_date'];
        $ldate = date('Y-m-d', strtotime($Date . ' - 15 days'));
        $itcode = $_POST['itcod'];
        $srno = $_POST['srno'];
        //     echo $srno;
        $Source = $pers['Source'];

        $from_date = $pers['from_date'];
        $to_date = $pers['to_date'];
        $companyId = $pers['company'];
        //echo date('Y-m-d', strtotime($Date. ' + 10 days'));
        $att_dept = $pers['att_dept'];
//         (isset($_POST['att_dept']) ? $_POST['att_dept'] : null);




        $sql = "select w.*,theod.emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
dept_desc,status_name,cata_desc from (
select  yearmn,eb_id,sum(wdays) wdays,sum(lvdays) lvdays,sum(hldays) hldays,sum(wdays+lvdays+hldays) twdays from (
select concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) yearmn,eb_id,
ceil(sum(working_hours-idle_hours)/7.5) wdays,0 lvdays,0 hldays
from daily_attendance da where is_active =1 and company_id =" . $companyId . "
and attendance_date between '" . $from_date . "' and '" . $to_date . "' and spell='C' and da.attendance_type='R'
group by concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) ,eb_id,eb_no
union all
select concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) yearmn,eb_id,
ceil(sum(working_hours-idle_hours)/8) wdays,0 lvdays,0 hldays
from daily_attendance da where is_active =1 and company_id =" . $companyId . "
and attendance_date between '" . $from_date . "' and '" . $to_date . "' and spell<>'C' and attendance_type='R'
group by concat(substr(attendance_date,1,4),substr(attendance_date,6,2)) ,eb_id,eb_no
union all
select concat(substr(leave_date,1,4),substr(leave_date,6,2)) yearmn,eb_id,0 wdays,count(*) as lvdays,0 hldays
from leave_transactions lt 
join (select * from leave_tran_details ltd  where ltd.is_active=1) ltd on lt.leave_transaction_id =ltd.ltran_id
and lt.status =3 and lt.company_id=" . $companyId . " and leave_date between '" . $from_date . "' and '" . $to_date . "'
group by concat(substr(leave_date,1,4),substr(leave_date,6,2)),eb_id
union all
select concat(substr(holiday_date,1,4),substr(holiday_date,6,2)) yearmn,eb_id,0 wdays,0 as lvdays,count(*) hldays
from holiday_master hm  
join (select * from tbl_hrms_holiday_transactions thht  where thht.is_active=1) thht on hm.id=thht.holiday_id
where hm.company_id=2 and holiday_date between '" . $from_date . "' and '" . $to_date . "'
group by concat(substr(holiday_date,1,4),substr(holiday_date,6,2)),eb_id
) g group by yearmn ,eb_id 
) w left join tbl_hrms_ed_personal_details thepd on w.eb_id=thepd.eb_id
left join (select eb_id,emp_code,department_id,catagory_id from tbl_hrms_ed_official_details theod where is_active=1 ) theod on theod.eb_id=w.eb_id 
left join department_master dm on theod.department_id=dm.dept_id
left join status_master sm on thepd.status=sm.status_id
left join category_master cm on theod.catagory_id=cm.cata_id
where thepd.company_id=" . $companyId
        ;


        $n = 0;
        //       if (strlen($itcode . $srno) > 0) {
//            $sql = $sql . " where ";
        if ($itcode) {
            $sql = $sql . " and emp_code='" . $itcode . "'";
            $n++;
        }
        if ($srno) {
            if ($n == 0) {
                //           $sql = $sql . "  peff<" . $srno;
            } else {
                //           $sql = $sql . " and peff<" . $srno;
            }
            //            }


        }
        if ($att_dept != '0' && $att_dept != '' && $att_dept != null) {
            $sql .= " and dept_desc= '" . $att_dept . "'";
        }

        $date1 = $from_date;
        $date2 = $to_date;

        $dt1 = date_create($date1);
        $dt2 = date_create($date2);
        $diff = date_diff($dt1, $dt2);
        $dfm = $diff->format("%a") + 1;

        //echo $dfm;

        $date3 = $dt1;
        $date4 = $dt2;
        $date5 = $dt1;

        $x = 1;
        $query = "select emp_code,wname,status_name,dept_desc,cata_desc,";
        /*
                $sqlp = "select date_to from EMPMILL12.tbl_report_period trp where trp.date_from 
                between '" . $from_date . "' and '" . $to_date . "' and company_id =" . $companyId;
        */
        $nmmon = 0;
        $sqlp = "WITH RECURSIVE YearMonths AS (
            SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn, '" . $from_date . "' AS start_date
            UNION ALL
            SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
            FROM YearMonths
            WHERE start_date <= '" . $to_date . "'
        )
        SELECT yearmn
        FROM YearMonths";
        $sqlp = "WITH RECURSIVE YearMonths AS (
        SELECT DATE_FORMAT('" . $from_date . "', '%Y%m') AS yearmn,  '" . $from_date . "' AS start_date
        UNION ALL
        SELECT DATE_FORMAT(DATE_ADD(start_date, INTERVAL 1 MONTH), '%Y%m'), DATE_ADD(start_date, INTERVAL 1 MONTH)
        FROM YearMonths
        WHERE DATE_ADD(start_date, INTERVAL 1 MONTH) <= '" . $to_date . "'
    )
    SELECT yearmn
    FROM YearMonths";

        $queryp = $this->db->query($sqlp);
        $datap = $queryp->result();
        foreach ($datap as $recordp) {
            //        $bid=$record->BUSINESSUNIT_ID;
            $string = $recordp->yearmn;

            $yr = substr($string, 0, 4);
            $month = substr($string, 4, 2);
            $dm = $yr . '/' . $month;
            $nmmon++;

            //$query=$query."MAX(CASE WHEN loom_date = '$string' THEN eff ELSE 0 END) AS '$dm',";

            if ($Source == 1) {
                $query = $query . "MAX(CASE WHEN yearmn = '$string' THEN wdays ELSE 0 END) AS '$dm',";
            } else {
                $query = $query . "MAX(CASE WHEN yearmn = '$string' THEN twdays ELSE 0 END) AS '$dm',";

            }
            //$date3=date_add($date3,date_interval_create_from_date_string("1 days"));




        }

        //$query=rtrim($query)."
//	count(*) 'Total_Days',round(sum(whrs*eff)/sum(whrs),2)	'Avg_eff'";

        if ($Source == 1) {
            $query = rtrim($query) . "round(sum(wdays)/$nmmon,1) 'Total_Days',round(sum(wdays),0)  'Average'";
        } else {
            $query = rtrim($query) . "round(sum(twdays)/$nmmon,1) 'Total_Days',round(sum(twdays),0)	'Average'";
        }
        /*
                if ($Source == 1) {
                    $query = rtrim($query) . "round(sum(wdays)/count(*),1) 'Total_Days',round(sum(wdays),0)  'Average'";
                } else {
                    $query = rtrim($query) . "round(sum(wdays)/count(*),1) 'Total_Days',round(sum(twdays),0)	'Average'";
                }
        */
        // distinct(peff) 'Avg Eff'";z
        $cmpn = 'Njm';

        $query = rtrim($query, ", ");
        $query = $query . " from ( " . $sql . ") h group by emp_code,wname,dept_desc,cata_desc,status_name order by dept_desc,emp_code";

        $sql = $query;


        //	echo $sql;


        //echo $sql;
        $q = $this->db->query($sql);
        // $this->varaha->print_arrays($pers, $this->db->last_query());
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $row) {
                $data[] = $row;
            }
            return $data;
        }
        return false;
    }

}
?>