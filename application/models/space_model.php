<?php
Class space_model extends CI_Model
{
	function __construct(){
		//parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;	
		$this->load->helper('number');	
	}
	
	/*Function for updating track's size => Common Function*/
	function update_track_size(){
		$this->db->select('*');
		$this->db->from('tracks');
		$query = $this->db->get();
		$records_count = $query->num_rows();

		if($records_count > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$filePath = asset_upload_path()."media/".$row["userId"]."/".$row["trackName"];
				$file_size = filesize($filePath);

				$data=array('filesize'=>$file_size);
				$this->db->where('id',$row["id"]);
				$this->db->update('tracks',$data);

			}
		}else{
			$output["status"] = "fail";
		}
	}	
	/*Function for updating track's size => Common Function ends*/

	/*Function for updating user's new size*/
	function update_user_new_space($array = array()){
		$response = array('status'=>'fail',"msg"=>'Space not updated successfully.');
		if(!empty($array))
		{
			extract($array);
			$userId = ($userId!= null) ? $userId : $this->sess_userid;
			$user_ar = getvalfromtbl("*","users","id='".$userId."'");
			
			if($plan_space == '-1')
			{
				$updated_total_space = "-1";
				$updated_avail_space = "-1";
			}else{
				$user_current_total_space = $user_ar["total_space"];
				$updated_total_space = $user_current_total_space + $plan_space;
				
				$user_current_avail_space = $user_ar["avail_space"];
				$updated_avail_space = $user_current_avail_space + $plan_space;
				
				/*Check with db tracks used space*/
				$where = "userId = '".$userId."'";
				$this->db->select('SUM(filesize) total_used_space');
				$this->db->from('tracks');
				$this->db->where($where);
				$query = $this->db->get();
				$records_count = $query->num_rows();

				if($records_count > 0)
				{
					$row = $query->row_array();	

					if($plan_space > $row["total_used_space"])
					{
						$updated_avail_space = $plan_space - $row["total_used_space"];
					}
					else if($row["total_used_space"] > $plan_space){
						$updated_avail_space = 0;
					}					
				}
				/*Check with db tracks used space*/
				
			}

			if($updated_total_space != "" && $updated_avail_space != "")
			{
				$data_update = array(
					'total_space' =>  $updated_total_space,
					'avail_space' => $updated_avail_space					
					);		
				$this->db->update('users', $data_update);
				$response["status"] = "success";
				$response["msg"] = "Space updated successfully.";
			}
		}	
		return $response;
	}
	/*Function for updating user's new size ends*/


}
?>