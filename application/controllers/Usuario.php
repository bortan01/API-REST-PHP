<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Usuario extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Usuario_model');
    }

    public function registroUser_post()
    {
        //  $data = $this->post();
        $fullname = "test7@gmail.com";
        $username =  "test7@gmail.co";
        $email = "test7@gmail.co";
        $password = "test7@gmail.co";
        $respuesta = $this->Usuario_model->createAccount($fullname, $username, $email, $password);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }


    public function loginUser_post()
    {
        //  $data = $this->post();
        $username = "test2@gmail.com";
        $password = "test2@gmail.com";

        $respuesta = $this->Usuario_model->loginUser($username, $password);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}