<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "home";
$route['404_override'] = 'home';
$route['music'] = "home/index/music";
$route['instrumental'] = "home/index/instrumental";
$route['license'] = "home/index/license";

$route['webhooks'] = "webhooks/index";
$route['webhooks/cancel'] = "webhooks/cancel";
$route['webhooks/charge_successfully'] = "webhooks/charge_successfully";
$route['webhooks/charged_not_successfully'] = "webhooks/charged_not_successfully";
$route['csv_insert'] = "csv_insert/index";


$route['waveform/(:any)/(:any).json'] = "api/waveform_api/waveform/$1/$2/format/json";

$route['assets/upload/track/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/track/format/json";
$route['assets/upload/users/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/users/format/json";
$route['assets/upload/album/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/album/format/json";
$route['assets/upload/playlist/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/playlist/format/json";
$route['assets/upload/feed_images/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/feed_images/format/json";
$route['assets/upload/articles/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/articles/format/json";
$route['assets/upload/about/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/about/format/json";
$route['assets/images/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/format/json";

/*$route['assets/upload/track/(:any)/(:any)/(:any)'] = "api/images_api/all_photos/$1/$2/$3/track/format/json";
*/
$route['api/login'] = "api/login_api/login/format/json";

$route['api/change_password_api'] = "api/account_api/change_password/format/json";
//VAT Country code
$route['api/country'] = "api/vat_api/check_current_country/format/json";



$route['api/unread'] = "api/unread_msgs_api/get_unread_conv/format/json"; 
$route['api/users'] = "api/user_api/users/format/json";
//$route['message'] = "api/message_api/message/format/json";
$route['api/cp'] = "api/cp_api/cp/format/json";
$route['api/message'] = "api/message_api/message/format/json";
$route['api/ajaxusercheck'] = "api/user_api/chk_username/format/json";
$route['api/verifyuser/(:any)'] = "api/signup_api/verify_link_process/$1/format/json";
$route['api/forgotupwd'] = "api/login_api/login/format/json";
//user roles
$route['api/userrolesapi'] = "api/user_profile_api/user_roles/format/json";
//edit profile url
$route['api/editprofile'] = "api/user_profile_api/update_profile/format/json";
$route['api/changeusercover'] = "api/user_profile_api/update_profile_cover/format/json";
$route['api/invitefriends'] = "api/user_profile_api/invite_friends/format/json";
$route['api/article_image_upload'] = "api/article_api/upload_image/format/json";
$route['api/article_image_uploaded/(:num)'] = "api/article_api/article_images/id/$1/format/json";
$route['api/signup'] = "api/signup_api/signup/format/json"; 
$route['api/membershipcheck'] = "api/membership_api/check_current_plan/format/json"; 
$route['api/cancelsubscription'] = "api/membership_api/cancel_plan/format/json"; 
$route['api/uploadfiles'] = "api/upload_api/uploadfiles/format/json";


$route['api/check_user_connect'] = "api/stripe_api/user_connect_check/format/json";
$route['api/buy'] = "api/stripe_api/buytrack/format/json";
$route['api/albumbuy'] = "api/stripe_api/buyalbum/format/json";

$route['api/paypal/buy'] = "api/paypal_api/buytrack/format/json";
$route['api/paypal/process'] = "api/paypal_api/process/format/json";
$route['api/paypal/cancel'] = "api/paypal_api/cancel/format/json";

$route['api/cartitem'] = "api/cart_api/cart_item/$1/format/json";
$route['api/cart/(:any)'] = "api/cart_api/cart_content/$1/format/json";
$route['api/cart'] = "api/cart_api/cart_content/format/json";

$route['api/delete_conversation/(:num)'] = "api/message_api/delete_conversations/$1/format/json";

/* New Track Details API */
$route['api/track-details/likes-count/(:num)'] = 'new_api/trackdetails_api/likes_count/$1/format/json';
$route['api/track-details/shares-count/(:num)'] = 'new_api/trackdetails_api/shares_count/$1/format/json';
$route['api/track-details/plays-count/(:num)'] = 'new_api/trackdetails_api/plays_count/$1/format/json';
$route['api/track-details/comments-count/(:num)'] = 'new_api/trackdetails_api/comments_count/$1/format/json';
$route['api/track-details/playlists-count/(:num)'] = 'new_api/trackdetails_api/playlists_count/$1/format/json';
$route['api/track-details/downloads-count/(:num)'] = 'new_api/trackdetails_api/downloads_count/$1/format/json';
$route['api/track-details/common-details/(:num)'] = 'new_api/trackdetails_api/common_details/$1/format/json';
$route['api/track-details/show-tracks-by-tag/(:num)/(:any)'] = 'new_api/trackdetails_api/show_tracks_by_tag/$1/$2/format/json';

/* New Track Upload API */
$route['api/track-upload/upload-track-file'] = 'new_api/trackupload_api/upload_track/format/json';
$route['api/track-upload/upload-track-info'] = 'new_api/trackupload_api/upload_track_data/format/json';
$route['api/track-upload/upload-track-img'] = 'new_api/trackupload_api/upload_track_img/format/json';

/* New Users API */
$route['api/user/check-auth'] = 'api/user_api/check_auth/format/json';

$route['api/(:any)/(:any)'] = "api/track/user/id/$1/id2/$2/format/json";
$route['api/(:any)'] = "api/user_api/user/id/$1/format/json";
$route['api/linkcrawler'] = "api/feed_api/crawl_data/format/json";
$route['api/feed_save'] = "api/feed_api/save_feed/format/json";
$route['api/feed_highlight'] = "api/feed_api/highlight_feed/format/json";
$route['api/feed_new_comment'] = "api/following_api/new_comment/format/json";
$route['api/feed_delete_comment'] = "api/following_api/delete_comment/format/json";
$route['api/feed_repost'] = "api/following_api/feed_repost/format/json";
$route['api/feed_delete'] = "api/following_api/delete_feed/format/json";
$route['api/feed_edit'] = "api/following_api/edit_feed/format/json";

$route['api/notification/read'] = "api/unread_msgs_api/read_notifications/format/json";
$route['api/notification/list'] = "api/unread_msgs_api/notifications_list/format/json";

$route['api/new_artists'] = 'api/commonfn_api/new_artists/format/json';




/*Gift couppon apply*/
$route['api/apply_coupon_api'] = "api/coupon_api/apply_gift_coupon/format/json";
$route['save_track'] = "api/uploadsave_api/save_track_db";
$route['edit_save_track'] = "api/uploadsave_api/save_track_db/eaction/edit";
$route['track_delete'] = "api/uploadsave_api/delete_track/format/json";

$route['album_delete'] = "api/commonfn_api/album_delete/format/json";
$route['playlist_delete'] = "api/commonfn_api/playlist_delete/format/json";
$route['create_playlist'] = "api/playlist_api/playlist_create/format/json";
$route['increase_track_counter'] = "api/commonfn_api/track_counter_increase/format/json";
$route['savewaveform'] = "api/uploadsave_api/savewaveform/format/json";
$route['fetch_ta_info'] = "api/uploadsave_api/fetch_uainfo/format/json"; 
$route['ulogout'] = "api/logout/loggout/format/json";
$route['msg_alloweduserlist'] = "api/message_api/msgalloweduserlist/format/json";

$route['genre_list'] = "api/commonfn_api/genre_list/format/json";
$route['secondary_genre_list'] = "api/commonfn_api/sec_genre_list/format/json";
$route['browse_pop_users'] = "api/commonfn_api/browse_pop_users/format/json";
$route['upload_details'] = "api/commonfn_api/upload_list/format/json";
$route['usersong_json'] = "api/commonfn_api/get_usertracks_json/format/json";
$route['album_json'] = "api/commonfn_api/get_album_json/format/json";
$route['playlist_json'] = "api/commonfn_api/get_playlist_json/format/json";
$route['state_list'] = "api/commonfn_api/statelist/format/json";
$route['city_list'] = "api/commonfn_api/citylist/format/json";
$route['search'] = "api/commonfn_api/search_records/format/json";
$route['exp_search'] = "api/commonfn_api/exp_search_records/format/json";
$route['explore_search_tags'] = "api/commonfn_api/explore_tags_search/format/json";
$route['follow'] = "api/commonfn_api/follow/format/json";
$route['unfollow'] = "api/commonfn_api/unfollow/format/json";
$route['like_track'] = "api/commonfn_api/like_track/format/json";
$route['dislike_track'] = "api/commonfn_api/dislike_track/format/json";
$route['myplaylist'] = "api/commonfn_api/get_my_playset/format/json";
$route['newcomment'] = "api/comment_api/comment/format/json";
$route['playlist_songs'] = "api/commonfn_api/playset_songs/format/json";
$route['addtoplaylist'] = "api/commonfn_api/addtrack_to_playlist/format/json";
$route['removefromplaylist'] = "api/commonfn_api/removetrack_from_playlist/format/json";
$route['create_album'] = "api/commonfn_api/create_album/format/json";
$route['album_list'] = "api/commonfn_api/album_list";

$route['initial_playlist'] = "api/commonfn_api/initial_playlist_json/format/json";

$route['download/(:any)/(:any)'] = "download/index/$1/$2";
$route['download/(:any)'] = "download/index/$1";


$route['message/new'] = "message/message/new_message";
$route['message/(:any)'] = "message/message/index/$1";

$route['notifications'] = "notifications/notifications/index";

$route['cron/(:any)'] = "cron/$1";
$route['crop/index/(:any)'] = "crop/crop/index/$1";
$route['crop'] = "crop/crop";
$route['temp'] = "temp/index";
$route['temp/(:any)'] = "temp/$1";

//$route['msg_alloweduserlist/queries/(:any)'] = "api/message_api/msgalloweduserlist/$1/format/json";


//$route['Reset/(:any)'] = "api/login_api/reset_pwd/format/json";
$route['reset/(:any)'] = "reset/index/$1";
$route['payment'] = "payment/index";
//$route['api/resetpwdapi/'] = "api/login_api/login/";


/*share a link from following*/
$route['following/detail/(:num)'] = "following/following/detail/$1";
$route['following/feedcomment/(:num)/(:num)'] = "following/following/detail/$1/$2";
$route['following/(:num)'] = "following/following/index/$1";
/*share a link from following ends*/


/* $route['api'] = "api/index"; */
 

$route['admin'] = "admin/login";
$route['sign_up'] = "sign_up/sign_up";
$route['sign_up/(:any)'] = "sign_up/$1";
$route['sign_in/(:any)'] = "sign_in/sign_in";
$route['sign_in'] = "sign_in/sign_in";


/*admin membership*/
$route['admin/membership'] = "admin/membership";
$route['admin/detail/(:any)'] = "admin/detail/$1";

//$route['admin/membership/(:any)'] = "admin/membership/detail/$1";
/*admin membership*/

/*Front membership*/
$route['membership'] = "membership/membership";
$route['membership/(:any)'] = "membership/membership/detail/$1";
//http://localhost/imusify/api/cancelsubscription
$route['membership/cancelsubscription'] = "membership/membership/cancel";

/*Front membership*/

/*Front gift coupon*/
$route['giftcoupon'] = "giftcoupon/giftcoupon";
/*Front gift coupon ends*/

$route['fblogin'] = "sign_in/fblogin/login";
$route['fblogout'] = "sign_in/fblogin/logout";

$route['suc_fb_login'] = "sign_in/suc_fb_login";

$route['linklogin'] = "sign_in/linklogin";
$route['linklogin/(:any)'] = "sign_in/linklogin/$1";
//$route['linklogindata'] = "sign_in/linklogin/data";

$route['sclogin'] = "sign_in/sclogin";
$route['sclogin/(:any)'] = "sign_in/sclogin/$1";

$route['admin/(:any)'] = "admin/$1";

$route['trackdetail'] = "trackdetail/trackdetail";
$route['cart'] = "trackdetail/cart/cartindex";
$route['cart/(:any)'] = "trackdetail/cart/cartindex/$1";

$route['message'] = "message/message";
$route['following'] = "following/following";
$route['sets'] = "playlist/playlist";
$route['setup'] = "setup/index";
$route['invite'] = "invite/index";
$route['article/(:any)'] = "article/article/index/$1";

$route['sets/(:any)'] = "playlist/playlist/index/$1";
$route['(:any)/sets/(:any)'] = "playlist/playlist/index/$2/$1";
$route['liked'] = "liked/liked";

//$route['recommended'] = "browse/browse";
$route['browse'] = "browse/recommended";
$route['browse/(:any)/(:num)'] = "browse/recommended/index/$1/$2";
$route['browse/(:any)'] = "browse/recommended/index/$1";
$route['explore'] = "explore/explore";
$route['explore/(:any)/(:num)'] = "explore/explore/index/$1/$2";
/*$route['explore/(:any)'] = "explore/explore/index/$1";*/

/*Upload page*/
$route['upload'] = "upload/upload";
$route['upload/album/create'] = "upload/upload/index/album/create";
$route['upload/album/edit/(:num)'] = "upload/upload/albumedit/$1";
$route['upload/track/edit/(:num)'] = "upload/upload/trackedit/$1";

$route['upload/(:any)'] = "upload/upload/index/$1";
$route['upload/(:any)/(:num)'] = "upload/upload/index/$1/$2";

$route['account'] = "account/index";
$route['account/stripe/connect'] = "account/index/connect";
$route['account/(:any)'] = "account/index/$1";

$route['vat'] = "vat/index";
//$route['vat/stripe/connect'] = "vat/index/connect";
//$route['vat/update'] = "vat_api/update";
$route['api/vat/update'] = "api/vat_api/vat_update/format/json";

$route['stripeoperations/(:any)'] = "stripeoperations/$1";


/*Upload page*/

/*User profile page's links */
$route['(:any)/listened-songs'] = "profile/profile/index/$1/listened-songs/";
$route['(:any)/uploaded-songs'] = "profile/profile/index/$1/uploaded-songs/";
$route['(:any)/new-songs'] = "profile/profile/index/$1/new-songs/";
$route['(:any)/albums'] = "profile/profile/index/$1/albums/";
$route['(:any)/feed'] = "profile/profile/index/$1/feeds/";
$route['(:any)/followers'] = "profile/profile/index/$1/followers/";
$route['(:any)/following'] = "profile/profile/index/$1/followings/";

$route['(:any)/uploaded-songs/(:num)'] = "profile/profile/index/$1/uploaded-songs/$2";
$route['(:any)/popular-songs/(:num)'] = "profile/profile/index/$1/popular-songs/$2";
$route['(:any)/listened-songs/(:num)'] = "profile/profile/index/$1/listened-songs/$2";
$route['(:any)/new-songs/(:num)'] = "profile/profile/index/$1/new-songs/$2";
$route['(:any)/albums/(:num)'] = "profile/profile/index/$1/albums/$2";
$route['(:any)/followers/(:num)'] = "profile/profile/index/$1/followers/$2";
$route['(:any)/following/(:num)'] = "profile/profile/index/$1/followings/$2";
/*User profile page's links ends */
/*$route['data_api/(:any)/(:any)/(:any)'] = "api/data_api/output/$1/$2/$3/format/json"; 
$route['data_api/(:any)/(:any)'] = "api/data_api/output/$1/$2/tracktype/format/json"; 
$route['data_api/(:any)'] = "api/data_api/output/$1/usertype/format/json"; */

$route['data_api'] = "api/data_api/output_main/format/json";


//$route['user_json'] = "api/commonfn_api/get_user_json/format/json";
//$route['track_json'] = "api/commonfn_api/get_track_json/format/json";
$route['usertype_json'] = "api/commonfn_api/get_usertype_json/format/json";
//$route['stream/(:num)'] = "stream/index/$1";
//andy
$route['stream/(:any)'] = "stream/index/$1";
/*Testing json ends*/

/*Content pages*/
$route['content/(:any)'] = "content/content/index/$1";
$route['about'] = "about/about";
/*all album of user*/

//click to edit Profile page Show profile firstly
$route['(:any)/edit'] = "profile/edit_profile/index/$1";

/*Track Detail section*/
$route['(:any)/(:any)/comment/(:num)'] = "trackdetail/trackdetail/index/$1/$2/comments/$3";
$route['(:any)/(:any)/likes/(:num)'] = "trackdetail/trackdetail/index/$1/$2/likes/$3";
$route['(:any)/(:any)/comment'] = "trackdetail/trackdetail/index/$1/$2/comments";
$route['(:any)/(:any)/buy'] = "trackdetail/trackdetail/index/$1/$2/buy/$3";
$route['(:any)/(:any)/likes'] = "trackdetail/trackdetail/index/$1/$2/likes";
$route['(:any)/(:any)'] = "trackdetail/trackdetail/index/$1/$2";

/*Track Detail section ends*/

$route['(:any)'] = "profile/profile/index/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */