<?php
class Purchase_list_item_wise_model extends CI_Model
{

	var $table = 'tbl_proc_po_detail as tppd';	
	var $column_order = array(null, 'po_sequence_no','po_date','status_name','item_desc'); //set column field database for datatable orderable
	var $column_search = array( 'po_sequence_no','po_date','status_name','item_desc'); //set column field database for datatable searchable 
	var $order = array('po_id' => 'desc'); // default order
	var $myselect = "tppd.po_detail_id , tppd.qty , tppd.rate ,tppd.rate_lastpurchase , tppd.indent ,tppd.indent_detail ,tppd.item , tppd.tax ,tppd.installation_rate ,tppd.installation_amount ,tppd.make ,tppd.uom_code ,tpp.po_sequence_no ,tpp.po_date  ,sm.status_name ,tpbh.name, i.group_code , igm.group_desc, i.item_code ,i.item_desc";

	public function __construct()
	{		
		$this->load->database();		
	}

	
	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		/* Old Query Removed on 07-03-2023 By given query Nandu */

		// $this->db->select($this->myselect);
		// $this->db->from($this->table);
		// $this->db->join('itemmaster i', 'tppd.item = i.item_id', 'left');
		// $this->db->join('item_group_master igm', 'i.group_code =igm.group_code', 'left');
		// $this->db->join('tbl_proc_budget_heads tpbh' , 'tppd.budget_head =tpbh.budget_head_id', 'left');
		// $this->db->join('tbl_proc_po tpp' , 'tppd.po =tpp.po_id', 'left');
		// $this->db->join('status_master sm' , 'tppd.status =sm.status_id', 'left');	
		// $this->db->where("tppd.created_date>= '".date('Y-m-d',strtotime($from_date))." 00:00:00' and tppd.created_date<= '".date('Y-m-d',strtotime($to_date))." 00:00:00'");
		

		// if($companyId==118){
			
		// }else{
		// 	$this->db->where('tpp.company',$companyId);
		// }
		// if($_POST['search']['value']){
		// 	$i = 0;
		// 	foreach ($this->column_search as $item){
		// 		if($i===0){	
		// 			$this->db->like($item, $_POST['search']['value']);
		// 		}else{
		// 			$this->db->or_like($item, $_POST['search']['value']);
		// 		}	
		// 	$i++;
		// 	}
		// }
		
		// if(isset($_POST['order'])) // here order processing
		// {
		// 	$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } 
		// else if(isset($this->order))
		// {
		// 	$order = $this->order;
		// 	$this->db->order_by(key($order), $order[key($order)]);
		// }

		$sql = "SELECT 
		tppd.po_detail_id,
		tppd.qty,
		tppd.rate,
		'' AS 'rate_lastpurchase',
		'' AS 'indent',
		tppd.indent_detail 'indent_detail',
		' ' AS 'item',
		tax,
		tppd.installation_rate,
		tppd.installation_amount,
		tppd.make,
		tppd.uom_code,
		tpp.po_sequence_no,
		tpp.po_date,
		stm.status_name,
		cm.name,
		im.group_code,
		igm.group_desc,
		im.item_code,
		im.item_desc,
		ROUND((tppd.rate * tppd.qty),2)  'item_wise_value',
		sm.supp_name 'supplier',
		cm.name 'customer',
		round(qty*rate) 'po_value_without_tax',
		ROUND(((tppd.rate * tppd.qty) + ((tppd.rate * tppd.qty)*tppd.tax/100)),2)  'po_gross_value_with_tax',
		case when tpp.source in ('WITHBOM','WITHINDENT','WITHOUTINDENT') THEN 'PO' else 'WO' end 'source'
	from tbl_proc_po tpp 
	LEFT JOIN suppliermaster sm 
	ON tpp.supplier = sm.supp_id
	LEFT JOIN branch_master bm
	ON tpp.branch = bm.branch_id
	LEFT JOIN customer_master cm
	ON tpp.customer = cm.id
	LEFT JOIN tbl_proc_po_detail tppd
	ON tpp.po_id = tppd.po
	LEFT JOIN itemmaster im
	ON tppd.item=im.item_id
	LEFT JOIN scm_indent_type_master sitm
	ON tpp.category=sitm.indent_type_id
	LEFT JOIN tbl_proc_budget_heads tpbh
	ON tpp.budget_head=tpbh.budget_head_id
	LEFT JOIN status_master stm
	ON tpp.status=stm.status_id
	LEFT JOIN item_group_master igm 
	ON im.group_code=igm.group_code and im.company_id=igm.company_id WHERE tpp.po_date >= '".date('Y-m-d',strtotime($from_date))."' and tppd.created_date <= '".date('Y-m-d',strtotime($to_date))."'";




		
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

