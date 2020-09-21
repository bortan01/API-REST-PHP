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
        $fullname = "test55@gmail.com";
        $username =  "test55@gmail.co";
        $email = "test55@gmail.co";
        $password = "123456";
        $respuesta = $this->Usuario_model->createAccount($fullname, $username, $email, $password);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function loginUser_post()
    {
        //  $data = $this->post();
        $email = "test44@gmail.co";
        $password = "123456";

        //$respuesta = $this->Usuario_model->loginUser($email, $password);
        $this->load->model('Firebase_model');
        $login = $this->Firebase_model->loginEmailPassword($email, $password);
        $this->response($login, REST_Controller::HTTP_OK);
    }
    public function obtenerUsiario_get()
    {
        $uid = "sFn3HUM3FIRjBLVjNnVo9DcJQWf1";
        $this->load->model('Firebase_model');
        $this->Firebase_model->obtnerUsuarioUID($uid);
    }
}