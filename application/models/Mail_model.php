<?php
defined('BASEPATH') or exit('No direct script access allowed');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './vendor/PHPMailer/Exception.php';
require './vendor/PHPMailer/PHPMailer.php';
require './vendor/PHPMailer/SMTP.php';

class Mail_model extends CI_Model
{
   private $mail=null;
   function __construct(){
    $this->mail= new PHPMailer();
    $this->mail->isSMTP();
    $this->mail->SMTPAuth = true;
    $this->mail->SMTPSecure = 'tls';
    $this->mail->Host ="smtp.gmail.com";
    $this->mail->Port =587;

    $this->mail->Username = "jmozalfaro@gmail.com";
    $this->mail->Password ="pliqgifjbddtfxdp";

   }

   //para enviar un correo
   public function metEnviar(string $titulo, string $nombre, string $correo, string $asunto,string $bodyHTML){

      $this->mail->setFrom("jmozalfaro@gmail.com",$titulo);
      $this->mail->addAddress($correo,$nombre);
      $this->mail->Subject = $asunto;
      $this->mail->Body    =$bodyHTML;
      $this->mail->isHTML(true);
      $this->mail->CharSet  = "UTF-8";

      return $this->mail->send();
}

   
}