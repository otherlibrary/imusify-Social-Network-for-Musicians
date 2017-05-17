<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Surveys extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "surveys";
		$this->pagetitle = "Surveys";
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

		add_js(array('surveys.js'));

		/*echo "<pre>";
			print_r($_POST);*/


		if($_POST)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('title', 'Email', 'trim|required|xss_clean');
			//$this->form_validation->set_rules('options[]', 'Options', 'trim|required|xss_clean');			
			$this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');			
			$this->form_validation->set_rules('status', 'Options', 'trim|required|xss_clean|callback_add_survey');

			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'Survey added successfully.!');
				redirect(base_url().'admin/'.$this->pagename, 'refresh');					
			}
		}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['title']='';		
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
				$this->form_validation->set_rules('title', 'Email', 'trim|required|xss_clean');
				$this->form_validation->set_rules('description', 'Code', 'trim|required|xss_clean|callback_edit_article');
				
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Article successfully Updated.!');
					redirect(base_url().'admin/'.$this->pagename, 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from articles where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['title']=$row->title;
					$data['description']=$row->description;
					$data['status']=$row->status;
					$data['headlineDisp']=$row->headlineDisp;										
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
	
	function add_survey()
	{	
		$temp = $this->pagename."_model";
		$return_type = $this->$temp->insert_survey();
		return true;	
	}
	
	function edit_survey()
	{
		$temp = $this->pagename."_model";
		$this->$temp->edit_article();		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."_model";
			$this->$temp->delete_survey($id);
			$this->session->set_flashdata('msg', 'Survey deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}	
	function chng_status()
	{
		$temp = $this->pagename."_model";
		$this->$temp->chng_stats();
	}	
}

