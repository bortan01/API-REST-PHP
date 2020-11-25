<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Tours_paquete_model extends CI_Model
{
    public $id_tours;
    public $nombreTours;
    public $start;
    public $end;
    public $lugar_salida;
    public $precio;
    public $incluye;
    public $no_incluye;
    public $requisitos;
    public $promociones;
    public $descripcion_tur;
    public $cupos_disponibles;
    public $nombre_encargado;
    public $estado;
    public $aprobado;
    public $tipo;


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
    public function guardar(array $turPaquete)
    {
        // print_r($turPaquete);
        // die();
        $nombreTabla = "tours_paquete";
        $turPaquete["incluye"] = str_replace(",", "_", $turPaquete["incluye"]);
        $turPaquete["no_incluye"] = str_replace(",", "_", $turPaquete["no_incluye"]);
        $turPaquete["requisitos"] = str_replace(",", "_", $turPaquete["requisitos"]);
        $turPaquete["lugar_salida"] = str_replace(",", "_", $turPaquete["lugar_salida"]);


        print_r($turPaquete);
        die();
        $insert = $this->db->insert($nombreTabla, $turPaquete);
        if (!$insert) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'turPaquete'   => null
            );
            return $respuesta;
        } else {
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $foto = $this->Imagen_model->guardarGaleria("tours_paquete", $identificador);

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'id'           => $identificador,
                'turPaquete'   => $turPaquete
            );
            return $respuesta;
        }
    }
    public function obtenerViaje(array $data = array())
    {
        $this->load->model("Utils_model");
        $nombreTabla = "tours_paquete";
        $parametros = $this->verificar_camposEntrada($data);


        $this->db->select('*');
        $this->db->from($nombreTabla);
        // $this->db->join('contacto', 'sitio_turistico.informacion_contacto=contacto.id_contacto');
        // $this->db->join('tipo_sitio', 'sitio_turistico.id_tipo_sitio=tipo_sitio.id_tipo_sitio');
        $this->db->where($parametros);

        $query = $this->db->get();
        $respuesta  = $query->result();

        foreach ($respuesta as $tur) {
            ///CON LA FUNCIOIN EXPLOTE CREAMOS UN UN ARRAY A PARTIR DE UN STRING, EN ESTE CASO
            //CADA ELEMENTO LLEGA HASTA DONDE APARECE UNA COMA
            $tur->incluye      = explode("_", $tur->incluye);
            $tur->no_incluye   = explode("_", $tur->no_incluye);
            $tur->requisitos   = explode("_", $tur->requisitos);
            $tur->lugar_salida = explode("_", $tur->lugar_salida);
            $tur->promociones  = json_decode($tur->promociones, true);
        }

        // foreach ($sitios as $fila) {
        //     $url = "http://www.lagraderia.com/wp-content/uploads/2018/12/no-imagen.jpg";
        //     $this->db->select("foto_path");
        //     $this->db->where("identificador", $fila->id_contacto);
        //     $this->db->where("tipo", "contacto");
        //     $query = $this->db->get("galeria");

        //     foreach ($query->result() as $galeria) {
        //         $url = $galeria->foto_path;
        //     }
        //     $fila->url = $url;
        // }


        return $respuesta;
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