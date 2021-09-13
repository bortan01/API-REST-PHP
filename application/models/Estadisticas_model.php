<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Estadisticas_model extends CI_Model
{

   public function get_ingresos($star, $end)
   {
      $viajes      = $this->get_ingresosTours($star, $end);
      $asesorias   = $this->get_ingresosAsesorias($star, $end);
      $vehiculos   = $this->get_ingresosVehiculos($star, $end);
      $encominedas = $this->get_ingresosEncomiendas($star, $end);

      return array(
         'ingresosTours'       => $viajes['ingresosTours'],
         'ingresosPaquetes'    => $viajes['ingresosPaquetes'],
         'ingresoAsesorias'    => $asesorias['ingresoAsesorias'],
         'ingresoVehiculos'    => $vehiculos['ingresoVehiculos'],
         'ingresoEncomiendas'  => $encominedas['ingresoEncomiendas'],
         'tours'               => $viajes['tours'],
         'paquetes'            => $viajes['paquetes'],
         'asesorias'           => $asesorias['asesorias'],
         'vehiculos'           => $vehiculos['vehiculos'],
         'encomiendas'         => $encominedas['encomiendas'],
      );
   }
   public function get_ingresosTours($star,  $end)
   {
      $this->load->model('Conf_model');
      $this->db->select('id_cliente, id_tours, nombreTours, asientos_seleccionados, label_asiento, cantidad_asientos, start, end, lugar_salida, incluye, no_incluye, requisitos, descripcion_tur, fecha_reserva, formaPagoUtilizada, monto, resultadoTransaccion, tipo,usuario.nombre as nombreUsuario');
      $this->db->from('usuario');
      $this->db->join('detalle_tour', 'id_cliente');
      $this->db->join('tours_paquete', 'id_tours');
      $this->db->join('reserva_tour', 'id_detalle');
      $this->db->where('fecha_reserva >=', $star);
      $this->db->where('fecha_reserva <=', $end);


      $query = $this->db->get();
      $listaViajes = $query->result();
      $totalIngresosTours = 0;
      $totalIngresosPaquetes = 0;
      $listaTours = array();
      $listaPaquetes = array();
      if ($query->conn_id->error == '') {
         foreach ($listaViajes as $index => $viaje) {
            if ($viaje->tipo == 'Tour Nacional' || $viaje->tipo == 'Tour Internacional') {
               $totalIngresosTours += $viaje->monto;
               array_push($listaTours, $viaje);
            } else {
               $totalIngresosPaquetes += $viaje->monto;
               array_push($listaPaquetes, $viaje);
            }
         }
         // return $listaCitas;
         return  array(
            'ingresosTours'    => $totalIngresosTours,
            'ingresosPaquetes' => $totalIngresosPaquetes,
            'tours'            => $listaTours,
            'paquetes'         => $listaPaquetes
         );
      } else {
         return  array(
            'ingresosTours'    => 0,
            'ingresosPaquetes' => 0,
            'tours'            => array(),
            'paquetes'         => array()
         );
      }
   }
   public function get_ingresosVehiculos($start, $end)
   {
      $this->db->select('*');
      $this->db->from('detalle_vehiculo');
      $this->db->join('usuario', 'detalle_vehiculo.id_cliente = usuario.id_cliente');
      $this->db->join('vehiculo', 'detalle_vehiculo.id_vehiculo = vehiculo.idvehiculo');
      $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
      $this->db->where('fechaDevolucion >=', $start);
      $this->db->where('fechaDevolucion <=', $end);
      $query = $this->db->get();
      $listaReservas = $query->result();

      $totalReservas = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaReservas as $index => $reserva) {
            $totalReservas += $reserva->totalDevolucion;
         }
         return  array('ingresoVehiculos' => $totalReservas, 'vehiculos' => $listaReservas);
      } else {
         return  array('ingresoVehiculos' => 0, 'vehiculos' => array());
      }
   }
   public function get_ingresosEncomiendas($star, $end)
   {
      $this->db->select('*');
      $this->db->from('encomienda');
      $this->db->join('usuario', 'usuario.id_cliente = encomienda.id_usuario');
      $this->db->where('fecha >=', $star);
      $this->db->where('fecha <=', $end);
      $query = $this->db->get();
      $listaEncomiendas = $query->result();

      $totalReservas = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaEncomiendas as $index => $encomineda) {
            $totalReservas += $encomineda->total_cliente;
         }
         return  array('ingresoEncomiendas' => $totalReservas, 'encomiendas' => $listaEncomiendas);
      } else {
         return  array('ingresoEncomiendas' => 0, 'encomiendas' => array());
      }
   }
   public function get_ingresosAsesorias($star, $end)
   {

      $this->db->select('*');
      $this->db->from('cita');
      $this->db->join('usuario', 'id_cliente');
      $this->db->where('fecha >=', $star);
      $this->db->where('fecha <=', $end);

      $query = $this->db->get();
      $listaCitas = $query->result();
      $totalAsesorias = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaCitas as $index => $cita) {
            $totalAsesorias += $cita->cobros;
         }
         return  array('ingresoAsesorias' => $totalAsesorias, 'asesorias' => $listaCitas);
      } else {
         return  array('ingresoAsesorias' => 0, 'asesorias' => array());
      }
   }
}