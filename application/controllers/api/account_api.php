<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Account_Api extends REST_Controller
{
	function __construct() {
		parent::__construct();
        $this->load->model('user_profile');			
    }
    
	function change_password_post()
    {
		$this->form_validation->set_rules('current_password','Current Password', 'required');
		$this->form_validation->set_rules('new_password','New Password', 'required|matches[confirm_new_password]');
		$this->form_validation->set_rules('confirm_new_password','Confirm New Password', 'required');
		if ($this->form_validation->run() == FALSE)
		{			
			$this->response(array('error' => lang('error_user_roles_val_required')), 400);
		}
		else
		{
				$current_password = $this->input->post('current_password');	
				$new_password = $this->input->post('new_password');	
				$this->load->model("change_password");

				$user_roles_response = $this->change_password->chgPassword($current_password,$new_password);
				
				if($user_roles_response == true)
				{	
					$resp["status"] = "success";
					$resp["msg"] = "Password changed successfully.";
					$this->response($resp, 200); 
				}
				else
				{
					$this->response(array('error' => lang('error_try_again')), 404);
				}			
		}		
    } 
	
}