<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class mood extends MY_Controller {
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('admin/mood_model');
		/*if($this->is_logged_in()){
			redirect('home', 'refresh');			
		}*/		
		if(!$this->is_admin_logged_in("mood"))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}		
	}

	public function index()
	{
		$data=array();
		$this->template->set('nav', 'Manage Mood');
		$this->template->set('title', 'Manage Mood-'.SITE_NM);
		$this->template->load_main('mood');	
	}
	 
	function datatable()
    {
		$this->mood_model->datatable();
    }
	
	function add()
	{	
		if($_POST)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('mood', 'Mood', 'trim|required|xss_clean|callback_add_mood');
			if ($this->form_validation->run() == FALSE)
			{	  
				
			}
			else{
				$this->session->set_flashdata('msg', 'Mood successfully Added.!');
				redirect(base_url().'admin/mood', 'refresh');
				
			}
		}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['mood']='';
		$data['status']='y';
		$data['type']='p';					
		$this->template->set('nav', 'Manage Mood');
		$this->template->set('title', 'Add Mood -'.SITE_NM);
		$this->template->load_main('mood_operation',$data);
	}
		
	public function mood_exist_check($str){

		$check = getvalfromtbl("id","mood","mood = '".$str."'","single");
		if($check > 0)
			return false;
		else
			return true;
	}	

	public function edit($id)
	{
	   
		if($id>0){
		
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('mood', 'Mood', 'trim|required|xss_clean|callback_edit_mood');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Mood successfully Updated.!');
					redirect(base_url().'admin/mood', 'refresh');
				}
			}
			
			
			$data=array();
			$query=$this->db->query("select * from mood where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['mood']=$row->mood;
					$data['status']=$row->status;					
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Manage Mood');
				$this->template->set('title', 'Edit Mood-'.SITE_NM);
				$this->template->load_main('mood_operation',$data);
			}
			else{
				redirect(base_url().'admin/mood', 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/mood', 'refresh');
			exit;
		}
	   
		
	}
	
	function add_mood($str)
	{
		$var = $this->mood_exist_check($str);
		if($var == true)
			$this->mood_model->insert_mood();
		else
		{
			$this->session->set_flashdata('msg', 'Mood already exist.');
			return false;	
		}	
	}
	
	function edit_mood($str)
	{
		$var = $this->mood_exist_check($str);
		if($var == true)
			$this->mood_model->edit_mood();
		else
		{
			$this->session->set_flashdata('msg', 'Mood already exist.');
			return false;	
		}		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$this->mood_model->delete_mood($id);
			$this->session->set_flashdata('msg', 'Mood deleted successfully.!');
		}
		redirect(base_url().'admin/mood', 'refresh');		
	}
	
	function chng_status()
	{
			$this->mood_model->chng_stats();
	}	
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */