<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Viajes_model extends CI_Model
{
  
    public $nombre_tipoviaje;
    
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
}