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
  public function showNull_get()
  {
    $data = $this->get();
    $respuesta = $this->Itinerario_model->obtenerNulos($data);
    $this->response($respuesta, REST_Controller::HTTP_OK);
  }

  public function calendar_get()
  {
    $data = $this->get();
    $respuesta = $this->Itinerario_model->obtenerCalendario($data);
    $this->response($respuesta, REST_Controller::HTTP_OK);
  }

  public function calendarSave_post()
  {
    $data = $this->post();

    
    if (!empty($data["eventos"])) {
      $eventos = json_decode($data["eventos"], true);
      $this->Itinerario_model->guardar($eventos, $data['id_tours']);
    }
    //ESTE ESPARA LOS SITIOS QUE YA ESTABAN EN EL CALENDARIO Y POSIBLEMENTE HAN SIDO ALTERADOS
    if (!empty($data["sitiosOld"])) {
      $sitios = json_decode($data["sitiosOld"], true);
      $this->Itinerario_model->editar($sitios,$data['id_tours']);
    }
    //ESTE RECIBE UN ARREGLO DE NUEVOS EVENTOS, LOS QUE APARECEN LUEGO DE DARLE AGREGAR VIENE CON ALLDAY NULL
    if (!empty($data["sitiosNew"])) {
      $sitios = json_decode($data["sitiosNew"], true);
      $this->Itinerario_model->guardar($sitios, $data['id_tours']);
    }
    $respuesta = array(
      'err'         => FALSE,
      'mensaje'     => 'Registro(s) guardado(s) Exitosamente',
      'itinerario'  => null
    );
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
  public function elimination_delete()
  {
    $data = $this->delete();

    if (!isset($data["id_itinerario"])) {
      $respuesta = array(
        'err' => TRUE,
        'mensaje' => "no se encontro el id itinerario"
      );
      $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
    } else {
      $respuesta = $this->Itinerario_model->borrar($data);
      if ($respuesta['err']) {
        $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
      } else {
        $this->response($respuesta, REST_Controller::HTTP_OK);
      }
    }
  }
}