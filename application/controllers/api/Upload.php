<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Upload extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/pdf; charset=UTF-8,application/json ");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        // $this->load->model('Upload_model', 'UploadModel');
    }
    public function UploadFile_post()
    {
        $folder = $_GET['folder'];
        $file = $_FILES['file']['name'];
        $x = explode('.', $file);
        $ekstensi = strtolower(end($x));
        $this->load->library('my_lib');
        $config['upload_path'] = './assets/file/';
        $config['allowed_types'] = 'gif|jpg|png|pdf';
        $config['file_name'] = $this->my_lib->FileName();
        $config['overwrite'] = true;
        $config['max_size'] = 2048; // 2MB
        // $config['max_width']            = 1024;
        // $config['max_height']           = 768;
        $nama = $this->my_lib->FileName() . '.' . $ekstensi;
        $path = './assets/file/'.$folder.'/' . $nama;
        if (move_uploaded_file($_FILES['file']['tmp_name'], $path)) {
            $message = [
                'data' => $nama,
            ];
            $this->response($message, REST_Controller::HTTP_OK);
        } else {
            $message = [
                'data' => "",
            ];
            $this->response($message, REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function deleteFile_delete()
    {
        $berkas = $_GET['berkas'];
        $folder = $_GET['folder'];
        $path = './assets/file/'.$folder.'/' . $berkas;
        if(unlink($path)){
            $message=[
                'status'=>true,
            ];
            $this->response($message,REST_Controller::HTTP_OK);
        }else{
            $message=[
                'status'=>false,
            ];
            $this->response($message,REST_Controller::HTTP_BAD_REQUEST);
        }
    }

    public function ReadFile_get()
    {
        $filename = './assets/file/805748704012562.pdf';
        // $handle = fopen($filename , 'r');
        // $data = fread($handle,filesize($filename ));
        // ob_clean();
        // flush();
        // readfile($filename);
        $string = ['file' => readfile($filename)];
        // $this->response($string, REST_Controller::HTTP_OK);
    }
}
