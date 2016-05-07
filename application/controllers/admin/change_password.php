<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Change_password extends MY_Controller {


	function __construct()
	{
		parent::__construct();
		
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');			
		}*/
		
		if(!$this->is_admin_logged_in())
		{			
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}
		
	}

	public function index()
	{
		
			$data=array();
			add_asset_js('js/admin-cp.js');
			$this->template->set('nav', 'Dashboard');
			$this->template->set('nav_sub', 'dashboard1');
			$this->template->set('title', 'Dashboard-'.SITE_NM);
			$this->template->load_main('change_password');
	   
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */