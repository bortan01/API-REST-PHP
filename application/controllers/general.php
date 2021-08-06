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
class general extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('General_model');
	}

	public function general_get()
	{

		$data = $this->get();
		$generales = $this->General_model->get_general($data);

		if (isset($generales)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'datos_generales' => $generales

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'datos_generales' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}


	//MODIFICAR
	public function actualizarGeneral_put()
	{

		$data = $this->put();
		if (!isset($data["idgeneral"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->General_model->editar($data);
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