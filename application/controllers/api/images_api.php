<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class images_Api extends REST_Controller
{
	
	function __construct() {
    parent::__construct();       
    $this->load->library('timthumb.php');

  }
  
  function all_photos_get($w,$h,$src,$type,$zc = 1){
      //create thumbnail for image
    $_GET["w"] = $w;
    $_GET["h"] = $h;      
    if($type == "track")    	     
     $_GET["src"] = asset_upload_path()."track/".$src;             
     else if($type == "album")
     $_GET["src"] = asset_upload_path()."album/".$src;    	
    else if($type == "users")
      $_GET["src"] = asset_upload_path()."users/".$src;    	
    else if($type == "playlist")
     $_GET["src"] = asset_upload_path()."playlist/".$src;
   else if($type == "feed_images")
     $_GET["src"] = asset_upload_path()."feed_images/".$src;
   else if($type == "articles")
     $_GET["src"] = asset_upload_path()."articles/".$src;
   else if($type == "about")
     $_GET["src"] = asset_upload_path()."about/".$src;
   else      
    $_GET["src"] = asset_path()."images/".$src;

   $a = new timthumb();         
   $a->start();     
}	
}