		/* Old Query Removed on 07-03-2023 By given query Nandu */
		// $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// if($_POST['length'] != -1)
		// $this->db->limit($_POST['length'], $_POST['start']);
		// $query = $this->db->get();
		// // $this->varaha->print_arrays($this->db->last_query());
		// return $query->result();

		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $this->varaha->print_arrays($sql);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		/* Old Query Removed on 07-03-2023 By given query Nandu */
		// $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		// $query = $this->db->get();
		// return $query->num_rows();

		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{	
		/* Old Query Removed on 07-03-2023 By given query Nandu */
		// $this->db->select($this->myselect);
		// $this->db->from($this->table);
		// $this->db->join('itemmaster i', 'tppd.item = i.item_id', 'left');
		// $this->db->join('item_group_master igm', 'i.group_code =igm.group_code', 'left');
		// $this->db->join('tbl_proc_budget_heads tpbh' , 'tppd.budget_head =tpbh.budget_head_id', 'left');
		// $this->db->join('tbl_proc_po tpp' , 'tppd.po =tpp.po_id', 'left');
		// $this->db->join('status_master sm' , 'tppd.status =sm.status_id', 'left');	
		// $this->db->where("tppd.created_date>= '".date('Y-m-d',strtotime($from_date))." 00:00:00' and tppd.created_date<= '".date('Y-m-d',strtotime($to_date))." 00:00:00'");	
		
		// if($companyId==118){
			
		// }else{
		// 	$this->db->where('tpp.company',$companyId);
		// }
		// return $this->db->count_all_results();

		$this->db->from($this->table);		
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		/* Old Query Removed on 07-03-2023 By given query Nandu */
		// $this->db->select($this->myselect);
		// $this->db->from($this->table);
		// $this->db->join('itemmaster i', 'tppd.item = i.item_id', 'left');
		// $this->db->join('item_group_master igm', 'i.group_code =igm.group_code', 'left');
		// $this->db->join('tbl_proc_budget_heads tpbh' , 'tppd.budget_head =tpbh.budget_head_id', 'left');
		// $this->db->join('tbl_proc_po tpp' , 'tppd.po =tpp.po_id', 'left');
		// $this->db->join('status_master sm' , 'tppd.status =sm.status_id', 'left');	
		// $this->db->where("tppd.created_date>= '".date('Y-m-d',strtotime($pers['from_date']))." 00:00:00' and tppd.created_date<= '".date('Y-m-d',strtotime($pers['to_date']))." 00:00:00'");
		// $this->db->where("tpp.company",$pers['company']);	
		
		// if($pers['company']==118){
			
		// }else{
		// 	$this->db->where('tpp.company',$pers['company']);
		// }

		$sql = "SELECT 
		tppd.po_detail_id,
		tppd.qty,
		tppd.rate,
		'' AS 'rate_lastpurchase',
		'' AS 'indent',
		tppd.indent_detail 'indent_detail',
		' ' AS 'item',
		tax,
		tppd.installation_rate,
		tppd.installation_amount,
		tppd.make,
		tppd.uom_code,
		tpp.po_sequence_no,
		tpp.po_date,
		stm.status_name,
		cm.name,
		im.group_code,
		igm.group_desc,
		im.item_code,
		im.item_desc,
		ROUND((tppd.rate * tppd.qty),2)  'item_wise_value',
		sm.supp_name 'supplier',
		cm.name 'customer',
		round(qty*rate) 'po_value_without_tax',
		ROUND(((tppd.rate * tppd.qty) + ((tppd.rate * tppd.qty)*tppd.tax/100)),2)  'po_gross_value_with_tax',
		case when tpp.source in ('WITHBOM','WITHINDENT','WITHOUTINDENT') THEN 'PO' else 'WO' end 'source'
	from tbl_proc_po tpp 
	LEFT JOIN suppliermaster sm 
	ON tpp.supplier = sm.supp_id
	LEFT JOIN branch_master bm
	ON tpp.branch = bm.branch_id
	LEFT JOIN customer_master cm
	ON tpp.customer = cm.id
	LEFT JOIN tbl_proc_po_detail tppd
	ON tpp.po_id = tppd.po
	LEFT JOIN itemmaster im
	ON tppd.item=im.item_id
	LEFT JOIN scm_indent_type_master sitm
	ON tpp.category=sitm.indent_type_id
	LEFT JOIN tbl_proc_budget_heads tpbh
	ON tpp.budget_head=tpbh.budget_head_id
	LEFT JOIN status_master stm
	ON tpp.status=stm.status_id
	LEFT JOIN item_group_master igm 
	ON im.group_code=igm.group_code and im.company_id=igm.company_id WHERE tpp.po_date >= '".date('Y-m-d',strtotime($pers['from_date']))."' and tppd.created_date <= '".date('Y-m-d',strtotime($pers['to_date']))."'";

		$q = $this->db->query($sql);
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