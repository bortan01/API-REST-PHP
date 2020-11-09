<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class DetalleServicio extends REST_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('detalle_servicio_model');
    }
    public function save_post()
    {
        $data = $this->post();
       
        $servicios =json_decode($data["servil"],true);

        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        //$this->form_validation->run('insertarServicioDetalle')
        if (TRUE) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN

            $respuesta =  $this->detalle_servicio_model->guardar($servicios, "1");
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

        $respuesta =  $this->detalle_servicio_model->obtenerServicio($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}