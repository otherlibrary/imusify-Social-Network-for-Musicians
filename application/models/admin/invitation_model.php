<?php
Class invitation_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,code,date_format(createdDate,"%d-%m-%Y"),status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('invitation_code');

		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{

		$content='<a href="'.base_url().'admin/invitation/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
		<i class="entypo-pencil"></i>
		Edit
	</a>

	<a href="'.base_url().'admin/invitation/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
		<i class="entypo-cancel"></i>
		Delete
	</a>
	';
	return $content;
}

public function insert_code()
{
	$status=$this->input->post('status');
	$code=$this->input->post('code');
	$allowedNum = ($this->input->post('code') > 0) ? $this->input->post('code') : 50000;

	$is_exist = getvalfromtbl("id","invitation_code","code=".$code);

	if($is_exist > 0)
	{
		return false;
	}
	else{
		$data = array(
			'code' => $code,		  
			'status'=>$status,
			'allowedNum' => $allowedNum,
			'createdDate' => date('Y-m-d H:i:s'),
			'endDate' => date('Y-m-d H:i:s', strtotime("+365 days"))
			);
		$this->db->insert('invitation_code', $data);
	}
}

public function edit_code()
{
	$id = $this->input->post('id');
	$status=$this->input->post('status');
	$code = $this->input->post('code');
	$data = array(
		'code' => $code,		  
		'status'=>$status		   
		);
	$this->db->where('id', $id);
	$this->db->update('invitation_code', $data);
}

public function delete_code($id)
{
	$this->db->delete('invitation_code', array('id' => $id));
}

public function chng_stats()
{
	$invitationid=$this->input->post('invitationid');
	$status=$this->input->post('status');

	if($invitationid > 0 && $status !='')
	{

		$data = array(
			'status'=>$status
			);


		$this->db->where('id', $invitationid);
		$this->db->update('invitation_code', $data);
	}	
}


}
?>