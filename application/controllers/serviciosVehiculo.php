<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
$allowedOrigins = [
	"https://admin.tesistours.com",
	"https://tesistours.com"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
class serviciosVehiculo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Servicios_model');
	}
	public function servicios_get()
	{

		$data = $this->get();
		$respuesta = $this->Servicios_model->get_servicios($data);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}

	public function mostrarServicios_get()
	{

		$data = $this->get();
		$respuesta = $this->Servicios_model->get_mostrarServicios($data);
		$this->response($respuesta, REST_Controller::HTTP_OK);
	}


	//INSERTAR
	public function servicios_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('servicios_put')) {

			$servicio = $this->Servicios_model->set_datos($data);

			$respuesta = $servicio->insert();

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
	public function actualizarServicios_put()
	{

		$data = $this->put();
		if (!isset($data["idservicios_opc"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador del Servicio');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->Servicios_model->editar($data);
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
	public function eliminarServicios_delete()
	{
		$data = $this->delete();
		if (!isset($data["idservicios_opc"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Servicios Opcionales');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idservicios_opc' => $data["idservicios_opc"], 'activo' => FALSE);
			try {
				$respuesta = $this->Servicios_model->borrar($campos);
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