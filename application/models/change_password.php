<?php
Class Change_password extends CI_Model
{
	 function chgPassword($password,$nw_password)
	 {
		
		$query="select password from users where id= ? "; 
		$db_password = $this->db->query($query,array($this->session->userdata(ADMIN_SESSION_NAME)->id))->row()->password;
		
		if((md5($password) == $db_password) && $nw_password !="") 
		{
			$nw_password = md5($nw_password);
			
			$sql = "Update users SET password= ? WHERE id= ? ";
			$this->db->query($sql, array($nw_password, $this->session->userdata(ADMIN_SESSION_NAME)->id));
			
			if($this->db->affected_rows() > 0)
			{
				return true;
			}
			else
			{
				return false ;
			}			
		}
	 }
	
	
}
?>