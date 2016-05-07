<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Genre extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/genre_model');
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');
			
		}*/
		
		if(!$this->is_admin_logged_in("genre"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}
		
	}

	public function index()
	{
		
			$data=array();
			$this->template->set('nav', 'Manage Genre');
			$this->template->set('title', 'Manage Genre-'.SITE_NM);
			$this->template->load_main('genre');
	   
		
	}
	 
	function datatable()
    {
		$this->genre_model->datatable();
    }
	
	
	
	function add()
	{
	
		if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('genre', 'Genre', 'trim|required|xss_clean|callback_add_genre');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Genre successfully Added.!');
					redirect(base_url().'admin/genre', 'refresh');
					
				}
			}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['genre']='';
		$data['status']='y';
		$data['type']='p';
							
		$this->template->set('nav', 'Manage Genre');
		$this->template->set('title', 'Add Genre -'.SITE_NM);
		$this->template->load_main('genre_operation',$data);
	}
	
	public function edit($id)
	{	   
		if($id>0){
		
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('genre', 'Genre', 'trim|required|xss_clean|callback_edit_genre');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Genre successfully Updated.!');
					redirect(base_url().'admin/genre', 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from genre where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['genre']=$row->genre;
					$data['status']=$row->status;
					$data['type']=$row->type;
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Manage Genre');
				$this->template->set('title', 'Edit Genre-'.SITE_NM);
				$this->template->load_main('genre_operation',$data);
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
	
	function add_genre()
	{
		$this->genre_model->insert_genre();
	}
	
	function edit_genre()
	{
		$this->genre_model->edit_genre();
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$this->genre_model->delete_genre($id);
			$this->session->set_flashdata('msg', 'Genre deleted successfully.!');
		}
		redirect(base_url().'admin/genre', 'refresh');		
	}
	
	function chng_status()
	{
			$this->genre_model->chng_stats();
	}
	
	
	
	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */