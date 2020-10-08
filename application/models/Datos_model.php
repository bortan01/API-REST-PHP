<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Datos_model extends CI_Model
{
    public $id_generales;
    public $ciudad_partida;
    public $fechaHoraPartida;
    public $ciudad_llegada;
    public $fechaHoraLlegada;
    public $adultos;
    public $ninos;
    public $bebes;
    public $maletas;
    public $activo = TRUE;
    
    public function get_generales(){

        $query=$this->db->get('datos_generales');

            return $query->result();
        }
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Datos_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            }
            }
            return $this; 
        }
   
        public function insert(){
           $query=$this->db->get_where('datos_generales',array('id_generales'=>$this->id_generales) );
           $datos=$query->row();
   
               if (isset($datos)) {
               $respuesta=array(
                   
                   'err'=>TRUE,
                   'mensaje'=>'Datos generales fueron registrados'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('datos_generales',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'datos_id'=>$this->db->insert_id()
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
        $nombreTabla = "datos_generales";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Datos_model->verificar_camposEntrada($data);
        $this->db->where('id_generales', $campos["id_generales"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Datos Generales' => $campos

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
                'Datos Generales' => null
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
            if (property_exists('Datos_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('id_generales', $campos["id_generales"]);
        $hecho = $this->db->update('datos_generales', $campos);
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
                'Datos Generales' => null
            );
            return $respuesta;
        }
    }
}