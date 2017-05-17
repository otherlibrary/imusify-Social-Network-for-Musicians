<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
*/

require APPPATH.'/libraries/REST_Controller.php';
require APPPATH.'/libraries/vatValidation.class.php';

class Vat_api extends REST_Controller
{
        
        function __construct()
	{
                parent::__construct();
		//$this->load->model('Membership_model');		                
                //$this->load->library("braintree_lib");                
                $this->load->model('Ilogin');		
	}
        
	function check_current_country_get()
	{	
                //US
                //$ip_address = '192.185.148.51';
                //DE
                //$ip_address = '95.90.232.152';
                $ip_address = $this->input->ip_address();                
                //default value
                $country = 'DE';                                
                $euro = true;
                $city = '';
                $state = '';                  
                try {
                    $country = get_ip_country_code($ip_address);                                
                    $city = get_ip_city($ip_address);
                    $state = get_ip_state($ip_address);
                    $euro = get_ip_eu($country);
                } catch (Exception $e) {
                    //address is not found in database                     
                }                
                $ar['country'] = $country;
                $ar['eu'] = $euro;
                $ar['city'] = $city;
                $ar['state'] = $state;                
		$this->response($ar,200);
	}
        
        function vat_update_post()
	{	
                $length = 0;
                if($this->post('id')) $length = strlen(trim($this->post('id')));    
                if($this->post('id') && $length > 6){
                    $ar['status'] = 'success';                    
                    $temp = str_split(trim($this->post('id')));                                        
                    $vat_id = substr($this->post('id'), 2);
                    $country_code = $temp[0].$temp[1];
                    //e.g. GB727255821
                    //var_dump ($country_code, $id_remaining,$length, $temp);exit;                    
                    //$vatValidation = new vatValidation( array('debug' => true));                                        
                    $vatValidation = new vatValidation( array('debug' => false));                                        
                    if($vatValidation->check($country_code, $vat_id)) {                            
                            $denomination = $vatValidation->getDenomination();
                            $name = $vatValidation->getName();
                            $address = $vatValidation->getAddress();
                            $ar['name'] = $name;                            
                            $address = preg_replace('/\s+/', ' ', trim($address));
                            $ar['address'] = $address;                            
                            $ar['denomination'] = $denomination;                            
                            //update VAT ID and address, name on db
                            $result = $this->Ilogin->update_vat(trim($this->post('id')), trim($denomination.'/'.$name), $address);
                            $this->response($ar,200);
                    } else {
                        $ar['status'] = 'error';
                        $ar['msg'] = 'The VAT ID is not valid';
                        $this->response($ar,200);                            
                    }                                        
                } else {
                    $ar['status'] = 'error';
                    $ar['msg'] = 'The VAT ID is not valid';
                    $this->response($ar,200);
                }		
	}
        

}