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
        $this->load->model('Tours_paquete_model');
        $this->load->model('detalle_servicio_model');
        $this->load->model('Itinerario_model');
    }

    public function save_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarTurPaquete')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $turPaquete = $this->Tours_paquete_model->verificar_camposEntrada($data);
            $respuesta =  $this->Tours_paquete_model->guardar($turPaquete);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //SE GURDO EL TUR POR LO QUE YA TENEMOS EL ID PARA EL DETALLE
                if (!empty($data["servicios"])) {
                    $servicios = json_decode($data["servicios"], true);
                    $this->detalle_servicio_model->guardar($servicios, $respuesta['id']);
                }
                if (!empty($data["sitios"])) {
                    $itinerario = json_decode($data["sitios"], true);
                    $this->Itinerario_model->guardar($itinerario, $respuesta['id']);
                }
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
    public function update_put()
    {
        $data = $this->put();
        if (!isset($data["id_tours"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de Tour o Paquete');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            try {
                $respuesta = $this->Tours_paquete_model->editar($data);
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
    public function obtenerViaje_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerViaje($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function elimination_delete()
    {
        $data = $this->delete();
        if (!isset($data["id_tours"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de paquete');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $campos = array('id_tours' => $data["id_tours"], 'estado' => 'inactivo');

            try {
                $respuesta = $this->Tours_paquete_model->borrar($campos);
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