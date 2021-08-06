<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
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

      $data = $this->post();

      $respuesta = $this->Mail_model->enviar($data);
      if ($respuesta['err']) {
         $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
      } else {
         $this->response($respuesta, REST_Controller::HTTP_OK);
      }
   }
}