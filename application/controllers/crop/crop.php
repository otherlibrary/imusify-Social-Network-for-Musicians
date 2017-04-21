<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class crop extends MY_Controller {

	function __construct()
	{
		parent::__construct();		
		$this->load->model('commonfn');
		$this->sess_id = $this->session->userdata('user')->id;
	}

	function index($action = NULL)
	{
                
		if($action == "track_cover"){
			$trackId = $this->input->post('trackId');
		}
		else if($action == "trackImg"){//upload Track Image (Upload new or Edit image)
			$r = $this->input->post('r');						 
		}
		else if($action == "user_profile")
		{
			$userId = $this->sess_id;			
		}
		else if($action == "article_cover"){
			$randomnumber = $this->input->post('randomnumber');
			/*echo "<pre>";
			print_r($this->input->post());
			*/
			$this->session->set_userdata('article'.$randomnumber,$this->input->post());

			//echo '<pre>'; print_r($this->session->all_userdata());

			/*$response = Array(
				"status" => 'success',
				"url" => ""
				);
			print json_encode($response);
			exit;	*/		
		}
		else{
			$playlist_name = $this->input->post('playlist_name');
			$playlist_id = $this->input->post('playlist_id');
			$randomnumber = $this->input->post('randomnumber');
		}

		if($action == "user_cover")
		{
			$folder_name = "users/";				
		}
		else if($action == "track_cover" || $action == "trackImg")
		{
			$folder_name = "track/";
		}
		else if($action == "user_profile"){
			$folder_name = "users/";				
		}
		else if($action == "album_image")
		{
			$folder_name = "album/";

		}
		else if($action == "article_cover"){
			$folder_name = "articles_temp/";						
		}			
		else 
		{
			$folder_name = "playlist_temp/";
		}	
		$this->load->model("crop_model");
		$response = $this->crop_model->image_crop($image_data,$folder_name,$action);

		if($response['status'] == "success")
		{
			extract($response);
			if($action == "user_cover")
			{
				$temps = getvalfromtbl("id","photos","type = 'pc' AND detailId = '".$this->sess_id."'","single");	

				if($temps > 0)
				{
					$data = array(
						'default_pic' => 'n'					
						);			
					$where = "(type = 'pc' AND detailId ='".$this->sess_id."')";			
					$this->db->where($where);
					$this->db->update('photos', $data);	
				}

				$data = array(
					'detailid' => $this->sess_id,
					'dir' => $folder_name,
					'name' => $img_name.$type,
					'type'=>'pc',
					'default_pic' => 'y'	
					);				
				$this->db->insert('photos', $data);

				$response = array(
					"status" => 'success',
					"url" => $this->commonfn->get_photo('pc',$this->sess_id)
				);// generate an error... or use the log_message() function to log your error	
			}
			else if($action == "track_cover"){

				//$temps = getvalfromtbl("id","photos","type = 'tc' AND detailId = '".$trackId."'","single");	
                                $temps = getvalfromtbl("id","photos","type = 't' AND detailId = '".$trackId."'","single");	
				//echo " temps ".$temps;
				if($temps > 0)
				{
					$data = array(
						'default_pic' => 'n'                                                
						);			
					//$where = "(type = 'tc' AND detailId ='".$trackId."' )";	
                                        $where = "(type = 't' AND detailId ='".$trackId."' )";			
					$this->db->where($where);
					$this->db->update('photos', $data);	
				}

				$data = array(
					'detailid' => $trackId,
					'dir' => $folder_name,
					'name' => $img_name.$type,
					//'type'=>'tc',
                                        'type'=>'t',
					'default_pic' => 'y'	
					);				
				$this->db->insert('photos', $data);

				$response = array(
					"status" => 'success',
					//"url" => $this->commonfn->get_photo('tc',$trackId)
                                        "url" => $this->commonfn->get_photo('t',$trackId)
				);// generate an error... or use the log_message() function to log your error				
			}
			else if($action == "trackImg"){

				/*if($this->session->userdata('image_')->$r != "")
				{
					unlink(asset_upload_path()."track/".$this->session->userdata('image_')->$r);
				}*/
				$this->session->set_userdata("image_".$r,$img_name.$type);
                                $trackphoto_counter = getvalfromtbl("count(*)","photos","detailId='".$r."' AND type = 't'");
                                //var_dump($trackphoto_counter["count(*)"]);
                                //if edit, update track image link
                                $counter = intval($trackphoto_counter["count(*)"]);
                                if($counter > 0)
				{
                                    $photoInfo = getvalfromtbl("*","photos","detailId='".$r."' AND type = 't'");
                                    $new_picture = $img_name.$type;
                                    $update_array = array('name'=>$new_picture); 
                                    //update picture for this track
                                    //$this->db->set('name', $new_picture, FALSE);
                                    $this->db->where('id', $photoInfo['id']);
                                    $this->db->update('photos', $update_array);
                                    //var_dump($photoInfo);exit;
				} else {
                                    //no image but should insert new image for this track                                    
                                    $new_picture = $img_name.$type;                                    
                                    $insert_array = array(
                                    "name"=>$new_picture,
                                    'detailId'=> $r,
                                    'type'=> 't',
                                    'dir' => 'track/'
                                    );
                                    $this->db->insert('photos', $insert_array);
                                    
                                }
                                    
                                //var_dump ($r, $img_name, $type);exit;
                                
                                
                                
				$response = array(
					"status" => 'success',
					"url" => base_url()."assets/upload/track/".$img_name.$type
				);// generate an error... or use the log_message() function to log your error					
			}
			else if($action == "user_profile"){

				$temps = getvalfromtbl("id","photos","type = 'p' AND detailId = '".$this->sess_id."'","single");	
					//echo " temps ".$temps;
				if($temps > 0)
				{
					$data = array(
						'default_pic' => 'n'					
						);			
					$where = "(type = 'p' AND detailId ='".$this->sess_id."' )";			
					$this->db->where($where);
					$this->db->update('photos', $data);	
				}

				$data = array(
					'detailid' => $this->sess_id,
					'dir' => $folder_name,
					'name' => $img_name.$type,
					'type'=>'p',
					'default_pic' => 'y'	
					);				
				$this->db->insert('photos', $data);

				$response = array(
					"status" => 'success',
					"url" => $this->commonfn->get_photo('p',$this->sess_id)
					);
			}
			else if($action == "album_image")
			{
				$this->session->set_userdata("album",$img_name.$type);
				$response = array(
					"status" => 'success',
					"url" => base_url()."assets/upload/album/".$img_name.$type
					);			
			}	
			else if($action == "article_cover"){
				$temp_array = array();
				$temp_array[$randomnumber] = $img_name.$type;
				$this->session->set_userdata("article_temp",$temp_array);
			}
			else
			{
				if($playlist_id > 0)
				{

					$this->db->trans_start();
					$data = array(
						'name' => $playlist_name,
						'updatedDate'=>date('Y-m-d H:i:s')
						);
					$this->db->where('id', $playlist_id);
					$this->db->update('playlist', $data); 
					$data = array(
						'name' => $img_name.$type
						);
					$this->db->where('detailid', $playlist_id);
					$this->db->where('type', 'pl');
					$this->db->update('photos', $data); 
					$this->db->trans_complete();
				}
				else{
					$this->db->trans_start();
					$temp_array = array();
					$temp_array[$randomnumber] = $img_name.$type;
					$this->session->set_userdata("playlist_temp",$temp_array);

					if($playlist_name != NULL || $playlist_name != "")
					{
						$data = array(
							'name' => $playlist_name,
							'perLink' => $this->commonfn->get_permalink($playlist_name,'playlist','perLink','id'),
							'userId' => $this->session->userdata('user')->id,
							'createdDate'=>date('Y-m-d H:i:s'),
							'updatedDate'=>date('Y-m-d H:i:s')
							);
						$this->db->insert('playlist', $data); 

						$insert_id = $this->db->insert_id();
						$data = array(
							'detailid' => $insert_id,
							'dir' => 'playlist/',
							'name' => $img_name.$type,
							'type'=>'pl'
							);
						$this->db->insert('photos', $data); 
					}						
					$this->db->trans_complete();
				}
				if ($this->db->trans_status() !== FALSE)
				{
					$response = Array(
						"status" => 'success',
						"url" => base_url()."assets/upload/playlist_temp/".$img_name.$type
						);
				}
				else{
					$response = Array(
						"error" => 'something went wrong,please try again later'
						);
				}

			}
		}
		print json_encode($response);
	}
}