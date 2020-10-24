<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class SitioTuristico extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('SitioTuristico_model');
    }

    public function index()
    {
        $this->load->view('upload_form', array('error' => ' '));
    }

    public function save_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarSitio')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN

            try {
                //code...
                $respuesta = $this->SitioTuristico_model->guardar($data);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            } catch (\Throwable $th) {
                //throw $th;
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

    public function show_get()
    {
        $data = $this->get();
        ///si no mandan id por defecto sera 0
        $sitio = $this->SitioTuristico_model->obtenerSitio($data);
        $this->response($sitio, REST_Controller::HTTP_OK);
    }
    public function update_put()
        {
            $data = $this->put();
        ///VERIFICANDO SI EXISTE EL ID PRINCIPAL DE LA TABLA
        if (!isset($data["id_sitio_turistico"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->SitioTuristico_model->editar($data);
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
        if (!isset($data["id_sitio_turistico"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->SitioTuristico_model->elimination($data);
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