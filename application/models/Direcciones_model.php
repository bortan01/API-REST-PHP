<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Direcciones_model extends CI_Model
{
    public $iddireccionesReserva;
    public $idlugar;
    public $direccionRecogida;
    public $fechaHoraRecogida;
    public $direccionDevolucion;
    public $fechaHoraDevolucion;

    public function get_direccion(){

        $query=$this->db->get('direccionesreserva');

            return $query->result();
        }
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Direcciones_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
            
           $query=$this->db->get_where('direccionesreserva',array('iddireccionesReserva'=>$this->iddireccionesReserva) );
           $direccion=$query->row();
   
               if (isset($direccion)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'La direccion fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('direccionesreserva',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'direccion_id'=>$this->db->insert_id()
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
        $nombreTabla = "direccionesreserva";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Direcciones_model->verificar_camposEntrada($data);
        $this->db->where('iddireccionesReserva', $campos["iddireccionesReserva"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Direccion' => $campos

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
                'Direccion' => null
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
            if (property_exists('Direcciones_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
   
}