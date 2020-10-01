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
    public $foto;
    public $foto_documento;
    public $activo;


    public function __construct()
    {
        $this->load->model('Firebase_model');
    }
    public function createAccount($correo, $password)
    {
        $usuarioFirebase = $this->Firebase_model->crearUsuarioConEmailPassword($correo, $password);

        if ($usuarioFirebase["err"]) {
            return array("err" => TRUE, 'mensaje' => $usuarioFirebase["mensaje"]);
        }

        $nombreTabla    = "usuario";
        $this->uuid     = $usuarioFirebase["uid"];
        $this->activo   = TRUE;
        $insert = $this->db->insert($nombreTabla, $this);

        if ($insert) {
            return array('err' => FALSE, 'status' => 200, 'mensaje' => 'Cuenta Creada Exitosamente!');
        } else {
            return array('err' => TRUE, 'mensaje' => $this->db->error_message());
        }
    }

    public function loginUser($username, $password)
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

    public function getUser($id)
    {
        $this->load->model("Utils_model");

        try {

            $usuarioSEleccionado = $this->Utils_model->selectTabla("usuario", array('id_cliente' => $id), true);
            ///usuario seleccionado es un array de clases genericas

            if (count($usuarioSEleccionado) != 1) {
                $respuesta = array('err' => TRUE, 'usuario' => 'No se encontro el Usuario');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'usuario' => $usuarioSEleccionado[0]);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'message' => $e->getMessage());
        }
    }

    public function getUserNivel($nivel)
    {
        $this->load->model("Utils_model");

        try {

            $usuarioSEleccionado = $this->Utils_model->selectTabla("usuario", array('nivel' => $nivel, 'activo' => TRUE));
            ///usuario seleccionado es un array de clases genericas

            if (count($usuarioSEleccionado) < 1) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'No hay usuarios');
                return $respuesta;
            } else {
                $respuesta = array('err' => FALSE, 'usuarios' => $usuarioSEleccionado);
                return $respuesta;
            }
        } catch (Exception $e) {
            return array('err' => TRUE, 'status' => 400, 'message' => $e->getMessage());
        }
    }

    public function createChatRecord($user_1_uuid, $user_2_uuid)
    {
        $this->db->select('chat_uuid');
        $where = "(user_1_uuid = '$user_1_uuid' AND user_2_uuid = '$user_2_uuid') OR (user_1_uuid = '$user_2_uuid' AND user_2_uuid = '$user_1_uuid')";
        $this->db->where($where);
        $this->db->limit(1);
        $query = $this->db->get('chat_record');
        $result  = $query->result();

        $ar = [];
        $ar['user_1_uuid'] = $user_1_uuid;
        $ar['user_2_uuid'] = $user_2_uuid;

        if (count($result) == 1) {
            $ar['chat_uuid'] = $result[0]->chat_uuid;
            return array('status' => 200, 'message' => $ar);
        } else {
            $chat_uuid = date("HisYmd");
            $data = array(
                'chat_uuid'   => $chat_uuid,
                'user_1_uuid' => $user_1_uuid,
                'user_2_uuid' => $user_2_uuid
            );

            $this->db->insert('chat_record', $data);

            $ar['chat_uuid'] = $chat_uuid;

            return array('status' => 200, 'message' => $ar);
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
    public function editar()
    {
        $nombreTabla = "usuario";

        try {
            $update = $this->db->update($nombreTabla, $this, "id_cliente");
        

            if (!$update) {
                //NO GUARDO
                $respuesta = array(
                    'err'         => TRUE,
                    'mensaje'     => 'Ningun Registro Fue Modificado',
                    'itinerario'  => null
                );
                return $respuesta;
            } else {

                $respuesta = array(
                    'err'          => FALSE,
                    'mensaje'      => 'Registro Modificado Exitosamente XXXX',
                    'itinerario'  => $this
                );
                return $respuesta;
            }
        } catch (\Throwable $th) {
            $respuesta = array(
                'err'          => TRUE,
                'mensaje'      => 'PROBLEMA INTERNO DE SERVIDOR',

            );
            return $respuesta;
        }
    }
}