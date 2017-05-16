<?php if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class recommended extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('browse_recommended');
        $this->pop_artist_limit = 3;
        $this->rec_to_dis = 10;
    }

    function index($action = "", $page = null)
    {
        $this->load->helper('url');
        $cm = $this->config->item('meta_keyword') . ',def,';
        $this->config->set_item('meta_keyword', $cm);
        $this->config->set_item('title', 'Recommended');
        add_css(['app.v1.css']);
        if ($this->session->userdata('user')->id > 0) {
            $left_panel = true;
            $username = $this->session->userdata('user')->username;
        } else {
            $left_panel = false;
            $username = null;
        }

        if ($page != "" && $page > 0) {
            $start_limit = (($page - 1) * $this->rec_to_dis);
        }


        if ($action == "new-songs") {
            $total_records = $this->browse_recommended->fetch_new_tracks("", "", "", "counter");

            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_new_tracks("", $new_limit, "", "", $start_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }

            $data_array = $this->browse_recommended->fetch_new_tracks("", $this->rec_to_dis);
            /*dump($data_array);
            exit();*/
            $temp_name = "general/browse_rec_music_row.html";
            $tabid = "new-songs";
            $class = "browse-songs waveform-hover";
            $active_class = "active";
            $songs_ul = "true";
            $new_songs_active = "new_songs_active";
            $header_tmp = "true";
            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/new-songs";
                $load_cont = "#new-songs";
                $load_tmpl = "browse_pop_new_songs";
            } else {
                $loadmore = "";
            }
        } else if ($action == "popular-artist") {
            $total_records = $this->browse_recommended->fetch_popular_artist("", "", "", "counter");

            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_popular_artist("", $new_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }

            $data_array = $this->browse_recommended->fetch_popular_artist("", $this->rec_to_dis);
            $temp_name = "browse/browse_rec_pop_artist_row.html";
            $tabid = "pop-artists";
            $class = "video-list";
            $popular_artist_active = "popular_artist_active";

            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/popular-artist";
                $load_cont = "#pop-artists";
                $load_tmpl = "browse_popartist";
            } else {
                $loadmore = "";
            }
        } else if ($action == "new-artists") {
            $total_records = $this->browse_recommended->fetch_new_artist("", "", "", "counter");
            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_new_artist("", $new_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }
            $data_array = $this->browse_recommended->fetch_new_artist("", $this->rec_to_dis);
            $temp_name = "browse/browse_rec_pop_artist_row.html";
            $tabid = "new-artists";
            $class = "video-list";
            $new_artist_active = "new_artist_active";

            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/new-artists";
                $load_cont = "#new-artists";
                $load_tmpl = "browse_popartist";
            } else {
                $loadmore = "";
            }
        } else if ($action == "popular-playlist") {

            $total_records = $this->browse_recommended->fetch_popular_playlist("", "", "", "counter");
            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_popular_playlist("", $new_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }

            $data_array = $this->browse_recommended->fetch_popular_playlist("", $this->rec_to_dis);
            $temp_name = "browse/browse_rec_pop_artist_row.html";
            $tabid = "pop-artists";
            $class = "video-list";
            //$tabid = "new-artists";
            $popular_playlist_active = "popular_playlist_active";

            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/popular-playlist";
                $load_cont = "#pop-artists";
                $load_tmpl = "browse_popartist";
            } else {
                $loadmore = "";
            }
        } else if ($action == "new-playlist") {
            $total_records = $this->browse_recommended->fetch_new_playlist("", "", "", "counter");
            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_new_playlist("", $new_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }

            $data_array = $this->browse_recommended->fetch_new_playlist("", $this->rec_to_dis);
            $temp_name = "browse/browse_rec_pop_artist_row.html";
            $tabid = "new-playlist";
            $class = "video-list";
            //$tabid = "new-artists";
            $new_playlist_active = "new_playlist_active";

            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/new-playlist";
                $load_cont = "#new-playlist";
                $load_tmpl = "browse_popartist";
            } else {
                $loadmore = "";
            }
        } else {

            $total_records = $this->browse_recommended->fetch_popular_tracks("", "", "", "counter");

            if ($page != null && $page > 0) {
                $new_limit = $start_limit . "," . $this->rec_to_dis;
                $last_page = ceil($total_records / $this->rec_to_dis);
                $data_array = $this->browse_recommended->fetch_popular_tracks("", $new_limit, "", "", $start_limit);
                $final_array["data"] = $data_array;
                $final_array["page"] = $page + 1;
                $final_array["last_page"] = $last_page;
                header('Content-Type: application/json');
                echo json_encode($final_array);
                exit;
            }

            $temp_name = "general/browse_rec_music_row.html";
            $data_array = $this->browse_recommended->fetch_popular_tracks("", $this->rec_to_dis);

            $tabid = "popular-songs";
            $class = "browse-songs waveform-hover";
            $songs_ul = "true";
            $action = "popular-songs";
            //$cls_active = "popular_song_active";
            $popular_songs_active = "popular_songs_active";
            $header_tmp = "true";

            if ($total_records > $this->rec_to_dis) {
                $loadmore = "true";
                $load_url = "browse/popular-songs/";
                $load_cont = "#popular-songs";
                $load_tmpl = "browse_pop_new_songs";
            } else {
                $loadmore = "";
            }
        }

        //dump($data_array);
        $p[] = [
            "song_cover_image" => img_url() . "song-cover-img.png", "pl_name" => "Unknown", "pl_songs" => "150",
            "pl_followers" => "150", "pl_likes" => "250",
        ];

        /*recently_listened*/
        $rl[] = [
            "tab_image" => img_url() . "vedio-img1.jpg", "tab_name" => "abc",
            "tab_waveform" => img_url() . "small-wave-img.png",
        ];


        $new_songs_active = isset($new_songs_active) ? $new_songs_active : "";
        $popular_artist_active = isset($popular_artist_active) ? $popular_artist_active : "";
        $new_artist_active = isset($new_artist_active) ? $new_artist_active : "";
        $popular_playlist_active = isset($popular_playlist_active) ? $popular_playlist_active : "";
        $new_playlist_active = isset($new_playlist_active) ? $new_playlist_active : "";
        $popular_songs_active = isset($popular_songs_active) ? $popular_songs_active : "";

        $popular_users = $this->browse_recommended->fetch_popular_artist("", $this->pop_artist_limit, "", "", "", "300");

        $recom_Arr = [
            /*'playsets' => $p,
            'recently_listened' => $rl,*/
            'username' => $username,
            'user_type' => "user",
            'data_array' => $data_array,
            'tabid' => $tabid,
            'class_nm' => $class,
            'songs_ul' => $songs_ul,
            'cls_active' => $cls_active,
            'new_songs_active' => $new_songs_active,
            'popular_artist_active' => $popular_artist_active,
            'new_artist_active' => $new_artist_active,
            'popular_playlist_active' => $popular_playlist_active,
            'new_playlist_active' => $new_playlist_active,
            'popular_songs_active' => $popular_songs_active,
            'popular_users' => $popular_users,
            'loadmore' => $loadmore,
            'load_more_url' => $load_url,
            'template' => $load_tmpl,
            'container' => $load_cont,
            'header_tmp' => $header_tmp,
            'recommended_page' => "true",
        ];

        $template_arry['MainPanel'] = "main.html";
        $template_arry['leftPanel'] = "left_panel.html";
        $template_arry['rightPanel'] = "right_panel.html";
        /* $template_arry['songsRow']="upload/uploaded_song_row.html";
        $template_arry['songslistRow']="upload/album_track_list_row.html"; */
        //$template_arry['popularSongs']="browse/browse_rec_music_row.html";
        //$template_arry['popArtist']="browse/browse_rec_pop_artist_row.html";

        $template_arry['tabRender'] = "general/tab_render.html";

        if ($action == "new-songs" || $action == "popular-songs") {
            $template_arry['profileHeader'] = "browse/browse_music_header.html";
        }

        $template_arry['popUsers'] = "browse/popular_user_row.html";
        $template_arry['arrayData'] = $temp_name;

        if ($loadmore == "true") {
            $template_arry['loadMore'] = "general/loadmore.html";
        }

        $template_arry['contentPanel'] = "browse/browse_recommended.html";
        $template_arry['playerPanel'] = "player_panel.html";

        $data1 = get_template_content($template_arry, $recom_Arr);

        $a['data'] = $data1;
        $a['redirectURL'] = base_url();
        $a['current_tm'] = 'recomended';
        $this->load->view('home', $a);

    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */