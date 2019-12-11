<?php

class Khsm_Model extends CI_Model
{
    public function AmbilKhs($npm)
    {
        $this->db->where('npm', $npm);
        $result = $this->db->get('mahasiswa');
        $itemMahasiswa = $result->result();
        $kurikulum = $result->row('kurikulum');
        $this->db->where('status_mk', 'OK');
        $this->db->where('kdps', $result->row('kdps'));
        $this->db->group_start();
        $this->db->where('kurikulum', $kurikulum);
        $this->db->or_where('kurikulum', 'ALL');
        $this->db->group_end();
        $this->db->order_by('matakuliah.smt', 'ASC');
        $result = $this->db->get('matakuliah');
        $matakuliah = $result->result();
        $this->db->where('status_mk', 'NO');
        $this->db->where('kdps', $result->row('kdps'));
        $this->db->group_start();
        $this->db->where('kurikulum', $kurikulum);
        $this->db->or_where('kurikulum', 'ALL');
        $this->db->group_end();
        $this->db->order_by('matakuliah.smt', 'ASC');
        $result = $this->db->get('matakuliah');
        $matakuliahNo = $result->result();
        $this->db->where('npm', $npm);
        $result = $this->db->get('transkip');
        $itemKhsm = $result->result();
        $KhsmData = array(
            'Datas' => array(),
            'IPK' => "",
            'SKS' => "",
        );
        $TotalSks = 0;
        $Totalnxsks = 0;
        foreach ($matakuliah as $valueMatakuliah) {
            $item = array(
                'nmmk' => $valueMatakuliah->nmmk,
                'smt' => $valueMatakuliah->smt,
                'mk_konversi' => $valueMatakuliah->mk_konversi,
                'sks' => $valueMatakuliah->sks,
                'npm' => "-",
                'kmk' => $valueMatakuliah->kmk,
                'nhuruf' => "-",
                'nxsks' => "-",
                'ngBinding' => "",
                'ket' => "-",
            );
            foreach ($itemKhsm as $key => $valueKhsm) {
                $nilai = 0;

                if ($valueMatakuliah->kmk == $valueKhsm->kmk) {
                    if ((int) $valueKhsm->nxsks > $nilai) {
                        $warna = "";
                        if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B") {
                            $warna = "info";
                        } else if ($valueKhsm->nilai == "c") {
                            $warna = "danger";
                        } else {
                            $warna = "warning";
                        }

                        $item['npm'] = $valueKhsm->npm;
                        $item['kmk'] = $valueKhsm->kmk;
                        $item['nhuruf'] = $valueKhsm->nilai;
                        $item['nxsks'] = $valueKhsm->nxsks;
                        $item['ngBinding'] = $warna;
                        $item['ket'] = $valueKhsm->ket;
                        if ($valueKhsm->ket == "L") {
                            $nilai = (int) $valueKhsm->nxsks;
                            $TotalSks += (int) $valueMatakuliah->sks;
                        }
                    }
                } else {
                    foreach ($matakuliahNo as $valueNo) {
                        if ($valueMatakuliah->kmk == $valueNo->mk_konversi) {
                            $nilai = 0;
                            if ($valueKhsm->kmk == $valueNo->kmk) {
                                if ((int) $valueKhsm->nxsks > $nilai) {
                                    $warna = "";
                                    if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B") {
                                        $warna = "info";
                                    } else if ($valueKhsm->nilai == "c") {
                                        $warna = "danger";
                                    } else {
                                        $warna = "warning";
                                    }

                                    $item['npm'] = $valueKhsm->npm;
                                    $item['kmk'] = $valueKhsm->kmk;
                                    $item['nhuruf'] = $valueKhsm->nilai;
                                    $item['nxsks'] = $valueKhsm->nxsks;
                                    $item['ngBinding'] = $warna;
                                    $item['ket'] = $valueKhsm->ket;
                                    if ($valueKhsm->ket == "L") {
                                        $nilai = (int) $valueKhsm->nxsks;
                                        $TotalSks += (int) $valueMatakuliah->sks;
                                    }
                                }
                            }
                        }
                    }
                }
                $Totalnxsks += (int) $nilai;
            }
            array_push($KhsmData['Datas'], $item);

        }
        $IPK = 0;
        if ($Totalnxsks == 0) {
            $IPK = 0;
        } else {
            $IPK = round($Totalnxsks / $TotalSks, 2);
        }

        $KhsmData['IPK'] = $IPK;
        $KhsmData['SKS'] = $TotalSks;
        return $KhsmData;
    }
    public function AmbilIPS($npm)
    {
        $this->db->where('npm', $npm);
        $result = $this->db->get('mahasiswa');
        $itemMahasiswa = $result->result();
        $kurikulum = $result->row('kurikulum');
        $this->db->where('status_mk', 'OK');
        $this->db->where('kdps', $result->row('kdps'));
        $this->db->group_start();
        $this->db->where('kurikulum', $kurikulum);
        $this->db->or_where('kurikulum', 'ALL');
        $this->db->group_end();
        $this->db->order_by('matakuliah.smt', 'ASC');
        $result = $this->db->get('matakuliah');
        $matakuliah = $result->result();
        $this->db->where('status_mk', 'NO');
        $this->db->where('kdps', $result->row('kdps'));
        $this->db->group_start();
        $this->db->where('kurikulum', $kurikulum);
        $this->db->or_where('kurikulum', 'ALL');
        $this->db->group_end();
        $this->db->order_by('matakuliah.smt', 'ASC');
        $result = $this->db->get('matakuliah');
        $matakuliahNo = $result->result();
        $this->db->where('npm', $npm);
        $result = $this->db->get('transkip');
        $itemKhsm = $result->result();
        $KhsmData = array(
            'Datas' => array(),
            'IPK' => "",
            'SKS' => "",
        );
        $TotalSks = 0;
        $Totalnxsks = 0;
        foreach ($matakuliah as $valueMatakuliah) {
            $item = array(
                'nmmk' => $valueMatakuliah->nmmk,
                'smt' => $valueMatakuliah->smt,
                'mk_konversi' => $valueMatakuliah->mk_konversi,
                'sks' => $valueMatakuliah->sks,
                'npm' => "-",
                'kmk' => $valueMatakuliah->kmk,
                'nhuruf' => "-",
                'nxsks' => "-",
                'ngBinding' => "",
                'ket' => "-",
            );
            foreach ($itemKhsm as $key => $valueKhsm) {
                $nilai = 0;

                if ($valueMatakuliah->kmk == $valueKhsm->kmk) {
                    if ((int) $valueKhsm->nxsks > $nilai) {
                        $warna = "";
                        if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B") {
                            $warna = "info";
                        } else if ($valueKhsm->nilai == "c") {
                            $warna = "danger";
                        } else {
                            $warna = "warning";
                        }

                        $item['npm'] = $valueKhsm->npm;
                        $item['kmk'] = $valueKhsm->kmk;
                        $item['nhuruf'] = $valueKhsm->nilai;
                        $item['nxsks'] = $valueKhsm->nxsks;
                        $item['ngBinding'] = $warna;
                        $item['ket'] = $valueKhsm->ket;
                        if ($valueKhsm->ket == "L") {
                            $nilai = (int) $valueKhsm->nxsks;
                            $TotalSks += (int) $valueMatakuliah->sks;
                        }
                    }
                } else {
                    foreach ($matakuliahNo as $valueNo) {
                        if ($valueMatakuliah->kmk == $valueNo->mk_konversi) {
                            $nilai = 0;
                            if ($valueKhsm->kmk == $valueNo->kmk) {
                                if ((int) $valueKhsm->nxsks > $nilai) {
                                    $warna = "";
                                    if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B") {
                                        $warna = "info";
                                    } else if ($valueKhsm->nilai == "c") {
                                        $warna = "danger";
                                    } else {
                                        $warna = "warning";
                                    }

                                    $item['npm'] = $valueKhsm->npm;
                                    $item['kmk'] = $valueKhsm->kmk;
                                    $item['nhuruf'] = $valueKhsm->nilai;
                                    $item['nxsks'] = $valueKhsm->nxsks;
                                    $item['ngBinding'] = $warna;
                                    $item['ket'] = $valueKhsm->ket;
                                    if ($valueKhsm->ket == "L") {
                                        $nilai = (int) $valueKhsm->nxsks;
                                        $TotalSks += (int) $valueMatakuliah->sks;
                                    }
                                }
                            }
                        }
                    }
                }
                $Totalnxsks += (int) $nilai;
            }
            array_push($KhsmData['Datas'], $item);

        }
        $IPK = 0;
        if ($Totalnxsks == 0) {
            $IPK = 0;
        } else {
            $IPK = round($Totalnxsks / $TotalSks, 2);
        }

        $KhsmData['IPK'] = $IPK;
        $KhsmData['SKS'] = $TotalSks;
        return $KhsmData;
    }
    public function insert($data)
    {
        $this->db->trans_start();
        $resultBA = $this->db->insert('ban_matakuliah', $data->ba);
        $resultkhs = $this->db->insert('khsm', $data->khsm);
        $idkhs = $this->db->insert_id();
        foreach ($data['detailkhsm'] as $key => $value) {
            $detail = [
                'idPengampu' => $value['idPengampu'],
                'idKrs' => $idkhs

            ];
            $this->db->insert("khsm_detail", $value);
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === false) {
            return false;
        }else{
            return true;
        }
    }
    public function getNilai($item)
    {
        $id = $item->id;
        $resultMatakuliah = $this->db->query("
            SELECT
                `jadwal_kuliah`.*,
                `matakuliah`.`smt`,
                `matakuliah`.`kurikulum`,
                `program_studi`.`nmps`,
                `dosen`.`nmdsn`,
                `dosen_pengampu`.`nidn`
            FROM
                `jadwal_kuliah`
                LEFT JOIN `tahun_akademik` ON `jadwal_kuliah`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `jadwal_kuliah`.`gg` =
                `tahun_akademik`.`gg`
                LEFT JOIN `matakuliah` ON `matakuliah`.`kmk` = `jadwal_kuliah`.`kmk`
                AND `matakuliah`.`kdps` = `jadwal_kuliah`.`kdps`
                LEFT JOIN `program_studi` ON `jadwal_kuliah`.`kdps` = `program_studi`.`kdps`
                LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`kmk` = `dosen_pengampu`.`kmk`
                RIGHT JOIN `dosen` ON `dosen`.`nidn` = `dosen_pengampu`.`nidn`
                RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
            WHERE
                `tahun_akademik`.`status` = 'AKTIF' AND
                `dosen_pengampu`.`mengajar` = 'Y' AND
                `pegawai`.`IdUser` = '$id'
            ORDER BY
                `matakuliah`.`nmmk`
        ");
        $a = $resultMatakuliah->result_object();
        foreach ($a as $key => $value) {
            $kmk = $value->kmk;
            $resultMahasiswa = $this->db->query("
                SELECT
                    `mahasiswa`.`npm`,
                    `mahasiswa`.`jenjang`,
                    `mahasiswa`.`nmmhs`,
                    `khsm_detail`.`thakademik`,
                    `khsm_detail`.`gg`,
                    `khsm_detail`.`nhuruf`,
                    `khsm_detail`.`nxsks`,
                    `khsm_detail`.`ket`,
                    `krsm_detail`.`sks`,
                    `matakuliah`.`nmmk`,
                    `khsm_detail`.`kmk`,
                    `khsm_detail`.`nilai`,
                    `krsm_detail`.`kelas`
                FROM
                    `khsm_detail`
                    LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `khsm_detail`.`npm`
                    LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                    `khsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `khsm_detail`.`gg`
                    LEFT JOIN `krsm_detail` ON `krsm_detail`.`thakademik` =
                    `khsm_detail`.`thakademik` AND `krsm_detail`.`gg` = `khsm_detail`.`gg` AND
                    `krsm_detail`.`npm` = `khsm_detail`.`npm` AND `krsm_detail`.`kmk` =
                    `khsm_detail`.`kmk`
                    LEFT JOIN `matakuliah` ON `khsm_detail`.`kmk` = `matakuliah`.`kmk`
                WHERE
                    `khsm_detail`.`kmk` = '$kmk' AND
                    `tahun_akademik`.`status` = 'AKTIF' AND
                    `krsm_detail`.`kelas` = '$value->kelas'

            ");
            $a = $resultMahasiswa->result_object();
            foreach ($a as $key3 => $value3) {
                $value3->nilai = floatval($value3->nilai);
            }
            $value->Mahasiswa = $a;
        }
        return $a;

    }
    public function putNilai($item)
    {
        $this->db->where("thakademik", $item['thakademik']);
        $this->db->where("gg", $item['gg']);
        $this->db->where("nidn", $item['nidn']);
        $this->db->where("kdps", $item['kdps']);
        $this->db->where("kmk", $item['kmk']);
        $this->db->where("kelas", $item['kelas']);
        $result = $this->db->get("ban_matakuliah");
        $num = $result->num_rows();

        $this->db->trans_start();
        if($num == 0){
            $tg = date("Y-m-d");
            $data = [
                "thakademik" => $item['thakademik'],
                "gg" => $item['gg'],
                "nidn" => $item['nidn'],
                "kdps" => $item['kdps'],
                "kmk" => $item['kmk'],
                "nmdsn" => $item['nmdsn'],
                "nmps" => $item['nmps'],
                "nmmk" => $item['nmmk'],
                "kelas" => $item['kelas'],
                "tgban" =>$tg
            ];
            $this->db->insert("ban_matakuliah", $data);
        }
        foreach ($item['Mahasiswa'] as $key => $value) {
            $this->db->set("nhuruf", $value['nhuruf']);
            $this->db->set("nxsks", $value['nxsks']);
            $this->db->set("nilai", $value['nilai']);
            $this->db->set("ket", $value['ket']);
            $this->db->where("thakademik", $value['thakademik']);
            $this->db->where("gg", $value['gg']);
            $this->db->where("npm", $value['npm']);
            $this->db->where("kmk", $value['kmk']);
            $this->db->update("khsm_detail");
            $this->db->where("npm", $value['npm']);
            $this->db->where("kdps", $item['kdps']);
            $this->db->where("kmk", $value['kmk']);
            $result =  $this->db->get('transkip');
            if($result->num_rows()>0){
                $DataTranskip = $result->result_object();
                if($value['nxsks'] > $DataTranskip[0]->nxsks){
                    $this->db->set("nxsks", $value['nxsks']);
                    $this->db->set("nilai", $value['nilai']);
                    $this->db->set("ket", $value['ket']);
                    $this->db->where("npm", $value['npm']);
                    $this->db->where("kdps", $item['kdps']);
                    $this->db->where("kmk", $value['kmk']);
                    $this->db->update("transkip");
                }
            }
        }
        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        }else{
            $this->db->trans_complete();
            return true;
        }
        

        
    }
}
