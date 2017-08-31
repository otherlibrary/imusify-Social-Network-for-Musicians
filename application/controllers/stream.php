<?php if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class stream extends MY_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('home_modal');
        $this->load->library('user_agent');
        $this->load->library("VideoStream");
    }

    function index($trackId)
    {
        $this->load->helper('file');
        $trackId = str_replace('.mp3', '', $trackId);

        if ( ! $this->agent->is_referral()) {
            exit;
        }

        if ($trackId > 0) {
            $data = getvalfromtbl("trackName,userId", "tracks", "id = '" . $trackId . "'", "multiple");
            $filePath = asset_upload_path() . "media/" . $data["userId"] . "/" . $data["trackName"];
            $this->videostream->start($filePath);
        }
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */