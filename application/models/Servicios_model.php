<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Servicios_model extends CI_Model
{
    public $idservicios_opc;
    public $nombre_servicio;
    public $descripcion;
    public $precio;
    public $activo = TRUE;
    
    public function get_servicios(){

        $query=$this->db->get('servicios_opc');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Servicios_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('servicios_opc',array('nombre_servicio'=>$this->nombre_servicio) );
           $lugar=$query->row();
   
               if (isset($lugar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El servicio fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('servicios_opc',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'servicio_id'=>$this->db->insert_id()
                   );
               }else{
                   //error
   
                   $respuesta=array(
                       'err'=>TRUE,
                       'mensaje'=>'Error al insertar',
                       'error'=>$this->db->_error_message(),
                       'error_num'=>$this->db->_error_number()
                   );
               
               }
            return $respuesta;
        }

    //MODIFICAR
    public function editar($data)
    {
        $nombreTabla = "servicios_opc";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Servicios_model->verificar_camposEntrada($data);
        $this->db->where('idservicios_opc', $campos["idservicios_opc"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Servicios' => $campos

            );
            return $respuesta;
        } 
        else 
        {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Servicios' => null
            );
            return $respuesta;
        }
    }

    //VERIFICAR DATOS
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Servicios_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idservicios_opc', $campos["idservicios_opc"]);
        $hecho = $this->db->update('servicios_opc', $campos);
        if ($hecho) {
            //ELIMINANDO REGISTRO
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente'
            );
            return $respuesta;
        } else {
            //NO ELIMINO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Servicios Opcionales' => null
            );
            return $respuesta;
        }
    }
   
   
}