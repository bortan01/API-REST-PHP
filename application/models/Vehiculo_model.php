<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Vehiculo_model extends CI_Model
{
    public $idvehiculo;
    public $id_rentaCarFK;
    public $idmodelo;
    public $id_transmicionFK;
    public $idcategoria;
    public $placa;
    public $anio;
    public $color;
    public $puertas;
    public $pasajeros;
    public $precio_diario;
    public $descripcion;
    public $detalles;
    public $activo = TRUE;
    public $kilometraje;
    public $tipoCombustible;
    public $opc_avanzadas;


    public function get_vehiculo(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('vehiculo');
        $this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion');
        $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
        $this->db->join('marca_vehiculo', 'modelo.id_marca = marca_vehiculo.id_marca');
        $this->db->join('categoria', 'vehiculo.idcategoria=categoria.idcategoria');
        $this->db->join('usuario', 'vehiculo.id_rentaCarFK = usuario.id_cliente');

        $this->db->where($parametros);
        $this->db->where_in('vehiculo.activo', 1);
        $query = $this->db->get();

        $respuesta = $query->result();
        $this->load->model('Imagen_model');
        foreach ($respuesta as $row) {
            $row->opc_avanzadas =   explode(",", $row->opc_avanzadas);
            $identificador = $row->idvehiculo;
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('vehiculo', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = "http://localhost/API-REST-PHP/uploads/auto.png";
            } else {
                $row->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('vehiculo', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
    }
    public function get_vehiculoForApp(array $data)
    {
        $this->load->model('Imagen_model');
        $this->db->select('*');
        $this->db->from('vehiculo');
        $this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion');
        $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
        $this->db->join('marca_vehiculo', 'modelo.id_marca = marca_vehiculo.id_marca');
        $this->db->join('categoria', 'vehiculo.idcategoria=categoria.idcategoria');
        $this->db->join('usuario', 'vehiculo.id_rentaCarFK=usuario.id_cliente');

        if (isset($data['idCategoria']) && !empty($data['idCategoria'])) {
            $this->db->where('vehiculo.idcategoria', $data['idCategoria']);
        }


        $this->db->where('vehiculo.activo', 1);
        $query = $this->db->get();

        $respuesta = $query->result();
        foreach ($respuesta as $row) {
            $row->opc_avanzadas =   explode(",", $row->opc_avanzadas);
            $identificador = $row->idvehiculo;
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('vehiculo', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = "http://localhost/API-REST-PHP/uploads/auto.png";
            } else {
                $row->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('vehiculo', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->galeria = [];
            } else {
                $row->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
    }
    public function get_opciones()
    {
        $this->db->select('*');
        $this->db->from('servicios_opc');
        return $this->db->get()->result();
    }
    public function get_modelo()
    {
        $this->db->select('*');
        $this->db->from('modelo');
        return $this->db->get()->result();
    }

    public function get_opcionesByClient($id_detalle)
    {
        $this->db->select('*');
        $this->db->from('detalle_serviciosVehiculo');
        $this->db->where('id_detalle', $id_detalle);
        return $this->db->get()->result();
    }
    public function set_datos($data_cruda)
    {

        foreach ($data_cruda as $nombre_campo => $valor_campo) {

            if (property_exists('Vehiculo_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }
        return $this;
    }

    public function insert()
    {

        $query = $this->db->get_where('vehiculo', array('placa' => $this->placa));
        $carrito = $query->row();

        if (isset($carrito)) {

            ///LOGRO GUARDAR LOS DATOS, TRATAREMOS DE GUARDAR LA GALERIA SI MANDARON FOTOS


            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'El vehiculo fue registrado'
            );
            return $respuesta;
        }

        //insertar el registro
        $hecho = $this->db->insert('vehiculo', $this);

        if ($hecho) {
            #insertado
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            $this->Imagen_model->guardarGaleria("vehiculo",  $identificador);
            //EN ESTE CASO NO GUARDARA UNA FOTO SI NO UN PDF
            $this->Imagen_model->guardarImagen("comprobante_vehiculo",  $identificador);
            $respuesta = array(
                'err' => FALSE,
                'mensaje' => 'Registro insertado correctamente',
                'vehiculo_id' => $this->db->insert_id()
            );
        } else {
            //error
            $this->load->model('Imagen_model');
            $identificador = $this->db->insert_id();
            ///ESTO ES PARA GUARDAR UNA IMAGEN INDIVIDUAL Y UNA GALERIA
            $this->Imagen_model->guardarGaleria("vehiculo", $identificador);
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
        $nombreTabla = "vehiculo";

        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Vehiculo_model->verificar_camposEntrada($data);
        $this->db->where('idvehiculo', $campos["idvehiculo"]);

        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'Vehiculo' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'Vehiculo' => null
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
            if (property_exists('Vehiculo_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }
        return $objeto;
    }

    //ELIMINAR
    public function borrar($campos)
    {
        //ELIMINAR UN REGISTRO
        $this->db->where('idvehiculo', $campos["idvehiculo"]);
        $hecho = $this->db->update('vehiculo', $campos);
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
                'vehiculo' => null
            );
            return $respuesta;
        }
    }

    public function getFlota(array $data)
    {
        $parametros = $this->verificar_camposEntrada($data);

        $this->load->model('Imagen_model');
        $this->db->select('*');
        $this->db->from('vehiculo');
        $this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK=transmisionvehiculo.idtransmicion');
        $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
        $this->db->join('marca_vehiculo', 'modelo.id_marca = marca_vehiculo.id_marca');
        $this->db->join('categoria', 'vehiculo.idcategoria=categoria.idcategoria');

        $this->db->where($parametros);
        $this->db->where_in('vehiculo.activo', 1);
        $query = $this->db->get();

        $respuesta = $query->result();
        foreach ($respuesta as $row) {
            $row->opc_avanzadas =   explode(",", $row->opc_avanzadas);
            $identificador = $row->idvehiculo;
            $respuestaFoto =   $this->Imagen_model->obtenerImagen('vehiculo', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $row->foto = [];
            } else {
                $row->foto = $respuestaFoto;
            }
        }
        return $respuesta;
    }

    public function obtenerHistorial(array $parametros)
    {
        $this->load->model('Imagen_model');

        $this->db->select('
                        idvehiculo,
                        usuario.id_cliente,
                        id_detalle,
                        fecha_reserva,
                        resultadoTransaccion,
                        monto,
                        nombre_detalle,
                        direccionRecogida_detalle,
                        direccionDevolucion_detalle,
                        fechaDevolucion,
                        horaDevolucion,
                        totalDevolucion,
                        puertas,
                        pasajeros,
                        precio_diario,
                        descripcion,
                        detalles,
                        anio,
                        modelo,
                        transmision,
                        nombre_categoria,
                        opc_avanzadas,
                        tipoCombustible
                        
        ');
        $this->db->from('vehiculo');
        $this->db->join('detalle_vehiculo', 'detalle_vehiculo.id_vehiculo = vehiculo.idvehiculo');
        $this->db->join('usuario', 'id_cliente');
        $this->db->join('modelo', 'idmodelo');
        $this->db->join('reserva_vehiculo', 'id_detalle');
        $this->db->join('transmisionvehiculo', 'vehiculo.id_transmicionFK = transmisionvehiculo.idtransmicion');
        $this->db->join('categoria', 'idcategoria');
       
        $this->db->where('id_cliente',$parametros['id_cliente']);

        $query     = $this->db->get();
        $respuesta = $query->result();

        foreach ($respuesta as $vehiculo) {
            $vehiculo->opc_avanzadas =   explode(",", $vehiculo->opc_avanzadas);
            $identificador = $vehiculo->idvehiculo;
            $vehiculo->servicios = $this->get_opcionesByClient($vehiculo->id_detalle);
            $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica('vehiculo', $identificador);
            if ($respuestaFoto == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $vehiculo->foto = "http://localhost/API-REST-PHP/uploads/auto.png";
            } else {
                $vehiculo->foto = $respuestaFoto;
            }
            $respuestaGaleria =   $this->Imagen_model->obtenerGaleria('vehiculo', $identificador);
            if ($respuestaGaleria == null) {
                //por si no hay ninguna foto mandamos una por defecto
                $vehiculo->galeria = [];
            } else {
                $vehiculo->galeria = $respuestaGaleria;
            }
        }
        return $respuesta;
    }
}