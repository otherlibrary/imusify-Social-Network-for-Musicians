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
     * @param int         $track_type
     * @param string|null $track_musician_type
     * @param string|null $waveform
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
        $track_type,
        $track_musician_type = 'm',
        $waveform = null
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
            'createdDate' => date('Y-m-d H:i:s'),
            'track_type' => $track_type,
            'track_musician_type' => $track_musician_type,

            'plays' => 0,
            'likes' => 0,
            'shares' => 0,
            'comments' => 0,
            'price' => 0,
            'is_sellable' => 'n',
            'license' => 'n',
            'track_nonprofit_avail' => 'n',
            'waveRunningDate' => date('Y-m-d H:i:s'),
            'waveCompletedDate' => date('Y-m-d H:i:s'),
        ];

        if (!empty($waveform)) {
            $trackData['waveform'] = '[' . $waveform . ']';
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
     * @param $trackId
     * @return mixed
     */
    public function removeSecondaryGenres($trackId)
    {
        $this->ci->db->delete('track_genre', ['trackId' => $trackId]);

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
     * @param $trackId
     * @return mixed
     */
    public function removeMoods($trackId)
    {
        $this->ci->db->delete('track_moods', ['trackId' => $trackId]);

        return $this->ci->db->affected_rows();
    }

    /**
     * @param int   $trackId
     * @param array $postData
     */
    public function createLicensesFromPost($trackId, $postData)
    {
        $trackData = [];
        $usageType = '';
        $query = $this->ci->db->query('SELECT * FROM track_licence_types WHERE status = \'y\'');
        foreach ($query->result_array() as $lic) {
            if (empty($postData['lic_id_' . $lic['id']])) {
                continue;
            }
            if ($postData['lic_id_' . $lic['id']] == 'null') {
                continue;
            }
            $insertData = [
                'trackId' => $trackId,
                'licenceId' => $lic['id'],
                'licencePrice' => $postData['lic_id_' . $lic['id']],
                'createdDate' => date('Y-m-d H:i:s'),
            ];
            $this->ci->db->insert('track_licence_price_details', $insertData);

            switch ($lic['lic_type']) {
                case 's':
                    if (empty($trackData['is_sellable'])) {
                        $trackData['is_sellable'] = 'y';
                        $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 's'", "single") . ",";
                    }
                    break;
                case 'l':
                case 'el':
                    if (empty($trackData['license'])) {
                        $trackData['license'] = 'y';
                        $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'l'", "single") . ",";
                    }
                    break;
                case 'np':
                    if (empty($trackData['track_nonprofit_avail'])) {
                        $trackData['track_nonprofit_avail'] = 'y';
                        $usageType .= getvalfromtbl("id", "buy_usage_types", "type = 'np'", "single") . ",";
                    }
                    break;
            }
        }
        if ($usageType != null) {
            $usageType = rtrim($usageType, ",");
            $trackData['usage_type'] = $usageType;
        }
        if (!empty($trackData)) {
            $this->ci->db->where('id', $trackId);
            $this->ci->db->update('tracks', $trackData);
        }
    }

    /**
     * @param $trackId
     * @return mixed
     */
    public function removeLicenses($trackId)
    {
        $this->ci->db->delete('track_licence_price_details', ['trackId' => $trackId]);

        return $this->ci->db->affected_rows();
    }

    /**
     * @param int         $trackId
     * @param int         $albumId
     * @param int         $userId
     * @param string      $title
     * @param string      $description
     * @param int         $releaseMonth
     * @param int         $releaseDay
     * @param int         $releaseYear
     * @param int         $genreId
     * @param string      $isPublic
     * @param string      $usage_type
     * @param string      $track_musician_type
     * @param string|null $sale_available
     * @param string|null $licence_available
     * @param string|null $nonprofit_available
     */
    public function updateTrack(
        $trackId,
        $albumId,
        $userId,
        $title,
        $description,
        $releaseMonth,
        $releaseDay,
        $releaseYear,
        $genreId,
        $isPublic = 'n',
        $usage_type,
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
            'description' => $description,
            'release_mm' => $releaseMonth,
            'release_dd' => $releaseDay,
            'release_yy' => $releaseYear,
            'genreId' => $genreId,
            'isPublic' => $isPublic,
            'usage_type' => $usage_type,
            'track_musician_type' => $track_musician_type,
        ];

        if ($sale_available != null) {
            $trackData['is_sellable'] = $sale_available;
        }

        if ($licence_available != null) {
            $trackData['license'] = $licence_available;
        }

        if ($nonprofit_available != null) {
            $trackData['track_nonprofit_avail'] = $nonprofit_available;
        }

        $this->ci->db->where('id', $trackId);
        $this->ci->db->update('tracks', $trackData);
    }

    /**
     * Deletes the specified track and returns the result array
     * @param int $trackId
     * @param int $userId
     * @return array|null
     */
    public function deleteTrack($trackId, $userId)
    {
        $trackInfo = getvalfromtbl("*", "tracks", "id='" . $trackId . "' AND userId = '" . $userId . "'");
        if (!empty($trackInfo)) {
            $this->ci->db->delete('tracks', ['id' => $trackId]);
            $this->ci->db->delete('tracks_downloads', ['track_id' => $trackId]);
            $this->ci->db->delete('track_soundlike', ['trackId' => $trackId]);
            $this->ci->db->delete('track_moods', ['trackId' => $trackId]);
            $this->ci->db->delete('track_licence_price_details', ['trackId' => $trackId]);
            $this->ci->db->delete('track_instruments', ['trackId' => $trackId]);
            $this->ci->db->delete('track_genre', ['trackId' => $trackId]);
            $this->ci->db->delete('track_comments', ['trackId' => $trackId]);
            $this->ci->db->delete('playlist_detail', ['trackId' => $trackId]);
            $this->ci->db->delete('playinglog', ['trackId' => $trackId]);
            $this->ci->db->delete('likelog', ['trackId' => $trackId]);

            $file_path = asset_path() . "upload/media/" . $userId . "/" . $trackInfo["trackName"];
            if (file_exists($file_path)) {
                @unlink($file_path);
            }

            $trackPhoto = getvalfromtbl("*", "photos", "detailId='" . $trackdet . "' AND type = 't'");
            if (!empty($trackPhoto)) {
                $filename = asset_path() . 'upload/' . $trackPhoto['dir'] . $trackPhoto['name'];
                if (file_exists($filename)) {
                    @unlink($filename);
                }
                $this->ci->db->delete('photos', ['detailId' => $trackId, 'dir' => 'track/']);
            }

            $this->load->model('space_model');
            $userCurrentSpace = $this->space_model->getUserCommonSpace($userId);
            $this->space_model->updateUserSpace($userId, $userCurrentSpace['used_space'] - $trackInfo['filesize']);

            $result = [
                'status' => 'success',
                'msg' => 'Specified track successfully deleted.',
            ];
        } else {
            $result = [
                'status' => 'fail',
                'msg' => 'Can\'t find specified track.',
            ];
        }

        return $result;
    }
}