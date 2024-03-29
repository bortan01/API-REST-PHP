<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
     "https://admin.martineztraveltours.com",
    "https://martineztraveltours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class Usuario extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Usuario_model');
        $this->load->model('Firebase_model');
        $this->load->model('Imagen_model');
    }
    public function registroUser_post()
    {
        // echo date("Hismd") ;
        // die();
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
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $dataUsuario = $this->Usuario_model->getOneUser(array('uuid' => $respuesta['user_uuid']));
                if ($dataUsuario['err']) {
                    $this->response($dataUsuario, REST_Controller::HTTP_BAD_REQUEST);
                } else {

                    $respuesta['id_cliente'] =  $dataUsuario["id_cliente"];
                    $respuesta['nombre'] =  $dataUsuario["nombre"];
                    $respuesta['correo'] =  $dataUsuario["correo"];
                    $respuesta['nivel'] =  $dataUsuario["nivel"];
                    $respuesta['celular'] =  $dataUsuario["celular"];
                    $respuesta['fbToken'] =  $dataUsuario["fbToken"];
                    $respuesta['dui'] =  $dataUsuario["dui"];
                    $respuesta['foto'] =  $dataUsuario["foto"];

                    if ($dataUsuario['activo'] ==  1) {
                        $this->response($respuesta, REST_Controller::HTTP_OK);
                    } else {
                        $respuesta  =  array("err" => TRUE, "mensaje" => "INVALID_EMAIL");
                        $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                    }
                }
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
    public function obtenerUsuarioByChat_get()
    {
        $respuesta =  $this->Usuario_model->getUserByChat();
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function obtenerChat_post()
    {
        $data = $this->post();
        if (isset($data["user_1"])) {
            $user1 = $data["user_1"];
        } else {
            $admin = $this->Usuario_model->getAdminByChat();
            if ($admin  == null) {
                $this->response(array('mensaje' =>  'no  hay administrador'), REST_Controller::HTTP_BAD_REQUEST);
            }
            $user1 = $admin->uuid;
        }
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

        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if ($this->form_validation->run('ActualizarUsuario')) {
            if (isset($data["password"])) {
                $respuestaFirebase = $this->Firebase_model->cambioPassword($data['correo'], $data["password"]);
                if ($respuestaFirebase["err"]) {
                    $this->response($respuestaFirebase, REST_Controller::HTTP_BAD_REQUEST);
                }
            }

            $respuesta = $this->Usuario_model->editar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
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
    public function restaurar_put()
    {
        $data = $this->put();
        if (!isset($data["id_cliente"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de usuario');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $campos = array('id_cliente' => $data["id_cliente"], 'activo' => TRUE);

            try {
                $respuesta = $this->Usuario_model->restaurar($campos);
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
        if (isset($data['email'])) {
            $respuesta = $this->Firebase_model->cambioPassword($data['email'], "12344596");
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function updateFecha_put()
    {
        $data = $this->put();
        // $data['ultimaConexion'] = DateTime::createFromFormat('d/m/Y',  new DateTime())->format('Y-m-d');
        date_default_timezone_set('America/El_Salvador');
        $data['ultimaConexion'] = date("Y-m-d H:i:s A");
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if (isset($data['uuid'])) {

            $respuesta = $this->Usuario_model->actualizarFechaChat($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Es necesario el UUID',

            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function datosUsuario_get()
    {

        $data = $this->get();
        $usuario = $this->Usuario_model->get_datosUsuario($data);

        if (isset($usuario)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $usuario

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function cotizacionesRealizadas_get()
    {

        $data = $this->get();
        $serAdquiridos = $this->Usuario_model->get_cotizacionesRealizadas($data);

        if (isset($serAdquiridos)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $serAdquiridos

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function encomiendasRealizadas_get()
    {

        $data = $this->get();
        $serAdquiridos = $this->Usuario_model->get_encomiendasRealizadas($data);

        if (isset($serAdquiridos)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $serAdquiridos

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }


    public function vehiculosAlquilados_get()
    {

        $data = $this->get();
        $serAdquiridos = $this->Usuario_model->get_vehiculosAlquilados($data);

        if (isset($serAdquiridos)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $serAdquiridos

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function toursAdquiridos_get()
    {

        $data = $this->get();
        $serAdquiridos = $this->Usuario_model->get_toursAdquiridos($data);

        if (isset($serAdquiridos)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $serAdquiridos

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function vuelosCotizaciones_get()
    {

        $data = $this->get();
        $serAdquiridos = $this->Usuario_model->get_vuelosCotizaciones($data);

        if (isset($serAdquiridos)) {

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Cargado correctamente',
                'servicios' => $serAdquiridos

            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al cargar los datos.',
                'servicios' => null

            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        }
    }
    public function obtenerAdminByChat_get()
    {
        $respuesta =  $this->Usuario_model->getAdminByChat();

        if ($respuesta  == null) {
            $this->response(array('mensaje' => 'no se encontro administrador'), REST_Controller::HTTP_BAD_REQUEST);
        }
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}
