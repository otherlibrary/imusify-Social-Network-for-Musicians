<?php
Class content_model extends CI_Model
{
	function datatable()
    {
		$this->load->helper('datatable_helper');
		$this->load->library('Datatables');
		$this->datatables->select('id,title,SUBSTRING(`description`, 1, 100),url,status')
        ->unset_column('id')
        ->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_status(status,id)')
		->edit_column('type', '$1', 'check_type(type)')		
		->from('content');
        
        echo $this->datatables->generate();
    }
	
	function get_actions($id)
	{
	
		$content='<a href="'.base_url().'admin/content/edit/'.$id.'"" class="btn btn-default btn-sm btn-icon icon-left">
					<i class="entypo-pencil"></i>
					Edit
				</a>
				
				<a href="'.base_url().'admin/content/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
					<i class="entypo-cancel"></i>
					Delete
				</a>
				';
		return $content;
	}
	
	public function insert_content()
	{
		$status=$this->input->post('status');
		$title=$this->input->post('title');
		$description = htmlentities($this->input->post('description'));
		$url = $this->input->post('url');
		$data = array(
		   'title' => $title,		  
		   'description'=>$description,
		   'url'=>$url,
		   'status'=>$status,
		   'createdDate' => date('Y-m-d H:i:s')
		);
		$this->db->insert('content', $data);
		
	}
	
	public function edit_content()
	{
		$this->load->helper("htmlpurifier");
		$id = $this->input->post('id');
		$status=$this->input->post('status');
		$title=$this->input->post('title');
		//$description = html_purify($this->input->post('description'),"comment");
		
		$description = $this->input->post('description',true);
		
		$url = $this->input->post('url');
		
		$data = array(
		   'title' => $title,		  
		   'description'=>$description,
		   'url'=>$url,
		   'status'=>$status
		);
		$this->db->where('id', $id);
		$this->db->update('content', $data);
		
	}
	
	public function delete_content($id)
	{
		$this->db->delete('content', array('id' => $id));
	}
	
	public function chng_stats()
	{
		$contentid=$this->input->post('contentid');
		$status=$this->input->post('status');
		
		if($contentid > 0 && $status !='')
		{
			
				$data = array(
				   'status'=>$status
				);
			
			
			$this->db->where('id', $contentid);
			$this->db->update('content', $data);
		}	
	}
		
	
}
?>