<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CotizarVehiculo_model extends CI_Model
{
    public $idcotizarVehiculo;
    public $id_usuario;
    public $modelo;
    public $anio;
    public $caracteristicas;
    public $direccion_recogida;
    public $fechaRecogida;
    public $HoraRecogida;
    public $direccion_devolucion;
    public $fechaDevolucion;
    public $HoraDevolucion;
    public $descuentosCotizacion=0;
    public $totalCotizacion=0;
    public $respuestaCotizacion="";
    public $activo = TRUE;
    
    public function get_cotizar(array $data){

        $parametros = $this->verificar_camposEntrada($data);
        $this->db->select('*');
        $this->db->from('cotizarvehiculo');
        $this->db->join('usuario', 'cotizarvehiculo.id_usuario = usuario.id_cliente');
        $this->db->join('modelo', 'cotizarvehiculo.modelo = modelo.idmodelo');
        $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaRecogida,"%d-%m-%Y") as fechaRecogida');
        $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaDevolucion,"%d-%m-%Y") as fechaDevolucion');
        $this->db->where($parametros);
        $this->db->where_in('cotizarvehiculo.activo',1);
        $this->db->order_by('idcotizarVehiculo', 'desc');
        $query=$this->db->get();

        $respuesta = $query->result();
    
        return $respuesta;
    }
   
       public function set_datos( $data_cruda){
   
            foreach ($data_cruda as $nombre_campo => $valor_campo) {
   
            if (property_exists('CotizarVehiculo_model',$nombre_campo)) {
                $this->$nombre_campo=$valor_campo;
            
            }
                
            }
            return $this; 
        }
   
        public function insert(){
   
            
           $query=$this->db->get_where('cotizarvehiculo',array('idcotizarVehiculo'=>$this->idcotizarVehiculo) );
           $lugar=$query->row();
   
               if (isset($lugar)) {
               $respuesta=array(
                   'err'=>TRUE,
                   'mensaje'=>'La cotizacion fue registrada'
               );
               return $respuesta;
               }
   
               //insertar el registro
               $hecho=$this->db->insert('cotizarvehiculo',$this);
   
               if ($hecho) {
                   #insertado
                   $respuesta=array(
                       'err'=>FALSE,
                       'mensaje'=>'Registro insertado correctamente',
                       'cotizacion_id'=>$this->db->insert_id()
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
        $nombreTabla = "cotizarvehiculo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->CotizarVehiculo_model->verificar_camposEntrada($data);
        $this->db->where('idcotizarVehiculo', $campos["idcotizarVehiculo"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) 
        {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Cotizacion' => $campos

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
                'Cotizacion' => null
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
            if (property_exists('CotizarVehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idcotizarVehiculo', $campos["idcotizarVehiculo"]);
        $hecho = $this->db->update('cotizarvehiculo', $campos);
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
                'cotizacion' => null
            );
            return $respuesta;
        }
    }
   
   
   
}