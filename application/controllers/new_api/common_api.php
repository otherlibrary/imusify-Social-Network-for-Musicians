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

    function roles_get()
    {
        $this->load->model('user_profile');

        $roles_array = $this->user_profile->get_user_roles();
        $user_selected_array = $this->user_profile->user_db_roles("roles");
        $user_def_db_roles = $this->user_profile->get_user_roles("is_default='y'","","true");

        $new_array = array();
        foreach($roles_array as $roles)
        {
            $roles['selected'] = false;
            $roles['default'] = false;

            if(!empty($user_selected_array) && in_array($roles["id"],$user_selected_array))
            {
                $roles['selected'] = true;
            }

            //set default array of roles and set it as default
            if(!empty($user_def_db_roles) && in_array($roles["id"],$user_def_db_roles))
            {

                $roles['default'] = true;
                $roles['selected'] = true;
            }

            array_push($new_array,$roles);
        }


        $data = array(
            'roles' => 	$new_array,
            'u_db_roles' => 	$user_selected_array
        );

        echo json_encode($data);
    }

}