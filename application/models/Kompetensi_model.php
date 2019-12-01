<?php

class Kompetensi_Model extends CI_Model
{
    public function GetKomptensi()
    {
        $result =  $this->db->get("komptensi_pembelajaran");
        $data = [
            "Kompetensi"=>array()
        ];
        foreach ($result->result_array() as $key => $value) {
            $class = "";
            if($value['nmkom']=="PEDAGOGIK"){
                $class = "panel panel-primary";
            }else if($value['nmkom']=="PROFESIONAL"){
                $class = "panel panel-success";
            }else if($value['nmkom']=="KEPRIBADIAN"){
                $class = "panel panel-warning";
            }else{
                $class = "panel panel-danger";
            }
            $pertanyaan = [
                "Kompetensi"=> $value['nmkom'],
                "klas"=>$class,
                "Pertanyaan" => ""
            ];
            $this->db->where("idkom", $value['idkom']);
            $resultpertanyaan = $this->db->get("butir_nilai");
            $pertanyaan['Pertanyaan'] = $resultpertanyaan->result_array();

            array_push($data['Kompetensi'], $pertanyaan);
        }
        return $data;
    }
}
