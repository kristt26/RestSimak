<?php

class Upload_Model extends CI_Model
{
    public function updateData($photo, $data, $status)
    {
        if($status){
            $this->db->set('photo', $photo['photo']);
            $this->db->where('IdUser', $data->id);
            $result = $this->db->update('mahasiswa');
            return $result;
        }else{
            $this->db->set('photo', $photo['photo']);
            $this->db->where('IdUser', $data->id);
            $result = $this->db->update('pegawai');
            return $result;
        }
        
    }
    
}
