<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class DetalleTour extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model("Detalle_tour_model");
        $this->load->model("ReservaTour_model");
        $this->load->model("Tours_paquete_model");
    }

    public function saveByAgency_post()
    {
        $data = $this->post();
        $idDetalle = date("His") . rand(1, 1000);
        $data['id_detalle'] = $idDetalle;
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if (!true) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $respuesta     = $this->Detalle_tour_model->guardar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                $reservaTur = [];
                $reservaTur["IdTransaccion"]        = date("HisYmd") . rand(1, 1000);
                $reservaTur["EnlacePago"]["Id"]     = $idDetalle;
                $reservaTur["FechaTransaccion"]     = date("Y-m-d H:i:s");
                $reservaTur["FormaPagoUtilizada"]   = 'Agencia';
                $reservaTur["ResultadoTransaccion"] = 'ExitosaAprobada';
                $reservaTur["Monto"]                = $data['total'];
                $reservaTur["Cantidad"]             = 1;

                $respuesta = $this->ReservaTour_model->guardar($reservaTur);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    // $respuesta     = $this->Tours_paquete_model->editar(arry('id_tours'=>''));

                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            }
        }
    }
    public function obtenerDetalleVehiculo_get()
    {
        $data = $this->get();
        $respuesta =  $this->Detalle_tour_model->obtenerDetalle($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}