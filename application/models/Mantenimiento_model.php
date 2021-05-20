<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Mantenimiento_model extends CI_Model
{
    public $id_mantenimiento;
    public $id_vehiculoFK;
    public $fecha_mantenimiento;
    public $lugar_mantenimiento;
    public $mantenimiento_realizado;
    public $piezas_cambiadas;
    public $comentariosIncidentes;
    public $costoMantenimiento;
    public $activo_mantenimiento = TRUE;
    
    public function get_mantenimiento(array $data){
    
        $parametros = $this->verificar_camposEntrada($data);//idMantenimiento 
        $this->db->select('*');
        $this->db->from('modelo');
        $this->db->join('marca_vehiculo', 'modelo.id_marca = marca_vehiculo.id_marca');
        $this->db->join('vehiculo', 'vehiculo.idmodelo = modelo.idmodelo');
        $this->db->join('mantenimiento', 'mantenimiento.id_vehiculoFK = vehiculo.idvehiculo');
        $this->db->select('DATE_FORMAT(mantenimiento.fecha_mantenimiento,"%d-%m-%Y") as fecha_mantenimiento');
        $this->db->where($parametros);//id_mantenimeto=1
        $this->db->where_in('mantenimiento.activo_mantenimiento',1);
        $query=$this->db->get();

        $respuesta = $query->result();
      
        
        foreach ($respuesta as $mantenimiento) {
            ///CON LA FUNCIOIN EXPLOTE CREAMOS UN UN ARRAY A PARTIR DE UN STRING, EN ESTE CASO
            //CADA ELEMENTO LLEGA HASTA DONDE APARECE UNA COMA
            $mantenimiento->mantenimiento_realizado =   explode(",", $mantenimiento->mantenimiento_realizado);
            $mantenimiento->piezas_cambiadas =   explode(",", $mantenimiento->piezas_cambiadas);
        }

            return $respuesta;
        }
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('Mantenimiento_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;   
            }
            }
            return $this; 
        }
   
        public function insert(){
            
           $query=$this->db->get_where('mantenimiento',array('fecha_mantenimiento'=>$this->fecha_mantenimiento) );
           $mantenimientos=$query->row();
   
               if (isset($mantenimientos)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'Mantenimiento fue registrado'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('mantenimiento',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'mantenimiento_id'=>$this->db->insert_id()
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
        $nombreTabla = "mantenimiento";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Mantenimiento_model->verificar_camposEntrada($data);
        $this->db->where('id_mantenimiento', $campos["id_mantenimiento"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Mantenimiento' => $campos

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
                'Mantenimiento' => null
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
            if (property_exists('Mantenimiento_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    } 
   
     //ELIMINAR
     public function borrar($campos)
     {
         //ELIMINAR UN REGISTRO
         $this->db->where('id_mantenimiento', $campos["id_mantenimiento"]);
         $hecho = $this->db->update('mantenimiento', $campos);
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
                 'Mantenimiento' => null
             );
             return $respuesta;
         }
     }
   
}