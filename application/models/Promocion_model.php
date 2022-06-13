<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Promocion_model extends CI_Model
{
    public $idpromocion_vuelo;
    public $idaerolineaFK;
    public $idclaseFK;
    public $nombre_promocion;
    public $pais_promocion;
    public $lugarSalida_promocion;
    public $precio_promocion;
    public $fechaDisponible_promocion;
    public $descripcion_promocion;
    public $activo = TRUE;


    public function get_promocion(array $data)
    {

        $this->load->model('Conf_model');
        $this->load->model('Imagen_model');

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('promocion_vuelo');
        $this->db->join('aerolinea', 'promocion_vuelo.idaerolineaFK = aerolinea.idaerolinea');
        $this->db->join('tipo_clase', 'promocion_vuelo.idclaseFK = tipo_clase.idclase');
        $this->db->select('DATE_FORMAT(promocion_vuelo.fechaDisponible_promocion,"%d-%m-%Y") as fechaDisponible_promocion');
        $this->db->where($parametros);
        $this->db->where('fechaDisponible_promocion >=',  date('Y-m-d'));


        $this->db->where('promocion_vuelo.activo', 1);
        $query = $this->db->get();

        $respuesta = $query->result();

        foreach ($respuesta as $row) {

            $identificador = $row->idpromocion_vuelo;
            $respuestaFoto =   $this->Imagen_model->obtenerGaleria('promociones', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/viaje.png";
            } else {
                $row->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('promocion_vuelo', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
    }


    public  function cotizar()
    {
        $aerolineas = $this->Aerolinea_model->get_aerolinea(array());
        $clases = $this->Clases_model->get_clases(array());
		$tiposViejes = $this->Viajes_model->get_viajes(array());
		$condiciones = $this->infoAdicional_model->get_informacion(array());


		return array(
			'aerolineas'  => $aerolineas,
            'clases'      => $clases,
            'tiposViajes' => $tiposViejes,
            'condiciones' => $condiciones
		
		);
    }

    public function set_datos($data_cruda)
    {

        foreach ($data_cruda as $nombre_campo => $valor_campo) {

            if (property_exists('Promocion_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function insert()
    {

        $query = $this->db->get_where('promocion_vuelo', array('idpromocion_vuelo' => $this->idpromocion_vuelo));
        $carrito = $query->row();

        if (isset($carrito)) {

            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Promocion fue registrada'
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
        $hecho = $this->db->insert('promocion_vuelo', $this);

        if ($hecho) {
            #insertado
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("promociones",  $identificador);
            //EN ESTE CASO NO GUARDARA UNA FOTO SI NO UN PDF
            $this->Imagen_model->guardarImagen("comprobante_promociones",  $identificador);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'promo_id' => $this->db->insert_id()
            );
        } else {
            //error
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("promocion_vuelo", $identificador);
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
        $nombreTabla = "promocion_vuelo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Promocion_model->verificar_camposEntrada($data);
        $this->db->where('idpromocion_vuelo', $campos["idpromocion_vuelo"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'promocion' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'promocion' => null
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
            if (property_exists('Promocion_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idpromocion_vuelo', $campos["idpromocion_vuelo"]);
        $hecho = $this->db->update('promocion_vuelo', $campos);
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
                'promocion' => null
            );
            return $respuesta;
        }
    }
    public function getPromoimagen(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('promocion_vuelo');
        $this->db->join('aerolinea', 'promocion_vuelo.idaerolineaFK = aerolinea.idaerolinea');
        $this->db->join('tipo_clase', 'promocion_vuelo.idclaseFK = tipo_clase.idclase');
        $this->db->where($parametros);
        $this->db->where_in('promocion_vuelo.activo', 1);

        $query = $this->db->get();

        $respuesta = $query->result();
        $this->load->model('Imagen_model');
        foreach ($respuesta as $row) {

            $identificador = $row->idpromocion_vuelo;
            $respuestaFoto =   $this->Imagen_model->obtenerImagen('promociones', $identificador);
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
