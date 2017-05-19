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
     * [POST /api/track-upload/upload-track-info]
     */
    public function upload_track_data_post()
    {
        $this->load->library('TrackDataService');
        $this->load->library('getid3/getID3');
        $this->load->library('form_validation');
        $this->load->model('commonfn');

        $typicalValidation = 'trim|required|xss_clean';
        $typicalNonreqValidation = 'trim|xss_clean';
        $this->form_validation->set_rules('album_id', 'Album ID', $typicalNonreqValidation . '|integer');
        $this->form_validation->set_rules('title', 'Title', $typicalValidation);
        $this->form_validation->set_rules('filename', 'Filename', $typicalValidation);
        $this->form_validation->set_rules('desc', 'Description', $typicalValidation);
        $this->form_validation->set_rules('genre_id', 'Genre ID', $typicalValidation . '|integer');
        $this->form_validation->set_rules('is_public', 'Public', $typicalValidation . '|integer');
        $this->form_validation->set_rules('track_type', 'Upload Type', $typicalValidation);
        $this->form_validation->set_rules('type_artist', 'Type Artist', $typicalNonreqValidation);
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
            $trackPath = $this->trackdataservice->getTrackPath(null, $this->post('filename'), $userData->id);
            $trackInfo = $getID3->analyze($trackPath);
            $permaLink = $this->commonfn->get_permalink($this->post('title'), "tracks", "perLink", "id");

            $date = json_decode($this->post('release_date'));
            if (!empty($date)) {
                $date = $date->date;
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
                $date->month, // month
                $date->day, // day
                $date->year, // year
                $this->post('genre_id'),
                $this->post('is_public') ? 'y' : 'n',
                filesize($trackPath),
                $this->post('track_type'),
                $trackInfo['tags']['id3v2']['bpm'][0],
                $this->trackdataservice->fetchTrackTypes($trackInfo['fileformat']),
                $this->trackdataservice->fetchTrackType($trackInfo['fileformat']),
                $this->trackdataservice->fetchTrackType($trackInfo['fileformat']),
                $this->post('type_artist')[0],
                $this->post('waveform')
            );

            $secondGenres = $this->post('second_genre_id');
            if (!empty($secondGenres)) {
                $genreIds = explode(',', $secondGenres);
                $this->trackdataservice->addSecondaryGenres($userData->id, $trackId, $genreIds);
            }

            $moods = $this->post('pick_moods');
            if (!empty($moods)) {
                $moodIds = explode(',', $moods);
                $this->trackdataservice->addMoods($userData->id, $trackId, $moodIds);
            }

            $this->trackdataservice->createLicensesFromPost($trackId, $this->post());

            $result = [
                'track_id' => $trackId,
            ];

            $this->response($result, 200);
        }
    }

    /**
     * Upload image for specified track from img / base64 data
     * [POST /api/track-upload/upload-track-img]
     */
    public function upload_track_img_post()
    {
        $this->load->library('UploadService');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('track_id', 'track_id', 'trim|required|xss_clean|integer');
        $this->form_validation->set_rules('type', 'type', 'trim|required|xss_clean');

        if ($this->form_validation->run() == false) {
            $response = [
                'errors' => $this->validation_errors(),
            ];
            $this->response($response, 200);
        }

        $userData = $this->session->userdata('user');
        if (!empty($userData->id)) {
            $result = $this->uploadservice->uploadTrackImage(
                $this->post('track_id'),
                $this->post('type') == 'base64' ? true : false,
                $this->post()
            );

            $this->response($result, 200);
        }
    }

    /**
     * Delete specified track and all related info
     * [POST /api/track-delete]
     */
    public function delete_track_post()
    {
        $this->load->library('TrackDataService');

        $this->form_validation->set_rules('track_id', 'track_id', 'trim|required|xss_clean|integer');
        if ($this->form_validation->run() == false) {
            $response = [
                'errors' => $this->validation_errors(),
            ];
            $this->response($response, 200);
        }

        $result = null;
        $userData = $this->session->userdata('user');
        if (!empty($userData->id)) {
            $result = $this->trackdataservice->deleteTrack($this->post('track_id'), $userData->id);
        }

        $this->response($result, 200);
    }
}