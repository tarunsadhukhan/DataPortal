<?php
class Varaha_model extends CI_Model
{
	
	public function __construct()
	{		
		$this->load->database();		
	}
	

	public function getMainMenuList($companyId,$user_grp_id){	

		// return $companyId.'-'.$user_grp_id;
		// $this->varaha->print_arrays($user_grp_id, "---", $this->session->userdata('userid'));
		if($user_grp_id!=0){
			$this->db->where('is_enable', 1);
			$this->db->where('user_grp_id', $user_grp_id);
			$q = $this->db->get('user_grp_menu_master');
			// $this->varaha->print_arrays($this->db->last_query());
			if($q->num_rows() > 0){
				foreach($q->result() as $row){
					$menuIds[] = $row->menu_id;
				}
				
				// $this->db->where('company_id', $companyId);
				$this->db->where('company_id', $companyId);
				$this->db->where('is_active',1);
				$this->db->where_in('menu_id',$menuIds);
				$qv = $this->db->get('tbl_menu_company_mapping');
				// $this->varaha->print_arrays($this->db->last_query());
				if($qv->num_rows() > 0){
					foreach($qv->result() as $row){
						$menuIdsv[] = $row->menu_id;
					}
					
					$this->db->where('parent_id',68);
					$this->db->where_in('menu_id',$menuIdsv);
					$this->db->order_by('orderby','ASC');
					$q = $this->db->get('menu_master');
					// $this->varaha->print_arrays($this->db->last_query());
					if($q->num_rows() > 0){
						foreach($q->result() as $row){
							$data[] = $row;
						}
						return $data;
					}	
				}								
			}
			return false;
		}else{	
			$this->db->where('parent_id',68);		
			$this->db->order_by('orderby','ASC');	
			$q = $this->db->get('menu_master');
			if($q->num_rows() > 0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}					
		}	
		
		return false;
	}

	public function getCompanyList(){
		$this->db->select('org_id, comp_id,company_code, company_name');
		$q = $this->db->get('company_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function getSubMenuRow($id){
		$this->db->where_in('menu_id',$id);
		$q = $this->db->get('menu_master');
		if($q->num_rows() > 0){
			return $q->row();
		}else{
			return false;
		}
	}
	public function getSubMenuConditionsRow($id){
		$this->db->where_in('menu_id',$id);
		$q = $this->db->get('menu_searching_cols');
		if($q->num_rows() > 0){
			return $q->row();
		}else{
			return false;
		}
	}

	public function getMenuBasedQuery($menu_id, $from_date, $to_date, $companyId){
		$queryRow = $this->getSubMenuRow($menu_id);
		if($queryRow){
			$query = $queryRow->query;
			if($query){
				$conditionsRow = $this->getSubMenuConditionsRow($menu_id);
				if($conditionsRow){
					if($conditionsRow->col1_type!=''){
						if($conditionsRow->col1_type=='date'){
							$query = $query . " WHERE ". $conditionsRow->col1 . " >= '".$from_date."'";
						}
					}
					if($conditionsRow->col2_type!=''){
						if($conditionsRow->col2_type=='date'){
							$query = $query . " and ". $conditionsRow->col2 . " <= '".$to_date."'"; 
						}
					}
					if($conditionsRow->col3_type!=''){
						if($conditionsRow->col3_type=='id'){
							$query = $query . " and ". $conditionsRow->col3 . " = ".$companyId; 
						}
					}					
					if($conditionsRow->col4_type!=''){
						
					}
					if($conditionsRow->col3_type!=''){
						
					}
					if($conditionsRow->group_by!=''){
						if($conditionsRow->group_by){
							$query = $query . " GROUP BY ". $conditionsRow->group_by; 
						}
					}
					if($conditionsRow->order_by!=''){
						if($conditionsRow->order_by_1){
							$query = $query . " ORDER BY ". $conditionsRow->order_by; 
						}
					}
					if($conditionsRow->order_by_1!=''){
						if($conditionsRow->order_by_1){
							$query = $query . " ORDER BY ". $conditionsRow->order_by_1; 
						}
					}
					if($conditionsRow->order_by_2!=''){
						if($conditionsRow->order_by_2){
							$query = $query . " ORDER BY ". $conditionsRow->order_by_2; 
						}
					}
					if($conditionsRow->order_by_3!=''){
						if($conditionsRow->order_by_3){
							$query = $query . " ORDER BY ". $conditionsRow->order_by_3; 
						}
					}
					return $query;
				}

			}
			
			

		}
		return false;

    }
	
	public function getSubMenuList($id=null, $user_grp_id){	


		if($user_grp_id!=0){
			$this->db->where('is_enable', 1);
			$this->db->where('user_grp_id', $user_grp_id);
			$q = $this->db->get('user_grp_menu_master');
			if($q->num_rows() > 0){
				foreach($q->result() as $row){
					$menuIds[] = $row->menu_id;
				}
				if($id){
					$this->db->where('parent_id',$id);
				}	
				$this->db->where('display_menu',1);
				$this->db->where_in('menu_id',$menuIds);
				$q = $this->db->get('menu_master');
				if($q->num_rows() > 0){
					foreach($q->result() as $row){
						$data[] = $row;
					}
					return $data;
				}					
			}
			return false;

		}else{

			if($id){
				$this->db->where('parent_id',$id);
			}	
			$this->db->where('display_menu',1);
			$q = $this->db->get('menu_master');
			if($q->num_rows() > 0){
				foreach($q->result() as $row){
					$data[] = $row;
				}
				return $data;
			}

		}
		return false;

	}

	public function getMenuData($id=null){	
		
		$this->db->where('menu_id',$id);			
		$q = $this->db->get('menu_master');
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;

	}

	public function login($username,$password){
			$databasecr = $this->varaha->getTenentId();

			// $this->varaha->print_arrays($databasecr['tenentId']);
		
            $sms_url = $databasecr['serverIp']."/security-api/oauth/token";
			// $parameters = array(
			// 	'grant_type' => 'password',
			// 	'username' =>  $username,
			// 	'password' =>  $password,
			// );

			$data = "grant_type=password&username=$username&password=$password";
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_POST, false);
            curl_setopt($ch, CURLOPT_URL, $sms_url);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'X-TenantId: '.$databasecr['tenentId'],
				'Authorization: Basic dHV0b3JpYWxzcG9pbnQ6bXktc2VjcmV0LWtleQ=='
			));
            $output = curl_exec($ch);
            curl_close($ch);
			// $this->varaha->print_arrays( $sms_url,$data,$databasecr['tenentId'],$output);
            if ($output){
				
				$mdata = json_decode($output, true);
				
				if($mdata['access_token']){
					$sms_url = $databasecr['serverIp']."/security-api/api/easybusiness/user/authenticateUserLogin";
					$parameters = array(
						'reqSource' => 0,
						'userName' =>  $username,
						'password' =>  $password,
					);
					$headers = [
						"Content-Type: application/json",
						"X-Content-Type-Options:nosniff",
						"Accept:application/json",
						"Cache-Control:no-cache",
						'X-TenantId: '.$databasecr['tenentId'],
						'Authorization: '.$mdata['token_type'].$mdata['access_token'],
						'CompanyID: 0',
					];
					$chs = curl_init();
					curl_setopt($chs, CURLOPT_POST, true);
					curl_setopt($chs, CURLOPT_URL, $sms_url);
					curl_setopt($chs,CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($chs, CURLOPT_POSTFIELDS, json_encode($parameters));
					// curl_setopt($chs, CURLOPT_POSTFIELDS, $mydata);
					curl_setopt($chs, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($chs, CURLOPT_HTTPHEADER, $headers);
					$output1 = curl_exec($chs);
					curl_close($chs);
					// $this->varaha->print_arrays($output1);
					if ($output1){
						
						$compdata= array();
						$res = json_decode($output1, true);
						$companyId = 0 ;
						if($this->data['companys']){	
							foreach ($this->data['companys'] as $comp) {
								if($i==0){
									$companyId = $comp['compId'];					
								}
								$i++;
							}
						}	
						
						if($res){
							if($res['companyDetails']){
								$mc=0;
								foreach($res['companyDetails'] as $company){
									if($mc==0){
										$companyId = $company['value'];	
									}

									$mdt = array(
										'compId' => $company['value'],
										'name' => $company['name'],
										'label' => $company['label'],
										'label' => $company['label'],
										'userGroupId' => $company['userGroupId'],
										'companyLogo' => $company['companyLogo'],
									);
									$mc++;
									$compdata[] = $mdt;
								}
							}
						}
						$ssdata = array(
							'loginStatus' => $res['loginStatus'],
							'userid' => $res['userId'],
							'userName' => $res['userName'],
							'imageURL' => $res['imageURL'],
							'email' => $res['email'],
							'orgId' => $res['orgId'],
							'ebId' => $res['ebId'],
							'ebNo' => $res['ebNo'],
							'companyId' => $companyId,
							'mobileNumber' => $res['mobileNumber'],
							'Authorization' => $mdata['token_type'].$mdata['access_token'],
							'companys' => $compdata,
						);
						// $this->varaha->print_arrays($ssdata);
						$this->session->set_userdata($ssdata);
						return 1;

					}else{						
						return 2;
					}
				}else{					
					return 2;
				}				
			}else{				
				return 2;
			}
                
			//snehadeep
			//sneha9948?


		
		
	}

	public function logout(){
		
		
		$data = array('loginStatus' => "",
		'userId' => "",
		'userName' => "",
		'imageURL' => "",
		'email' => "",
		'orgId' => "",
		'ebId' => "",
		'ebNo' => "",
		'mobileNumber' => "",
		'companys' => "", 'menuId'=>null);
		$this->session->unset_userdata($data);
		$this->session->sess_destroy();	
		session_start();
		session_destroy();
		return true;
	}

	public function getAllGodownsNos(){
		
		$q = $this->db->get('warehouse_details');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function getAllStatus(){
		
		$q = $this->db->get('status_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllDepartments($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('department_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
		//	echo 'this is dept ' . $data;
			return $data;
		}
		return false;
	}

	public function getAllEffMaster(){
		$this->db->order_by('eff_mast_name','ASC');
		$q = $this->db->get('tbl_eff_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function validateEjmWageQCode($deptId, $wageQCode){
		$this->db->where('dept_id', $deptId);
		$this->db->where('wage_q_code', $wageQCode);
		$q = $this->db->get('ejm_wage_qcode_link');
		return $q->num_rows() > 0;
	}

	public function getFneTargetEntry($deptId, $targetType, $effCodeId, $qualCode, $dateFrom, $dateTo){
		$this->db->where('dept_id', $deptId);
		$this->db->where('target_type', $targetType);
		$this->db->where('date_from', $dateFrom);
		$this->db->where('date_to', $dateTo);
		if ($targetType === 'E') {
			$this->db->where('eff_mast_code_id', $effCodeId);
		} else {
			$this->db->where('qual_code', $qualCode);
		}
		$q = $this->db->get('tbl_all_trn_eff');
		return $q->num_rows() > 0 ? $q->row() : false;
	}

	public function saveFneTargetEntry($data, $recordId = null){
		if ($recordId) {
			$this->db->where('all_trn_eff_id', $recordId);
			return $this->db->update('tbl_all_trn_eff', $data);
		}

		return $this->db->insert('tbl_all_trn_eff', $data);
	}

	public function cloneLastFortnightTargets($newDateFrom, $newDateTo){
		// Calculate last fortnight: 15 days before the new date_from
		$prevDateTo = date('Y-m-d', strtotime($newDateFrom . ' -1 day'));
		$prevDateFrom = date('Y-m-d', strtotime($prevDateTo . ' -14 days'));

		// Fetch all records from the last fortnight
		$this->db->where('date_from', $prevDateFrom);
		$this->db->where('date_to', $prevDateTo);
		$q = $this->db->get('tbl_all_trn_eff');

		if ($q->num_rows() == 0) {
			return array('success' => false, 'message' => 'No data found for last fortnight (' . $prevDateFrom . ' to ' . $prevDateTo . ')');
		}

		$clonedCount = 0;
		$skippedCount = 0;

		foreach ($q->result() as $row) {
			// Check if record already exists for new date range
			$this->db->where('dept_id', $row->dept_id);
			$this->db->where('target_type', $row->target_type);
			$this->db->where('date_from', $newDateFrom);
			$this->db->where('date_to', $newDateTo);
			if (!empty($row->eff_mast_code_id)) {
				$this->db->where('eff_mast_code_id', $row->eff_mast_code_id);
			}
			if (!empty($row->qual_code)) {
				$this->db->where('qual_code', $row->qual_code);
			}
			$exists = $this->db->get('tbl_all_trn_eff');

			if ($exists->num_rows() > 0) {
				$skippedCount++;
				continue;
			}

			// Insert cloned record with new dates
			$newData = array(
				'dept_id' => $row->dept_id,
				'target_type' => $row->target_type,
				'eff_mast_code_id' => $row->eff_mast_code_id,
				'qual_code' => $row->qual_code,
				'target_eff' => $row->target_eff,
				'date_from' => $newDateFrom,
				'date_to' => $newDateTo
			);
			$this->db->insert('tbl_all_trn_eff', $newData);
			$clonedCount++;
		}

		$msg = $clonedCount . ' record(s) cloned successfully from ' . $prevDateFrom . ' to ' . $prevDateTo;
		if ($skippedCount > 0) {
			$msg .= '. ' . $skippedCount . ' record(s) skipped (already exist).';
		}

		return array('success' => true, 'message' => $msg, 'cloned' => $clonedCount, 'skipped' => $skippedCount);
	}
	public function getAllMasterDepartments($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('master_department');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}


	public function getAllMccodes($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('EMPMILL12.mechine_code_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
	 		return $data;
		}
		return false;
	}


	public function getAlleffM($companyId){
	//	$this->db->where('company_id',$companyId);
		$this->db->select("*, CONCAT(eff_code, '-', eff_mast_name) as eff_label");
		$q = $this->db->get('EMPMILL12.tbl_eff_master');
	//	$this->varaha->print_arrays($this->db->last_query());
	//log_message('error', $this->db->last_query());

		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
	 		return $data;
		}
		return false;
	}


	/*/*
	public function getAllMccodes($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('EMPMILL12.MC_CODE_MASTER');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
*/	

	public function getAllAgents($companyId){
		$this->db->where('company_id',$companyId);
		$this->db->where('supp_type','O');
		$q = $this->db->get('suppliermaster');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function getJuteQuality($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('jute_quality_price_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}


	public function getAllCategorys($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('category_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllBranchs($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('branch_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllComponets($companyId){
		// $this->db->where('company_id',$companyId);
		$q = $this->db->get('tbl_pay_components');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	public function getAllPayschemes($companyId){
		$this->db->where('BUSINESSUNIT_ID',$companyId);
		$q = $this->db->get('tbl_pay_scheme');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	public function getAllDesignations($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('designation');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}


	public function getAllSpells($companyId){
		$this->db->where('company_id',$companyId);
		$q = $this->db->get('spell_master');
		if($q->num_rows() > 0){
			foreach($q->result() as $row){
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}
	
	

    public function getEmbedGoogleLinks($companyId, $submenuId){
        $this->db->where('is_active',1);
        $this->db->where('company_id',$companyId);
        $this->db->where('menu_id',$submenuId);
		$q = $this->db->get('tbl_embedded_google_links');
		if($q->num_rows() > 0){
			return $q->row()->google_link;
		}
		return false;
    }

	public function getEmbedGoogleLinksData($companyId,$subMenuId){
        $this->db->where('is_active',1);
        $this->db->where('company_id',$companyId);
        $this->db->where('menu_id',$submenuId);
		$q = $this->db->get('tbl_embedded_google_links');
		// return $this->db->last_query();
		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
    }
	// public function getUpdateTest(){
	// 	$data="";
	// 	$sql = "select scm_po_hdr.hdr_id, scm_po_hdr.po_num, jute_po_hdr_test.old_po_num, jute_po_hdr_test.new_po_num  from scm_po_hdr, jute_po_hdr_test WHERE scm_po_hdr.po_num = jute_po_hdr_test.old_po_num ;";
	// 	$q = $this->db->query($sql);
	// 	if($q->num_rows()>0){
	// 		foreach($q->result() as $row){
	// 			$data .= $this->updatemyTest($row);
	// 		}
	// 		return $data;
	// 	}
	// 	return false;
	
	// }

	// public function updatemyTest($row){
	// 	$this->db->where('po_num',$row->po_num);
	// 	// $this->db->update('scm_po_hdr',array('po_num'=> $row->new_po_num));
	// 	$sql = "UPDATE `scm_po_hdr` SET `po_num` = '".$row->new_po_num."' WHERE `po_num` = '".$row->po_num."';<br>";
	// 	return $sql;
	// }
	
}
?>