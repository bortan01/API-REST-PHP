<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Modelo_model extends CI_Model
{
    public $idmodelo;
    public $id_marca;
    public $modelo;

    public function get_modelo(){

        $query=$this->db->get('modelo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Modelo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('modelo',array('modelo'=>$this->modelo) );
           $modelos=$query->row();
   
               if (isset($modelos)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Modelo fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('modelo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'modelo_id'=>$this->db->insert_id()
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