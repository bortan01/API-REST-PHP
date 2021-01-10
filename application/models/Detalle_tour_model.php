<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Detalle_tour_model extends CI_Model
{
    public $id_detalle;
    public $id_tours;
    public $id_cliente;
    public $asientos_seleccionados;
    public $label_asiento;
    public $nombre_producto;
    public $total;
    public $urlQrCodeEnlace;
    public $urlEnlace;
    public $descripcionProducto;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Detalle_tour_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function guardar($data)
    {
        $camposTur = $this->verificar_camposEntrada($data);
        // $camposTur['id_detalle'] = date("HisYmd") . rand(1, 100);
        $nombreTabla = "detalle_tour";
        $insertTur = $this->db->insert($nombreTabla, $camposTur);

        if (!$insertTur) {
            //NO GUARDO
            $respuesta = array(
                'err'             => TRUE,
                'mensaje'         => 'Error al insertar detalle tur', $this->db->error_message(),
                'error_number'    => $this->db->error_number()
            );
            return $respuesta;
        } else {
            $identificador = $this->db->insert_id();
            $respuesta = array(
                'err'             => FALSE,
                'mensaje'         => 'Registro Guardado Exitosamente',
                'id'              => $identificador
            );
            return $respuesta;
        }
    }
    public function obtenerDetalle(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "detalle_tour";

        try {
            $parametros = $this->Detalle_vehiculo_model->verificar_camposEntrada($data);
            $deetalleSeleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);
            ///usuario seleccionado es un array de clases genericas

            if (count($deetalleSeleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro el Usuario');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'detalleVehiculo' => $deetalleSeleccionado);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
}