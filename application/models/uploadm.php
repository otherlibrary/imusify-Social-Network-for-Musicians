<?php
Class uploadm extends CI_Model
{	
	function __construct(){
                parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;		
	}
	
	/*Insert track*/
	function insert_track($title,$desc,$mm,$dd,$yy,$genre,$label,$image,$folder_name,$sec_tags_genid,$album_id,$r,$eaction,$moods_list,$instruments_list,$music_vocals_y = NULL,$music_vocals_gender = NULL,$sale_available = NULL,$sale_available_ar = NULL,$licence_available = NULL,$licence_available_ar = NULL,$nonprofit_available = NULL,$post = NULL)
	{	
		$this->load->model('commonfn');
                                
		if($eaction == "edit")
		{

			$trackeditInfo = getvalfromtbl("*","tracks","id='".$r."'");
			$trackposttype_ar = explode(".",$trackeditInfo["trackName"]);
			$trackposttype = end($trackposttype_ar);
			if($trackeditInfo["track_buyable_types"] != "" || $trackeditInfo["track_buyable_types"] != null)
			{
				$tracktype = $trackeditInfo["track_buyable_types"];
			}
			else{
				$tracktype = $this->fetch_track_types($trackposttype);
			}
			
			if($trackeditInfo["track_buyable_current_type"] > 0)
			{
				$track_buyable_current_type = $trackeditInfo["track_buyable_current_type"];
			}
			else{
				$track_buyable_current_type = getvalfromtbl("id","tracktypes","name LIKE '%".$trackposttype."%'","single");
			}
			if($album_id > 0)
			{
				$album_id = $album_id;	
			}else{
				$album_id = 0;
			}
			$usage_type = null;
			if($post["sale_available"] && $post["sale_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 's'","single").",";
			}

			if($post["licence_available"] && $post["licence_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 'l'","single").",";
			}

			if($post["nonprofit_available"] && $post["nonprofit_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 'np'","single").",";
			}

			if($usage_type != null){
				$usage_type = rtrim($usage_type,",");
			}
			
			$data = array(
				'albumId' =>$album_id,
				'userId' =>  $this->sess_userid,
				'title' => $this->db->escape_str($title),
				'description' => $this->db->escape_str($desc),
				'release_mm' => $this->db->escape_str($mm),
				'release_dd' => $this->db->escape_str($dd),
				'release_yy' => $this->db->escape_str($yy),	
				'genreId' => $this->db->escape_str($genre),
				'isPublic' => $post["ispublic"],
				'track_buyable_types' => $tracktype,
				'track_buyable_current_type' => $track_buyable_current_type,
				'usage_type'=>$usage_type,
                                'trackuploadbpm' => $post["trackuploadbpm"],
				);
			$where = "(id='".$r."' AND userId ='".$this->sess_userid."' )";
			$this->db->where($where);
			$this->db->update('tracks', $data);
			$track_id = $r;			
			
			/*Handle secondary genres*/
			$session_user_id = $this->sess_userid;
			$track_exists_role =  $this->track_db_genres($track_id);	
			$db_count = count($track_exists_role);	
			if($db_count > 0)
			{
				$diff = array_diff($track_exists_role,$sec_tags_genid);
				if(!empty($diff))
				{
					$this->db->where('trackId', $track_id);
					$this->db->where_in('genreId', $diff);
					$this->db->delete('track_genre');
				}
			}
			if (!empty ($sec_tags_genid) && !empty ($track_exists_role)){
                            $diff1 = array_diff($sec_tags_genid,$track_exists_role);
                            if(!empty($diff1))
                            {
                                    $i = 0;
                                    foreach($diff1 as $roles1) {
                                            $data_g[$i]["trackId"] = $track_id;	
                                            $data_g[$i]["userId"] = $session_user_id;
                                            $data_g[$i]["genreId"] = $roles1;
                                            $data_g[$i]["createdDate"] = date('Y-m-d H:i:s');
                                            $i++;
                                    };	
                                    $this->db->insert_batch('track_genre', $data_g);
                            }
                            /*Handle secondary genres ends*/                                                        
                        }
                        
                        
			
			
			/*Handle moods*/
			$track_exists_moods =  $this->track_db_genres($track_id,'m','moodId');
                        
			$db_count_m = count($track_exists_moods);	
			if($db_count_m > 0)
			{
				$diff_m = array_diff($track_exists_moods,$post["moods_list"]);
				if(!empty($diff_m))
				{
					$this->db->where('trackId', $track_id);
					$this->db->where_in('moodId', $diff_m);
					$this->db->delete('track_moods');
				}				
			}			
                        
			if (!empty ($post["moods_list"]) && !empty ($track_exists_moods)){
                            $diff_m1 = array_diff($post["moods_list"],$track_exists_moods);
                            if(!empty($diff_m1))
                            {
                                    $i = 0;
                                    foreach($diff_m1 as $roles1) {
                                            $data_m[$i]["trackId"] = $track_id;	
                                            $data_m[$i]["userId"] = $session_user_id;
                                            $data_m[$i]["moodId"] = $roles1;
                                            $data_m[$i]["createdDate"] = date('Y-m-d H:i:s');
                                            $i++;
                                    };	
                                    $this->db->insert_batch('track_moods', $data_m);
                            }
                            /*Handle moods ends*/
                        }
                        

			/*Handle instruments moods_list instruments*/
			$track_exists_instruments =  $this->track_db_genres($track_id,'in','instumentId');	
			$db_count_i = count($track_exists_instruments);	
			if($db_count_i > 0)
			{
				$diff_i1 = array_diff($track_exists_instruments,$post["instruments"]);
				if(!empty($diff_i1))
				{
					$this->db->where('trackId', $track_id);
					$this->db->where_in('instumentId', $diff_i1);
					$this->db->delete('track_instruments');
				}
			}
                        
                        if (!empty ($post["instruments"]) && !empty ($track_exists_instruments)){
                            $diff_i = array_diff($post["instruments"],$track_exists_instruments);
                            /*dump($diff_i);*/
                            if(!empty($diff_i))
                            {
                                    $i = 0;
                                    foreach($diff_i as $roles1) {
                                            $data_i[$i]["trackId"] = $track_id;	
                                            $data_i[$i]["userId"] = $session_user_id;
                                            $data_i[$i]["instumentId"] = $roles1;
                                            $data_i[$i]["createdDate"] = date('Y-m-d H:i:s');
                                            $i++;
                                    };		
                                    /*dump($data_i);*/
                                    $this->db->insert_batch('track_instruments',$data_i);

                                    /*echo $this->db->last_query();*/

                            }
                            /*Handle instruments/ */
                        }
			
			
			/*Price licence calculation */
			$licence_price_arr = array();
			$licence_id_ar = array();
			$m = 0;
			
			/*Price sell calculation */
			if(!empty($post["sale_available_ar"]))
			{
				foreach ($post["sale_available_ar"] as $key => $value)
				{
					$licence_id_ar["sell_".$value] = $value;
				}
			}
			/*Price sell calculation ends */

			/*Price licence calculation */
			if(!empty($post["licence_available_ar"]))
			{
				foreach ($post["licence_available_ar"] as $key => $value)
				{
					$licence_id_ar["lic_".$value] = $value;
				}
			}
			/*Price licence calculation ends */

			/*Price non-profit licence calculation */
			if(!empty($post["np_licence_ar"]))
			{
				foreach ($post["np_licence_ar"] as $key => $value)
				{
					$licence_id_ar["np_".$value] = $value;
				}
			}


			/*dump();*/

			/*Price non-profit licence calculation ends */
			$temp_licence_list_ar = array();
			if(!empty($licence_id_ar))
			{
				foreach ($licence_id_ar as $key => $value) {
					$temp_licence_list_ar[$key] =  $value;
				}	
			}

			$track_exists_licences =  $this->track_db_licences($track_id);	
			$db_count_l = count($track_exists_licences);

			if($db_count_l > 0)
			{
				$diff_l = array_diff($track_exists_licences,$temp_licence_list_ar);

				if(!empty($diff_l))
				{
					$this->db->where('trackId', $track_id);
					$this->db->where_in('licenceId', $diff_l);
					$this->db->delete('track_licence_price_details');
				}
			}


			$diff_l_1 = array_diff($temp_licence_list_ar,$track_exists_licences);
			
			if(!empty($temp_licence_list_ar))
			{
				/*$this->db->insert_batch('track_licence_price_details', $licence_price_arr);*/

				foreach ($temp_licence_list_ar as $key => $value) {

					if(in_array($value, $track_exists_licences))
					{
						if($post[$key] > 0)
						{
							$update_array = array('licencePrice'=>$post[$key]); 
							$this->db->where("trackId",$track_id);
							$this->db->where("licenceId",$value);
							$this->db->update("track_licence_price_details",$update_array);
						}


					}	
					else{
						$insert_array = array(
							"trackId"=>$track_id,
							'licenceId'=>$value,
							'licencePrice'=>$post[$key],
							'createdDate' => date('Y-m-d H:i:s')
							);
						$this->db->insert("track_licence_price_details",$insert_array);
					}
				}
			}
		}
		else{
                    //insert new track
			$this->load->library('getid3/getID3');
			$this->load->library('MP3Waveform');
			$this->load->model('commonfn');
			$permalink = $this->commonfn->get_permalink($title,"tracks","perLink","id");
			$session_user_id = $this->session->userdata('user')->id;
			$file_name = $this->session->userdata('song_'.$r);
			$old_physical_path = asset_path()."temp/".$session_user_id."/".$file_name;

			//$old_physical_filesize = filesize($old_physical_path);

			$user_current_filesize = getvalfromtbl("avail_space","users","id='".$this->sess_userid."'","single");                        
                        if ($user_current_filesize > 0){//if storage of membership plan is limited
                             $used_space = getvalfromtbl("used_space","users","id='".$this->sess_userid."'","single");
                            //var_dump ($user_current_filesize, $used_space);exit;
                            //2515321 bytes
                            //$user_current_filesize = 200000000;
                            //if((int)$old_physical_filesize > (int)$user_current_filesize && (int)$user_current_filesize != (int)-1){
                            if((int)$used_space >= (int)$user_current_filesize){    //no more space to upload
    //				//delete 
                                    if (file_exists(asset_path()."temp/".$session_user_id."/".$file_name)) 
                                    {
                                            unlink($old_physical_path);
                                    }
                                    $response["status"] = "fail";
                                    $response["msg"] = "Please update your membership plan. You need more space to upload";
                                    return $response;
                            }
                        }
                       


			/*Time length of file*/				
			// Initialize getID3 engine
			$getID3 = new getID3;		
			$filename = $old_physical_path;		
			/*Analyze file and store returned data in $ThisFileInfo*/
			$ThisFileInfo = $getID3->analyze($filename);	
			$time_length_song = $ThisFileInfo["playtime_string"];
			$bitrate = $ThisFileInfo["bitrate"];
			/*Time length of file ends*/

			$parts = explode('.', $file_name);
			$extIndex = count($parts) - 1;
			$ext = strtolower(@$parts[$extIndex]);

			$final_new_name = mt_rand(1,1000000)."_".md5(time());
			$new_song_name = $final_new_name.".".$ext; 	

			if($ext == "wav"){

				$output = '';
				$new_physical_mp3_path = asset_path()."upload/media/".$session_user_id."/".$final_new_name.".mp3";
				exec("lame $old_physical_path $new_physical_mp3_path",$output);	
			}
			else if($ext == "ogg"){
				$output = '';
				$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$final_new_name.".mp3";
				exec("sox $old_physical_path $new_physical_path",$output);
			}
			else if($ext == "AIF"){
				/*lame -b 40 -m m --resample 22.05 -S - 'outfile.mp3'*/
				$output = '';
				$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$final_new_name.".mp3";
				exec("lame -b 40 -m m --resample $old_physical_path $new_physical_path",$output);	

			}
			else if($ext == "FLAC"){
				/*sudo apt-get install flac*/
				/*flac -c -d "1 - Let It In.flac" | lame -V 6 --ta "$ARTIST" - "1 - Let It In.mp3"*/
				$output = '';
				$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$final_new_name.".mp3";
				exec("flac -c -d $old_physical_path | lame -V 6 --ta $new_physical_path",$output);
			}
			else if($ext == "AAC"){
				/*ffmpeg -i audio.aac -acodec libmp3lame -ac 2 -ab 160 audio.mp3*/

			}	

			if(!file_exists(asset_path()."upload/media/".$session_user_id))
			{
				mkdir(asset_path()."upload/media/".$session_user_id,0777,true);
			}		
			$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$new_song_name;

			if (file_exists(asset_path()."temp/".$session_user_id."/".$file_name)) 
			{
				if (copy($old_physical_path, $new_physical_path)) {
					unlink($old_physical_path);
				}
			}

			if($ext != "mp3")
			{
				$new_song_name = $final_new_name.".mp3"; 
				$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$final_new_name.".mp3";
			}

			/*Convert audio in 128 kbps*/
			$this->load->library("soundexchange");
			$converted_file_name = asset_path()."upload/media/".$session_user_id."/high_".$new_song_name;
			$temp_status = $this->soundexchange->convert($new_physical_path,$converted_file_name,TRUE, 128);
			/*Convert audio in 128 kbps*/

			$array["wave_path"]=asset_upload_path().'wavejson/';
			$array["temp_path"]=asset_path().'temp/'.$this->sess_userid."/";
			//$w = new MP3Waveform($array);

			$array["file_name"] = $new_physical_path;
			$array["json_file_name"]=$final_new_name;
                        
                        //enable waveform creation and call API to process waveform after saving
                        //used for later Player
                        //create waveform for uploaded song
			$w= new MP3Waveform;
                        //var_dump ($array);
			$w->set_path_detail($array);
			$result = $w->convertToWavAndGenerateWaveData();
                        
                        
			if($album_id > 0)
			{
				$album_id = $album_id;
			}else{
				$album_id = 0;
			}							
			$filesize = filesize($new_physical_path);
			$trackposttype = $post["tracktype"];
			/*if($trackposttype != "mp3")
			{}*/
			$tracktype = $this->fetch_track_types($trackposttype);
			$track_buyable_current_type = getvalfromtbl("id","tracktypes","name LIKE '%".$trackposttype."%'","single");


			$usage_type = null;
			if($post["sale_available"] && $post["sale_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 's'","single").",";
			}

			if($post["licence_available"] && $post["licence_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 'l'","single").",";
			}

			if($post["nonprofit_available"] && $post["nonprofit_available"] != "")
			{
				$usage_type .= getvalfromtbl("id","buy_usage_types","type = 'np'","single").",";
			}

			if($usage_type != null){
				$usage_type = rtrim($usage_type,",");
			}
			

			$data = array(
				'albumId' => $album_id,
				'userId' =>  $session_user_id,
				'title' => $this->db->escape_str($title),
				'perLink' => $permalink,
				'description' => $this->db->escape_str($desc),
				'release_mm' => $this->db->escape_str($mm),
				'release_dd' => $this->db->escape_str($dd),
				'release_yy' => $this->db->escape_str($yy),	
				'genreId' => $this->db->escape_str($genre),
				'createdDate' => date('Y-m-d H:i:s'),
				'trackName' => $new_song_name,
				'timelength' => $time_length_song,
				'bitrate' => $bitrate,
				'isPublic' => $post["ispublic"],
				'filesize' => $filesize,
				'trackuploadType' => $post["trackuploadtype"],
                                'trackuploadbpm' => $post["trackuploadbpm"],
				'track_buyable_types' => $tracktype,
				'track_buyable_current_type' => $track_buyable_current_type,
				'usage_type'=>$usage_type
				);	
                        //$json_encode = json_encode($data);
                        //var_dump ($json_encode);exit;
                        //$result = $this->db->insert('tracks',$data);
                        
			//$this->db->query("insert into temp(data)  values('".json_decode($data)."')");

			/*dump($data);*/
			/*print_r($data);
			exit;*/

			//$temp_arr = array();
			if($music_vocals_y != NULL)
			{
				$data["track_type"] = $music_vocals_y;
			}

			if($music_vocals_gender != NULL)
			{
				$data["track_musician_type"] = $music_vocals_gender;
			}

			if($sale_available != NULL)
			{
				$data["is_sellable"] = $sale_available;
			}	

			if($licence_available != NULL)
			{
				$data["license"] = $licence_available;
			}

			if($nonprofit_available != NULL)
			{
				$data["track_nonprofit_avail"] = $nonprofit_available;
			}
			//$sale_available_ar = NULL,$licence_available_ar = NULL
			//print_r($data);	
			$result = $this->db->insert('tracks', $data);
                        
                        //var_dump ('Insert tracks: ',$result, $data);exit;
			//$this->db->query("insert into temp(data)  values('".$this->db->last_query()."')");
			/*print $this -> db ->last_query();*/
			$track_id = $this->db->insert_id();					

			

			/*Save tags to db*/

			/*Insert in feed table*/
			$this->load->model("following_model");
			$feedtypeId = getvalfromtbl("id","feed_type","shortName='iv'","single");
			$param_arr = array('userId'=>$session_user_id,'itemId'=>$track_id,'feedtypeId'=>$feedtypeId,'user_role'=>'p');
			$this->following_model->insert_feed_log($param_arr);
			/*Insert in feed table*/

			/*Update used_space for this user*/
			//$user_space = getvalfromtbl("used_space","users","id='".$session_user_id."'","single");
                        $used_space = getvalfromtbl("used_space","users","id='".$this->sess_userid."'","single");
//			if((int)$user_space != (int)-1){
                        $update_user_space = $used_space + $filesize;			
                        $data=array('used_space'=>$update_user_space);
                        $this->db->where('id',$this->sess_userid);
                        $this->db->update('users',$data);	
						

			$i = 0;		
			$data1 = array();
			//print_r($sec_tags_genid);
			if(!empty($sec_tags_genid))
			{
				foreach($sec_tags_genid as $gen) {
							//$arr[$i] = $roles;
					$data1[$i]["trackId"] =   $track_id;
					$data1[$i]["userId"] = $session_user_id;
					$data1[$i]["genreId"] = $gen;
					$data1[$i]["createdDate"] = date('Y-m-d H:i:s');	
					$i++;
				};					
				$this->db->insert_batch('track_genre', $data1);
			}

			/*Save moods to db*/
			$j = 0;		
			$data2 = array();
			/*	print_r($sec_tags_genid);*/
			if(!empty($moods_list))
			{
				foreach($moods_list as $mood) {
					/*$arr[$i] = $roles;*/
					$data2[$j]["trackId"] = $track_id;
					$data2[$j]["userId"] = $session_user_id;
					$data2[$j]["moodId"] = $mood;
					$data2[$j]["createdDate"] = date('Y-m-d H:i:s');	
					$j++;
				};					
				$this->db->insert_batch('track_moods', $data2);
			}
			/*Save moods to db ends*/
			/*$instruments_list*/
			/*save instuments to db*/
			$in = 0;		
			$data3 = array();
			if(!empty($instruments_list))
			{
				foreach($instruments_list as $instrument) {
					$data3[$in]["trackId"] = $track_id;
					$data3[$in]["instumentId"] = $instrument;
					$data3[$in]["createdDate"] = date('Y-m-d H:i:s');	
					$in++;
				};					
				$this->db->insert_batch('track_instruments', $data3);
			}	
			/*save instuments to db ends*/

			if($this->session->userdata('image_'.$r) != "")
			{
				$image_update = $this->session->userdata('image_'.$r);
				$folder_name = "track/";
				$this->session->unset_userdata('image_'.$r);
			}

			$data_alb_photo = array(
				'detailId' => $track_id,
				'dir' => $folder_name,
				'name' => $image_update,
				'type' => 't'
				);

			$this->db->insert('photos', $data_alb_photo);

			$album_photo_id= $this->db->insert_id();		 
			/*Save track image to photo table ends*/

			/*Price licence calculation */
			$licence_price_arr = array();
			$licence_id_ar = array();
			$m = 0;
			/*Price sell calculation */
			$after_track_upload_ar = array();
			if(!empty($post["sale_available_ar"]))
			{
				$after_track_upload_ar['is_sellable'] = 'y';
				foreach ($post["sale_available_ar"] as $key => $value)
				{
					$licence_id_ar["sell_".$value] = $value;
				}
			}
			/*Price sell calculation ends */

			/*Price licence calculation */
			if(!empty($post["licence_available_ar"]))
			{
				$after_track_upload_ar['license'] = 'y';
				foreach ($post["licence_available_ar"] as $key => $value)
				{
					$licence_id_ar["lic_".$value] = $value;
				}
			}
			/*Price licence calculation ends */

			/*Price non-profit licence calculation */
			if(!empty($post["np_licence_ar"]))
			{
				foreach ($post["np_licence_ar"] as $key => $value)
				{
					$licence_id_ar["np_".$value] = $value;
				}
			}
			/*Price non-profit licence calculation ends */
			foreach ($licence_id_ar as $key => $value) {
				$licence_price_arr[$m]["trackId"] = $track_id;
				$licence_price_arr[$m]["licenceId"] = $value;
				$licence_price_arr[$m]["licencePrice"] = $post[$key];
				$licence_price_arr[$m]["createdDate"] = date('Y-m-d H:i:s');
				$m++;
			}

			if(!empty($licence_price_arr)){
				$this->db->insert_batch('track_licence_price_details', $licence_price_arr);
			}/*Licence price insert ends*/

			if(!empty($after_track_upload_ar))
			{
				$this->db->where('id',$track_id);
				$this->db->update('tracks',$after_track_upload_ar);
			}
		}
		$user_data = $this->session->userdata('user');
		/*Fetch name*/
		$this -> db -> select('id,title,description,release_mm,release_dd,release_yy,plays,likes,shares,comments,timelength,perLink');
		$this -> db -> from('tracks');
		$where = "id='".$track_id."'";				   
		$this->db->where($where);	   
		$query = $this -> db -> get();	 
		$song_name = $query->result_array();
		$song_name[0]["main_title"] = $song_name[0]["title"];
		$song_name_ret = $song_name[0];
		/* $song_name_ret["main_title"] =  $song_name[0]["title"];*/
		$song_name_ret["track_image"] =  $this->commonfn->get_photo('t',$track_id);

		if($album_id > 0)
		{
			$song_name_ret["row_id"] =  "album_row";
			$song_name_ret["album_id"] =  $album_id;
			$song_name_ret["album_song"] =  "yes";
			$song_name_ret["isalbum"] =  "yes";
			$song_name_ret["ttrackId"] = $song_name[0]["id"];
		}
		else
		{
			$song_name_ret["row_id"] =  "music_row";
			$song_name_ret["album_song"] =  "no"; 
			$song_name_ret["edittype"] =  "t"; 


		}	   
		$song_name_ret["track_link"] = base_url().$user_data->profileLink."/".$song_name[0]["perLink"];
		$song_name_ret["editid"] =  $song_name[0]["id"];	

		/**/
		$song_name_ret["tags"] = $this->fetch_track_genre($song_name[0]["id"]);

		$avail_space_db = getvalfromtbl("avail_space","users","id='".$session_user_id."'","single");
                //var_dump ($user_data, $this->session->userdata('user'));
		$song_name_ret["avail_space"] = $avail_space_db;

		/*Udate Session*/
		
		$user_data->avail_space_db = $avail_space_db;
		$this->session->set_userdata('user', $user_data);
		/*Udate Session*/


		return $song_name_ret;
	}


	function fetch_track_types($tracktype = null){

		$output = array();
		$this -> db -> select('id');
		$this -> db -> from('tracktypes');
		$where = "(name='".$tracktype."' or name='mp3')";	
		$this -> db -> limit(1);
		$this->db->where($where);	  

		$query = $this -> db -> get();	
		$response = "";   
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$response .= $row["id"].",";
			}
			$response = rtrim($response,",");
			
		}
		return $response;
	}


	function fetch_user_tracks($cond = NULL,$limit = NULL,$orderby = NULL,$width = 100,$height = 150,$counter = NULL)
	{
		$this->load->model('commonfn');
		$output = array();
		if($cond != NULL)
			$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND ".$cond."";	
		else				
			$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id ";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY ".$orderby;	
		else
			$orderby ="ORDER BY tt.id DESC";	


		$query = $this->db->query("SELECT tt.id,tt.perLink,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.likes,tt.comments,tt.shares,g.genre,(SELECT profileLink from users where id = tt.userId) AS userperlink FROM tracks as tt,genre as g ".$cond." ".$orderby." ".$limit." ");

		/*echo $this->db->last_query();*/

		if($counter == "counter"){
			/*echo $query->num_rows();*/
			return $query->num_rows();
		}

		foreach ($query->result_array() as $row)
		{
			$row["track_image"] = $this->commonfn->get_photo('t',$row["id"]);
			$row["track_link"] = base_url().$row["userperlink"]."/".$row["perLink"];
			$row["editid"] = $row["id"];
			$row["edittype"] = "t";
			$row["main_title"] = $row["title"];
			$row["total_songs"] = "";
			$row["song_list"][] = $row;
			$row["userId"] = $this->session->userdata('user')->id;
			$row["row_id"] = "music_row";
			$row["tags"] = $this->fetch_track_genre($row["id"]);
			$output[] = $row;					
		}
		return $output;
	}


	function fetch_track_genre($track_id,$cond = NULL,$orderby = null,$limit = null)
	{	

		if($cond != NULL)
			$cond = " WHERE tg.trackId = ".$track_id." AND g.id = tg.genreId AND ".$cond."";	
		else				
			$cond = " WHERE tg.trackId = ".$track_id." AND g.id = tg.genreId ";

		$query = $this->db->query("SELECT tg.*,g.genre FROM track_genre as tg,genre as g ".$cond." ".$orderby." ".$limit." ");

		foreach ($query->result_array() as $row)
		{
			$output[] = $row["genre"];					
		}
		return $output;
	}


	function fetch_user_albums($cond = NULL,$limit = NULL,$width = IMG_222,$height = IMG_222,$counter = NULL)
	{

		$this->load->model('commonfn');
		$output = array();
					//echo $cond;
		if($cond != NULL)
			$cond = " WHERE a.status = 'y' AND ".$cond."";	
		else				
			$cond = " WHERE a.status = 'y'";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		/*$query = $this->db->query("SELECT a.id,a.name,a.release_mm as arelease_mm,a.release_dd as arelease_dd,a.release_yy as arelease_yy,t.title,t.id as ttrackId,t.timelength,t.perLink,t.release_dd,t.release_mm,t.release_yy,t.plays,t.likes,t.shares,a.id as aid,(SELECT genre from genre where genre.id = a.genre) AS album_genre_name,(SELECT COUNT(albumId) FROM tracks where tracks.albumId = a.id) AS total_albums_songs,(SELECT profileLink from users where id = t.userId) AS userperlink FROM albums as a LEFT OUTER JOIN tracks as t ON  (a.id = t.albumId LIMIT 50) ".$cond." order by aid DESC,t.id DESC $limit");*/


		$query = $this->db->query("select t.title,t.id as ttrackId,t.timelength,t.perLink,t.release_dd,t.release_mm,t.release_yy,t.plays,t.likes,t.shares,at.* from (SELECT a.id,a.name,a.release_mm as arelease_mm,a.release_dd as arelease_dd,a.release_yy as arelease_yy,a.id as aid,(SELECT genre from genre where genre.id = a.genre) AS album_genre_name,(SELECT COUNT(albumId) FROM tracks where tracks.albumId = a.id) AS total_albums_songs,(SELECT profileLink from users where id = a.userId) AS userperlink FROM albums as a  ".$cond." order by aid DESC ".$limit.") as at LEFT JOIN tracks as t ON  (at.id = t.albumId) order by aid DESC,t.id DESC");

		


		/*echo $this->db->last_query();*/
		if($counter == "counter"){
			return $query->num_rows();
		}

		$temps = NULL;
		$i = 0;
		$j = 0;
		foreach ($query->result_array() as $row)
		{
			if($temps == NULL)
			{
				$albumrow[$i]["track_image"] = $this->commonfn->get_photo('a',$row["id"],$width,$height);
				$albumrow[$i]["editid"] = $row["id"];
				$albumrow[$i]["edittype"] = "a";
				$albumrow[$i]["main_title"] = $row["name"];
				$albumrow[$i]["genre"] = $row["album_genre_name"];
				$albumrow[$i]["aid"] = $row["aid"];					
				$albumrow[$i]["release_mm"] = $row["arelease_mm"];					
				$albumrow[$i]["release_dd"] = $row["arelease_dd"];					
				$albumrow[$i]["release_yy"] = $row["arelease_yy"];					
				$albumrow[$i]["total_songs"] = $row["total_albums_songs"]." Songs - ";
				$albumrow[$i]["row_id"] = "album_row";
				$albumrow[$i]["userId"] = $this->session->userdata('user')->id;
				$albumrow[$i]["editalbumurl"] = base_url()."upload/album/edit/".$row["id"];

				if($row["title"] != NULL || $row["title"] != "")
				{
					$row["isalbum"] = "yes";
					$row["track_link"] = base_url().$row["userperlink"]."/".$row["perLink"];
					$row["track_image"] = $this->commonfn->get_photo('t',$row["ttrackId"]);
					$row["tags"] = $this->fetch_track_genre($row["id"]);
					
					$row["trackediturl"] = base_url().'upload/track/edit/'.$row["ttrackId"];
					$albumrow[$i]["song_list"][$j] = $row;
				}
				$temps = $row["aid"];
			}
			else if($row["aid"] == $temps){
				$j++;
				$row["isalbum"] = "yes";
				$row["track_link"] = base_url().$row["userperlink"]."/".$row["perLink"];
				$row["track_image"] = $this->commonfn->get_photo('t',$row["ttrackId"]);
				$row["tags"] = $this->fetch_track_genre($row["id"]);
				$row["trackediturl"] = base_url().'upload/track/edit/'.$row["ttrackId"];
				$albumrow[$i]["song_list"][$j] = $row;
			}
			else{
				$i++;
				$j = 0;
				$albumrow[$i]["track_image"] = $this->commonfn->get_photo('a',$row["id"],IMG_222,IMG_222);
				$albumrow[$i]["editid"] = $row["id"];
				$albumrow[$i]["edittype"] = "a";
				$albumrow[$i]["main_title"] = $row["name"];
				$albumrow[$i]["genre"] = $row["album_genre_name"];
				$albumrow[$i]["aid"] = $row["aid"];
				$albumrow[$i]["release_mm"] = $row["arelease_mm"];					
				$albumrow[$i]["release_dd"] = $row["arelease_dd"];					
				$albumrow[$i]["release_yy"] = $row["arelease_yy"];				$albumrow[$i]["userId"] = $this->session->userdata('user')->id;			
				$albumrow[$i]["row_id"] = "album_row";
				$albumrow[$i]["total_songs"] = $row["total_albums_songs"]." Songs - ";							
				if($row["title"] != NULL || $row["title"] != "")
				{
					$row["isalbum"] = "yes";
					$row["track_link"] = base_url().$row["userperlink"]."/".$row["perLink"];
					$row["track_image"] = $this->commonfn->get_photo('t',$row["ttrackId"]);
					$row["tags"] = $this->fetch_track_genre($row["id"]);
					$row["trackediturl"] = base_url().'upload/track/edit/'.$row["ttrackId"];
					$albumrow[$i]["song_list"][$j] = $row;
				}
				$temps = $row["aid"];
			}
			$output = $albumrow;
		}
		return $output;
	}

	/*Fetch track or album information*/
	function fetch_info($id,$type,$cond = NULL,$limit = NULL)
	{
		$this->load->model('commonfn');
		$output = array();			
		if($cond == NULL)
			$cond = " WHERE status = 'y' AND id = ".$id."";
		else
			$cond = " WHERE status = 'y' AND id = ".$id." AND ".$cond."";	
		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($type == 'a')
		{
			$table = "albums";
			$cancel_class = "album_cancel";

		}
		else if($type == 't')
		{
			$table = "tracks";
			$cancel_class = "track_cancel";	
		}							

		$query = $this->db->query("SELECT * FROM ".$table." ".$cond." ".$limit." ");
		$row = $query->row(); 
		if($type == 'a')
		{
			$row->name = $row->name;				
			$row->album_img = $this->commonfn->get_photo($type,$row->id);
			$row->album_list = "";
			$comare_var = $row->genre;

			$row->save_class = 'create_album_submit';
		}else{
			$row->name = $row->title;
			$row->track_image = $this->commonfn->get_photo($type,$row->id);
			if($row->isPublic =='y')
			{
				$row->ispublic = 'checked="checked"';
			}
			else{
				$row->isprivate = 'checked="checked"';
			}

			if($row->track_type != '' || $row->track_type != null || $row->track_musician_type != '' || $row->track_musician_type != null)
			{
				$row->vocal_switch = true;
			}

			if($row->track_type == 'r')
			{
				$row->music_vocals_r = 'checked="checked"';
			}
			else if($row->track_type == 'si'){
				$row->music_vocals_si = 'checked="checked"';
			}else{
				$row->music_vocals_sp = 'checked="checked"';
			}

			if($row->track_musician_type == 'm')
			{
				$row->music_vocals_gender_m = 'checked="checked"';
			}
			else if($row->track_musician_type == 'f'){
				$row->music_vocals_gender_f = 'checked="checked"';
			}else{
				$row->music_vocals_gender_both = 'checked="checked"';
			}


			/*Mood List*/
			$mood_list_array = $this->commonfn->get_moods();

			$mood_list_final_array = array();
			$moodvaluearray = $this->track_db_genres($id,'m','moodId');
			
			if(!empty($mood_list_array)){
				foreach ($mood_list_array as $key => $value1) {
					if(in_array($value1["id"], $moodvaluearray)){
						$value1["selected"] = "true";
					}
					$mood_list_final_array[] = $value1;
				}	
			}
			$row->moods_list = $mood_list_final_array;
			/*Mood List ends*/

			/*Instruments List*/
			$instrument_list_array = $this->commonfn->get_instuments();
			$instrument_list_final_array = array();
			$instrumentvaluearray = $this->track_db_genres($id,'in','instumentId');
			
			if(!empty($instrument_list_array)){
				foreach ($instrument_list_array as $key => $value2) {
					if(in_array($value2["id"], $instrumentvaluearray)){
						$value2["selected"] = "true";
					}
					$instrument_list_final_array[] = $value2;
				}	
			}

			$row->instuments_list = $instrument_list_final_array;
			/*Instruments List ends*/

			/*sell type list*/
			$check_high_low_type_track = getvalfromtbl("type","tracktypes","id = '".$row->track_buyable_current_type."'","single");
			$high_quality_flag = false;
			if($check_high_low_type_track == 'h')
			{
				$high_quality_flag = true;
			}		

			$sell_type_list_array = $this->commonfn->get_licence_types("tlt.lic_type='s'",null,null,true,$id,$high_quality_flag);
			$sell_type_list = array();
			$sell_valuearray = $this->track_db_licences($id);
			
			if(!empty($sell_type_list_array)){
				foreach ($sell_type_list_array as $key => $value) {
					if(in_array($value["id"], $sell_valuearray)){
						$value["selected"] = " checked";
						$row->sell_type_switch = "checked";
					}
					else{
						$value["selected"] = "";
					}
					$row->sell_type_list[] = $value;
				}	
			}
			/*sell type list ends*/

			/*Licence type list*/
			$licence_type_list_array = $this->commonfn->get_licence_types("tlt.lic_type='l'",null,null,true,$id,$high_quality_flag);
			$licence_type_list = array();
			
			if(!empty($licence_type_list_array)){
				foreach ($licence_type_list_array as $key => $value) {
					if(in_array($value["id"], $sell_valuearray)){
						$value["selected"] = " checked";
						$row->licence_type_switch = "checked";
					}
					else{
						$value["selected"] = " ";
					}
					$row->licence_type_list[] = $value;
				}	
			}
			/*Licence type list*/

			/*np type list*/
			$np_type_list_array = $this->commonfn->get_licence_types("tlt.lic_type='np'",null,null,true,$id,$high_quality_flag);
			$np_type_list = array();
			if(!empty($np_type_list_array)){
				foreach ($np_type_list_array as $key => $value) {
					if(in_array($value["id"], $sell_valuearray)){
						$value["selected"] = " checked";
						$row->np_type_switch = "checked";
					}
					else{
						$value["selected"] = " ";
					}
					$row->np_type_list[] = $value;
				}	
			}
			/*np type list*/
			$session_user_id = $this->session->userdata('user')->id;
			$comare_var = $row->genreId;
			if($session_user_id > 0)
			{
				$compare_var = $row->albumId;
				/*Album*/
				$album_list_array = $this->commonfn->get_albums();
				$album_final_array = array();
				if(!empty($album_list_array)){
					foreach ($album_list_array as $key => $value) {
						if($value["id"] == $compare_var){
							$value["selected"] = "true";
						}
						$album_final_array[] = $value;
					}	
				}
				$row->album_list = $album_final_array;
				/*Album ends*/
			}	
			$row->save_class = 'edit_track';
		}
		/*Genre*/
		$genre_list_array = $this->commonfn->get_genre("type='p'");
		$genre_final_array = array();

		if(!empty($genre_list_array)){
			foreach ($genre_list_array as $key => $value) {
				if($value["id"] == $comare_var){
					$value["selected"] = "true";
				}
				$genre_final_array[] = $value;
			}	
		}
		$row->genre_list = $genre_final_array;
		/*Genre ends*/

		/*Secondary genres*/
		$sec_genre_list_array = $this->commonfn->get_genre("type = 's'");
		$sec_genre_final_array = array();
		$valuearray = $this->track_db_genres($id,$type);
		if(!empty($sec_genre_list_array)){
			foreach ($sec_genre_list_array as $key => $value) {
				if(in_array($value["id"], $valuearray)){
					$value["selected"] = "true";
				}
				$sec_genre_final_array[] = $value;
			}	
		}
		$row->sec_genre_list = $sec_genre_final_array;
		/*Secondary genres*/
		$row->track_upload_type_list = $this->commonfn->get_track_upload_types();
		$row->r = $row->id;
		$row->delid = $row->id;
		$row->deltype = "au";	
		$row->checkboxselected = "yes";	
		$row->action_class = "cancel ".$cancel_class;
		$row->userId = $this->session->userdata('user')->id;
		$row->display = "displaynone";
		$row->displaynone = "";		
		return  $row;	
	}

	/*function for fetch perticular genre of a song*/
	/*Fetch db records*/
	function track_db_genres($detailId,$type = 't',$column = "genreId")
	{
		/*$track_exists_role = array();
		$session_user_id = $this->session->userdata('user')->id;
		$data = array();
		$this -> db -> select('genreId');			
		$this -> db -> from('track_genre');	   
		$where = "(trackId='".$trackId."')";
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			$data = $query->result_array();	
			foreach ($data as $row)
			{
				$track_exists_role[] = $row["genreId"];
			}
		}		
		return $track_exists_role;	*/	
		$sec_genre_exist_role = array();
		$this -> db -> select($column);	
		if($type == "a")
		{
			$table = "albums_genre";
			$where = "(albumId='".$detailId."')";
		}
		else if ($type == "m") {
			$table = "track_moods";
			$where = "(trackId='".$detailId."')";
		}	
		else if($type == "in"){
			$table = "track_instruments";
			$where = "(trackId='".$detailId."')";
		}
		else{
			$table = "track_genre";
			$where = "(trackId='".$detailId."')";
		}	
		$this -> db -> from($table);	  

		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			$data = $query->result_array();	
			foreach ($data as $row)
			{
				$sec_genre_exist_role[] = $row[$column];
			}
		}		
		return $sec_genre_exist_role;

	}

	function track_db_licences($trackid = 0){
		/*echo $trackid." z ";*/
		if($trackid > 0)
		{
			$licence_exist_list = array();
			$where = "(trackId='".$trackid."')";
			$this -> db -> select("licenceId");
			$this -> db -> from("track_licence_price_details");	  

			$this->db->where($where);	   
			$query = $this -> db -> get();	   
			
			if($query -> num_rows() > 0)
			{
				$data = $query->result_array();	
				foreach ($data as $row)
				{
					$licence_exist_list[] = $row["licenceId"];
				}
			}		
			return $licence_exist_list;

			
		}

	}
        //delete track
	function delete_trackfromdb($userId = NULL,$trackdet = NULL,$deltype = NULL){
		$response = array();
		if($userId > 0)
		{
			if($trackdet > 0 && $deltype == "au")
			{
				$trackInfo = getvalfromtbl("*","tracks","id='".$trackdet."' AND userId = '".$userId."'");
                                
                                    /*Unlink a track from folder*/
				$file_path = asset_path()."upload/media/".$userId."/".$trackInfo["trackName"];
				if(file_exists($file_path))
				{
					unlink($file_path);
				}
                                
				/*Unlink a track from folder ends*/

				/*Unlink a photo from folder*/
				$trackphoto_counter = getvalfromtbl("count(*)","photos","detailId='".$trackdet."' AND type = 't'");
                                //var_dump($trackphoto_counter);
                                //$trackphoto_counter is an array 
                                // ["count(*)"]=> string(1) "1"
				if($trackphoto_counter[0] > 1)
				{

				}
				else{
					$trackphoto = getvalfromtbl("*","photos","detailId='".$trackdet."' AND type = 't'");
                                        if (!empty($trackphoto)){
                                            $filename =  asset_path()."upload/".$trackphoto["dir"].$trackphoto["name"];                                        
                                            if(file_exists($filename))
                                            {
                                                try{
                                                    unlink($filename);
                                                } catch(Exception $e) {

                                                }                                                
                                            }
                                        }    
					
                                        //remove photo track 
                                        $this->db->where('detailId', $trackdet);
                                        $this->db->where('type',  't');
                                        $this->db->delete('photos');	
                                        
					$trackcphoto = getvalfromtbl("*","photos","detailId='".$trackdet."' AND type = 'tc'");
                                        if (! empty($trackcphoto)){
                                            $filecname =  asset_path()."upload/".$trackcphoto["dir"].$trackcphoto["name"];

                                            if(file_exists($filecname))
                                            {
                                                    unlink($filecname);
                                            }
                                        }
					
				}
                                
									
				/*Unlink a photo from folder*/
				$this->db->where('id', $trackdet);
				$this->db->where('userId', $userId);
				$this->db->delete('tracks');					
				/*update user's sapce in user table => Membership*/
				//$this->db->set('avail_space', "avail_space+".$trackInfo['filesize'], FALSE);
				//$this->db->where('userId', $trackInfo['userId']);
				//$this->db->update('users');
				/*update user's sapce in user table => Membership*/
				$response["status"] = "success";
				$response["msg"] = $trackInfo["title"]." deleted successfully.";
				$response["track_sapce"] = $trackInfo["filesize"];
			}
                        //first time fu just upload track
			else if(($trackdet != "" || $trackdet != NULL) && $deltype == "fu"){
				$filename =  asset_path()."temp/".$userId."/".$trackdet;
					
				$file_size = filesize($filename);
				$response["track_sapce"] = $file_size;
				$response["status"] = "success";
				$response["msg"] = "Upload cancelled - ".$trackdet;
                                if(file_exists($filename))
				{
					unlink($filename);
				}
			}
			else{
				$response["status"] = "fail";
				$response["msg"] = "fail";
			}
		}else{
			$response["status"] = "fail";
			$response["msg"] = "fail";
		}
		return $response;
	}
}
?>