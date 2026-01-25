<?php
class Api_model extends CI_Model
{

	
	
	public function __construct()
	{		
		$this->load->database();		
	}
	
	public function getConsoleMenus($orgId){

		// $subrow=array();
		$subrow = (object) "";
		$this->db->where('org_id',$orgId);
		$this->db->where('is_active',1);
		$q = $this->db->get('tbl_org_subscription');
		if($q->num_rows()>0){
			$subrow->subscription = $q->row();
			
			$this->db->where('parent_id',0);
			$this->db->where('req_source',0);
			$this->db->where('saleble',1);
			$this->db->where("menu_id !=201");
			$this->db->select('menu_id,menu,menu_icon_name');
			$q=  $this->db->get('menu_master');
			if($q->num_rows()>0){
				foreach($q->result() as $row){
					$row->menu_type = 1;
					$row->selectedMenu = $this->getSelectedMenus($orgId, $row->menu_id, $subrow->subscription->id);
					$row->checked = ($this->getSelectedMenus($orgId, $row->menu_id, $subrow->subscription->id) ? true : false);				
					$data[] = $row;
				}
				$data[] = array('menu_id' => 1, 'menu' => 'Location Tracking','menu_icon_name'=>"location_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,1, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,1, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 2, 'menu' => 'Facial Recognition','menu_icon_name'=>"facial_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,2, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,2, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 3, 'menu' => 'Whatsapp','menu_icon_name'=>"whatsaap_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,3, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,3, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 4, 'menu' => 'Tally','menu_icon_name'=>"tally_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,4, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,4, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 5, 'menu' => 'Biometric Attendance','menu_icon_name'=>"biometric_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,5, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,5, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 6, 'menu' => 'Payment Gateway','menu_icon_name'=>"payment_img",'menu_type' => 2,'selectedMenu'=>$this->getSelectedMenus($orgId,6, $subrow->subscription->id), 'checked'=> ($this->getSelectedMenus($orgId,6, $subrow->subscription->id) ? true : false));
				$data[] = array('menu_id' => 6001, 'menu' => 'Companys','menu_icon_name'=>"",'menu_type' => 3,'selectedMenu'=>$this->getSelectedMenus($orgId,6001, $subrow->subscription->id), 'checked' => "");
				$data[] = array('menu_id' => 6002, 'menu' => 'Users','menu_icon_name'=>"",'menu_type' => 4,'selectedMenu'=>$this->getSelectedMenus($orgId,6002, $subrow->subscription->id), 'checked' => "");
				$subrow->addonsdata = $data;
				$subrow->user_data = $this->getUserData($orgId);
				$subrow->customer_data = $this->getCustomerData($subrow->subscription->invoiceId);
				return $subrow;
			}
		}		
		return false;
	}

	public function getCustomerData($invId){
		$this->db->where('is_active',1);
		$this->db->where('invoice_id',$invId);
		$q = $this->db->get('invoice_hdr');
		if($q->num_rows()>0){
			$this->db->where('id',$q->row()->customer_id);
			$qs = $this->db->get('customer_master');
			if($qs->num_rows()>0){
				return $qs->row();
			}
		}
		return false;
	}
	public function getUserData($orgId){
		$this->db->select('user_id,first_name,last_name,mobile,email, company_id');
		$this->db->where('organization',$orgId);
		$q = $this->db->get('user_details');
		if($q->num_rows()>0){
			$q->row()->companyname = $this->getCompanyName($q->row()->company_id);
			return $q->row();			
		}
		return false;
	}

	public function getCompanyName($compId){
	
		$this->db->where('comp_id',$compId);
		$q = $this->db->get('company_master');
		if($q->num_rows()>0){				
			return $q->row()->company_name;			
		}
		return false;
	}


	public function getSelectedMenus($orgId,$menuId, $subscription_id){
		$this->db->where('orgId', $orgId);
		$this->db->where('addonId', $menuId);
		$this->db->where('subscription_id', $subscription_id);
		$q=  $this->db->get('tbl_org_addons');
		if($q->num_rows()>0){				
			return $q->row();
		}
		return false;
	}

	
	
	public function updateModules($data){

		
		$no_of_users = $data->no_of_users;
		$no_of_companies = $data->no_of_companies;
		$orgId = $data->orgId;

		if($data){
			$this->db->where('id',$data->customerId);
			$qs = $this->db->get('customer_master');
			if($qs->num_rows()>0){
				return $qs->row();
			}else{
				$custData = array(
					'address' => $data->billingAddress,
					'billing_state_code' => $data->billingStateCode,
					'company_id' => $data->companyId,
					'contact_no' => $data->billingContactNo,
					'contact_person' => $data->billingFirstName,
					'created_by' => $data->userId,
					'email_id' => $data->billingEmailId,
					'gst_no' => $data->billingGstNumber,
					'name' => $data->billingFirstName,
					'pincode' =>$data->billingPinCode,
					'shipping_address' => $data->shippingAddress,
					'shipping_state_code' => $data->shippingStateCode,
					'state' => $data->billingState,
					'created_date_time' => date('Y-m-d h:i:s', time()),
					'state2' =>$data->shippingState,
					'pincode2' =>$data->shippingPinCode,
					'gst_no2' =>$data->shippingGstNumber,
				);
				$this->db->insert('customer_master', $custData);
			}
		}
		
		
		

		if($data->modules){
			$subsription = array(
				'org_id' => $orgId,
				'is_active' => 2,
				'created_by' => $data->updatedprice->created_by,
				'created_date' => date('Y-m-d h:i:s', time()),
				'type_of_puchase' => 2,
				'payment_id' => 0,
				'subscription_months' => $data->updatedprice->subscription_months,
				'module_price' => $data->updatedprice->module_price,
				'addons_price' => $data->updatedprice->addons_price,
				'total_price' =>$data->updatedprice->total_price,
				'status' => 1,
				'companys' => $data->updatedprice->companys,
				'users' => $data->updatedprice->users,
			);

			$this->db->insert('tbl_org_subscription', $subsription);
			$subscription_id = $this->db->insert_id();
			// $subscription_id = 2;
		}

		if($data->modules){
			foreach($data->modules as $module){
				if($module->checked){
					$org_adds[] = array(						
						'orgId' => $orgId,
						'addonId' => $module->menu_id,
						'addonname' => $module->menu,
						'isActive' => 2,
						'createdBy' => $data->updatedprice->created_by,
						'transactions_count' => 0,
						'createdDate' => date('Y-m-d h:i:s', time()),
						'trail_months' => $data->updatedprice->subscription_months,
						'module_price' => $data->updatedprice->module_price,
						'addons_price' => $data->updatedprice->addons_price,
						'total_price' => $data->updatedprice->total_price,
						'type_of_purchase' => 2,
						'trail_days' => 0,
						'subscription_id' => $subscription_id,
					);
				}
			}

		}
		if($data->addonlist){
			foreach($data->addonlist as $addons){
				if($addons->checked){
					$transactions_count = 0;
					if($addons->menu == 'Location Tracking'){
						$transactions_count = 50000;
					}
					if($addons->menu == 'Facial Recognitio'){
						$transactions_count = 10000;
					}
					if($addons->menu == 'Whatsap'){
						$transactions_count = 10000;
					}
					$org_adds[] = array(						
						'orgId' => $orgId,
						'addonId' => $addons->menu_id,
						'addonname' => $addons->menu,
						'isActive' => 2,
						'createdBy' => $data->updatedprice->created_by,
						'transactions_count' => $transactions_count,
						'createdDate' => date('Y-m-d h:i:s', time()),
						'trail_months' => $data->updatedprice->subscription_months,
						'module_price' => $data->updatedprice->module_price,
						'addons_price' => $data->updatedprice->addons_price,
						'total_price' => $data->updatedprice->total_price,
						'type_of_purchase' => 2,
						'trail_days' => 0,
						'subscription_id' => $subscription_id,
					);
				}
			}

		}

		if($no_of_users){
			$org_adds[] = array(						
				'orgId' => $orgId,
				'addonId' => 6002,
				'addonname' => 'Users',
				'isActive' => 2,
				'createdBy' => $data->updatedprice->created_by,
				'transactions_count' => $no_of_users,
				'createdDate' => date('Y-m-d h:i:s', time()),
				'trail_months' => $data->updatedprice->subscription_months,
				'module_price' => $data->updatedprice->module_price,
				'addons_price' => $data->updatedprice->addons_price,
				'total_price' => $data->updatedprice->total_price,
				'type_of_purchase' => 2,
				'trail_days' => 0,
				'subscription_id' => $subscription_id,
			);
		}
		if($no_of_companies){
			$org_adds[] = array(						
				'orgId' => $orgId,
				'addonId' => 6001,
				'addonname' => 'Companys',
				'isActive' => 2,
				'createdBy' => $data->updatedprice->created_by,
				'transactions_count' => $no_of_companies,
				'createdDate' => date('Y-m-d h:i:s', time()),
				'trail_months' => $data->updatedprice->subscription_months,
				'module_price' => $data->updatedprice->module_price,
				'addons_price' => $data->updatedprice->addons_price,
				'total_price' => $data->updatedprice->total_price,
				'type_of_purchase' => 2,
				'trail_days' => 0,
				'subscription_id' => $subscription_id,
			);
		}	
		
		if($org_adds){
			foreach($org_adds as $addons){
				$this->db->insert('tbl_org_addons',$addons);
			}
		}


		return $subscription_id;
	}


	public function createInvoice($data){
		$this->load->library('varaha');
		$this->db->where('org_id', $data->orgId);
		$this->db->where('is_active', 1);
		if($this->db->update('tbl_org_subscription', array('is_active' => 0))){
			$this->db->where('orgId',$data->orgId);
			$this->db->where('isActive',1);
			$this->db->update('tbl_org_addons', array('isActive'=> 0));

			$this->db->where('id',$data->razorpay_order_id);
			$this->db->where('is_active', 2);
			if($this->db->update('tbl_org_subscription', array('is_active'=> 1, 'payment_id'=> $data->transactionid))){
				$this->db->where('subscription_id',$data->razorpay_order_id);
				$this->db->where('isActive',2);			
				$this->db->update('tbl_org_addons', array('isActive'=> 1));
				/* INVOICE INSERTING */
				$comprow = array();
				$yerrow = array();
				$customerrow = array();
				$this->db->where('org_id',$data->orgId);
				$comp = $this->db->get('company_master'); // Getting Compnay Details
				
				if($comp->num_rows()>0){
					$comprow = $comp->row();
					
					$this->db->where('company_id',$comprow->comp_id);
					$yerres = $this->db->get('academic_years'); // Getting Academic Year
					if($yerres->num_rows()>0){
						$yerrow = $yerres->row();
					}
					$this->db->where('company_id',$comprow->comp_id);
					$customer = $this->db->get('customer_master');
					if($customer->num_rows()>0){
						$customerrow = $customer->row();
					}
				}
				// $this->varaha->print_arrays($comprow->comp_id,$customerrow, $yerrow);
				if($customerrow){
					$customerId = $customerrow->id;
					$companyname = $customerrow->name;
					$address = $customerrow->address;
					$address1 = $customerrow->shipping_address;
					$TotalFinalCost = $data->amount;
					$usr_id = $data->userId;
					$yer = ($yerrow ? $yerrow->year : date('Y',time()));
					$stateCode = $customerrow->billing_state_code;
					$stateCode1 = $customerrow->shipping_state_code;
					$this->db->where('state_id',$customerrow->state);
					$state = $this->db->get('state_master');
					if($state->num_rows()>0){
						$stateName = $state->row()->state_name;
					}else{
						$stateName = "";
					}
					$this->db->where('state_id',$customerrow->state2);
					$state1 = $this->db->get('state_master');
					if($state1->num_rows()>0){
						$stateName1 = $state1->row()->state_name;
					}else{
						$stateName1 = "";
					}					
				}else{

				}


				$this->db->where('company_id', ($comprow ? $comprow->comp_id : 0));
				$this->db->where('is_active', 1);
				$this->db->where('ac_year',($yerrow ? $yerrow->year : date('Y',time())));
				$this->db->select('Max(invoice_unique_no) as unique_no');
				$unique = $this->db->get('invoice_hdr');
				if($unique->num_rows()>0){
					$uniqueNo = $unique->row()->unique_no + 1;
				}
				$usr_id = $data->userId;
				$short_code = ($comprow ? $comprow->company_code : "INV");
				$invoice_no_string = $short_code.'/'.date('M', strtotime(date('m',time()) . '01')).'/'.$uniqueNo.'/'.($yerrow ? $yerrow->year : date('Y',time()));

                  $invHdr = array(
                    "invoice_type"=> "I",    //required
                    "invoice_unique_no" => $uniqueNo,
                    "invoice_date" => date('Y-m-d',time()), 
                    "invoice_no_string" => $invoice_no_string,                   
                    "customer_id"=> $customerId,//required
                    "customer_name"=> $companyname,//required
                    "billing_address"=> $address,
                    "shipping_address"=> $address1,
                    "invoice_amount"=> $TotalFinalCost,
                    "due_amount"=> 0,
                    "tax_amount"=> "0.00",
                    "grand_total"=> $TotalFinalCost,//required
                    "intra_inter_state"=> 1,
                    "created_by"=> $usr_id,
                    "ac_year"=> $yer,//required
                    "billing_state_code"=> $stateCode,
                    "shipping_state_code"=> $stateCode1,
                    "type_of_sale"=> "Sale Of Material",//required
                    "payable_tax"=> "N",
                    "tds_payable"=> "N",
                    "billing_state_name"=> $stateName,
                    "shipping_state_name"=> $stateName1,
                    "round_off"=> 0,
                    "is_active"=> 1,
					"company_id" => $comprow->comp_id
                );
				

				if($this->db->insert('invoice_hdr',$invHdr)){
					$invoiceId = $this->db->insert_id();
					$this->db->where('id',$data->razorpay_order_id);
					$this->db->update('tbl_org_subscription', array('invoiceId'=> $invoiceId));
					$inv_lineItems = array(
						"item_id" => "(1001)",//required
						"item_name"=> "Wastage",//required
						"item_group"=> "999",//required
						"item_description"=> "CLOUD",//required
						"quantity"=> "1",
						"rate"=> $TotalFinalCost,//required
						"tax_amount"=> $TotalFinalCost,                  
						"total_amount"=> $TotalFinalCost,//required
						"company_id"=> ($comprow ? $comprow->comp_id : 0),
						"is_active"=> 1,
						"invoice_id" => $invoiceId
					  );
					$this->db->insert('invoice_line_items',$inv_lineItems);
				}

                // $inv_fields = implode(',', array_keys($invHdr));
                // $inv_values = implode("','", $invHdr);
                // $inv_query ="INSERT INTO invoice_hdr ($inv_fields) VALUES ('$inv_values')";
                // $conn->query($inv_query);

                // $invoiceId = $conn->insert_id;
                // if($invoiceId){
                //   $inv_lineItems = array(
                //     "item_id" => "(1001)",//required
                //     "item_name"=> "Wastage",//required
                //     "item_group"=> "999",//required
                //     "item_description"=> "CLOUD",//required
                //     "quantity"=> "1",
                //     "rate"=> $TotalFinalCost,//required
                //     "tax_amount"=> $TotalFinalCost,                  
                //     "total_amount"=> $TotalFinalCost,//required
                //     "company_id"=> $com_id,
                //     "is_active"=> 1,
                //     "invoice_id" => $invoiceId
                //   );
                //   $inv_line_fields = implode(',', array_keys($inv_lineItems));
                //   $inv_line_values = implode("','", $inv_lineItems);
                //   $inv_line_query ="INSERT INTO invoice_line_items ($inv_line_fields) VALUES ('$inv_line_values')";
                //   $conn->query($inv_line_query);
                // }


				
			}
			return $data->razorpay_order_id;
		}
		return false;
		
	}

	public function getInvoiceListData($orgId){

		$this->db->where('is_active', 1);
		$this->db->where('org_id', $orgId);
		$q = $this->db->get('tbl_org_subscription');
		if($q->num_rows()>0){
			$row = $q->row();
			$row->addons = $this->getAddonsforSubscription($row->id);
			$row->invoiceData = $this->getInvoiceData($row->invoiceId);
			$row->due_amount = $this->getInvDueAmt($row->invoiceData);
			$row->previousplans = $this->getPreviousPlans($orgId);
			return $row;
		}

		return false;
		

	}

	public function getPreviousPlans($orgId){
		$this->db->where('is_active', 0);
		$this->db->where('org_id', $orgId);
		$q = $this->db->get('tbl_org_subscription');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$row->created_date = date('d-m-Y',strtotime($row->created_date));
				$addons = $this->getAddonsforSubscription($row->id);
				$addonsData = "";
				$modulesData = "";
				if($addons){
					$i=0;
					$j=0;					
					foreach($addons as $addon){
						if($addon->addonId==1 || $addon->addonId==2 || $addon->addonId==3 || $addon->addonId==4 || $addon->addonId==5 || $addon->addonId==6){
							if($i==0){
								$addonsData = $addon->addonname;
							}else{
								$addonsData = $addonsData.','.$addon->addonname;
							}
							$i++;
						}else if($addon->addonId==6001  || $addon->addonId==6002){

						}else{
							if($j==0){
								$modulesData = $addon->addonname;
							}else{
								$modulesData = $modulesData.','.$addon->addonname;
							}
							
						}
					}
				}
				$row->modulesData = $modulesData;
				$row->addonsData = $addonsData;
				$data[] = $row;
			}
			return $data;
		}
		return false;
	}

	public function getInvDueAmt($invdata){
		$due_data = 0;
		if($invdata){
			foreach($invdata as $inv){
				$due_data = $inv->due_amount;
			}
		}
		return $due_data;
	}

	public function getAddonsforSubscription($subscription_id){		
		$this->db->where('subscription_id', $subscription_id);
		$q=  $this->db->get('tbl_org_addons');
		if($q->num_rows()>0){	
			foreach($q->result() as $row){
				$data[] = $row;
			}	
			return $data;	
		}
		return false;
	}
	public function getInvoiceData($invId){		
		$this->db->where('is_active', 1);
		$this->db->where('invoice_id', $invId);
		$q=  $this->db->get('invoice_hdr');
		if($q->num_rows()>0){	
			foreach($q->result() as $row){
				$row->invLineItems = $this->getInvoiceLineItems($row->invoice_id);
				$data[] = $row;
			}	
			return $data;	
		}
		return false;
	}

	public function getInvoiceLineItems($invoice_id){		
		$this->db->where('invoice_id', $invoice_id);
		$this->db->where('is_active',1);
		$q=  $this->db->get('invoice_line_items');
		if($q->num_rows()>0){	
			foreach($q->result() as $row){
				$data[] = $row;
			}	
			return $data;	
		}
		return false;
	}

	public function getConsoleDashboardData($orgId){
		$dash=array();
		$modules = 0;
		$addonscount = 0;
		$purchased_addons = array();
		$this->db->where('is_active', 1);
		$this->db->where('org_id', $orgId);
		$q = $this->db->get('tbl_org_subscription');
		if($q->num_rows()>0){
			$row = $q->row();
			$row->addons = $this->getAddonsforSubscription($row->id);
			if($row->addons){
				foreach($row->addons as $addons){
					if($addons->addonId==6001){

					}else if($addons->addonId==6002){

					}else if(($addons->addonId==1) || ($addons->addonId==2) || ($addons->addonId==3) || ($addons->addonId==4) || ($addons->addonId==5) || ($addons->addonId==6)){
						$addonscount = $addonscount + 1;
						if($addons->addonId==1){
							$icon = 'LocationIcon';
						}else if($addons->addonId==2){
							$icon = 'FacialIcon';
						}else if($addons->addonId==3){
							$icon = 'WhatsAppIcon';
						}else if($addons->addonId==4){
							$icon = 'TallyIcon';
						}else if($addons->addonId==5){
							$icon = 'BiometricIcon';
						}else if($addons->addonId==6){
							$icon = 'PaymentIcon';
						}
						$purchased_addons[] = array('icon'=>$icon,'name'=> $addons->addonname);
					}else{
						$modules = $modules + 1;
						$modules_names[] = array('icon'=>'', 'name' =>  $addons->addonname);
					}
				}
			}
			// $this->varaha->print_arrays($this->getInvoiceListData($orgId));//Vowerp@1
			$invdata = $this->getInvoiceListData($orgId);
			$invno=0;
			$invdate='';
			if($invdata){
				$addonslist = $invdata->addons;
				$invoicelist = $invdata->invoiceData;
				if($invoicelist){
					foreach($invoicelist as $invoice){
						$invno=$invoice->invoice_no_string;
						$invdate=date("jS M'y",strtotime($invoice->invoice_date));
					}
				}
			}

			if($row->subscription_months == 1){
				$plan = "Monthly";
			}else if($row->subscription_months == 3){
				$plan = "Quarterly";
			}else if($row->subscription_months == 6){
				$plan = "Semi-Annually";
			}else{
				$plan = "Yearly";
			}

			$usedCompanys = $this->getUsedCompanys($orgId);
			$usedUsers  = $this->getUsedUsers($orgId);
			$dash=array(
				'companys' => $row->companys,
				'usedcompanys' => $usedCompanys,
				'users' => $row->users,
				'usedusers' => $usedUsers,
				'modules' => $modules,
				'addons' => $addonscount,
				'invno' => $invno,
				'invdate' => $invdate,
				'plan' => $plan,
				'modules_amt' => $invdata->module_price,
				'addons_amt' => $invdata->addons_price,
				'total_amt' => $invdata->total_price,
				'Payment_Status' => ($invdata->due_amount > 0 ? "Due" : "Paid"),
				'purchased_addons' => $purchased_addons,
				'module_addons' => $modules_names,
				'next_due_on' => date("jS M'y", strtotime( "+1 month", strtotime($invoice->invoice_date) ))
			);
		

		}

		return $dash;
	}

	public function getUsedCompanys($orgId){
		$com = 0;
		$this->db->where('org_id',$orgId);
		$q= $this->db->get('company_master');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$com = $com + 1;
			}	
			return $com;	
		}
		return $com;
	}

	public function getUsedUsers($orgId){
		$use = 0;
		$this->db->where('organization',$orgId);
		$q= $this->db->get('user_details');
		if($q->num_rows()>0){
			foreach($q->result() as $row){
				$use = $use + 1;
			}	
			return $use;	
		}
		return $use;
	}

	public function createapprovermaster($data){

		$this->db->where('company_id',$data->company_id);
		$this->db->where('config_parameter','tasksList'); //select * from configuration_table where company_id = 1 AND config_parameter = 'tasksList'
		$q=$this->db->get('configuration_table');
		if($q->num_rows()>0){
			$configItems = $q->row()->config_value;
			$dataconfig = json_decode($configItems, TRUE);
			if($dataconfig){
				foreach($dataconfig as $config){
					$mdata=array(
					'company_id' => $data->company_id,
					'created_by' => $data->created_by,
					'task_desc' => $data->task_des,
					'branch_id' => $data->branch_id,
					'task_id' => $config['taskId']
					);
					$this->db->insert('approver_master',$mdata);
				}
			}
		}

	}

	public function createconfiguration($data){
		if($data){
			foreach($data->config_value as $config){
				$dataconfig[] = $config;
			  
			  }
			  $config_string = json_encode($dataconfig);
			$mdata = array(
				'company_id'=> $data->company_id,
				'config_parameter'=> "$data->config_parameter",
				'config_value'=> $config_string,
				'is_active'=> $data->is_active,
			);
			// $this->varaha->print_arrays($mdata);
			if($this->db->insert('configuration_table',$mdata)){
				return true;
			}
		}
		return false;
	}
}
?>