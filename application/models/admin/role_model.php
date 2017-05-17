<?php
Class role_model extends CI_Model
{
	function datatable()
    {
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,role,createdDate,status,is_default')
        ->unset_column('id')
        ->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)') 
		->edit_column('is_default', '$1', 'check_is_default(is_default,id)') 
        ->from('user_roles');
        
        echo $this->datatables->generate();
    }
	
	function get_actions($id)
	{
	
		$content='<a href="'.base_url().'admin/roles/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
					<i class="entypo-pencil"></i>
					Edit
				</a>
				
				<a href="'.base_url().'admin/roles/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
					<i class="entypo-cancel"></i>
					Delete
				</a>
				';
		return $content;
	}
	
	public function insert_role()
	{
		$status=$this->input->post('status');
		$is_default=$this->input->post('is_default');
		$user_role=$this->input->post('user_role');
		$data = array(
		   'role' => $user_role ,
		   'createdDate' => date('Y-m-d H:i:s'),
		   'status'=>$status,
		   'is_default'=>$is_default
		);
		$this->db->insert('user_roles', $data);
		
	}
	
	public function edit_role()
	{
		$id = $this->input->post('id');
		$role = $this->input->post('user_role');
		$is_default=$this->input->post('is_default');
		$status=$this->input->post('status');
		
		$data = array(
               'role' => $role,
			   'status'=>$status,
			   'is_default'=>$is_default
            );
		$this->db->where('id', $id);
		$this->db->update('user_roles', $data);
		
	}
	
	public function delete_role($id)
	{
		$this->db->delete('user_roles', array('id' => $id));
	}
	
	public function chng_stats()
	{
		$roleid=$this->input->post('roleid');
		$status=$this->input->post('status');
		$action=$this->input->post('action');
		if($roleid > 0 && $status !='')
		{
			if($action=='stats_col')
			{
				$data = array(
				   'status'=>$status
				);
			}
			else if($action=='default_col'){
				$data = array(
				   'is_default'=>$status
				);
			}
			
			$this->db->where('id', $roleid);
			$this->db->update('user_roles', $data);
		}	
	}
		
	
}
?>