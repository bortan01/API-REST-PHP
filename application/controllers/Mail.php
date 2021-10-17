<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
   "https://admin.tesistours.com",
   "https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
   header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class Mail extends REST_Controller
{

   public function __construct()
   {
      //constructor del padre
      parent::__construct();
      $this->load->database();
      $this->load->model('Mail_model');
   }
   //INSERTAR
   public function send_post()
   {
      $bodyHTML ='
      <h2>Hola,ZORRA FUNCIONA desde el hosting</h2>
      <br>
      <br>
      <br>
      Mensaje final';
  
  
      $enviado = $this->Mail_model->metEnviar("titulo, del correo","estoy probando","juan.moz@ues.edu.sv","asuntox",$bodyHTML);
  
      if($enviado){
         // echo ("enviado");
         $respuesta=array(
				'err'=>FALSE,
				'mensaje'=>'Enviado'
			);
         $this->response($respuesta, REST_Controller::HTTP_OK);
      }else{
         // echo ("No se puede enviar el correo");
         $respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'No se puede enviar el correo'
			);
         $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);

      }
         ///****este este es codigo de boris */
     /* $data = $this->post();

      $respuesta = $this->Mail_model->enviar($data);
      if ($respuesta['err']) {
         $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
      } else {
         $this->response($respuesta, REST_Controller::HTTP_OK);
      }*/
   }
}