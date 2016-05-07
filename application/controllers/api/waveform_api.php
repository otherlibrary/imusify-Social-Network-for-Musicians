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

class Waveform_Api extends REST_Controller
{
	function waveform_get($user,$track){
     	
		$userId = getvalfromtbl("id","users","profileLink = ".$this->db->escape($user)."","single");
    	$trackId = getvalfromtbl("id,trackName","tracks","userId = '".$userId."' AND perLink = ".$this->db->escape($track)."","multiple");
		
		if($userId > 0 && $trackId["id"] > 0)
		{			
			$temp_name = preg_replace('/\\.[^.\\s]{3,4}$/', '', $trackId["trackName"]);			
			$data = file_get_contents(base_url().'assets/upload/wavejson/'.$temp_name.".json");
			//dump($json);
    		$json = json_decode($data, TRUE);
   		}
   		
   		print_r($data);

	    /*echo ('<pre> print the json ');
	   		 print_r ($json);
	    echo ('</pre>');

	    echo '<br>output:</br>';*/

    /*foreach ($json as $key => $value)
    {
        switch ( $key ) {
            case 'name' :
                echo "Name: $value";
                break;
            case 'cob' : 
                echo ' Status: ' . $value['status'] . ']<br />';
                break;
            case 'another field' :
                // and so on
                break;
        }

	}*/        
	}
	
}