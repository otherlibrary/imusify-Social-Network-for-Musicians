<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MP3Waveform
{
	public static $LAME_PATH = "/usr/bin/lame";	
	public $file_name;
	public $json_file_name;
	public $wave_path;
	public $temp_path;
	public $tmp_name;
        public $delete_temp;
	public  function __construct($array='')
	{
		
		if(!empty($array)){
			foreach ($array as $key => $value) {
				$this->{$key}=$value;
			}
		}
	}
	public function set_path_detail($array)
	{
			
		if(!empty($array)){
			foreach ($array as $key => $value) {
				$this->{$key}=$value;
			}
		}
	

	}
	public function findExtension ($filename)
	{
	   $filename = strtolower($filename) ;
	   $exts = explode(".", $filename) ;
	   $n = count($exts)-1;
	   $exts = $exts[$n];
	   return $exts;
	}
	/*Make waveform and etc....*/

	public function convertToWavAndGenerateWaveData()
	{

		$extension = $this->findExtension($this->file_name);

		if($extension != "wav")
		{
			$this->copy_original_mp3();
			$mp3_encode_result = self::convertMP3ToWav();
                        //var_dump($mp3_encode_result);
		}
		$result = $this->generate_wavedata($this->json_file_name);
                //var_dump($result);
		if($extension != "wav")
		{
			$this->deleteTemporaryFiles();
		}
	}

	public function copy_original_mp3()
	{
		
		if($this->temp_path!='' && !file_exists($this->temp_path))
		{
			mkdir(trim($this->temp_path,"/"),0777);
		}
		$this->tmp_name = $this->temp_path.$this->json_file_name;
		$result_copy = copy($this->file_name,  $this->tmp_name . "_o.mp3");
                //var_dump ($result_copy);
	}

	public function deleteTemporaryFiles()
	{
            $path = $this->tmp_name. "_o.mp3";
            if(file_exists($path)){
                if (unlink( $this->tmp_name. "_o.mp3")) $result = true; 
            } 
	
            $new_file= $this->temp_path.$this->json_file_name.'.wav';
            if(file_exists($new_file)){
                if (unlink( $new_file)) $result = true; 
            } 
            
            $new_file= $this->temp_path.$this->json_file_name.'.mp3';
            if(file_exists($new_file)){
                if (unlink( $new_file)) $result = true; 
            } 
            
//		if (unlink( $this->tmp_name. ".mp3")) $this->delete_temp = true;
//		if (unlink( $this->tmp_name.".wav")) $this->delete_temp = true;

	}
	public function generate_wavedata()
	{
	
		if($this->wave_path!='' && !file_exists($this->wave_path))
		{
			mkdir($this->wave_path,0777);
		}
		/*$json_filename = asset_upload_path().'wavejson/'.$final_new_name.".json";*/
		$json_filename = $this->wave_path.$this->json_file_name.".json";
                $command = "php ".$this->wave_path."php-waveform-json.php ".$this->tmp_name.".wav > $json_filename";
		//var_dump ($command);
                //$waveform_data = exec("waveformjson ".$this->tmp_name.".wav $json_filename");
                $waveform_data = exec($command);
                
                
		return $waveform_data;
		
	}
    /**
     * convert mp3 to wav using lame decoder
     * First, resample the original mp3 using as mono (-m m), 16 bit (-b 16), and 8 KHz (--resample 8)
     * Secondly, convert that resampled mp3 into a wav
     * We don't necessarily need high quality audio to produce a waveform, doing this process reduces the WAV
     * to it's simplest form and makes processing significantly faster
     */
	public function convertMP3ToWav()
	{
		$file_name= $this->temp_path.$this->json_file_name;
		//return exec(self::$LAME_PATH . " {$filename}_o.mp3 -m m -S -f -b 16 --resample 8 {$filename}.mp3 && " . self::$LAME_PATH . " -S --decode {$filename}.mp3 {$filename}.wav");
                //andy
                $command = "lame ".$file_name."_o.mp3 -m m -S -f -b 16 --resample 8 $file_name.mp3 && lame -S --decode $file_name.mp3 $file_name.wav";
                $result = exec($command);
                //var_dump ($command);
                
                return $result;
	}

}

?>