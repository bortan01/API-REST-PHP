<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class DetalleVehiculo extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('DetalleVehiculo_model');
    }

    public function save_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarDetalleVehiculo')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $detalleViculo = $this->DetalleVehiculo_model->verificar_camposEntrada($data);
            $respuesta     = $this->DetalleVehiculo_model->guardar($detalleViculo);
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
    public function obtenerDetalleVehiculo_get()
    {
        $data = $this->get();
        $respuesta =  $this->DetalleVehiculo_model->obtenerDetalle($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }

}