<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class account extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
	}

	function index($action = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		if($action == "connect")
		{
			$this->load->model('stripe_operation');
			$response = $this->stripe_operation->user_stripe_connect_check();
			/*print_r($response);exit;*/
			if($response["status"] == "success")
			{
				$data = array(
					'status'=>'connected',
					"stripe_connect" => "true"
				);
				$temp_name = "account/stripe_connected.html";
			} 
			else if($response["status"] == "error")
			{
				$temp_name = "account/stripe_connect.html";
				$url = base_url()."stripeoperations/redirect_stripe";
				$data = array(			
					'stripe_connect_url' => $url,
					'status'=>'notconnected',
					"stripe_connect" => "true"
				);
			}
		}
		else
		{
			$temp_name = "account/change_password.html";
			$overview_active = true;
			$data = array(			
				'home_page' => "true",
				"change_password" => "true"
				);
		}
		$template_arry['MainPanel'] = "main.html";
		$template_arry['leftPanel'] = "left_panel.html";
		$template_arry['rightPanel'] = "right_panel.html";
		$template_arry['contentPanel'] = "account/main.html";
		$template_arry['templaterow'] = $temp_name;		
		$template_arry['playerPanel'] = "player_panel.html";
		$data1=get_template_content($template_arry,$data);

		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */