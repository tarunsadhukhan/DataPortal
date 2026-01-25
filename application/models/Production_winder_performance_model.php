<?php
class Production_winder_performance_model extends CI_Model
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


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
$sql="select eb_id,eb_no,empname,sum(prod) prod,sum(whrs) whrs,round(sum(prod)/sum(whrs)*8,0) 
prod_8hrs,
round(sum(targetprod)/sum(whrs)*8,0) target_8hrs,
round(sum(targetprod)-sum(prod),0) diff,
round((round(sum(prod)/ sum(whrs)* 8, 0))/(round(sum(targetprod)/ sum(whrs)* 8, 0))*100,2) eff,
wnd_group_name,image_html from ( 
select swd.*,da.eb_id,da.eb_no,(da.working_hours-idle_hours) whrs,da.attendance_type,
concat(thepd.first_name, ifnull(thepd.middle_name, ''), ifnull(thepd.last_name, '')) AS empname,d.desig,om.OCCU_CODE,
(TARGET_PROD/8*(da.working_hours-idle_hours)) targetprod
from EMPMILL12.spellwindingdata swd
left join (select * from vowsls.daily_ebmc_attendance dea where dea.is_active=1) dea
on dea.attendace_date=swd.tran_date and dea.spell=swd.spell and dea.mc_id=swd.WND_MC_ID
left join (select * from vowsls.daily_attendance where is_active=1) da
on da.daily_atten_id=dea.daily_atten_id
left join vowsls.tbl_hrms_ed_personal_details thepd on thepd.eb_id=da.eb_id
left join vowsls.ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID=da.worked_designation_id
Left join EMPMILL12.OCCUPATION_MASTER om on om.occu_id=oolt.ORA_TABLE_ID
left join vowsls.designation d on d.id=da.worked_designation_id
where prod>0 and swd.tran_date between '".$from_date."' and '".$to_date."' and swd.company_id=".$companyId."
) g left join (select * from EMPMILL12.tbl_item_image tii where image_type='E') tii  on eb_id=tii.item_id
group by eb_id,eb_no,empname,wnd_group_name,image_html
order by wnd_group_name,eb_no
";
	//	echo $sql;
		$i = 0;
		if($_POST['search']['value']){
			foreach ($this->column_search as $item){
				if($i===0){	
					$sql = $sql . $item ." LIKE ". $_POST['search']['value'];
				}else{
					$sql = $sql . $item ."OR LIKE ". $_POST['search']['value'];
				}

			$i++;
			}
		}
		if(isset($_POST['order'])) {
			$sql = $sql . "ORDER BY ". $this->column_order[$_POST['order']['0']['column']].",". $_POST['order']['0']['dir'];
		}else if(isset($this->order)){
			$order = $this->order;
			// $sql = $sql . "ORDER BY ". key($order) .",". $order[key($order)];
		}

		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
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

		
 
$sql="select eb_id,eb_no,empname,sum(prod) prod,sum(whrs) whrs,round(sum(prod)/sum(whrs)*8,0) 
prod_8hrs,
round(sum(targetprod)/sum(whrs)*8,0) target_8hrs,
round(sum(targetprod)-sum(prod),0) diff,
round((round(sum(prod)/ sum(whrs)* 8, 0))/(round(sum(targetprod)/ sum(whrs)* 8, 0))*100,2) eff,
wnd_group_name,image_html from ( 
select swd.*,da.eb_id,da.eb_no,(da.working_hours-idle_hours) whrs,da.attendance_type,
concat(thepd.first_name, ifnull(thepd.middle_name, ''), ifnull(thepd.last_name, '')) AS empname,d.desig,om.OCCU_CODE,
(TARGET_PROD/8*(da.working_hours-idle_hours)) targetprod
from EMPMILL12.spellwindingdata swd
left join (select * from vowsls.daily_ebmc_attendance dea where dea.is_active=1) dea
on dea.attendace_date=swd.tran_date and dea.spell=swd.spell and dea.mc_id=swd.WND_MC_ID
left join (select * from vowsls.daily_attendance where is_active=1) da
on da.daily_atten_id=dea.daily_atten_id
left join vowsls.tbl_hrms_ed_personal_details thepd on thepd.eb_id=da.eb_id
left join vowsls.ORA_OCCU_LINK_TABLE oolt on oolt.MYSQL_TABLE_ID=da.worked_designation_id
Left join EMPMILL12.OCCUPATION_MASTER om on om.occu_id=oolt.ORA_TABLE_ID
left join vowsls.designation d on d.id=da.worked_designation_id
where prod>0 and swd.tran_date between '".$pers['from_date']."' and '".$pers['to_date']."' and swd.company_id=".$pers['company']."
) g left join (select * from EMPMILL12.tbl_item_image tii where image_type='E') tii  on eb_id=tii.item_id
group by eb_id,eb_no,empname,wnd_group_name,image_html
order by wnd_group_name,eb_no
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
	
}
?>
	
