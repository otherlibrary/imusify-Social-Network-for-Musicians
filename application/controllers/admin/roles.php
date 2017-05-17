<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Roles extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/role_model');
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');
			
		}*/		
		if(!$this->is_admin_logged_in("roles"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}		
	}

	public function index()
	{
			$data=array();
			$this->template->set('nav', 'Manage Roles');
			$this->template->set('title', 'Manage Roles-'.SITE_NM);
			$this->template->load_main('roles');		
	}
	 
	function datatable()
    {
		$this->role_model->datatable();
    }
	
	function add()
	{
	
		if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('user_role', 'User role', 'trim|required|xss_clean|callback_add_role');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'User role successfully Added.!');
					redirect(base_url().'admin/roles', 'refresh');
					
				}
			}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['name']='';
		$data['status']='n';
		$data['is_default']='n';
		
					
		$this->template->set('nav', 'Roles');
		$this->template->set('title', 'Add Role -'.SITE_NM);
		$this->template->load_main('roles_operation',$data);
	}
	
	public function edit($id)
	{
	   
		if($id>0){
		
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('user_role', 'Role', 'trim|required|xss_clean|callback_edit_role');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'User role successfully Updated.!');
					redirect(base_url().'admin/roles', 'refresh');
				}
			}
			
			
			$data=array();
			$query=$this->db->query("select * from user_roles where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['role']=$row->role;
					$data['status']=$row->status;
					$data['is_default']=$row->is_default;
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Roles');
				$this->template->set('title', 'Edit Role-'.SITE_NM);
				$this->template->load_main('roles_operation',$data);
			}
			else{
				redirect(base_url().'admin/roles', 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/roles', 'refresh');
			exit;
		}
	   
		
	}
	
	function add_role()
	{
		$this->role_model->insert_role();
	}
	
	function edit_role()
	{
		$this->role_model->edit_role();
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$this->role_model->delete_role($id);
			$this->session->set_flashdata('msg', 'User role deleted successfully.!');
		}
		redirect(base_url().'admin/roles', 'refresh');		
	}
	
	function chng_status()
	{
			$this->role_model->chng_stats();
	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */