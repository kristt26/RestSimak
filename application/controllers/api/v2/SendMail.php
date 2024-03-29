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
			$mail->SMTPDebug = SMTP::DEBUG_SERVER;
			$mail->isSMTP();
			// $mail->Host = 'srv26.niagahoster.com';
			$mail->Host = 'smtp-relay.sendinblue.com';
			$mail->SMTPAuth = true;
			// $mail->Username = 'kristt26@stimiksepnop.ac.id';
			$mail->Username = 'ocph23@gmail.com';
			$mail->Password = 'js7X8SwIrbxDkR56';
			$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
			$mail->Port = 587; //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
			$mail->setFrom('ocph23@gmail.com', 'Testing');
			$mail->addAddress('kristt26@gmail.com', "Ajenkris");
			$mail->isHTML(true);
			$mail->Subject = 'Undangan';
			$mail->msgHTML("Testing");
			$result = $mail->send();
			$this->response($result, REST_Controller::HTTP_OK);
		} catch (Exception $e) {
			$mail->ErrorInfo;
			$this->response($mail->ErrorInfo, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}
