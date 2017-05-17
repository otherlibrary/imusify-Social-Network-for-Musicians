<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Membership_api extends REST_Controller
{
//        function __construct()
//	{
//                parent::__construct();
//		$this->load->model('Membership_model');
//		$this->load->library("braintree_lib");
//	}
        
	function check_current_plan_post()
	{
		$this->load->model('membership_model');
		$data = $this->membership_model->check_user_membership_plandate();
		if($data["status"] == "current_plan_active")
		{
			$ar["status"] = "plan_active";
			$ar["endDate"] = $data["endDate"];
			$ar["msg"] = "";
		}
		else if($data["status"] == "no_plan_active"){
			$ar["status"] = "no_plan_active";	
			$ar["msg"] = "";
		}
		$this->response($ar,200);
	}

	/*Function for cancelling a active plan*/
	function cancel_plan(){
                echo 'test';exit;
		//$this->load->model('membership_model');
		
                //$data = $this->membership_model->cancel_user_membership_plan();
                
                $userId = $this->session->userdata('user')->id;
		//subscriptionId
		$subscriptionId = getvalfromtbl("subscriptionId","user_plan_details","userId = '".$userId."' AND status = 'a'");
                //var_dump ($subscriptionId);
                //$sub_id = $this->braintree_lib->search_subscription($subscriptionId);
                //var_dump ($sub_id);exit;  
                try {
                    $result = $this->braintree_lib->cancel_subscription($subscriptionId['subscriptionId']);
                }                
                catch (Exception $e) {
                    echo 'Subscription ID Not Found'."\n";
                    exit;
                }	
                
                
		//if($data["status"] == "success")
                if($result->success == 1)		
		{
			$ar["status"] = "success";
			$ar["msg"] = "You have successfully cancelled your plan.";
			$ar["msg"] = "";
		}
		else {
			$ar["status"] = "fail";	
			$ar["msg"] = "Some error occured please try again.";
			$ar["msg"] = "";
		}
		$this->response($ar,200);
	}
	/*Function for cancelling a active plan ends*/


}