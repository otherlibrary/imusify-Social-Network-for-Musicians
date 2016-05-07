<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class sign_up_mail extends MY_Controller {

	function __construct()
	{
		parent::__construct("","nologin");
	}

	function index()
	{
		$m[]=array("img"=>img_url()."vedio-img1.jpg");
		$m[]=array("img"=>img_url()."vedio-img2.jpg");
		$m[]=array("img"=>img_url()."img3.jpg");
		$m[]=array("img"=>img_url()."img4.jpg");
		$m[]=array("img"=>img_url()."vedio-img2.jpg");
		$m[]=array("img"=>img_url()."vedio-img5.jpg");
		$m[]=array("img"=>img_url()."img3.jpg");
		$m[]=array("img"=>img_url()."img4.jpg");
		$m[]=array("img"=>img_url()."vedio-img1.jpg");
		$data = array(
			'news'=>$m
		);
		
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="sign_up/sign_up_mail.html";
		
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$data);

		$a['data'] = $data1;
		$a['current_tm']='sign_up_mail';
		
		$this->load->view('home',$a);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */