<?php
defined('BASEPATH') or exit('No direct script access allowed');
class cotizarVuelo_model extends CI_Model
{
    public $id_cotizacion;
    public $id_cliente;
    public $id_generales;
    public $opc_avanzadas;
    public $idinfo_adicional;
    public $total;
    public $descuentos;

    public function get_cotizar(){

        $query=$this->db->get('cotizacion_vuelo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('cotizarVuelo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('cotizacion_vuelo',array('id_cotizacion'=>$this->id_cotizacion) );
           $cotizar=$query->row();
   
               if (isset($cotizar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Informacion adicional fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('cotizacion_vuelo',$this);
   
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