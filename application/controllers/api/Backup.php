<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;

require APPPATH . '/libraries/REST_Controller.php';

class Backup extends \Restserver\Libraries\REST_Controller
{
    public function __construct($config = 'rest')
    {
        parent::__construct($config);
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        header("Access-Control-Allow-Methods: POST, GET, DELETE, PUT, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
        $this->load->model('Dosen_model', 'DosenModel');
    }

    public function datatable_get()
    {
        $this->load->dbutil();
        $this->load->library('Authorization_Token');
        $is_valid_token = $this->authorization_token->validateToken();
        if ($is_valid_token['status'] === true) {
            $prefs = array(
                'tables'        => array('mahasiswa'),   // Array of tables to backup.
                'ignore'        => array(),                     // List of tables to omit from the backup
                'format'        => 'txt',                       // gzip, zip, txt
                'filename'      => 'mybackup.sql',              // File name - NEEDED ONLY WITH ZIP FILES
                'add_drop'      => FALSE,                        // Whether to add DROP TABLE statements to backup file
                'add_insert'    => TRUE,                        // Whether to add INSERT data to backup file
                'newline'       => "\n",                         // Newline character used in backup file
                'foreign_key_checks' =>TRUE
            );
            $dbs = $this->dbutil->backup($prefs);
            $a = explode("INSERT",$dbs);
            $this->response($dbs, REST_Controller::HTTP_OK);
        }
    }
}
