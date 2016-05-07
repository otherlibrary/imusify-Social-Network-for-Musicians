<?php
Class giftcoupon_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;	
		$this->load->helper('number');

	}
	
	/*applying coupon starts*/
	function apply_coupon($code){
		$this->load->library("braintree_lib");	
		if($code != "")
		{
			/*First check code is valid or not*/
			$code_details = getvalfromtbl("*","membership_coupons","code = '".$code."'");
			$code_valid_check = $code_details["id"];
			if($code_valid_check > 0)
			{	

				/*Check if user is invited to use this code*/
				$code_used_invited_check = getvalfromtbl("id","giftcoupon_user_invite_log","couponId = '".$code_valid_check."' AND userId = '".$this->sess_userid."'","single");
				if($code_used_invited_check > 0)
				{

					/*If code valid then check user is not using the code again*/
					$code_used_check = getvalfromtbl("id","giftcoupon_used_log","couponId = '".$code_valid_check."' AND userId = '".$this->sess_userid."'","single");
					if($code_used_check > 0)
					{
						/*code earlier used so error*/
						$response["status"] = "fail";
						$response["msg"] = "You have already used this coupon code.Please contact admin for more details.";
					}
					else{
						$type = $code_details["type"];
						$month_limit = $code_details["month_limit"];
						if($month_limit == "l")
						{
							$end_date = date('Y-m-d H:i:s', strtotime("+100000 days"));
						}else{
							$cur_date = date('Y-m-d');
							$date = new DateTime($cur_date);
							$date->modify("+$month_limit month");
							$end_date = $date->date;
						}
						$this->load->model("space_model");
						if($type == "t")
						{
							/*Check user current plan*/
							$user_curent_plan_details = getvalfromtbl("*","user_plan_details","userId='".$this->sess_userid."' AND status = 'a'");
							$subscriptionId = $user_curent_plan_details["subscriptionId"]; 
							$user_curent_planid = $user_curent_plan_details["planId"];
							$gift_coupon_plan_details = getvalfromtbl("*","membership_plan","id='".$code_details["planId"]."'");

							$gift_coupon_plan_amount = $gift_coupon_plan_details["amount"];
							/*Check user current plan*/
							if($user_curent_planid == $code_details["planId"])
							{
								/*Current plan is equal to gift coupon plan*/
								$subscription_array['planId'] = $code_details["planId"];
								$add["inheritedFromId"] = 'premiumdiscountcoupon';
								$add["amount"] = $gift_coupon_plan_amount;
								if($code_details["month_limit"] == 'l')
								{
									$add["neverExpires"] = true;
								}
								else{
									$add["numberOfBillingCycles"] = $code_details["month_limit"];	
								}							
							}
							else if($user_curent_planid != $code_details["planId"]){
								
								/*Check current plan is free or not*/
								$free_plan_id = getvalfromtbl("id","membership_plan","amount='0'","single");
								/*Check current plan is free or not*/

								if($free_plan_id == $user_curent_planid)
								{

									/*this is free user just make entry in table*/
									$this->load->model("payment_transactions");
									$this->payment_transactions->update_user_plan_details_status($this->sess_userid,$planId,true);

									if($code_details["month_limit"] == 'l')
										$end_date =date('Y-m-d H:i:s', strtotime("+50000 days"));
									else
										$end_date = date('Y-m-d', strtotime("+".$code_details['month_limit']." month"));
									
									$insert_array_log = array(
										'userId'	=>	$this->sess_userid,
										'planId'	=>	$code_details["planId"],
										'subscriptionId' => "",
										'startDate'	=>	date('Y-m-d H:i:s'),
										'endDate'	=>	$end_date,
										'createdDate'	=>	date('Y-m-d H:i:s'),
										'amount'	=>	$gift_coupon_plan_amount,
										'paymentId'	=>	'',
										'planDetails' => json_encode($gift_coupon_plan_details)
										);

									$this->db->insert('user_plan_details',$insert_array_log);	
									/*User table log ends here*/
									$response["status"] = "success";
									$response["msg"] = "Gift coupon applied successfully.";
									$response["result"] = $result;	
								}
								else
								{
									$subscription_array['planId'] = $code_details["planId"];
									$add["inheritedFromId"] = 'premiumdiscountcoupon';
									$add["amount"] = $gift_coupon_plan_amount;
									if($code_details["month_limit"] == 'l')
									{
										$add["neverExpires"] = true;
									}
									else{
										$add["numberOfBillingCycles"] = $code_details["month_limit"];	
									}
								}
							}
							/*Make update on braintree*/

							$subscription_array['discounts']["add"] = $add;

							/*Make update on braintree*/
							$result = $this->braintree_lib->update_subscription($subscriptionId,$subscription_array);

							/*Updatign space */
							$plan_space = getvalfromtbl("space","membership_plan_detail","planId='".$code_details["planId"]."'","single");
							$prepare_arg_ar = array('userId'=>$this->sess_userid,'plan_space'=>$plan_space);
							$response_arr = $this->space_model->update_user_new_space($prepare_arg_ar);	
							/*Updatign space ends*/

							if($result->success == 1)
							{
								$response["status"] = "success";
								$response["msg"] = "Gift coupon applied successfully.";
								$response["result"] = $result;							 
							}else{
								$response["status"] = "success";
								$response["msg"] = "Coupon is not valid";
								$response["result"] = $result;							
							}							 
						}
						else if($type == "s"){
							$plan_space = $code_details["space_allowed"];
							$prepare_arg_ar = array('userId'=>$this->sess_userid,'plan_space'=>$plan_space);
							$response_arr = $this->space_model->update_user_new_space($prepare_arg_ar);						
						}
						/*Insert entry in gift coupon log*/
						$data = array(
							'couponId' =>  $code_valid_check,
							'userId' =>  $this->sess_userid,
							'startDate' => date('Y-m-d H:i:s'),
							'endDate' => $end_date,
							'createdDate' => date('Y-m-d H:i:s'),
							'couponDetails' => json_encode($code_details)
						);	
						if($code_details["month_limit"] == 'l')
							array_push($data, array('isLifetime' => 'y'));

						$this->db->insert('giftcoupon_log', $data);		
						$log_id = $this->db->insert_id();	
						/*Insert entry in gift coupon log ends*/
						if($log_id > 0)
						{
							$response["status"] = "success";
							$response["msg"] = "Coupon applied successfully.";
						}
					}
				}else{
					$response["status"] = "fail";
					$response["msg"] = "You are not invited to use this gift coupon yet.";
				}
				/*Check if user is invited to use this ends*/				
			}else{
				$response["status"] = "fail";
				$response["msg"] = "Coupon is not valid or expired.";
			}			
		}
		else{
			$response["status"] = "fail";
			$response["msg"] = "Gift coupon not applied.";			
		}
		return $response; 
	}
	/*applying coupon ends*/

	

}
?>