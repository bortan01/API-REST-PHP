<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class TurPaquete extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('TurPaquete_model');
    }

    public function tur_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarTurPaquete')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $turPaquete = $this->TurPaquete_model->verificar_campos($data);
            $respuesta = $turPaquete->guardar();
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

    public function test_post()
    {
        $this->load->model('Imagen_model');
        //COMO SUBIR UNA FOTO ENVIAR PARAMETRO foto tipo file
        //SOLO ACEPTA ARCHIVOS TIPO IMAGEN NO MAYORES A 2MB
        //$imagen = $this->Imagen_model->guardarImagen();
        //$this->response($imagen, REST_Controller::HTTP_OK);

        //PARA SUBIR MUCHAS FOTOS Y GUARDARLAS EN LA TABLA GALERIA
        //PRIMER PARAMETRO   => NOMBRE DE TABLA
        //SEGUNDO PARAMETRO  => ID FORANEO
        // $imagenes = $this->Imagen_model->guardarGaleria("tours_paquete", 10);
        // $this->response($imagenes, REST_Controller::HTTP_OK);
        
        
    }
}