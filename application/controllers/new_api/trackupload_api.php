<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class trackupload_api extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Upload file from POST-request to server and returns the JSON with uploading result
     * [POST /api/track-upload/upload-track-file]
     */
    function upload_track_post()
    {
        $this->load->library('UploadService');

        $result = $this->uploadservice->uploadTrackFile();

        echo json_encode($result);
    }
}