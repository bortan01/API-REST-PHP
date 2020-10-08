<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Servicios_adicionales_model extends CI_Model
{

    public $id_servicios;
    public $nombre;
    public $descripcion_servicio;
    public $costos_defecto;
    public $tipo_servicio;
    public $informacion_contacto;
    public $asientos_derecho;
    public $asientos_izquierdos;
    public $asientos_fondo;
    public $filas;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Servicios_adicionales_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
    
        //este es un objeto tipo cliente model
        return $objeto;
    }

    public function guardar(array $data)
    {
        $nombreTabla = "servicios_adicionales";
        $servicio = $this->verificar_camposEntrada($data);
        $insert = $this->db->insert($nombreTabla, $servicio);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'serrvicio'      => null
            );
            return $respuesta;
        } else {
            //ESTA ES POR SI SE VA A SUBIR LA GALAREIA 
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("tours_paquete", $identificador);
            
            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'servicio'   => $servicio
            );
            return $respuesta;
        }
    }

    public function obtenerServicio(array $data = array())
    {
       $nombreTabla = "servicios_adicionales";
        try {
            $parametros = $this->verificar_camposEntrada($data);
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $servicioSeleccionado = $query->result();
                      
            if (count($servicioSeleccionado)<1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'servicio'     => null
                );
                return $respuesta;
            } else {
                
                 $respuesta = array(
                    'err'          => FALSE,
                    'servicio'   => $servicioSeleccionado
                );
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }

    public function editar($data)
    {
        $nombreTabla = "servicios_adicionales";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->verificar_camposEntrada($data);
        $this->db->where('id_servicios', $campos["id_servicios"]);
      
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'sitio' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'sitio' => null
            );
            return $respuesta;
        }
    }
    public function elimination($campos)
    {
        $nombreTabla = "servicios_adicionales";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos["activo"] = FALSE;      
        $this->db->where('id_servicios', $campos["id_servicios"]);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Elimiinado Exitosamente',
                'sitio' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'sitio' => null
            );
            return $respuesta;
        }
    }
}