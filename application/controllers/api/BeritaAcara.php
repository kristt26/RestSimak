<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class BeritaAcara extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Methods:  GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Origin: *");
        // header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $this->load->model('BeritaAcara_model', 'BeritaAcaraModel');
    }
    public function AddBaMengajar_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $akses;
            $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            if(is_null($_GET)){
                $akses = true;
            }else{
                $akses = false;
            }
            $Output = $this->BeritaAcaraModel->insert($data, $akses);
            if ($Output['status']) {
                $message = [
                    "data" => $Output['id']
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => "Jam pengisian berita acara telah berakhir silahkan hubungi bagian BAAK"
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            $message = [
                "data" => "Session anda telah habis"
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function LaporanBa_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $result = $this->BeritaAcaraModel->GetLaporan($is_valid_token['data']);
            $message =
                [
                "data" => $result,
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $message = [
                "data" => "Session anda telah habis",
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }

    }

    public function GetBaMengajar_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $idjadwal = $this->get('idjadwal');
            $nidn = $this->get('nidn');
            $Output = $this->BeritaAcaraModel->get($idjadwal, $nidn);
            if (!empty($Output)) {
                $message = [
                    "status" => true,
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "status" => true,
                    "data" => [],
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        } else {
            $message = [
                "data" => "Session anda telah habis",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function updateBaMengajar_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        // $data =json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
        if ($is_valid_token['status'] === true) {
            $decoded_input = json_decode(file_get_contents("php://input"), true);
            $Output = $this->BeritaAcaraModel->update($decoded_input);
            if ($Output) {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }

        } else {
            $message = [
                "data" => "Session Habis",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function Persetujuan_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->BeritaAcaraModel->Persetujuan();
            if ($Output) {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                "data" => "Session Habis",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function Rekap_put()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->BeritaAcaraModel->rekap();
            if ($Output) {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                "data" => "Session Habis",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function laporan_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->BeritaAcaraModel->laporan();
            if ($Output) {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                "data" => "Session Habis",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function histori_post()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $data = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->BeritaAcaraModel->histori($data);
            if ($Output) {
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $this->response('Tidak ada data', REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function thba_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->BeritaAcaraModel->thba();
            if ($Output) {
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $this->response('data tidak ditemukan', REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->response( "Session Habis", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function HapusBa_delete()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $decoded_input = json_decode(file_get_contents("php://input"), true);
            $id = $decoded_input["idbamengajardosen"];
            $Output = $this->BeritaAcaraModel->hapus($id);
            if ($Output) {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    "data" => $Output,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                "data" => "Session Habis",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
