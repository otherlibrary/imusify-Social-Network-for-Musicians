<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Sekati CodeIgniter Asset Helper
 *
 * @package		Sekati
 * @author		Jason M Horwitz
 * @copyright	Copyright (c) 2013, Sekati LLC.
 * @license		http://www.opensource.org/licenses/mit-license.php
 * @link		http://sekati.com
 * @version		v1.2.7
 * @filesource
 *
 * @usage 		$autoload['config'] = array('asset');
 * 				$autoload['helper'] = array('asset');
 * @example		<img src="<?=asset_url();?>imgs/photo.jpg" />
 * @example		<?=img('photo.jpg')?>
 *
 * @install		Copy config/asset.php to your CI application/config directory
 *				& helpers/asset_helper.php to your application/helpers/ directory.
 * 				Then add both files as autoloads in application/autoload.php:
 *
 *				$autoload['config'] = array('asset');
 * 				$autoload['helper'] = array('asset');
 *
 *				Autoload CodeIgniter's url_helper in `application/config/autoload.php`:
 *				$autoload['helper'] = array('url');
 *
 * @notes		Organized assets in the top level of your CodeIgniter 2.x app:
 *					- assets/
 *						-- css/
 *						-- download/
 *						-- img/
 *						-- js/
 *						-- less/
 *						-- swf/
 *						-- upload/
 *						-- xml/
 *					- application/
 * 						-- config/asset.php
 * 						-- helpers/asset_helper.php
 */

/*
|--------------------------------------------------------------------------
| Custom Asset Paths for asset_helper.php
|--------------------------------------------------------------------------
|
| URL Paths to static assets library
|
*/

if(ADMIN_PANEL==true){
	$config['asset_path'] 		= 'resources/';
	$config['asset_admin_path'] 		= 'assets/';
	$config['css_path'] 		= 'resources/css/';
	$config['asset_css_path'] 		= 'assets/css/';
	$config['download_path'] 	= 'resources/download/';
	$config['less_path'] 		= 'resources/less/';
	$config['js_path'] 			= 'resources/js/';
	$config['asset_js_path'] 	= 'assets/js/';	
	$config['view_path'] 		= 'resources/views/';
	$config['img_path'] 		= 'resources/images/';
	$config['swf_path'] 		= 'resources/swf/';
	$config['upload_path'] 		= 'resources/upload/';
	$config['xml_path'] 		= 'resources/xml/';	

}
else{

	$config['asset_path'] 		= 'assets/';
	$config['css_path'] 		= 'assets/css/';
	$config['download_path'] 	= 'assets/download/';
	$config['less_path'] 		= 'assets/less/';
	$config['js_path'] 			= 'assets/js/';
	$config['view_path'] 			= 'assets/views/';
	$config['img_path'] 		= 'assets/images/';
	$config['swf_path'] 		= 'assets/swf/';
	$config['upload_path'] 		= 'assets/upload/';
	$config['xml_path'] 		= 'assets/xml/';

}


/* End of file asset.php */