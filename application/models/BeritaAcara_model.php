<?php

class BeritaAcara_Model extends CI_Model{
    public function Insert($data)
    {
        $result = $this->db->insert("bamengajardosen", $data);
        $data['idbamengajardosen']=$this->db->insert_id();
        $id = $this->db->insert_id();
        $this->db->where("idbamengajardosen", $id);
        $data = $this->db->get("bamengajardosen");
        if($result){
            $data = [
                "status" => true,
                "data" => $data->result_array()
            ];
            return $result;
        }else{
            $data = [
                "status" => false
            ];
            return $result;
        }
    }

    public function get($idjadwal, $nidn)
    {
        $this->db->where("idjadwal", $idjadwal);
        $this->db->where("nidn", $nidn);
        $result = $this->db->get("bamengajardosen");
        return $result->result_array();
    }

    public function update($data)
    {
        $this->db->where("idbamengajardosen", $data["idbamengajardosen"]);
        $result = $this->db->update("bamengajardosen", $data);
        return $result;
    }
}