<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model("commonfn");

	}

	function RemoveFiles($path,$timeago) {
		$files = array();
		$index = array();
		if ($handle = opendir($path)) {
    	#clearstatcache();   # not needed, unlink() clears the cache automatically
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					if(is_dir($path.'/'.$file))
						$this->RemoveFiles($path.'/'.$file,$timeago);
					elseif(filemtime( $path.'/'.$file ) < $timeago) {
						unlink($path.'/'.$file);
						//echo 'Deleted '.$path.'/'.$file."\n";
					}
				}
			}
			closedir($handle);
		}
	}

	public function generate_waveform(){
		$this->commonfn->generate_tracks_waveform();
	} 
	
	function index()
	{	
		$date = date('Y-m-d');
		
		/*For removing songs from temp directory*/	
		$dir = asset_path()."temp/";		
		$this->RemoveFiles($dir,strtotime('-1 day'));	
		/*For removing songs from temp directory ends*/

		/*Active this user if his block time is overed*/
		//$this->commonfn->unblock_follow_user();			
		/*Active this user if his block time is overed*/
		
		/*generate waveform if it is not already made*/
		/*$this->commonfn->generate_tracks_waveform();*/
		/*generate waveform if it is not already made ends*/


		exit;		
	}


	/*Function for checking if free coupon time is completed*/
	function check_plan_exist_coupon(){
		$this->load->model("Membership_model");
		$this->Membership_model->change_coupon_membership();
	}
	/*Function for checking if free coupon time is completed*/



	
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */