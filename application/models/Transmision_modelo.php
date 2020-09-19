<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Transmision_model extends CI_Model
{
    public $idtransmicion;
    public $trasmicion;
    

    public function get_modelo(){

        $query=$this->db->get('transmicion');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Transmision_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('transmicion',array('trasmicion'=>$this->trasmicion) );
           $transmisiones=$query->row();
   
               if (isset($transmisiones)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Transmision fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('transmicion',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'transmision_id'=>$this->db->insert_id()
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