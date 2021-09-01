<?php defined('BASEPATH') or exit('No direct script access allowed');
date_default_timezone_set('Asia/Tokyo');

use Restserver\Libraries\REST_Controller;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require APPPATH . '/libraries/REST_Controller.php';

class SendMail extends \Restserver\Libraries\REST_Controller
{

	public function __construct($config = 'rest')
	{
		parent::__construct($config);
		header("Access-Control-Allow-Methods:  GET, POST, OPTIONS, PUT, DELETE");
		header("Access-Control-Allow-Origin: *");
		// header("Content-Type: application/json; charset=UTF-8");
		header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
	}

	public function send_get()
	{
		$mail = new PHPMailer(true);
        try {
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host = 'mail.stimiksepnop.ac.id'; //Set the SMTP server to send through
            $mail->SMTPAuth = true; //Enable SMTP authentication
            $mail->Username = 'kristt26@stimiksepnop.ac.id'; //SMTP username
            $mail->Password = '26031988@Aj'; //SMTP password
            $mail->SMTPSecure = 'ssl'; //Enable implicit TLS encryption
            $mail->Port = 465; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            $mail->setFrom('emailfortesting1011@gmail.com', 'Testing');
            $mail->addAddress('kristt26@gmail.com', "Ajenkris"); //Add a recipient
            $mail->isHTML(true); //Set email format to HTML
            $mail->Subject = 'Undangan';
            // $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
            $mail->msgHTML("Testing");
            $result = $mail->send();
            // return true;
			$this->response($result, REST_Controller::HTTP_OK);
        } catch (Exception $e) {
			$mail->ErrorInfo;
			$this->response($mail->ErrorInfo, REST_Controller::HTTP_BAD_REQUEST);
        }
	}
}
