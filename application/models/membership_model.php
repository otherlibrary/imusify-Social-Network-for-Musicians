<?php
Class Membership_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;	
		$this->load->helper('number');	
	}
	
	function fetch_all_plans($user_id = NULL,$limit = NULL,$cond = NULL,$counter = NULL,$space_format = NULL)
	{
		
		$i = 1;
		$output = array();		
		$userId = ($userId != NULL) ? $userId : $this->sess_uid;	
		$user_current_active_plan = $this->user_plan_details();
		if($userId > 0)
			$where = "status='y'";
		else
			$where = "status='y'";

		if($cond != NULL)
			$where .= " AND ".$cond;

		$this->db->select('*');
		$this->db->from('membership_plan');
		//$this->db->join('membership_plan_detail as mpd', 'mp.id = mpd.planId','left');
		$this->db->where($where);
		if($limit != NULL)
			$this ->db-> limit($limit);

		$this->db->order_by("amount","desc");
		
		$query = $this->db->get();
		//print_query();
		$records_count = $query->num_rows();

		if($counter != NULL)
			return $records_count;		

		if($records_count > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$plan_details = $this->plans_details($row["id"],NULL,$space_format);

				if($row["id"] == $user_current_active_plan)
				{
					$row["current_plan_active"] = $row["id"];
				}

				$row["payment_url"] = base_url()."payment/".$row["id"];
				$row["plan_details"] = $plan_details; 
				//echo $row["space"];
				$output[] = $row;					
			}
		}else{
			$output["status"] = "fail";
		}
		return $output;
	}	


	function plans_details($plan_id,$cond = NULL,$space_format = NULL)
	{
		$i = 1;
		$output = array();		
		$userId = ($userId != NULL) ? $userId : $this->sess_uid;	
		$comments_array = array();

		/*Preparing all comments*/
		$query_com = $this->db->query("SHOW FULL COLUMNS FROM `membership_plan_detail`");
		$row_comm = $query_com->result_array();			
		unset($row_comm[0]);
		unset($row_comm[1]);
		
		foreach ($row_comm as $key => $value) {
			
			//if($value["Field"] != "id" || $value["Field"] != "planId")
			//{
			$comments_array[$value["Field"]] = $value["Comment"];	
			//}
		}	
		//dump($comments_array);exit;
		/*Preparing all comments*/
		$where = "planId = '".$plan_id."'";
		if($cond != NULL)
			$where .= " AND ".$cond;

		$this->db->select('*');
		$this->db->from('membership_plan_detail');
		$this->db->where($where);
		if($limit != NULL)
			$this ->db-> limit(1);
		
		$query = $this->db->get();
		$records_count = $query->num_rows();

		if($records_count > 0)
		{
			$row = $query->row_array();
			if($space_format != NULL)
			{
				if($row["space"] == "-1")
					$row["space"] = "Unlimited";	
				else	
					$row["space"] = $row["space"]; 
			}
			else{
				if($row["space"] == "-1")
					$row["space"] = "Unlimited";	
				else	
					$row["space"] = byte_format($row["space"]); 
			}	
			//$row["unformated_space"] = $row["space"]; 	
			//dump($row);exit;
			unset($row["id"]);
			unset($row["planId"]);

			$temp_array = array();
			$i = 0;	
			foreach ($row as $key => $value) {
				$temp_array[$i]["name"] = $key;
				$temp_array[$i]["text"] = $comments_array[$key];
				$temp_array[$i]["value"] = $value;
				$i++;
			}
			$output = $temp_array;			
		}else{
			$output["status"] = "fail";
		}
		//dump($output);
		return $output;
	}	


	function get_plan_id($plan_name = NULL,$cond = NULL)
	{
		$where = "plan_id = '".$plan_name."'";
		if($cond != NULL)
			$where .= " AND ".$cond;

		$this->db->select('id');
		$this->db->from('membership_plan');
		$this->db->where($where);
		$this ->db-> limit(1);
		$query = $this->db->get();
		$records_count = $query->num_rows();
		if($records_count > 0)
		{
			$row = $query->row_array();
			return $row["id"];
		}else{
			return "fail";
		}		
	}


	/*Function for updating user's membership's area*/
	function update_user_size($planId = NULL,$userId = NULL){
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;
		if($planId != NULL)
		{
			$plan_space = getvalfromtbl("space","membership_plan_detail","id='".$planId."'","single");
			//avail_space
			
			$where = "userId = '".$userId."'";
			$this->db->select('SUM(filesize) total_used_space');
			$this->db->from('tracks');
			$this->db->where($where);
			$query = $this->db->get();
			$records_count = $query->num_rows();

			if($records_count > 0)
			{
				$row = $query->row_array();	
				if($plan_space > $row["total_used_space"])
				{
					$update_user_space = $plan_space - $row["total_used_space"];
				}
				else if($row["total_used_space"] > $plan_space){
					//$update_user_space = $row["total_used_space"] - $plan_space;					
					$update_user_space = 0;
				}					
				$data=array('avail_space'=>$update_user_space);
				$this->db->where('id',$userId);
				$this->db->update('users',$data);
				$output["status"] = "success";
				$output["msg"] = "Success";
			}else{
				$output["status"] = "fail";
				$output["msg"] = "Failure";
			}
		}
		return $output;
	}
	/*Function for updating user's membership's area*/


	/*Function for updating track's size => Common Function*/
	function update_track_size(){
		$this->db->select('*');
		$this->db->from('tracks');
		$query = $this->db->get();
		$records_count = $query->num_rows();

		if($records_count > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$filePath = asset_upload_path()."media/".$row["userId"]."/".$row["trackName"];
				$file_size = filesize($filePath);

				$data=array('filesize'=>$file_size);
				$this->db->where('id',$row["id"]);
				$this->db->update('tracks',$data);

			}
		}else{
			$output["status"] = "fail";
		}
	}	
	/*Function for updating track's size => Common Function ends*/

	/*Check user memebrship exist upto*/
	function check_user_membership_plandate($userId = NULL){	
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;
		$date = date('Y-m-d');
		$freemiumplanid = getvalfromtbl("id","membership_plan","amount='0'",'single');
		$where = "userId = '".$userId."' AND status = 'a' AND planId != '".$freemiumplanid."' AND (CURDATE() between startDate and endDate or DATE_FORMAT(startDate,'%Y-%m-%d') = '".$date."')";
		$this -> db -> select('id,date_format(endDate,"%d %b %Y") as formatdate');
		$this -> db -> from('user_plan_details');
		$this -> db -> where($where);
		$this -> db -> limit(1);
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0) {
			$plan = $query->row_array();
			$response["status"] = "current_plan_active";
			$response["endDate"] = $plan["formatdate"];
			return $response;
		}
		else   
		{
			$response["status"] = "no_plan_active";
			return $response;
		}
	}
	/*Check user memebrship exist upto ends*/

	/*Function for checking a user's plan details*/
	
	function user_update_plan_check($user_id = NULL,$plan_id = NULL){
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;
		if($plan_id > 0)
		{

		}else{
			$plan_id = getvalfromtbl("id","membership_plan","plan_id='".$plan_id."'","single");
		}

		if($userId != NULL && $plan_id != NULL)
		{
			$where = "userId = '".$userId."' AND status = 'a' AND subscriptionId IS NOT NULL";
			$this -> db -> select('id,subscriptionId');
			$this -> db -> from('user_plan_details');
			$this -> db -> where($where);
			$this -> db -> limit(1);
			$query = $this -> db -> get();	
			if($query -> num_rows() > 0) {
				$plan = $query->row_array();
				$update_check = getvalfromtbl("id","user_plan_details","userId = '".$userId."' AND status = 'a' AND planId = '".$plan_id."'","single");
				if($update_check > 0)
				{
					$response["status"] = "sameplan";					
					return $response;
				}else{
					$response["status"] = "upgrade";
					$response["subscriptionId"] = $plan["subscriptionId"];
					return $response;
				}
			}
			else   
			{
				$response["status"] = "firsttimebuy";
				return $response;
			}
		}else{}
	}
	/*Function for checking a user's plan details ends*/

	/*Cancel all plan and make user a free user*/
	function cancel_all_plan($userId = NULL){
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;
		$plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
		$this->update_user_size($plan_id);
                //change from subscription active to cancelled
                $data=array('status'=> 'can');
		$this->db->where('userId',$userId);
		$this->db->update('user_plan_details',$data);
                
	}

	function cancel_user_membership_plan(){
		$this->load->library("braintree_lib");
		$this->load->model("payment_transactions");

		$userId = $this->session->userdata('user')->id;
		$subscriptionId = getvalfromtbl("subscriptionId","user_plan_details","userId = '".$userId."' AND status = 'a'");
		$result = $this->braintree_lib->cancel_subscription($subscriptionId);
		if($result)
		{
			//$this->Membership_model->cancel_all_plan();
			$plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
			$this->update_user_size($plan_id);
			$this->payment_transactions->successfull_transaction($userId,$plan_id,$result,true);
			$response["status"] = "success";
		}else{
			$response["status"] = "fail";
		}
		return $response;
	}

	/*Cancel user mem*/
	function braintree_cancel_subscription($subscirptionId){
		$userId = getvalfromtbl("userId","user_plan_details","type = 'a' AND subscriptionId='".$subscirptionId."'","single");

		if($userId > 0)
		{
			/*Change status of user to cancelled and make it normal user*/
			$data=array('status'=>'can');
			$this->db->where('userId',$userId);
			$this->db->update('user_plan_details',$data);
			/*Change status of user to cancelled and make it normal user ends*/

			/*Change status of user profile to cancelled and make it normal user*/
			$data1=array('member_plan'=>'u');
			$this->db->where('userId',$userId);
			$this->db->update('users',$data1);	
			/*Change status of user to cancelled and make it normal user ends*/

			/*Make this as a free user*/
			$plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
			$temp = $this->update_user_size($plan_id);
			/*Make this as a free user ends*/
			return $temp;

		}else{

		}
	}
	/*Cancel all plan and make user a free user ends*/

	/*Get current user plan details*/
	function user_plan_details(){
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;
		$where = "userId = '".$userId."' AND status = 'a'";
		$this -> db -> select('id,planId');
		$this -> db -> from('user_plan_details');
		$this -> db -> where($where);
		$this -> db -> limit(1);
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0) {
			$plan = $query->row_array();
			return $plan["planId"];
		}
	}
	/*Get current user plan details*/


	/*Change user's membership and make to older memerbship plan*/
	function change_coupon_membership($limit = NULL){
		$datenow = date('Y-m-d');
		$where = "('".$datenow."' INTERVAL 7 DAY) >= DATE_FORMAT(gcul.endDate,'%Y-%m-%d') AND gcul.isLifetime != 'y'";
		$this->db->select('gcul.*,u.email,u.firstname,u.lastname,DATE_FORMAT(gcul.endDate,"%Y-%m-%d") as couponenddate');
		$this->db->from('giftcoupon_used_log as gcul');
		$this->db->join('users as u', 'gcul.userId = u.id','left');
		$this->db->where($where);	
		if($limit != NULL)
			$this ->db-> limit($limit);
		$query = $this->db->get();
			//print_query();
		$records_count = $query->num_rows();
		if($counter != NULL)
			return $records_count;		

		if($records_count > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($datenow == $row["couponenddate"])
				{
					/*Update this user's plan details as older plan one*/
					$get_last_plan_id = getvalfromtbl("planId","user_plan_details","status = 'c' AND userId = '".$row["userId"]."' AND ","single","id desc");

					$this->load->model('payment_transactions');
					$this->load->library("braintree_lib");

					$free_plan_id = getvalfromtbl("id","membership_plan","amount = '0'","single");
					$plan_details =  getvalfromtbl("*","membership_plan_details","id = '".$free_plan_id."'");
					if($free_plan_id == $get_last_plan_id)
					{
						/*Earlier user was using free plan change it to free user again*/
						$this->load->model("payment_transactions");
						$this->payment_transactions->update_user_plan_details_status($this->sess_userid,$free_plan_id,true);	

						$end_date = date('Y-m-d', strtotime("+".$code_details['month_limit']." month"));

						$insert_array_log = array(
							'userId'	=>	$this->sess_userid,
							'planId'	=>	$free_plan_id,
							'subscriptionId' => "",
							'startDate'	=>	date('Y-m-d H:i:s'),
							'endDate'	=>	$end_date,
							'createdDate'	=>	date('Y-m-d H:i:s'),
							'amount'	=>	0,
							'paymentId'	=>	'',
							'planDetails' => json_encode($plan_details)
							);

						$this->db->insert('user_plan_details',$insert_array_log);
						
						/*Earlier user was using free plan change it to free user again ends*/
					}
					else{
						
						/*User was having other plan earlier and got premium or other plan*/
						$user_curent_plan_details = getvalfromtbl("*","user_plan_details","userId='".$this->sess_userid."' AND status = 'a'");
						$subscriptionId = $user_curent_plan_details["subscriptionId"];
						$last_plan_details = getvalfromtbl("plan_id,amount","membership_plan","id='".$get_last_plan_id."'");
						$subscription_array['planId'] = $last_plan_details["plan_id"];	
						$subscription_array['price'] = $last_plan_details["amount"];
						$result = $this->braintree_lib->update_subscription($subscriptionId,$subscription_array);
						/*User was having other plan earlier and got premium or other plan ends*/
					}
					/*Update this user's plan details as older plan one ends*/
					$this->load->model("space_model");
					$prepare_arg_ar = array('userId'=>$this->sess_userid,'plan_space'=>$plan_space);
					$response_arr = $this->space_model->update_user_new_space($prepare_arg_ar);						
				}

				$data_mail = array(
					'login_url' => base_url()."login",
					'email' => $email,
					'end_date' => $row["endDate"]
					);			
				$abc=$this->template->load('mail/email_template','mail/giftplanexpires',$data_mail,TRUE);
				send_mail(ADMIN_MAIL,$email,"Successfully registered with Imusify",$abc);
			}
		}
	}
	/*Change user's membership and make to older memerbship plan ends*/



}
?>