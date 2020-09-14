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
            "client_secret"=> $this->Credenciales_model->client_secret,
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

    public function crearEnlacePago($monto, $nombreProducto, $descripcion, $imagen, $webHook)
    {
        $this->load->model('Credenciales_model');
        $curl = curl_init();
        $certificate = "C:\wamp\cacert.pem";
        curl_setopt($curl, CURLOPT_CAINFO, $certificate);
        curl_setopt($curl, CURLOPT_CAPATH, $certificate);
        $ACCESS_TOKEN = $this->obtenerToken()->access_token;

        $headers[] = "authorization:Bearer " . $ACCESS_TOKEN;
        $headers[] = "Content-Type:application/json-patch+json";

        $fieds = '{
            "identificadorEnlaceComercio": "' . $this->Credenciales_model->client_id . '",
            "monto": ' . $monto . ',
            "nombreProducto": "' . $nombreProducto . '",
            "formaPago": {
              "permitirTarjetaCreditoDebido": true,
              "permitirPagoConPuntoAgricola": true
            },
            "infoProducto": {
              "descripcionProducto": "' . $descripcion . '",
              "urlImagenProducto": "' . $imagen . '"
            },
            "configuracion": {
              "esMontoEditable": true,
              "esCantidadEditable": false,
              "cantidadPorDefecto": 1,
              "urlRedirect": "https://www.facebook.com/martineztours99",
              "emailsNotificacion": "fjmiranda009@gmail.com",
              "urlWebhook": "' . $webHook . '",
              "notificarTransaccionCliente": true
            }
          }';
        curl_setopt_array($curl, array(
            CURLOPT_URL            => "https://api.wompi.sv/EnlacePago",
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

    public function crearEnlacePagopPrueba($monto, $nombreProducto, $descripcion, $imagen, $webHook)
    {
        $response = '{
                        "idEnlace":20692,
                        "urlQrCodeEnlace":"https://wompistorage.blob.core.windows.net/imagenes/f7c5e956-5fa4-4cf6-9480-aaaa855b1d7e.jpg",
                        "urlEnlace":"https://lk.wompi.sv/KrLN",
                        "estaProductivo":false
                    }';
        $err = FALSE;

        if ($err) {
            //ERROR DE cURL
            return array('err' => "ERROR DE cURL " . $err);
        } else {
            $decodificada = json_decode($response, true);

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