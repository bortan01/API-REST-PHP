<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Clases_model extends CI_Model
{
    public $idclase;
    public $nombre_clase;
    
    public function get_clases(){
        $query=$this->db->get('tipo_clase');
            return $query->result();
        }

       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Clases_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){
           $query=$this->db->get_where('tipo_clase',array('nombre_clase'=>$this->nombre_clase) );
           $clases=$query->row();
   
               if (isset($clases)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Tipo de Clase fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('tipo_clase',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'clase_id'=>$this->db->insert_id()
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
        $nombreTabla = "tipo_clase";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Clases_model->verificar_camposEntrada($data);
        $this->db->where('idclase', $campos["idclase"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Tipo de Clase' => $campos

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
                'Tipo de Clase' => null
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
            if (property_exists('Clases_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
}