<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {
	
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
		//$this->load->model("common");
		
		
	}

	public function index()
	{		
			
			$data=array();
			$data["users"] = getvalfromtbl("count(*)","users","","single");
			$this->template->set('nav', 'Dashboard');
			$this->template->set('nav_sub', 'dashboard1');
			$this->template->set('title', 'Dashboard-'.SITE_NM);
			$this->template->load_main('home',$data);	   	
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */