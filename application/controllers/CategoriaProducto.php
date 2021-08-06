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
class CategoriaProducto extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('CategoriaPro_model');
	}
	public function deleteCategoria_post()
	{

		$data = $this->post();
		$verificar = $this->CategoriaPro_model->set_datos($data);
		$respuesta = $this->CategoriaPro_model->eliminar($verificar);

		$this->response($respuesta);
	}


	public function updateCategoria_post()
	{

		$data = $this->post();

		$verificar = $this->CategoriaPro_model->set_datos($data);
		$respuesta = $this->CategoriaPro_model->modificar_categoria($verificar);

		$this->response($respuesta);
	} //fin de metodo

	public function categoria_get()
	{

		$cate = $this->CategoriaPro_model->get_categoria();

		if (isset($cate)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'Categorias' => $cate

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'Categorias' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function categoria_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('cate_put')) {
			//todo bien
			//$this->response('Todo bien');
			$cate = $this->CategoriaPro_model->set_datos($data);
			$respuesta = $this->CategoriaPro_model->insert($cate);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				$this->response($respuesta);
			}
		} else {
			//algo mal

			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Hay errores en el envio de la informacion',
				'errores' => $this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		}
	}
}