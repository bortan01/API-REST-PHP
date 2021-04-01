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
        $nombreTabla = "tours_paquete";

        isset($turPaquete["start"]) &&  $turPaquete["start"] = $this->combertirFecha($turPaquete["start"]);
        isset($turPaquete["end"]) &&   $turPaquete["end"] = $this->combertirFecha($turPaquete["end"]);
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
            $tipoGaleria = $turPaquete['tipo'];
            $foto = $this->Imagen_model->guardarGaleria($tipoGaleria, $identificador);

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
        $nombreTabla = "tours_paquete";
        $parametros = $this->verificar_camposEntrada($data);

        $tipo = isset($parametros['tipo']) ? $parametros['tipo'] : '';
        // echo $tipo;
        switch ($tipo) {
            case 'Allpaquete':
                $this->db->where("(tipo='Paquete Nacional' OR tipo='Paquete Internacional' OR tipo='Paquete Privado')");
                break;
            case 'paquete':
                $this->db->where("(tipo='Paquete Nacional' OR tipo='Paquete Internacional')");
                break;
            case 'tour':
                $this->db->where("(tipo='Tour Nacional' OR tipo='Tour Internacional')");
                break;
            case 'Paquete Nacional':
                $this->db->where('tipo', 'Paquete Nacional');
                break;
            case 'Paquete Internacional':
                $this->db->where('tipo', 'Paquete Internacional');
                break;
            case 'Tour Nacional':
                $this->db->where('tipo', 'Tour Nacional');
                break;
            case 'Tour Internacional':
                $this->db->where('tipo', 'Tour Internacional');
                break;
            default:
                # code...
                break;
        }
        if (isset($parametros['tipo']))  unset($parametros['tipo']);

        $this->db->order_by('id_tours', 'DESC');
        $this->db->where($parametros);

        $query = $this->db->get($nombreTabla);
        $respuesta  = $query->result();
        $this->load->model('Imagen_model');
        foreach ($respuesta as $tur) {
            ///CON LA FUNCIOIN EXPLOTE CREAMOS UN UN ARRAY A PARTIR DE UN STRING, EN ESTE CASO
            //CADA ELEMENTO LLEGA HASTA DONDE APARECE UNA COMA
            $tur->incluye      = json_decode($tur->incluye, true);
            $tur->no_incluye   = json_decode($tur->no_incluye, true);
            $tur->requisitos   = json_decode($tur->requisitos, true);
            $tur->lugar_salida = json_decode($tur->lugar_salida, true);
            $tur->promociones  = json_decode($tur->promociones, true);
            $tur->descripcionForApp = ($tur->descripcion_tur);
            $tur->descripcion_tur = nl2br($tur->descripcion_tur);


            $identificador = $tur->id_tours;
            $tipoFoto = $tur->tipo;
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica($tipoFoto, $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $tur->foto = "http://localhost/API-REST-PHP/uploads/viaje.jpg";
            } else {
                $tur->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria($tipoFoto, $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $tur->galeria[] = "http://localhost/API-REST-PHP/uploads/viaje.jpg";
            } else {
                $tur->galeria = $respuestaGaleria;
            }
        }




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
    public function obtenerInfoAdicional(array $parametros = array())
    {
        $this->db->select('id_sitio_turistico,nombre_sitio,descripcion_sitio');
        $this->db->from("tours_paquete");
        $this->db->join('itinerario', 'id_tours');
        $this->db->join('sitio_turistico', 'id_sitio_turistico');
        $this->db->where($parametros);
        $query = $this->db->get();
        $sitiosTuristicos  = $query->result();

        foreach ($sitiosTuristicos  as $row) {
            $identificador = $row->id_sitio_turistico;
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('sitio_turistico', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        $this->db->select('id_servicios,nombre_servicio,descripcion_servicio');
        $this->db->from("tours_paquete");
        $this->db->join('detalle_servicio', 'id_tours');
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where($parametros);
        $query = $this->db->get();
        $servicosAdicionales  = $query->result();

        foreach ($servicosAdicionales  as $row) {
            $identificador = $row->id_servicios;
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('servicios_adicionales', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        $respuesta = array('sitiosTuristicos' => $sitiosTuristicos, 'serviciosAdicionales' => $servicosAdicionales);
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

        //INFORMACION GENERAL DE TUR O PAQUETE
        $this->db->select('descripcion_tur,incluye,no_incluye,requisitos,lugar_salida, promociones,cupos_disponibles,nombreTours,start,end,precio', "descripcion_tur");
        $this->db->from("tours_paquete");
        $this->db->where($parametros);
        $query = $this->db->get();
        $result  = $query->result();
        //MUCHA DE LA INFORMACION ESTA GUARDADA COMO STRING POR LO QUE HAY QUE DECODIFICARLA 
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

        //BUSCAMOS EL TRANSPORTE ASICIADO A ESTE TUR, POR DEFECTO SOLO TOMAREMOS LA CANTIDAD DE UN VEHICULO
        $this->db->select('asientos_deshabilitados, nombre_servicio, fila_trasera,  asiento_derecho, asiento_izquierdo,filas');
        $this->db->from("detalle_servicio");
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where(array('id_tours' => $parametros['id_tours'], 'id_tipo_servicio' => 2));
        $query = $this->db->get();
        //SI SE ENCONTRO EL VEHICULO ASOCIADO AL VIAJE, BUSCAREMOS LOS ASIENTOS QUE HAN SIDO RESERVADOS POR CLIENTES
        if ($query->row(0)) {
            $transporte = $query->row(0);
            $this->db->select('asientos_seleccionados');
            $this->db->from("tours_paquete");
            $this->db->join('detalle_tour', 'id_tours');
            $this->db->join('reserva_tour', 'id_detalle');
            $this->db->where(array('id_tours' => $parametros['id_tours']));
            $query = $this->db->get();

            $asientosOcupados = [];
            foreach ($query->result() as $row) {
                $seleccionados = explode(",", $row->asientos_seleccionados);
                foreach ($seleccionados as $item) {
                    array_push($asientosOcupados, $item);
                }
            }
            $transporte->ocupados = $asientosOcupados;
        } else {
            $transporte = null;
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
            'transporte' => $transporte,
            // 'asientos' => $asientos

        );

        return $respuesta;
    }
    public function editar($data)
    {
        $nombreTabla = "tours_paquete";

        $campos = $this->Tours_paquete_model->verificar_camposEntrada($data);
        $this->db->where('id_tours', $campos["id_tours"]);
        isset($campos["start"]) &&  $campos["start"] = $this->combertirFecha($campos["start"]);
        isset($campos["end"]) &&   $campos["end"] = $this->combertirFecha($campos["end"]);

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
    public function actualizarCupos($data)
    {
        $nombreTabla = "tours_paquete";
        $this->db->set('cupos_disponibles', 'cupos_disponibles -' . $data['cantidad_asientos'], FALSE);
        $this->db->where('id_tours', $data['id_tours']);
        $hecho = $this->db->update($nombreTabla);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number()
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