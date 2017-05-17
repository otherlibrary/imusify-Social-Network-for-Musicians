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

class Comment_api extends REST_Controller
{
	  
    function comment_post()
    {
		$track_id = $this->post('trackId');
		$user_id = $this->post('userId');
		$comment = $this->post('commentDesc');
		$commentTime = $this->post('t');
		if($track_id > 0 && $user_id > 0 && $comment!='')
        {
        	$this->load->model('track_detail');
			$data = $this->track_detail->postComment($track_id,$user_id,$comment,$commentTime);
			if($data == "true")
			{
				$ar["status"] = "success";
				$ar["msg"] = "You have successfully commented on this track.";
			}
			$this->response($ar,200);            
        }
		else
		{
			$ar["status"] = "error";
			$ar["msg"] = "Please try again.";
			$this->response($ar, 404);
		}
	}
}