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
        $campos = [];
        $campos["id_reserva"]           = $data["IdTransaccion"];
        $campos["id_detalle"]           = $data["EnlacePago"]["Id"];
        $campos["fecha_reserva"]        = $data["FechaTransaccion"];;
        $campos["formaPagoUtilizada"]   = $data["FormaPagoUtilizada"];
        $campos["resultadoTransaccion"] = $data["ResultadoTransaccion"];
        $campos["monto"]                = $data["Monto"];
        $campos["cantidad"]             = $data["Cantidad"];


        $insert = $this->db->insert($nombreTabla, $campos);
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
            $this->load->model("Detalle_tour_model");
            $this->load->model("Tours_paquete_model");
            $detalleTur = $this->Detalle_tour_model->obtenerDetalle($campos);
            $respuesta = $this->Tours_paquete_model->actualizarCupos($detalleTur[0]);

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente'
            );
            return $respuesta;
        }
    }
    public function obtener(array $data = array())
    {
        $nombreTabla = "reservaTour";
        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from($nombreTabla);
        $this->db->where($parametros);

        $query = $this->db->get();
        $respuesta  = $query->result();
        return $respuesta;
    }
}