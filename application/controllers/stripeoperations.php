<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(FCPATH.'application/libraries/stripe/lib/Stripe.php');
class stripeoperations extends CI_Controller {
	/*http://stackoverflow.com/questions/23728387/stripe-js-wont-charge-with-ajax-submit*/
	function __construct()
	{
		parent::__construct();
		Stripe::setApiKey(STRIPE_API_KEY);
	}
	
	function deauthorize()
	{	
		$body = @file_get_contents('php://input');
		$event_json = json_decode($body); 

		$this->db->query("insert into temp(data) values('".$body."')");

	}

	function redirect_stripe(){
		$authorize_request_body = array(
			'response_type' => 'code',
			'scope' => 'read_write',
			'client_id' => CLIENT_ID
			);
		$url = AUTHORIZE_URI . '?' . http_build_query($authorize_request_body);
		redirect($url);
	}

	function authorize(){
		$this->load->model('stripe_operation');
		$response = $this->stripe_operation->user_stripe_connect_check();

		if($response["status"] != "success")
		{
			if($this->input->get('code')){
				$code = $this->input->get('code');
				$token_request_body = array(
					'client_secret' => STRIPE_API_KEY,
					'grant_type' => 'authorization_code',
					'client_id' => CLIENT_ID,
					'code' => $code
					);

				$req = curl_init(TOKEN_URI);
				curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($req, CURLOPT_POST, true );
				curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));
				$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
				$resp = json_decode(curl_exec($req), true);
				curl_close($req);

				if($resp && $resp["stripe_user_id"])
				{
					$this->load->model("stripe_operation");
					$response = $this->stripe_operation->insert_authorized_user_data($resp);
					$response_js["status"] = "success";
					$response_js["msg"] = "Your account is connected with stripe successfully.";
				}
				else{
					$response_js["status"] = "error";
					$response_js["msg"] = "Error Please try again.";
				}
			}
			else if($this->input->get('error')){
				$response_js["status"] = "error";
				$response_js["msg"] = $this->input->get('error_description');
			}
		}		
		$this->load->view('stripe_connect',$response_js);
	}

	function purchase_song($orderid){
		$order_id =  $this->session->userdata('order_'.$orderid);

		if($order_id)
		{
			$this->load->model("order_model");
			$order_data = $this->order_model->get_order_details($order_id);
			$data["total"] = $order_data["total"]*100;
			$data["imusifyPrice"] =$order_data["imusifyPrice"];
			$data["composerPrice"] = $order_data["composerPrice"];
			$data["tracktitle"] = "a";
			$this->load->view('song_buy',$data);
		}
		else{

		}
	}

	function albumpayment(){
		$response = array();
		$order_random_id = $this->input->post("order_random_id");
		$stripeToken = $this->input->post("token");
		if($order_random_id && $stripeToken)
		{
			$orderid = getvalfromtbl("id","orders","order_random_id = '".$order_random_id."'","single");
			$order_id =  $this->session->userdata('order_'.$orderid);
			$this->load->model("order_model");
			$this->load->model("stripe_operation");
			$order_data = $this->order_model->get_order_details($order_id);
			$trackId = $order_data["trackId"];
			$destination = $this->stripe_operation->get_stripe_accountid($order_data["trackId"]);
			$destination = 'acct_16Vdk0KG2x9jYzJH';
			
			if($destination){
				try {			
					$charge = Stripe_Charge::create(array(
						"amount" => $order_data["total"]*100, 
						"currency" => "USD",
						"source" => $stripeToken,
						"description" => "Membership charge",
						'destination' => $destination,
						'application_fee' => $order_data["imusifyPrice"]*100
						));
					$charge_arr = serialize($charge); 
					
					if($charge->paid == 1)
					{
						$response["status"] = "success";
						$res = $this->order_model->album_order_success($order_id,json_encode($charge_arr),$order_data);
						$response["msg"] = "You have successfully bought album.Kindly check email for more information.";
					}
					else{
						$response["status"] = "error";
						$response["msg"] = "Something went wrong.Please try again.";
					}
				} catch(\Stripe\Error\Card $e) {
					$response["status"] = "error";
					$response["msg"] = "Something went wrong.Please try again.";
					$response["charge_arr"] = $charge_arr;
				}
			}
			else{
				$response["status"] = "error";
				$response["msg"] = "Something went wrong.Please try again.";
			}
		}
		else{
			$response["status"] = "error";
			$response["msg"] = "Something went wrong.Please try again.";
		}		
		print json_encode($response);
		exit;
	}


	function trackpayment(){
		$response = array();
		$order_random_id = $this->input->post("order_random_id");
		$stripeToken = $this->input->post("token");
		if($order_random_id && $stripeToken)
		{
			$orderid = getvalfromtbl("id","orders","order_random_id = '".$order_random_id."'","single");
			$order_id =  $this->session->userdata('order_'.$orderid);
			$this->load->model("order_model");
			$this->load->model("stripe_operation");
			$order_data = $this->order_model->get_order_details($order_id);
			$trackId = $order_data["trackId"];
			$destination = $this->stripe_operation->get_stripe_accountid($order_data["trackId"]);
			$destination = 'acct_16Vdk0KG2x9jYzJH';
			
			if($destination){
				try {			
					$charge = Stripe_Charge::create(array(
						"amount" => $order_data["total"]*100, 
						"currency" => "USD",
						"source" => $stripeToken,
						"description" => "Membership charge",
						'destination' => $destination,
						'application_fee' => $order_data["imusifyPrice"]*100
						));
					$charge_arr = serialize($charge); 
					
					if($charge->paid == 1)
					{
						$response["status"] = "success";
						$res = $this->order_model->order_success($order_id,json_encode($charge_arr),$order_data);
						$response["msg"] = "You have successfully bought track.Kindly check email for more information.";
					}
					else{
						$response["status"] = "error";
						$response["msg"] = "Something went wrong.Please try again.";
					}
				} catch(\Stripe\Error\Card $e) {
					$response["status"] = "error";
					$response["msg"] = "Something went wrong.Please try again.";
					$response["charge_arr"] = $charge_arr;
				}
			}
			else{
				$response["status"] = "error";
				$response["msg"] = "Something went wrong.Please try again.";
			}
		}
		else{
			$response["status"] = "error";
			$response["msg"] = "Something went wrong.Please try again.";
		}		
		print json_encode($response);
		exit;
	}
}