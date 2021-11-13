<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Encomienda_model extends CI_Model
{

    public $id_encomienda;
    public $id_usuario;
    public $id_municipio;
    public $ciudad_origen;
    public $codigo_postal_origen;
    public $estado;
    public $fecha;
    public $total_encomienda;
    public $total_comision;
    public $total_cliente;


    public function eliminarEncomienda($datos)
    {

        $query = $this->db->get_where('encomienda', array('id_encomienda' => $datos["id_encomienda"]));
        $encomienda = $query->row();

        if (!isset($encomienda)) {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'La encomienda no existe'
            );
            return $respuesta;
        }
        $this->db->set(array('estado' => 'Inactivo'));
        $this->db->where('id_encomienda', $datos["id_encomienda"]);
        $hecho = $this->db->update('encomienda');

        if ($hecho) {
            #borrado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro dado de baja correctamente'
            );
        } else {
            //error

            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    } //fin metodo

    public function altaEncomienda($datos)
    {

        $query = $this->db->get_where('encomienda', array('id_encomienda' => $datos["id_encomienda"]));
        $encomienda = $query->row();

        if (!isset($encomienda)) {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'La encomienda no existe'
            );
            return $respuesta;
        }
        $this->db->set(array('estado' => 'Enviado'));
        $this->db->where('id_encomienda', $datos["id_encomienda"]);
        $hecho = $this->db->update('encomienda');

        if ($hecho) {
            #borrado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro dada de alta correctamente'
            );
        } else {
            //error

            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    } //fin metodo

    public function modificar_encomienda($datos)
    {

        $nombreTabla = "encomienda";
        $this->db->set($datos);
        $this->db->where('id_encomienda', $datos["id_encomienda"]);
        $update = $this->db->update('encomienda');

        if (!$update) {
            //NO GUARDO 
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Encomienda'   => null
            );
            return $respuesta;
        } else {
            //$identificador = $this->db->insert_id();

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'id'           => $datos['id_encomienda'],
                'encomienda'   => $datos
            );
            return $respuesta;
        }






        //************
        $this->db->set($datos);
        $this->db->where('id_encomienda', $datos["id_encomienda"]);

        $hecho = $this->db->update('encomienda');

        if ($hecho) {
            #borrado
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro actualizado correctamente',
                'Formularios' => $datos
            );
        } else {
            //error

            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }

    public function get_encomiendaEnvio($data)
    {
        $this->db->select('*');
        $this->db->from('encomienda');
        $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario', 'inner');
        $this->db->where($data);
        $this->db->where_in(array('estado' => 'Enviado'));
        $query = $this->db->get();
        return $query->result();
    }

    public function get_encomienda(array $data)
    {
        $this->load->model('Conf_model');

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('encomienda');
        $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario');
        $this->db->select('DATE_FORMAT(encomienda.fecha,"%d-%m-%Y") as fecha');
        $this->db->where($parametros);

        $query = $this->db->get();

        //$this->db->select('encomienda.id_encomienda,encomienda.id_usuario, encomienda.ciudad_origen,encomienda.codigo_postal_origen, usuario.nombre, DATE_FORMAT(encomienda.fecha, "%d-%m-%Y") as fecha,encomienda.estado');

        $respuesta = $query->result();

        $this->load->model('Imagen_model');
        foreach ($respuesta as $row) {

            $identificador = $row->id_encomienda;
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('encomienda', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.png";
            } else {
                $row->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('encomienda', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
        // return $query->result();
    }

    public function get_encomiendaModificar(array $data)
    {
        $this->db->select('*');
        $this->db->from('encomienda');
        $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario', 'inner');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->result();
    }

    //************para sacar los datos de destino
    public function get_encomiendaDestino(array $data)
    {
        $this->db->select('*');
        $this->db->from('detalle_destino');
        $this->db->where($data);
        $query = $this->db->get();
        return $query->result();
    }


    public function set_datos($data_cruda)
    {

        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($data_cruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Encomienda_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        return $objeto;
    } //fin de capitalizar los datos segun el modelo y campos correctos de la base

    public function insertarEncomienda($datos)
    {

        $nombreTabla = "encomienda";
        $insert = $this->db->insert($nombreTabla, $datos);

        $id =  $this->db->insert_id();

        if ($insert) {
            #insertado
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("encomienda",  $identificador);
            //EN ESTE CASO NO GUARDARA UNA FOTO SI NO UN PDF
            $this->Imagen_model->guardarImagen("comprobante_encomienda",  $identificador);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'encomienda_id' => $id
            );
        } else {
            //error
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("encomienda", $identificador);
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    } //fin de insertar la pregunta


    //VERIFICAR DATOS
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Encomienda_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    public function get_encomiendaForApp(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $this->db->select('encomienda.id_encomienda,encomienda.id_usuario, encomienda.ciudad_origen,encomienda.codigo_postal_origen, encomienda.fecha as fecha,encomienda.estado,total_cliente,nombre_municipio');
        $this->db->from('encomienda');
        $this->db->join('usuario', 'usuario.id_cliente=encomienda.id_usuario', 'inner');
        $this->db->join('municipio_envio', 'id_municipio');
        $this->db->where($parametros);
        $this->db->order_by('id_encomienda', 'desc');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_detalleDestinoForApp(string $id)
    {
        $this->db->select('*');
        $this->db->from('detalle_destino');
        $this->db->where('id_encomienda', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_municipioEnvio()
    {
        $this->db->select('*');
        $this->db->from('municipio_envio');
        $query = $this->db->get();
        return $query->result();
    }
    public function actualizarCostoEnvio($data)
    {


        $this->db->set($data);
        $this->db->where('id_municipio', $data["id_municipio"]);
        $hecho = $this->db->update('municipio_envio');

        if ($hecho) {
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Registro Actualizado',
                'data'    => $data
            );
        } else {
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'ERROR DE ACTUALIZACION',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }
}