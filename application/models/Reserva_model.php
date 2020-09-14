<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Reserva_model extends CI_Model
{
    public $id_reserva;
    public $id_tours;
    public $id_usuario;
    public $fecha_reserva;
    public $formaPagoUtilizada;
    public $resultadoTransaccion;
    public $monto;
    public $cantidad;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Reserva_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }
    public function guardar($data)
    {
        $nombreTabla = "reserva";
        $this->id_reserva           = $data["IdTransaccion"];
        $this->id_tours             = $data["EnlacePago"]["Id"];
        $this->id_usuario           = 1;
        $this->fecha_reserva        = $data["FechaTransaccion"];;
        $this->formaPagoUtilizada   = $data["FormaPagoUtilizada"];
        $this->resultadoTransaccion = $data["ResultadoTransaccion"];
        $this->monto                = $data["Monto"];
        $this->cantidad             = $data["Cantidad"];

        
        try {

            $insert = $this->db->insert($nombreTabla, $this);

            if ($insert) {


                $respuesta = array(
                    'err' => FALSE,
                    'mensaje' => 'Registro Guardado Exitosamente',
                    'reserva' => $this
                );
                return $respuesta;
            } else {
                //NO GUARDO
                $respuesta = array(
                    'err' => TRUE,
                    'mensaje' => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'cliente' => null
                );
                return $respuesta;
            }
        } catch (Exception $e) {
            echo "dentro de  cartch";
            $respuesta = array(

                'err' => TRUE


            );
            return $respuesta;
        }
    }
}