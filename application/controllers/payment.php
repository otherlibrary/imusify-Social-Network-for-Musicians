<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payment extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;
//		if($this->sess_userid && $this->sess_userid["id"] > 0)
//		{
//
//		}
//		else{
//			redirect('home', 'refresh');
//			exit;
//		}
		$this->load->model('payment_transactions');
		$this->load->library("braintree_lib");
	}

	public function index()
	{
		/*echo '<pre>'; print_r($this->session->all_userdata());exit;*/
		$plan_sess_detail = $this->session->userdata('payment_info');
                //var_dump ($plan_sess_detail);exit;
		$subscription_array = array();
		if(!$plan_sess_detail)
		{
			echo "No plan selected";
			exit;
		}
		$plan_id = $plan_sess_detail["plan_id"];	
		$customer_id = $this->session->userdata('user')->braintreecustId;

		/*Check if user is upgarding plan*/
		$this->load->model("membership_model");
		$user_plan_upgrade_check = $this->membership_model->user_update_plan_check($this->sess_userid,$plan_id);
		$action = "create";
		if($user_plan_upgrade_check["status"] == "upgrade")
		{
			$action = "update";
			$subscriptionId = $user_plan_upgrade_check["subscriptionId"];
			//$subscription_array['id'] = $subscriptionId;				
			$subscription_array['price'] = $plan_sess_detail["amount"];	
			//$subscription_array['merchantAccountId'] = "3b56fj26zd7787r5";	
			
		}
		else if($user_plan_upgrade_check == "sameplan"){
			echo "same plan ";exit;
		}		
		/*Check if user is upgarding plan ends */
		$verify = false;
		$payment_nonce = $_POST["payment_method_nonce"];
		if($customer_id != "" || $customer_id != NULL)
		{
			$verify = true;
			$subscription_array['paymentMethodNonce'] = $payment_nonce;
			$subscription_array['planId'] = $plan_id;
		}
		if($verify == false)
		{
			$data_array = array(

				'firstName' => $this->session->userdata('user')->firstname,
				'lastName' => $this->session->userdata('user')->lastname,
				'phone' => '281.330.8004',
				'fax' => '419.555.1235',
				'website' => 'http://example.com',
				'email' => (string)$this->session->userdata('user')->email,
				'company' => "abc",
				'paymentMethodNonce' => $payment_nonce
				);
			/*echo "<pre>";
			print_r($data_array);*/
			$customer_created = $this->braintree_lib->create_customer($data_array);
			/*echo "<pre>";
			print_r($customer_created);*/
			$customer_id  = $customer_created->customer->id;
			$temp = $customer_created->customer->paymentMethods();
			$customer_token = $temp[0]->token;
			//exit;
			/*update braintree id to user's table */
			$array_update = array('braintreecustId'=>$customer_id);

			$this->db->where('id',$this->sess_userid);
			$this->db->update('users',$array_update);
			/*update braintree id to user's table */
			$subscription_array['paymentMethodToken'] = $customer_token;
			$subscription_array['planId'] = $plan_id;
		}
		/*Giving user free trial period of 12 months*/
		/*$subscription_array['trialPeriod'] = true;
		$subscription_array['trialDuration'] = 12;
		$subscription_array['trialDurationUnit'] = 'month';*/
		/*Giving user free trial period of 12 months*/
		if($action == "create")
			$result = $this->braintree_lib->create_subscription($subscription_array);
		else if($action == "update")
			$result = $this->braintree_lib->update_subscription($subscriptionId,$subscription_array);
		
		//echo "<pre>";
		//print_r($result);
		if($result->success == 1)
		{
                        //insert payment transaction                    
			$this->payment_transactions->successfull_transaction($this->sess_userid,$plan_id,$result,null,"yes");
                        
			/*Update free space of this user*/
			//$this->is_logged_in("");
			/*Update free space of this user*/      
                        
                        
                        //$this->session->set_flashdata("notification",'Membership has been purchased successfully');
                        
			/*Unset a payment session*/
			$this->session->unset_userdata('payment_info');
			/*Unset a payment session*/			

			//echo "<script>window.close();</script>";
			//echo "";
                        //redirect to homepage
                        redirect (base_url().'?payment=success', 'refresh');
		}
		else{
                    //print out error message
                    
			$this->session->unset_userdata('payment_info');
                        //redirect to membership page to try again
                        
                        redirect (base_url().'/membership/'.$plan_id.'?payment=failed');
			//echo "<pre>";
			//print_r($result);
			//exit;
		}

	}	
}

/* End of file payment.php */
/* Location: ./application/controllers/payment.php */