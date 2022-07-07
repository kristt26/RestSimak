<?php

class Dosenwali_model extends CI_Model
{
	public function save($data)
	{
	}

	public function read($kdps, $iddosen)
	{
		$result = $this->db->query("SELECT
			`mahasiswa`.`npm`,
			`mahasiswa`.`kdps`,
			`mahasiswa`.`jenjang`,
			`mahasiswa`.`nmmhs`
		FROM
			`dosen_wali`
			LEFT JOIN `mahasiswa` ON `dosen_wali`.`npm` = `mahasiswa`.`npm`
		WHERE kdps='$kdps' AND iddosen='$iddosen' AND statuskul IN ('AKTIF', 'TIDAK AKTIF') ORDER BY mahasiswa.npm ASC")->result();
		return $result;
	}
}
