<?php
Class tracks_model extends CI_Model
{
	public $admin_id;
	function __construct(){
		$this->admin_id = getvalfromtbl("id","users","userType='a'","single");		
	}

	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,title,description,plays,likes,shares,comments,status')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->from('tracks');

		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		/**/
		$action_get = getvalfromtbl("id","likelog","userId='".$this->admin_id."' AND trackId = '".$id."'","single");
		if($action_get > 0)
		{

			$link = '<a href="'.base_url().'admin/tracks/unlike/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
						<i class="entypo-heart-empty"></i>
						Unlike
					</a>';
		}else{
			$link = '<a href="'.base_url().'admin/tracks/like/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
						<i class="entypo-heart"></i>
						Like
					</a>';
		}

		$content=$link.'

		<a href="'.base_url().'admin/tracks/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>
		<a href="'.base_url().'admin/tracks/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
	';
	return $content;
}

public function insert_track()
{
			

}

public function edit_track()
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

public function delete_track($id)
{
	$track_user_id = getvalfromtbl("userId","tracks","id='".$id."'","single");
	$this->load->model("uploadm");
	$this->uploadm->delete_trackfromdb($track_user_id,$id,"au");	
	//$this->db->delete('tracks', array('id' => $id));
}


public function like_track($id)
{
	$this->load->model("commonfn");
	$this->commonfn->like_track($id,$this->admin_id);
}

public function unlike_track($id)
{
	$this->load->model("commonfn");
	$this->commonfn->dislike_track($id,$this->admin_id);	

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