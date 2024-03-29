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
                        if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B+" || $valueKhsm->nilai == "B" || $valueKhsm->nilai == "C+") {
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
                        if ($valueKhsm->ket !== "TL") {
                            $nilai = (int) $valueKhsm->nxsks;
                            $TotalSks += (int) $valueMatakuliah->sks;
                        }
                    } else {
                        $warna = "warning";
                        $item['npm'] = $valueKhsm->npm;
                        $item['kmk'] = $valueKhsm->kmk;
                        $item['nhuruf'] = $valueKhsm->nilai;
                        $item['nxsks'] = $valueKhsm->nxsks;
                        $item['ngBinding'] = $warna;
                        $item['ket'] = $valueKhsm->ket;
                    }
                } else {
                    foreach ($matakuliahNo as $valueNo) {
                        if ($valueMatakuliah->kmk == $valueNo->mk_konversi) {
                            $nilai = 0;
                            if ($valueKhsm->kmk == $valueNo->kmk) {
                                if ((int) $valueKhsm->nxsks > $nilai) {
                                    $warna = "";
                                    if ($valueKhsm->nilai == "A" || $valueKhsm->nilai == "B+" || $valueKhsm->nilai == "B" || $valueKhsm->nilai == "C+") {
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
                                    if ($valueKhsm->ket !== "TL") {
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
        $this->db->trans_begin();
        $resultBA = $this->db->insert('ban_matakuliah', $data->ba);
        $resultkhs = $this->db->insert('khsm', $data->khsm);
        $idkhs = $this->db->insert_id();
        foreach ($data['detailkhsm'] as $key => $value) {
            $detail = [
                'idPengampu' => $value['idPengampu'],
                'idKrs' => $idkhs,

            ];
            $this->db->insert("khsm_detail", $value);
        }

        if ($this->db->trans_status() === false) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return true;
        }
    }
    
    public function getNilai($item)
    {
        $id = $item->id;
        $resultMatakuliah = $this->db->query("SELECT
            `jadwal_kuliah`.*,
            Count(`krsm_detail`.`npm`) AS `jumlahMahasiswa`,
            `dosen`.`nidn`,
            `matakuliah`.`smt`,
            `matakuliah`.`kurikulum`,
            `program_studi`.`nmps`,
            `dosen`.`nmdsn`
        FROM
            `jadwal_kuliah`
            LEFT JOIN `krsm_detail` ON `krsm_detail`.`thakademik` =
        `jadwal_kuliah`.`thakademik` AND `krsm_detail`.`gg` = `jadwal_kuliah`.`gg`
        AND `krsm_detail`.`kmk` = `jadwal_kuliah`.`kmk` AND `krsm_detail`.`kelas` =
        `jadwal_kuliah`.`kelas`
            RIGHT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
        `jadwal_kuliah`.`thakademik` AND `tahun_akademik`.`gg` =
        `jadwal_kuliah`.`gg`
            LEFT JOIN `dosen_pengampu` ON `dosen_pengampu`.`thakademik` =
        `tahun_akademik`.`thakademik` AND `dosen_pengampu`.`gg` =
        `tahun_akademik`.`gg` AND `jadwal_kuliah`.`idpengampu` =
        `dosen_pengampu`.`idpengampu`
            LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
            RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
            LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
        `dosen_pengampu`.`idmatakuliah` AND `matakuliah`.`kdps` =
        `jadwal_kuliah`.`kdps`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `matakuliah`.`kdps`
        WHERE
            `tahun_akademik`.`status` = 'AKTIF' AND
            `pegawai`.`IdUser` = '$id' AND
            `dosen_pengampu`.`mengajar` = 'Y'
        GROUP BY
            `jadwal_kuliah`.`thakademik`,
            `jadwal_kuliah`.`gg`,
            `jadwal_kuliah`.`kelas`,
            `jadwal_kuliah`.`kmk`,
            `dosen`.`nidn`
        ORDER BY
            `matakuliah`.`nmmk`,
            `krsm_detail`.`kelas`
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
                    `krsm_detail`.`bup`,
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
                    `krsm_detail`.`kelas` = '$value->kelas'  AND
                    matakuliah.status_mk = 'OK'

            ");
            $b = $resultMahasiswa->result_object();
            foreach ($b as $key3 => $value3) {
                $value3->nilai = floatval($value3->nilai);
            }
            $value->Mahasiswa = $b;
        }
        return $a;

    }

	public function getProgress($data = null) {
		$data = $this->db->query("SELECT
		`jadwal_kuliah`.*, `program_studi`.`nmps`,
		(SELECT COUNT(*) FROM khsm_detail LEFT JOIN mahasiswa on mahasiswa.npm = khsm_detail.npm WHERE jadwal_kuliah.kelas = mahasiswa.kelas AND jadwal_kuliah.kmk=khsm_detail.kmk AND jadwal_kuliah.thakademik=khsm_detail.thakademik AND jadwal_kuliah.gg = khsm_detail.gg AND khsm_detail.ket='L')as lulus, 
		(SELECT COUNT(*) FROM khsm_detail LEFT JOIN mahasiswa on mahasiswa.npm = khsm_detail.npm WHERE jadwal_kuliah.kelas = mahasiswa.kelas AND jadwal_kuliah.kmk=khsm_detail.kmk AND jadwal_kuliah.thakademik=khsm_detail.thakademik AND jadwal_kuliah.gg = khsm_detail.gg)as total, 
		(SELECT COUNT(*) FROM khsm_detail LEFT JOIN mahasiswa on mahasiswa.npm = khsm_detail.npm WHERE jadwal_kuliah.kelas = mahasiswa.kelas AND jadwal_kuliah.kmk=khsm_detail.kmk AND jadwal_kuliah.thakademik=khsm_detail.thakademik AND jadwal_kuliah.gg = khsm_detail.gg AND khsm_detail.ket IS NULL)as belum
		FROM
		`jadwal_kuliah`
		LEFT JOIN `dosen_pengampu` ON `dosen_pengampu`.`idpengampu` =
		`jadwal_kuliah`.`idpengampu`
		LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`idtahunakademik` =
		`dosen_pengampu`.`idtahunakademik`
		LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
		`dosen_pengampu`.`idmatakuliah`
		LEFT JOIN `program_studi` ON `jadwal_kuliah`.`kdps` = `program_studi`.`kdps`
		LEFT JOIN `kaprodi` ON `program_studi`.`idprodi` = `kaprodi`.`idprodi`
		LEFT JOIN `pegawai` ON `kaprodi`.`idpegawai` = `pegawai`.`idpegawai`
		WHERE pegawai.IdUser='$data->id' AND tahun_akademik.status='AKTIF' ORDER by smt,nmmk,kelas asc")->result();
		return $data;
	}
    
    public function getAllNilai($item)
    {
        $id = $item->id;
        $resultMatakuliah = $this->db->query("SELECT
            `jadwal_kuliah`.*,
            Count(`krsm_detail`.`npm`) AS `jumlahMahasiswa`,
            `dosen`.`nidn`,
            `matakuliah`.`smt`,
            `matakuliah`.`kurikulum`,
            `program_studi`.`nmps`,
            `dosen`.`nmdsn`
        FROM
            `jadwal_kuliah`
            LEFT JOIN `krsm_detail` ON `krsm_detail`.`thakademik` =
        `jadwal_kuliah`.`thakademik` AND `krsm_detail`.`gg` = `jadwal_kuliah`.`gg`
        AND `krsm_detail`.`kmk` = `jadwal_kuliah`.`kmk` AND `krsm_detail`.`kelas` =
        `jadwal_kuliah`.`kelas`
            RIGHT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
        `jadwal_kuliah`.`thakademik` AND `tahun_akademik`.`gg` =
        `jadwal_kuliah`.`gg`
            LEFT JOIN `dosen_pengampu` ON `dosen_pengampu`.`thakademik` =
        `tahun_akademik`.`thakademik` AND `dosen_pengampu`.`gg` =
        `tahun_akademik`.`gg` AND `jadwal_kuliah`.`idpengampu` =
        `dosen_pengampu`.`idpengampu`
            LEFT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
            RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`
            LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
        `dosen_pengampu`.`idmatakuliah` AND `matakuliah`.`kdps` =
        `jadwal_kuliah`.`kdps`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `matakuliah`.`kdps`
        WHERE
            `tahun_akademik`.`status` = 'AKTIF' AND
            `dosen_pengampu`.`mengajar` = 'Y'
        GROUP BY
            `jadwal_kuliah`.`thakademik`,
            `jadwal_kuliah`.`gg`,
            `jadwal_kuliah`.`kelas`,
            `jadwal_kuliah`.`kmk`,
            `dosen`.`nidn`
        ORDER BY
            `matakuliah`.`nmmk`,
            `krsm_detail`.`kelas`
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
                    `krsm_detail`.`kelas` = '$value->kelas'  AND
                    matakuliah.status_mk = 'OK'

            ");
            $b = $resultMahasiswa->result_object();
            foreach ($b as $key3 => $value3) {
                $value3->nilai = floatval($value3->nilai);
            }
            $value->Mahasiswa = $b;
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
        if ($num == 0) {
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
                "tgban" => $tg,
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
            $result = $this->db->get('transkip');
            if ($result->num_rows() > 0) {
                $DataTranskip = $result->result_object();
                if ($value['nxsks'] > $DataTranskip[0]->nxsks || $value['bup']=='B') {
                    $this->db->set("nxsks", $value['nxsks']);
                    $this->db->set("nilai", $value['nhuruf']);
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
        } else {
            $this->db->trans_complete();
            return true;
        }

    }
}
