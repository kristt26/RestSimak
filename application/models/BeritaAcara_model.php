<?php

class BeritaAcara_Model extends CI_Model{
    public function Insert($data)
    {
        $result = $this->db->insert("bamengajardosen", $data);
        if($result){
            return $this->db->insert_id();
        }else{
            return 0;
        }
        
    }

    public function get($idjadwal, $nidn)
    {
        $this->db->where("idjadwal", $idjadwal);
        $this->db->where("nidn", $nidn);
        $result = $this->db->get("bamengajardosen");
        return $result->result_array();
    }

    public function GetLaporan($data)
    {
        $message = [
            "data"=>array()
        ];
        $this->db->where("status", "true");
        $result = $this->db->get("program_studi");
        $DataProdi = $result->result_array();
        $result = $this->db->query("
            SELECT
                *
            FROM
                `matakuliah`
            WHERE 
                matakuliah.kurikulum != 'null' OR matakuliah.kurikulum!=2011
      
        ");
        $DataMatakuliah = $result->result_array();
        $result = $this->db->query("
            SELECT
                *
            FROM
                `bamengajardosen`
        ");
        $DataBa = $result->result_array();
        foreach ($DataProdi as $key => $value) {
            $resultprodi = [
                "$value[nmps]"=> array()
            ];
            foreach ($DataMatakuliah as $key1 => $value1) {
                $resultMatkul = [
                    "$value1[nmmk]" => array()
                ];
                if($value['kdps']==$value1['kdps']){
                    foreach ($DataBa as $key2 => $value2) {
                        if($value1['kmk']==$value2['kmk']){
                            array_push($resultMatkul["$value1[nmmk]"], $value2);
                        }
                    }
                    array_push($resultprodi["$value[nmps]"], $resultMatkul);
                }
            }
            array_push($message['data'], $resultprodi);
        }
        return $message;
    }

    public function update($data)
    {
        $this->db->where("idbamengajardosen", $data["idbamengajardosen"]);
        $result = $this->db->update("bamengajardosen", $data);
        return $result;
    }
}