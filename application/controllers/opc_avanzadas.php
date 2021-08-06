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
class opc_avanzadas extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('opcAvanzadas_model');
	}
	public function opciones_get()
	{

		$informacion = $this->opcAvanzadas_model->get_opciones();

		if (isset($informacion)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'informacion' => $informacion

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'informacion' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}


	//INSERTAR
	public function opciones_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('opciones_put')) {

			$informacion = $this->opcAvanzadas_model->set_datos($data);

			$respuesta = $informacion->insert();

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
	public function actualizarOpciones_put()
	{

		$data = $this->put();
		if (!isset($data["idopc_avanzadas"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Opciones');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->opcAvanzadas_model->editar($data);
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
	public function eliminarOpciones_delete()
	{
		$data = $this->delete();
		if (!isset($data["idopc_avanzadas"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Opciones Avanzadas');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idopc_avanzadas' => $data["idopc_avanzadas"], 'activo' => FALSE);
			try {
				$respuesta = $this->opcAvanzadas_model->borrar($campos);
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