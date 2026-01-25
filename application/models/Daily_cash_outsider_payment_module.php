<?php
class Daily_cash_outsider_payment_module extends CI_Model
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
 
        
	 
          

        
         $sql="select concat(substr( ifnull(tct.shr_name,''),1,3),'/',ifnull(ts.short_name,'')) cntloca,cm.contractor_name,
         ts.short_name,wm.eb_no,
         CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) 
         AS empname,
         dm.dept_desc,d.desig ,shift,working_hours ,rate ,oth_rate ,amount ,tdcop.dept_id ,dm.dept_code 
         from EMPMILL12.tbl_daily_cash_outsider_payment tdcop 
         join worker_master wm on tdcop.eb_id =wm.eb_id 
         join (select * from tbl_hrms_ed_official_details theod where is_active=1) theod on theod.eb_id =tdcop.eb_id  
         join department_master dm on dm.dept_id =tdcop.dept_id 
         join designation d on d.id =tdcop.desig_id 
         left join EMPMILL12.tbl_sublocation ts on ts.subloca_id =tdcop.subloca_id 
         left join contractor_master cm on cm.cont_id =tdcop.cont_id
         left join EMPMILL12.tbl_contractor_type tct ON tdcop.cont_id =tct.cont_id 
         WHERE pay_date ='".$paydate."' and tdcop.company_id=".$companyId." and tdcop.is_active =1";
        if ($itcod) {
            $sql=$sql." and wm.eb_no='".$itcod."'";
        }    
        if ($att_dept) {
            if ($att_dept<>'ALL') {
                $sql=$sql." and dept_desc='".$att_dept."'";
            }    
        }    
        if ($Source) {
          if ($Source<>'ALL') {
            $sql=$sql." and tdcop.shift='".$Source."'";
          }  
        }    


        $sql=$sql." order by dm.dept_code,cm.contractor_name,wm.eb_no "
        ;
        



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
        $from_date  =$pers['from_date'];             
        $companyId=$pers['company'];
        $paydate=$pers['from_date'];
        $compid=$pers['company'];
  
        $Source = $pers['Source'];
        $att_dept = $pers['att_dept'];
        $itcod = $pers['itcod'];

  
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


        $sql="select concat(substr( ifnull(tct.shr_name,''),1,3),'/',ifnull(ts.short_name,'')) cntloca,cm.contractor_name,
        ts.short_name,wm.eb_no,
        CONCAT(trim(wm.worker_name), ' ', IFNULL(trim(wm.middle_name), ''), ' ', IFNULL(trim(wm.last_name), '')) 
        AS empname,
        dm.dept_desc,d.desig ,shift,working_hours ,rate ,oth_rate ,amount ,tdcop.dept_id ,dm.dept_code 
        from EMPMILL12.tbl_daily_cash_outsider_payment tdcop 
        join worker_master wm on tdcop.eb_id =wm.eb_id 
        join (select * from tbl_hrms_ed_official_details theod where is_active=1) theod on theod.eb_id =tdcop.eb_id  
        join department_master dm on dm.dept_id =tdcop.dept_id 
        join designation d on d.id =tdcop.desig_id 
        left join EMPMILL12.tbl_sublocation ts on ts.subloca_id =tdcop.subloca_id 
        left join contractor_master cm on cm.cont_id =tdcop.cont_id
        left join EMPMILL12.tbl_contractor_type tct ON tdcop.cont_id =tct.cont_id 
        WHERE pay_date ='".$paydate."' and tdcop.company_id=".$companyId." and tdcop.is_active =1
       ";
       if ($itcod) {
            $sql=$sql." and wm.eb_no='".$itcod."'";
        }    
        if ($att_dept) {
            if ($att_dept<>'ALL') {
                $sql=$sql." and dept_desc='".$att_dept."'";
            }    
        }    
        if ($Source) {
            if ($Source<>'ALL') {
              $sql=$sql." and tdcop.shift='".$Source."'";
            }  
          }    
         $sql=$sql." order by dm.dept_code,cm.contractor_name,wm.eb_no "
        ;
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
	// da.attendance_type in ('R', 'O', 'C')
	// 	and da.attendance_source in ('A', 'M')
	
}
?>