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
            $sitio = $this->SitioTuristico_model->verificar_campos($data);

            try {
                //code...
                $respuesta = $sitio->guardar();
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

    public function sitio_get($id = 0)
    {
        ///si no mandan id por defecto sera 0
        $sitio = $this->SitioTuristico_model->obtenerSitio($id);
        $this->response($sitio, REST_Controller::HTTP_OK);
    }
}