<?php
Class mood_model extends CI_Model
{

	function datatable()
    {
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,mood,status')
        ->unset_column('id')
        ->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')			
		->from('mood');        
        echo $this->datatables->generate();
        /*dump($this->datatables->generate());
        exit;*/
    }
	
	function get_actions($id)
	{	
		$content='<a href="'.base_url().'admin/mood/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
					<i class="entypo-pencil"></i>
					Edit
				</a>
				
				<a href="'.base_url().'admin/mood/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
					<i class="entypo-cancel"></i>
					Delete
				</a>
				';
		return $content;
	}
	
	public function insert_mood()
	{
		$status=$this->input->post('status');
		$mood=$this->input->post('mood');
		$data = array(
		   'mood' => $mood,
		   'status'=>$status,
		   'createdDate' => date('Y-m-d H:i:s')
		);
		$this->db->insert('mood', $data);		
	}
	
	public function edit_mood()
	{
		$id = $this->input->post('id');
		$mood = $this->input->post('mood');		
		$status=$this->input->post('status');
		
		$data = array(
               'mood' => $mood,
			   'status'=>$status			   
        );
		$this->db->where('id', $id);
		$this->db->update('mood', $data);		
	}
	
	public function delete_mood($id)
	{
		$this->db->delete('mood', array('id' => $id));
	}
	
	public function chng_stats()
	{
		$moodid=$this->input->post('id');
		$status=$this->input->post('status');
		
		if($moodid > 0 && $status !='')
		{			
			$data = array(
			   'status'=>$status
			);		
			
			$this->db->where('id', $genreid);
			$this->db->update('mood', $data);
		}	
	}	
	
}
?>