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

class following_api extends REST_Controller
{

	function __construct() {
		parent::__construct();
		$this->load->model('following_model');
	}

	function new_comment_post()
	{
		$parentId = $this->post('parentId') ? $this->post('parentId') : 0;
		$comment = $this->post('comment') ? $this->post('comment') : "";
		$feedlogId = $this->post('feedlogId') ? $this->post('feedlogId') : 0;
		$param_arr = array(
			'parentId'=>$parentId,
			'comment'=>$comment,
			'feedlogId'=>$feedlogId
			);
			if($feedlogId > 0 && $comment != "")	
			{
				$data = $this->following_model->new_feed_comment($param_arr);
				if(!empty($data))
					$this->response($data,200);
				else
					$this->response('error',404);
			}	
			else{
				$this->response('error',404);
			}				
	}

	function delete_comment_post(){
		$id = $this->post('id') ? $this->post('id') : 0;
		if($id > 0)
		{
			$param_arr = array(
				'id'=> $id,
			);	


			$data = $this->following_model->delete_feed_comment($param_arr);
			if(!empty($data))
				$this->response($data,200);
			else
				$this->response('error',404);		
		}
		else{

		}	
	}

	/*Function for reposting a feed*/
	function feed_repost_post(){
		$feedpostid = $this->post('feedpostid') ? $this->post('feedpostid') : 0;
		if($feedpostid > 0)
		{
			$param_arr1 = array('feedpostid'=> $feedpostid);	
			/*echo "<pre>";
				print_r($param_arr);*/

			$data = $this->following_model->feed_repost($param_arr1);
			if(!empty($data))
				$this->response($data,200);
			else
				$this->response('error',404);		
		}
		else{

		}	
	}
	/*Function for reposting a feed ends*/

	/*Function for deleting a feed*/
	function delete_feed_post(){
		$id = $this->post('id') ? $this->post('id') : 0;
		if($id > 0)
		{
			$param_arr1 = array('id'=> $id);	
			$data = $this->following_model->delete_feed($param_arr1);
			if(!empty($data))
				$this->response($data,200);
			else
				$this->response('error',404);		
		}
		else{

		}	
	}
	/*Function for deleting a feed ends*/
}