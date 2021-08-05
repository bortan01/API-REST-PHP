<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com');
require APPPATH . '/libraries/REST_Controller.php';
class Detalle_envio extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('DetalleEnvio_model');
	}

	public function deleteEnvios_post()
	{

		$data = $this->post();
		$verificar = $this->DetalleEnvio_model->set_datos($data);
		$respuesta = $this->DetalleEnvio_model->eliminar($verificar);

		$this->response($respuesta);
	}

	public function updateEnvios_post()
	{

		$data = $this->post();

		$verificar = $this->DetalleEnvio_model->set_datos($data);
		$respuesta = $this->DetalleEnvio_model->modificar_detalle($verificar);

		$this->response($respuesta);
	} //fin de metodo
	//************rama get todas**/***************************
	public function detalleEnvio_get()
	{

		$data = $this->get();
		$detalle = $this->DetalleEnvio_model->get_detallesEnvio($data);

		if (isset($detalle)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'detalles' => $detalle

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'detalles' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function entregar_post()
	{

		$data = $this->post();

		$respuesta = $this->DetalleEnvio_model->entregarDetalle($data);

		if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}
	}

	public function detalleEnvios_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('detalleEnvio_put')) {
			//todo bien
			//$this->response('Todo bien');
			$detalle = $this->DetalleEnvio_model->set_datos($data);
			$respuesta = $this->DetalleEnvio_model->insertarDetalle($detalle);

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
