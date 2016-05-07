<?php
Class user_profile extends CI_Model
{

	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->sess_uid = $this->session->userdata('user')->id;		
	}
	
	//Function to get roles from database
	function get_user_roles($cond = NULL,$limit = NULL,$flag = NULL)
	{
		if($flag == "true")
			$this -> db -> select('id');
		else
			$this -> db -> select('id,role');

		$this -> db -> from('user_roles');

		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";

		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("role");
		$query = $this -> db -> get();	   
	   //print $this -> db ->last_query();
		if($query -> num_rows() > 0)
		{
			
			if($flag == "true")
			{
				//$user_exists_role = $query->result_array();
				$data = $query->result_array();	
				//print_r($data);	
				foreach ($data as $row1)
				{
					$output[] = $row1["id"];
				}				
			}else{

				foreach ($query->result_array() as $row)
				{
					$output[] = $row;
				}
			}
			return $output;
		}	   
	}	
	
	function udiffCompare($a, $b)
	{
		return $a['roleId'] - $b['roleId'];
	}
	
	/*Fetch db records*/
	function user_db_roles($flag = "default")
	{
		
		$user_exists_role = array();
		$session_user_id = $this->session->userdata('user')->id;
		$data = array();
		if($flag == "roles")	
			$this -> db -> select('roleId');
		else if($flag == "all")
			$this -> db -> select('role');
		else 
			$this -> db -> select('id,roleId');

		$this -> db -> from('user_roles_details');	 

		if($flag == "all")
			$this -> db -> join('user_roles', 'user_roles.id = user_roles_details.roleId');


		$where = "(userId='".$session_user_id."')";
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			if($flag == "roles")
			{
				//$user_exists_role = $query->result_array();
				$data = $query->result_array();	
				//print_r($data);	
				foreach ($data as $row)
				{
					$user_exists_role[] = $row["roleId"];
				}				
			}
			else if($flag == "all"){
				foreach ($query->result_array() as $row)
				{
					$user_exists_role[] = $row["role"];
				}
			}
			else{
				foreach ($query->result_array() as $row)
				{
					$user_exists_role[] = $row;
				}		
			}	
		}		
		return $user_exists_role;		
	}
	
	/*Modal save user roles...*/
	function insert_roles($roles)
	{
		$session_user_id = $this->session->userdata('user')->id;
		$i = 0;		
		$data = array();		
		$user_exists_role =  $this->user_db_roles("roles");					

				//admin set default roles
		$user_def_db_roles = $this->get_user_roles("is_default='y'","","true");

		$f_array = array_merge($roles,$user_def_db_roles);

		if(count($user_def_db_roles) > 0)
		{
			$roles = array_unique($f_array);
		}
		$post_count = count($roles);
		$db_count = count($user_exists_role);				
		if($db_count > 0)
		{
			$diff = array_diff($user_exists_role,$roles);
			/*dump($user_exists_role);*/
			if(!empty($diff))
			{
				$this->db->where('userId', $session_user_id);
				$this->db->where_in('roleId', $diff);
				$this->db->delete('user_roles_details');
			}
							//echo $this->db->last_query();
		}

		/*dump($roles);
		dump($user_exists_role);*/
		$diff1 = array_diff($roles,$user_exists_role);
		/*var_dump($diff1);*/
		if(!empty($diff1))
		{
			foreach($diff1 as $roles1) {
							//$arr[$i] = $roles;
				$data[$i]["userId"] = $session_user_id;
				$data[$i]["roleId"] = $roles1;
				$data[$i]["createdDate"] = date('d:m:Y H:m:s');
				$data[$i]["ipAddress"] = get_client_ip();
				$i++;
			};					
			$this->db->insert_batch('user_roles_details', $data);
		}

		$data_update = array(
			'role_added' => 'y'
			);				
		$this->db->where('id', $session_user_id);
		$this->db->update('users', $data_update); 

		return "Success";		
	}
	
	//Function to get user details...
	function get_user_details($profileLink = NULL,$cond = NULL,$flag = NULL)
	{
		//echo "a".$profileLink;
		/*Get id*/
		$this -> db -> select('id');			
		$this -> db -> from('users');	   
		$where = "profileLink='".$profileLink."'";
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		if($query -> num_rows() > 0)
		{
			$data=$query->row();
			$userid = $data->id;		
		}		
		else{
			return "usernotexist";
		}
		//echo $this->db->last_query();		
		$query = $this->db->query("select u.id,u.countryId, u.cityId, u.stateId, u.gender,u.firstname,u.lastname,u.username,u.description,u.dob_m,u.dob_d,u.dob_y,u.weburl,(SELECT COUNT(id) from followinglog where toId = '".$userid."') as followers, (SELECT COUNT(id) from followinglog where fromId = '".$userid."') as following,(SELECT COUNT(id) from tracks where userId = '".$userid."') as tracks,(SELECT COUNT(id) from albums where userId = '".$userid."') as albums,(SELECT name from location where location.location_id = u.countryId) as countryName,(SELECT name from location where location.location_id = u.cityId) as cityName from users as u where profileLink = '".$profileLink."'");

		$user_roles_ar = $this->user_db_roles("all");
		$row = $query->result_array();
		$row[0]["user_roles_ar"] = $user_roles_ar;
		
		return $row[0];	
	} 
	
	/*update_profile*/
	function update_profile($fname,$lname,$country,$website,$description,$mm,$dd,$yy,$image,$folder_name,$state,$city)
	{
                
                //$description = ereg_replace( "\n",'|', $description);
                //echo ($description);
		//exit;
                //$description = nl2br($description);      
                $description = htmlentities($description, ENT_QUOTES);
                
		$data = array(
			'firstname' =>$this->db->escape_str($fname),
			'lastname' =>  $this->db->escape_str($lname),
			'description' => $description,
			'updated' => date('Y-m-d H:i:s'),
			'dob_m' => $this->db->escape_str($mm),
			'dob_d' => $this->db->escape_str($dd),
			'dob_y' => $this->db->escape_str($yy),	
			'weburl' => $this->db->escape_str($website),
			'countryId' => $this->db->escape_str($country),
			'stateId' => $this->db->escape_str($state),
			'cityId' => $this->db->escape_str($city)

			);			
		$where = "(id ='".$this->sess_uid."' )";			
		$this->db->where($where);
		$this->db->update('users', $data);

		/*Insert to photo table entry*/
                //do not update photo again
                /*
		$img_exist = getvalfromtbl("id","photos"," type='p' AND detailId = '".$this->sess_uid."'","single","");

		if($img_exist > 0)
		{
			$row = getvalfromtbl("*","photos"," type='p' AND detailId = '".$this->sess_uid."'");
                        $path = asset_path().$row["dir"].$row["name"];
			if (file_exists($path)) unlink($path);

			$data = array(
				'dir' => $folder_name,
				'name' => $image
				);			
			$where = "(id ='".$row["id"]."' )";			
			$this->db->where($where);
			$this->db->update('photos', $data);				
		}
		else
		{				
			$data_u_photo = array(
				'detailId' =>  $this->sess_uid,
				'dir' => $folder_name,
				'name' => $image,
				'type' => 'p'
				);
			$this->db->insert('photos', $data_u_photo);						
		}
                */
		return "success";

	} 
	
	/*Update cover photo*/
	
	/*update_profile*/
	function update_cover_profile($image,$folder_name)
	{		
		/*Insert to photo table entry*/

		$img_exist = getvalfromtbl("id","photos"," type='pc' AND detailId = '".$this->sess_uid."'","single","");

		if($img_exist > 0)
		{
			$row = getvalfromtbl("*","photos"," type='pc' AND detailId = '".$this->sess_uid."'");				
			unlink(asset_path().$row["dir"]."/".$row["name"]);

			$data = array(
				'dir' => $folder_name,
				'name' => $image
				);			
			$where = "(id ='".$row["id"]."' )";			
			$this->db->where($where);
			$this->db->update('photos', $data);				
		}
		else
		{				
			$data_u_photo = array(
				'detailId' =>  $this->sess_uid,
				'dir' => $folder_name,
				'name' => $image,
				'type' => 'pc'
				);
			$this->db->insert('photos', $data_u_photo);						
		}			
		return "success";
	} 



	/*Listened songs*/
	function fetch_listened_tracks($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL)
	{
		$this->load->model('commonfn');

		$output = array();

		if($cond != NULL)
			$cond = " WHERE tt.status = 'y' AND tt.userId = u.id AND pl.trackId = tt.id AND ".$cond."";	
		else				
			$cond = " WHERE tt.status = 'y' AND tt.userId = u.id AND pl.trackId = tt.id";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY pl.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY pl.id DESC";	

		$query = $this->db->query("SELECT tt.id,tt.title,tt.perLink,u.profileLink,u.firstname as artist_name,u.lastname FROM tracks as tt,users as u,playinglog as  pl ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	

			//echo print_query();
		foreach ($query->result_array() as $row)
		{
			$row["tab_image"] = $this->commonfn->get_photo('t',$row["id"],IMG_235,IMG_235);
				//$row["tab_name"] = $row["title"];
			$row["tab_name"] = character_limiter($row["title"], 20, $end_char = '&#8230;');
				//$row["tab_waveform"] = img_url()."wave1.png";
			$row["tab_waveform"] = base_url()."waveform/".$row["profileLink"]."/".$row["perLink"].".json";
			$row["link"] = base_url().$row["profileLink"]."/".$row["perLink"];
			$row["role"] = "trackdetail";

			$output[] = $row;					

		}
		return $output;
	}
	/*Listened songs ends*/
	
	/*Uploaded songs*/
	function fetch_uploaded_tracks($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$startlimit = NULL)
	{
		$this->load->model('commonfn');

		$output = array();

		if($cond != NULL)
			$cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.albumId = a.id AND ".$cond."";	
		else				
			$cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.albumId = a.id";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY tt.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY tt.id DESC";	

		$query = $this->db->query("SELECT tt.id,tt.title,tt.timelength,tt.perLink,u.profileLink,u.firstname as artist_name,u.lastname,a.name as album_nm FROM tracks as tt,users as u,albums as a ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	
			//echo print_query();
		$i = 1;
		if($startlimit != NULL)
			$i = $startlimit+1;	

		foreach ($query->result_array() as $row)
		{				
			$row["tab_image"] = $this->commonfn->get_photo('t',$row["id"]);
			$row["index"] = $i;	

			if($i % 2 != 0)
				$row["gray_bg"] = "gray-bg";
			else
				$row["gray_bg"] = "";

				//$row["song_name"] = $row["title"];
			$row["song_name"] = character_limiter($row["title"], 20, $end_char = '&#8230;');

			$row["album_name"] = $row["album_nm"];
			$row["song_length"] = $row["timelength"];	

			$row["role"] = 	"trackdetail";
			$row["link"] = base_url().$row["profileLink"]."/".$row["perLink"];
			$output[] = $row;
			$i++;			
		}
		return $output;
	}
	/*uploaded songs ends*/
	
	
	/*albums*/
	function fetch_albums($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL)
	{
		$this->load->model('commonfn');

		$output = array();

		if($cond != NULL)
			$cond = " WHERE a.status = 'y' AND ".$cond."";	
		else				
			$cond = " WHERE a.status = 'y'";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY a.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY a.id DESC";	

		$query = $this->db->query("SELECT a.name,a.id FROM albums as a ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	
			//echo print_query();
		$i = 1;
		foreach ($query->result_array() as $row)
		{
			$row["tab_image"] = $this->commonfn->get_photo('a',$row["id"],IMG_235,IMG_235);
			$row["i"] = $i;
			if($i %2 != 0)
				$row["gray_bg"] = "gray-bg";
			else
				$row["gray_bg"] = "";

			$row["tab_name"] = $row["name"];
			$row["tab_waveform"] = img_url()."wave1.png";
			$output[] = $row;					
			$i++;
		}
		return $output;
	}
	/*albums ends*/
	
	/*Followers*/
	function fetch_followers($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL)
	{
		$this->load->model('commonfn');

		$output = array();

		if($cond != NULL)
			$cond = " WHERE  u.status = 'y' AND fl.fromId = u.id AND ".$cond."";	
		else				
			$cond = " WHERE u.status = 'y' AND fl.fromId = u.id";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY fl.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY fl.id DESC";	

		$query = $this->db->query("SELECT u.firstname,u.id,u.lastname,u.username,u.profileLink FROM users as u,followinglog as fl ".$cond." ".$orderby." ".$limit." ");
			//print_query();

		if($counter != NULL)
			return $query->num_rows();	

		foreach ($query->result_array() as $row)
		{
			$row["tab_image"] = $this->commonfn->get_photo('p',$row["id"]);
			$row["tab_name"] = $row["username"];
			$row["tab_waveform"] = img_url()."wave1.png";

			$row["link"] = base_url().$row["profileLink"];
			$row["role"] = "profile";


			$output[] = $row;					

		}
		return $output;
	}
	/*followers ends*/
	
	/*Followings*/
	function fetch_followings($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL)
	{
		$this->load->model('commonfn');

		$output = array();

		if($cond != NULL)
			$cond = " WHERE  u.status = 'y' AND fl.toId = u.id AND ".$cond."";	
		else				
			$cond = " WHERE u.status = 'y' AND fl.toId = u.id";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY fl.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY fl.id DESC";	

		$query = $this->db->query("SELECT u.firstname,u.id,u.lastname,u.username,u.profileLink FROM users as u,followinglog as fl ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	

		foreach ($query->result_array() as $row)
		{

			$row["tab_image"] = $this->commonfn->get_photo('p',$row["id"]);
			$row["tab_name"] = $row["username"];
			$row["total_songs"] = "";
			$row["tab_waveform"] = img_url()."wave1.png";

			$row["link"] = base_url().$row["profileLink"];
			$row["role"] = "profile";

			$output[] = $row;					

		}
		return $output;
	}
	/*Followings ends*/
	
	/*Popular songs*/
	function fetch_popular_tracks($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$startlimit = NULL)
	{
		$output = array();

		if($cond != NULL)
			$cond = " WHERE tt.status = 'y' AND tt.albumId = a.id AND ".$cond."";	
		else				
			$cond = " WHERE tt.status = 'y' AND tt.albumId = a.id";

		if($limit != NULL || $limit != "")
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY tt.plays DESC,".$orderby;	
		else
			$orderby ="ORDER BY tt.plays DESC";	

		$query = $this->db->query("SELECT tt.id,tt.title,tt.timelength,a.name as album FROM tracks as tt,albums as a ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	

			//echo print_query();
		$i = 1;
		if($startlimit != NULL)
			$i = $startlimit + 1;	
		foreach ($query->result_array() as $row)
		{
			$row["i"] = $i;
			if($i %2 != 0)
				$row["gray_bg"] = "gray-bg";
			else
				$row["gray_bg"] = "";
			$row["index"] = $i;		
				//$row["song_name"] = $row["title"];	
			$row["song_name"] = character_limiter($row["title"], 20, $end_char = '&#8230;');		
			$row["album_name"] = $row["album"];
			$row["waveform"] = img_url()."wave1.png";
			$row["song_length"] = $row["timelength"];
			$output[] = $row;					
			$i++;
		}
		return $output;
	}
	
	
	//Function to get all playlist of loggedin user
	function get_my_playlists($cond = NULL,$limit = NULL,$orderby = NULL,$userid = null){
		$this->load->model('commonfn');
		$output = array();
		$userid = ($userid != null) ? $userid : $this->sess_id;
		if($cond != NULL)
			$cond = " WHERE pl.status = 'y' AND userId = '".$userid."' AND".$cond."";	
		else				
			$cond = " WHERE pl.status = 'y' AND userId = '".$userid."'";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY ".$orderby;	
		else
			$orderby ="ORDER BY pl.id DESC";	

		$query = $this->db->query("SELECT pl.id,pl.name,pl.perLink,pl.like,(SELECT COUNT(id) FROM playlist_detail WHERE playlist_id = pl.id AND userId = '".$this->sess_id."') AS tot_songs FROM playlist as pl ".$cond." ".$orderby." ".$limit." ");
			//echo print_query();
		foreach ($query->result_array() as $row)
		{
			$row["song_cover_image"] = $this->commonfn->get_photo('pl',$row["id"]);
			$row["pl_likes"] = $row["like"];
			$row["pl_songs"] = $row["tot_songs"];

			$row["link"] = base_url().$this->session->userdata('user')->profileLink."/sets/".$row["perLink"];

			$output[] = $row;												
		}
		return $output;	
	}	

	function invite_friends($userid = NULL,$post = NULL,$email = NULL){
		$this->load->model("admin/invite_model");
		$userId = ($userid != NULL) ? $userid : $this->sess_uid;
		$email = ($email != NULL) ? $email : $this->session->userdata('user')->email;
		$return_type = $this->invite_model->insert_invite("user",$email,$userId);
		$response = array();
		$msg = NULL;
		if(!empty($return_type) && count($return_type) > 0)
		{	
			if(!empty($return_type))
			{
				foreach ($return_type as $key => $value) {
					$msg .= "<br />".$value." Already Invited.";
				}
			}
			$response["status"] = "success";
			$response["msg"] = $msg;
			return $response;
		}	
		else{
			$response["status"] = "success";
			$response["msg"] = "Your Friends invited successfully.";
			return $response;			
		}




	}

}//modal over
?>