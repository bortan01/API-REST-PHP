<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Transmision_model extends CI_Model
{
    public $idtransmicion;
    public $transmision;

    public function get_transmision(){

        $query=$this->db->get('transmisionvehiculo');

            return $query->result();
        }
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Transmision_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){

           $query=$this->db->get_where('transmisionvehiculo',array('transmision'=>$this->transmision) );
           $transmisiones=$query->row();
   
               if (isset($transmisiones)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Transmision fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('transmisionvehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'transmision_id'=>$this->db->insert_id()
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

    //MODIFICAR
    public function editar($data)
    {
        $nombreTabla = "transmisionvehiculo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Transmision_model->verificar_camposEntrada($data);
        $this->db->where('idtransmicion', $campos["idtransmicion"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'transmision' => $campos

            );
            return $respuesta;
        } 
        else 
        {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'transmision' => null
            );
            return $respuesta;
        }
    }

    //VERIFICAR DATOS
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Transmision_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    } 
}