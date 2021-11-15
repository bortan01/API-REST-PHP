<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Restore_model extends CI_Model
{
   function droptable()
   {

      $connection = mysqli_connect('localhost', 'root', '', 'agencia');

		$tablasExistentes = array();
		$result = mysqli_query($connection, "SHOW TABLES");
		while ($row = mysqli_fetch_row($result)) {
			$tablasExistentes[] = $row[0];
		}

      $tablasPorEliminar = [
      "galeria",
      "chat_record",
      "detalle_servicio",
      "servicios_adicionales",
      "tipo_servicio",
      "contacto",
      "itinerario",
      "sitio_turistico",
      "tipo_sitio",
      "reserva_tour",
      "detalle_tour",
      "tours_paquete",
      "cotizar_tourpaquete",
      "galeria_vehiculo",
      "cotizarvehiculo",
      "mantenimiento",
      "reserva_vehiculo",
      "detalle_serviciosvehiculo",
      "detalle_vehiculo",
      "vehiculo",
      "modelo",
      "transmisionvehiculo",
      "usuarioRentaCar",
      "rentaCar",
      "categoria",
      "marca_vehiculo",
      "servicios_opc",
      "bitacora",
      "comision",
      "detalle_encomienda",
      "tarifa",
      "unidades_medidas",
      "producto",
      "detalle_destino",
      "detalle_envio",
      "encomienda",
      "municipio_envio",
      "cita",
      "opciones_respuestas",
      "formulario_migratorio",
      "pregunta",
      "ramas_preguntas",
      "info_adicional",
      "general",
      "cotizacion_vuelo",
      "tipo_viaje",
      "promocion_vuelo",
      "aerolinea",
      "alianza",
      "tipo_clase",
      "usuariorentacar",
      "rentacar",
      "usuario"];

      foreach ($tablasPorEliminar as $table) {
         if (in_array($table, $tablasExistentes)) {
            $this->dbforge->drop_table($table);
         }
      }

   }
}