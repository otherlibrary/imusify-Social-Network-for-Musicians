<?php

class UploadService
{
    function __construct()
    {
        $this->ci =& get_instance();
        $this->ci->load->library('upload');
        $this->ci->load->library('session');
        $this->ci->load->model('space_model');
    }

    /**
     * Fetch the file from POST query and store this one on server
     * @return array|null
     */
    function uploadTrackFile()
    {
        $userData = $this->ci->session->userdata('user');
        if ($userData->id) {
//            $availSpace = $userData->avail_space;
            $availSpace = 20 * 1024;
            $uploadPath = asset_path() . 'upload/media/' . $userData->id . '/';

            $uploadConfig = [
                'upload_path' => $uploadPath,
                'max_size' => $availSpace,
                'allowed_types' => 'mp3|mp2|ogg|aac|amr|wma|aiff|wav|flac|alac',
            ];

            $this->fixUploadPath($uploadPath);
            if (!$this->uploadFile('file', $uploadConfig)) {
                $error = [
                    'error' => $this->getUploadErrors(),
                ];

                return $error;
            } else {
                $data = [
                    'upload_data' => $this->getUploadResult(),
                ];

                $spaceData = $this->ci->space_model->getUserCommonSpace($userData->id);
                if (!empty($spaceData)) {
                    $this->ci->space_model->updateUserSpace(
                        $userData->id,
                        $spaceData['used_space'] + intval(($data['upload_data']['file_size'] * 1024))
                    );
                }

                return $data;
            }

            return null;
        }
    }

    /**
     * @param string $file
     * @param array  $ciUploadConfig
     * @return bool
     */
    public function uploadFile($file, $ciUploadConfig)
    {
        $this->ci->upload->initialize($ciUploadConfig);

        return $this->ci->upload->do_upload($file);
    }

    /**
     * @param string $beginTag
     * @param string $endTag
     * @return string
     */
    public function getUploadErrors($beginTag = ' ', $endTag = ' ')
    {
        return $this->ci->upload->display_errors($beginTag, $endTag);
    }

    /**
     * @return array
     */
    public function getUploadResult()
    {
        return $this->ci->upload->data();
    }

    /**
     * @param string $path
     */
    public function fixUploadPath($path)
    {
        if (!is_dir($path)) {
            mkdir($path);
        }
    }
}