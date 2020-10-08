<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Itinerario_model extends CI_Model
{
    public function __construct()
    {
        $this->load->model("Utils_model");
    }

    public $id_itinerario;
    public $id_tours;
    public $id_sitio_turistico;
    public $title;
    public $start;
    public $end;
    public $allDay;
    public $backgroundColor;
    public $borderColor;


    public function verificar_campos(array $dataCruda)
    {
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists("Itinerario_model", $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $this;
    }
    public function guardar(array $data)
    {
        $nombreTabla = "itinerario";


        $insert = $this->db->insert_batch($nombreTabla, $data);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'itinerario'      => null
            );
            return $respuesta;
        } else {
            $this->id_itinerario = $this->db->insert_id();
            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro de tItinerario Guardado Exitosamente',
            );
            return $respuesta;
        }
    }

    public function editar(array $data)
    {
        $nombreTabla = "itinerario";

        for ($i = 0; $i < count($data); $i++) {
            if (isset($data[$i]["end"])) {
                $fecha = $data[$i]["end"] = DateTime::createFromFormat('d/m/Y H:m:s', $data[$i]["end"]);
                $data[$i]["end"] = $data[$i]["end"]->format('Y-m-d h:m:s');
            }
            if (isset($data[$i]["start"])) {
                $fecha =  DateTime::createFromFormat('d/m/Y H:m:s', $data[$i]["start"]);
                $data[$i]["start"] = $fecha->format('Y-m-d h:m:s');
            }
        }

        try {

            $insert = $this->db->update_batch($nombreTabla, $data, 'id_itinerario');


            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'         => TRUE,
                    'mensaje'     => 'Ningun Registro Fue Modificado',
                    'itinerario'  => null
                );
                return $respuesta;
            } else {
                $this->id_itinerario = $this->db->insert_id();
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Guardado Exitosamente',

                );
                return $respuesta;
            }
        } catch (\Throwable $th) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'PROBLEMA INTERNO DE SERVIDO',

            );
            return $respuesta;
        }
    }

    public function obtener(array $data)
    {
        $nombreTabla = "itinerario";
        try {
            $parametros = $this->verificar_camposEntrada($data);
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $itinerarioSeleccionado = $query->result();

            if (count($itinerarioSeleccionado) < 1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'itinerario'     => null
                );
                return $respuesta;
            } else {


                for ($i = 0; $i < count($itinerarioSeleccionado); $i++) {
                    if (isset($itinerarioSeleccionado[$i]->start) && $itinerarioSeleccionado[$i]->start != null) {

                        $itinerarioSeleccionado[$i]->start = new DateTime($itinerarioSeleccionado[$i]->start);
                    }
                    if (isset($itinerarioSeleccionado[$i]->end) && $itinerarioSeleccionado[$i]->end != null) {
                        $itinerarioSeleccionado[$i]->end = new DateTime($itinerarioSeleccionado[$i]->end);
                    }
                }
                $respuesta = array(
                    'err'          => FALSE,
                    'itinerario'   => $itinerarioSeleccionado
                );
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }













        return $respuesta;
    }

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Itinerario_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
}