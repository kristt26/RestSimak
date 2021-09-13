<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class TahunAkademik extends \Restserver\Libraries\REST_Controller

{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('TahunAkademik_model', 'TahunAkademik');
    }
    public function TAAktif_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->TahunAkademik->getTAAktif();
            if ($Output > 0 && !empty($Output)) {
                $message = [
                    'status' => true,
                    'data' => $Output,
                    'message' => "Success!",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            } else {
                $message = [
                    'status' => false,
                    'message' => 'empty!!!',
                    'data' => json_encode(array()),
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }
        }
    }

    public function ambilTA_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $Output = $this->TahunAkademik->select();
            $this->response($Output, REST_Controller::HTTP_OK);
        } else {
            $this->response('Anda Tidak Memiliki Akses, Periksa Session Anda', REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}