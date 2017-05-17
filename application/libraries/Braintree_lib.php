<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH.'third_party/Braintree.php';

/*
 *  Braintree_lib
 *  This is a codeigniter wrapper around the braintree sdk, any new sdk can be wrapped around here
 *  License: MIT to accomodate braintree open source sdk license (BSD)
 *  Author: Clint Canada
 *  Library tests (and parameters for lower Braintree functions) are found in:
 *  https://github.com/braintree/braintree_php/tree/master/tests/integration
 */

/*
    General Usage:
        In Codeigniter controller
        function __construct(){
            parent::__construct();
            $this->load->library("braintree_lib");
        }

        function <function>{
            $token = $this->braintree_lib->create_client_token();
            $data['client_token'] = $token;
            $this->load->view('myview',$data);
        }

        In View section
        <script src="https://js.braintreegateway.com/v2/braintree.js"></script>
        <script>
              braintree.setup("<?php echo $client_token;?>", "<integration>", options);
        </script>

    For more information on javascript client: 
    https://developers.braintreepayments.com/javascript+php/sdk/client/setup
 */

    class Braintree_lib extends Braintree{

       function __construct() {
        parent::__construct();
        // We will load the configuration for braintree
        $CI = &get_instance();
        $CI->config->load('braintree', TRUE);
        $braintree = $CI->config->item('braintree');
        // Let us load the configurations for the braintree library
        Braintree_Configuration::environment($braintree['sandbox']);
        Braintree_Configuration::merchantId($braintree['v6wyx6s394n5wn9m']);
        Braintree_Configuration::publicKey($braintree['wpyc9j4s7k3r7ry2']);
        Braintree_Configuration::privateKey($braintree['ebbd2e9a75d61570bc4b93e85809b10a']);
    }

    // This function simply creates a client token for the javascript sdk
    function create_client_token($customerId = NULL){
    	if($customerId != NULL)
        {
            $clientToken = Braintree_ClientToken::generate(array('customerId'=>$customerId));
        }else{
            $clientToken = Braintree_ClientToken::generate();

        }
        return $clientToken;

    }

    /*Custom j*/
    function create_customer($cust_array){
        extract($cust_array);
        $result = Braintree_Customer::create(array(
            'firstName' => $firstName,
            'lastName' => $lastName,
            'paymentMethodNonce' => $paymentMethodNonce
            ));
        if ($result->success) {

            return $result;
            /*echo($result->customer->id);
            echo($result->customer->paymentMethods()[0]->token);*/
        } else {
            foreach($result->errors->deepAll() AS $error) {
                $msg = $error->code . ": " . $error->message . "\n";
                //echo($error->code . ": " . $error->message . "\n");
            }
            return $msg;
        }
    }

    function create_subscription($array){
        //var_dump ($array);exit;
        $return = Braintree_Subscription::create($array);
       /* echo "<pre>";
        print_r($return);*/
        //exit;
        return $return;
    }    

    function update_subscription($subscriptionId,$array){
         /*echo "  a  ".$subscriptionId;
        echo "<pre>";
        print_r($array);
        */
        $return = Braintree_Subscription::update($subscriptionId,$array);
        /* echo "<pre>";
        print_r($return);
        exit;*/
        return $return;
    }  

    function search_subscription($array){
        /*the_subscription_id*/
        $return = Braintree_Subscription::find($array);
        return $return;
    }
    
    
    
    function cancel_subscription($subscriptionId){
        /*extract($array);the_subscription_id*/
        $return = Braintree_Subscription::cancel($subscriptionId);                
        return $return;
    }
    /*Custom j*/
    function import_subsciption_plan(){
        $plans = Braintree_Plan::all();
        return $plans; 
    }
}