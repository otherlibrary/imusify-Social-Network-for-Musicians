<?php
Class share_model extends CI_Model
{
	function __construct(){
		parent::__construct();
		$session_user_id = $this->session->userdata('user')->id;
		$this->sess_userid = $session_user_id;	
		$this->load->helper('number');
		$this->load->library('simple_html_dom');
		$this->feedtype = $this->get_feed_db_types();
	}
	
	function get_youtube_thumbnail($url)
	{
		$parse = parse_url($url);
		if(!empty($parse['query'])) {
			preg_match("/v=([^&]+)/i", $url, $matches);
			$id = $matches[1];
		} else {
			/*to get basename*/
			$info = pathinfo($url);
			$id = $info['basename'];
		}
		$img = "http://img.youtube.com/vi/$id/1.jpg";
		return $img;
	}

	function parse_vimeo($link)
	{
		$regexstr = '~
            # Match Vimeo link and embed code
		(?:<iframe [^>]*src=")?       # If iframe match up to first quote of src
		(?:                         # Group vimeo url
			https?:\/\/             # Either http or https
			(?:[\w]+\.)*            # Optional subdomains
			vimeo\.com              # Match vimeo.com
			(?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
			\/                      # Slash before Id
			([0-9]+)                # $1: VIDEO_ID is numeric
			[^\s]*                  # Not a space
			)                           # End group
"?                          # Match end quote if part of src
(?:[^>]*></iframe>)?        # Match the end of the iframe
(?:<p>.*</p>)?              # Match any title information stuff
~ix';

preg_match($regexstr, $link, $matches);
return $matches[1];
}

function get_vimeo_thumbnail($url)
{
	$id = parse_vimeo($url);
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://vimeo.com/api/v2/video/$id.php");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_TIMEOUT, 10);
	$output = unserialize(curl_exec($ch));
	$output = $output[0]['thumbnail_medium'];
	curl_close($ch);
	return $output;
}

/*Function for youtube video expander*/
function youtube_expander(){}
/*Function for youtube video expander ends*/

/*Function for getting file based content*/
function get_url_data($url){
	$get_content = file_get_html($url);
		/*echo "<pre>";
		var_dump($get_content);*/

		if($get_content)
		{
			/*Get Page Title */
			foreach($get_content->find('title') as $element) 
			{
				$page_title = $element->plaintext;
			}		
			/*	Get Body Text*/
			foreach($get_content->find('body') as $element) 
			{
				$page_body = trim($element->plaintext);
			$pos=strpos($page_body, ' ', 200); //Find the numeric position to substract
			$page_body = substr($page_body,0,$pos ); //shorten text to 200 chars
		}	
		$image_urls = array();

		//get all images URLs in the content
		foreach($get_content->find('img') as $element) 
		{
				/* check image URL is valid and name isn't blank.gif/blank.png etc..
				you can also use other methods to check if image really exist */
				if(!preg_match('/blank.(.*)/i', $element->src) && filter_var($element->src, FILTER_VALIDATE_URL))
				{
					$image_urls[] =  $element->src;
				}
			}

		//prepare for JSON 
			$output = array('title'=>$page_title, 'images'=>$image_urls, 'content'=> $page_body);
		}
		else{
			$output = array('status'=>'fail','msg'=>'No results found.');
		}
		
		return $output;
	} 

	function file_get_contents_curl($url)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		$data = curl_exec($ch);
		$info = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

		//checking mime types
		if(strstr($info,'text/html')) {
			curl_close($ch);
			return $data;
		} else {
			return false;
		}
	}
	/*Function for getting file based content ends*/

	/*Function for checking feed type*/
	function feed_type_check($text = NULL,$imagedata = NULL){
		$return_arr = array();	
		/* Check if this a url */
		if(filter_var($text, FILTER_VALIDATE_URL))
		{
		/*echo "Yes it is url";
		exit; die well*/

		$check = $this->checktypes("");

		if($check == true)
		{
			/*Extension valid check*/

		}else{
			/*Check for image extension*/

			/*Check for image extension*/
		}

	}
	else
	{
		/*echo "No it is not url";*/
		$return_arr["text"] = $text;
	}
	/* Check if this a url ends*/
	return $return_arr; 	
}
/*Function for checking feed type ends*/


/*Function for checking media allowed types*/
function checktypes(){
	$allowed_image_type =  array('gif','png' ,'jpg');
	$allowed_video_type =  array('mkv','avi' ,'mp4');
	$allowed_audio_type =  array('mp3','wav' ,'ogg');

		/*$filename = $_FILES['video_file']['name'];
		$ext = pathinfo($filename, PATHINFO_EXTENSION);
		if(!in_array($ext,$allowed) ) {
			echo 'error';
		}*/
		return true;

	}
	/*Function for checking media allowed types*/


	


	/*Function that returns all type of types of feeds*/
	function get_feed_db_types(){
		$output = array();
		$this -> db -> select('*');
		$this -> db -> from('feed_type');
		if($cond != NULL)
			$where = "(status='y' AND ".$cond.")";	
		else
			$where = "(status='y')";
		
		$this->db->where($where);	   
		$query = $this -> db -> get();	   
		/*print $this -> db ->last_query();*/
		if($query -> num_rows() > 0)
		{
			foreach ($query->result_array() as $row)
			{
				$output[$row['name']] = $row["id"];
			}
			return $output;
		}
	}	
	/*Function that returns all type of types of feeds*/

	/*Final ifrmae function which crawls url data and gives api*/
	function crawl_url_data($url,$imageQuantity = NULL,$header = NULL){
		/*var_dump($url);*/
		if($url != "")
		{
			/*echo "a";*/
			$this->load->library("classes/LinkPreview");
			SetUp::init();
			$text = " ".str_replace("\n", " ", $url);
			$header = "";
			/*	echo $text;*/
			$linkPreview = new LinkPreview();
			$response = $linkPreview->crawl($text, $imageQuantity, $header);
			SetUp::finish();
		}
		else{
			$response["status"]  = "fail";
			$response["msg"]  = "No data crawled.";
		}
		return $response; 
	}
	/*FInal ifrmae function which crawls url data and gives api ends*/

	function highlight_feed($text,$description){
		$this->load->library("classes/LinkPreview");
		$this->load->library("classes/HighLight");		
		SetUp::init();
		$answer = array("urls" => HighLight::url($text), "description" => HighLight::url($description));
		return $answer;
	}

}
?>