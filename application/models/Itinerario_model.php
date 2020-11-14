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
    public $costo;
    public $por_usuario;
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
    public function guardar(array $sitiosTuristicos, string $id_tours)
    {
        $nombreTabla = "itinerario";

        if (count($sitiosTuristicos) < 1) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => "NO SE INSERTO NINGUN REGISTRO",
                'itinerario'      => null
            );
            return $respuesta;
        } else {

            for ($i = 0; $i < count($sitiosTuristicos); $i++) {
                $sitiosTuristicos[$i]["id_tours"] = $id_tours;
          



            }
            $insert = $this->db->insert_batch($nombreTabla, $sitiosTuristicos);
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
    }
    public function editar(array $data)
    {

        $nombreTabla = "itinerario";
        try {
            if (count($data) < 1) {
                $respuesta = array(
                    'err'         => TRUE,
                    'mensaje'     => 'Ningun Registro Fue Modificado',
                    'itinerario'  => null
                );
            } else {
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

        try {
            $parametros = $this->verificar_camposEntrada($data);

            $this->db->select('*');
            $this->db->from("itinerario");
            $this->db->join('sitio_turistico', 'id_sitio_turistico');
            $this->db->where($parametros);
            $query = $this->db->get();
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

                foreach ($itinerarioSeleccionado as $key => $value) {
                    if ($value->title  == null) {
                        $value->title = $value->nombre_sitio;
                    }
                    $value->id = $value->id_itinerario;
                }
                // $respuesta = array(
                //     'err'          => FALSE,
                //     'itinerario'   => $itinerarioSeleccionado
                // );
                return $itinerarioSeleccionado;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
    public function obtenerNulos(array $data)
    {

        try {
            $parametros = $this->verificar_camposEntrada($data);

            $this->db->select('*');
            $this->db->from("itinerario");
            $this->db->where($parametros);
            $this->db->where("start", null);
            $query = $this->db->get();
            $itinerarioSeleccionado = $query->result();



            return $itinerarioSeleccionado;
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }


    public function obtenerCalendario(array $data)
    {
        try {
            $start = DateTime::createFromFormat("Y-m-d\TH:i:sO", $data["start"]);
            $end   = DateTime::createFromFormat("Y-m-d\TH:i:sO", $data["end"]);

            $this->db->select('*');
            $this->db->from("itinerario");
            $this->db->where("id_tours", $data["id_tours"]);
            $this->db->where('start >=', $start->format('Y-m-d H:i:s'));
            $this->db->where('end <=', $end->format('Y-m-d H:i:s'));
            $query = $this->db->get();
            $itinerarioSeleccionado = $query->result();

            foreach ($itinerarioSeleccionado as $key => $value) {
                $value->id = $value->id_itinerario;
            }
            return $itinerarioSeleccionado;
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
    public function borrar($campos)
    {
        $nombreTabla = "itinerario";
        $this->db->where('id_itinerario', $campos["id_itinerario"]);
        $hecho = $this->db->delete($nombreTabla);

        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente',
                'id'      => $campos["id_itinerario"]
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