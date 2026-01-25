<?php
class Purchase_reservations_model extends CI_Model
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
		
		// $eb_no = $_POST['eb_no'];
		// $this->varaha->print_arrays($Source);

		
		

	$sql = "select
	b.group_code,
	b.item_code,
	item_desc,
	sum(stockqty) stock_qty,
	sum(Indqty) Reserved ,
	sum(Expected_by_07092022) Expected_by_07092022,
	sum(Expected_by_14092022) Expected_by_14092022,
	sum(Expected_by_22092022) Expected_by_22092022,
	sum(Expected_by_30092022) Expected_by_30092022,
	sum(Expected_after_30092022) Expected_after_30092022,
	sum(stockqty-indqty + Expected_Before_01092022 + Expected_by_07092022 + Expected_by_14092022 + Expected_by_22092022 + Expected_by_30092022 + Expected_after_30092022) Total_SQM
from
	(
	select
		group_code,
		item_code,
		0 \"Expected_Before_01092022\",
		0 \"Expected_by_07092022\",
		0 \"Expected_by_14092022\",
		0 \"Expected_by_22092022\",
		0 \"Expected_by_30092022\",
		0 \"Expected_after_30092022\",
		round(sum(received_qty-issued_qty + transfer_in_qty -transfer_out_qty + store_return_qty), 3) stockqty ,
		0 indqty
	from
		view_store_receipt_transfer_issue vsrti
	where
		company_id = ".$companyId."
		and tran_status not in (4, 6, 0)
	group by
		group_code,
		item_code,
		item_name
	having
		round(sum(received_qty-issued_qty + transfer_in_qty -transfer_out_qty + store_return_qty), 3)>0
union all
	select
		*,
		0 stkqty,
		0 indqty
	from
		(
		select
			group_code,
			item_code,
			0 \"Expected_Before_01092022\",
			round(quantity-gate_po_placed, 3) \"Expected_by_07092022\",
			0 \"Expected_by_14092022\",
			0 \"Expected_by_22092022\",
			0 \"Expected_by_30092022\",
			0 \"Expected_after_30092022\"
		from
			scm_po_hdr sph ,
			scm_po_line_item spli
		where
			sph.po_num = spli.po_num
			and (spli.actual_quantity -spli.gate_po_placed) >0
				and group_code <> '999'
				and sph.company_id = ".$companyId."
				and spli.status not in (4, 6, 0)
					and spli.is_active = 1
					and expected_date >= '2022-09-01'
					and expected_date <= '2022-09-07'
			union ALL
				select
					group_code,
					item_code,
					0 \"Expected_Before_01092022\",
					0 \"Expected_by_07092022\",
					round(quantity-gate_po_placed, 3) \"Expected_by_14092022\",
					0 \"Expected_by_22092022\",
					0 \"Expected_by_30092022\",
					0 \"Expected_after_30092022\"
				from
					scm_po_hdr sph ,
					scm_po_line_item spli
				where
					sph.po_num = spli.po_num
					and (spli.actual_quantity -spli.gate_po_placed) >0
						and group_code <> '999'
						and sph.company_id = ".$companyId."
						and spli.status not in (4, 6, 0)
							and spli.is_active = 1
							and expected_date >= '2022-09-08'
							and expected_date <= '2022-09-14'
					union ALL
						select
							group_code,
							item_code,
							0 \"Expected_Before_01092022\",
							0 \"Expected_by_07092022\",
							0 \"Expected_by_14092022\",
							round(quantity-gate_po_placed, 3) \"Expected_by_22092022\",
							0 \"Expected_by_30092022\",
							0 \"Expected_after_30092022\"
						from
							scm_po_hdr sph ,
							scm_po_line_item spli
						where
							sph.po_num = spli.po_num
							and (spli.actual_quantity -spli.gate_po_placed) >0
								and group_code <> '999'
								and sph.company_id = ".$companyId."
								and spli.status not in (4, 6, 0)
									and spli.is_active = 1
									and expected_date >= '2022-09-15'
									and expected_date <= '2022-09-22'
							union ALL
								select
									group_code,
									item_code,
									0 \"Expected_Before_01092022\",
									0 \"Expected_by_07092022\",
									0 \"Expected_by_14092022\",
									0 \"Expected_by_22092022\",
									round(quantity-gate_po_placed, 3) \"Expected_by_30092022\",
									0 \"Expected_after_30092022\"
								from
									scm_po_hdr sph ,
									scm_po_line_item spli
								where
									sph.po_num = spli.po_num
									and (spli.actual_quantity -spli.gate_po_placed) >0
										and group_code <> '999'
										and sph.company_id = ".$companyId."
										and spli.status not in (4, 6, 0)
											and spli.is_active = 1
											and expected_date >= '2022-09-23'
											and expected_date <= '2022-09-30'
									union ALL
										select
											group_code,
											item_code,
											0 \"Expected_Before_01092022\",
											0 \"Expected_by_07092022\",
											0 \"Expected_by_14092022\",
											0 \"Expected_by_22092022\",
											0 \"Expected_by_30092022\",
											round(quantity-gate_po_placed, 3) \"Expected_after_30092022\"
										from
											scm_po_hdr sph ,
											scm_po_line_item spli
										where
											sph.po_num = spli.po_num
											and (spli.actual_quantity -spli.gate_po_placed) >0
												and group_code <> '999'
												and sph.company_id = ".$companyId."
												and spli.status not in (4, 6, 0)
													and spli.is_active = 1
													and expected_date >'2022-09-30' 
) a
union all
	select
		group_code,
		item_code,
		0 \"Expected_Before_01092022\",
		0 \"Expected_by_07092022\",
		0 \"Expected_by_14092022\",
		0 \"Expected_by_22092022\",
		0 \"Expected_by_30092022\",
		0 \"Expected_after_30092022\",
		0 stockqty ,
		sili.indent_qty -sili.po_placed indqty
	from
		scm_indent_line_item sili
	where
		sili.indent_qty -sili.po_placed >0
) b,
	itemmaster im
