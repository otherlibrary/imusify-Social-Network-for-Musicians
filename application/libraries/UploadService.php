<?php

class UploadService
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * Fetch the file from POST query and store this one on server
     * @return array|null
     */
    function uploadTrackFile()
    {
        $this->ci->load->library('session');

        $userData = $this->ci->session->userdata('user');
        if ($userData->id) {
            $availSpace = $userData->avail_space;

            $uploadPath = asset_path() . 'upload/media/' . $userData->id . '/';
            if (!is_dir($uploadPath)) {
                mkdir($uploadPath);
            }

            $uploadConfig = [
                'upload_path' => $uploadPath,
                'max_size' => $availSpace,
                'allowed_types' => 'mp3|mp2|ogg|aac|amr|wma|aiff|wav|flac|alac',
            ];
            $this->ci->load->library('upload', $uploadConfig);

            if (!$this->ci->upload->do_upload('file')) {
                $error = [
                    'error' => $this->ci->upload->display_errors(' ', ' '),
                ];

                return $error;
            } else {
                $data = [
                    'upload_data' => $this->ci->upload->data(),
                ];

                return $data;
            }

            return null;
        }
    }
}