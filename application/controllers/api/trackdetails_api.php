<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH.'/libraries/REST_Controller.php';

class trackdetails_api extends REST_Controller
{
    function likes_count_get($trackId)
    {
        $this->load->model('track_detail');

        $likes = $this->track_detail->likes_count($trackId);

        $result = [
            'likes' => $likes
        ];

        echo json_encode($result);
    }
}

?>