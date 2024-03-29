<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Servicios_adicionales_model extends CI_Model
{
    public $id_servicios;
    public $id_tipo_servicio;
    public $nombre_servicio;
    public $descripcion_servicio;
    public $costos_defecto;
    public $id_contacto;
    public $activo;
    public $asientos_deshabilitados;
    public $asientos_dispobibles;
    public $filas;
    public $asiento_derecho;
    public $asiento_izquierdo;
    public $fila_trasera;


    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Servicios_adicionales_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }


        return $objeto;
    }

    public function guardar(array $data)
    {
        $nombreTabla = "servicios_adicionales";

        $data["activo"]                 = TRUE;
        $data["asientos_deshabilitados"] = isset($data["asientos_deshabilitados"]) ? $data["asientos_deshabilitados"]  : "";
        $data["asientos_dispobibles"]   = isset($data["asientos_dispobibles"])    ? $data["asientos_dispobibles"]     : "";
        $data["fila_trasera"]           = isset($data["fila_trasera"]) ? $data["fila_trasera"] : null;
        $servicio                       = $this->verificar_camposEntrada($data);
        $insert                         = $this->db->insert($nombreTabla, $servicio);

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
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("servicios_adicionales", $identificador);
            //$this->Imagen_model->guardarImagen("contacto", $identificador);

            $servicio["err"]      = FALSE;
            $servicio["id"]       = $identificador;
            $servicio["mensaje"]  = "Registro Guardado Exitosamente";
            return $servicio;
        }
    }
    public function obtenerServicio(array $data = array())
    {
        $nombreTabla = "servicios_adicionales";
        try {
            $parametros = $this->verificar_camposEntrada($data);
            $parametros["servicios_adicionales.activo"] = TRUE;

            $this->db->select('*');
            $this->db->from($nombreTabla);
            $this->db->join('contacto', 'servicios_adicionales.id_contacto=contacto.id_contacto');
            $this->db->join('tipo_servicio', 'servicios_adicionales.id_tipo_servicio=tipo_servicio.id_tipo_servicio');
            $this->db->where($parametros);
            $query = $this->db->get();
            $servicioSeleccionado  = $query->result();

            if (count($servicioSeleccionado) < 1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'servicio'     => null
                );
                return $respuesta;
            } else {
                foreach ($servicioSeleccionado as $fila) {
                    $url = "http://www.lagraderia.com/wp-content/uploads/2018/12/no-imagen.jpg";
                    $this->db->select("foto_path");
                    $this->db->where("identificador", $fila->id_contacto);
                    $this->db->where("tipo", "contacto");
                    $query = $this->db->get("galeria");

                    foreach ($query->result() as $galeria) {
                        $url = $galeria->foto_path;
                    }
                    $fila->url = $url;
                    if (empty($fila->asientos_deshabilitados)) {
                        $fila->asientos_deshabilitados = [];
                    } else {
                        $fila->asientos_deshabilitados =   explode(",", $fila->asientos_deshabilitados);
                    }
                }
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
    public function editar($data)
    {
        $nombreTabla = "servicios_adicionales";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $data["asientos_deshabilitados"] = isset($data["asientos_deshabilitados"]) ? $data["asientos_deshabilitados"]  : "";
        $data["asientos_dispobibles"]    = isset($data["asientos_dispobibles"])    ? $data["asientos_dispobibles"]     : "";
        
        if (isset ($data["fila_trasera"])) {
            $data["fila_trasera"] = $data["fila_trasera"] == "true";
            
        }

        $campos = $this->verificar_camposEntrada($data);
        $this->db->where('id_servicios', $campos["id_servicios"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'sitio' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'sitio' => null
            );
            return $respuesta;
        }
    }
    public function elimination($campos)
    {
        $nombreTabla      = "servicios_adicionales";
        $identificador    = $campos["id_servicios"];
        $campos["activo"] = FALSE;
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_servicios', $identificador);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            $this->load->model('Imagen_model');
            $this->Imagen_model->eliminarGaleria($nombreTabla, $identificador);
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Elimiinado Exitosamente',
                'sitio' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'sitio' => null
            );
            return $respuesta;
        }
    }
}