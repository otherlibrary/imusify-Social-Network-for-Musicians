<?php if ( ! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/*
|--------------------------------------------------------------------------
| Base Site URL
|--------------------------------------------------------------------------
|
| URL to your CodeIgniter root. Typically this will be your base URL,
| WITH a trailing slash:
|
|	http://example.com/
|
| If this is not set then CodeIgniter will guess the protocol, domain and
| path to your installation.
|
*/
$config['base_url'] = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "https" : "http");
$config['base_url'] .= "://" . $_SERVER['HTTP_HOST'];
$config['base_url'] .= str_replace(basename($_SERVER['SCRIPT_NAME']), "", $_SERVER['SCRIPT_NAME']);

//error_reporting(E_ALL);
error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

define("SITE_ENV", 'development');

$curl     = $_SERVER['REQUEST_URI'];
$curl_arr = explode("/", $curl);
$curl_arr = array_filter($curl_arr);
$admin    = false;
if ( ! empty($curl_arr)) {
    if (in_array("admin", $curl_arr)) {
        $admin = true;
    }
}
define('ADMIN_DIR', 'admin');

// set default (and only) controller to admin if so
if ($admin == true) {

    define('ADMIN_PANEL', true);

    define('SITE_NM', "Imusify Admin");

    /*$config['asset_admin_js'] = array('jquery.validationEngine-en.js','jquery.validationEngine.js','croppic.min.js');
    $config['asset_admin_css'] = array('validationEngine.jquery.css','croppic.css');*/
    $config['asset_css']  = ['js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css'];
    $config['header_css'] = [
        'neon.css',
        'custom.css',
        'tagmanager.css'
        /*,'jquery.tagsinput.css'*/,
        'font-icons/entypo/css/entypo.css',
        'font-icons/font-awesome/css/font-awesome.min.css',
        'validationEngine.jquery.css',
        'croppic.css',
    ];
    $config['header_js']  = [
        'jquery-1.10.2.min.js',
        'jquery.validate.min.js',
        'gsap/main-gsap.js',
        'jquery-ui/js/jquery-ui-1.10.3.minimal.min.js',
        'bootstrap.min.js',
        'jquery.validationEngine-en.js',
        'jquery.validationEngine.js',
        'croppic.min.js',
        'tagmanager.js',
        /*'jquery.tagsinput.js',*/
        'joinable.js',
        'resizeable.js',
        'neon-api.js',
        'bootstrap-switch.min.js',
        'neon-chat.js',
        'neon-custom.js',
        'neon-demo.js',
        'jquery.sparkline.min.js',
        'jquery.dataTables.min.js',
        'datatables/TableTools.min.js',
        'dataTables.bootstrap.js',
        'datatables/jquery.dataTables.columnFilter.js',
        'datatables/lodash.min.js',
        'datatables/responsive/js/datatables.responsive.js',
        'select2/select2.min.js',
        'jquery.multi-select.js',
    ];

    $config['title']            = 'Imusify';
    $config['meta_title']       = $config['title'];
    $config['meta_description'] = 'Political website, Liberal, progressive, blog, posts,';
    $config['meta_keyword']     = 'abc';
    $config['content_type']     = 'text/html; charset=utf-8';
    $config['meta']             = [];
} else {

    define('SITE_NM', "Imusify");

    //'jquery.effects.core.js','jquery.effects.slide.js'
    define('ADMIN_PANEL', false);
    $config['asset_css']  = [];
    $config['header_css'] = [
        'bootstrap-switch.css',
        'bootstrap.min.css',
        'jquery.tagsinput.css',
        'bootstrap-theme.min.css',
        'jquery-ui.css',
        'select2.css',
        'enjoyhint.css',
        'style.css',
        'audioplayer.css',
        'scrollbar.css',
        'style_set.css',
        'validationEngine.jquery.css',
        'toastr.css',
        'audio',
        'tagmanager.css',
        'croppic.css',
        'ion.rangeSlider.css',
        'ion.rangeSlider.skinHTML5.css',
        'jquery-te-1.4.0.css',
    ];

    //$config['header_js']  = array('jquery-1.10.2.min.js','jquery-ui.min.js','jquery.form.js','croppic.min.js','jquery.history.js','jquery.tmpl.js','jquery.tagsinput.js','tmpload.js','bootstrap.min.js','bootstrap-editable.js','bootstrap-switch.js','select2/select2.min.js','jquery.scrollbar.min.js','audioplayer.dev.js','formdata.js','jsapi','imagesloaded.pkgd.js','masonry.pkgd.min.js','imusify.player.api.js','jquery.gallery.js','sc_js1.js','sc_js.js','ion.rangeSlider.js','sc-player.js','soundcloud.player.api.js','jquery.tooltipster.js','enjoyhint.js','pace.js','app.js','jquery.validationEngine-en.js','jquery.validationEngine.js','toastr.min.js','jquery.imagesloaded.js','jquery.customSelect.js','jquery.wookmark.js','html5.js','modernizr.custom.53451.js','jquery.ba-throttle-debounce.js','jquery.iframe-transport.js','jquery.fileupload.js','jquery.fileupload-process.js','typeahead.bundle.js','tagmanager.js','jquery.scrollTo.js','soundcloud-waveform.js','waveform.js');
    $config['header_js'] = [
        'jquery-1.10.2.min.js',
        'jquery-ui.min.js',
        'jquery.form.js',
        'croppic.min.js',
        'jquery.history.js',
        'jquery.tmpl.js',
        'jquery.tagsinput.js',
        'tmpload.js',
        'bootstrap.min.js',
        'bootstrap-editable.js',
        'bootstrap-switch.js',
        'select2/select2.min.js',
        'jquery.scrollbar.min.js',
        'audioplayer.dev.js',
        'formdata.js',
        'jsapi',
        'imagesloaded.pkgd.js',
        'masonry.pkgd.min.js',
        'imusify.player.api.js',
        'jquery.gallery.js',
        'sc_js1.js',
        'ion.rangeSlider.js',
        'sc-player.js',
        'soundcloud.player.api.js',
        'jquery.tooltipster.js',
        'enjoyhint.js',
        'pace.js',
        'app.js',
        'jquery.validationEngine-en.js',
        'jquery.validationEngine.js',
        'toastr.min.js',
        'jquery.imagesloaded.js',
        'jquery.customSelect.js',
        'jquery.wookmark.js',
        'html5.js',
        'modernizr.custom.53451.js',
        'jquery.ba-throttle-debounce.js',
        'jquery.iframe-transport.js',
        'jquery.fileupload.js',
        'jquery.fileupload-process.js',
        'typeahead.bundle.js',
        'tagmanager.js',
        'jquery.scrollTo.js',
        'waveform.js',
        'id3-minimized.js',
        'jquery-te-1.4.0.min.js',
    ];//remove soundclound-waveform.js, 'sc_js.js'

    $config['title']            = 'Imusify';
    $config['meta_title']       = $config['title'];
    $config['meta_description'] = 'Political website, Liberal, progressive, blog, posts,';
    $config['meta_keyword']     = 'abc';
    $config['content_type']     = 'text/html; charset=utf-8';
    $config['meta']             = [];
}

if (isset($_POST['ajax']) && $_POST['ajax'] == true) {
    $config['ajax'] = true;
} else {
    $config['ajax'] = false;
}
/*
|--------------------------------------------------------------------------
| Index File
|--------------------------------------------------------------------------
|
| Typically this will be your index.php file, unless you've renamed it to
| something else. If you are using mod_rewrite to remove the page set this
| variable so that it is blank.
|
*/
$config['index_page'] = '';

/*
|--------------------------------------------------------------------------
| URI PROTOCOL
|--------------------------------------------------------------------------
|
| This item determines which server global should be used to retrieve the
| URI string.  The default setting of 'AUTO' works for most servers.
| If your links do not seem to work, try one of the other delicious flavors:
|
| 'AUTO'			Default - auto detects
| 'PATH_INFO'		Uses the PATH_INFO
| 'QUERY_STRING'	Uses the QUERY_STRING
| 'REQUEST_URI'		Uses the REQUEST_URI
| 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
|
*/
$config['uri_protocol'] = 'AUTO';

/*
|--------------------------------------------------------------------------
| URL suffix
|--------------------------------------------------------------------------
|
| This option allows you to add a suffix to all URLs generated by CodeIgniter.
| For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/urls.html
*/

$config['url_suffix'] = '';

/*
|--------------------------------------------------------------------------
| Default Language
|--------------------------------------------------------------------------
|
| This determines which set of language files should be used. Make sure
| there is an available translation if you intend to use something other
| than english.
|
*/
$config['language'] = 'english';

/*
|--------------------------------------------------------------------------
| Default Character Set
|--------------------------------------------------------------------------
|
| This determines which character set is used by default in various methods
| that require a character set to be provided.
|
*/
$config['charset'] = 'UTF-8';

/*
|--------------------------------------------------------------------------
| Enable/Disable System Hooks
|--------------------------------------------------------------------------
|
| If you would like to use the 'hooks' feature you must enable it by
| setting this variable to TRUE (boolean).  See the user guide for details.
|
*/
$config['enable_hooks'] = false;

/*
|--------------------------------------------------------------------------
| Class Extension Prefix
|--------------------------------------------------------------------------
|
| This item allows you to set the filename/classname prefix when extending
| native libraries.  For more information please see the user guide:
|
| http://codeigniter.com/user_guide/general/core_classes.html
| http://codeigniter.com/user_guide/general/creating_libraries.html
|
*/
$config['subclass_prefix'] = 'MY_';

/*
|--------------------------------------------------------------------------
| Allowed URL Characters
|--------------------------------------------------------------------------
|
| This lets you specify with a regular expression which characters are permitted
| within your URLs.  When someone tries to submit a URL with disallowed
| characters they will get a warning message.
|
| As a security measure you are STRONGLY encouraged to restrict URLs to
| as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
|
| Leave blank to allow all characters -- but only if you are insane.
|
| DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
|
*/
$config['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

/*
|--------------------------------------------------------------------------
| Enable Query Strings
|--------------------------------------------------------------------------
|
| By default CodeIgniter uses search-engine friendly segment based URLs:
| example.com/who/what/where/
|
| By default CodeIgniter enables access to the $_GET array.  If for some
| reason you would like to disable it, set 'allow_get_array' to FALSE.
|
| You can optionally enable standard query string based URLs:
| example.com?who=me&what=something&where=here
|
| Options are: TRUE or FALSE (boolean)
|
| The other items let you set the query string 'words' that will
| invoke your controllers and its functions:
| example.com/index.php?c=controller&m=function
|
| Please note that some of the helpers won't work as expected when
| this feature is enabled, since CodeIgniter is designed primarily to
| use segment based URLs.
|
*/
$config['allow_get_array']      = true;
$config['enable_query_strings'] = false;
$config['controller_trigger']   = 'c';
$config['function_trigger']     = 'm';
$config['directory_trigger']    = 'd'; // experimental not currently in use

/*
|--------------------------------------------------------------------------
| Error Logging Threshold
|--------------------------------------------------------------------------
|
| If you have enabled error logging, you can set an error threshold to
| determine what gets logged. Threshold options are:
| You can enable error logging by setting a threshold over zero. The
| threshold determines what gets logged. Threshold options are:
|
|	0 = Disables logging, Error logging TURNED OFF
|	1 = Error Messages (including PHP errors)
|	2 = Debug Messages
|	3 = Informational Messages
|	4 = All Messages
|
| For a live site you'll usually only enable Errors (1) to be logged otherwise
| your log files will fill up very fast.
|
*/
$config['log_threshold'] = 0;

/*
|--------------------------------------------------------------------------
| Error Logging Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| application/logs/ folder. Use a full server path with trailing slash.
|
*/
$config['log_path'] = '';

/*
|--------------------------------------------------------------------------
| Date Format for Logs
|--------------------------------------------------------------------------
|
| Each item that is logged has an associated date. You can use PHP date
| codes to set your own date formatting
|
*/
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
|--------------------------------------------------------------------------
| Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the default
| system/cache/ folder.  Use a full server path with trailing slash.
|
*/
$config['cache_path'] = '';

/*
|--------------------------------------------------------------------------
| Encryption Key
|--------------------------------------------------------------------------
|
| If you use the Encryption class or the Session class you
| MUST set an encryption key.  See the user guide for info.
|
*/
$config['encryption_key'] = 'L7VmiNIgofINb1O11sEJoESUnprs2x8x';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_cookie_name'		= the name you want for the cookie
| 'sess_expiration'			= the number of SECONDS you want the session to last.
|   by default sessions last 7200 seconds (two hours).  Set to zero for no expiration.
| 'sess_expire_on_close'	= Whether to cause the session to expire automatically
|   when the browser window is closed
| 'sess_encrypt_cookie'		= Whether to encrypt the cookie
| 'sess_use_database'		= Whether to save the session data to a database
| 'sess_table_name'			= The name of the session database table
| 'sess_match_ip'			= Whether to match the user's IP address when reading the session data
| 'sess_match_useragent'	= Whether to match the User Agent when reading the session data
| 'sess_time_to_update'		= how many seconds between CI refreshing Session Information
|
*/
$config['sess_cookie_name']     = 'ci_cookies';
$config['sess_expiration']      = 0;
$config['sess_expire_on_close'] = true;
$config['sess_encrypt_cookie']  = true;
$config['sess_use_database']    = true;
$config['sess_table_name']      = 'ci_sessions';
$config['sess_match_ip']        = false;
$config['sess_match_useragent'] = true;
$config['sess_time_to_update']  = 604800;

/*
|--------------------------------------------------------------------------
| Cookie Related Variables
|--------------------------------------------------------------------------
|
| 'cookie_prefix' = Set a prefix if you need to avoid collisions
| 'cookie_domain' = Set to .your-domain.com for site-wide cookies
| 'cookie_path'   =  Typically will be a forward slash
|
*/
$config['cookie_prefix'] = '';
$config['cookie_domain'] = '';
$config['cookie_path']   = '/';

/*
|--------------------------------------------------------------------------
| Global XSS Filtering
|--------------------------------------------------------------------------
|
| Determines whether the XSS filter is always active when GET, POST or
| COOKIE data is encountered
|
*/
$config['global_xss_filtering'] = false;

/*
|--------------------------------------------------------------------------
| Cross Site Request Forgery
|--------------------------------------------------------------------------
| Enables a CSRF cookie token to be set. When set to TRUE, token will be
| checked on a submitted form. If you are accepting user data, it is strongly
| recommended CSRF protection be enabled.
|
| 'csrf_token_name' = The token name
| 'csrf_cookie_name' = The cookie name
| 'csrf_expire' = The number in seconds the token should expire.
*/
$config['csrf_protection']  = false;
$config['csrf_token_name']  = 'csrf_test_name';
$config['csrf_cookie_name'] = 'csrf_cookie_name';
$config['csrf_expire']      = 7200;

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not 'echo' any values with compression enabled.
|
*/
$config['compress_output'] = false;

/*
|--------------------------------------------------------------------------
| Master Time Reference
|--------------------------------------------------------------------------
|
| Options are 'local' or 'gmt'.  This pref tells the system whether to use
| your server's local time as the master 'now' reference, or convert it to
| GMT.  See the 'date helper' page of the user guide for information
| regarding date handling.
|
*/
$config['time_reference'] = 'local';

/*
|--------------------------------------------------------------------------
| Rewrite PHP Short Tags
|--------------------------------------------------------------------------
|
| If your PHP installation does not have short tag support enabled CI
| can rewrite the tags on-the-fly, enabling you to utilize that syntax
| in your view files.  Options are TRUE or FALSE (boolean)
|
*/
$config['rewrite_short_tags'] = false;

$config['max_width']  = '2048';
$config['max_height'] = '2048';
/*
|--------------------------------------------------------------------------
| Reverse Proxy IPs
|--------------------------------------------------------------------------
|
| If your server is behind a reverse proxy, you must whitelist the proxy IP
| addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
| header in order to properly identify the visitor's IP address.
| Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
|
*/
$config['proxy_ips'] = '';



/* End of file config.php */
/* Location: ./application/config/config.php */