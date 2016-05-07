<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
define('FILE_READ_MODE', 0644);
define('FILE_WRITE_MODE', 0666);
define('DIR_READ_MODE', 0755);
define('DIR_WRITE_MODE', 0777);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/

define('FOPEN_READ',							'rb');
define('FOPEN_READ_WRITE',						'r+b');
define('FOPEN_WRITE_CREATE_DESTRUCTIVE',		'wb'); // truncates existing file data, use with care
define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE',	'w+b'); // truncates existing file data, use with care
define('FOPEN_WRITE_CREATE',					'ab');
define('FOPEN_READ_WRITE_CREATE',				'a+b');
define('FOPEN_WRITE_CREATE_STRICT',				'xb');
define('FOPEN_READ_WRITE_CREATE_STRICT',		'x+b');


/* End of file constants.php */
/* Location: ./application/config/constants.php */

define('RES_DIR', 'assets');

/*Admin URL*/
define('ADMIN_MAIL', 'admin@imusify.com');


define('LINK_LOGIN_APP_KEY', 'LINK_LOGIN_APP_KEY');
define('LINK_LOGIN_APP_SECRET', 'LINK_LOGIN_APP_SECRET');






define('USER_PROFILE_PIC', 'users');
define('ALBUM_PIC', 'album');
define('TRACK_PIC', 'track');
define('PLAYSET_PIC', 'playset');

define('IMG_235', 235);
define('IMG_222', 222);

define('IMG_156', 156);
define('IMG_176', 176);
define('IMG_180', 180);
define('IMG_66', 66);
define('IMG_41', 41);
define('IMG_376', 376);

define('FOLLOW_LIMIT_DAY', 50);
define('FOLLOW_BLOCK_LIMIT_DAY', 150);
//define('FOLLOW_BLOCK_LIMIT_DAY', 15);


//define('FOLLOW_LIMIT_THREE_DAY', 150);

define('detail_external_video', ' has Posted this video.');

define('detail_external_image', ' has Posted this image.');
define('detail_internal_image', ' has Posted this image.');

define('detail_external_audio', ' has Posted this audio.');
define('detail_internal_audio', ' has uploaded this audio.');

define('detail_text', ' posted this text.');
define('detail_url', '  posted this url.');


define('STRIPE_API_KEY', 'sk_test_E6bSvTsqvSYhCU1hHPhrs3wj');
define('CLIENT_ID', 'ca_7TWyW8D6Ccd3hmC8VebDrBqZ5XaC5Ahg');
define('TOKEN_URI', 'https://connect.stripe.com/oauth/token');
define('AUTHORIZE_URI', 'https://connect.stripe.com/oauth/authorize');


define('STRIPE_PUBLIC_API_KEY', 'pk_test_ar32yaPnAs5OZjkS4Q0TwXvO');


define('MAX_UPLOAD_FILE_SIZE_ALLOWED', 5368709120);

define('ADMIN_SESSION_NAME', 'adminuser');
define('USER_SESSION_NAME', 'user');


define('LINK_LOGIN_URL', 'http://dev.imusify.com/');






