<?php
class Purchase_list_include_canceld_model extends CI_Model
{

	var $table = 'tbl_proc_po as po';	
	var $column_order = array(null, 'bill_to_state_name','bill_to_address','ship_to_address','po_date','po_sequence_no','Status'); //set column field database for datatable orderable
	var $column_search = array( 'bill_to_state_name','bill_to_address','ship_to_address','po_date','po_sequence_no','Status'); //set column field database for datatable searchable 
	var $order = array('po_id' => 'desc'); // default order
	var $myselect = "po.po_id ,po.created_by ,po.created_date ,po.last_modified_by ,po.last_modified_date ,po.bill_to_address ,
	po.bill_to_state_name,po.ship_to_address ,po.ship_to_state_name ,po.credit_days ,po.po_date ,po.po_sequence_no ,
	po.source ,po.tax_payable ,po.delivery_timeline ,po.supplier_branch ,po.billing_branch ,po.category ,
	po.net_amount ,po.total_amount ,po.tax_type,po.item_group ,po.advance_type ,po.advance_percentage ,
	po.advance_amount  ,sm2.status_name as Status, tpbh.name Budget_Head ,tpi.indent_squence_no ,
	cm.company_code ,bm.branch_name , bm.branch_address , igm.group_desc , sm.supp_name , cm2.name as customer";

	
	
	public function __construct()
	{		
		$this->load->database();		
	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{

		
		
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->join('suppliermaster sm', 'po.supplier=sm.supp_id', 'left');
		$this->db->join('item_group_master igm', 'po.item_group =igm.group_code', 'left');
		$this->db->join('branch_master bm' , 'po.branch =bm.branch_id', 'left');
		$this->db->join('company_master cm', 'po.company =cm.comp_id', 'left');
		$this->db->join('tbl_proc_indent tpi' , 'po.indent = tpi.indent_id', 'left');
		$this->db->join('tbl_proc_budget_heads tpbh' , 'po.budget_head =tpbh.budget_head_id', 'left');
		$this->db->join('status_master sm2' , 'po.status =sm2.status_id', 'left');
		$this->db->join('customer_master cm2' , 'po.customer =cm2.id', 'left');
		$this->db->where("po.created_date>= '".date('Y-m-d',strtotime($from_date))." 00:00:00' and po.created_date<= '".date('Y-m-d',strtotime($to_date))." 00:00:00'");
		if($companyId==118){
			
		}else{
			$this->db->where('po.company', $companyId);
		}
		
		if($_POST['search']['value']){
			$i = 0;
			foreach ($this->column_search as $item){
				if($i===0){	
					$this->db->like($item, $_POST['search']['value']);
				}else{
					$this->db->or_like($item, $_POST['search']['value']);
				}	
			$i++;
			}
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}


	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		// $this->varaha->print_arrays($this->db->last_query());
		$query = $this->db->get();
		
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{	
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->join('suppliermaster sm', 'po.supplier=sm.supp_id', 'left');
		$this->db->join('item_group_master igm', 'po.item_group =igm.group_code', 'left');
		$this->db->join('branch_master bm' , 'po.branch =bm.branch_id', 'left');
		$this->db->join('company_master cm', 'po.company =cm.comp_id', 'left');
		$this->db->join('tbl_proc_indent tpi' , 'po.indent = tpi.indent_id', 'left');
		$this->db->join('tbl_proc_budget_heads tpbh' , 'po.budget_head =tpbh.budget_head_id', 'left');
		$this->db->join('status_master sm2' , 'po.status =sm2.status_id', 'left');
		$this->db->join('customer_master cm2' , 'po.customer =cm2.id', 'left');
		$this->db->where("po.created_date>= '".date('Y-m-d',strtotime($from_date))." 00:00:00' and po.created_date<= '".date('Y-m-d',strtotime($to_date))." 00:00:00'");	
		if($companyId==118){
			
		}else{
			$this->db->where('po.company', $companyId);
		}
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->join('suppliermaster sm', 'po.supplier=sm.supp_id', 'left');
		$this->db->join('item_group_master igm', 'po.item_group =igm.group_code', 'left');
		$this->db->join('branch_master bm' , 'po.branch =bm.branch_id', 'left');
		$this->db->join('company_master cm', 'po.company =cm.comp_id', 'left');
		$this->db->join('tbl_proc_indent tpi' , 'po.indent = tpi.indent_id', 'left');
		$this->db->join('tbl_proc_budget_heads tpbh' , 'po.budget_head =tpbh.budget_head_id', 'left');
		$this->db->join('status_master sm2' , 'po.status =sm2.status_id', 'left');
		$this->db->join('customer_master cm2' , 'po.customer =cm2.id', 'left');
		$this->db->where("po.created_date>= '".date('Y-m-d',strtotime($pers['from_date']))." 00:00:00' and po.created_date<= '".date('Y-m-d',strtotime($pers['to_date']))." 00:00:00'");
		
		if($pers['company']==118){
			
		}else{
			$this->db->where('po.company',$pers['company']);
		}	
		$q = $this->db->get();
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