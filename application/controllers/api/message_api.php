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

class Message_api extends REST_Controller
{
	 
	 function __construct() {
		parent::__construct();
		$this->load->model('message_model');
		
    }	

    function message_post()
    {
		$from = $this->post('msg_from');
		$to = $this->post('msg_to');
		$message = $this->post('msg_content');		
		
		if($from > 0 && $to > 0 && $message!='')
        {			
			$data = $this->message_model->postMessage($from,$to,$message);
			$this->response($data);            
        }
		else
		{
			$this->response(NULL, 400);			
		}
	}
	
	function message_get()
    {
    	$conversationId = $this->get('conversationId');		
		
		if(!$conversationId)
        {
            $this->response(NULL, 400);
        }
		else
		{			
			$data = $this->message_model->getMessages($conversationId);
			$this->response($data);
		}
 	}
    
    function msgalloweduserlist_get($query){
    	//$query = $this->get('query'); 		
 		if($query != "")
        {			
			$data = $this->message_model->alloweduserlist($query);			
			$this->response($data);            
        }
		else
		{
			$this->response(NULL, 400);			
		}


 	}

 	function delete_conversations_post($conversationId){
 		if($conversationId > 0)
 		{
 			$this->load->model("conversation_model");
 			$data = $this->conversation_model->delete_conversation($conversationId);
			$this->response($data,200);            
 		}
 	}

 	function msgalloweduserlist_post(){
 		
 		$query = $this->post('query'); 		
 		if($query != "")
        {			
			$data = $this->message_model->alloweduserlist($query);
			$us_ar["success"] = "success";								
			$us_ar["data"] = $data;
			$this->response($us_ar,200);            
        }
		else
		{
			$this->response(NULL, 400);			
		}


 	}

    
}