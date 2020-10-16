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
        $respuesta = array();
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
        $data = $_GET;
        $tipo          = $data["tipo"];
        $identificador = $data["identificador"];

        $respuesta = $this->Imagen_model->obtenerImagen($tipo, $identificador);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}