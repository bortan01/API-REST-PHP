<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class Asesoria extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Rama_model');
		$this->load->model('Pregunta_model');
		//$this->load->helper('utilidades');

	}

//--------------tabla(preguntas)--para las preguntas

	//************rama get todas**/***************************
	public function preguntita_get(){

	$pregunta=$this->Pregunta_model->get_pregunta();

	if (isset($pregunta)) {
		//quitar campos que no quiero
		//unset($cita->motivo);
		//unset($cliente->telefono2);
		$respuesta=array('err'=>FALSE,
						 'mensaje'=>'Registros cargados correctamente',
						  'preguntas'=>$pregunta);

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

	//***********************fin de rama get //**************

	//********************INSERTAR**************************
	public function cerrada_post(){
		$data=$this->post();
		$this->load->library('form_validation');
	    if ( $this->form_validation->run('pregunta_put') ) {
		$pregunta=$data['pregunta'];
		$id_rama=$data['id_rama'];
		$opcion=$data['opcion'];
		$opcion_respuesta=$data['opcion_respuesta'];
		$cuantos=count($data['opcion_respuesta']);

		$respuesta=$this->Pregunta_model->insertarCerrada($pregunta,$id_rama,$opcion,$opcion_respuesta,$cuantos); 

		if ($respuesta['err']) {

			/*$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion'
			);*/

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
	public function preguntita_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);
		if ( $this->form_validation->run('pregunta_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$pregunta=$this->Pregunta_model->set_datos($data);
		$respuesta=$pregunta->insert(); 

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
	//***********************

	//*******para actualizar
	public function updatePregunta_post(){

		$data=$this->post();

		$verificar=$this->Pregunta_model->verificar_campos($data);

       $respuesta=$this->Pregunta_model->modificar_pregunta($verificar);

	    if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	 }

	}
	//**********fin de actualizar

	//********para eliminar
	 
	 public function deletePregunta_post(){

	 $data=$this->post();
	 $verificar=$this->Pregunta_model->verificar_campos($data);
     $respuesta=$this->Pregunta_model->eliminar($verificar);

      if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	 }

	 }//fin metodo


//----------------------fin de las preguntas



	///------------tabla(rama)------------para las ramas de las preguntas de la asesoria/*******

	 public function deleteRama_post(){

	 $data=$this->post();
	 //$id_rama=$data["id_rama"];
	  $verificar=$this->Rama_model->verificar_campos($data);

	 $respuesta=$this->Rama_model->eliminar($verificar);

	 	  if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	    }
	 }//eliminar

	 //*******para actualizar
	public function updateRama_post(){

		$data=$this->post();

		$verificar=$this->Rama_model->verificar_campos($data);

       $respuesta=$this->Rama_model->modificar_rama($verificar);

	   if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	 }

	}
	//**********fin de actualizar
	//************rama get**/***************************
	public function ramita_get(){
		$rama=$this->Rama_model->get_rama();

	if ($rama['err']) {
		
		$this->response($rama,REST_Controller::HTTP_OK);
	}else{
		
		$this->response($rama,REST_Controller::HTTP_NOT_FOUND);

	}
   }

	//***********************fin de rama get //**************

	//********************INSERTAR**************************
	public function ramitas_post(){

		$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('rama_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$rama=$this->Rama_model->set_datos($data);

		$respuesta=$rama->insert(); 

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
	//***********************
//----------------fin de los metodos de las ramas


}