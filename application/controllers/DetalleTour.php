<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
    "https://admin.tesistours.com",
    "https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
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
        $this->load->model('Wompi_model');
    }

    public function saveByAgency_post()
    {
        $data = $this->post();
        $idDetalle = date("His") . rand(1, 1000);
        $data['id_detalle'] = $idDetalle;
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);

        //corremos las reglas de validacion
        if (!$this->form_validation->run('insertarDetalleTur')) {
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
                    //para mandar el correo
                    $cuerpo="<h2>Nombre del producto adquirido : ".$data['nombre_producto']."</h2><br>
                    <h4>Su compra fue procesada con éxito con un precio de: 
                    $".$data['total']."</h4><br>
                    <h4>Descripción del servicio: ".$data['descripcionProducto']."</h4><br>
                    <h4>Asientos seleccionados: ".$data['asientos_seleccionados']."</h4><br>
                    <h4>Cantidad de asientos: ".$data['cantidad_asientos']."</h4><br>
                    <br><h4>Gracias por preferirnos, puedes visitar nuestra página web: https://tesistours.com/
                    </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";

                    $this->load->model('Mail_model');
                    $this->Mail_model->metEnviarUno('Adquisición de Tours ','','Información de adquisición de Tours',$cuerpo,$data['id_cliente']);
                     //fin de para mandar correo

                    // ENVIAR CORREO ELECTRONICO A PERSONA QUE HA REALIZADO LA RESERVA
                    // INFORMACION QUE ESTA AL INTERIOR DE $data
                    // {
                    //     "id_tours": "4",
                    //     "id_cliente": "2036220712",
                    //     "asientos_seleccionados": "1_3,1_4",
                    //     "label_asiento": "13,41",
                    //     "nombre_producto": "Tur a  San lejos ",
                    //     "total": "34234.294",
                    //     "descripcionProducto": "descripcion completa ",
                    //     "cantidad_asientos": "5",
                    //     "id_detalle": "200741753"
                    // }
                    
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            }
        }
    }
    public function saveByClient_post()
    {
        $data = $this->post();
        ///SE HACE LA PETICION A WOMPI
        //corremos las reglas de validacion
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if (!$this->form_validation->run('insertarDetalleTur')) {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $respuesta     = $this->Detalle_tour_model->guardarByCliente($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //para mandar el correo
                $cuerpo="<h2>Nombre del producto adquirido : ".$data['nombre_producto']."</h2><br>
                <h4>Su compra fue procesada con éxito con un precio de: 
                $".$data['total']."</h4><br>
                <h4>Descripción del servicio: ".$data['descripcionProducto']."</h4><br>
                <h4>Asientos seleccionados: ".$data['asientos_seleccionados']."</h4><br>
                <h4>Cantidad de asientos: ".$data['cantidad_asientos']."</h4><br>
                <h4>Descripción del producto: ".$data['descripcionProducto']."</h4><br>
                <br><h4>Gracias por preferirnos, puedes visitar nuestra página web: https://tesistours.com/
                </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";

                $this->load->model('Mail_model');
                $this->Mail_model->metEnviarUno('Adquisición de Tours ','','Información de adquisición de Tours',$cuerpo,$data['id_cliente']);
                 //fin de para mandar correo

                 //para mandar el correo a los empleados
                 $this->db->select('nombre');
                 $this->db->from('usuario');
                 $this->db->where('id_cliente',$data['id_cliente']);
                 $query = $this->db->get();
                foreach ($query->result() as $row)
                {
                 $cuerpo="<h2>Adquisición de Tours: ".$data['nombre_producto']."</h2><br>
                 <h4>Se realizó una Adquisición de servicio del cliente: ".$row->nombre.",
                 <h4>Descripción del servicio: ".$data['descripcionTurPaquete']."</h4><br>
                <h4>Asientos seleccionados: ".$data['asientos_seleccionados']."</h4><br>
                <h4>Cantidad de asientos: ".$data['cantidad_asientos']."</h4><br>
                <h4>Descripción del producto: ".$data['descripcionProducto']."</h4><br>
                 <br>Atte:<br>Martínez Travel & Tours";
                }
                 $this->load->model('Mail_model');
                 $this->Mail_model->metEnviar('Adquisición de Tours','Adquisición de Cliente',$cuerpo);
                //fin de para mandar correo a los empleados
                
                // ENVIAR CORREO ELECTRONICO A CLIENTE QUE HIZO LA RESERVA A TRAVEZ DE UN PAGO EN LINEA
                // INFORMACION AL INTERIOR DE $data
                // {
                //     "id_tours": "11",
                //     "id_cliente": "2046000712",
                //     "asientos_seleccionados": "10_5,10_6,10_7",
                //     "label_asiento": "53,54,55",
                //     "nombre_producto": "TOURS CAYOS DE BELICE!!",
                //     "total": "445",
                //     "descripcionTurPaquete": "Hermoso tour a cayos debelice ven y distruta de este maravilloso viaje",
                //     "cantidad_asientos": "2",
                //     "descripcionProducto": "2 X Asiento(s) Normal $325.0 c/u, Sub Total $650.0  Total: $650.0 ",
                //     "tipo": ""
                // }
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        }
    }
    public function showDetalleTur_get()
    {
        $data = $this->get();
        $respuesta = $this->Detalle_tour_model->obtenerDetalle($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function updateChequeo_post()
    {
        $data = $this->post();
        $respuesta = $this->Detalle_tour_model->actualizarChekeo($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
}