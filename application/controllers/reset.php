<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reset extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		 $this->load->model('Ilogin');
	}
	
	function index($key)
	{
		$db_exist_check = $this->Ilogin->check_fp_code_exist($key);	
		$this->config->set_item('title','Manage Roles');	
		if($db_exist_check == "false")
		{
				redirect(base_url(), 'refresh');
		}		
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		
		$m[]=array("img_url"=>img_url()."vedio-img1.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img2.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img3.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img4.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img2.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img5.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img3.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."img4.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		$m[]=array("img_url"=>img_url()."vedio-img1.jpg","wave_img"=>img_url().'hover-wave-img.png',"profile_img"=>img_url().'profile-img.png');
		
		$left_panel = false;
		$username = null;
		
		$data = array(
			'news'=>$m,
			'fp_code' => 	$key	
		);
				
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="sign_in/reset.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		//$a['redirectURL']=base_url();
		$a['current_tm']='reset';
		$this->load->view('home',$a);
	}	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */