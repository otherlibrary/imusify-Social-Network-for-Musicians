<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "notification";
		$this->pagetitle = "Manage Notification";
		$this->load->model("admin/".$this->pagename."_model");
		if(!$this->is_admin_logged_in($this->pagename))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}	
	}

	public function index()
	{
		$data=array();
		$this->template->set('nav', 'Manage '.$this->pagetitle);
		$this->template->set('title', 'Manage '.$this->pagetitle.'-'.SITE_NM);
		$this->template->load_main($this->pagename);		
	}

	function datatable()
	{
		$temp = $this->pagename."_model";
		$this->$temp->datatable();
	}
	
	function add()
	{
		if($_POST)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('notification', 'Email', 'trim|required|xss_clean|callback_add_notification');
			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'Notification successfully Added.!');
				redirect(base_url().'admin/'.$this->pagename, 'refresh');
				return true;
			}
		}
		$data=array();
		$code=$this->input->post("notification");
		$data['id']='';
		$data['action']='Add';
		$data['status']='y';	
		$data['codelist'] = "";

		$this->template->set('nav', 'Manage '.$this->pagetitle);
		$this->template->set('title', 'Add '.$this->pagetitle.' -'.SITE_NM);
		$this->template->load_main($this->pagename.'_operation',$data);
	}
	
	public function edit($id)
	{	   
		if($id>0){
		
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('notification', 'Notification', 'trim|required|xss_clean|callback_edit_notification');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'notification successfully Updated.!');
					redirect(base_url().'admin/notification', 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from admin_notification where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['notification']=$row->notification;
					$data['status']=$row->status;
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Manage Genre');
				$this->template->set('title', 'Edit Genre-'.SITE_NM);
				$this->template->load_main('notification_operation',$data);
			}
			else{
				redirect(base_url().'admin/genre', 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/genre', 'refresh');
			exit;
		}
	}
	
	function add_notification()
	{
		$temp = $this->pagename."_model";
		$return_type = $this->$temp->insert_notification();
		return true;
	}
	
	function edit_notification()
	{
		$temp = $this->pagename."_model";
		$this->$temp->edit_notification();		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."_model";
			$this->$temp->delete_code($id);
			$this->session->set_flashdata('msg', 'Notification deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}
	
	function chng_status()
	{
		$temp = $this->pagename."_model";
		$this->$temp->chng_stats();
	}
}

