<?php defined('BASEPATH') OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct()
    {
        parent::__construct();
		$this->load->library('varaha');
		$this->load->library('columns');
		$this->load->library('form_validation');

		
		
    }

    function page_construct($page, $data = array()) {
		
        $this->data['data'] = $data;		
		
		// $this->varaha->print_arrays($this->session->userdata('companys'));
		// $this->data['companys'] = $this->varaha_model->getCompanyList();
		$this->data['companys'] = $this->session->userdata('companys');
		$i=0;	
		$user_grp_id = $this->varaha->getUserGroupId($this->session->userdata('companyId'));
		$subMenuId= 68;
		$this->data['menus'] = $this->varaha_model->getMainMenuList($this->session->userdata('companyId'),$user_grp_id);
		// $this->varaha->print_arrays($this->data['menus']);
		// $this->varaha->print_arrays($this->session->userdata('companyId'), $this->data['menus'], $user_grp_id);
		if($this->data['menus']){
			$j=0;
			foreach($this->data['menus'] as $menu){
			  if($menu->menu_id!=560){
				  if($j==0){
					$subMenuId=$menu->menu_id;
				  }				
				$j++;
			  }
			}
		}
		if($this->data['menus']){
		$mainmenuId = ($this->session->userdata('menuId') ? $this->session->userdata('menuId') : $data['mainmenuId']);
		
		$this->data['submenu'] = $this->varaha_model->getSubMenuList(($mainmenuId ? $mainmenuId : $subMenuId),$user_grp_id);
		}else{
			$mainmenuId = 0 ;
			$this->data['submenu'] =0;
		}
		// $this->varaha->print_arrays($this->data['submenu']);
		$this->load->view('common/header',$this->data);
		$this->load->view('common/menu');	
		$this->load->view($page);
		$this->load->view('common/footer');
    }
	
	function page_view($page, $data = array()) {
       $this->load->view($page,$this->data);
    }
	
	
	// public function logout(){
	// 	$this->mwelcome->logout();
	// 	redirect(base_url(),'refresh');
	// }ves001--MadhuBhanu@78
	
	
	

}
