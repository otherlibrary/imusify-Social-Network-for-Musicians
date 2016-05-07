<?php
Class album_model extends CI_Model
{
	function get_album_details($album_id = 0)
	{	
		$response = array();
		if($album_id > 0)
		{
			$this -> db -> select('*');
			$this -> db -> from('albums');
			$this->db->where("id",$album_id);
			$query = $this -> db -> get();	
			if($query -> num_rows() > 0)
			{
				$response = $query->row_array();
				$response["list"] = $this->get_album_songs($album_id);
			}
		}
		return $response;
	}


	function get_album_songs($album_id = 0)
	{
		$response = array();
		$this -> db -> select('*');
		$this -> db -> from('tracks');
		$this->db->where("albumId",$album_id);
		$query = $this -> db -> get();	
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$response[] = $row;
			}
		}
		return $response;
	}


}
?>