<?php

class sclogin extends my_controller {
	
	function __construct() {
		parent::__construct();	
		//load the input and session libraries - this can be removed if they are auto-loaded
		//$this->load->library(Array('input','session'));
		
		//start the session
		//session_start();
	
		$this->load->helper('url');
		$this->load->model('ilogin');		
		//rebuild the $_GET array because CI unsets it
		//parse_str($_SERVER['QUERY_STRING'],$_GET);	
		}
		
		function index() {	
			//Load settings from config file
			$this->load->config('social_keys');			
			//print  $this->config->item('soundcloud_key');
			$config = array(
			'clientId'=> $this->config->item('soundcloud_key'),
			'clientSecret'=>$this->config->item('soundcloud_secret'),
			'redirecturi'=> $this->config->item('soundcloud_callback_url')
			);	
			/* $this->data['clientId']    = $this->config->item('soundcloud_key');
			$this->data['clientSecret'] = $this->config->item('soundcloud_secret');
			$this->data['redirecturi']    = $this->config->item('soundcloud_callback_url');
			//	$this->data['tmp_path']        = $this->config->item('soundcloud_tmp_path');
			$this->data['development']    =true; */
	   
			$this->load->library("Services_Soundcloud",$config);
			$soundcloud = new Services_Soundcloud($config);
			$authorizeUrl = $soundcloud->getAuthorizeUrl();
	
			if($authorizeUrl)
			 {
			 //redirect($authorizeUrl);
					$url = "https://soundcloud.com/connect?client_id=".$this->config->item('soundcloud_key')."&redirect_uri=".$this->config->item('soundcloud_callback_url')."&response_type=code&consumer_key=".$this->config->item('soundcloud_secret')."";
				redirect($url);
			 }
	    
	}
	
	public function callback()
	{
	
	//Load settings from config file
		$this->load->config('social_keys');
		/* $this->data['clientId']    = $this->config->item('soundcloud_key');
		$this->data['clientSecret'] = $this->config->item('soundcloud_secret');
		$this->data['redirecturi']    = $this->config->item('soundcloud_callback_url');
		$this->data['development']    =true;
		//$this->data['tmp_path']        = $this->config->item('soundcloud_tmp_path'); */
		 $code = (strlen($this->input->get('code')))
            ? $this->input->get('code')
            : null;
					$config = array(
			'clientId'=> $this->config->item('soundcloud_key'),
			'clientSecret'=>$this->config->item('soundcloud_secret'),
			'redirecturi'=> $this->config->item('soundcloud_callback_url')
			);

		$this->load->library("Services_Soundcloud",$config);
		$soundcloud = new Services_Soundcloud($config);
	
	if($code){
		try {

		$accessToken = $soundcloud->accessToken($code);
		//print_r($accessToken);
		//$this->session->set_userdata("access_token",$accessToken);
		} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
			exit($e->getMessage());
		}
		try {
				$user = json_decode($soundcloud->get('me'), true);
				$sc_username = $user["username"];
				$sc_status = $this->ilogin->user_exist_check(null,$sc_username);
 
				if($sc_status  == "dbexist")
				{
					if($this->session->userdata(USER_SESSION_NAME)->id > 0)
					{
						$left_panel = true;
						$username = $this->session->userdata(USER_SESSION_NAME)->username;
						$this->ilogin->update_social_id("scid",$user->id);
					}
					else
						$left_panel = false;
					
					/*already user session set just need to change panels*/					
					$data1 = array(
						'title'=>$this->config->item('title'),
						'url' => base_url(),
						'img'=>img_url(),
						'loggedin'=>$left_panel,
						'news'=>$m,
						'username'=>$username,
						'c_username'=>"a",
						'c_password'=>"b",
						"remem"=>false
					);
					$d["flag"] = "false";
					$d["response"] = $data1;
				}				
				else if($sc_status  == "intermediate"){					
					$user1["provider"] = "sc";
					$user1["gender"] = "";
					$user1["email"] = "";
					$user1["first_name"] = $user["first_name"];
					$user1["last_name"] = $user["last_name"];
					$user1["sc_username"] = $user["username"];	
					$user1["id"] = $user["id"];					
					$this->session->set_userdata('socialuser',$user1);
					$d["flag"] = "true";			
					$d["gender"] = "";
					$d["response"] = $this->session->userdata("socialuser");		
					} 
					$this->load->view('temp',$d);		
		} catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
		exit($e->getMessage());
		}

		//echo "";
		//print_r($me);
		
		
		
		}
	
	}
	
}