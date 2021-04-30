<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Imagen extends REST_Controller
{
    public function __construct()
    {
        //constructor del padre
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
    }
    public function save_post()
    {
        $tipo = $_POST["tipo"];
        $identificador = $_POST["identificador"];
        $respuesta =  $this->Imagen_model->guardarImagen($tipo, $identificador);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }

    public function delete_post()
    {
        $id = $_POST["key"];
        $this->Imagen_model->eliminarImagen($id);
        $respuesta = array("key" => $id, "initialPreview" => array(), "initialPreviewConfig" => array(), "initialPreviewThumbTags" => array(), "append" => TRUE);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function show_get()
    {
        $data          = $_GET;
        $tipo          = $data["tipo"];
        $identificador = $data["identificador"];

        $respuesta = $this->Imagen_model->obtenerImagen($tipo, $identificador);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function apdate_post()
    {

        if (count($_FILES)) {

            $tipo = $_POST["tipo"];
            $identificador = $_POST["identificador"];
            $this->Imagen_model->actualizarGaleria($tipo, $identificador);
            $respuesta = array('mensaje' => 'imagen actualizada');
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array('mensaje' => 'no se seleccionono ninguna imagen');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function savePhotoPerfil_post()
    {
        $tipo = $_POST["tipo"];
        $identificador = $_POST["identificador"];
        $this->load->model('Imagen_model');
        $this->Imagen_model->eliminarGaleria($tipo, $identificador);
        $respuesta = $this->Imagen_model->guardarImagen($tipo, $identificador);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}