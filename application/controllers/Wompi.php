<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Wompi extends REST_Controller
{
    public function __construct()
    {
        //llamado del constructor del padre 
        parent::__construct();
        $this->load->database();
        $this->load->library('image_lib');
        $this->load->library('upload');
    }

    public function obtenerToken_get()
    {
        include_once "../../info/credenciales.php";


        $curl = curl_init();


        //Tell cURL where our certificate bundle is located.
        $certificate = "C:\wamp\cacert.pem";
        curl_setopt($curl, CURLOPT_CAINFO, $certificate);
        curl_setopt($curl, CURLOPT_CAPATH, $certificate);

        curl_setopt_array($curl, array(


            CURLOPT_URL => "https://id.wompi.sv/connect/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS =>
            "grant_type=" . $grant_type .
                "&client_id=" . $client_id .
                "&client_secret=" . $client_secret .
                "&audience=.$audience",
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $token = curl_exec($curl);


        curl_close($curl);

        if ($token["error"]) {
            $this->response(json_decode($token), REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response(json_decode($token), REST_Controller::HTTP_OK);
        }
    }
}