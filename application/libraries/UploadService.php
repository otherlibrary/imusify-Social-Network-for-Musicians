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

    /**
     * @param integer    $trackId
     * @param bool       $base64
     * @param array|null $postData
     * @return array|null
     */
    public function uploadTrackImage($trackId, $base64 = false, $postData = null)
    {
        $uploadPath = asset_upload_path() . 'track/';
        $result = null;
        if (!$base64) {
            $uploadConfig = [
                'upload_path' => $uploadPath,
                'max_size' => 5 * 1024,
                'allowed_types' => 'jpg|jpeg|png',
            ];
            if (!$this->uploadFile('file', $uploadConfig)) {
                $error = [
                    'error' => $this->getUploadErrors(),
                ];

                return $error;
            } else {
                $data = $this->getUploadResult();
                $filename = $data['file_name'];

                if (!empty($this->insertPhotoToDb($filename, $trackId, 'track/', 't'))) {
                    $result = [
                        'file_path' => $data['full_path'],
                    ];
                }

                return $result;
            }
        } else {
            if (empty($postData)) {
                return null;
            }

            $data = $postData['file'];

            $extension = $this->getImgExtensionFromString($data);
            $fileName = md5(time()) . mt_rand() . $extension;
            $filepath = $uploadPath . $fileName;
            $file = $this->createImageFromString($data, $filepath);

            if (!empty($file)) {
                if (!empty($this->insertPhotoToDb($fileName, $trackId, 'track/', 't'))) {
                    $result = [
                        'file_path' => $filepath,
                    ];
                }
            }

            return $result;
        }
    }

    /**
     * @param string $string
     * @return null|string
     */
    public function getImgExtensionFromString($string)
    {
        if (stristr($string, 'jpeg') != false) {
            return '.jpeg';
        } else if (stristr($string, 'jpg') != false) {
            return '.jpg';
        } else if (stristr($string, 'png') != false) {
            return '.png';
        }

        return '.jpeg';
    }

    /**
     * @param string $data
     * @param string $filepath
     * @return bool|int
     */
    public function createImageFromString($data, $filepath)
    {
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $data));

        $file = file_put_contents(
            $filepath,
            $data
        );

        return $file;
    }

    /**
     * @param string $filename
     * @param int    $detailId
     * @param string $dir
     * @param string $type
     * @return int
     */
    public function insertPhotoToDb($filename, $detailId, $dir, $type = 't')
    {
        $currentPhoto = getvalfromtbl('*', 'photos', "detailId=$detailId and type='$type'");

        if (!empty($currentPhoto)) {
            $this->ci->db->update('photos', ['name' => $filename], 'id=' . $currentPhoto['id']);
            unlink(asset_upload_path() . $dir . $currentPhoto['name']);
        } else {
            $insertData = [
                'detailId' => $detailId,
                'dir' => $dir,
                'name' => $filename,
                'type' => $type,
                'default_pic' => 'y',
            ];
            $this->ci->db->insert('photos', $insertData);
        }

        return $this->ci->db->affected_rows();
    }

    /**
     * @param int $userId
     * @param string $data
     * @return bool
     */
    public function uploadUserImage($userId, $data)
    {
        $uploadPath = asset_path() . 'upload/users/';
        $this->fixUploadPath($uploadPath);

        $extension = $this->getImgExtensionFromString($data);
        $fileName = md5(time()) . mt_rand() . $extension;
        $filepath = $uploadPath . $fileName;
        $file = $this->createImageFromString($data, $filepath);

        if (!empty($file)) {
            if (!empty($this->insertPhotoToDb($fileName, $userId, 'users/', 'p'))) {
                return true;
            }
        }

        return false;
    }
}