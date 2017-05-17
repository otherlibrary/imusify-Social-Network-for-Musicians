<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->sess_id = $this->session->userdata('user')->id;		
	}

	public function cartindex($track_common_detail = null,$type = null)
	{
		/*$buy_step_id = $this->session->userdata("step");
		
		if($buy_step_id == "tracktype")
		{
			$licence_type_list = $track_common_detail["licence_detail"];
		}
		else{
			$this->load->model("icart");
			$response["licence_type_list"] = $this->icart->fetch_buy_usage_types();
			$response["head_title"] = "Usage Types";			
		}
		return $response;*/
	}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */