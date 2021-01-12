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
        $this->load->model("Detalle_vehiculo_model");
        $this->load->model("ReservaVehiculo_model");
        $this->load->model('Wompi_model');

    }

    public function saveByAgency_post()
    {
        $data = $this->post();
        $idDetalle = date("His") . rand(1, 1000);
        $data['id_detalle'] = $idDetalle;
        
        //corremos las reglas de validacion
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if (!$this->form_validation->run('insertarDetalleVehiculo')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            //ACA SE LLENA LA TABLA detalle_vehiculo
            $respuesta     = $this->Detalle_vehiculo_model->guardar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                ///ACA SE LLENA LA TABLA reserva_vehiculo
                $reseraVehiculo = [];
                $reseraVehiculo["IdTransaccion"]        = date("HisYmd") . rand(1, 1000);
                $reseraVehiculo["EnlacePago"]["Id"]     = $idDetalle;
                $reseraVehiculo["FechaTransaccion"]     = date("Y-m-d H:i:s");
                $reseraVehiculo["FormaPagoUtilizada"]   = 'Agencia';
                $reseraVehiculo["ResultadoTransaccion"] = 'ExitosaAprobada';
                $reseraVehiculo["Monto"]                = $data['total'];
                $reseraVehiculo["Cantidad"]             = 1;

                $respuesta = $this->ReservaVehiculo_model->guardar($reseraVehiculo);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            }
        }
    }
    public function saveByClient_post()
    {
        $data = $this->post();
        //corremos las reglas de validacion
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if (!$this->form_validation->run('insertarDetalleVehiculo')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $respuesta     = $this->Detalle_vehiculo_model->guardarByCliente($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        }
    }
    public function obtenerDetalleVehiculo_get()
    {
        $data = $this->get();
        $respuesta =  $this->Detalle_vehiculo_model->obtenerDetalle($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    
}