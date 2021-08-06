<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
    "https://admin.tesistours.com",
    "https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require('./vendor/autoload.php');
require APPPATH . '/libraries/REST_Controller.php';



class Wompi extends REST_Controller
{
    public function __construct()
    {
        //llamado del constructor del padre 
        parent::__construct();
        $this->load->database();
        $this->load->library('image_lib');
        $this->load->library('upload');
        $this->load->model('Wompi_model');
    }

    public function obtenerToken_get()
    {
        $respuesta = $this->Wompi_model->obtenerToken();
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }

    public function prueba_post($nombre, $numero)
    {
        echo "en prueba ", $nombre . " " . $numero;
    }
}