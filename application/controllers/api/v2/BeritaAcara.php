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

    public function index()
    {
        echo json_encode(['data']);
    }
}
