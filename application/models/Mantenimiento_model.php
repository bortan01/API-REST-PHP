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
   
}