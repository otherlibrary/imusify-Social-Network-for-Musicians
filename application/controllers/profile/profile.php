<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('user_profile');
		$this->rec_to_dis = 3;
		$this->sess_id = $this->session->userdata('user')->id;
		
	}

	function index($profileLink,$action = "",$page = "")
	{
		//echo " ".$profileLink." ".$action." ",$page;exit;
		//$profileLink = sanitize($profileLink);
		//$action = sanitize($action);
		//$page = sanitize($page);
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		$this->config->set_item('title','Profile');
		if($this->session->userdata('user')->id > 0)
		{
			$left_panel = true;
			$username = $this->session->userdata('user')->username;
		}
		else
		{	$left_panel = false;$username = null;}
		
		$user_profile_type = "user";
		//$user_profile_type = "artist";
		
		$user_db_details = $this->user_profile->get_user_details($profileLink);
		/*dump($user_db_details);*/
		if($user_db_details == "usernotexist")
		{
			redirect(base_url(), 'refresh');
		}
	
		if($page != "" && $page > 0)
			$start_limit = (($page - 1)*$this->rec_to_dis) ;
	
		if($user_profile_type == "user")
			$a['data'] = $this->user_profile($profileLink,$user_db_details,$action,$page,$start_limit);
		else if($user_profile_type == "artist")
			$a['data'] = $this->artist_profile($profileLink,$user_db_details,$action,$page,$start_limit);
		
		$a['redirectURL']=base_url();
		$a['current_tm']='profile';
		$this->load->view('home',$a);
	}
	
        function strpos_all($haystack, $needle) {
            $offset = 0;
            $allpos = array();
            while (($pos = strpos($haystack, $needle, $offset)) !== FALSE) {
                $offset   = $pos + 1;
                $allpos[] = $pos;
            }
            return $allpos;
        }

        //rewrite description for hyperlinks
        function description_hyperlink($description){                        
            $number_of_links = intval(floor(substr_count($description, '.') / 2));     
            $description = $this->remove_end_dot($description);
            //var_dump($description);exit;
            //var_dump ($description, $number_of_links);exit;
            for ($i=0; $i< $number_of_links;$i++){
                $all_dot_position_array = $this->strpos_all($description, '.');                
                $description = $this->change_hyperlink($description, $all_dot_position_array[$i*4]);
                //var_dump ('end of round ', $description);
            }
            //exit;
            //change \n to <br /> html
            $description = ereg_replace( "\n",'<br />', $description);            
            //var_dump ($description);exit;
            return $description;
        }
        
        function remove_end_dot($description){
            $all_dot_position_array = $this->strpos_all($description, '.'); 
            $arr_temp = str_split($description);
            for($i=0; $i < count($all_dot_position_array); $i++){
                if ($arr_temp[$all_dot_position_array[$i] -1] != ' ' && $arr_temp[$all_dot_position_array[$i] + 1] == ' ') 
                    $arr_temp[$all_dot_position_array[$i]] = '';    
            }
            $temp = '';
            for($i=0; $i < count($arr_temp); $i++){
                $temp = $temp.$arr_temp[$i];
            }
            return $temp;            
        }
        
        function change_hyperlink($description, $start){
            $length = strlen($description);
            $dot1 = strpos($description, '.', $start);
            //var_dump('dot1 ', $dot1);
            if ($dot1){
                $temp = substr($description, 0, $dot1);
                
                $space1 = strrpos($temp, ' ');
                //var_dump('temp ', $temp, $space1);
                if (! $space1) {
                  if (substr($temp, 0, 1) != ' ') $space1 = -1;  
                } 
                
                //var_dump ('start of search ', $start, $temp, $space1,$dot1);                      
                $temp = substr($description, $dot1 + 1);
                $dot2 = strpos($temp, '.');
                if ($dot2){
                    $temp = substr($description, $dot1 + $dot2+ 1);
                    $space2 = strpos($temp, ' ');
                    //var_dump ('space2 dot2', $space2, $dot2);
                    //if (! $space2) $space2 = 0; 
                    if (! $space2){
                        if (substr($description, $length-1, 1) != ' ') $space2 = strlen($temp);  
                    }
                    //var_dump ('space2', $space2);
                    $link = substr($description, $space1+ 1, $dot1+ $dot2+ $space2 - $space1);
                    $link = trim($link);

                    if (stristr($link, 'http://')){
                      $description = str_replace($link, '<a href="'.$link.'" target="_blank">'.$link.'</a>', $description);    
                    } else{
                      $description = str_replace($link, '<a href="http://'.$link.'" target="_blank">'.$link.'</a>', $description);    
                    }
                    //var_dump ($space1, $dot1+ $dot2+ $space2, $description, $link);                                                                      
                }
            }
            return $description;
        }
        
        
	function user_profile($profileLink,$user_db_details,$action,$page,$start_limit = NULL)
	{
		$album_text = "ALBUM";
		$profile_link_uid = getvalfromtbl("id","users","profileLink = '".$profileLink."'","single","");
				
		if($this->sess_id == $profile_link_uid)
		{
			//user is vieving own profie
			$my_profile = "true";	
			$other_profile = "";			
		}else if($this->sess_id > 0){
			//user is viewing another users profile
			$other_profile = "true";
			$is_following = getvalfromtbl("id","followinglog"," fromId = '".$this->sess_id."'  AND toId = '".$profile_link_uid."'","single","");
			
			if($is_following > 0)
			{
				$following = "true";
				$follow = "";
			}
			else{
				$following = "";
				$follow = "true";
			}			
		}
		else{
			//user is not logged
			$my_profile = "";	
			$other_profile = "true";
			$following = "";
			$follow = "true";
		}		
		
		$temp_name = "profile/recent_listen_row.html";
		
		if($action == "uploaded-songs")
		{
			$this->config->set_item('title','Uploaded Songs');
			$data_array = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",$this->rec_to_dis);
			$temp_name = "profile/artist_popular_row.html";
			$class_nm = "browse-songs prof_feed";
			$tabid = "prof_feed";
			$header_tmp = "true";
			$upload_active_class = "feed";
			$total_records = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/feed/";
				$load_cont = "#prof_feed";
				$load_tmpl = "profile_artist_feed";
			}else{
				$loadmore = "";
			}

			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",$new_limit,'','',$start_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}			
		}
                else if($action == "feeds")
		{
			$this->config->set_item('title','Feeds');
			$data_array = $this->user_profile->fetch_feeds("u.id = '".$profile_link_uid."'",$this->rec_to_dis);
			$class_nm = "vedio-songs-list";
			$tabid = "feeds";
			//class vedio-songs-list
			$album_active_class = "albums";
			$total_records = $this->user_profile->fetch_feeds("u.id = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/albums/";
				$load_cont = "#feeds";
				$load_tmpl = "profile_recent_listen";
			}else{
				$loadmore = "";
			}
			//var_dump($page);exit;
			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_feeds("u.id = '".$profile_link_uid."'",$new_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
		}
		else if($action == "albums")
		{
			$this->config->set_item('title','Albums');
			$data_array = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",$this->rec_to_dis);
                        //var_dump($data_array);exit;
			$class_nm = "vedio-songs-list";
			$tabid = "albums";
			//class vedio-songs-list
			$album_active_class = "albums";
			$total_records = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/albums/";
				$load_cont = "#albums";
				$load_tmpl = "profile_recent_listen";
			}else{
				$loadmore = "";
			}
			
			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",$new_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
		}
		else if($action == "followers")
		{
			$this->config->set_item('title','Followers');
			$data_array = $this->user_profile->fetch_followers("fl.toId = '".$profile_link_uid."'",$this->rec_to_dis);
			$class_nm = "vedio-songs-list";
			$tabid = "followers";
			
			$followers_active_class = "followers";
			$total_records = $this->user_profile->fetch_followers("fl.toId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/followers/";
				$load_cont = "#followers";
				$load_tmpl = "profile_recent_listen";
			}else{
				$loadmore = "";
			}

			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_followers("fl.toId = '".$profile_link_uid."'",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
		}
		else if($action == "followings")
		{
			$this->config->set_item('title','Followings');
			$data_array = $this->user_profile->fetch_followings("fl.fromId = '".$profile_link_uid."'",$this->rec_to_dis);
			$class_nm = "vedio-songs-list";
			$tabid = "following";
			$followings_active_class = "followings";
			$total_records = $this->user_profile->fetch_followings("fl.fromId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/followings/";
				$load_cont = "#following";
				$load_tmpl = "profile_recent_listen";	
			}else{
				$loadmore = "";
			}

			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_followings("fl.fromId = '".$profile_link_uid."'",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}

		}
		else{
			$this->config->set_item('title','Listened Songs');
			$data_array = $this->user_profile->fetch_listened_tracks("pl.userId = '".$profile_link_uid."'",$this->rec_to_dis);
			$class_nm = "vedio-songs-list video-list";
			$tabid = "listened-songs";
			$action = "listened-songs";
			$listen_active_class = "listened-songs";
			$total_records = $this->user_profile->fetch_listened_tracks("pl.userId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/listened-songs/";
				$load_cont = "#listened-songs";
				$load_tmpl = "profile_recent_listen";					
			}else{
				$loadmore = "";
			}

			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_listened_tracks("pl.userId = '".$profile_link_uid."'",$new_limit);
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
		}
		
		$p = $this->user_profile->get_my_playlists(null,null,null,$profile_link_uid);

		$upload_active_class = isset($upload_active_class) ? $upload_active_class : "";
		$album_active_class = isset($album_active_class) ? $album_active_class : "";
		$followers_active_class = isset($followers_active_class) ? $followers_active_class : "";		
		$followings_active_class = isset($followings_active_class) ? $followings_active_class : "";
		$listen_active_class = isset($listen_active_class) ? $listen_active_class : "";
		
		$this->load->model('commonfn');
		$profile_image = $this->commonfn->get_photo('p',$profile_link_uid,157,157);
		$profile_cover_image = $this->commonfn->get_photo('pc',$profile_link_uid);
		//echo $profile_image;exit; 
		
		if($user_db_details["gender"] == "m")
			$gender_nm = "Male";
		else if($user_db_details["gender"] == "f")
			$gender_nm = "Female";
			
		$follower_text = "FOLLOWER";
		$song_text = "SONG";
		$albums_text = "ALBUM";
			
		if($user_db_details["followers"] > 1)
			$follower_text = "FOLLOWERS";

		if($user_db_details["tracks"] > 1)
			$song_text = "SONGS";

		if($user_db_details["albums"] > 1)
			$album_text = "ALBUMS";
                
                $description = $user_db_details["description"];
                
                
                //find all link and add anchor tag for it                                
                //only support one link
                //$description = $this->description_hyperlink($description);                
                //var_dump ($description);exit;
                 
                $description = html_entity_decode($description);
                
		$user_profile = array(
			'no_of_followers'=>$user_db_details["followers"],
			'no_of_following' => $user_db_details["following"],
			'no_of_songs' => $user_db_details["tracks"],
			'no_of_albums' => $user_db_details["albums"],	
			'follower_text' => $follower_text,
			'song_text' => $song_text,
			'album_text' => $album_text,
			'profile_image'=>$profile_image,
			'firstname'=>$user_db_details["firstname"],
			'lastname'=>$user_db_details["lastname"],
			'description' => $description,
			'playsets' => $p,
			'username'=>$username,
			'user_type' => "user",
			'cover_image' => $profile_cover_image,
			"my_profile" => $my_profile,
			"extra_class" => "artist-profile-edit",
			"profile_link" => $profileLink,
			"country" => ($user_db_details["cityName"] != "") ? ", ".$user_db_details["countryName"] : $user_db_details["countryName"] ,
			"city" => $user_db_details["cityName"]." ",
			"other_profile" => $other_profile,
			"follow" => $follow,
			"following" => $following,
			"tofollowid" => $profile_link_uid,
			"followingId" => $profile_link_uid,			
			'data_array' => $data_array,
			'tabid' => $tabid,
			'class_nm' => $class_nm,
			'header_tmp' => $header_tmp,
			'upload_active_class' => $upload_active_class,
			'album_active_class' => $album_active_class,
			'followers_active_class' => $followers_active_class,
			'followings_active_class' => $followings_active_class,
			'listen_active_class' => $listen_active_class,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'gender_nm' => $gender_nm,
			'user_roles_ar' => $user_db_details["user_roles_ar"]
		);
				
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="profile/profile.html";		
		$template_arry['playsetRow']="profile/playset_row.html";
			
		if($follow == "true")
			$template_arry['followRow'] = "profile/follow-row.html";
		
		if($following == "true")
			$template_arry['folowingRow'] = "profile/following-row.html";	
		
		if($header_tmp == "true")
			$template_arry['profileHeader'] = "profile/profile_music_header.html";
		
		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";
		
		$template_arry['tabRender'] = "profile/profile_tab_render.html";
		$template_arry['arrayData'] = $temp_name;
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$user_profile);
		
		return $data1; 
		
	}
	
	function artist_profile($profileLink,$user_db_details,$action,$page,$start_limit = NULL){
		$album_text = "ALBUM";
		$profile_link_uid = getvalfromtbl("id","users","profileLink = '".$profileLink."'","single","");
				
		if($this->sess_id == $profile_link_uid)
		{
			//user is vieving own profie
			$my_profile = "true";	
			$other_profile = "";			
		}else if($this->sess_id > 0){
			//user is viewing another users profile
			$other_profile = "true";
			$is_following = getvalfromtbl("id","followinglog"," fromId = '".$this->sess_id."'  AND toId = '".$profile_link_uid."'","single","");
			
			if($is_following > 0)
			{
				$following = "true";
				$follow = "";
			}
			else{
				$following = "";
				$follow = "true";
			}			
		}
		else{
			//user is not logged
			$my_profile = "";	
			$other_profile = "true";
			$following = "";
			$follow = "true";
		}	
		
		
		if($action == "new-songs")
		{
			//$tabid = "popular-songs";
			$data_array = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",$this->rec_to_dis);
			$temp_name = "profile/artist_popular_row.html";
			$temp_name = "profile/artist_popular_row.html";
			$class_nm = "browse-songs artist_new-songs";
			$tabid = "prof_uploaded_songs";
			$header_tmp = "true";
			$new_active_class = "new-songs";
			$total_records = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",'','','counter');

			if($total_records > $this->rec_to_dis)
			{
				$loadmore = "true";
				$load_url = $profileLink."/new-songs/";
				$load_cont = "#prof_uploaded_songs";
				$load_tmpl = "profile_artist_popular_songs";				
			}else{
				$loadmore = "";
			}
			
			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_uploaded_tracks("tt.userId = '".$profile_link_uid."'",$new_limit,'','',$start_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
    			echo json_encode( $final_array );
    			exit;
			}
		}
		else if($action == "albums")
		{
			$data_array = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",$this->rec_to_dis);
			$temp_name = "profile/recent_listen_row.html";
			$class_nm = "vedio-songs-list";
			$tabid = "albums";
			//class vedio-songs-list
			$album_active_class = "albums";
			$total_records = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",'','','counter');
			
			if($total_records > $this->rec_to_dis)
			{
					$loadmore = "true";
					$load_url = $profileLink."/albums/";
					$load_cont = "#albums";
					$load_tmpl = "profile_recent_listen";
			}else{
				$loadmore = "";
			}			
			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_albums("a.userId = '".$profile_link_uid."'",$new_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
				echo json_encode( $final_array );
				exit;
			}		
		}
		else{
			$data_array = $this->user_profile->fetch_popular_tracks("tt.userId = '".$profile_link_uid."'",$this->rec_to_dis);
			$temp_name = "profile/artist_popular_row.html";
			$class_nm = "browse-songs artist_popular-songs";
			$tabid = "popular-songs";
			$header_tmp = "true";
			$popular_active_class = "popular-songs";							
			$total_records = $this->user_profile->fetch_popular_tracks("tt.userId = '".$profile_link_uid."'",'','','counter');
			//echo $total_records;exit;
			if($total_records > $this->rec_to_dis)
			{
					$loadmore = "true";
					$load_url = $profileLink."/popular-songs";
					$load_cont = ".artist_popular-songs";
					$load_tmpl = "profile_artist_popular_songs";
			}else{
				$loadmore = "";
			}			
			//$page 
			if($page != NULL && $page > 0)
			{
				$new_limit = $start_limit.",".$this->rec_to_dis;
				$last_page = ceil($total_records/$this->rec_to_dis);	
				$data_array = $this->user_profile->fetch_popular_tracks("tt.userId = '".$profile_link_uid."'",$new_limit,'','',$start_limit);	
				$final_array["data"] = $data_array;	
				$final_array["page"] = $page+1;
				$final_array["last_page"] = $last_page;
				header('Content-Type: application/json');
				echo json_encode( $final_array );
				exit;
			}
		}
			
		$this->load->model('commonfn');
		$profile_image = $this->commonfn->get_photo('p',$profile_link_uid);
		$profile_cover_image = $this->commonfn->get_photo('pc',$profile_link_uid);
		
		$new_active_class = isset($new_active_class) ? $new_active_class : "";
		$album_active_class = isset($album_active_class) ? $album_active_class : "";
		$popular_active_class = isset($popular_active_class) ? $popular_active_class : "";		
		
		if($user_db_details["gender"] == "m")
			$gender_nm = "Male";
		else if($user_db_details["gender"] == "f")
			$gender_nm = "Female";
		

		$follower_text = "FOLLOWER";
		$song_text = "SONG";
		$albums_text = "ALBUM";
			
		if($user_db_details["followers"] > 1)
			$follower_text = "FOLLOWERS";

		if($user_db_details["tracks"] > 1)
			$song_text = "SONGS";
		
		if($user_db_details["albums"] > 1)
			$album_text = "ALBUMS";



		$user_profile = array(
			'title'=>$this->config->item('title'),
			'url' => base_url(),
			'img'=>img_url(),		
			'cover_image'=>$profile_cover_image,
			'artist_name' => $user_db_details["firstname"],
			'profile_image' => $profile_image,
			'firstname'=>$user_db_details["firstname"],
			'lastname'=>$user_db_details["lastname"],			
			"country" => $user_db_details["countryName"],
			"city" => $user_db_details["cityName"]."  ",		
			'no_of_followers'=>$user_db_details["followers"],
			'description' => $user_db_details["description"],
			'no_of_songs' => $user_db_details["tracks"],
			'no_of_albums' => $user_db_details["albums"],
			'album_text' => $album_text,
			'follower_text' => $follower_text,
			'song_text' => $song_text,
			//'popular_songs' => $ps,
			'loggedin'=>$left_panel,
			'username'=>$username,
			'user_type' => "artist",
			"extra_class" => "",
			'data_array' => $data_array,
			'tabid' => $tabid,
			'class_nm' => $class_nm,
			"profile_link" => $profileLink,
			'header_tmp' => $header_tmp,
			'new_active_class' => $new_active_class,
			'album_active_class' => $album_active_class,
			'popular_active_class' => $popular_active_class,
			"other_profile" => $other_profile,
			"follow" => $follow,
			"following" => $following,
			"tofollowid" => $profile_link_uid,
			"followingId" => $profile_link_uid,
			"my_profile" => $my_profile,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			'gender_nm' => $gender_nm,
			'user_roles_ar' => $user_db_details["user_roles_ar"]			
		);
				
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="profile/artist_profile.html";
		
		if($follow == "true")
			$template_arry['followRow'] = "profile/follow-row.html";
		
		if($following == "true")
			$template_arry['folowingRow'] = "profile/following-row.html";	
		
		if($header_tmp == "true")
			$template_arry['profileHeader'] = "profile/profile_music_header.html";
		
		if($loadmore == "true")
			$template_arry['loadMore'] = "general/loadmore.html";
		
		$template_arry['tabRender'] = "profile/profile_tab_render.html";
				
		$template_arry['arrayData'] = $temp_name;
		$template_arry['playerPanel']="player_panel.html";
		
		$data1=get_template_content($template_arry,$user_profile);		
		return $data1; 
		
	}
	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */