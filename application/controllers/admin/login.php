<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends MY_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	 function __construct()
	{
		parent::__construct();
		if($this->is_admin_logged_in())
		{
			redirect(base_url().ADMIN_DIR.'/home', 'refresh');
		}
		
	}

	public function index()
	{
		
			$data=array();
			$this->template->set('title', 'Login-'.SITE_NM);
			add_js(array('neon-login.js'));
			$this->template->load(ADMIN_DIR.'/template1',ADMIN_DIR.'/login');
		
		
	}
	
	public function reset_password()
	{
		
			$data=array();
			$fp_code=$this->uri->segment(4);
			$data['fp_code']=$fp_code;
			$this->template->set('title', 'Reset Password-'.SITE_NM);
			add_js(array('neon-login.js'));
			$this->template->load(ADMIN_DIR.'/template1',ADMIN_DIR.'/reset_password',$data);
		
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */