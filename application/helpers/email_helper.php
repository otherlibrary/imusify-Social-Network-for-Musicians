<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once APPPATH . 'third_party/GeoIP2/geoip2.phar';

if ( ! function_exists('send_mail'))
{
	function send_mail($from,$to,$subject,$message,$attachment = false,$attachment_file = null)
	{
        //get an instance of CI so we can access our configuration
		$CI =& get_instance();
		$CI->load->library( 'email' );
		$CI->email->from($from, 'Admin-Imusify');
		$CI->email->to($to);
		$CI->email->set_mailtype('html');
		$CI->email->subject($subject);
		$CI->email->message($message);
		if($attachment == true && $attachment_file != null)
		{
			$CI->email->attach($attachment_file);
		}


		$CI->email->send();
        //return the full asset path
        //return base_url() . $CI->config->item('asset_path');
	}
        
        function get_ip_country_code($ip_address) {
            
            $reader = new GeoIp2\Database\Reader(APPPATH.'third_party/GeoIP2/GeoLite2-City.mmdb');
            //$record = $reader->country($ip_address);
            $record = $reader->city($ip_address);                        
            return $record->country->isoCode;
        }
 
        function get_ip_country($ip_address) {
            
            $reader = new GeoIp2\Database\Reader(APPPATH.'third_party/GeoIP2/GeoLite2-City.mmdb');
            //$record = $reader->country($ip_address);
            $record = $reader->city($ip_address);                        
            return $record->country->name;
        }
        
       function get_ip_eu($country) {
            $euro = ['AD','AT','BE','CY','DE','EE','ES','FI','FR','GR','IE','IT','LV','LU','MC','ME','MT','NL','PT','SI','SK','SM','VA'];
            $result = false;
            if (array_search($country, $euro)) $result = true;            
            return $result;
        }        
                                
        function get_ip_city($ip_address) {
            
            $reader = new GeoIp2\Database\Reader(APPPATH.'third_party/GeoIP2/GeoLite2-City.mmdb');
            //$record = $reader->country($ip_address);
            $record = $reader->city($ip_address);

            return $record->city->name;
          }
          
        function get_ip_state($ip_address) {
            
            $reader = new GeoIp2\Database\Reader(APPPATH.'third_party/GeoIP2/GeoLite2-City.mmdb');
            //$record = $reader->country($ip_address);
            $record = $reader->city($ip_address);

            return $record->mostSpecificSubdivision->name;
          }
                    
        
}
