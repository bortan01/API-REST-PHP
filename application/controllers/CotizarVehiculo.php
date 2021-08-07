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
