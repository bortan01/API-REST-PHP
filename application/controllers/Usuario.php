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
        $this->load->model('Firebase_model');
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
            $respuesta = $this->Usuario_model->createAccount($data);
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


            $respuesta = $this->Firebase_model->loginEmailPassword($email, $password);
            if ($respuesta['err']) {
                $this->response($respuesta["mensaje"], REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta["message"], REST_Controller::HTTP_OK);
            }
        }
    }
    public function obtenerUsuario_get()
    {
        $data = $this->get();
        

        $respuesta =  $this->Usuario_model->getUser($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
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
    public function enviarNotificacion_post()
    {

        $tokens = ["cg7jHTZxRmuLoLePCmVfR3:APA91bEaEaN0fw_iWrphfXd9uk1JcyIYBk0k3XAqh4ESLOKmzRmFCPx5umvhRKlsy4URu0n13ft_fyPI_cBoqTfxY7WNe9No69bz9ANvrVEjnU_dmrVsaLPGbuhQ3oYfwVPaUISHAChX"];
        $respuesta =   $this->Firebase_model->EnviarNotificacionSDK();
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function update_put()
    {
        $data = $this->put();
        ///VERIFICANDO SI EXISTE EL ID PRINCIPAL DE LA TABLA
        if (!isset($data["id_cliente"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de usuario');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Usuario_model->editar($data);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            } catch (\Throwable $th) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
            }
        }
    }
    public function elimination_delete()
    {
        $data = $this->delete();
        if (!isset($data["id_cliente"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de usuario');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $campos = array('id_cliente' => $data["id_cliente"], 'activo' => FALSE);

            try {
                $respuesta = $this->Usuario_model->borrar($campos);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            } catch (\Throwable $th) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
            }
        }
    }
    public function generarEnlace_post()
    {
        $data = $this->post();
        $this->load->library('form_validation');
        $this->form_validation->set_data($data);



        //corremos las reglas de validacion
        if (!$this->form_validation->run('crearEnlace')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $this->load->model('Imagen_model');
            $imagen = $this->Imagen_model->guardarImagen('ENLACE_PAGO', 2020);

            if ($imagen["err"]) {
                $respuesta = array(
                    "err" => TRUE,
                    "mensaje" => "har errores en el envio de informacion",
                    "errores" => array("foto" => "La foto es un campo obligatorio")
                );
                $this->response($respuesta, REST_Controller::HTTP_OK);
            } else {
                $urlImagen      = $imagen["path"];
                $monto          = $data["monto"];
                $nombreProducto = $data["nombreProducto"];
                $descripcion    = $data["descripcion"];

                $webHook        = "";
                $this->load->model('Wompi_model');
                $urlImagen = 'https://wompistorage.blob.core.windows.net/imagenes/f7c5e956-5fa4-4cf6-9480-aaaa855b1d7e.jpg';
                $respuesta =  $this->Wompi_model->crearEnlacePagoHttp($monto, $nombreProducto, $descripcion, $urlImagen, $webHook);
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        }
    }
    public function updatePassword_put()
    {
        $data = $this->put();
        if (isset($data['email'])) {;
            $respuesta =   $this->Firebase_model->cambioPassword($data['email']);
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}