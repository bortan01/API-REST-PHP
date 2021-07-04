<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Comision extends REST_Controller
{
	public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Comision_model');
	
		}
	public function comision_post(){

		$respuesta=$this->Comision_model->insertarComisionAuto();

		if ($respuesta['err']) {
		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}

    }//fin

    public function comisionUpdate_post(){
    	$data=$this->post();

		$respuesta=$this->Comision_model->updateComision($data);

		if ($respuesta['err']) {
		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
		$this->response($respuesta); 	
		}

    }//fin



}