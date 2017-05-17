<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Data_Api extends REST_Controller
{
	
	function __construct() {
        parent::__construct();
        $this->load->model('commonfn');       
		//$this->sess_id = $this->session->userdata('user')->id;			
    }

    function output_main_get(){
    	$url = $this->input->get("url");
    	$url = trim(str_replace(base_url()," ",$url));
    	
    	$data = explode("/", $url);
    	$count = count($data);
    	if($count == 1)
    	{
    		$this->output_get($data[0],"usertype");
    	}
    	else if($count == 2)
    	{
    		$this->output_get($data[0],$data[1],"tracktype");
    	}
    	else if($count == 3 && $data[1] == "sets")
    	{
    		$this->output_get($data[0],"sets",$data[2]);
    	}
    }

    function output_get($first = NULL,$second = NULL,$third = NULL){
    	
    	if($first != NULL && $second == "usertype")
    	{
    		//User json
    		$userlink = $first;

			if($userlink <= 0)
				$userlink = "a";
			
			if($userlink != "")
			{
				$response = $this->commonfn->get_user_info_json($userlink);	
				//var_dump($response);
				if($response != "")
				{
					$this->response($response, 200);												
				}
	     		else{
				 	$this->response("", 404);
				}	
			}
			else{
				$this->response("", 404);
			}	
    	}
    	else if($first != NULL && $second != NULL && $third == "tracktype"){
    		
    		// track json
    		$userId = getvalfromtbl("id","users","profileLink = ".$this->db->escape($first)."","single");
    		
    		$trackId = getvalfromtbl("id","tracks","userId = '".$userId."' AND perLink = ".$this->db->escape($second)."","single");
				
			if($trackId  > 0)
			{
				$response = $this->commonfn->get_track_info_json($trackId);
				
				if($response != "")
				{
					$this->response($response, 200); // 200 being the HTTP response code														
				}			
			}
			else{
				$this->response("", 404);
			}
		}
    	else if($first != NULL && $second != NULL && $third != NULL)
    	{
    		//playlist album or user
    		if($first != NULL && $second == "sets" && $third != NULL)
    		{    			 
    			$userId = getvalfromtbl("id","users","profileLink = ".$this->db->escape($first)."","single");
    			$playlistId = getvalfromtbl("id","playlist","userId = '".$userId."' AND perLink = ".$this->db->escape($third)."","single");
				
				if($playlistId  > 0)
				{
					$response = $this->commonfn->get_playlist_info_json($playlistId);
					if($response)
					{
						$this->response($response, 200); // 200 being the HTTP response code												
					}					
				}
				else{
					$this->response("", 404);
				}
    		}
    		else if($first != NULL && $second == "album" && $third != NULL)
    		{
    			//album
				$albumId = $this->post("albumId");	

				if($albumId  <= 0)
					$albumId  = "1";

				if($albumId > 0)
				{
					$response = $this->commonfn->get_album_info_json($albumId);
					//dump($response);
					if($response)
					{
						 // 200 being the HTTP response code
						$this->response($response, 200);											
					}					
				}
				else{
					$this->response("", 404);
				}
			}
    		else{
    		}
    	}
    }
}