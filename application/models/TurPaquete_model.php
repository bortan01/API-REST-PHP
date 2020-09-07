<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TurPaquete_model extends CI_Model
{
    public $nombreTours;
    public $fecha_salida;
    public $lugar_salida;
    public $precio;
    public $incluye;
    public $no_incluye;
    public $requisitos;
    public $promociones;
    public $descripcion;
    public $foto;
    public $cupos_disponibles;
    public $nombre_encargado;
    public $estado;
    public $tipo;
    public $aprobado;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('TurPaquete_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function guardar()
    {

        $this->load->model('Wompi_model');
        print_r($this->Wompi_model->obtenerToken());
        die();


        $nombreTabla = "tours_paquete";
        ///INTENTAMOS GUARDAR LA IMAGEN
        $fotoSubida = $this->Imagen_model->guardarImagen();
        $this->foto = $fotoSubida["path"];

        $insert = $this->db->insert($nombreTabla, $this);
        if ($insert) {
            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS
            $identificador = $this->db->insert_id();
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
                'cliente' => $identificador
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
    }
}