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
class marcaVehiculo extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Marca_model');
	}
	public function marca_get()
	{

		$marcas = $this->Marca_model->get_marca();

		if (isset($marcas)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'marcas' => $marcas

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'marcas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}


	//INSERTAR
	public function marca_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('marca_put')) {

			$marcas = $this->Marca_model->set_datos($data);

			$respuesta = $marcas->insert();

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
	public function actualizarMarca_put()
	{

		$data = $this->put();
		if (!isset($data["id_marca"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de la Marca');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			try {
				$respuesta = $this->Marca_model->editar($data);
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
	public function eliminarMarca_delete()
	{
		$data = $this->delete();
		if (!isset($data["id_marca"])) {
			$respuesta = array('err' => TRUE, 'mensaje' => 'No se encontro ningun identificador de Marca');
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$campos = array('id_marca' => $data["id_marca"], 'activo' => FALSE);
			try {
				$respuesta = $this->Marca_model->borrar($campos);
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