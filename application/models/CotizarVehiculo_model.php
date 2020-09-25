<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CotizarVehiculo_model extends CI_Model
{
    public $idalquilerVehiculo;
    public $id_vehiculo;
    public $id_usuario;
    public $idlugar;
    public $idservicios_opc;
    public $direccion_recogida;
    public $fecha_recogida;
    public $hora_recogida;
    public $direccion_devolucion;
    public $fecha_devolucion;
    public $hora_devolucion;
    
    public function get_cotizar(){

        $query=$this->db->get('cotizarvehiculo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('CotizarVehiculo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('cotizarvehiculo',array('idalquilerVehiculo'=>$this->idalquilerVehiculo) );
           $lugar=$query->row();
   
               if (isset($lugar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'La cotizacion fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('cotizarvehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'cotizacion_id'=>$this->db->insert_id()
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