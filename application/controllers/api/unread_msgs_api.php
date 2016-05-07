<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class unread_msgs_api extends REST_Controller
{
	function __construct(){
		parent::__construct();
		if($this->session->userdata('user'))	{
			$this->sess_id = $this->session->userdata('user')->id;	
		}	

	}

	function get_unread_conv_get()
	{
		$this->load->model('conversation_model');
		$this->load->model('commonfn');
		$data=$this->conversation_model->get_unread_conversation();
		$data1['conversations']=$data;

		//For notifications
		/*$data_n = $this->commonfn->get_unread_notifications();
		$data1['notifications'] = $data_n;*/		
		return $this->response($data1);
	}


	function notifications_list_post()
	{
		$this->load->model('commonfn');
		$lastmodified_timestamp = $this->post('timestamp');
		$lastmodified_date = date("Y-m-d H:i:s",$lastmodified_timestamp);
		
		$last_record_timestamp_ar = getvalfromtbl("createdDate","notification","toId='".$this->sess_id."'","single","createdDate DESC");
		
		$currentmodified_timestamp = $lastmodified_timestamp;
		if($last_record_timestamp_ar)
		{
			$currentmodified_timestamp = strtotime($last_record_timestamp_ar);
			$currentmodified_date = $last_record_timestamp_ar;
		}
		while ($currentmodified_timestamp <= $lastmodified_timestamp) 
		{
			usleep(10000); 
			clearstatcache();
			$last_record_timestamp_ar = getvalfromtbl("createdDate","notification","toId='".$this->sess_id."'","single","createdDate DESC");

			if($last_record_timestamp_ar)
			{
				$currentmodified_timestamp = strtotime($last_record_timestamp_ar);
				$currentmodified_date =$last_record_timestamp_ar;
			}
		}
		$data1 = array();
		$data1['timestamp'] = $currentmodified_timestamp;
		if($lastmodified_timestamp > 0)
		{
			$cond = "createdDate > '".$lastmodified_date."'";
		}
		else{
			$limit = 10;
			$cond = null;
		}
		$notifications = $this->commonfn->get_unread_notifications($cond,$limit);
		$notifications = array_reverse($notifications);
		$data1["notifications"] = $notifications;
		/*dump($notifications);*/
		$rec1 = getvalfromtbl("COUNT(id)","notification","toId='".$this->sess_id."' AND isRead = 'n'","single");;
		/*echo "Z";
		dump($rec1);*/
		$data1["notification_unread_count"] = $rec1["counter"];
		$rec2 =  getvalfromtbl("COUNT(id)","notification","toId='".$this->sess_id."'","single");
		$data1["notifications_count"] = $rec2["counter2"];
		$this->response($data1);
	}

	/*function get_unread_notification(){
		$this->load->model('commonfn');
		$data_n = $this->commonfn->get_unread_notifications();
		$data1['notifications'] = $data_n;		
		return $this->response($data1);
	}*/

	function read_notifications_post(){
		$this->load->model('commonfn');
		$user_id = $this->session->userdata('user')->id; 
		$update_array = array('isRead'=>'y');

		$this->db->where("toId",$user_id);
		$this->db->update("notification",$update_array);

		$data1 = array('status'=>'success');

		$rec1 = getvalfromtbl("COUNT(id)","notification","toId='".$this->sess_id."' AND isRead = 'n'","single");;
		$data1["notification_unread_count"] = $rec1["counter"];
		$rec2 =  getvalfromtbl("COUNT(id)","notification","toId='".$this->sess_id."'","single");
		$data1["notifications_count"] = $rec2["counter2"];
		$this->response($data1);


		/*$where_array=array("toId"=>$this->current_session["id"]);
		$update_array=array("isRead"=>"y");
		$this->notification_model->update_by($where_array,$update_array);*/
	}



}