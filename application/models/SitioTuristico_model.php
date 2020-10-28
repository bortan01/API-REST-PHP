<?php
defined('BASEPATH') or exit('No direct script access allowed');
class SitioTuristico_model extends CI_Model
{

    public $nombre_sitio;
    public $longitud;
    public $latitud;
    public $descripcion_sitio;
    public $tipo;
    public $informacion_contacto;
    public $id_sitio_turistico;
    public $precio_sitio;
    public $estado;


    public function verificar_campos($dataCruda)
    {
        ///par aquitar campos no existentes 
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('SitioTuristico_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }


        return $this;
    }

    public function guardar(array $data)
    {
        $data["estado"] = TRUE;
        $campos = $this->verificar_camposEntrada($data);
        $nombreTabla = "sitio_turistico";
        $insert = $this->db->insert($nombreTabla, $campos);
        if ($insert) {
            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria($nombreTabla,  $identificador);

            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro Guardado Exitosamente',
                'id' => $identificador,
                'sitio' => $campos
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'cliente' => null
            );
            return $respuesta;
        }
    }
    public function obtenerSitio(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        try {
            $this->db->select('id_sitio_turistico,id_contacto,sitio_turistico.nombre_sitio,precio_sitio,tipo,latitud,longitud,descripcion_sitio, contacto.nombre_contacto as contactoN,telefono,correo ');
            $this->db->from("sitio_turistico");
            $this->db->join('contacto', 'sitio_turistico.informacion_contacto=contacto.id_contacto');
            $this->db->where($parametros);
            // $this->db->where($parametros);
            $query = $this->db->get();
            $sitios  = $query->result();
            if (count($sitios) < 1) {

                $respuesta = array('err' => FALSE, 'sitios' => null, 'mensaje' => "NO SE ENCONTRO NINGUN SITIO TURISTICO");
                return $respuesta;
            } else {
                foreach ($sitios as $fila) {
                    $url = "http://www.lagraderia.com/wp-content/uploads/2018/12/no-imagen.jpg";
                    $this->db->select("foto_path");
                    $this->db->where("identificador", $fila->id_contacto);
                    $this->db->where("tipo", "contacto");
                    $query = $this->db->get("galeria");
                 
                   foreach ($query->result() as $galeria ) {
                       $url = $galeria->foto_path;
                   }
                   $fila->url = $url;
                }
                
                $respuesta = array('err' => FALSE, 'sitios' => $sitios);
                return $respuesta;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('SitioTuristico_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function editar($data)
    {
        $nombreTabla = "sitio_turistico";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->verificar_camposEntrada($data);
        $this->db->where('id_sitio_turistico', $campos["id_sitio_turistico"]);

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
        $nombreTabla      = "sitio_turistico";
        $identificador    = $campos["id_sitio_turistico"];
        $campos["estado"] = FALSE;
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_sitio_turistico', $identificador);
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