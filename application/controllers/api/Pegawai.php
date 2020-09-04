<?php

defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';
class Pegawai extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('Pegawai_model', 'PegawaiModel');
    }

    public function getpegawai_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $idpegawai = $this->uri->segment(3);
            $Output = $this->PegawaiModel->select($idpegawai);
            $this->response($Output, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'message' => "Session Anda telah kadaluarsa",
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

}

/* End of file Controllername.php */
