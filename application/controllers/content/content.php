<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {

	function __construct()
	{
		parent::__construct();	
		$this->load->model('commonfn');	
	}

	function index($pageurl = NULL)
	{
		$pageurl = urldecode($pageurl);
		$pagecontent = $this->commonfn->page_content($pageurl);
		$allpages = $this->commonfn->page_content($pageurl,"allpages");
		$this->config->set_item('title',$pagecontent["title"]);
		$page_detail["ptitle"] = $pagecontent["title"];
		$page_detail["pdescription"] = $pagecontent['description'];		
		$page_detail["allpages"] = $allpages;
		$page_detail["cur_page"] = $pageurl;
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";		
		$template_arry['contentPanel']="content/content.html";	
		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$page_detail);

		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='content';
		$this->load->view('home',$a);
	}
	
}//class over

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */