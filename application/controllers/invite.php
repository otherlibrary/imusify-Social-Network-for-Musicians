<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class invite extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		//$this->load->model('user_profile');	
		//echo $this->is_logged_in();		
	}

	function index()
	{	

		$this->load->model("invitation");
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		$left_panel = true;
		$username = $this->session->userdata('user')->username;
		$i = 0;

		
		$data = array();
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="invite/invite.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";

		//$data["codes"] = $this->invitation->get_invitation_code();
		
		$data["invite_code"] = $this->invitation->get_user_invitation_code();
		/*echo $data["invite_code"];
		exit;*/
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['current_tm']='invite';
		//$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */