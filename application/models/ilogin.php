<?php

Class ilogin extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('commonfn');
    }

    public function login($username = "",
                          $password = "",
                          $rememberme = null,
                          $id = null,
                          $type = null,
                          $post_array = null)
    {
        if (empty($this)) {
            return;
        }
        $this->db->select('id,email,username,password,usertype,role_added,profileLink,firstname,lastname,braintreecustId,avail_space,never_sell');
        $this->db->from('users');
        //var_dump ($id, $type, $password, $username);exit;
        if ($id != null) {
            $where = "id='" . $id . "'";
        } else {
            $where = "(username='" . $username . "' or email='" . $username . "' ) AND password='" . md5($password) . "'";
        }
        if (!empty ($post_array['fbid'])) {
            $where = "(username='" . $username . "')";
        }

        if ($type == "user") {
            $where .= "AND usertype = '" . $type . "'";
        }


        $this->db->where($where);
        $this->db->limit(1);

        $query = $this->db->get();
        // var_dump($this->db->last_query());exit;
        if ($query->num_rows() == 1) {
            $user = $query->row();
            $temp_uimg = $this->commonfn->get_photo('p', $user->id);
            //$temp_array = explode("/",$temp);
            //$temp_uimg = end($temp_array);
            $user->profileImage = $temp_uimg;
            $user->loggedin = true;

            //check role of user: artist or normal user
            $user_id = $user->id;
            //get all roles ID if available
            $this->db->select('ud.roleId, ur.role');
            $this->db->from('user_roles_details as ud');
//                        $where = "userId='".$user_id."'";                         
//                        $this->db->where($where);
            $this->db->join('user_roles as ur', 'ur.id = ud.roleId AND ud.userId = "' . $user_id . '"', 'inner');
            $this->db->limit(100);

            $query = $this->db->get();
            //var_dump($this->db->last_query());exit;
            $user->artist = false;
            if ($query->num_rows() > 0) {
                foreach ($query->result_array() as $row) {
                    if ($row['type'] == 'artist') {
                        $user->artist = true;//specify: it is artist profile
                        break;
                    }
                }
            }
            //add country code and european union
            $ip_address = $this->input->ip_address();
            //default value
            $country = 'DE';
            $country_name = 'Germany';
            $euro = true;
            $city = '';
            $state = '';
            try {
                $country = get_ip_country_code($ip_address);
                $country_name = get_ip_country($ip_address);
                $city = get_ip_city($ip_address);
                $state = get_ip_state($ip_address);
                $euro = get_ip_eu($country);
            } catch (Exception $e) {
                echo $e->getMessage();
                //address is not found in database
            }

            $user->country = $country;
            $user->country_name = $country_name;
            $user->eu = $euro;


            if ($user->usertype == "a" || $user->usertype == "s") {
                //admin account
                $this->session->set_userdata(ADMIN_SESSION_NAME, $user);
                $this->session->set_userdata(USER_SESSION_NAME, $user);
            } else {
                //normal account
                $this->session->set_userdata(USER_SESSION_NAME, $user);
            }


            //print_r($this->session->all_userdata());
            // if (isset($rememberme) && $rememberme == '1') {
            //     $this->rememberme->setCookie($this->session->userdata(USER_SESSION_NAME)->id);
            // }

            /*if(isset($rememberme) && $rememberme == '1')
            {
                //$this->input->set_cookie($name, $value, $expire, $domain, $path, $prefix, $secure);
                //$this->input->set_cookie("username", $username, 2419200);
                //$this->input->set_cookie("pwd", $password, 2419200);
                //$this->config->set_item("sess_expire_on_close", "0");
                //$this->rememberme->setCookie($this->input->post('netid'));
            }
            else{
                        $this->input->delete_cookie("username");
                        $this->input->delete_cookie("pwd");
            }*/

            return $user;
        } else {
            return false;
        }
    }

    public function forgot_password($email, $user = null)
    {

        $this->db->where('email', $email);
        $this->db->from('users');
        $num_res = $this->db->count_all_results();

        if ($num_res == 1) {
            // Make a small string (code) to assign to the user // to indicate they've requested a change of // password
            $code = mt_rand('5000', '200000');
            $data = [
                'fp_code' => $code,
            ];

            $this->db->where('email', $email);
            if ($this->db->update('users', $data)) {
                // Update okay, send email
                if ($user == "true") {
                    $url = base_url() . "reset/" . $code;
                } else {
                    $url = base_url() . "admin/login/reset_password/" . $code;
                }

                $data = [
                    'url' => $url,
                ];

                $abc = $this->template->load('mail/email_template', 'mail/reset_password', $data, true);
                send_mail(ADMIN_MAIL, $email, "Reset Password", $abc);
                $body = "We have sent you an email,check your inbox to reset your password";

                return $body;

            } else {
                // Some sort of error happened, redirect user // back to form
                return false;
            }
        } else {
            return false;
        }

    }

    public function reset_password($fp_code, $nw_password, $flag = null)
    {

        $this->db->where('fp_code', $fp_code);
        $this->db->from('users');
        $num_res = $this->db->count_all_results();

        if ($num_res == 1) {
            // Make a small string (code) to assign to the user // to indicate they've requested a change of // password

            $data = [
                'password' => MD5($nw_password),
                'fp_code' => "",
            ];

            $this->db->where('fp_code', $fp_code);
            if ($this->db->update('users', $data)) {
                if ($flag == "user") {
                    return "Password changed successfully,<a href='" . base_url() . "'>Login</a> to continue";
                } else {
                    return "Password changed successfully,<a href='" . base_url() . "admin/login'>Login</a> to continue";
                }
            } else {
                return false;
            }
        } else {
            return false;
        }

    }

    //update VAT ID
    public function update_vat($id, $name, $address)
    {
        $data = [
            'vat_id' => $id,
            'vat_name' => $name,
            'vat_address' => $address,
        ];
        $user_id = $this->session->userdata('user')->id;
        $this->db->where('id', $user_id);
        if ($this->db->update('users', $data)) {
            return true;
        } else {
            return false;
        }
    }

    //get VAT ID
    public function get_vat()
    {
        $this->db->select('vat_id, vat_name, vat_address');
        $this->db->from('users');
        $user_id = $this->session->userdata('user')->id;
        $this->db->where('id', $user_id);
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $result = $query->row();

            return $result;
        }

    }

    /*check existance of username */
    function user_exist_check($email = null, $sc_username = null, $username = null, $flag = false, $field = null)
    {

        // $where = "(username='".$username."' or email='".$username."' ) AND password='".MD5($password)."'  ";
        //$this->db->where($where);

        $this->db->select('id,email,username,usertype');
        $this->db->from('users');
        //var_dump ($sc_username, $email, $username);exit;
        if ($sc_username != null) {
            $where = " sc_username ='" . $sc_username . "'";
        } else if ($username != null) {
            $where = " username ='" . $username . "'";
        } else {
            $where = " email ='" . $email . "'";
        }

        $this->db->where($where);
        $this->db->limit(1);

        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            //email exist or uname exist
            if ($flag == true) {
                $ar[0] = $field;
                $ar[1] = false;

                return $ar;
                //return false;
            }
            $user = $query->row();
            $user->loggedin = true;
            $this->session->set_userdata('user', $user);

            return "dbexist";
        } else {
            if ($flag == true) {
                $ar[0] = $field;
                $ar[1] = true;

                return $ar;
            }

            /*insert it into db*/

            return "intermediate";
        }
    }


    /*Update linked in id*/
    function update_social_id($provider, $id)
    {
        $session_user_id = $this->session->userdata('user')->id;

        if ($provider != "" && $id != "") {
            $data_update = [
                $provider => $id,
            ];
            $this->db->where('id', $session_user_id);
            $this->db->update('users', $data_update);
        }
    }

    function forgot_user_password($email)
    {
        $this->db->where('email', $email);
        $this->db->from('users');
        $num_res = $this->db->count_all_results();

        if ($num_res == 1) {
            // Make a small string (code) to assign to the user // to indicate they've requested a change of // password
            $code = mt_rand('5000', '200000');
            $data = [
                'password' => md5($code),
            ];

            $this->db->where('email', $email);
            if ($this->db->update('users', $data)) {
                // Update okay, send email
                $url = base_url() . "login";
                $data = [
                    'url' => $url,
                    'new_password' => $code,
                ];

                $abc = $this->template->load('mail/email_template', 'mail/reset_user_password', $data, true);
                send_mail(ADMIN_MAIL, $email, "Reset Password", $abc);
                $body = "We have sent you an email,check your inbox to reset your password";

                return $body;
            } else {
                // Some sort of error happened, redirect user // back to form
                return false;
            }
        } else {
            return false;
        }

    }

    function cookie_login($id)
    {
        self::login("", "", "", $id);
    }

    function check_fp_code_exist($fp_code)
    {

        $this->db->select('id,fp_code');
        $this->db->from('users');
        $where = "fp_code='" . $fp_code . "'";
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get();

        if ($query->num_rows() == 1) {
            $user = $query->row();
            $this->session->set_userdata('fp_code', $user);

            return "true";
        } else {
            return "false";
        }
    }

}

?>