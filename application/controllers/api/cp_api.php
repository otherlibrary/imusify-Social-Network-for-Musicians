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

class Cp_Api extends REST_Controller
{
	  
    function cp_post()
    {
		if(!$this->post('password') || !$this->post('nw_password') || !$this->post('cm_password'))
        {
			if($this->post('nw_password') !== $this->post('cm_password'))
			{
				$this->response(NULL, 400);
			}
        }
		
		$this->load->model('change_password');
		$status = $this->change_password->chgPassword($this->post('password'),$this->post('nw_password'));
		
		if($status)
		{
			 $this->response(array('status' => 'Password Changed successfully'), 200); // 200 being the HTTP response code
		}
		else
		{
			 $this->response(array('status' => 'Something went wrong,try again'), 400);
		}
		
    }
    
    
}