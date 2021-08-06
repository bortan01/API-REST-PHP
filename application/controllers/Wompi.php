<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
require APPPATH . '/libraries/REST_Controller.php';
require('./vendor/autoload.php');



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
        $respuesta =$this->Wompi_model->obtenerToken();
        $this->response($respuesta, REST_Controller::HTTP_OK);
     
    }

    public function prueba_post($nombre, $numero)
    {
        echo "en prueba ", $nombre . " " . $numero;
    }
}