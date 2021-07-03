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
    public $cantidad_asientos;
    public $chequeo;

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
    public function guardar($data)
    {
        $camposTur = $this->verificar_camposEntrada($data);
        $nombreTabla = "detalle_tour";
        $insertTur = $this->db->insert($nombreTabla, $camposTur);

        if (!$insertTur) {
            //NO GUARDO
            $respuesta = array(
                'err'             => TRUE,
                'mensaje'         => 'Error al insertar detalle tur', $this->db->error_message(),
                'error_number'    => $this->db->error_number()
            );
            return $respuesta;
        } else {
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
        // $foto        = $this->Imagen_model->obtenerImagenUnica("tours_paquete", $data["id_tours"]);
        $foto        = "https://seeklogo.com/images/R/republica-de-el-salvador-en-la-america-central-logo-E8163F8CF3-seeklogo.com.jpg";

        if (!isset($foto)) {
            $foto = "https://seeklogo.com/images/R/republica-de-el-salvador-en-la-america-central-logo-E8163F8CF3-seeklogo.com.jpg";
        }
        $total       = $data["total"];
        $nombre      = $data["nombre_producto"];
        $descripcion = nl2br($data["descripcionProducto"] . '<br>' .  $data["descripcionTurPaquete"]);
        $respuestaWompi = $this->Wompi_model->crearEnlacePagopPrueba($total, $nombre, $descripcion, $foto, $urlWebHook);
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
        $nombreTabla = "detalle_tour";
        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from($nombreTabla);
        $this->db->where($parametros);

        $query = $this->db->get();
        $respuesta  = $query->result_array();
        return $respuesta;
    }
    public function actualizarChekeo($data)
    {
        $nombreTabla = "detalle_tour";
        $this->db->set('chequeo', $data['chequeo']);
        $this->db->where('id_detalle',  $data['id_detalle']);
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
}