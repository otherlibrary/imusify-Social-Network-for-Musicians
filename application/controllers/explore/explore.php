<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class explore extends MY_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->model('browse_recommended');
		$this->load->model('commonfn');
		$this->rec_to_dis = 10;
	}

	function index($action = "",$page = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		
		if($this->session->userdata('user')->id > 0)
		{
			$left_panel = true;
			$username = $this->session->userdata('user')->username;
		}
		else
		{	$left_panel = false;$username = null;}		
		
		
		if($page != "" && $page > 0)
			$start_limit = (($page - 1)*$this->rec_to_dis) ;	


		/*if($action == "new-songs")
		{

			$total_records = $this->browse_recommended->fetch_new_tracks("","","","counter");

			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_new_tracks("",$new_limit,"","",$start_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

			$data_array = $this->browse_recommended->fetch_new_tracks("",$this->rec_to_dis);
			$temp_name = "general/browse_rec_music_row.html";//name of temp
			$tabid = "new-songs";
			$class = "browse-songs";
			$active_class = "active";
			$songs_ul = "true";
			$new_songs_active = "new_songs_active";
			$header_tmp = "true";	
			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/new-songs";
				$load_cont = "#new-songs";
				$load_tmpl = "browse_pop_new_songs";
			}else{
				$loadmore = "";
			}
		}
		else if($action == "popular-artist")
		{

			$total_records = $this->browse_recommended->fetch_popular_artist("","","","counter");

			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_popular_artist("",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

			$data_array = $this->browse_recommended->fetch_popular_artist("",$this->rec_to_dis);
			$temp_name = "browse/browse_rec_pop_artist_row.html";
			$tabid = "pop-artists";
			$class = "video-list";
			$popular_artist_active = "popular_artist_active";

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/popular-artist";
				$load_cont = "#pop-artists";
				$load_tmpl = "browse_popartist";
			}else{
				$loadmore = "";
			}			
		}
		else if($action == "new-artists")
		{

			$total_records = $this->browse_recommended->fetch_new_artist("","","","counter");
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_new_artist("",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
			$data_array = $this->browse_recommended->fetch_new_artist("",$this->rec_to_dis);
			$temp_name = "browse/browse_rec_pop_artist_row.html";
			$tabid = "new-artists";
			$class = "video-list";	
			$new_artist_active = "new_artist_active";	

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/new-artists";
				$load_cont = "#new-artists";
				$load_tmpl = "browse_popartist";
			}else{
				$loadmore = "";
			}
		}
		else if($action == "popular-playlist")
		{


			$total_records = $this->browse_recommended->fetch_popular_playlist("","","","counter");
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_popular_playlist("",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

			$data_array = $this->browse_recommended->fetch_popular_playlist("",$this->rec_to_dis);
			$temp_name = "browse/browse_rec_pop_artist_row.html";
			$tabid = "pop-artists";
			$class = "video-list";		
			//$tabid = "new-artists";
			$popular_playlist_active = "popular_playlist_active";

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/popular-playlist";
				$load_cont = "#pop-artists";
				$load_tmpl = "browse_popartist";
			}else{
				$loadmore = "";
			}
		}
		else if($action == "new-playlist")
		{

			$total_records = $this->browse_recommended->fetch_new_playlist("","","","counter");
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_new_playlist("",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

			$data_array = $this->browse_recommended->fetch_new_playlist("",$this->rec_to_dis);
			$temp_name = "browse/browse_rec_pop_artist_row.html";
			$tabid = "new-playlist";
			$class = "video-list";		
			//$tabid = "new-artists";
			$new_playlist_active = "new_playlist_active";

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/new-playlist";
				$load_cont = "#new-playlist";
				$load_tmpl = "browse_popartist";
			}else{
				$loadmore = "";
			}
		}
		else
		{*/
			
			$this->config->set_item('title','Explore');
			$total_records = $this->browse_recommended->fetch_popular_tracks("","","","counter");

			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;					
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->browse_recommended->fetch_popular_tracks("",$new_limit,"","",$start_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

			$temp_name = "general/browse_rec_music_row.html";
			$data_array = $this->browse_recommended->fetch_popular_tracks("",$this->rec_to_dis);
			$tabid = "popular-songs";
			$class = "browse-songs";
			$songs_ul = "true";
			$action = "popular-songs";
			//$cls_active = "popular_song_active";
			$popular_songs_active = "popular_songs_active";
			$header_tmp = "true";

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = "explore/popular-songs/";
				$load_cont = "#popular-songs";
				$load_tmpl = "browse_pop_new_songs";
			}else{
				$loadmore = "";
			}
		/*}*/


		/*$new_songs_active = isset($new_songs_active) ? $new_songs_active : "";
		$popular_artist_active = isset($popular_artist_active) ? $popular_artist_active : "";
		$new_artist_active = isset($new_artist_active) ? $new_artist_active : "";
		$popular_playlist_active = isset($popular_playlist_active) ? $popular_playlist_active : "";
		$new_playlist_active = isset($new_playlist_active) ? $new_playlist_active : "";
		$popular_songs_active = isset($popular_songs_active) ? $popular_songs_active : "";*/
		
		$genres = $this->commonfn->get_genre("type='p'",$this->rec_to_dis);
		$explore_genres = $genres;
		$moods_list = $this->commonfn->get_moods();
		$instruments_list = $this->commonfn->get_instuments();

		$recom_Arr = array(
			'playsets' => $p,
			'recently_listened' => $rl,
			'username'=>$username,
			'user_type' => "user",
			"explore_songs" => $explore_songs,
			'data_array' => $data_array,
			'tabid' => $tabid,
			'class_nm' => $class,
			'songs_ul' => $songs_ul,
			'cls_active' => $cls_active,
			/*'new_songs_active' => $new_songs_active,
			'popular_artist_active' => $popular_artist_active,
			'new_artist_active' => $new_artist_active,
			'popular_playlist_active' => $popular_playlist_active,
			'new_playlist_active' => $new_playlist_active,
			'popular_songs_active' => $popular_songs_active,*/
			'popular_users' => $popular_users,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'header_tmp' => $header_tmp,
			'genres' => $genres,
			'explore_genres' => $explore_genres,
			'moods_list' => $moods_list,
			'instruments_list' => $instruments_list
		);
		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['tabRender']="general/tab_render.html";	

		/*if($action == "new-songs" || $action == "popular-songs")
		{*/			
		$template_arry['profileHeader'] = "browse/browse_music_header.html";
		//}	
		
		$template_arry['arrayData'] = $temp_name; 

		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";
		
		$template_arry['tagsManage']="explore/tags.html";
		$template_arry['contentPanel']="explore/explore.html";
	
		$template_arry['playerPanel']="player_panel.html";
		$data1=get_template_content($template_arry,$recom_Arr );
		
		$a['data'] = $data1;
		$a['redirectURL']=base_url();
		$a['current_tm']='recomended';
		$this->load->view('home',$a);
		
	}
	
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */