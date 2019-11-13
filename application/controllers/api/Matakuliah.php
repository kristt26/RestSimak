<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Matakuliah extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        $this->load->model('Matakuliah_model', 'MatakuliahModel');
    }
    public function GetMatakuliah_get()
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: GET");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

        $Output = $this->MatakuliahModel->AmbilMatakuliah();
        $message = [
            'status' => true,
            'data' => $Output
        ];
        $this->response($message, REST_Controller::HTTP_OK);
    }
}