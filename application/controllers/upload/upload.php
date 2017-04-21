<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends MY_Controller {
	function __construct()
	{                
		//parent::__construct("","front");
                parent::__construct();
		$this->load->model('commonfn');
		$this->load->model("uploadm");
		$this->def_rec_dis_m = 2;	
		//echo 'test';exit;
	}

	function index($action = "",$page = NULL)
	{                            
		$this->config->set_item('title','Upload');
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
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
			$start_limit = (($page - 1)*$this->def_rec_dis_m) ;	

		$genre_list	 = $this->commonfn->get_genre("type='p'");
		$sec_genre_list = $this->commonfn->get_genre("type='s'");
                $sound_like_list = $this->commonfn->get_soundlike();
                //var_dump ($sound_like_list);exit;
		$moods_list = $this->commonfn->get_moods();
		$session_user_id = $this->session->userdata('user')->id;
		$albumform_dis_class = "displaynone";
		if($action == "album")
		{	
			$total_records = $this->uploadm->fetch_user_albums("a.userId = '".$session_user_id."'",null,86,86,'counter');

			if($total_records > $this->def_rec_dis_m)
			{
				$loadmore = "true";
				$load_url = "upload/album";
				$load_cont = "#my_album_row";
				$load_tmpl = "albumListrow";
				$load_extra_class = "upload_album_loadmore";

			}else{
				$loadmore = "";
			}

			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->def_rec_dis_m;					
				$last_page = ceil($total_records/$this->def_rec_dis_m);	
				$data_array = $this->uploadm->fetch_user_albums("a.userId = '".$session_user_id."'",$new_limit,86,86);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
				echo json_encode( $final_array );
				exit;
			}
			$temp_name = "upload/album_list.html";
			$data_array = $this->uploadm->fetch_user_albums("a.userId = '".$session_user_id."'",$this->def_rec_dis_m,86,86);	
			$tabid = "my_album_row";
			$class = "albums";
			if($page == "create")
				$albumform_dis_class = "display";

			$album_active = 'active';
		}else{

			$total_records = $this->uploadm->fetch_user_tracks("tt.userId = '".$session_user_id."' AND tt.albumId = '0'",NULL,NULL,100,150,'counter');
                        
			/*dump($total_records);exit();*/
			/*echo $total_records;*/

			if($total_records > $this->def_rec_dis_m)
			{
				$loadmore = "true";
				$load_url = "upload/audio";
				$load_cont = "#my_songs_row";
				$load_tmpl = "uploaded_song_row";
			}else{
				$loadmore = "";
			}

			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->def_rec_dis_m;	
				$last_page = ceil($total_records/$this->def_rec_dis_m);	                                
				$data_array = $this->uploadm->fetch_user_tracks("tt.userId = '".$session_user_id."' AND tt.albumId = '0' ",$new_limit,NULL,86,86);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
				echo json_encode( $final_array );
				exit;
			}	
                        //fetch list of uploaded songs/tracks for Upload page
			$temp_name = "upload/uploaded_song_row.html";
			$data_array = $this->uploadm->fetch_user_tracks("tt.userId = '".$session_user_id."' AND tt.albumId = '0'",$this->def_rec_dis_m);
			$tabid = "my_songs_row";
			$class = "audiotab";
			$music_active = 'active';
		}
		//var_dump($data_array);exit;
		$track_detail = array(			
			'cover_image' =>img_url()."cover-img.png",
			'genre_list' => $genre_list	,
			'sec_genre_list' => $sec_genre_list,
                        'sound_like_list' => $sound_like_list,
			"upload_page" => "true",
			'data_array' => $data_array,
			'tabid' => $tabid,
			'class_nm' => $class,
			'albumform_dis_class'=>$albumform_dis_class	,
			'music_active' => $music_active,
			'album_active' => $album_active,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'load_extra_class'=>$load_extra_class                        
			);

		$template_arry['MainPanel'] = "main.html";
		$template_arry['leftPanel'] = "left_panel.html";
		$template_arry['rightPanel'] = "right_panel.html";		
		
		$template_arry['contentPanel'] = "upload/upload.html";
		$template_arry['uploadtabRender'] = "upload/tab_render.html";
		
		if($action == "album")
			$template_arry['albumListrow'] = "upload/album_track_list_row.html";
		
		$template_arry['arrayData'] = $temp_name; 

		if($loadmore == true)	
			$template_arry['loadMore'] = "general/loadmore.html";		

		$template_arry['playerPanel'] = "player_panel.html";
		$data1=get_template_content($template_arry,$track_detail);
                //var_dump ($data1);exit;
		$a['data'] = $data1;
                
		$a['redirectURL']=base_url();
		$a['current_tm']='';
		$this->load->view('home',$a);
	}
	


	function albumedit($id){
		$userId = $this->session->userdata('user')->id;
		$is_exist = getvalfromtbl("id","albums","id='".$id."' AND userId = '".$userId."'");
		if($is_exist)
		{
			if($userId > 0)
			{
				$data = $this->uploadm->fetch_info($id,'a');
				$this->config->set_item('title','Edit Album');
			/*	dump($data);*/
				$template_arry['MainPanel']="main.html";
				$template_arry['leftPanel']="left_panel.html";
				$template_arry['popUpContent']="upload/album_edit.html";		
				$template_arry['rightPanel']="right_panel.html";
				$template_arry['contentPanel']="upload/upload.html";
				$template_arry['playerPanel']="player_panel.html";
				$template_arry['bigPlayerPanel']="big_player.html";
				$a["albumform_dis_class"] = 'displaynone';
				$a['data']=get_template_content($template_arry,$data);
				$a['redirectURL']=base_url();
				$a['current_tm']='albumedit';
				$this->load->view('home',$a);
			}
			else{
				redirect("","refresh");
			}
		}
		else{
			redirect("","refresh");
		}
	}

	function trackedit($id){
		$userId = $this->session->userdata('user')->id;
                //var_dump($userId);exit();
		$is_exist = getvalfromtbl("id","tracks","id='".$id."' AND userId = '".$userId."'");
		if($is_exist)
		{
			if($this->session->userdata('user')->id > 0)
			{
				$data = $this->uploadm->fetch_info($id,'t');
                                				
				$this->config->set_item('title','Edit Track');
				$template_arry['MainPanel']="main.html";
				$template_arry['leftPanel']="left_panel.html";
				$template_arry['popUpContent']="upload/trackedit.html";		
				$template_arry['rightPanel']="right_panel.html";
				$template_arry['contentPanel']="upload/upload.html";
				$template_arry['playerPanel']="player_panel.html";
				$template_arry['bigPlayerPanel']="big_player.html";
				$a['data']=get_template_content($template_arry,$data);
				$a['redirectURL']=base_url();
				$a['current_tm']='trackedit';
				$this->load->view('home',$a);
			}
			else{
				redirect("","refresh");
			}
		}
		else{
			redirect("","refresh");
		}




	}

}