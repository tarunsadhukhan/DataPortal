<?php
class Jute_issue_rec_summary_model extends CI_Model
{

	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'bill_to_state_name','bill_to_address','ship_to_address','po_date','po_sequence_no','Status'); //set column field database for datatable orderable
	var $column_search = array( 'bill_to_state_name','bill_to_address','ship_to_address','po_date','po_sequence_no','Status'); //set column field database for datatable searchable 
	var $order = array('tran_date' => 'desc'); // default order
	var $myselect = "tran_date AS 'Issue_Date', jute_receive_no AS 'MR_No',quality_name as 'Quality', godown as 'Godown_ID',unit_conversion as 'Pack_Type', 
	round(bales_issued+drums_issued,2) as 'Quantity',
	round(weight_issued,2) as 'Weight','QNT' as 'Unit', 
	round(rate,2) as 'Rate', 
	round(issue_value,2) as 'Issue_Value',mr_line_id as 'MR_Line_No', quality_code as 'Quality_ID',godown_name as 'Godown_Name',status_name as 'Status'  ";

	
	public function __construct()
	{		
		$this->load->database();		
	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno)
	{
		
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->where('transaction_type','I');
		if($mrno){
			$this->db->where('jute_receive_no',$mrno);
		}
		$this->db->where('company_id',$companyId);
		// $this->db->where('company_id',1);
		$this->db->where("tran_date >= '".date('Y-m-d',strtotime($from_date))."' and tran_date<= '".date('Y-m-d',strtotime($to_date))."'");
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

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// $this->varaha->print_arrays($this->db->last_query());
		return $query->result();
	}

	function count_filtered($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno)
	{
		$this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($mainmenuId,$submenuId, $companyId, $from_date,$to_date, $mrno)
	{	
		$this->db->select($this->myselect);
		$this->db->from($this->table);
		$this->db->where('transaction_type','I');
		$this->db->where('company_id',$companyId);
		$this->db->where("tran_date >= '".date('Y-m-d',strtotime($from_date))."' and tran_date <= '".date('Y-m-d',strtotime($to_date))."'");	
		return $this->db->count_all_results();
	}

	public function directReport($pers){

		$this->db->select($this->myselect);
		$this->db->from($this->table);
		if($pers['mrno']){
			$this->db->where('jute_receive_no',$pers['mrno']);
		}
		$this->db->where('transaction_type','I');
		$this->db->where('company_id',$pers['company']);
		$this->db->where("tran_date>= '".date('Y-m-d',strtotime($pers['from_date']))."' and tran_date<= '".date('Y-m-d',strtotime($pers['to_date']))."'");	
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