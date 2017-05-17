<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class trackedit_api extends REST_Controller
{
    public function edit_track_info_post()
    {
        $this->load->library('TrackDataService');

        $result = null;
        $trackId = $this->post('track_id');
        $trackData = getvalfromtbl('id', 'tracks', 'id = ' . $trackId, 'single');

        if (empty($trackData)) {
            $result = [
                'errors' => 'This track doesn\'t exists in database.',
            ];

            $this->response($result, 200);
        }

        $typicalValidation = 'trim|required|xss_clean';
        $typicalNonreqValidation = 'trim|xss_clean';
        $this->form_validation->set_rules('album_id', 'Album ID', $typicalNonreqValidation . '|integer');
        $this->form_validation->set_rules('title', 'Title', $typicalValidation);
        $this->form_validation->set_rules('desc', 'Description', $typicalValidation);
        $this->form_validation->set_rules('genre_id', 'Genre ID', $typicalValidation . '|integer');
        $this->form_validation->set_rules('is_public', 'Public', $typicalValidation . '|integer');
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
            $date = json_decode($this->post('release_date'));
            if (!empty($date)) {
                $date = $date->date;
            }

            $usageType = null;

            $saleAvailable = 'n';
            $saleAlbumPrice = $this->post('album');
            $saleSinglePrice = $this->post('single');
            if (!empty($saleAlbumPrice) || !empty($saleSinglePrice)) {
                $saleAvailable = 'y';
                $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 's'", "single") . ",";
            }

            $licensingKeys = [
                'advertising',
                'corporate',
                'documentaryFilm',
                'film',
                'software',
                'internetVideo',
                'liveEvent',
                'musicHold',
                'musicProd1k',
                'musicProd10k',
                'musicProd50k',
                'musicProd51k',
                'website',
            ];

            $licenseAvailable = 'n';
            foreach ($licensingKeys as $key) {
                if (!empty($this->post($key))) {
                    $licenseAvailable = 'y';
                    $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'l'", "single") . ",";
                    break;
                }
            }

            $nonprofitAvailable = 'n';
            if (!empty($this->post('nonProfit'))) {
                $nonprofitAvailable = 'y';
                $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'np'", "single") . ",";
            }

            if ($usageType != null) {
                $usageType = rtrim($usageType, ",");
            }

            $this->trackdataservice->updateTrack(
                $this->post('album_id'),
                $userData->id,
                $this->post('title'),
                $this->post('desc'),
                $date->month, // month
                $date->day, // day
                $date->year, // year
                $this->post('genre_id'),
                $this->post('is_public') ? 'y' : 'n',
                $usageType,
                $this->post('type_artist')[0],
                $saleAvailable,
                $licenseAvailable,
                $nonprofitAvailable
            );
        }

        $secondGenres = $this->post('second_genre_id');
        if (!empty($secondGenres)) {
            $genreIds = explode(',', $secondGenres);
            $this->trackdataservice->removeSecondaryGenres($trackId);
            $this->trackdataservice->addSecondaryGenres($userData->id, $trackId, $genreIds);
        }

        $moods = $this->post('pick_moods');
        if (!empty($moods)) {
            $moodIds = explode(',', $moods);
            $this->trackdataservice->removeMoods($trackId);
            $this->trackdataservice->addMoods($userData->id, $trackId, $moodIds);
        }

        $this->trackdataservice->removeLicenses($trackId);
        $this->trackdataservice->createLicensesFromPost($this->post(), $trackId);

        $result = $this->post();

        $this->response($result, 200);
    }
}