<?php
Class notification_model extends CI_Model
{
	function datatable()
	{
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,notification,date_format(createdDate,"%d-%m-%Y")')
		->unset_column('id')
		->add_column('Actions',$this->get_actions('$1'),'id')
		/*->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		*/
		->from('admin_notification');
		echo $this->datatables->generate();
	}
	
	function get_actions($id)
	{
		/*<a href="'.base_url().'admin/notification/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
			<i class="entypo-pencil"></i>
			Edit
		</a>*/
		$content='
		

		<a href="'.base_url().'admin/notification/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
			<i class="entypo-cancel"></i>
			Delete
		</a>
		';
		return $content;
	}

	public function insert_notification()
	{
		$notification=$this->input->post('notification');
		$invite_from_id = getvalfromtbl("id","users","usertype='a'","single");
		$prepare_arr = array();

		$status=$this->input->post('status');
		$data = array(
			'notification' => $notification,
			'status'=>$status,
			'createdDate'=>date('Y-m-d H:i:s')
			);
		$this->db->insert('admin_notification', $data);
		$detail_id =  $this->db->insert_id();

		
		/*Get all user's id*/
		$user_id = array();
		$this -> db -> select('id');
		$this -> db -> from('users');

		if($cond != NULL)
			$where = "(status='y' AND usertype = 'u' AND ".$cond.")";	
		else
			$where = "(status='y' AND usertype = 'u')";
		
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
	 	if($query -> num_rows() > 0)
		{
			
			foreach ($query->result_array() as $row)
			{
				$user_id[] = $row["id"];
			}			
		}
		/*Get all user's id ends*/
		//dump($user_id);
		
		/*Prepare batch array*/
		$i = 0;
		foreach ($user_id as $key => $value) {			
			$prepare_arr[$i]["toId"] = $value;
			$prepare_arr[$i]["fromId"] = $invite_from_id;
			$prepare_arr[$i]["detailId"] = $detail_id;		
			$prepare_arr[$i]["notification"] = $notification;
			$prepare_arr[$i]["createdDate"] = date('Y-m-d H:i:s');
			$prepare_arr[$i]["type"] = 'an';
			$i++;
		}
		/*dump($prepare_arr);
		exit;*/
		$this->db->insert_batch('notification', $prepare_arr);
		return true;
	}

	public function edit_notification()
	{
		/*$id = $this->input->post('id');
		$notification=$this->input->post('notification');
		$status=$this->input->post('status');
		$data = array(
			'notification' => $notification,
			'status'=>$status,
			'createdDate'=>date('Y-m-d H:i:s')
			);
		$this->db->where('id', $id);
		$this->db->update('admin_notification', $data);*/
	}

	public function delete_code($id)
	{
		//$this->db->delete('notification', array('id' => $id));
		$this->db->where('detailId', $id);
		$this->db->where('type', 'an');
		$this->db->delete('notification'); 

		$this->db->delete('admin_notification', array('id' => $id));
	}

	public function chng_stats()
	{
		$nid=$this->input->post('nid');
		$status=$this->input->post('status');
		if($nid > 0 && $status !='')
		{
			$data = array(
				'status'=>$status
				);
			$this->db->where('id', $nid);
			$this->db->update('admin_notification', $data);
		}	
	}


}
?>