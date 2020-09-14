<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Marca_model extends CI_Model
{
    public $id_marca;
    public $marca;

    public function get_marca(){

        $query=$this->db->get('marca_vehiculo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Marca_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('marca_vehiculo',array('marca'=>$this->marca) );
           $marcas=$query->row();
   
               if (isset($marcas)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Marca fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('marca_vehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'marca_id'=>$this->db->insert_id()
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