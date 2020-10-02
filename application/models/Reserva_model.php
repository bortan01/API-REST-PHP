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
        $this->load->model("Usuario_model");
        ///ESTO NOS RETORNARA UN ARRAY
        $usuario = $this->Usuario_model->getUser(array('correo' => $data["Cliente"]["Email"]));

        if (!$usuario['err']) {

            $this->id_usuario = ($usuario["usuario"][0]->id_cliente);
        } else {
            $this->id_usuario = NULL;
        }
        $this->id_reserva           = $data["IdTransaccion"];
        $this->id_tours             = $data["EnlacePago"]["Id"];
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
