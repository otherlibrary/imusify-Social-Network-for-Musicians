<?php

require(APPPATH . '/libraries/REST_Controller.php');

/**
 * Created by igorko on 02.06.17.
 */
class Playlist extends REST_Controller
{
    public $result = [
        'code'    => 200,
        'message' => 'Success',
        'data'    => [],
        'debug'   => null,
    ];


    public function responseSuccess($data = [])
    {
        $this->result['data'] = $data;

        return $this->response($this->result);
    }

    public function responseError($message = 'Oops! Error!', $debug_data = [])
    {
        $this->result['code'] = 400;
        $this->result['Success'] = $message;
        $this->result['debug'] = $debug_data;

        return $this->response($this->result);
    }

    function __construct()
    {
        parent::__construct();

        $this->load->model('new/playlist_model');
    }

    function list_get()
    {
        $result = $this->playlist_model->get_all();
        if ($result) {
            $this->responseSuccess($result);
        } else {
            $this->responseError();
        }
    }

    function show_get($id)
    {
        $result = $this->playlist_model->get($id);
        if ($result) {
            $this->responseSuccess($result);
        } else {
            $this->responseError();
        }
    }

    function create_post()
    {
        $result = $this->playlist_model->insert($this->post());
        if ($result) {
            $this->responseSuccess();
        } else {
            $this->responseError();
        }
    }

    function update_post($id)
    {
        $result = $this->playlist_model->update($id, $this->post());
        if ($result) {
            $this->responseSuccess();
        } else {
            $this->responseError();
        }
    }

    function delete_post($id)
    {
        $result = $this->playlist_model->delete($id);
        if ($result) {
            $this->responseSuccess();
        } else {
            $this->responseError();
        }
    }
}