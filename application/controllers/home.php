<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('browse_recommended');
		$this->load->model('commonfn');
		$this->load->model('home_modal');

		$this->rec_to_dis = 10;
	}

	function index($action = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		if($action == "music")
		{
			$records = $this->browse_recommended->fetch_new_tracks("",$this->rec_to_dis,NULL,NULL,NULL,373,373,'','',38,38);	
			$temp_name = "home/music_row.html";
			$music_active = true;	
			$this->config->set_item('title','Music');				
		}
		else if($action == "articles")
		{
			$this->config->set_item('title','Articles');
		}
		else if($action == "news")
		{
			$this->config->set_item('title','News');
		}
		else if($action == "instrumental")
		{
			$records = $this->browse_recommended->fetch_instrumental("",$this->rec_to_dis,NULL,NULL,NULL,373,373,'',38,38);	
			$temp_name = "home/music_row.html";
			$instrument_active = true;
			$this->config->set_item('title','Instrumental');
		}
		else if($action == "license")
		{
			$records = $this->browse_recommended->fetch_new_tracks("tt.license='y'",$this->rec_to_dis,NULL,NULL,NULL,373,373,'','',38,38);	
			$temp_name = "home/music_row.html";
			$license_active = true;
			$this->config->set_item('title','Licence');
		}
		else
		{
			$loadmore = "true";
			$load_url = "home/";
			$load_cont = "#all-items-masonary";
			$load_tmpl = "home_article_row,home_music_row";

			/*$records = $this->browse_recommended->fetch_new_tracks("",$this->rec_to_dis,NULL,NULL,NULL,373,373,'','',38,38);	*/
			$records = $this->home_modal->get_overview("",$this->rec_to_dis,NULL,NULL,NULL,373,373,'','',38,38);	
			/*echo "<pre>";
			print_r($records);
			echo "</pre>";
			exit();*/
			$template_arry['tabRender']= "home/tab_render.html";		
			$template_arry['articleRow'] = "home/article_row.html";
			$template_arry['trackRow'] = "home/music_row.html";

			$overview_active = true;
			$this->config->set_item('title','Overview');
		}

		//$notification = $this->home_modal->get_my_notification("",$this->rec_to_dis);
		//dump($notification);
		$data = array(			
			'records'=>$records,
			'music_active' => ($music_active) ? $music_active : false,
			'instrument_active' => ($instrument_active) ? $instrument_active : false,
			'overview_active' => ($overview_active) ? $overview_active : false,
			'license_active' => ($license_active) ? $license_active : false,			
			'all_news_active' => $all_news_active,
			'home_page' => "true",
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont
			);
		$template_arry['MainPanel'] = "main.html";
		$template_arry['leftPanel'] = "left_panel.html";
		$template_arry['rightPanel'] = "right_panel.html";
		//$template_arry['bigPlayerPanel']="big_player.html";
		$template_arry['contentPanel'] = "headlines.html";
		//$template_arry['notificationRow']="notification_row.html";
		//$template_arry['newsRow']="news_row.html";	

		if(isset($temp_name) && $temp_name != null)	
			$template_arry['recordsRow']=$temp_name;		
		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";


		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$data);

		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$this->load->view('home',$a);
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */