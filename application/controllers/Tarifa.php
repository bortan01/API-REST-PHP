<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Tarifa extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Tarifa_model');

	}
public function tarifas_get(){

	$tarifa=$this->Tarifa_model->get_tarifa();

	if (isset($tarifa)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'tarifas'=>$tarifa

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.'
		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}//fin metodo

public function tarifa_post(){

	    $data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ( $this->form_validation->run('tarifa_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$tarifa=$this->Tarifa_model->set_datos($data);
        $respuesta=$this->Tarifa_model->insert($tarifa); 

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
}//fin metodo
}