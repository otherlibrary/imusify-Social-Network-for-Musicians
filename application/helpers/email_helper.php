<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

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
}
