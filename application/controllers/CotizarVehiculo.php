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
class cotizarVehiculo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('CotizarVehiculo_model');
		$this->load->model('Conf_model');
		$this->load->model('Mail_model');

	}
	public function cotizar_get()
	{
		$data = $this->get();
		$cotizacion = $this->CotizarVehiculo_model->get_cotizar($data);

		if (isset($cotizacion)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'cotizacion' => $cotizacion

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'cotizacion' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}


	//INSERTAR
	public function cotizar_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('cotizar_put')) {

			$cotizacion = $this->CotizarVehiculo_model->set_datos($data);

			$respuesta = $cotizacion->insert();

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				//para formaterar las horas
				$partes     = explode("-", $data["fechaRecogida"]);
				$recogida   = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
				$part       = explode("-", $data["fechaRecogida"]);
				$devolucion = $part[2] . '-' . $part[1] . '-' . $part[0];
				//para mandar el correo a los empleados
				$this->db->select('nombre');
				$this->db->from('usuario');
				$this->db->where('id_cliente',$data['id_usuario']);
				$query = $this->db->get();
			   foreach ($query->result() as $row)
			   {
				$cuerpo="<h2>Cotización de vehículo cliente: ".$row->nombre."</h2><br>
				<h4>Características del vehículo: ".$data['caracteristicas']."</h4><br>
				<h4>Fecha de recogida: ".$recogida."</h4><br>
				<h4>Hora de recogida: ".$data['horaRecogida']."</h4><br>
			    <h4>Dirección de recogida: ".$data['direccion_recogida']."</h4><br><br>
				
				<h4>Fecha de devolución: ".$devolucion."</h4><br>
				<h4>Hora de devolución: ".$data['horaDevolucion']."</h4><br>
				<h4>Dirección de devolución: ".$data['direccion_devolucion']."</h4><br>
			   <h4>Verificar Cotización: ".$this->Conf_model->SISTEMA."</h4>	
			   <br>Atte:<br>Martínez Travel & Tours";
			   }
				
				$this->Mail_model->metEnviar('Cotización de vehículo','Cotización de Cliente',$cuerpo);
			   //fin de para mandar correo a los empleados
			   // COTIZACION REALIZADA POR EL CLIENTE, ENVIAR EL CORREO A USUARIOS TIPO EMPLEADO
				// INFORMACION CONTENIDA AL INTERIOR DE $data
			   //{
			   //		"id_usuario": "2036220712",
			   //		"horaRecogida": "18:25:00",
			   //		"horaDevolucion": "18:6:00",
			   //		"fechaRecogida": "2021-10-21",
			   //		"fechaDevolucion": "2021-10-29",
			   //		"direccion_recogida": "SAN VICENTE",
			   //		"direccion_devolucion": "AGENCIA MARTINEZ",
			   //		"caracteristicas": "QUE TENGA. RUEDAS REDONDAS",
			   //		"anio": "2012",
			   //		"modelo": "1"
			   // }
				$this->response($respuesta);
			}
		} else {

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hay errores en el envio de la informacion',
				'errores' => $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
	//MODIFICAR
	public function actualizarCotizacion_put()
	{

		$data = $this->put();
		if (!isset($data["idcotizarVehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Cotizacion');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->CotizarVehiculo_model->editar($data);
				if ($respuesta['err']) {
					$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
				} else {
					//para mandar el correo
					$this->db->select('id_usuario,caracteristicas');
					$this->db->from('cotizarvehiculo');
					$this->db->where('idcotizarVehiculo',$data['idcotizarVehiculo']);
					$query = $this->db->get();
					foreach ($query->result() as $row)
					{
						$id=$row->id_usuario;
					   $cuerpo="<h2>La cotización de vehículo realizada con características: ".$row->caracteristicas."</h2><br>
					   <h4>Fue procesada con éxito con respuesta: ".$data['respuestaCotizacion']."
					   <h4>Con un total de: $".$data['totalCotizacion']."
					   </h4><br><h4>Gracias por preferirnos, puedes verificar la respuesta a tu cotización nuestra página web: ".$this->Conf_model->PAGINA."
					   </h4><br>También puedes descargar nuestra aplicación móvil<br>Atte:<br>Martínez Travel & Tours";
				
					}
					
					 $this->Mail_model->metEnviarUno('Cotización de vehículo','','Respuesta de Cotización vehículo',$cuerpo,$id);
					 //fin de para mandar correo
					// ENVIAR CORREO DE RESPUESTA A CLIENTE UQE HIZO LA COTIZACION
					// INFORMACION ALA INTERIOR DE $data
					// "idcotizarVehiculo": "1",
					// "descuentosCotizacion": "2",
					// "totalCotizacion": "120",
					// "respuestaCotizacion": "si tenemos carros con 4 ruedas
					$this->response($respuesta, REST_Controller::HTTP_OK);
				}
			} catch (\Throwable $th) {
				$respuesta = array('err' => TRUE, 'mensaje' => 'Error interno de servidor');
			}
		}
	}

	//ELIMINAR
	public function eliminarCotizacion_delete()
	{
		$data = $this->delete();
		if (!isset($data["idcotizarVehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Cotizacion');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idcotizarVehiculo' => $data["idcotizarVehiculo"], 'activo' => FALSE);

			try {
				$respuesta = $this->CotizarVehiculo_model->borrar($campos);
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
}