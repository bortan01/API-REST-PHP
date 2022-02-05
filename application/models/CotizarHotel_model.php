<?php
defined('BASEPATH') or exit('No direct script access allowed');
class CotizarHotel_model extends CI_Model
{
   public $idcotizacion_hotel;
   public $idhotel;
   public $idcliente;
   public $fechaEntradaSalida;
   public $detalleHabitaciones;
   public $servicios_adicionales;
   public $respuesta = "Sin respuesta";
   public $total ;
   public $activo = TRUE;

   public function get_cotizar(array $data)
   {

      $parametros = $this->verificar_camposEntrada($data);
      $this->db->select('*');
      $this->db->from('cotizacion_hotel');
      $this->db->join('usuario', 'cotizacion_hotel.id_usuario = usuario.id_cliente');
      $this->db->join('modelo', 'cotizacion_hotel.modelo = modelo.idmodelo');
      $this->db->select('DATE_FORMAT(cotizacion_hotel.fechaRecogida,"%d-%m-%Y") as fechaRecogida');
      $this->db->select('DATE_FORMAT(cotizacion_hotel.fechaDevolucion,"%d-%m-%Y") as fechaDevolucion');
      $this->db->where($parametros);
      $this->db->where_in('cotizacion_hotel.activo', 1);
      $this->db->order_by('idcotizarVehiculo', 'desc');
      $query = $this->db->get();

      $respuesta = $query->result();

      return $respuesta;
   }

   public function set_datos($data_cruda)
   {

      foreach ($data_cruda as $nombre_campo => $valor_campo) {

         if (property_exists('CotizarHotel_model', $nombre_campo)) {
            $this->$nombre_campo = $valor_campo;
         }
      }
      return $this;
   }

   public function insert()
   {

      //insertar el registro
      $hecho = $this->db->insert('cotizacion_hotel', $this);

      if ($hecho) {
         #insertado
         $respuesta = array(
            'err' => FALSE,
            'mensaje' => 'Registro insertado correctamente',
            'cotizacion_id' => $this->db->insert_id()
         );
      } else {
         //error
         $respuesta = array(
            'err' => TRUE,
            'mensaje' => 'Error al insertar',
            'error' => $this->db->_error_message(),
            'error_num' => $this->db->_error_number()
         );
      }
      return $respuesta;
   }
   //MODIFICAR
   public function editar($data)
   {
      $nombreTabla = "cotizacion_hotel";

      ///VAMOS A ACTUALIZAR UN REGISTRO
      $campos = $this->CotizarHotel_model->verificar_camposEntrada($data);
      $this->db->where('idcotizarVehiculo', $campos["idcotizarVehiculo"]);

      $hecho = $this->db->update($nombreTabla, $campos);
      if ($hecho) {
         ///LOGRO ACTUALIZAR 
         $respuesta = array(
            'err'     => FALSE,
            'mensaje' => 'Registro Actualizado Exitosamente',
            'Cotizacion' => $campos

         );
         return $respuesta;
      } else {
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
         if (property_exists('CotizarHotel_model', $nombre_campo)) {
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
      $hecho = $this->db->update('cotizacion_hotel', $campos);
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