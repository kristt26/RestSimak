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
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Mahasiswa_model', 'MahasiswaModel');
    }
    public function GetMahasiswa_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $npm = $this->get('npm');   
            $Output = $this->MahasiswaModel->AmbilMahasiswa($npm);
            if (!empty($Output)) {
                $message = [
                    'status' => true,
                    'data' => $Output['data'],
                    'message' => "Success!",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'data' => [],
                    'message' => "Kosong",
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }else{
            $message = [
                'status' => false,
                'message' => "anda tidak memiliki akses",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    public function DataMahasiswa_get($npm=null)
    {
        $Output = $this->MahasiswaModel->GetMahasiswa($npm);
        if (!empty($Output)) {
            $message = [
                'status' => true,
                'data' => $Output['data'],
                'message' => "Success!",
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => false,
                'data' => [],
                'message' => "Kosong",
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}