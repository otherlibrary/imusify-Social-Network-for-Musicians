<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class trackdetails_api extends REST_Controller
{
    /**
     * Returns JSON with count of likes for specified track
     * @param int $trackId
     * [GET /api/track-details/likes-count/{track id}]
     */
    function likes_count_get($trackId)
    {
        $this->load->model('track_detail');

        $this->track_detail->tracks_by_tag($trackId, 'instrumental');

        $likes = $this->track_detail->likes_count($trackId);

        $result = [
            'likes' => $likes,
        ];

        echo json_encode($result);
    }

    /**
     * Returns JSON with count of shares for specified track
     * @param int $trackId
     * [GET /api/track-details/shares-count/{track id}]
     */
    function shares_count_get($trackId)
    {
        $this->load->model('track_detail');

        $shares = $this->track_detail->shares_count($trackId);

        $result = [
            'shares' => $shares,
        ];

        echo json_encode($result);
    }

    /**
     * Returns JSON with count of plays for specified track
     * @param int $trackId
     * [GET /api/track-details/plays-count/{track id}]
     */
    function plays_count_get($trackId)
    {
        $this->load->model('track_detail');

        $plays = $this->track_detail->plays_count($trackId);

        $result = [
            'plays' => $plays,
        ];

        echo json_encode($result);
    }

    /**
     * Returns JSON with count of comments for specified track
     * @param int $trackId
     * [GET /api/track-details/comments-count/{track id}]
     */
    function comments_count_get($trackId)
    {
        $this->load->model('track_detail');

        $comments = $this->track_detail->comments_count($trackId);

        $result = [
            'comments' => $comments,
        ];

        echo json_encode($result);
    }

    /**
     * Returns JSON with count of comments for specified track
     * @param int $trackId
     * [GET /api/track-details/playlists-count/{track id}]
     */
    function playlists_count_get($trackId)
    {
        $this->load->model('track_detail');

        $playlists = $this->track_detail->playlists_count($trackId);

        $result = [
            'playlists' => $playlists,
        ];

        echo json_encode($result);
    }

    /**
     * Returns JSON with count of downloads for specified track
     * @param int $trackId
     * [GET /api/track-details/downloads-count/{track id}]
     */
    function downloads_count_get($trackId)
    {
        $this->load->model('track_detail');

        $downloads = $this->track_detail->downloads_count($trackId);

        $result = [
            'downloads' => $downloads,
        ];

        echo json_encode($result);
    }

    /**
     * Returns the JSON with common details for specified track
     * @param int $trackId
     * [GET /api/track-details/common-details/{track id}
     */
    function common_details_get($trackId)
    {
        $this->load->model('track_detail');

        $data = $this->track_detail->common_details($trackId);

        $result = array_map(function ($value) {
            return (int)$value;
        }, $data);

        echo json_encode($result);
    }

    /**
     * Returns the JSON with ids of tracks related to specific tag
     * @param int $id
     * @param string $type
     * [GET /api/track-details/show-tracks-by-tag/{tag id}/{tag type (genre, instrumental, mood)}]
     */
    function show_tracks_by_tag_get($id, $type)
    {
        $this->load->model('track_detail');

        $tracks = $this->track_detail->tracks_by_tag($id, $type);

        $result = [
            'tracks' => $tracks,
        ];

        echo json_encode($result);
    }
}