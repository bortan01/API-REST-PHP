<?php
defined('BASEPATH') or exit('No direct script access allowed');
$allowedOrigins = [
	"https://admin.martineztraveltours.com",
	"https://martineztraveltours.com/"
];
if (isset($_SERVER["HTTP_ORIGIN"]) && in_array($_SERVER["HTTP_ORIGIN"], $allowedOrigins)) {
	header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
}
require APPPATH . '/libraries/REST_Controller.php';
class Caja extends REST_Controller
{
	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Cajas_model');
	}

	public function deleteCaja_post()
	{

		$data = $this->post();
		$verificar = $this->Cajas_model->set_datos($data);
		$respuesta = $this->Cajas_model->eliminar($verificar);

		if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}
	} //fin metodo

	public function updateCaja_post()
	{

		$data = $this->post();

		$verificar = $this->Cajas_model->set_datos($data);
		$respuesta = $this->Cajas_model->modificar_caja($verificar);

		if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}
	} //fin de metodo

	public function cajas_get()
	{

		$caja = $this->Cajas_model->get_caja();

		if (isset($caja)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'cajas' => $caja

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.'
			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	} //fin metodo

	public function caja_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('caja_put')) {
			//todo bien
			//$this->response('Todo bien');
			$caja = $this->Cajas_model->set_datos($data);
			$respuesta = $this->Cajas_model->insert($caja);

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
	} //fin metodo

}