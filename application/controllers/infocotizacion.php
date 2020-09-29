<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class infocotizacion extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('infoAdicional_model');
	}
	public function informacion_get(){

	$informacion=$this->infoAdicional_model->get_informacion();

	if (isset($informacion)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'informacion'=>$informacion

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'informacion'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

	
	//INSERTAR
	public function informacion_put(){

		$data=$this->put();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('informacion_put') ) {
		
		$informacion=$this->infoAdicional_model->set_datos($data);

		$respuesta=$informacion->insert(); 

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