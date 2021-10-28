<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
    "https://admin.tesistours.com",
    "https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class TurPaquete extends REST_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Imagen_model');
        $this->load->model('Tours_paquete_model');
        $this->load->model('detalle_servicio_model');
        $this->load->model('Detalle_tour_model');
        $this->load->model('ReservaTour_model');
        $this->load->model('Itinerario_model');
        $this->load->model('Conf_model');
        $this->load->model('Mail_model');
    }
    public function save_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarTurPaquete')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $turPaquete = $this->Tours_paquete_model->verificar_camposEntrada($data);
            $respuesta =  $this->Tours_paquete_model->guardar($turPaquete);

            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //SE GURDO EL TUR POR LO QUE YA TENEMOS EL ID PARA EL DETALLE
                if (!empty($data["servicios"])) {
                    $servicios = json_decode($data["servicios"], true);
                    $this->detalle_servicio_model->guardar($servicios, $respuesta['id']);
                }
                if (!empty($data["sitios"])) {
                    $itinerario = json_decode($data["sitios"], true);
                    $this->Itinerario_model->guardar($itinerario, $respuesta['id']);
                }

                // COLOCAR ACA ABAJO EL MENSAJE DE TOUR O PAQUETE PUBLICADO 
                // PUEEDE SER UN TOUR O UN PAQUETE 
                // CONTENIDO DE $respuesta
                // "err": false,
                // "mensaje": "Registro Guardado Exitosamente",
                // "id": 13,
                // "turPaquete": {
                //     "nombreTours": "Vamos a canada Internacional",
                //     "lugar_salida": "Parque cañas",
                //     "precio": "4321000",
                //     "incluye": "todo lo que quieras",
                //     "no_incluye": "cosas sin valor",
                //     "requisitos": "pasaporte",
                //     "promociones": "cocos gratis",
                //     "descripcion_tur": "un viaje espectacular",
                //     "cupos_disponibles": "100",
                //     "nombre_encargado": "Xabi Alosnso",
                //     "estado": "1",
                //     "tipo": "Tour Nacional",
                //     "aprobado": "1",
                //     "start": "2020-11-10",
                //     "end": "2020-11-12"
                // }
                
                
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function savePrivado_post()
    {
        $data = $this->post();
        $id_cliente = $data['id_cliente'];
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        //corremos las reglas de validacion
        if ($this->form_validation->run('insertarTurPaquete')) {
            //VERIFICAMOS QUE TODOS LOS PARAMETROS ESTEN BIEN
            $turPaquete = $this->Tours_paquete_model->verificar_camposEntrada($data);
            $respuesta =  $this->Tours_paquete_model->guardarTourPrivado($turPaquete, $id_cliente);

            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //SE GURDO EL TUR POR LO QUE YA TENEMOS EL ID PARA EL DETALLE
                if (!empty($data["servicios"])) {
                    $servicios = json_decode($data["servicios"], true);
                    $this->detalle_servicio_model->guardar($servicios, $respuesta['id']);
                }
                if (!empty($data["sitios"])) {
                    $itinerario = json_decode($data["sitios"], true);
                    $this->Itinerario_model->guardar($itinerario, $respuesta['id']);
                }

                
            //para mandar el correo
             $cuerpo="<h2>Cotización de paquete: ".$data['nombreTours']."</h2><br>
             <h4>Tipo de paquete: ".$data['tipo']." fue procesada con éxito con un precio de: $".$data['precio'].".</h4><br>
              <h4>Descripción del paquete: ".$data['descripcion_tur']."</h4><br>
             <h4>Gracias por preferirnos, visita nuestra página web: ".$this->Conf_model->PAGINA."
            </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";

             $this->Mail_model->metEnviarUno('Cotización de paquete','','Cotización paquete privado',$cuerpo,$data['id_cliente']);
             //fin de para mandar correo


                // ENVIAR CORREO DE CONFIRMACION SOLO AL CLIENTE QUE HIZO LA RESERVA
                // CONTENIDO DE $respuesta
                // {
                //     "err": false,
                //     "mensaje": "Registro Guardado Exitosamente",
                //     "id": 16,
                //     "turPaquete": {
                //         "nombreTours": "vamos a timbutu",
                //         "precio": "1",
                //         "descripcion_tur": "asdjflkajsdflksajdlfa",
                //         "no_incluye": "[\"otros no especificados\"]",
                //         "requisitos": "[\"dui vigente\"]",
                //         "incluye": "[\"entradas\"]",
                //         "lugar_salida": "[\"San Vicente\"]",
                //         "promociones": "[]",
                //         "cupos_disponibles": "1",
                //         "tipo": "Privado",
                //         "start": "2020-11-10",
                //         "end": "2020-11-12",
                //         "estado": "1",
                //         "aprobado": "1"
                //     },
                //     "data": {
                //         "id_cliente": "2058460712",
                //         "id_tours": 16,
                //         "nombre_producto": "vamos a timbutu",
                //         "total": 1,
                //         "cantidad_asientos": "1",
                //         "chequeo": "[{\"estado\":false,\"requisito\":\"dui vigente\"}]",
                //         "asientos_seleccionados": "NO_SELECCIONADO",
                //         "label_asiento": "NO_LABEL",
                //         "descripcionProducto": "Reserva Completa"
                //     },
                //     "id_cliente": "2058460712"
                // }


                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function update_post()
    {
        $data = $this->post();
        $this->load->library("form_validation");
        $this->form_validation->set_data($data);
        if ($this->form_validation->run('editarTurPaquete')) {
            $respuesta = $this->Tours_paquete_model->editar($data);
            if ($respuesta['err']) {
                $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            } else {
                //SE GURDO EL TUR POR LO QUE YA TENEMOS EL ID PARA EL DETALLE
                if (!empty($data["servicios"])) {

                    $servicios = json_decode($data["servicios"], true);
                    $this->detalle_servicio_model->editar($servicios, $respuesta["viaje"]["id_tours"]);
                }
                if (!empty($data["sitios"])) {
                    $itinerario = json_decode($data["sitios"], true);
                    $this->Itinerario_model->editar($itinerario, $respuesta["viaje"]["id_tours"]);
                }
                $this->response($respuesta, REST_Controller::HTTP_OK);
            }
        } else {
            //algo mal
            $respuesta = array(
                'err' => TRUE,
                'mensaje' => 'har errores en el envio de informacion',
                'errores' => $this->form_validation->get_errores_arreglo()
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        }
    }
    public function show_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerViaje($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function showApp_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerViajeApp($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function showEdit_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerViajeEdit($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function elimination_delete()
    {
        $data = $this->delete();
        if (!isset($data["id_tours"])) {
            $respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro nungun identificador de paquete');
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

            $campos = array('id_tours' => $data["id_tours"], 'estado' => '0');

            try {
                $respuesta = $this->Tours_paquete_model->borrar($campos);
                if ($respuesta['err']) {
                    $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
                } else {
                    $this->response($respuesta, REST_Controller::HTTP_OK);
                }
            } catch (\Throwable $th) {
                $respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
            }
        }
    }
    public function test_post()
    {
        $this->load->model('Imagen_model');
        //COMO SUBIR UNA FOTO ENVIAR PARAMETRO foto tipo file
        //SOLO ACEPTA ARCHIVOS TIPO IMAGEN NO MAYORES A 2MB
        //$imagen = $this->Imagen_model->guardarImagen();
        //$this->response($imagen, REST_Controller::HTTP_OK);

        //PARA SUBIR MUCHAS FOTOS Y GUARDARLAS EN LA TABLA GALERIA
        //PRIMER PARAMETRO   => NOMBRE DE TABLA
        //SEGUNDO PARAMETRO  => ID FORANEO
        // $imagenes = $this->Imagen_model->guardarGaleria("tours_paquete", 10);
        // $this->response($imagenes, REST_Controller::HTTP_OK);


    }
    public function showReserva_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->informacionViaje($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function showAdicional_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerInfoAdicional($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function showInfoReserva_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerInfoReserva($data);

        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function cotizacion_post()
    {
        $data = $this->post();
        $respuesta =  $this->Tours_paquete_model->guardarCotizacion($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {

             //para mandar el correo
            $this->db->select('nombre');
		    $this->db->from('usuario');
		    $this->db->where('id_cliente',$data['id_cliente']);
		    $query = $this->db->get();
            foreach ($query->result() as $row)
            {
             $cuerpo="<h2>Cotización de paquete: ".$data['peticion']."</h2><br>
            <h4>Se realizó una cotización de un paquete del cliente: ".$row->nombre.", 
            pendiente de respuesta
            </h4><br>Fecha de petición: ".$data['fechaPeticion']."<br>
			<h4>Verificar Cotización: ".$this->Conf_model->SISTEMA."</h4>	
            <br>Atte:<br>Martínez Travel & Tours";
            }
             
             $this->Mail_model->metEnviar('Cotización de paquete','Cotización de Cliente',$cuerpo);
             //fin de para mandar correo
            // COTIZACION REALIZADA POR UN CLIENTE , ENVIAR CORREO A USUARIOS TIPO EMPLEADO
            // INFORMACOPM AL INTERIOR DE $data
            
            // "id_cliente": "2023590712",
            // "peticion": "un viaje a cancun con hotel incluido",
            // "fechaPeticion": "2021-05-12",
            // "visto": "0"
         
            
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function cotizacion_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerCotizaciones($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
    public function cotizacion_put()
    {
        $data = $this->put();
        $respuesta =  $this->Tours_paquete_model->responderCotizacion($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
             //para mandar el correo
             $this->db->select('id_cliente,peticion');
             $this->db->from('cotizar_tourpaquete');
             $this->db->where('idCotizar',$data['idCotizar']);
             $query = $this->db->get();
             foreach ($query->result() as $row)
             {
                 $id=$row->id_cliente;
                $cuerpo="<h2>La cotización de paquete realizada: ".$row->peticion."</h2><br>
                <h4>Fue procesada con éxito con respuesta :".$data['respuesta']."
                </h4><br><h4>Gracias por preferirnos, puedes verificar la respuesta a tu cotización nuestra página web: ".$this->Conf_model->PAGINA."
                </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";
         
             }
            
              $this->Mail_model->metEnviarUno('Cotización de paquete','','Respuesta de Cotización paquete',$cuerpo,$id);
              //fin de para mandar correo
            // enviar correo a cliente que hizo la cotizacion
            // informacion contenida en $data
            //{
            //    "idCotizar": "3",
            //    "respuesta": "si tenemos su tour yeah!!!",
            //    "visto": "1"
            //}
            
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function cotizacionByClient_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerRespuestas($data);
        if ($respuesta['err']) {
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
        } else {
            $this->response($respuesta, REST_Controller::HTTP_OK);
        }
    }
    public function analitica_get()
    {
        $data = $this->get();
        $respuesta =  $this->Tours_paquete_model->obtenerAnalitica($data);
        $this->response($respuesta, REST_Controller::HTTP_OK);
    }
}