<?php
defined('BASEPATH') or exit('No direct script access allowed');
class ReservaTour_model extends CI_Model
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
            if (property_exists('ReservaTour_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
    public function guardar($data)
    {
        $nombreTabla = "reserva_tour";

        $this->id_reserva           = $data["IdTransaccion"];
        $this->id_detalle           = $data["EnlacePago"]["Id"];
        $this->fecha_reserva        = $data["FechaTransaccion"];;
        $this->formaPagoUtilizada   = $data["FormaPagoUtilizada"];
        $this->resultadoTransaccion = $data["ResultadoTransaccion"];
        $this->monto                = $data["Monto"];
        $this->cantidad             = $data["Cantidad"];


        $insert = $this->db->insert($nombreTabla, $this);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'cliente' => null
            );
            return $respuesta;
        } else {
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
                'reserva' => $this
            );
            return $respuesta;
        }
    }
}