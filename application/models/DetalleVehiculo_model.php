<?php
defined('BASEPATH') or exit('No direct script access allowed');
class DetalleVehiculo_model extends CI_Model
{
    public $iddetalleVehiculo;
    public $idvehiculo;
    public $total;
    public $urlQrCodeEnlace;
    public $urlEnlace;
    public $descripcion;
    public $nombre;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('DetalleVehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function guardar($detalleVehiculo)
    {
        $this->load->model('Wompi_model');
        $this->load->model('Imagen_model');
        $nombreTabla = "detalleVehiculo";
        $urlWebHook  = "https://api.christianmeza.com/index.php/reserva/save";
        $foto        = $this->Imagen_model->obtenerImagenUnica("vehiculo", $detalleVehiculo["idvehiculo"]);
      
        if (!isset($foto)) {
            $foto = "https://www.pagina.christianmeza.com/img/logo.jpg";
        } 
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($detalleVehiculo["total"],$detalleVehiculo["nombre"], $detalleVehiculo["descripcion"], $foto, $urlWebHook);

        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $detalleVehiculo["iddetalleVehiculo"]  = $respuestaWompi["idEnlace"];
            $detalleVehiculo["urlQrCodeEnlace"]   = $respuestaWompi["urlQrCodeEnlace"];
            $detalleVehiculo["urlEnlace"]         = $respuestaWompi["urlEnlace"];

            $insert = $this->db->insert($nombreTabla, $detalleVehiculo);
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
                    'detalleVehiculo' => $detalleVehiculo
                );
                return $respuesta;
            }
        }

    }
    public function obtenerDetalle(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "detalleVehiculo";

        try {
            $parametros = $this->DetalleVehiculo_model->verificar_camposEntrada($data);
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