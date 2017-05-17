<?php

Class crop_model extends CI_Model
{

    function __construct()
    {
        $session_user_id = $this->session->userdata('user')->id;
        $this->sess_userid = $session_user_id;
    }

    function create_image_frombase64($img_data, $folder_name)
    {
        $img_name = md5(time()) . mt_rand();
        if (file_exists(asset_upload_path() . $folder_name) == false) {
            mkdir(asset_upload_path() . $folder_name, 0777);
        }
        $output_filename = asset_upload_path() . $folder_name . $img_name;
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $img_data));

        file_put_contents($output_filename . ".png", $data);
        $response["img_name"] = $img_name;
        $response["type"] = "png";
        $response["folder_name"] = $folder_name;
        $response["output_filename"] = $output_filename;
        $response["name"] = $img_name . ".png";

        return $response;
    }

    function image_crop($image_data, $folder_name, $action)
    {
        $imgUrl = $this->input->post('imgUrl', false);
        $imgType = $this->input->post('imgType', false);

        //var_dump ($this->input->post('img'));
        //var_dump ($_FILES);

        $imgUrl = $imgType . "," . $imgUrl;
        // original sizes
        $imgInitW = $this->input->post('imgInitW');
        $imgInitH = $this->input->post('imgInitH');
        // resized sizes
        $imgW = $this->input->post('imgW');
        $imgH = $this->input->post('imgH');
        // offsets
        $imgY1 = $this->input->post('imgY1');
        $imgX1 = $this->input->post('imgX1');
        // crop box
        $cropW = $this->input->post('cropW');
        $cropH = $this->input->post('cropH');
        // rotation angle
        $angle = $this->input->post('rotation');

        $jpeg_quality = 100;
        $img_name = md5(time()) . mt_rand();
        $output_filename = asset_upload_path() . $folder_name . $img_name;
        //check artwork from track or uploaded image
        if ($this->input->post('img')) {
            $type = $this->input->post('imgType');
            if (stristr($type, 'jpeg')) {
                $img_full_name = $img_name . '.jpeg';
                $type = '.jpeg';
            } else if (stristr($type, 'jpg')) {
                $img_full_name = $img_name . '.jpeg';
                $type = '.jpeg';
            } else if (stristr($type, 'png')) {
                $img_full_name = $img_name . '.png';
                $type = '.png';
            } else {
                $img_full_name = $img_name . '.gif';
                $type = '.gif';
            }
            $data = base64_decode($this->input->post('img'));
            //from root folder imusify (not from model)
            $r = file_put_contents('./assets/upload/track/' . $img_full_name, $data);
            if (!$r) {
                //false to save picture cover artwork
                $response = [
                    "status" => 'error',
                    "message" => 'Can`t write artwork from track',
                ];
            } else {
                //success to save file
                $response["status"] = "success";
                $response["msg"] = "Uploaded successfully.";
                $response["img_name"] = $img_name;
                $response["type"] = $type;
                $response["folder_name"] = $folder_name;
                $response["output_filename"] = $output_filename;
            }

        } else {
            //uploaded image
            if (file_exists(asset_upload_path() . $folder_name) == false) {
                mkdir(asset_upload_path() . $folder_name, 0777);
            }


            $pos = strpos($imgUrl, ";");
            //$what = explode(':', substr($imgUrl, 0, $pos));
            $image_info = getimagesize($imgUrl);
            $what = $image_info['mime'];
            switch (strtolower($what)) {
                case 'image/png':
                    $img_r = imagecreatefrompng($imgUrl);
                    $source_image = imagecreatefrompng($imgUrl);
                    $type = '.png';
                    break;
                case 'image/jpeg':
                    $img_r = imagecreatefromjpeg($imgUrl);
                    $source_image = imagecreatefromjpeg($imgUrl);
                    error_log("jpg");
                    $type = '.jpeg';
                    break;
                case 'image/gif':
                    $img_r = imagecreatefromgif($imgUrl);
                    $source_image = imagecreatefromgif($imgUrl);
                    $type = '.gif';
                    break;
                default:
                    die('image type not supported');
            }
            if (!is_writable(dirname($output_filename))) {
                $response = [
                    "status" => 'error',
                    "message" => 'Can`t write cropped File',
                ];
            } else {

                // resize the original image to size of editor
                $resizedImage = imagecreatetruecolor($imgW, $imgH);
                imagecopyresampled($resizedImage, $source_image, 0, 0, 0, 0, $imgW, $imgH, $imgInitW, $imgInitH);
                // rotate the rezized image
                $rotated_image = imagerotate($resizedImage, -$angle, 0);
                // find new width & height of rotated image
                $rotated_width = imagesx($rotated_image);
                $rotated_height = imagesy($rotated_image);
                // diff between rotated & original sizes
                $dx = $rotated_width - $imgW;
                $dy = $rotated_height - $imgH;
                // crop rotated image to fit into original rezized rectangle
                $cropped_rotated_image = imagecreatetruecolor($imgW, $imgH);
                imagecolortransparent($cropped_rotated_image, imagecolorallocate($cropped_rotated_image, 0, 0, 0));
                imagecopyresampled($cropped_rotated_image, $rotated_image, 0, 0, $dx / 2, $dy / 2, $imgW, $imgH, $imgW, $imgH);
                // crop image into selected area
                $final_image = imagecreatetruecolor($cropW, $cropH);
                imagecolortransparent($final_image, imagecolorallocate($final_image, 0, 0, 0));
                imagecopyresampled($final_image, $cropped_rotated_image, 0, 0, $imgX1, $imgY1, $cropW, $cropH, $cropW, $cropH);
                // finally output png image
                //imagepng($final_image, $output_filename.$type, $png_quality);
                imagejpeg($final_image, $output_filename . $type, $jpeg_quality);

                $response["status"] = "success";
                $response["msg"] = "Uploaded successfully.";
                $response["img_name"] = $img_name;
                $response["type"] = $type;
                $response["folder_name"] = $folder_name;
                $response["output_filename"] = $output_filename;
            }
        }

        return $response;
    }

}