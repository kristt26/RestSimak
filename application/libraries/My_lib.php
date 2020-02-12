<?php defined('BASEPATH') or exit('No direct script access allowed');

class My_Lib
{
    public function __construct() {
        $this->CI =& get_instance();
    }

    public function FileName()
    {
        $fn = 15;
        $characters = '0123456789';
        $randomString = '';

        for ($i = 0; $i < $fn; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }
        return $randomString;
    }
}
