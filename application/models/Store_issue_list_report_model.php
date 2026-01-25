<?php
class Store_issue_list_report_model extends CI_Model
{


	var $table = 'view_jute_receipt_issue_sale';	
	var $column_order = array(null, 'company_code','company_name'); //set column field database for datatable orderable
	var $column_search = array( 'company_code','company_name'); //set column field database for datatable searchable 
	// var $order = array('comp_id' => 'desc'); // default order



   
	
	public function __construct()
	{		
		$this->load->database();		
		$this->load->library('session');	}
	

	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
/*
$sql =  "SELECT `values`.`Quality ID` as `Quality_ID`, `values`.`Quality Name`  as `Quality_Name`,
		SUM(`values`.`Receipt Bales`) as `Receipt_Bales`,
		SUM(`values`.`Issue Bales`) as `Issue_Bales` ,
		SUM(`values`.`Sold Bales`) as `Sold_Bales`,
		SUM(`values`.`Drums`) as `Drums` ,
		SUM(`values`.`Drums Issued`) as `Drums_Issued`,
		SUM(`values`.`Drums Sold`) as `Drums_Sold`,
		SUM(`values`.`Receipt Wt.(QNT)`) as `Receipt_Wt` ,
		SUM(`values`.`Issued Wt.(QNT)`) as `Issued_Wt`,
		SUM(`values`.`Sold Wt.(QNT)`) as `Sold_Wt`,
		SUM(`values`.`Avg. Issue Rate`) as `Avg_Issue_Rate`,
		SUM(`values`.`Issued Val (In Lakhs)`) as `Issued_Val`,
		SUM(`values`.`Opening Bales`) as `Opening_Bales`,
		SUM(`values`.`Opening Drums`) as `Opening_Drums`,
		SUM(`values`.`Opening Wt.(QNT)`) as `Opening_Wt`
		from (SELECT quality_code AS 'Quality ID',
		quality_name AS 'Quality Name',
		ROUND(SUM(bales_receipt), 2) AS 'Receipt Bales',
		ROUND(SUM(bales_issued), 2) AS 'Issue Bales',
		ROUND(SUM(bales_sold), 2) AS 'Sold Bales',
		ROUND(SUM(drums_receipt),2) AS 'Drums',
		ROUND(SUM(drums_issued),2) AS 'Drums Issued',
		ROUND(SUM(drums_sold), 2) AS 'Drums Sold',
		ROUND(SUM(accepted_weight),2) AS 'Receipt Wt.(QNT)',
		ROUND(SUM(weight_issued), 2) AS 'Issued Wt.(QNT)',
		ROUND(SUM(weight_sold), 2) AS 'Sold Wt.(QNT)',
		IFNULL(ROUND(SUM(weight_issued * rate) / SUM(weight_issued),2),0) AS 'Avg. Issue Rate',
		ROUND(SUM(weight_issued * rate) / 100000,2) AS 'Issued Val (In Lakhs)',0 AS 'Opening Bales',0 AS 'Opening Drums',0 AS 'Opening Wt.(QNT)'
		FROM view_jute_receipt_issue_sale
		WHERE company_id = ".$companyId." AND tran_date >= '".date('Y-m-d',strtotime($from_date))."' and tran_date<= '".date('Y-m-d',strtotime($to_date))."'  
		AND tran_status NOT IN (4 ,6) GROUP BY `quality_code` , `quality_name`  UNION  SELECT  quality_code AS 'Quality ID', quality_name AS 'Quality Name', 0 AS 'Receipt Bales', 0 AS 'Issue Bales', 0 AS 'Sold Bales', 0 AS 'Drums', 0 AS 'Drums Issued', 0 AS 'Drums Sold', 0 AS 'Receipt Wt.(QNT)', 0 AS 'Issued Wt.(QNT)', 0 AS 'Sold Wt.(QNT)', 0 AS 'Avg. Issue Rate', 0 AS 'Issued Val (In Lakhs)', ROUND(SUM(bales_receipt) -  SUM(bales_issued) - SUM(bales_sold), 2) AS 'Opening Bales', ROUND(SUM(drums_receipt)- SUM(drums_issued) - SUM(drums_sold), 2) AS 'Opening Drums', ROUND(SUM(accepted_weight) - SUM(weight_issued) - SUM(weight_sold), 2) AS 'Opening Wt.(QNT)'     
		FROM view_jute_receipt_issue_sale     
		WHERE  company_id = ".$companyId."  and tran_date < '".date('Y-m-d', strtotime($from_date))."'   and tran_status not in (4,6)      
		GROUP BY `quality_code` ,`quality_name`  ) `values` 
		GROUP BY `values`.`Quality ID`,`values`.`Quality Name` ORDER BY `values`.`Quality ID`";
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
*/
$eb_no = $_POST['eb_no'];
	
$this->load->library('session');
		$itcode = $_POST['itcod'];
		$costcenter = $_POST['costcenter'];
		$itemdesc = $_POST['itemdesc'];
//		echo $itemdesc;

