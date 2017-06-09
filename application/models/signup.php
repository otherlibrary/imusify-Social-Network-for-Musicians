<?php

Class Signup extends CI_Model
{
    function sign_up($fname, $lname, $uname, $email, $password, $gender, $mm, $dd, $yy, $invitecode = null, $post_array)
    {
        $is_invited = 0;
        if ($gender == 'male') {
            $gender = 'm';
        } else if ($gender == 'female') {
            $gender = 'f';
        }
        $this->db->select('id');
        $this->db->from('users');
        $where = "(username='" . $uname . "' or email='" . $email . "')";
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            //email exist or uname exist
            return false;
        } else {
            //$this->db->escape
            $token = md5(time());
            $temp = $this->session->userdata("socialuser");
//			$this->load->model("invitation");
//			$is_invited = $this->invitation->email_invited_check_exist($email,$invitecode);
//			if($is_invited == false)
//			{
//				return "notinvitedyet";
//				exit;
//			}
            /*Prepare all membership details*/

            $plan_id_ar = getvalfromtbl("id,amount", "membership_plan", "amount = '0'");
            $plan_id = $plan_id_ar["id"];
            $plan_amount = $plan_id_ar["amount"];
            $this->load->model("payment_transactions");
            $plan_details = $this->payment_transactions->plan_detail_json($plan_id);
            /*var_dump($plan_details);*/
            $plan_space = json_decode($plan_details);
            /*var_dump($plan_space);*/
            $total_space = $plan_space->space;
            $used_space = 0;
            $avail_space = $total_space - $used_space;
            /*Prepare all membership details ends*/

            if (isset($temp) && $temp["provider"] != "") {
                if ($temp["provider"] == "fb") {
                    $temp_array = [
                        'firstname' => $temp["first_name"],
                        'lastname'  => $temp["last_name"],
                        'fbid'      => $temp["id"],
                    ];
                }
                /*
                                else if($temp["provider"] == "in")
                {

                    $temp_array = array(
                        'firstname' =>  $temp["first_name"],
                        'lastname' =>  $temp["last_name"],
                        'liid' =>  $temp["id"]
                        );
                }else if($temp["provider"] == "sc"){
                    $temp_array = array(
                        'firstname' =>  $temp["first_name"],
                        'lastname' =>  $temp["last_name"],
                        'scid' =>  $temp["id"],
                        'sc_username' => $temp["sc_username"]
                        );
                }
                                */
                $commmon_detail_array = [
                    'username'    => $this->db->escape_str($uname),
                    'email'       => $this->db->escape_str($email),
                    'password'    => md5($this->db->escape_str($password)),
                    'ipaddress'   => get_client_ip(),
                    'gender'      => $this->db->escape_str($gender),
                    'usertype'    => 'u',
                    'created'     => date('Y-m-d H:i:s'),
                    'updated'     => date('Y-m-d H:i:s'),
                    'token'       => $token,
                    'profileLink' => $this->db->escape_str($uname),
                    'total_space' => $total_space,
                    'avail_space' => $avail_space,
                    'used_space'  => $used_space,
                    'newsletter'  => ($post_array['newsletter'] == 'on') ? 'on' : 'off',
                ];
                $data = array_merge($temp_array, $commmon_detail_array);
            } else {
                $data = [
                    'firstname'     => $this->db->escape_str($fname),
                    'lastname'      => $this->db->escape_str($lname),
                    'username'      => $this->db->escape_str($uname),
                    'email'         => $this->db->escape_str($email),
                    'password'      => md5($this->db->escape_str($password)),
                    'ipaddress'     => get_client_ip(),
                    'gender'        => $this->db->escape_str($gender),
                    'usertype'      => 'u',
                    'created'       => date('Y-m-d H:i:s'),
                    'updated'       => date('Y-m-d H:i:s'),
                    'token'         => $token,
                    'profileLink'   => $this->db->escape_str($uname),
                    'invitedFromId' => $is_invited,
                    'total_space'   => $total_space,
                    'avail_space'   => $avail_space,
                    'used_space'    => $used_space,
                    'newsletter'    => ($post_array['newsletter'] == 'on') ? 'on' : 'off',
                    'fbid'          => (isset($post_array['fbid'])) ? $post_array['fbid'] : '',
                ];
            }
            /*var_dump($data);*/
            $this->db->insert('users', $data);
            $user_id = $this->db->insert_id();

            /*Insert in plan details id*/
            $data_plan_det = [
                'userId'      => $user_id,
                'planId'      => $plan_id,
                'startDate'   => date('Y-m-d H:i:s'),
                'endDate'     => date('Y-m-d H:i:s', strtotime("+50000 days")),
                'createdDate' => date('Y-m-d H:i:s'),
                'status'      => 'a',
                'amount'      => $plan_amount,
                'planDetails' => $plan_details,
            ];
            $this->db->insert('user_plan_details', $data_plan_det);
            /*Insert in plan details id*/

            $data_mail = [
                'login_url'   => base_url() . "login",
                'username'    => $uname,
                'mail'        => $email,
                'verify_link' => base_url() . "api/verifyuser/token/" . $token,
            ];
            $abc = $this->template->load('mail/email_template', 'mail/register', $data_mail, true);
            if (empty ($post_array['facebook'])) {
                send_mail(ADMIN_MAIL, $email, "Successfully registered with Imusify", $abc);
            }
            return $user_id;
        }
    }

    function verify_modal_process($token)
    {
        $this->db->select('id,username');
        $this->db->from('users');
        $where = "(token='" . $token . "' )";
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            $user_data = $query->row();
            $data_update_query = [
                'emailverified' => 'y',
            ];
            $this->db->where('id', $user_data->id);
            $this->db->update('users', $data_update_query);
            $ar['status'] = "verified_success";
            $ar['username'] = $user_data->username;
            return $ar;
        } else {
            $ar['status'] = "verified_unsuccess";
            $ar['username'] = "Guest";
            return $ar;
        }
    }
}

?>