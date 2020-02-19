<?php

class Pengumuman_Model extends CI_Model
{
    public function insert($data)
    {
        $this->db->where('berkas', $data['berkas']);
        $num = $this->db->get('pengumuman');
        if($num->num_rows()===0){
            $result = $this->db->insert('pengumuman', $data);
            $message = [
                'status' => $result,
                'data' => $this->db->insert_id()
            ];
            return $message;
        }
    }    
    public function get()
    {
        $result = $this->db->get('pengumuman');
        return $result->result_object();
    }
    public function delete($id)
    {
        $this->db->where('id', $id);
        $result = $this->db->delete('pengumuman');
        return $result;
    }
}