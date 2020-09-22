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
    public function MahasiswaPublick($npm=null)
    {
        if ($npm != null) {
            $ResultMahasiswa = $this->db->query("
            SELECT
                `mahasiswa`.`npm`,
                `mahasiswa`.`kdps`,
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
                `mahasiswa`.`nmibu`,
                `mahasiswa`.`sumbiaya`,
                `mahasiswa`.`statuskul`,
                `mahasiswa`.`tgdaftar`,
                `mahasiswa`.`kurikulum`
            FROM
                `mahasiswa` WHERE npm='$npm' AND statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
            if ($ResultMahasiswa->num_rows()>0) {
                return $ResultMahasiswa->result()[0];
            } else {
                $data = [
                    'message' => "Tidak Data Mahasiswa",
                ];
                return $data;
            }
        } else {
            $ResultMahasiswa = $this->db->query("
            SELECT
                `mahasiswa`.`npm`,
                `mahasiswa`.`kdps`,
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
                `mahasiswa`.`nmibu`,
                `mahasiswa`.`sumbiaya`,
                `mahasiswa`.`statuskul`,
                `mahasiswa`.`tgdaftar`,
                `mahasiswa`.`kurikulum`
            FROM
            `mahasiswa` WHERE statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
            if (count($ResultMahasiswa->result())>0) {
                return $ResultMahasiswa->result();
            } else {
                $data = [
                    'message' => "Tidak Data Mahasiswa",
                ];
                return $data;
            }
        }
    }
    public function Matakuliah($npm=null)
    {
        
        $this->load->library('my_lib');
        $mahasiswa = $this->db->query("SELECT
            `mahasiswa`.`npm`,
            `mahasiswa`.`nmmhs`,
            `mahasiswa`.`jursmu`,
            `mahasiswa`.`nmsmu`,
            `mahasiswa`.`jk`,
            `mahasiswa`.`provsmu`,
            (SELECT COUNT(*) FROM daftar_ulang WHERE daftar_ulang.idmahasiswa=mahasiswa.id) AS lamaKuliah
        FROM
            `mahasiswa`
            LEFT JOIN `daftar_ulang` ON `daftar_ulang`.`idmahasiswa` = `mahasiswa`.`Id`
        WHERE
            `mahasiswa`.`statuskul` = 'LULUS' AND 
            `mahasiswa`.`jursmu` <> '-' AND (SELECT COUNT(*) FROM daftar_ulang WHERE daftar_ulang.idmahasiswa=mahasiswa.id)>=8
        GROUP BY mahasiswa.id")->result();

        // $result = $this->db->query("SELECT
        //     `transkip`.`nmmk`,
        //     `transkip`.`nilai`,
            // `transkip`.`smt`,
            // CASE
            //     WHEN nilai = 'A' THEN 4
            //     WHEN nilai = 'B' THEN 3
            //     WHEN nilai = 'C' THEN 2
            //     WHEN nilai = 'D' THEN 1
            // END AS bobotnilai
        // FROM
        //     `transkip`
        // where SUBSTRING(npm, 1, 4) > '2010' AND smt <=4")->result();
        foreach ($mahasiswa as $key => $value) {
            $value->matakuliah = $this->db->query("SELECT
                `matakuliah`.`kmk`,
                `matakuliah`.`nmmk`,
                `krsm`.`sms`,
                `khsm_detail`.`nhuruf`,
                `matakuliah`.`sks`,
                CASE
                    WHEN nhuruf = 'A' THEN 4
                    WHEN nhuruf = 'B' THEN 3
                    WHEN nhuruf = 'C' THEN 2
                    WHEN nhuruf = 'D' THEN 1
                    WHEN nhuruf = 'E' THEN 0
                    WHEN nhuruf = '' THEN 0
                END AS nilai
            FROM
                `krsm`
                LEFT JOIN `khsm` ON `krsm`.`IdKrsm` = `khsm`.`IdKrsm`
                LEFT JOIN `khsm_detail` ON `khsm`.`Id` = `khsm_detail`.`IdKhsm`
                LEFT JOIN `matakuliah` ON `khsm_detail`.`kmk` = `matakuliah`.`kmk`
            WHERE krsm.npm='$value->npm' AND krsm.sms IN('1','2','3','4')
            ORDER BY krsm.sms ASC")->result();
        }
        // $temp = $this->my_lib->groupArray($result, "npm");
        return $mahasiswa;
    }
}
