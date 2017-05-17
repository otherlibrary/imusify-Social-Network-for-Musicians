<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Send_gift_coupon extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "send_gift_coupon";
		$this->pagetitle = "Send gift coupon";
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
		$this->load->model("admin/send_gift_coupon_model");
		if($_POST)
		{
			$this->load->library('form_validation');
			$this->form_validation->set_rules('hidden-email', 'Email', 'trim|required|xss_clean');
			$this->form_validation->set_rules('code', 'Code', 'trim|required|xss_clean|callback_send_gift_coupon');
			
			if ($this->form_validation->run() == FALSE)
			{	  

			}
			else{
				$this->session->set_flashdata('msg', 'User invited with gift code successfully Added.!');
				redirect(base_url().'admin/'.$this->pagename, 'refresh');
				return true;
			}
		}
		$data=array();
		$code=$this->input->post("code");
		$data['id']='';
		$data['action']='Add';
		$data['code']=$code;
		$data['status']='y';	
		$data['codelist'] = $this->send_gift_coupon_model->get_gift_codes();
		$this->template->set('nav', 'Manage '.$this->pagetitle);
		$this->template->set('title', 'Add '.$this->pagetitle.' -'.SITE_NM);
		$this->template->load_main($this->pagename.'_operation',$data);
	}
	
	
	function send_gift_coupon()
	{
		$temp = $this->pagename."_model";
		$return_type = $this->$temp->send_gift_coupon();
		$msg = NULL;
		if(!empty($return_type) && count($return_type) > 0)
		{	
			if(!empty($return_type))
			{
				foreach ($return_type as $key => $value) {
					$msg .= "<br />".$value." Already Invited.";
				}
			}
		}	

		if($msg!=""){
			$this->form_validation->set_message('send_gift_coupon',$msg);
			return false;
		}
		return true;

	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."_model";
			$this->$temp->delete_code($id);
			$this->session->set_flashdata('msg', 'Send gift coupon log deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}
	
	function chng_status()
	{
		$temp = $this->pagename."_model";
		$this->$temp->chng_stats();
	}
}

