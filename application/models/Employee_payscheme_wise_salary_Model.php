<?php
class Employee_payscheme_wise_salary_Model extends CI_Model
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

		$payid=0;
		$branch_id = $_POST['branch_id'];
		$payscheme_id = $_POST['payscheme_id'];
 		// $this->varaha->print_arrays($att_dept);
$ni=0;

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
					$sqlmhd=$sqlmhd."eb_no ".",";
					$sqlfhd=$sqlfhd.'eb_no'.",";
			}	
			if ($id==2)  {
					$sqlmhd=$sqlmhd."emp_name".",";
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

//$sqlmhd = substr($sqlmhd, 0, -1);

		 $sqlp="select ".$sqlmhd."eb_id";

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
//	 echo $sql;

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
		 where PAYPERIOD_ID=".$payid."  group by PAYPERIOD_ID,".$sqlfhd."eb_id ";
 		
 
  //echo 'final';
	//	echo $sqlp;
		return $sqlp;
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

 		// and da.status_id
		// and da.worked_department_id
		// and da.worked_designation_id
		// and da.spell is not null
		// and da.attendance_type is not null
		// and da.eb_no is not null;";

		$payid=0;
		$branch_id = $pers['branch_id'];
		$payscheme_id = $pers['payscheme_id'];
		$from_date = $pers['from_date'];
		$to_date = $pers['to_date'];
		$companyId = $pers['company'];

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
		where PAYPERIOD_ID=".$payid."  group by PAYPERIOD_ID,".$sqlfhd."eb_id ";
		
$sql=$sqlp;
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


	public function getColumns($subId,$companyId,$branch_id,$paysh) {
		$payscheme_id = $paysh;
//		$branch_id = $_POST['branch_id'];
  		// $this->varaha->print_arrays($att_dept);

		$sql="select
		case when tppc.payslip_order=1 then 'eb_no'
		when tppc.payslip_order=2 then 'emp_name' 
		else tpc.CODE end CODE,
		tppc.desc_print
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (select * from tbl_pay_scheme where status=32) tps on
		tps.ID = tppc.payscheme_id
	left join (select * from tbl_pay_scheme_details where status=1) tpsd on tps.id=tpsd.PAY_SCHEME_ID and 
	tpsd.COMPONENT_ID  =tppc.component_id and tpsd.PAY_SCHEME_ID =tppc.payscheme_id 
	left 	JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id  = tpc.ID
	WHERE
		tps.ID = ".$payscheme_id."
		AND tppc.company_id  = ".$companyId."
		AND tppc.branch_id = ".$branch_id."
	and 	tppc.payscheme_id>0
		and
		tppc.is_active = 1
		and tppc.payslip_print=1
		ORDER BY
		tppc.payscheme_id,payslip_order";

		$sql="select
		case when tppc.payslip_order=1 then 'eb_no'
		when tppc.payslip_order=2 then 'emp_name'
		when tppc.payslip_order=3 then 'Department'
		when tppc.payslip_order=4 then 'Designation'
		when tppc.payslip_order=5 then 'Uan No'
		when tppc.payslip_order=6 then 'ESI No'
		else tpc.CODE end CODE,
		tppc.desc_print
	from
		EMPMILL12.tbl_payslip_print_component_updated tppc
	left join (select * from tbl_pay_scheme where status=32) tps on
		tps.ID = tppc.payscheme_id
	left join (select * from tbl_pay_scheme_details where status=1) tpsd on tps.id=tpsd.PAY_SCHEME_ID and 
	tpsd.COMPONENT_ID  =tppc.component_id and tpsd.PAY_SCHEME_ID =tppc.payscheme_id 
	left 	JOIN vowsls.tbl_pay_components tpc ON
		tppc.component_id  = tpc.ID
	WHERE
		tps.ID = ".$payscheme_id."
		AND tppc.company_id  = ".$companyId."
		AND tppc.branch_id = ".$branch_id."
	and 	tppc.payscheme_id>0
		and
		tppc.is_active = 1
		and tppc.payslip_print=1
		ORDER BY
		tppc.payscheme_id,payslip_order";
//		echo $sql;
 
        $query = $this->db->query($sql);
    
	
	
	
		return $query->result_array();
   
	}
//	public function getDynamicArray($subId,$companyId,$branch_id,$paysh)
	public function getDynamicArray($subId,$companyId)
	{
		$payid=0;
		$branch_id = $_POST['branch_id'];
		$paysh = $_POST['payscheme_id'];
		$branch_id=$this->session->userdata('branch_id');
		$paysh = $this->session->userdata('payscheme_id');
		
		if (strlen($branch_id)==0) { $branch_id=0;}
		if (strlen($paysh)==0) { $paysh=0;}

		

	//			$tableName = 'your_table_name'; // replace with your actual table name
				$columns = $this->getColumns($subId,$companyId,$branch_id,$paysh);
/*
				$data = array(
					'no' => 'S.No',
					'Date' => 'Attendance&nbsp;Date',
					'Spell' => 'Spell',
					'EB_No' => 'EB_No',
					'Name' => 'Name',
					'Department' => 'Department',
					'Designation' => 'Designation',
					'attendance_type' => 'Att_Type',
					'attendance_source' => 'Attendance&nbsp;Source',
					'Working_Hours' => 'Working&nbsp;Hours',
					'MC_Nos' => 'MC&nbsp;Nos',
					'Remarks' => 'Remarks',                
				);
*/	

 				$data = [];
				foreach ($columns as $column) {
					$columnName = $column['CODE'];
					$value = $column['desc_print'];
					$data[$columnName] = $value;
//					echo  $columnName;
 				
			}
//			echo 'annana';
//	var_dump($data);
				return $data;
			}
	

	
	


}
?>