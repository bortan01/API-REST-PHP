<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tours_paquete_model extends CI_Model
{
    public $id_tours;
    public $nombreTours;
    public $fecha_salida;
    public $lugar_salida;
    public $precio;
    public $incluye;
    public $no_incluye;
    public $requisitos;
    public $promociones;
    public $descripcion;
    public $foto;
    public $cupos_disponibles;
    public $nombre_encargado;
    public $estado;
    public $tipo;
    public $aprobado;
    public $urlQrCodeEnlace;
    public $urlEnlace;


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

    public function guardar()
    {
        $nombreTabla = "tours_paquete";
        $this->load->model('Wompi_model');
        ///SUBIMOS LA IMAGEN AL SERVIDOR Y OBTENEMOS SU URL
        // $fotoSubida = $this->Imagen_model->guardarImagen();
        // $this->foto = $fotoSubida["path"];
        $this->foto     = "https://www.adslzone.net/app/uploads-adslzone.net/2019/04/borrar-fondo-imagen-930x487.jpg";
        $urlWebHook     = "https://api.christianmeza.com/index.php/reserva/save";

        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($this->precio, $this->nombreTours, $this->descripcion, $this->foto, $urlWebHook);

        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $this->id_tours        = $respuestaWompi["idEnlace"];
            $this->urlQrCodeEnlace = $respuestaWompi["urlQrCodeEnlace"];
            $this->urlEnlace       = $respuestaWompi["urlEnlace"];

            $insert = $this->db->insert($nombreTabla, $this);
            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'          => TRUE,
                    'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                    'error_number' => $this->db->error_number(),
                    'cliente'      => null
                );
                return $respuesta;
            } else {
                $identificador = $this->db->insert_id();
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Guardado Exitosamente',
                    'turPaquete'   => $this
                );
                return $respuesta;
            }
        }
    }
    public function obtenerViaje(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "tours_paquete";

        try {
            $parametros = $this->Tours_paquete_model->verificar_camposEntrada($data);
            $viajeSEleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);
            ///usuario seleccionado es un array de clases genericas

            if (count($viajeSEleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro el Usuario');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'viaje' => $viajeSEleccionado);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }

    public function editar($data)
    {
        $nombreTabla = "tours_paquete";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Tours_paquete_model->verificar_camposEntrada($data);
        $this->db->where('id_tours', $campos["id_tours"]);

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
        $this->db->where('id_tours', $campos["id_tours"]);
       
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
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
}