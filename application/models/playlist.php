<?php
Class playlist_model extends CI_Model
{
	//Function to get roles from database
	function get_playlists(){
		$query = $this->db->get_where('playlist', array('status' => 'y');
		$playlist=array();
		foreach ($query->result() as $row)
		{
			$playlist[]=$row;
		}
		print_r($playlist);
		exit;
	}
}
?>