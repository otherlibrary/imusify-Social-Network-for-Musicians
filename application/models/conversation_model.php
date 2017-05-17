<?php
Class Conversation_model extends CI_Model
{

	public function __construct(){
		parent::__construct();		
		if($this->session->userdata('user'))		
			$this->sess_id = $this->session->userdata('user')->id;	
	}

	function delete_conversation($conversationId){

		$status = $this->db->query("DELETE from conversation where id = '".$conversationId."' AND userId = '".$this->sess_id."'");

		$response = array('status'=>'fail','msg'=>'Something went wrong.Please try again');
		if($status)
		{
			$response["status"] = "success";
			$response["msg"] = "Conversation deleted successfully.";

		}
		return $response;
	}

	//Function to get roles from database
	function getConversation($conversationId)
	 {
		$conversation =array(); 
		$userid=$this->session->userdata('user')->id;
		
		$query="select t.id as conversation_id,user_id,firstname,lastname,username from (SELECT id,if(`from_id`= ? ,`to_id`,`from_id`) as user_id FROM `conversation` WHERE ((`from_id`= ? and sender_del='n') OR (`to_id` = ? and rec_del='n')) ORDER BY `id` DESC) as t , users as u where u.id=t.user_id"; 
		$result = $this->db->query($query,array($userid,$userid,$userid));
		
		$this->load->model('commonfn');
		foreach ($result->result_array() as $row)
		{
			if($row['conversation_id']==$conversationId)
			{
				$row['conv_class']='active';
			}
			else
			{
				$row['conv_class']='';
			}
			$row['profile_id']=$row['user_id'];
			$row['msg_url']=base_url().'message/'.$row['conversation_id'];
			$row['img_path']=$this->commonfn->get_photo('p',$row['user_id']);				
			$members_data = getvalfromtbl("content,createdDate","message","conversation_id = '".$row['conversation_id']."'","multiple","id desc");	
			$row['last_message'] = $members_data["content"];
			$row['timeago'] = timeago($members_data["createdDate"]);	
			$row['delete_path'] = base_url()."api/delete_conversation/".$row["conversation_id"];

			$conversation[]=$row;
			/*$conversation[]=$row;
			$conversation[]=$row;*/
			
		}




		$data = array(
               'isRead' => 'y'
            );

		$this->db->where('to_id', $userid);
		$this->db->where('conversation_id',$conversationId);
		$this->db->update('message', $data); 

		return $conversation;
	}
	
	function get_unread_conversation()
	 {
		$conversation =array(); 
		$userid=$this->session->userdata('user')->id;
		
		/*$query="SELECT i1.*,u.firstname,u.lastname,u.username
				FROM message AS i1 
				LEFT JOIN message AS i2
				ON (i1.conversation_id = i2.conversation_id AND i1.createdDate < i2.createdDate)
				INNER JOIN users as u
				ON (i1.from_id = u.id)
				WHERE i2.createdDate IS NULL and i1.isRead=? and i1.to_id=? order by createdDate desc";*/
$query="SELECT i1.*,u.firstname,u.lastname,u.username,u.id as uid,u.profileLink 
				FROM message AS i1 
				LEFT JOIN message AS i2
				ON (i1.conversation_id = i2.conversation_id AND i1.createdDate < i2.createdDate)
				INNER JOIN users as u
				ON (i1.from_id = u.id)
				WHERE i1.isRead=? and i1.to_id=? order by createdDate asc";

		$result = $this->db->query($query,array('n',$userid));
		//print_query();
		//echo print_query();
		$this->load->model('commonfn');
		$final_arr=array();
		$i=0;
		$conv_ids =array();
		foreach ($result->result_array() as $row)
		{
			if(!isset($conv_ids[$row['conversation_id']]))
			{
				$conv_ids[$row['conversation_id']] = $i;
				$i++;
			}
			if(!isset($final_arr[$conv_ids[$row['conversation_id']]])){
				$final_arr[$conv_ids[$row['conversation_id']]]=array();				
			}
			
			$final_arr[$conv_ids[$row['conversation_id']]]=$row;
				$final_arr[$conv_ids[$row['conversation_id']]]['profile_id']=$row['uid'];
				$final_arr[$conv_ids[$row['conversation_id']]]['profileLink']=$row['profileLink'];				
				$final_arr[$conv_ids[$row['conversation_id']]]['timeago']=timeago($row['createdDate']);				
				$final_arr[$conv_ids[$row['conversation_id']]]['msg_url']=base_url().'message/'.$row['conversation_id'];
				$final_arr[$conv_ids[$row['conversation_id']]]['img_path']=$this->commonfn->get_photo('p',$row['uid']);
			if(!isset($final_arr[$conv_ids[$row['conversation_id']]]['messages'])){
				$final_arr[$conv_ids[$row['conversation_id']]]['messages']=array();
			}
			$message=array();
			$message['message']=$row['content'];
			$message['from_id']=$row['from_id'];
			$message['to_id']=$row['to_id'];
			$message['id']=$row['id'];
			$message['createdDate']=$row['createdDate'];

			array_push($final_arr[$conv_ids[$row['conversation_id']]]['messages'],$message);
			
			//$conversation[]=$row;
		}

		usort($final_arr,'conv_DescSort');
		return $final_arr;
	}
	
}
function conv_DescSort($res1,$res2)
	{
	//	if ($res1['create_date'] == $res2['create_date']) return 0;
		return ($res1['createdDate'] >= $res2['createdDate']) ? 1 : -1;
	}
?>