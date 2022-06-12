<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
   "https://admin.martineztraveltours.com",
	"https://martineztraveltours.com/"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
   header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class Estadisticas extends REST_Controller
{
   public function __construct()
   {
      parent::__construct();
      $this->load->model("Estadisticas_model");
      $this->load->database();
   }
   public function ingresos_get()
   {
      $data = $this->get();
      $respuesta =  $this->Estadisticas_model->get_ingresos($data['start'], $data['end']);
      $this->response($respuesta, REST_Controller::HTTP_OK);
   }
}