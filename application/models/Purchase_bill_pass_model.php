<?php
class Purchase_bill_pass_model extends CI_Model
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
		// $this->varaha->print_arrays($companyId);

		if($companyId==118){
			$condition = "";
		}else{
			$condition = "  and tpi.company = ".$companyId;
		}
		

		$sql = "select 
		billpass_number, 
		DATE_FORMAT(billpass_approve_date,'%d/%m/%Y') AS `billpass_date`,
		store_receipt_no,
		po_sequence_no,
		supp_name,
		status_name,
		a.branch_name,
		a.branch_name, 
		invoice_number,
		invoice_date,
		invoice_amount from
		(select 
		billpass_number,
		billpass_approve_date,
		billpass_date ,
		store_receipt_no ,
		tpp.po_sequence_no ,  
		s.supp_name, 
		billpass_status ,
		sm.status_name ,
		tpi.branch ,
		bm.branch_name  ,
		invoice_number ,
		invoice_date ,
		invoice_amount ,
		tpi.project  
		from tbl_proc_inward tpi , tbl_proc_po tpp , status_master sm ,branch_master bm  ,suppliermaster s 
		where  tpi.po=tpp.po_id and billpass_status =sm.status_id  and tpi.branch =bm.branch_id ".$condition."
		and tpi.supplier =s.supp_id and billpass_number is not NULL 
		) a 
		where billpass_date>='".$from_date."' and billpass_date<='".$to_date."'
		order by billpass_number ";
		
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
			$condition = "  and tpi.company = ".$pers['company'];
		}
		
		$sql = "select 
		billpass_number, 
		DATE_FORMAT(billpass_approve_date,'%d/%m/%Y') AS `billpass_date`,
		store_receipt_no,
		po_sequence_no,
		supp_name,
		status_name,
		a.branch_name,
		a.branch_name, 
		invoice_number,
		invoice_date,
		invoice_amount from
		(select 
		billpass_number,
		billpass_approve_date,
		billpass_date ,
		store_receipt_no ,
		tpp.po_sequence_no ,  
		s.supp_name, 
		billpass_status ,
		sm.status_name ,
		tpi.branch ,
		bm.branch_name  ,
		invoice_number ,
		invoice_date ,
		invoice_amount ,
		tpi.project  
		from tbl_proc_inward tpi , tbl_proc_po tpp , status_master sm ,branch_master bm  ,suppliermaster s 
		where  tpi.po=tpp.po_id and billpass_status =sm.status_id  and tpi.branch =bm.branch_id   ".$condition."
		and tpi.supplier =s.supp_id and billpass_number is not NULL 
		) a 
		where billpass_date>='".$pers['from_date']."' and billpass_date<='".$pers['to_date']."'
		order by billpass_number;";		
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
	
