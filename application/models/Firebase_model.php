<?php
defined('BASEPATH') or exit('No direct script access allowed');
require  'C:\wamp64\www\API-REST-PHP\vendor\autoload.php';

use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Firebase\Messaging\CloudMessage;

class Firebase_model extends CI_Model
{
    private $firebase;
    function __construct()
    {
        try {
            $serviceAccount = ServiceAccount::fromValue('C:\wamp64\www\API-REST-PHP\push.json');
            $this->firebase = (new Factory)->withServiceAccount($serviceAccount);
        } catch (PDOException $e) {
            echo 'Exception -> ';
            var_dump($e->getMessage());
        }
    }

    public function EnviarNotificacionSDK()
    {
        $data = [
            'UID' => 'ABC',
            'NOMBRE' => 'BORIS',
            'COMIDA' => 'MARUCHAN',
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
        ];

        $messaging = $this->firebase->createMessaging();
        $message = CloudMessage::withTarget("topic", "TODOS_LOS_ANDROID")
            ->withNotification(Notification::create('TITULO', 'CUERPO DEL MENSAJE'))
            ->withData($data);

        $respuesta =  $messaging->send($message);
        return $respuesta;
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

    public function crearToken($uid, $adicional)
    {


        $auth = $this->firebase->createAuth();
        $customToken = $auth->createCustomToken($uid, $adicional);
        $auth->signInWithCustomToken($customToken);


        return (string) $customToken;
    }
}