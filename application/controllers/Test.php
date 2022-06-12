<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.martineztraveltours.com');
require APPPATH . '/libraries/REST_Controller.php';
class Test extends REST_Controller
{

    public function enviar_get()
    {
        $this->load->model('Firebase_model');
        $respuesta =  $this->Firebase_model->prueba();

        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}
