<?php

class UserService
{
    function __construct()
    {
        $this->ci =& get_instance();
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
        $userData = $this->ci->session->userdata('user');
        $currentUserId = $userData->id;
        $result = [];
        $user = getvalfromtbl('*', 'users', 'id = ' . $userId);
        if (!empty($user)) {
            if (!empty($image = getvalfromtbl('*', 'photos', "detailId = $userId and dir = 'users/'"))) {
                $result['user_image'] = '/assets/upload/users/' . $image['name'];
            }
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
}