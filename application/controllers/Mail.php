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

   //SOLO PARA UN DESTINATARIO
   public function sendUno_post(){

      $data = $this->post();

      //print_r($data);
		//die();
      /*$bodyHTML ='
      <h2>Varios correos a la vez desde la BD</h2>
      <br>
      <br>
      <br>
      Mensaje final';*/
  
  
      $enviado = $this->Mail_model->metEnviarUno($data['titulo'],"Juan","juan.moz@ues.edu.sv",$data['asunto'],$data['body']);
  
      if($enviado){
         // echo ("enviado");
         $respuesta=array(
				'err'=>FALSE,
				'mensaje'=> $enviado['enviado']
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


   //ESTA FUNCION ES PARA MAS DE UN DESTINATARIO
   public function send_post()
   {
      $data = $this->post();
     /* $bodyHTML ='
      <h2>Varios correos a la vez desde la BD</h2>
      <br>
      <br>
      <br>
      Mensaje final';*/
  
  
      $enviado = $this->Mail_model->metEnviar($data['titulo'],"","juan.moz@ues.edu.sv",$data['asunto'],$data['body']);
  
      if($enviado){
         // echo ("enviado");
         $respuesta=array(
				'err'=>FALSE,
				'mensaje'=> $enviado['enviado']
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