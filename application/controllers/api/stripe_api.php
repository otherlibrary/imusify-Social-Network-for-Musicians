<?php defined('BASEPATH') OR exit('No direct script access allowed');


require APPPATH.'/libraries/REST_Controller.php';

class Stripe_Api extends REST_Controller
{

  function __construct() {
    parent::__construct();
    $this->sess_id = $this->session->userdata('user')->id;          
  }

  public function user_connect_check_post(){
    $this->load->model('stripe_operation');
    $data = $this->stripe_operation->user_stripe_connect_check(); 

    if(!empty($data))
    {   
      $this->response($data, 200); 
    }
    else
    {
      $this->response(array('error' => 'Error in validating user.'), 404);
    }

  }

  public function buyalbum_post(){
   $albumid = $this->post("albumid");
   if($albumid)
   {
     $this->load->model('stripe_operation');
     $data = $this->stripe_operation->buy_album_check($albumid); 
     $this->session->set_userdata("order_".$data["order_id"],$data["order_id"]);  
     if(!empty($data))
     {   
       $this->response($data, 200); 
     }
     else
     {
      $this->response(array('error' => 'Invalid Request.'), 404);
    }
  }
  else{
     $this->response(array('error' => 'Invalid Request.'), 404);
  }
}


public function buytrack_post(){
  $values = $this->post("values");
  $trackid = $this->post("trackid");
  $values = rtrim($values,',');

  if($trackid && $values)
  {
   $this->load->model('stripe_operation');
   $data = $this->stripe_operation->buy_song_check($values,$trackid);
          /* $this->session->set_userdata("trackId",$trackid);    
           $this->session->set_userdata("total",$data["total"]);    
           $this->session->set_userdata("imusify_price",$data["imusify_price"]);    
           $this->session->set_userdata("composer_price",$data["composer_price"]); */ 
           $this->session->set_userdata("order_".$data["order_id"],$data["order_id"]);  
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