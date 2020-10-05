<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Alianzas_model extends CI_Model
{
    public $idalianza;
    public $nombre_alianza;
    
    public function get_alianzas(){

        $query=$this->db->get('alianza');

            return $query->result();
        }
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Alianzas_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
            }
            return $this; 
        }
   
        public function insert(){
   
           $query=$this->db->get_where('alianza',array('nombre_alianza'=>$this->nombre_alianza) );
           $alianzas=$query->row();
   
               if (isset($alianzas)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Alianza fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('alianza',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'alianza_id'=>$this->db->insert_id()
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
        $nombreTabla = "alianza";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Alianzas_model->verificar_camposEntrada($data);
        $this->db->where('idalianza', $campos["idalianza"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Alianza' => $campos

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
                'Alianza' => null
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
            if (property_exists('Alianzas_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
   
   
}