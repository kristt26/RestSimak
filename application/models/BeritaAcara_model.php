<?php

class BeritaAcara_Model extends CI_Model
{
    public function Insert($data)
    {
        $result = $this->db->insert("bamengajardosen", $data);
        if ($result) {
            return $this->db->insert_id();
        } else {
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
            "data" => array(),
        ];
        $this->db->where("status", "true");
        $result = $this->db->get("program_studi");
        $DataProdi = $result->result_array();
        $result = $this->db->query("
            SELECT
                `matakuliah`.`kmk`,
                `matakuliah`.`nmmk`,
                `matakuliah`.`kdps`,
                `matakuliah`.`kurikulum`,
                `matakuliah`.`idmatakuliah`,
                `jadwal_kuliah`.`idjadwal`,
                `jadwal_kuliah`.`kelas`,
                `jadwal_kuliah`.`sks`,
                `pegawai`.`Nama`,
                `tahun_akademik`.`thakademik`,
                `tahun_akademik`.`gg`,
                `program_studi`.`nmps`
            FROM
                `jadwal_kuliah`
                LEFT JOIN `tahun_akademik` ON `jadwal_kuliah`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `jadwal_kuliah`.`gg` =
                `tahun_akademik`.`gg`
                LEFT JOIN `matakuliah` ON `matakuliah`.`kmk` = `jadwal_kuliah`.`kmk`
                AND `matakuliah`.`kdps` = `jadwal_kuliah`.`kdps`
                LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`kmk` = `dosen_pengampu`.`kmk`
                RIGHT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
                RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
                LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `matakuliah`.`kdps`
            WHERE
                `tahun_akademik`.`status` = 'AKTIF' AND
                `dosen_pengampu`.`mengajar` = 'Y' AND
                `matakuliah`.`kurikulum` NOT IN (2011)

        ");
        $DataMatakuliah = $result->result_array();
        $result = $this->db->query("
            SELECT
                *
            FROM
                `bamengajardosen`
        ");
        $DataBa = $result->result_array();
        foreach ($DataMatakuliah as $key1 => $value1) {
            $DatasMatakuliah = [
                "Matakuliah"=>$value1['nmmk'],
                "dosen"=>$value1['Nama'],
                "kmk"=>$value1['kmk'],
                "kelas"=>$value1['kelas'],
                "sks"=>$value1['sks'],
                "jurusan"=>$value1['nmps'],
                "beritaacara"=> array()
            ];
            foreach ($DataBa as $key => $value2) {
                if($value1['idjadwal']==$value2['idjadwal']){
                    array_push($DatasMatakuliah['beritaacara'], $value2);
                }
            }
            array_push($message['data'], $DatasMatakuliah);
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
