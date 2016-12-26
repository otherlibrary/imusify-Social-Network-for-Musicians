<?php
Class Liked_model extends CI_Model
{	
	function __construct(){
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;		
	}
	
	function fetch_user_liked_tracks($userId = NULL,$cond = NULL,$limit = NULL,$start_limit = NULL,$orderby = NULL,$counter = NULL)
	{
		$this->load->model('commonfn');
		$output = array();
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;

		if($cond != NULL)
			$cond = "  tt.status = 'y' AND l.userId = '".$userId."' AND ".$cond."";	
		else				
			$cond = "  tt.status = 'y' AND l.userId = '".$userId."'";

		if($orderby != NULL)
			$orderby = $orderby;	
		else
			$orderby ="l.id DESC";	

		$this->db->select('tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.userId,tt.perLink,u.profileLink,u.username,g.genre');
		$this->db->from('likelog as l');
		$this->db->join('tracks as tt', 'l.trackId = tt.id','left');
		$this->db->join('genre as g', 'tt.genreId = g.id','left');
		$this->db->join('users as u', 'tt.userId = u.id','left');
		$this->db->where($cond);

		if($limit != NULL)
			$this ->db-> limit($limit);
		
		$this->db->order_by($orderby);
		$query = $this->db->get();
		//echo $this->db->last_query();
		$records_count = $query->num_rows();

		if($counter != NULL)
				return $records_count;

		foreach ($query->result_array() as $row)
		{	
			$row["track_image"] = $this->commonfn->get_photo('t',$row["id"]);
			$row["editid"] = $row["id"];
			$row["edittype"] = "t";
			$row["main_title"] = $row["title"];
			$row["total_songs"] = "";
			$row["song_list"][] = $row;
			$row["row_id"] = "music_row";
                        $row["trackLink"] = base_url().$row["profileLink"]."/".$row["perLink"];
			if($row["userId"] == $userId)
			{
				$row["my_song"] = "true";
			}
			$output[] = $row;					
		}
		return $output;
	}
}
	?>