<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH.'controllers/trackdetail/cart.php'); //include controller
class Trackdetail extends MY_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('track_detail');
		$this->sess_id = $this->session->userdata('user')->id;
		$this->rec_to_dis = 3;
	}

	function index($profileLink,$trackLink,$action = NULL,$page = NULL)
	{
		$this->load->helper('url');
		$cm=$this->config->item('meta_keyword').',def,';
		$this->config->set_item('meta_keyword',$cm);
		$buynow_btn_enabled = false;
		$but_btn_id = "buy_track_btn";
		if($this->session->userdata('user')->id > 0)
		{
			$left_panel = true;
			$username = $this->session->userdata('user')->username;
		}
		else
		{	
			$left_panel = false;$username = null;
		}		
		/*$trackLink = sanitize($trackLink);
		$profileLink = sanitize($profileLink);*/
		if($this->sess_id > 0){
			$cond = "perLink = ".$this->db->escape($trackLink)." AND ((isPublic='n' and userId='".$this->sess_id."') or isPublic='y')";
		}
		else
			$cond = "isPublic = 'y' AND perLink = ".$this->db->escape($trackLink)."";

		$track_exists = getvalfromtbl("id","tracks",$cond,"single","");
			/*echo $this->db->last_query();
			var_dump($track_exists);exit;	*/

			if($track_exists <= 0)
			{
				redirect('home', 'refresh');		
			}
			$track_common_detail = $this->track_detail->get_song_details($track_exists);

			/*echo " ID ".$track_common_detail["userId"];*/

			/*dump($track_common_detail);*/
			$buyer_stripe_connected = getvalfromtbl("stripe_connect","users","id='".$track_common_detail["userId"]."'","single");
//			echo "<pre>";
//			var_dump($buyer_stripe_connected);
//			var_dump($track_common_detail["album_full_buyable"]);
//			exit();
			if(($track_common_detail["album_full_buyable"] == true || $track_common_detail["is_track_sellable"] == true) && $this->sess_id != $track_common_detail["userId"] && $buyer_stripe_connected == 'y')
			{
				$buynow_btn_enabled = true;
			}else if($this->sess_id == $track_common_detail["userId"] && $action == "buy"){

				$this->session->flashdata("notification","You can't buy your own song.");
				redirect(site_url(),"refresh");
			}
			/*var_dump($buynow_btn_enabled);exit;*/
			if($page != "" && $page > 0)
				$start_limit = (($page - 1)*$this->rec_to_dis) ;

			if($this->sess_id > 0){
				$is_liked = getvalfromtbl("id","likelog"," userId = '".$this->sess_id."'  AND trackId = '".$track_exists."'","single","");

				if($is_liked > 0)
					$like_class = "dislike_js";	
				else
					$like_class = "like_js";		
			}
			else{
				$like_class = "like_js";
			}

			if($action == "likes")
			{
				$data_array = $this->track_detail->fetch_track_likes($track_exists,'',$this->rec_to_dis);
				$temp_name = "general/box_3.html";
				$class_nm = "vedio-songs-list";
				$tabid = "like";
				$like_active_class = "true";
				$total_records = $this->track_detail->fetch_track_likes($track_exists,'','','','counter');
				$app_temp_name = "box3";			
				if($total_records > $this->rec_to_dis)
				{
					$loadmore = "true";
					$load_url = $profileLink."/".$trackLink."/likes";
					$load_cont = "#like";
					$load_tmpl = "box3";
				}else{
					$loadmore = "";
				}
				if($page != NULL && $page > 0)
				{
					$new_limit = $start_limit.",".$this->rec_to_dis;
					$last_page = ceil($total_records/$this->rec_to_dis);	
					$data_array = $this->track_detail->fetch_track_likes($track_exists,'',$new_limit);	
					$final_array["data"] = $data_array;	
					$final_array["page"] = $page+1;
					$final_array["last_page"] = $last_page;
					header('Content-Type: application/json');
					echo json_encode( $final_array );
					exit;
				}
				$final_array["data_array"] = $data_array;	
				$final_array["temp_name"] = $temp_name;	
				$final_array["class_nm"] = $class_nm;	
				$final_array["tabid"] = $tabid;	
				$final_array["app_temp_name"] = $app_temp_name;				
				$final_array["loadmore"] = $loadmore;
				$final_array["load_more_url"] = $load_url;
				$final_array["container"] = $load_cont;
				$final_array["template"] = $load_tmpl;			
				$ajax = $this->input->post("ajax");
				if($ajax == true){
					header('Content-Type: application/json');
					echo json_encode( $final_array );
					exit;
				}else{
					$temp = "general/box_3.html";
				}
			}	
			else if($action == "comments" && $this->input->post("ajax") == true){

				$tabid = "comment";
			//$like_active_class = "true";	

				if($page != NULL && $page > 0)
				{
					$new_limit = $start_limit.",".$this->rec_to_dis;
					$last_page = ceil($total_records/$this->rec_to_dis);	
					$data_array = $this->track_detail->fetch_track_comments($track_exists,"",$new_limit);	
					$final_array["data"] = $data_array;	
					$final_array["page"] = $page+1;
					$final_array["last_page"] = $last_page;
					header('Content-Type: application/json');
					echo json_encode( $final_array );
					exit;
				}

				$data_array = $this->track_detail->fetch_track_comments($track_exists);
				$app_temp_name = "comment_row";				
				$class_nm = "";				
				$final_array["data_array"] = $data_array;	
				$final_array["temp_name"] = $temp_name;	
				$final_array["class_nm"] = $class_nm;	
				$final_array["tabid"] = $tabid;	
				$final_array["app_temp_name"] = $app_temp_name;
				$final_array["loadmore"] = $loadmore;
				$final_array["load_more_url"] = $load_url;
				$final_array["container"] = $load_cont;
				$final_array["template"] = $load_tmpl;
				header('Content-Type: application/json');
				echo json_encode( $final_array );
				exit;
			}	
			else if($action == "comments"){
				/*$page*/
				$data_array = $this->track_detail->fetch_track_comments($track_exists,"",$this->rec_to_dis);		
				$tabid = "comment";
				$ajax = $this->input->post("ajax");
				$comment_active_class = "true";
				$total_records = $this->track_detail->fetch_track_comments($track_exists,'','','','counter');
				if($total_records > $this->rec_to_dis)
				{
					$loadmore = "true";
					$load_url = $profileLink."/".$trackLink."/comment";
					$load_cont = "#comment";
					$load_tmpl = "comment_row";
				}else{
					$loadmore = "";
				}						
				$temp = "general/comment_row.html";
			}
			else if($action == "buy"){
				$album_fully_buyable = "";

				if($track_common_detail["is_track_sellable"] == true && $buynow_btn_enabled == true)
				{}
				else if($track_common_detail["album_full_buyable"] == true && $buynow_btn_enabled == true)
				{
					$album_fully_buyable = $track_common_detail["album_full_buyable"];
					$but_btn_id = "album_track_btn";
				}
				else{
					$this->session->set_userdata('notification','Invalid request.');
					redirect(base_url().$profileLink."/".$trackLink,"refresh");
					exit;
				}
				$licence_type_list = array();
				$tabid = "buynow";
				$temp = "trackdetail/buynow.html";	
				$buy_now_active_class = "true";
			}
			else{
				$tabid = "biography";
				$temp = "trackdetail/biography.html";
				$temp_other = "true";
				$biography_active_class = "true";
			}
			/*print_r($track_common_detail);*/
			$sm = $this->track_detail->fetch_similar_tracks($track_exists);
			$profile_link_uid = getvalfromtbl("id","users","profileLink = '".$profileLink."'","single","");

			if($this->sess_id == $profile_link_uid)
			{
			/*user is vieving own profie*/
				$my_profile = "true";	
				$other_profile = "";			
			}else if($this->sess_id > 0){
			/*user is viewing another users profile*/
				$other_profile = "true";
				$is_following = getvalfromtbl("id","followinglog"," fromId = '".$this->sess_id."'  AND toId = '".$profile_link_uid."'","single","");

				if($is_following > 0)
				{
					$following = "true";
					$follow = "";
				}
				else{
					$following = "";
					$follow = "true";
				}			
			}
			else{
				/*user is not logged*/
				$my_profile = "";	
				$other_profile = "true";
				$following = "";
				$follow = "true";
			}	

			
			/*dump($sm);
			exit();*/
			$track_detail = array(			
			/*'comments' => $c,
			'likes' => $l,*/
			'similarmusic' => $sm,
			'cover_image' =>$track_common_detail["cover_image"],
			'track_title' => $track_common_detail["title"],
			'track_plays' => $track_common_detail["plays"],
			'track_likes' => $track_common_detail["likes"],
			'track_comments' => $track_common_detail["comments"],
			'track_shares' => $track_common_detail["shares"],
			'artist_profile_pic' =>$track_common_detail["artist_profile_pic"],
			'artist_firstname' => $track_common_detail["artist_firstname"],
			'artist_lastname' => $track_common_detail["artist_lastname"],
			'no_of_followers' => $track_common_detail["no_of_followers"],
			'no_of_songs' => $track_common_detail["no_of_songs"],
			'no_of_albums' => $track_common_detail["no_of_albums"],
			'waveimg'  =>  img_url()."wave-img.png",
			'track_timelength' => $track_common_detail["timelength"],
			'track_total_comments' => $track_common_detail["comments"],
			'track_total_likes' => $track_common_detail["likes"],
			'track_genre' => $track_common_detail["genre"],
			'track_release' => $track_common_detail["release_mm"]." ".$track_common_detail["release_dd"]." ".$track_common_detail["release_yy"],
			'track_label' => $track_common_detail["label"],
			'artist_city' => ($track_common_detail["city"] != "") ? $track_common_detail["city"]." , " : "",
			'artist_country' => $track_common_detail["country"],
			'profilelink' => $profileLink,
			'tracklink' => $trackLink,
			'url' => base_url(),
			'img' => img_url(),
			"my_profile" => $my_profile,
			"other_profile" => $other_profile,
			"follow" => $follow,
			"following" => $following,
			"trackId" => $track_exists,
			"data_array"=>$data_array,
			"current_url" => base_url().$profileLink."/".$trackLink,
			"tabid" =>$tabid,
			"class_nm"=>$class_nm,
			"like_active_class" => $like_active_class,
			"comment_active_class" => $comment_active_class,
			"biography_active_class" => $biography_active_class,			
			"buy_now_active_class" => $buy_now_active_class,
			'loadmore' => $loadmore,
			'load_more_url' => $load_url,
			'template' => $load_tmpl,
			'container' => $load_cont,
			"like_class" => $like_class,
			'licence_type_list'=>$licence_type_list,
			'album_fully_buyable'=>$album_fully_buyable,
			'head_title' => $head_title,
			'row_class' => $row_class,
			'buynow_btn_enabled' => $buynow_btn_enabled,
			'track_kbps' => $track_common_detail["track_kbps"],
			'but_btn_id'=>$but_btn_id
			);

		/*dump($track_detail);*/

$template_arry['MainPanel']="main.html";
$template_arry['leftPanel']="left_panel.html";
$template_arry['rightPanel']="right_panel.html";		
$template_arry['contentPanel']="trackdetail/trackdetail.html";

if($follow == "true")
	$template_arry['followRow'] = "profile/follow-row.html";

if($following == "true")
	$template_arry['folowingRow'] = "profile/following-row.html";

$template_arry['newComment'] = "general/new_comment.html";


if($temp_other && $temp_other == "true"){
	$template_arry['tabRender'] = $temp;
}
else if($action == "buy")
{
	$template_arry['tabRender'] = $temp;
	$template_arry['licenceType'] = "trackdetail/buynow_licence_row.html";	
}
else{
	$template_arry['tabRender'] = "general/tab_render.html";
	$template_arry['arrayData'] = $temp;		
}

if($loadmore == "true")
	$template_arry['loadMore'] = "general/loadmore.html";

$template_arry['similarRow']="trackdetail/similar_music_row.html";		
$template_arry['playerPanel']="player_panel.html";


$data1=get_template_content($template_arry,$track_detail);

$meta_array = array();

$meta_array[""] = "";
$meta_array[""] = "";
$meta_array[""] = "";


add_meta($meta_array);


$a['data'] = $data1;
$a['redirectURL']=base_url();
$a['current_tm']='profile';
$this->load->view('home',$a);
}

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */