<?php
class Man_mechine_report_module extends CI_Model
{

	var $table = 'daily_attendance da ';	
	var $column_order = array(null, 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable orderable
	var $column_search = array( 'Tran_No','EB_No','Name','Date','Department','Designation','Mark','Spell','Idle_Hours','Spell_Hours','Work_Hours','Source','Type','Status','Remarks'); //set column field database for datatable searchable 
	// var $order = array('id' => 'desc'); // default order
    // var $order = array('a.godown','a.godown_name' ,'m.item_desc', 'a.quality_name');
	
	public function __construct()
	{		
		$this->load->database();		
	}


	private function _get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		$att_type = $_POST['att_type'];
		$att_status = $_POST['att_status'];
		$att_desig = (isset($_POST['att_desig']) ? $_POST['att_desig'] : null);
		$att_spells = (isset($_POST['att_spells']) ? $_POST['att_spells'] : null);
		$eb_no = $_POST['eb_no'];
		$att_cat_att = $_POST['att_cat_att'];
        $srno = $_POST['srno'];


        $compid=$companyId;
        $paydate= $from_date;
    	$Source = $_POST['Source'];
		$att_dept = (isset($_POST['att_dept']) ? $_POST['att_dept']: null);
		$itcod = $_POST['itcod'];
        $spaydate=substr($paydate,0,4).'-'.substr($paydate,5,2).'-01';
 
	 
        $sql="select k.*,md.dept_desc,case when rem=2 then concat(dept_desc,' Department Total')  else desig end desigd 
        ,(shift_a+shift_b+shift_c) totshift,(target_a+target_b+target_c) tottarget from ( 
            select tdd.desig_id,tdhands,tdexcess,tdshort,ifnull(tdt.shift_a,0) shift_a,
            ifnull(tdt.shift_b,0) shift_b,
            ifnull(tdt.shift_c,0) shift_c,
            ifnull(target_a,0) target_a,
            ifnull(target_b,0) target_b,
            ifnull(target_c,0) target_c,
            ifnull(excess_hands,0) excess_hands,
            ifnull(short_hands,0) short_hands, 
            desig,ifnull(HOCCU_CODE,'') HOCCU_CODE,ifnull(dept_code,'') dept_code,
            ifnull(omn.DIRECT_INDIRECT,'') DIRECT_INDIRECT , 1 rem from (
            select desig_id,sum(shift_a+shift_b+shift_c) tdhands, sum(excess_hands) tdexcess,sum(short_hands) tdshort from 
            EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date between '".$spaydate."' and '".$paydate."' and is_active =1 and company_id =".$compid."
            group by desig_id 
            ) tdd left join
            (select desig_id tdtdesig,shift_a,shift_b,shift_c,target_a,target_b,target_c, excess_hands,short_hands from 
            EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date ='".$paydate."' and is_active =1 and company_id =".$compid."
            ) tdt on tdd.desig_id=tdt.tdtdesig
             left join designation d on d.id=tdd.desig_id
             left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id=tdd.desig_id
             left join master_department md on md.mdept_id=d.department and d.company_id=md.company_id
            union all
            select 0 desig_id,sum(shift_a+shift_b+shift_c) tdhands,sum(tdexcess) tdexcess,sum(tdshort) tdshort,sum(shift_a) shift_a,sum(shift_b) shift_b,sum(shift_c) shift_c,
            sum(target_a) target_a,sum(target_b) target_b,sum(target_c) target_c,sum(excess_hands) excess_hands,sum(short_hands) short_hands,
            'TOTAL' desig,'' HOCCU_CODE,ifnull(dept_code,'') dept_code,'' DIRECT_INDIRECT, 2 rem from (
            select tdd.desig_id,tdhands,tdexcess,tdshort,tdt.shift_a,tdt.shift_b,tdt.shift_c,target_a,target_b,target_c, excess_hands,short_hands,
            desig,HOCCU_CODE,dept_code,omn.DIRECT_INDIRECT, 1 rem from (
            select desig_id,sum(shift_a+shift_b+shift_c) tdhands, sum(excess_hands) tdexcess,sum(short_hands) tdshort from 
            EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date between '".$spaydate."' and '".$paydate."' and is_active =1 and company_id =".$compid."
            group by desig_id 
            ) tdd left join
            (select desig_id tdtdesig,shift_a,shift_b,shift_c,target_a,target_b,target_c, excess_hands,short_hands from 
            EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date ='".$paydate."' and is_active =1 and company_id =".$compid."
            ) tdt on tdd.desig_id=tdt.tdtdesig
             left join designation d on d.id=tdd.desig_id
             left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id=tdd.desig_id
             left join master_department md on md.mdept_id=d.department and d.company_id=md.company_id
            ) g group by dept_code  
            ) k
            left join master_department md on md.dept_code=k.dept_code 
            where md.company_id=".$compid." and desig_id>0 
            ";
        if ($att_dept != '0' && $att_dept != '' && $att_dept != null) {
            $sql .= " and dept_desc= '" . $att_dept . "'";
        }


        $sql=$sql." order by dept_code,rem,HOCCU_CODE 
            ";
 

         



	//	echo $sql;
		return $sql;
	}

	function get_datatables($mainmenuId,$submenuId, $companyId, $from_date,$to_date)
	{
		
		$sql = $this->_get_datatables_query($mainmenuId,$submenuId, $companyId, $from_date,$to_date);
//		 $this->varaha->print_arrays($sql);
		if($_POST['length'] != -1)
		$sql = $sql .= " LIMIT ". $_POST['start'].",". $_POST['length'];	
		$query = $this->db->query($sql);
//		 $this->varaha->print_arrays($this->db->last_query());
		// $resdata=array(
		// 	'result' => $query->result(),
		// 	'num_rows' => $query->num_rows(),
		// );
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

		$att_cat = $pers['att_cat'];
        $srno = $pers['srno'];
		$att_cat_att = $pers['att_cat_att'];
        $from_date  =$pers['from_date'];             
        $companyId=$pers['company'];
        $paydate=$pers['from_date'];
        $compid=$pers['company'];
  
        $Source = $pers['Source'];
        $att_dept = $pers['att_dept'];
        $itcod = $pers['itcod'];
        $spaydate=substr($paydate,0,4).'-'.substr($paydate,5,2).'-01';

  
        $sql="select k.*,md.dept_desc,case when rem=2 then concat(dept_desc,' Department Total')  else desig end desigd
        ,(shift_a+shift_b+shift_c) totshift,(target_a+target_b+target_c) tottarget from ( 
            select tdd.desig_id,tdhands,tdexcess,tdshort,ifnull(tdt.shift_a,0) shift_a,
            ifnull(tdt.shift_b,0) shift_b,
            ifnull(tdt.shift_c,0) shift_c,
            ifnull(target_a,0) target_a,
            ifnull(target_b,0) target_b,
            ifnull(target_c,0) target_c,
            ifnull(excess_hands,0) excess_hands,
            ifnull(short_hands,0) short_hands, 
            desig,ifnull(HOCCU_CODE,'') HOCCU_CODE,ifnull(dept_code,'') dept_code,ifnull(omn.DIRECT_INDIRECT,'') DIRECT_INDIRECT , 1 rem from (
            select desig_id,sum(shift_a+shift_b+shift_c) tdhands, sum(excess_hands) tdexcess,sum(short_hands) tdshort from EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date between '".$spaydate."' and '".$paydate."' and is_active =1 and company_id =".$compid."
            group by desig_id 
            ) tdd left join
            (select desig_id tdtdesig,shift_a,shift_b,shift_c,target_a,target_b,target_c, excess_hands,short_hands from EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date ='".$paydate."' and is_active =1 and company_id =".$compid."
            ) tdt on tdd.desig_id=tdt.tdtdesig
             left join designation d on d.id=tdd.desig_id
             left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id=tdd.desig_id
             left join master_department md on md.mdept_id=d.department and d.company_id=md.company_id
            union all
            select 0 desig_id,ifnull(sum(tdhands),0) tdhands,	ifnull(sum(tdexcess),0) tdexcess,
            ifnull(sum(tdshort),0) tdshort,
            ifnull(sum(shift_a),0) shift_a,
            ifnull(sum(shift_b),0) shift_b,
            ifnull(sum(shift_c),0) shift_c,
            ifnull(sum(target_a),0) target_a,
            ifnull(sum(target_b),0) target_b,
            ifnull(sum(target_c),0) target_c,
            ifnull(sum(excess_hands),0) excess_hands,
            ifnull(sum(short_hands),0) short_hands,
            'TOTAL' desig,'' HOCCU_CODE,ifnull(dept_code,'') dept_code,'' DIRECT_INDIRECT, 2 rem from (
            select tdd.desig_id,tdhands,tdexcess,tdshort,tdt.shift_a,tdt.shift_b,tdt.shift_c,target_a,target_b,target_c, excess_hands,short_hands,
            desig,HOCCU_CODE,dept_code,omn.DIRECT_INDIRECT, 1 rem from (
            select desig_id,sum(shift_a+shift_b+shift_c) tdhands, sum(excess_hands) tdexcess,sum(short_hands) tdshort from EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date between '".$spaydate."' and '".$paydate."' and is_active =1 and company_id =".$compid."
            group by desig_id 
            ) tdd left join
            (select desig_id tdtdesig,shift_a,shift_b,shift_c,target_a,target_b,target_c, excess_hands,short_hands from EMPMILL12.tbl_daily_hand_comp_data hcd
            where tran_date ='".$paydate."' and is_active =1 and company_id =".$compid."
            ) tdt on tdd.desig_id=tdt.tdtdesig
             left join designation d on d.id=tdd.desig_id
             left join EMPMILL12.OCCUPATION_MASTER_NORMS omn on omn.desig_id=tdd.desig_id
             left join master_department md on md.mdept_id=d.department and d.company_id=md.company_id
            ) g group by dept_code  
            ) k
            left join master_department md on md.dept_code=k.dept_code 
            where md.company_id=".$compid."
            ";
      
            $sql=$sql." order by dept_code,rem,HOCCU_CODE 
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
	// da.attendance_type in ('R', 'O', 'C')
	// 	and da.attendance_source in ('A', 'M')
	
	public function getmechinesummdata($pers)
        {
            $from_date  =$pers['from_date'];             
            $companyId=$pers['company'];
            $paydate=$pers['from_date'];
            $compid=$pers['company'];
            $spaydate=substr($paydate,0,4).'-'.substr($paydate,5,2).'-01';
         
       $sql="select mcm.mc_code,mcm.Mechine_type_name,mcm.no_of_installed_mc,tdsmd.shift_a,tdsmd.shift_b,tdsmd.shift_c  from EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
       left join EMPMILL12.mechine_code_master mcm on tdsmd.mc_code_id =mcm.mc_code_id 
       where tdsmd.is_active =1 and tdsmd.tran_date ='".$from_date."' and tdsmd.company_id =".$companyId."   order by mcm.mc_code"; 
       
       $sql="select tdm.*,tom.* from
       (select tdsmd.mc_code_id mccodid,sum(shift_a+shift_b+shift_c) tdmc from  EMPMILL12.tbl_daily_summ_mechine_data tdsmd
       where tdsmd.is_active =1 and tdsmd.tran_date between '".$spaydate."' and '".$paydate."'  and tdsmd.company_id =".$companyId."
       group by tdsmd.mc_code_id
       ) tdm 
       left join (
       select tdsmd.mc_code_id,mcm.mc_code,mcm.Mechine_type_name,mcm.no_of_installed_mc, tdsmd.shift_a,tdsmd.shift_b,tdsmd.shift_c,
       (shift_a+shift_b+shift_c) totalmc  from EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
       left join EMPMILL12.mechine_code_master mcm on tdsmd.mc_code_id =mcm.mc_code_id 
       where tdsmd.is_active =1 and tdsmd.tran_date ='".$paydate."' and tdsmd.company_id =".$companyId."
       ) tom on tdm.mccodid=tom.mc_code_id";


       $sql="select tdm.*,tom.* from
       (select tdsmd.mc_code_id mccodid,mcm.mc_code,mcm.Mechine_type_name,mcm.no_of_installed_mc,sum(shift_a+shift_b+shift_c) tdmc 
       from  EMPMILL12.tbl_daily_summ_mechine_data tdsmd
       left join EMPMILL12.mechine_code_master mcm on tdsmd.mc_code_id =mcm.mc_code_id 
       where tdsmd.is_active =1 and tdsmd.tran_date  between '".$spaydate."' and '".$paydate."'  
       and tdsmd.company_id =".$companyId."
       group by tdsmd.mc_code_id
       ) tdm 
       left join (
       select tdsmd.mc_code_id, tdsmd.shift_a,tdsmd.shift_b,tdsmd.shift_c,
       (shift_a+shift_b+shift_c) totalmc  from EMPMILL12.tbl_daily_summ_mechine_data tdsmd 
       where tdsmd.is_active =1 and tdsmd.tran_date ='".$paydate."' and tdsmd.company_id =".$companyId."
       ) tom on tdm.mccodid=tom.mc_code_id order by tdm.mccodid";


  
//       echo $sql; 
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