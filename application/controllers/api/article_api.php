<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Article_Api extends REST_Controller
{
	function __construct() {
        parent::__construct();
        //$this->load->model('article');			
    }

	function upload_image_post()
    {
    		if(isset($_FILES['file'])  && $_FILES['file']['tmp_name']!='')
			{
				
				$folder_name = "articles/";
				if(file_exists(asset_path()."upload/".$folder_name)==false){
					mkdir(asset_path()."upload/".$folder_name,0777);
				}
				//$path_parts = pathinfo($_FILES['file']['tmp_name']);		
				$ext = end(explode('.', $_FILES['file']['name']));
				/*echo "<pre>";
				print_r($path_parts);
				*//*echo $ext;*/
				 $config['upload_path'] = asset_path()."upload/".$folder_name;
				 $config['file_name'] =md5(date("Y-m-d H:i:s").rand()).".".$ext;
				 $config['allowed_types'] = 'gif|jpg|png';
				
				 $this->load->library('upload', $config);
				 $this->upload->initialize($config);
				
				$field_name = "file";
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
					$us_ar["success"] = "success";
					$us_ar["msg"] = "Article image uploaded successfully.";
					$us_ar["filelink"] = asset_url()."upload/".$folder_name."/".$image;
					$this->response($us_ar, 200); // 200 being the HTTP response code				
				}
			}
			else{
				$this->response(array('error' => 'Please try again.'), 404);
			}				
				//$user = $this->article->update_article_images($image,$folder_name);
    } 	

    function article_images_get(){
    	$id = $this->get("id");
    	if($id>0)
    	{
    		echo $id;
    	}
    	else{
				$this->response(array('error' => 'Please try again.'), 404);
		}
    }

}