<?php
defined('BASEPATH') or exit('No direct script access allowed');
class infoAdicional_model extends CI_Model
{
    public $idinfo_adicional;
    public $condiciones;
    public $anuncios;
    public $otros;
    
    public function get_informacion(){

        $query=$this->db->get('info_adicional');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('infoAdicional_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('info_adicional',array('idinfo_adicional'=>$this->idinfo_adicional) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Informacion adicional fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('info_adicional',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'info_id'=>$this->db->insert_id()
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