<?php
defined('BASEPATH') or exit('No direct script access allowed');
class UsuarioRentaAuto_model extends CI_Model
{
    public $idusuarioRentaCar;
    public $usuario;
    public $contrasena;
    public $activo = TRUE;

    public function get_usuario(){

        $query=$this->db->get('usuarioRentaCar');

            return $query->result();
        }
       public function set_datos( $data_cruda){
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('UsuarioRentaAuto_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('usuarioRentaCar',array('usuario'=>$this->usuario) );
           $usu=$query->row();
   
               if (isset($usu)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Encargado de RentaCars fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('usuarioRentaCar',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'usuario_id'=>$this->db->insert_id()
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
        $nombreTabla = "usuarioRentaCar";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->UsuarioRentaAuto_model->verificar_camposEntrada($data);
        $this->db->where('idusuarioRentaCar', $campos["idusuarioRentaCar"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Encargado de Renta Cars' => $campos

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
                'Encargado de Renta Cars' => null
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
            if (property_exists('UsuarioRentaAuto_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    } 


    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idusuarioRentaCar', $campos["idusuarioRentaCar"]);
        $hecho = $this->db->update('usuarioRentaCar', $campos);
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
                'Encargado de Renta Cars' => null
            );
            return $respuesta;
        }
    }  
}