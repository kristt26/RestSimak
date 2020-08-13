<?php
class Mahasiswa_Model extends CI_Model
{
    public function selectIPK($npm = null)
    {
        $result = $this->db->query("SELECT
            SUM(CASE 
                WHEN ket= 'L' THEN 1*nxsks
                ELSE
                0*nxsks
                END)/SUM(CASE 
                WHEN ket = 'L' THEN 1*sks
                ELSE
                0*sks
                END) AS IPK
            FROM
                `transkip`
            WHERE
                npm='$npm'
            GROUP BY npm");
        if ($result->result()) {
            return $result->result()[0];
        } else {
            $data = [
                'message' => "Data tidak ditemukan",
            ];
            return $data;
        }
    }
    public function selectIPS($npm = null)
    {
        $result = $this->db->query("SELECT
            `daftar_ulang`.`thakademik`,
            `daftar_ulang`.`gg`,
            `mahasiswa`.`nmmhs`,
            (SUM(`khsm_detail`.`nxsks`) / `krsm`.`jmsks`) AS 'IPS'
        FROM
            `mahasiswa`
            LEFT JOIN `daftar_ulang` ON `daftar_ulang`.`idmahasiswa` = `mahasiswa`.`Id`
            LEFT JOIN `krsm` ON `krsm`.`iddu` = `daftar_ulang`.`iddu`
            LEFT JOIN `khsm` ON `khsm`.`IdKrsm` = `krsm`.`IdKrsm`
            LEFT JOIN `khsm_detail` ON `khsm_detail`.`IdKhsm` = `khsm`.`Id`
        WHERE `mahasiswa`.`npm` = '$npm'
        GROUP BY `daftar_ulang`.`tgdu`");
        
        if ($result->num_rows()>0) {
            foreach ($result->result() as $key => $value) {
                $value->smt = $key +1;
                $value->IPS = $value->IPS==null ? 0: $value->IPS;
            }
            return $result->result();
        } else {
            $data = [
                'message' => "Data tidak ditemukan",
            ];
            return $data;
        }
    }
    public function AmbilMahasiswa($npm)
    {
        if ($npm != null && $npm!=="undefined") {
            $ResultMahasiswa = $this->db->query("SELECT
                `mahasiswa`.`npm`,
                `mahasiswa`.`jenjang`,
                `mahasiswa`.`kelas`,
                `mahasiswa`.`nmmhs`,
                `mahasiswa`.`tmlhr`,
                `mahasiswa`.`tglhr`,
                `mahasiswa`.`jk`,
                `mahasiswa`.`agama`,
                `mahasiswa`.`kewarga`,
                `mahasiswa`.`pendidikan`,
                `mahasiswa`.`nmsmu`,
                `mahasiswa`.`jursmu`,
                `mahasiswa`.`kotasmu`,
                `mahasiswa`.`kabsmu`,
                `mahasiswa`.`provsmu`,
                `mahasiswa`.`pekerjaan`,
                `mahasiswa`.`almt`,
                `mahasiswa`.`notlp`,
                `mahasiswa`.`status`,
                `mahasiswa`.`jmsaudara`,
                `mahasiswa`.`nmayah`,
                `mahasiswa`.`almtayah`,
                `mahasiswa`.`nmibu`
            FROM
                `mahasiswa`
            WHERE
                `mahasiswa`.`npm`='$npm' AND `mahasiswa`.`statuskul` IN ('AKTIF')");
            if ($ResultMahasiswa->num_rows()) {
                return $ResultMahasiswa->result()[0];
            } else {
                $data = [
                    'status' => false,
                    'message' => "Tidak Data Mahasiswa",
                ];
                return $data;
            }
        } else {
            $ResultMahasiswa = $this->db->query("SELECT
                `mahasiswa`.`npm`,
                `mahasiswa`.`jenjang`,
                `mahasiswa`.`kelas`,
                `mahasiswa`.`nmmhs`,
                `mahasiswa`.`tmlhr`,
                `mahasiswa`.`tglhr`,
                `mahasiswa`.`jk`,
                `mahasiswa`.`agama`,
                `mahasiswa`.`kewarga`,
                `mahasiswa`.`pendidikan`,
                `mahasiswa`.`nmsmu`,
                `mahasiswa`.`jursmu`,
                `mahasiswa`.`kotasmu`,
                `mahasiswa`.`kabsmu`,
                `mahasiswa`.`provsmu`,
                `mahasiswa`.`pekerjaan`,
                `mahasiswa`.`almt`,
                `mahasiswa`.`notlp`,
                `mahasiswa`.`status`,
                `mahasiswa`.`jmsaudara`,
                `mahasiswa`.`nmayah`,
                `mahasiswa`.`almtayah`,
                `mahasiswa`.`nmibu`
                FROM
                `mahasiswa`
                WHERE
                `mahasiswa`.`statuskul` IN ('AKTIF')");
            if ($ResultMahasiswa->num_rows()>0) {
                return $ResultMahasiswa->result();
            } else {
                $data = [
                    'status' => false,
                    'message' => "Data tidak ditemukan",
                ];
                return $data;
            }
        }
    }
}
