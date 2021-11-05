<?php

use Google\CRC32\Table;

defined('BASEPATH') or exit('No direct script access allowed');
class Restore_model extends CI_Model
{
   function droptable()
   {

      $this->dbforge->drop_table("galeria");
      $this->dbforge->drop_table("chat_record");
      $this->dbforge->drop_table("detalle_servicio");
      $this->dbforge->drop_table("servicios_adicionales");
      $this->dbforge->drop_table("tipo_servicio");
      $this->dbforge->drop_table("contacto");
      $this->dbforge->drop_table("itinerario");
      $this->dbforge->drop_table("sitio_turistico");
      $this->dbforge->drop_table("tipo_sitio");
      $this->dbforge->drop_table("reserva_tour");
      $this->dbforge->drop_table("detalle_tour");
      $this->dbforge->drop_table("tours_paquete");
      $this->dbforge->drop_table("cotizar_tourPaquete");
      $this->dbforge->drop_table("galeria_vehiculo");
      $this->dbforge->drop_table("cotizarvehiculo");
      $this->dbforge->drop_table("mantenimiento");
      $this->dbforge->drop_table("reserva_vehiculo");
      $this->dbforge->drop_table("detalle_serviciosvehiculo");
      $this->dbforge->drop_table("detalle_vehiculo");
      $this->dbforge->drop_table("vehiculo");
      $this->dbforge->drop_table("modelo");
      $this->dbforge->drop_table("transmisionvehiculo");
      $this->dbforge->drop_table("usuarioRentaCar");
      $this->dbforge->drop_table("rentaCar");
      $this->dbforge->drop_table("categoria");
      $this->dbforge->drop_table("marca_vehiculo");
      $this->dbforge->drop_table("servicios_opc");
      $this->dbforge->drop_table("bitacora");
      $this->dbforge->drop_table("comision");
      $this->dbforge->drop_table("detalle_encomienda");
      $this->dbforge->drop_table("tarifa");
      $this->dbforge->drop_table("unidades_medidas");
      $this->dbforge->drop_table("producto");
      $this->dbforge->drop_table("detalle_destino");
      $this->dbforge->drop_table("detalle_envio");
      $this->dbforge->drop_table("encomienda");
      $this->dbforge->drop_table("municipio_envio");
      $this->dbforge->drop_table("cita");
      $this->dbforge->drop_table("opciones_respuestas");
      $this->dbforge->drop_table("formulario_migratorio");
      $this->dbforge->drop_table("pregunta");
      $this->dbforge->drop_table("ramas_preguntas");
      $this->dbforge->drop_table("info_adicional");
      $this->dbforge->drop_table("general");
      $this->dbforge->drop_table("cotizacion_vuelo");
      $this->dbforge->drop_table("tipo_viaje");
      $this->dbforge->drop_table("promocion_vuelo");
      $this->dbforge->drop_table("aerolinea");
      $this->dbforge->drop_table("alianza");
      $this->dbforge->drop_table("tipo_clase");
      $this->dbforge->drop_table("usuario");
   }
}