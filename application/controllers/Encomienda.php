<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Encomienda extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Encomienda_model');
		$this->load->model('DetalleEncomienda_model');

	}

	 public function deleteEncomienda_post(){

	 $data=$this->post();
	 $verificar=$this->Encomienda_model->set_datos($data);
     $respuesta=$this->Encomienda_model->eliminar($verificar);

	 	  $this->response($respuesta);
	 }//fin metodo


	public function updateEncomienda_post(){

		$data=$this->post();

		$verificar=$this->Encomienda_model->set_datos($data);
        $respuesta=$this->Encomienda_model->modificar_encomienda($verificar);

	    $this->response($respuesta);

	}//fin de metodo


	public function encomienda_get(){

	$enco=$this->Encomienda_model->get_encomienda();

	if (isset($enco)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'Encomiendas'=>$enco

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.',
			'Encomiendas'=>null

		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}
   }

   public function encomiendas_post(){

		$data=$this->post();
		//print_r($data);
		//die();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('encomienda_put') ) {
			//todo bien
			//$this->response('Todo bien')
		$encomiendas=$this->Encomienda_model->set_datos($data);
        $respuesta=$this->Encomienda_model->insertarEncomienda($encomiendas); 

		if ($respuesta['err']) {

		$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 	

		}else{
			if (!empty($data["detalle_encomienda"])) {
                    $detalle = json_decode($data["detalle_encomienda"], true);
                    $this->DetalleEncomienda_model->guardarDetalle($detalle, $respuesta['id']);
                }
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