<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mantenimiento_model extends CI_Model
{
    public $id_mantenimiento;
    public $id_vehiculoFK;
    public $fecha;
    public $lugar;
    public $mantenimiento_realizado;
    public $piezas_cambiadas;
    public $comentariosIncidentes;
    public $costoMantenimiento;
    public $activo = TRUE;
    
    public function get_mantenimiento(){

        $query=$this->db->get('mantenimiento');

            return $query->result();
        }
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Mantenimiento_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;   
            }
            }
            return $this; 
        }
   
        public function insert(){
            
           $query=$this->db->get_where('mantenimiento',array('fecha'=>$this->fecha) );
           $mantenimientos=$query->row();
   
               if (isset($mantenimientos)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Mantenimiento fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('mantenimiento',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'mantenimiento_id'=>$this->db->insert_id()
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
        $nombreTabla = "mantenimiento";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Mantenimiento_model->verificar_camposEntrada($data);
        $this->db->where('id_mantenimiento', $campos["id_mantenimiento"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Mantenimiento' => $campos

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
                'Mantenimiento' => null
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
            if (property_exists('Mantenimiento_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    } 
   
     //ELIMINAR
     public function borrar($campos)
     {
         //ELIMINAR UN REGISTRO
         $this->db->where('id_mantenimiento', $campos["id_mantenimiento"]);
         $hecho = $this->db->update('mantenimiento', $campos);
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
                 'Mantenimiento' => null
             );
             return $respuesta;
         }
     }
   
}