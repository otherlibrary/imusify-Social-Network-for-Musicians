<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package		CodeIgniter
 * @subpackage	Rest Server
 * @category	Controller
 * @author		Phil Sturgeon
 * @link		http://philsturgeon.co.uk/code/
*/

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH.'/libraries/REST_Controller.php';

class Feed_api extends REST_Controller
{
	function crawl_data_post()
	{
		$this->load->model('share_model');
		$url = $this->post('crawlurl') ? $this->post('crawlurl') : "";	
		$data = $this->share_model->crawl_url_data($url);
		if($data)
		{
			echo $data; 						
		}
		else{
			$ar["status"] = "fail";	
			$ar["msg"] = $data["msg"];
			$this->response($ar,200);
		}		
	}

	function highlight_feed_post(){
		$this->load->model('share_model');
		$text = $this->post('text') ? $this->post('text') : "";	
		$description = $this->post('description') ? $this->post('description') : "";	
		$data = $this->share_model->highlight_feed($text,$description);
		if($data)
		{
			$this->response($data,200);						
		}
		else{
			$ar["status"] = "fail";	
			$ar["msg"] = $data["msg"];
			$this->response($ar,200);
		}
	}

	function save_feed_post(){
		$this->load->model('following_model');

		$postdata=$this->post('data');
		$array["image"] =  $postdata['image'] ? $postdata['image'] : "";	
		$array["title"] =  $postdata['text'] ? $postdata['text'] : "";	
		$array["canonicalUrl"] =  $postdata['fancyUrl'] ? $postdata['fancyUrl'] : "";	
		$array["description"] =  $postdata['description'] ? $postdata['description'] : "";	
		$array["iframe"] =  $postdata['videoIframe'] ? $postdata['videoIframe'] : "";	
		$array["url"] =  $postdata['hrefUrl'] ? $postdata['hrefUrl'] : "";	
		$array["feedType"] =  $postdata['feedType'] ? $postdata['feedType'] : "";		
		$array["imgType"] =  $postdata['imgType'] ? $postdata['imgType'] : "";			
		$array["imgUrl"] =  $postdata['imgUrl'] ? $postdata['imgUrl'] : "";		
		/*echo "<pre>";
			print_r($array);*/
		$data = $this->following_model->insert_feed($array);
		if($data["status"] == "success")
		{
			$ar["status"] = "success";	
			$ar["id"] = $data["id"];	
			/*$ar["profile_img_url"] = $data["profile_img_url"];*/
			/*$ar["username"] = $data["username"];*/
			/*$ar["feed_detail"] = $data["feed_detail"];*/
			$ar["feed_detail"] = $data["feed_data"];
			/*$ar["feed_ago"] = timeago($data["inserted_time"]);*/
			/*$ar["feedtitle"] = $array["title"];
			$ar["feedimage"] = $array["image"];
			$ar["description"] = $array["description"];*/
			/*$ar["feed_detail"] = "You has posted this link.";*/	
			/*$ar["feed_comments"] = "";*/
			$ar["msg"] = $data["msg"];
		}
		else{
			$ar["status"] = "fail";	
			$ar["msg"] = $data["msg"];			
		}	
		$this->response($ar,200);
	}

}