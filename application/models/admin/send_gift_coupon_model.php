<?php
Class send_gift_coupon_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,email,couponId,date_format(createdDate,"%d-%m-%Y"),status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('giftcoupon_user_invite_log');
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

public function send_gift_coupon()
{
	//$email=$this->input->post('email');
	$email=$this->input->post('hidden-email');
	$codeId=$this->input->post('code');
	$prepare_arr = array();
	$already_existed = array();
	$email_ar = explode(",",$email);
	$email_ar = array_filter($email_ar);
	if(count($email_ar) > 0)
	{
		foreach ($email_ar as $key => $value) {
			$userId = getvalfromtbl("id","users","email='".$value."'","single");
			$query = $this->db->query("select (SELECT id from giftcoupon_user_invite_log where (email='".$value."' or userId = '".$userId."') AND couponId = '".$codeId."') as id");
			$row = $query->row_array();
			$is_exist = $row["id"];
			if($is_exist > 0)
			{
				$already_existed[] = $value;			
			}
			else{
				$prepare_arr[$i]["userId"] = $userId;
				$prepare_arr[$i]["email"] = $value;		
				$prepare_arr[$i]["couponId"] = $codeId;						
				$prepare_arr[$i]["createdDate"] = date('Y-m-d H:i:s');
				$i++;			
			}
		}		
		$send_mail = ADMIN_MAIL;
		/*Insert batch*/
		$gift_code = getvalfromtbl("code","membership_coupons","id='".$codeId."'","single");
		if(count($prepare_arr) > 0)
		{
			$this->db->insert_batch('giftcoupon_user_invite_log', $prepare_arr);
			foreach ($prepare_arr as $key => $value) {
				$data_mail = array(
					'login_url' => base_url()."login",
					'email' => $value["email"],
					'gift_code' => $gift_code
					);			
				$abc=$this->template->load('mail/email_template','mail/giftcodeinvite',$data_mail,TRUE);
				send_mail($send_mail,$value["email"],"Imusify gift coupon",$abc);	
			}
		}
		/*Insert batch Send Mail*/
		if(count($already_existed) > 0)
			return $already_existed;
		else 
			return true;
	}	
	else{
		return false;
	}
}

public function delete_code($id)
{
	$this->db->delete('giftcoupon_user_invite_log', array('id' => $id));
}

public function get_gift_codes($cond = NULL,$limit = NULL,$start_limit = NULL,$counter = NULL)
{
	$output = array();
	$this -> db -> select('id,code');
	$this -> db -> from('membership_coupons');
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