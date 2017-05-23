<?php

class UserService
{
    function __construct()
    {
        $this->ci =& get_instance();
    }

    /**
     * @param int $userId
     * @return array|null
     */
    public function getUserRoles($userId)
    {
        $query = $this->ci->db->query("SELECT r.role FROM user_roles_details ur
                                LEFT JOIN user_roles r ON r.id = ur.roleId
                              WHERE ur.userId = $userId");

        $result = [];
        foreach ($query->result_array() as $role) {
            if (!empty($role['role'])) {
                $result[] = $role['role'];
            }
        }

        return $result;
    }

    /**
     * Deletes all roles for specified user
     * @param int $userId
     * @return int|null
     */
    public function resetRoles($userId)
    {
        $this->ci->db->delete('user_roles_details', ['userId' => $userId]);

        return $this->ci->db->affected_rows();
    }

    /**
     * Adds the list of roles to specified user
     * @param int   $userId
     * @param array $roles
     * @return array
     */
    public function addRoles($userId, $roles)
    {
        $insertData = [];
        foreach ($roles as $role) {
            $insertData[] = [
                'userId' => $userId,
                'roleId' => $role,
                'createdDate' => date('Y-m-d H:i:s'),
                'ipAddress' => get_client_ip(),
            ];
        }
        $this->ci->db->insert_batch('user_roles_details', $insertData);

        return $this->ci->db->affected_rows();
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserInfo($userId)
    {
        $this->ci->load->model('commonfn');
        $userData = $this->ci->session->userdata('user');
        $currentUserId = $userData->id;
        $result = [];
        $user = getvalfromtbl('*', 'users', 'id = ' . $userId);
        if (!empty($user)) {

            $result['user_image'] = $this->ci->commonfn->get_photo('p', $userId);
            $result['user_type'] = $user['member_plan'] == 'a' ? 'artist' : 'user';
            $result['firstname'] = $user['firstname'];
            $result['lastname'] = $user['lastname'];
            $result['followers'] = getvalfromtbl('COUNT(id)', 'followinglog', 'toId = ' . $userId, 'single');
            $result['following'] = getvalfromtbl('COUNT(id)', 'followinglog', 'fromId = ' . $userId, 'single');
            $followStatus = getvalfromtbl('COUNT(id)', 'followinglog', "fromId=$currentUserId toId=$userId", 'single');
            $result['follow_status'] = $followStatus == 1;
            $result['followingId'] = $userId;
            $result['username'] = $user['username'];
            $result['my_profile'] = $user['id'] == $userId;
            $result['loggedIn'] = $result['my_profile'];
            $result['user_roles_ar'] = $this->getUserRoles($userId);
            $result['playlists'] = []; // todo
        }

        return $result;
    }

    /**
     * @param int $userId
     * @return array
     */
    public function getUserInfoForEdit($userId)
    {
        $userData = getvalfromtbl('firstname,lastname,weburl,countryId,stateId,cityId,description,dob_d,dob_m,dob_y', 'users', 'id = ' . $userId);
        if (!empty($userData)) {
            $userData['birthdate'] = $userData['dob_d'] . '.' . $userData['dob_m'] . '.' . $userData['dob_y'];
            unset($userData['dob_d']);
            unset($userData['dob_m']);
            unset($userData['dob_y']);
            $userData['image'] = $this->ci->commonfn->get_photo('p', $userId);

            return $userData;
        }

        return [
            'error' => 'Cannot find specified user',
        ];
    }

    /**
     * @param int    $userId
     * @param string $firstname
     * @param string $lastname
     * @param string $weburl
     * @param int    $countryId
     * @param int    $stateId
     * @param int    $cityId
     * @param string $description
     * @param int    $dob_d
     * @param int    $dob_m
     * @param int    $dob_y
     * @return int
     */
    public function editUserInfo(
        $userId,
        $firstname,
        $lastname,
        $weburl,
        $countryId,
        $stateId,
        $cityId,
        $description,
        $dob_d,
        $dob_m,
        $dob_y
    )
    {
        $userData = [
            'firstname' => $firstname,
            'lastname' => $lastname,
            'weburl' => $weburl,
            'countryId' => $countryId,
            'stateId' => $stateId,
            'cityId' => $cityId,
            'description' => $description,
            'dob_d' => $dob_d,
            'dob_m' => $dob_m,
            'dob_y' => $dob_y,
            'updated' => date('Y-m-d H:i:s'),
        ];

        $this->ci->db->where('id', $userId);
        $this->ci->db->update('users', $userData);

        return $this->ci->db->affected_rows();
    }
}