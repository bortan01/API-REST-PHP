<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Modelo_model extends CI_Model
{
    public $idmodelo;
    public $id_marca;
    public $modelo;

    public function get_modelo(){

        $query=$this->db->get('modelo');

            return $query->result();
        }
   
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Modelo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }


        //INSERTAR
        public function insert(){
   
            
           $query=$this->db->get_where('modelo',array('modelo'=>$this->modelo) );
           $modelos=$query->row();
   
               if (isset($modelos)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Modelo fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('modelo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'modelo_id'=>$this->db->insert_id()
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
        $nombreTabla = "modelo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Modelo_model->verificar_camposEntrada($data);
        $this->db->where('idmodelo', $campos["idmodelo"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'modelo' => $campos

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
                'modelo' => null
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
            if (property_exists('Modelo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
}