<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Content extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/content_model');
		if(!$this->is_admin_logged_in("content"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}	
	}

	public function index()
	{
			$data=array();
			$this->template->set('nav', 'Manage Content');
			$this->template->set('title', 'Manage Content-'.SITE_NM);
			$this->template->load_main('content');		
	}
	 
	function datatable()
    {
		$this->content_model->datatable();
    }
	
	function add()
	{
		add_js(array('ckeditor/ckeditor.js'));	
		if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean');
				$this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
				$this->form_validation->set_rules('url', 'URL', 'trim|required|xss_clean|callback_add_content');


				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Content successfully Added.!');
					redirect(base_url().'admin/content', 'refresh');
					
				}
			}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['title']='';
		$data['description']='';
		$data['url']='';
		$data['status']='y';	
							
		$this->template->set('nav', 'Manage Content');
		$this->template->set('title', 'Add Content -'.SITE_NM);
		$this->template->load_main('content_operation',$data);
	}
	
	public function edit($id)
	{	
		add_js(array('ckeditor/ckeditor.js'));		

		if($id>0){
		
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('title', 'title', 'trim|required|xss_clean');
				$this->form_validation->set_rules('description', 'Description', 'trim|required|xss_clean');
				$this->form_validation->set_rules('url', 'URL', 'trim|required|xss_clean|callback_edit_content');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Content page successfully Updated.!');
					redirect(base_url().'admin/content', 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from content where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['title']=$row->title;
					$data['description']=$row->description;
					$data['url']=$row->url;
					$data['status']=$row->status;					
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Manage Content');
				$this->template->set('title', 'Edit Content-'.SITE_NM);
				$this->template->load_main('content_operation',$data);
			}
			else{
				redirect(base_url().'admin/content', 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/content', 'refresh');
			exit;
		}
	}
	
	function add_content()
	{
		$this->content_model->insert_content();
	}
	
	function edit_content()
	{
		$this->content_model->edit_content();
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$this->content_model->delete_content($id);
			$this->session->set_flashdata('msg', 'Content page deleted successfully.!');
		}
		redirect(base_url().'admin/content', 'refresh');		
	}
	
	function chng_status()
	{
			$this->content_model->chng_stats();
	}
}

