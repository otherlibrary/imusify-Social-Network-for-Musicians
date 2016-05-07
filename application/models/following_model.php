<?php
Class following_model extends CI_Model
{
	function __construct(){
		parent::__construct();		
		$this->sess_userid =  $this->session->userdata('user')->id;	
		$this->load->helper('number');
		$this->load->model("share_model");
		$this->feedtype = $this->share_model->get_feed_db_types();
		$this->load->model("commonfn");
	}

	/*Function for getting user's feed*/
	function get_user_feed($array = array()){
		extract($array);
		$user_profile_img_h = (isset($user_profile_img_h) && $user_profile_img_h > 0) ? $user_profile_img_h : 57;
		$user_profile_img_w = (isset($user_profile_img_w) && $user_profile_img_w > 0) ? $user_profile_img_w : 57;
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;	
		$where = "(fl.userId = '".$userId."' or follog.toId=fl.userId)";
		$audiofeed = false;
		$videofeed = false;
		$textfeed = false;
		$linkfeed = false;
		$imagefeed = false;
		if(isset($cond) && $cond != "")
			$where .= $cond;			
		$this->db->select('DISTINCT(fl.id),f.id as feedid,f.title as feedtitle,fl.*,tt.*,f.*,u.username,u.profileLink,ft.name as feedtype,fl.createdDate as feedcdate,f.image as feedimage,f.id as feedId,(SELECT COUNT(id) from feed_comments where feedlogId = fl.id) as comments_count,u.profileLink,u.id as uidd');
		$this->db->from('feed_log as fl');
		$this->db->join('feeds as f', 'fl.feedId = f.id','left');
		$this->db->join('users as u', 'fl.userId = u.id','left');
		$this->db->join('followinglog as follog', 'follog.fromId = "'.$userId.'"','left');
		$this->db->join('tracks as tt', 'tt.id = fl.itemId AND tt.status="y" AND tt.isPublic="y"','left');
		$this->db->join('track_genre as tg', 'tt.id = tg.trackId','left');
		$this->db->join('feed_type as ft', 'fl.feedtypeId = ft.id','left');
		$this->db->where($where);		
		/*if(isset($limit) && $limit != NULL)
		$this ->db-> limit($limit);*/
		if($start_limit != NULL && $limit != NULL)	
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{
			if($limit != NULL)
				$this -> db -> limit($limit);
		}
		$this->db->order_by("fl.createdDate","desc");
		$query = $this->db->get();
		/*print_query();*/
		$records_count = $query->num_rows();
		if(isset($counter) && $counter != NULL)
			return $records_count;
		if($records_count > 0)
		{
			$i = 1;

			foreach ($query->result_array() as $row)
			{
				$row["profile_img_url"] = $this->commonfn->get_photo("p",$row["uidd"],$user_profile_img_h,$user_profile_img_w);
				$row["feed_ago"] = timeago($row["feedcdate"]);
				$row["feedimage"] = $row["feedimage"];
				$row["feedtitle"] = html_entity_decode($row["feedtitle"]);
				$row["videoIframe"] = html_entity_decode($row["iframe"]);
				$row["description"] = html_entity_decode($row["description"]);
				$row["total_comments"] = $row["comments_count"];
				$row["profile_url"] = base_url().$row["profileLink"];
				$row["detailpage_url"] = base_url()."following/detail/".$row["feedid"];

				if($userId == $row["userId"])
					$name = "You";
				else
					$name = $row["username"];
				if($row["feedtypeId"] > 0)	
				{
					$key = array_search ($row["feedtypeId"], $this->feedtype);
					$detail_text = constant("detail_{$key}");					
				}	
				$row["feed_detail"] = $name.$detail_text;
				if($row["userId"] == $userId)	
				{
					$row["own_feed"] = "true";
				}	
				$comments_ar = array('feedlogId'=>$row["id"],'limit'=>'5');
				$row["feed_comments"] = $this->get_feed_comments($comments_ar);

				if($row["feedtype"] == "text")
					$textfeed = true;					
				
				if($row["feedtype"] == "external_audio" || $row["feedtype"] == "internal_audio")
					$audiofeed = true;
				
				if($row["feedtype"] == "external_video")
					$videofeed = true;					

				if($row["feedtype"] == "url")
					$linkfeed = true;					
				
				if($row["feedtype"] == "internal_image")
				{
					$imagefeed = true;
					$row["feedimage"] = $this->commonfn->get_photo("fi",$row["id"],57,57);
				}
				$row["audiofeed"] = $audiofeed;
				$row["videofeed"] = $videofeed;
				$row["linkfeed"] = $linkfeed;
				$row["imagefeed"] = $imagefeed;
				$row["textfeed"] = $textfeed;


				$output[] = $row;					
				$i++;
			}
		}else{
			$output["status"] = "fail";
			$output["msg"] = "There is no feed yet.";
			$output["fail"] = "fail";
		}
		return $output;		
	}	
	/*Function for getting user's feed ends*/	

	/*Function to get perticular feed's option*/
	function get_feed_comments($array = array()){
		extract($array);
		$userId = (isset($userId) && $userId > 0) ? $userId : $this->sess_userid;	
		$output = array();
		$this->db->start_cache();
		$this -> db -> select('fc.*,u.username,u.profileLink,u.id as uid');
		$this -> db -> from('feed_comments as fc');
		$this->db->join('users as u', 'fc.userId = u.id','left');

		if($cond != NULL)
			$where = "(fc.status='y' AND fc.feedlogId = '".$feedlogId."' AND ".$cond.")";	
		else
			$where = "(fc.status='y' AND fc.feedlogId = '".$feedlogId."')";
		
		
		$this->db->where($where);	   
		$this->db->order_by("fc.id","desc");
		$this->db->stop_cache();
		$totalcommetnsRows = $this->db->count_all_results();
		if(isset($start_limit) && $start_limit != NULL && isset($limit) && $limit != NULL)
		{
			$this -> db -> limit($limit,$start_limit);	
		}else{

			if($limit != NULL)
				$this -> db -> limit($limit);
		}
		$query = $this -> db -> get();	   
			//print_query();
		if(isset($counter) && $counter == "counter")
			return $query -> num_rows();
		
		$this->db->flush_cache();
		
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$row["user_profile_img"] = $this->commonfn->get_photo("p",$row["userId"],57,57);
				$row["commentator_name"] = $row["username"];
				$row["time_ago"] = timeago($row["createdDate"]);
				$row["total_comments"] = $totalcommetnsRows;
				$output[] = $row;
			}
			
			return $output;
		}
	}
	/*Function to get perticular feed's option*/

	/*Feed new comment*/
	function new_feed_comment($array = array()){
		if(!empty($array))
		{
			extract($array);
			$userId = (isset($userId) && $userId > 0) ? $userId : $this->sess_userid;
			$parentId = (isset($parentId) && $parentId > 0) ? $parentId : 0;	
			$output = array();
			$insert_array = array(
				'feedlogId'=>$feedlogId,
				'userId'=>$userId,
				'parentId'=>$parentId,
				'comment'=>$comment,
				'createdDate'=>date('Y-m-d H:i:s')
				);
			/*echo "<pre>";
			print_r($insert_array);*/

			$this->db->insert("feed_comments",$insert_array);
			/*print_query();*/
			$commentId = $this->db->insert_id();
			if($commentId > 0)
			{
				$user_details = getvalfromtbl("id,username,profileLink","users","id='".$userId."'","single");	
				$output["status"] = 'success';
				$output["msg"] = 'Commented successfully.';
				$output["comment"] = $comment;
				$output["id"] = $commentId;
				$output["commentator_name"] = $user_details["username"];
				$output["user_img_url"] = $this->commonfn->get_photo("p",$user_details["id"],57,57);
				$comments_ar = array('feedlogId'=>$feedlogId,'limit'=>'5');
				$output["feed_comments"] = $this->get_feed_comments($comments_ar);	
			}else{
				$output["status"] = 'fail';
				$output["msg"] = 'Please try again.';
			}			
		}
		else{
			$output["status"] = 'fail';			
		}
		return $output;
	}
	/*Feed new comment ends*/

	/*Delete feed comment*/
	function delete_feed_comment($array = array()){
		$response = array();
		if(!empty($array))
		{
			$userId = (isset($userId) && $userId > 0) ? $userId : $this->sess_userid;	
			$this->db->where('id', $id);
			$this->db->where('userId', $userId);
			$this->db->delete('feed_comments');
			if ($this->db->_error_message()) {
				$response["status"] = "fail";
				$response["msg"] = 'Error! ['.$this->db->_error_message().']';
			} 
			else if (!$this->db->affected_rows()) {
				$response["status"] = "fail";
				$response["msg"] = 'Error! ID ['.$id.'] not found';
			} 
			else 
			{
				$response["status"] = "success";
				$response["msg"] = "Comment deleted successfully.";
			}
		}else{
			$response["status"] = "fail";
			$response["msg"] = "Delete comment failed";
		}
		return $response;
	}
	/*Delete feed comment*/


	/*function for follow suggestions*/
	function follow_suggestions($array = array()){
		extract($array);
		$userId = (isset($userId) && $userId > 0) ? $userId : $this->sess_userid;	
		$limit = (isset($limit) && $limit) ? $limit : null;
		$user_details = getvalfromtbl("countryId,stateId,cityId","users","id='".$userId."'");
		$countryId = $user_details["countryId"];
		$stateId = $user_details["stateId"];
		$cityId = $user_details["cityId"];
		$query_build = "SELECT u.username, u.id, u.cityId, u.countryId, u.stateId, if(u.cityId=$cityId,1,0) as city_flag, if(u.countryId=$countryId,1,0) as country_flag, if(u.stateId=$stateId,1,0) as state_flag, COUNT(DISTINCT fl.id) AS count_mutual FROM users AS u LEFT JOIN followinglog AS fl ON (fl.toId = u.id) LEFT JOIN followinglog AS fl2 ON (IFNULL(fl.fromId, -1) = fl2.toId AND fl2.fromId = '".$userId."') WHERE u.id <> '".$userId."' AND u.id NOT IN(SELECT toId FROM followinglog WHERE fromId= '".$userId."') GROUP BY u.id ORDER BY count_mutual DESC,city_flag DESC,state_flag DESC,country_flag DESC LIMIT $limit";

		$query = $this->db->query($query_build);
		foreach ($query->result_array() as $row)
		{
			$row["follw_user_image"] = $this->commonfn->get_photo("p",$user_details["id"],57,57);
			$output[] = $row;
		}
		return $output;
	}
	/*function for follow suggestions ends*/

	/*function for article suggestions*/
	function article_suggestions($array = array()){
		extract($array);
		$userId = ($userId != NULL) ? $userId : $this->sess_userid;	
		$where = "status = 'y'";
		$this->db->select('*');
		$this->db->from('articles');
		$this->db->where($where);		
		if(isset($limit) && $limit != NULL)
			$this ->db-> limit($limit);
		$this->db->order_by("createdDate","desc");
		$query = $this->db->get();
		/*print_query();*/
		$records_count = $query->num_rows();
		/*echo " num ".$records_count;*/
		if(isset($counter) && $counter != NULL)
			return $records_count;

		if($records_count > 0)
		{
			$i = 1;
			foreach ($query->result_array() as $row)
			{
				$row["detail_link"] = base_url()."article/".$row["perLink"];
				$output[] = $row;					
				$i++;
			}
		}else{
			$output["status"] = "fail";
		}
		return $output;
	}
	/*function for article suggestions ends*/

	/*Function for repost feed*/
	function feed_repost($array = array()){

		if(!empty($array))
		{
			extract($array);
			$response = array();
			$userId = (isset($userId) && $userId != NULL) ? $userId : $this->sess_userid;	
			$createdDate = date('Y-m-d H:i:s');
			if($feedpostid > 0)
			{
				
				$exist_ar = getvalfromtbl("*","feed_log","id='".$feedpostid."'");
				$new_array = array(
					'userId' => $userId,
					'itemId' => $exist_ar["itemId"],
					'feedId' => $exist_ar["feedId"],
					'feedtypeId' => $exist_ar["feedId"],
					'createdDate' => date('Y-m-d H:i:s'),
					'user_role' => 'r'
					);
				$this->db->insert("feed_log",$new_array);
				
				$insert_id = $this->db->insert_id();
				if($insert_id > 0)
				{
					$response["status"] = "success";
					$response["msg"] = "You have successfully reposted this feed.";
				}						
				
			}
			else{
				$response["status"] = "fail";
				$response["msg"] = "Please try again.Feed not shared successfully.";
			}
		}else{}
		return $response;
	}
	/*Function for repost feed ends*/

	/*Delete feed*/
	function delete_feed($array = array()){
		$response = array();
		if(!empty($array))
		{
			extract($array);
			$userId = (isset($userId) && $userId > 0) ? $userId : $this->sess_userid;		
			$this->db->where('id', $id);
			$this->db->where('userId', $userId);
			$this->db->delete('feeds');
			if ($this->db->_error_message()) {
				$response["status"] = "fail";
				$response["msg"] = 'Error! ['.$this->db->_error_message().']';
			} 
			else if (!$this->db->affected_rows()) {
				$response["status"] = "fail";
				$response["msg"] = 'Error! ID ['.$id.'] not found';
			} 
			else 
			{
				$response["status"] = "success";
				$response["msg"] = "Feed deleted successfully.";
			}
		}else{
			$response["status"] = "fail";
			$response["msg"] = "Delete comment failed";
		}
		return $response;
	}
	/*Delete feed comment*/



	/*Function for inserting a record in feed*/
	function insert_feed($array = array()){	
		$this->load->model("commonfn");	
		if(!empty($array))
		{
			/*echo "<pre>";
			print_r($array);
			*/
			extract($array);
			$userId = ($userId != NULL) ? $userId : $this->sess_userid;
			$inserted_time = date('Y-m-d H:i:s');

			if($feedType == "internal_image")
			{
				$this->load->model("crop_model");
				$image = $imgType.",".$imgUrl;
				$image_response = $this->crop_model->create_image_frombase64($image,"feed_images/");
				$image = "";
			}

			$array = array(
				'userId'=>$userId,
				'title'=>$title,				
				'url'=>$url,
				'image'=>$image,
				'canonicalUrl'=>$canonicalUrl,
				'description'=>$description,
				'iframe'=> $iframe,
				'data'=> '',
				'createdDate'=> $inserted_time
				);	
			/*echo "<pre>";
			print_r($array);*/
			$this->db->insert('feeds', $array);	
			$feedId = $this->db->insert_id();
			$itemId = 0;
			if($feedType == "internal_image")
			{
				
				$insert_array = array(
					'detailId'=>$feedId,
					'dir'=>$image_response["folder_name"],
					'name'=>$image_response["name"],
					'type'=>'fi'
					);

				$this->db->insert('photos',$insert_array);
				$itemId = $this->db->insert_id();
			}
			if($feedId > 0)
			{
				$pass_arr = array('feedId'=>$feedId,'feedType'=>$feedType,'itemId'=>$itemId);
				$feedlogId = $this->insert_feed_log($pass_arr);	
				if($feedlogId > 0)
				{
					$response["status"] = 'success';
					$response["id"] = $feedId;
					/*$response["profile_img_url"] = $this->commonfn->get_photo("p",$userId,57,57);
					$response["username"] = $this->session->userdata('user')->username;
					$response["inserted_time"] = $inserted_time;*/
					$response["msg"] = 'successfully posted.';
					$param_array = array('cond'=>'AND fl.feedId="'.$feedId.'"');
					/*echo "<pre>";
					print_r($param_array);*/
					$response["feed_data"] = $this->get_user_feed($param_array);



					$response["feed_data"] = $response["feed_data"][0];
				}
				else{
					$response["status"] = 'fail';
					$response["msg"] = '';
				}
			}
			else{
				$response["status"] = 'fail';
				$response["msg"] = '';
			}							
		}
		else{
			$response["status"] = 'fail';
			$response["msg"] = '';
		}
		return $response;
	}
	/*Function for inserting a record in feed*/
	
	/*Function for inserting a record in feed log*/
	function insert_feed_log($array = array()){
		if(!empty($array))
		{
			extract($array);
			$userId = ($userId != NULL) ? $userId : $this->sess_userid;
			$itemId = ($itemId > 0) ? $itemId : 0;
			$feedId = ($feedId > 0) ? $feedId : 0;
			$feedType = ($feedType != "") ? $feedType : ""; 
			if($feedType != "")
			{
				/*echo " a ".$feedType;
				echo "<pre>";
				print_r($this->feedtype); */
				$feedtypeId = $this->feedtype[$feedType];
			}
			$feedtypeId = ($feedtypeId > 0) ? $feedtypeId : 0;
			$user_role = ($user_role != "") ? $user_role : 'p';
			$array = array('userId'=>$userId,
				'itemId'=>$itemId,
				'feedId'=>$feedId,
				'feedtypeId'=>$feedtypeId,
				'createdDate'=>date('Y-m-d H:i:s'),
				'user_role'=> $user_role
				);		
			$this->db->insert('feed_log', $array);	
			$feedlogId = $this->db->insert_id();
			return $feedlogId;
		}else{
			return false;
		}
	}
	/*Function for inserting a record in feed log*/	

}
?>