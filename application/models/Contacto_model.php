<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Contacto_model extends CI_Model
{
    public $id_contacto;
    public $nombre_contacto;
    public $telefono;
    public $correo;

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Contacto_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }


        return $objeto;
    }
    public function guardar(array $data)
    {
        $nombreTabla = "contacto";
        $data["activo"] = TRUE;
        $contact = $this->verificar_camposEntrada($data);
        $insert = $this->db->insert($nombreTabla, $contact);
        if (!$insert) {
            //NO GUARDO
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'Error al insertar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'contacto'      => null
            );
            return $respuesta;
        } else {
            //ESTA ES POR SI SE VA A SUBIR LA GALAREIA 
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL
           $this->Imagen_model->guardarImagen("contacto", $identificador);

            $respuesta = array(
                'err'          => FALSE,
                'mensaje'      => 'Registro Guardado Exitosamente',
                'contacto'     =>   $contact,
                'id'           =>$identificador
            );
            return $respuesta;
        }
    }
    public function obtenerContacto(array $data = array())
    { 
        $nombreTabla = "contacto";
        $data["activo"] = TRUE;
        try {
            $parametros = $this->verificar_camposEntrada($data);
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $contactoSeleccionado = $query->result();

            if (count($contactoSeleccionado) < 1) {
                //PROBLEMA
                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'NO HAY RESULTADOS QUE MOSTRAR',
                    'contactos'     => null
                );
                return $respuesta;
            } else {
                foreach ($contactoSeleccionado as $row) {
                    $identificador = $row->id_contacto;
                    $respuestaFoto=   $this->Imagen_model->obtenerImagenUnica("contacto", $identificador);
                    if ($respuestaFoto == null) {
                        //por si no hay ninguna foto mandamos una por defecto
                        $row->foto = "http://localhost/API-REST-PHP/uploads/avatar.png";
                    }else{
                        $row->foto = $respuestaFoto;
                    }
                }

                $respuesta = array(
                    'err'          => FALSE,
                    'contactos'   => $contactoSeleccionado
                );
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'mensaje' => $e->getMessage());
        }
    }
    public function editar($data)
    {
        $nombreTabla = "contacto";
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->verificar_camposEntrada($data);
        $this->db->where('id_contacto', $campos["id_contacto"]);

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
        $nombreTabla      = "contacto";
        $identificador    = $campos["id_contacto"];
        $campos["activo"] = FALSE;
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_contacto', $identificador);
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
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'sitio' => null
            );
            return $respuesta;
        }
    }
}