<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ReservaVehiculo_model extends CI_Model
{
    public $id_reserva;
    public $id_detalle;
    public $fecha_reserva;
    public $formaPagoUtilizada;
    public $resultadoTransaccion;
    public $monto;
    public $cantidad;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('ReservaVehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }    
    public function guardar($data)
    {
        $nombreTabla = "reserva_vehiculo";
        $campos = [];
        $campos ["id_reserva"]           = $data["IdTransaccion"];
        $campos ["id_detalle"]           = $data["EnlacePago"]["Id"];
        $campos ["fecha_reserva"]        = $data["FechaTransaccion"];;
        $campos ["formaPagoUtilizada"]   = $data["FormaPagoUtilizada"];
        $campos ["resultadoTransaccion"] = $data["ResultadoTransaccion"];
        $campos ["monto"]                = $data["Monto"];
        $campos ["cantidad"]             = $data["Cantidad"];

        $insert = $this->db->insert($nombreTabla, $campos);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number()
            );
            return $respuesta;
        } else {
            ///SI SE DESEA MODIFICAR MAS TRABLAS HACERLO ACA 

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente'
            );
            return $respuesta;
        }
    }
}