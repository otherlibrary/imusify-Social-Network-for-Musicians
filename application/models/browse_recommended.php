<?php

Class browse_recommended extends CI_Model
{

    function __construct()
    {
        $session_user_id = $this->session->userdata('user')->id;
        $this->sess_userid = $session_user_id;
    }

    function fetch_popular_tracks($condition = null,
                                  $limit = null,
                                  $orderby = null,
                                  $counter = null,
                                  $start_limit = null,
                                  $user_id = null)
    {
        $this->load->model('commonfn');
        $userId = ($userId != null) ? $userId : $this->sess_userid;
        $output = [];

        if ($userId > 0)
            //tracks as tt,genre as g,users as u,albums as al
            //$cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id
            //      AND ((tt.isPublic='n' and tt.userId='".$userId."') or tt.isPublic='y')";
        {
            $cond = " WHERE tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id  AND tt.albumId = al.id 
                            AND ((tt.isPublic='n' and tt.userId='" . $userId . "') or tt.isPublic='y')";
        } else {
            $cond = " WHERE tt.isPublic='y' AND tt.status = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id";
        }

        if ($condition != null) {
            $cond .= " AND " . $condition;
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY plays DESC," . $orderby;
        } else //$orderby ="ORDER BY plays,tt.id DESC";
        {
            $orderby = "ORDER BY plays DESC";
        }

        $query = $this->db->query("SELECT tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,
                    tt.shares,tt.perLink,tt.waveform,g.genre,u.firstname as artist_name,u.lastname,u.username,u.profileLink,
                    al.name as album, tt.albumId as albumid FROM tracks as tt,genre as g,users as u,albums as al " . $cond . " " . $orderby . " " . $limit . " ");
//               $query = $this->db->query("SELECT tt.id,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,
//                    tt.shares,tt.perLink,g.genre,u.firstname as artist_name,u.lastname,u.profileLink
//                  FROM tracks as tt,genre as g,users as u ".$cond." ".$orderby." ".$limit." "); 
        //var_dump ($this->db->last_query());exit;
        if ($counter != null) {
            return $query->num_rows();
        }

        $i = 1;

        if ($start_limit != null) {
            $i = $start_limit + 1;
        }

        foreach ($query->result_array() as $row) {
            $row["track_image"] = $this->commonfn->get_photo('t', $row["id"]);
            $row["i"] = $i;
            if ($i % 2 != 0) {
                $row["gray_bg"] = "gray-bg";
            } else {
                $row["gray_bg"] = "";
            }

            $row["main_title"] = character_limiter($row["title"], 20, $end_char = '...');
            $row["artist_mini_name"] = character_limiter($row["artist_name"], 20, $end_char = '...');
            $row["total_songs"] = "";
            $row["waveform"] = $row['waveform'];
            $row["trackLink"] = base_url() . $row["profileLink"] . "/" . $row["perLink"];
            $row["profileLink"] = base_url() . $row["profileLink"];

            $row["like_js_class"] = "like_js";
            $row["like_class"] = "like-icon";

            if ($userId > 0) {
                $is_liked = getvalfromtbl("id", "likelog", " userId = '" . $userId . "'  AND trackId = '" . $row["id"] . "'", "single", "");
                if ($is_liked > 0) {
                    $row["like_js_class"] = "dislike_js";
                    $row["like_class"] = "unlike-icon";
                }
            }
            if ($row['albumid'] == 0) {
                $row['album'] = '  ';
            }
            //$row['album'] = '';
            $output[] = $row;
            //var_dump ($output, $row);exit;
            //$output['album'] = 'ABC';
            $i++;
        }

        return $output;
    }

    /*Function for fetching new tracks*/
    function fetch_new_tracks($condition = null,
                              $limit = null,
                              $orderby = null,
                              $counter = null,
                              $start_limit = null,
                              $width = null,
                              $height = null,
                              $images_only = null,
                              $userId = null,
                              $uwidth = null,
                              $uheight = null)
    {
        $userId = ($userId != null) ? $userId : $this->sess_userid;
        $this->load->model('commonfn');
        $output = [];
        if ($userId > 0) {
            $cond = " tt.status = 'y' AND ((tt.isPublic='n' and tt.userId='" . $userId . "') or tt.isPublic='y')";
        } else {
            $cond = " tt.status = 'y' AND tt.isPublic='y'";
        }

        if ($condition != null) {
            $cond .= " AND " . $condition;
        }

        $cond .= " AND tt.is_sellable = 'y' ";

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY tt.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY tt.id DESC";
        }

        $this->db->select('tt.id,tt.featured,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,tt.isPublic,tt.userId as trackuserid,tt.waveform,g.genre,u.firstname as artist_name,u.lastname,u.id as uid,u.profileLink,a.name as album');
        $this->db->from('tracks as tt');
        $this->db->join('genre as g', 'tt.genreId = g.id', 'left');
        $this->db->join('albums as a', 'tt.albumId = a.id', 'left');
        $this->db->join('users as u', 'tt.userId = u.id', 'left');
        $this->db->where($cond);
        $this->db->order_by("tt.id", "desc");
        $query = $this->db->get();
//                echo $this->db->last_query();
//	        var_dump($query);exit;	

        if ($counter != null) {
            return $query->num_rows();
        }
        $i = 1;
        if ($start_limit != null) {
            $i = $start_limit + 1;
        }

        foreach ($query->result_array() as $row) {
            $row["track_image"] = $this->commonfn->get_photo('t', $row["id"]);
            $row["user_image"] = $this->commonfn->get_photo('p', $row["uid"]);
            $row["main_title"] = character_limiter($row["title"], 20, $end_char = '...');
            $row["artist_mini_name"] = character_limiter($row["artist_name"], 20, $end_char = '...');
            $row["i"] = $i;
            $row["total_songs"] = "";
            $row["featured"] = ($row["featured"] == 'y' ? true : false);
            //$row["waveform"] = img_url()."wave1.png";
            $row["waveform"] = $row['waveform'];
            $row["trackLink"] = base_url() . $row["profileLink"] . "/" . $row["perLink"];
            $row["profileLink"] = base_url() . $row["profileLink"];
            $row["album"] = ($row["album"] == "" || $row["album"] == null) ? "-" : $row["album"];

            $row["like_js_class"] = "like_js";
            $row["like_class"] = "like-icon";

            if ($userId > 0) {
                $is_liked = getvalfromtbl("id", "likelog", " userId = '" . $userId . "'  AND trackId = '" . $row["id"] . "'", "single", "");
                if ($is_liked > 0) {
                    $row["like_js_class"] = "dislike_js";
                    $row["like_class"] = "unlike-icon";
                }
            }
            $output[] = $row;
            $i++;
        }

        //var_dump($output);exit;
        return $output;
    }

    function fetch_instrumental($condition = null,
                                $limit = null,
                                $orderby = null,
                                $counter = null,
                                $start_limit = null,
                                $width = null,
                                $height = null,
                                $userId = null,
                                $uwidth = null,
                                $uheight = null)
    {
        $this->load->model('commonfn');
        $userId = ($userId != null) ? $userId : $this->sess_userid;

        $output = [];

        if ($userId > 0) {
            $cond = " WHERE ti.trackId=tt.id and tt.status = 'y' AND tt.isPublic = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id AND tt.status = 'y' AND ((tt.isPublic='n' and tt.userId='" . $userId . "') or tt.isPublic='y')";
        } else {
            $cond = " WHERE ti.trackId=tt.id and tt.status = 'y' AND tt.isPublic = 'y' AND tt.genreId = g.id AND tt.userId = u.id AND tt.albumId = al.id AND tt.status = 'y' AND tt.isPublic='y'";
        }

        if ($condition != null) {
            $cond .= " AND" . $condition;
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY tt.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY tt.id DESC";
        }

        $query = $this->db->query("SELECT tt.id,tt.featured,tt.title,tt.release_mm,tt.release_dd,tt.release_yy,tt.createdDate,tt.timelength,tt.plays,tt.comments,tt.shares,tt.perLink,tt.waveform,g.genre,u.firstname as artist_name,u.lastname,u.id as uid,u.profileLink,al.name as album FROM tracks as tt,genre as g,users as u,track_instruments as ti,albums as al " . $cond . " " . $orderby . " " . $limit . " ");

        if ($counter != null) {
            return $query->num_rows();
        }

        //echo print_query();
        $i = 1;

        if ($start_limit != null) {
            $i = $start_limit + 1;
        }

        foreach ($query->result_array() as $row) {
            $row["track_image"] = $this->commonfn->get_photo('t', $row["id"]);
            $row["user_image"] = $this->commonfn->get_photo('p', $row["uid"]);
            $row["main_title"] = character_limiter($row["title"], 20, $end_char = '...');
            $row["artist_mini_name"] = character_limiter($row["artist_name"], 20, $end_char = '...');
            $row["featured"] = ($row["featured"] == 'y' ? true : false);
            $row["i"] = $i;
            $row["total_songs"] = "";
            //$row["waveform"] = img_url()."wave1.png";
            $row["waveform"] = $row['waveform'];
            $row["wave"] = img_url() . 'hover-wave-img.png';

            $row["trackLink"] = base_url() . $row["profileLink"] . "/" . $row["perLink"];
            $row["profileLink"] = base_url() . $row["profileLink"];

            $output[] = $row;
            $i++;
        }

        return $output;
    }

    /*Pop artist*/
    /*Function for fetching popular artist*/
    function fetch_popular_artist($cond = null,
                                  $limit = null,
                                  $orderby = null,
                                  $counter = null,
                                  $start_limit = null,
                                  $big_img = null)
    {
        $this->load->model('commonfn');

        //get all artist IDs from user_roles_details
        $query = $this->db->query("select distinct userId from user_roles_details");
        $string_user_ids = "(";
        foreach ($query->result_array() as $row) {
            foreach ($row as $key => $val) {
                $string_user_ids .= "$val,";
            }
        }
        $string_user_ids = rtrim($string_user_ids, ",");
        $string_user_ids .= ')';
        //var_dump ($string_user_ids);exit;

        $output = [];

        if ($big_img != null) {
            $cons = IMG_376;
        } else {
            $cons = IMG_156;
        }

        if ($cond != null) {
            $cond = " WHERE u.status = 'y' AND tt.isPublic = 'y' AND u.id=t.id AND " . $cond . "";
        } else {
            $cond = " WHERE u.status = 'y' AND tt.isPublic = 'y' AND u.id=t.id";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY " . $orderby;
        } else {
            $orderby = "ORDER BY avg desc,tot_songs desc";
        }

        //select u.*,songs_plays,tot_songs,COALESCE((t.songs_plays/t.tot_songs),0) as avg from (SELECT u.id,(SELECT COALESCE(COUNT(id),0) from tracks where tracks.userId = u.id) as tot_songs,(SELECT COALESCE(SUM(plays),0) from tracks where tracks.userId = u.id) as songs_plays FROM users as u WHERE u.status = 'y' ORDER BY u.id DESC) as t ,users u where u.id=t.id order by avg desc,tot_songs desc

        //$query = $this->db->query("select u.*,songs_plays,tot_songs,COALESCE((t.songs_plays/t.tot_songs),0) as avg from (SELECT u.id,(SELECT COALESCE(COUNT(id),0) from tracks where tracks.userId = u.id) as tot_songs,(SELECT COALESCE(SUM(plays),0) from tracks where tracks.userId = u.id) as songs_plays FROM users as u WHERE u.status = 'y' ORDER BY u.id DESC) as t ,users u".$cond." ".$orderby." ".$limit." ");

        $query = $this->db->query("select u.id,u.firstname,u.lastname,u.profileLink,songs_plays,tot_songs,COALESCE((t.songs_plays/t.tot_songs),0) as avg from (SELECT u.id,(SELECT COALESCE(COUNT(id),0) from tracks where tracks.userId = u.id) as tot_songs,(SELECT COALESCE(SUM(plays),0) from tracks where tracks.userId = u.id) as songs_plays FROM users as u WHERE u.status = 'y' ORDER BY u.id DESC) 
                    as t ,users u where u.id=t.id AND u.id IN $string_user_ids order by avg desc,tot_songs desc " . $limit . "");
        //var_dump ($this->db->last_query());exit;
        if ($counter != null) {
            return $query->num_rows();
        }
        //echo print_query();
        $i = 1;
        foreach ($query->result_array() as $row) {
            $row["user_img"] = $this->commonfn->get_photo('p', $row["id"]);
            $row["artist_name"] = $row["firstname"] . " " . $row["lastname"];

            $row["i"] = $i;
            $row["total_songs"] = "";
            //$row["waveform_img"] = img_url() . "wave1.png";
            $row["link"] = base_url() . $row["profileLink"];
            $row["role"] = base_url() . $row["profile"];
            $output[] = $row;
            $i++;
        }

        return $output;
    }


    /*Pop artist*/
    /*Function for fetching popular artist*/
    function fetch_new_artist($cond = null, $limit = null, $orderby = null, $counter = null, $start_limit = null)
    {
        $this->load->model('commonfn');

        $query = $this->db->query("select distinct userId from user_roles_details");
        $string_user_ids = "(";
        foreach ($query->result_array() as $row) {
            foreach ($row as $key => $val) {
                $string_user_ids .= "$val,";
            }
        }
        $string_user_ids = rtrim($string_user_ids, ",");
        $string_user_ids .= ')';


        $output = [];

        if ($cond != null) {
            $cond = " WHERE u.status = 'y' AND u.id IN $string_user_ids AND " . $cond . "";
        } else {
            $cond = " WHERE u.status = 'y' AND u.id IN $string_user_ids";
        }
        //$cond = " WHERE u.status = 'y'";

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY u.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY u.id DESC";
        }

        $query = $this->db->query("SELECT u.id,u.firstname,u.lastname,u.profileLink FROM users as u " . $cond . " " . $orderby . " " . $limit . " ");
//                $last = $query->last_query();
//                var_dump($last);
//                exit;
        if ($counter != null) {
            return $query->num_rows();
        }
        //echo print_query();
        $i = 1;
        foreach ($query->result_array() as $row) {
            $row["user_img"] = $this->commonfn->get_photo('p', $row["id"]);
            $row["artist_name"] = $row["firstname"] . " " . $row["lastname"];
            //$row["waveform_img"] = img_url() . "wave1.png";
            $row["link"] = base_url() . $row["profileLink"];
            $row["role"] = base_url() . $row["profile"];
            $row["i"] = $i;
            $output[] = $row;
            $i++;
        }

        return $output;
    }

    /*popular playlist*/
    function fetch_popular_playlist($cond = null, $limit = null, $orderby = null, $counter = null, $start_limit = null)
    {
        $this->load->model('commonfn');

        $output = [];

        if ($cond != null) {
            $cond = " WHERE pl.status = 'y' AND " . $cond . "";
        } else {
            $cond = " WHERE pl.status = 'y' AND pl.userId = u.id";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY pl.plays,pl.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY pl.plays DESC,pl.id DESC";
        }

        $query = $this->db->query("SELECT pl.id,pl.name,pl.perLink,u.firstname,u.lastname,u.profileLink FROM playlist as pl,users as u " . $cond . " " . $orderby . " " . $limit . " ");
        if ($counter != null) {
            return $query->num_rows();
        }
        foreach ($query->result_array() as $row) {
            $row["user_img"] = $this->commonfn->get_photo('pl', $row["id"]);
            $row["artist_name"] = $row["name"];
            //$row["waveform_img"] = img_url() . "wave1.png";

            $row["link"] = base_url() . $row["profileLink"] . "/sets/" . $row["perLink"];
            $row["role"] = "playlist-detail";

            $output[] = $row;
            $i++;
        }

        return $output;
    }

    /*Function for fetching new playlist*/
    function fetch_new_playlist($cond = null, $limit = null, $orderby = null, $counter = null, $start_limit = null)
    {
        $this->load->model('commonfn');

        $output = [];

        if ($cond != null) {
            $cond = " WHERE pl.status = 'y' AND " . $cond . "";
        } else {
            $cond = " WHERE pl.status = 'y' AND pl.userId = u.id";
        }

        if ($limit != null) {
            $limit = " LIMIT " . $limit . " ";
        }

        if ($orderby != null) {
            $orderby = "ORDER BY pl.id DESC," . $orderby;
        } else {
            $orderby = "ORDER BY pl.id DESC";
        }

        $query = $this->db->query("SELECT pl.id,pl.name,pl.perLink,u.firstname,u.lastname,u.profileLink FROM playlist as pl,users as u " . $cond . " " . $orderby . " " . $limit . " ");
        //echo print_query();
        if ($counter != null) {
            return $query->num_rows();
        }
        foreach ($query->result_array() as $row) {
            $row["user_img"] = $this->commonfn->get_photo('pl', $row["id"]);
            $row["artist_name"] = $row["name"];
            //$row["waveform_img"] = img_url() . "wave1.png";

            $row["link"] = base_url() . $row["profileLink"] . "/sets/" . $row["perLink"];
            $row["role"] = "playlist-detail";

            $output[] = $row;
            $i++;
        }

        return $output;
    }

    /*function for fetch perticular genre of a song*/
    /*Fetch db records*/
    function track_db_genres($trackId)
    {
        //$user_exists_role = array();
        $track_exists_role = [];
        $session_user_id = $this->session->userdata('user')->id;
        $data = [];
        $this->db->select('genreId');

        $this->db->from('track_genre');
        $where = "(trackId='" . $trackId . "')";
        $this->db->where($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $data = $query->result_array();
            //print_r($data);
            foreach ($data as $row) {
                $track_exists_role[] = $row["genreId"];
            }
        }

        return $track_exists_role;
    }
}

?>