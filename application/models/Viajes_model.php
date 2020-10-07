<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Viajes_model extends CI_Model
{
  
    public $idtipo_viaje;
    public $nombre_tipoviaje;
    public $activo = TRUE;
    
    public function get_viajes(){

        $query=$this->db->get('tipo_viaje');

            return $query->result();
        }
   
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Viajes_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){
           $query=$this->db->get_where('tipo_viaje',array('nombre_tipoviaje'=>$this->nombre_tipoviaje) );
           $viajes=$query->row();
   
               if (isset($viajes)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Tipo de Viaje fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('tipo_viaje',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'viaje_id'=>$this->db->insert_id()
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
        $nombreTabla = "tipo_viaje";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Viajes_model->verificar_camposEntrada($data);
        $this->db->where('idtipo_viaje', $campos["idtipo_viaje"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Tipo de Viaje' => $campos

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
                'Tipo de Viaje' => null
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
            if (property_exists('Viajes_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idtipo_viaje', $campos["idtipo_viaje"]);
        $hecho = $this->db->update('tipo_viaje', $campos);
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
                'Tipo de Viaje' => null
            );
            return $respuesta;
        }
    }
}