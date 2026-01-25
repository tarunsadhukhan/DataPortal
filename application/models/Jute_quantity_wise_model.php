<?php
class Jute_quantity_wise_model extends CI_Model
{

	var $table = 'scm_mr_hdr';	
	var $column_order = array(null, 'J_code','Quality' ,'Opening_Weight', 'Opening_Bales','Opening_Drums','Receipt_Weight','Receipt_Bales','Receipt_Drums','Issued_Weight','Issued_Bales','Issued_Drums','Closing_Weight','Closing_Bales','Closing_Drums'); //set column field database for datatable orderable
	var $column_search = array( 'J_code','Quality' ,'Opening_Weight', 'Opening_Bales','Opening_Drums','Receipt_Weight','Receipt_Bales','Receipt_Drums','Issued_Weight','Issued_Bales','Issued_Drums','Closing_Weight','Closing_Bales','Closing_Drums'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    var $order = array('J_code','Quality' ,'Opening_Weight', 'Opening_Bales','Opening_Drums','Receipt_Weight','Receipt_Bales','Receipt_Drums','Issued_Weight','Issued_Bales','Issued_Drums','Closing_Weight','Closing_Bales','Closing_Drums');
	
	public function __construct()
	{		
		$this->load->database();		
	}

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql =  "select jqpm.id as J_code, jqpm.jute_quality as Quality, jqpm.op_2019 as Opening_Weight, jqpm.bales as Opening_Bales, jqpm.drums as Opening_Drums, (select sum(smhl.actual_weight) from scm_mr_hdr smh cross join scm_mr_line_item smhl where smh.jute_receive_no=smhl.jute_receive_no and smhl.is_active=1 and smh.mr_good_recept_status='3' and smh.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl.actual_quality=jqpm.id) as Receipt_Weight, 
		(select sum(smhl1.actual_bale) from scm_mr_hdr smh1 cross join scm_mr_line_item smhl1 where smh1.jute_receive_no=smhl1.jute_receive_no and smhl1.is_active=1 and smh1.mr_good_recept_status='3' and smh1.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh1.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl1.actual_quality=jqpm.id) as Receipt_Bales, 
		(select sum(smhl2.actual_loose) from scm_mr_hdr smh2 cross join scm_mr_line_item smhl2 where smh2.jute_receive_no=smhl2.jute_receive_no and smhl2.is_active=1 and smh2.mr_good_recept_status='3' and smh2.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh2.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl2.actual_quality=jqpm.id) as Receipt_Drums, 
		(select sum(js.total_weight) from jute_issue js where js.jute_quality=jqpm.id and js.is_active=1 and js.issue_date>= '".date('Y-m-d',strtotime($from_date))."' and js.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js.issue_status=3) as Issued_Weight, 
		(select sum(js1.quantity) from jute_issue js1 where js1.jute_quality=jqpm.id and js1.is_active=1 and js1.issue_date>='".date('Y-m-d',strtotime($from_date))."' and js1.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js1.issue_status=3 and js1.bale_loose='BALE') as Issued_Bales, 
		(select sum(js2.quantity) from jute_issue js2 where js2.jute_quality=jqpm.id and js2.is_active=1 and js2.issue_date>='".date('Y-m-d',strtotime($from_date))."' and js2.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js2.issue_status=3 and js2.bale_loose='LOOSE') as Issued_Drums, 
		(select sum(smhl3.actual_weight) from scm_mr_hdr smh3 cross join scm_mr_line_item smhl3 where smh3.jute_receive_no=smhl3.jute_receive_no and smhl3.is_active=1 and smh3.mr_good_recept_status='3' and smh3.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh3.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl3.actual_quality=jqpm.id) as Closing_Weight, 
		(select sum(smhl4.actual_bale) from scm_mr_hdr smh4 cross join scm_mr_line_item smhl4 where smh4.jute_receive_no=smhl4.jute_receive_no and smhl4.is_active=1 and smh4.mr_good_recept_status='3' and smh4.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh4.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl4.actual_quality=jqpm.id) as Closing_Bales, 
		(select sum(smhl5.actual_loose) from scm_mr_hdr smh5 cross join scm_mr_line_item smhl5 where smh5.jute_receive_no=smhl5.jute_receive_no and smhl5.is_active=1 and smh5.mr_good_recept_status='3' and smh5.jute_receive_dt>='".date('Y-m-d',strtotime($from_date))."' and smh5.jute_receive_dt<='".date('Y-m-d',strtotime($to_date))."' and smhl5.actual_quality=jqpm.id) as Closing_Drums, 
		(select sum(js3.total_weight) from jute_issue js3 where js3.jute_quality=jqpm.id and js3.is_active=1 and js3.issue_date>='".date('Y-m-d',strtotime($from_date))."' and js3.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js3.issue_status=3), 
		(select sum(js4.quantity) from jute_issue js4 where js4.jute_quality=jqpm.id and js4.is_active=1 and js4.issue_date>='".date('Y-m-d',strtotime($from_date))."' and js4.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js4.issue_status=3 and js4.bale_loose='BALE'), 
		(select sum(js5.quantity) from jute_issue js5 where js5.jute_quality=jqpm.id and js5.is_active=1 and js5.issue_date>='".date('Y-m-d',strtotime($from_date))."' and js5.issue_date<='".date('Y-m-d',strtotime($to_date))."' and js5.issue_status=3 and js5.bale_loose='LOOSE') from jute_quality_price_master jqpm where jqpm.company_id='".$companyId."'";
		$i = 0;
		if($_POST['search']['value']){
			foreach ($this->column_search as $item){
				if($i===0){	
					$sql = $sql . $item ." LIKE ". $_POST['search']['value'];
				}else{
					$sql = $sql . $item ." OR LIKE ". $_POST['search']['value'];
				}

			$i++;
			}
		}
		if(isset($_POST['order'])) {
			$sql = $sql . " ORDER BY ". $this->column_order[$_POST['order']['0']['column']];
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
		// return $this->varaha->print_arrays($this->db->last_query());
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

		
		$sql =  "select jqpm.id as J_code, jqpm.jute_quality as Quality, jqpm.op_2019 as Opening_Weight, jqpm.bales as Opening_Bales, jqpm.drums as Opening_Drums, (select sum(smhl.actual_weight) from scm_mr_hdr smh cross join scm_mr_line_item smhl where smh.jute_receive_no=smhl.jute_receive_no and smhl.is_active=1 and smh.mr_good_recept_status='3' and smh.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl.actual_quality=jqpm.id) as Receipt_Weight, 
		(select sum(smhl1.actual_bale) from scm_mr_hdr smh1 cross join scm_mr_line_item smhl1 where smh1.jute_receive_no=smhl1.jute_receive_no and smhl1.is_active=1 and smh1.mr_good_recept_status='3' and smh1.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh1.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl1.actual_quality=jqpm.id) as Receipt_Bales, 
		(select sum(smhl2.actual_loose) from scm_mr_hdr smh2 cross join scm_mr_line_item smhl2 where smh2.jute_receive_no=smhl2.jute_receive_no and smhl2.is_active=1 and smh2.mr_good_recept_status='3' and smh2.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh2.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl2.actual_quality=jqpm.id) as Receipt_Drums, 
		(select sum(js.total_weight) from jute_issue js where js.jute_quality=jqpm.id and js.is_active=1 and js.issue_date>= '".date('Y-m-d',strtotime($pers['from_date']))."' and js.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js.issue_status=3) as Issued_Weight, 
		(select sum(js1.quantity) from jute_issue js1 where js1.jute_quality=jqpm.id and js1.is_active=1 and js1.issue_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and js1.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js1.issue_status=3 and js1.bale_loose='BALE') as Issued_Bales, 
		(select sum(js2.quantity) from jute_issue js2 where js2.jute_quality=jqpm.id and js2.is_active=1 and js2.issue_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and js2.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js2.issue_status=3 and js2.bale_loose='LOOSE') as Issued_Drums, 
		(select sum(smhl3.actual_weight) from scm_mr_hdr smh3 cross join scm_mr_line_item smhl3 where smh3.jute_receive_no=smhl3.jute_receive_no and smhl3.is_active=1 and smh3.mr_good_recept_status='3' and smh3.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh3.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl3.actual_quality=jqpm.id) as Closing_Weight, 
		(select sum(smhl4.actual_bale) from scm_mr_hdr smh4 cross join scm_mr_line_item smhl4 where smh4.jute_receive_no=smhl4.jute_receive_no and smhl4.is_active=1 and smh4.mr_good_recept_status='3' and smh4.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh4.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl4.actual_quality=jqpm.id) as Closing_Bales, 
		(select sum(smhl5.actual_loose) from scm_mr_hdr smh5 cross join scm_mr_line_item smhl5 where smh5.jute_receive_no=smhl5.jute_receive_no and smhl5.is_active=1 and smh5.mr_good_recept_status='3' and smh5.jute_receive_dt>='".date('Y-m-d',strtotime($pers['from_date']))."' and smh5.jute_receive_dt<='".date('Y-m-d',strtotime($pers['to_date']))."' and smhl5.actual_quality=jqpm.id) as Closing_Drums, 
		(select sum(js3.total_weight) from jute_issue js3 where js3.jute_quality=jqpm.id and js3.is_active=1 and js3.issue_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and js3.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js3.issue_status=3), 
		(select sum(js4.quantity) from jute_issue js4 where js4.jute_quality=jqpm.id and js4.is_active=1 and js4.issue_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and js4.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js4.issue_status=3 and js4.bale_loose='BALE'), 
		(select sum(js5.quantity) from jute_issue js5 where js5.jute_quality=jqpm.id and js5.is_active=1 and js5.issue_date>='".date('Y-m-d',strtotime($pers['from_date']))."' and js5.issue_date<='".date('Y-m-d',strtotime($pers['to_date']))."' and js5.issue_status=3 and js5.bale_loose='LOOSE') from jute_quality_price_master jqpm where jqpm.company_id='".$pers['company']."'";
		$q = $this->db->query($sql);
		// $this->varaha->print_arrays($this->db->last_query());
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