<?php

/**
 * Created by igorko on 02.06.17.
 */
class Playlist_model extends MY_Model
{
    public function get_all()
    {
        $query = $this->db->get('playlist');
        return $query->result('array');
    }

    public function get($id)
    {
        return $this->db->get_where('playlist', ['id' => $id])->row_array();
    }

    public function insert($data)
    {
        $this->load->model('commonfn');

        $data = array_merge($data, [
            'perLink'     => $this->commonfn->get_permalink($data['name'], "playlist", "perLink", "id"),
        ]);

        $this->db->insert('playlist', $data);

        return $this->db->insert_id();
    }

    public function update($id, $data)
    {
        return $this->db->update('playlist', $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete('playlist', ['id' => $id]);
    }
}