<?php
class Overstay_after_leave_report_model extends CI_Model
{

	var $table = 'daily_attendance da ';	
	var $column_order = array(null, 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable orderable
	var $column_search = array( 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    // var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name');
	
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_desig = (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$att_spells = (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
		$eb_no = $_POST['eb_no'];
		$att_cat_att = $_POST['att_cat_att'];
        $srno = $_POST['srno'];


        $compid=$companyId;
        $paydate= $from_date;
    	$Source = $_POST['Source'];
		$att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept']: null);
		$itcod = $_POST['itcod'];
        
        $fromdate=$from_date;
        $todate=$to_date;
 
	 
          

        
         $sql="select emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
         dept_desc,cata_desc,leave_type_description,k.* from (
            select h.*,g.jebid,ifnull(jdate,'') jdate,
             case when jdate is null then (CURRENT_DATE()-leave_to_date-1) 
             else jdate-leave_to_date-1 end 
             ovdate from (
             SELECT lt.eb_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate
             FROM leave_transactions lt
             LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
             WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
             AND da.attendance_type = 'R'
             GROUP BY lt.eb_id, lt.leave_from_date, lt.leave_to_date
         ) h left join 
         (    
         SELECT da.eb_id jebid, MIN(da.attendance_date) AS jdate
         FROM (
             SELECT lt.eb_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate,
                    MIN(da.attendance_date) - lt.leave_to_date AS ovrstay
             FROM leave_transactions lt
             LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
             WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
             AND da.attendance_type = 'R'
             GROUP BY lt.eb_id, lt.leave_from_date, lt.leave_to_date
         ) k
         inner JOIN daily_attendance da ON k.eb_id = da.eb_id
         WHERE da.is_active = 1
         AND da.attendance_date > k.leave_to_date
         GROUP BY k.eb_id
         ) g on h.eb_id=g.jebid    
         ) k 
         left join (
         select * from tbl_hrms_ed_official_details where is_active=1  
         ) theod on k.eb_id=theod.eb_id
         join  tbl_hrms_ed_personal_details thepd  on k.eb_id= thepd.eb_id
         join department_master dm on theod.department_id=dm.dept_id
         join category_master cm on cm.cata_id=catagory_id
         join leave_types lt2 on lt2.leave_type_id=k.leave_type_id
        where ovdate>=1 and thepd.company_id=".$compid  ;

        $sql="select emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
        dept_desc,cata_desc,leave_type_description,k.* from (
            select h.*,g.*,
            case when jdate is null then (CURRENT_DATE()-leave_to_date-1) 
            else jdate-leave_to_date-1 end 
            ovdate from (
            SELECT lt.eb_id,leave_type_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate
            FROM leave_transactions lt
            LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
            WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
            AND da.attendance_type = 'R'
            GROUP BY lt.eb_id, leave_type_id,lt.leave_from_date, lt.leave_to_date
        ) h left join 
        (    
        SELECT da.eb_id jebid, MIN(da.attendance_date) AS jdate
        FROM (
            SELECT lt.eb_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate,
                   MIN(da.attendance_date) - lt.leave_to_date AS ovrstay
            FROM leave_transactions lt
            LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
            WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
            AND da.attendance_type = 'R'
            GROUP BY lt.eb_id, lt.leave_from_date, lt.leave_to_date
        ) k
        inner JOIN daily_attendance da ON k.eb_id = da.eb_id
        WHERE da.is_active = 1
        AND da.attendance_date > k.leave_to_date
        GROUP BY k.eb_id
        ) g on h.eb_id=g.jebid    
        ) k 
        left join (
        select * from tbl_hrms_ed_official_details where is_active=1  
        ) theod on k.eb_id=theod.eb_id
        join  tbl_hrms_ed_personal_details thepd  on k.eb_id= thepd.eb_id
        join department_master dm on theod.department_id=dm.dept_id
        join category_master cm on cm.cata_id=catagory_id
        join leave_types lt2 on lt2.leave_type_id=k.leave_type_id
        where ovdate>=1 and thepd.company_id=".$compid." and thepd.is_active=1"
        ;

        if ($itcod) {
            $sql=$sql." and theod.emp_code='".$itcod."'";
        }    
        if ($att_dept) {
            if ($att_dept<>'ALL') {
                $sql=$sql." and dept_desc='".$att_dept."'";
            }    
        }    


        $sql=$sql."   order by leave_to_date
        ";
        



//		echo $sql;
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
//		 $this->varaha->print_arrays($sql);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
	//	 $this->varaha->print_arrays($this->db->last_query());
		// $resdata=array(
		// 	'result' => $query->result(),
		// 	'num_rows' => $query->num_rows(),
		// );
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

		$att_cat = $pers['att_cat'];
        $srno = $pers['srno'];
		$att_cat_att = $pers['att_cat_att'];
        $fromdate  =$pers['from_date'];             
        $companyId=$pers['company'];
        $todate  =$pers['to_date'];    
        $paydate=$pers['from_date'];
        $compid=$pers['company'];
  
        $Source = $pers['Source'];
        $att_dept = $pers['att_dept'];
        $itcod = $pers['itcod'];

  
        $sql="select emp_code,CONCAT(trim(thepd.first_name), ' ', IFNULL(trim(thepd.middle_name), ''), ' ', IFNULL(trim(thepd.last_name), '')) AS wname,
        dept_desc,cata_desc,leave_type_description,k.* from (
            select h.*,g.jebid,ifnull(jdate,'') jdate,
            case when jdate is null then (CURRENT_DATE()-leave_to_date-1) 
            else jdate-leave_to_date-1 end 
            ovdate from (
            SELECT lt.eb_id,leave_type_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate
            FROM leave_transactions lt
            LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
            WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
            AND da.attendance_type = 'R'
            GROUP BY lt.eb_id, leave_type_id,lt.leave_from_date, lt.leave_to_date
        ) h left join 
        (    
        SELECT da.eb_id jebid, MIN(da.attendance_date) AS jdate
        FROM (
            SELECT lt.eb_id, lt.leave_from_date, lt.leave_to_date, MIN(da.attendance_date) AS joindate,
                   MIN(da.attendance_date) - lt.leave_to_date AS ovrstay
            FROM leave_transactions lt
            LEFT JOIN daily_attendance da ON lt.eb_id = da.eb_id
            WHERE lt.leave_to_date >= '".$fromdate."' and lt.leave_to_date<='".$todate."'
            AND da.attendance_type = 'R'
            GROUP BY lt.eb_id, lt.leave_from_date, lt.leave_to_date
        ) k
        inner JOIN daily_attendance da ON k.eb_id = da.eb_id
        WHERE da.is_active = 1
        AND da.attendance_date > k.leave_to_date
        GROUP BY k.eb_id
        ) g on h.eb_id=g.jebid    
        ) k 
        left join (
        select * from tbl_hrms_ed_official_details where is_active=1  
        ) theod on k.eb_id=theod.eb_id
        join  tbl_hrms_ed_personal_details thepd  on k.eb_id= thepd.eb_id
        join department_master dm on theod.department_id=dm.dept_id
        join category_master cm on cm.cata_id=catagory_id
        join leave_types lt2 on lt2.leave_type_id=k.leave_type_id
        where ovdate>=1 and thepd.company_id=".$compid." and thepd.is_active=1"
        ;

       if ($itcod) {
           $sql=$sql." and theod.emp_code='".$itcod."'";
       }    
       if ($att_dept) {
           if ($att_dept<>'ALL') {
               $sql=$sql." and dept_desc='".$att_dept."'";
           }    
       }    


       $sql=$sql."   order by leave_to_date
       ";
       


 
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
	// da.attendance_type in ('R', 'O', 'C')
	// 	and da.attendance_source in ('A', 'M')
	
}
?>