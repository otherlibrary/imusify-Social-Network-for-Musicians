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

class User_Api extends REST_Controller
{
    function playlist_get()
    {

        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
        $users = [
            1 => ['id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'],
            2 => ['id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'],
            3 => ['id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', ['hobbies' => ['fartings', 'bikes']]],
        ];

        $user = @$users[$this->get('id')];

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(['error' => 'User could not be found'], 404);
        }
    }

    function user_get()
    {
        //modela
        //

        if (!$this->get('id')) {
            $this->response(NULL, 400);
        }

        // $user = $this->some_model->getSomething( $this->get('id') );
        $users = [
            1 => ['id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com', 'fact' => 'Loves swimming'],
            2 => ['id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com', 'fact' => 'Has a huge face'],
            3 => ['id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => 'Is a Scott!', ['hobbies' => ['fartings', 'bikes']]],
        ];

        $user = @$users[$this->get('id')];

        if ($user) {
            $this->response($user, 200); // 200 being the HTTP response code
        } else {
            $this->response(['error' => 'User could not be found'], 404);
        }
    }

    function user_post()
    {

        //$this->some_model->updateUser( $this->get('id') );
        $message = ['id' => $this->get('id'), 'name' => $this->post('name'), 'email' => $this->post('email'), 'message' => 'ADDED!'];

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function user_delete()
    {
        //$this->some_model->deletesomething( $this->get('id') );
        $message = ['id' => $this->get('id'), 'message' => 'DELETED!'];

        $this->response($message, 200); // 200 being the HTTP response code
    }

    function users_get()
    {

        //$users = $this->some_model->getSomething( $this->get('limit') );
        $users = [
            ['id' => 1, 'name' => 'Some Guy', 'email' => 'example1@example.com'],
            ['id' => 2, 'name' => 'Person Face', 'email' => 'example2@example.com'],
            3 => ['id' => 3, 'name' => 'Scotty', 'email' => 'example3@example.com', 'fact' => ['hobbies' => ['fartings', 'bikes']]],
        ];

        if ($users) {
            $this->response($users, 200); // 200 being the HTTP response code
        } else {
            $this->response(['error' => 'Couldn\'t find any users!'], 404);
        }
    }


    public function send_post()
    {
        var_dump($this->request->body);
    }


    public function send_put()
    {
        var_dump($this->put('foo'));
    }

    function chk_username_get($username = NULL)
    {

        $this->load->model('ilogin');

        if ($this->get('fieldValue')) {

            if ($this->get('fieldId') == 'uname')
                $user = $this->ilogin->user_exist_check(null, null, $this->get('fieldValue'), true, $this->get('fieldId'));
            else
                $user = $this->ilogin->user_exist_check($this->get('fieldValue'), null, null, true, $this->get('fieldId'));

            if ($user) {
                $this->response($user, 200); // 200 being the HTTP response code
            } else {
                $this->response(['error' => 'Error in validating user.'], 404);
            }
        }

    }

    /**
     * Returns the JSON with user data from session
     * [GET api/user/check-auth]
     */
    function check_auth_get()
    {
        $userData = $this->session->userdata('user');

        $result = [
            'user_id' => $userData->id,
            'user_type' => $userData->usertype,
            'username' => $userData->username,
            'loggedin' => $userData->loggedin,
        ];

        echo json_encode($result);
    }
}