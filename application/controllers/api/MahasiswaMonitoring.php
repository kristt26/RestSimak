<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class MahasiswaMonitoring extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest') {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, PUT, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('MahasiswaMonitoring_model', 'MahasiswaMonitoringModel');
        $this->load->library('Authorization_Token');
        
    }

    public function Select_get()
    {
        $isValidate = $this->authorization_token->validateToken();
        if($isValidate['status']===true){
            $result = $this->MahasiswaMonitoringModel->get($isValidate['data']);
            if($result['status']=='Mahasiswa'){
                if(!empty($result['data'])){
                    $message = [
                        'pesan' => "PERINGATAN",
                        'data' => $result['data']
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                }else{
                    $message = [
                        'pesan' => "true",
                        'data' => []
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                }
            }else{
                $message=[
                    'data' => $result
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
            
            
        }else{
            $message = [
                'message' => 'Anda tidak memiliki Akses',
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
    public function Prodi_get()
    {
        $isValidate = $this->authorization_token->validateToken();
        if($isValidate['status']===true){
            $result = $this->MahasiswaMonitoringModel->prodi($isValidate['data']);
            $mahasiswa = $this->MahasiswaMonitoringModel->mahasiswa_prodi($isValidate['data']);
            if($result['status']=='Mahasiswa'){
                if(!empty($result['data'])){
                    $item=[
                        'mahasiswa'=>$mahasiswa,
                        'warning'=>$result['data']
                    ];
                    $message = [
                        'pesan' => "PERINGATAN",
                        'data' => $item
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                }else{
                    $message = [
                        'pesan' => "true",
                        'data' => []
                    ];
                    $this->response($message, REST_Controller::HTTP_OK);
                }
            }else{
                $message=[
                    'mahasiswa'=>$mahasiswa,
                    'warning' => $result
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
            
            
        }else{
            $message = [
                'message' => 'Anda tidak memiliki Akses',
            ];
            $this->response($message, REST_Controller::HTTP_UNAUTHORIZED);
        }
    }
}