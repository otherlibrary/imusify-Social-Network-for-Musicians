<?php defined('BASEPATH') OR exit('No direct script access allowed');


class Upload_api extends MY_Controller
{
	function __construct() {
		
		parent::__construct();	
		/*$this->db->query("insert into temp(data) values('upload success handler1')");	*/	
    }	
	function uploadfiles(){
		$var = $this->load->model("UploadHandler");
		/*$this->db->query("insert into temp(data) values('upload success handler')");*/

	}	
}