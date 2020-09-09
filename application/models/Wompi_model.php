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

        $headers[] = "content-type: application/x-www-form-urlencoded";
        $fieds = [
            "grant_type"   => $this->Credenciales_model->grant_type,
            "client_id"    => $this->Credenciales_model->client_id,
            "client_secret" => $this->Credenciales_model->client_secret,
            "audience"     => $this->Credenciales_model->audience,

        ];
        $fields_string = http_build_query($fieds);

        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://id.wompi.sv/connect/token",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_POSTFIELDS     => $fields_string,
            CURLOPT_HTTPHEADER     => $headers,
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

    public function crearEnlacePago()
    {

        $curl = curl_init();
        $certificate = "C:\wamp\cacert.pem";
        curl_setopt($curl, CURLOPT_CAINFO, $certificate);
        curl_setopt($curl, CURLOPT_CAPATH, $certificate);
        //$ACCESS_TOKEN = $this->obtenerToken()->access_token;

        //$headers[] = "authorization:Bearer " . $ACCESS_TOKEN;
        $headers[] = "Content-Type:application/json-patch+json";

        $fieds = '{
            "identificadorEnlaceComercio": "a43e34b1-2dae-478c-852b-9f05865177c9",
            "monto": 1200,
            "nombreProducto": "TESLA 3000",
            "formaPago": {
              "permitirTarjetaCreditoDebido": true,
              "permitirPagoConPuntoAgricola": false
            },
            "infoProducto": {
              "descripcionProducto": "ES UN TESLA",
              "urlImagenProducto": "https://admin.christianmeza.com/img/COSTA.jpg"
            },
            "configuracion": {
              "urlRedirect": "https://pagina.christianmeza.com/",
              "esMontoEditable": false,
              "esCantidadEditable": true,
              "cantidadPorDefecto": 1,
              "emailsNotificacion": "fjmiranda009@gmail.com",
              "urlWebhook": "https://api.christianmeza.com/index.php/Clientes/pago/",
            }
          }';
        // curl_setopt_array($curl, array(
        //     CURLOPT_URL            => "https://api.wompi.sv/EnlacePago",
        //     CURLOPT_RETURNTRANSFER => true,
        //     CURLOPT_ENCODING       => "",
        //     CURLOPT_MAXREDIRS      => 10,
        //     CURLOPT_TIMEOUT        => 30,
        //     CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
        //     CURLOPT_CUSTOMREQUEST  => "POST",
        //     CURLOPT_SSL_VERIFYHOST => false,
        //     CURLOPT_SSL_VERIFYPEER => false,
        //     CURLOPT_POSTFIELDS     => $fieds,
        //     CURLOPT_HTTPHEADER     => $headers,
        // ));

        //$response = curl_exec($curl);
        //$err = curl_error($curl);

        //curl_close($curl);
        $err = false;
        if ($err) {
            //ERROR DE cURL
            return array('err' => "ERROR DE cURL " . $err);
        } else {
          
            $respons = '{"idEnlace":20222,"urlQrCodeEnlace":"https://wompistorage.blob.core.windows.net/imagenes/b4225b2d-0756-4b99-8883-725ea44b40dd.jpg","urlEnlace":"https://lk.wompi.sv/cVmK","estaProductivo":false}';
            $decodificada = json_decode($respons, true);

            if ($decodificada == null) {
                //ERROR INTERNO DE WOMPI
                return array('err' => "ERROR INTERNO DE WOMPI");
             
            } else {
                if (!isset($decodificada["idEnlace"])) {
                    //RESPUEESTA DE ERROR DE WOMPI
                   return array('err' => "ERROR DE PETICION");
                   
                } else {
                    //TENEMOS NUESTRA RESPUETA CORRECTA DE WOMPI
                    return $decodificada;
                    
                }
            }
        }
   
    }
}