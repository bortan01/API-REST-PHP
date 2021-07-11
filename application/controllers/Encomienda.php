<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Encomienda extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Encomienda_model');
		$this->load->model('DetalleEncomienda_model');
		$this->load->model('DetalleDestino_model');		
		$this->load->model('DetalleEnvio_model');
	}

	public function deleteEncomienda_delete()
	{

		$data = $this->delete();
		$verificar = $this->Encomienda_model->set_datos($data);
		$respuesta = $this->Encomienda_model->eliminarEncomienda($verificar);

		$this->response($respuesta);
	} //fin metodo

	public function altaEnco_delete()
	{

		$data = $this->delete();
		$verificar = $this->Encomienda_model->set_datos($data);
		$respuesta = $this->Encomienda_model->altaEncomienda($verificar);

		$this->response($respuesta);
	} //fin metodo


	public function updateEncomienda_post()
	{

		$data = $this->post();
		//print_r($data);
		//die();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('encomienda_put')) {
			//todo bien
			//$this->response('Todo bien')
			$encomiendas = $this->Encomienda_model->set_datos($data);
			$respuesta = $this->Encomienda_model->modificar_encomienda($encomiendas);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				if (!empty($data["detalle_encomienda"])) {
					$detalle = json_decode($data["detalle_encomienda"], true);
					$detalleDes = json_decode($data["detalle_destino"], true);

					$this->DetalleEncomienda_model->modificarDetalle($detalle, $respuesta['id']);
					$this->DetalleDestino_model->modificarDetalleDestino($detalleDes, $respuesta['id']);
				}
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
	} //fin de metodo

	public function encomiendaActualizar_get()
	{

		$data = $this->get();
		$enco = $this->Encomienda_model->get_encomiendaEnvio($data);

		if (isset($enco)) {
			//quitar campos que no quiero
			//unset($cliente->telefono1);
			//sunset($cliente->telefono2);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'Encomiendas' => $enco

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'Encomiendas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function encomiendaModificar_get()
	{

		$data = $this->get();
		$enco               = $this->Encomienda_model->get_encomiendaModificar($data);
		$destino            = $this->Encomienda_model->get_encomiendaDestino($data);
		$detalle_encomienda = $this->DetalleEncomienda_model->get_detalle($data);
		$historial          = $this->DetalleEnvio_model->get_detallesEnvio($data);
		if (isset($enco)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'Encomiendas' => $enco,
				'Detalles_destino' => $destino,
				'detalle' => $detalle_encomienda,
				'historial' => $historial

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'Encomiendas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function encomienda_get()
	{

		$data = $this->get();
		$enco = $this->Encomienda_model->get_encomienda($data);

		if (isset($enco)) {
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'Encomiendas' => $enco

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'Encomiendas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}

	public function encomiendas_post()
	{

		$data = $this->post();
		//print_r($data);
		//die();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('encomienda_put')) {
			//todo bien
			//$this->response('Todo bien')
			$encomiendas = $this->Encomienda_model->set_datos($data);
			$respuesta = $this->Encomienda_model->insertarEncomienda($encomiendas);

			if ($respuesta['err']) {

				$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
			} else {
				if (!empty($data["detalle_encomienda"])) {
					$detalle = json_decode($data["detalle_encomienda"], true);
					$detalleDes = json_decode($data["detalle_destino"], true);

					$this->DetalleEncomienda_model->guardarDetalle($detalle, $respuesta['encomienda_id']);
					$this->DetalleDestino_model->guardarDetalleDestino($detalleDes, $respuesta['encomienda_id']);
				}
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

	public function show_get()
	{
		$data = $this->get();
		$enco = $this->Encomienda_model->get_encomiendaForApp($data);

		if (isset($enco)) {


			foreach ($enco as  $value) {
				$informacionDestino =  $this->Encomienda_model->get_detalleDestinoForApp($value->id_encomienda);
				$value->destino = $informacionDestino;
			}

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'Encomiendas' => $enco,


			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'Encomiendas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}