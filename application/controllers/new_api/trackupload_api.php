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
    public function upload_track_post()
    {
        $this->load->library('UploadService');

        $result = $this->uploadservice->uploadTrackFile();

        $this->response($result);
    }

    /**
     * Insert track info into database
     */
    public function upload_track_data_post()
    {
        $this->load->library('TrackDataService');
        $this->load->library('getid3/getID3');
        $this->load->library('form_validation');
        $this->load->model('commonfn');

        $typicalValidation = 'trim|required|xss_clean';
        $this->form_validation->set_rules('album_id', 'Album ID', $typicalValidation . '|integer', ['required' => 'You have not provided %s.']);
        $this->form_validation->set_rules('title', 'Title', $typicalValidation);
        $this->form_validation->set_rules('filename', 'Filename', $typicalValidation);
        $this->form_validation->set_rules('desc', 'Description', $typicalValidation);
        $this->form_validation->set_rules('genre_id', 'Genre ID', $typicalValidation . '|integer');
        $this->form_validation->set_rules('is_public', 'Public', $typicalValidation . '|integer');
        $this->form_validation->set_rules('track_upload_type', 'Upload Type', $typicalValidation);
        $this->form_validation->set_rules('track_upload_bpm', 'Track BPM', $typicalValidation);
        $this->form_validation->set_rules('track_type', 'Track Type', $typicalValidation);
        $this->form_validation->set_rules('music_vocals_y', 'Music Vocals', $typicalValidation);
        $this->form_validation->set_rules('music_vocals_gender', 'Vocals Gender', $typicalValidation);
        $this->form_validation->set_rules('sale_available', 'Sale Available', $typicalValidation);
        $this->form_validation->set_rules('license_available', 'License Available', $typicalValidation);
        $this->form_validation->set_rules('nonprofit_available', 'Nonprofit Available', $typicalValidation);
        $this->form_validation->set_rules('release_date', 'Release Date', $typicalValidation);
        if ($this->form_validation->run() == false) {
            $response = [
                'errors' => $this->validation_errors(),
            ];
            $this->response($response, 200);
        }


        $userData = $this->session->userdata('user');

        if ($userData->id) {
            $getID3 = new getID3;
            $trackPath = $this->trackdataservice->getTrackPath(null, $this->post('filename'));
            $trackInfo = $getID3->analyze($trackPath);
            $permaLink = $this->commonfn->get_permalink($this->post('title'), "tracks", "perLink", "id");
            $buyableType = getvalfromtbl("id", "tracktypes", "name LIKE '%" . $this->post('track_type') . "%'", "single");

            $date = explode('-', $this->post('release_date'));

            $usageType = null;
            if (!empty($this->post('sale_available'))) {
                $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 's'", "single") . ",";
            }

            if (!empty($this->post('license_available'))) {
                $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'l'", "single") . ",";
            }

            if (!empty($this->post('nonprofit_available'))) {
                $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'np'", "single") . ",";
            }

            if ($usageType != null) {
                $usageType = rtrim($usageType, ",");
            }

            $trackId = $this->trackdataservice->createTrack(
                $this->post('album_id'),
                $userData->id,
                $this->post('title'),
                $trackInfo['playtime_string'],
                $trackInfo['bitrate'],
                $permaLink,
                $this->post('filename'),
                $this->post('desc'),
                $date[1], // month
                $date[0], // day
                $date[2], // year
                $this->post('genre_id'),
                $this->post('is_public'),
                filesize($trackPath),
                $this->post('track_upload_type'),
                $this->post('track_upload_bpm'),
                $this->trackdataservice->fetchTrackTypes($this->post('track_type')),
                $buyableType,
                $usageType,
                $this->post('music_vocals_y'),
                $this->post('music_vocals_gender'),
                $this->post('sale_available'),
                $this->post('license_available'),
                $this->post('nonprofit_available')
            );

            $result = [
                'track_id' => $trackId,
            ];

            $this->response($result, 200);
        }
    }
}