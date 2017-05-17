<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("about_model");
	}

	function index()
	{
		$array = $this->about_model->get_about_detail();
		$array_counter = $this->about_model->get_counter();
		$this->config->set_item('title','About');
		$data = array();
		$data["team_members"] = $array["team"];
		$data["industry_professional"] = $array["ip"];
		
		$data["minute_uploaded"] = nice_number($array_counter["total_time"]);
		$data["total_tracks"] = nice_number($array_counter["total_tracks"]);
		$data["likes"] = nice_number($array_counter["total_track_like"]);
		$data["playlists"] = nice_number($array_counter["total_playlist"]);
		$data["purchase"] = 0;		
		/*dump($data);*/
		$template_arry['MainPanel'] = "main.html";
		$template_arry['leftPanel'] = "left_panel.html";
		$template_arry['popUpContent'] = "about/about.html";
		$template_arry['teamRow'] = "about/team_row.html";
		$template_arry['industry_professional_row'] = "about/industry_professional_row.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="headlines.html";
		$template_arry['newsRow']="news_row.html";
		$template_arry['playerPanel']="player_panel.html";						
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$data);
		$a['data'] = $data1;
		$a['current_tm']='about';
		$this->load->view('home',$a);
		
		
	}
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */