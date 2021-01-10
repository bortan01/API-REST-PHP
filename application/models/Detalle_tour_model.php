<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Detalle_tour_model extends CI_Model
{
    public $id_detalle;
    public $id_tours;
    public $id_cliente;
    public $asientos_seleccionados;
    public $label_asiento;
    public $nombre_producto;
    public $total;
    public $urlQrCodeEnlace;
    public $urlEnlace;
    public $descripcionProducto;


    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Detalle_tour_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function guardarByCliente($detalleTur)
    {
        $this->load->model('Wompi_model');
        $this->load->model('Imagen_model');
        $nombreTabla = "detalle_tour";
        $urlWebHook  = "https://api.christianmeza.com/ReservaTour/save";
        $foto        = $this->Imagen_model->obtenerImagenUnica("vehiculo", $detalleTur["id_tours"]);

        if (!isset($foto)) {
            $foto = "https://www.pagina.christianmeza.com/img/logo.jpg";
        }
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($detalleTur["total"], $detalleTur["nombre"], $detalleTur["descripcion"], $foto, $urlWebHook);

        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $detalleTur["id_detalle"]        = $respuestaWompi["idEnlace"];
            $detalleTur["urlQrCodeEnlace"]   = $respuestaWompi["urlQrCodeEnlace"];
            $detalleTur["urlEnlace"]         = $respuestaWompi["urlEnlace"];

            $insert = $this->db->insert($nombreTabla, $detalleTur);
            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'             => TRUE,
                    'mensaje'         => 'Error al insertar ', $this->db->error_message(),
                    'error_number'    => $this->db->error_number(),
                    'detalleVehiculo' => null
                );
                return $respuesta;
            } else {
                $identificador = $this->db->insert_id();
                $respuesta = array(
                    'err'             => FALSE,
                    'mensaje'         => 'Registro Guardado Exitosamente',
                    'detalleVehiculo' => $detalleTur
                );
                return $respuesta;
            }
        }
    }
    public function guardarByAgencia($data)
    {
        $campos = $this->verificar_camposEntrada($data);
        $campos['']
        $nombreTabla = "detalle_tour";
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($detalleTur["total"], $detalleTur["nombre"], $detalleTur["descripcion"], $foto, $urlWebHook);

        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $detalleTur["id_detalle"]        = $respuestaWompi["idEnlace"];
            $detalleTur["urlQrCodeEnlace"]   = $respuestaWompi["urlQrCodeEnlace"];
            $detalleTur["urlEnlace"]         = $respuestaWompi["urlEnlace"];

            $insert = $this->db->insert($nombreTabla, $detalleTur);
            if (!$insert) {
                //NO GUARDO
                $respuesta = array(
                    'err'             => TRUE,
                    'mensaje'         => 'Error al insertar ', $this->db->error_message(),
                    'error_number'    => $this->db->error_number(),
                    'detalleVehiculo' => null
                );
                return $respuesta;
            } else {
                $identificador = $this->db->insert_id();
                $respuesta = array(
                    'err'             => FALSE,
                    'mensaje'         => 'Registro Guardado Exitosamente',
                    'detalleVehiculo' => $detalleTur
                );
                return $respuesta;
            }
        }
    }
    public function obtenerDetalle(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "detalle_tour";

        try {
            $parametros = $this->Detalle_vehiculo_model->verificar_camposEntrada($data);
            $deetalleSeleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);
            ///usuario seleccionado es un array de clases genericas

            if (count($deetalleSeleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro el Usuario');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'detalleVehiculo' => $deetalleSeleccionado);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
}