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

class Surveys_api extends REST_Controller
{
	function survey_vote_post()
    {
		$surveyId = $this->post('surveyId');
		$optionId = $this->post('optionId');
		$userId = $this->post('userId');
		if($surveyId > 0 && $optionId > 0)
        {
        	$this->load->model('surveys');
			$data = $this->surveys->voteonsurvey($surveyId,$optionId,$userId);
			if($data == "success")
			{
				$ar["status"] = "success";
				$ar["msg"] = "You have successfully voted on this survey.";
			}
			else if($data=="already_voted"){
				$ar["status"] = "error";
				$ar["msg"] = "You have already voted on this survey.";
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