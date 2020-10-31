<?php
defined('BASEPATH') or exit('No direct script access allowed');
class TipoServicio_model extends CI_Model
{
    public $id_tipo_servicio;
    public $tipo_servicio;
    public $estado;

    public function guardar(array $data)
    {
        $data["estado"] = TRUE;
        $campos = $this->verificar_camposEntrada($data);
        $nombreTabla = "tipo_servicio";
         
        $insert = $this->db->insert($nombreTabla, $campos);
        if ($insert) {
            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS
            $identificador = $this->db->insert_id();

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
                'id' => $identificador,
                'tipo' => $campos
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'tipo' => null
            );
            return $respuesta;
        }
    }
    public function obtenerTipo(array $data)
    {
        $data["estado"] = TRUE;
        $parametros = $this->verificar_camposEntrada($data);

        try {
            $nombreTabla = "tipo_servicio";
            $this->db->select('*');
            $this->db->from($nombreTabla);
            $this->db->where($parametros);
            $query = $this->db->get();
            $tipo  = $query->result();
            if (count($tipo) < 1) {

                $respuesta = array('err' => FALSE, 'tipo' => null, 'mensaje' => "NO SE ENCONTRO NINGUN TIPO");
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'tipo' => $tipo);
                return $respuesta;
            }
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
            if (property_exists('TipoServicio_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function editar($data)
    {
        $nombreTabla = "tipo_servicio";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->verificar_camposEntrada($data);
        $this->db->where('id_tipo_servicio', $campos["id_servicio_turistico"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'servicio' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'servicio' => null
            );
            return $respuesta;
        }
    }
    public function elimination($campos)
    {
        $nombreTabla = "tipo_servicio";
        $identificador    = $campos["id_tipo_servicio"];
        $campos["estado"] = FALSE;
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_tipo_servicio', $identificador);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {

            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Elimiinado Exitosamente',
                'tipo' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'tipo' => null
            );
            return $respuesta;
        }
    }
}