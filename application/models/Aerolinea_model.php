<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Aerolinea_model extends CI_Model
{
    public $idaerolinea;
    public $nombre_aerolinea;
    
    
    public function get_aerolinea(){

        $query=$this->db->get('aerolinea');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Aerolinea_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('aerolinea',array('nombre_aerolinea'=>$this->nombre_aerolinea) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Aerolinea fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('aerolinea',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'aerolinea_id'=>$this->db->insert_id()
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