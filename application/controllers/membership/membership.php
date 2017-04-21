<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class membership extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		$this->load->model('membership_model');
		$this->load->library("braintree_lib");
	}
	function index()
	{
		/*$planId = "2";
		$plan_details = getvalfromtbl("*","membership_plan_detail","planId = '".$planId."'");

		echo "<pre>";
			var_dump($plan_details);
		
			$plan_details = json_encode($plan_details);
			echo "<pre>";
			var_dump($plan_details);
		exit;*/

		//echo "a";exit;
		$this->config->set_item('title','Membership Plan');
		$plans = $this->membership_model->fetch_all_plans();
		//dump($plans);exit;
		$temp_ar = array();
		$i = 0;
		foreach ($plans as $key => $value) {
			foreach ($value["plan_details"] as $key1 => $value1) {
				$temp_ar[$key1]["text"] = $value1["text"];

				if($value1["value"] == 'y')
					$temp_ar[$key1]["values"][$i] = '<i class="fa fa-check-circle"></i>';	
				else if($value1["value"] == 'n')
					$temp_ar[$key1]["values"][$i] = '<i class="fa fa-times-circle"></i>';	
				else
					$temp_ar[$key1]["values"][$i] = $value1["value"];  
			}
			$i++;
		}
//		var_dump($temp_ar);
//		exit;
		$data = array(
			'news'=>$m,
			'plans'=>$plans,
			'columns_arr' => $temp_ar
			);		
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="membership/membership.html";		
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";						
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['current_tm']='membership';
		$this->load->view('home',$a);
	}


	/*Payment details*/
	public function detail($plan_name){
		//echo $this->session->userdata('user')->email;
		//echo '<pre>'; print_r($this->session->all_userdata());exit;
		if($plan_name != NULL)
		{
			$this->load->model('payment_transactions');
			$plan_id = getvalfromtbl("id","membership_plan","plan_id = '".$plan_name."'","single");

			if($plan_id > 0)
			{
				$plan_exists = $this->payment_transactions->check_plan_exists($plan_id);		
				if($plan_exists == "notexist")
				{
					$this->session->set_userdata('msg',"Plan does not exist.");
					redirect('membership', 'refresh');
					exit;
				}
				$data1['plan']	=	$plan_exists["name"];
				$data1['amount']	=	$plan_exists["amount"];
				$data1['plan_id']	=	$plan_exists["plan_id"];
				$data1['id']	=	$plan_exists["id"];		
				$this->session->set_userdata('payment_info',$data1);
                                //var_dump ($data1);
                                //var_dump($this->session->userdata('payment_info'));
                                //exit;
				//echo '<pre>'; print_r($this->session->all_userdata());exit;
				$token = $this->braintree_lib->create_client_token($this->session->userdata('user')->braintreecustId);
            	$data = array(
					'plan_details'=> $plan_exists,
					'client_token' => $token,
					'payment_url' => base_url()."payment"
				);
                if ($_GET['payment'] == 'failed') $data['failed'] = true;
            	$template_arry['MainPanel']="main.html";
				$template_arry['leftPanel']="left_panel.html";
				$template_arry['popUpContent']="membership/membership_plan_purchase.html";		
				$template_arry['rightPanel']="right_panel.html";
				$template_arry['contentPanel']="headlines.html";
				$template_arry['newsRow']="news_row.html";
				$template_arry['playerPanel']="player_panel.html";						
				$template_arry['bigPlayerPanel']="big_player.html";
				$data1=get_template_content($template_arry,$data);
				$a['data'] = $data1;
				$a['current_tm']='membership_plan_purchase';
				$this->load->view('home',$a);
			}
		}/*If condition overs*/
	}

	/*Cancel Membership*/
	public function cancel(){
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
                                
		if($result->success == 1)
		{
                        //andy
                        //need to change membership from a to can (Active to Cancelled)
			$this->membership_model->cancel_all_plan();                        
                        
                        //can not redirect due to api ajax call 
                        //redirect (base_url().'?cancel=success', 'refresh');
                        
                        //return message cancelled successfully
                        //$this->response(array('success' => 'Your membership is cancelled successfully'), 200); // 200 being the HTTP response code
                        //echo 'Cancelled the subscription successfully';
                        
                        //$success = array("success" => "Your membership is cancelled successfully");
                        //json_encode($success);
                        
                        //frontend load json and show it
//                        $ar["status"] = "success";
//			$ar["msg"] = "You have successfully cancelled your plan.";
//			$ar["msg"] = "";
                        $data['json'] = '{"status":"success", "msg": "You have successfully cancelled your plan"}';
                        $this->load->view('json_view', $data);
                        
                        
		}else{
                        $data['json'] = '{"status":"failed", "msg": "Subscription was not cancelled. Please try again later"}';
                        $this->load->view('json_view', $data);                        
                    //$json_result = json_encode($result);
                    //echo 'Had error to cancel this Subscription ID '.$subscriptionId.'  '.$json_result;
                    //exit;
                }
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */