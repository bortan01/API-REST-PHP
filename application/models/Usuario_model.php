<?php
//this->db->select('title, content, date');
//$this->db->where('name!=', $name);
//$this->db->or_where('id >', $id);
//$query = $this->db->get('mytable');
//$query->result()

//$query = $this->db->get_compiled_select('mytable');
// echo $query ;

defined('BASEPATH') or exit('No direct script access allowed');
class Usuario_model extends CI_Model
{
    public $id_cliente;
    public $nombre;
    public $correo;
    public $nivel;
    public $celular;
    public $uuid;
    public $fbToken;
    public $dui;
    public $activo;
    public $ultimaConexion;

    public function __construct()

    {
        $this->load->model('Firebase_model');
    }
    public function createAccount($data)
    {
        $usuarioFirebase = $this->Firebase_model->crearUsuarioConEmailPassword($data["correo"], $data["password"]);
        if ($usuarioFirebase["err"]) {
            return array("err" => TRUE, 'mensaje' => $usuarioFirebase["mensaje"]);
        } else {
            date_default_timezone_set('America/El_Salvador');
            $nombreTabla              = "usuario";
            $miUser                   = $this->verificar_campos($data);
            $miUser->uuid             = $usuarioFirebase["uid"];
            $miUser->activo           = TRUE;
            $miUser->ultimaConexion   =  date("Y-m-d H:i:s A");
            // CREAMOS UN ID RANDOM
            $idRamdom = date("Hismd");
            echo $idRamdom;
            $miUser->id_cliente       = $idRamdom;


            $insert = $this->db->insert($nombreTabla, $miUser);

            if ($insert) {
                $identificador = $this->db->insert_id();
                //PARA GUARDAR GALERIA (LOS DUI)
                $this->Imagen_model->guardarGaleria("usuario_documentos",  $identificador);
                //PARA GUARDAR LA FOTO DE PERFIL
                $this->Imagen_model->guardarImagen("usuario_perfil",  $identificador);
                return array('err' => FALSE,  'mensaje' => 'Cuenta Creada Exitosamente!');
            } else {
                return array('err' => TRUE, 'mensaje' => $this->db->error_message());
            }
        }
    }

    public function loginUserEjemplo($username, $password)
    {
        $query = $this->db->get_where("users", array("username" => $username), 1);
        $stmt = $query->result();

        if (count($stmt) == 1) {
            $row = $stmt[0];
            if (password_verify($password, $row->password)) {

                $_SESSION['user_uuid'] = $row->uuid;
                $_SESSION['username'] = $row->username;
                $_SESSION['fullname'] = $row->fullname;

                $ar = [];
                $ar['message'] =  'User Logged in Successfully';
                $ar['user_uuid'] = $row->uuid;

                $additionalClaims = ['username' => $row->username, 'email' => $row->email];


                $customToken = $this->Firebase_model->crearToken($ar['user_uuid'], $additionalClaims);

                $ar['token'] = $customToken;

                return array('status' => 200, 'message' => $ar);
            } else {
                return array('status' => 303, 'message' => 'Password does not match');
            }
        } else {
            return array('status' => 303, 'message' => $username . ' does not exists');
        }
    }

    public function getUser(array $data = array())
    {
        $this->load->model('Conf_model');
        try {
            $parametros = $this->Usuario_model->verificar_camposEntrada($data);
            $nombreTabla = "usuario";
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $result = $query->result();

            foreach ($result as $row) {
                $identificador = $row->id_cliente;
                $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica("usuario_perfil", $identificador);
                if ($respuestaFoto == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/avatar.png";
                } else {
                    $row->foto = $respuestaFoto;
                }
            }

            return array('err' => FALSE, 'usuarios' => $result);
        } catch (Exception $e) {
            return array('err' => TRUE, 'mensaje' => $e->getMessage());
        }
    }
    public function getUserByChat()
    {
        try {
            $this->load->model('Conf_model');
            $nombreTabla = "usuario";
            $this->db->where('nivel', 'CLIENTE');
            $this->db->order_by('ultimaConexion', 'DESC');

            $query = $this->db->get($nombreTabla);
            $result = $query->result();

            foreach ($result as $row) {
                $identificador = $row->id_cliente;
                $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica("usuario_perfil", $identificador);
                if ($respuestaFoto == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $row->foto = $this->Conf_model->URL_SERVIDOR . "uploads/avatar.png";
                } else {
                    $row->foto = $respuestaFoto;
                }
            }
            $administrador = $this->getAdminByChat();

            return array('err' => FALSE, 'usuarios' => $result, 'administrador' => $administrador);
        } catch (Exception $e) {
            return array('err' => TRUE, 'mensaje' => $e->getMessage());
        }
    }

    public function getAdminByChat()
    {
        try {
            $this->load->model('Conf_model');
            $nombreTabla = "usuario";
            $this->db->where('nivel', 'ADMINISTRADOR');

            $query = $this->db->get($nombreTabla);
            $result = $query->row();

            if ($result != null) {
                $identificador = $result->id_cliente;
                $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica("usuario_perfil", $identificador);
                if ($respuestaFoto == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $result->foto = $this->Conf_model->URL_SERVIDOR . "uploads/avatar.png";
                } else {
                    $result->foto = $respuestaFoto;
                }
                return  $result;
            }
            return null;
        } catch (Exception $e) {
            return null;
        }
    }


    public function getOneUser(array $data = array())
    {
        try {
            $this->load->model('Conf_model');
            $parametros = $this->Usuario_model->verificar_camposEntrada($data);
            $nombreTabla = "usuario";
            $this->db->where($parametros);
            $query = $this->db->get($nombreTabla);
            $row = $query->row_array();

            $identificador = $row['id_cliente'];

            if (isset($identificador) && $identificador != '') {
                $respuestaFoto =   $this->Imagen_model->obtenerImagenUnica("usuario_perfil", $identificador);
                if ($respuestaFoto == null) {
                    //por si no hay ninguna foto mandamos una por defecto
                    $row["foto"] = $this->Conf_model->URL_SERVIDOR . "uploads/avatar.png";
                } else {
                    $row["foto"] = $respuestaFoto;
                }
            }
            $row["err"] = FALSE;
            return $row;
        } catch (Exception $e) {
            return array('err' => TRUE, 'mensaje' => $e->getMessage());
        }
    }

    public function createChatRecord($user_1_uuid, $user_2_uuid)
    {
        $this->db->select('chat_uuid');
        $where = "(user_1_uuid = '$user_1_uuid' AND user_2_uuid = '$user_2_uuid') OR (user_1_uuid = '$user_2_uuid' AND user_2_uuid = '$user_1_uuid')";
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get('chat_record');
        $result  = $query->row();

        //RETORNAREMOS INFO  CHAT
        $infoChat = [];
        $infoChat['user_1_uuid'] = $user_1_uuid;
        $infoChat['user_2_uuid'] = $user_2_uuid;
        //VERIFICAMOS SI YA EXISTEM CHATS ENTRE LOS DOS USUARIO
        if ($result != null) {
            $infoChat['chat_uuid'] = $result->chat_uuid;
            return $infoChat;
        } else {
            //SI NO EEXISTE CREAMOS LA RELACION
            $chat_uuid = date("HisYmd");
            $data = array(
                'chat_uuid'   => $chat_uuid,
                'user_1_uuid' => $user_1_uuid,
                'user_2_uuid' => $user_2_uuid
            );

            $this->db->insert('chat_record', $data);
            $infoChat['chat_uuid'] = $chat_uuid;
            return $infoChat;
        }
    }
    public function logout()
    {

        if (isset($_SESSION['username'])) {

            unset($_SESSION['username']);
            unset($_SESSION['user_uuid']);
            session_destroy();

            return array('status' => 200, 'message' => 'User Logout Successfully');
        }
        return array('status' => 303, 'message' => 'Logout Fail');
    }
    public function verificar_campos($dataCruda): Usuario_model
    {
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Usuario_model', $nombre_campo)) {
                $this->$nombre_campo = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $this;
    }

