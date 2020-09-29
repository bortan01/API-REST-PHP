<?php
defined('BASEPATH') or exit('No direct script access allowed');
class opcAvanzadas_model extends CI_Model
{
    public $idopc_avanzadas;
    public $idaerolinea;
    public $idclase;
    public $idalianza;
    public $idtipo_viaje;
    public $vuelosin_escala;
    public $misma_aerolinea;
    public $equipaje_extra;

    public function get_opciones(){

        $query=$this->db->get('opc_avanzadas');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('opcAvanzadas_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('opc_avanzadas',array('idopc_avanzadas'=>$this->idopc_avanzadas) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Informacion adicional fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('opc_avanzadas',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'opc_id'=>$this->db->insert_id()
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