<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');
			
		}*/
		
		if(!$this->is_admin_logged_in("user"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}
		
	}

	public function index()
	{
		
			$data=array();
			add_js("");
			$this->template->set('nav', 'Manage User');
			$this->template->set('nav_sub', 'dashboard1');
			$this->template->set('title', 'Manage User-'.SITE_NM);
			$this->template->load_main('user');
	   
		
	}
	 
	function datatable()
    {
		$this->load->library('Datatables');
		$this->load->helper('datatable_helper');
        $this->datatables->select('id,username,email,description,status')
        ->unset_column('id')
        ->add_column('Actions',$this->get_actions('$1'),'id')
		->edit_column('status', '$1', 'check_user_status(status,id)') 
        ->from('users');
        
        echo $this->datatables->generate();
    }
	
	function get_actions($id)
	{
		$content='<a href="'.base_url().'admin/user/delete/'.$id.'" class="btn btn-danger btn-sm btn-icon icon-left" onclick="return  confirmDelete()">
					<i class="entypo-cancel"></i>
					Delete
				</a>
				
				<a href="'.base_url().'admin/user/profile/'.$id.'" class="btn btn-info btn-sm btn-icon icon-left">
					<i class="entypo-info"></i>
					Profile
				</a>';
		return $content;
	}
	
	function delete($id)
	{
		$this->db->delete('users', array('id' => $id));
		$this->session->set_flashdata('msg', 'User deleted successfully.!');
		redirect(base_url().'admin/user', 'refresh');		
	}
	function chng_status()
	{
		$user_id=$this->input->post('uid');
		$status=$this->input->post('status');
		if($user_id > 0 && $status !='')
		{
				$data = array(
               
			   'status'=>$status
            );
			$this->db->where('id', $user_id);
			$this->db->update('users', $data);
		}		
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */