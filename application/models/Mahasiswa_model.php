<?php
class Mahasiswa_Model extends CI_Model
{
    public function detailmahasiswa($npm)
    {
        $result = $this->db->query("SELECT
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
            `mahasiswa`.`kurikulum`,
            `program_studi`.`nmps`,
            (SELECT
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
                        mahasiswa.Id=`transkip`.`Idmahasiswa`)AS IPK,
            (SELECT count(*) FROM `daftar_ulang` WHERE mahasiswa.Id=`daftar_ulang`.`idmahasiswa`) AS semester
        FROM
            `mahasiswa`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `mahasiswa`.`kdps`
            LEFT JOIN `transkip` ON `transkip`.`Idmahasiswa` = `mahasiswa`.`Id`
            LEFT JOIN `daftar_ulang` ON `mahasiswa`.`Id` = `daftar_ulang`.`idmahasiswa`
        WHERE
            `mahasiswa`.`npm` = '$npm' AND
            `mahasiswa`.`statuskul` IN ('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')
        GROUP BY mahasiswa.Id;")->row_array();
        return $result;
    }
    public function AmbilMahasiswa($npm)
    {
        if ($npm != null && $npm !== "undefined") {
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
            if ($ResultMahasiswa->num_rows()) {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Success",
                ];
                return $data;
            } else {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
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
                `mahasiswa`.`kurikulum`,
                `user`.`Status` AS `userStatus`,
                `user`.`Email`,
                `user`.`Password`,
                `user`.`Username`
            FROM
            `mahasiswa`
            LEFT JOIN `user` ON `user`.`Id` = `mahasiswa`.`IdUser`
            WHERE statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
            if ($ResultMahasiswa->num_rows()) {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Success",
                ];
                return $data;
            } else {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Tidak Data Mahasiswa",
                ];
                return $data;
            }
        }
    }
    public function mahasiswaonly($npm = null)
    {
        if ($npm != null) {
            $string = "npm='$npm' AND";
        } else {
            $string = "";
        }

        $result = $this->db->query("
            SELECT
            `mahasiswa`.*,
            `user`.`Email`
            FROM
                `mahasiswa`
                INNER JOIN `user` ON `mahasiswa`.`IdUser` = `user`.`Id`
            WHERE $string statuskul in('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')")->result();
        return $result;

    }

    public function MahasiswaPublick($npm)
    {
        if ($npm != null && $npm !== "undefined") {
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
            if ($ResultMahasiswa->num_rows()) {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Success",
                ];
                return $data;
            } else {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
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
                `mahasiswa` WHERE statuskul in ('AKTIF', 'CUTI', 'TIDAK AKTIF', 'TRANSFER')");
            if ($ResultMahasiswa->num_rows()) {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Success",
                ];
                return $data;
            } else {
                $data = [
                    'status' => true,
                    'data' => $ResultMahasiswa->result(),
                    'message' => "Tidak Data Mahasiswa",
                ];
                return $data;
            }
        }
    }

    public function addmahasiswa($param)
    {
        $data = (object) $param;
        
        $itemuser = [
            'Username' => $data->npm,
            'Password' => md5("stimik1011"),
            'Email' => $data->npm."@stimiksepnop.ac.id",
            'Status' => 'true',
        ];
        // $cek = $this->db->query("SELECT * FROM mahasiswa where npm = $data->npm")->row_array();
        // if(count($cek >0)){
        //     return false;
        // }else{
        //     $this->db->insert('user', $itemuser);
        //     try {
        //         $this->db->trans_begin();
        //         // $IdUser = $this->db->insert_id();
        //         // $userinrole = [
        //         //     'RoleId' => 4,
        //         //     'IdUser' => $IdUser,
        //         // ];
        //         // $data['IdUser'] = $IdUser;
        //         // $mahasiswa = [
        //         //     'npm'=>$data->npm,
        //         //     'kdps'=>$data->kdps,
        //         //     'jenjang'=>$data->jenjang,
        //         //     'kelas'=>$data->kelas,
        //         //     'nmmhs'=>$data->nmmhs,
        //         //     'tmlhr'=>$data->tmlhr,
        //         //     'tglhr'=>$data->tglhr,
        //         //     'jk'=>$data->jk,
        //         //     'agama'=>$data->agama,
        //         //     'kewarga'=>$data->kewarga,
        //         //     'pendidikan'=>$data->pendidikan,
        //         //     'nmsmu'=>$data->nmsmu,
        //         //     'jursmu'=>$data->jursmu,
        //         //     'kotasmu'=>$data->kotasmu,
        //         //     'kabsmu'=>$data->kabsmu,
        //         //     'provsmu'=>$data->provsmu,
        //         //     'pekerjaan'=>$data->pekerjaan,
        //         //     'almt'=>$data->almt,
        //         //     'notlp'=>$data->notlp,
        //         //     'status'=>$data->status,
        //         //     'jmsaudara'=>$data->jmsaudara,
        //         //     'nmayah'=>$data->nmayah,
        //         //     'almtayah'=>$data->almtayah,
        //         //     'nmibu'=>$data->nmibu,
        //         //     'sumbiaya'=>$data->sumbiaya,
        //         //     'statuskul'=>"TIDAK AKTIF",
        //         //     'tgdaftar'=>$data->tgdaftar,
        //         //     'kurikulum'=>$data->kurikulum->kurikulum,
        //         //     'IdUser'=>$IdUser,
        //         // ];
        //         // $this->db->insert('mahasiswa', $mahasiswa);
        //         // $data['Id'] = $this->db->insert_id();
        //         // $thakademik = $this->db->query("SELECT * FROM tahun_akademik WHERE status = 'AKTIF'")->row_object();
        //         // $du = [
        //         //     'thakademik'=>$thakademik->thakademik,
        //         //     'gg'=>$thakademik->gg,
        //         //     'npm'=>$data->npm,
        //         //     'kdps'=>$data->kdps,
        //         //     'tgdu'=>$thakademik->tglMulai,
        //         //     'stdu'=>"TIDAK AKTIF",
        //         //     'last_reg'=>$thakademik->tglTutup,
        //         //     'idmahasiswa'=>$data['Id'],
        //         // ];
        //         // $this->db->insert('daftar_ulang', $du);
        //         if ($this->db->trans_status()) {
        //             $this->db->trans_commit();
        //             return true;
        //         } else {
        //             $this->db->trans_rollback();
        //             return false;
        //         }
        //     } catch (\Throwable $th) {
        //         $this->db->trans_rollback();
        //         return $th->getMessage();
        //     }
            
        // }
        return $itemuser;

    }

    public function MahasiswabyProdi($user)
    {
        $result = $this->db->query("SELECT
            `mahasiswa`.*
        FROM
            `mahasiswa`
            LEFT JOIN `program_studi` ON `mahasiswa`.`kdps` = `program_studi`.`kdps`
            LEFT JOIN `kaprodi` ON `kaprodi`.`idprodi` = `program_studi`.`idprodi`
            LEFT JOIN `pegawai` ON `pegawai`.`idpegawai` = `kaprodi`.`idpegawai`
        WHERE pegawai.IdUser='$user->id'")->result();
        return $result;
    }

    public function MahasiswaAll()
    {
        $result = $this->db->query("SELECT
            *
        FROM
            `mahasiswa` ORDER BY npm, nmmhs")->result();
        return $result;
    }
}