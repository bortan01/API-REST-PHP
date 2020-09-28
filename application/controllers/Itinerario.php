<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Itinerario extends REST_Controller
{
    public function obtenerItinerario_get()
    {
      $this->load->model("Utils_model");
      $resuult =$this->Utils_model->selectTabla("tours_paquete", array("id_tours"=>1),true);
      print_r($resuult);
    }

}