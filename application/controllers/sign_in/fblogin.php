<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class fblogin extends my_controller {
	public function __construct(){
		parent::__construct();
		
		// To use site_url and redirect on this controller.
        $this->load->helper('url');
		$this->load->model('ilogin');
	}
	
	public function login(){
		//$this->load->library('facebook'); // Automatically picks appId and secret from config
        // OR
        // You can pass different one like this
		//print 'a';
        $this->load->library('facebook', array(
            'appId' => '668742323232861',
            'secret' => 'c6203a89f59ec798239b8ebdd02271f5',
        ));
		
		$user = $this->facebook->getUser();
        //print_r($user);
        if ($user) {
            try {
				//print 'b try';
				//print_r($user);
				
                $data['user_profile'] = $this->facebook->api('/me');
				//print_r($data['user_profile']);
				
				//print $data['user_profile']["email"];
				$fb_status = $this->ilogin->user_exist_check($data['user_profile']["email"]);
				
				if($fb_status  == "dbexist")
				{
					if($this->session->userdata('user')->id > 0)
					{
						$left_panel = true;
						$username = $this->session->userdata('user')->username;
						$this->ilogin->update_social_id("fbid",$data['user_profile']["id"]);
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
				else if($fb_status  == "intermediate"){
					$data['user_profile']["provider"] = "fb";
					$this->session->set_userdata('socialuser',$data['user_profile']);
					$d["flag"] = "true";		
					$d["response"] = $this->session->userdata("socialuser");						
				}
				
            } catch (FacebookApiException $e) {
				//print 'c';
                $user = null;
            }
        }else {
            $this->facebook->destroySession();
        }
		
        if ($user) {
           // $data['logout_url'] = site_url('welcome/logout'); // Logs off application
            // OR 
            // Logs off FB!
            // $data['logout_url'] = $this->facebook->getLogoutUrl();
			//print_r($this->session->all_userdata());
				
					//exit;
					
			$this->load->view('temp',$d);
        } else {
			$data['login_url'] = $this->facebook->getLoginUrl(array(
                //'redirect_uri' => site_url('suc_fb_login'), 
                'scope' => array("email","") // permissions here
            ));
			//print $data['login_url'];
			redirect($data['login_url']);	 
        }       
	     
	   //$this->load->view('login',$data);
	}
	
    public function logout(){
        $this->load->library('facebook');
        // Logs off session from website
        $this->facebook->destroySession();
        // Make sure you destory website session as well.
        redirect('welcome/login');
    }

}

