<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Wompi_model extends CI_Model
{

    public function obtenerToken()
    {
        $this->load->model('Credenciales_model');
        $curl = curl_init();


        //ESTE EES UN CERTIFICADO SSD PARA PERMITIRNOS HACER LA PETICION
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
            "grant_type=" . $this->Credenciales_model->grant_type .
                "&client_id=" . $this->Credenciales_model->client_id .
                "&client_secret=" . $this->Credenciales_model->client_secret .
                "&audience=" . $this->Credenciales_model->audience,
            CURLOPT_HTTPHEADER => array(
                "content-type: application/x-www-form-urlencoded"
            ),
        ));

        $token = curl_exec($curl);


        curl_close($curl);

        if (isset($token["error"])) {
            //NO HAY TOKEN
            return FALSE;
        } else {
            return json_decode($token);
        }
    }
}