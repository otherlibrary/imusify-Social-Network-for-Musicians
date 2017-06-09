<?php

/**
 * Created by igorko on 08.06.17.
 */
class MY_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
        $this->db->conn_id->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    }
}