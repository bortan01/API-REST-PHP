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
        $idTour = $data["id_tours"];
        $respuesta = $this->Utils_model->selectTabla("itinerario", array("id_tours" => $idTour));


        for ($i = 0; $i < count($respuesta); $i++) {
            if (isset($respuesta[$i]->start) && $respuesta[$i]->start != null) {

                $respuesta[$i]->start = DateTime::createFromFormat('Y-m-d h:m:s', $respuesta[$i]->start);
                $respuesta[$i]->start = $respuesta[$i]->start->format('d/m/Y H:m:s');
            }
            if (isset($respuesta[$i]->end) && $respuesta[$i]->end != null) {
                $respuesta[$i]->end = DateTime::createFromFormat('Y-m-d h:m:s', $respuesta[$i]->end);
                $respuesta[$i]->end = $respuesta[$i]->end->format('d/m/Y H:m:s');
            }
        }




        return $respuesta;
    }
}