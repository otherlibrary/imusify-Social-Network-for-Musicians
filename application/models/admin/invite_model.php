<?php
Class invite_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,email,date_format(createdDate,"%d-%m-%Y"),status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('invitation_user_detail');

		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		/*<a href="'.base_url().'admin/invite/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
		<i class="entypo-pencil"></i>
		Edit
	</a>*/
	$content='

	<a href="'.base_url().'admin/invite/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
		<i class="entypo-cancel"></i>
		Delete
	</a>
	';
	return $content;
}

public function insert_invite($type = NULL,$useremail = NULL,$userId = NULL)
{
	//$email=$this->input->post('email');
	$email=$this->input->post('hidden-email');
	$codeId=$this->input->post('code');
        if (empty($codeId)) $codeId = 0;
	$prepare_arr = array();
	$already_existed = array();
	$mail_array = array();

	if($type == "user" && $userId != NULL)
		$invite_from_id = $userId;
	else	
		$invite_from_id = getvalfromtbl("id","users","usertype='a'","single");
	$i = 0;

	$email_ar = explode(",",$email);
	//$email_ar = array_filter($email_ar);
        //var_dump ($email_ar);exit;
	if(count($email_ar) > 0)
	{
		
		foreach ($email_ar as $key => $value) {
//			$query = $this->db->query("select (SELECT id from invitation_user_detail where email='".$value."' AND codeId = '".$codeId."') as id, (SELECT id from users WHERE email = '".$value."') as reg_user");
//			$row = $query->row_array();
//			$is_exist = $row["id"];
//			$is_already_reg = $row["reg_user"];
//			if($is_already_reg > 0)
//			{
//				$already_existed[] = $value;			
//			}
//			else if($is_exist > 0){
//				$mail_array[] = $value;
//			}
//			else{
				$prepare_arr[$i]["codeId"] = $codeId;
				$prepare_arr[$i]["fromId"] = $invite_from_id;
				$prepare_arr[$i]["email"] = $value;			
				$prepare_arr[$i]["createdDate"] = date('Y-m-d H:i:s');
				$prepare_arr[$i]["endDate"] = date('Y-m-d H:i:s', strtotime("+365 days"));
				$i++;			
			//}
		}

		if($type == "user")
			$send_mail = $useremail;
		else
			$send_mail = ADMIN_MAIL;

		/*Insert batch*/
		$invite_code = getvalfromtbl("code","invitation_code","id='".$codeId."'","single");
		if(count($prepare_arr) > 0)
		{
			$this->db->insert_batch('invitation_user_detail', $prepare_arr);

			foreach ($prepare_arr as $key => $value) {
				$data_mail = array(
					'signup_url' => base_url()."sign_up",
					'email' => $value["email"],
					'invite_code' => $invite_code

					);			
				$abc=$this->template->load('mail/email_template','mail/invite',$data_mail,TRUE);
				$result = send_mail(ADMIN_MAIL,$value["email"],"Imusify signup invitation",$abc);	
                                //send_mail(ADMIN_MAIL,$email,"Reset Password",$abc);
                                //var_dump($abc);exit;
			}

		}
		/*Insert batch Send Mail*/
		/*Send Mail*/
		if(!empty($mail_array))
		{
			foreach ($mail_array as $key => $value) {
				$data_mail = array(
					'signup_url' => base_url()."sign_up",
					'email' => $value,
					'invite_code' => $invite_code
					);			
				$abc=$this->template->load('mail/email_template','mail/invite',$data_mail,TRUE);
				send_mail($send_mail,$value,"Imusify signup invitation",$abc);	
			}
		}
		/*Send Mail*/	

		if(count($already_existed) > 0)
			return $already_existed;
		else 
			return true;
	}	
	else{
		return false;
	}



}

public function edit_invite()
{
	/*
	$id = $this->input->post('id');
	$status=$this->input->post('status');
	$code = $this->input->post('code');
	$data = array(
		'code' => $code,		  
		'status'=>$status		   
		);
	$this->db->where('id', $id);
	$this->db->update('invitation_code', $data);*/
}

public function delete_code($id)
{
	$this->db->delete('invitation_user_detail', array('id' => $id));
}

public function chng_stats()
{
	/*
	$invitationid=$this->input->post('invitationid');
	$status=$this->input->post('status');

	if($invitationid > 0 && $status !='')
	{

		$data = array(
			'status'=>$status
			);


		$this->db->where('id', $invitationid);
		$this->db->update('invitation_code', $data);
	}	*/
}


}
?>