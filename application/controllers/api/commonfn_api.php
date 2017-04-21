<?php defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH.'/libraries/REST_Controller.php';

class Commonfn_Api extends REST_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('commonfn');
        $this->load->model('playlist_model');
        $this->load->model('browse_recommended');
        $this->load->model('home_modal');
        $this->load->model("liked_model");
        $this->load->model('track_detail');
        $this->sess_id = $this->session->userdata('user')->id;
        $this->rec_to_dis = 10;
    }

    function upload_list_post()
    {

        $genre_list = $this->commonfn->get_genre("type='p'");
        $sec_genre_list = $this->commonfn->get_genre("type = 's'");
        $sound_like_list = $this->commonfn->get_soundlike();
        $mood_list = $this->commonfn->get_moods();
        $instuments_list = $this->commonfn->get_instuments();
        $sell_type_list = $this->commonfn->get_licence_types("lic_type='s'");
        $licence_types_list = $this->commonfn->get_licence_types("lic_type='l'");
        $np_type_list = $this->commonfn->get_licence_types("lic_type='np'");
        //exclusive license
        $el_type_list = $this->commonfn->get_licence_types("lic_type='el'");
        $track_upload_type_list = $this->commonfn->get_track_upload_types();

        $tracktypes = $this->commonfn->gettracktypes();


        $session_user_id = $this->session->userdata('user')->id;

        if ($session_user_id > 0) {
            $album_list = $this->commonfn->get_albums();
        }
        $response["genre"] = $genre_list;
        $response["sec_genre"] = $sec_genre_list;
        $response["sound_like_list"] = $sound_like_list;
        $response["album_list"] = $album_list;
        $response["mood_list"] = $mood_list;
        $response["instuments_list"] = $instuments_list;
        $response["sell_type_list"] = $sell_type_list;
        $response["licence_type_list"] = $licence_types_list;
        $response["np_type_list"] = $np_type_list;
        $response["el_type_list"] = $el_type_list;
        $response["track_upload_type_list"] = $track_upload_type_list;
        $response["lower_type_list"] = $tracktypes["lower_type_list"];
        $response["higher_type_list"] = $tracktypes["higher_type_list"];

        //print_r($user_roles_response);
        if ($response != "") {
            $this->response($response, 200); // 200 being the HTTP response code
        } else {
            $this->response(['error' => lang('error_try_again')], 404);
        }
    }

    function follow_post()
    {
        $to_id = $this->post('toid');
        $refreshpanel = ($this->post('refreshpanel') != "") ? $this->post('refreshpanel') : null;
        //must log in
        if ($to_id > 0 && !empty ($this->sess_id)) {
            if ($this->sess_id == $to_id) return $this->response(['error' => 'Follow unsuccessfull Can not follow yourself'], 404);
            $response = $this->commonfn->follow($to_id, NULL, $refreshpanel);
            /*print_r($response);*/
            if ($response != "") {
                if (isset ($response["status"])) {
                    if ($response["status"] == "successfull_follow" && $refreshpanel == "yes") {
                        $us_ar["success"] = "success";
                        $us_ar["data"] = $response["data"];
                        $us_ar["msg"] = "Following successfully.";
                        $this->response($us_ar, 200);
                    }
                }

                if ($response == "successfull_follow") {
                    $us_ar["success"] = "success";
                    $us_ar["followingId"] = $to_id;
                    $us_ar["msg"] = "Following successfully.";
                } else if ($response == "blocked") {
                    $us_ar["error"] = "error";
                    $us_ar["followingId"] = $to_id;
                    $us_ar["msg"] = "You are blocked.";
                } else if ($response == "blockedfortoday") {
                    $us_ar["error"] = "error";
                    $us_ar["followingId"] = $to_id;
                    $us_ar["msg"] = "Maximum users you can follow limit has reached.";
                } else if ($response == "perblock") {
                    $us_ar["error"] = "error";
                    $us_ar["followingId"] = $to_id;
                    $us_ar["msg"] = "You are blocked to access " . SITE_NM . " Please contact admin for further.";
                } else if ($response == "Alreadyfollowed") {
                    $us_ar["error"] = "error";
                    $us_ar["followingId"] = $to_id;
                    $us_ar["msg"] = "You already followed this user";
                }
                $this->response($us_ar, 200);
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        } else {
            if (empty($this->sess_id)) $this->response(['error' => 'Please log in Follow unsuccessfull.'], 404);
            else $this->response(['error' => 'Please try again Follow unsuccessfull.'], 404);
        }

    }

    function like_track_post()
    {
        $trackId = $this->post('trackId');
        if ($trackId > 0) {
            $response = $this->commonfn->like_track($trackId);

            if (!empty($response)) {
                if ($response["status"] == "successfull_tracklike") {
                    $us_ar["success"] = "success";
                    $us_ar["likeId"] = $trackId;
                    $us_ar["data"] = $response;
                    $us_ar["msg"] = "You have liked track successfully.";
                    $us_ar["msgtype"] = "success";
                    $us_ar["msgtitle"] = "success";
                } else if ($response["status"] == "already_liked") {
                    $us_ar["success"] = "success";
                    $us_ar["likeId"] = $trackId;
                    $us_ar["data"] = $response;
                    $us_ar["msg"] = "You have already liked this track.";
                    $us_ar["msgtype"] = "info";
                    $us_ar["msgtitle"] = "info";

                }
                $this->response($us_ar, 200); // 200 being the HTTP response code
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        } else {
            $this->response(['error' => 'Please try again track like unsuccessfull.'], 404);
        }
    }

    function unfollow_post()
    {
        $to_id = $this->post('toid');
        if ($to_id > 0 && !empty ($this->sess_id)) {
            $response = $this->commonfn->unfollow($to_id);
            if ($response != "") {
                if ($response == "successfull_unfollow") {
                    $us_ar["success"] = "success";
                    $us_ar["tofollowid"] = $to_id;
                    $us_ar["msg"] = "Un-followed successfully.";
                }
                $this->response($us_ar, 200);
            } else {

                $this->response("", 404);
            }
        } else {
            if (empty($this->sess_id)) $this->response(['error' => 'Please log in Follow unsuccessfull.'], 404);
            else $this->response(['error' => 'Please try again follow unsuccessfull.'], 404);
        }
    }


    /*Dislike track*/
    function dislike_track_post()
    {

        $trackId = $this->post('trackId');
        if ($trackId > 0) {
            $response = $this->commonfn->dislike_track($trackId);

            if (!empty($response)) {
                if ($response["status"] == "successfull_unlike") {
                    $us_ar["success"] = "success";
                    $us_ar["removeid"] = "#row" . $this->sess_id;
                    $us_ar["data"] = $response;
                    $us_ar["msg"] = "You have Unliked track successfully.";
                    $us_ar["msgtype"] = "success";
                    $us_ar["msgtitle"] = "success";
                } else if ($response["status"] == "error") {
                    $us_ar["error"] = "error";
                    $us_ar["data"] = $response;
                    $us_ar["msg"] = "Please try again.";
                    $us_ar["msgtype"] = "info";
                    $us_ar["msgtitle"] = "info";
                }
                $this->response($us_ar, 200); // 200 being the HTTP response code
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        } else {
            $this->response(['error' => 'Please try again track like unsuccessfull.'], 404);
        }
    }
    /*Unlike track ends*/


    /*Get state list of user*/
    function statelist_get()
    {
        $country_id = $this->get('country_id');

        if ($country_id > 0) {
            $response = $this->commonfn->get_states_city($country_id);

            if ($response != "") {
                $this->response($response, 200);
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        }
    }

    /*Get city list of user*/
    function citylist_get()
    {
        $state_id = $this->get('state_id');
        if ($state_id > 0) {
            $response = $this->commonfn->get_states_city($state_id);

            if ($response != "") {
                $this->response($response, 200);
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        }
    }

    //function for creating album
    function create_album_post()
    {
        //$this->form_validation->set_rules('album_image', 'Image', 'trim|required|xss_clean');

        //$this->form_validation->set_rules('album_title', 'Title', 'trim|required|xss_clean');


        //$this->form_validation->set_rules('album_desc', 'Desc', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('rdate_mm', 'Month', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('rdate_dd', 'Date', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('rdate_yy', 'Year', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('album_label', 'Label', 'trim|required|xss_clean');

        //if ($this->form_validation->run() == FALSE)
        //{
        //	$this->response(array('error' => lang('error_signup_val_required')), 400);
        //}
        //else
        //{
        //$this->load->view('formsuccess');

        //echo $this->post('sec_genre');

        //var_dump($this->post());exit;
        $temp = $this->post();
        $data = $temp[0];
        parse_str($data, $searcharray);
        //e.g. searcharray
        // [id] =>
        //    [waveform] =>
        //    [album_title] => Album title test
        //    [album_label] => test label
        //    [album_desc] => test des
        //    [genre] => 1
        //    [rdate_mm] => 01
        //    [rdate_dd] => 01
        //    [rdate_yy] => 2015
        //    [sec_tags] => Array
        //        (
        //            [0] => 31
        //            [1] => 36
        //        )
        //
        //    [album_avail_for_sale] => y
        //    [album_fully_avail] => y
        //    [album_price] => 10
        //print_r($searcharray);exit;
        $flag = false;//flag to decide: create or update Update if flag true
        $id = $searcharray['id'];
        if ($id > 0) {
            $flag = true;
        }


        $album_row = $this->commonfn->insert_album('', $searcharray['album_title'], $searcharray['album_desc'], $searcharray['rdate_mm'],
            $searcharray['rdate_dd'], $searcharray['rdate_yy'], $searcharray['album_label'], $searcharray['genre'], "", "", $searcharray['sec_tags'],
            $flag, $id, $searcharray['album_avail_for_sale'], $searcharray['album_fully_avail'], $searcharray['album_price']);

        if ($album_row) {
            $us_ar["success"] = "success";
            $this->response($album_row, 200);
        } else {
            $this->response(['error' => 'Please try again.Album not created.'], 404);
        }
        //}
    }

    /*Function for adding track to playset*/
    function addtrack_to_playlist_post()
    {

        $track_id = $this->post('trackid');
        $playlist_id = $this->post('playlistid');

        if ($track_id > 0 && $playlist_id > 0) {
            $response = $this->commonfn->addtrack_to_playlist($playlist_id, $track_id);

            if ($response != "") {
                if ($response == "added") {
                    $us_ar["success"] = "success";
                    $us_ar["playlistId"] = $playlist_id;
                    $us_ar["msg"] = "Track added to playlist successfully.";
                } else if ($response == "track_exist") {
                    $us_ar["error"] = "error";
                    $us_ar["playlistId"] = $playlist_id;
                    $us_ar["msg"] = "Track already exist in playlist.";
                }
                $this->response($us_ar, 200); // 200 being the HTTP response code
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        } else {
            $this->response(['error' => 'Please try again follow unsuccessfull.'], 404);
        }


    }
    /*Function for adding track to playset ends*/

    /*Function for removing track from playset*/
    function removetrack_from_playlist_post()
    {
        $track_id = $this->post('trackid');
        $playlist_id = $this->post('playlistid');

        if ($track_id > 0 && $playlist_id > 0) {
            $response = $this->commonfn->removetrack_from_playlist($playlist_id, $track_id);
            //dump($response);
            //print_r($user_roles_response);
            if ($response != "") {
                if ($response["status"] == "removed") {
                    $us_ar["success"] = "success";
                    $us_ar["songs_count"] = $response["no_of_tracks"];
                    $us_ar["msg"] = "Track removed from playlist successfully.";
                }
                $this->response($us_ar, 200); // 200 being the HTTP response code
            } else {
                //$this->response(array('error' => lang('error_try_again')), 404);
                $this->response("", 404);
            }
        } else {
            $this->response(['error' => 'Please try again follow unsuccessfull.'], 404);
        }


    }

    /*Function for adding track to playset ends*/

    function get_my_playset_post()
    {

        if ($this->sess_id > 0) {
            $trackId = $this->post("tid");
            $response = $this->playlist_model->get_my_playlists($trackId);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }

    /*Function for playset songs...*/
    function playset_songs_post()
    {
        $playlistId = $this->post("plid");

        if ($playlistId > 0) {
            $response = $this->playlist_model->get_playlist_songs($playlistId);

            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }
    /*Function for playset songs...*/

    /*User json data*/
    function get_user_json_get()
    {
        $userId = $this->post("userId");

        if ($userId <= 0)
            $userId = "27";

        if ($userId > 0) {
            $response = $this->commonfn->get_user_info_json($userId);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }


    /*track json data*/
    function get_track_json_get()
    {
        $trackId = $this->post("trackId");

        if ($trackId <= 0)
            $trackId = "2";

        if ($trackId > 0) {
            $response = $this->commonfn->get_track_info_json($trackId);

            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }


    /*track json data*/
    function get_usertracks_json_get()
    {
        $trackId = $this->post("userId");
        if ($userId <= 0)
            $userId = "27";

        if ($userId > 0) {
            $response = $this->commonfn->get_usertracks_info_json($userId);
            //dump($response);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }

    /*Initial Playlist json data*/
    function initial_playlist_json_get()
    {
        $type = $this->get("type");
        if (empty($type)) $type = 'home';
        switch ($type) {
            case 'home':
                $response = $this->home_modal->get_overview("", $this->rec_to_dis, NULL, NULL, NULL, 373, 373, '', '', 38, 38);
                break;
            case 'browse':
                $start_limit = 0;
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $response = $this->browse_recommended->fetch_popular_tracks("", $new_limit, "", "", $start_limit);
                break;
            case 'playlist':  //link /sets
                $response = $this->playlist_model->get_playlists($playlistLink, $profileLink);
                break;
            case 'favorite':
                $response = $this->liked_model->fetch_user_liked_tracks(NULL, NULL, 2);
                break;
            default:
                //track detail
                //get similar songs
                //$track_exists = true;

                $temp = $this->get("current_link");
                $temp_arr = explode('/', $temp);
                $count = count($temp_arr);
                $trackLink = $temp_arr[$count - 1];
                $cond = "isPublic = 'y' AND perLink = " . $this->db->escape($trackLink) . "";
                $track_exists = getvalfromtbl("id", "tracks", $cond, "single", "");
                //var_dump($track_exists);exit;
                $response = $this->track_detail->fetch_similar_tracks($track_exists);

        }


        if (is_array(($response))) $response = array_slice($response, 0, 10);
        //dump($response);
        if ($response) {
            $us_ar["success"] = "success";
            $us_ar["data"] = $response;
            //$us_ar["msg"] = "";
        }
        $this->response($us_ar, 200); // 200 being the HTTP response code
    }


    /*Playset json*/
    function get_playlist_json_get()
    {
        $playlistId = $this->post("playlistId");
        if ($playlistId <= 0)
            $playlistId = "6";

        if ($playlistId > 0) {
            $response = $this->commonfn->get_playlist_info_json($playlistId);
            //dump($response);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }


    /*album json*/
    function get_album_json_get()
    {
        $albumId = $this->post("albumId");
        if ($albumId <= 0)
            $albumId = "1";

        if ($albumId > 0) {
            $response = $this->commonfn->get_album_info_json($albumId);
            //dump($response);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }
    }

    function get_usertype_json_get()
    {

        if (!empty($this->session->userdata('user')->id)) {
            $user_id = $this->session->userdata('user')->id;
            //get all roles ID if available
            $this->db->select('ud.roleId, ur.type, ur.role');
            $this->db->from('user_roles_details as ud');
            $this->db->join('user_roles as ur', 'ur.id = ud.roleId AND ud.userId = "' . $user_id . '"', 'inner');
            $this->db->limit(100);
            $query = $this->db->get();
            $artist_found = false;
            if ($query->num_rows() > 0) {

                foreach ($query->result_array() as $row) {
                    if ($row['type'] == 'artist') {
                        $artist_found = true;
                        $us_ar["status"] = "success";
                        $us_ar["data"] = 'artist';
                        $this->response($us_ar, 200); // 200 being the HTTP response code
                    }
                }

            }
            if (!$artist_found) {
                $us_ar["status"] = "success";
                $us_ar["data"] = 'user';
                $this->response($us_ar, 200); // 200 being the HTTP response code
            }


        } else $this->response("", 404);

    }

    function browse_pop_users_post()
    {
        $this->load->model('browse_recommended');
        $startlimit = $this->post("startlimit");

        if ($startlimit > 0) {
            $response = $this->browse_recommended->fetch_popular_artist("", $startlimit . ",3");
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }

    }


    /*Search functionlaity*/
    function search_records_post()
    {
        $this->load->model('search');
        $keyword = $this->post("keyword");

        if ($keyword != "" || $keyword != NULL) {
            $response = $this->search->search_records($keyword);
            //dump($response);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["data"] = $response;
                //$us_ar["msg"] = "";
            } else {
                $us_ar["error"] = "error";
                $us_ar["msg"] = "No records found for this keyword.";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }

    }

    function explore_tags_search_post()
    {
        $startlimit = $this->post("startlimit");
        $rec_to_dis = 10;
        if ($startlimit > 0) {
            $response = $this->commonfn->get_genre("type='p'", $rec_to_dis, $startlimit);
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["explore_genres"] = $response;
                $total_records = $this->commonfn->get_genre("type='p'", "", "", "", "counter");
                $us_ar["last_page"] = ceil($total_records / $rec_to_dis);

            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        } else {
            $this->response("", 404);
        }

    }

    function sec_genre_list_post()
    {
        $genreId = $this->post("genreId");
        if ($genreId > 0) {
            $response = $this->commonfn->get_genre("type='s' AND parentId = '" . $genreId . "'");
            if ($response) {
                $us_ar["success"] = "success";
                $us_ar["sub_genres"] = $response;
            } else {
                $us_ar["success"] = "error";
                $us_ar["msg"] = "No genre found.";

            }
            $this->response($us_ar, 200);
        } else {
            $us_ar["success"] = "error";
            $us_ar["sub_genres"] = "No genre found.";
            $this->response($us_ar, 404);
        }

    }

    function track_counter_increase_post()
    {
        $trackId = $this->post("trackId");
        if ($trackId > 0) {
            $reponse = $this->commonfn->track_counter_increase($this->post());
            if ($reponse["status"] == "success") {
                $this->response($reponse, 200);
            }
        } else {
            $this->response("", 404);
        }
    }

    function album_delete_post()
    {
        $userId = $this->post("userId");
        $albumId = $this->post("albumId");
        if ($userId > 0 && $albumId > 0) {
            $reponse = $this->commonfn->album_delete($userId, $albumId);
            if ($reponse["status"] == "success") {
                $this->response($reponse, 404);
            }
        } else {
            $this->response("", 404);
        }
    }

    function playlist_delete_post()
    {
        $userId = $this->post("userId");
        $playlistId = $this->post("playlistId");
        if ($userId > 0 && $playlistId > 0) {
            $reponse = $this->commonfn->playlist_delete($userId, $playlistId);
            if ($reponse["status"] == "success") {
                $this->response($reponse, 404);
            }
        } else {
            $this->response("", 404);
        }
    }


    function exp_search_records_post()
    {
        $this->load->model('search');
        $this->load->model('browse_recommended');

        $condition = "";
        /*$id = $this->post("id");
        $type = $this->post("type");
        $extra = $this->post("extra");*/
        $search_params = $this->post("search_params");
        //print_r($search_params);
        //$type = $this->post("type");
        $extra = $this->post("extra");

        //genre_explore_list
        if ($search_params["genre_explore_list"] != "") {
            $condition .= " AND tt.genreId = '" . $search_params["genre_explore_list"] . "'";
        }

        //sub genre_explore_list
        if ($search_params["subgenre_explore_list"] != "") {
            $explode_arr = explode(",", $search_params["subgenre_explore_list"]);
            $condition1 = "AND (";
            $count = count($explode_arr);
            $i = 0;
            $temp = 0;
            foreach ($explode_arr as $ear) {
                if ($i == 0 || $i == $count) {
                    $condition1 .= " tg.genreId = '" . $ear . "'";
                    $temp = $ear;
                } else {
                    if ($temp != $ear) {
                        $condition1 .= " OR tg.genreId = '" . $ear . "'";
                        $temp = $ear;
                    }
                }
                $i++;
            }
            $condition1 .= ")";
            $condition .= $condition1;
        }

        if ($search_params["d_genre_explore"] > 0) {
            $condition .= " AND tt.genreId = '" . $search_params["d_genre_explore"] . "'";
        }
        if ($search_params["d_mood_explore"] > 0) {
            $condition .= " AND tm.moodId = '" . $search_params["d_mood_explore"] . "'";
        }
        /* Instruments explore search */
        if ($search_params["d_instrument_explore"] > 0) {
            $condition .= " AND ti.instumentId = '" . $search_params["d_instrument_explore"] . "'";
        }
        /* Instruments explore search ends */

        /*Price range*/
        if ($search_params["price_from"] > 0 && $search_params["price_to"] > 0) {
            $condition .= " AND tt.price BETWEEN '" . $search_params["price_from"] . "' AND '" . $search_params["price_to"] . "' ";
        }
        /*Price range ends*/

        /*time*/
        if ($search_params["time_from"] >= 0 && $search_params["time_to"] > 0) {
            $condition .= " AND tt.timelength BETWEEN '" . $search_params["time_from"] . "' AND '" . $search_params["time_to"] . "' ";
        }
        /*Price range ends*/

        if ($condition != "") {

            $response = $this->search->search_track_records($condition);

            if ($response['status'] == "fail") {
                $us_ar["success"] = "fail";
                $us_ar["error"] = "error";
                $us_ar["msg"] = "Please try again with different parameter.";
            } else if ($response) {
                $us_ar["success"] = "success";
                $us_ar["header_tmp"] = "true";
                $us_ar["data_array"] = $response;

                if ($extra == "subgenre") {
                    //echo "type = 's' AND id NOT IN (".$search_params["subgenre_explore_list"].") AND parentId = '".$search_params["genre_explore_list"]."'";

                    if ($search_params["subgenre_explore_list"] != "")
                        $us_ar["explore_genres"] = $this->commonfn->get_genre("type = 's' AND id NOT IN (" . $search_params["subgenre_explore_list"] . ") AND parentId = '" . $search_params["genre_explore_list"] . "'");
                    else
                        $us_ar["explore_genres"] = $this->commonfn->get_genre("type = 's' AND parentId = '" . $search_params["genre_explore_list"] . "'");
                }

                if ($extra == "allgenre")
                    $us_ar["explore_genres"] = $this->commonfn->get_genre("type = 'p'");

                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200);

        } else if ($condition == "" || $condition == NULL) {

            $response = $this->browse_recommended->fetch_popular_tracks("", 10);

            if ($response['status'] == "fail") {
                $us_ar["success"] = "fail";
                $us_ar["error"] = "error";
                $us_ar["msg"] = "Please try again with different parameter.";
            } else if ($response) {
                $us_ar["success"] = "success";
                $us_ar["header_tmp"] = "true";
                $us_ar["data_array"] = $response;
                $us_ar["explore_genres"] = $this->commonfn->get_genre("type = 'p'");
                $us_ar["header_main_title"] = "SEARCH WITH TAGS";
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200);

        } else {
            $this->response("", 404);
        }
        /*if(($type != "" || $type != NULL) && $id > 0)
        {
            if($type == "subgenre")
            {
                $response = $this->search->search_track_records("tg.genreId = '".$id."'");
            }else{
                $response = $this->search->advanced_search_records("tt.genreId = '".$id."'");
            }

            //dump($response);
            if($response['status'] == "fail")
            {
                $us_ar["success"] = "fail";
                $us_ar["error"] = "error";
                $us_ar["msg"] = "Please try again with different parameter.";
            }
            else if($response)
            {
                $us_ar["success"] = "success";
                $us_ar["header_tmp"] = "true";
                $us_ar["data_array"] = $response;
                if($extra == "subgenre" && $type != "subgenre")
                    $us_ar["explore_genres"] = $this->commonfn->get_genre("type = 's' AND parentId = '".$id."'");
                //$us_ar["msg"] = "";
            }
            $this->response($us_ar, 200); // 200 being the HTTP response code
        }
        else{
            $this->response("", 404);
        }	*/
    }

    /**
     * Returns the last registered artists ordered by count of likes on added tracks
     *
     * [GET /api/new_artists]
     */
    public function new_artists_get()
    {
        $this->load->model('user_profile');
        $result = $this->user_profile->fetchNewArtists();

        $this->response($result, 200);
    }
}