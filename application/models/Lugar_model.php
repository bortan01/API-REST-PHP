<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lugar_model extends CI_Model
{
    public $idlugar_recog_dev;
    public $nombre_lugar;
    
    
    public function get_lugar(){

        $query=$this->db->get('lugar_recog_dev');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Lugar_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('lugar_recog_dev',array('nombre_lugar'=>$this->nombre_lugar) );
           $lugar=$query->row();
   
               if (isset($lugar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El lugar fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('lugar_recog_dev',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'lugar_id'=>$this->db->insert_id()
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