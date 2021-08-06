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
class vehiculo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Vehiculo_model');
	}
	public function vehiculos_get()
	{
		$data = $this->get();
		$carro = $this->Vehiculo_model->get_vehiculo($data);

		if (isset($carro)) {
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'autos' => $carro

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	//INSERTAR
	public function vehiculo_post()
	{
		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('autos_put')) {

			$carro = $this->Vehiculo_model->set_datos($data);

			$respuesta = $carro->insert();

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
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
	public function actualizarVehiculo_put()
	{

		$data = $this->put();
		if (!isset($data["idvehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Vehiculo');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->Vehiculo_model->editar($data);
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

	//ELIMINAR
	public function eliminarVehiculo_delete()
	{
		$data = $this->delete();
		if (!isset($data["idvehiculo"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Vehiculo');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idvehiculo' => $data["idvehiculo"], 'activo' => FALSE);

			try {
				$respuesta = $this->Vehiculo_model->borrar($campos);
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


	public function flota_get()
	{
		$data = $this->get();
		$carro = $this->Vehiculo_model->getFlota($data);

		if (isset($carro)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'autos' => $carro

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function show_get()
	{
		$data                = $this->get();
		$carro               = $this->Vehiculo_model->get_vehiculoForApp($data);
		$opcionesAdicionales = $this->Vehiculo_model->get_opciones();
		$modelo 					= $this->Vehiculo_model->get_modelo();
		if (isset($carro)) {
			$respuesta = array(
				'err'                 => FALSE,
				'mensaje'             => 'Registro Cargado correctamente',
				'autos'               => $carro,
				'opcionesAdicionales' => $opcionesAdicionales,
				'modelo'              => $modelo

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err'     => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'autos'   => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function historial_get()
	{
		$data                = $this->get();
		$historial               = $this->Vehiculo_model->obtenerHistorial($data);

		if (isset($historial)) {
			$respuesta = array(
				'err'             => FALSE,
				'mensaje'         => 'Registro Cargado correctamente',
				'historialDetalles'  => $historial,
			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err'            => TRUE,
				'mensaje'        => 'Error al cargar los datos.',
				'historialDetalles' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}