where
	b.group_code = im.group_code
	and b.item_code = im.item_code
	and b.group_code <> '999'
	and company_id = ".$companyId."
group by
	b.group_code ,
	b.item_code ,
	item_desc
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

		
		$sql = "
		select
	b.group_code,
	b.item_code,
	item_desc,
	sum(stockqty) stock_qty,
	sum(Indqty) Reserved ,
	sum(Expected_by_07092022) Expected_by_07092022,
	sum(Expected_by_14092022) Expected_by_14092022,
	sum(Expected_by_22092022) Expected_by_22092022,
	sum(Expected_by_30092022) Expected_by_30092022,
	sum(Expected_after_30092022) Expected_after_30092022,
	sum(stockqty-indqty + Expected_Before_01092022 + Expected_by_07092022 + Expected_by_14092022 + Expected_by_22092022 + Expected_by_30092022 + Expected_after_30092022) Total_SQM
from
	(
	select
		group_code,
		item_code,
		0 \"Expected_Before_01092022\",
		0 \"Expected_by_07092022\",
		0 \"Expected_by_14092022\",
		0 \"Expected_by_22092022\",
		0 \"Expected_by_30092022\",
		0 \"Expected_after_30092022\",
		round(sum(received_qty-issued_qty + transfer_in_qty -transfer_out_qty + store_return_qty), 3) stockqty ,
		0 indqty
	from
		view_store_receipt_transfer_issue vsrti
	where
		company_id = ".$pers['company']."
		and tran_status not in (4, 6, 0)
	group by
		group_code,
		item_code,
		item_name
	having
		round(sum(received_qty-issued_qty + transfer_in_qty -transfer_out_qty + store_return_qty), 3)>0
union all
	select
		*,
		0 stkqty,
		0 indqty
	from
		(
		select
			group_code,
			item_code,
			0 \"Expected_Before_01092022\",
			round(quantity-gate_po_placed, 3) \"Expected_by_07092022\",
			0 \"Expected_by_14092022\",
			0 \"Expected_by_22092022\",
			0 \"Expected_by_30092022\",
			0 \"Expected_after_30092022\"
		from
			scm_po_hdr sph ,
			scm_po_line_item spli
		where
			sph.po_num = spli.po_num
			and (spli.actual_quantity -spli.gate_po_placed) >0
				and group_code <> '999'
				and sph.company_id = ".$pers['company']."
				and spli.status not in (4, 6, 0)
					and spli.is_active = 1
					and expected_date >= '2022-09-01'
					and expected_date <= '2022-09-07'
			union ALL
				select
					group_code,
					item_code,
					0 \"Expected_Before_01092022\",
					0 \"Expected_by_07092022\",
					round(quantity-gate_po_placed, 3) \"Expected_by_14092022\",
					0 \"Expected_by_22092022\",
					0 \"Expected_by_30092022\",
					0 \"Expected_after_30092022\"
				from
					scm_po_hdr sph ,
					scm_po_line_item spli
				where
					sph.po_num = spli.po_num
					and (spli.actual_quantity -spli.gate_po_placed) >0
						and group_code <> '999'
						and sph.company_id = ".$pers['company']."
						and spli.status not in (4, 6, 0)
							and spli.is_active = 1
							and expected_date >= '2022-09-08'
							and expected_date <= '2022-09-14'
					union ALL
						select
							group_code,
							item_code,
							0 \"Expected_Before_01092022\",
							0 \"Expected_by_07092022\",
							0 \"Expected_by_14092022\",
							round(quantity-gate_po_placed, 3) \"Expected_by_22092022\",
							0 \"Expected_by_30092022\",
							0 \"Expected_after_30092022\"
						from
							scm_po_hdr sph ,
							scm_po_line_item spli
						where
							sph.po_num = spli.po_num
							and (spli.actual_quantity -spli.gate_po_placed) >0
								and group_code <> '999'
								and sph.company_id = ".$pers['company']."
								and spli.status not in (4, 6, 0)
									and spli.is_active = 1
									and expected_date >= '2022-09-15'
									and expected_date <= '2022-09-22'
							union ALL
								select
									group_code,
									item_code,
									0 \"Expected_Before_01092022\",
									0 \"Expected_by_07092022\",
									0 \"Expected_by_14092022\",
									0 \"Expected_by_22092022\",
									round(quantity-gate_po_placed, 3) \"Expected_by_30092022\",
									0 \"Expected_after_30092022\"
								from
									scm_po_hdr sph ,
									scm_po_line_item spli
								where
									sph.po_num = spli.po_num
									and (spli.actual_quantity -spli.gate_po_placed) >0
										and group_code <> '999'
										and sph.company_id = ".$pers['company']."
										and spli.status not in (4, 6, 0)
											and spli.is_active = 1
											and expected_date >= '2022-09-23'
											and expected_date <= '2022-09-30'
									union ALL
										select
											group_code,
											item_code,
											0 \"Expected_Before_01092022\",
											0 \"Expected_by_07092022\",
											0 \"Expected_by_14092022\",
											0 \"Expected_by_22092022\",
											0 \"Expected_by_30092022\",
											round(quantity-gate_po_placed, 3) \"Expected_after_30092022\"
										from
											scm_po_hdr sph ,
											scm_po_line_item spli
										where
											sph.po_num = spli.po_num
											and (spli.actual_quantity -spli.gate_po_placed) >0
												and group_code <> '999'
												and sph.company_id = ".$pers['company']."
												and spli.status not in (4, 6, 0)
													and spli.is_active = 1
													and expected_date >'2022-09-30' 
) a
union all
	select
		group_code,
		item_code,
		0 \"Expected_Before_01092022\",
		0 \"Expected_by_07092022\",
		0 \"Expected_by_14092022\",
		0 \"Expected_by_22092022\",
		0 \"Expected_by_30092022\",
		0 \"Expected_after_30092022\",
		0 stockqty ,
		sili.indent_qty -sili.po_placed indqty
	from
		scm_indent_line_item sili
	where
		sili.indent_qty -sili.po_placed >0
) b,
	itemmaster im
where
	b.group_code = im.group_code
	and b.item_code = im.item_code
	and b.group_code <> '999'
	and company_id = ".$pers['company']."
group by
	b.group_code ,
	b.item_code ,
	item_desc";
		
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
	
