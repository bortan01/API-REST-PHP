<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Reserva extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('Reserva_model');
    }

    public function save_post()
    {
        $data = $this->post();

        $respuesta = $this->Reserva_model->guardar($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}