        $sql="select hdr_id Issue_No,issuedate Issue_Date,dept_desc Department , g.itemcode Item_Code,
		item_desc Item_Description,uom_code Unit,cost_desc Cost_Center,issue_qty Issue_Quantity,
		issue_value Issue_Value,indent_type_desc EXP_Type,branch_name Branch,store_print_no SR_No,
		mm.mechine_name Mechine_Name from 
		(
		select sih.company_id,cmm.company_name,bm.branch_name,sih.hdr_id,issue_date,DATE_FORMAT(issue_date, '%d-%m-%Y') 
		issuedate ,md.dept_desc,cm.cost_desc, concat(im.group_code,im.item_code) itemcode,im.item_desc,im.uom_code,sih.issue_qty,sih.issue_value,
		sitm.indent_type_desc,sih.machine_id,tpi.store_receipt_no store_print_no
		from scm_issue_hdr sih,branch_master bm,company_master cmm, master_department md,costmaster cm,itemmaster im,scm_indent_type_master sitm ,
		tbl_proc_inward tpi
		where sih.company_id=cmm.comp_id and sih.branch_id=bm.branch_id
		and sih.deptcost=cm.id and sih.company_id=cm.company_id and sih.item_id=im.item_id
		and sih.indent_type_id=sitm.indent_type_code and sih.is_active=1 
		and sih.inward =tpi.inward_id  and sih.dept_id=md.rec_id and issue_date  between '".date('Y-m-d',strtotime($from_date))."' 
		and '".date('Y-m-d',strtotime($to_date))."'
		and sih.company_id= ".$companyId."
		) g
		left  join
		mechine_master mm on g.machine_id=mm.mechine_id ";
		$ln=0;
		$n=1;	
		$ln=strlen($itcode)+strlen($costcenter)+strlen($itemdesc);
		if ($ln>0) {
			$sql .= " where  ";
		}	
		if($itcode){
			$sql .= "   itemcode= '".$itcode."'";
		}
		if($itemdesc){
			$sql .= "   item_desc like  '%".$itemdesc."%'";
		}

		$sql .= " order by hdr_id";


		$sql="select
		hdr_id Issue_No,
		issuedate Issue_Date,
		dept_desc Department ,
		g.itemcode Item_Code,
		item_desc Item_Description,
		uom_code Unit,
		cost_desc Cost_Center,
		issue_qty Issue_Quantity,
		issue_value Issue_Value,
		indent_type_desc EXP_Type,
		branch_name Branch,
		store_print_no SR_No,
		mm.mechine_name Mechine_Name,
		g.company_id
	from
		(
		select
			sih.company_id,
			cmm.company_name,
			bm.branch_name,
			sih.hdr_id,
			issue_date,
			DATE_FORMAT(issue_date, '%d-%m-%Y') issuedate ,
			md.dept_desc,
			cm.cost_desc,
			concat(im.group_code, im.item_code) itemcode,
			im.item_desc,
			im.uom_code,
			sih.issue_qty,
			sih.issue_value,
			sitm.indent_type_desc,
			sih.machine_id,
			tpi.store_receipt_no store_print_no
		from
			scm_issue_hdr sih,
			branch_master bm,
			company_master cmm,
			master_department md,
			costmaster cm,
			itemmaster im,
			scm_indent_type_master sitm ,
			tbl_proc_inward tpi
		where
			sih.company_id = cmm.comp_id
			and sih.branch_id = bm.branch_id
			and sih.deptcost = cm.id
			and sih.company_id = cm.company_id
			and sih.item_id = im.item_id
			and sih.indent_type_id = sitm.indent_type_code
			and sih.is_active = 1
			and sih.inward = tpi.inward_id
			and sih.dept_id = md.rec_id
			 ) g
	left join mechine_master mm on
		g.machine_id = mm.mechine_id
	where  issue_date between '".date('Y-m-d',strtotime($from_date))."' 
	and '".date('Y-m-d',strtotime($to_date))."'
	and g.company_id= ".$companyId; 
	if($itcode){
		$sql .= "  and itemcode= '".$itcode."'";
	}
	if($costcenter){
		$sql .= "  and  cost_desc like  '%".$costcenter."%'";
	}
	if($itemdesc){
		$sql .= "   and item_desc like  '%".$itemdesc."%'";
	}

	
	$sql .= " order by hdr_id";



//		echo $sql;

//echo $sql;
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];
//echo $sql;
		$query = $this->db->query($sql);
//		 $this->varaha->print_arrays($this->db->last_query());
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

		$sql="select hdr_id `Issue_No`,issuedate `Issue_Date`,dept_desc `Department` , g.itemcode `Item_Code`,
		item_desc `Item_Description`,uom_code Unit,cost_desc `Cost_Center`,issue_qty `Issue_Quantity`,
		issue_value `Issue_Value`,indent_type_desc `EXP_Type`,branch_name Branch,store_print_no `SR_No`,
		mm.mechine_name `Mechine_Name` from 
		(
		select sih.company_id,cmm.company_name,bm.branch_name,sih.hdr_id,issue_date,DATE_FORMAT(issue_date, '%d-%m-%Y') 
		issuedate ,md.dept_desc,cm.cost_desc, concat(im.group_code,im.item_code) itemcode,im.item_desc,im.uom_code,sih.issue_qty,sih.issue_value,
		sitm.indent_type_desc,sih.machine_id,tpi.store_receipt_no store_print_no
		from scm_issue_hdr sih,branch_master bm,company_master cmm, master_department md,costmaster cm,itemmaster im,scm_indent_type_master sitm ,
		tbl_proc_inward tpi
		where sih.company_id=cmm.comp_id and sih.branch_id=bm.branch_id
		and sih.deptcost=cm.id and sih.company_id=cm.company_id and sih.item_id=im.item_id
		and sih.indent_type_id=sitm.indent_type_code and sih.is_active=1 
		and sih.inward =tpi.inward_id  and sih.dept_id=md.rec_id and issue_date  between '".date('Y-m-d',strtotime($pers['from_date']))."'  
		and '".date('Y-m-d',strtotime($pers['to_date']))."' 
		and sih.company_id=".$pers['company']."
		) g
		left  join
		mechine_master mm on g.machine_id=mm.mechine_id order by hdr_id";
//echo $sql;
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