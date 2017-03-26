<?php defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/libraries/REST_Controller.php';
include_once(APPPATH.'/libraries/config.php');
include_once(APPPATH.'/libraries/functions.php');
include_once(APPPATH.'/libraries/paypal.class.php');

class Paypal_Api extends REST_Controller
{

  function __construct() {
    parent::__construct();
    $this->sess_id = $this->session->userdata('user')->id;       
  }


public function process_get(){
    $this->load->model("order_model");
    $token = $this->get("token");
    $payer_ID = $this->get("PayerID");
    $url = $this->session->userdata('url');
    $paypal= new MyPayPal();
    $result = $paypal->DoExpressCheckoutPayment();
    $success = true; 
    if(is_array($result)){
        if($result['id'] != 0){
            if($result['message'] == 'Success'){
                //store all transaction details     
                $order_id = intval($this->session->userdata('order_id')); 
                $order_data = $this->order_model->get_order_details($order_id);
                $result2 = $paypal->GetTransactionDetails();      
                $result2 = json_encode($result2);
                $result2 = urldecode($result2);                                                               
                $order_update = $this->order_model->order_success($order_id,$result2,$order_data);
            }else{
                //pending payment
                $success = FALSE;
            }                        
        } else {
            //failed
            $success = FALSE;
        }
    } else {
        $success = FALSE;
    }
    //var_dump($result,$result2);
    //exit;
            
    $token_current = $this->session->userdata('ec_token');
    $url = $this->session->userdata('url');
    if($success) $url = $url.'?payment='.$token;
    else $url = $url.'?failed='.$token; 
    
    //var_dump($url);exit;
    redirect($url,"refresh");
    exit;
    
}  

public function cancel_get(){
    
    $token = $this->get("token");    
    
    $token_current = $this->session->userdata('ec_token');
    $url = $this->session->userdata('url');
    $url = $url.'?cancel='.$token;
    //var_dump($url);exit;
    redirect($url,"refresh");
    exit;
    
}  


public function buytrack_post(){
  $values = $this->post("values");
  $trackid = $this->post("trackid");
  $url = $this->post("url");
  $values = rtrim($values,',');
  $paypal= new MyPayPal();
  $this->load->helper('url');
  if($trackid && $values)
  {
   $this->load->model('stripe_operation');
   $data = $this->stripe_operation->buy_song_check($values,$trackid);          
   $this->session->set_userdata("order_".$data["order_id"],$data["order_id"]);  
   $products = [];
		
    // set an item via POST request

        $products[0]['ItemName'] = $data['title'];
        $products[0]['ItemPrice'] = (float) ($data['total'] / 100);
        $products[0]['ItemNumber'] = 1;
        $products[0]['ItemDesc'] = $data['title'];
        $products[0]['ItemQty']	= 1;

        //-------------------- prepare charges -------------------------

        $charges = [];		
        //Other important variables like tax, shipping cost
        $charges['TotalTaxAmount'] = 0;  //Sum of tax for all items in this order. 
        $charges['HandalingCost'] = 0;  //Handling cost for this order.
        $charges['InsuranceCost'] = 0;  //shipping insurance cost for this order.
        $charges['ShippinDiscount'] = 0; //Shipping discount for this order. Specify this as negative number.
        $charges['ShippinCost'] = 0; //Although you may change the value later, try to pass in a shipping amount that is reasonably accurate.
        //		
        //------------------SetExpressCheckOut-------------------		
        //We need to execute the "SetExpressCheckOut" method to obtain paypal token
        $data['token'] = $paypal->SetExpressCheckOut($products, $charges);
        $data['token'] = urldecode($data['token']);
        $this->session->set_userdata("ec_token",$data["token"]); 
        $this->session->set_userdata("url",$url); 
        $this->session->set_userdata("order_random_id",$data["order_random_id"]); 
        $this->session->set_userdata("order_id",$data["order_id"]); 
        $data['merchant_account_id'] = Merchant_Account_ID;
        //$url = current_url();
        //var_dump($data, $url);exit;
        //$data['paypal_token'] = $token;        
           if(!empty($data))
           {                                 
            $this->response($data, 200); 
          }
          else
          {
            $this->response(array('error' => 'Error in validating user.'), 404);
          }  
        }
      }
    }