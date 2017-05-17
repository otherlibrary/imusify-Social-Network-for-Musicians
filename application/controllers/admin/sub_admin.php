<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sub_admin extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/sub_admin_model');
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');
			
		}*/
		
		if(!$this->is_admin_logged_in("sub_admin"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}
		
	}

	public function index()
	{
		
		$data=array();
		$this->template->set('nav', 'Manage Sub Admin');
		$this->template->set('nav_sub', 'dashboard1');
		$this->template->set('title', 'Manage Subadmin-'.SITE_NM);
		$this->template->load_main('sub_admin');

		
	}

	function datatable()
	{
		$this->sub_admin_model->datatable();
	}

	function add()
	{
		if($_POST)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('firstname', 'firstname', 'trim|required|xss_clean');
			$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|callback_add_subadmin');
			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'Sub Admin successfully Added.!');
				redirect(base_url().'admin/sub_admin', 'refresh');

			}
		}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['genre']='';
		$data['status']='y';
		$data['type']='p';

		$query=$this->db->query("SELECT sm.* from site_modules as sm where sm.allow_subadmin = 'y'");
		
		foreach ($query->result_array() as $row)
		{	
			$checked=FALSE;
			
			if($row['abc'] > 0)
			{
				$checked=TRUE;
			}
			$result[]=array(
				'title' => $row['title'],
				'id'  => $row['id'],
				'checked'=>$checked
				);

		}
		$data['result']=$result;
		


		$this->template->set('nav', 'Manage Sub Admin');
		$this->template->set('title', 'Add Sub Admin -'.SITE_NM);
		$this->template->load_main('sub_admin_operation',$data);
	}

	function add_subadmin(){
		$res = $this->sub_admin_model->add_subadmin($this->input->post("firstname"),$this->input->post("lastname"),$this->input->post("username"),$this->input->post("email"),$_POST['my-select']);
		if($res["status"] == "success")
		{
			$this->session->set_flashdata('msg', 'Sub admin added successfully.');
			redirect(base_url().'admin/sub_admin', 'refresh');
			exit();		
		}
		else{
			$this->session->set_flashdata('msg', $res["msg"]);
			redirect(base_url().'admin/sub_admin/add', 'refresh');
			exit();
		}
	}

	function edit($id)
	{
		if($_POST)
		{
			$this->sub_admin_model->edit($_POST['my-select'],$id);
			$this->session->set_flashdata('msg', 'Pemissions updates successfully.!');
			redirect(base_url().'admin/sub_admin', 'refresh');
		}
		$data['action']='Edit';
		$data['id']=$id;
		$query=$this->db->query("SELECT sm.*,(SELECT id from user_perm as up where up.module_id = sm.id AND up.user_id = ".$id.") as abc from site_modules as sm where sm.allow_subadmin = 'y'");
		
		foreach ($query->result_array() as $row)
		{	
			$checked=FALSE;
			
			if($row['abc'] > 0)
			{
				$checked=TRUE;
			}
			$result[]=array(
				'title' => $row['title'],
				'id'  => $row['id'],
				'checked'=>$checked
				);

		}
		$data['result']=$result;
		$this->template->set('nav', 'Subadmin');
		$this->template->set('title', 'Edit Permission-'.SITE_NM);
		$this->template->load_main('sub_admin_operation',$data);
	}
	
	function delete($id)
	{
		if($id > 0)
		{
			
			$this->sub_admin_model->delete($id);
			$this->session->set_flashdata('msg', 'User deleted successfully.!');	
		}
		
		redirect(base_url().'admin/sub_admin', 'refresh');		

	}
	function chng_status()
	{
		$user_id=$this->input->post('uid');
		$status=$this->input->post('status');
		if($user_id > 0 && $status !='')
		{
			$this->sub_admin_model->chng_status($user_id,$status);
		}		
		
	}
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */