<?php
defined('BASEPATH') or exit('No direct script access allowed');
require APPPATH . '/libraries/REST_Controller.php';
class FormularioMigratorio extends REST_Controller
{

public function __construct(){
		//constructor del padre
		parent::__construct();
		$this->load->database();
		$this->load->model('Cita_model');
		$this->load->model('Pregunta_model');
		$this->load->model('FormularioMigratorio_model');

	}
public function deleteFormulario_post(){

	 $data=$this->post();
	 $verificar=$this->FormularioMigratorio_model->set_datos($data);
     $respuesta=$this->FormularioMigratorio_model->eliminar($verificar);

      if ($respuesta['err']) {

			$this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST); 

		}else{
			 $this->response($respuesta);

	    }
}//fin metodo

public function updateFormulario_post(){
	$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ( $this->form_validation->run('formulario_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$id_cita=$data['id_cita'];
		$id_pregunta=$data['id_pregunta'];
		$respuestas=$data['respuesta'];
		$mas_respuesta=$data['respuesta_mas'];
		$mas_id=$data['id_pregunta_mas'];
		//para los input que solo es una pregunta
		$id_pregunta1=$data['id_pregunta1'];
		$respuestas1=$data['respuesta1'];

        $respuesta=$this->FormularioMigratorio_model->insertarActualizaciones($id_cita,$id_pregunta,$respuestas,$mas_respuesta,$mas_id,$id_pregunta1,$respuestas1); 

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

}//fin de metodo

public function formulariosLlenos_get(){

	$id =$this->uri->segment(3);
	$opciones=$this->Pregunta_model->get_opciones();
	$formulario=$this->FormularioMigratorio_model->get_formularios_llenos($id);

	if (isset($formulario)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'formulario'=>$formulario,
			'opciones'=>$opciones

		);
		$this->response($respuesta);
	}else{
		$respuesta=array(
			'err'=>TRUE,
			'mensaje'=>'Error al cargar los datos.'
		);
		$this->response($respuesta,REST_Controller::HTTP_NOT_FOUND);

	}

}

public function formularios_get(){

	$formulario=$this->FormularioMigratorio_model->get_pregunta();

	if (isset($formulario)) {
		//quitar campos que no quiero
		//unset($cliente->telefono1);
		//sunset($cliente->telefono2);
		$respuesta=array(
			'err'=>FALSE,
			'mensaje'=>'Registro Cargado correctamente',
			'formulario'=>$formulario

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

public function formulario_post(){

	    $data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data($data);

		if ( $this->form_validation->run('formulario_put') ) {
			//todo bien
			//$this->response('Todo bien');
		$id_cita=$data['id_cita'];

		//para los combobox
		if (isset($data['id_pregunta'])) {
			# code...
			$id_pregunta=$data['id_pregunta'];
		    $respuestas=$data['respuesta'];
		}else{
			$id_pregunta=NULL;
		    $respuestas=NULL;	
		}
		
		//para los input que tienen mas de una respuesta
		if (isset($data['respuesta_mas'])) {
			# code...
		$mas_respuesta=$data['respuesta_mas'];
		$mas_id=$data['id_pregunta_mas'];
		}else{
			$mas_respuesta=NULL;
			$mas_id=NULL;
		}
		

		//para los input que solo es una pregunta
		if (isset($data['id_pregunta1'])) {
		$id_pregunta1=$data['id_pregunta1'];
		$respuestas1=$data['respuesta1'];
		}else{
		$id_pregunta1=NULL;
		$respuestas1=NULL;
		}
		
		$this->Cita_model->formularioModificar($id_cita);
        $respuesta=$this->FormularioMigratorio_model->insertarFormularios($id_cita,$id_pregunta,$respuestas,$mas_respuesta,$mas_id,$id_pregunta1,$respuestas1); 

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