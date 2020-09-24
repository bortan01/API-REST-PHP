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
        $data = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if (!$this->form_validation->run('insertarUsuario')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $fullname = $data["fullname"];
            $username =  $data["username"];
            $email    = $data["email"];
            $password = $data["password"];

            $respuesta = $this->Usuario_model->createAccount($fullname, $username, $email, $password);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        }
    }
    public function loginUser_post()
    {
        $data = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);



        //corremos las reglas de validacion
        if (!$this->form_validation->run('loginUsuario')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $email    = $data["username"];
            $password = $data["password"];

            $this->load->model('Firebase_model');
            $respuesta = $this->Firebase_model->loginEmailPassword($email, $password);
            if ($respuesta['err']) {
                $this->response($respuesta["message"], REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta["message"], REST_Controller::HTTP_OK);
            }
        }
    }
    public function obtenerUsuarios_get()
    {

        $respuesta =  $this->Usuario_model->getUsers();
        if ($respuesta['err']) {
            $this->response($respuesta["message"], REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function obtenerChat_post()
    {
        $data = $this->post();

        $user1 = $data["user_1"];
        $user2 = $data["user_2"];
        $respuesta =  $this->Usuario_model->createChatRecord($user1, $user2);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }

    public function logout_post()
    {
        $resp = array('status' => 200, 'message' => 'User Logout Successfully');
        echo json_encode($resp);
    }
}