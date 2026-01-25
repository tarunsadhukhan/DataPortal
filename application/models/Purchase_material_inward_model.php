<?php
class Purchase_material_inward_model extends CI_Model
{

	


	var $table = 'tbl_proc_inward';	
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
		
		// $eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		if($companyId==118){
			$condition = "";
		}else{
			$condition = "and company=".$companyId;
		}
		

		$sql = "select inward_sequence_no ,inward_date,po_sequence_no,supp_name,
		invoice_number ,invoice_date,store_receipt_no,
		store_receipt_date,inward_sequence_no as dispatch_entry_no,  status_name,branch_name,branch_name as project_name
		from (
		select tpi.company,inward_sequence_no,inward_date,tpi.po,tpp.po_sequence_no ,tpi.supplier  ,invoice_number ,invoice_date ,store_receipt_no,
		store_receipt_date ,tpi.branch ,tpi.project,tpi.source,tpi.status  from tbl_proc_inward tpi
		left join tbl_proc_po tpp
		on  tpi.po=tpp.po_id
		) a, branch_master bm ,suppliermaster s ,status_master sm
		where a.supplier=s.supp_id and a.branch=bm.branch_id and a.status=sm.status_id and source in ('WITHPO','WITHOUTPO')
		and inward_date>='".$from_date."' and inward_date<='".$to_date."'".$condition ."
		order by inward_date,inward_sequence_no";

		
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

		if($pers['company']==118){
			$condition = "";
		}else{
			$condition = "and company=".$pers['company'];
		}

		
		$sql = "select inward_sequence_no ,inward_date,po_sequence_no,supp_name,
		invoice_number ,invoice_date,store_receipt_no,
		store_receipt_date,inward_sequence_no as dispatch_entry_no,  status_name,branch_name,branch_name as project_name
		from (
		select tpi.company,inward_sequence_no,inward_date,tpi.po,tpp.po_sequence_no ,tpi.supplier  ,invoice_number ,invoice_date ,store_receipt_no,
		store_receipt_date ,tpi.branch ,tpi.project,tpi.source,tpi.status  from tbl_proc_inward tpi
		left join tbl_proc_po tpp
		on  tpi.po=tpp.po_id
		) a, branch_master bm ,suppliermaster s ,status_master sm
		where a.supplier=s.supp_id and a.branch=bm.branch_id and a.status=sm.status_id and source in ('WITHPO','WITHOUTPO')
		and inward_date>='".$pers['from_date']."' and inward_date<='".$pers['to_date']."'".$condition."
		order by inward_date,inward_sequence_no";			
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
	
