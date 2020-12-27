<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Contacto extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('Contacto_model');
    }
    public function save_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarContacto')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN

            $respuesta =  $this->Contacto_model->guardar($data);
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
    public function show_get()
    {
        $data = $this->get();

        $respuesta =  $this->Contacto_model->obtenerContacto($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function update_put()
    {
        $data = $this->put();

        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if ($this->form_validation->run('ActualizarContacto')) {
             
            $respuesta = $this->Contacto_model->editar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'hay errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function elimination_delete()
    {
        $data = $this->delete();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if ($this->form_validation->run('EliminarContacto')) {
             
            $respuesta = $this->Contacto_model->borrar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'hay errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
   
}