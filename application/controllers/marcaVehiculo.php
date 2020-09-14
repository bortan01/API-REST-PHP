<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class marcaVehiculo extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Marca_model');
	}
	public function marca_get(){

	$marcas=$this->Marca_model->get_marca();

	if (isset($marcas)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'marcas'=>$marcas

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'marcas'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function marca_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('marca_put') ) {
		
		$marcas=$this->Marca_model->set_datos($data);

		$respuesta=$marcas->insert(); 

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