    public function verificar_camposEntrada($dataCruda)
    {
        $objeto = array();
        ///par aquitar campos no existentes
        foreach ($dataCruda as $nombre_campo => $valor_campo) {
            # para verificar si la propiedad existe..
            if (property_exists('Usuario_model', $nombre_campo)) {
                $objeto[$nombre_campo] = $valor_campo;
            }
        }

        //este es un objeto tipo cliente model
        return $objeto;
    }
    public function editar($data)
    {
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Usuario_model->verificar_camposEntrada($data);
        $this->db->where('id_cliente', $campos["id_cliente"]);
        $hecho = $this->db->update('usuario', $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'usuario' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'usuario' => null
            );
            return $respuesta;
        }
    }
    public function borrar($campos)
    {
        $nombreTabla  = 'usuario';
        $identificador = $campos["id_cliente"];
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_cliente', $identificador);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            $this->load->model('Imagen_model');
            // $this->Imagen_model->eliminarGaleria($nombreTabla, $identificador);
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente'
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'usuario' => null
            );
            return $respuesta;
        }
    }
    public function restaurar($campos)
    {
        $nombreTabla  = 'usuario';
        $identificador = $campos["id_cliente"];
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $this->db->where('id_cliente', $identificador);
        $hecho = $this->db->update($nombreTabla, $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Eliminado Exitosamente'
            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'usuario' => null
            );
            return $respuesta;
        }
    }
    public function actualizarFechaChat($data)
    {
        ///VAMOS A ACTUALIZAR UN REGISTRO
        $campos = $this->Usuario_model->verificar_camposEntrada($data);
        $this->db->where('uuid', $campos["uuid"]);
        $hecho = $this->db->update('usuario', $campos);
        if ($hecho) {
            ///LOGRO ACTUALIZAR 
            $respuesta = array(
                'err'     => FALSE,
                'mensaje' => 'Registro Actualizado Exitosamente',
                'usuario' => $campos

            );
            return $respuesta;
        } else {
            //NO GUARDO
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'Error al actualizar ', $this->db->error_message(),
                'error_number' => $this->db->error_number(),
                'usuario' => null
            );
            return $respuesta;
        }
    }

    public function get_datosUsuario(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('usuario');
        $this->db->where($parametros);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_cotizacionesRealizadas(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('usuario');
        //$this->db->from('detalle_vehiculo' );
        //$this->db->join('usuario', 'detalle_vehiculo.id_cliente = usuario.id_cliente');
        //$this->db->join('vehiculo', 'detalle_vehiculo.id_vehiculo = vehiculo.idvehiculo');
        //$this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');
        $this->db->join('cotizarvehiculo', 'cotizarvehiculo.id_usuario = usuario.id_cliente');
        $this->db->join('modelo', 'cotizarvehiculo.modelo = modelo.idmodelo');
        $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaRecogida,"%d-%m-%Y") as fechaRecogida');
        $this->db->select('DATE_FORMAT(cotizarvehiculo.fechaDevolucion,"%d-%m-%Y") as fechaDevolucion');
        $this->db->where($parametros);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_encomiendasRealizadas(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);

        $this->db->select('*');
        $this->db->from('encomienda');
        $this->db->join('usuario', 'encomienda.id_usuario = usuario.id_cliente');
        $this->db->join('detalle_encomienda', 'detalle_encomienda.id_encomienda = encomienda.id_encomienda');
        $this->db->join('producto', 'detalle_encomienda.id_producto = producto.id_producto');
        $this->db->join('detalle_destino', 'detalle_destino.id_encomienda = encomienda.id_encomienda');
        $this->db->where($parametros);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_vehiculosAlquilados(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $otra = $data['id_cliente'];
        // $where=explode('=',$parametros);

        $this->db->select('*');
        $this->db->from('detalle_vehiculo');
        $this->db->join('usuario', 'detalle_vehiculo.id_cliente = usuario.id_cliente');
        $this->db->join('vehiculo', 'detalle_vehiculo.id_vehiculo = vehiculo.idvehiculo');
        $this->db->join('modelo', 'vehiculo.idmodelo = modelo.idmodelo');

        $this->db->where('usuario.id_cliente', $otra);

        $query = $this->db->get();

        return $query->result();
    }

    public function get_toursAdquiridos(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $aux = $data['id_cliente'];
        // $where=explode('=',$parametros);

        $this->db->select('*');
        $this->db->from('detalle_tour');
        $this->db->join('usuario', 'detalle_tour.id_cliente = usuario.id_cliente');
        $this->db->join('tours_paquete', 'detalle_tour.id_tours = tours_paquete.id_tours');
        $this->db->where('usuario.id_cliente', $aux);

        //WHERE tours_paquete.tipo="Tour Nacional" || tours_paquete.tipo="Tour Internacional"
        $query = $this->db->get();
        return $query->result();
    }

    public function get_vuelosCotizaciones(array $data)
    {

        $parametros = $this->verificar_camposEntrada($data);
        $aux = $data['id_cliente'];

        $this->db->select('*');
        $this->db->from('cotizacion_vuelo');
        $this->db->join('aerolinea', 'cotizacion_vuelo.idaerolinea = aerolinea.idaerolinea');
        $this->db->join('alianza', 'aerolinea.idalianza = alianza.idalianza');
        $this->db->join('tipo_clase', 'cotizacion_vuelo.idclase = tipo_clase.idclase');
        $this->db->join('tipo_viaje', 'cotizacion_vuelo.idtipo_viaje = tipo_viaje.idtipo_viaje');
        $this->db->join('usuario', 'cotizacion_vuelo.id_cliente = usuario.id_cliente');
        $this->db->select('DATE_FORMAT(cotizacion_vuelo.fechaPartida,"%d-%m-%Y") as fechaPartida');
        $this->db->select('DATE_FORMAT(cotizacion_vuelo.fechaLlegada,"%d-%m-%Y") as fechaLlegada');
        $this->db->where('usuario.id_cliente', $aux);
        $query = $this->db->get();
        return $query->result();
    }
}