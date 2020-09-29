<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Cita extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Cita_model');
		//$this->load->model('Pregunta_model');
		//$this->load->helper('utilidades');

	}

public function cita_get(){

	$cita=$this->Cita_model->get_citas();

	if (isset($cita)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		//$respuesta=array($cita);
		$this->response($cita);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'citas'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
}


	public function citas_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('citas_put') ) {
			//todo bien
			//recogo los datos que vienen del formulario

			$title=$data["motivo"];
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

}