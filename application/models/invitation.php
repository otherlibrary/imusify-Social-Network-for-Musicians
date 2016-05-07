<?php
Class Invitation extends CI_Model
{
	function __construct(){
		if($this->session->userdata('user'))		
			$this->sess_id = $this->session->userdata('user')->id;	
	}
	
	//Function to get genre from database
	function get_invitation_code($cond = NULL,$limit = NULL,$start_limit = NULL,$counter = NULL)
	{
		$output = array();
		$this -> db -> select('id,code');
		$this -> db -> from('invitation_code');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		
		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}

		$this->db->where($where);	   
		$this->db->order_by("id");
		$query = $this -> db -> get();	   
	 	if($counter == "counter")
			return $query -> num_rows();

		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[] = $row;
			}
			return $output;
		}	   
	}

	/*check if email invited*/
	function email_invited_check_exist($email = NULL,$invitationcode = NULL){
		$where = "(ud.email='".$email."' AND ud.status = 'y' AND ic.code = '".$invitationcode."')";
		$this->db->select('ud.id,ud.fromId');	
		$this->db->from('invitation_user_detail as ud');
		$this->db->join('invitation_code as ic', 'ud.codeId = ic.id','left');
		$this->db->where($where);
		$this ->db-> limit(1);
		$query = $this -> db -> get();	
		$row =   $query->row_array();
		//echo $this->db->last_query();
	   	if($query -> num_rows() == 1)
	   	{
			return $row["fromId"];	
	 	}
	   	else
	   	{
	   		/*You are not invited yet...*/
	   		return false;	
	   	}	   
	}	
	/*check if email invited ends*/


	/**/
	function get_user_invitation_code($userId = NULL){

			$userId = ($userId != NULL) ? $userId : $this->sess_id;
			$email = getvalfromtbl("email","users","id='".$userId."'","single");
			$i = 1;
			$output = array();		
			$where = "ud.email = '".$email."'";
			$this->db->select('ic.code');			
			$this->db->from('invitation_user_detail as ud');
			$this->db->join('invitation_code as ic', 'ud.codeId = ic.id','left');
			$this->db->where($where);
			$this ->db-> limit(1);
			
			$query = $this->db->get();
			//echo $this->db->last_query();
			$records_count = $query->num_rows();
				
			if($counter != NULL)
				return $records_count;		

			if($records_count > 0)
			{	
					$row = $query->row_array();
					return $row["code"];						
					
			}else{
				return "";
			}			
			
	}
	/**/

	
}//class over
?>