<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends MY_Controller {


	function __construct()
	{
		parent::__construct();
		
		
	}

	public function index()
	{
		
			$this->session->unset_userdata('adminuser');
                        session_start(); 
	   session_destroy();
	   redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */