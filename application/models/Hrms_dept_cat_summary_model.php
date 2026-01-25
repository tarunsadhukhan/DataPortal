<?php
class Hrms_dept_cat_summary_model extends CI_Model
{

	


	var $table = 'worker_master wm';	
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
		$Source = $_POST['Source'];
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_dept = $_POST['att_dept'];
		$att_desig = $_POST['att_desig'];
		$att_spells = $_POST['att_spells'];
		$eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		
		

		$sql = "select
		*
	from
		(
		select
			master_department,
			sub_department,
			sum(permenant) as permenant,
			sum(budli) as budli,
			sum(new_budli) as new_budli,
			sum(contract) as contract,
			sum(retired) as retired,
			sum(other) as other,
			ifnull(sum(permenant), 0)+ ifnull(sum(budli), 0)+
			ifnull(sum(new_budli), 0)+ ifnull(sum(contract), 0)
			+ ifnull(sum(retired), 0) as total
		from
			(
			select
				md.dept_code,
				md.dept_desc as master_department,
				wm.dept_id,
				dm.dept_desc as sub_department,
				case
					when cm.cata_desc = \"PERMENANT\" then count(*)
				end as permenant,
				case
					when cm.cata_desc = \"BUDLI\" then count(*)
				end as budli,
				case
					when cm.cata_desc = \"NEW BUDLI\" then count(*)
				end as new_Budli,
				case
					when cm.cata_desc = \"RETIRED\" then count(*)
				end as retired,
				case
					when cm.cata_desc = \"CONTRACT\" then count(*)
				end as contract,
				case
					when cm.cata_desc not in 
					('PERMENANT','BUDLI','NEW BUDLI','RETIRED','CONTRACT') then count(*)
				end as other
			from
				worker_master wm
			left join category_master cm on
				cm.cata_id = wm.cata_id
			left join department_master dm on
				dm.dept_id = wm.dept_id
			left join master_department md on
				md.mdept_id = dm.mdept_id
				and md.company_id = dm.company_id
			where
				wm.company_id = ".$companyId."
				and
				wm.active = 'Y'
				and wm.employee_approval_status = 3
			group by
				cm.cata_desc ,
				md.dept_code ,
				md.dept_desc,
				wm.dept_id,
				dm.dept_desc,
				md.order_id
			order by
				md.order_id,
				md.dept_desc,
				dm.dept_desc) a
		group by
			master_department,
			sub_department
			) b
	UNION
		select
			'' as master_department,
			'Total' as sub_department,
			sum(permenant) as permenant,
			sum(budli) as budli,
			sum(new_budli) as new_budli,
			sum(contract) as contract,
			sum(retired) as retired,
			sum(other) as other,
			ifnull(sum(permenant), 0)+ ifnull(sum(budli), 0)+
			ifnull(sum(new_budli), 0)+ ifnull(sum(contract), 0)
			+ ifnull(sum(retired), 0) as total
		from
			(
			select
				md.dept_code,
				md.dept_desc as master_department,
				wm.dept_id,
				dm.dept_desc as sub_department,
				case
					when cm.cata_desc = \"PERMENANT\" then count(*)
				end as permenant,
				case
					when cm.cata_desc = \"BUDLI\" then count(*)
				end as budli,
				case
					when cm.cata_desc = \"NEW BUDLI\" then count(*)
				end as new_Budli,
				case
					when cm.cata_desc = \"RETIRED\" then count(*)
				end as retired,
				case
					when cm.cata_desc = \"CONTRACT\" then count(*)
				end as contract,
				case
					when cm.cata_desc not in 
					('PERMENANT','BUDLI','NEW BUDLI','RETIRED','CONTRACT') then count(*)
				end as other
			from
				worker_master wm
			left join category_master cm on
				cm.cata_id = wm.cata_id
			left join department_master dm on
				dm.dept_id = wm.dept_id
			left join master_department md on
				md.mdept_id = dm.mdept_id
				and md.company_id = dm.company_id
			where
				wm.company_id = ".$companyId."
				and
				wm.active = 'Y'
				and wm.employee_approval_status = 3
			group by
				cm.cata_desc ,
				md.dept_code ,
				md.dept_desc,
				wm.dept_id,
				dm.dept_desc,
				md.order_id
			order by
				md.order_id,
				md.dept_desc,
				dm.dept_desc) b
	";
		
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
		// $this->varaha->print_arrays($sql);		
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

		
		$sql = "select
		*
	from
		(
		select
			master_department,
			sub_department,
			sum(permenant) as permenant,
			sum(budli) as budli,
			sum(new_budli) as new_budli,
			sum(contract) as contract,
			sum(retired) as retired,
			sum(other) as other,
			ifnull(sum(permenant), 0)+ ifnull(sum(budli), 0)+
			ifnull(sum(new_budli), 0)+ ifnull(sum(contract), 0)
			+ ifnull(sum(retired), 0) as total
		from
			(
			select
				md.dept_code,
				md.dept_desc as master_department,
				wm.dept_id,
				dm.dept_desc as sub_department,
				case
					when cm.cata_desc = \"PERMENANT\" then count(*)
				end as permenant,
				case
					when cm.cata_desc = \"BUDLI\" then count(*)
				end as budli,
				case
					when cm.cata_desc = \"NEW BUDLI\" then count(*)
				end as new_Budli,
				case
					when cm.cata_desc = \"RETIRED\" then count(*)
				end as retired,
				case
					when cm.cata_desc = \"CONTRACT\" then count(*)
				end as contract,
				case
					when cm.cata_desc not in 
					('PERMENANT','BUDLI','NEW BUDLI','RETIRED','CONTRACT') then count(*)
				end as other
			from
				worker_master wm
			left join category_master cm on
				cm.cata_id = wm.cata_id
			left join department_master dm on
				dm.dept_id = wm.dept_id
			left join master_department md on
				md.mdept_id = dm.mdept_id
				and md.company_id = dm.company_id
			where
				wm.company_id = ".$pers['company']."
				and
				wm.active = 'Y'
				and wm.employee_approval_status = 3
			group by
				cm.cata_desc ,
				md.dept_code ,
				md.dept_desc,
				wm.dept_id,
				dm.dept_desc,
				md.order_id
			order by
				md.order_id,
				md.dept_desc,
				dm.dept_desc) a
		group by
			master_department,
			sub_department
			) b
	UNION
		select
			'' as master_department,
			'Total' as sub_department,
			sum(permenant) as permenant,
			sum(budli) as budli,
			sum(new_budli) as new_budli,
			sum(contract) as contract,
			sum(retired) as retired,
			sum(other) as other,
			ifnull(sum(permenant), 0)+ ifnull(sum(budli), 0)+
			ifnull(sum(new_budli), 0)+ ifnull(sum(contract), 0)
			+ ifnull(sum(retired), 0) as total
		from
			(
			select
				md.dept_code,
				md.dept_desc as master_department,
				wm.dept_id,
				dm.dept_desc as sub_department,
				case
					when cm.cata_desc = \"PERMENANT\" then count(*)
				end as permenant,
				case
					when cm.cata_desc = \"BUDLI\" then count(*)
				end as budli,
				case
					when cm.cata_desc = \"NEW BUDLI\" then count(*)
				end as new_Budli,
				case
					when cm.cata_desc = \"RETIRED\" then count(*)
				end as retired,
				case
					when cm.cata_desc = \"CONTRACT\" then count(*)
				end as contract,
				case
					when cm.cata_desc not in 
					('PERMENANT','BUDLI','NEW BUDLI','RETIRED','CONTRACT') then count(*)
				end as other
			from
				worker_master wm
			left join category_master cm on
				cm.cata_id = wm.cata_id
			left join department_master dm on
				dm.dept_id = wm.dept_id
			left join master_department md on
				md.mdept_id = dm.mdept_id
				and md.company_id = dm.company_id
			where
				wm.company_id = ".$pers['company']."
				and
				wm.active = 'Y'
				and wm.employee_approval_status = 3
			group by
				cm.cata_desc ,
				md.dept_code ,
				md.dept_desc,
				wm.dept_id,
				dm.dept_desc,
				md.order_id
			order by
				md.order_id,
				md.dept_desc,
				dm.dept_desc) b
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
	
