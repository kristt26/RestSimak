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
	public function insert_user(array $UserData)
	{
		$this->db->insert($this->UserTable, $UserData);
		return $this->db->insert_id();
	}

	public function TambahJadwal($data = null)
	{
		$this->load->model('TahunAkademik_model', 'TahunAkademik');
		$thakademik = $this->TahunAkademik->TAAktif();
		$item = [
			'thakademik' => $data['thakademik'],
			'gg' => $data['gg'],
			'hari' => $data['hari'],
			'ws' => str_replace(".", ":", $data['jamselesai']),
			'wm' => str_replace(".", ":", $data['jammulai']),
			'kdps' => $data['kdps'],
			'kmk' => $data['kmk'],
			'kelas' => $data['kelas'],
			'nmmk' => $data['nmmk'],
			'sks' => $data['sks'],
			'ruangan' => $data['ruangan'],
			'dsn_saji' => $data['dsn_saji'],
			'idpengampu' => $data['idpengampu'],
			// 'idtahunakademik' => $thakademik['idtahunakademik'],
		];
		$this->db->trans_begin();
		$this->db->insert("jadwal_kuliah", $item);
		if ($this->db->trans_status() == true) {
			$this->db->trans_commit();
			$data['idjadwal'] = $this->db->insert_id();
			return $data;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function UbahJadwal($data = null)
	{
		$this->load->model('TahunAkademik_model', 'TahunAkademik');
		$thakademik = $this->TahunAkademik->TAAktif();
		$item = [
			'thakademik' => $data['thakademik'],
			'gg' => $data['gg'],
			'hari' => $data['hari'],
			'ws' => str_replace(".", ":", $data['jamselesai']),
			'wm' => str_replace(".", ":", $data['jammulai']),
			'kdps' => $data['kdps'],
			'kmk' => $data['kmk'],
			'kelas' => $data['kelas'],
			'nmmk' => $data['nmmk'],
			'sks' => $data['sks'],
			'ruangan' => $data['ruangan'],
			'dsn_saji' => $data['dsn_saji'],
			'idpengampu' => $data['idpengampu'],
			// 'idtahunakademik' => $thakademik['idtahunakademik'],
		];
		$this->db->trans_begin();
		$this->db->where('idjadwal', $data['idjadwal']);
		$this->db->update("jadwal_kuliah", $item);
		if ($this->db->trans_status() == true) {
			$this->db->trans_commit();
			return $data;
		} else {
			$this->db->trans_rollback();
			return false;
		}
	}

	public function selectAllJadwal()
	{
		$result = $this->db->query("SELECT
            `jadwal_kuliah`.*
        FROM
            `jadwal_kuliah`
            LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`idpengampu` =
            `dosen_pengampu`.`idpengampu`
            LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
            `tahun_akademik`.`idtahunakademik`
        WHERE tahun_akademik.status='AKTIF'")->result();
		return $result;
	}

	public function getJadwalDosen($data)
	{
		$result = $this->db->query("SELECT
                `jadwal_kuliah`.*,
                `dosen`.`nidn`,
                `matakuliah`.`smt`,
                `matakuliah`.`kurikulum`,
                `program_studi`.`nmps`,
                `dosen`.`nmdsn`,
								(SELECT
            COUNT(*)
        FROM
            `krsm_detail`
            LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
        `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
        WHERE krsm_detail.kmk=jadwal_kuliah.kmk AND tahun_akademik.status='AKTIF' AND krsm_detail.kelas=jadwal_kuliah.kelas)AS jumlahmahasiswa
            FROM
                `jadwal_kuliah`
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
                `pegawai`.`IdUser` = '$data->id' AND
                `dosen_pengampu`.`mengajar` = 'Y'
            GROUP BY jadwal_kuliah.thakademik, jadwal_kuliah.gg, jadwal_kuliah.kelas, jadwal_kuliah.kmk
        ");
		return $result->result_object();
	}

	public function getJadwalKuliah($npm, $kelas)
	{
		$result = $this->db->query("SELECT
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
		if ($result->num_rows()) {
			return $result->result_object();
		} else {
			return 0;
		}
	}

	public function getAllJadwal($var = null)
	{
		$hasil = $this->db->query("SELECT
                `jadwal_kuliah`.*,
                `dosen`.`nidn`,
                `matakuliah`.`smt`,
                `matakuliah`.`kurikulum`,
                `program_studi`.`nmps`,
                `dosen`.`nmdsn`,
                                (SELECT
            COUNT(*)
        FROM
            `krsm_detail`
            LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
        `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
        WHERE krsm_detail.kmk=jadwal_kuliah.kmk AND tahun_akademik.status='AKTIF' AND krsm_detail.kelas=jadwal_kuliah.kelas)AS jumlahmahasiswa
            FROM
                `jadwal_kuliah`
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

                `tahun_akademik`.`status` = 'AKTIF'
            GROUP BY jadwal_kuliah.thakademik, jadwal_kuliah.gg, jadwal_kuliah.kelas, jadwal_kuliah.kmk
        ");
		if ($hasil->num_rows()) {
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
			if (
				$DataMahasiswa->statuskul == "TIDAK AKTIF" ||
				$DataMahasiswa->statuskul == "TRANSFER" ||
				$DataMahasiswa->statuskul == "CUTI"
			) {

				$this->db->where('npm', $DataMahasiswa->npm);
				$this->db->where('thakademik', $DataTA->thakademik);
				$this->db->where('gg', $DataTA->gg);
				$resultDU = $this->db->get('daftar_ulang');
				$TgSistem = date('Y-m-d');
				if ($resultDU->num_rows()) {
					if ($DataTA->tglReg <= $TgSistem) {
						$tgl = date('Y-m-d');
						$tglmhs = $resultDU->row('last_reg');
						$Tanggal_sistem = strtotime($tgl);
						$TglReg = strtotime($tglmhs);
						if ($Tanggal_sistem <= $TglReg) {
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
											'streg' => true,
											'mahasiswa' => $DataMahasiswa,
											'semester' => $resultDU->num_rows(),
										);
										return $DataJadwal;
									} else {
										$DataJadwal = array(
											'message' => 'Jadwal',
											'status' => false,
											'streg' => true,
										);
										return $DataJadwal;
									}
								}
							}
						} else {
							$DataJadwal = array(
								'message' => 'BatasReg',
								'status' => true,
							);
							return $DataJadwal;
						}
					} else {
						$DataJadwal = array(
							'message' => 'MulaiReg',
							'status' => true,
							'dataReg' => $DataTA,
						);
						return $DataJadwal;
					}
				} else {
					$DataJadwal = array(
						'message' => 'Daftar Ulang',
						'status' => false,
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
				} else {
					$DataJadwal = array(
						'message' => 'Daftar Ulang',
						'status' => false,
					);
					return $DataJadwal;
				}
			}
		}
	}

	public function getJadwalProdi()
	{
		$prodi = $this->db->get_where('program_studi', ['status' => 'true'])->result();
		foreach ($prodi as $key => $itemprodi) {
			$itemprodi->kurikulum = $this->db->query("SELECT kdps, kurikulum FROM `matakuliah` WHERE kurikulum is not null AND kdps = '$itemprodi->kdps' GROUP BY kurikulum")->result();
			foreach ($itemprodi->kurikulum as $key => $itemkurikulum) {
				$itemkurikulum->matakuliah = $this->db->query("SELECT
                    `matakuliah`.*,
                    `dosen_pengampu`.`idpengampu`,
                    `dosen_pengampu`.`thakademik`,
                    `dosen_pengampu`.`gg`
                FROM
                    `dosen_pengampu`
                    LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
                    `tahun_akademik`.`idtahunakademik`
                    LEFT JOIN `matakuliah` ON `matakuliah`.`idmatakuliah` =
                    `dosen_pengampu`.`idmatakuliah`
                WHERE tahun_akademik.status='AKTIF' AND matakuliah.kurikulum = '$itemkurikulum->kurikulum' AND `dosen_pengampu`.kdps='$itemprodi->kdps'")->result();
				foreach ($itemkurikulum->matakuliah as $key => $itemmatakuliah) {
					$itemmatakuliah->dosen = $this->db->query("SELECT
                        `dosen`.*,
                        dosen_pengampu.idpengampu
                    FROM
                        `dosen_pengampu`
                        RIGHT JOIN `dosen` ON `dosen`.`iddosen` = `dosen_pengampu`.`iddosen`
                        LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`idtahunakademik` =
                        `dosen_pengampu`.`idtahunakademik`
                    WHERE dosen_pengampu.kmk = '$itemmatakuliah->kmk' AND tahun_akademik.status='AKTIF'")->result();
				}
			}
		}
		$kelas = $this->db->get("kelas")->result();
		$all = $this->db->query("SELECT
            `jadwal_kuliah`.*,
            `program_studi`.`nmps`,
            `matakuliah`.`kurikulum`
        FROM
            `jadwal_kuliah`
            LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`idpengampu` =
        `dosen_pengampu`.`idpengampu`
            LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
        `tahun_akademik`.`idtahunakademik`
            LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `jadwal_kuliah`.`kdps`
            LEFT JOIN `matakuliah` ON `jadwal_kuliah`.`kmk` = `matakuliah`.`kmk`
        WHERE
            `tahun_akademik`.`status` = 'AKTIF'
        ORDER BY
            `jadwal_kuliah`.`nmmk`,
            `jadwal_kuliah`.`kelas`")->result();
		return ['prodi' => $prodi, 'kelas' => $kelas, 'jadwal' => $all];
	}

	public function getMahasiswa($kmk, $kelas)
	{
		$result = $this->db->query("SELECT
            `mahasiswa`.`npm`,
            `mahasiswa`.`nmmhs`,
            `mahasiswa`.`kelas`
        FROM
            `krsm_detail`
            INNER JOIN `mahasiswa` ON `krsm_detail`.`npm` = `mahasiswa`.`npm`
            LEFT JOIN `tahun_akademik` ON `tahun_akademik`.`thakademik` =
        `krsm_detail`.`thakademik` AND `tahun_akademik`.`gg` = `krsm_detail`.`gg`
        WHERE krsm_detail.kmk='$kmk' AND tahun_akademik.status='AKTIF' AND krsm_detail.kelas='$kelas'")->result();
		return $result;
	}

	public function hapus($idjadwal)
	{
		$this->db->where('idjadwal', $idjadwal);
		$result = $this->db->delete('jadwal_kuliah');
		return $result;
	}

	public function jadwal_praktikum($kdps)
	{
		$prodi = $this->db->query("SELECT nmps AS prodi, kdps, jenjang FROM program_studi WHERE kdps='$kdps'")->row_array();
		if ($prodi['kdps'] == "55420") {
			$prodi['matakuliah'] = $this->db->query("SELECT
				`jadwal_kuliah`.*,
				`program_studi`.`nmps`,
				`matakuliah`.`kurikulum`,
				`dosen`.`nidn`,
				`dosen`.`nmdsn`
			FROM
				`jadwal_kuliah`
				LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`idpengampu` =
			`dosen_pengampu`.`idpengampu`
				LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
			`tahun_akademik`.`idtahunakademik`
				LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `jadwal_kuliah`.`kdps`
				LEFT JOIN `matakuliah` ON `jadwal_kuliah`.`kmk` = `matakuliah`.`kmk`
				LEFT JOIN `dosen` ON `dosen_pengampu`.`iddosen` = `dosen`.`iddosen`
			WHERE
				`tahun_akademik`.`status` = 'AKTIF' AND program_studi.kdps='$kdps' AND RIGHT(matakuliah.nmmk, 5)='(KBR)' AND matakuliah.status_mk='OK
			ORDER BY
				`jadwal_kuliah`.`nmmk`,
				`jadwal_kuliah`.`kelas`")->result();
		} else if ($prodi['kdps'] == "57201") {
			$prodi['matakuliah'] = $this->db->query("SELECT
				`jadwal_kuliah`.*,
				`program_studi`.`nmps`,
				`matakuliah`.`kurikulum`,
				`matakuliah`.`idmatakuliah`,
				`dosen`.`nidn`,
				`dosen`.`nmdsn`
			FROM
				`jadwal_kuliah`
				LEFT JOIN `dosen_pengampu` ON `jadwal_kuliah`.`idpengampu` =
			`dosen_pengampu`.`idpengampu`
				LEFT JOIN `tahun_akademik` ON `dosen_pengampu`.`idtahunakademik` =
			`tahun_akademik`.`idtahunakademik`
				LEFT JOIN `program_studi` ON `program_studi`.`kdps` = `jadwal_kuliah`.`kdps`
				LEFT JOIN `matakuliah` ON `jadwal_kuliah`.`kmk` = `matakuliah`.`kmk`
				LEFT JOIN `dosen` ON `dosen_pengampu`.`iddosen` = `dosen`.`iddosen`
			WHERE
				`tahun_akademik`.`status` = 'AKTIF' AND program_studi.kdps='$kdps' AND RIGHT(matakuliah.nmmk, 3)='[+]' AND matakuliah.status_mk='OK'
			ORDER BY
				`jadwal_kuliah`.`nmmk`,
				`jadwal_kuliah`.`kelas`")->result();
		}
		return $prodi;
	}
}
