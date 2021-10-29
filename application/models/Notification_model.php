<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Notification_model extends CI_Model
{
   public function obtenerNotifications()
   {
      $notificationsVehiculo = $this->getCotizacionesVehiculo();
      $notificationsVuelo = $this->getCotizacionesVuelos();
      $cotizacionesPaquetes = $this->getCotizacionesPaquetes();
      $ultimasReservas = $this->getUltimasReservas();
      $Allnotifications = $notificationsVehiculo +$notificationsVuelo+$cotizacionesPaquetes+$ultimasReservas;

      $data = array(
         'notificationsVehiculo' => $notificationsVehiculo,
         'notificationsVuelo'    => $notificationsVuelo,
         'cotizacionesPaquetes'  => $cotizacionesPaquetes,
         'ultimasReservas'       => $ultimasReservas,
         'Allnotifications'      => $Allnotifications
      );
      return $data;
   }
   public function getCotizacionesVehiculo()
   {
      $this->db->select('*');
      $this->db->from('cotizarvehiculo');
      $this->db->join('usuario', 'cotizarvehiculo.id_usuario = usuario.id_cliente');
      $this->db->join('modelo', 'cotizarvehiculo.modelo = modelo.idmodelo');
      $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaRecogida,"%d-%m-%Y") as fechaRecogida');
      $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaDevolucion,"%d-%m-%Y") as fechaDevolucion');
      $this->db->where('cotizarvehiculo.activo', 1);
      $this->db->order_by('idcotizarVehiculo', 'desc');
      //$query = $this->db->get();

      $respuesta = $this->db->count_all_results();

      return $respuesta;
   }
   public function getCotizacionesVuelos()
   {
      $this->db->select('*');
      $this->db->from('cotizacion_vuelo');
      $this->db->join('aerolinea', 'cotizacion_vuelo.idaerolinea = aerolinea.idaerolinea');
      $this->db->join('alianza', 'aerolinea.idalianza = alianza.idalianza');
      $this->db->join('tipo_clase', 'cotizacion_vuelo.idclase = tipo_clase.idclase');
      $this->db->join('tipo_viaje', 'cotizacion_vuelo.idtipo_viaje = tipo_viaje.idtipo_viaje');
      $this->db->join('usuario', 'cotizacion_vuelo.id_cliente = usuario.id_cliente');
      $this->db->select('DATE_FORMAT(cotizacion_vuelo.fechaPartida,"%d-%m-%Y") as fechaPartida');
      $this->db->select('DATE_FORMAT(cotizacion_vuelo.fechaLlegada,"%d-%m-%Y") as fechaLlegada');
      $this->db->where_in('cotizacion_vuelo.activo', 1);
      //$query = $this->db->get();

      $respuesta = $this->db->count_all_results();
      return $respuesta;
   }

   public function getCotizacionesPaquetes()
   {

      $this->db->select('*');
      $this->db->from('cotizar_tourpaquete');
      $this->db->order_by('idCotizar', 'ASC');
      $this->db->where('visto', '0');
      // $query = $this->db->get();
      $cotizaciones  = $this->db->count_all_results();

      return $cotizaciones;
   }

   public function getUltimasReservas()
   {
      $this->db->select('id_cliente,
                           id_tours,
                           id_detalle,
                           nombre,
                           nombreTours,
                           asientos_seleccionados,
                           label_asiento,
                           cupos_originales,
                           cantidad_asientos,
                           start,
                           end,
                           requisitos,
                           fecha_reserva,
                           formaPagoUtilizada,
                           monto,
                           descripcionProducto,
                           resultadoTransaccion,
                           chequeo,
                           tipo');

      $this->db->from('usuario');
      $this->db->join('detalle_tour', 'id_cliente');
      $this->db->join('tours_paquete', 'id_tours');
      $this->db->join('reserva_tour', 'id_detalle');
      $this->db->where('resultadoTransaccion', 'ExitosaAprobada');
      $this->db->where('
               `fecha_reserva` > (
                            SELECT `notificationTours` FROM `usuario` where `id_cliente`= 2019200712)
               ', NULL, FALSE);
      // $this->db->where($parametros);
      $ultimasReservas =  $this->db->count_all_results();
      return $ultimasReservas;
   }
   public function getFechaNotificacion($id_cliente)
   {

      $this->db->select('notificationTours');
      $this->db->from('usuario');
      $this->db->where('id_cliente', $id_cliente);
      $query = $this->db->get();
      return $query->result();
   }
}