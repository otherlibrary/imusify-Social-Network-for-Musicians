<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package        CodeIgniter
 * @subpackage    Rest Server
 * @category    Controller
 * @author        Phil Sturgeon
 * @link        http://philsturgeon.co.uk/code/
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class playlist_Api extends REST_Controller
{
    function playlist_create_post()
    {
        $this->load->model("playlist_model");
        $name         = $this->post('name');
        $randomnumber = $this->post('randomnumber');
        $this->load->model("playlist_model");
        if ($name != "" && $randomnumber != "") {
            $response = $this->playlist_model->insert_playlist($name, $randomnumber);
            if ( ! empty($response)) {
                if ($response["status"] == "success") {
                    $us_ar["status"] = "success";
                    $us_ar["msg"]    = "Playlist created successfully.";
                } else if ($response["status"] == "fail") {
                    $us_ar["status"] = "fail";
                    $us_ar["msg"]    = "Playlist not created.";
                }
                $this->response($us_ar, 200);
            } else {
                $this->response("", 404);
            }
        } else {
            $this->response(['error' => 'Please try again playlist create unsuccessfull.'], 404);
        }
    }
    /*unfollow track ends*/

}