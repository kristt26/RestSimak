<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

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
        $this->load->model('Pengumuman_model', 'PengumumanModel');
    }
    public function Simpan_post()
    {
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->PengumumanModel->insert($_POST);
            if($Output['status']){
                $message = [
                    'data' => $Output['data']
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }else{
                $message = [
                    'message' => "Gagal Simpan"
                ];
                $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
            }
        }
    }

    public function Ambil_get()
    {
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $_POST = json_decode($this->security->xss_clean($this->input->raw_input_stream), true);
            $Output = $this->PengumumanModel->get();
            $message= 
            [
                'data' => $Output
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }
}