<?php defined('BASEPATH') OR exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';

class common_api extends REST_Controller
{
    /**
     * Returns the common list of license types in DB
     * [GET api/licenses-list]
     **/
    public function licenses_list_get()
    {
        $sql = 'SELECT id, name, description, lic_type, mp3, wave FROM track_licence_types WHERE status = \'y\'';

        $query = $this->db->query($sql);
        $result = $query->result();

        $this->response($result);
    }
}