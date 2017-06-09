<?php



/**
 * Created by igorko on 02.06.17.
 */
class Playlist extends MY_Controller
{


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
        $this->load->library('form_validation');
        $this->lang->load('error');

        function alpha_dash_spaces($str)
        {
            // $CI =& get_instance();
            // $CI->form_validation->set_message('alpha_dash_spaces', 'The %s field may only contain alpha-numeric characters.');
            return ( ! preg_match("/^([-a-z_ ])+$/i", $str)) ? false : true;
        }

        $this->form_validation->set_rules('name', 'Name', 'trim|required|xss_clean|callback_alpha_dash_spaces|min_length[1]|max_length[255]');

        if ($this->form_validation->run() == false) {
            $this->responseError(empty($this->validation_errors()[0]) ? [$this->lang->line('error_request_empty')] : $this->validation_errors());
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
                $this->responseError($this->upload->display_errors(null, null));
            }
            $data['picture'] = $upload_path . '/' . $filename;
        }

        $user_data = $this->session->userdata('user');
        if (empty($user_data)) {
            // $this->responseError($this->lang->line('error_user_from_session'));
            $user_data['id'] = 1;
        }
        $data['userId'] = $user_data['id'];

        try {
            $this->playlist_model->insert($data);
        } catch (Exception $e) {
            if (ENVIRONMENT != 'production') {
                $debug = array_slice($e->getTrace(), 0, 5);
            }
            $this->responseError($e->getMessage(), $debug);
        }

        $this->responseSuccess();
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