<?php

/**
 * Created by igorko on 02.06.17.
 */
class Playlist_detail_model extends MY_Model
{
    public function get($id)
    {
        return $this->db->get_where('playlist_detail', ['id' => $id])->row_array();
    }

    public function insert($data)
    {
        return $this->db->insert('playlist_detail', $data);
    }

    public function update($id, $data)
    {
        return $this->db->update('playlist_detail', $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete('playlist_detail', ['id' => $id]);
    }

    public function delete_where($data)
    {
        return $this->db->delete('playlist_detail', $data);
    }
}