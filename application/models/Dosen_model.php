<?php

class Dosen_Model extends CI_Model
{
    public function get()
    {
        $result = $this->db->query("
            SELECT
                *
            FROM
                `dosen`
                RIGHT JOIN `pegawai` ON `dosen`.`idpegawai` = `pegawai`.`idpegawai` 
            WHERE 
                `dosen`.nidn!='null' AND
                `dosen`.scholarId!='null'
        ");
        $Data = $result->result_array();
        $b=array(
            "Data"=>array()
        );
        foreach ($Data as $ke => $value) {
            $a = "http://cse.bth.se/~fer/googlescholar-api/googlescholar.php?user=".$value['scholarId'];
            $response = $this->callAPI("GET", $a, false);
            if(strpos($response, '"total_citations": ,')){
                $response = str_replace('"total_citations":', '"total_citations": 0',$response);
            }
            $data = array(
                "nidn"=>$value["nidn"],
                "nmsdn"=>$value["nmdsn"],
                "pendidikan"=>$value['pendakhir'],
                "scholarId" => $value['scholarId'],
                "Publikasi"=> $response
            );
            array_push($b["Data"], $data);
        }
        return $b["Data"];
    }    
    public function callAPI($method, $url, $data){
        $curl = curl_init();
     
        switch ($method){
           case "POST":
              curl_setopt($curl, CURLOPT_POST, 1);
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
              break;
           case "PUT":
              curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
              if ($data)
                 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);			 					
              break;
           default:
              if ($data)
                 $url = sprintf("%s?%s", $url, http_build_query($data));
        }
     
        // OPTIONS:
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
           'APIKEY: 111111111111111111111',
           'Content-Type: application/json',
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
     
        // EXECUTE:
        $result = curl_exec($curl);
        if(!$result){die("Connection Failure");}
        curl_close($curl);
        return $result;
     }
}
