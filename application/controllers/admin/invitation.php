<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Invitation extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "invitation";
		$this->pagetitle = "Invitation";
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
			$this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|callback_add_code');
			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'Invitation code successfully Added.!');
				redirect(base_url().'admin/'.$this->pagename, 'refresh');					
			}
		}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['code']='';
		$data['status']='y';	

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
				$this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|callback_edit_code');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Invitation code successfully Updated.!');
					redirect(base_url().'admin/'.$this->pagename, 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from invitation_code where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['code']=$row->code;
					$data['status']=$row->status;					
					$data['action']='Edit';
				}				
				$this->template->set('nav', 'Manage '.$this->pagetitle );
				$this->template->set('title', 'Edit -'.$this->pagetitle .SITE_NM);
				$this->template->load_main($this->pagename.'_operation',$data);
			}
			else{
				redirect(base_url().'admin/'.$this->pagename, 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/'.$this->pagename, 'refresh');
			exit;
		}
	}
	
	function add_code()
	{
		$temp = $this->pagename."_model";
		$this->$temp->insert_code();
	}
	
	function edit_code()
	{
		$temp = $this->pagename."_model";
		$this->$temp->edit_code();
		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."_model";
			$this->$temp->delete_code($id);
			$this->session->set_flashdata('msg', 'Invitation Code deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}
	
	function chng_status()
	{
		$temp = $this->pagename."_model";
		$this->$temp->chng_stats();
	}
}

