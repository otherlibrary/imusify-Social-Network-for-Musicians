<?php
Class about_model extends CI_Model
{
	function get_about_detail($cond = NULL,$counter = NULL)
	{
		$this->load->model("commonfn");
		$output = array();
		$team_array = array();
		$ip_array = array();
		$final_ip_array = array();
		$this -> db -> select('*');
		$this -> db -> from('about_members');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		$this->db->where($where);	   
		$this->db->order_by("type");
		$this->db->order_by("sequence");
		$query = $this -> db -> get();	   
		if($counter == "counter")
			return $query -> num_rows();
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row["photo"] = $this->commonfn->get_photo("ab",$row["id"],166,166);
				if($row["type"] == "t")
				{
					$team_array[] = $row;
				}
				else if($row["type"] == "ip"){
					$ip_array[] = $row;
				}
				/*$output[] = $row;*/
			}
			$ip_array_count = count($ip_array);
			$ip_array_page_count = 	ceil($ip_array_count/4);
			$k = 0;
			for($i=0;$i<$ip_array_page_count;$i++){
				if($i == 0)
					$final_ip_array[$i]["active"] = "active";
				for($j=0;$j<4;$j++){
					$final_ip_array[$i]["team_members"][] = $ip_array[$k];
					$k++;	
				}
			}
			$output["team"] = $team_array;
			$output["ip"] = $final_ip_array;
			return $output;
		}
		else{
			return false;
		}	   
	}


	function get_counter()
	{
		/*SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( `timelength` ) ) ) AS total_time FROM `tracks`*/
		$output = array();
		$query = $this->db->query("SELECT SEC_TO_TIME( SUM( TIME_TO_SEC( `timelength` ) ) ) AS total_time,(SELECT COUNT(id) from tracks) AS total_tracks,(SELECT COUNT(id) from likelog) as total_track_like,(SELECT count(id) from playlist) as total_playlist FROM `tracks`");
		$row = $query->row_array();	
		$output["total_time"] = $row["total_time"];
		$output["total_tracks"] = $row["total_tracks"];
		$output["total_track_like"] = $row["total_track_like"];
		$output["total_playlist"] = $row["total_playlist"];
		return $output;		   
	}




}
?>