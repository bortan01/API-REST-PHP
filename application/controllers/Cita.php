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
	
		}

 public function deleteCita_post(){

	 $data=$this->post();
	 $verificar=$this->Cita_model->set_datos($data);
	 $respuesta=$this->Cita_model->eliminar($verificar);
	 	  $this->response($respuesta);
	 }

public function updateCita_post(){

		$data=$this->post();
		//recogere los datos para pode concatenar
		$id_cita=$data["id_cita"];
		$fecha=$data["fecha"];
		$compania=$data["asistencia"];

		$input=$data["input"];
		$asistiran=$data["asistiran"];
		$hora=$data["start"];

		//$verificar=$this->Cita_model->set_datos($data);
        $respuesta=$this->Cita_model->modificar_cita($id_cita,$fecha,$compania,$input,$asistiran,$hora);

	    $this->response($respuesta);

	}//fin de metodo

public function cita_get(){

	$cita=$this->Cita_model->get_citas();

	if (isset($cita)) {
		//quitar campos que no quiero
		//unset($cita->motivo);
		//unset($cliente->telefono2);
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
		$this->form_validation->set_data($data);

		if ( $this->form_validation->run('citas_put') ) {
			//todo bien
			//$this->response('Todo bien');
		//$cita=$this->Cita_model->set_datos($data);
		$id_cliente=$data["id_cliente"];
		$asistencia=$data["asistencia"];
		if (isset($data['asistiran'])) {
			# code...
			$personas=$data['asistiran'];
		}else{
			$personas=NULL;
		}
		
		$motivo=$data["title"].': '.$data["usuario"];
		$color="#007bff";
		$textColor="#FFFFFF";
		$start=$data["fecha"].' '.$data["start"];
		$fecha=$data["fecha"];
		$hora=$data["start"];

		$respuesta=$this->Cita_model->insertCita($id_cliente,$asistencia,$personas,$motivo,$color,$textColor,$start,$fecha,$hora); 

		if ($respuesta['err']) {

			$respuesta=array(
				'err'=>TRUE,
				'mensaje'=>'Hay errores en el envio de la informacion'
			);

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

/*
prueba

$data=$this->post();
		$this->load->library('form_validation');
		$this->form_validation->set_data ($data);

		if ( $this->form_validation->run('citas_put') ) {
			//todo bien
			//recogo los datos que vienen del formulario

			$title=$data["title"];
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


*/