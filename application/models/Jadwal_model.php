<?php

class Jadwal_Model extends CI_Model
{
    protected $JadwalTable = 'jadwal_kuliah';
    protected $TahunAkademikTable = 'tahun_akademik';
    protected $MahasiswaTable = 'mahasiswa';
    protected $TemKrsmTable = 'tem_krsm';
    protected $TemDetailKrsmTable = 'tem_detail_krsm';
    protected $KrsmTable = 'krsm';
    protected $DetailKrsmTable = 'krsm_detail';
    // protected $UserinRoleTable = 'userinrole';
    // protected $RoleTable = 'role';
    // protected $PegawaiTable = 'pegawai';
    public function insert_user(array $UserData)
    {
        $this->db->insert($this->UserTable, $UserData);
        return $this->db->insert_id();
    }

    public function getJadwalDosen($data)
    {
        $result = $this->db->query("
            SELECT
            `dosen`.`nidn`,
            `jadwal_kuliah`.*
            FROM
                `jadwal_kuliah`
                RIGHT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`kmk` = `dosen_pengampu`.`kmk`
                RIGHT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
                `jadwal_kuliah`.`thakademik` AND `tahun_akademik`.`gg` =
                `jadwal_kuliah`.`gg` AND `dosen_pengampu`.`thakademik` =
                `tahun_akademik`.`thakademik` AND `dosen_pengampu`.`gg` =
                `tahun_akademik`.`gg`
                RIGHT JOIN `dosen` ON `dosen`.`nidn` = `dosen_pengampu`.`nidn`
                RIGHT JOIN `pegawai` ON `pegawai`.`idpegawai` = `dosen`.`idpegawai`            
            WHERE tahun_akademik.status = 'AKTIF' AND pegawai.IdUser='$data->id'
        ");
        if($result->num_rows()){
            return $result->result_object();
        }else{
            return 0;
        }
    }

    public function getJadwalKuliah($npm, $kelas)
    {
        $result = $this->db->query("
        SELECT
        *
      FROM
        `krsm_detail`
        LEFT JOIN `tahun_akademik` ON `krsm_detail`.`thakademik` =
          `tahun_akademik`.`thakademik` AND `krsm_detail`.`gg` = `tahun_akademik`.`gg`
        RIGHT JOIN `jadwal_kuliah` ON `jadwal_kuliah`.`thakademik` =
          `krsm_detail`.`thakademik` AND `jadwal_kuliah`.`gg` = `krsm_detail`.`gg` AND
          `jadwal_kuliah`.`kmk` = `krsm_detail`.`kmk` AND `jadwal_kuliah`.`kelas` =
          `krsm_detail`.`kelas`
        LEFT JOIN `mahasiswa` ON `mahasiswa`.`npm` = `krsm_detail`.`npm`
            
            WHERE mahasiswa.npm = '$npm' AND tahun_akademik.status='AKTIF'");
        if($result->num_rows()){
            return $result->result_object();
        }else{
            return 0;
        }
    }

    public function getAllJadwal(Type $var = null)
    {
        $hasil = $this->db->query("
        SELECT
            `jadwal_kuliah`.*,
            `matakuliah`.`smt`,
            `matakuliah`.`kurikulum`
        FROM
            `jadwal_kuliah`
            LEFT JOIN `tahun_akademik` ON `jadwal_kuliah`.`thakademik` =
            `tahun_akademik`.`thakademik` AND `jadwal_kuliah`.`gg` =
            `tahun_akademik`.`gg`
            LEFT JOIN `matakuliah` ON `matakuliah`.`kmk` = `jadwal_kuliah`.`kmk`
            AND `matakuliah`.`kdps` = `jadwal_kuliah`.`kdps`
        WHERE
            `tahun_akademik`.`status` = 'AKTIF'
        ORDER BY
            `matakuliah`.`smt`");
        if($hasil->num_rows())
        {
            return $hasil->result_object();
        }
    }

    public function getjadwalmahasiswa($IdUser)
    {
        $this->db->select('*');
        $this->db->where('IdUser', $IdUser);
        $Mahasiswa = $this->db->get($this->MahasiswaTable);
        $StatusTA = "AKTIF";
        foreach ($Mahasiswa->result() as $value) {
            $DataMahasiswa = $value;
        }
        $this->db->select('*');
        $this->db->where('status', $StatusTA);
        $DataTahunAkademik = $this->db->get($this->TahunAkademikTable);
        foreach ($DataTahunAkademik->result() as $value) {
            $DataTA = $value;
        }
        if ($Mahasiswa->num_rows()) {
            if ($DataMahasiswa->statuskul == "TIDAK AKTIF" ||
                $DataMahasiswa->statuskul == "TRANSFER" ||
                $DataMahasiswa->statuskul == "CUTI") {

                $this->db->where('npm', $DataMahasiswa->npm);
                $this->db->where('thakademik', $DataTA->thakademik);
                $this->db->where('gg', $DataTA->gg);
                $resultDU = $this->db->get('daftar_ulang');
                if($resultDU->num_rows()){
                    $tgl = date('Y-m-d');
                    $tglmhs = $resultDU->row('last_reg');
                    $Tanggal_sistem = strtotime($tgl);
                    $TglReg = strtotime($tglmhs);
                    if($Tanggal_sistem<$TglReg){
                        $this->db->where('npm', $DataMahasiswa->npm);
                        $this->db->where('thakademik', $DataTA->thakademik);
                        $this->db->where('gg', $DataTA->gg);
                        $resultTemKrsm = $this->db->get($this->TemKrsmTable);
                        if ($resultTemKrsm->num_rows()) {
                            $this->db->where('IdKrsm', $resultTemKrsm->row('Id'));
                            $resultDetailKrsm = $this->db->get($this->TemDetailKrsmTable);
                            $Datas = array(
                                'TemKrsm' => json_encode($resultTemKrsm->result()),
                                'TemDetailKrsm' => json_encode($resultDetailKrsm->result()),
                                'message' => "TemKrsm",
                            );
                            return $Datas;
                        } else {
                            if ($DataTahunAkademik->num_rows()) {
                                $this->db->select('*');
                                $this->db->join('matakuliah', 'matakuliah.kmk = jadwal_kuliah.kmk', 'left');
                                $this->db->where('jadwal_kuliah.kdps', $DataMahasiswa->kdps);
                                $this->db->where('jadwal_kuliah.thakademik', $DataTA->thakademik);
                                $this->db->where('jadwal_kuliah.gg', $DataTA->gg);
                                // $this->db->where('kelas', $DataMahasiswa->kelas);
                                $this->db->group_start();
                                $this->db->where('kurikulum', $DataMahasiswa->kurikulum);
                                $this->db->or_where('kurikulum', 'ALL');
                                $this->db->group_end();
                                $this->db->order_by('matakuliah.smt', 'ASC');
                                $ItemJadwal = $this->db->get($this->JadwalTable);
                                if ($ItemJadwal->num_rows()) {
                                    $DataJadwal = array(
                                        'Jadwal' => json_encode($ItemJadwal->result()),
                                        'Kelas' => $DataMahasiswa->kelas,
                                        'message' => 'Jadwal',
                                        'status' => true,
                                        'streg' =>true
                                    );
                                    return $DataJadwal;
                                }else{
                                    $DataJadwal = array(
                                        'message' => 'Jadwal',
                                        'status' => false,
                                        'streg' =>true
                                    );
                                    return $DataJadwal;
                                }
                            }
                        }
                    }else{
                        $DataJadwal = array(
                            'message' => 'BatasReg',
                            'status' => true
                        );
                        return $DataJadwal;
                    }
                }else{
                    $DataJadwal = array(
                        'message' => 'Daftar Ulang',
                        'status' => false
                    );
                    return $DataJadwal;
                }
            } else {
                $this->db->where('npm', $DataMahasiswa->npm);
                $this->db->where('thakademik', $DataTA->thakademik);
                $this->db->where('gg', $DataTA->gg);
                $resultKrsm = $this->db->get($this->KrsmTable);
    
                if ($resultKrsm->num_rows()) {
                    $resultDetailKrsm = $this->db->query("
                    SELECT
                    `krsm_detail`.*,
                    `khsm_detail`.`nhuruf`,
                    `khsm_detail`.`nxsks`,
                    `khsm_detail`.`ket`
                    FROM
                    `krsm_detail`
                    LEFT JOIN `khsm_detail` ON `khsm_detail`.`thakademik` =
                        `krsm_detail`.`thakademik` AND `khsm_detail`.`gg` = `krsm_detail`.`gg` AND
                        `khsm_detail`.`npm` = `krsm_detail`.`npm` AND `khsm_detail`.`kmk` =
                        `krsm_detail`.`kmk`
                  WHERE `krsm_detail`.`npm` = '$DataMahasiswa->npm' AND `krsm_detail`.`thakademik` = '$DataTA->thakademik' AND `krsm_detail`.`gg` = '$DataTA->gg'");
                    $DataKrsm = array(
                        'Krsm' => $resultKrsm->result(),
                        'DetailKrsm' => $resultDetailKrsm->result(),
                        'message' => 'Krsm',
                    );
                    return $DataKrsm;
                }else{
                    $DataJadwal = array(
                        'message' => 'Daftar Ulang',
                        'status' => false
                    );
                    return $DataJadwal;
                }
                
            }
        } 
    }

}
