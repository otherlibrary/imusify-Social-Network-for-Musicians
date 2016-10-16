<?php
Class commonfn extends CI_Model
{
	public function __construct(){
		parent::__construct();		
		if($this->session->userdata('user'))		
			$this->sess_id = $this->session->userdata('user')->id;	
	}
	
	//Function to get genre from database
	public function get_genre($cond = NULL,$limit = NULL,$start_limit = NULL,$subgenre_id = NULL,$counter = NULL)
	{
		$output = array();
		$this -> db -> select('id,genre,parentId');
		$this -> db -> from('genre');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		
		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}

		$this->db->where($where);	   
		$this->db->order_by("id");
		$query = $this -> db -> get();	   
	  //print $this -> db ->last_query();
		if($counter == "counter")
			return $query -> num_rows();

		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($row["parentId"] > 0)
				{
					$row["classnm"] = "subgenre";
				}	
				else
					$row["classnm"] = "genre";

				$output[] = $row;
			}
			return $output;
		}	   
	}
	
	/*Get moods*/

	function get_moods($cond = NULL,$limit = NULL,$start_limit = NULL)
	{
		$output = array();
		$this -> db -> select('id,mood');
		$this -> db -> from('mood');

		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";

		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("mood");
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[] = $row;
			}
			return $output;
		}	   
	}	

	/*Get moods ends*/

	/*Get instuments*/
	function get_instuments($cond = NULL,$limit = NULL,$start_limit = NULL)
	{
		$output = array();
		$this -> db -> select('id,name');
		$this -> db -> from('instruments');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";

		if($limit != NULL)
			$this -> db -> limit($limit);
		$this->db->where($where);	   
		$this->db->order_by("name");
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[] = $row;
			}
			return $output;
		}	   
	}	


	/*Get instuments ends*/
	
	/*Get licence types*/
	
	function get_licence_types($cond = NULL,$limit = NULL,$start_limit = NULL,$flag = false,$trackId = 0,$high_quality_flag = false)
	{
		$output = array();
		if($flag == true)
		{
			$this -> db -> select('tlt.id,tlt.name,tlt.description,tlt.mp3,tlt.wave,tlpd.licencePrice');
			$this -> db -> from('track_licence_price_details as tlpd');
			$this->db->join('track_licence_types as tlt', 'tlpd.licenceId = tlt.id AND tlpd.trackId = "'.$trackId.'"','right');
		}
		else{
			$this -> db -> select('tlt.id,tlt.name,tlt.description,tlt.mp3,tlt.wave');
			$this -> db -> from('track_licence_types as tlt');
		}

		if($cond != NULL){
                    //andy
			$where = "(tlt.status='y' AND ".$cond.")";	
                        if ($flag) $where = "(".$cond.")";
                }                    
		else {
                        	$where = "(tlt.status='y')";
                                if ($flag) $where = '';
                }
		
                        
		if($limit != NULL)
			$this -> db -> limit($limit);
		$this->db->where($where);	   
		$this->db->order_by("tlt.name");
		$query = $this ->db-> get();	   
		/* print $this -> db ->last_query();*/
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($high_quality_flag == true)
				{
					$row["mp3"] = $row["wav"];
				}

				if($flag == true && ($row["licencePrice"] != null || $row["licencePrice"] != ''))
				{
					$row["mp3"] = $row["licencePrice"];
				}

				$output[] = $row;
			}
			return $output;
		}	   
	}
	
	
	/*Get licence types ends*/

	function gettracktypes(){

		$output = array();
		$this -> db -> select('*');
		$this -> db -> from('tracktypes');
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				if($row["type"] == "l")
				{
					$output["lower_type_list"][] = $row["name"]; 
				}else if($row["type"] == "h"){
					$output["higher_type_list"][] = $row["name"];
				}
				$output[] = $row;
			}
			return $output;
		}

	}


	/*Get get_track_upload_types types*/
	
	function get_track_upload_types($cond = NULL,$limit = NULL,$start_limit = NULL)
	{
		$output = array();
		$this -> db -> select('id,name');
		$this -> db -> from('track_upload_type');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		if($limit != NULL)
			$this -> db -> limit($limit);
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[] = $row;
			}
			return $output;
		}	   
	}
	
	
	/*Get get_track_upload_types types ends*/




	/*get_albums*/
	function get_albums($cond = NULL,$limit = NULL,$start_limit = NULL)
	{
		$output = array();
	    //$session_user_id = $this->session->userdata('user')->id;
		$this -> db -> select('id,name');
		$this -> db -> from('albums');

		if($cond != NULL)
			$where = "(status='y' AND userId = '".$this->sess_id."' AND ".$cond.")";	
		else
			$where = "(status='y' AND userId = '".$this->sess_id."')";

		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("id");
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[] = $row;
			}
			return $output;
		}	   
	}

	/*Insert album*/
	function insert_album($image,$title,$desc,$mm,$dd,$yy,$label,$genre,$image,$folder_name,$sec_genre,$isupdate = false,$album_id = null,$album_avail_for_sale,$album_fully_avail,$album_price)
	{

		if($album_avail_for_sale == 'y')
		{
			$album_avail_for_sale = 'y';
		}else{
			$album_avail_for_sale = 'n';
		}
		if($album_fully_avail == 'y')
		{
			$album_fully_avail = 'f';
		}else{
			$album_fully_avail = 'p';
		}
		
		$data = array(
			'userId' =>  $this->sess_id,
			'genre' => $this->db->escape_str($genre),
			'name' =>  $this->db->escape_str($title),
			'description' => $this->db->escape_str($desc),
			'label' => $this->db->escape_str($label),
			'release_mm' => $this->db->escape_str($mm),
			'release_dd' => $this->db->escape_str($dd),
			'release_yy' => $this->db->escape_str($yy),
			'is_selleble' => $album_avail_for_sale,
			'selleble_type' => $album_fully_avail,
			'price' => $album_price
			);	



		if($isupdate == true && $album_id > 0){
			$this->db->where("id",$album_id);
			$this->db->update('albums', $data);	
		}
		else{
			$permalink = $this->get_permalink($title,"albums","perLink","id");	
			$data['createdDate'] = date('Y-m-d H:i:s');
			$data['perLink'] = $permalink;	 
			$this->db->insert('albums', $data);
			/*echo $this->db->last_query();	
			echo $this->db->_error_message();*/
			$album_id = $this->db->insert_id();	
			/*echo  $album_id;	*/	
		}
		/*dump($data);*/
		
		if($this->session->userdata('album') != "")
		{
			$image = $this->session->userdata('album');
			$folder_name = "album/";
			$this->session->unset_userdata('album');	

			$data_alb_photo = array(
				'detailId' =>  $album_id,
				'dir' => $folder_name,
				'name' => $image,
				'type' => 'a',
				'default_pic' => 'y'
				);

			$this->db->insert('photos', $data_alb_photo);		
			$album_photo_id= $this->db->insert_id();
		}

		/*Save album id into genre*/
		$i = 0;		
		$data1 = array();
		if(!empty($sec_genre))
		{
			$this->db->query("DELETE from albums_genre where albumId NOT (".$sec_genre.") AND albumId =  '".$album_id."'");

			foreach($sec_genre as $gen) {
				$data1[$i]["albumId"] =   $album_id;
				$data1[$i]["userId"] = $this->sess_id;
				$data1[$i]["genreId"] = $gen;
				$data1[$i]["createdDate"] = date('Y-m-d H:i:s');	
				$i++;
			};					
			$this->db->insert_batch('albums_genre', $data1);
		}	   

		$width = IMG_222;
		$height = IMG_222;
		$query = $this->db->query("SELECT a.id,a.name,a.release_mm as arelease_mm,a.release_dd as arelease_dd,a.release_yy as arelease_yy,a.id as aid,(SELECT genre from genre where genre.id = a.genre) AS album_genre_name,(SELECT COUNT(albumId) FROM tracks where tracks.albumId = a.id) AS total_albums_songs,(SELECT profileLink from users where id = a.userId) AS userperlink FROM albums as a  WHERE a.id = '".$album_id."' LIMIT 1");

		$row = $query->row_array();
		$albumrow["track_image"] = $this->commonfn->get_photo('a',$row["id"],$width,$height);
		$albumrow["editid"] = $row["id"];
		$albumrow["edittype"] = "a";
		$albumrow["main_title"] = $row["name"];
		$albumrow["genre"] = $row["album_genre_name"];
		$albumrow["aid"] = $row["aid"];					
		$albumrow["release_mm"] = $row["arelease_mm"];					
		$albumrow["release_dd"] = $row["arelease_dd"];					
		$albumrow["release_yy"] = $row["arelease_yy"];					
		$albumrow["total_songs"] = $row["total_albums_songs"]." Songs - ";
		$albumrow["row_id"] = "album_row";
		$albumrow["userId"] = $this->session->userdata('user')->id;
		$albumrow["editalbumurl"] = base_url()."upload/album/edit/".$row["id"];


		return $albumrow;	   
	}	
	
	/*get photo details*/
	function get_photo($type,$deailId,$width = NULL,$height = NULL,$image_exist_check = NULL)
	{
		$this -> db -> select('domain,dir,name');
		$this -> db -> from('photos');
		if($type == "pc" || $type == "p")
			$where = "(type='".$type."' AND detailId = '".$deailId."' AND default_pic = 'y')";
		else	
			$where = "(type='".$type."' AND detailId = '".$deailId."')";
		$this -> db -> limit(1);
		$this->db->order_by("id","desc");
		$this->db->where($where);	                
		$query = $this -> db -> get();
		
		switch($type)
		{
			case "p":
			$file_url = asset_url()."images/user-profile-img.jpg";
			$file_nm = "user-profile-img.jpg";
			break;

			case "pc":
			$file_url = asset_url()."images/cover-img.png";
			$file_nm = "cover-img.png";
			break;			

			case "pl":
			$file_url = asset_url()."images/track-img.jpg";
			$file_nm = "track-img.jpg";
			break;			

			case "a":
			$file_url = asset_url()."images/track-img.jpg";
			$file_nm = "track-img.jpg";
			break;			

			case "t"://track picture
			$file_url = asset_url()."images/track-img.jpg";
			$file_nm = "track-img.jpg";
			break;

			case "tc":
			$file_url = asset_url()."images/cover-img.png";
			$file_nm = "cover-img.png";
			break;

			case "art":
			$file_url = asset_url()."images/cover-img.png";
			$file_nm = "cover-img.png";
			break;


			default:
			$file_url = "";	
		}
                //andy
                //var_dump ($query->num_rows(), $type, $deailId, $file_nm);
		if($query -> num_rows() > 0)
		{
			$row = $query->result_array();	
                        //admin and user                         
			if(file_exists(asset_path()."upload/".$row[0]["dir"].$row[0]["name"]) || 
                                file_exists(asset_admin_path()."upload/".$row[0]["dir"].$row[0]["name"]) )
			{
				if($height != NULL && $height > 0 && $width != NULL && $width > 0)
					$file_url = base_url()."assets/upload/".$row[0]["dir"].$width."/".$height
				."/".$row[0]["name"];
				else
					$file_url = asset_url()."upload/".$row[0]["dir"].$row[0]["name"];
                                
                                //andy replace resources to assets
                                $file_url = str_replace('resources', 'assets', $file_url);
			}
			else{
				$file_url = $file_url;
			}			
		}
		else{		
			if($height != NULL && $height > 0 && $width != NULL && $width > 0)
			{
				$file_url = asset_url()."images/".$width."/".$height."/".$file_nm;
			}
			else{
				$file_url = $file_url;
			}
		}	
		/*echo $file_url;*/
		return $file_url;
	}

	/*Permalink generate*/
	function get_permalink($title,$table,$dbcolumn,$orderby = NULL,$cond = NULL)
	{

		$this->load->library("Slug");
		$title = trim($title);
		if($cond != NULL)
			$cond = " WHERE status = 'y'";
		else
			$cond = " WHERE status = 'y' AND ".$cond."";	

		$query = $this->db->query("SELECT ".$dbcolumn." FROM ".$table." WHERE ".$dbcolumn." LIKE '".$title."-%' Order by ".$orderby." DESC LIMIT 1");
		$row = $query->result_array();	
		
		if(!empty($row))
		{
			$temp = $row[0][$dbcolumn];	
			$temp_string = explode("-",$temp);				
			
			$val = end($temp_string);									
			$val = (int)$val;
			$val = $val+1;			

			$slug_title = $this->slug->url_slug(
				$title, 
				array(
					'delimiter' => '_'
					)
				);

			return $slug_title."-".$val;			
		}else{			
			$temps = getvalfromtbl("perLink",$table,"perLink = '".$title."'","single","");

			if($temps == $title)
			{
				return $temps."-1";				
			}else{
				$slug_title = $this->slug->url_slug($title,array(
					'delimiter' => '_'
					));
				return $slug_title;
			}			
		}
	}


	//Function to get countries 
	function get_countries($cond = NULL,$limit = NULL,$start_limit = NULL)
	{
		$this->db->query("SET NAMES 'utf8'");
		$this->db->query("SET CHARACTER SET 'utf8'");

		$this -> db -> select('location_id,name');
		$this -> db -> from('location');

		if($cond != NULL)
			$where = "(is_visible='0' AND parent_id = '0' AND ".$cond.")";	
		else
			$where = "(is_visible='0' AND parent_id = '0')";
		
		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("name");
		$query = $this -> db -> get();	   
		
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				//$row["name"] = utf8_decode($row["name"]);
				$output[] = $row;
			}
			return $output;
		}	   
	}

	//Function to get states processId =  country or state id... 
	function get_states_city($processId)
	{

		$this->db->query("SET NAMES 'utf8'");
		$this->db->query("SET CHARACTER SET 'utf8'");

		$this -> db -> select('location_id,name');
		$this -> db -> from('location');

		if($cond != NULL)
			$where = "(is_visible='0' AND parent_id = '".$processId."' AND ".$cond.")";	
		else
			$where = "(is_visible='0' AND parent_id = '".$processId."')";
		
		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("name");
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row["name"] = utf8_decode($row["name"]);
				$output[] = $row;
			}
			return $output;
		}	   
	}

	/*Unblock a user once his time is overed*/
	/*function unblock_follow_user(){
		$time = date('Y-m-d H:i:s');
		$affected_user_ar = array();
		
		$this -> db -> select('id,userId');
		$this -> db -> from('user_block_list');
		$where = "status = 'y' AND endDate <= '".$time."' AND type = 'f'";	
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$affected_user_ar[] = $row;
			}				
		}

		$block_data_ar =  array();
		$notification_ar = array();
		
		$i = 0;
		foreach ($affected_user_ar as $key => $value) {
			$temp = array();
			$temp = array("id"=>$value["id"],"status"=>'c');
			$block_data_ar[] = $temp;
			$notification_ar[$i]["toId"] = $value["userId"];
			$notification_ar[$i]["fromId"] = $admin_id;
			$notification_ar[$i]["detailId"] = $value["id"];
			$notification_ar[$i]["notification"] = "Admin has unblocked you for following.";
			$notification_ar[$i]["createdDate"] = date('Y-m-d H:i:s');
			$notification_ar[$i]["type"] = "b";			
			$i++;
		}
		$this->db->update_batch('user_block_list', $block_data_ar, 'id');
		$this->db->insert_batch('notification', $notification_ar);			
	}*/
	/*Unblock a user once his time is overed*/

	function follow_today_check($toid,$fromId){
		$date = date('Y-m-d');
		$is_blocked = getvalfromtbl("id,status","user_block_list","type = 'f' AND userId = '".$fromId."' AND (status = 'y' OR status = 'n') AND (DATE_FORMAT(createdDate,'%Y-%m-%d') = '".$date."' OR '".$time."' BETWEEN createdDate AND endDate)");
		if(!empty($is_blocked) && $is_blocked["id"] > 0)
		{	
			if($is_blocked["status"] == "c")
				return "blocked";
			else
				return "blockedfortoday";
		}else{
			return "true";
		}
	}
        
        function follow_exist($toid,$fromId){		
		$query1 = $this->db->query("SELECT id from followinglog WHERE fromId = $fromId AND toId = $toid ");
                $row_today = $query1->result_array();
                //var_dump ($row_today);
		if( !empty($row_today) && count($row_today) > 0)
		{				
				return "Alreadyfollowed";			
		}else{
			return false;
		}
	}
        
	function follow_function($fromid){
		$date = date('Y-m-d');
		$intervl_days = FOLLOW_BLOCK_LIMIT_DAY - 1;
		if($fromid > 0)
		{	
			$query1 = $this->db->query("SELECT COUNT(id) as total_follow_today,(SELECT COUNT(id) from followinglog  WHERE  DATE_FORMAT(created,'%Y-%m-%d')  >= ( '".$date."' - INTERVAL ".$intervl_days." DAY ) AND DATE_FORMAT(created,'%Y-%m-%d')  <= ( '".$date."' ) AND fromId = '".$fromid."') as three_day_counter from followinglog where DATE_FORMAT(created,'%Y-%m-%d') = '".$date."' AND fromId = '". $fromid."'");
			$row_today = $query1->result_array();
			$today_follow_rec = $row_today[0]["total_follow_today"];
			$three_day_follow_rec = $row_today[0]["three_day_counter"];
				//print_query();
				//echo " 1 ".$today_follow_rec;
				//echo " 2 ".$three_day_follow_rec; 

			if($today_follow_rec == FOLLOW_LIMIT_DAY)
			{	
				/*made 50follows today make entry of n => */
				$data = array(
					'userId' =>  $fromid,
					'endDate' => date('Y-m-d H:i:s', strtotime("+1 days")),				
					'status' => 'n',
					'createdDate' => date('Y-m-d H:i:s'),
					'type' => 'f'			
					);

				$this->db->insert('user_block_list', $data);
				/*made 50follows today make entry of n => */
					//echo FOLLOW_LIMIT_DAY*FOLLOW_BLOCK_LIMIT_DAY;
				if($three_day_follow_rec == FOLLOW_LIMIT_DAY*FOLLOW_BLOCK_LIMIT_DAY)
				{
					$is_blocked_again = getvalfromtbl("id,status","user_block_list","type = 'f' AND userId = '".$fromid."' AND (status = 'c')");
						//var_dump($is_blocked_again);
					if(!empty($is_blocked_again))
					{
						/*Mail to admin*/

						/*$data_mail = array(
							'login_url' => base_url()."login",
							'username' => $uname,
							'mail' => $email,
							'verify_link' => base_url()."api/verifyuser/token/".$token
						);			
						$abc=$this->template->load('mail/email_template','mail/register',$data_mail,TRUE);

						send_mail(ADMIN_MAIL,$email,"Username blocked for following continuously.",$abc);*/

						/*Mail to user*/
						/*$data_mail = array(
							'login_url' => base_url()."login",
							'username' => $uname,
							'mail' => $email,
							'verify_link' => base_url()."api/verifyuser/token/".$token
							);			
						$abc=$this->template->load('mail/email_template','mail/register',$data_mail,TRUE);

						send_mail(ADMIN_MAIL,$email,"You are blocked for following many users.",$abc);*/

						/*Mail to admin and user remaining*/

						/*Update track table*/
						$this->db->where('id', $fromid);
						$this->db->set('status', 'n', FALSE);	
						$this->db->update('users');
					}else{
							//first time blocked
					}
					
					//var_dump(strtotime("+".FOLLOW_BLOCK_LIMIT_DAY." days"));
					/*make entry of blocked yes*/
					$data_a = array(
						'userId' =>  $fromid,
						'endDate' => date('Y-m-d H:i:s', strtotime("+15 days")),
						'createdDate' => date('Y-m-d H:i:s'),
						'status' => 'y',
						'type' => 'f'			
						);

					$this->db->insert('user_block_list', $data_a);

					/*make entry of blocked yes*/
				}
				else{
					return "true";
				}
			}
			else{
				return "true";
			}
		}else{
			return "false";
		}
	}

	function follow($toid,$fromid = NULL,$refreshpanel = NULL){
		$session_user_id = $this->session->userdata('user')->id;
		$fromid = ($fromid != NULL) ? $fromid : $session_user_id;
		$response_today = $this->follow_today_check($toid,$fromid);
                $follow_exist = $this->follow_exist($toid,$fromid);     
                //var_dump ($follow_exist);exit;
		if($response_today == "true" && ! $follow_exist)
		{
			$data = array(
				'toId' => $toid,
				'fromId' => $fromid,				
				'created' => date('Y-m-d H:i:s'),
				'ipAddress' => get_client_ip()			
				);
			$this->db->insert('followinglog', $data);

			/*Notification entry*/
			$data = array(
				'toId' => $toid,
				'fromId' => $this->sess_id,	
				'detailId' => $toid,
				'notification' => $this->session->userdata('user')->username." is following you now",
				'createdDate' => date('Y-m-d H:i:s'),
				'type' => "f"			
				);

			$this->db->insert('notification', $data);
			$function_call = $this->follow_function($fromid);

			
			if($refreshpanel != NULL)
			{
				$this->load->model("following_model");
				$response["status"] = "successfull_follow";
				$param_array = array('limit'=>4);
				$response["data"] = $this->following_model->follow_suggestions($param_array);
				return $response;
			}

			return "successfull_follow";
		}
		else{
                    if ($follow_exist) return $follow_exist;
                    else return $response_today;
		}
	}




	function unfollow($toid){
            
            
		$where = "fromId = '".$this->sess_id."' AND toId = '".$toid."'";
		$this->db->where($where);
		$this->db->delete('followinglog');

		$affected = $this->db->affected_rows();
		if($affected > 0)
		{				
			$this->db->where('toId', $toid);
			$this->db->where('fromId', $this->sess_id);
			$this->db->where('type', "f");
			$this->db->delete('notification'); 

			return "successfull_unfollow";
		}else{
			return "error";
		}				

	}


	/*Function for like*/
	function like_track($trackId,$likedbyuserid = NULL){

		$LikedbyId = ($likedbyuserid != NULL) ? $likedbyuserid : $this->sess_id;
		$is_exists = getvalfromtbl("id","likelog","userId = '".$LikedbyId."' AND trackId = '".$trackId."'","single","");
		$toid = getvalfromtbl("userId","tracks","id = '".$trackId."'","single","");
		
		if($is_exists > 0)
		{
			$final_array["status"] = "already_liked";
		}
		else{
			$data = array(
				'trackId' => $trackId,
				'userId' => $LikedbyId,				
				'createdDate' => date('Y-m-d H:i:s')											
				);
			$this->db->insert('likelog', $data);

			/*Update track table*/
			$this->db->where('id', $trackId);
			$this->db->set('likes', '`likes`+ 1', FALSE);	
			$this->db->update('tracks');

			/*Notification entry*/
			$data = array(
				'toId' => $toid,
				'fromId' => $LikedbyId,	
				'detailId' => $trackId,
				'notification' => $this->session->userdata('user')->username." has liked your track.",
				'createdDate' => date('Y-m-d H:i:s'),
				'type' => "tl"			
				);

			$this->db->insert('notification', $data);

			$final_array = array();
			$final_array["status"] = "successfull_tracklike";
			$final_array["userId"] = $LikedbyId;
			$final_array["likes"] = getvalfromtbl("likes","tracks","id = '".$trackId."'","single","");			
			$final_array["username"] = $this->session->userdata('user')->username;	
			$final_array["url"] = base_url();
			$final_array["profileLink"] = $this->session->userdata('user')->profileLink;	
			$final_array["user_pic"] = $this->get_photo("p",$this->session->userdata('user')->id);
			$final_array["row_id"] = "row".$LikedbyId;


		}
		return $final_array;
	}
	/*Function for like ends*/

	/*Function for like*/
	function dislike_track($trackId,$dislikedbyuserid = NULL){
		$disLikedbyId = ($dislikedbyuserid != NULL) ? $dislikedbyuserid : $this->sess_id;
		$where = "userId = '".$disLikedbyId."' AND trackId = '".$trackId."'";
		$toid = getvalfromtbl("userId","tracks","id = '".$trackId."'","single","");
		$this->db->where($where);
		$this->db->delete('likelog');

		$affected = $this->db->affected_rows();
		if($affected > 0)
		{
			/*Update track table*/
			$this->db->where('id', $trackId);
			$this->db->set('likes', '`likes`- 1', FALSE);	
			$this->db->update('tracks');

			$this->db->where('toId', $toid);
			$this->db->where('fromId', $disLikedbyId);
			$this->db->where('type', "tl");
			$this->db->delete('notification'); 

			$final_array["likes"] = getvalfromtbl("likes","tracks","id = '".$trackId."'","single","");
			$final_array["status"] = "successfull_unlike";				
		}else{				
			$final_array["status"] = "error";
		}

		return $final_array;
	}
	/*Function for like ends*/

	/*add song to playlist*/
	function addtrack_to_playlist($playlistId,$trackId){			
		$this -> db -> select('id');
		$this -> db -> from('playlist_detail');
		$where = "(userId='".$this->sess_id."' AND playlist_id = '".$playlistId."' AND trackId = '".$trackId."')";
		$this -> db -> limit(1);
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		//print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			return "track_exist";	
		}else{
		   				//block this user for 15 days
			$data = array(
				'playlist_id' => $playlistId,
				'userId' => $this->sess_id,
				'trackId' => $trackId,
				'createdDate' => date('Y-m-d H:i:s')			
				);												
			$this->db->insert('playlist_detail', $data);


			$this->db->set('no_of_track', 'no_of_track+1', FALSE);
			$this->db->where('id', $playlistId);
			$this->db->update('playlist');

			return "added";
		}	
	}

	/**/	
	function removetrack_from_playlist($playlistId,$trackId){			

		$this->db->where('playlist_id', $playlistId);
		$this->db->where('userId', $this->sess_id);
		$this->db->where('trackId', $trackId);
		$this->db->delete('playlist_detail'); 

		$this->db->set('no_of_track', 'no_of_track-1', FALSE);
		$this->db->where('id', $playlistId);
		$this->db->update('playlist');

		$no_of_tracks = getvalfromtbl("no_of_track","playlist","id = '".$playlistId."'","single");

		$temp["no_of_tracks"] = $no_of_tracks; 
		$temp["status"] = "removed";
		return $temp;

	}

	function get_user_info_json($userlink,$cond = NULL,$track_user = NULL){
		//id kind permalink username last_modified uri permalink_url avatar_url country first_name last_name full_name description city discogs_name website online track_count playlist_count plan followers_count followings_count
		$this -> db -> select('id,username,member_plan,profileLink,updated,firstname,lastname,description,weburl');
		$this -> db -> from('users');	   

		if($userlink > 0)
		{
			if($cond != NULL)
				$where = "(status='y' AND id = ".$this->db->escape($userlink)." AND  ".$cond.")";	
			else
				$where = "status='y' AND id = ".$this->db->escape($userlink)."";
		}	
		else{
			if($cond != NULL)
				$where = "(status='y' AND profileLink = ".$this->db->escape($userlink)." AND  ".$cond.")";	
			else
				$where = "status='y' AND profileLink = ".$this->db->escape($userlink)."";
		}

	   //echo print_query();
		$this -> db -> limit(1);   
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			$row = $query->result_array();					
			$row = $row[0];
			$row1["id"] = $row["id"];					
			$row1["kind"] = "user";					
			$row1["permalink"] = $row["profileLink"];
			$row1["username"] = $row["username"];					
			$row1["last_modified"] = $row["updated"];				
			$row1["uri"] = NULL;
			$row1["permalink_url"] = base_url().$row["profileLink"];
			$row1["avatar_url"] = $this->get_photo("p",$row["id"]);					
			if($track_user == "true")
			{
				$output = $row1;			
				return $output;
			}				
					//$row1["country"] = $row["countryname"];
			$row1["first_name"] = $row["firstname"];
			$row1["last_name"] = $row["lastname"];
			$row1["full_name"] = $row["firstname"]." ".$row["lastname"];
			$row1["description"] = $row["description"];
					//$row1["city"] = "";
					//$row1["discogs_name"]
					//$row1["myspace_name"]
			$row1["website"] = $row["weburl"];
					//$row1["website_title"] = NULL;
					//$row1["online"] = NULL;
					//$row1["track_count"] = NULL;
					//$row1["playlist_count"] = NULL;					
			if($row["member_plan"] == "u")
				$temp = "free";
			else if($row["member_plan"] == "a")
				$temp = "paid";					
			$row1["plan"] = $temp;
					//$row1["public_favorites_count"] = NULL;
					//$row1["followers_count"] = NULL;
					//$row1["followings_count"] = NULL;
					//$row1["subscriptions"] = NULL;
			$row1["tracks"] = $this->get_usertracks_info_json($row["id"]);

			$output = $row1;

			return $output;
		}		
	}

	function get_track_info_json($trackId,$cond = NULL,$arr = NULL){
	  //$output = array();
		$this->load->model("track_detail");
		$query1 = $this->db->query("SELECT tt.*,g.genre FROM tracks as tt,genre as g WHERE tt.genreId = g.id AND tt.id = ".$this->db->escape_str($trackId)." ");
		//echo print_query();
		if($query1 -> num_rows() > 0)
		{
			$row = $query1->result_array();
			$row = $row[0];
                        //var_dump ($row);exit;
			$row1["kind"] = "track";
			$row1["id"] = $row["id"];
			$row1["created_at"] = $row["createdDate"];
			$row1["user_id"] = $row["userId"];
			$timelength=$row["timelength"];
			$timelength_arr=explode(":", $timelength);
			$timelength=(count($timelength_arr)>2)?$timelength:'00:'.$timelength;
			$parsed = date_parse($timelength);
					//print_r($parsed);
			if($parsed['hour'] > 0)
				$seconds = $parsed['hour'] * 3600 + $parsed['minute'] * 60 + $parsed['second'];	
			else if($parsed['minute'] > 0)
				$seconds = $parsed['minute'] * 60 + $parsed['second'];	
			else
				$seconds = $parsed['second'];

			$mili_seconds = $seconds * 1000;
			$row1["duration"] = $mili_seconds;
			$row1["position"] = 0;
			$row1["buffer"] = 0;

			if($row["commentable"] == "y")
				$temp_commnet = (string)"true";
			else
				$temp_commnet = (string)"false";

			$row1["commentable"] = $temp_commnet;	
			
			$row1["comments"] = $this->track_detail->fetch_track_comments($row["id"]);


					//$row1["state"] = NULL;	
					//$row1["original_content_size"] = NULL;	
					//$row1["last_modified"] = NULL;	
					//$row1["sharing"] = NULL;	
					//$row1["tag_list"] = NULL;
			$row1["permalink"] = $row["perLink"];					
			$row1["streamable"] = (string)"true";
					//$row1["embeddable_by"] = (string)"true";

			if($row["downloadable"] == "y")
				$temp_down = (string)"true";
			else
				$temp_down = (string)"false";								
			$row1["downloadable"] = $temp_down;
					//$row1["purchase_url"] = NULL;
					//$row1["label_id"] = NULL;
					//$row1["purchase_title"] = NULL;
			$row1["genre"] = $row["genre"];					
			$row1["title"] = $row["title"];
			
			$row1["mini_title"] = character_limiter($row["title"], 20);

			$row1["description"] = $row["description"];	
					//$row1["label_name"] = NULL;
					//$row1["release"] = NULL;
					//$row1["track_type"] = NULL;
					//$row1["key_signature"] = NULL;
					//$row1["isrc"] = NULL;
					//$row1["video_url"] = NULL;
					//$row1["bpm"] = NULL;
			$row1["release_year"] = $row["release_yy"];
			$row1["release_month"] = $row["release_mm"];
			$row1["release_day"] = $row["release_dd"];

					//$row1["original_format"] = NULL;
					//$row1["license"] = $row["release_dd"];
					//$row1["uri"] = $row["release_dd"];
			$row1["user"] = $this->get_user_info_json($row["userId"],"","true");
                        

			$row1["permalink_url"] = $row1["user"]["permalink_url"]."/".$row["perLink"];
					//$row1["artwork_url"] = $row1["user"]["permalink_url"]."/".$row["perLink"];

			$temp_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $row["trackName"]);	
                        
			$filename =  asset_path()."upload/wavejson/".$temp_name.".json";
                        $filename = str_replace('resources', 'assets', $filename);
                        //andy
                        //var_dump ($filename);exit;

			if(file_exists($filename))
			{
				$row1["waveform_url"] = asset_url()."upload/wavejson/".$temp_name.".json";
                                $row1["waveform_url"] = str_replace('resources', 'assets', $row1["waveform_url"]);
			}
			else{
				$row1["waveform_url"] =  "";
			}




			//$row1["stream_url"] = base_url()."stream/".$row["id"];
                        //andy
                        $row1["stream_url"] = base_url()."stream/".$row["id"].'.mp3';
			$row1["playback_count"] = $row["plays"];
					//$row1["download_count"] = $row["description"];
			$row1["favoritings_count"] = $row["likes"];
			$row1["comment_count"] =  $row["comments"];
                        
			$row1["track_avtar"] =  $this->get_photo('t',$row1["id"]);
                        //var_dump ($row1);exit;
					/* if($arr == "true")
					{
						$output[] = $row1;			
					}
					else  */
						$output = $row1;			
					
					return $output;
				}		
			}	

			/*all songs of this user*/	
			function get_usertracks_info_json($userId,$cond = NULL,$limit = NULL){
				$output = array();
				$this -> db -> select('id');
				$this -> db -> from('tracks');

				if($cond != NULL)
					$where = "(status='y' AND userId = '".$userId."' AND ".$cond.")";	
				else
					$where = "(status='y' AND userId = '".$userId."')";

				if($limit != NULL)
					$this -> db -> limit($limit);

				$this->db->where($where);	   
				$this->db->order_by("id");
				$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
				if($query -> num_rows() > 0)
				{
					$i  = 0;
					foreach ($query->result_array() as $row)
					{
						$output[] = $this->get_track_info_json($row["id"]);

					}
					return $output;
				}
			}


			/*all songs of this playlist*/	
			function get_playlist_info_json($playlistId,$cond = NULL,$limit = NULL){

				$output = array();
				$output1 = array();

				$query1 = $this->db->query("SELECT pl.id,pl.updatedDate,pl.name,pl.perLink,pl.name,pl.createdDate,pl.no_of_track,pl.userId as pluid,u.profileLink,u.username FROM playlist as pl,users as u WHERE pl.userId = u.id AND pl.id = ".$this->db->escape_str($playlistId)." ");	   

				if($query1 -> num_rows() > 0)
				{		  	
					$row = $query1->result_array();
			//dump($row);
					$row = $row[0];

					$output["duration"] = NULL;
					$output["release_day"] = $row["createdDate"];
					$output["permalink_url"] = base_url().$row["profileLink"]."/".$row["perLink"];
					$output["genre"] = NULL;
					$output["permalink"] = $row["perLink"];
					$output["purchase_url"] = NULL;
					$output["release_month"] = $row["createdDate"];
					$output["description"] = NULL;

					$output["uri"] = base_url()."playlists/".$row["id"];
					$output["label_name"] = NULL;
					$output["tag_list"] = NULL;
					$output["release_year"] = NULL;
					$output["track_count"] = $row["no_of_track"];
					$output["user_id"] = $row["pluid"];
					$output["last_modified"] = $row["updatedDate"];			
					$output["license"] = "all-rights-reserved";

					$this -> db -> select('trackId');
					$this -> db -> from('playlist_detail');

					if($cond != NULL)
						$where = "(playlist_id = '".$playlistId."' AND ".$cond.")";	
					else
						$where = "(playlist_id = '".$playlistId."')";

					if($limit != NULL)
						$this -> db -> limit($limit);

					$this->db->where($where);	   
					$this->db->order_by("id","desc");
					$query = $this -> db -> get();	   
		   //print $this -> db ->last_query();
					if($query -> num_rows() > 0)
					{			   
						foreach ($query->result_array() as $row1)
						{
							$output1[] = $this->get_track_info_json($row1["trackId"]);					
						}
						$output["tracks"] = $output1;
					}

					$output["playlist_type"] = NULL;
					$output["id"] = $row["id"];
					$output["downloadable"] = NULL;
					$output["sharing"] ="public";
					$output["created_at"] = $row["createdDate"];
					$output["release"] = NULL;
					$output["kind"] = "playlist";
					$output["title"] = $row["name"];;
					$output["type"] = NULL;
					$output["purchase_title"] = NULL;

					$output["created_with"]["permalink_url"] = NULL;
					$output["created_with"]["name"] = NULL;
					$output["created_with"]["external_url"] = NULL;
					$output["created_with"]["uri"] = NULL;
					$output["created_with"]["creator"] = NULL;
					$output["created_with"]["id"] = NULL;
					$output["created_with"]["kind"] = NULL;

					$output["artwork_url"] = NULL;
					$output["ean"] = NULL;
					$output["streamable"] = true;

					$output["user"] = $this->get_user_info_json($row["pluid"],"","true");

					$output["embeddable_by"] = NULL;
					$output["label_id"] = NULL;		
				}
				return $output; 
			}

			/*all songs of this album*/	
			function get_album_info_json($albumId,$cond = NULL,$limit = NULL){

				$this -> db -> select('id');
				$this -> db -> from('tracks');

				if($cond != NULL)
					$where = "(albumId = '".$albumId."' AND ".$cond.")";	
				else
					$where = "(albumId = '".$albumId."')";

				if($limit != NULL)
					$this -> db -> limit($limit);

				$this->db->where($where);	   
				$this->db->order_by("id");
				$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
				if($query -> num_rows() > 0)
				{
					$i  = 0;
					foreach ($query->result_array() as $row)
					{
						$output[$i] = $this->get_track_info_json($row["id"]);
						$i++;
					}
					return $output;
				}
			}	

			function get_unread_notifications($cond = null,$limit = null,$counter = null,$width = null,$height = null){
				$this -> db -> select('notification,createdDate,fromId,id,type,detailId');
				$this -> db -> from('notification');
				$output = array();
				$notifications = array();
				if($cond != NULL)
					$where = "(toId = '".$this->sess_id."' AND ".$cond.")";	
				else
					$where = "(toId = '".$this->sess_id."')";

				if($limit != NULL)
					$this -> db -> limit($limit);

				$this->db->where($where);	   
				$this->db->order_by("id","desc");
				$query = $this -> db -> get();

				if($counter != null)
					return $query -> num_rows();

				if($query -> num_rows() > 0)
				{

					foreach ($query->result_array() as $row)
					{
						$row["user_img"] = $this->get_photo('p',$row["fromId"],$width,$height);
						$row["notification_id"] = $row["id"];
						$row["timeago"] = timeago($row["createdDate"]);
						$link_var = "";
						$not_detail_role = "";
						
						if($row["type"] == "f" || $row["type"] == "pv")
						{
							$link_var = getvalfromtbl("profileLink","users","id='".$row["detailId"]."'","single");
							$not_detail_role = "profile";
							$link_user_var  = $link_var;
						}
						else if($row["type"] == "tl")
						{

							$query = $this->db->query("SELECT tt.perLink,u.profileLink FROM tracks as tt,users as u WHERE tt.id = '".$row["detailId"]."' AND tt.userId = u.id");
							/*print_query();*/
							$row1 = $query->row_array();
							/*var_dump($row1);	*/
							if(!empty($row))
							{
								$link_var = $row1["profileLink"]."/".$row1["perLink"];
							}
							$not_detail_role = "trackdetail";
							$link_user_var  = $row1["profileLink"];
						}
						if($row["type"] != "an")
						{
							$row["not_detail_link"] = base_url().$link_var;
							$row["not_detail_role"] = $not_detail_role;
							$row["user_profile_link"] = base_url().$link_user_var;	
						}
						$output[] = $row;					
					}

					$notifications = $output;			
				}
				/*dump($notifications);
				exit();*/
				return $notifications;
			}	

			function page_content($pageurl = NULL,$type = NULL,$cond = NULL){

				$output = array();
	   // var_dump($pageurl);
				if($type == "allpages")
				{
					$this -> db -> select('title,url');
					$this -> db -> from('content');
					$where = "(status = 'y')";
				}
				else{
					$this -> db -> select('*');
					$this -> db -> from('content');

					if($cond != NULL)
						$where = "(status = 'y' AND url = '".$pageurl."' AND ".$cond.")";	
					else
						$where = "(status = 'y' AND url = '".$pageurl."')";

					$this -> db -> limit(1);
				}

				$this->db->where($where);	   
				$query = $this -> db -> get();	   
				$count = $query -> num_rows();
				if($count > 0)
				{	
					if($count == 1){
						$row = $query->row_array();
						$output = $row;	
					}else{
						foreach ($query->result_array() as $row)
						{
							if($row["url"] == $pageurl)
								$row["active"] = "active";

							$row["pageurl"] = base_url()."content/".$row["url"];
							$output[] = $row;					
						}
					}	   						
					return $output;
				}
			}

			/*Function to increase counter of track*/
			function track_counter_increase($post_ar){
				if(!empty($post_ar))
				{
					extract($post_ar);
					$data_ar = array(
						'trackId' => $trackId,
						'userId' =>  $userId,
						'ipAddress' => get_client_ip(),
						'createdDate' => date('Y-m-d H:i:s')
						);
					$this->db->insert('playinglog', $data_ar);		
					$insert_id= $this->db->insert_id();					

					
					
					if($insert_id > 0)
					{
						$this->db->where('id', $trackId);
						$this->db->set('plays', '`plays`+ 1', FALSE);
						$this->db->update('tracks');
						/*$track_count = getvalfromtbl("plays","tracks","id='".$trackId."'");	*/			
						$response["status"] = "success";
						/*$response["count"] = $track_count["plays"];	*/
						$query = $this->db->query("SELECT tt.plays,tt.perLink,u.profileLink FROM tracks as tt,users as u WHERE tt.id = '".$trackId."' AND tt.userId = u.id");

						$row = $query->row_array();	


						if(!empty($row))
						{
							$response["count"] = $track_count = $row["plays"];
							$response["perLink"] = base_url().$row["profileLink"]."/".$row["perLink"];

						}

					}else{
						$response["status"] = "error";
						$query = $this->db->query("SELECT tt.plays,tt.perLink,u.profileLink FROM tracks as tt,users as u WHERE tt.id = '".$trackId."' AND tt.userId = u.id");
						
						$row = $query->row_array();
						if(!empty($row))
						{
							$response["count"] = $track_count = $row["plays"];
							$response["perLink"] = base_url().$row["profileLink"]."/".$row["perLink"];

						}
					}
				}
				else{
					$response["status"] = "error";
					$response["msg"] = "Track number not increased.";
				}
				return $response;
			}		
			/*Function to increase counter of track*/


			/*album delete*/
			function album_delete($userId,$albumId){
				$this->load->model("uploadm");
				if($userId > 0 && $albumId > 0)
				{
					$this -> db -> select('id');
					$this -> db -> from('tracks');
					$where = "(albumId = '".$albumId."' AND userId = '".$userId."')";
					$this->db->where($where);	   
					$query = $this -> db -> get();	   
					/*echo $this->db->last_query();*/
					$count = $query -> num_rows();


					if($count > 0)
					{	
						foreach ($query->result_array() as $row)
						{
							$this->uploadm->delete_trackfromdb($userId,$row["id"],"au");
						}
					}
					else{

					}

					$albumInfo = getvalfromtbl("*","albums","id='".$albumId."' AND userId = '".$userId."'");

					/*Unlink a photo from folder*/
					$albumphoto_counter = getvalfromtbl("count(*)","photos","detailId='".$albumId."' AND type = 'a'");

					if($albumphoto_counter > 1)
					{

					}
					else{
						$albumphoto = getvalfromtbl("*","photos","detailId='".$albumId."' AND type = 'a'");

						$filename =  asset_path()."upload/".$albumphoto["dir"].$albumphoto["name"];

						if(file_exists($filename))
						{
							unlink($filename);
						}							
					}					
					/*Unlink a photo from folder*/

					$this->db->where('id', $albumId);
					$this->db->where('userId', $userId);
					$this->db->delete('albums');
					/*echo $this->db->last_query();*/
					$response["status"] = "success";
					$response["msg"] = $albumInfo["name"]." deleted successfully.";				
				}else{
					$response["status"] = "fail";
					$response["msg"] = " Fail.";
				}
				return $response;
			}
			/*album delete*/

			/*album delete*/
			function playlist_delete($userId,$playlistId){
				if($userId > 0 && $playlistId > 0)
				{
					$playlistInfo = getvalfromtbl("*","playlist","id='".$playlistId."' AND userId = '".$userId."'");

					/*Unlink a photo from folder*/
					$playlistpic_counter = getvalfromtbl("count(*)","photos","detailId='".$playlistId."' AND type = 'pl'");

					if($playlistpic_counter > 1)
					{

					}
					else{
						$albumphoto = getvalfromtbl("*","photos","detailId='".$playlistId."' AND type = 'pl'");

						$filename =  asset_path()."upload/".$albumphoto["dir"].$albumphoto["name"];

						if(file_exists($filename))
						{
							unlink($filename);
						}							
					}					
					/*Unlink a photo from folder*/

					$this->db->where('id', $playlistId);
					$this->db->where('userId', $userId);
					$this->db->delete('playlist');
					$response["status"] = "success";
					$response["msg"] = $playlistInfo["name"]." deleted successfully.";
				}
				return $response;

			}
			/*album delete*/

			function generate_tracks_waveform($param = NULL){
				$this->load->library('MP3Waveform');
				if(!empty($param))
				{
					extract($param);
				}
				if($userid != NULL && $userid > 0)
				{
					$where = "t.userId = '".$userid."' AND t.waveGenerated = 'n'";
				}
				else{
					$where = "t.waveGenerated = 'n'";	
				}
				$this -> db -> select('*');
				$this -> db -> from('tracks as t');
				$this->db->where($where);	   
				$query = $this -> db -> get();	   
				$count = $query -> num_rows();
				//echo $count;

				//print $this->db->last_query();
				if($count > 0)
				{	
					foreach ($query->result_array() as $row)
					{
						/*echo "<pre>";
						print_r($row);*/
						/*Update and make status of r*/
						$data_update = array(
							'waveGenerated' => 'r',
							'waveRunningDate' => date('Y-m-d H:i:s')
							);				
						$this->db->where('id', $row['id']);
						$this->db->update('tracks', $data_update); 
						/*Update and make status of r ends*/
						$josn_file_name_ar = explode(".",$row["trackName"]);
						$name = $josn_file_name_ar[0];
						//echo $name;
						if($name != "")
						{
							/*var_dump(file_exists(asset_path()."upload/wavejson/".$name.".json"));*/
							//check if josn exist or notifications
							if (!file_exists(asset_path()."upload/wavejson/".$name.".json")) 
							{	
								//generate a new json file of same name
								//generate waveform and copy it to a folder
								//echo "a";
								$session_user_id = $row["userId"];
								$new_physical_path = asset_path()."upload/media/".$session_user_id."/".$row["trackName"];
								/*echo $new_physical_path;*/
								$array["wave_path"] = asset_upload_path().'wavejson/';
								$array["temp_path"] = asset_path().'temp/'.$this->sess_userid."/";
								$array["file_name"] = $new_physical_path;
								$array["json_file_name"]=$name;
								$w= new MP3Waveform;
								$w->set_path_detail($array);
								$w->convertToWavAndGenerateWaveData();
								
								/*Make status to y and */
								/*Update and make status of r*/
								$data_update_w = array(
									'waveGenerated' => 'y',
									'waveCompletedDate' => date('Y-m-d H:i:s')
									);				
								$this->db->where('id', $row['id']);
								$this->db->update('tracks', $data_update_w); 

							}
							//check if josn exist or not ends
						}else
						{
							
						}
					}
				}	
				else{

				}
			}			
}//class over
?>