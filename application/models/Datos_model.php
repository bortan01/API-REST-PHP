<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Datos_model extends CI_Model
{
    public $id_generales;
    public $ciudad_partida;
    public $fecha_partida;
    public $hora_partida;
    public $ciudad_llegada;
    public $fecha_llegada;
    public $hora_llegada;
    public $adultos;
    public $ninos;
    public $bebes;
    public $maletas;
    
    public function get_generales(){

        $query=$this->db->get('datos_generales');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Datos_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('datos_generales',array('id_generales'=>$this->id_generales) );
           $datos=$query->row();
   
               if (isset($datos)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Datos generales fueron registrados'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('datos_generales',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'datos_id'=>$this->db->insert_id()
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