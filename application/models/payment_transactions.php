<?php
Class payment_transactions extends CI_Model
{	
	//check plan exists
	function check_plan_exists($plan_id,$cond = NULL,$limit = NULL){
		$this -> db -> select('id,plan_id,amount,name');
		$this -> db -> from('membership_plan');
		$this -> db -> where('status', "y");
		$this -> db -> where('id',$plan_id);
		
		if($cond != NULL)
			$this -> db -> where($cond);

		$this -> db -> limit(1);
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0) {
			$plan = $query->row_array();
			return $plan;
		} else   {
			return "notexist";
		}
	}			
	//check plan exists ends..

	//check user already purchased plan
	function check_user_plan_exist($companyId,$planId,$cond = NULL,$limit = NULL){
		$this -> db -> select('id');
		$this -> db -> from('payment_transactions');
		$this -> db -> where('order_status', "a");
		$this -> db -> where('companyId',$companyId);
		$this -> db -> where('planId',$planId);

		if($cond != NULL)
			$this -> db -> where($cond);

		$this -> db -> limit(1);
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0) {
			$plan = $query->row_array();
			return $plan;
		} else   {
			return "notexist";
		}
	}
	//check user already purchased plan ends

	/*Function that returns plan detail in json*/
	function plan_detail_json($planId,$flag = 'json'){
		
		$plan_details = NULL;
		if($planId > 0)
		{
			$plan_details = getvalfromtbl("*","membership_plan_detail","planId = '".$planId."'");
			/*var_dump($plan_details);*/
			if($flag == 'json')	
				$plan_details = json_encode($plan_details);
			else
				$plan_details = $plan_details;
		}
		return $plan_details;
	}
	/*Function that returns plan detail in json ends*/


	//save data to table
	function successfull_transaction($userId,$planId,$sucarr,$plan_cancel = NULL,$plan_updated = NULL){
		$this->load->helper('date');
		$this->load->model("membership_model");
		if($plan_cancel != NULL && $plan_cancel != "")
		{
			$amount = 0;
			$txnId = "";
			$subscriptionId = "";
			$start_date = 
			$end_date =  "";
			$plan_sting_name = $planId;
			$planId= $this->membership_model->get_plan_id($planId);
			$status = 'can';
		}else{
			/*$amount = $sucarr->subscription->transactions[0]->amount;
			$txnId = $sucarr->subscription->transactions[0]->id;*/
			$amount = $sucarr->subscription->price;
			$txnId = $sucarr->subscription->id;
			$subscriptionId = $sucarr->subscription->id;
			$start_date = $sucarr->subscription->billingPeriodStartDate->format('Y-m-d H:i:s');
			$end_date =  $sucarr->subscription->billingPeriodEndDate->format('Y-m-d H:i:s');
			$plan_sting_name = $planId;
			$planId= $this->membership_model->get_plan_id($planId);
			$status = 's';
		}

		$insert_array = array(
			'userId'	=>	$userId,
			'txnId'	=>	$txnId,
			'amount'	=>	$amount,
			'createdDate'	=>	date('Y-m-d H:i:s'),
			'result_array'	=>	json_encode($sucarr),
			'ip_address'	=>	get_client_ip(),
			'status' => $status
			);
		$this->db->insert('payment_transactions',$insert_array);				
		$latDataId 	=	$this->db->insert_id();
		/*User table log*/
		
		/*Check if user plan is already exist or not*/
		/*$user_plan_exist = getvalfromtbl("id","user_plan_details","userId = '".$userId."'","single");
		if($user_plan_exist > 0)
		{
			if($plan_cancel != NULL)
			{
				$cond = "userId = '".$userId."' AND status = 'a'";
			}else{
				$cond = "userId = '".$userId."' AND planId = '".$planId."'";
			}

			$user_sameplan_exist = getvalfromtbl("id","user_plan_details",$cond,"single");

			if($plan_updated == 'yes')
			{
				$data=array('status'=>'can');
				$this->db->where('status','a');
			}
			else
				$data=array('status'=>'c');
			
			$this->db->where('userId',$userId);
			$this->db->update('user_plan_details',$data);			
		}
		else{
			$status = 'a';
		}*/
		/*Calling a function*/
		$this->update_user_plan_details_status($userId,$planId,$plan_cancel);
		/*Calling a function*/

		$plan_details = $this->plan_detail_json($planId);
		
		/*Check if user plan is already exist or not ends*/
		$insert_array_log = array(
			'userId'	=>	$userId,
			'planId'	=>	$planId,
			'subscriptionId' => $subscriptionId,
			'startDate'	=>	$start_date,
			'endDate'	=>	$end_date,
			'createdDate'	=>	date('Y-m-d H:i:s'),
			'amount'	=>	$amount,
			'paymentId'	=>	$latDataId,
			'planDetails' => $plan_details
			);
		//print_r($insert_array_log);
		$this->db->insert('user_plan_details',$insert_array_log);	
		/*User table log ends here*/

		/*Update user's space in user table*/
		$this->membership_model->update_user_size($planId);
		/*Update user's space in user table*/
		
		/*Make this user artist or normal user*/
		if($plan_cancel != NULL)
		{
			$data1=array('member_plan'=>'u');
		}else{
			$data1=array('member_plan'=>'a');
		}
		$this->db->where('userId',$userId);
		$this->db->update('users',$data1);	
		/*Make this user artist ends normal user*/

		return $latDataId;

	}
	//save data to table ends


	/*Function for updating a status => Check if user plan is already exist or not*/
	public function update_user_plan_details_status($userId,$planId,$plan_cancel = NULL,$plan_updated = NULL){

		$user_plan_exist = getvalfromtbl("id","user_plan_details","userId = '".$userId."'","single");
		if($user_plan_exist > 0)
		{
			if($plan_cancel != NULL)
			{
				$cond = "userId = '".$userId."' AND status = 'a'";
			}else{
				$cond = "userId = '".$userId."' AND planId = '".$planId."'AND status = 'a'";
			}

			$user_sameplan_exist = getvalfromtbl("id","user_plan_details",$cond,"single");

			if($plan_updated == 'yes')
			{
				$data=array('status'=>'can');
				$this->db->where('status','a');
			}
			else
				$data=array('status'=>'c');
			
			$this->db->where('userId',$userId);
			$this->db->update('user_plan_details',$data);	
			return "success";		
		}
		else{
			$status = 'a';
			return $status;
		}
	}
	/*Function for updating a status => Check if user plan is already exist or not ends*/


	/*Success webhook of a user*/
	public function webhook_success_charge($sucarr){
		$this->load->helper('date');
		$this->load->model("membership_model");
		$amount = $sucarr->subscription->price;
		$txnId = $sucarr->subscription->id;
		$subscriptionId = $sucarr->subscription->id;
		$discountStauts = 'n';
		$currentBillingCycle =  null;
		$numberOfBillingCycles = null;

		$custId = $sucarr->subscription->transactions[0]->customer["id"];

		/*$message = " id ".$custId;
			$insert_arrays = array('data' => $message);
			$this->db->insert('temp',$insert_arrays);
		*/
		$userId = getvalfromtbl("id","users","braintreecustId = '".$custId."'","single");
		$start_date = $sucarr->subscription->billingPeriodStartDate->format('Y-m-d H:i:s');
		$end_date =  $sucarr->subscription->billingPeriodEndDate->format('Y-m-d H:i:s');
		$planId = $sucarr->subscription->planId;
		$plan_sting_name = $planId;
		$planId= $this->membership_model->get_plan_id($planId);
		$status = 's';

		/*Entry into payment transactions*/
		$insert_array = array(
			'userId'	=>	$userId,
			'txnId'	=>	$txnId,
			'amount'	=>	$amount,
			'createdDate'	=>	date('Y-m-d H:i:s'),
			'result_array'	=>	json_encode($sucarr),
			'ip_address'	=>	get_client_ip(),
			'status' => $status
			);
		$this->db->insert('payment_transactions',$insert_array);				
		$latDataId 	=	$this->db->insert_id();
		/*Entry into payment transactions ends*/

		$this->update_user_plan_details_status($userId,$planId);
		/*Calling a function*/

		$plan_details = $this->plan_detail_json($planId);
		
		if(!empty($sucarr->subscription->discounts))
		{
			$discountStauts = 'y';
			$currentBillingCycle =  $sucarr->subscription->discounts->currentBillingCycle;
			$numberOfBillingCycles = $sucarr->subscription->discounts->numberOfBillingCycles;
		}
		
		/*Check if user plan is already exist or not ends*/
		$insert_array_log = array(
			'userId'	=>	$userId,
			'planId'	=>	$planId,
			'subscriptionId' => $subscriptionId,
			'startDate'	=>	$start_date,
			'endDate'	=>	$end_date,
			'createdDate'	=>	date('Y-m-d H:i:s'),
			'amount'	=>	$amount,
			'paymentId'	=>	$latDataId,
			'planDetails' => $plan_details,
			'discountStauts' => $discountStauts,
			'currentBillingCycle' => $currentBillingCycle,
			'numberOfBillingCycles' => $numberOfBillingCycles
		);		
		$this->db->insert('user_plan_details',$insert_array_log);		

		/*Update user's space in user table*/
		$this->membership_model->update_user_size($planId,$userId);
		/*Update user's space in user table*/
		
		/*Make this user artist or normal user*/
		/*if($plan_cancel != NULL)
		{
			$data1=array('member_plan'=>'u');
		}else{
			$data1=array('member_plan'=>'a');
		}
		$this->db->where('userId',$userId);
		$this->db->update('users',$data1);*/	
		/*Make this user artist ends normal user*/
		$insert_arrays = array('data' => $message_t);
		$this->db->insert('temp',$insert_arrays);
		return $latDataId;
	}
	/*Success webhook of a user*/


	/*Webhook plan unsuccess charge*/
	public function webhook_unsuccess_charge($sucarr){

	}
	/*Webhook plan unsuccess charge ends*/


	/*Webhook plan cancel*/
	public function webhook_cancel($subscriptionId){
		$this->load->model("membership_model");
		$userId = getvalfromtbl("userId","user_plan_details","subscriptionId = '".$subscriptionId."'","single");
		$plan_id_ar = getvalfromtbl("id,amount","membership_plan","amount = '0'");
		$plan_id = $plan_id_ar["id"];
		$plan_amount = $plan_id_ar["amount"];
		$plan_details = $this->plan_detail_json($plan_id);
		
		$this->update_user_plan_details_status($userId,$plan_id);
		/*Make new entry */
		$data_plan_det = array(
			'userId' => $userId,
			'planId' => $plan_id,
			'startDate' => date('Y-m-d H:i:s'),
			'endDate' => date('Y-m-d H:i:s', strtotime("+50000 days")),
			'createdDate' => date('Y-m-d H:i:s'),
			'status' => 'a',
			'amount' => $plan_amount,
			'planDetails' => $plan_details
			);		
		$this->db->insert('user_plan_details', $data_plan_det);
		/*Make new entry ends*/

		$this->membership_model->update_user_size($plan_id);
		$response["status"] = "success";		
		return $response;
	}
	/*Webhook plan cancel ends*/






}
?>