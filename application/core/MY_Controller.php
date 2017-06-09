<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH . '/libraries/REST_Controller.php');

class MY_Controller extends REST_Controller {

    public $result = [
        'error'   => false,
        'message' => 'Success',
        'data'    => [],
        'debug'   => [],
    ];
	public $user_sess;
	public function __construct($module = NULL,$check = "")
	{
		parent::__construct();
		//$this->load->model('login');

		$this->user = ($this->session->userdata(USER_SESSION_NAME)) ? $this->session->userdata(USER_SESSION_NAME) : "";
		if($check == "front")
		{		
			$this->is_logged_in();
			//$this->clear_cache();
		}

		if($check == "nologin")
		{	
			$this->is_logged_in("nologin");			
		}

		$cookie_user = $this->rememberme->verifyCookie();
		
		if ($cookie_user) {

		// find user id of cookie_user stored in application database
			$user = $this->login->cookie_login($cookie_user);
			
			//print_r($user);
			// set session if necessary
			if (!$this->session->userdata('user_id')) {
				$this->session->set_userdata('user_id', $user);
			}
			$this->user = $user;
		}
		else if ($this->session->userdata('user_id')) {
			
			$this->user = $this->session->userdata('user_id');
		}	
		//print_r($this->session->userdata);
		//print_r($this->session->userdata('user')->usertype);
	}
	
	function is_logged_in($flag = "")
	{	

		if($flag == "nologin")
		{

			if((isset($this->session->userdata(USER_SESSION_NAME)->usertype) && $this->session->userdata(USER_SESSION_NAME)->usertype =='u' && $this->session->userdata(USER_SESSION_NAME)->id>0))
			{
				
				redirect(base_url());
				return false;
				exit;
			}
			else{
				return true;
			}
		}
		else{
			if((isset($this->session->userdata(USER_SESSION_NAME)->usertype) && $this->session->userdata(USER_SESSION_NAME)->usertype=='u' && $this->session->userdata(USER_SESSION_NAME)->id>0))
			{
				
				/*Update session*/
				$this->load->model('ilogin');
				$result_ar = $this->ilogin->login('','',NULL,$this->session->userdata(USER_SESSION_NAME)->id);	
				/*Update session*/
				return true;
			}else{
				
				$red_url = base_url()."signin/?parenturl=".current_url();
				redirect($red_url);

				redirect(base_url());
				return false;
				exit;
			}
		}
	}
	
	public function is_admin_logged_in($module='')
	{
		
		//print_r($this->session->all_userdata());
		if(isset($this->session->userdata(ADMIN_SESSION_NAME)->usertype) && ($this->session->userdata(ADMIN_SESSION_NAME)->usertype=='a' || $this->session->userdata(ADMIN_SESSION_NAME)->usertype=='s'))
		{
			if($module!='' && $this->session->userdata(ADMIN_SESSION_NAME)->usertype=='s')
			{
				if($this->check_permission($module))
				{
					return true;
				}
				else
				{
					redirect(base_url().ADMIN_DIR.'/home', 'refresh');
				}
			}
			else
			{
				//print_r($this->session->all_userdata());
				return true;
			}
		}
		else
		{
			return false;
		}        
	}
	
	public function check_permission($module)
	{	
		$query="select id from site_modules where module='".$module."'";
		$module_id = $this->db->query($query)->row()->id;
		
		$query="select id from user_perm where module_id=".$module_id." and user_id=".$this->session->userdata('adminuser')->id."";
		
		if($this->db->query($query)->row()->id > 0)
		{
			
			return true;
		}
		else
		{
			return false;
		}
	}

    public function responseSuccess($data = [])
    {
        $this->result['data'] = $data;

        return $this->response($this->result);
    }

    public function responseError($message = 'Oops! Error!', $debug_data = [])
    {
        $this->result['error'] = true;
        $this->result['message'] = $message;
        $this->result['debug'] = $debug_data;

        return $this->response($this->result);
    }
	
}
