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
class cart_Api extends REST_Controller
{
	function __construct() {
		parent::__construct();
		$this->trackid = 0;		
		$this->orderid = 0;		
		$this->values = "";		
		$this->is_prev_flag = false;
		$this->sess_userid = $this->session->userdata('user')->id; 	
		$this->response_ar =  array();
		$this->load->model("track_detail");
		$this->load->model("icart");
		$this->load->model("order_model");
		$this->order = array();
		$this->track = array();
		$this->api_msg = lang('error_try_again');
	}
	function render_usage_types()
	{
		if(!empty($this->order) && $this->order["id"] > 0)
		{
			$this->response_ar["orderid"] = $this->order["id"];
		}
		$this->response_ar["licence_type_list"] = $this->icart->get_usage_types($this->track,$this->order);;
		$this->response_ar["head_title"] = "Choose usage types";
		$this->response_ar["nexturl"] = base_url()."api/cart/exclusive";
		$this->response_ar["prevurl"] = false;
		
	}
	function set_usage_types()
	{
		$response = $this->icart->set_usage_type($this->track,$this->values,$this->order);
		if($response["status"] == "fail")
		{
			
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}
	function exclusive(){
		if($this->is_prev_flag === true)
		{
			$this->prev_exclusive();
		}
		else{
			$this->next_exclusive();
		}
	}
	function render_exclusive()
	{
		if($this->order["id"] > 0)
		{
			$this->response_ar["orderid"] = $this->order["id"];
		}
		$this->response_ar["licence_type_list"] = $this->icart->get_exclusive_type($this->track,$this->order);
		$this->response_ar["head_title"] = "Choose exclusive licence";
		$this->response_ar["nexturl"] = base_url()."api/cart/trackfiletype";
		$this->response_ar["prevurl"] = base_url()."api/cart/usage_type";
		$this->api_status = 200;
	}
	function next_exclusive(){

		$this->set_usage_types();
		$this->refresh_order();
		/*If choose usage type yes*/
		if($this->order["is_usage_type"] == "y" && $this->order["usage_id"] > 0 )
		{
			
			/*if commercial selected then render exclusive*/
			if($this->order["is_commercial"] ==true)
			{
				$this->render_exclusive();
			}
			else{
				$non_exclusive_ar = $this->icart->get_exclusive_type($track,$order,'n');
				$db_non_exclusive_id = $non_exclusive_ar[0]["id"];
				$this->values = $db_non_exclusive_id;
				$this->filetypes();	
			}
		}
		else{
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}
	function prev_exclusive(){
		if($this->order["is_commercial"] ==true)
		{
			$this->render_exclusive();
		}
		else{
			$this->render_usage_types();
		}
	}
	function set_exclusive_types()
	{
		$response = $this->icart->set_exclusive_type($this->track,$this->values,$this->order);
		if($response["status"] == "fail")
		{
			$this->response(array('error' => lang('error_try_again')), 404);		
		}
	}
	function filetypes(){
		if($this->is_prev_flag === true)
		{
			$this->prev_file_types();
		}
		else{
			$this->next_file_types();
		}
	}
	function render_filetypes()
	{
		
		if(!empty($this->order) && $this->order["id"] > 0)
		{
			$this->response_ar["orderid"] = $this->order["id"];
		}
		$this->response_ar["licence_type_list"] = $this->icart->get_file_type($this->track,$this->order);

		$db_exclusive_id =  getvalfromtbl("id","buy_exclusive_types","type='e'","single");
                //var_dump($this->order, $db_exclusive_id);exit;
		if($this->order["is_exclusive"] && $this->order["is_exclusive"] == 'y' && $db_exclusive_id == $this->order["exclusive_id"])
		{

			$cls = 'buy_data buy_row_li';
			if($this->order["filetype_id"] != "")
			{
				$filetype_ar = explode(",",$this->order["filetype_id"]);
				if(count($filetype_ar) > 1)
				{
					$cls .= " active";
				}
			}

			$add_array = array(
				'id' => 'b',
				'name' =>'Both',
				'extension' =>'both',
				'pricing'=>'',
				'type'=>'trackfile_type',
				'row_class' => $cls
			);
			array_push($this->response_ar["licence_type_list"],$add_array);
		}
		$this->response_ar["head_title"] = "Choose file types";
		$this->response_ar["nexturl"] = base_url()."api/cart/licence_types";
		$this->response_ar["prevurl"] = base_url()."api/cart/exclusive";
	}
	function prev_file_types(){
		if(count($this->track["trackFileTypes"]) == 1)
		{
			$this->values = $this->track["trackFileTypes"][0]["id"];
			$this->exclusive();
		}
		else{
			$this->render_filetypes();
		}
	}
        //license
	function next_file_types(){
                //get license price 
                
		$this->set_exclusive_types();
		$this->refresh_order();
		if(count($this->track["trackFileTypes"]) == 1)
		{
			$this->values = $this->track["trackFileTypes"][0]["id"];
			$this->licences();
		}
		else if(count($this->track["trackFileTypes"]) > 1){
			$this->render_filetypes();
		}
		$this->api_status = 200;
	}
	function set_file_types(){
		if($this->values == "b")
		{
			$this->values = getvalfromtbl("track_buyable_types","tracks","id='".$this->order["trackId"]."'","single");
		}
		$response = $this->icart->set_file_type($this->track,$this->values,$this->order);
		if($response["status"] == "fail")
		{
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}
	function licences(){
		$this->next_licences();
	}
	function next_licences(){
		$this->set_file_types();
		$this->refresh_order();
                //var_dump($this->order);exit;
		if($this->order["is_usage_type"] == "y" && $this->order["usage_id"] > 0 && $this->order["is_exclusive"]=="y" && $this->order['is_filetype']=='y')
		{
			$this->render_licences();
		}
		else{
			
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}
	function render_licences()
	{
		
		if(!empty($this->order) && $this->order["id"] > 0)
		{
			$this->response_ar["orderid"] = $this->order["id"];
		}
		$this->response_ar["licence_type_list"] = $this->icart->get_licences($this->track,$this->order);
                //Exclusive license Price must be equal or greater than 1000 
                /*
                for($i=0; $i < count($this->response_ar["licence_type_list"]); $i++){
                    if ($this->response_ar["licence_type_list"][$i]['licencePrice'] < 1000){
                        $this->response_ar["licence_type_list"][$i]['licencePrice'] = 1000;
                    }                        
                } 
                 */
                              
                //var_dump($this->response_ar["licence_type_list"][0]);exit;
		if(!empty($this->response_ar["licence_type_list"]))
		{
			$cart_html = "";
			$cart_final_price = 0;
			foreach ($this->response_ar["licence_type_list"] as $key => $value) {
				/*printr($value);*/
				if($value["preselected"] == "yes")
				{
					//$cart_final_price += $value["licenceTotalPrice"];
                                        $cart_final_price += $value["licencePrice"];                                        
					$cart_html .= '<li id="subitem'.$value["id"].'"><p>'.$value["name"].'<span>$'.$value["licencePrice"].'</span></p></li>';
				}	
			}
		}
		$this->response_ar["head_title"] = "Choose Licences";
		$this->response_ar["nexturl"] = false;
		$this->response_ar["prevurl"] = base_url()."api/cart/trackfiletype";
		$this->response_ar["display_cart_div"] = true;
		$this->response_ar["cart_final_price"] = $cart_final_price;
		$this->response_ar["cart_html"] = $cart_html;

		/*printr($this->response_ar);*/

	}
	function refresh_order(){
		/*$order = $this->order_model->check_order_exist($this->trackid);
		$this->track = $order["track"];
		$this->order = $order["order"];
		$this->orderid = $this->order["id"];*/
		$trackdetail = $this->track_detail->get_song_details($this->trackid);
		/*echo "z";*/
		
		$this->order = $trackdetail["order"];
		$this->orderid = $this->order["id"];
		unset($trackdetail["order"]);
		$this->track = $trackdetail;
	}
	function cart_content_post($type = null)
	{

		/*echo "Z";exit;*/
		$this->values = $this->input->post("values");
		$this->values = rtrim($this->values,",");
		$this->trackid = $this->input->post("trackid");
                //$this->exclusive_license = filter_var($this->input->post("exclusive"), FILTER_VALIDATE_BOOLEAN);
                
		$this->is_prev_flag = filter_var($this->input->post("is_prev_flag"), FILTER_VALIDATE_BOOLEAN);
		$this->refresh_order();
		$is_usage_type_render_flag = false;
		$is_exclusive_render_flag = false;
		$is_filetype_render_flag = false;
		$is_licence_render_flag = false;

		if($this->sess_userid<=0){
			$this->response(array('error' => 'Please login to buy.'), 404);
		}
		if($this->track["userId"] == $this->sess_userid)
		{
			$this->response(array('error' => 'You can\'t buy your own song.'), 404);
		}


		if($this->track["album_full_buyable"] == true)
		{
			$this->load->model("album_model");
			$album_details =  $this->album_model->get_album_details($this->track["albumId"]);
			$this->response_ar["track_list"] = $album_details["list"]; 
			$this->response_ar["album_fully_buyable"] = true;
			$this->response_ar["album_price"] = $album_details["price"];
			$this->response_ar["albumid"] = $album_details["id"];
			$this->response($this->response_ar, 200);	
		}
		else if($this->track["is_track_sellable"] == true)
		{
			if($type == "usage_type")
			{
				$is_usage_type_render_flag = true;
			}
			else if($type == "exclusive")
			{
                                //list of exclusive/non-exclusive licenses
				$is_exclusive_render_flag = true;
			}
			else if($type == "trackfiletype")
			{
                                //licenses
				$is_filetype_render_flag = true;	
			}
			else if($type == "licence_types")
			{
				$is_licence_render_flag=true;
			}
			else if(!empty($this->order)){

				$is_licence_render_flag = true;
				$is_usage_type_render_flag = ($this->order["is_usage_type"] == "y") ? false : true;
				$is_exclusive_render_flag = ($this->order["is_exclusive"] == "y") ? false : true;
				$is_filetype_render_flag = ($this->order["is_filetype"] == "y") ? false : true;
				
				if($is_licence_render_flag===true){
					$this->values=$this->order['filetype_id'];
				}
				else if($is_filetype_render_flag==true){
                                    //license flag
					$this->values=$this->order['exclusive_id'];
                                        
				}else if($is_exclusive_render_flag==true){
					$this->values=$this->order['usage_id'];
				}

			}
			else if(empty($this->order)){
				
				$is_usage_type_render_flag = true;
				
			}

			/*if(!empty($this->order) && $this->is_prev_flag===false)
			{

			}*/
			
			//var_dump($is_licence_render_flag,$is_filetype_render_flag,$is_exclusive_render_flag);exit;
			if($is_licence_render_flag === true){
				$this->licences();
				$this->response($this->response_ar, 200);	
			}
			if($is_filetype_render_flag === true){
				$this->filetypes();
				$this->response($this->response_ar, 200);	
			}
			if($is_exclusive_render_flag === true){
				$this->exclusive();
				$this->response($this->response_ar, 200);	
			}
			if($is_usage_type_render_flag === true){	
				
				$this->render_usage_types();
				$this->response($this->response_ar, 200);
			}
			
			
			
		}
		else
		{
			$this->response_ar["nobuy_button"] = true;
			$this->response($this->response_ar, 200);	
		}
	}


	function cart_item_post(){
		$id = $this->post("id");
		$price = $this->post("price");
		$trackid = $this->post("trackid");
		$orderid = $this->post("orderid");
		if($id > 0 && $price > 0 && $trackid > 0)
		{
			$this->response_ar = $this->icart->set_licences($trackid,$orderid,$id);
			$this->response($this->response_ar, 200);
		}
		else
		{
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}

	function cart_content_post_old($type = null)
	{
		$buy_step_id = $type;
		$values = $this->input->post("values");
		$trackid = $this->input->post("trackid");
		$this->load->model("track_detail");
		$track_common_detail = $this->track_detail->get_song_details($trackid);
		if($buy_step_id == "trackfiletype")
		{
			$values = rtrim($values,",");
			/*$this->session->set_userdata("trackfiletype",$values);*/
			$buy_session_current_ar = $this->session->userdata('buy_'.$trackid);  
			$buy_session_current_ar["trackfiletype"] = $values;
			$this->session->set_userdata('buy_'.$trackid,$buy_session_current_ar); 
			$this->load->model("icart");
			$response["licence_type_list"] = $this->icart->fetch_buy_exclusive_types($trackid);
			$response["head_title"] = "Choose exclusive licence";
			$step_name = "exclusive";
			$response["status"] = "success";
			$nexturl = base_url()."api/cart/licence_types";
			$prevurl = base_url()."api/cart";
		}
		else if($buy_step_id == "exclusive"){
			$this->load->model("icart");
			$response["licence_type_list"] = $this->icart->fetch_buy_exclusive_types($trackid);
			$response["head_title"] = "Choose exclusive licence";
			$step_name = "exclusive";
			$nexturl = base_url()."api/cart/licence_types";
			$prevurl = base_url()."api/cart/usage_type";
			$response["status"] = "success";
		}
		else if($buy_step_id == "usage_type")
		{	
			$this->load->model("icart");
			$response["licence_type_list"] = $this->icart->fetch_buy_usage_types();
			$response["head_title"] = "Usage Types";
			$response["status"] = "success";
			$nexturl = base_url()."api/cart";
			$prevurl = "";
		}
		else if($buy_step_id =="licence_types"){
			$values = rtrim($values,",");
			/*$this->session->set_userdata("exclusive_type",$values);*/
			$buy_session_current_ar = $this->session->userdata('buy_'.$trackid);  
			$buy_session_current_ar["exclusive_type"] = $values;
			$this->session->set_userdata('buy_'.$trackid,$buy_session_current_ar); 
			$track_common_detail = $this->track_detail->get_song_details($trackid);
			$response["licence_type_list"] = $track_common_detail["licence_detail"];
			$response["head_title"] = "CHOOSE YOUR LICENCE TYPE";
			$response["status"] = "success";
			$response["display_cart_div"] = true;
			$nexturl = "";
			$prevurl = base_url()."api/cart/exclusive";
		}
		else{
			$values = rtrim($values,",");
			$trackfiletype = $this->session->userdata("trackfiletype");
			$this->session_ar["trackid"] =  $trackid;
			$this->session_ar["usage_type"] =  $values;
			$this->session->set_userdata("buy_".$trackid,$this->session_ar);
			if($values != "" || $trackfiletype != "")
			{
				/*Check for current song is available for wav also */
				if(!empty($track_common_detail))
				{
					/*buy_usage_types*/
					$exclusive_det_ar = getvalfromtbl("type","buy_exclusive_types","id='".$buy_session_current_ar["exclusive_type"]."'","single"); 
					if(count($track_common_detail["trackFileTypes"]) == 1)
					{
						$this->load->model("icart");
						$response["licence_type_list"] = $this->icart->fetch_buy_exclusive_types();
						$response["head_title"] = "Choose exclusive licence";
						$step_name = "exclusive";
						$nexturl = base_url()."api/cart/licence_types";
						$prevurl = base_url()."api/cart/usage_type";
						$response["status"] = "success";
					}
					else{
						foreach ($track_common_detail["trackFileTypes"] as $key => $value) {
							$value["row_class"] = "buy_data buy_row_li";
							$value["type"] = "trackfile_type";						
							$response["licence_type_list"][] = $value;
						}
						$step_name = "trackfiletype";
						/*$nexturl = base_url()."api/cart/exclusive";*/
						$nexturl = base_url()."api/cart/trackfiletype";
						$prevurl = base_url()."api/cart/usage_type";
						$response["status"] = "success";
					}
				}
				/*Check for current song is available for wav also */
			}
			else{
			}
		}
		$response["nexturl"] = ($nexturl != "") ? $nexturl : "";
		$response["prevurl"] = ($prevurl != "") ? $prevurl : "";
		if($response != "")
		{	
			$this->response($response, 200);				
		}
		else
		{
			$this->response(array('error' => lang('error_try_again')), 404);
		}
	}
}//class over
