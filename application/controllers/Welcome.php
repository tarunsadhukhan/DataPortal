<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends MY_Controller {
	
	public function __construct() {
        parent::__construct();
		$this->load->library('session');

		$this->load->model('varaha_model');
		ini_set("allow_url_fopen", true);
    }

	public function test(){
		echo "Nagesh is working";
	}


	
	public function index()
	{
		// error_reporting(0);

		
		if($this->session->userdata('userid')==''){
			$this->data['headtitle'] = "LogIn";
			$this->load->view('login/signin',$this->data);
		}else{
			$menudata=null;
			$this->data['headtitle'] = "Dashboard";
			$this->data['companys'] = $this->session->userdata('companys');
			if($this->data['companys']){
				$i=0;	
				$companys = $this->session->userdata('companys');
				if($companys){
					foreach($companys as $comp){
						$user_grp_id = $this->varaha->getUserGroupId($comp['compId']);
						$subMenuId= 68;
						$this->data['menus'] = $this->varaha_model->getMainMenuList($comp['compId'],$user_grp_id);	
						// $this->varaha->print_arrays($this->data['menus']);		
						if($this->data['menus']){
							$j=0;
							foreach($this->data['menus'] as $menu){
								if($menu->menu_id!=560){
									if($this->session->userdata('menuId')){
										if($this->session->userdata('menuId') == $menu->menu_id){
											$menudata=$menu;
										}
									}else{
										if($j==0){
											$menudata=$menu;
										}	
									}
												
									$j++;
								}
							}
							break;
						}
					}
				}
								


				// $this->varaha->print_arrays($this->data['menus'],$menudata);
				if($menudata){
					$redirect = ($menudata->menu_state ? $menudata->menu_state : $menudata->menu_path).'/dashboard/'.$menudata->menu_id;
					redirect($redirect);
				}else{
					
					// $this->page_construct('nomenu');
					$this->load->view('nomenu');
				}
			}else{
				
				$this->load->view('nocompanys');
			}
						
		}
	}
	public function login()
	{
		
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password', "Password", 'required');
		
		 if ($this->form_validation->run('Welcome/login') == true) {
			$res = $this->varaha_model->login($this->input->post('username'),$this->input->post('password'));
			$type = isset($_POST['type']) ? $_POST['type']: 0;
			// $this->varaha->print_arrays($res);
			$redirect = base_url();
			
			if($res==1){
				$this->session->set_flashdata('message', "Login Successfully.");
				redirect($redirect);
			}else if($res==2){
				$this->session->set_flashdata('error', "Sorry! Username Or Password incorrect.");
				redirect($redirect);  
			}else if($res==3){
				$this->session->set_flashdata('error', "Sorry! Password incorrect.");
				redirect($redirect);  
			}else if($res==4){
				$this->session->set_flashdata('error', "Sorry! User Not Active. Plz. Contact Admin");
				redirect($redirect);
			}
		 }else{
			$this->session->set_flashdata('error', validation_errors());
			redirect($redirect); 
		 }
		
		
	}

	public function ChangeCompany(){
		$compId = $_GET['compId'];
		$menuId = $_GET['menuId'];
	//	echo 'company'.$compId;
//		print_r ('company'.$compId);
		$this->load->model('Api_model'); 
		$cname=$this->Api_model->getCompanyName($compId);
		$this->session->set_userdata(array('companyId' => $compId,'companyname' => $cname,  'menuId' => $menuId));	
		
//		$this->session->set_userdata(array('companyId' => $compId, 'menuId' => $menuId));	
		return true;
	}

	public function logout(){
		if($this->varaha_model->logout()){			
			redirect(base_url());
		}
	}
	
	public function getGoogleLinkData($companyId,$subMenuId){
		// $input = $this->input->post();	
		
		// if(!$input){
		// 	$json = file_get_contents('php://input');
		// 	$input = json_decode($json);
		// 	$input = (array) $input;
		// }
		
		$res = $this->varaha_model->getEmbedGoogleLinksData($companyId,$subMenuId);
		if($res){
			$output = array(
				"status" => true,
				"messge" => "Google links data Successfully.",
				"result" => $res,
			);
			echo json_encode($output);
		}else{
			$error = array(
				"status" => false,
				"error" => "Getting data Error",
				"error_description"=>"Sorry! error found.",
				"input" => ''
			);
			echo json_encode($error);
		}
	}
	
}
