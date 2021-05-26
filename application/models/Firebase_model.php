<?php
defined('BASEPATH') or exit('No direct script access allowed');
require('./vendor/autoload.php');

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Exception\AuthException;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\Auth\UserNotFound;
use Kreait\Firebase\Messaging\MulticastSendReport;

class Firebase_model extends CI_Model
{
    private $firebase;
    function __construct()
    {
        try {
            $serviceAccount = ServiceAccount::fromValue('./push.json');
            $this->firebase = (new Factory)->withServiceAccount($serviceAccount);
        } catch (PDOException $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }
    public function crearUsuarioConEmailPassword($email, $password)
    {
        try {
            $auth = $this->firebase->createAuth();
            $user = $auth->createUserWithEmailAndPassword($email, $password);
            $uid = $user->uid;
            // $campos = ['displayName' => 'El Coco loco'];
            // $campos = ['nivel' => 'CLIENTE'];
            // $auth->setCustomUserClaims($uid, ['admin' => true, 'key1' => 'value1']);
            // $auth->updateUser($uid, $campos);

            //PARA ENVIAR CORREO ELECTRONICO DE VERIFICACION
            //$user = $auth->getUser('some-uid');
            //$auth->sendEmailVerificationLink($user);
            return array("err" => FALSE, "uid" => $uid);
        } catch (AuthException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (FirebaseException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        }
    }
    public function obtnerUsuarioUID($uid)
    {
        try {

            $auth = $this->firebase->createAuth();
            $user = $auth->getUser($uid);
            return array("err" => FALSE, "user" => $user);
        } catch (AuthException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (FirebaseException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (UserNotFound $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        }
    }
    public function loginEmailPassword($email, $clearTextPassword)
    {
        try {
            $auth = $this->firebase->createAuth();
            $signInResult = $auth->signInWithEmailAndPassword($email, $clearTextPassword);
           

            $data = $signInResult->data();
            $uid = $data['localId'];
            $customToken =   $auth->createCustomToken($uid);

            $ar = [];
            $ar['err']       = FALSE;
            $ar['message']   = 'LOGIN EXITOSO';
            $ar['user_uuid'] = $uid;
            $ar['token']     = (string)$customToken;
            // $ar['data']     = $data;

            return $ar;
        } catch (AuthException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (FirebaseException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (UserNotFound $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        }
    }
    public function EnviarNotificacionSDK($tokens = array(), $titulo  = "AGENCIA MARTINEZ Y TOURS", $body = "MIRA NUESTROS NUEVOS PRODUCTOS", $url  = "https://pagina.christianmeza.com/img/logo.jpg")
    {
        $data = [
            'ruta' => 'HomeTours',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];
        $messaging = $this->firebase->createMessaging();


        if (count($tokens) == 0) {
            $notification = Notification::create($titulo, $body, $url);
            $message = CloudMessage::withTarget("topic", "TODOS_LOS_USUARIOS")
                ->withNotification($notification)
                ->withData($data);
            $respuesta =  $messaging->send($message);
            return $respuesta;
        } else {
            //DEBEN DE SER MENOS DE 500 POR PETICION

            $notification = ["title" => $titulo, "body" => $body, "image" => $url];
            $message = CloudMessage::fromArray(['data' => $data, 'notification' => $notification]);


            $sendReport = $messaging->sendMulticast($message, $tokens);

            $errores_envio = [];
            if ($sendReport->hasFailures()) {
                foreach ($sendReport->failures()->getItems() as $failure) {
                    $errores_envio[] =  $failure->error()->getMessage() . PHP_EOL;
                }
            }
            $respuesta = array(
                "Envios exitosos" => $sendReport->successes()->count() . PHP_EOL,
                "Envios Fallidos" => $sendReport->failures()->count() . PHP_EOL,
                "Errores de Envio" => $errores_envio
            );
            return $respuesta;
        }
    }
    public function crearToken($uid, $adicional)
    {
        $auth = $this->firebase->createAuth();
        $customToken = $auth->createCustomToken($uid, $adicional);
        $auth->signInWithCustomToken($customToken);
        return (string) $customToken;
    }
    public function EnviarNotificacion()
    {
        $this->load->model('Credenciales_model');
        $curl = curl_init();
        $certificate = "C:\wamp\cacert.pem";
        curl_setopt($curl, CURLOPT_CAINFO, $certificate);
        curl_setopt($curl, CURLOPT_CAPATH, $certificate);


        $headers[] = "authorization:" . $this->Credenciales_model->clave_servidor;
        $headers[] = "Content-Type:application/json";
        //dVPpP65klCc:APA91bE89sOevN-Bxy4jVRXKwwkUrfAONFGL0xFeQA_R86hoem_OZaGQUVwB7LzEIzxE-M93cNksD32kueKyqxq0Hf8tHcHOnPXGqAa000Wj8V5PxCxXJwdq4THs5bFMOF2ERzzcarm0
        ///topics/TODOS_LOS_ANDROID
        $fieds = '{
            "to" : "/topics/TODOS_LOS_ANDROID",
            "notification" :{
                "title" : "Notificacion desde postman",
                "body" : "Body desde postman afad a  sdjfla ",
                "image" : "https://scontent-mia3-1.xx.fbcdn.net/v/t1.0-9/40803958_635079063559459_4250848395503075328_o.jpg?_nc_cat=101&_nc_sid=09cbfe&_nc_ohc=OnmJM_2ubjUAX9sZI3H&_nc_ht=scontent-mia3-1.xx&oh=a2db1c56e8ecfa6fb8d1e7b7f5bcebfc&oe=5F86908C",
                "sound" : "default",
                "subtitle" :"Este es el subtitulo",
                "color" : "#7CFC00"     
            },
            "data":{
                "UID" :"ABC",
                "NOMBRE" :"BORIS",
                "COMIDA" :"DULCES y  CERVEZA",
                "click_action" :"FLUTTER_NOTIFICATION_CLICK"
            }
        }';
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://fcm.googleapis.com/fcm/send",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => $fieds,
            CURLOPT_HTTPHEADER     => $headers,
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        if ($err) {
            //ERROR DE cURL
            return array('err' => "ERROR DE cURL " . $err);
        } else {

            $decodificada = json_decode($response, true);
            return $decodificada;

            // if ($decodificada == null) {
            //     //ERROR INTERNO DE FIREBASE
            //     return array('err' => "ERROR INTERNO DE FIREBASE");
            // } else {
            //     if (!isset($decodificada["multicast_id"])) {
            //         //RESPUEESTA DE ERROR DE FIREBASE
            //         return array('err' => "ERROR DE PETICION");
            //     } else {
            //         //TENEMOS NUESTRA RESPUETA CORRECTA DE FIREBASE
            //         return $decodificada;

            //     }
            // }
        }
    }
    public function cambioPassword($email, $password)
    {
        try {

            $auth = $this->firebase->createAuth();
            $user = $auth->getUserByEmail($email);
            $updatedUser = $auth->changeUserPassword($user->uid, $password);
            return array("err" => FALSE, "user" => $updatedUser);
        } catch (AuthException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        } catch (FirebaseException $e) {
            return array("err" => TRUE, "mensaje" => $e->getMessage());
        }
    }
}