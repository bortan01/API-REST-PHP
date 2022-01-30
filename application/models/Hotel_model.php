<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Hotel_model extends CI_Model
{
    public $idhotel;
    public $nombreHotel;
    public $nombrePais;
    public $precioNoche;
    public $descripcionHotel;
    public $incluye;
    public $activo = TRUE;


    public function get_hotel(array $data)
    {

        $this->load->model('Conf_model');
        $this->load->model('Imagen_model');

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('hotel');
        $this->db->where($parametros);
    
        $this->db->where('hotel.activo', 1);
        $query = $this->db->get();

        $respuesta = $query->result();
        
       

        foreach ($respuesta as $row) {
     //       $row->incluye =   explode(",", $row->incluye);

            $identificador = $row->idhotel;
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('hoteles', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.png";
            } else {
                $row->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('hoteles', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
    }


    

    public function set_datos($data_cruda)
    {

        foreach ($data_cruda as $nombre_campo => $valor_campo) {

            if (property_exists('Hotel_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function insert()
    {

        $query = $this->db->get_where('hotel', array('idhotel' => $this->idhotel));
        $hotelito = $query->row();

        if (isset($hotelito)) {

            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Hotel fue registrado'
            );
            return $respuesta;
        }
        //*******CODIGO PARA LOS CORREOS */
       /* $cuerpo="<h2>Promociòn: ".$this->nombre_promocion."</h2><br>
        <h4>Para el pais de: ".$this->pais_promocion." con salida de 
        ".$this->lugarSalida_promocion." con precio de: ".$this->precio_promocion.",
         valido hasta: ".$this->fechaDisponible_promocion."
         </h4><br><h4>Descripcion de Promociòn: ".$this->descripcion_promocion."
         </h4><br>Visita nuestra pagina web: https://tesistours.com/<br>Tambien puedes descargar nuestra aplicación móvil<br>Att:<br>Martìnez T&T";
       
        $this->load->model('Mail_model');
        $this->Mail_model->metEnviar($this->nombre_promocion,'Promociones de vuelos',$cuerpo);
         //FIN DE CODIGO PARA LOS CORREOS
        //insertar el registro*/
        $hecho = $this->db->insert('hotel', $this);

        if ($hecho) {
            #insertado
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("hoteles",  $identificador);
            //EN ESTE CASO NO GUARDARA UNA FOTO SI NO UN PDF
            $this->Imagen_model->guardarImagen("comprobante_hoteles",  $identificador);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'hotelito_id' => $this->db->insert_id()
            );
        } else {
            //error
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("hotel", $identificador);
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al insertar',
                'error' => $this->db->_error_message(),
                'error_num' => $this->db->_error_number()
            );
        }
        return $respuesta;
    }
    //MODIFICAR
    public function editar($data)
    {
        $nombreTabla = "hotel";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Hotel_model->verificar_camposEntrada($data);
        $this->db->where('idhotel', $campos["idhotel"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'hotel' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'hotel' => null
            );
            return $respuesta;
        }
    }

    //VERIFICAR DATOS
    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///quitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Hotel_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idhotel', $campos["idhotel"]);
        $hecho = $this->db->update('hotel', $campos);
        if ($hecho) {
            //ELIMINANDO REGISTRO
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente'
            );
            return $respuesta;
        } else {
            //NO ELIMINO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al eliminar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'hotel' => null
            );
            return $respuesta;
        }
    }
    public function getHotelimagen(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('hotel');
        
        $this->db->where($parametros);
        $this->db->where_in('hotel.activo', 1);

        $query = $this->db->get();

        $respuesta = $query->result();
        $this->load->model('Imagen_model');
        foreach ($respuesta as $row) {

            $identificador = $row->idhotel;
            $respuestaFoto =   $this->Imagen_model->obtenerImagen('hoteles', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = [];
            } else {
                $row->foto = $respuestaFoto;
            }
        }
        return $respuesta;
    }
}