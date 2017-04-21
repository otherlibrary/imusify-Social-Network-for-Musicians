<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';
class Uploadsave_api extends REST_Controller
{
	function __construct() {
		parent::__construct();
		$this->load->model("uploadm");
	}
	
	function save_track_db_post(){		
		//echo " ".$this->get("eaction");		
		$this->form_validation->set_rules('title', 'Title', 'trim|required|xss_clean');
		$this->form_validation->set_rules('desc', 'Desc', 'trim|required|xss_clean');
		$this->form_validation->set_rules('mm', 'Mm', 'trim|required|xss_clean');
		$this->form_validation->set_rules('dd', 'Dd', 'trim|required|xss_clean');
		$this->form_validation->set_rules('yy', 'Yy', 'trim|required|xss_clean');		
                                                                
		//$this->form_validation->set_rules('genre', 'Genre', 'trim|required|xss_clean');
		//$this->form_validation->set_rules('label', 'Label', 'trim|required|xss_clean');
		/*if($this->post('sale_available_ar') != "" || $this->post('licence_available_ar') != "")
			$this->form_validation->set_rules('agree', 'Agree', 'trim|required|xss_clean');
		*/
			if ($this->form_validation->run() == FALSE)
			{		
				$this->response(array('error' => lang('error_signup_val_required')), 400);
			}
			else
			{
				$eaction = $this->get("eaction");    
                                if(!empty($_GET['nonevermusic'])) $no_update_never_sell = true;
                                else $no_update_never_sell = false;
                                //var_dump($no_update_never_sell);exit;
                                //var_dump ($eaction, $this->post());exit;
				$track = $this->uploadm->insert_track($this->post('title'),$this->post('desc'),$this->post('mm'),$this->post('dd'),$this->post('yy'),$this->post('genre'),$this->post('label'),$image,$folder_name,$this->post('sec_genner'),$this->post('add_album'),$this->post('r'),
                                        $eaction,$this->post('moods_list'),$this->post('sound_like'),$this->post('instruments'),$this->post('music_vocals_y'),$this->post('music_vocals_gender'),$this->post('sale_available'),$this->post('sale_available_ar'),$this->post('licence_available'),$this->post('licence_available_ar'),$this->post('nonprofit_available'),$this->post('exclusive_licence_ar'),$this->post(),$no_update_never_sell);

				if($track["status"] == "fail")
				{
					$this->response($track, 200);
				}	

				if(!empty($track))
				{
					$us_ar["success"] = "success";
					if($eaction == "edit")
					{
						$us_ar["msg"] = $this->post('title')." Edited successfully.";	
					}else{
						$us_ar["msg"] = $this->post('title')." Uploaded successfully.";
					}
					$us_ar["r"] = $this->post('r');
					$us_ar["data"]["song_list"] =  $track;
					$us_ar["data"] =  $track;
					$this->response($us_ar, 200); 						
				}
				else{
				//$this->response(array('error' => lang('error_try_again')), 404);				
				}			
			}   	
		}

		function fetch_uainfo_post(){

			$id = $this->post('id');
			$type = $this->post('type');				
			$track = $this->uploadm->fetch_info($id,$type);			
			if(!empty($track))
			{
				$us_ar["success"] = "success";
				$us_ar["data"] =  $track;
				$this->response($us_ar, 200); 											
			}
			else{
				//$this->response(array('error' => lang('error_try_again')), 404);
			}
		}


		function savewaveform_post(){

			$pixels = $this->post("pixels");
			$response = $pixels;
			$filename = md5(time().rand());

			$folder_name = "waveforms";

			if(!file_exists(asset_upload_path().$folder_name))
			{
				mkdir($folder_name,777);
			}
			$fp = fopen(asset_upload_path().$folder_name."/".$filename.'.json', 'w');
			fwrite($fp, json_encode($response));
			fclose($fp);

			if(!empty($track))
			{
				$us_ar["success"] = "success";
				$us_ar["name"] =  $filename;
			$this->response($us_ar, 200); // 200 being the HTTP response code												
		}
		else
		{
			$this->response("", 404);				
		}

	}

	function delete_track_post(){		
		$us_ar = array();
		$userId = $this->post("userId");
		$trackId = $this->post("trackId");
		$deltype = $this->post("deltype");		
                //$deltype = 'au';
		/*au meand already uploaded and fu = first time uploaded*/
		if($trackId > 0 && $deltype == "au")
		{

			$response = $this->uploadm->delete_trackfromdb($userId,$trackId,$deltype);
			if($response["status"] == "success")
			{
				$us_ar["status"] = "success";
				$us_ar["msg"] = $response["msg"];
				/*membership*/
				$us_ar["track_space"] = $response["track_space"];
				/*membership ends*/
				$this->response($us_ar, 200); 
			}else{
				$us_ar["status"] = "error";
				$us_ar["msg"] = "error";
				$this->response("", 404);	
			}
		}else if($trackId != "" && $deltype == "fu"){
                    
			$response = $this->uploadm->delete_trackfromdb($userId,$trackId,$deltype);
                        //var_dump ($response);
			if($response["status"] == "success"){
				$us_ar["status"] = "success";
				$us_ar["msg"] = $response["msg"];
				/*membership*/
				$us_ar["track_space"] = $response["track_space"];
				/*membership ends*/
				$us_ar["track_type"] = "n";
                                
				$this->response($us_ar, 200); 
			}else{
				$this->response("", 404);	
			}
		}
		else{
			$us_ar["status"] = "error";
			$us_ar["msg"] = "Error";
			$this->response($us_ar, 404);
		}

	}
}