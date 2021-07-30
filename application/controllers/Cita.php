<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Cita extends REST_Controller
{

	public function __construct()
	{
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Cita_model');
		$this->load->model('Imagen_model');
	}

	public function ingresos_get(){
		$data = $this->get();

		$respuesta = $this->Cita_model->ingresos($data);

		if ($respuesta['err']) {
			# code...
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}

	}
	public function verificarExist_get()
	{

		$data = $this->get();


		$respuesta = $this->Cita_model->existSioNo($data);
		$this->response($respuesta);
	}
	public function verCita_get()
	{
		$data = $this->get();
		$respuesta = $this->Cita_model->verCita($data);
		$this->response($respuesta);
	}
	public function deleteCita_post()
	{
		$data = $this->post();
		$verificar = $this->Cita_model->set_datos($data);
		$respuesta = $this->Cita_model->eliminar($verificar);
		$this->response($respuesta);
	}
	public function moverDias_post()
	{
		$data = $this->post();
		//recogere los datos para pode concatenar
		$id_cita = $data["id_cita"];
		$fecha = $data["fecha"];
		$start = $data["fecha"] . ' ' . $data["start"];
		$hora = $data["start"];
		$ya = $this->Cita_model->mover($id_cita, $fecha, $start, $hora);

		if ($ya['err']) {
			# code...
			$this->response($ya, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($ya);
		}
	}
	public function updateCita_post()
	{

		$data = $this->post();
		//recogere los datos para pode concatenar
		$id_cita = $data["id_cita"];
		$compania = $data["asistencia"];
		$personas = $data["asistiran"];
		$pasaporte_personas = $data["pasaporte_personas"];
		$cuantos = $data["cuantos"];
		$hora = $data["start"];
		$partes = explode("-", $data["fecha"]);
		$fechaConvertida = $partes[2] . '-' . $partes[1] . '-' . $partes[0];
		$fecha = $fechaConvertida;

		$respuesta = $this->Cita_model->modificar_cita($id_cita, $compania, $personas, $pasaporte_personas, $cuantos, $hora, $fecha);

		if ($respuesta['err']) {
			# code...
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
		} else {
			$this->response($respuesta);
		}
	} //fin de metodo
	public function formularioMigratorioCitas_get()
	{

		$cita = $this->Cita_model->get_citasFormulario();

		if (isset($cita)) {
			//quitar campos que no quiero
			//unset($cita->motivo);
			//$respuesta=array($cita);
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'citas' => $cita

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function formularios_get()
	{

		$citas = $this->Cita_model->get_formularios();

		if (isset($citas)) {
			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'citas' => $citas

			);
			$this->response($respuesta);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	public function cita_get()
	{

		$cita = $this->Cita_model->get_citas();

		if (isset($cita)) {
			//quitar campos que no quiero
			//unset($cita->motivo);
			//unset($cliente->telefono2);
			//$respuesta=array($cita);
			$this->response($cita);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	//***********visualizar citas pagina web
	public function citaWeb_get()
	{
		$data = $this->get();
		$citaWeb = $this->Cita_model->get_citasWeb($data);

		if (isset($citaWeb)) {

			$this->response($citaWeb);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
	//**********************


	public function citas_post()
	{

		$data = $this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ($this->form_validation->run('citas_put')) {
			//todo bien
			//$this->response('Todo bien');
			//$cita=$this->Cita_model->set_datos($data);
			$id_cliente = $data["id_cliente"];
			
			$motivo = $data["title"] . ': ' . $data["usuario"];
			$color = "#007bff";
			$textColor = "#FFFFFF";
			$partes = explode("-", $data["fecha"]);
			$fechaConvertida = $partes[2] . '-' . $partes[1] . '-' . $partes[0];

			$start = $fechaConvertida . ' ' . $data["start"];
			$fecha = $fechaConvertida;
			$hora = $data["start"];

			$respuesta = $this->Cita_model->insertCita($id_cliente, $motivo, $color, $textColor, $start, $fecha, $hora);

			if ($respuesta['err']) {

				/*$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion'
			);*/

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

	public function pasaportes_get()
	{
		$data = $this->get();
		$cita = $this->Cita_model->getPasaportes($data);

		if (isset($cita)) {

			$respuesta = array(
				'err' => FALSE,
				'mensaje' => 'Registro Cargado correctamente',
				'citas' => $cita

			);
			$this->response($respuesta, REST_Controller::HTTP_OK);
		} else {
			$respuesta = array(
				'err' => TRUE,
				'mensaje' => 'Error al cargar los datos.',
				'citas' => null

			);
			$this->response($respuesta, REST_Controller::HTTP_NOT_FOUND);
		}
	}
}

/*
prueba

$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('citas_put') ) {
			//todo bien
			//recogo los datos que vienen del formulario

			$title=$data["title"];
			$descripcion=$data["descripcion"];
			$start=$data["start"];
			$fecha=$data["fecha"];

			$respuesta=$this->Cita_model->insertCita($title,$descripcion,$start,$fecha);
		       
		       if ($respuesta['err']) {

		         $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		         }else{
		           $this->response($respuesta); 	
		          }

		}else{
			//algo mal

			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion',
				'errores'=>$this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 
		}
	}


*/