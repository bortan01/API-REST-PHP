<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Imagen_model extends CI_Model
{
    public $foto_path;
    public $tipo;
    public $identificador;
    public $activo;

    ///EN EL BODY DEBE DE RECIBIR UN PARAMETRO LLAMADO foto, DE TIPO FILE
    public function guardarImagen()
    {
        $URL = "http://localhost/API-REST-PHP/uploads/";
        $config['upload_path']   = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']      = 2000;
        $config['file_name']     = date("HisYmd") . rand(1, 100) . ".jpg";

        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('foto')) {
            $errorImagen = $this->upload->error_msg;
            $respuesta = array(
                'err'     => TRUE,
                'mensaje' =>  $errorImagen[0],
                'nombre'  => null,
                'path'    => null
            );
            return $respuesta;
        } else {
            $data = $this->upload->data();
            $path =  $URL . $data["file_name"];
            $nombre = $data["file_name"];
            $respuesta = array(
                'err'   => FALSE,
                'mensaje' => "Imagen subida exitosamente",
                'nombre'  => $nombre,
                'path'    => $path,
            );
            return $respuesta;
        }
    }
    //EN EL BODY DEBE DE RECIBIR UN PARAMETRO LLAMADO fotos[], DE TIPO FILE
    public function guardarGaleria($tipo, $identificador, $activo = TRUE)
    {
        $URL = "http://localhost/API-REST-PHP/uploads/";
        if (!isset($_FILES['fotos'])) {
            $informacion_subida[] = array(
                "error"   => TRUE,
                "mensaje" => "No se ha encontrado ningun archivo",
                "nombre"  => null,
                "path"    => null

            );
            return $informacion_subida;
        } else {
            ///NUMERO DE FOTOS
            $numeroFotos = count($_FILES['fotos']['name']);
            $informacion_subida = array();

            for ($i = 0; $i < $numeroFotos; $i++) {
                if (!empty($_FILES['fotos']['name'][$i])) {

                    //CREAMOS LA PROPIEDAD userfile EN DONDE VAMOS A ALMACENAR DE MANERA TEMPORAL UNO POR UNO LAS IMAGENES
                    $_FILES['userfile']['name']     = $_FILES['fotos']['name'][$i];
                    $_FILES['userfile']['type']     = $_FILES['fotos']['type'][$i];
                    $_FILES['userfile']['tmp_name'] = $_FILES['fotos']['tmp_name'][$i];
                    $_FILES['userfile']['error']    = $_FILES['fotos']['error'][$i];
                    $_FILES['userfile']['size']     = $_FILES['fotos']['size'][$i];

                    $config['upload_path']   = './uploads/';
                    $config['allowed_types'] = 'gif|jpg|png|jpeg';
                    $config['max_size']      = 2000;
                    $config['detect_mime']   = TRUE;
                    //GENERAMOS UN NOMBRE UNICO
                    $config['file_name']     = date("HisYmd") . rand(1, 100) . ".jpg";

                    $this->load->library('upload', $config);

                    //EMPEZAMOS A GUARDAR LAS IMAGENES EN LA CARPETA 
                    if ($this->upload->do_upload('userfile')) {
                        //ESTO ES PARA CONSEGUIR LA INFORMACION DE LA SUBIDA
                        $data = $this->upload->data();
                        $nombre = $data["file_name"];
                        $path = $URL . $nombre;

                        //PROCEDEMOS A GUARDARLO EN LA BASE DE DATOS LA IMAGEN YA SUBIDA
                        $this->tipo          = $tipo;
                        $this->identificador = $identificador;
                        $this->foto_path     = $path;
                        $this->activo     = $activo;
                        $this->db->insert('galeria', $this);

                        ///INFORMACION DE FOTOS GUARDADAS

                        $informacion_subida[] = array(
                            "error"   => FALSE,
                            "mensaje" => "Imagen subida exitosamente",
                            "nombre"  => $nombre,
                            "path"    => $path

                        );
                    } else {
                        $errorImagen = $this->upload->error_msg;
                        $informacion_subida[] = array(
                            "error"   => TRUE,
                            "mensaje" => $errorImagen[0],
                            "nombre"  => null,
                            "path"    => null

                        );
                    }
                }
            }
            return $informacion_subida;
        }
    }
    //DEBE DE ENVIARSE LA URL DE LA IMAGEN A ELIMINAR
    public function eliminarImagen($urlImagen)
    {
        $URL = "http://localhost/API-REST-PHP/uploads/";
        $RUTA = "C:/wamp64/www/API-REST-PHP/uploads/";
        $ruta_foto = ($RUTA . substr($urlImagen,  strlen($URL)));


        try {
            if (file_exists($ruta_foto)) {
                unlink($ruta_foto);
            }
        } catch (\Throwable $th) {
            $th->getMessage();
        } catch (Exception $e) {
            $e->getMessage();
        }
    }
    public function eliminarGaleria($tipo, $identificado)
    {
        $this->db->where(array("tipo" => $tipo, "identificador" => $identificado));
        $query = $this->db->get("galeria");
        $imagenes = $query->result();

        $URL = "http://localhost/API-REST-PHP/uploads/";
        $RUTA = "C:/wamp64/www/API-REST-PHP/uploads/";
        foreach ($imagenes as $imagen) {
            $urlImagen = $imagen->foto_path;
            $ruta_foto = ($RUTA . substr($urlImagen,  strlen($URL)));
            try {
                if (file_exists($ruta_foto)) {
                    unlink($ruta_foto);
                }
            } catch (\Throwable $th) {
                $th->getMessage();
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }
    public function obtenerImagenUnica($tipo, $identificado)
    {
        $this->db->where(array("tipo" => $tipo, "identificador" => $identificado, "activo" => TRUE));
        $query = $this->db->get("galeria");
        $imagenes = $query->result();

        $URL = "http://localhost/API-REST-PHP/uploads/";
        $RUTA = "C:/wamp64/www/API-REST-PHP/uploads/";
        foreach ($imagenes as $imagen) {
            $urlImagen = $imagen->foto_path;
            $ruta_foto = ($RUTA . substr($urlImagen,  strlen($URL)));
            try {
                if (file_exists($ruta_foto)) {
                    return $urlImagen;
                }
            } catch (\Throwable $th) {
                $th->getMessage();
            } catch (Exception $e) {
                $e->getMessage();
            }
        }
    }
    
   
}