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
class alianzas extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Alianzas_model');
	}
	public function alianzas_get()
	{

		$alianza = $this->Alianzas_model->get_alianzas();

		if (isset($alianza)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'alianzas' => $alianza

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'alianzas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	//INSERTAR
	public function alianzas_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('alianzas_put')) {

			$alianza = $this->Alianzas_model->set_datos($data);

			$respuesta = $alianza->insert();

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
	public function actualizarAlianza_put()
	{

		$data = $this->put();
		if (!isset($data["idalianza"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Alianza');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->Alianzas_model->editar($data);
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
	public function eliminarAlianza_delete()
	{
		$data = $this->delete();
		if (!isset($data["idalianza"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Alianza');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('idalianza' => $data["idalianza"], 'activo' => FALSE);
			try {
				$respuesta = $this->Alianzas_model->borrar($campos);
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