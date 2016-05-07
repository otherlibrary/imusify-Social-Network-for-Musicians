<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class signup_Api extends REST_Controller
{
	function __construct() {
        parent::__construct();
        $this->load->model('signup');	
		$this->load->model('ilogin');	
    }

	function signup_post()
    {
		$this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('uname', 'Username', 'trim|required|xss_clean');
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|xss_clean');
		$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');		
		$this->form_validation->set_rules('mm', 'Month', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('dd', 'Date', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('yy', 'Year', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('agree','Agree','required');
		$this->form_validation->set_rules('invitecode','Invitecode','required');

		
		if ($this->form_validation->run() == FALSE)
		{			
			//$this->load->view('myform');
			$this->response(array('error' => lang('error_signup_val_required')), 400);
		}
		else
		{
			//$this->load->view('formsuccess');			
			$user = $this->signup->sign_up($this->post('fname'),$this->post('lname'),$this->post('uname'),$this->post('email'),$this->post('password'),$this->post('gender'),$this->post('mm'),$this->post('dd'),$this->post('yy'),$this->post('invitecode'));
			/*var_dump($user);*/
			if($user>0)
			{	
					//$us_ar=array("id"=>$user);
					//var_dump($this->session->userdata('user'));
					$us_ar = $this->ilogin->login($this->post('uname'),$this->post('password'),"");
					$this->response($us_ar, 200); // 200 being the HTTP response code					
			}
			else if($user == "notinvitedyet"){
				$this->response(array('error' => 'You are either not invited yet or invitation code incorrect.Please request admin for signup.'), 404);
			}
			else
			{
					$this->response(array('error' => 'Please try again.Signup unsuccessful.'), 404);
			}			
		}		
    }     
	
	function verify_link_process_get(){
		$token = $this->get('token');

		if($token != "")
		{
			$check = $this->signup->verify_modal_process($token);
			
			if($check["verified_success"])
			{
				$msg = "You have verified account successfully.";
				$this->session->set_userdata('notification',$msg);
				redirect('home', 'refresh');
			}else{
				$msg = "Account verification failed.Please contact admin.";
				$this->session->set_userdata('notification',$msg);
				redirect('home', 'refresh');
			}
		}
	}	
		
}