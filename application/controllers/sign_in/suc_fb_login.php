<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suc_fb_login extends MY_Controller {

	function __construct()
	{
		parent::__construct();
	}
	
	function index()
	{
		
		$this->load->view('temp');
	}	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */