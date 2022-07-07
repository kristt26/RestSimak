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
		$data['prodi'] = $this->db->query("SELECT * FROM program_studi WHERE status='true'")->result_object();
		$data['dosen'] = $this->db->query("SELECT * FROM dosen")->result_object();
		return $data;
	}
}
