<?php
Class explore extends CI_Model
{
	
	function __construct(){
			$session_user_id = $this->session->userdata('user')->id;
			$this->sess_userid = $session_user_id;		
	}
	
	function fetch_user_tracks($cond = NULL,$limit = NULL)
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
			
			$query = $this->db->query("SELECT tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.label,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,g.genre FROM tracks as tt,genre as g ".$cond." ".$orderby." ".$limit." ");
			
			//print_query();
			/* echo '<pre>';
				 print_r($this->db->last_query());
			 echo '</pre>';*/			
			
			foreach ($query->result_array() as $row)
			{
					
				$row["track_image"] = $this->commonfn->get_photo('t',$row["id"]);
				$row["editid"] = $row["id"];
				$row["edittype"] = "t";
				$row["main_title"] = $row["title"];
				$row["total_songs"] = "";
				$row["song_list"][] = $row;
			//	$row["song_list"] = "";
				$row["row_id"] = "music_row";
				
				$output[] = $row;					
			}
			return $output;
	}

		
	
	/*function for fetch perticular genre of a song*/
	/*Fetch db records*/
	function track_db_genres($trackId)
	{
		
		//$user_exists_role = array();
		$track_exists_role = array();
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
				//print_r($data);	
				foreach ($data as $row)
				{
					$track_exists_role[] = $row["genreId"];
				}
		}		
		return $track_exists_role;		
	}
}
?>