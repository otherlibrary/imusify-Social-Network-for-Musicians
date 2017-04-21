<?php
Class genre_model extends CI_Model
{
	function datatable()
    {
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,genre,type,status')
        ->unset_column('id')
        ->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('genre');
        
        echo $this->datatables->generate();
    }
	
	function get_actions($id)
	{
	
		$content='<a href="'.base_url().'admin/genre/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
					<i class="entypo-pencil"></i>
					Edit
				</a>
				
				<a href="'.base_url().'admin/genre/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
					<i class="entypo-cancel"></i>
					Delete
				</a>
				';
		return $content;
	}
	
	public function insert_genre()
	{
		$status=$this->input->post('status');
		$type=$this->input->post('type');
		$genre=$this->input->post('genre');
		$data = array(
		   'genre' => $genre ,
		   'status'=>$status,
		   'type'=>$type
		);
		$this->db->insert('genre', $data);
		
	}
	
	public function edit_genre()
	{
		$id = $this->input->post('id');
		$genre = $this->input->post('genre');
		$type=$this->input->post('type');
		$status=$this->input->post('status');
		
		$data = array(
               'genre' => $genre,
			   'status'=>$status,
			   'type'=>$type
            );
		$this->db->where('id', $id);
		$this->db->update('genre', $data);
		
	}
	
	public function delete_genre($id)
	{
		$this->db->delete('genre', array('id' => $id));
	}
	
	public function chng_stats()
	{
		$genreid=$this->input->post('genreid');
		$status=$this->input->post('status');
		
		if($genreid > 0 && $status !='')
		{
			
				$data = array(
				   'status'=>$status
				);
			
			
			$this->db->where('id', $genreid);
			$this->db->update('genre', $data);
		}	
	}
		
	
}
?>