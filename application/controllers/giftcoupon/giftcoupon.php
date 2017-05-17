<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class giftcoupon extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		$this->load->model('Membership_model');
		$this->load->library("braintree_lib");			
	}

	function index()
	{
		/*$cur_date = date('Y-m-d');
		echo $cur_date;
		$date = new DateTime($cur_date);
		$date->modify("+6 month");
		dump($date);
		//echo " A ".$date->format('l, F jS, Y');
		echo $date->date;
		exit;*/
		$data = array();		
		$this->config->set_item('title','Gift Coupon');
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="giftcoupon/giftcoupon.html";		
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";						
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['current_tm']='giftcoupon';
		$this->load->view('home',$a);
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */