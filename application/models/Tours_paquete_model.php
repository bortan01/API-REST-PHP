<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tours_paquete_model extends CI_Model
{
    public $id_tours;
    public $nombreTours;
    public $start;
    public $end;
    public $lugar_salida;
    public $precio;
    public $incluye;
    public $no_incluye;
    public $requisitos;
    public $promociones;
    public $descripcion_tur;
    public $cupos_disponibles;
    public $nombre_encargado;
    public $estado;
    public $aprobado;
    public $tipo;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Tours_paquete_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }
    public function guardar(array $turPaquete)
    {
        // print_r($turPaquete);
        // die();
        $nombreTabla = "tours_paquete";
        $insert = $this->db->insert($nombreTabla, $turPaquete);
        if (!$insert) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'turPaquete'   => null
            );
            return $respuesta;
        } else {
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $foto = $this->Imagen_model->guardarGaleria("tours_paquete", $identificador);

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'id'           => $identificador,
                'turPaquete'   => $turPaquete
            );
            return $respuesta;
        }
    }
    public function obtenerViaje(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "tours_paquete";
        // $data["estado"] = 1;
        $parametros = $this->verificar_camposEntrada($data);



        $this->db->select('*');
        $this->db->from($nombreTabla);
        // $this->db->join('contacto', 'sitio_turistico.informacion_contacto=contacto.id_contacto');
        // $this->db->join('tipo_sitio', 'sitio_turistico.id_tipo_sitio=tipo_sitio.id_tipo_sitio');
        $this->db->where($parametros);

        $query = $this->db->get();
        $respuesta  = $query->result();

        foreach ($respuesta as $tur) {
            ///CON LA FUNCIOIN EXPLOTE CREAMOS UN UN ARRAY A PARTIR DE UN STRING, EN ESTE CASO
            //CADA ELEMENTO LLEGA HASTA DONDE APARECE UNA COMA
            $tur->incluye      = json_decode($tur->incluye, true);
            $tur->no_incluye   = json_decode($tur->no_incluye, true);
            $tur->requisitos   = json_decode($tur->requisitos, true);
            $tur->lugar_salida = json_decode($tur->lugar_salida, true);
            $tur->promociones  = json_decode($tur->promociones, true);
            $tur->descripcion_tur = nl2br($tur->descripcion_tur);
        }

        // foreach ($sitios as $fila) {
        //     $url = "http://www.lagraderia.com/wp-content/uploads/2018/12/no-imagen.jpg";
        //     $this->db->select("foto_path");
        //     $this->db->where("identificador", $fila->id_contacto);
        //     $this->db->where("tipo", "contacto");
        //     $query = $this->db->get("galeria");

        //     foreach ($query->result() as $galeria) {
        //         $url = $galeria->foto_path;
        //     }
        //     $fila->url = $url;
        // }


        return $respuesta;
    }
    public function obtenerViajeEdit(array $parametros = array())
    {
        $incluye = [];
        $no_incluye = [];
        $requisitos = [];
        $lugar_salida = [];
        $promociones = [];
        $nombreTur  = "";
        $start = "";
        $end = "";
        $precio = "";
        $cupos_disponibles = "";
        $descripcion_tur = "";


        $this->db->select('id_servicios,costo,por_usuario,nombre_servicio');
        $this->db->from("detalle_servicio");
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where($parametros);
        $query = $this->db->get();
        $servicios  = $query->result();

        $this->db->select('id_sitio_turistico,costo,por_usuario,nombre_sitio');
        $this->db->from("itinerario");
        $this->db->join('sitio_turistico', 'id_sitio_turistico');
        $this->db->where($parametros);
        $query = $this->db->get();
        $tur  = $query->result();

        $this->db->select('descripcion_tur,incluye,no_incluye,requisitos,lugar_salida, promociones,cupos_disponibles,nombreTours,start,end,precio', "descripcion_tur");
        $this->db->from("tours_paquete");
        $this->db->where($parametros);
        $query = $this->db->get();
        $result  = $query->result();

        foreach ($result as $viaje) {
            $incluye =  json_decode($viaje->incluye, true);
            $no_incluye =  json_decode($viaje->no_incluye, true);
            $requisitos =  json_decode($viaje->requisitos, true);
            $lugar_salida =  json_decode($viaje->lugar_salida, true);
            $promociones =  json_decode($viaje->promociones, true);
            $nombreTurX  = $viaje->nombreTours;
            $start =  $viaje->start;
            $end =  $viaje->end;
            $precio = $viaje->precio;
            $cupos_disponibles = $viaje->cupos_disponibles;
            $descripcion_tur = $viaje->descripcion_tur;
        }

        $respuesta = array(
            'nombre' => $nombreTurX,
            'start' => $start,
            'end' => $end,
            'precio' => $precio,
            'cupos' => $cupos_disponibles,
            'descripcion_tur' => $descripcion_tur,
            'incluye' => $incluye,
            'no_incluye' => $no_incluye,
            'requisitos' => $requisitos,
            'lugar_salidas' => $lugar_salida,
            'promociones' => $promociones,
            'servicios' => $servicios,
            'turs' => $tur,

        );

        return $respuesta;
    }
    public function informacionViaje(array $parametros = array())
    {
        $incluye = [];
        $no_incluye = [];
        $requisitos = [];
        $lugar_salida = [];
        $promociones = [];
        $nombreTur  = "";
        $start = "";
        $end = "";
        $precio = "";
        $cupos_disponibles = "";
        $descripcion_tur = "";


        $this->db->select('descripcion_tur,incluye,no_incluye,requisitos,lugar_salida, promociones,cupos_disponibles,nombreTours,start,end,precio', "descripcion_tur");
        $this->db->from("tours_paquete");
        $this->db->where($parametros);
        $query = $this->db->get();
        $result  = $query->result();

        foreach ($result as $viaje) {
            $incluye =  json_decode($viaje->incluye, true);
            $no_incluye =  json_decode($viaje->no_incluye, true);
            $requisitos =  json_decode($viaje->requisitos, true);
            $lugar_salida =  json_decode($viaje->lugar_salida, true);
            $promociones =  json_decode($viaje->promociones, true);
            $nombreTurX  = $viaje->nombreTours;
            $start =  $viaje->start;
            $end =  $viaje->end;
            $precio = $viaje->precio;
            $cupos_disponibles = $viaje->cupos_disponibles;
            $descripcion_tur = $viaje->descripcion_tur;
        }


        $this->db->select('asientos_deshabilitados, nombre_servicio, fila_trasera,  asiento_derecho, asiento_izquierdo,filas');
        $this->db->from("detalle_servicio");
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where(array('id_tours'=> $parametros['id_tours'], 'id_tipo_servicio'=>2));
        $query = $this->db->get();
        // $vehiculos  = $query->result();
        if ($query->row(0)) {
            $transporte = $query->row(0);
             $transporte;
        }else{
            $transporte = null;
        }

        $this->db->select('asientos_seleccionados');
        $this->db->from("tours_paquete");
        $this->db->join('detalle_tour', 'id_tours');
        $this->db->join('reserva_tour', 'id_detalle');
        $this->db->where(array('id_tours'=> $parametros['id_tours']));
        $query = $this->db->get();
        $asientos  = $query->result();
   



        $respuesta = array(
            'nombre' => $nombreTur,
            'start' => $start,
            'end' => $end,
            'precio' => $precio,
            'cupos' => $cupos_disponibles,
            'descripcion_tur' => $descripcion_tur,
            'incluye' => $incluye,
            'no_incluye' => $no_incluye,
            'requisitos' => $requisitos,
            'lugar_salidas' => $lugar_salida,
            'promociones' => $promociones,
            'transporte' =>$transporte,
            'asientos' =>$asientos

        );

        return $respuesta;
    }
    public function editar($data)
    {
        $nombreTabla = "tours_paquete";

        $campos = $this->Tours_paquete_model->verificar_camposEntrada($data);
        $this->db->where('id_tours', $campos["id_tours"]);
        $campos["start"] = $this->combertirFecha($campos["start"]);
        $campos["end"] = $this->combertirFecha($campos["end"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'viaje' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'viaje' => null
            );
            return $respuesta;
        }
    }
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Tours_paquete_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function borrar($campos)
    {

        $nombreTabla = "tours_paquete";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $identificador = $campos["id_tours"];
        $this->db->where('id_tours', $identificador);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $this->Imagen_model->eliminarGaleria($nombreTabla, $identificador);
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente',
                'id'      => $campos["id_tours"]
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),

            );
            return $respuesta;
        }
    }
    public function combertirFecha($fecha)
    {
        //PRIMERA PARTE ES COMO NOS MANDAN EL STRING (d/m/Y)
        //EL SEGUNDO ES EL NUEVO FORMATO AL QUE LO VAMOS A PASAR  (Y-m-d)
        return DateTime::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    }
}