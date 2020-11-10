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
