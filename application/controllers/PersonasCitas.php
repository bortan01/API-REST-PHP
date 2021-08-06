<?php
defined('BASEPATH') or exit('No direct script access allowed');
header('Access-Control-Allow-Origin: https://admin.tesistours.com/');
require APPPATH . '/libraries/REST_Controller.php';
class PersonasCitas extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('PersonasCitas_model');
	
		}

public function personas_get(){

	$id_cita =$this->uri->segment(3);

	$personas=$this->PersonasCitas_model->get_personas($id_cita);

	if (isset($personas)) {
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'personas'=>$personas

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'citas'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}
}