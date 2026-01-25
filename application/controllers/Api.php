<?php
defined('BASEPATH') or exit('No direct script access allowed');

// require APPPATH . 'libraries/REST_Controller.php';

class Api extends CI_Controller
{

	
	public function __construct()
	{
		parent::__construct();
		$this->load->library('session');
		$this->load->library('varaha');
		$this->load->model('api_model');
		// $this->load->library('curl');
		header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers", "X-Requested-With, content-type");
        header("Access-Control-Allow-Credentials", "true");
        // header("Access-Control-Max-Age", "1800");
        // header("Access-Control-Allow-Headers", "content-type");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, X-Requested-With");
        header("Access-Control-Allow-Methods", "PUT, POST, GET, DELETE, PATCH, OPTIONS");

	}

	public function index(){
		echo "No direct script access allowed.";
	}

	public function test(){
		echo "Nagesh";
	}


	public function getAllModules($orgId){

		$menus = $this->api_model->getConsoleMenus($orgId);
		if($menus){
			$output = array(
				"status" => true,
				"messge" => "Menus successfully.",
				"result" => $menus,
			);
			echo json_encode($output);
		}else{
			$error = array(
				"status" => false,
				"error" => "OTP Error",
				"error_description"=>"Sorry! Incorrect Credentials.",
			);
			echo json_encode($error);
		}
		
	}


	function updateModules(){
		$json = file_get_contents('php://input');
			$input = json_decode($json);
			$input = (array) $input;
			$input = (object) $input;


			$res = $this->api_model->updateModules($input);

		$output = array(
			"status" => true,
			"messge" => "Modules Updated successfully.",
			"result" => $res,
		);
		echo json_encode($output);
	}

	function createInvoice(){
		$json = file_get_contents('php://input');
			$input = json_decode($json);
			$input = (array) $input;
			$input = (object) $input;


			$res = $this->api_model->createInvoice($input);

		$output = array(
			"status" => true,
			"messge" => "Modules Updated successfully.",
			"result" => $res,
		);
		echo json_encode($output);
	}
	function createapprovermaster(){
		$json = file_get_contents('php://input');
			$input = json_decode($json);
			$input = (array) $input;
			$input = (object) $input;


			$res = $this->api_model->createapprovermaster($input);

		$output = array(
			"status" => true,
			"messge" => "Modules Updated successfully.",
			"result" => $res,
		);
		echo json_encode($output);
	}
	function createconfiguration(){
		$json = file_get_contents('php://input');
			$input = json_decode($json);
			$input = (array) $input;
			$input = (object) $input;


			$res = $this->api_model->createconfiguration($input);

		$output = array(
			"status" => true,
			"messge" => "Modules Updated successfully.",
			"result" => $res,
		);
		echo json_encode($output);
	}
	public function getOrgInvoiceList($orgId){

		$menus = $this->api_model->getInvoiceListData($orgId);
		if($menus){
			$output = array(
				"status" => true,
				"messge" => "Invoice List successfully.",
				"result" => $menus,
			);
			echo json_encode($output);
		}else{
			$error = array(
				"status" => false,
				"error" => "OTP Error",
				"error_description"=>"Sorry! Incorrect Credentials.",
			);
			echo json_encode($error);
		}
		
	}

	public function getConsoleDashboardData($orgId){

		$menus = $this->api_model->getConsoleDashboardData($orgId);
		if($menus){
			$output = array(
				"status" => true,
				"messge" => "Invoice List successfully.",
				"result" => $menus,
			);
			echo json_encode($output);
		}else{
			$error = array(
				"status" => false,
				"error" => "OTP Error",
				"error_description"=>"Sorry! Incorrect Credentials.",
			);
			echo json_encode($error);
		}
		
	}

	
	function otpvalidate(){
		$input = $this->input->post();	
		if(!$input){
			$json = file_get_contents('php://input');
			$input = json_decode($json);
			$input = (array) $input;
		}

		$num_length = strlen((string)$input['otp']);
		if($num_length == 6) {
			if ( is_numeric($input['otp']) ) {

			}else{
				$error = array(
					"status" => false,
					"error" => "OTP Error",
					"error_description"=>"Sorry! Incorrect OTP.",
					"input" => ''
				);
				echo json_encode($error);
			}
		} else {
			$error = array(
				"status" => false,
				"error" => "OTP Error",
				"error_description"=>"Sorry! Incorrect OTP.",
				"input" => ''
			);
			echo json_encode($error);
		}

		$res = $this->api_model->otpvalidate($input);
		if($res){
			$output = array(
				"status" => true,
				"messge" => "OTP verified successfully.",
				"result" => $res,
			);
			echo json_encode($output);
		}else{
			$error = array(
				"status" => false,
				"error" => "OTP Error",
				"error_description"=>"Sorry! Incorrect OTP.",
				"input" => ''
			);
			echo json_encode($error);
		}
		
	}


}