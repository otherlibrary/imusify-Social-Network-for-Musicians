<?php

class track_detail extends CI_Model
{
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        $this->load->model('commonfn');
        $this->sess_uid = $this->session->userdata('user')->id;
    }

    //Function to get user details...
    function get_song_details($trackId = null, $cond = null, $flag = null, $returnflag = false)
    {
        $this->load->model('user_profile');
        //artist_firstname  artist_lastname no_of_followers no_of_songs no_of_albums track_genre
        $query = $this->db->query("select tt.*,u.firstname as artist_firstname,u.lastname as artist_lastname,g.genre,(SELECT name from location where location_type = '0' AND location_id = u.countryId) as country,(SELECT name from location where location_type = '2' AND location_id = u.cityId) as city FROM tracks as tt,users as u,genre as g WHERE u.id = tt.userId AND  g.id = tt.genreId AND tt.id = '" . $trackId . "' LIMIT 1");
        /*print_query();*/
        $row = $query->row_array();
//                	echo $this->db->last_query();
//			var_dump($row);exit;
        /*print_r($row);*/
        //andy use track cover because the user can upload artwork for track cover
        //$row["cover_image"] = $this->commonfn->get_photo('tc',$row["id"]);
        //change to use track thumbnail small version
        $row["cover_image"] = $this->commonfn->get_photo('t', $row["id"]);
        //$row["cover_image"] = $this->commonfn->get_photo('t',$row["id"]);//tc track cover t track
        $row["artist_profile_pic"] = $this->commonfn->get_photo('p', $row["userId"], IMG_66, IMG_66);
        $row["no_of_followers"] = $this->user_profile->fetch_followers("fl.toId = '" . $row["userId"] . "'", "", "", "counter");
        $row["no_of_songs"] = $this->user_profile->fetch_uploaded_tracks("tt.userId = '" . $row["userId"] . "'", "", "", "counter");
        $row["no_of_albums"] = $this->user_profile->fetch_albums("a.userId = '" . $row["userId"] . "'", "", "", "counter");

        $row["track_kbps"] = round($row["bitrate"] / 1000);
        $trackId = $row["id"];

        /*check if song is available for perticular sale or album fully buyable*/
        $is_track_sellable = true;
        $albumId = $row["albumId"];
        $flag_temp = false;
        if ($albumId > 0) {  //album fully buyable
            $where = " (id ='" . $albumId . "') ";
            $this->db->select('selleble_type');
            $this->db->from('albums');
            $this->db->where($where);
            $this->db->limit(1);
            $query = $this->db->get();
            $records_count = $query->num_rows();
            if ($records_count > 0) {
                $row_album = $query->row_array();
                if ($row_album["selleble_type"] == "f") {
                    $flag_temp = true;
                    $row["album_full_buyable"] = true;
                    $is_track_sellable = false;
                }
            }
        }

        /*if($row["usage_type"] != "")
        {
            $is_track_sellable = true;
            $this->load->model("icart");
            $array = array('usage_type'=>$row["usage_type"]);
            $row["db_usage_type"] = $this->icart->get_usage_types($array);
        }*/
        $row["is_track_sellable"] = $is_track_sellable;
        $usage_types = $row["usage_type"];
        $db_usage_type_arr = explode(",", $usage_types);
        $commercial_id = getvalfromtbl('id', 'buy_usage_types', 'type="l"', 'single');
        $exclusive_id = getvalfromtbl('id', 'buy_exclusive_types', 'type="e"', 'single');
        $non_exclusive_id = getvalfromtbl('id', 'buy_exclusive_types', 'type="n"', 'single');
        if ($commercial_id > 0 && in_array($commercial_id, $db_usage_type_arr)) {
            $row['trackExclusiveType'] = $non_exclusive_id . "," . $exclusive_id;
        } else {
            $row['trackExclusiveType'] = $non_exclusive_id;
        }
        if ($row["track_buyable_types"] != "") {
            $row["trackFileTypes"] = $this->fetch_track_file_types($row["track_buyable_types"]);
        }

        $this->load->model("order_model");
        $row["order"] = $this->order_model->check_order_exist($row["id"]);
        $row["is_pending_order_type"] = false;
        if (!empty($row["order"]) && $row["order"]["status"] == "p") {
            $row["is_pending_order_type"] = true;
        }

        if ($returnflag == true) {
            return $row;
        }

        /*if($flag_temp == false){
            $row["licence_detail"] = $this->fetch_track_licence_type($trackId);
        }
        /*print_r($row);
        exit;*/

        return $row;
    }


    function fetch_track_file_types($track_buyable_types)
    {

        $track_buyable_types = explode(",", $track_buyable_types);
        $this->db->select('*');
        $this->db->from('tracktypes');
        $this->db->where_in('id', $track_buyable_types);

        if ($limit != null) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();

        /*echo $this->db->last_query();*/
        $records_count = $query->num_rows();
        /*echo $records_count;*/
        if ($records_count > 0) {
            $response = [];
            foreach ($query->result_array() as $row) {
                $response[] = $row;
            }
        }

        return $response;
    }

    function fetch_track_licence_type($trackId)
    {
        $set_price_flag = false;
        $exclusive_price_flag = false;

        $buy_session_current_ar = $this->session->userdata('buy_' . $trackId);
        /*Wave file and selected for download as mp3*/
        /*$track_common_detail["trackFileTypes"]*/
        $trackdata = $this->get_song_details($trackId, null, null, true);
        /*track_buyable_current_type*/
        /*echo "<pre>";
        print_r($buy_session_current_ar);
        echo "</pre>";*/
        if (!empty($buy_session_current_ar)) {
            if (!empty($trackdata)) {
                foreach ($trackdata["trackFileTypes"] as $key => $value) {
                    if ($value["id"] == $buy_session_current_ar["trackfiletype"] && $buy_session_current_ar["trackfiletype"] != $trackdata["track_buyable_current_type"]) {
                        /*echo "<pre>";
                        print_r($value);*/
                        $set_price_flag = true;
                        /*$value["pricing"]*/
                        /*echo " j".$buy_session_current_ar["trackfiletype"];*/
                        /*If user selects mp3*/
                        $cur_user_percent = getvalfromtbl("pricing", "tracktypes", "id='" . $buy_session_current_ar["trackfiletype"] . "'", "single");

                        /*Db % may be 120 of wav file*/
                        $cur_track_percent = getvalfromtbl("pricing", "tracktypes", "id='" . $trackdata["track_buyable_current_type"] . "'", "single");
                    }
                }
            }

            if ($buy_session_current_ar["exclusive_type"] && $buy_session_current_ar["exclusive_type"] > 0) {
                /*echo "z";*/
                $exclusive_percent = getvalfromtbl("pricing", "buy_exclusive_types", "id='" . $buy_session_current_ar["exclusive_type"] . "'", "single");

                /*echo " K ".$exclusive_percent;*/

                if ($exclusive_percent > 0) {
                    $exclusive_price_flag = true;
                }
            }
        }

        /*Wave file and selected for download as mp3 ends*/

        $where = " (ttd.trackId='" . $trackId . "' AND tlt.lic_type = 'l') ";

        if ($cond != null) {
            $where .= " AND " . $cond;
        }

        $this->db->select('ttd.licencePrice,tlt.*');
        $this->db->from('track_licence_price_details as ttd');
        $this->db->join('track_licence_types as tlt', 'tlt.id = ttd.licenceId', 'left');
        $this->db->where($where);

        if ($limit != null) {
            $this->db->limit($limit);
        }

        $query = $this->db->get();
        $records_count = $query->num_rows();

        if ($counter != null) {
            return $records_count;
        }

        if ($records_count > 0) {
            $i = 1;
            if ($start_limit != null) {
                $i = $start_limit + 1;
            }

            foreach ($query->result_array() as $row) {
                $row["row_class"] = 'licence_type_sel_js';
                $row["type"] = '';

                if ($set_price_flag == true) {
                    $new_price = ($row["licencePrice"] * $cur_user_percent) / $cur_track_percent;

                    $row["licencePrice"] = $new_price;
                }
                if ($exclusive_price_flag == true) {
                    $new_price = ($row["licencePrice"] * $exclusive_percent) / 100;
                    $row["licencePrice"] = $new_price;
                }
                $response[] = $row;
            }
        }

        return $response;
    }

    /*Comments songs*/
    function fetch_track_comments($trackId, $cond = null, $limit = null, $orderby = null, $counter = null)
    {
        $output = [];

        if ($trackId <= 0) {
            return "";
        }

        if ($cond != null) {
            $cond = " WHERE tc.status = 'y' AND tc.userId = u.id AND tc.trackId = '" . $trackId . "' AND " . $cond . "";
        } else {
            $cond = " WHERE tc.status = 'y' AND tc.userId = u.id AND tc.trackId = '" . $trackId . "'";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY tc.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY tc.id DESC";
        }

        $query = $this->db->query("SELECT tc.id as commentId,tc.comment,tc.createdDate,u.firstname,u.lastname,u.profileLink,u.id as uid  FROM track_comments as tc,users as u " . $cond . " " . $orderby . " " . $limit . " ");
        //print_query();
        if ($counter != null) {
            return $query->num_rows();
        }

        //echo print_query();
        foreach ($query->result_array() as $row) {
            $row["user_pic"] = $this->commonfn->get_photo('p', $row["uid"], IMG_156, IMG_156);
            $row["user_name"] = $row["firstname"] . " " . $row["lastname"];
            $row["timeago"] = timeago($row["createdDate"]);
            $row["link"] = base_url() . $row["profileLink"];

            $row["seconds"] = $row["commentTime"];
            $row["time"] = $row["commentTime"];
            $row["trackId"] = $row["trackId"];
            //$row["profileLink"] = $row["profileLink"];

            $output[] = $row;
        }

        return $output;
    }
    /*Listened songs ends*/


    /*Likes songs*/
    function fetch_track_likes($trackId, $cond = null, $limit = null, $orderby = null, $counter = null)
    {
        if ($trackId <= 0) {
            return "";
        }

        $output = [];

        if ($cond != null) {
            $cond = " WHERE u.id = ll.userId AND u.status = 'y' AND ll.trackId = '" . $trackId . "' " . $cond . "";
        } else {
            $cond = " WHERE u.id = ll.userId AND u.status = 'y' AND ll.trackId = '" . $trackId . "' ";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY ll.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY ll.id DESC";
        }

        $query = $this->db->query("SELECT u.username,u.id,u.profileLink FROM likelog as ll,users as u " . $cond . " " . $orderby . " " . $limit . " ");
        //print_query();
        if ($counter != null) {
            return $query->num_rows();
        }

        //echo print_query();
        foreach ($query->result_array() as $row) {
            $row["user_pic"] = $this->commonfn->get_photo('p', $row["id"], IMG_156, IMG_156);
            $row["link"] = base_url() . $row["profileLink"];
            $row["user_waveform"] = img_url() . "small-wave-img.png";
            $row["row_id"] = "row" . $row["id"];

            $output[] = $row;
        }

        return $output;
    }
    /*likes songs ends*/

    /*similar songs*/
    function fetch_similar_tracks($trackId, $cond = null, $limit = null, $orderby = null, $counter = null)
    {
        if ($trackId <= 0) {
            return "";
        }

        $output = [];

        $cur_genre_id = getvalfromtbl("genreId,userId", "tracks", "id = '" . $trackId . "'");

        if ($cond != null) //$cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.id != '".$trackId."' AND (tt.genreId = '".$cur_genre_id["genreId"]."' OR tt.userId != '".$cur_genre_id["userId"]."') ".$cond."";
        {
            $cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.id != '" . $trackId . "' AND (tt.genreId = '" . $cur_genre_id["genreId"] . "') " . $cond . "";
        } else //$cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.id != '".$trackId."'  AND (tt.genreId = '".$cur_genre_id["genreId"]."' OR tt.userId != '".$cur_genre_id["userId"]."')";
        {
            $cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.id != '" . $trackId . "'  AND (tt.genreId = '" . $cur_genre_id["genreId"] . "')";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        } else {
            $limit = " LIMIT 10";
        }
        if ($orderby != null) {
            $orderby = "ORDER BY tt.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY tt.id DESC";
        }

        $query = $this->db->query("SELECT tt.id as tid,tt.title,tt.perLink,u.firstname,u.lastname,u.profileLink,u.username FROM tracks as tt,users as u " . $cond . " " . $orderby . " " . $limit . " ");

        if ($query->num_rows() == 0) {
            $cond = " WHERE tt.status = 'y' AND u.id = tt.userId AND tt.id != '" . $trackId . "'  AND (tt.userId != '" . $cur_genre_id["userId"] . "')";
            $query = $this->db->query("SELECT tt.id as tid,tt.title,tt.perLink,u.firstname,u.lastname,u.profileLink,u.username FROM tracks as tt,users as u " . $cond . " " . $orderby . " " . $limit . " ");
        }
        //print_query();exit;
        if ($counter != null) {
            return $query->num_rows();
        }

        //echo print_query();
        foreach ($query->result_array() as $row) {
            $row["track_pic"] = $this->commonfn->get_photo('t', $row["tid"], IMG_41, IMG_41);
            $row["user_name"] = $row["firstname"] . " " . $row["lastname"];
            $row["trackLink"] = base_url() . $row["profileLink"] . "/" . $row["perLink"];
            $row["profileLink"] = base_url() . $row["profileLink"];
            $row["minititle"] = character_limiter($row["title"], 20, $end_char = '...');

            $output[] = $row;
        }
//                $length = count($output);
//                //Choose random 10 tracks from 100
//                for( $k=0; $k < $length; $k++){
//                        //$j = (int)($length * rand(0,1));
//                        $j = array_rand($output,1);
//                        $temp= $output[$k];
//                        $output[$k]= $output[$j];
//                        $output[$j]= $temp;
//                }
//                $output_random = array();
//                for( $k=0; $k < 10; $k++){
//                    $output_random[] =$output[$k]; 
//                }

        //return $output_random;
        return $output;
    }
    /*similar songs ends*/

    /*update_profile*/
    function update_cover_track($image, $folder_name)
    {
        /*Insert to photo table entry*/
        $img_exist = getvalfromtbl("id", "photos", " type='pc' AND detailId = '" . $this->sess_uid . "'", "single", "");

        if ($img_exist > 0) {
            $row = getvalfromtbl("*", "photos", " type='pc' AND detailId = '" . $this->sess_uid . "'");
            unlink(asset_path() . $row["dir"] . "/" . $row["name"]);

            $data = [
                'dir' => $folder_name,
                'name' => $image,
            ];
            $where = "(id ='" . $row["id"] . "' )";
            $this->db->where($where);
            $this->db->update('photos', $data);
        } else {
            $data_u_photo = [
                'detailId' => $this->sess_uid,
                'dir' => $folder_name,
                'name' => $image,
                'type' => 'pc',
            ];
            $this->db->insert('photos', $data_u_photo);
        }

        return "success";

    }

    function postComment($track_id, $user_id, $comment, $commentTime)
    {
        if ($track_id > 0 && $user_id > 0 && $comment != "" && $commentTime != "") {
            $data_t_comment = [
                'trackId' => $track_id,
                'userId' => $user_id,
                'comment' => $comment,
                'commentTime' => $commentTime,
                'createdDate' => date('Y-m-d H:i:s'),
                'ipAddress' => '',
            ];
            $this->db->insert('track_comments', $data_t_comment);

            return "true";
        } else {
            return "false";
        }
    }

    /**
     * Returns the count of likes for specified track
     * @param int $trackId
     * @return int
     */
    public function likes_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS likes_count FROM likelog WHERE trackId = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->likes_count;
        }

        return 0;
    }

    /**
     * Returns the count of shares for specified track
     * @param int $trackId
     * @return int
     */
    public function shares_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS shares_count FROM tracks WHERE id = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->likes_count;
        }

        return 0;
    }

    /**
     * Returns the count of plays for specified track
     * @param int $trackId
     * @return int
     */
    public function plays_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS plays_count FROM playinglog WHERE trackId = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->plays_count;
        }

        return 0;
    }

    /**
     * Returns the count of comments for specified track
     * @param int $trackId
     * @return int
     */
    public function comments_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS comments_count FROM track_comments WHERE trackId = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->comments_count;
        }

        return 0;
    }

    /**
     * Returns the count of playlists where specified track existing
     * @param int $trackId
     * @return int
     */
    public function playlists_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS playlists_count FROM playlist_detail WHERE trackId = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->playlists_count;
        }

        return 0;
    }

    /**
     * Returns the count of downloads for specified track
     * @param int $trackId
     * @return int
     */
    public function downloads_count($trackId)
    {
        $sql = 'SELECT COUNT(id) AS downloads_count FROM tracks_downloads WHERE track_id = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result();

        if (!empty($result[0])) {
            return (int)$result[0]->downloads_count;
        }

        return 0;
    }

    /**
     * Returns the common track details
     * @param int $trackId
     * @return array|null
     */
    public function common_details($trackId)
    {
        $sql = 'SELECT
                    t.shares AS shares_count,
                    COUNT(ll.id) AS likes_count,
                    COUNT(pl.id) AS plays_count,
                    COUNT(tc.id) AS comments_count,
                    COUNT(pd.id) AS playlists_count,
                    COUNT(td.id) AS downloads_count
                FROM tracks t
                    LEFT JOIN likelog ll ON ll.trackId = t.id
                    LEFT JOIN playinglog pl ON pl.trackId = t.id
                    LEFT JOIN track_comments tc ON tc.trackId = t.id
                    LEFT JOIN playlist_detail pd ON pd.trackId = t.id
                    LEFT JOIN tracks_downloads td ON td.track_id = t.id
                WHERE t.id = ' . $trackId;

        $query = $this->db->query($sql);
        $result = $query->result_array();

        if (!empty($result[0])) {
            return $result[0];
        }

        return null;
    }

    /**
     * Returns the ids of tracks which related to specific tag
     * @param int $tagId
     * @param int $tagType
     * @return array|null
     */
    public function tracks_by_tag($tagId, $tagType)
    {
        $sql = null;

        if (is_array($tagId)) {
            $idCondition = ' IN(' . array_to_str_list($tagId) . ')';
        } else {
            $idCondition = ' = ' . $tagId;
        }
        switch ($tagType) {
            case 'genre':
                $sql = 'SELECT trackId FROM track_genre WHERE genreId' . $idCondition;
                break;
            case 'instrumental':
                $sql = 'SELECT trackId FROM track_instruments WHERE instumentId' . $idCondition;
                break;
            case 'mood':
                $sql = 'SELECT trackId FROM track_moods WHERE moodId' . $idCondition;
                break;
        }
        if (!empty($sql)) {
            $query = $this->db->query($sql);
            $result = $query->result();

            if (!empty($result)) {
                $trackIds = null;
                foreach ($result as $row) {
                    $trackIds[] = (int)$row->trackId;
                }

                sort($trackIds);

                return $trackIds;
            }

            return null;
        }
    }

    /**
     *
     */
    public function track_by_id($trackId)
    {
        $track = 'SELECT 
                    t.id AS trackId,
                    t.title,
                    t.description,
                    t.release_dd,
                    t.release_mm,
                    t.release_yy,
                    t.trackuploadType,
                    t.genreId,
                    g.genre,
                    t.isPublic,
                    t.track_musician_type
                FROM tracks t 
                    LEFT JOIN genre g ON g.Id = t.genreId  
                    LEFT JOIN track_moods tm ON tm.trackId = t.Id 
                WHERE t.id = ' . $trackId;

        $secondary_genres = 'SELECT  
                                genreId
                             FROM track_genre 
                             WHERE trackId = ' . $trackId;


        $moods = 'SELECT
                      tm.moodId
                  FROM track_moods tm
                      LEFT JOIN mood m ON m.Id = tm.MoodId
                  WHERE tm.trackId = ' . $trackId;

        $licenses = 'SELECT 
                        licenceId AS id,
                        licencePrice 
                     FROM track_licence_price_details 
                     WHERE trackId = ' . $trackId;

        $licence_list = 'SELECT 
                             id,
                             name,
                             description,
                             lic_type
                         FROM track_licence_types 
                         WHERE status = \'y\'  ';
        $licence_list = $this->db->query($licence_list)->result_array();

        $track = $this->db->query($track)->result_array();

        $secondary_genres = $this->db->query($secondary_genres)->result_array();
        $moods = $this->db->query($moods)->result_array();
        $licenses = $this->db->query($licenses)->result_array();


        if (!empty($track[0])) {
            $res_secondary_genres = [];
            foreach ($secondary_genres as $secondary_genre) {
                $res_secondary_genres[] = $secondary_genre["genreId"];
            }
            $res_moods = [];
            foreach ($moods as $mood) {
                $res_moods[] = $mood["moodId"];
            }
            $res_licences = [];
            foreach ($licenses as $license) {
                $res_licences[$license["id"]] = $license["licencePrice"];
            }
            $licenses = [];
            foreach ($licence_list as $value) {
                if (!empty($value['licencePrice'])) {
                    $value['licencePrice'] = $res_licences[$value['id']];
                    $licenses[] = $value;
                } else {
                    $value['licencePrice'] = null;
                    $licenses[] = $value;
                }
            }

            $track[0]['moods'] = $res_moods;
            $track[0]['secondary_genres'] = $res_secondary_genres;
            $track[0]['licences'] = $licenses;

            return $track[0];
        }

        return null;
    }
}