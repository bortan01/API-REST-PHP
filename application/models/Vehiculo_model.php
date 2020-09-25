<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Vehiculo_model extends CI_Model
{
    public $idvehiculo;
    public $id_rentaCarFK;
    public $id_marcaFK;
    public $id_transmicionFK;
    public $placa;
    public $anio;
    public $puertas;
    public $pasajeros;
    public $precio_diario;
    public $descripcion;
    public $detalles;
    
    public function get_vehiculo(){

        $query=$this->db->get('vehiculo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Vehiculo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('vehiculo',array('placa'=>$this->placa) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El vehiculo fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('vehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'vehiculo_id'=>$this->db->insert_id()
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