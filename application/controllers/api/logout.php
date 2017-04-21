<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Logout extends REST_Controller {

	function __construct()
	{
		parent::__construct();
	}
	public function loggout_post()
	{
		
			$this->session->unset_userdata('user');
                        session_start(); 
			session_destroy();	
			$data["loggedin"] = false;
			$data["last_login_time"] = date('Y-m-d H:i:s');
			$this->response($data, 200);
	}	
}
