<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Liked extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");
		$this->load->model("liked_model");
		$this->rec_to_dis = 2;	
	}

	function index($action = "",$page = NULL)
	{
		$loadmore = NULL;
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		$this->config->set_item('title','Favourites');
		if($this->session->userdata('user')->id > 0)
		{
			$left_panel = true;
			$username = $this->session->userdata('user')->username;
		}
		else
		{	
			$left_panel = false;
			$username = null;
		}		
		if($page != "" && $page > 0)
			$start_limit = (($page - 1)*$this->rec_to_dis) ;	

		$session_user_id = $this->session->userdata('user')->id;
		$total_records = $this->liked_model->fetch_user_liked_tracks(NULL,NULL,NULL,NULL,NULL,"counter");

		$my_liked_musics = $this->liked_model->fetch_user_liked_tracks(NULL,NULL,$this->rec_to_dis);

		if($total_records > $this->rec_to_dis)
		{
			$loadmore = "true";
			$load_url = "liked";
			$load_cont = ".my_liked_songs";
			$load_tmpl = "liked_uploaded_song_row";
		}else{
			$loadmore = "";
		}	

		if($page != NULL && $page > 0)
		{
			$new_limit = $start_limit.",".$this->rec_to_dis;					
			$last_page = ceil($total_records/$this->rec_to_dis);	
			$data_array = $this->liked_model->fetch_user_liked_tracks(NULL,NULL,$this->rec_to_dis,$start_limit);
			$final_array["data"] = $data_array;	
			$final_array["page"] = $page+1;
			$final_array["last_page"] = $last_page;
			header('Content-Type: application/json');
			echo json_encode( $final_array );
			exit;
		}

		//dump($my_liked_musics);
		$liked_detail = array(			
			'my_liked_musics' => $my_liked_musics,
			"liked_page" => "true",
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,		
			);
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";	

		$template_arry['songsRow']="liked/liked_uploaded_song_row.html";
		$template_arry['songslistRow']="liked/liked_track_list_row.html";

		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";


		$template_arry['contentPanel']="liked/liked.html";		
		
		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$liked_detail);
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='';
		$this->load->view('home',$a);
	}
	
}//class over

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */