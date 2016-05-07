<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class download extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();	
		$this->load->helper('download');	
		$this->sess_userid = $this->session->userdata('user')->id;
	}

	function index($order_random_id = null,$type = "track")
	{	

		$redirect_home = false;

		if($this->sess_userid > 0)
		{
			if($order_random_id != null)
			{	
				$order_random_id_ar = explode(".",$order_random_id);
				$order_random_id = $order_random_id_ar[0];
				$param_ext = $order_random_id_ar[1];

				$order_data = getvalfromtbl("*","orders","userId = '".$this->sess_userid."' AND order_random_id = '".$order_random_id."'");

				if($order_data)
				{	

					$this->load->model("track_detail");
					$trackdata = $this->track_detail->get_song_details($order_data["trackId"]);



					/*$filetype_id = $order_data["filetype_id"];*/
					$extension = getvalfromtbl("extension","tracktypes","extension='".$param_ext."'","single");



					if($trackdata)
					{	
						$db_track_filename = $trackdata["trackName"];
						$db_track_filename_ar = explode(".",$db_track_filename);
						if($type == "track")
						{

							$physical_filepath = FCPATH."assets/upload/media/".$trackdata["userId"]."/".$db_track_filename_ar[0]."/".$extension;
							if(file_exists($physical_filepath))
							{
								$file = FCPATH."assets/upload/media/".$trackdata["userId"]."/".$db_track_filename_ar[0]."/".$extension;
								$title_ext = ".".$filename;
							}
							else{
								$file = FCPATH."assets/upload/media/".$trackdata["userId"]."/".$db_track_filename;
								$title_ext = ".mp3";
							}

						}
						else if($type == "licence")
						{
							$title_ext = ".pdf";
							$file = FCPATH."assets/upload/track_licences/".$order_data["order_random_id"].".pdf";
						}
						ob_clean();
						$path    =   file_get_contents($file);
						$name    =   $trackdata["title"].$title_ext;
						force_download($name, $path);
					}
				}
				else{
					$redirect_home = true;								
				}
			}
			if($redirect_home == true)
			{
				$this->session->set_userdata('notification','Invalid request.');
				redirect(base_url(),"refresh");
				exit;
			}
		}else{
			$this->session->set_userdata('notification','Please login to access this page.');
			redirect("sign_in","refresh");
			exit;
		}


	}
}