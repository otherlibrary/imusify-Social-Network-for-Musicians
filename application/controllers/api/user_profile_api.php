<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class User_profile_Api extends REST_Controller
{
	
	function __construct() {
        parent::__construct();
        $this->load->model('user_profile');			
    }

	function user_roles_post()
    {
	
		$this->form_validation->set_rules('user_roles[]','Roles', 'required');		
		if ($this->form_validation->run() == FALSE)
		{						
                    //$this->response(array('error' => lang('error_user_roles_val_required')), 400);
                    //No Role Just Music Lover
                    //empty role
                    $user_roles_response = $this->user_profile->insert_roles(array());
                    //print_r($user_roles_response);				
                    if($user_roles_response != "")
                    {	
                            $this->response($user_roles_response, 200); // 200 being the HTTP response code					
                    }
                    else
                    {
                            $this->response(array('error' => lang('error_try_again')), 404);
                    }
		}
		else
		{
				//print_r($this->post('user_roles'));
				$user_roles_response = $this->user_profile->insert_roles($this->post('user_roles'));
				//print_r($user_roles_response);				
				if($user_roles_response != "")
				{	
					$this->response($user_roles_response, 200); // 200 being the HTTP response code					
				}
				else
				{
					$this->response(array('error' => lang('error_try_again')), 404);
				}			
		}		
    } 
	
	/*save user profile info*/
	function update_profile_post()
    {
		
		$this->form_validation->set_rules('fname', 'Firstname', 'trim|required|xss_clean');
		$this->form_validation->set_rules('lname', 'Lastname', 'trim|required|xss_clean');
		
		/*$this->form_validation->set_rules('mm', 'Month', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('dd', 'Date', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('yy', 'Year', 'trim|required|xss_clean|numeric');
		$this->form_validation->set_rules('agree','Agree','required');*/
		
		if ($this->form_validation->run() == FALSE)
		{			
			//$this->load->view('myform');
			$this->response(array('error' => lang('error_signup_val_required')), 400);
		}
		else
		{
			/*			
			if(isset($_FILES['image'])  && $_FILES['image']['tmp_name']!='')
			{
				$folder_name = "users/";
				
				if(file_exists(asset_path()."upload/".$folder_name)==false){
					mkdir(asset_path()."upload/".$folder_name,0777);
				}
				
				 $config['upload_path'] = asset_path()."upload/".$folder_name;
				 $config['file_name'] =md5(date("Y-m-d H:i:s").rand());
				 $config['allowed_types'] = 'gif|jpg|png';
				
				 $this->load->library('upload', $config);
				 $this->upload->initialize($config);
				
				$field_name = "image";
				if ( ! $this->upload->do_upload($field_name))
				{
						$this->form_validation->set_message('insert',$this->upload->display_errors());
						return false;
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					
				//	$config1['image_library'] = 'gd2';
					$config1['source_image'] = $data['upload_data']['full_path'];
					//$config1['create_thumb'] = TRUE;
					//$config1['maintain_ratio'] = TRUE;
					//$config1['width']     = 100;
					//$config1['height']   = 100;
					//$config1['thumb_marker']='';
					$config1['new_image']=asset_path()."upload/track/".$data['upload_data']['file_name'];					
					//$this->image_lib->initialize($config1);
					//$this->image_lib->resize();
					//$this->image_lib->clear();					
					$image=$data['upload_data']['file_name'];
					//$this->load->view('upload_success', $data);
				}
			}	
                        */
				$user = $this->user_profile->update_profile($this->post('fname'),$this->post('lname'),$this->post('country'),$this->post('website'),$this->post('description'),$this->post('mm'),$this->post('dd'),$this->post('yy'),$image,$folder_name,$this->post('state'),$this->post('city'),
                                        $this->post('cname'),$this->post('cname2'));
				
				if($user)
				{	
					$us_ar["success"] = "success";
					$us_ar["msg"] = "Your profile was edited successfully.";
					$this->response($us_ar, 200); // 200 being the HTTP response code					
				}
				else
				{
					$this->response(array('error' => 'Please try again.Profile not edited successfully.'), 404);
				}	
			
		}
    } 	

    /*Change cover pic image*/

    /*save user profile info*/
	function update_profile_cover_post()
    {
		if ($this->form_validation->run() == FALSE)
		{			
			//$this->load->view('myform');
			//$this->response(array('error' => lang('error_signup_val_required')), 400);
		}
		else
		{			
			/*Save image to folder*/
			if(isset($_FILES['image'])  && $_FILES['image']['tmp_name']!='')
			{
				$folder_name = "users/";
				
				if(file_exists($folder_name)==false){
					mkdir($folder_name,0777);
				}
				
				 $config['upload_path'] = asset_path()."upload/".$folder_name;
				 $config['file_name'] =md5(date("Y-m-d H:i:s").rand());
				 $config['allowed_types'] = 'gif|jpg|png';
				
				 $this->load->library('upload', $config);
				 $this->upload->initialize($config);
				
				$field_name = "image";
				if ( ! $this->upload->do_upload($field_name))
				{
						$this->form_validation->set_message('insert',$this->upload->display_errors());
						return false;
				}
				else
				{
					$data = array('upload_data' => $this->upload->data());
					
				//	$config1['image_library'] = 'gd2';
					$config1['source_image'] = $data['upload_data']['full_path'];
					//$config1['create_thumb'] = TRUE;
					//$config1['maintain_ratio'] = TRUE;
					//$config1['width']     = 100;
					//$config1['height']   = 100;
					//$config1['thumb_marker']='';
					$config1['new_image']=asset_path()."upload/track/".$data['upload_data']['file_name'];					
					//$this->image_lib->initialize($config1);
					//$this->image_lib->resize();
					//$this->image_lib->clear();					
					$image=$data['upload_data']['file_name'];
					//$this->load->view('upload_success', $data);
				}
			}				
				$user = $this->user_profile->update_cover_profile($image,$folder_name);
				
				if($user)
				{	
					$us_ar["success"] = "success";
					$us_ar["msg"] = "Your profile edited successfully.";
					$this->response($us_ar, 200); // 200 being the HTTP response code					
				}
				else
				{
					$this->response(array('error' => 'Please try again.Profile not edited successfully.'), 404);
				}	
			
		}
    } 	

    /*Function to invite friends*/
    function invite_friends_post(){
    	$this->form_validation->set_rules('hidden-email', 'Email', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE)
		{			
			//$this->load->view('myform');
			$this->response(array('error' => lang('error_signup_val_required')), 400);
		}
		else
		{
                    
				$user = $this->user_profile->invite_friends($this->post('userid'),$this->post());
				
				if($user)
				{	
					$us_ar["status"] = "success";
					$us_ar["msg"] = $user["msg"];
					$this->response($us_ar, 200); // 200 being the HTTP response code					
				}
				else
				{
					$this->response(array('error' => 'Please try again.'), 404);
				}	
			
		}

    }
    /*Function to invite friends*/



}