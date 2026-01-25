<?php
class Worker_Master_With_Last_Working_Day_Model extends CI_Model
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


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $filters = array())
	{
		// Use POST data if filters not provided (default behavior)
		$Source = isset($filters['Source']) ? $filters['Source'] : (isset($_POST['Source']) ? $_POST['Source'] : null);
		$att_type = isset($filters['att_type']) ? $filters['att_type'] : (isset($_POST['att_type']) ? $_POST['att_type'] : null);
		$att_spells = isset($filters['att_spells']) ? $filters['att_spells'] : (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
        $att_status = isset($filters['att_status']) ? $filters['att_status'] : (isset($_POST['att_status']) ? $_POST['att_status'] : null);
		$att_dept = isset($filters['att_dept']) ? $filters['att_dept'] : (isset($_POST['att_dept']) ? $_POST['att_dept'] : null);
		$att_desig = isset($filters['att_desig']) ? $filters['att_desig'] : (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$eb_no = isset($filters['eb_no']) ? $filters['eb_no'] : (isset($_POST['eb_no']) ? $_POST['eb_no'] : null);
		$att_cat = isset($filters['att_cat']) ? $filters['att_cat'] : (isset($_POST['att_cat']) ? $_POST['att_cat'] : null);
        $esino = isset($filters['itcod']) ? $filters['itcod'] : (isset($_POST['itcod']) ? $_POST['itcod'] : null);
        $pfno = isset($filters['srno']) ? $filters['srno'] : (isset($_POST['srno']) ? $_POST['srno'] : null);
        $uanno = isset($filters['mrno']) ? $filters['mrno'] : (isset($_POST['mrno']) ? $_POST['mrno'] : null);
        $bankaccno = isset($filters['itemdesc']) ? $filters['itemdesc'] : (isset($_POST['itemdesc']) ? $_POST['itemdesc'] : null);
        $actv = isset($filters['jutesummary']) ? $filters['jutesummary'] : (isset($_POST['jutesummary']) ? $_POST['jutesummary'] : null);

	
		$sql="select thepd.eb_id,theod.emp_code,CONCAT(first_name, ' ', IFNULL(middle_name, ' '), ' ', IFNULL(last_name, ' ')) AS emp_name,
        thepd.gender,dm.dept_code,dm.dept_desc,d.desig,cm.cata_desc,thepd.date_of_birth,theod.date_of_join,thee.esi_no,thep.pf_no,thep.pf_date_of_join,thep.pf_uan_no,
        thebd.bank_acc_no ,thebd.ifsc_code ,thebd.bank_name,cnt.contractor_name,sm.status_name,tps.NAME ,
        case when thepd.is_active=1 then 'Active' else 'InActive' end isactive,da.last_workings  
        from tbl_hrms_ed_personal_details thepd
        left join tbl_hrms_ed_official_details theod on thepd.eb_id  =theod.eb_id and theod.is_active =1                                                                                                           								
        left join tbl_hrms_ed_bank_details thebd on thepd.eb_id =thebd.eb_id and thebd.is_active =1  
        left join tbl_hrms_ed_esi thee on thepd.eb_id  =thee.eb_id and thee.is_active =1                                                                                                            								
        left join tbl_hrms_ed_pf thep on thepd.eb_id  =thep.eb_id  and thep.is_active =1
        left join department_master dm on dm.dept_id=theod.department_id 
        left join designation d on d.id=theod.designation_id 
        left join category_master cm on cm.cata_id=theod.catagory_id 
        left join contractor_master cnt on cnt.cont_id=theod.contractor_id 
        left join status_master sm on sm.status_id =thepd.status
        left join tbl_pay_employee_payscheme tpep on thepd.eb_id=tpep.EMPLOYEEID and tpep.STATUS =1
        left join tbl_pay_scheme tps on tps.ID =tpep.PAY_SCHEME_ID
        left join (select eb_id,max(attendance_date) last_workings from daily_attendance where is_active=1 group by eb_id) da 
        on da.eb_id=thepd.eb_id
        where thepd.company_id=$companyId and theod.emp_code is not null ";

 
    	 if($eb_no){
			$sql .= " and emp_code= '".$eb_no."'";
		}
		if($att_status){
			$sql .= " and status_name= '".$att_status."'";
		}
 		if($att_dept!='0' && $att_dept!=''  && $att_dept!=null){
			$sql .= " and dept_desc= '".$att_dept."'";
		}
		if($att_desig!=0 && $att_desig!=''  && $att_desig!=null){
			$sql .= " and desig= '".$att_desig."'";
		}
		if($esino!=0 && $esino!=''  && $esino!=null){
			$sql .= " and esi_no= '".$esino."'";
		}
 		
		if($pfno!=0 && $pfno!=''  && $pfno!=null){
			$sql .= " and pf_no= '".$pfno."'";
		}
		if($uanno!=0 && $uanno!=''  && $uanno!=null){
			$sql .= " and pf_uan_no= '".$uanno."'";
		}	
		if($bankaccno!=0 && $bankaccno!=''  && $bankaccno!=null){
			$sql .= " and bank_acc_no= '".$bankaccno."'";
		}
		if($att_cat){
			$sql .= " and cata_desc= '".$att_cat."'";
		}
		if($actv){
			if($actv=='Active'){
				$sql .= " and thepd.is_active=1";
			}else if($actv=='InActive'){
				$sql .= " and thepd.is_active=0";
			}
		}	

		$sql.=" order by cata_desc";

//echo $sql; 

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
        $comp = $pers['company'];

		
        // Call _get_datatables_query with no filters (will use base query only)
        $sql = $this->_get_datatables_query(null, null, $comp, null, null, array());

		$q = $this->db->query($sql);
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