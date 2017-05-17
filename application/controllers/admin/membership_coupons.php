<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership_coupons extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "membership_coupons";
		$this->pagetitle = "Manage Membership Coupons";
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
			$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
			$this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|callback_add_coupon');
			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'Coupon successfully Added.');
				redirect(base_url().'admin/'.$this->pagename, 'refresh');
				return true;
			}
		}
		$data=array();
		$data['id']='';
		$data['action']='Add';
		$data['status']='y';	
		$data['type']='p';	

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
				$this->form_validation->set_rules('type', 'Type', 'trim|required|xss_clean');
				$this->form_validationn->set_rules('code', 'Code', 'trim|required|xss_clean|callback_edit_coupon');
				if ($this->form_validation->run() == FALSE)
				{	  
					
				}
				else{
					$this->session->set_flashdata('msg', 'Coupon successfully Updated.!');
					redirect(base_url().'admin/membership_coupons', 'refresh');
				}
			}		
			
			$data=array();
			$query=$this->db->query("select * from membership_coupons where id='$id'"); 
			if($query -> num_rows() == 1 ){
				$result=$query->result();
				foreach($result as $row){
					$data['id']=$row->id;
					$data['code']=$row->code;
					$data['type']=$row->type;
					$data['month_limit']=$row->month_limit;
					$data['status']=$row->status;
					$data['action']='Edit';
					}
				
				$this->template->set('nav', 'Manage Genre');
				$this->template->set('title', 'Edit Genre-'.SITE_NM);
				$this->template->load_main($this->pagename.'_operation',$data);
			}
			else{
				redirect(base_url().'admin/membership_coupons', 'refresh');
				exit;
			}
		}
		else{
			redirect(base_url().'admin/membership_coupons', 'refresh');
			exit;
		}
	}
	
	function add_coupon()
	{
		$temp = $this->pagename."_model";
		$return_type = $this->$temp->insert_coupon();
		if($return_type == false)
		{
			$this->session->set_flashdata('msg', 'Same coupon exist.Please insert different code.');
			return false;
		}	
		else
			return true;
	}
	
	function edit_coupon()
	{
		$temp = $this->pagename."_model";
		$this->$temp->edit();		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."_model";
			$this->$temp->delete_code($id);
			$this->session->set_flashdata('msg', 'Coupon deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}
	
	function chng_status()
	{
		$temp = $this->pagename."_model";
		$this->$temp->chng_stats();
	}
}

