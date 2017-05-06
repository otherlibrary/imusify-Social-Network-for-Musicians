<?php

class TrackDataService
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * @param int    $albumId
     * @param int    $userId
     * @param string $title
     * @param int    $timelength
     * @param int    $bitrate
     * @param string $perLink
     * @param string $trackName
     * @param string $description
     * @param int    $releaseMonth
     * @param int    $releaseDay
     * @param int    $releaseYear
     * @param int    $genreId
     * @param string $isPublic
     * @param int    $filesize
     * @param int    $trackuploadType
     * @param double $trackuploadbpm
     * @param int    $track_buyable_types
     * @param int    $track_buyable_current_type
     * @param string $usage_type
     * @param null   $music_vocals_y
     * @param null   $music_vocals_gender
     * @param null   $sale_available
     * @param null   $licence_available
     * @param null   $nonprofit_available
     * @return int
     */
    public function createTrack(
        $albumId,
        $userId,
        $title,
        $timelength,
        $bitrate,
        $perLink,
        $trackName,
        $description,
        $releaseMonth,
        $releaseDay,
        $releaseYear,
        $genreId,
        $isPublic,
        $filesize,
        $trackuploadType,
        $trackuploadbpm,
        $track_buyable_types,
        $track_buyable_current_type,
        $usage_type,
        $music_vocals_y = null,
        $music_vocals_gender = null,
        $sale_available = null,
        $licence_available = null,
        $nonprofit_available = null
    )
    {
        $trackData = [
            'albumId' => $albumId,
            'userId' => $userId,
            'title' => $title,
            'timelength' => $timelength,
            'bitrate' => $bitrate,
            'perLink' => $perLink,
            'trackName' => $trackName,
            'description' => $description,
            'release_mm' => $releaseMonth,
            'release_dd' => $releaseDay,
            'release_yy' => $releaseYear,
            'genreId' => $genreId,
            'isPublic' => $isPublic,
            'filesize' => $filesize,
            'trackuploadType' => $trackuploadType,
            'trackuploadbpm' => $trackuploadbpm,
            'track_buyable_types' => $track_buyable_types,
            'track_buyable_current_type' => $track_buyable_current_type,
            'usage_type' => $usage_type,
            'createdDate' => date('Y-m-d H:i:s'),

            'plays' => 0,
            'likes' => 0,
            'shares' => 0,
            'comments' => 0,
            'price' => 0,
            'waveRunningDate' => date('Y-m-d H:i:s'),
            'waveCompletedDate' => date('Y-m-d H:i:s'),
        ];
        if ($music_vocals_y != null) {
            $data["track_type"] = $music_vocals_y;
        }

        if ($music_vocals_gender != null) {
            $data["track_musician_type"] = $music_vocals_gender;
        }

        if ($sale_available != null) {
            $data["is_sellable"] = $sale_available;
        }

        if ($licence_available != null) {
            $data["license"] = $licence_available;
        }

        if ($nonprofit_available != null) {
            $data["track_nonprofit_avail"] = $nonprofit_available;
        }

        $this->ci->db->insert('tracks', $trackData);

        return $this->ci->db->insert_id();
    }

    /**
     * @param int|null $trackId
     * @param int|null $trackName
     * @return string|null
     */
    public function getTrackPath($trackId = null, $trackName = null)
    {
        if (!empty($trackId)) {
            $track = getvalfromtbl('*', 'tracks', 'id = ' . $trackId, 'single');
            if (!empty($track)) {
                return asset_path() . 'upload/media/' . $track['userId'] . '/' . $track['trackName'];
            }
        } else if (!empty($trackName)) {
            $track = getvalfromtbl('*', 'tracks', 'trackName = \'' . $trackName . '\'', 'single');
            if (!empty($track)) {
                return asset_path() . 'upload/media/' . $track['userId'] . '/' . $track['trackName'];
            }
        }

        return null;
    }

    /**
     * @param string|null $tracktype
     * @return string
     */
    public function fetchTrackTypes($tracktype = null)
    {
        $this->ci->db->select('id');
        $this->ci->db->from('tracktypes');
        $where = "(name='" . $tracktype . "' or name='mp3')";
        $this->ci->db->limit(1);
        $this->ci->db->where($where);

        $query = $this->ci->db->get();
        $response = "";
        if ($query->num_rows() > 0) {
            foreach ($query->result_array() as $row) {
                $response .= $row["id"] . ",";
            }
            $response = rtrim($response, ",");

        }

        return $response;
    }
}