<?php
class Du_Model extends CI_Model
{
    public function checkDu()
    {
		$ta = $this->db->query("SELECT
				*
			FROM
				`tahun_akademik`
			WHERE status='AKTIF'")->row_array();
        $mahasiswa = $this->db->query("SELECT
			mahasiswa.npm,
			mahasiswa.id,
			mahasiswa.kdps,
			(SELECT COUNT(*) FROM daftar_ulang WHERE mahasiswa.npm=daftar_ulang.npm) AS ducount
		FROM
			mahasiswa
		WHERE mahasiswa.statuskul IN ('TIDAK AKTIF', 'TRANSFER')")->result();
		$data = [];
		foreach ($mahasiswa as $key => $value) {
			if((int)$value->ducount==0){
				$item = [
					'thakademik'=>$ta['thakademik'],
					'gg'=>$ta['gg'],
					'npm'=>$value->npm,
					'kdps'=>$value->kdps,
					'tgdu'=>$ta['tglMulai'],
					'stdu'=>"TIDAK AKTIF",
					'last_reg'=>$ta['tglTutup'],
					'keterangan'=>"-",
					'idmahasiswa'=>$value->id,
				];
				array_push($data, $item);
			}
		}
		$this->db->trans_begin();
		$this->db->insert_batch('daftar_ulang', $data);
		if ($this->db->trans_status()) {
            $this->db->trans_commit();
            return $data;
        } else {
            $this->db->trans_rollback();
            return false;
        }
    }
}
