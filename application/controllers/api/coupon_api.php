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

class Coupon_api extends REST_Controller
{

	

	function apply_gift_coupon_post()
	{
		
		$this->load->model('giftcoupon_model');
		$code = $this->post('code') ? $this->post('code') : "";
	
		
		$data = $this->giftcoupon_model->apply_coupon($code);
		if($data["status"] == "success")
		{
			$ar["status"] = "success";
			$ar["data"] = $result;
			
		}
		else{
			$ar["status"] = "fail";	
			$ar["msg"] = $data["msg"];
		}
		$this->response($ar,200);
	}
}