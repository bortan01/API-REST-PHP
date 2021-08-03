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
    public function guardarTourPrivado(array $turPaquete, string $id_cliente)
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
            $this->Imagen_model->guardarGaleria($tipoGaleria, $identificador);

            $dataDetalle                           = [];
            $dataDetalle['id_cliente']             = $id_cliente;
            $dataDetalle['id_tours']               = $identificador;
            $dataDetalle['nombre_producto']        = $turPaquete['nombreTours'];
            $dataDetalle['total']                  = $turPaquete['precio'] * $turPaquete['cupos_disponibles'];
            $dataDetalle['cantidad_asientos']      = $turPaquete['cupos_disponibles'];
            $dataDetalle['chequeo']                = $turPaquete['requisitos'];
            $dataDetalle['asientos_seleccionados'] = 'NO_SELECCIONADO';
            $dataDetalle['label_asiento']          = 'NO_LABEL';
            $dataDetalle['descripcionProducto']    = 'Reserva Completa';

            $respuestaDetalle = $this->guardarDetalle($dataDetalle);
            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'id'           => $identificador,
                'turPaquete'   => $turPaquete,
                'data'         => $respuestaDetalle,
            );
            return $respuesta;
        }
    }
    public function guardarDetalle($data)
    {
        $idDetalle = date("His") . rand(1, 1000);
        $data['id_detalle'] = $idDetalle;
        $respuestaDetalle     = $this->Detalle_tour_model->guardar($data);
        if ($respuestaDetalle['err']) {
            return $respuestaDetalle;
        } else {
            $reservaTur = [];
            $reservaTur["IdTransaccion"]        = date("HisYmd") . rand(1, 1000);
            $reservaTur["EnlacePago"]["Id"]     = $idDetalle;
            $reservaTur["FechaTransaccion"]     = date("Y-m-d H:i:s");
            $reservaTur["FormaPagoUtilizada"]   = 'Agencia';
            $reservaTur["ResultadoTransaccion"] = 'ExitosaAprobada';
            $reservaTur["Monto"]                = $data['total'];
            $reservaTur["Cantidad"]             = 1;

            $respuestaReserva = $this->ReservaTour_model->guardar($reservaTur);
            if ($respuestaReserva['err']) {
                return $respuestaReserva;
            } else {
                return $respuestaReserva;
            }
        }
    }
    public function obtenerViaje(array $data = array())
    {
        $this->load->model('Conf_model');
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
                $tur->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.jpg";
            } else {
                $tur->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria($tipoFoto, $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $tur->galeria[] = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.jpg";
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
        $start = "";
        $end = "";
        $precio = "";
        $cupos_disponibles = "";
        $descripcion_tur = "";
        $nombreTurX = "";

        //INFORMACION GENERAL DE TUR O PAQUETE
        $this->db->select('descripcion_tur,incluye,no_incluye,requisitos,lugar_salida, promociones,cupos_disponibles,nombreTours,start,end,precio,descripcion_tur');
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
            $this->db->where(array('id_tours' => $parametros['id_tours'], 'resultadoTransaccion' => 'ExitosaAprobada'));
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

    public function obtenerInfoReserva(array $data)
    {
        $this->load->model('Conf_model');
        $this->db->select('id_cliente, id_tours, nombreTours, asientos_seleccionados, label_asiento, cantidad_asientos, start, end, lugar_salida, incluye, no_incluye, requisitos, descripcion_tur, fecha_reserva, formaPagoUtilizada, monto, descripcionProducto, resultadoTransaccion, tipo');
        $this->db->from('usuario');
        $this->db->join('detalle_tour', 'id_cliente');
        $this->db->join('tours_paquete', 'id_tours');
        $this->db->join('reserva_tour', 'id_detalle');
        $this->db->where("id_cliente", $data["id_cliente"]);


        $query = $this->db->get();
        $infoReserva = $query->result();

        if ($query->conn_id->error == '') {
            foreach ($infoReserva as  $value) {
                $value->descripcionWeb = nl2br($value->descripcion_tur);
                $value->transporte =  $this->obtenerTransporte($value->id_tours);


                $value->incluye                = json_decode($value->incluye, true);
                $value->no_incluye             = json_decode($value->no_incluye, true);
                $value->requisitos             = json_decode($value->requisitos, true);
                $value->lugar_salida           = json_decode($value->lugar_salida, true);
                $value->asientos_seleccionados = explode(',', $value->asientos_seleccionados);


                $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica($value->tipo, $value->id_tours);
                if ($respuestaFoto == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $value->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.jpg";
                } else {
                    $value->foto = $respuestaFoto;
                }


                $respuestaGaleria =   $this->Imagen_model->obtenerGaleria($value->tipo, $value->id_tours);
                if ($respuestaGaleria == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $value->galeria[] = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.jpg";
                } else {
                    $value->galeria = $respuestaGaleria;
                }
            }

            $respuesta = array(
                'err'              => FALSE,
                'mensaje'          => 'Datos Cargados Exitosamente',
                'reservas'         => $infoReserva
            );

            return $respuesta;
        } else {
            $respuesta = array(
                'err'              => TRUE,
                'mensaje'          => 'Error al Cargar Datos',
                'reservas'         =>  null
            );
        }
    }


    public function combertirFecha($fecha)
    {
        //PRIMERA PARTE ES COMO NOS MANDAN EL STRING (d/m/Y)
        //EL SEGUNDO ES EL NUEVO FORMATO AL QUE LO VAMOS A PASAR  (Y-m-d)
        return DateTime::createFromFormat('d/m/Y', $fecha)->format('Y-m-d');
    }

    public function obtenerTransporte($idTour)
    {
        $this->db->select('filas, 
                           asiento_derecho, 
                           asiento_izquierdo, 
                           fila_trasera, 
                           id_tours, 
                           id_tipo_servicio,
                           asientos_deshabilitados
                        ');
        $this->db->from('detalle_servicio');
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where('id_tipo_servicio', '2');
        $this->db->where('id_tours', $idTour);
        $respuesta = $this->db->get()->row();
        if ($respuesta == null) {
            return null;
        }

        $respuesta->asientos_deshabilitados =  explode(',', $respuesta->asientos_deshabilitados);
        return $respuesta;
    }

    public function guardarCotizacion(array $data)
    {
        $nombreTabla = 'cotizar_tourpaquete';
        $insert = $this->db->insert($nombreTabla, $data);

        if (!$insert) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'cotizacion'   => null
            );
            return $respuesta;
        } else {
            $identificador = $this->db->insert_id();
            $data['idCotizar'] = $identificador;

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'cotizacion'   => $data
            );
            return $respuesta;
        }
    }
    public function obtenerCotizaciones(array $parametros = array())
    {

        $this->db->select('*');
        $this->db->from('cotizar_tourpaquete');
        $this->db->join('usuario', 'id_cliente');
        $this->db->order_by('idCotizar', 'ASC');
        $this->db->where('visto', $parametros['visto']);
        $query = $this->db->get();
        $cotizaciones  = $query->result();


        return $cotizaciones;
    }

    public function responderCotizacion(array $campos = array())
    {
        $nombreTabla = 'cotizar_tourpaquete';
        $this->db->where('idCotizar', $campos["idCotizar"]);
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
    public function obtenerRespuestas(array $campos = array())
    {
        $nombreTabla = 'cotizar_tourpaquete';
        $this->db->where('id_cliente', $campos["id_cliente"]);
        $query = $this->db->get($nombreTabla);
        $cotizaciones = $query->result();
        if ($cotizaciones) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'          => FALSE,
                'cotizaciones' => $cotizaciones
            );
            return $respuesta;
        }
    }
    public function obtenerAnalitica(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $this->db->select('id_cliente,
                           id_tours,
                           id_detalle,
                           nombre,
                           nombreTours,
                           asientos_seleccionados,
                           label_asiento,
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
        $this->db->where($parametros);


        $query            = $this->db->get();
        $infoReserva      = $query->result();
        $transporte       = $this->obtenerTransporte($data["id_tours"]);
        $sitios           = $this->obtenerSitios($data["id_tours"]);
        $servicios        = $this->obtenerServicios($data["id_tours"]);
        $asientosOcupados = [];
        $totalIngresos    = 0;
        $totalPasajeros   = 0;
        $nombre           = '';
        $start            = '';
        $end              = '';


        if ($query->conn_id->error == '') {
            foreach ($infoReserva as  $value) {
                $totalIngresos                += $value->monto;
                $totalPasajeros               += $value->cantidad_asientos;
                $nombre                       = $value->nombreTours;
                $start                        = $value->start;
                $end                          = $value->end;
                $value->requisitos            = json_decode($value->requisitos, true);
                $value->chequeo               = json_decode($value->chequeo, true);
                $value->descripcionProducto   = nl2br($value->descripcionProducto);
                $listaAsientos                = explode(',', $value->asientos_seleccionados);

                foreach ($listaAsientos as $asiento) {
                    array_push($asientosOcupados, $asiento);
                }
            }

            $respuesta = array(
                'err'              => FALSE,
                'mensaje'          => 'Datos Cargados Exitosamente',
                'nombre'           => $nombre,
                'totalIngresos'    => $totalIngresos,
                'start'            => $start,
                'end'              => $end,
                'tatalPasajeros'   => $totalPasajeros,
                'reservas'         => $infoReserva,
                'sitios'           => $sitios,
                'servicios'        => $servicios,
                'transporte'       => $transporte,
                'ocupados'         => $asientosOcupados
            );

            return $respuesta;
        } else {
            $respuesta = array(
                'err'              => TRUE,
                'mensaje'          => 'Error al Cargar Datos',
                'reservas'         =>  null,
                'transporte'       =>  null
            );
        }
    }
    public function obtenerServicios(String $parametros)
    {
        $this->db->select('id_servicios,costo,por_usuario,nombre_servicio');
        $this->db->from("detalle_servicio");
        $this->db->join('servicios_adicionales', 'id_servicios');
        $this->db->where('id_tours', $parametros);
        $query = $this->db->get();
        $servicios  = $query->result();
        return $servicios;
    }
    public function obtenerSitios(String $parametros)
    {
        $this->db->select('id_sitio_turistico,costo,por_usuario,nombre_sitio');
        $this->db->from("itinerario");
        $this->db->join('sitio_turistico', 'id_sitio_turistico');
        $this->db->where('id_tours', $parametros);
        $query = $this->db->get();
        $sitios  = $query->result();
        return $sitios;
    }
}
