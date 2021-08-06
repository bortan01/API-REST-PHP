<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
    "https://admin.tesistours.com",
    "https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class ReservaTour extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('ReservaTour_model');
    }

    public function save_post()
    {
        $data = $this->post();

        $respuesta = $this->ReservaTour_model->guardar($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }

    public function mensaje_post()
    {
        $this->load->model('Firebase_model');

        $respuesta = $this->Firebase_model->EnviarNotificacion();

        if (isset($respuesta['err'])) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}