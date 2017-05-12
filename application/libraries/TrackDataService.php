<?php

class TrackDataService
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * @param int         $albumId
     * @param int         $userId
     * @param string      $title
     * @param int         $timelength
     * @param int         $bitrate
     * @param string      $perLink
     * @param string      $trackName
     * @param string      $description
     * @param int         $releaseMonth
     * @param int         $releaseDay
     * @param int         $releaseYear
     * @param int         $genreId
     * @param string      $isPublic
     * @param int         $filesize
     * @param int         $trackuploadType
     * @param double      $trackuploadbpm
     * @param int         $track_buyable_types
     * @param int         $track_buyable_current_type
     * @param string      $usage_type
     * @param int         $track_type
     * @param string|null $track_musician_type
     * @param null        $sale_available
     * @param null        $licence_available
     * @param null        $nonprofit_available
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
        $isPublic = 'n',
        $filesize,
        $trackuploadType,
        $trackuploadbpm,
        $track_buyable_types,
        $track_buyable_current_type,
        $usage_type,
        $track_type,
        $track_musician_type = 'm',
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
            'track_type' => $track_type,
            'track_musician_type' => $track_musician_type,

            'plays' => 0,
            'likes' => 0,
            'shares' => 0,
            'comments' => 0,
            'price' => 0,
            'waveRunningDate' => date('Y-m-d H:i:s'),
            'waveCompletedDate' => date('Y-m-d H:i:s'),
        ];

        $data['is_sellable'] = 'n';
        if ($sale_available != null) {
            $data['is_sellable'] = $sale_available;
        }

        $data['license'] = 'n';
        if ($licence_available != null) {
            $data['license'] = $licence_available;
        }

        $data['track_nonprofit_avail'] = 'n';
        if ($nonprofit_available != null) {
            $data['track_nonprofit_avail'] = $nonprofit_available;
        }

        $this->ci->db->insert('tracks', $trackData);

        return $this->ci->db->insert_id();
    }

    /**
     * @param int|null $trackId
     * @param int|null $trackName
     * @param int|null $userId
     * @return string|null
     */
    public function getTrackPath($trackId = null, $trackName = null, $userId = null)
    {
        if (!empty($trackId)) {
            $track = getvalfromtbl('*', 'tracks', 'id = ' . $trackId, 'single');
            if (!empty($track)) {
                return asset_path() . 'upload/media/' . $track['userId'] . '/' . $track['trackName'];
            }
        } else if (!empty($trackName) && !empty($userId)) {
            return asset_path() . 'upload/media/' . $userId . '/' . $trackName;
        } else if (!empty($trackName)) {
            $track = getvalfromtbl('*', 'tracks', 'trackName = \'' . $trackName . '\'', 'single');
            if (!empty($track)) {
                return asset_path() . 'upload/media/' . $track['userId'] . '/' . $track['trackName'];
            }
        }

        return null;
    }

    /**
     * @param string $tracktype
     * @return string
     */
    public function fetchTrackTypes($tracktype)
    {
        $this->ci->db->select('id');
        $this->ci->db->from('tracktypes');
        $where = "(name='" . $tracktype . "' or name='mp3')";
        $this->ci->db->limit(1);
        $this->ci->db->where($where);
        $query = $this->ci->db->get();

        $response = null;
        if ($query->num_rows() > 0) {
            $response = array_to_str_list($query->result_array()[0], ',');
        }

        return $response;
    }

    /**
     * @param string $tracktype
     * @return string
     */
    public function fetchTrackType($tracktype)
    {
        $id = getvalfromtbl('id', 'tracktypes', "name='$tracktype' or name='mp3'", 'single');

        return $id;
    }

    /**
     * @param int   $userId
     * @param int   $trackId
     * @param array $genres
     * @return int|null
     */
    public function addSecondaryGenres($userId, $trackId, $genres)
    {
        if (!count($genres)) {
            return null;
        }

        $insertData = [];
        foreach ($genres as $genre) {
            $insertData[] = [
                'userId' => $userId,
                'trackId' => $trackId,
                'genreId' => $genre,
                'createdDate' => date('Y-m-d H:i:s'),
            ];
        }

        $this->ci->db->insert_batch('track_genre', $insertData);

        return $this->ci->db->affected_rows();
    }

    /**
     * @param int   $userId
     * @param int   $trackId
     * @param array $moods
     * @return int|null
     */
    public function addMoods($userId, $trackId, $moods)
    {
        if (!count($moods)) {
            return null;
        }

        $insertData = [];
        foreach ($moods as $mood) {
            $insertData[] = [
                'userId' => $userId,
                'trackId' => $trackId,
                'moodId' => $mood,
                'createdDate' => date('Y-m-d H:i:s'),
            ];
        }

        $this->ci->db->insert_batch('track_moods', $insertData);

        return $this->ci->db->affected_rows();
    }

    /**
     * @param array $query
     * @param int   $trackId
     */
    public function createLicensesFromPost($query, $trackId)
    {
        $licensingKeys = [
            'single' => 1,
            'album' => 2,
            'advertising' => 14,
            'corporate' => 10,
            'documentaryFilm' => 9,
            'film' => 8,
            'software' => 16,
            'internetVideo' => 11,
            'liveEvent' => 17,
            'musicHold' => 15,
            'musicProd1k' => 4,
            'musicProd10k' => 5,
            'musicProd50k' => 6,
            'musicProd51k' => 7,
            'website' => 12,
            // exclusive
            'advertisingE' => 26,
            'corporateE' => 22,
            'documentaryFilmE' => 21,
            'filmE' => 20,
            'softwareE' => 28,
            'internetVideoE' => 23,
            'liveEventE' => 29,
            'musicHoldE' => 27,
            'musicProd1kE' => 31,
            'musicProd10kE' => 31,
            'musicProd50kE' => 31,
            'musicProd51kE' => 31,
            'websiteE' => 24,
        ];

        foreach ($licensingKeys as $key => $value) {
            if (!empty($query[$key])) {
                $insertData = [
                    'trackId' => $trackId,
                    'licenceId' => $value,
                    'licencePrice' => $query[$key],
                    'createdDate' => date('Y-m-d H:i:s'),
                ];
                $this->ci->db->insert('track_licence_price_details', $insertData);
            }
        }
    }
}