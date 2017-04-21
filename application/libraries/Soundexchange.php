<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
/**
 * SoundExchange
 * A php wrapper for sox (Sound Exchange)
 *
 * @author John Arroyo www.johnarroyo.com
 * @package soundexchange
 * @copyright Copyright (c) 2011, John Arroyo
 */

class Soundexchange 
{
	public function __construct() { 

	}
	
	/**
	 * Make a full sox call
	 *
	 * @param String $sox_cmd a full sox command string minus the sox keyword
	 * @return String $return value from 'sox' command
	 */
	public function sox($sox_cmd)
	{
		return exec("sox $sox_cmd");
	}
	
	/**
	 * Mix 2 or more audio files
	 * 
	 * @param Array $filenames
	 * @param String $outputFile
	 * @return 
	 */
	public static function mix($filenames, $outputFile, $bitrate = 128)
	{
		if (is_array($filenames) AND (count($filenames) > 1))
		{
			$files = implode(" ", $filenames);
			$cmd = "sox -m $files $outputFile";
		} else
		{
			$msg = "You must submit an array of 2 or more files";
		}
		
		$msg = exec($cmd);		
		error_log('cmd: '.$cmd);
		
		return $msg;
	}
	
	/**
	 * Concatenate 2 or more audio files
	 * 
	 * @param Array $filenames
	 * @param String $outputFile
	 * @return String $message error message, empty if no errors
	 */
	public static function concatenate($filenames, $outputFile, $bitrate = 128)
	{
		if (is_array($filenames) AND (count($filenames) > 1))
		{
			$files = implode(" ", $filenames);
			$cmd = "sox --combine concatenate $files $outputFile";
		} else
		{
			$msg = "You must submit an array of 2 or more files";
		}
		
		$msg = exec($cmd);
		error_log('concatenate cmd: '.$cmd);
		
		return $msg;
	}
	
	/**
	 *
	 */
	public static function trim($oldfile, $newfile, $start, $duration)
	{
		$cmd = "sox $oldfile $newfile trim $start $duration";
		error_log('concatenate cmd: '.$cmd);
		
		return exec($cmd);
	}
	
	/**
	 * Get audio file stats
	 *
	 * @param String $filename
	 * @return Array $infoArray 
	 */
	public static function stats($filename)
	{
		$cmd = "sox $filename -e stat";
		$output = array();
		$value = exec($cmd);
		
		// parse return value
		$output['error_msg'] = "";
		error_log('concatenate cmd: '.$cmd);
	
		return $output;
	}
	
	/**
	 * Create a preview file
	 * 
	 */
	public static function preview($file, $duration, $bitrate, $outputFile = null, $normalize = TRUE)
	{
		// find the length
		// trim based on duration and length
		// sox infile outfile gain −n −3
		// resample
		// if no output file name output based on input file + '_preview.mp3'
		
		// error_log('concatenate cmd: '.$cmd);
	}
	
	/**
	 * Get audio file info
	 *
	 * @param String $filename
	 * @return Array $infoArray 
	 */
	public static function info($filename)
	{
		$cmd = "sox --i $filename";
		error_log('concatenate cmd: '.$cmd);
		
		return exec($cmd);
	}
	
	/**
	 * 
	 */
	public function convert($original, $converted, $lame = FALSE, $bitrate = 128)
	{
		if ($lame)
		{
			$cmd = "lame -h -b $bitrate $original $converted";
		} else
		{
			$cmd = "sox $original $converted";
		}
		return exec($cmd);
	}
}

?>