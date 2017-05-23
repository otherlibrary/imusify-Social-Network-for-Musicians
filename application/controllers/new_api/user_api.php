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

    /**
     * @param int $userId
     * [GET /api/user/get-info/{id}]
     */
    public function get_info_get($userId)
    {
        $this->load->library('UserService');

        $result = $this->userservice->getUserInfo($userId);

        echo json_encode($result);
    }

    /**
     * @param int $userId
     * [GET /api/user/get-info-for-edit/{id}]
     */
    public function get_info_for_edit_get($userId)
    {
        $this->load->library('UserService');

        $result = $this->userservice->getUserInfoForEdit($userId);

        echo json_encode($result);
    }

    /**
     * [POST /api/user/edit-info]
     */
    public function edit_info_post()
    {
        $this->load->library('UserService');
        $this->load->library('UploadService');

        $date = json_decode($this->post('birthdate'));
        $userData = $this->session->userdata('user');
        $userId = $this->post('user_id');
        if($userData->id == $userId && $userId) {
            $this->userservice->editUserInfo(
                $userId,
                $this->post('firstname'),
                $this->post('lastname'),
                $this->post('weburl'),
                $this->post('countryId'),
                $this->post('stateId'),
                $this->post('cityId'),
                $this->post('description'),
                $date->date->day,
                $date->date->month,
                $date->date->year
            );

            $this->uploadservice->uploadUserImage($userId, $_POST['image']);

            $this->response(['status' => 'success'], 200);
        } else {
            $this->response(['status' => 'error', 'msg' => 'You can modify only your profile.']);
        }
    }
}