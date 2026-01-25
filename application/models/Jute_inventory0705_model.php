<?php
class Jute_inventory0705_model extends CI_Model
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

$sql="select * from (
select
	1 rem,
	item_code,
	actual_quality,
	jute_quality Quality,
	sum(openrweight),
	sum(issuerweight),
	sum(openrweight-issuerweight) Opening_Stock,
	sum(recvweight) recvweight,
	sum(issueweight) issueweight,
	sum(openrweight-issuerweight + recvweight-issueweight) closweight
from
	(
	SELECT
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality,
		SUM(CASE WHEN smh.jute_receive_dt >= '2025-05-07' AND smh.jute_receive_dt < '".date('Y-m-d',strtotime($from_date))."' THEN smli.accepted_weight ELSE 0 END) AS openrweight,
		SUM(CASE WHEN smh.jute_receive_dt between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		  THEN smli.accepted_weight ELSE 0 END) AS recvweight,
		0 AS issuerweight,
		0 issueweight
	FROM
		scm_mr_line_item smli
	LEFT JOIN scm_mr_hdr smh ON
		smli.jute_receive_no = smh.jute_receive_no
	LEFT JOIN jute_quality_price_master jqpm ON
		smli.item_code = jqpm.item_code
		AND smli.actual_quality = jqpm.id
	WHERE
		smh.mr_good_recept_status NOT IN (4, 6)
		AND smli.status NOT IN (4, 6)
		AND smli.is_active = 1
		AND smh.jute_receive_dt >= '2025-05-07'
		AND smh.jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."'
		AND smh.company_id = 2
	GROUP BY
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality
union all
	SELECT
		ji.jute_type item_code,
		ji.jute_quality actual_quality,
		jqpm.jute_quality,
		0 openrweight,
		0 recvweight ,
		SUM(CASE WHEN ji.issue_date >= '2025-05-07' AND ji.issue_date < '".date('Y-m-d',strtotime($from_date))."' THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issuerweight,
		SUM(CASE WHEN ji.issue_date between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		 THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issueweight
	FROM
		jute_issue ji
	LEFT JOIN jute_quality_price_master jqpm ON
		ji.jute_type = jqpm.item_code
		AND ji.jute_quality = jqpm.id
	WHERE
		ji.issue_status NOT IN (4, 6)
		AND ji.is_active = 1
		AND ji.issue_date >= '2025-05-07'
		AND ji.issue_date <= '".date('Y-m-d',strtotime($to_date))."'
		AND ji.company_id = 2
		and ji.bale_loose not in ('WASTAGE')
	GROUP BY
		ji.jute_type,
		ji.jute_quality,
		jqpm.jute_quality ) g
group by
	item_code,
	actual_quality,
	jute_quality
	union all	
	 select 2 rem,' ' item_code,
	'' actual_quality,
	'Grand Total'  Quality,
	sum(openrweight),
	sum(issuerweight),
	sum(openrweight-issuerweight) Opening_Stock,
	sum(recvweight) recvweight,
	sum(issueweight) issueweight,
	sum(openrweight-issuerweight + recvweight-issueweight) closweight
from
	(
	SELECT
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality,
		SUM(CASE WHEN smh.jute_receive_dt >= '2025-05-07' AND smh.jute_receive_dt < '".date('Y-m-d',strtotime($from_date))."' THEN smli.accepted_weight ELSE 0 END) AS openrweight,
		SUM(CASE WHEN smh.jute_receive_dt between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."' 
		THEN smli.accepted_weight ELSE 0 END) AS recvweight,
		0 AS issuerweight,
		0 issueweight
	FROM
		scm_mr_line_item smli
	LEFT JOIN scm_mr_hdr smh ON
		smli.jute_receive_no = smh.jute_receive_no
	LEFT JOIN jute_quality_price_master jqpm ON
		smli.item_code = jqpm.item_code
		AND smli.actual_quality = jqpm.id
	WHERE
		smh.mr_good_recept_status NOT IN (4, 6)
			AND smli.status NOT IN (4, 6)
				AND smli.is_active = 1
				AND smh.jute_receive_dt >= '2025-05-07'
				AND smh.jute_receive_dt <= '".date('Y-m-d',strtotime($to_date))."'
				AND smh.company_id = 2
			GROUP BY
				smli.item_code,
				smli.actual_quality,
				jqpm.jute_quality
union all
	select jos.item_code,quality_id actual_quality,jute_quality,weight openrweight,0 recvweight ,0 AS issuerweight,	0 issueweight
 from  EMPMILL12.jute_op_stock jos
left join jute_quality_price_master jqpm on jos.item_code = jqpm.item_code
		AND jos.quality_id = jqpm.id
		union all
			SELECT
				ji.jute_type item_code,
				ji.jute_quality actual_quality,
				jqpm.jute_quality,
				0 openrweight,
				0 recvweight ,
				SUM(CASE WHEN ji.issue_date >= '2025-05-07' AND ji.issue_date < '".date('Y-m-d',strtotime($from_date))."' THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issuerweight,
				SUM(CASE WHEN ji.issue_date between '".date('Y-m-d',strtotime($from_date))."' and  '".date('Y-m-d',strtotime($to_date))."'
				 THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issueweight
			FROM
				jute_issue ji
			LEFT JOIN jute_quality_price_master jqpm ON
				ji.jute_type = jqpm.item_code
				AND ji.jute_quality = jqpm.id
			WHERE
				ji.issue_status NOT IN (4, 6)
					AND ji.is_active = 1
					AND ji.issue_date >= '2025-05-07'
					AND ji.issue_date <= '".date('Y-m-d',strtotime($to_date))."'
					AND ji.company_id = 2
					and ji.bale_loose not in ('WASTAGE')
				GROUP BY
					ji.jute_type,
					ji.jute_quality,
					jqpm.jute_quality ) g
) k order by rem,Quality        
    
";

//echo $sql;

 
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

     //   echo "Direct Report Called";
		$from_date=$pers['from_date'];
		$to_date=$pers['to_date'];
 
$sql="select * from (
select
	1 rem,
	item_code,
	actual_quality,
	jute_quality Quality,
	sum(openrweight),
	sum(issuerweight),
	sum(openrweight-issuerweight) Opening_Stock,
	sum(recvweight) recvweight,
	sum(issueweight) issueweight,
	sum(openrweight-issuerweight + recvweight-issueweight) closweight
from
	(
	SELECT
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality,
		SUM(CASE WHEN smh.jute_receive_dt >= '2025-05-07' AND smh.jute_receive_dt < '".date('Y-m-d',strtotime($from_date))."' THEN smli.accepted_weight ELSE 0 END) AS openrweight,
		SUM(CASE WHEN smh.jute_receive_dt between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		  THEN smli.accepted_weight ELSE 0 END) AS recvweight,
		0 AS issuerweight,
		0 issueweight
	FROM
		scm_mr_line_item smli
	LEFT JOIN scm_mr_hdr smh ON
		smli.jute_receive_no = smh.jute_receive_no
	LEFT JOIN jute_quality_price_master jqpm ON
		smli.item_code = jqpm.item_code
		AND smli.actual_quality = jqpm.id
	WHERE
		smh.mr_good_recept_status NOT IN (4, 6)
		AND smli.status NOT IN (4, 6)
		AND smli.is_active = 1
		AND smh.jute_receive_dt >= '2025-05-07'
		AND smh.jute_receive_dt<= '".date('Y-m-d',strtotime($to_date))."'
		AND smh.company_id = 2
	GROUP BY
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality
union all
	SELECT
		ji.jute_type item_code,
		ji.jute_quality actual_quality,
		jqpm.jute_quality,
		0 openrweight,
		0 recvweight ,
		SUM(CASE WHEN ji.issue_date >= '2025-05-07' AND ji.issue_date < '".date('Y-m-d',strtotime($from_date))."' THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issuerweight,
		SUM(CASE WHEN ji.issue_date between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."'
		 THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issueweight
	FROM
		jute_issue ji
	LEFT JOIN jute_quality_price_master jqpm ON
		ji.jute_type = jqpm.item_code
		AND ji.jute_quality = jqpm.id
	WHERE
		ji.issue_status NOT IN (4, 6)
		AND ji.is_active = 1
		AND ji.issue_date >= '2025-05-07'
		AND ji.issue_date <= '".date('Y-m-d',strtotime($to_date))."'
		AND ji.company_id = 2
		and ji.bale_loose not in ('WASTAGE')
	GROUP BY
		ji.jute_type,
		ji.jute_quality,
		jqpm.jute_quality ) g
group by
	item_code,
	actual_quality,
	jute_quality
	union all	
	 select 2 rem,' ' item_code,
	'' actual_quality,
	'Grand Total'  Quality,
	sum(openrweight),
	sum(issuerweight),
	sum(openrweight-issuerweight) Opening_Stock,
	sum(recvweight) recvweight,
	sum(issueweight) issueweight,
	sum(openrweight-issuerweight + recvweight-issueweight) closweight
from
	(
	SELECT
		smli.item_code,
		smli.actual_quality,
		jqpm.jute_quality,
		SUM(CASE WHEN smh.jute_receive_dt >= '2025-05-07' AND smh.jute_receive_dt < '".date('Y-m-d',strtotime($from_date))."' THEN smli.accepted_weight ELSE 0 END) AS openrweight,
		SUM(CASE WHEN smh.jute_receive_dt between  '".date('Y-m-d',strtotime($from_date))."' and '".date('Y-m-d',strtotime($to_date))."' 
		THEN smli.accepted_weight ELSE 0 END) AS recvweight,
		0 AS issuerweight,
		0 issueweight
	FROM
		scm_mr_line_item smli
	LEFT JOIN scm_mr_hdr smh ON
		smli.jute_receive_no = smh.jute_receive_no
	LEFT JOIN jute_quality_price_master jqpm ON
		smli.item_code = jqpm.item_code
		AND smli.actual_quality = jqpm.id
	WHERE
		smh.mr_good_recept_status NOT IN (4, 6)
			AND smli.status NOT IN (4, 6)
				AND smli.is_active = 1
				AND smh.jute_receive_dt >= '2025-05-07'
				AND smh.jute_receive_dt <= '".date('Y-m-d',strtotime($to_date))."'
				AND smh.company_id = 2
			GROUP BY
				smli.item_code,
				smli.actual_quality,
				jqpm.jute_quality
union all
	select jos.item_code,quality_id actual_quality,jute_quality,weight openrweight,0 recvweight ,0 AS issuerweight,	0 issueweight
 from  EMPMILL12.jute_op_stock jos
left join jute_quality_price_master jqpm on jos.item_code = jqpm.item_code
		AND jos.quality_id = jqpm.id
		union all
			SELECT
				ji.jute_type item_code,
				ji.jute_quality actual_quality,
				jqpm.jute_quality,
				0 openrweight,
				0 recvweight ,
				SUM(CASE WHEN ji.issue_date >= '2025-05-07' AND ji.issue_date < '".date('Y-m-d',strtotime($from_date))."' THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issuerweight,
				SUM(CASE WHEN ji.issue_date between '".date('Y-m-d',strtotime($from_date))."' and  '".date('Y-m-d',strtotime($to_date))."'
				 THEN ROUND(ji.total_weight * 100, 0) ELSE 0 END) AS issueweight
			FROM
				jute_issue ji
			LEFT JOIN jute_quality_price_master jqpm ON
				ji.jute_type = jqpm.item_code
				AND ji.jute_quality = jqpm.id
			WHERE
				ji.issue_status NOT IN (4, 6)
					AND ji.is_active = 1
					AND ji.issue_date >= '2025-05-07'
					AND ji.issue_date <= '".date('Y-m-d',strtotime($to_date))."'
					AND ji.company_id = 2
					and ji.bale_loose not in ('WASTAGE')
				GROUP BY
					ji.jute_type,
					ji.jute_quality,
					jqpm.jute_quality ) g
) k order by rem,Quality        
    
";


//echo $sql;


		
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
	
