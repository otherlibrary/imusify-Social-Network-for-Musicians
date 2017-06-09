<?php
require(APPPATH . '/libraries/REST_Controller.php');

/**
 * Created by igorko on 02.06.17.
 */
class Playlist extends REST_Controller
{
    function __construct()
    {
        parent::__construct();

        $this->load->model('new/playlist_model');
        $this->load->library('ApiService');
    }

    function list_get()
    {
        $result = $this->playlist_model->get_all();
        if ($result) {
            return $this->response($this->apiservice->responseSuccess($result));
        } else {
            return $this->response($this->apiservice->responseError());
        }
    }

    function show_get($id)
    {
        $result = $this->playlist_model->get($id);
        if ($result) {
            return $this->response($this->apiservice->responseSuccess($result));
        } else {
            return $this->response($this->apiservice->responseError());
        }
    }

    function create_post()
    {
        $this->load->library('form_validation');
        $this->lang->load('error');

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|callback_alpha_dash_spaces|min_length[1]|max_length[255]');
        $this->form_validation->set_message('alpha_dash_spaces', 'The %s field may only contain letters, digits, dash and underscore.');

        if ($this->form_validation->run() == false) {
            return $this->response($this->apiservice->responseError(
                empty($this->validation_errors()[0])
                    ?
                    [$this->lang->line('error_request_empty')]
                    :
                    $this->validation_errors())
            );
        }

        $data = [
            'name'    => $this->post('name'),
            'picture' => 'uploads/default_playlist_cover.png',
        ];

        if (isset($_FILES['picture']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $filename = uniqid() . '.' . pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $upload_path = 'uploads/playlist';
            $this->load->library('upload', [
                'upload_path'   => asset_path() . $upload_path,
                'file_name'     => $filename,
                'allowed_types' => "gif|jpg|png|jpeg",
                'max_size'      => "3072000", // 3 MB (3×1000×1024)
                'max_height'    => "1024",
                'max_width'     => "1024",
            ]);
            if ( ! $this->upload->do_upload('picture')) {
                return $this->response($this->apiservice->responseError($this->upload->display_errors(null, null)));
            }
            $data['picture'] = $upload_path . '/' . $filename;
        }

        $user_data = $this->session->userdata('user');
        if (empty($user_data)) {
            // return $this->response($this->apiservice->responseError($this->lang->line('error_user_from_session')));
            $user_data['id'] = 1;
        }
        $data['userId'] = $user_data['id'];

        try {
            $new_id = $this->playlist_model->insert($data);
        } catch (Exception $e) {
            if (ENVIRONMENT != 'production') {
                $debug = array_slice($e->getTrace(), 0, 5);
            }
            return $this->response($this->apiservice->responseError($e->getMessage(), $debug));
        }

        return $this->response($this->apiservice->responseSuccess(['id' => $new_id]));
    }

    function update_post($id)
    {
        $record = $this->playlist_model->get($id);
        if (empty($record)) {
            return $this->response($this->apiservice->responseError($this->lang->line('error_db_record_not_found')));
        }
        $this->load->library('form_validation');
        $this->lang->load('error');

        $this->form_validation->set_rules('name', 'Name', 'trim|xss_clean|callback_alpha_dash_spaces|min_length[1]|max_length[255]');
        $this->form_validation->set_rules('no_of_track', 'Number of tracks', 'trim|xss_clean|integer|is_natural|less_than[999]');
        $this->form_validation->set_rules('status', 'Status', 'trim|xss_clean|integer|is_natural_no_zero|less_than[3]');
        $this->form_validation->set_rules('plays', 'Number of plays', 'trim|xss_clean|integer|is_natural');
        $this->form_validation->set_rules('likes', 'Likes', 'trim|xss_clean|integer|is_natural');
        $this->form_validation->set_rules('shares', 'Shares', 'trim|xss_clean|integer|is_natural');
        $this->form_validation->set_rules('comments', 'Comments', 'trim|xss_clean|integer|is_natural');

        $this->form_validation->set_message('alpha_dash_spaces', 'The %s field may only contain letters, digits, dash and underscore.');

        if ($this->form_validation->run() == false) {
            return $this->response($this->apiservice->responseError(empty($this->validation_errors()[0]) ? [$this->lang->line('error_request_empty')] : $this->validation_errors()));
        }

        $data = $this->apiservice->extract_req_data($this->post(), ['name', 'no_of_track', 'status', 'plays', 'like', 'share', 'comments']);

        if (isset($_FILES['picture']) && is_uploaded_file($_FILES['picture']['tmp_name'])) {
            $filename = uniqid() . '.' . pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
            $upload_path = 'uploads/playlist';
            $this->load->library('upload', [
                'upload_path'   => asset_path() . $upload_path,
                'file_name'     => $filename,
                'allowed_types' => "gif|jpg|png|jpeg",
                'max_size'      => "3072000", // 3 MB (3×1000×1024)
                'max_height'    => "1024",
                'max_width'     => "1024",
            ]);
            if ( ! $this->upload->do_upload('picture')) {
                return $this->response($this->apiservice->responseError($this->upload->display_errors(null, null)));
            }
            $data['picture'] = $upload_path . '/' . $filename;
            // delete old file
            $tmp = unlink(asset_path() . $record['picture']);
        }

        try {
            $this->playlist_model->update($id, $data);
        } catch (Exception $e) {
            if (ENVIRONMENT != 'production') {
                $debug = array_slice($e->getTrace(), 0, 5);
            }
            return $this->response($this->apiservice->responseError($e->getMessage(), $debug));
        }

        return $this->response($this->apiservice->responseSuccess());
    }

    function delete_post($id)
    {
        $record = $this->playlist_model->get($id);
        if (empty($record)) {
            return $this->response($this->apiservice->responseError($this->lang->line('error_db_record_not_found')));
        }
        try {
            $this->playlist_model->delete($id);
        } catch (Exception $e) {
            if (ENVIRONMENT != 'production') {
                $debug = array_slice($e->getTrace(), 0, 5);
            }
            return $this->response($this->apiservice->responseError($e->getMessage(), $debug));
        }
        $tmp = unlink(asset_path() . $record['picture']);

        return $this->response($this->apiservice->responseSuccess());
    }
}