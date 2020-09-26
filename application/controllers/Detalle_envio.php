<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Detalle_envio extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('DetalleEnvio_model');
	
	}

//--------------tabla(preguntas)--para las preguntas

	//************rama get todas**/***************************
	public function detalleEnvio_get(){

	$detalle=$this->DetalleEnvio_model->get_pregunta();

	if (isset($detalle)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'Detalle'=>$detalle

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'detalle'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

public function detalleEnvios_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('detalleEnvio_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$detalle=$this->DetalleEnvio_model->set_datos($data);

		$respuesta=$detalle->insert(); 

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

}