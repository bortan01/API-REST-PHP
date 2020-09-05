<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Asesoria extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Rama_model');
		$this->load->model('Pregunta_model');
		//$this->load->helper('utilidades');

	}

//--------------tabla(preguntas)--para las preguntas

	//********************INSERTAR**************************
	public function preguntita_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('pregunta_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$pregunta=$this->Pregunta_model->set_datos($data);

		$respuesta=$pregunta->insert(); 

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
	//***********************


//----------------------fin de las preguntas



	///------------tabla(rama)------------para las ramas de las preguntas de la asesoria/*******
	//************rama get**/***************************
	public function ramita_get(){

	$ramita_id=$this->uri->segment(3);

	//validar***********
	if (!isset($ramita_id)) {

		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Es necesario el ID de la rama.'

		);

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST );
		return;
	}

	$ramita=$this->Rama_model->get_rama($ramita_id);

	if (isset($ramita)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'ramas'=>$ramita

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'La rama con el id '.$ramita_id.' no existe.',
			'cliente'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	//***********************fin de rama get //**************

	//********************INSERTAR**************************
	public function ramita_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('rama_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$rama=$this->Rama_model->set_datos($data);

		$respuesta=$rama->insert(); 

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
	//***********************
//----------------fin de los metodos de las ramas


}