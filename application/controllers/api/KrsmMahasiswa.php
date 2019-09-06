<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class KrsmMahasiswa extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('KrsmMahasiswa_model', 'KrsmMahasiswaModel');
    }
    public function GetAll_get()
    {
        $npm = $this->get('npm');
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $npm = $this->get('npm');
            if (!$npm) {
                $message = [
                    'status' => false,
                    'message' => 'Tidak ada Parameter',
                    'data' => NULL,
                ];
                $this->response($message, REST_Controller::HTTP_NOT_FOUND);
            }else{
                $result = $this->KrsmMahasiswaModel->GetOneByTAAktif($npm);
                if ($result) {
                    $message = [
                        'status' => false,
                        'message' => 'Tidak ada Parameter',
                        'data' => $result,
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                } else {
                    $message = [
                        'status' => false,
                        'message' => 'Tidak ada Data',
                        'data' => NULL,
                    ];
                    $this->response($message, REST_Controller::HTTP_NOT_FOUND);
                }
            }
        }
    }
}
