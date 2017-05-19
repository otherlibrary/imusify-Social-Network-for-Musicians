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
}