<?php
class Sks_Model extends CI_Model
{
    public function SksMahasiswa($npm, $thakademik, $gg)
    {
        if ($thakademik == null) {
            $resultkhs = $this->db->query(
                "SELECT
                *
                FROM
                    `krsm_detail`
                    LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                    `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
                WHERE
                    `krsm_detail`.`npm` = '$npm' AND
                    `tahun_akademik`.`status` = 'AKTIF'"
            );
            $SKS = 0;
            $Praktikum = 0;
            $riset = false;
            $kp= false;
            if ($resultkhs->num_rows()) { 
                foreach ($resultkhs->result() as $key => $value) {
                    $SKS += $value->sks;
                    $matakuliah = $value->nmmk;
                    $Cari = strpos($matakuliah, 'PRAKT.');
                    if ($Cari !== false) {
                        $Praktikum += 1;
                    } else {
                        $Cari1 = strpos($matakuliah, '[+]');
                        if ($Cari1 !== false) {
                            $Praktikum += 1;
                        }
                    }
                    if($value->kmk == "000008"){
                        $kp=true;
                    }
                    if($value->kmk == "000010"){
                        $riset=true;
                    }
                }
            }
            $Data = [
                'SKS' => $SKS,
                'Praktikum' => $Praktikum,
                'Riset' => $riset,
                "KP" => $kp

            ];
            return $Data;
        }else{
            $resultkhs = $this->db->query(
                "SELECT
                *
                FROM
                    `krsm_detail`
                    LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                    `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
                WHERE
                    `krsm_detail`.`npm` = '$npm' AND
                    `krsm_detail`.`thakademik` = '$thakademik' AND
                    `krsm_detail`.`gg` = '$gg'"
            );
            $SKS = 0;
            $Praktikum = 0;
            $riset = false;
            $kp= false;
            if ($resultkhs->num_rows()) {
                foreach ($resultkhs->result() as $key => $value) {
                    $SKS += $value->sks;
                    $matakuliah = $value->nmmk;
                    $Cari = strpos($matakuliah, 'PRAKT.');
                    if ($Cari !== false) {
                        $Praktikum += 1;
                    } else {
                        $Cari1 = strpos($matakuliah, '[+]');
                        if ($Cari1 !== false) {
                            $Praktikum += 1;
                        }
                    }
                    if($value->kmk == "000008"){
                        $kp=true;
                    }
                    if($value->kmk == "000010"){
                        $riset=true;
                    }
                }
            }
            $Data = [
                'SKS' => $SKS,
                'Praktikum' => $Praktikum,
                'Riset' => $riset,
                "KP" => $kp
            ];
            return $Data;
        }

    }
}
