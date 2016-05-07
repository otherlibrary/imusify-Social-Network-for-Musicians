<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webhooks extends CI_Controller {
	function __construct()
	{
		parent::__construct();
		$this->load->library("Braintree_lib");	
		$this->load->model('Membership_model');
		$this->load->model('payment_transactions');

	}
	function cancel()
	{	

		if(isset($_GET["bt_challenge"])) {
			$temp=(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
			echo $temp;
			$insert_array = array(
				'data' => $temp
				);
			$this->db->insert('temp',$insert_array);
		}
		else if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) 
		{
			$webhookNotification = Braintree_WebhookNotification::parse($_POST["bt_signature"], $_POST["bt_payload"]);
			$subscriptionId = $webhookNotification->subscription->id;
			$this->Membership_model->braintree_cancel_subscription($subscriptionId);
			$message = "[Webhook Received " . $webhookNotification->timestamp->format('Y-m-d H:i:s') . "] "
			. "Kind: " . $webhookNotification->kind . " | "	. "Subscription: " . $webhookNotification->subscription->id . "\n";
			$insert_arrays = array('data' => $message);
			$this->db->insert('temp',$insert_arrays);			
		}
	}

	/*Function for charging successfully*/
	function charge_successfully()
	{	
		if(isset($_GET["bt_challenge"])) {
			$temp=(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
			/*echo $temp;
			$insert_array = array(
				'data' => $temp
				);
			$this->db->insert('temp',$insert_array);*/
		}
		else if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) 
		{
			$webhookNotification = Braintree_WebhookNotification::parse($_POST["bt_signature"], $_POST["bt_payload"]);

			$message = "Webhook Received : charge_successfully ".json_encode($webhookNotification);
			$insert_arrays = array('data' => $message);
			$this->db->insert('temp',$insert_arrays);

			/*Update all db details*/
			$this->payment_transactions->webhook_success_charge($webhookNotification);
			/*Update all db details*/
		}
	}
	/*Function for charging successfully*/

	/*Function for not charging successfully*/
	function charged_not_successfully()
	{	
		if(isset($_GET["bt_challenge"])) {
			$temp=(Braintree_WebhookNotification::verify($_GET["bt_challenge"]));
			echo $temp;
			$insert_array = array(
				'data' => $temp
				);
			$this->db->insert('temp',$insert_array);
		}
		else if(isset($_POST["bt_signature"]) && isset($_POST["bt_payload"])) 
		{
			$webhookNotification = Braintree_WebhookNotification::parse($_POST["bt_signature"], $_POST["bt_payload"]);

			$message = "Webhook Received : charged not successfully ".json_encode($webhookNotification);
			$insert_arrays = array('data' => $message);
			$this->db->insert('temp',$insert_arrays);			
		}
	}
	/*Function for not charging successfully*/



}