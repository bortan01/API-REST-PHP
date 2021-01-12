<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Detalle_vehiculo_model extends CI_Model
{
    public $id_detalle;
    public $id_vehiculo;
    public $id_cliente;
    public $iddireccionesReserva;
    public $total;
    public $urlQrCodeEnlace;
    public $urlEnlace;
    public $nombre;


    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Detalle_vehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }
    public function guardar($data)
    {
        $camposVehiculo = $this->verificar_camposEntrada($data);
        $nombreTabla = "detalle_vehiculo";

        $insertTur = $this->db->insert($nombreTabla, $camposVehiculo);
        if (!$insertTur) {
            //NO GUARDO
            $respuesta = array(
                'err'             => TRUE,
                'mensaje'         => 'Error al insertar detalle tur', $this->db->error_message(),
                'error_number'    => $this->db->error_number()
            );
            return $respuesta;
        } else {
            //GUARDADO
            $respuesta = array(
                'err'             => FALSE,
                'mensaje'         => 'Registro Guardado Exitosamente',
                'detalleTur'      => $data
            );
            return $respuesta;
        }
    }
    public function guardarByCliente($data)
    {
        $this->load->model('Wompi_model');
        $this->load->model('Imagen_model');
        $urlWebHook  = "https://api.christianmeza.com/ReservaVehiculo/save";
        $foto        = $this->Imagen_model->obtenerImagenUnica("vehiculo", $data["id_vehiculo"]);

        if (!isset($foto)) {
            $foto = "https://www.pagina.christianmeza.com/img/logo.jpg";
        }
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($data["total"], $data["nombre"], $data["descripcion"], $foto, $urlWebHook);
        if (!isset($respuestaWompi["idEnlace"])) {
            //HAY ERROR DE WOMPI
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' => $respuestaWompi["err"],
            );
            return $respuesta;
        } else {
            //RECUPERAMOS LA INFORMACION DE WOMPI Y TRATAMOS DE GUARDAR EN LA BD
            $data["id_detalle"]        = $respuestaWompi["idEnlace"];
            $data["urlQrCodeEnlace"]   = $respuestaWompi["urlQrCodeEnlace"];
            $data["urlEnlace"]         = $respuestaWompi["urlEnlace"];
            $guardado =  $this->guardar($data);

            if ($guardado["err"]) {
                return $guardado;
            } else {
                $respuesta = array(
                    'err'             => FALSE,
                    'idEnlace' =>   $respuestaWompi["idEnlace"],
                    'urlQrCodeEnlace' => $respuestaWompi["urlQrCodeEnlace"],
                    'urlEnlace' => $respuestaWompi["urlEnlace"]
                );
                return $respuesta;
            }
        }
    }
    public function obtenerDetalle(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "detalle_vehiculo";

        try {
            $parametros = $this->verificar_camposEntrada($data);
            $deetalleSeleccionado = $this->Utils_model->selectTabla($nombreTabla, $parametros);
            ///usuario seleccionado es un array de clases genericas

            if (count($deetalleSeleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro el detalle vehiculo');
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