<?php
Class Message_model extends CI_Model
{	

	function __construct(){
		$this->sess_id = $this->session->userdata('user')->id;	
		$this->load->model("commonfn");
	}

	//Function to get roles from database
	function getMessages($conversationId,$last_msg_id=NULL)
	{
		$messages =array(); 
		$userid=$this->session->userdata('user')->id;
		
		if($last_msg_id > 0)
		{
			$query="select *,content as content_text,if(`from_id`= ? ,'my-box','member-box') as class_nm from message where conversation_id = ? and id = ?"; 
			$result = $this->db->query($query,array($userid,$conversationId,$last_msg_id));
		}
		else
		{
			$query="select *,content as content_text,if(`from_id`= ? ,'my-box','member-box') as class_nm from message where conversation_id = ?"; 
			$result = $this->db->query($query,array($userid,$conversationId));
		}	
		
		$this->load->model('commonfn');
		foreach ($result->result_array() as $row)
		{
			unset($row['content']);
			if($row['class_nm']=="my-box")
			{
				$row['usr_img_path']=$this->commonfn->get_photo('p',$row['from_id']);
			}
			else
			{
				$row['usr_img_path']=$this->commonfn->get_photo('p',$row['to_id']);
			}
			
			$row['timeago']=timeago($row["createdDate"]);
			$messages[]=$row;
		}
		
		return $messages;
	}
	 
	 function postMessage($from,$to,$message)
	 {
		 if ($this->form_validation->integer($from) && $this->form_validation->integer($to) && $this->form_validation->required($message)){
			 
			 $this->db->trans_start();
			 
			 $query="select conversation_id from message where (from_id= ? and to_id = ?) or (to_id = ? and from_id = ?)"; 
			 $conversation_id = $this->db->query($query,array($from,$to,$from,$to))->row()->conversation_id;
			
			 if($conversation_id > 0)
			 {
				 
			 }
			 else
			 {
				$data=array(
					'from_id'=>$from,
					'to_id'=>$to,
					'createdDate'=>date('Y-m-d H:i:s')
				);
				$this->db->insert('conversation',$data);
				$conversation_id = $this->db->insert_id();	
			 }
			  
			 $data=array(
					'from_id'=>$from,
					'to_id'=>$to,
					'conversation_id'=>$conversation_id,
					'content'=>$message,
					'createdDate'=>date('Y-m-d H:i:s')
				);
			$this->db->insert('message',$data);
			//print_query();
			$last_msg_id = $this->db->insert_id();
			$this->db->trans_complete();
			
			if ($this->db->trans_status() !== FALSE)
			{
				$response["conversation_id"] = $conversation_id;				
				$query = $this->db->query("SELECT u.firstname,u.lastname,u.username,u.profileLink,u.id FROM users as u,conversation as c WHERE c.to_id = u.id AND c.id = '".$conversation_id."' LIMIT 1");
				$row = $query->result_array();		

				$response["profile_id"] = $row[0]["id"];
				$response["msg_url"] = base_url()."message/".$conversation_id;
				$response["img_path"] = $this->commonfn->get_photo("p",$row[0]["id"]);
				$response["firstname"] = $row[0]["firstname"];
				$response["lastname"] = $row[0]["lastname"];				
				$response["messages"] = $this->getMessages($conversation_id,$last_msg_id);
				
			}
			else{
				$response = Array(
						"error" => 'something went wrong,please try again later'
					);
			}
		 }
		 return $response;
	 }
		
	/*Function for getting users list */	
	 function alloweduserlist($query,$cond = NULL,$limit = NULL){
	 	$output = array();
	 	if($cond != '')
	 		$cond = "";
	 	else		
	 		$cond = "";

	 	if($limit != "")
	 		$limit = "LIMIT ".$limit;	 	
		
		$query="SELECT DISTINCT(u.id) as uid, u.id,u.firstname,u.lastname,u.username from (SELECT *,if(`fromId`='".$this->sess_id."',`toId`,`fromId`) as fr_id  FROM `followinglog` WHERE (`fromId`='".$this->sess_id."' OR `toId` = '".$this->sess_id."')) AS t,users as u WHERE u.id = t.fr_id AND (u.username LIKE '%".$this->db->escape_like_str($query)."%' OR u.firstname LIKE '%".$this->db->escape_like_str($query)."%' OR u.lastname LIKE '%".$this->db->escape_like_str($query)."%')";		
		$res =  $this->db->query($query);	
		foreach ($res->result_array() as $row)
		{
				$row["id"] = $row["uid"];
				$row["user_image"] = $this->commonfn->get_photo('p',$row["uid"]);
				//$row["username"] = $row["username"];								
				$output[] = $row;
		}		
		return $output;
	 }
	 /*Function ends*/
}
?>