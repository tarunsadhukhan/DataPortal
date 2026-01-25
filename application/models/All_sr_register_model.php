<?php
class All_sr_register_model extends CI_Model
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

		$itcode = $_POST['itcod'];
		$itemdesc = $_POST['itemdesc'];
		$suppname = $_POST['suppname'];

	//	echo 'supp name'.$suppname;

$sql="select store_receipt_no,store_receipt_date,recpdate,challanno,challanno_date,
supp_name,round(sum(amount+tax_amount),0) tot_amount from
(
select tpi.company,tpi.store_receipt_no,store_receipt_date,DATE_FORMAT(store_receipt_date,'%d-%m-%Y') recpdate,
tpi.challanno,DATE_FORMAT(tpi.challanno_date,'%d-%m-%Y') challanno_date,s.supp_name,tpid.approved_qty ,
(tpid.approved_qty * tpid.rate) amount,
case when igst_percentage>0 then 
round((tpid.approved_qty * tpid.rate)*tpid.igst_percentage/100,2)
when cgst_percentage+sgst_percentage>0 then 
round((tpid.approved_qty * tpid.rate)*tpid.cgst_percentage/100,2)+round((tpid.approved_qty * tpid.rate)*tpid.sgst_percentage/100,2)
else 0 end tax_amount 
from tbl_proc_inward tpi 
left join suppliermaster s on s.supp_id =tpi.supplier 
left join tbl_proc_inward_detail tpid on tpi.inward_id =tpid.inward
where  substr(store_receipt_date,1,10) between '".$from_date."' and '".$to_date."'
and store_approved_by is not null and tpi.sr_status =3 and tpid.is_active =1 and company=  ".$companyId."
) g ";
if ($suppname) {
	$sql=$sql." and supp_name like '%".$suppname."%'";
}
$sql=$sql." group by company,store_receipt_no,store_receipt_date,recpdate,challanno,challanno_date,supp_name 
order by store_receipt_date  ";


$sql="select
	store_receipt_no,
	store_receipt_date,
	recpdate,
	challanno,
	challanno_date,
	supp_name ,
	case when abs(round_off_value)>0 then
	sum(amount + tax_amount-discount)+round_off_value 
	else
	case when sum(amount + tax_amount-discount)-round(sum(amount + tax_amount-discount),0)=.5 
	then round(sum(amount + tax_amount-discount),0)+1
	else round(sum(amount + tax_amount-discount),0) end  
	end tot_amount
	from
	(
	select g.*,	case
			when igst_percentage>0 then 
			round (( amount-discount) * igst_percentage / 100, 2)
			when cgst_percentage + sgst_percentage>0 then 
			round ((amount-discount) * cgst_percentage / 100, 2)+round( (amount-discount)* sgst_percentage / 100, 2)
			else 0
		end tax_amount from (
	select
		tpi.company,
		tpi.store_receipt_no,
		store_receipt_date,
		DATE_FORMAT(store_receipt_date, '%d-%m-%Y') recpdate,
		tpi.challanno,
		DATE_FORMAT(tpi.challanno_date, '%d-%m-%Y') challanno_date,
		s.supp_name,
		tpid.approved_qty ,
		(tpid.approved_qty * tpid.rate) amount,
		case when tpid.discount_mode=2 then round((tpid.approved_qty * tpid.rate)*tpid.discount /100,2) else discount end discount,
		tpid.igst_percentage,tpid.cgst_percentage,tpid.sgst_percentage  ,tpi.round_off_value 
	from tbl_proc_inward tpi 
left join suppliermaster s on s.supp_id =tpi.supplier 
left join tbl_proc_inward_detail tpid on tpi.inward_id =tpid.inward
where  substr(store_receipt_date,1,10) between '".$from_date."' and '".$to_date."'
and store_approved_by is not null and tpi.sr_status =3 and tpid.is_active =1 and company=  ".$companyId."
) g ) v
group by
	company,
	store_receipt_no,
	store_receipt_date,
	recpdate,
	challanno,
	challanno_date,
	supp_name,round_off_value 
