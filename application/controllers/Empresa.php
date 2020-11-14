<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Empresa extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Empresa_model');
	}

public function municipios_get(){
	$data = $this->get();
	$municipio = $this->Empresa_model->get_municipio($data);

	if (isset($municipio)) {
		
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'municipios'=>$municipio

		);
		$this->response($respuesta,REST_Controller::HTTP_OK);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'municipios'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}

public function deptos_get(){

	$deptos=$this->Empresa_model->get_deptos();

	if (isset($deptos)) {
		//quitar campos que no quiero
		//unset($cita->motivo);
		//unset($cliente->telefono2);
		$respuesta=array('err'=>FALSE,
						 'mensaje'=>'Registros cargados correctamente',
						  'deptos'=>$deptos);

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
public function empresas_get(){
	$data=$this->get();
	$empresa=$this->Empresa_model->get_empresas($data);

	if (isset($empresa)) {
		//quitar campos que no quiero
		//unset($cita->motivo);
		//unset($cliente->telefono2);
		$respuesta=array('err'=>FALSE,
						 'mensaje'=>'Registros cargados correctamente',
						  'empresa'=>$empresa);

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



public function empresa_post(){

	$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);
		if ( $this->form_validation->run('empresa_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$empresa=$this->Empresa_model->set_datos($data);
		$respuesta=$empresa->insertarEmpresa(); 

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
