<?php

/**
 * Created by igorko on 02.06.17.
 */
class Playlist_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    function get_all()
    {
        $query = $this->db->get('playlist');
        return $query->result('array');
    }

    public function get($id){
        return $this->db->get_where('playlist', ['id' => $id])->row();
    }

    function insert($data)
    {
        return $this->db->insert('playlist', $data);
    }

    function update($id, $data)
    {
        return $this->db->update('playlist', $data, ['id' => $id]);
    }

    public function delete($id){
        return $this->db->delete('playlist', ['id' => $id]);
    }
}