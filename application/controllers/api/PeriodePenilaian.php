<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class PeriodePenilaian extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('PeriodePenilaian_model', 'PeriodePenilaianModel');
    }
    public function GetPeriodeAktif_get()
    {
        $Output = $this->PeriodePenilaianModel->Getperiode();
        if (!empty($Output)) {
            $message = [
                'status' => true,
                'data' => $Output
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'status' => false,
                'data' => [],
            ];
            $this->response($message, REST_Controller::HTTP_NOT_FOUND);
        }
    }
}