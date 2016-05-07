<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class setup extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		$this->load->model('user_profile');	
		//echo $this->is_logged_in();		
	}

	function index()
	{
		//echo "a";
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
		
		$roles_array =  $this->user_profile->get_user_roles();
		//print_r($roles_array);
		$left_panel = true;
		$username = $this->session->userdata('user')->username;
		
		$user_selected_array = $this->user_profile->user_db_roles("roles");
		
		//admin set default roles
		$user_def_db_roles = $this->user_profile->get_user_roles("is_default='y'","","true");
		//print_r($user_def_db_roles);			
		$new_array = array();	
		$i = 0;
		
				foreach($roles_array as $roles)
				{
					$roles['selected'] = false;
					$roles['default'] = false;
					
					if(!empty($user_selected_array) && in_array($roles["id"],$user_selected_array))
					{
						$roles['selected'] = true;
					}
					
					//set default array of roles and set it as default
					if(!empty($user_def_db_roles) && in_array($roles["id"],$user_def_db_roles))
					{
						
						$roles['default'] = true;
						$roles['selected'] = true;	
					}
					
					array_push($new_array,$roles);
				}
		
		$data = array(	
			'news'=>$m,	
			'roles' => 	$new_array,
			'u_db_roles' => 	$user_selected_array	
		);
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="setup/role.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		
		$data1=get_template_content($template_arry,$data);

		$a['data'] = $data1;
		$a['current_tm']='setup';
		//$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */