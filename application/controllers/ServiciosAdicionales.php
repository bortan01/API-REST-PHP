<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class ServiciosAdicionales extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('Servicios_adicionales_model');
    }
    public function save_post()
    {
        sleep (1);
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

          //corremos las reglas de validacion
        if ($this->form_validation->run('insertarServicio')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN

            $respuesta =  $this->Servicios_adicionales_model->guardar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function obtenerServicio_get()
    {
        $data = $this->get();

        $respuesta =  $this->Servicios_adicionales_model->obtenerServicio($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function update_put()
    {
        $data = $this->put();
        ///VERIFICANDO SI EXISTE EL ID PRINCIPAL DE LA TABLA
        if (!isset($data["id_servicios"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de servicio');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Servicios_adicionales_model->editar($data);
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
        ///VERIFICANDO SI EXISTE EL ID PRINCIPAL DE LA TABLA
        if (!isset($data["id_servicios"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de servicio');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Servicios_adicionales_model->elimination($data);
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
}