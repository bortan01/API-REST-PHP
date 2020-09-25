<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class aerolinea extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Aerolinea_model');
	}
	public function aerolinea_get(){

	$aerolineas=$this->Aerolinea_model->get_aerolinea();

	if (isset($aerolineas)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'aerolineas'=>$aerolineas

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'aerolineas'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function aerolinea_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('aerolinea_put') ) {
		
		$aerolineas=$this->Aerolinea_model->set_datos($data);

		$respuesta=$aerolineas->insert(); 

		if ($respuesta['err']) {

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}
		}else{

			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion',
				'errores'=>$this->form_validation->get_errores_arreglo()
			);
			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 
		}
	}
}