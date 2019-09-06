<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Khsm extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct();
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Khsm_model', 'KhsmModel');
    }

    public function GetKhsm_get()
    {
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        $npm = $this->get('npm');
        if ($is_valid_token['status'] === true) {
            $Output = $this->KhsmModel->AmbilKhs($npm);
            if (!empty($Output && $Output != false)) {
                $message = [
                    'status' => true,
                    'data' => (object) $Output,
                    'message' => "Success",
                ];
                $this->response($message, REST_Controller::HTTP_OK);
            }
        }
    }

    public function AmbilIPK_get()
    {
        $npm = $this->get('npm');
        $Output = $this->KhsmModel->AmbilIPS($npm);
        if (!empty($Output && $Output != false)) {
            $Datas = array(
                'Data' =>[]
            );
            for ($i=1; $i <=8 ; $i++) { 
                $item = [
                    'Semester' => '',
                    'IPS' => '',
                    'SKS' => '',
                    'NSKS' => '',
                    'ListMatakuliah' => array()
                ];
                $nilai = 0;
                $sks = 0;
                foreach ($Output['Datas'] as $key => $value) {
                    if($value['smt']==$i){
                        $nilai += (int)$value['nxsks'];
                        $sks += (int)$value['sks'];
                        array_push($item['ListMatakuliah'], $value);
                    }
                }
                $IPS = $nilai / $sks;
                $item['Semester']= $i;
                $item['IPS']=$IPS;
                $item['SKS']=$sks;
                $item['NSKS']=$nilai;
                array_push($Datas['Data'], $item);
                
            }
            $message = [
                'status' => true,
                'data' => $Datas,
                'message' => "Success",
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        }
    }
}
