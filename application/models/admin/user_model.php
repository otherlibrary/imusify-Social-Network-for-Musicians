<?php
Class user_model extends CI_Model
{
	function get_users()
	{
		$this -> db -> select('id,email,fullname,username,status');
	   $this -> db -> from('users');
	   
	   $where = "usertype!='a' ";
	   $this->db->where($where);
	   

	   $query = $this -> db -> get();
	   
	   $output = array(
		"sEcho" => intval($_GET['sEcho']),
		"iTotalRecords" => 1,
		"iTotalDisplayRecords" => 1,
		"aaData" => array()
	);
	   if($query -> num_rows() == 1)
	   {
			$user=$query->row();
			foreach($user as $us)
			{
				$output['aaData'][] = $us;
			}
			print json_encode($output);
			exit;
	   }
	   
	}
	
}
?>