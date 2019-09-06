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
            array_push($KhsmData['Datas'], json_encode($item));

        }
        $IPK = 0;
        if($Totalnxsks==0){
            $IPK = 0;
        }else
            $IPK = round($Totalnxsks / $TotalSks, 2);
        
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
                'ket' => "-"
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
        if($Totalnxsks==0){
            $IPK = 0;
        }else
            $IPK = round($Totalnxsks / $TotalSks, 2);
        
        $KhsmData['IPK'] = $IPK;
        $KhsmData['SKS'] = $TotalSks;
        return $KhsmData;
    }
}
