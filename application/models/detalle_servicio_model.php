<?php
defined('BASEPATH') or exit('No direct script access allowed');
class detalle_servicio_model extends CI_Model
{

    public $id_tours;
    public $id_servicios;
    public $costo;
    public $por_usuario;
    public $nuemo_veces;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('detalle_servicio_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    public function guardar(array $servicios, string $id_tours)
    {
        $nombreTabla = "detalle_servicio";
        for ($i = 0; $i < count($servicios); $i++) {
            $servicios[$i]["id_tours"] = $id_tours;
        }
        // print_r($servicios);
        // die();
        //$servicio = $this->verificar_camposEntrada($data);
        $insert = $this->db->insert_batch($nombreTabla, $servicios);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'serrvicio'      => null
            );
            return $respuesta;
        } else {
            //ESTA ES POR SI SE VA A SUBIR LA GALAREIA 
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("tours_paquete", $identificador);

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                //'servicio'   => $servicio
            );
            return $respuesta;
        }
    }

    public function obtenerServicio(array $data = array())
    {
        $nombreTabla = "detalle_servicio";
        try {
            $parametros = $this->verificar_camposEntrada($data);
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $servicioSeleccionado = $query->result();

            if (count($servicioSeleccionado) < 1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'servicio'     => null
                );
                return $respuesta;
            } else {

                $respuesta = array(
                    'err'          => FALSE,
                    'servicio'   => $servicioSeleccionado
                );
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
}