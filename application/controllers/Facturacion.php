<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
    "https://admin.martineztraveltours.com",
	"https://martineztraveltours.com/"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class Facturacion extends REST_Controller
{


    public function __construct()
    {
        //llamado del constructor del padre 
        parent::__construct();
        $this->load->database();
        $this->load->model('Facturacion_model');
        $this->load->model('Facturacion_detalle_model');
    }

    public function factura_get()
    {
        //este parametro viene por url 
        $factura_id = $this->uri->segment(3);

        //validamos el parametro si no existe enviamos un HTTP_BAD_REQUEST
        if (!isset($factura_id)) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'Es necesario el ID de la factura',
                'factura' => null
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            //este return es para que no continue
            return;
        }
        //solicitamos ese cliente al modelo 
        $factura = $this->Facturacion_model->obtenerFactura($factura_id);

        ///validamos si existe el cliente, si no existe enviamos un HTTP_NOT_FOUND
        if (!isset($factura)) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'EL registro con el id ' . $factura_id . ' no existe',
                'factura' => null
            );
            $this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
        } else {
            ///si llegamos aqui todo esta bien y podemos retornar un HTTP_OK

            $this->db->reset_query();
            $detalle =  $this->Facturacion_detalle_model->obtenerFacturas($factura_id);


            $respuesta = array(
                'error' => FALSE,
                'mensaje' => 'todo ok',
                'factura' => $factura,
                'detalle' => $detalle
            );
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}