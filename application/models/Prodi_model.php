<?php

class Prodi_Model extends CI_Model
{
	public function select()
	{
		$data = $this->db->query("SELECT * FROM program_studi")->result_object();
		foreach ($data as $key => $value) {
			$value->kurikulum = $this->db->query("SELECT DISTINCt kurikulum FROM matakuliah WHERE kdps = '$value->kdps' AND kurikulum IS NOT NULL")->result_object();
		}
		return $data;
	}

	public function get()
	{
		$data['ta'] = $this->db->query("SELECT * FROM tahun_akademik WHERE status='AKTIF'")->result();
		$data['prodi'] = $this->db->query("SELECT * FROM program_studi WHERE status='true'")->row_object();
		$data['dosen'] = $this->db->query("SELECT * FROM dosen")->result_object();
		$data['mahasiswa'] = $this->db->query("SELECT
			`mahasiswa`.`npm`,
			`mahasiswa`.`kdps`,
			`mahasiswa`.`nmmhs`
		FROM
			`mahasiswa`
			LEFT JOIN `dosen_wali` ON `mahasiswa`.`npm` = `dosen_wali`.`npm`
		WHERE
			`mahasiswa`.`status` IN ('AKTIF', 'TIDAK AKTIF') AND
			`dosen_wali`.`Id` IS NULL")->result_object();
		return $data;
	}
}
