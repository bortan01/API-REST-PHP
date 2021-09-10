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
         'tours'       => $viajes['tours'],
         'paquetes'    => $viajes['paquetes'],
         'asesorias'   => $asesorias,
         'vehiculos'   => $vehiculos,
         'encominedas' => $encominedas,
      );
   }
   public function get_ingresosTours($star,  $end)
   {
      $this->load->model('Conf_model');
      $this->db->select('id_cliente, id_tours, nombreTours, asientos_seleccionados, label_asiento, cantidad_asientos, start, end, lugar_salida, incluye, no_incluye, requisitos, descripcion_tur, fecha_reserva, formaPagoUtilizada, monto, resultadoTransaccion, tipo');
      $this->db->from('usuario');
      $this->db->join('detalle_tour', 'id_cliente');
      $this->db->join('tours_paquete', 'id_tours');
      $this->db->join('reserva_tour', 'id_detalle');
      $this->db->where('fecha_reserva >=', $star);
      $this->db->where('fecha_reserva <=', $end);


      $query = $this->db->get();
      $listaCitas = $query->result();
      $totalIngresosTours = 0;
      $totalIngresosPaquetes = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaCitas as $index => $tours) {
            if ($tours->tipo == 'Tour Nacional' || $tours->tipo == 'Tour Internacional') {
               $totalIngresosTours += $tours->monto;
            } else {
               $totalIngresosPaquetes += $tours->monto;
            }
         }
         // return $listaCitas;
         return  array('tours' => $totalIngresosTours, 'paquetes' => $totalIngresosPaquetes);
      } else {
         return  array('tours' => 0, 'paquetes' => 0);
      }
   }
   public function get_ingresosVehiculos($start, $end)
   {
      $this->db->select('*');
      $this->db->from('detalle_vehiculo');
      $this->db->where('fechaDevolucion >=', $start);
      $this->db->where('fechaDevolucion <=', $end);
      $query = $this->db->get();
      $listaReservas= $query->result();

      $totalReservas = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaReservas as $index => $reserva) {
            $totalReservas += $reserva->totalDevolucion;
         }
         return  $totalReservas;
      } else {
         return  0;
      }
   }
   public function get_ingresosEncomiendas($star, $end)
   {
      $this->db->select('*');
      $this->db->from('encomienda');
      $this->db->where('fecha >=', $star);
      $this->db->where('fecha <=', $end);
      $query = $this->db->get();
      $listaEncomiendas= $query->result();

      $totalReservas = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaEncomiendas as $index => $encomineda) {
            $totalReservas += $encomineda->total_cliente;
         }
         return  $totalReservas;
      } else {
         return  0;
      }
   }
   public function get_ingresosAsesorias($star, $end)
   {

      $this->db->select('*');
      $this->db->from('cita');
      $this->db->where('fecha >=', $star);
      $this->db->where('fecha <=', $end);

      $query = $this->db->get();
      $listaCitas = $query->result();
      $totalAsesorias = 0;
      if ($query->conn_id->error == '') {
         foreach ($listaCitas as $index => $cita) {
            $totalAsesorias += $cita->cobros;
         }
         return  $totalAsesorias;
      } else {
         return  0;
      }
   }
}