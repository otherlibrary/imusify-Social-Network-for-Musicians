<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Membership extends MY_Controller {
	public $pagename;
	public $pagetitle;	
	function __construct()
	{
		parent::__construct();
		$this->pagename = "membership";
		$this->pagetitle = "Manage Membership";
		$this->load->model("admin/".$this->pagename."admin_model");
		if(!$this->is_admin_logged_in($this->pagename))
		{	
			redirect(base_url().ADMIN_DIR.'/login', 'refresh');
		}	
		
		$this->load->model('Membership_model');
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
		$temp = $this->pagename."admin_model";
		$this->$temp->datatable();
	}

	function detail($id = NULL){
		if($id != NULL)
		{
			$data=array();
			$data["plan_details"] = $this->Membership_model->fetch_all_plans(NULL,NULL,"id='".$id."'");
			$data["plan_details"] = $data["plan_details"][0];
			
			/*echo "<pre>";
			print_r($data);
			exit;*/
			$this->template->set('nav', 'Manage '.$this->pagetitle);
			$this->template->set('title', ' Detail '.$this->pagetitle.' -'.SITE_NM);
			$this->template->load_main($this->pagename.'_detail',$data);	
		}
	}	

	function edit($id)
	{	


		if($id>0){
			
			if($_POST)
			{
				$this->load->library('form_validation');
				$this->form_validation->set_rules('space', 'Space', 'trim|required|xss_clean');
				$this->form_validation->set_rules('can_message', 'Can_message', 'trim|required|xss_clean');
				$this->form_validation->set_rules('frontpage', 'Frontpage', 'trim|required|xss_clean');
				$this->form_validation->set_rules('mp3_split_imusify', 'Mp3_split_imusify', 'trim|required|xss_clean');
				$this->form_validation->set_rules('mp3_split_composer', 'Mp3_split_composer', 'trim|required|xss_clean');
				$this->form_validation->set_rules('licence_split_imusify', 'Licence_split_imusify', 'trim|required|xss_clean');
				$this->form_validation->set_rules('licence_split_composer', 'Licence_split_composer', 'trim|required|xss_clean');
				$this->form_validation->set_rules('can_vote_new_features', 'Can_vote_new_features', 'trim|required|xss_clean');
				$this->form_validation->set_rules('stats', 'Stats', 'trim|required|xss_clean');
				
				$this->form_validation->set_rules('widget', 'Widget', 'trim|required|xss_clean');
				$this->form_validation->set_rules('ads', 'Ads', 'trim|required|xss_clean');
				$this->form_validation->set_rules('aiff/wav', 'Aiff/wav', 'trim|required|xss_clean');
				$this->form_validation->set_rules('free_distribution', 'Free_distribution', 'trim|required|xss_clean');
				$this->form_validation->set_rules('placement_opportunities', 'Placement_opportunities', 'trim|required|xss_clean|callback_edit_membership');
				if ($this->form_validation->run() == FALSE)
				{	  
					$this->session->set_flashdata('msg', 'Error');
					redirect(base_url().'admin/membership', 'refresh');	
				}
				else{
					$this->session->set_flashdata('msg', 'Membership details successfully Updated.!');
					redirect(base_url().'admin/membership', 'refresh');
				}
			}			
			$data=array();
			$data["plan_details"] = $this->Membership_model->fetch_all_plans(NULL,NULL,"id='".$id."'",NULL,true);
			$data["plan_details"] = $data["plan_details"][0];
			$data["action"] = "Edit";
			/*echo "<pre>";
			print_r($data);
			exit;*/
			$this->template->set('nav', 'Manage Membership Detail');
			$this->template->set('title', 'Edit Membership Detail-'.SITE_NM);
			$this->template->load_main('membership_operation',$data);			
		}
		else{
			redirect(base_url().'admin/membership', 'refresh');
			exit;
		}			
	}
	function edit_membership(){

		$temp = $this->pagename."admin_model";
		$this->$temp->edit();		
	}
	
	function delete($id)
	{
		if($id>0)
		{
			$temp = $this->pagename."admin_model";
			$this->$temp->delete_code($id);
			$this->session->set_flashdata('msg', 'Invitation deleted successfully.!');
		}
		redirect(base_url().'admin/'.$this->pagename, 'refresh');		
	}
	
	function chng_status()
	{
		$temp = $this->pagename."admin_model";
		$this->$temp->chng_stats();
	}

	/*Update all plans of membership which is in braintree api*/
	function membership_update_plans()
	{
		$this->load->library("braintree_lib");
		$plans = $this->braintree_lib->import_subsciption_plan();
		$temp = $this->pagename."admin_model";
		$this->$temp->create_update_subsciption_plans($plans);
		$this->session->set_flashdata('msg', 'All plans imported successfully.Please edit plan details.');
		redirect(base_url().'admin/'.$this->pagename, 'refresh');
	}
	/*Update all plans of membership which is in braintree api*/


}

