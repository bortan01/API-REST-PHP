<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SitioTuristico_model extends CI_Model
{

    public $nombre;
    public $longitud;
    public $latitud;
    public $ubicacion;
    public $descripcion;
    public $informacion_contacto;
    public $tipo;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('SitioTuristico_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }


        return $this;
    }

    public function guardar()
    {
        $nombreTabla = "sitio_turistico";
        $insert = $this->db->insert($nombreTabla, $this);
        if ($insert) {
            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria($nombreTabla,  $identificador);

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

    public function obtenerSitio()
    {
        $nombreTabla = "sitio_turistico";

        try {

            //se buscaran todos los sitios turisticos
            $query = $this->db->get($nombreTabla);
            $sitios = $query->result();

            foreach ($sitios as $fila) {
                $path = [];
                $this->db->select("foto_path");
                $this->db->where("identificador", $fila->id_sitio_turistico);
                $this->db->where("tipo", $nombreTabla);
                $query = $this->db->get("galeria");

                foreach ($query->result() as $foto) {
                    $path[] = $foto->foto_path;
                }
                $fila->path = $path;
            }
            return $sitios;
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}