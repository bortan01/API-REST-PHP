<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Lugar_model extends CI_Model
{
    public $idlugar_recog_dev;
    public $nombre_lugar; 
    public $activo = TRUE;
    
    public function get_lugar(){

        $query=$this->db->get('lugar_recog_dev');

            return $query->result();
        }

       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Lugar_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('lugar_recog_dev',array('nombre_lugar'=>$this->nombre_lugar) );
           $lugar=$query->row();
   
               if (isset($lugar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El lugar fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('lugar_recog_dev',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'lugar_id'=>$this->db->insert_id()
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
        $nombreTabla = "lugar_recog_dev";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Lugar_model->verificar_camposEntrada($data);
        $this->db->where('idlugar_recog_dev', $campos["idlugar_recog_dev"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Lugar' => $campos

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
                'Lugar' => null
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
            if (property_exists('Lugar_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

     //ELIMINAR
     public function borrar($campos)
     {
         //ELIMINAR UN REGISTRO
         $this->db->where('idlugar_recog_dev', $campos["idlugar_recog_dev"]);
         $hecho = $this->db->update('lugar_recog_dev', $campos);
         if ($hecho) {
             //ELIMINANDO REGISTRO
             $respuesta = array(
                 'err'     => FALSE,
                 'mensaje' => 'Registro Eliminado Exitosamente'
             );
             return $respuesta;
         } else {
             //NO ELIMINO
             $respuesta = array(
                 'err' => TRUE,
                 'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                 'error_number' => $this->db->error_number(),
                 'lugar' => null
             );
             return $respuesta;
         }
     }
   
}