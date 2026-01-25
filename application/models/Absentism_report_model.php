<?php
class Absentism_report_model extends CI_Model
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
		$Source = $_POST['Source'];
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept']: null);
		$att_desig = (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$att_spells = (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
		$eb_no = $_POST['eb_no'];
		$itcod = $_POST['itcod'];
		$att_cat_att = $_POST['att_cat_att'];
        $srno = $_POST['srno'];
		// $this->varaha->print_arrays($att_dept);

//echo 'filter'.$srno.'===='.$att_cat_att.'='.$att_dept.'='.$itcod;	

 
	 
        $sql="select * from (select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS empname,dept_desc,cata_desc,
        DATE_FORMAT(mxdate,'%d-%m-%Y') mxdate,case when '".$from_date."'>mxdate then DATEDIFF('".$from_date."', mxdate)  else 0 end  absent_for, 
        wm.company_id,wm.cata_id
        from worker_master wm 
        join department_master dm on wm.dept_id =dm.dept_id and dm.company_id=wm.company_id  
        join category_master cm on wm.cata_id=cm.cata_id
        join (
        select eb_id,company_id,max(attdate) mxdate from (
        select eb_id,eb_no, attendance_date attdate,attendance_type,company_id,worked_department_id dept_id,worked_designation_id desg_id,spell,working_hours -idle_hours whrs
        from daily_attendance da where is_active =1
        union all
        select thht.eb_id,eb_no,hm.holiday_date attdate,'H' attendance_type,hm.company_id,wm.dept_id ,wm.desg_id,' ' spell,8 whrs  from tbl_hrms_holiday_transactions thht
        join holiday_master hm on thht.holiday_id =hm.id 
        join worker_master wm on thht.eb_id =wm.eb_id 
        where thht.is_active =1 
        union all
        select lt.eb_id,wm.eb_no,ltd.leave_date attdate,ltp.leave_type_code atttype  ,lt.company_id,wm.dept_id,wm.desg_id,' ' spell,8 whrs  from leave_tran_details ltd 
        join leave_transactions lt on ltd.ltran_id =lt.leave_transaction_id
        join leave_types ltp on ltp.leave_type_id  =lt.leave_type_id 
        join worker_master wm on wm.eb_id =lt.eb_id 
        where ltd.is_active =1 
        ) g where  attdate<='".$from_date."' 
        group by eb_id,company_id
        ) k on wm.eb_id=k.eb_id
        where wm.active='Y' 
        ) g where company_id=".$companyId;
    	if($itcod){
			$sql .= " and  eb_no= '".$itcod."'";
		}
             if ($att_cat_att>0) {
                $sql .= " and   cata_id=".$att_cat_att;
            }
            if ($srno>0) {
                $sql .= "  and absent_for>=".$srno;
            } else {    
                $sql .= "  and absent_for>0";
            }
            if ($att_dept>0) {
                $sql .= "  and  dept_desc='".$att_dept."'";
            }
         

        $sql=$sql." order by dept_desc,cata_desc,eb_no";
        


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

        $att_dept = $pers['att_dept'];
		$att_cat = $pers['att_cat'];
        $itcod = $pers['itcod'];
        $srno = $pers['srno'];
		$att_cat_att = $pers['att_cat_att'];
        $from_date  =$pers['from_date'];             
        $companyId=$pers['company'];

        $sql="select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS empname,dept_desc,cata_desc,
        DATE_FORMAT(mxdate,'%d-%m-%Y') mxdate,case when '".$pers['from_date']."'>mxdate then DATEDIFF('".$pers['from_date']."', mxdate)  else 0 end  absent_for 
        from worker_master wm 
        join department_master dm on wm.dept_id =dm.dept_id and dm.company_id=wm.company_id  
        join category_master cm on wm.cata_id=cm.cata_id
        join (
        select eb_id,company_id,max(attdate) mxdate from (
        select eb_id,eb_no, attendance_date attdate,attendance_type,company_id,worked_department_id dept_id,worked_designation_id desg_id,spell,working_hours -idle_hours whrs
        from daily_attendance da where is_active =1
        union all
        select thht.eb_id,eb_no,hm.holiday_date attdate,'H' attendance_type,hm.company_id,wm.dept_id ,wm.desg_id,' ' spell,8 whrs  from tbl_hrms_holiday_transactions thht
        join holiday_master hm on thht.holiday_id =hm.id 
        join worker_master wm on thht.eb_id =wm.eb_id 
        where thht.is_active =1 
        union all
        select lt.eb_id,wm.eb_no,ltd.leave_date attdate,ltp.leave_type_code atttype  ,lt.company_id,wm.dept_id,wm.desg_id,' ' spell,8 whrs  from leave_tran_details ltd 
        join leave_transactions lt on ltd.ltran_id =lt.leave_transaction_id
        join leave_types ltp on ltp.leave_type_id  =lt.leave_type_id 
        join worker_master wm on wm.eb_id =lt.eb_id 
        where ltd.is_active =1 
        ) g where  attdate<='".$pers['from_date']."' 
        group by eb_id,company_id
        ) k on wm.eb_id=k.eb_id
        where wm.active='Y' and wm.company_id=".$pers['company']."
        order by dept_desc,cata_desc,eb_no";

        $sql="select * from (select wm.eb_no,CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) AS empname,dept_desc,cata_desc,
        DATE_FORMAT(mxdate,'%d-%m-%Y') mxdate,case when '".$from_date."'>mxdate then DATEDIFF('".$from_date."', mxdate)  else 0 end  absent_for, 
        wm.company_id,wm.cata_id
        from worker_master wm 
        join department_master dm on wm.dept_id =dm.dept_id and dm.company_id=wm.company_id  
        join category_master cm on wm.cata_id=cm.cata_id
        join (
        select eb_id,company_id,max(attdate) mxdate from (
        select eb_id,eb_no, attendance_date attdate,attendance_type,company_id,worked_department_id dept_id,worked_designation_id desg_id,spell,working_hours -idle_hours whrs
        from daily_attendance da where is_active =1
        union all
        select thht.eb_id,eb_no,hm.holiday_date attdate,'H' attendance_type,hm.company_id,wm.dept_id ,wm.desg_id,' ' spell,8 whrs  from tbl_hrms_holiday_transactions thht
        join holiday_master hm on thht.holiday_id =hm.id 
        join worker_master wm on thht.eb_id =wm.eb_id 
        where thht.is_active =1 
        union all
        select lt.eb_id,wm.eb_no,ltd.leave_date attdate,ltp.leave_type_code atttype  ,lt.company_id,wm.dept_id,wm.desg_id,' ' spell,8 whrs  from leave_tran_details ltd 
        join leave_transactions lt on ltd.ltran_id =lt.leave_transaction_id
        join leave_types ltp on ltp.leave_type_id  =lt.leave_type_id 
        join worker_master wm on wm.eb_id =lt.eb_id 
        where ltd.is_active =1 
        ) g where  attdate<='".$from_date."' 
        group by eb_id,company_id
        ) k on wm.eb_id=k.eb_id
        where wm.active='Y' 
        ) g where company_id=".$companyId;
    	if($itcod){
			$sql .= " and  eb_no= '".$itcod."'";
		}
             if ($att_cat_att>0) {
                $sql .= " and   cata_id=".$att_cat_att;
            }
            if ($srno>0) {
                $sql .= "  and absent_for>=".$srno;
            } else {    
                $sql .= "  and absent_for>0";
            }
            if ($att_dept>0) {
                $sql .= "  and  dept_desc='".$att_dept."'";
            }
         

        $sql=$sql." order by dept_desc,cata_desc,eb_no";
        
  


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