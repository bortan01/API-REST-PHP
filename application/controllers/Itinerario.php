<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Itinerario extends REST_Controller
{
  public function __construct()
  {
    parent::__construct();
    $this->load->model("Itinerario_model");
    $this->load->model("Utils_model");
    $this->load->database();
  }


  public function show_get()
  {
    $data = $this->get();
    $respuesta = $this->Itinerario_model->obtener($data);


    $this->response($respuesta, REST_Controller::HTTP_OK);
  }
  public function save_post()
  {
    $data = $this->post();
    
    //$this->Itinerario_model->verificar_campos($data);
    $respuesta = $this->Itinerario_model->guardar($data);
    if ($respuesta['err']) {
      $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
    } else {
      $this->response($respuesta, REST_Controller::HTTP_OK);
    }
  }
  public function update_put()
  {
    $data = $this->put();
    
    $respuesta = $this->Itinerario_model->editar($data);
    if ($respuesta['err']) {
      $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
    } else {
      $this->response($respuesta, REST_Controller::HTTP_OK);
    }
  }
}