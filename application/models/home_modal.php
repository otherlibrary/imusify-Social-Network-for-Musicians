<?php
Class home_modal extends CI_Model
{
	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->model('commonfn');
		$this->sess_uid = $this->session->userdata('user')->id;
	}
	
	/*similar songs*/
	function get_my_notification($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL)
	{


		$output = array();

		if($cond != NULL)
			$cond = " WHERE n.toId = '".$this->sess_uid."' AND u.id = n.fromId AND ".$cond."";	
		else				
			$cond = " WHERE n.toId = '".$this->sess_uid."' AND u.id = n.fromId ";

		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY n.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY n.id DESC";	

		$query = $this->db->query("SELECT n.id,n.notification,n.fromId,n.createdDate,u.firstname,u.lastname,u.profileLink FROM notification as n,users as u ".$cond." ".$orderby." ".$limit." ");

		if($counter != NULL)
			return $query->num_rows();	

			//echo print_query();
		foreach ($query->result_array() as $row)
		{
			$row["user_pic"] = $this->commonfn->get_photo('p',$row["fromId"]);
			$row["timeago"] = timeago($row["createdDate"]);

			$output[] = $row;			
		}
		return $output;
	}
	/*similar songs ends*/

	/*Function for fetching new tracks*/
	function get_overview($condition = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$start_limit = NULL,$width = NULL,$height = NULL,$images_only = NULL,$userId = NULL,$uwidth = NULL,$uheight = NULL)
	{
		$userId = ($userId != NULL) ? $userId : $this->sess_uid;
		$this->load->model('commonfn');
		$output = array();
		if($userId > 0)
			$cond = " tt.status = 'y' AND ((tt.isPublic='n' and tt.userId='".$userId."') or tt.isPublic='y')";	
		else
			$cond = " tt.status = 'y' AND tt.isPublic='y'";


		if($condition != NULL)
			$cond .= " AND".$condition;

		if($orderby != NULL)
			$orderby ="ORDER BY tt.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY tt.id DESC";	

		$this->db->select('tt.id,tt.featured,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,tt.isPublic,tt.userId as trackuserid,g.genre,u.firstname as artist_name,u.lastname,u.id as uid,u.profileLink,a.name as album');
		$this->db->from('tracks as tt');
		$this->db->join('genre as g', 'tt.genreId = g.id','left');
		$this->db->join('albums as a', 'tt.albumId = a.id','left');
		$this->db->join('users as u', 'tt.userId = u.id','left');

		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}

		$this->db->where($cond);
		$this->db->order_by("tt.id","desc");			
		$query = $this->db->get();

		$total_counter = $query->num_rows();

		if($counter != NULL)
			return $query->num_rows();
		$i = 1;
		if($start_limit != NULL)
			$i = $start_limit+1;

		foreach ($query->result_array() as $row)
		{
			$row["track_image"] = $this->commonfn->get_photo('t',$row["id"],$width,$height);
			$row["user_image"] = $this->commonfn->get_photo('p',$row["uid"],$uwidth,$uheight);
			$row["main_title"] = character_limiter($row["title"], 20,$end_char = '&#8230;');				
			$row["artist_mini_name"] = character_limiter($row["artist_name"], 20,$end_char = '&#8230;');		
			$row["i"] = $i;
			$row["total_songs"] = "";
			$row["featured"]= ($row["featured"]=='y'?true:false);
				//$row["waveform"] = img_url()."wave1.png";
			$row["waveform"] = base_url()."waveform/".$row["profileLink"]."/".$row["perLink"].".json";
			$row["wave"] = img_url().'hover-wave-img.png';
			$row["trackLink"] = base_url().$row["profileLink"]."/".$row["perLink"];
			$row["profileLink"] = base_url().$row["profileLink"];				
			$row["album"] = ($row["album"] == "" || $row["album"] == NULL) ? "-" : $row["album"]; 
			$row["is_track"] = true;
			$row["like_js_class"] = "like_js";
			$row["like_class"] = "like-icon";

			if($userId > 0){
				$is_liked = getvalfromtbl("id","likelog"," userId = '".$userId."'  AND trackId = '".$row["id"]."'","single","");
				if($is_liked > 0){
					$row["like_js_class"] = "dislike_js";	
					$row["like_class"] = "unlike-icon";
				}				
			}
			$output[] = $row;
			$i++;				
		}

		

		/*Fetch Articles*/
		$cond2 = "a.status = 'y' AND a.headlineDisp = 'y'";
		$this->db->select('a.*');
		$this->db->from('articles as a');
		$this->db->where($cond2);
		$this->db->order_by("a.id","desc");		
		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}	
		$query2 = $this->db->get();

		$total_counter += $query2->num_rows();

		if($counter != NULL)
			return $query2->num_rows();
		$i = 1;
		if($start_limit != NULL)
			$i = $start_limit+1;

		foreach ($query2->result_array() as $row)
		{
			$row["article_image"] = $this->commonfn->get_photo('art',$row["id"],371,371);	
			$row["is_article"] = true;
			$output[] = $row;
		}
		/*Fetch Articles ends*/
		$ord = array();
		foreach ($output as $key => $value){
			$ord[] = strtotime($value['createdDate']);
		}
		array_multisort($ord, SORT_DESC, $output);
		/*echo "<pre>";
		print_r($output);
		exit();*/
		if($limit > $total_counter)
		{
			/*call again this function*/
		}
		return $output;
	}

}//modal over
?>