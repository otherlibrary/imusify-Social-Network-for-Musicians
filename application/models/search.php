<?php
Class search extends CI_Model
{
	function __construct()
	{
        // Call the Model constructor
		parent::__construct();
		$this->load->model('commonfn');
		$this->sess_uid = $this->session->userdata('user')->id;
	}
	
	/*similar songs*/
	function search_records($keyword,$cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$userId = NULL)
	{
		if($keyword == "" || $keyword == NULL)
			return "";

		$userId = ($userId != NULL) ? $userId : $this->sess_uid;	
		$i = 1;
		$output = array();
		$final_output = array();
		$this -> db -> select('id,username,firstname,lastname,profileLink');
		$this -> db -> from('users');

		if($cond != NULL)
			$where = "(status='y' AND (username LIKE '%".$keyword."%' OR firstname LIKE '%".$keyword."%' OR lastname LIKE '%".$keyword."%') AND ".$cond.")";	
		else
			$where = "(status='y' AND (username LIKE '%".$keyword."%' OR firstname LIKE '%".$keyword."%' OR lastname LIKE '%".$keyword."%'))";

		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("id","DESC");
		$query = $this -> db -> get();	   

		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row["artist_pic"] = $this->commonfn->get_photo('p',$row["id"],200,200);
				$row["link"] = base_url().$row["profileLink"];

				$output[] = $row;
			}
			$final_output["artists"] = $output;
		}
			//Artists ends

		/*tracks ends*/
		$this -> db -> select('tt.id,tt.title,tt.perLink,u.profileLink');
		$this -> db -> from('tracks as tt');
		$this->db->join('users as u', 'tt.userId = u.id','left');
		/*if($cond != NULL)
			$where = "(status='y' AND title LIKE '%".$keyword."%' AND ".$cond.")";	
		else
		$where = "(status='y' AND title LIKE '%".$keyword."%')";*/
		if($userId > 0)
			$where = "(tt.status='y' AND tt.title LIKE '%".$keyword."%') AND ((tt.isPublic='n' and tt.userId='".$userId."') or tt.isPublic='y')";	
		else
			$where = "(tt.status='y' AND tt.isPublic='y' AND tt.title LIKE '%".$keyword."%')";
		
		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("tt.id","DESC");
		$query_track = $this -> db -> get();	   
	   	//print $this -> db ->last_query();
		if($query_track -> num_rows() > 0)
		{
			foreach ($query_track->result_array() as $row_track)
			{
				$row_track["index"] = $i;
				$row_track["trackLink"] = base_url().$row_track["profileLink"]."/".$row_track["perLink"];
				$output1[] = $row_track;
				$i++;
			}
			$final_output["tracks"] = $output1;
		}
			//tracks ends
		   //	dump($final_output);


		//playlists	
		$this -> db -> select('id,name,perLink');
		$this -> db -> from('playlist');

		if($cond != NULL)
			$where = "(status='y' AND name LIKE '%".$keyword."%' AND ".$cond.")";	
		else
			$where = "(status='y' AND name LIKE '%".$keyword."%')";

		if($limit != NULL)
			$this -> db -> limit($limit);

		$this->db->where($where);	   
		$this->db->order_by("id","DESC");
		$query_pl = $this -> db -> get();	   
	   		//print $this -> db ->last_query();
		if($query_pl -> num_rows() > 0)
		{
			foreach ($query_pl->result_array() as $row_pl)
			{
				$row_pl["playlist_pic"] = $this->commonfn->get_photo('pl',$row_pl["id"],200,200);
				
				$row_pl["link"] = base_url()."sets/".$row_pl["perLink"];

				$output2[] = $row_pl;
			}
			$final_output["playlists"] = $output2;
		}
			//playlists ends
		return $final_output;
	}


	/*similar songs*/
	function advanced_search_records($condition = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$search_subgenre = NULL,$user_id = NULL)
	{
		$i = 1;
		$output = array();
		$userId = ($userId != NULL) ? $userId : $this->sess_uid;	
		/*if($cond != NULL)
			$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id AND ".$cond."";	
		else				
		$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id";*/
		if($userId > 0)
			$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id AND ((tt.isPublic='n' and tt.userId='".$userId."') or tt.isPublic='y')";	
		else
			$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id AND tt.isPublic='y'";

		if($condition != NULL)
			$cond .= " AND ".$condition;


		if($limit != NULL)
			$limit = " LIMIT ".$limit." ";

		if($orderby != NULL)
			$orderby ="ORDER BY tt.id DESC,".$orderby;	
		else
			$orderby ="ORDER BY tt.id DESC";	
		if($search_subgenre != NULL)
			$query = $this->db->query("SELECT tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.label,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,g.genre,u.firstname as artist_name,u.lastname,u.profileLink,al.name as album FROM tracks as tt,genre as g,users as u,albums as al,track_genre as tg  ".$cond." ".$orderby." ".$limit." ");
		else	
			$query = $this->db->query("SELECT tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.label,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,g.genre,u.firstname as artist_name,u.lastname,u.profileLink,al.name as album FROM tracks as tt,genre as g,users as u,albums as al ".$cond." ".$orderby." ".$limit." ");

		$records_count = $query->num_rows();
			//print_query();	
		if($counter != NULL)
			return $records_count;		

		if($records_count > 0)
		{
			$i = 1;

			if($start_limit != NULL)
				$i = $start_limit+1;

			foreach ($query->result_array() as $row)
			{
				$row["track_image"] = $this->commonfn->get_photo('t',$row["id"]);
				$row["i"] = $i;
				if($i %2 != 0)
					$row["gray_bg"] = "gray-bg";
				else
					$row["gray_bg"] = "";

				$row["main_title"] = $row["title"];
				$row["total_songs"] = "";				
				$row["waveform"] = base_url()."waveform/".$row["profileLink"]."/".$row["perLink"].".json";
				$row["trackLink"] = base_url().$row["profileLink"]."/".$row["perLink"];
				$row["profileLink"] = base_url().$row["profileLink"];
				$output[] = $row;					
				$i++;
			}
		}else{
			$output["status"] = "fail";
		}
			//echo print_query();

			//playlists ends
		return $output;
	}


	/*advanced search records...*/
	function search_track_records($cond = NULL,$limit = NULL,$orderby = NULL,$counter = NULL,$userId = NULL)
	{
		$i = 1;
		$output = array();		
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;	
		/*if($cond != NULL)
			$where = "(tt.status='y' ".$cond.")";	
		else
			$where = "(tt.status='y')";	*/

		if($userId > 0)
			$where = " WHERE (tt.status='y') AND ((tt.isPublic='n' and tt.userId='".$userId."') or tt.isPublic='y')";
		else
			$where = " WHERE (tt.status='y') AND tt.isPublic='y'";

		if($cond != NULL)
			$where .= " AND ".$cond;

		$this->db->select('DISTINCT(tt.id),tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.label,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,g.genre,u.firstname as artist_name,u.lastname,u.profileLink,al.name as album');
		$this->db->from('tracks as tt');
		$this->db->join('track_genre as tg', 'tt.id = tg.trackId','left');
		$this->db->join('genre as g', 'tt.genreId = g.id','left');
		$this->db->join('albums as al', 'tt.albumId = al.id','left');
		$this->db->join('track_moods as tm', 'tt.id = tm.trackId','left');
		$this->db->join('track_instruments as ti', 'tt.id = ti.trackId','left');
		$this->db->join('users as u', 'tt.userId = u.id','left');
		$this->db->where($where);

		if($limit != NULL)
			$this ->db-> limit($limit);

			//$this->db->order_by();			
		$query = $this->db->get();
			//print_query();
		$records_count = $query->num_rows();

		if($counter != NULL)
			return $records_count;		

		if($records_count > 0)
		{
			$i = 1;

			if($start_limit != NULL)
				$i = $start_limit+1;

			foreach ($query->result_array() as $row)
			{
				$row["track_image"] = $this->commonfn->get_photo('t',$row["id"]);
				$row["i"] = $i;
				if($i %2 != 0)
					$row["gray_bg"] = "gray-bg";
				else
					$row["gray_bg"] = "";

				$row["main_title"] = $row["title"];
				$row["total_songs"] = "";				
				$row["waveform"] = base_url()."waveform/".$row["profileLink"]."/".$row["perLink"].".json";
				$row["trackLink"] = base_url().$row["profileLink"]."/".$row["perLink"];
				$row["profileLink"] = base_url().$row["profileLink"];
				$output[] = $row;					
				$i++;
			}
		}else{
			$output["status"] = "fail";
		}
		//echo print_query();
		//playlists ends
		return $output;
	}




}//modal over
?>