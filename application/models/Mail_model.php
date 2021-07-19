<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('./vendor/autoload.php');
require('./vendor/phpmailer/phpmailer/src/Exception.php');
require('./vendor/phpmailer/phpmailer/src/PHPMailer.php');
require('./vendor/phpmailer/phpmailer/src/SMTP.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;

class Mail_model extends CI_Model
{
   function __construct()
   {
   }
   public function enviar($data)
   {
      $mail = new PHPMailer(true);

      try {
         //Server settings
         $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         $mail->Username   = 'jmozalfaro@gmail.com';                     //SMTP username
         $mail->Password   = 'pliqgifjbddtfxdp';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
         $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

         //Recipients
         $mail->setFrom('from@example.com', 'Mailer');
         $mail->addAddress('joe@example.net', 'Joe User');     //Add a recipient
         $mail->addAddress('ellen@example.com');               //Name is optional
         $mail->addReplyTo('info@example.com', 'Information');
         $mail->addCC('cc@example.com');
         $mail->addBCC('bcc@example.com');

         //Attachments
         // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
         // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

         //Content
         $mail->isHTML(true);                                  //Set email format to HTML
         $mail->Subject = 'Here is the subject';
         $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
         $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

         $mail->send();
         $respuesta = array('err' => FALSE, 'mensaje' => 'Mensaje enviado');
         return $respuesta;
      } catch (Exception $e) {
         $mensaje = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
         $respuesta = array('err' => TRUE, 'mensaje' =>"No se pudo enviar el mensaje {$mail->ErrorInfo}");

         return $respuesta;
      }
   }
}