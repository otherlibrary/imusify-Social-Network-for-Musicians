<?php
class linklogin extends my_controller
{
		
	function __construct() {
	parent::__construct();
		$this->load->helper('url');
		$this->load->model('ilogin');
 }

 function index() {

	 // setup before redirecting to Linkedin for authentication.
	 $linkedin_config = array(
	 'appKey' => LINK_LOGIN_APP_KEY,
	 'appSecret' => LINK_LOGIN_APP_SECRET,
	 'callbackUrl' => LINK_LOGIN_URL.'linklogin/data');
	 
	 $this->load->library('linkedin', $linkedin_config);
	 $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);
	 $token = $this->linkedin->retrieveTokenRequest();

	 $this->session->set_flashdata('oauth_request_token_secret', $token['linkedin']['oauth_token_secret']);
	 $this->session->set_flashdata('oauth_request_token', $token['linkedin']['oauth_token']);

	  $link = "https://api.linkedin.com/uas/oauth/authorize?oauth_token=" . $token['linkedin']['oauth_token'];
	 redirect($link);
  }

  function cancel() {

  // See https://developer.linkedin.com/documents/authentication
 // You need to set the 'OAuth Cancel Redirect URL' parameter to <your URL>/linkedin_signup/cancel

  echo 'Linkedin user cancelled login';
  }

  function logout() {
  session_unset();
  $_SESSION = array();
  echo "Logout successful";
  }

  function data() {

 $linkedin_config = array(
 'appKey' => LINK_LOGIN_APP_KEY,
 'appSecret' => LINK_LOGIN_APP_SECRET,
 'callbackUrl' => LINK_LOGIN_URL.'linklogin/data/'
 );

 $this->load->library('linkedin', $linkedin_config);
 $this->linkedin->setResponseFormat(LINKEDIN::_RESPONSE_JSON);

 $oauth_token = $this->session->flashdata('oauth_request_token');
 $oauth_token_secret = $this->session->flashdata('oauth_request_token_secret');

 $oauth_verifier = $this->input->get('oauth_verifier');
 $response = $this->linkedin->retrieveTokenAccess($oauth_token, $oauth_token_secret, $oauth_verifier);

 // ok if we are good then proceed to retrieve the data from Linkedin
  if ($response['success'] === TRUE) {

 // From this part onward it is up to you on how you want to store/manipulate the data 
 $oauth_expires_in = $response['linkedin']['oauth_expires_in'];
 $oauth_authorization_expires_in = $response['linkedin']['oauth_authorization_expires_in'];

 $response = $this->linkedin->setTokenAccess($response['linkedin']);
 $profile = $this->linkedin->profile('~:(id,first-name,last-name,picture-url,email-address)');
 
 $profile_connections = $this->linkedin->profile('~/connections:(id,first-name,last-name,picture-url,industry)');
 $user = json_decode($profile['linkedin']); 
 
 /* echo 'User data:';
 print '<pre>';
 print_r($user);
 print '</pre>'; */
 
 $user_array = array('linkedin_id' => $user->id, 'second_name' => $user->lastName, 'profile_picture' => $user->pictureUrl, 'first_name' => $user->firstName);
 
$email = $user->emailAddress;
$linkedin_status = $this->ilogin->user_exist_check($email);
				
				if($linkedin_status  == "dbexist")
				{
					if($this->session->userdata(USER_SESSION_NAME)->id > 0)
					{
						$left_panel = true;
						$username = $this->session->userdata(USER_SESSION_NAME)->username;
						
						//update query for linked in id...	
						$this->ilogin->update_social_id("liid",$user->id);
					}
					else
						$left_panel = false;
					
					/*already user session set just need to change panels*/					
					$data1 = array(
						'title'=>$this->config->item('title'),
						'url' => base_url(),
						'img'=>img_url(),
						'loggedin'=>$left_panel,
						'news'=>$m,
						'username'=>$username,
						'c_username'=>"a",
						'c_password'=>"b",
						"remem"=>false
					);
					$d["flag"] = "false";
					$d["response"] = $data1;
				}				
				else if($linkedin_status  == "intermediate"){
					
					//$data['user'][""] = "li";
					$user1["provider"] = "in";
					$user1["gender"] = "";
					$user1["email"] = $email;
					$user1["first_name"] = $user->firstName;
					$user1["last_name"] = $user->lastName;
					$user1["id"] = $user->id;
					$this->session->set_userdata('socialuser',$user1);
					$d["flag"] = "true";			
					$d["gender"] = "";
					$d["response"] = $this->session->userdata("socialuser");		
					}
					/*echo 'User data:';
					print '<pre>';
					print_r($this->session->all_userdata());
					print '</pre>';
					exit;*/
					$this->load->view('temp',$d);
 
  // For example, print out user data
 /*echo 'User data:';
 print '<pre>';
 print_r($user_array);
 print '</pre>';

 echo '<br><br>';*/

 // Example of company data
 /*$company = $this->linkedin->company('1337:(id,name,ticker,description,logo-url,locations:(address,is-headquarters))');
 echo 'Company data:';
 print '<pre>';
 print_r($company);
 print '</pre>';

  echo '<br><br>';

 echo 'Logout';
 echo '<form id="linkedin_connect_form" action="../logout" method="post">';
 echo '<input type="submit" value="Logout from LinkedIn" />';
 echo '</form>';*/
 
 
 } else {
  // bad token request, display diagnostic information
		//echo "Request token retrieval failed:<br /><br />RESPONSE:<br /><br />" . print_r($response, TRUE);
  }
 }
		
}
