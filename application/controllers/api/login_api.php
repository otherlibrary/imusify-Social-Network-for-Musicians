<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Example
 *
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array.
 *
 * @package        CodeIgniter
 * @subpackage     Rest Server
 * @category       Controller
 * @author         Phil Sturgeon
 * @link           http://philsturgeon.co.uk/code/
 */

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
require APPPATH . '/libraries/REST_Controller.php';

class Login_Api extends REST_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('Ilogin');
    }

    function login_post()
    {
        return $this->response('good', 200);
        if ($this->post('fp_code')) {

            $this->form_validation->set_rules('rst_password', 'Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('cm_rst_password', 'Reset Password', 'trim|required|xss_clean');

            if ($this->form_validation->run() == false) {
                //$this->load->view('myform');
                $this->response(null, 400);
            } else {

                $type = ($this->post('type')) ? $this->post('type') : "";

                $user = $this->Ilogin->reset_password($this->post('fp_code'), $this->post('rst_password'), $type);
                if ($user) {
                    $this->response($user, 200); // 200 being the HTTP response code
                } else {
                    $this->response(['error' => 'Something went wrong , try again later'], 404);
                }
            }
            /*if($this->post('rst_password') !== $this->post('cm_rst_password'))
            {
                $this->response(NULL, 400);
            }*/
        } else if ($this->post('email')) {
            if ($this->post('user')) {
                $user = $this->Ilogin->forgot_password($this->post('email'), "true");
            } else {
                $user = $this->Ilogin->forgot_password($this->post('email'));
            }

            if ($user) {
                $this->response(['success' => 'Please check mail for reset password.'], 200); // 200 being the HTTP response code
            } else {
                $this->response(['error' => 'Email Address not associated with any account'], 404);
            }
        } else {
            //echo "  ".$this->post('username');
            //echo "  ".$this->post('password');
//				if ($this->post('email')){
//                                    
//                                    
//                                }
            $this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean');
            //$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

            if ($this->form_validation->run() == false) {
                //$this->load->view('myform');
                $this->response(null, 400);
            } else {

                $user = $this->Ilogin->login(
                    $this->post('username'),
                    $this->post('password'),
                    $this->post('rememberme'),
                    null,
                    $this->post('type'),
                    $this->post()
                );

                if ($user) {
                    $this->response($user, 200); // 200 being the HTTP response code
                } else {
                    $this->response(['error' => 'User could not be found'], 404);
                }

            }
        }
    }
}//class over
	