<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class user_api extends REST_Controller
{
    /**
     * Adds the list of roles to specified user
     * [POST /api/user/set-roles]
     */
    public function set_roles_post()
    {
        $this->load->library('UserService');

        $this->form_validation->set_rules('user_roles[]', 'user_roles[]', 'required');
        if ($this->form_validation->run() == false) {
            $response = [
                'errors' => $this->validation_errors(),
            ];
            $this->response($response, 200);
        }

        $result = [
            'status' => 'fail',
            'msg' => 'Whoops, looks like something went wrong!',
        ];
        $userData = $this->session->userdata('user');
        $userId = $userData->id;
        if ($userId) {
            $this->userservice->resetRoles($userId);
            $roles = $this->userservice->addRoles($userId, $this->post('user_roles'));
            if ($roles) {
                $result = [
                    'status' => 'success',
                    'msg' => 'Roles successfully added.',
                ];
            }
        }

        $this->response($result, 200);
    }

    public function get_info()
    {

    }
}