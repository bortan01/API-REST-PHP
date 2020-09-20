<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Usuario_model extends CI_Model
{
    public $uuid;
    public $fullname;
    public $username;
    public $password;
    public $email;


    public function __construct()
    {
        $this->load->model('Firebase_model');
    }


    private function getUuid()
    {
        return $this->db->select('SELECT uuid() as uuid');
        // "")->fetch()['uuid'];
    }

    private function isExists($table, $key, $value)
    {

        try {
            $stmt = $this->db->get_where($table, array($key => $value), 1);


            $resultado = $stmt->result();

            if (count($resultado) > 0) {
                return array('status' => 303, 'message' => $value . ' already exists');
            } else {
                return array('status' => 200, 'message' => $value);
            }
        } catch (Exception $e) {
            return array('status' => 405, 'message' => $e->getMessage());
        }
    }

    public function createAccount($fullname, $username, $email, $password)
    {

        if (empty($fullname) || empty($username) || empty($email) || empty($password)) {
            return array('status' => 303, 'message' => 'Empty Fields');
        } else {


            $emailResp = $this->isExists('users', 'email', $email);
            if ($emailResp['status'] != 200) {
                return $emailResp;
            }

            $usernameResp = $this->isExists('users', 'username', $username);
            if ($usernameResp['status'] != 200) {
                return $usernameResp;
            }

            $nombreTabla    = "users";
            $this->uuid     = $this->getUuid();
            $this->fullname = $fullname;
            $this->username = $username;
            $this->password = $password = password_hash($password, PASSWORD_BCRYPT, ['cost' => 8]);
            $this->email    = $email;

            $insert = $this->db->insert($nombreTabla, $this);

            if ($insert) {
                return array('status' => 200, 'message' => 'Account creatded Successfully..!');
            } else {
                return array('status' => 303, 'message' => $this->db->error_message());
            }
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

    public function createChatRecord($user_1_uuid, $user_2_uuid)
    {

        // $chat_uuid_stmt = $this->con->prepare("SELECT chat_uuid FROM chat_record WHERE (user_1_uuid = :user_1_uuid AND user_2_uuid = :user_2_uuid) OR (user_1_uuid = :user_22_uuid AND user_2_uuid = :user_11_uuid) LIMIT 1");

        // $chat_uuid_stmt->bindParam(":user_1_uuid", $user_1_uuid, PDO::PARAM_STR);
        // $chat_uuid_stmt->bindParam(":user_2_uuid", $user_2_uuid, PDO::PARAM_STR);
        // $chat_uuid_stmt->bindParam(":user_22_uuid", $user_2_uuid, PDO::PARAM_STR);
        // $chat_uuid_stmt->bindParam(":user_11_uuid", $user_1_uuid, PDO::PARAM_STR);

        // $chat_uuid_stmt->execute();
        // $ar = [];

        // if (empty($user_1_uuid) || empty($user_2_uuid)) {
        //     return  array('status' => 303, 'message' => 'Invalid details');
        // }

        // $ar['user_1_uuid'] = $user_1_uuid;
        // $ar['user_2_uuid'] = $user_2_uuid;

        // if ($chat_uuid_stmt->rowCount() == 1) {
        //     $ar['chat_uuid'] = $chat_uuid_stmt->fetch(PDO::FETCH_ASSOC)['chat_uuid'];
        //     return array('status' => 200, 'message' => $ar);
        // } else {
        //     $chat_uuid = $this->getUuid();
        //     $begin_chat_stmt = $this->con->prepare("INSERT INTO `chat_record`(`chat_uuid`, `user_1_uuid`, `user_2_uuid`) VALUES (:chat_uuid, :user_1_uuid, :user_2_uuid)");

        //     $begin_chat_stmt->bindParam(':chat_uuid', $chat_uuid, PDO::PARAM_STR);
        //     $begin_chat_stmt->bindParam(':user_2_uuid', $user_1_uuid, PDO::PARAM_STR);
        //     $begin_chat_stmt->bindParam(':user_1_uuid', $user_2_uuid, PDO::PARAM_STR);

        //     $begin_chat_stmt->execute();
        //     $ar['chat_uuid'] = $chat_uuid;

        //     return array('status' => 200, 'message' => $ar);
        // }
    }

    public function getUsers()
    {
        $query =  $this->db->get('users');
        $ar = [];
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $ar[] = $row;
        }

        return array('status' => 200, 'message' => ['users' => $ar]);
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
}