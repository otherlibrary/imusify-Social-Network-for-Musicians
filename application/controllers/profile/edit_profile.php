<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class edit_profile extends MY_Controller {

	function __construct()
	{
		parent::__construct("","front");	
		$this->load->model('user_profile');
		$this->load->model('commonfn');		
	}

	function index($profileLink)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		
		if($this->session->userdata('user')->id > 0)
		{
			$left_panel = true;
			$username = $this->session->userdata('user')->username;
		}
		else
		{	$left_panel = false;$username = null;}
		
		$user_profile_type = "user";
		
		$user_db_details = $this->user_profile->get_user_details($profileLink);
		
		if($user_db_details == "usernotexist")
		{
			redirect(base_url(), 'refresh');
		}
		
		if($user_profile_type == "user")
			$a['data'] = $this->user_profile($profileLink,$user_db_details);
		else if($user_profile_type == "artist")
			$a['data'] = $this->artist_profile($profileLink,$user_db_details);
		
		$a['redirectURL']=base_url();
		$a['current_tm']='edit_profile';
		$this->load->view('home',$a);

	}
	
	function user_profile($profileLink,$user_db_details)
	{
		
		$countries = $this->commonfn->get_countries();
		
		if($this->session->userdata('user')->profileLink == $profileLink)
		{
			$my_profile = "true";			
		}else{
			$my_profile = "";			
		}
		
		
		$image =  $this->commonfn->get_photo('p',$user_db_details["id"],"157","157");

                
                
                
		$user_profile = array(
			'no_of_followers'=>$user_db_details["followers"],
			'no_of_following' => $user_db_details["following"],
			'no_of_songs' => $user_db_details["tracks"],
			'no_of_albums' => $user_db_details["albums"],			
			'profile_image'=>$image,
			'firstname'=>$user_db_details["firstname"],
			'lastname'=>$user_db_details["lastname"],
			'weburl'=>$user_db_details["weburl"],			
			'description'=>$user_db_details["description"],
			'dob_m'=>$user_db_details["dob_m"],			
			'dob_d'=>$user_db_details["dob_d"],			
			'dob_y'=>$user_db_details["dob_y"],			
			'playsets' => $p,
			'recently_listened' => $rl,
			'username'=>$username,
			'user_type' => "user",
			'cover_image' => img_url()."cover-img.png",
			"my_profile" => $my_profile,
			"extra_class" => "artist-profile-edit",
			"profile_link" => $profileLink,
			"countries" => $countries,
                        "active_country" => $user_db_details["countryId"],
                        "active_state" => $user_db_details["stateId"],
                        "active_city" => $user_db_details["cityId"]                        
		);
                
                $country_name = '';
                $state_name = '';
                $city_name = '';
                //fetch city and state if the user already entered
                if ($user_db_details["countryId"] > 0){
                    $country_name = getvalfromtbl("name","location","location_id='".$user_db_details["countryId"]."'","single");
                    $state_name = getvalfromtbl("name","location","location_id='".$user_db_details["stateId"]."'","single");
                    $city_name = getvalfromtbl("name","location","location_id='".$user_db_details["cityId"]."'","single");                    
                    $user_profile['country_name'] = $country_name;
                    $user_profile['state_name'] = $state_name;
                    $user_profile['city_name'] = $city_name;
                }
                
                
		//dump($user_profile);		
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['popUpContent']="profile/edit_profile.html";		
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="profile/profile.html";
		$template_arry['playsetRow']="profile/playset_row.html";
		$template_arry['recentlyListened']="profile/recent_listen_row.html";
		$template_arry['playerPanel']="player_panel.html";
		$template_arry['bigPlayerPanel']="big_player.html";
		$data1=get_template_content($template_arry,$user_profile);
		
		return $data1; 
		
	}
	
	function artist_profile($profileLink,$user_db_details){
		/*Popular SOngs*/
		/*recently_listened*/
		$i = 0;
		$image =  $this->commonfn->get_photo('p',$user_db_details["id"]);	

		$user_profile = array(
			'title'=>$this->config->item('title'),
			'url' => base_url(),
			'img'=>img_url(),		
			'cover_image'=>img_url().'cover-img.png',
			'artist_name' => $user_db_details["firstname"],
			'profile_image' => $image,
			'firstname'=>$user_db_details["firstname"],
			'lastname'=>$user_db_details["lastname"],			
			'country'=>"Turkey",
			'city'=>"Istanbul",			
			'no_of_followers'=>$user_db_details["followers"],
			'no_of_songs' => "15",
			'no_of_albums' => "20",
			"artist_founded" => "2015",
			"artist_genre" => "Rap & Hip-Hop",
			"artist_guitar" => "abc",
			"artist_sex" => "male",
			"artist_keyboard" => "abcdef",
			'popular_songs' => $ps,
			'loggedin'=>$left_panel,
			'username'=>$username,
			'user_type' => "artist",
			"extra_class" => ""
		);
				
		$template_arry['MainPanel']="main.html";
		$template_arry['leftPanel']="left_panel.html";
		$template_arry['rightPanel']="right_panel.html";
		$template_arry['contentPanel']="profile/artist_profile.html";
		$template_arry['popSongs']="profile/artist_popular_row.html";
		$template_arry['playerPanel']="player_panel.html";
		
		$data1=get_template_content($template_arry,$user_profile);		
		return $data1; 
		
	}
		
}