order by
	store_receipt_date";




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

		$itcode = $pers['itcod'];
		$itemdesc = $pers['itemdesc'];
		$suppname = $pers['suppname'];
 
$sql="select store_receipt_no,recpdate,challanno,challanno_date,supp_name,round(sum(amount+tax_amount),0) tot_amount from
(
select tpi.company,tpi.store_receipt_no,store_receipt_date,DATE_FORMAT(store_receipt_date,'%d-%m-%Y') recpdate,
tpi.challanno,DATE_FORMAT(tpi.challanno_date,'%d-%m-%Y') challanno_date,s.supp_name,tpid.approved_qty ,
(tpid.approved_qty * tpid.rate) amount,
case when igst_percentage>0 then 
round((tpid.approved_qty * tpid.rate)*tpid.igst_percentage/100,2)
when cgst_percentage+sgst_percentage>0 then 
round((tpid.approved_qty * tpid.rate)*tpid.cgst_percentage/100,2)+round((tpid.approved_qty * tpid.rate)*tpid.sgst_percentage/100,2)
else 0 end tax_amount 
from tbl_proc_inward tpi 
left join suppliermaster s on s.supp_id =tpi.supplier 
left join tbl_proc_inward_detail tpid on tpi.inward_id =tpid.inward
where  substr(store_receipt_date,1,10) between '".$pers['from_date']."' and '".$pers['to_date']."'
and store_approved_by is not null and tpi.sr_status =3 and tpid.is_active =1
) g where company= ".$pers['company'];
if ($suppname) {
	$sql=$sql." and supp_name like '%".$suppname."%'";
}
$sql=$sql." group by company,store_receipt_no,store_receipt_date,recpdate,challanno,challanno_date,supp_name 
order by store_receipt_date  ";

$sql="select
	store_receipt_no,
	store_receipt_date,
	recpdate,
	challanno,
	challanno_date,
		supp_name ,
	case when abs(round_off_value)>0 then
	sum(amount + tax_amount-discount)+round_off_value 
	else
	case when sum(amount + tax_amount-discount)-round(sum(amount + tax_amount-discount),0)=.5 
	then round(sum(amount + tax_amount-discount),0)+1
	else round(sum(amount + tax_amount-discount),0) end  
	end tot_amount
	from
	(
	select g.*,	case
			when igst_percentage>0 then 
			round (( amount-discount) * igst_percentage / 100, 2)
			when cgst_percentage + sgst_percentage>0 then 
			round ((amount-discount) * cgst_percentage / 100, 2)+round( (amount-discount)* sgst_percentage / 100, 2)
			else 0
		end tax_amount from (
	select
		tpi.company,
		tpi.store_receipt_no,
		store_receipt_date,
		DATE_FORMAT(store_receipt_date, '%d-%m-%Y') recpdate,
		tpi.challanno,
		DATE_FORMAT(tpi.challanno_date, '%d-%m-%Y') challanno_date,
		s.supp_name,
		tpid.approved_qty ,
		(tpid.approved_qty * tpid.rate) amount,
		case when tpid.discount_mode=2 then round((tpid.approved_qty * tpid.rate)*tpid.discount /100,2) else discount end discount,
		tpid.igst_percentage,tpid.cgst_percentage,tpid.sgst_percentage  ,tpi.round_off_value
	from tbl_proc_inward tpi 
left join suppliermaster s on s.supp_id =tpi.supplier 
left join tbl_proc_inward_detail tpid on tpi.inward_id =tpid.inward
where  substr(store_receipt_date,1,10)between '".$pers['from_date']."' and '".$pers['to_date']."'
and store_approved_by is not null and tpi.sr_status =3 and tpid.is_active =1 and company= ".$pers['company']."
) g ) v
group by
	company,
	store_receipt_no,
	store_receipt_date,
	recpdate,
	challanno,
	challanno_date,
	supp_name,round_off_value
order by
	store_receipt_date";


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
	
