<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tours_paquete_model extends CI_Model
{
    public $id_tours;
    public $nombreTours;
    public $fecha_salida;
    public $lugar_salida;
    public $precio;
    public $no_incluye;
    public $requisitos;
    public $promociones;
    public $descripcion_tur;
    public $cupos_disponibles;
    public $nombre_encargado;
    public $estado;
    public $aprobado;
    public $tipo;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Tours_paquete_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function guardar(array $turPaquete)
    {
        // print_r($turPaquete);
        // die();
        $nombreTabla = "tours_paquete";
        $insert = $this->db->insert($nombreTabla, $turPaquete);
        if (!$insert) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'turPaquete'      => null
            );
            return $respuesta;
        } else {
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("tours_paquete", $identificador);
            
            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'turPaquete'   => $turPaquete
            );
            return $respuesta;
        }
    }
    public function obtenerViaje(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "tours_paquete";

        try {
            $parametros = $this->verificar_camposEntrada($data);
            $viajeSEleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);
            ///usuario seleccionado es un array de clases genericas
            if (count($viajeSEleccionado)<1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'viaje'     => null
                );
                return $respuesta;
            } else {
                
                 $respuesta = array(
                    'err'          => FALSE,
                    'viaje'   => $viajeSEleccionado
                );
                return $respuesta;
            }
           
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }

    public function editar($data)
    {
        $nombreTabla = "tours_paquete";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Tours_paquete_model->verificar_camposEntrada($data);
        $this->db->where('id_tours', $campos["id_tours"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'viaje' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'viaje' => null
            );
            return $respuesta;
        }
    }
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Tours_paquete_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function borrar($campos)
    {
        $nombreTabla = "tours_paquete";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_tours', $campos["id_tours"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente',
                'id'      => $campos["id_tours"]
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),

            );
            return $respuesta;
        }
    }
}