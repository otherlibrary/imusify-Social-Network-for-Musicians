<?php
Class Surveys extends CI_Model
{
	function __construct()
	{
        parent::__construct();
		$this->sess_uid = $this->session->userdata('user')->id;
	}
	
	/*update_profile*/
	function voteonsurvey($surveyId,$optionId,$userId = NULL)
	{		
		$userId = ($userId != NULL) ? $userId : $this->sess_uid;
		/*Insert to photo table entry*/			
		$img_exist = getvalfromtbl("id","voting_survey_log"," surveyId = '".$surveyId."' AND userId ='".$userId."'","single","");
		if($img_exist > 0)
		{
			//already voted
			return "already_voted";			
		}
		else
		{				
			$data_survey = array(
				'surveyId' =>  $surveyId,
				'optionId' => $optionId,
				'userId' => $userId,
				'ipAddress' => '',
				'createdDate' => date('Y-m-d H:i:s')
			);
			$this->db->insert('voting_survey_log', $data_survey);	
			return "success";					
		}		
	} 

	function postComment($track_id,$user_id,$comment,$commentTime){	
		if($track_id > 0 && $user_id > 0 && $comment != "" && $commentTime != "")
		{
			$data_t_comment = array(
				'trackId' =>  $track_id,
				'userId' => $user_id,
				'comment' => $comment,
				'commentTime' => $commentTime,
				'createdDate' => date('Y-m-d H:i:s'),
				'ipAddress' => ''
			);
			$this->db->insert('track_comments', $data_t_comment);
			return "true";
		}	
		else{
			return "false";
		}	
	}

}//modal over
?>