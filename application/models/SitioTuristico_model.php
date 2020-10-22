<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SitioTuristico_model extends CI_Model
{

    public $nombre;
    public $longitud;
    public $latitud;
    public $descripcion;
    public $tipo;
    public $informacion_contacto;
    public $id_sitio_turistico;
    public $precio_sitio;
    public $estado;


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

    public function guardar(array $data)
    {
        $data["estado"]=TRUE;
        $campos = $this->verificar_camposEntrada($data);
        $nombreTabla = "sitio_turistico";
        $insert = $this->db->insert($nombreTabla, $campos);
        if ($insert) {
            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria($nombreTabla,  $identificador);

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
                'id' => $identificador,
                'sitio' => $campos
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

    public function obtenerSitio(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        try {
            $this->db->select('sitio_turistico.nombre,precio_sitio,descripcion, contacto.nombre as contactoN,telefono,correo ');
            $this->db->from("sitio_turistico");
            $this->db->join('contacto', 'sitio_turistico.informacion_contacto=contacto.id_contacto');
            $this->db->where($parametros);
            // $this->db->where($parametros);
            $query = $this->db->get();
            $sitios  = $query->result();
            if (count($sitios) < 1) {

                $respuesta = array('err' => FALSE, 'sitios' => null, 'mensaje' => "NO SE ENCONTRO NINGUN USUARIO");
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'sitios' => $sitios);
                return $respuesta;
            }
            //$sitios = $query->result();
            // foreach ($sitios as $fila) {
            //     $path = [];
            //     $this->db->select("foto_path");
            //     $this->db->where("identificador", $fila->id_sitio_turistico);
            //     $this->db->where("tipo", $nombreTabla);
            //     $query = $this->db->get("galeria");

            //     foreach ($query->result() as $foto) {
            //         $path[] = $foto->foto_path;
            //     }
            //     $fila->path = $path;
            // }

         
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('SitioTuristico_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
}