<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Jayapura');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Jadwal extends \Restserver\Libraries\REST_Controller

{
	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$this->load->model('Jadwal_model', 'JadwalModel');
	}
	/**
	 *  Jadwal Kuliah
	 * @method : GET
	 */
	public function JadwalKuliah_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$npm = $this->get('npm');
			$kelas = $this->get('kelas');
			$Output = $this->JadwalModel->getJadwalKuliah($npm, $kelas);
			if ($Output > 0 && !empty($Output)) {
				$message = [
					'status' => true,
					'data' => $Output,
				];
				$this->response($message, REST_Controller::HTTP_OK);
			} else {
				$message = [
					'status' => false,
					'message' => "Tidak Ada Data",
				];
				$this->response($message, REST_Controller::HTTP_NOT_FOUND);
			}
		}
	}

	public function JadwalProdi_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$Output = $this->JadwalModel->getJadwalProdi();
			$this->response($Output, REST_Controller::HTTP_OK);
		}
	}

	public function TambahJadwal_post()
	{
		$data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
		$Output = $this->JadwalModel->TambahJadwal($data);
		$this->response($Output, REST_Controller::HTTP_OK);
		// $this->load->library('Authorization_Token');
		// $is_valid_token = $this->authorization_token->validateToken();
		// if ($is_valid_token['status'] === true) {

		// }
	}

	public function UbahJadwal_put()
	{
		$data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
		$Output = $this->JadwalModel->UbahJadwal($data);
		$this->response($Output, REST_Controller::HTTP_OK);
		// $this->load->library('Authorization_Token');
		// $is_valid_token = $this->authorization_token->validateToken();
		// if ($is_valid_token['status'] === true) {

		// }
	}

	public function GetAllJadwal_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$Output = $this->JadwalModel->selectAllJadwal();
			$this->response($Output, REST_Controller::HTTP_OK);
		}
	}

	/**
	 *  Jadwal Mengajar Dosen
	 * @method : GET
	 */
	public function JadwalDosen_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			// $npm = $this->get('nidn');
			$Output = $this->JadwalModel->getJadwalDosen($is_valid_token['data']);
			if ($Output > 0 && !empty($Output)) {
				$message = [
					'status' => true,
					'data' => $Output,
				];
				$this->response($message, REST_Controller::HTTP_OK);
			} else {
				$message = [
					'status' => false,
					'message' => [],
				];
				$this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
			}
		} else {
			$message = [
				"data" => "Session anda telah habis",
			];
			$this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
		}
	}

	public function MahasiswaKelas_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$kmk = $this->uri->segment(4);
			$kelas = $this->uri->segment(5);
			$Output = $this->JadwalModel->getMahasiswa($kmk, $kelas);
			$this->response($Output, REST_Controller::HTTP_OK);
		} else {
			$this->response('Anda tidak memiliki akses, check session anda', REST_Controller::HTTP_UNAUTHORIZED);
		}
	}

	/**
	 *  Semua Jadwal
	 * @method : POST
	 */

	public function jadwalall_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$Output = $this->JadwalModel->getAllJadwal();
			if ($Output > 0 && !empty($Output)) {
				$message = [
					'status' => true,
					'data' => json_encode($Output),
				];
				$this->response($message, REST_Controller::HTTP_OK);
			} else {
				$message = [
					'status' => false,
					'message' => "Tidak Ada Data",
				];
				$this->response($message, REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$message = [
				'status' => false,
				'message' => "Sessiom Habis",
			];
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function jadwalMahasiswa_get()
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$Output = $this->JadwalModel->getjadwalmahasiswa($is_valid_token['data']->id);
			if ($Output > 0 && !empty($Output)) {
				if ($Output['message'] == 'TemKrsm') {
					$Datakirim = array(
						'data' => $Output,
					);
					$message = [
						'status' => true,
						'data' => $Datakirim,
						'set' => $Output['message'],
					];
					$this->response($message, REST_Controller::HTTP_OK);
				} else if ($Output['message'] == 'Jadwal') {
					if ($Output['status'] == true) {
						$Datakirim = array(
							'data' => $Output['Jadwal'],
						);
						$message = [
							'status' => true,
							'data' => $Datakirim,
							'set' => $Output['message'],
							'mahasiswa' => $Output['mahasiswa'],
							'semester' => $Output['semester'],
						];
						$this->response($message, REST_Controller::HTTP_OK);
					} else {
						$message = [
							'status' => false,
							'data' => array(),
							'message' => "Data Jadwal Belum Tersedia Segera Hubungi Bagian BAAK Untuk ketersediaan",
						];
						$this->response($message, REST_Controller::HTTP_NOT_FOUND);
					}
				} else if ($Output['message'] == 'Krsm') {
					$Datakirim = array(
						'status' => false,
						'data' => $Output,
					);
					$message = [
						'status' => true,
						'data' => $Datakirim,
						'set' => $Output['message'],
					];
					$this->response($message, REST_Controller::HTTP_OK);
				} else if ($Output['message'] == 'Daftar Ulang') {
					$Datakirim = array(
						'status' => false,
						'data' => $Output,
					);
					$message = [
						'status' => true,
						'data' => $Datakirim,
						'set' => $Output['message'],
					];
					$this->response($message, REST_Controller::HTTP_OK);
				} else {
					$message = [
						'status' => false,
						'set' => $Output['message'],
						'dataReg' => $Output['dataReg'],
					];
					$this->response($message, REST_Controller::HTTP_OK);
				}
			} else {
				$message = [
					'status' => false,
					'message' => "Tidak Ada Data",
				];
				$this->response($message, REST_Controller::HTTP_NOT_FOUND);
			}
		} else {
			$message = [
				'status' => false,
				'message' => "Sessiom Habis",
			];
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function pengajuanKRS_post()
	{
		header("Access-Control-Allow-Origin: *");
		header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Methods: POST");
		header("Access-Control-Max-Age: 3600");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
		$_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
		$Output = $this->JadwalModel->pengajuanKRS_post($_POST);
		if ($Output > 0 && !empty($Output)) {
			$message = [
				'status' => true,
				'message' => "Pengajuan KRS Anda Berhasil",
			];
			$this->response($message, REST_Controller::HTTP_OK);
		} else {
			$message = [
				'status' => false,
				'message' => "Pengajuan KRS Anda Gagal",
			];
			$this->response($message, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function hapus_delete($a = null)
	{
		$this->load->library('Authorization_Token');
		$is_valid_token = $this->authorization_token->validateToken();
		if ($is_valid_token['status'] === true) {
			$idjadwal = $this->uri->segment(4);
			$result = $this->JadwalModel->hapus($idjadwal);
			if ($result) {
				$this->response($result, REST_Controller::HTTP_OK);
			} else {

				$this->response($result, REST_Controller::HTTP_BAD_REQUEST);
			}
			// $Output =
		}
	}

	public function jadwalByProdi_get()
	{
		$kdps = $_GET['kdps'];
		$Output = $this->JadwalModel->jadwal_praktikum($kdps);
		$this->response($Output, REST_Controller::HTTP_OK);
	}
}
