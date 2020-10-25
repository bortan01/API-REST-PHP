<?php
defined('BASEPATH') or exit('No direct script access allowed');
class RentaAuto_model extends CI_Model
{
    public $id_rentaCar;
    public $rentaCar;
    public $lugar;
    public $descripcion;
    public $telefono;
    public $usuario;
    public $contrasena;
    public $activo = TRUE;

    public function get_rentaAuto(){

        $query=$this->db->get('rentacar');

            return $query->result();
        }
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('RentaAuto_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('rentacar',array('rentaCar'=>$this->rentaCar) );
           $carrito=$query->row();
   
               if (isset($carrito)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'El RentaCars fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('rentacar',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'rentacar_id'=>$this->db->insert_id()
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
        $nombreTabla = "rentacar";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->RentaAuto_model->verificar_camposEntrada($data);
        $this->db->where('id_rentaCar', $campos["id_rentaCar"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Renta Car' => $campos

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
                'Renta Car' => null
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
            if (property_exists('RentaAuto_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    } 


    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('id_rentaCar', $campos["id_rentaCar"]);
        $hecho = $this->db->update(' rentacar', $campos);
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
                'renta cars' => null
            );
            return $respuesta;
        }
    }
   
}