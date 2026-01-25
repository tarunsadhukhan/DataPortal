<?php
class Hrms_department_summary_model extends CI_Model
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
				md.dept_desc as master_department ,
				count(*) as No_of_Emp
			from
				worker_master wm
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
				md.dept_desc
			order by
				md.dept_desc) a
		UNION
		select
			'Total' as master_department ,
			count(*) as No_of_Emp
		from
			worker_master wm
		left join department_master dm on
			dm.dept_id = wm.dept_id
		left join master_department md on
			md.mdept_id = dm.mdept_id
			and md.company_id = dm.company_id
		where
			wm.company_id = ".$companyId."
			and
			wm.active = 'Y'
			and wm.employee_approval_status = 3";
		
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

		
		$sql = "select
		*
	from
		(
		select
			md.dept_desc as master_department ,
			count(*) as No_of_Emp
		from
			worker_master wm
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
			md.dept_desc
		order by
			md.dept_desc) a
	UNION
	select
		'Total' as master_department ,
		count(*) as No_of_Emp
	from
		worker_master wm
	left join department_master dm on
		dm.dept_id = wm.dept_id
	left join master_department md on
		md.mdept_id = dm.mdept_id
		and md.company_id = dm.company_id
	where
		wm.company_id = ".$pers['company']."
		and
		wm.active = 'Y'
		and wm.employee_approval_status = 3";
		
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
	
