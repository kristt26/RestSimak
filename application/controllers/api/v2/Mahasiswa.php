<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Mahasiswa extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('v2/Mahasiswa_model', 'MahasiswaModel');
    }
    public function GetMahasiswa_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $npm = $this->uri->segment(3);

        if ($is_valid_token['status'] === true && !$this->authorization_token->userInRole('Mahasiswa')) {
            $Output = $this->MahasiswaModel->AmbilMahasiswa($npm);
            if ($Output) {
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => "Data tidak ditemukan",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                'message' => "Anda Tidak Memiliki Akses",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function GetIPKMahasiswa_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $npm = $this->uri->segment(3);
        $a = $npmm;
        if ($is_valid_token['status'] === true) {
            $Output = $this->MahasiswaModel->selectIPK($npm);
            if ($Output) {
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'message' => "data tidak ditemukan",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                'message' => "Anda Tidak Memiliki Akses",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function GetIPSMahasiswa_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $npm = $this->uri->segment(3);
        $a = $npmm;
        if ($is_valid_token['status'] === true) {
            $Output = $this->MahasiswaModel->selectIPS($npm);
            if ($Output) {
                $this->response($Output, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'message' => "data tidak ditemukan",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $message = [
                'status' => false,
                'message' => "Anda Tidak Memiliki Akses",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function DataMahasiswa_get()
    {
        $npm = $this->uri->segment(3);
        $Output = $this->MahasiswaModel->MahasiswaPublick($npm);
        if ($Output) {
            $this->response($Output, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => false,
                'data' => [],
                'message' => "Kosong",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function DataMhs_get()
    {
        $npm = $this->uri->segment(3);
        $Output = $this->MahasiswaModel->Matakuliah($npm);
        if ($Output) {
            $this->response($Output, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => false,
                'data' => [],
                'message' => "Kosong",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function Transkip_get()
    {
        $npm = $this->uri->segment(3);
        $this->response($npm, REST_Controller::HTTP_OK);
        // $Output = $this->MahasiswaModel->Matakuliah($npm);
        // if ($Output) {
        //     $this->response($Output, REST_Controller::HTTP_OK);
        // } else {
        //     $message = [
        //         'status' => false,
        //         'data' => [],
        //         'message' => "Kosong",
        //     ];
        //     $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        // }
    }
}
